<?php 
session_start();
  include("includes/db_connect.php");
  include 'includes/datum.php';  

if(isset($_COOKIE["msn_ldkf_login"]) and  isset($_SESSION['fail']) and $_SESSION['fail']!=1) {  
	$login=explode("_", $_COOKIE["msn_ldkf_login"]);
 	$_SESSION['kdnrv'] = $login[0]; 
 	$_SESSION['geheim'] = $login[1]; 

}
elseif(isset($_COOKIE['msn_loggedin'])) {
	$nr_test=$_COOKIE['msn_loggedin'];
	$test = mysql_query("SELECT kdnr, pass, stayloggedin FROM sessions where authid LIKE '$nr_test'");
	$test_row = mysql_fetch_row($test);
	$kntest=$test_row[0];
	$pwtest=$test_row[1];
	$checktest=$test_row[2];
	if(isset($pwtest)) {
		$iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB); 
		$iv = mcrypt_create_iv($iv_size, MCRYPT_RAND); 
		$kn=mcrypt_decrypt(MCRYPT_RIJNDAEL_256, "ldkfcryptkn", $kntest, MCRYPT_MODE_ECB, $iv);
		$pw=mcrypt_decrypt(MCRYPT_RIJNDAEL_256, "ldkfcryptpw", $pwtest, MCRYPT_MODE_ECB, $iv);
		$pw=substr($pw, 0, 4); 					
		$kn=substr($kn, 0, 8); 		
		if($checktest==1) {
			$_SESSION['geheim'] = $pw;
			$_SESSION['kdnrv'] = $kn;
			$_COOKIE["msn_ldkf_login"]=1;
		}
		else {
			setcookie("msn_ldkf_login","",time() - 3600);		
		}
			
	}

}
 if(isset($_POST['kdnrv']) and isset($_POST['geheim']) and $_POST['geheim']!="" and $_POST['kdnrv']!="" ) { 
 	$_SESSION['kdnrv'] = $_POST['kdnrv']; 
 	$_SESSION['geheim'] = $_POST['geheim'];   
 	if(isset($_POST['getloggedin']) and $_POST['getloggedin']==1) {
		
		if(!isset($_COOKIE['msn_loggedin'])) {
		$iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB); 
		$iv = mcrypt_create_iv($iv_size, MCRYPT_RAND); 
		$encryptedkn = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, "ldkfcryptkn", $_SESSION['kdnrv'], MCRYPT_MODE_ECB, $iv);
		$encryptedpw = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, "ldkfcryptpw", $_SESSION['geheim'], MCRYPT_MODE_ECB, $iv);	
		$test_ex = mysql_query("SELECT  pass FROM sessions where kdnr LIKE '$encryptedkn'");
		$test_rowex = mysql_fetch_row($test_ex);
		$existtest=$test_rowex[0];
		if(isset($existtest) and $existtest!="") {
			$auth = mysql_query("SELECT  authid FROM sessions where kdnr LIKE '$encryptedkn'");
			$getauth= mysql_fetch_row($auth);
			$authtest=$getauth[0];
			setcookie("msn_loggedin", $authtest, time()+(3600*24*365));
			$eintrag = "Update sessions SET stayloggedin = '1' where authid LIKE '$auth'"; 
			$eintragen = mysql_query($eintrag);
		}
		else{
			$eintrag = "INSERT INTO sessions (kdnr, pass, stayloggedin) VALUES ('$encryptedkn', '$encryptedpw', '1')"; 
    		$eintragen = mysql_query($eintrag);  
    		$testa = mysql_query("SELECT authid FROM sessions where kdnr LIKE '$encryptedkn'");
			$test_rowa = mysql_fetch_row($testa);
			$authtest=$test_rowa[0];
		}
		setcookie("msn_loggedin", $authtest, time()+(3600*24*365));
		}
   	else {
			$auth=$_COOKIE['msn_loggedin'];
			$eintrag = "Update sessions SET stayloggedin = '1' where authid LIKE '$auth'"; 
			$eintragen = mysql_query($eintrag);
		}


 		}
 	header('Location: intern.php'); 
}


