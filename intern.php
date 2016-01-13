<?php

include("includes/db_connect.php");
#Define constants
session_start();
$anz=0;
$anzahl=0;
$woche=0;
$no_meal=0;
if(isset($_SESSION['bestellung']) and $_SESSION['bestellung']==1) {
    $bestellt=1;
    unset($_SESSION['bestellung']);
}

if(isset($_GET['l']) and $_GET['l']==1  ) {
    $session_b=$_SESSION['id'];
    exec("python scripts/logout.py $session_b");
    session_destroy();
    $login=0;	
	if(isset($_COOKIE["msn_ldkf_login"])) {
		setcookie("msn_ldkf_login","",time() - 3600);
	
	}
		if(isset($_COOKIE['msn_loggedin'])) {
			$auth=$_COOKIE['msn_loggedin'];
			$eintrag = "Update sessions SET stayloggedin = '0' where authid LIKE '$auth'"; 
			$eintragen = mysql_query($eintrag);
		}
    header('Location: ./');
} 

if(isset($_POST['wkorb']) and $_POST['wkorb']==1) {
    $d=1;
    $j=0;
    $ssid=$_SESSION['id'];
    while($d<21) {
    	if(isset($_POST[$d]) and isset($_POST[$d.'_anz']) ) {
        $var=$_POST[$d];
        $anz=$_POST[$d.'_anz'];
        $v_explode = explode('_',$var);

        $id_e=$v_explode[0];
        $dat_e=$v_explode[1]; 
        $anzahl=$v_explode[2]; 
        if($anzahl=="") {$anzahl=0;}  
        if($anz=="") {
            $anz=0;
        }  
        //auslesen
        if($anz!=$anzahl) {
            exec("python scripts/selectmenu.py $ssid $dat_e $id_e $anz", $out);
            $outa[$j]=json_decode($out[0]);
            $j++;
        }
        $anzahl=0;
        $anz=0;
     }
        $d++;
    }
    if(isset($outa[0])) {
        exec("python scripts/checkout.py $ssid", $osut);
        $_SESSION['bestellung'] = 1;
    }
    header('Location: intern.php');
} 

$login=0;
if(isset($_SESSION['kdnrv']) and isset($_SESSION['geheim']) and $_SESSION['geheim']!="" and $_SESSION['kdnrv']!="" )
{
    if(isset($_GET['week'])){
        $woche=$_GET['week'];
    }
    $usr=$_SESSION['kdnrv'];
    $pw=$_SESSION['geheim'];
    exec("python scripts/login.py $usr $pw", $stdout);
    $session_a=json_decode($stdout[0]);
    $session=$session_a[0];
    $_SESSION['id']=$session;
        if($stdout[0]==0 and !isset( $_SESSION['id']))  {header('Location: ./'); $_SESSION['fail']=1;}
    $login=1;

	if($session!="") 
   {
		exec("python scripts/getmenu.py $session $woche", $getmen);
		if($woche==3 and $getmen[0]==1){
			header('Location: intern.php?week=0');
			}
		
		if($getmen[0]!=1 ){
			$getm=json_decode($getmen[0]); 
			}
		unset($_SESSION['fail']);
   }
}
else{
	header('Location: ./');
	$_SESSION['fail']=1;
	}  
	
	

 ?>
<!DOCTYPE html>
<html lang="de">
  <head>
  		<link rel="icon" href="favicon.png">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta http-equiv="content-type" content="text/html; charset=UTF-8" >    
    <title>Bestellseite</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/main.css" rel="stylesheet">
    <link href="css/lightbox.css" rel="stylesheet">
   
  </head>
<!--  Das hier wird nicht angezeigt-->
<div class="modal fade odrsuccess" aria-hidden="true" aria-labelledby="mySmallModalLabel" role="dialog" tabindex="-1" style="display: none;">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-body"> Bestellung &uuml;bermittelt. </div>
        </div>
    </div>
