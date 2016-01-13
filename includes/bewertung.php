<?php
  include("db_connect.php");
$essen_bew="";
if(isset($_POST['bew_a']) and !isset($_COOKIE[$_GET['bew']])) 
	{ 
 	$essen_bew = $_POST['bew_a'];
	$name_essen_bew=$_GET['bew'];
	}

if(isset($_POST['bew_b']) and !isset($_COOKIE[$_GET['bew']])) { 
 	$essen_bew = $_POST['bew_b'];
	$name_essen_bew=$_GET['bew'];
}
		
if(isset($_POST['bew_c'] ) and !isset($_COOKIE[$_GET['bew']])) { 
	$essen_bew = $_POST['bew_c'];
	$name_essen_bew=$_GET['bew'];
}
			
if(isset($_POST['bew_d'] ) and !isset($_COOKIE[$_GET['bew']])) { 
	$essen_bew = $_POST['bew_d'];
	$name_essen_bew=$_GET['bew'];
} 	
					
				
		
if($essen_bew!="" and $essen_bew!="-") {

$anz_bew = mysql_query("SELECT anzahl FROM bewertung WHERE id LIKE '$name_essen_bew'");
while($row_anz_bew = mysql_fetch_row($anz_bew))
$anz_bew_out = $row_anz_bew[0];
if(isset($anz_bew_out)and $anz_bew_out!=0) {
$upd_anz= $anz_bew_out + 1;
$update = "UPDATE bewertung SET anzahl = '$upd_anz' WHERE  id LIKE '$name_essen_bew' "; 
$update_bew_anz = mysql_query($update); 
$bew = mysql_query("SELECT bew FROM bewertung WHERE id LIKE '$name_essen_bew'");
while($row_bew = mysql_fetch_row($bew))
$bew_out = $row_bew[0];
$bew_new = ($bew_out + $essen_bew)/ 2;
 $update_bew = "UPDATE bewertung SET bew = '$bew_new' WHERE  id LIKE '$name_essen_bew' "; 
$update_bew = mysql_query($update_bew);  
	
} else {

$eintrag_id = "INSERT INTO bewertung (id, bew, anzahl) VALUES ('$name_essen_bew','$essen_bew','1')"; 
    $eintragen_id = mysql_query($eintrag_id); 
    if($eintragen_id == true) 
        { 
        } 
 else 
        { 
        } 

}
setcookie($name_essen_bew ,"true", time()+(3600*24), "/");
header('Location: ../?date_back='.$_GET['datum'].'#row'); 
} 

 
?>