$result_bewa = mysql_query("SELECT bew FROM bewertung WHERE id LIKE '$pfada'");
$row_bewa = mysql_fetch_row($result_bewa);
if(isset($row_bewa[0])) {
	$a1 =  $row_bewa[0]; 
	$a2=round($a1,2);
	$a3= round($a2,1);
	$bewertga= round($a3,0); 
} 
else {
	$bewertga=0;
}   
$result_bewb = mysql_query("SELECT bew FROM bewertung WHERE id LIKE '$pfadb'");
$row_bewb = mysql_fetch_row($result_bewb);
if(isset($row_bewb[0])) {
	$b1 =  $row_bewb[0]; 
	$b2=round($b1,2);  
	$b3= round($b2,1);
	$bewertgb= round($b3,0); 
} 
else {
	$bewertgb=0;
}
$result_bewc = mysql_query("SELECT bew FROM bewertung WHERE id LIKE '$pfadc'");
$row_bewc = mysql_fetch_row($result_bewc);
if(isset($row_bewc[0])) {
	$c1 =  $row_bewc[0]; 
	$c2=round($c1,2);  
	$c3= round($c2,1) ; 
	$bewertgc= round($c3,0);  
}
else {
	$bewertgc=0;
} 
$result_bewd = mysql_query("SELECT bew FROM bewertung WHERE id LIKE '$pfadd'");
$row_bewd = mysql_fetch_row($result_bewd);
if(isset($row_bewd[0])) { 
	$d1 =  $row_bewd[0];   
	$d2=round($d1,2);  
	$d3= round($d2,1) ; 
	$bewertgd= round($d3,0); 
}
else {
	$bewertgd=0;
}
$datum_anzeige = explode('-',$datum); 
$value_date = $datum_anzeige[2].'.'.$datum_anzeige[1].'.'.$datum_anzeige[0]; 
$pfad1="";
$result1 = mysql_query("SELECT name FROM essen WHERE id LIKE '$pfada'");
$row1 = mysql_fetch_row($result1);
$pfad1 = $row1[0];
$pfad2="";
$result2 = mysql_query("SELECT name FROM essen WHERE id LIKE '$pfadb'");
$row2 = mysql_fetch_row($result2);
$pfad2 = $row2[0];
$pfad3="";
$result3 = mysql_query("SELECT name FROM essen WHERE id LIKE '$pfadc'");
$row3 = mysql_fetch_row($result3);
$pfad3 = $row3[0];
$pfad4="";
$result4 = mysql_query("SELECT name FROM essen WHERE id LIKE '$pfadd'");
$row4 = mysql_fetch_row($result4);
$pfad4 = $row4[0];
$anz_bew_outa=0;		
$anz_bew_outb=0;		
$anz_bew_outc=0;		
$anz_bew_outd=0;		 	
$ka=0;
$kb=0;
$kc=0;
$kd=0;			
$image_o="grau.svg";
$image="gelb.svg";   			
?>          
<!DOCTYPE html>
<html lang="de">
	<head>
		<link rel="icon" href="favicon.png">
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
	 	<meta http-equiv="content-type" content="text/html; charset=UTF-8" > 
	 	<meta name="robots" content="index,follow">
 	 	<meta name="description" content="">
	 	<meta name="keywords" content=""> 
    	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    	<title>Startseite</title>
    	<link href="css/bootstrap.min.css" rel="stylesheet">
    	<link href="css/main.css" rel="stylesheet">
    	<link href="css/lightbox.css" rel="stylesheet">
    	<link href="css/bootstrap-theme.min.css" rel="stylesheet">
		<script type="text/javascript">
			function resettag(tag){
  	  		if(tag.value=="<?php  echo  $datum_anzeige[2] ; ?>"){
  	      		tag.value="";
  	  		}
			} 
			function resetmonat(monat){
 	   		if(monat.value=="<?php  echo  $datum_anzeige[1] ; ?>"){
 	      		monat.value="";
  	  		}
			} 
			function resetjahr(jahr){
    			if(jahr.value=="<?php  echo  $datum_anzeige[0] ; ?>"){
    	    		jahr.value="";
    			}
			} 
			function resettagback(tag){
   	 		if(tag.value==""){
   	   		tag.value="<?php echo  $datum_anzeige[2] ;  ?>"  ;
   	 		}
			} 
			function resetmonatback(monat){
   	 		if(monat.value==""){
   	   		monat.value="<?php echo  $datum_anzeige[1] ;  ?>"  ;
   	 		}
			} 
			function resetjahrback(jahr){
   	 		if(jahr.value==""){
   	   		jahr.value="<?php echo  $datum_anzeige[0] ;  ?>"  ;
   	 		}
			} 
		</script>
	</head>
	<body>
	<nav class="navbar navbar-inverse navbar-fixed-top" style="min-height:20px;">
	<!--<div class="container-fluid" id="comeback" style="display:none;">
		<a class="btn" style="padding:0px;color:white; margin-left:404px;" onclick="nav_show()">...</a>
	</div>-->
		<div class="container-fluid" id="navigation" style="display:block;">
   <!-- Brand and toggle get grouped for better mobile display -->
   		<div class="navbar-header">
   			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            	<span class="sr-only">Toggle navigation</span>
           	 	<span class="icon-bar"></span>
          	  	<span class="icon-bar"></span>
          	  	<span class="icon-bar"></span>
         	</button>
   			<a class="navbar-brand" href="http://ldkf.de">LDKF.de</a>
   		</div>
    <!-- Collect the nav links, forms, and other content for toggling -->
      	<div id="navbar" class="navbar-collapse collapse">
     			<ul class="nav navbar-nav" >
      			<li class="active"><a href="">Startseite<span class="sr-only">(current)</span></a></li>
      			<li><a href="upload.php">Uploader</a></li>
      			<li><a href="group.php" title="Erm&ouml;glicht Gruppierung von MSN-Accounts">Multi-Accounts</a></li>

      <?php 
			if(isset($_SESSION['fail']) and $_SESSION['fail']==1) {
				echo '</ul>
      				<ul class="nav navbar-nav navbar-right">
        					<li class="navbar-brand" style="color:red;">Fehler beim Login</li>
        					<form method="post" id="msn" class="navbar-form navbar-left" action="?">
        						<div class="form-group">
        							<input autocomplete="off" type="text" name="kdnrv" class="form-control" placeholder="Kundennummer">
         						<input autocomplete="off" type="password" name="geheim" class="form-control" placeholder="Geheimzahl">
        							<button type="submit" class="btn btn-primary">Einloggen</button>
        						</div>
        						<br>
								<div class="input-group" style="width:170px; color:white; vertical-align:middle">
										<span class="input-group">										
											<input style="height:0; margin:1px;" type="checkbox" value="1" name="getloggedin"> 
										</span>
										Angemldet bleiben									
									</div>
     	 					</form>
						</ul>';
				$_SESSION['fail']=0;
	 		}
			else {    
     			if(!isset($_SESSION['id']) and !isset($_COOKIE["msn_ldkf_login"])) {

     					 echo '<!--<li><a class="btn" style="padding:15px;" onclick="nav_hide()">...</a></li> -->
     					  			   
    						</ul>
      					<ul class="nav navbar-nav navbar-right">
        						<form method="post" class="navbar-form navbar-left" action="?">
        							<div class="form-group">
         							<input autocomplete="off" type="text" name="kdnrv" class="form-control" placeholder="Kundennummer">
          							<input autocomplete="off" type="password" name="geheim" class="form-control" placeholder="Geheimzahl">
          							<button type="submit" class="btn btn-primary">Einloggen</button>
    								</div>
    								<br>
									<div class="input-group" style="width:170px; color:white; vertical-align:middle">
										<span class="input-group">										
											<input style="height:0; margin:1px;" type="checkbox" value="1" name="getloggedin"> 
										</span>
										Angemldet bleiben									
									</div>
     							</form>
        					</ul>';
      		} 
      		else {
					echo '<li><a href="intern.php">Bestellseite</a></li>
							<!--<li><a class="btn" style="padding:15px;" onclick="nav_hide()">...</a></li>-->
						</ul>
						<ul class="nav navbar-nav navbar-right" style="vertical-align:middle;">
							<form class="navbar-form navbar-left">
   							<a href="/intern.php?l=1" class="btn btn-primary" style="margin-left:20px;">Ausloggen</a>		
     						</form>
     					</ul>';      
      		}
      	}
		?>
    	</div>
    	<!-- /.navbar-collapse -->
	</div>
  <!-- /.container-fluid -->
