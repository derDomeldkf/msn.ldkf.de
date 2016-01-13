<?php
	session_start();
	if(isset($_POST['kdnrv']) and isset($_POST['geheim']) and $_POST['geheim']!="" and $_POST['kdnrv']!="" ) {
		$_SESSION['kdnrv'] = $_POST['kdnrv'];
		$_SESSION['geheim'] = $_POST['geheim'];
		header('Location: intern.php'); 
	}
 	include 'includes/eintrag.php';
?>

<!DOCTYPE html>
<html lang="de">
  <head>
  		<link rel="icon" href="favicon.png">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	 <meta http-equiv="content-type" content="text/html; charset=UTF-8" >    
    <title>Uploader</title>
	 <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/bootstrap-theme.min.css" rel="stylesheet">	
    <link href="css/main.css" rel="stylesheet"> 
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
    				<a class="navbar-brand" href="http://ldkf.de">LDKF.de</a>
    			</div>
    			<!-- Collect the nav links, forms, and other content for toggling -->
       		<div id="navbar" class="navbar-collapse collapse">
      			<ul class="nav navbar-nav" style="">
        				<li><a href="./">Startseite<span class="sr-only">(current)</span></a></li>
        				<li class="active"><a href="upload.php">Uploader</a></li>
        				<li><a href="group.php" title="Erm&ouml;glicht Gruppierung von MSN-Accounts">Multi-Accounts</a></li> 
						 <?php 
			   
     			if(!isset($_SESSION['id']) and !isset($_COOKIE["msn_ldkf_login"])) {

     					 echo '      
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
										<span class="input-group login-span">										
											<input style="height:0; margin:1px;" type="checkbox" value="1" name="getloggedin"> 
										</span>
										Angemldet bleiben									
									</div>
     							</form>
        					</ul>';
      		} 
      		else {
					echo '<li><a href="intern.php">Bestellseite</a></li>
						</ul>
						<ul class="nav navbar-nav navbar-right" style="vertical-align:middle;">
							<form class="navbar-form navbar-left">
   							<a href="/intern.php?l=1" class="btn btn-primary" style="margin-left:20px;">Ausloggen</a>		
     						</form>
     					</ul>';      
      		}
      	
		?>
						
