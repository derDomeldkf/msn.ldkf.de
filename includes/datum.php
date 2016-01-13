 <?php 
	
$pfada = "";
$pfadb = "";
$pfadc = "";
$pfadd = "";
$allebilder ="";
$pathname ="";
$id ="";

		$day_week = date("D");
		$day_next = date("d"); 
		$month = date("m");
		$year = date("Y");
		
 $result_max = mysql_query("SELECT MAX(datum) FROM datum");
    while($row_max = mysql_fetch_row($result_max))
	$max_dat = $row_max[0];		
	
	$max_a = explode('-',$max_dat);	
	 $max_y=$max_a[0];
	 $max_m=$max_a[1];
	 $max_d=$max_a[2];	
	
 $result_min = mysql_query("SELECT MIN(datum) FROM datum");
    while($row_min = mysql_fetch_row($result_min))
	$min_dat = $row_min[0];	
			
	$min_a = explode('-',$min_dat);	
	$min_y=$min_a[0];
	$min_m=$min_a[1];
	$min_d=$min_a[2];
		 
		 if( isset($_GET['date_back']) ) {
		 $datum=$_GET['date_back'];
		 }
		else { 
if((isset($_POST['tag']) and isset($_POST['monat']) and isset($_POST['jahr'])) ) {  

	$day_next = str_pad($_POST['tag'], 2 ,'0', STR_PAD_LEFT);
 	$month =  str_pad($_POST['monat'], 2 ,'0', STR_PAD_LEFT);
 	$year =  $_POST['jahr'];  $datum_e="$year-$month-$day_next"; 
 	$result_next = mysql_query("SELECT datum FROM datum WHERE datum LIKE '$datum_e' and bez LIKE 'a'");
   while($row_next = mysql_fetch_row($result_next)) 
$test_next=$row_next[0];
 	while(!isset($test_next)) { 
  
	if($year>$max_y or ($year==$max_y and $month>$max_m) or( $year==$max_y and $month==$max_m and $day_next>$max_d ) ) {
	$year=$min_y;
	$month=$min_m;
	$day_next=$min_d;
		}
    else {
if($month==12 and $day_next>30) {
	$day_next=str_pad(01  , 2 ,'0', STR_PAD_LEFT);
	$month=str_pad(01  , 2 ,'0', STR_PAD_LEFT);
	$year=str_pad($year+0001  , 4 ,'0', STR_PAD_LEFT);
}
else{
if($day_next> 28 and $month==str_pad(02  , 2 ,'0', STR_PAD_LEFT) ) {
$day_next=str_pad(01  , 2 ,'0', STR_PAD_LEFT);                           //geht auch wenn 29 schon nach 28 weiter
$month=str_pad($month+01 , 2 ,'0', STR_PAD_LEFT);	  
	  
  } else {
if($day_next> 31) {
$day_next=str_pad(01  , 2 ,'0', STR_PAD_LEFT);
$month=str_pad($month+01 , 2 ,'0', STR_PAD_LEFT);	  
    }  
    
  else {
    	$day_next=str_pad($day_next+01  , 2 ,'0', STR_PAD_LEFT);
    }
    }
}}
  	 $datum_e ="$year-$month-$day_next";
	 $result_next = mysql_query("SELECT datum FROM datum WHERE datum LIKE '$datum_e' and bez LIKE 'a'");
   while($row_next = mysql_fetch_row($result_next)) 
$test_next=$row_next[0];
    } 
 $datum = $test_next;
 
	$_POST['tag']="";
	$_POST['monat']="";
	$_POST['jahr']="";
   }  
  
  else {
  	
	 
  	 if(isset ($_GET['next'])) {

	$datum_f = $_GET['next'] ; 
 	$date_out = explode('-',$datum_f); 
	$day_next =  $date_out[2];
	$month_next =  $date_out[1];
	$year =  $date_out[0];
	$datum_db="";
while($datum_db=="") { 

if("$year-$month_next-$day_next"==$max_dat) {

	$year=$min_y;
	$month_next=$min_m;
	$day_next=$min_d;
	}
else {
if($day_next< 31) {$day_next=str_pad($day_next+01  , 2 ,'0', STR_PAD_LEFT) ;} 
else { if($month_next< 12) { 
$month_next=str_pad($month_next+01 , 2 ,'0', STR_PAD_LEFT);
$day_next=str_pad(01  , 2 ,'0', STR_PAD_LEFT);}

else {  

$month_next=str_pad($month_next=01 , 2 ,'0', STR_PAD_LEFT); 
$day_next=str_pad(01  , 2 ,'0', STR_PAD_LEFT); 
$year=str_pad($year+0001  , 4 ,'0', STR_PAD_LEFT);
 
} }    }           
	$datum_s ="$year-$month_next-$day_next";  
   $result_next = mysql_query("SELECT datum FROM datum WHERE datum LIKE '$datum_s' and bez LIKE 'a'");
   while($row_next = mysql_fetch_row($result_next))
	$datum_db = $row_next[0]; } $datum = $datum_db; 
    }
  
  
	else 

if(isset ($_GET['prev'])) { 
  
	$datum_f = $_GET['prev'] ; 
 	$date_out = explode('-',$datum_f); 
 	$day_next =  $date_out[2];
 	$month_next =  $date_out[1];
 	$year =  $date_out[0]; 
 	$datum_db="";
while($datum_db=="") { 

	if($year<$min_y or ($year==$min_y and $month_next<$min_m) or ( $year==$min_y and $month_next==$min_m and $day_next<$min_d ) ) {
	 $year=$max_y;
	 $month_next=$max_m;
	 $day_next=$max_d; 

} else{


if($day_next > 01) {$day_next=str_pad($day_next-01  , 2 ,'0', STR_PAD_LEFT) ;} 
else { 
if($month_next > 01) {  

$month_next=str_pad($month_next-01 , 2 ,'0', STR_PAD_LEFT); 
$day_next=str_pad(31  , 2 ,'0', STR_PAD_LEFT); 


}   
else {  $month_next=str_pad($month_next=12 , 2 ,'0', STR_PAD_LEFT); 
$day_next=str_pad(31  , 2 ,'0', STR_PAD_LEFT); 
$year=str_pad($year-0001  , 4 ,'0', STR_PAD_LEFT); }  

 }}
	$datum_s ="$year-$month_next-$day_next";  
   $result_next = mysql_query("SELECT datum FROM datum WHERE datum LIKE '$datum_s' and bez LIKE 'a'");
   while($row_next = mysql_fetch_row($result_next))
	$datum_db = $row_next[0];}  $datum = $datum_db; 
  }
  
  	
  	else{
if(($day_week=="Sat") or ($day_week=="Sun")){
	$datum_db=""; while($datum_db=="") { 
		if($year>$max_y or ($year==$max_y and $month>$max_m) or( $year==$max_y and $month==$max_m and $day_next>$max_d ) ) {
	$year=$min_y;
	$month=$min_m;
	$day_next=$min_d;

		} else {
	if($day_next<=31) {  
	$day_next=str_pad($day_next+01  , 2 ,'0', STR_PAD_LEFT) ;} else { if($month<=12) {
	$month=str_pad($month+01 , 2 ,'0', STR_PAD_LEFT); $day_next=str_pad(01  , 2 ,'0', STR_PAD_LEFT);}else { 
	$month=str_pad($month=01 , 2 ,'0', STR_PAD_LEFT); $day_next=str_pad(01  , 2 ,'0', STR_PAD_LEFT); $year=str_pad($year+0001  , 4 ,'0', STR_PAD_LEFT);} }      
}
	$datum_s ="$year-$month-$day_next";  
   $result_next = mysql_query("SELECT datum FROM datum WHERE datum LIKE '$datum_s' and bez LIKE 'a'");
   while($row_next = mysql_fetch_row($result_next))
	$datum_db = $row_next[0];} $datum = $datum_db; }



else {
while(!isset($datum_db)) {		
	$datum = "$year-$month-$day_next"; 
	$result_case = mysql_query("SELECT datum FROM datum WHERE datum LIKE '$datum' and bez LIKE 'a'");
   while($row_case = mysql_fetch_row($result_case))
	$datum_db = $row_case[0];

	if($year>$max_y or ($year==$max_y and $month>$max_m) or( $year==$max_y and $month==$max_m and $day_next>$max_d ) ) {

	$year=$min_y;
	$month=$min_m;
	$day_next=$min_d;
	}
		else {
	if($day_next==31 and $month< 12) {
	$day_next= str_pad(01, 2 ,'0', STR_PAD_LEFT) ;          
	$month= str_pad( $month+01 , 2 ,'0', STR_PAD_LEFT) ; 
	}else{
	
if($month==12 and $day_next==31) {


	$month= str_pad( $month=01 , 2 ,'0', STR_PAD_LEFT) ; 
		$day_next= str_pad(01, 2 ,'0', STR_PAD_LEFT) ;
		 $year=str_pad($year+0001  , 4 ,'0', STR_PAD_LEFT); 
	         
} 

else {
	
	$day_next= str_pad( $day_next+01 , 2 ,'0', STR_PAD_LEFT) ;     }     
	}
	}
	
	}
	$datum=$datum_db;
}
$_POST['tag']="";
$_POST['monat']="";
$_POST['jahr']="";
if( isset ($_GET['datum']) and !isset ($_POST['datum'])  ) {$datum = $_GET['datum']; 

}
else {


 }
 
 
 }}}
	$result_tagn = mysql_query("SELECT tag FROM datum WHERE datum LIKE '$datum' and bez LIKE 'a'");
   while($meal_row = mysql_fetch_row($result_tagn))