</nav>
<div class="container main">
	<div class="masthead">
		<div class="jumbotron" style="border-radius:0px;">
			<h1>Willkommen auf msn.ldkf.de</h1>
      	<p class="lead">Hier kannst du ganz bequem dein Essen bestellen.</p>
       	<p class="lead">Welche Vorteile du hast, über uns und nicht direkt beim Menü-Service-Neuburg zu bestellen, siehst du <a href="info.php">hier</a>.</p><a name="row"></a>
      </div>       
		<div style="width:800px; padding:20px; padding-top:0">      
      	<div class="date_index"> 
      		<?php 
      			echo $datum_anz.', '.$value_date;
      		?>
 			</div>
			<marquee scrollamount=2 scrolldelay=10><b>
				Plan bis 
				<?php  
 					$result_plan = mysql_query("SELECT MAX(datum) FROM datum");
    				$row_plan = mysql_fetch_row($result_plan);
					$plan_ak = $row_plan[0];
					$plan_de = explode('-',$plan_ak);	
	 				echo $plan_de[2].'.'.$plan_de[1].'.'.$plan_de[0];
				?> 
				aktuell.</b>
			</marquee> 
   	</div>   
	</div>
<!-- Jumbotron -->
<form method="post" enctype="multipart/form-data" action="?#row"> 
	<table>
		<tr>
			<td style="padding-right:30px;"><input  class="btn btn-default" style="" value="Zu Datum gehen:" type="submit"></td> 
  			<td>
  				<input class="form-control" style="width:42px;" name="tag" type="text" value="<?php  echo  $datum_anzeige[2] ;  ?>" onfocus="resettag(tag)" onblur="resettagback(tag)">
  			</td>
  			<td><h2>.</h2></td>
  			<td>
  				<input class="form-control" style="width:42px;" name="monat" type="text" value="<?php echo $datum_anzeige[1]; ?>" onfocus="resetmonat(monat)" onblur="resetmonatback(monat)">
  			</td>
  			<td><h2>.</h2></td>
  			<td>
  				<input class="form-control" style="width:60px;" name="jahr" type="text" value="<?php echo $datum_anzeige[0]; ?>" onfocus="resetjahr(jahr)" onblur="resetjahrback(jahr)"  >
  			</td>
  			<td style="padding-left:60px;">
  				<a class="btn btn-primary btn-lg" href="?prev=<?php echo $datum; ?>#row"><<</a>
  			</td>
  			<td style="padding:10px;" >
  				<a class="btn btn-default btn-lg" href="?#row">Heute / N&auml;chster Schultag</a>
  			</td>
  			<td>
  				<a class="btn btn-primary btn-lg" href="?next=<?php echo $datum; ?>#row">>></a>
  			</td>
  		</tr>
	</table>