<!-- /.navbar-collapse -->
  				</div>
  <!-- /.container-fluid -->
  			</div>
		</nav>
		<div class="container main" style="min-height:100%;">
		<div class="masthead">
			<div class="jumbotron" style="border-radius:0">
      		<p style="font-size: 41pt;">Willkommen auf der Upload-Seite</p>
      		<p class="lead">Hier kannst du Bilder vom Essen hochladen.</p>
       		<p class="lead">Du kannst es aus der Liste wählen, oder auf der Startseite<br>das passende Essen auswählen.</p>
      	</div>       
		</div>
      <div class="row" style="color:black; margin-bottom:30px; margin-right:10px;">
      	<div class="col-xs-10" style="background-color:white; width:50%; padding-top:40px; padding-bottom:40px;float:left; position:relative;" >
      		<form style="font-family:Ubuntu; "method="post" name="eintr" class=" " enctype="multipart/form-data" action="" onsubmit="return chkFormular_1()">
               <select name="Essenname" class="select">
						<?php
							$datum_anzeige = explode('-',$datum); $value_date = $datum_anzeige[2].'.'.$datum_anzeige[1].'.'.$datum_anzeige[0]; 
							$pfad1="";
							$result1 = mysql_query("SELECT name FROM essen WHERE id LIKE '$pfada'");
     						while($row1 = mysql_fetch_row($result1))
								$pfad1 = $row1[0];
								$pfad2="";
							$result2 = mysql_query("SELECT name FROM essen WHERE id LIKE '$pfadb'");
							while($row2 = mysql_fetch_row($result2))
								$pfad2 = $row2[0];
								$pfad3="";
							$result3 = mysql_query("SELECT name FROM essen WHERE id LIKE '$pfadc'");
     						while($row3 = mysql_fetch_row($result3))
								$pfad3 = $row3[0];
								$pfad4="";
							$result4 = mysql_query("SELECT name FROM essen WHERE id LIKE '$pfadd'");
     						while($row4 = mysql_fetch_row($result4))
								$pfad4 = $row4[0];      
      					$change_month="00";
   						if(isset ($_GET['n'])){
   							echo '<option>';
 								$n=  $_GET['n'];
								if($n==1) {
									echo $pfad1;
								} 
								else { 
									if($n==2){
										echo  $pfad2;
									}
									else {
										if($n==3) {
											echo $pfad3;
										}	
										else {
											if($n==4){
												echo $pfad4;
											}
										}
									}
								}
	 							echo '</option">';
							}
							else {
								echo"<option selected>Keine Auswahl</option>";
								$result = mysql_query("SELECT datum FROM datum Where bez LIKE 'a' ORDER BY datum ASC ");
      						while($row = mysql_fetch_row($result)){
        							$resultd = mysql_query("SELECT id FROM datum WHERE datum LIKE '$row[0]'   ");
        							while($rowd = mysql_fetch_row($resultd)){
    									$resultda = mysql_query("SELECT name FROM essen WHERE id LIKE '$rowd[0]'   ");
        								while($rowda = mysql_fetch_row($resultda)){
    										if(isset($row[1])){
    				    						$select_anzeige= htmlentities($row[0]).''. htmlentities($row[1]);
    										}
    										else {
    				    						$select_anzeige= htmlentities($row[0]);			
    							  			}
											$y = explode('-',$select_anzeige);     $sel= $y[2].'.'.$y[1].'.'.$y[0]; 
											if($y[1]==01) {$monat_select="Januar";	}
											if($y[1]==02) {$monat_select="Februar";	}				
											if($y[1]==03) {$monat_select="M&auml;rz";	}				
											if($y[1]==04) {$monat_select="April";	}				
											if($y[1]==05) {$monat_select="Mai";	}				
											if($y[1]==06) {$monat_select="Juni";	}				
											if($y[1]==07) {$monat_select="Juli";	}				
											if($y[1]==8) {$monat_select="August";	}				
											if($y[1]==9) {$monat_select="September";	}				
											if($y[1]==10) {$monat_select="Oktober";	}				
											if($y[1]==11) {$monat_select="November";	}				
											if($y[1]==12) {$monat_select="Dezember";	}				
											if($change_month!=$y[1]) {
												$change_month=$y[1]; 
												$month_changed=1;
											} 
											else {
												$month_changed=0;
											}
											if($month_changed==1) {
		 										echo'</optgroup>';
												echo'<optgroup label="';echo $monat_select;echo '">'; 
												if($sel==$value_date) {
													echo '<option class="fett">(' .$sel.') '. htmlentities($rowda[0]).'</option>';
												}  
												else {
 													echo '<option class="option">(' .$sel.') '. htmlentities($rowda[0]).'</option>';
												}
											}
											else { 
												if($sel==$value_date) {
 													echo '<option class="fett">(' .$sel.') '. htmlentities($rowda[0]).'</option>';
												} 
												else {
 													echo '<option class="option">(' .$sel.') '. htmlentities($rowda[0]).'</option>';
												}
											} 			
 		 								}
 		 							}
 		 						}
   						}
						?>        
      			</select>
      			<input type="file" name="datei" accept="image/*" multiple onChange="fileThumbnail(this.files);">
					<input type="hidden" name="meldung" value="1"> 
					<input type="password" required type="password" class="input" name="pw" placeholder="Passwort"> 
					<br>
					<br>
      			<input class="btn btn-primary" value="Bild Hochladen" type="submit"> 
      		</form>    
     		</div>
   		<div class="tn" id="thumbnail">
   		</div>   
      </div>
   </div>
	<!-- Site footer -->
	<footer class="footer">
       <p style="color:white;">Version <?php include 'includes/version.php';?>  - erstellt mit Bluefish und Bootstrap - umgesetzt von Dominik Eichler und Alwin Ebermann</p>
       <p class="text-muted"><a  href="https://ldkf.de//de/impressum.html" target="_blank">Impressum</a> - <a target="_blank" href="https://ldkf.de//de/datenschutzerklaerung.html" >Datenschutz</a> - <a href="Information.php" target="_blank">&Uuml;ber diese Seite</a></p>  
	</footer>
   <script type="text/javascript" src="js/jquery-1.11.2.min.js"></script>
   <script type="text/javascript" src="js/bootstrap.min.js"></script>
   <script type="text/javascript" src="js/main.js"></script>
	<!-- /container -->
  </body>
</html>
