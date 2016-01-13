<?php
session_start();
  include("db_connect.php");
if(isset($_POST['username']) and isset($_POST['password']) and isset($_POST['password_wd']) and $_POST['password_wd']==$_POST['password'] and isset($_POST['msn']) and isset($_POST['msnpw'])) {
	$uname=$_POST['username'];
	$pw=$_POST['password'];
	$kdnrv=$_POST['msn'];
	$geheim=$_POST['msnpw'];
	$i=0;
	$test = mysql_query("SELECT id FROM `group` WHERE username LIKE '$uname' "); 
	$rowmn = mysql_fetch_row($test);
	$test_name = $rowmn[0];
	if(!isset($test_name)) {
		$cpw=md5($pw); //hier verschlüsseln
 		$eintrag = "INSERT INTO `group` ( username, pw) VALUES ('$uname', '$cpw') "; 
		$eintragen = mysql_query($eintrag);
		$id = mysql_query("SELECT id FROM `group` WHERE username LIKE '$uname' "); 
		$rowid = mysql_fetch_row($id);
		$got_id = $rowid[0];
		$iv_size_kd = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_ECB); 
		$iv_kd = mcrypt_create_iv($iv_size_kd, MCRYPT_RAND); 
		foreach($kdnrv as $user) {
			if($user!="") {
				$iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_ECB); 
				$iv = mcrypt_create_iv($iv_size, MCRYPT_RAND); 
				$encrypted = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $cpw, $geheim[$i], MCRYPT_MODE_ECB, $iv);  //alternativ schlüsselwort..hier eben userpw
				$encrypted_kd = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $cpw, $user, MCRYPT_MODE_ECB, $iv_kd);
				$kdnrv2="kdnrv".$i;
				$geheim2="geheim".$i;
				$eintrag = "Update `group` SET ".$kdnrv2." = '$encrypted_kd', ".$geheim2." = '$encrypted' where id LIKE '$got_id'"; 
				$eintragen = mysql_query($eintrag); 
				$i++;	
			}	
		}
  		$number = "UPDATE `group` SET number = '$i' where id LIKE '$got_id'";
		$ins_nr= mysql_query($number);
		$_SESSION['reg']=2;
	}
	else {
		$_SESSION['reg']=1;	
	}
}
else {
	$_SESSION['reg']=3;
}
header('Location: ../group.php'); 

?>