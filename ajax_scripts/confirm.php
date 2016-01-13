 <?php 
 $errors=0;
 session_start();
 if(!isset($_SESSION['id'])) {$errors=0;}
    if(isset($_SESSION['id'])) {
    $ssid=$_SESSION['id'];
        exec("python ../scripts/checkout.py $ssid", $osut);
        $_SESSION['bestellung'] = 1;
		  $errors=1;	    
    }
echo $errors;
?>
  