</div>
<!--hier geht's wieder los-->
  <body>
    
        <nav class="navbar navbar-inverse navbar-fixed-top">
            <div class="container-fluid">
                <!-- Brand and toggle get grouped for better mobile display -->
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    
                    <a class="navbar-brand" href="https://ldkf.de">LDKF.de</a>
                </div>
                <!-- Collect the nav links, forms, and other content for toggling 
						collapse navbar-collapse                
                -->
       		<div id="navbar" class="navbar-collapse collapse">
                    <ul class="nav navbar-nav" >
                        <li><a href="./">Startseite</a></li>
                        <li><a href="upload.php">Uploader</a></li>
                        <li><a href="group.php" title="Erm&ouml;glicht Gruppierung von MSN-Accounts">Multi-Accounts</a></li> 
                        <li class="active"><a href="intern.php">Bestellseite</a></li>
                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                        
                        <form class="navbar-form form-inline" id="order" action="/intern.php" method="post">
                        <button type="submit" id="nonejs_button" class="btn btn-primary">Bestellung &uuml;bermitteln</button><input type="hidden" value="1" name="wkorb">
                        <a href="/intern.php?l=1" class="btn btn-primary " style="margin-left:20px;">Ausloggen</a>
                    </ul>
                </div><!-- /.navbar-collapse -->
            </div><!-- /.container-fluid -->
        </nav>
    <div class="container" id="main">
	    <div class="jumbotron heading" style="border-radius:0">
	      <?php
if (isset($bestellt) and $bestellt == 1 ) {
    echo '<h1>Willkommen auf der Bestellseite</h1><p class="lead">Deine Bestellung war erfolgreich.</p>';
}     
else {   
    echo '<h1>Willkommen auf der Bestellseite</h1>
    <p class="lead">Hier kannst du dein Essen f&uuml;r die jeweilige Woche bestellen.<br>
    Die Bestellung wird direkt an die Website des Men&uuml;service-Neuburg &uuml;bermittelt.<br>
    Du kannst auch weiterhin auf der <a href="http://menue-service-neuburg.de/ " target="_blank">Seite des Essenanbieters</a> bestellen.</p><a id="row"></a>' ; 
}

