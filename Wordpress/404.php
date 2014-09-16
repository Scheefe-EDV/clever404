<?php
/*
 * clever404 — Wordpress-Edition
 * 
 *
 * PHP version 4 and 5
 *
 * @package    clever404
 * @category   Theme-AddOn
 * @author     Bastian Scheefe <info@scheefe-edv.de>
 * @license    tbd
 * @version    1.2
 * @link       htt://www.bastianoso.de/clever404.html
 * @deprecated 
 * @todo       
 * -----------------------------------
 * AMENDMENT HISTORY
 *
 * editDate: 2014-09
 * desc: 	 Erstbefüllung der Google-Suchfunktionen aus URL
 * 
 * initDate: 2014-03
 * -----------------------------------
 * CONFIGURATION:
*/
// Liste der Suchmaschinen
$searchEngingeList=array("google.com", "bing.com", "altavista.com", "google.de", "bing.de");

// Für welche Webseiten soll die E-Mail-Funktion aktiviert sein? (Format: "www.personenname.de","www.bastianoso.de")
$mailWhitelist= array();

// E-Mail-Adresse des Empfängers "mail@personnename.de" (inkl. Anführungszeichen)
$empfaenger = "";
// Name des Absenders "mail@personnename.de" (inkl. Anführungszeichen)
$absendername = "";
// E-Mail-Adresse des Absenders "404@personnename.de" (inkl. Anführungszeichen)
$absendermail = "";

/*
 * NO EDITING BEYOND THIS POINT (unless you know what you do ;)
*/

// IP-Adresse vom Client auslesen
if (!isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
	$clientIp = $_SERVER['REMOTE_ADDR'];
} else {
	$clientIp = $_SERVER['HTTP_X_FORWARDED_FOR'];
}

// Referer auslesen und auseinandernehmen
if (isset($_SERVER['HTTP_REFERER'])) {
	$referer=$_SERVER['HTTP_REFERER'];
	$parse = parse_url($referer);
	$refererDomain=$parse['host'];
}

// aufgerufene URL
$url="http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
// aufgerufene URL für die Suche aufbereiten
$urlSearchParts=pathinfo($_SERVER[REQUEST_URI]);
$urlSearch=$urlSearchParts['dirname'].' '.$urlSearchParts['filename'];
$urlSearch=str_replace("-"," ",$urlSearch);
$urlSearch=str_replace("/","",$urlSearch);
if ($urlSearch == " ") {
	$urlSearch=$urlSearchParts['dirname'].' '.$urlSearchParts['basename'];
	$urlSearch=str_replace("-"," ",$urlSearch);
	$urlSearch=str_replace("/","",$urlSearch);
}


// Serverdaten auslesen
$serverIp=$_SERVER['SERVER_ADDR'];
$serverUrl=$_SERVER['SERVER_NAME'];

// Request-ID berechnen
$requestId=mt_rand()."@".$serverIp;

// Fall für den Fall eines Fehlers der Fälle
$case=0;
// Die vier Fälle aufbauen
$mail=false;
if (!isset($referer)) {
	// Vertipper oder alter Bookmark
	$case=1;
} else {
	if (strpos($referer, $serverUrl)) {
		// Link auf eigener Seite ist falsch —> Überprüfung ob Hackingversuch
		if ($referer==$url) {
			// Der Referer ist identisch mit der aufgerufenen Seite. Dies deutet auf einen Bot hin.
			$case=5;
		} else {
			// Weitere Prüfung auf übliche Bot-Abfragen:
			if ((strpos($url, "RK=0")) or (strpos($url, "author="))) {
			  $case=5;
			} else {
			  // Scheinbar keine Bots, sondern ein Mensch. Also E-Mail rausschicken
			  $case=2;
			  $mail=true;
			}
		}
	} else {
		if (in_array($refererDomain, $searchEngineList)) {
			// Besucher kommt über Suchmaschine
			$case=3;
		} else {
			// Besucher kommt über andere Webseite -> Mail
			$case=4;
			$mail=true;
		}
	}
}
$requestId.="/".$case;

// E-Mail-Funktionalitäten
// Inhalt der E-Mail:
$betreff = "Fehler 404 auf ".$serverUrl;
$text = "Folgende Seite konnte von ".$clientIp." auf ".$serverUrl." nicht gefunden werden:\r\n ".$url.". \r\n\r\n Der Referer des Aufrufs lautet: ".$referer;
if ($mail) {
	if (in_array($refererDomain, $mailWhitelist)) {
		mail($empfaenger, $betreff, $text, "From: $absendername <$absendermail>");
	}
 }
 
 // Suchformular bauen
 $searchForm='<input name="qfront" type="text" style="width: 300px; text-size: 12px; height: 24px;" value="'.$urlSearch.'" /><input type="image" src="data:image/png;base64,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" value="submit" alt="Suchen" style="border:none;" />';
?>
<!DOCTYPE html>
<head>
	<title>Fehler 404 — Seite nicht auf <?php echo $serverUrl; ?> gefunden</title>
	<meta charset="UTF-8" />
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1" />
	<meta name="robots" content="noindex, nofollow" />
	<meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1" />
	<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/404.css" type="text/css" media="screen,projection" />
	<meta name="DC.Language" content="de" />
	<meta name="DC.Type" content="Text" />
	<script type="text/javascript">
		var domainroot="<?php echo $serverUrl; ?>"
		function Gsitesearch(curobj){
			curobj.q.value="site:"+domainroot+" "+curobj.qfront.value
		}
	</script>
	<?php wp_head(); ?>