</form> 
<div class="row" style="color:black; margin-bottom:35px;">
	<div class="col-xs-10 content">
		<div class="show">
     		<table width="100%" style="color:white"><tr><td width="90px;">Men&uuml; A</td><td width="140px;">
         <?php 
         $counter=0; 
    		while($counter<$bewertga) {
       		echo '<img src="images/gelb.svg" class="star" alt="" />';
       		$counter++;
    		}		
    		while($counter<5) {
       		echo '<img src="images/grau.svg" class="star" alt="" />';
       		$counter++;
			}
     		?>
			</td><td width="120px;"><font size="2">(<?php   
				$anz_bew_outa=0;
          	$anz_bewa = mysql_query("SELECT anzahl FROM bewertung WHERE id LIKE '$pfada'");
				while($row_anz_bewa = mysql_fetch_row($anz_bewa))
				$anz_bew_outa = $row_anz_bewa[0];     
				if($anz_bew_outa=="") {  
				$anz_bew_outa=0;
				}       
				echo $anz_bew_outa;
				if ($anz_bew_outa!=1){echo" Bewertungen";} else {echo " Bewertung";}    ?>) 
				</font>        </td><td align="right">
        <?php echo $pfad1;?>	</td></tr></table>			
         </div> 
            <div class="picture">
            
            
