clever404
=========

Wordpress 4040 Template with intelligence

WAS ES IST

clever404 ist eine schlaue 404er Seite für Dein Wordpress Blog.
Sie informiert Dich über eventuell falsch gesetzte Links auf Deiner eigenen Seite via E-Mail und
bietet dem User bei auftretenden Fehlern erweiterte Möglichkeiten zum Auffinden des gewünschten Inhaltes.

Folgende vier Usecases werden im Script berücksichtigt:

1. Link auf Deinem Blog verweist auf eine nicht existierende Seite
  -> Du bekommst hierüber auf Wunsch eine E-Mail
2. Besucher kommt über eine Suchmaschine auf eine nicht mehr existierende Seite
  -> Besucher erhält entsprechenden Hinweis und die Möglichkeit eine Suche auszuführen
3. Besucher kommt über eine andere Webseite auf eine nicht mehr existierende Seite
  -> Besucher erhält entsprechenden Hinweis und die Möglichkeit eine Suche auszuführen
4. sonstiger 404 Fehler
  -> Besucher erhält alle möglichen Hinweise und Suchmöglichkeiten an die Hand

Außerdem ist ein Bot-Schutz eingebaut, der zu 85% unnötige E-Mails an Dich verhindert.

INSTALLATIONSHINWEISE

1. kopiere die beiden Dateien (404.php und 404.css) in Dein Wordpress-Theme-Verzeichnis und überschreibe eventuell vorhandene Dateien (Backup machen!)
2. öffne die 404.php und fülle den Bereich "CONFIGURATION" aus. Folgende Felder müssen befüllt werden:
  a) Liste der Suchmaschinen
  b) Für welche Webseiten soll die E-Mail-Funktion aktiviert sein? (Format: "www.personenname.de","www.bastianoso.de")
  c) E-Mail-Adresse des Empfängers "mail@personnename.de" (inkl. Anführungszeichen)
  d) Name des Absenders "mail@personnename.de" (inkl. Anführungszeichen)
  e) E-Mail-Adresse des Absenders "404@personnename.de" (inkl. Anführungszeichen)
3. Probiere das Script aus!
