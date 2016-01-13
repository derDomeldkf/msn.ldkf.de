<?php
session_start();
$error=0;

if(isset($_POST)) {
    if(isset($_SESSION["id"])) {
        $sessid=$_SESSION["id"];
        $meal = explode('_',$_POST["mealinfo"]);
        if(count($meal)==3 && strlen($meal[0])==12 && is_numeric($meal[0]) && is_numeric($meal[2])) {
            exec("python ../scripts/selectmenu.py $sessid $meal[1] $meal[0] $meal[2]", $out);
            exec("python ../scripts/checkout.py $sessid", $out2);
            $out=json_decode($out[0]);
            if($out==0 || $out2[0]==0) {
                $error=1;
            }
        }
        else {
            $error=1;
        }
    }
    else {
        $error=1;
    }
}
if($error==1) {
    $return=array(1, 0);
    print json_encode($return);
}
else {
    $return=array(0, $meal[2]);
    print(json_encode($return));
}