<?php
$ordner = "pictures/".$pfada; 
	if ($ordner !=""){
		if(is_dir($ordner)) {
		$allebilder = scandir($ordner); 
		$ka=0; 
		foreach ($allebilder as $bild) { 
		$Tumbnail="";
	   $bildinfo = pathinfo($ordner."/neu/".$bild); 
		   if ($bild != "." && $bild != ".."  && $bild != "_notes" && $bildinfo['basename'] != "Thumbs.db") { 
				$ka= $ka+1; 
	    		if($ka > 3 or $bild=="neu" ) break;
	    		$upload_data_a="";
	    		$uplo = explode('.', $bildinfo['basename'] ); 
   			$upload_data_rowa = mysql_query("SELECT datum FROM bilder WHERE id LIKE '$uplo[0]' ");
   			$rowupla = mysql_fetch_row($upload_data_rowa);
						$upload_data_a1 = explode('-', $rowupla[0] ); 
						$upload_data_a = "$upload_data_a1[2].$upload_data_a1[1].$upload_data_a1[0]"; 	    
						$pic_path =$ordner."/".$bildinfo['basename'];
						echo '<div class="col-xs-4 thumb">
										<a class="thumbnail"'; if(!isset($_SESSION['id'])){ echo' style="margin-bottom:0" ';} echo 'href="'.$pic_path.'"data-lightbox="'. $ordner .'">
                    				<img class="img-responsive" src="'.$ordner."/neu/".$bildinfo['basename'].'" alt="">
                				</a>
            				</div>
          					<div class="modal fade bs-example-modal-lg" id="a'.$ka.'" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  									<div class="modal-dialog" style="width: 900px;">
            						<div class="modal-content" >
											<div class="modal-header"><div style="margin-bottom:10px">
        										<button type="button" class="close" data-dismiss="modal" aria-label="Close">&times;</button>
											</div>
      								</div>
      								<img class="img-responsive" title="'.$pic_path.'" src="'.$pic_path.'" alt=""></div>
      							</div>
								</div>';

    }
 }            }        }
	if($ka<=3){echo'
            <div class="col-xs-4 thumb">
                <a class="thumbnail"'; if(!isset($_SESSION['id'])){ echo' style="margin-bottom:0" ';} echo 'href="upload.php?n=1&datum='.$datum.'">
                    <img class="img-responsive" src="images/ph.png" alt="" on>
                </a>
            </div>';}?>
          </div>
<?php 
	if(isset($_SESSION['id'])){
      echo'<div class="bewertung">';
      if(!isset($_COOKIE[$pfada])) {  echo '
      <form method="post" action="includes/bewertung.php?bew='; echo  $pfada;echo "&datum="; echo $datum;echo '"><table cellspacing="1pt" cellpadding="1px">
         <tr> 
         <td>
         <select name="bew_a" class="select" style="height:17pt; width:26pt;">
         <option>-</option>
         <option>0</option>
         <option>1</option>
         <option>2</option>
         <option>3</option>
         <option>4</option>
         <option>5</option>
         </select></td>
         <td> /5 Sterne</td>
         <td><input class="btn btn-default bew" name=""'; echo " value='Essen bewerten'"; echo ' type="submit"></td>
         </tr>
         </table>
        </form> ';
       }
       else {
       	echo "Essen wurde bewertet.";
       }
		 echo'</div>';          
          }
          ?> 
        </div>
        
        <div class="col-xs-10 content">
         <div class="show">
          <table width="100%" style="color:white"><tr><td width="90px;"> Men&uuml; B</td><td width="140px;">
         <?php 
         $counter=0; 
    		while($counter<$bewertgb) {
       		echo '<img src="images/gelb.svg" class="star" alt="" />';
       		$counter++;
    		}		
    		while($counter<5) {
       		echo '<img src="images/grau.svg" class="star" alt="" />';
       		$counter++;
			}
     		?></td><td min-width="130px;"><font size="2">(<?php 
				$anz_bew_outb=0;
          	$anz_bewb = mysql_query("SELECT anzahl FROM bewertung WHERE id LIKE '$pfadb'");
				$row_anz_bewb = mysql_fetch_row($anz_bewb);
				$anz_bew_outb = $row_anz_bewb[0]; 
				if($anz_bew_outb=="") {  
				$anz_bew_outb=0;
				}        
				echo $anz_bew_outb; 
				if ($anz_bew_outb!=1){echo" Bewertungen";} else {echo " Bewertung";}    ?>)  </font> </td><td align="right">   <?php echo $pfad2;?>  </td></tr></table>
         </div> 
			<div class="picture">
<?php
		$ordner = "pictures/".$pfadb; 
		if ($ordner !=""){
								if(is_dir($ordner)) {
															$allebilder = scandir($ordner); 
															$kb=0;
															foreach ($allebilder as $bild) { 
															$bildinfo = pathinfo($ordner."/".$bild); 

      if ($bild != "." && $bild != ".."  && $bild != "_notes" && $bildinfo['basename'] != "Thumbs.db") { 
 		$kb= $kb+1; 
	   if($kb > 3 or $bild=="neu") break;
	    	    		$upload_data_b="";
	    	    		$uplo = explode('.', $bildinfo['basename'] ); 
	     				$upload_data_rowb = mysql_query("SELECT datum FROM bilder WHERE id LIKE '$uplo[0]' ");
   	$rowuplb = mysql_fetch_row($upload_data_rowb);
		$upload_data_b1 = explode('-', $rowuplb[0] ); 
		$upload_data_b = "$upload_data_b1[2].$upload_data_b1[1].$upload_data_b1[0]";  
		echo '<div class="col-xs-4">
										<a class="thumbnail"'; if(!isset($_SESSION['id'])){ echo' style="margin-bottom:0" ';} echo 'href="'.$ordner.'/'.$bildinfo['basename'].'"data-lightbox="'. $ordner . '">
                    				<img class="img-responsive" src="'.$ordner."/neu/".$bildinfo['basename'].'" alt="">
                				</a>
            				</div>
          					<div class="modal fade bs-example-modal-lg" id="b'.$kb.'" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  									<div class="modal-dialog" style="width: 900px;">
            						<div class="modal-content" >
											<div class="modal-header"><div style="margin-bottom:10px">
        									<button type="button" class="close" data-dismiss="modal" aria-label="Close">&times;</button>
											</div>
      								</div>
      								<img class="img-responsive thumb" title="'.$ordner."/".$bildinfo['basename'].'" src="'.$ordner."/".$bildinfo['basename'].'" alt=""></div>
      							</div>
								</div>';
            
            

    }
 }            }        }   
	if($kb<=3){echo'
            <div class="col-xs-4 thumb">
                <a class="thumbnail"'; if(!isset($_SESSION['id'])){ echo' style="margin-bottom:0" ';} echo 'href="upload.php?n=2&datum='.$datum.'">
                    <img class="img-responsive" src="images/ph.png" alt="">
                </a>
            </div>';}?>
          </div>
        
<?php 
	if(isset($_SESSION['id'])){
      echo'<div class="bewertung">';
      if(!isset($_COOKIE[$pfadb])) {  echo '
      <form method="post" action="includes/bewertung.php?bew='.$pfadb."&datum=".$datum.'"><table cellspacing="1pt" cellpadding="1px">
         <tr> 
         <td>
         <select name="bew_b" class="select" style="height:17pt; width:26pt;">
         <option>-</option>
         <option>0</option>
         <option>1</option>
         <option>2</option>
         <option>3</option>
         <option>4</option>
         <option>5</option>
         </select></td>
         <td> /5 Sterne</td>
         <td><input class="btn btn-default bew" name=""'; echo " value='Essen bewerten'"; echo ' type="submit"></td>
         </tr>
         </table>
        </form> ';
       }
       else {
       	echo "Essen wurde bewertet.";
       }
       echo '</div>';
    }
?>  
        
                 
        
        
        
        </div>
                
        <div class="col-xs-10 content">
         <div class="show">
          <table width="100%" style="color:white"><tr><td width="90px;"> Men&uuml; C</td><td width="140px;">
        <?php 
         $counter=0; 
    		while($counter<$bewertgc) {
       		echo '<img src="images/gelb.svg" class="star" alt="" />';
       		$counter++;
    		}		
    		while($counter<5) {
       		echo '<img src="images/grau.svg" class="star" alt="" />';
       		$counter++;
			}
     		?></td><td width="120px;"><font size="2">(<?php  
				$anz_bew_outc=0;
          	$anz_bewc = mysql_query("SELECT anzahl FROM bewertung WHERE id LIKE '$pfadc'");
				$row_anz_bewc = mysql_fetch_row($anz_bewc);
				$anz_bew_outc = $row_anz_bewc[0]; 
				if($anz_bew_outc=="") {  
				$anz_bew_outc=0;
				}           
				echo $anz_bew_outc;
				if ($anz_bew_outc!=1){echo" Bewertungen";} else {echo " Bewertung";}    ?>)    </font> </td><td align="right">    <?php echo $pfad3;?> </td></tr></table>
         </div> 
			<div class="picture">
<?php
		$ordner = "pictures/".$pfadc; 
		if ($ordner !=""){
								if(is_dir($ordner)) {
															$allebilder = scandir($ordner); 
															$kc=0;
															foreach ($allebilder as $bild) { 
															$bildinfo = pathinfo($ordner."/".$bild); 

      if ($bild != "." && $bild != ".."  && $bild != "_notes" && $bildinfo['basename'] != "Thumbs.db") { 
 		$kc= $kc+1; 
	   if($kc > 3 or $bild=="neu") break;
	    	    		$upload_data_c="";
	    	    		$uplo = explode('.', $bildinfo['basename'] ); 
	     				$upload_data_rowc = mysql_query("SELECT datum FROM bilder WHERE id LIKE '$uplo[0]' ");
   	$rowuplc = mysql_fetch_row($upload_data_rowc);
		$upload_data_c1 = explode('-', $rowuplc[0] );
		$upload_data_c = "$upload_data_c1[2].$upload_data_c1[1].$upload_data_c1[0]";  
		echo '<div class="col-xs-4 thumb">
										<a class="thumbnail"'; if(!isset($_SESSION['id'])){ echo' style="margin-bottom:0" ';} echo 'href="'.$ordner.'/'.$bildinfo['basename'].'"data-lightbox="'. $ordner . '">
                    				<img class="img-responsive"  src="'.$ordner."/neu/".$bildinfo['basename'].'" alt="">
                				</a>
            				</div>
          					<div class="modal fade bs-example-modal-lg" id="c'.$kc.'" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  									<div class="modal-dialog" style="width: 900px;">
            						<div class="modal-content" >
											<div class="modal-header"><div style="margin-bottom:10px">
        									<button type="button" class="close" data-dismiss="modal" aria-label="Close">&times;</button>
											</div>
      								</div>
      								<img class="img-responsive" title="'.$ordner."/".$bildinfo['basename'].'" src="'.$ordner."/".$bildinfo['basename'].'" alt=""></div>
      							</div>
								</div>';

    }
 }            }        }   
	if($kc<=3){echo'
            <div class="col-xs-4 thumb">
                <a class="thumbnail"'; if(!isset($_SESSION['id'])){ echo' style="margin-bottom:0" ';} echo 'href="upload.php?n=3&datum='.$datum.'">
                    <img class="img-responsive" src="images/ph.png" alt="">
                </a>
            </div>';}?>
          </div>
          
<?php 
	if(isset($_SESSION['id'])){
      echo'<div class="bewertung">';
      if(!isset($_COOKIE[$pfadc])) {  echo '
      <form method="post" action="includes/bewertung.php?bew='; echo  $pfadc;echo "&datum="; echo $datum;echo '"><table cellspacing="1pt" cellpadding="1px">
         <tr> 
         <td>
         <select name="bew_c" class="select" style="height:17pt; width:26pt;">
         <option>-</option>
         <option>0</option>
         <option>1</option>
         <option>2</option>
         <option>3</option>
         <option>4</option>
         <option>5</option>
         </select></td>
         <td> /5 Sterne</td>
         <td><input class="btn btn-default bew" name=""'; echo " value='Essen bewerten'"; echo ' type="submit"></td>
         </tr>
         </table>
        </form> ';} else { echo "Essen wurde bewertet.";}
        echo '</div>';
     }
		?>  
        
                   
          
        </div>
        
        <div class="col-xs-10 content">
         <div class="show">
          <table width="100%" style="color:white"><tr><td  width="90px;"> Men&uuml; D</td><td width="140px;">
        <?php 
         $counter=0; 
    		while($counter<$bewertgd) {
       		echo '<img src="images/gelb.svg" class="star" alt="" />';
       		$counter++;
    		}		
    		while($counter<5) {
       		echo '<img src="images/grau.svg" class="star" alt="" />';
       		$counter++;
			}
     		?></td><td width="120px;"><font size="2"> (<?php    
				$anz_bew_outd=0;
          	$anz_bewd = mysql_query("SELECT anzahl FROM bewertung WHERE id LIKE '$pfadd'");
				$row_anz_bewd = mysql_fetch_row($anz_bewd);
				$anz_bew_outd = $row_anz_bewd[0]; 
				if($anz_bew_outd=="") {  
				$anz_bew_outd=0;
				}           
				echo $anz_bew_outd;
				if ($anz_bew_outd!=1){echo" Bewertungen";} else {echo " Bewertung";}    ?>)  </font> </td><td align="right">  <?php echo $pfad4;?>  </td></tr></table>  
         </div> 
         <div class="picture">
			<?php
		$ordner = "pictures/".$pfadd; 
		if ($ordner !=""){
			if(is_dir($ordner)) {
				$allebilder = scandir($ordner); 
				$kd=0;
				foreach ($allebilder as $bild) { 
					$bildinfo = pathinfo($ordner."/".$bild); 
					if ($bild != "." && $bild != ".."  && $bild != "_notes" && $bildinfo['basename'] != "Thumbs.db") { 
 						$kd= $kd+1; 
	   				if($kd > 3 or $bild=="neu") break;
	    	    		$upload_data_d="";
	    	    		$uplo = explode('.', $bildinfo['basename'] ); 
	     				$upload_data_rowd = mysql_query("SELECT datum FROM bilder WHERE id LIKE '$uplo[0]' ");
   					$rowupld = mysql_fetch_row($upload_data_rowd);
						$upload_data_d1 = explode('-', $rowupld[0] ); 
						$upload_data_d = "$upload_data_d1[2].$upload_data_d1[1].$upload_data_d1[0]";  
							echo '<div class="col-xs-4 thumb">
										<a class="thumbnail"'; if(!isset($_SESSION['id'])){ echo' style="margin-bottom:0" ';} echo 'href="'.$ordner.'/'.$bildinfo['basename'].'"data-lightbox="'. $ordner . '">
                    				<img class="img-responsive" src="'.$ordner."/neu/".$bildinfo['basename'].'" alt="">
                				</a>
            				</div>
          					<div class="modal fade bs-example-modal-lg" id="d'.$kd.'" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  									<div class="modal-dialog" style="width: 900px;">
            						<div class="modal-content" >
											<div class="modal-header"><div style="margin-bottom:10px">
        									<button type="button" class="close" data-dismiss="modal" aria-label="Close">&times;</button>
											</div>
      								</div>
      								<img class="img-responsive"  title="'.$ordner."/".$bildinfo['basename'].'" src="';  echo  $ordner."/".$bildinfo['basename'];   echo '" alt=""></div>
      							</div>
								</div>';

    				}
 				}            
 			}        
 		}   
		if($kd<=3){echo'
            <div class="col-xs-4 thumb">
                <a class="thumbnail"'; if(!isset($_SESSION['id'])){ echo' style="margin-bottom:0" ';} echo 'href="upload.php?n=4&datum='.$datum.'">
                    <img class="img-responsive" src="images/ph.png" alt="">
                </a>
            </div>';}?>
          </div>
<?php
	if(isset($_SESSION['id'])){
      echo'<div class="bewertung">';
      if(!isset($_COOKIE[$pfadd])) {  echo '
      <form method="post" action="includes/bewertung.php?bew='; echo  $pfadd;echo "&datum="; echo $datum;echo '"><table cellspacing="1pt" cellpadding="1px">
         <tr> 
         <td>
         <select name="bew_d" class="select" style="height:17pt; width:26pt;">
         <option>-</option>
         <option>0</option>
         <option>1</option>
         <option>2</option>
         <option>3</option>
         <option>4</option>
         <option>5</option>
         </select></td>
         <td> /5 Sterne</td>
         <td><input class="btn btn-default bew" name=""'; echo " value='Essen bewerten'"; echo ' type="submit"></td>
         </tr>
         </table>
        </form> ';} else { echo "Essen wurde bewertet.";}
        echo '</div>';
     }
		?>  
        
                   
          
        </div>
        
        
        </div>
        </div>
            <!-- Site footer -->
     <footer class="footer">
       <p style="color:white;"> Version  <?php include 'includes/version.php';?>  - erstellt mit Bluefish und Bootstrap - umgesetzt von Dominik Eichler und Alwin Ebermann</p>
       <p class="text-muted"> <a  href="https://ldkf.de//de/impressum.html" target="_blank">Impressum</a> - <a target="_blank" href="https://ldkf.de//de/datenschutzerklaerung.html" >Datenschutz</a> - <a href="Information.php" target="_blank">&Uuml;ber diese Seite</a>      </p>  
		</footer>
		<script type="text/javascript" src="js/jquery-1.11.2.min.js"></script>
      <script type="text/javascript" src="js/lightbox.min.js"></script>
      <script type="text/javascript" src="js/bootstrap.min.js"></script>
      <script type="text/javascript" src="js/main.js"></script>
      <script type="text/javascript">
    		window.cookieconsent_options = {"message":"This website uses cookies to ensure you get the best experience on our website","dismiss":"Got it!","learnMore":"More info","link":"https://ldkf.de//en/privacy.html","theme":"dark-bottom"};
		</script>
      <script type="text/javascript" src="//s3.amazonaws.com/cc.silktide.com/cookieconsent.latest.min.js"></script>
     <!-- /container -->
    
  </body>
</html>
