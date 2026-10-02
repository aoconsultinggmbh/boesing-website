# Bösing Dental – Landingpage

Landingpage zur Praxisgewinnung für **Bösing Dental, Bingen am Rhein**.
Zielgruppe sind Zahnarztpraxen im Umkreis von rund 100 km; der Besucherstrom
kommt über Meta Ads. Die Seite hat ein Ziel: eine Anfrage für ein Erstgespräch.

Erstellt von AO Consulting GmbH.

## Aufbau

| Ordner | Inhalt |
|---|---|
| `website/` | **Die Webseite.** Nur was hier liegt, geht online. |
| `doku/` | Unterlagen: Anleitung, SEO-Beiblatt, Bildnamen, Vorlage für die Servereinstellungen |
| `.github/workflows/` | Die zwei Abläufe: Vorschau und Livegang |

## Die zwei Zweige

- **`main` = Vorschau.** Jede Änderung erscheint nach 1–2 Minuten unter
  <https://boesing.vorschau.ao-consult.de>. Suchmaschinen sind dort ausgesperrt.
- **`live` = echte Webseite.** Erst wenn der Zweig `live` auf den Stand von
  `main` gesetzt wird, lädt GitHub die Dateien zum Hoster hoch.

Nichts geht ohne Freigabe live.

## Regeln

1. Änderungen immer zuerst in der Vorschau prüfen, dann dem Kunden zeigen.
2. Keine externen Schriften, Skripte oder Einbettungen. Die Schriften liegen
   im Paket (`website/fonts/`), es gibt keine Verbindung zu Google.
3. Impressum und Datenschutz nur nach Rücksprache mit dem Kunden ändern.
4. Zugangsdaten gehören in den Passwort-Manager und in GitHub-Secrets, nie in Dateien.

Anleitung in leichter Sprache: `doku/ANLEITUNG.txt` und
`doku/livegang-anleitung.md` im Projekt puchmayr-website.

## Vor dem Livegang zu erledigen

Stand 01.10.2026. Erledigt ist: Entwurfs-Hinweise entfernt, eigene Seiten
`impressum.html` und `datenschutz.html` (Angaben aus dem Impressum der
Hauptseite, Hoster All-Inkl, Formular, Videos, Matomo), Versand-Skript
`anfrage-senden.php` mit Honigtopf und Zeitsperre, Formular mit Ersatzweg
(Mailprogramm), Empfänger `nboesing@boesing-dental.de`.

Domain: **www.boesing-dentallabor.de** (bei All-Inkl bestellt am 01.10.2026,
Konto w02227af). Eingetragen in canonical, robots.txt, sitemap.xml,
Video-Schema, .htaccess (Umleitung auf https und www, nur für diese Domain)
und als Absender `anfrage@boesing-dentallabor.de` im Versand-Skript.

Noch offen:

- Postfach `anfrage@boesing-dentallabor.de` bei All-Inkl anlegen.
- ~~SSL-Zertifikat~~ läuft für beide Namen (im Browser ohne Warnung geprüft).
  `live` am 02.10.2026 auf den Stand von `main` gesetzt (Ovidiu), Upload erfolgreich,
  Umleitung http und ohne www auf https://www.boesing-dentallabor.de geprüft.
- Matomo: Seite unter statistik.ao-consult.de anlegen, Kennung in
  `assets/statistik.js` eintragen, Skript auf allen drei Seiten nach
  `messung.js` einbinden.
- Impressum und Datenschutz dem Kunden zeigen und freigeben lassen.
- Testanfrage über den echten Server, Ankunft im Postfach prüfen.
