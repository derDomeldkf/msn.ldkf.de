<?php

session_start();

?>

<html lang="de">
  <head>
  		<link rel="icon" href="favicon.png">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta http-equiv="content-type" content="text/html; charset=UTF-8" >    
    <title>msn.ldkf.de - Bestellseite</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/main.css" rel="stylesheet">
    <link href="css/lightbox.css" rel="stylesheet">
   
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

            <a class="navbar-brand" href="https://ldkf.de">LDKF.de</a>
            </div>
        <!-- Collect the nav links, forms, and other content for toggling 
                collapse navbar-collapse                
        -->
        <div class="" id="navbar" style="vertical-align:middle;">
            <ul class="nav navbar-nav" >
                <li><a href="./">Startseite</a></li>
                <li><a href="upload.php">Uploader</a></li>            
<?php
     if(!isset($_SESSION['id'])) {
     	echo '</ul>
      <ul class="nav navbar-nav navbar-right">
        <form method="post" class="navbar-form navbar-left" action="index.php" >
        <div class="form-group">
          <input autocomplete="off" type="text" name="kdnrv" class="form-control" placeholder="Kundennummer">
          <input autocomplete="off" type="password" name="geheim" class="form-control" placeholder="Geheimzahl">

        <button type="submit" class="btn btn-primary">Einloggen</button>        </div>
      </form>
        
      </ul>';
      } else {
echo '
		<li><a href="intern.php">Bestellseite</a></li>
		</ul>

<ul class="nav navbar-nav navbar-right" style="vertical-align:middle;"><form class="navbar-form navbar-left">
   <a href="/intern.php?l=1" class="btn btn-primary " style="margin-left:20px;">Ausloggen</a>		
     </form></ul>

      ';      
      }



?>
        </div><!-- /.navbar-collapse -->
    </nav>
<div class="container" id="main">
    <div class="jumbotron heading">
        <h1>Warum bei uns bestellen?</h1>
        <p class="lead">Es gibt jede Menge Gr&uuml;nde, auf unserer Seite zu bestellen.</p>
    </div>
    <div id="all" class="container">
        <div id="meals">
            <div class="row day infobox">
                <h1>Bewertungen</h1>
                <p>
                    Jeder kann &uuml;ber unsere Website die aktuellen Gerichte bewerten - ganz ohne umst&auml;ndliche Anmeldung. So ist direkt bei der Essenbestellung ersichtlich, wie beliebt das jeweilige
                    Essen ist.
                </p>
            </div>
            <div class="row day infobox">
                <h1>Bilder</h1>
                <p>
                    F&uuml;r absolute Transparenz bieten wir jedem Besucher an, authentische Bilder der Essen hochzuladen. So ist schon fast zu jedem Essen mindestens ein Bild verf&uuml;gbar und man wei&szlig;
                    direkt, was sich hinter dem gro&szlig;artigen Namen verbirgt.
                </p>
            </div>
            <div class="row day infobox">
                <h1>Modernste Webtechnologien</h1>
                <p>
                    Wir geben uns nicht mit HTML und CSS ab. Wir nutzen neueste Webtechnologien wie jQuery, Javascript und Ajax, um eine komfortabel und einfach nutzbare Weboberfl&auml;che zu schaffen. Trotzdem 
                    vergessen wir auch &auml;ltere Ger&auml;te und Browser nicht. Unsere Website ist kompatibel mit allen Browsern und Ger&auml;ten, also auch Smartphones und Tablets.
                </p>
            </div>
            <div class="row day infobox">
                <h1>Sichere &Uuml;bertragung</h1>
                <p>
                    Sie rufen unsere Website immer automatisch verschl&uuml;sselt auf. Achten Sie auf https und das Schloss in der Adresszeile. Nur so sind ihre Bestellungen sicher vor gef&auml;hrlichen
                    Man-in-the-Middle-Angriffen, sicher! Heutzutage ist dies eigentlich selbstverständlich, trotzdem unternimmt der Men&uuml;service selbst nichts, um die sensiblen Kundendaten zu sch&uuml;tzen.
                    Bitte bedenken Sie, dass die &Uuml;bertragung Ihrer Daten von unserem Server zum Server vom Men&uuml;service unverschl&uuml;sselt erfolgen muss. Da Man-in-the-Middle-Angriffe jedoch fast immer in 
                    lokalen Netzwerken stattfinden, ist die Bestellung &uuml;ber unsere Seite trotzdem deutlich sicherer.
                </p>
            </div>
            <div class="row day infobox">
                <h1>Automatisches Speichern</h1>
                <p>
                    Das Einf&uuml;gen in den Warenkorb und Bestellen am Ende ist ein langer Vorgang. Auf unserer Seite wird die Bestellung direkt abgeschickt, sobald ein Textfeld verlassen wird (sofern der Browser dies
                    unterst&uuml;tzt). Die erfolgreiche Bestellung wird direkt signalisiert, das &Auml;ndern ist jederzeit m&ouml;glich, fehlerhaftes Bestellen ist also nicht problematisch.
                </p>
            </div>
            <div class="row day infobox">
                <h1>Regelm&auml;&szlig;ig neue Funktionen</h1>
                <p>
                    Die Internetseite des Essenanbieters hat seit 12 Jahren keine Aktualisierung erfahren. Soetwas gibt's bei uns nicht. Wir arbeiten stetig daran, unser Angebot zu verbessern. Folgende Funktionen sind
                    f&uuml;r die Zukunft geplant:
                </p>
                <h2>E-Mail-Benachrichtigung</h2>
                <p>
                    Wir planen eine Funktion zu integrieren, die Sie zu verschiedenen Ereignissen benachrichtigen kann. Beispielsweise
                </p>
                <ul>
                    <li>Wenn f&uuml;r einen neuen Zeitraum bestellt werden kann</li>
                    <li>Wenn an einem Tag kein Essen bestellt wurde</li>
                    <li>Jeden Tag das bestellte Essen</li>
                </ul>
                <h2>Multi-Accounts</h2>
                <p>
                   Die Anmeldung mit nicht ver&auml;nderbarer, vierstelliger PIN ist nicht sehr sicher. Wir planen ein neues Authentifikationssystem zu implementieren, &uuml;ber welches beliebige Nutzernamen
                   und beliebige und beliebig lange Passw&ouml;rter gew&auml;lt werden k&ouml;nnen. Dabei soll auch die Zusammenfassung mehrer Anmeldungen beim Men&uuml;service zu einem Benutzernamen m&ouml;glich
                   sein.
                </p>
            </div>
        </div>
    </div>
</div>
<footer class="footer">
       <p style="color:white;">Version <?php include 'includes/version.php';?>  - erstellt mit Bluefish und Bootstrap - umgesetzt von Dominik Eichler und Alwin Ebermann</p>
              <p class="text-muted"> <a  href="https://ldkf.de//de/impressum.html" target="_blank">Impressum</a> - <a target="_blank" href="https://ldkf.de//de/datenschutzerklaerung.html" >Datenschutz</a> - <a href="Information.php" target="_blank">&Uuml;ber diese Seite</a>      </p>  
</footer>
      <script type="text/javascript" src="js/jquery-1.11.2.min.js"></script>
      <script type="text/javascript" src="js/lightbox.min.js"></script>
      <script type="text/javascript" src="js/bootstrap.min.js"></script>
      <script type="text/javascript" src="js/main.js"></script>
     <!-- /container -->  
  </body>
</html>