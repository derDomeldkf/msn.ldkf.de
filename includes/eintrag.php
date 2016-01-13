<?php 
	include("includes/db_connect.php");
	include 'includes/datum.php';  
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
	$id ="";
	$dat=""; 
	//$_FILES['datei']['name']!=0 and
	if(isset($_FILES['datei']['name']) and  $_POST['Essenname']!=1 && isset($_POST['pw']) && $_POST['pw']=="baum"){
		$dat = $_FILES['datei']['name']; 
 		$size=getimagesize($_FILES['datei']['tmp_name']); 
	} 
	if($dat!="" && $size[2]!=0 ) { 
		if( isset ($_GET['n'])) {
 			$n=  $_GET['n'];
			if($n==1) {$pathname = $pfad1;} 
			else { 
				if($n==2) {$pathname = $pfad2;}
				else {
					if($n==3) {$pathname = $pfad3;}	
					else {
						if($n==4) {$pathname = $pfad4;}
					}
				}
			}
		}	
		else {
			$essenname = explode(' ',$_POST['Essenname'],2 );
			$pathname =  $essenname[1];
		}
		$resultn = mysql_query("SELECT id, mn FROM essen WHERE name LIKE '$pathname' "); 
    	while($rown = mysql_fetch_row($resultn)){
			$id = $rown[0];  
			$mn = $rown[1];  
		}
		if (is_dir("pictures/".$id) == false)  {
			mkdir (  "pictures/".$id."/", 0777);
		}
		$day_upl = date("d"); 
		$month_upl = date("m");
		$year_upl = date("Y");
		$picture_upload_date="$year_upl-$month_upl-$day_upl";
		$eintrag = "INSERT INTO bilder (datum, pfad, mn) VALUES ('$picture_upload_date','$pathname','$mn')"; 
    	$eintragen = mysql_query($eintrag); 
		$erg = ""; 
    	$resultbi = mysql_query("SELECT id FROM bilder WHERE pfad LIKE '$pathname' "); 
     	while($rowbi = mysql_fetch_row($resultbi))
     	$t1=$rowbi[0];
		$endg = $_FILES['datei']['type']; 
		$type = explode('/',$endg); 
		$erg = $t1.'.'.$type[1]; 
		move_uploaded_file($_FILES['datei']['tmp_name'], "pictures/$id/$erg"); 
		$x='pictures/'.$id;
		$bild=$erg;
		if (is_dir($x) == true){
			if(is_dir($x.'/neu') == false){
				mkdir ( $x.'/neu/', 0777);
			}
			$PicPathIn=$x.'/'; 
   		$PicPathOut=$x.'/neu/'; 
			$size=getimagesize("$PicPathIn"."$bild"); 
  			$breite=$size[0]; 
  			$hoehe=$size[1]; 
  			$neueBreite=600; 
			$neueHoehe=intval($hoehe*$neueBreite/$breite); 
			if($size[2]==2) { 
  				// JPG 
  				$altesBild=ImageCreateFromJPEG("$PicPathIn"."$bild"); 
  				$neuesBild=imagecreatetruecolor($neueBreite,$neueHoehe); 
  				imagecopyresized($neuesBild,$altesBild,0,0,0,0,$neueBreite,$neueHoehe,$breite,$hoehe); 
  				$quality=100;   
  				ImageJPEG($neuesBild,"$PicPathOut"."$bild",$quality); 
			} 
			if($size[2]==3) { 
  				// PNG 
  				$altesBild=ImageCreateFromPNG("$PicPathIn"."$bild"); 
  				$neuesBild=imagecreatetruecolor($neueBreite,$neueHoehe); 
 				imagecopyresized($neuesBild,$altesBild,0,0,0,0,$neueBreite,$neueHoehe,$breite,$hoehe); 
  				$quality=100;   
  				ImagePNG($neuesBild,"$PicPathOut"."$bild",$quality); 
  			} 
		}
		if(isset($_GET['datum'])) {	
			$d_back=$_GET['datum'];
			header('Location: index.php?date_back='.$d_back);
		}
	}   
?>