if($meal_row[0]=="Montag" or $meal_row[0]=="Dienstag" or $meal_row[0]=="Mittwoch" or $meal_row[0]=="Donnerstag" or $meal_row[0]=="Freitag"   ) {
$datum_anz=$meal_row[0];

}
else {

if($meal_row[0]=="Mo") {$datum_anz="Montag";}
if($meal_row[0]=="Di") {$datum_anz="Dienstag";}
if($meal_row[0]=="Mi") {$datum_anz="Mittwoch";}
if($meal_row[0]=="Do") {$datum_anz="Donnerstag";}
if($meal_row[0]=="Fr") {$datum_anz="Freitag";}
}

$resulta = mysql_query("SELECT id FROM datum WHERE datum LIKE '$datum' and bez LIKE 'a' ");
while($rowa = mysql_fetch_row($resulta))
$pfada = $rowa[0]; 
$resultb = mysql_query("SELECT id FROM datum WHERE datum LIKE '$datum' and bez LIKE 'b' ");
while($rowb = mysql_fetch_row($resultb))
$pfadb = $rowb[0]; 
$resultc = mysql_query("SELECT id FROM datum WHERE datum LIKE '$datum' and bez LIKE 'c' ");
while($rowc = mysql_fetch_row($resultc))
$pfadc = $rowc[0]; 
$resultd = mysql_query("SELECT id FROM datum WHERE datum LIKE '$datum' and bez LIKE 'd' ");
while($rowd = mysql_fetch_row($resultd))
$pfadd = $rowd[0]; 	

	?> 