$image_o="grau.svg";
$image="gelb.svg";
$c=0;
$i=0;
$daytest="";
while($daytest=="" and $i<=4) {
$current_date = date("Y-m-d", time()- ((date("N")-1-$i-(7*$woche))*60*60*24) );
$test = mysql_query("SELECT id FROM datum WHERE datum LIKE '$current_date' and bez LIKE 'a' ");
$test_row = mysql_fetch_row($test);
$daytest=$test_row[0];
$i++;
}

	if(!isset($getm) ) {
		if(isset($test_row[0])) {
		$i=0;
		$numbers=["a", "b", "c", "d"];
		$ij=0;
		while($i<5) {
			$current_date = date("Y-m-d", time()- ((date("N")-1-$i-(7*$woche))*60*60*24) );
			$j=0;
			while($j<4) {
				$meal = mysql_query("SELECT id, tag, preis FROM datum WHERE datum LIKE '$current_date' and bez LIKE '$numbers[$j]' ");
				while($meal_row = mysql_fetch_row($meal)){			
					$id_get=$meal_row[0];
					if($meal_row[1]=="Montag" or $meal_row[1]=="Dienstag" or $meal_row[1]=="Mittwoch" or $meal_row[1]=="Donnerstag" or $meal_row[1]=="Freitag"   ) {
						$tag_bestell=$meal_row[1];
					}
					else {
					if($meal_row[1]=="Mo") {$tag_bestell="Montag";}
					if($meal_row[1]=="Di") {$tag_bestell="Dienstag";}
					if($meal_row[1]=="Mi") {$tag_bestell="Mittwoch";}
					if($meal_row[1]=="Do") {$tag_bestell="Donnerstag";}
					if($meal_row[1]=="Fr") {$tag_bestell="Freitag";}
					}
					$preis=$meal_row[2];
					$no_meal=1;
				}
				$meal_name = mysql_query("SELECT name, mn FROM essen WHERE id = '$id_get'");
				while($meal_name_row = mysql_fetch_row($meal_name)){
					$name= $meal_name_row[0];
						if(isset($meal_name_row[1])){
							$id=$meal_name_row[1];
						}
						else {
							$id=0;
						}
				}
				$anz=0;
				$nr=$j;
    			$getm[$ij] = array($current_date, $tag_bestell ,$nr ,$name ,$preis ,$id, $anz );
				$j++;
				$ij++;
			}
			$i++;		
		}
		echo "(Eigene Datenbankausgabe)";
	}
		}
	
	
	$wochep=$woche+1;
	$wochem=$woche-1;
	            if (isset($getm)){
	echo '</div>       
        <div class="container all">
            <div class="weeknav">
                <a class="btn btn-primary btn-lg active" href="intern.php?week='.$wochem.'#row"><<</a>
                <a class="btn btn-default btn-lg active" href="intern.php?week=0#row">Aktuelle Woche / N&auml;chste Schulwoche</a>
                <a class="btn btn-primary btn-lg active" href="intern.php?week='.$wochep.'#row"> >> </a>
            </div>
            <div class="meals">';
            $i=0;
foreach($getm as $check){
	if($check[2]==0) {
		$i++;
		$sum[$i]=0;
	}	
	if($check[6]==1) {
		$sum[$i]=1;
	}
}
$jkl=0;
foreach ($getm as $info) {
	if($info[2]==0) {
		$jkl++;	
	}
    $meal= array("date"=>$info[0], "weekday"=>$info[1], "counter"=>$info[2], "meal"=>  $info[3], "betrag"=>$info[4], "mealnumber"=>$info[5], "anz"=>$info[6]);
	 if(strchr($info[0], '/')) {
	 	$datum_bestell="";
	 }
	 else {
    	$datum_bestell=$info[0];
    }
    $tag_bestell=$info[1];
    $nr=$info[2];
    $name=$info[3];
    $preis=$info[4];
    $id=$info[5]; 
    $anzahl=$info[6];
    $c++;
    $bewertga=0;		 									
    $bewertgb=0;
    $bewertgc=0;		 	 			
    $bewertgd=0;
    $bew=[];
    $pic=[];
    if($nr==0) {
        $value_date=date("d.m.Y", strtotime($datum_bestell));
        echo '<div class="row"><div class="date">'.$tag_bestell.", der ".$value_date.'</div></div><div class="row day">';
    }    
    $letters=["a", "b", "c", "d"];
    $pfad=[];
    foreach($letters as $letter) {
        //Hier wird das Bild gesucht
        $query="SELECT id FROM datum WHERE datum LIKE '$datum_bestell' and bez LIKE '".$letter."' ";
        $result=mysql_query($query);
        $pic[]="";
        while($row=mysql_fetch_array($result)) {
            $ordner="pictures/".$row[0];
            if ($row[0]!="" && is_dir($ordner)){
                $bilderlist=scandir($ordner);
                foreach($bilderlist as $bild) {
                    if ($bild != "." && $bild != ".."  && $bild != "_notes" && pathinfo($ordner."/neu/".$bild)['basename'] != "Thumbs.db" && $bild!="neu") { 
                        array_pop($pic);
                        $pic[]=$ordner."/neu/".$bild;
                        break;
                    }
                }
            }
        }
    }
    $query="SELECT id FROM datum WHERE datum LIKE '$datum_bestell' and bez LIKE '".$letters[$nr]."' ";
    $result=mysql_query($query);
    $rating_id=mysql_fetch_array($result)[0];
    $menues=["Men&uuml; A", "Men&uuml; B", "Men&uuml; C", "Men&uuml; D"];
    $menu=$menues[$nr];
    
    echo '<div class="col-md-3 col-xs-12 col-sm-6" style="width:25%; padding:2px;"><div class="meal';
    if($anzahl>0) {
        echo ' tdborder';
    } else {
		echo  ' nonborder';    
    }
    echo '"><span style="font-weight:bold">'.$menu."</span>"; 
    if($preis!=0) {
        echo " (";
        echo strtr($preis, ".", ",");
        echo"&euro;) ";

    }
    echo ' Anzahl: <input class="form-control anz-form input-sm number" autocomplete="off"  type="text" maxlength="1" pattern="[0-1]{1}" name="'.$c.'_anz'.'" placeholder="0" value="';
    if($anzahl!=0) { 
        echo $anzahl;
    } 
    echo '"';  	
    if($preis==0 or $no_meal==1 or ($sum[$jkl]==1 and $anzahl!=1)) {
        echo " disabled"; 
    } 
    echo '><input type="hidden" name="'. $c.'" value="'. $id. "_". $datum_bestell. "_". $anzahl. '"><br>'.  $name."<br>"; 
    echo 'Bewertung: ';
    //hier werden die Bewertungen ausgegeben
    $result=0;
    $result_bew = mysql_query("SELECT bew FROM bewertung WHERE id LIKE '$rating_id'");
$row_bew = mysql_fetch_row($result_bew);
if(isset($row_bew[0])) 
	{
	$result=round($row_bew[0]);						
	}    
    
    $counter=0; 
    while($counter<$result) {
       echo '<img src="images/gelb.svg" class="star" alt="" />';
       $counter++;
    }
    while($counter<5) {
       echo '<img src="images/grau.svg" class="star" alt="" />';
       $counter++;
    }	
    //hier die Bilder angezeigt, wenn sie verfügbar sind
    
    echo '<div id="'.$c.'_div" style="width:180px; padding:6;">';
    if($pic[$nr] !="") {
        echo '<a href="'.  str_replace('/neu', '', $pic[$nr]) .'" data-lightbox="'. $datum_bestell . '" data-title="' . $menu . ': ' . $meal["meal"] . '"><img class="mealimg" src="'.$pic[$nr].'" title="'.$pic[$nr].'"></a>';
    }
    else {
     echo '<img class="mealimg" src="images/empty.png">';
    }
    echo'</div></div>';
    
    echo '</div>';	
    if($nr==3) {
        echo '</div>';
    } 
}

	}
