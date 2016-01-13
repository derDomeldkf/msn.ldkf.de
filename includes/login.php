<?php
session_start();
include("db_connect.php");
if(isset($_POST['acname']) and isset($_POST['pw']) and $_POST['pw']!="" and $_POST['acname']!="") {
	$uname=$_POST['acname'];
	$get_pw = mysql_query("SELECT pw FROM `group` where username LIKE '$uname'"); 
	$rowpw= mysql_fetch_row($get_pw);
	$cpw = $rowpw[0];
	if(isset($cpw) and md5($_POST['pw']) == $cpw) { //hier entschlüsseln
		$_SESSION['ldkf-id']=1;
		$get_kd = mysql_query("SELECT kdnrv0, kdnrv1, kdnrv2  FROM `group` where username LIKE '$uname'"); 
		$rowkd= mysql_fetch_row($get_kd);
		$iv_size_k = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_ECB); 
		$iv_k = mcrypt_create_iv($iv_size_k, MCRYPT_RAND); 
		if(isset($rowkd[0]) and $rowkd[0]!="") {
			$kd[0]=mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $cpw, $rowkd[0], MCRYPT_MODE_ECB, $iv_k);
			$kd[0]=substr($kd[0], 0, 8);
			if(isset($rowkd[1])and $rowkd[1]!="") {
				$kd[1]=mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $cpw, $rowkd[1], MCRYPT_MODE_ECB, $iv_k);
				$kd[1]=substr($kd[1], 0, 8);
				if(isset($rowkd[2])and $rowkd[2]!="") {
					$kd[2]=mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $cpw, $rowkd[2], MCRYPT_MODE_ECB, $iv_k);
					$kd[2]=substr($kd[2], 0, 8);
				}
			}
		}
		$i=0;
		foreach($kd as $kdnr){
			if($kdnr!="") {
				$get_geh = mysql_query("SELECT geheim".$i."  FROM `group` where username LIKE '$uname'"); 
				$rowgeh= mysql_fetch_row($get_geh);
				$geh = $rowgeh[0];
				$iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_ECB); 
				$iv = mcrypt_create_iv($iv_size, MCRYPT_RAND); 
				$pw=mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $cpw, $geh, MCRYPT_MODE_ECB, $iv);
				$pw=substr($pw, 0, 4); 
				$usr=$kdnr;
    			exec("python ../scripts/login.py $usr $pw", $stdout);
    			$session_a=json_decode($stdout[0]);
    			$session=$session_a[0];
    			if(isset($session) and $session!="") {
    				$_SESSION['idldkf'][$i]=$session;
       			if($stdout[0]==0 and !isset($_SESSION['idldkf'][$i]) and (!isset($_SESSION['multi']) or $_SESSION['multi']!=1 ))  {
       				$_SESSION['multi']=0;
       			}
       			else {
       				$_SESSION['multi']=1;
       			}
       			$i++;
       		}
       	} 
		}	

		header('Location: ../group.php');
	}
	else {
		$_SESSION['error']=1;		
		header('Location: ../group.php'); 	

	} 
}
else {
	$_SESSION['error']=1;
	header('Location: ../group.php'); 	

}
?>