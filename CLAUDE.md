# CLAUDE.md

Hinweise für Claude Code zur Arbeit an diesem Repository.

## Was das Projekt ist

clever404 ist ein Drop-in-Ersatz für die `404.php` eines WordPress-Themes. Die Seite
klassifiziert den 404-Fehler anhand des Referers, zeigt dem Besucher eine passende
Erklärung samt vorbefüllter Google-Site-Suche und benachrichtigt den Betreiber per
E-Mail über kaputte Links.

Kein Plugin, kein Build, keine Abhängigkeiten — zwei Dateien, die in das Theme-
Verzeichnis kopiert werden.

## Aufbau

- `Wordpress/404.php` — gesamte Logik plus HTML-Ausgabe in einer Datei.
  Oben der Block `CONFIGURATION` (Suchmaschinenliste, Mail-Whitelist, Absender/
  Empfänger), darunter die Auswertung und das Markup.
- `Wordpress/404.css` — minifiziertes Stylesheet, alle Regeln unter `#wrapper`
  gescoped, damit das Theme-CSS nicht kollidiert. Wird über
  `get_template_directory_uri()` eingebunden.
- `README.md` — Nutzerdokumentation inkl. Installationsanleitung.

## Fallunterscheidung in `404.php`

Die Variable `$case` steuert Text und Mailversand:

| Case | Situation | Mail |
|---|---|---|
| 0 | Fallback (wird derzeit nicht erreicht) | nein |
| 1 | kein Referer — Vertipper oder alter Bookmark | nein |
| 2 | Referer liegt auf der eigenen Domain — interner Link defekt | ja |
| 3 | Referer ist eine Suchmaschine aus `$searchEngingeList` | nein |
| 4 | Referer ist eine fremde Webseite | ja |
| 5 | Bot-/Hacking-Verdacht (Referer == URL, `RK=0`, `author=`) | nein |

Mail geht nur raus, wenn `$mail === true` **und** die Referer-Domain in
`$mailWhitelist` steht. Bei leerer Whitelist verschickt das Script also nichts.

## Konventionen

- Zielplattform ist bewusst altes PHP (der Header nennt PHP 4/5) — keine modernen
  Sprachfeatures einbauen, ohne das vorher abzustimmen.
- Sprache der Oberfläche und der Kommentare ist Deutsch, Umlaute im Markup als
  HTML-Entities (`&uuml;`).
- Änderungen an der Fallunterscheidung immer in der Tabelle oben **und** in der
  `README.md` nachziehen.
- Der `AMENDMENT HISTORY`-Block im Datei-Header von `404.php` wird bei
  funktionalen Änderungen fortgeschrieben (`editDate` + kurze Beschreibung),
  `@version` entsprechend erhöht.
- Konfigurationswerte im Repo bleiben leer — keine echten E-Mail-Adressen oder
  Domains committen.

## Testen

Es gibt keine Testsuite. Manuell prüfen: Dateien in ein Theme kopieren, eine
nicht existierende URL aufrufen und die Fälle über den Referer durchspielen
(direkter Aufruf, interner Link, Suchmaschine, externe Seite).