else{

	$wochep=$woche+1;
	$wochem=$woche-1;
	echo '</div>
	<div class="container all">
            <div class="weeknav">
                <a class="btn btn-primary btn-lg active" href="intern.php?week='.$wochem.'#row"><<</a>
                <a class="btn btn-default btn-lg active" href="intern.php?week=0#row">Aktuelle Woche / N&auml;chste Schulwoche</a>
                <a class="btn btn-primary btn-lg active" href="intern.php?week='.$wochep.'#row"> >> </a>
            </div>
				<div class="row day empty"  style="height:400px;">
				Kein Plan f&uumlr diese Woche verf&uuml;gbar.';
}
?>    </form>
        </div>
    </div>
   </div>  
<footer class="footer">
       <p style="color:white;">Version <?php include 'includes/version.php';?>  - erstellt mit Bluefish und Bootstrap - umgesetzt von Dominik Eichler und Alwin Ebermann</p>
       <p class="text-muted"> <a  href="https://ldkf.de//de/impressum.html" target="_blank">Impressum</a> - <a target="_blank" href="https://ldkf.de//de/datenschutzerklaerung.html" >Datenschutz</a> - <a href="Information.php" target="_blank">&Uuml;ber diese Seite</a>      </p>  
</footer>
      <script type="text/javascript" src="js/jquery-1.11.2.min.js"></script>
      <script type="text/javascript" src="js/lightbox.min.js"></script>
      <script type="text/javascript" src="js/bootstrap.min.js"></script>
      <script type="text/javascript" src="js/main.js"></script>
     <!-- /container -->  
  </body>
</html>