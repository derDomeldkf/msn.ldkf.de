
 $(document).ready(function(){
   $(".number").change(function() {
     var formfield=this;
     $(".number").attr("disabled", "disabled");   //Felder temporär deaktivieren
     imageurl=$(formfield).parent().find('img[class="mealimg"]').attr("src");
     fieldname=$(this).next().attr("name");      //der Name des hidden-Felds
     $(this).next().attr("name", "mealinfo");    //entsprechendes hidden Feld in mealinfo umbenennen - ändern
     var part=$(this).next().val().split("_");       
     val=part[0]+"_"+part[1]+"_"+$(this).val();  //die neue Anzahl ins value vom hidden Feld schreiben
     $(this).next().val(val);                    //hier dann eintragen
     $.post("ajax_scripts/order.php", $('input[name="mealinfo"]').serialize()).done(function(data) {
       data2=$.parseJSON(data);
       if(data2[0]===1) {  //error
	 //alert("Bestellung konnte nicht gespeichert werden. Bitte gib keine Anzahlen gr&ouml;er als 1 ein. Dieses Feature kommt sp&auml;ter. Funktioniert es dann immer noch nicht, versuche dich neu einzuloggen");
	 $(formfield).parent().find('img[class="mealimg"]').hide();
	 $(formfield).parent().find('img[class="mealimg"]').attr("src", "images/cross.png").fadeIn(1000);
	 $(formfield).next().val(part[0]+"_"+part[1]+"_"+part[2]); //Anzahl zurücksetzen im hidden Feld
	 $(formfield).val(part[2]);  //Auch Textfeld auf alte Anzahl zurücksetzen
	 
       }
       else {
	 $(formfield).parent().find('img[class="mealimg"]').hide();
	 $(formfield).parent().find('img[class="mealimg"]').attr("src", "images/tick.png").fadeIn(1000);
	 if(data2[1]>=1) {
	   $(formfield).parent().addClass("tdborder");
	   $(formfield).parent().removeClass("nonborder");
	 }
	 else {
	   $(formfield).parent().removeClass("tdborder");
	   $(formfield).parent().addClass("nonborder");
	 }
       }
     });
     $(this).next().attr("name",fieldname);  //alten Namen wiederherstellen
     setTimeout(function(){
       $(".number").removeAttr("disabled");    //Alle Felder wieder aktivieren
       $(".outdated").attr("disabled", "disabled"); //vorherige Tage natürlich wieder deaktivieren
       if(data2[1]>0) {
	 $(formfield).parent().parent().parent().find('input').attr("disabled", "disabled");
	 $(formfield).removeAttr("disabled");
       }
       $(formfield).parent().find('img[class="mealimg"]').hide();
       $(formfield).parent().find('img[class="mealimg"]').attr("src", imageurl).fadeIn(1000);
     }, 3000);
     
   });
   
 }); 
 
 function nav_hide () {
   document.getElementById("navigation").style.display = "none";
   document.getElementById("comeback").style.display = "block";
   
   
 }   
 function nav_show() {
   document.getElementById("navigation").style.display =	"block"; 
   document.getElementById("comeback").style.display = "none";
 }
 
 
 
 function chkFormular () {
   if (document.reg.password.value != document.reg.password_wd.value)  {
     document.getElementById("pwcheck").style.display = "inline";
     setTimeout(show, 2000);
     
     document.reg.password_wd.value=""; 
     document.reg.passwordwd.focus();
     return false;
   }
   
 }   
 function show() {
   document.getElementById("pwcheck").style.display = "none";
 } 
 
 function chkFormular_1 () {
   if (document.eintr.Essenname.value == "Keine Auswahl"){
     alert("Es wurde noch kein Essen ausgew\u00e4hlt");
     document.eintr.Essenname.focus();
     return false;
   }
   
   if (document.eintr.datei.value == ""){
     alert("Es wurde noch kein Bild ausgew\u00e4hlt.");
     document.eintr.datei.focus();
     return false;
   }
 
    
 }			
 function fileThumbnail(files){
   var thumb = document.getElementById("thumbnail");
   thumb.innerHTML = "";
   if(!files)
     return;
   for(var i = 0; i < files.length; i++){
     var file = files[i];
     if(!file.type.match(/image.*/))
       continue;
     var img = document.createElement("img");
     var reader = new FileReader();
     reader.onload = (function(tImg) {
       return function(e) {
	 tImg.src = e.target.result;
       };
     })(img);
     reader.readAsDataURL(file);
     img.width = 400; 
     thumb.appendChild(img);
   }
 }

  