</head>
<body>
  <div id="wrapper">
    <div id="details" class="details-wrapper">
      <div class="wrapper heading overview">
        <h1>Fehler 404<small style="font-size:15px;white-space:nowrap">Request-ID: <?php echo $requestId; ?></small></h1>
        <h2 class="subhead">Seite nicht gefunden &#9785;</h2>
      </div>

      <section></section>

      <div class="section wrapper">
        <div class="columns two">
          <div class="column">
            <h2>Was ist passiert?</h2>
            <p>Die von Ihnen aufgerufene Seite<br /><em><?php echo $url; ?></em><br />konnte nicht gefunden werden.</p>
            <p>Wahrscheinliche Ursache(n):</p>
            <br />
			<form action="http://www.google.com/search" method="get" onSubmit="Gsitesearch(this)">
			<input name="q" type="hidden" class="texta" />
<?php
switch ($case) {
    case 0:
        echo '<p><strong>Die eingegebene Adresse ist falsch.</strong><br />
            <ul><li>&Uuml;berpr&uuml;fen Sie bitte die Adresszeile Ihres Browsers auf Tippfehler.</li></ul></p>
            
           <p><strong>Ihr Lesezeichen/Bookmark ist veraltet.</strong><br />
           <ul><li>Benutzen Sie die Suchfunktion, um den gewünschten Inhalt zu finden und aktualisieren Sie Ihr Lesezeichen/Bookmark: '.$searchForm.'</li></ul></p>
	
    		<p><strong>Die Seite wurde entfernt.</strong><br />
    		<ul><li>Versuchen Sie die Seite <a href="http://'.$serverUrl.'">&uuml;ber die Hauptnavigation</a> zu erreichen.</li></ul></p>';
        break;
    case 1:
        echo '<p><strong>Die eingegebene Adresse ist falsch.</strong><br />
            <ul><li>&Uuml;berpr&uuml;fen Sie bitte die Adresszeile Ihres Browsers auf Tippfehler.</li></ul></p>
            
            <p><strong>Ihr Lesezeichen/Bookmark ist veraltet.</strong><br />
            <ul><li>Benutzen Sie die Suchfunktion, um den gewünschten Inhalt zu finden und aktualisieren Sie Ihr Lesezeichen/Bookmark: '.$searchForm.'</li></ul></p>

    		<p><strong>Die Seite wurde entfernt.</strong><br />
    		<ul><li>Versuchen Sie die Seite <a href="http://'.$serverUrl.'">&uuml;ber die Hauptnavigation</a> zu erreichen.</li></ul></p>';
        break;
    case 2:
        echo '<p><strong>Der Link, dem Sie folgten, ist defekt.</strong><br />
    		<ul><li>Sie haben einen Fehler bei unserer Verlinkung gefunden!<br />
    		Unser Webmaster wurde soeben darüber informiert und wird den Fehler in Kürze beheben.</p>
    		<p>Bitte versuchen Sie es zu einem späteren Zeitpunkt erneut oder benutzen Sie die Suchfunktion, um den gewünschten Inhalt zu finden: '.$searchForm.'</li></ul></p>
    		<p><a href="".$referer."">&laquo; Zurück</a>';
        break;
    case 3:
        echo '<p><strong>Veraltete Informationen aus der Suchmaschine.</strong><br />
    		<ul><li>Die Suchmaschine '.$refererDomain.' hat Sie auf einen veralteten Link weitergeleitet. Bei der nächsten Aktualisierung des Suchmaschinenindexes wird dieser Link automatisch entfernt.</p>
    		<p>Benutzen Sie die Suchfunktion, um den gewünschten Inhalt dennoch zu finden: '.$searchForm.'</li></ul></p>';
        break;
    case 4:
        echo '<p><strong>Der Link, dem Sie folgten, ist veraltet.</strong><br />
    		<ul><li>Der Link auf der Webseite '.$refererDomain.' ist nicht mehr aktuell. Wir wurden über den Fehler informiert und versuchen den Seitenbetreiber von '.$refererDomain.' zu erreichen, damit der Link aktualisiert wird.</p>
    		<p>Benutzen Sie die Suchfunktion, um die Seite dennoch zu finden: '.$searchForm.'</li></ul></p>';
    	break;
    case 5:
        echo '<p><strong>Hackingversuch erkannt.</strong><br />
    		<ul><li>Wir haben einen Angriff auf die Seite festgestellt.<br />
    		Ihre IP-Adresse (<strong>'.$clientIp.'</strong>) wurde aufgezeichnet und wird bei weiteren Angriffsversuchen von uns automatisch an die zuständigen Behörden weitergeleitet.</li></ul></p>';
    	break;
}
?>
</form>
          </div>
        </div>
      </div>
      <div class="footer wrapper">
      	<p>Request-ID: <strong><?php echo $requestId; ?></strong> &bull; Ihre IP-Adresse: <?php echo $clientIp; ?><?php wp_footer(); ?>
      	</p>
	  </div>
    </div>
  </div>
</body>
</html>