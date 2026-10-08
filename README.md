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
- ~~Testanfrage über den echten Server, Ankunft im Postfach prüfen.~~ Erledigt am 08.10.2026, siehe unten.

## Kontaktformular: Stand 08.10.2026 (Awan)

Meldung des Kunden: Anfragen kommen nicht an. Befund nach Tests über den echten
Server: Das Skript verschickt korrekt, `mail()` funktioniert auf dem Konto.
Mails an Microsoft-365-Postfächer (Kunde und AO Consulting) kommen aber mit
rund 12 bis 15 Minuten Verzögerung an, bei einer so neuen Domain normal.
Beim Kunden vermutlich im Junk-Ordner gelandet. SPF, DKIM
(`kas202610011119._domainkey`) und DMARC sind korrekt gesetzt.

Eingebaut in `anfrage-senden.php`:

- Kopie (Cc) jeder Anfrage an tofik@ao-consult.de zur Kontrolle, nach zwei Wochen wieder entfernen.
- Sicherheitskopie (Bcc) jeder Anfrage ins All-Inkl-Postfach anfrage@boesing-dentallabor.de
  (Zugang per Webmail aus dem KAS, Benutzer m082660d). Dort geht nichts verloren.
- Eingangsbestätigung an die anfragende Praxis (Absender anfrage@, Antworten gehen an nboesing@).
- Versand per SMTP mit Anmeldung als Reserve: wird nur aktiv, wenn das GitHub-Secret
  `FORMULAR_SMTP_PASSWORT` (Passwort des Postfachs anfrage@) angelegt ist. Aktuell aus, nicht nötig.

Offen beim Kunden: Absender „Praxis-Seite Boesing Dental" in Outlook als sicher markieren.

## Meta-Pixel (02.10.2026)

Pixel-ID `1019905141107119` (Werbekonto Bösing Dental) in `assets/ao-konfiguration.js`.
Lädt nur nach Zustimmung im Einwilligungsfenster (Kategorie „Marketing"), vorher keine
einzige Anfrage an Meta. Gesendet werden nur `PageView` und `Lead` (erfolgreich
abgeschicktes Formular, ohne Inhalte); automatische Events sind im Code aus
(`autoConfig false`). Im Events Manager ebenfalls aus lassen: „Events automatisch ohne
Code tracken" und „Automatischer erweiterter Abgleich". Positivliste: boesing-dentallabor.de.
Datenschutz: neuer Punkt 9 (Meta-Pixel), Punkt 10 (Cookies und Einwilligung) neu,
Punkte 3 und 8 angepasst. Text des Einwilligungsfensters steht in `ao-konfiguration.js`
(`text`, `fein`), Farben in `stil.css` (Abschnitt „Einwilligungsfenster in der CI").
