<?php
session_start();
include("includes/db_connect.php");
$anz=0;
$anzahl=0;
$woche=0;
$no_meal=0;
 if(isset($_GET['week'])){
        $woche=$_GET['week'];
    }
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
    header('Location: ./group.php');
} 

if(isset($_POST['wkorb']) and $_POST['wkorb']==1) {
    $d=1;
    $j=0;
    $ssid=$_SESSION['idldkf'][0];
    while($d<21) {
    	if(isset($_POST[$d]) and isset($_POST[$d.'_anz'] )) {
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
            echo $outa[$j];
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
   // header('Location: group.php');

} 


?>

<html lang="de">
  <head>
  		<link rel="icon" href="favicon.png">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta http-equiv="content-type" content="text/html; charset=UTF-8" >    
    <title>Multi-Accounts</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/main.css" rel="stylesheet">
    <link href="css/bootstrap-theme.min.css" rel="stylesheet">
 </head>
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
        <div class="" id="navbar" style="vertical-align:middle;">
            <ul class="nav navbar-nav" >
                <li><a href="./">Startseite</a></li>
                <li><a href="upload.php">Uploader</a></li> 
                <li class="active"><a href="group.php" title="Erm&ouml;glicht Gruppierung von MSN-Accounts">Multi-Accounts</a></li>           
<?php
     if(!isset($_SESSION['ldkf-id']) or !isset($_SESSION['idldkf'])) {

     		if(isset($_SESSION['error']) and $_SESSION['error']==1) {
      echo '</ul>
      		<ul class="nav navbar-nav navbar-right">
        			<li class="navbar-brand" style="color:red;">Fehler beim Login</li>
       			 <form method="post" class="navbar-form navbar-left" action="includes/login.php" >
        				<div class="form-group">
          				<input autocomplete="off" type="text" name="acname" class="form-control" placeholder="Benutzername">
          				<input autocomplete="off" type="password" name="pw" class="form-control" placeholder="Passwort">
	       				<button type="submit" class="btn btn-primary">Einloggen</button>
	       			</div>
      			</form>
				</ul>';
				$_SESSION['error']="";
      		
      	}
      	else {
     	
     	
     	echo '</ul>
      		<ul class="nav navbar-nav navbar-right">
       		<form method="post" class="navbar-form navbar-left" action="includes/login.php" >
        			<div class="form-group">
          			<input autocomplete="off" type="text" name="acname" class="form-control" placeholder="Benutzername">
          			<input autocomplete="off" type="password" name="pw" class="form-control" placeholder="Passwort">
        				<button type="submit" class="btn btn-primary">Einloggen</button>
        			</div>
      		</form>
			</ul>';
      	} 
      }
      else{ 
		echo '</ul>
				<ul class="nav navbar-nav navbar-right">
   	         <form class="navbar-form form-inline" id="order" action="/group.php" method="post">
      	         <button type="submit" id="nonejs_button" class="btn btn-primary">Bestellung &uuml;bermitteln</button><input type="hidden" value="1" name="wkorb">
                  <a href="/group.php?l=1" class="btn btn-primary" style="margin-left:20px;">Ausloggen</a>
           </ul>'; 
      
      }    
	?>
        </div><!-- /.navbar-collapse -->
</nav>
<div class="container main">
	<div class="masthead">
		<div class="jumbotron" style="border-radius:0">
        
<?php
	if (isset($bestellt) and $bestellt == 1 ) {
   	echo '<p class="lead">Deine Bestellung war erfolgreich.</p>';
	}     
	else {   
    	echo '<p>Hier kanst du mehrere Accounts vom Men&uuml;service zu Einem zusammenfassen.</p>
        	 	<p>Diese Funktion wurde von uns entwickelt und ist nicht mit dem MSN abgesprochen. Sie soll das Bestellen vereinfachen, indem f&uuml;r bis zu 
        		 3 Leute gleichzeitig bestellt werden kann ohne ein erneutes Einloggen.</p>
        	 	<a id="row"></a>' ; 
	}
	$wochen=$woche+1;
	$wochep=$woche-1;
	if(isset($_SESSION['multi'])) { 
		if($_SESSION['multi']==1) {
			foreach($_SESSION['idldkf'] as $sessionid){
				if($sessionid!=""){
					exec("python scripts/getmenu.py $sessionid $woche", $getmen);
					if($woche==3 and $getmen[0]==1){
						header('Location: group.php?week=0');
					}
					if($getmen[0]!=1 ){
						$getm=json_decode($getmen[0]); 
					}
   			}
			}
			if (isset($getm)){
				echo '</div>       
        				<div class="container" id="all">
            			<div id="weeknav">
                			<a class="btn btn-primary btn-lg active" href="group.php?week='.$wochep.'#row"><<</a>
                			<a class="btn btn-default btn-lg active" href="group.php?week=0#row">Aktuelle Woche / N&auml;chste Schulwoche</a>
               			<a class="btn btn-primary btn-lg active" href="group.php?week='.$wochen.'#row"> >> </a>
            			</div>
            			<div id="meals">';
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
				$c=0;
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
        					echo '<div class="row"><div class="date">'.$tag_bestell.", der ".$value_date.'</div>
        						</div>
        						<div class="row day">';
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
   				echo '<div class="col-md-3 col-xs-12 col-sm-6" style="width:25%; padding:2px;">
   							<div class="meal';
    				if($anzahl>0) {
        				echo ' tdborder';
    				}
    				else {
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
					if(isset($row_bew[0])){
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
        				echo '<a href="'.str_replace('/neu', '', $pic[$nr]).'" data-lightbox="'.$datum_bestell.'" data-title="'.$menu.': '.$meal["meal"].'"><img class="mealimg" src="'.$pic[$nr].'" title="'.$pic[$nr].'"></a>';
    				}
   				else {
     					echo '<img class="mealimg" src="images/empty.png">';
    				}
    				echo'</div>
    						</div>';
    				echo '</div>';	
    				if($nr==3) {
        				echo '</div>';
    				} 
				}
				echo '</form>';
			}
			else{
				$wochep=$woche+1;
				$wochem=$woche-1;
				echo '</div>
						<div class="container" id="all">
            			<div id="weeknav">
                			<a class="btn btn-primary btn-lg active" href="group.php?week='.$wochem.'#row"><<</a>
                			<a class="btn btn-default btn-lg active" href="group.php?week=0#row">Aktuelle Woche / N&auml;chste Schulwoche</a>
                			<a class="btn btn-primary btn-lg active" href="group.php?week='.$wochep.'#row"> >> </a>
            			</div>
							<div class="row day empty"  style="height:400px;">
								Kein Plan f&uumlr diese Woche verf&uuml;gbar.';
			}	
		}
	}
	else {
		echo'</div>
				<div class="container" id="all">
					<div id="weeknav">
         		</div>
					<div class="row day"  style="height:500px; text-align:left; padding-left:60px; padding-top:30px;">
      				<form method="post" style="text-align:right; float:left; margin-left:0; width:400px;" action="includes/registrierung.php" name="reg" onsubmit="return chkFormular()">
         				<h2 style="margin-right:15px;">Neuen Account erstellen:</h2><br>
							<div class="form-group" style="margin-bottom:0;">
       						Benutzername: 
         					<input autocomplete="off" size="12" required type="text" name="username" class="" style="width:133px; " placeholder="Benutzername"> *
         					<br>
         					Passwort:
         					<input autocomplete="off" size="12" required type="password" name="password" class="" style="width:133px; " placeholder="Passwort"> *<br>
         					Passwort best&auml;tigen:
         					<input autocomplete="off" size="12" required type="password" name="password_wd" class="" style="width:133px; " placeholder="Passwort"  onchange="return chkFormular()"> *	
							</div>
							<div id="pwcheck" style="display:none; float:right; color:red;">
								Passw&ouml;rter stimmen nicht &uuml;berein.
							</div>		           
        					<br><br>
       					<div class="form-group" style="margin-bottom:0;">
       						1. MSN-Account: 
         					<input autocomplete="off" size="12" required maxlength="8" type="text" name="msn[]" class="" style="width:133px; " placeholder="Kundennummer">
         					<input autocomplete="off" size="5" required maxlength="4" type="password" name="msnpw[]" class="" style="width:133px; " placeholder="Geheim"> *	
							</div>
							<div class="" style="margin-bottom:0; margin-right:9px;" >
								2. MSN-Account: 
         					<input autocomplete="off" size="12"  maxlength="8" type="text" name="msn[]" class="" style="width:133px;" placeholder="Kundennummer">
         					<input autocomplete="off" size="5"  maxlength="4" type="password" name="msnpw[]" class="" style="width:133px;" placeholder="Geheim">	
							</div>
							<div class="" style="margin-bottom:0; margin-right:9px;">
								3. MSN-Account:
         					<input autocomplete="off" size="12"  maxlength="8" type="text" name="msn[]" class="" style="width:133px; " placeholder="Kundennummer">  
         					<input autocomplete="off" size="5"  maxlength="4" type="password" name="msnpw[]" class="" style="width:133px; " placeholder="Geheim">
							</div>
							<br>
							* Pflicht
							<button type="submit" class="btn btn-primary" style="margin-left:60px; margin-right:9px">
								Gruppieren
							</button>
      				</form>
      				<div class="message">';
      if(isset($_SESSION['reg'])) {
			switch($_SESSION['reg']) {
				case 3:
					echo "Fehler - Es sind nicht alle Felder korrekt ausgefüllt."; 
					$_SESSION['reg']="";
				break;
				case 1:
					echo "Der Benuztername ist schon vergeben.";
					$_SESSION['reg']="";
				break;
				case 2:
					echo "Account erfolgreich erstellt.";
					$_SESSION['reg']="";
				break;
				default:
					echo "";
				break;
			}
		}
		echo '</div>
		</div>
	</div>';
	}
?>   


		</div>
	</div>
</div>
<footer class="footer">
       <p style="color:white;">Version <?php include 'includes/version.php';?>  - erstellt mit Bluefish und Bootstrap - umgesetzt von Dominik Eichler und Alwin Ebermann</p>
       <p class="text-muted"> <a  href="https://ldkf.de//de/impressum.html" target="_blank">Impressum</a> - <a target="_blank" href="https://ldkf.de//de/datenschutzerklaerung.html" >Datenschutz</a> - <a href="Information.php" target="_blank">&Uuml;ber diese Seite</a>      </p>  
</footer>
      <script type="text/javascript" src="js/jquery-1.11.2.min.js"></script>
      <script type="text/javascript" src="js/bootstrap.min.js"></script>
      <script type="text/javascript" src="js/main.js"></script>
     <!-- /container -->  
  </body>
</html>