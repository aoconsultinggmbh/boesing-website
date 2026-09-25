# Landingpage Bösing Dental, Praxisgewinnung

Entwurf von AO Consulting GmbH, Stand 3. September 2026.
Zielgruppe sind Zahnarztpraxen im Umkreis von rund 100 km um Bingen am Rhein.
Der Traffic kommt später über Meta Ads, die Seite ist deshalb mobil zuerst gedacht
und hat nur ein Ziel: eine Anfrage für ein Erstgespräch.

## Dateien

```
boesing-dental-landingpage.html   Einzeldatei zum Vorzeigen, alles eingebettet
bau-einzeldatei.py      erzeugt diese Einzeldatei neu
bilder-namen.md         Zielnamen für Bilder und Videos
index.html              die Landingpage, Arbeitsfassung mit assets/
impressum.html          Entwurf, Angaben aus dem Impressum der Hauptseite
datenschutz.html        Entwurf, Struktur mit offenen Stellen
assets/stil.css         Layout und Farben
assets/skript.js        Menü, Video erst auf Klick, Formularprüfung
assets/schrift.css      Plus Jakarta Sans, lokal ausgeliefert
assets/barrierefreiheit.css / .js   Barrierefreiheits-Widget
fonts/                  Schriftdateien, keine Verbindung zu Google
img/                    noch leer, hier kommen die Bilder hinein
img-original/           Ablage für die Originale aus picdrop
videos/                 Ablage für die beiden MP4-Dateien
seo-beiblatt.md         Keywords, Schema, GEO
robots.txt, sitemap.xml Vorlagen, Domain muss eingetragen werden
```

Zum Ansehen: `index.html` doppelklicken. Alles läuft ohne Server.

Ein Hinweis dazu: Beim Öffnen per Doppelklick meldet die Browserkonsole einen
CORS-Fehler zu den vorgeladenen Schriftdateien. Das ist eine Eigenart des
`file://`-Protokolls, über HTTP tritt er nicht auf. Die Seite sieht trotzdem richtig aus.

## Zwei Fassungen

**Zum Vorzeigen:** `boesing-dental-landingpage.html`. Eine einzige Datei, etwa 185 KB,
mit eingebettetem CSS, Skript, Schriften und Favicon, dazu Impressum und Datenschutz
als eigene Abschnitte am Seitenende. Die Datei läuft per Doppelklick, funktioniert
ohne Internet und lässt sich per E-Mail weitergeben. Kein einziger Aufruf an einen
fremden Server, das habe ich gemessen.

**Zum Weiterarbeiten:** `index.html` mit `assets/` und `fonts/`. Hier ändere ich,
hier kommen die Bilder hinein. Nach jeder Änderung:

```
python3 bau-einzeldatei.py
```

Damit wird die Einzeldatei neu erzeugt. Die Einzeldatei selbst nie direkt bearbeiten,
sonst geht die Änderung beim nächsten Bauen verloren.

## Zum Weitergeben an den Kunden

`boesing-dental-landingpage-paket.zip`, etwa 32 MB. Darin die Einzeldatei und der
Ordner `videos` mit den beiden Web-Fassungen. Entpacken, `boesing-dental-landingpage.html`
doppelklicken, dann laufen auch die Videos. Die Einzeldatei allein per E-Mail
funktioniert ebenfalls, dann fehlen nur die Videos, und der Abspielknopf sagt das auch.

## Aufbau der Seite

1. Hero mit der Kernaussage „Erst ein Fall, dann entscheiden Sie" und zwei Wegen: Formular oder Anruf
2. Vertrauensleiste: 1998 gegründet, über 50 Mitarbeitende, Innovationspreis 2019, Green Dental Award 2023
3. Imagevideo mit Textzusammenfassung daneben
4. Was sich im Praxisalltag ändert, vier Karten
5. Ablauf des Kennenlernens in vier Schritten
6. Leistungen, sechs Bereiche aus der Hauptseite
7. Kundentestimonial-Video und drei Praxisstimmen
8. Region, Anfahrt und Einzugsgebiet
9. Print Green als Nachhaltigkeitsbaustein
10. Zehn Fragen aus Praxen, mit FAQ-Schema
11. Formular für das Erstgespräch, Telefon als Alternative

## Farben und Schrift

Seit dem 25. September 2026 nach dem Corporate Manual Rev. 1.1 von Bösing Dental:

| Rolle | Wert |
|---|---|
| Blau HKS 47 (Marke, Flächen, Icons) | #00b6e8 |
| Blau für große Überschriften | #0099c7 (HKS 47 leicht abgedunkelt, Kontrast 3:1) |
| Blau für Links, Knöpfe, kleine Texte | #007da3, Hover #005f7d (Kontrast 4,7:1) |
| Orange HKS 7 (Akzent) | #f29400 |
| Gelb HKS 3 (Akzent) | #ffd500 |
| Dunkelblau (Fließtext, Fuß) | #092738 |
| Grün (Print Green) | #39b54a |

Reines HKS 47 erreicht auf Weiß nur 2,4:1 Kontrast. Deshalb steht es auf Flächen,
Icons, dem Bogen im Hero und den Schrittkreisen, während Text in abgedunkelten Tönen
derselben Farbe gesetzt ist. Die Werte liegen als Variablen oben in `assets/stil.css`.
Die Hausschrift AbsaraSansTF aus dem Manual ist eine Kaufschrift und liegt nicht vor,
deshalb bleibt Plus Jakarta Sans (lokal ausgeliefert). Wenn das Labor die Lizenz und
die Schriftdateien stellt, tausche ich sie aus.

## Änderungen vom 25. September 2026 (Besprechung mit n.boesing)

- Farben nach Corporate Manual, siehe oben.
- Imagevideo steht jetzt oben rechts im Hero, das Begrüßungsbild ist in den Abschnitt
  „Einblick" gewandert.
- Hinweistext unter dem Luftbild entfernt.
- Echtes Google-G bei der Bewertung (`img/google-g.svg`).
- Inhaltsbreite 1400 px statt 1220 px, Kopfzeile 1560 px.
- FAQ „Nacharbeit" komplett entfernt (sichtbar und im Schema). Antwort zum Zahnersatz
  auf „von A bis Z" erweitert.
- Drittes Zitat: Dr. Johannes Christmann, Zahnmedizin Ingelheim (von boesing-dental.de),
  statt des zweiten Huber-Zitats.
- Orte Worms, Limburg und Frankfurt aus der Regionsliste genommen.
- Einwilligung von Sebastian Huber liegt vor, der gelbe Hinweis ist raus.

## Was noch fehlt

**Bilder, Stand jetzt.** Eingebaut und fertig aufbereitet als WebP plus JPG,
lange Kante 1800 px, mit Alt-Text, Breite und Höhe:

| Nummer | Wo | Dateiname |
|---|---|---|
| 98 | Einblick (neben dem Text) | begruessung-zahnarztpraxis-dentallabor-bingen |
| 8 | Festsitzender Zahnersatz | cad-konstruktion-zahnersatz-dentallabor-bingen |
| 53 | Herausnehmbarer Zahnersatz | totalprothese-ausarbeitung-dentallabor-bingen |
| 94 | SimplyDent | digitale-zahnersatz-konstruktion-dentallabor-bingen |
| 30 | Digitalisierung | cad-cam-fraesmaschine-zirkon-dentallabor-bingen |
| 41 | Service in Ihrer Praxis | abstimmung-zahnarztpraxis-dentallabor-bingen |
| Drohne_1 | Region | standort-luftbild-dentallabor-bingen-am-rhein |

Bild 19 ist aufbereitet, aber **nicht** eingebaut. Es zeigt die Ausarbeitung in der
Strahlkabine und nicht den 3D-Druck, deshalb passte es nicht auf die Schienen-Kachel.
Es liegt als `metallgeruest-ausarbeitung-strahlkabine-dentallabor-bingen` in `img/`.

Es fehlen noch acht Bildflächen: **20 oder 22** für die Schienen-Kachel und für Print
Green, **105 und 107** für das Team, **102, 39 und 40** für die Porträts. Originale
einfach in `img-original/` ablegen.

Bild **101** zeigt den Sohn des Inhabers mit einem Kunden und ist für die zweite Seite
vorgesehen, nicht als Porträt auf dieser Landingpage.

**Videos sind fertig eingebaut.** Beide laufen, laden erst nach Klick und liegen auf
dem eigenen Server. Kein Consent-Bedarf, keine Verbindung zu YouTube.

| Datei | Größe | Herkunft |
|---|---|---|
| `videos/imagevideo-dentallabor-bingen.mp4` | 17 MB, 1:02 min | aus 111 MB Original, 1080p, H.264, CRF 24 |
| `videos/testimonial-zahnarztpraxis-dentallabor-bingen.mp4` | 16 MB, 1:17 min | aus 140 MB Original |
| `img/imagevideo-poster-dentallabor-bingen.jpg` / `.webp` | 94 / 47 KB | Standbild aus Sekunde 22 |
| `img/testimonial-poster-dentallabor-bingen.jpg` / `.webp` | 93 / 44 KB | Standbild aus Sekunde 33 |

Die Originale bleiben unangetastet im Ordner `videos/` liegen, ich habe nur Kopien
erzeugt. Beide Fassungen haben eingebrannte Untertitel, das ist die UT-Version.
Die Vorschaubilder sind unten um 15 Prozent beschnitten, damit im Standbild keine
Untertitelzeile steht. Wenn du die Seite ohne eingebrannte Untertitel willst, liegen
im selben SharePoint-Ordner die Fassungen ohne UT, dann tausche ich sie aus.

**Gelb markierte Angaben im Entwurf.**

| Stelle | Was gebraucht wird |
|---|---|
| Hero, Ablauf, Formular, Danke | Rückmeldezeit, zum Beispiel „am selben Werktag" |
| Ablauf Schritt 2 | wer das Erstgespräch führt, Telefon oder Besuch, Dauer |
| Ablauf Schritt 3 | das konkrete Angebot für den ersten Fall |
| Region und FAQ | Kurierradius, Fahrtage, Kosten, echte Ortsliste |
| FAQ Nacharbeit | Regelung zu Nacharbeit und Gewährleistung |
| Testimonial | schriftliche Freigabe der Zahnarztpraxis Dr. Trunk für Nennung und Video. Der Sprecher ist Sebastian Huber, das steht jetzt auf der Seite. |
| Vertrauensleiste und Labor | „fast 60 Mitarbeitende" stammt aus dem Imagevideo, bitte als aktuelle Zahl bestätigen. Auf boesing-dental.de steht „über 50 Arbeitsplätze". |
| Fuß | Öffnungszeiten des Labors |
| Impressum | Verantwortlicher nach § 18 Abs. 2 MStV, Haftpflicht, Streitschlichtung, Bildnachweis |
| Datenschutz | Hoster, Speicherdauern, Datenschutzbeauftragter, Stand |
| Schema | Öffnungszeiten und Geokoordinaten, Bestätigung des 100-km-Radius |

**Freigaben.** Fachliche Freigabe der Leistungstexte und der Systemnamen, Einwilligung
der genannten Personen für Name und Foto, Freigabe der Praxen für Zitate und Video.

## Einwilligungsbanner: bewusst nicht drin

Die Seite lädt nichts, was eine Einwilligung braucht. Die Videos liegen auf dem
eigenen Server, die Schriften ebenfalls, es gibt keine Karte, keinen Analysedienst
und keine Cookies. Ein Banner wäre hier eine leere Hülle und ist deshalb nicht
eingebaut.

Sobald einer dieser Punkte dazukommt, muss das Banner mit:

- Meta Pixel oder ein Analysedienst
- Google Maps für die Anfahrt
- ein Video über YouTube oder Vimeo statt vom eigenen Server

Dann kommen `assets/einwilligung.css` und `assets/einwilligung.js` dazu, geladen
vor `skript.js`, mit Widerruf über „Cookie-Einstellungen" in der Fußzeile jeder Seite.
Ablehnen steht dabei gleich groß neben Zustimmen, vorbelegte Schalter sind nach dem
Planet49-Urteil des EuGH nicht wirksam.

## Favicon

`img/favicon.svg` als Monogramm in der CI, `img/favicon-32.png` als Rückfall und
`img/apple-touch-icon.png` mit 180 px. Alle drei sind in jeder Seite verlinkt. Wenn das
echte Logo kommt, tausche ich die drei Dateien gegen die Bildmarke aus dem Logo.

## FAQ und Schema hängen zusammen

Der sichtbare FAQ-Text und das `FAQPage`-Markup im `head` sind Wort für Wort gleich,
das prüft Google. Wenn eine der drei gelb markierten Antworten ersetzt wird, muss
dieselbe Änderung in das Schema. Sonst fällt die Auszeichnung aus.

Die Antworten nennen bewusst „Bösing Dental" und nicht „wir". So lassen sie sich in
einer KI-Antwort zitieren, was bei „wir fertigen ..." nicht funktioniert.

## Was am 3. September nachmittags dazugekommen ist

- **Logo** aus dem Original übernommen, `img/logo-boesing-dental.png` und `.webp`, im Kopf
  mit 58 px Höhe. Favicon ist die Zahngrafik allein, als `favicon-32.png`,
  `favicon-192.png` und `apple-touch-icon.png`. Die alte selbstgezeichnete
  `img/favicon.svg` ist nicht mehr verlinkt und kann weg.
- **Kopf-Button** „Erstgespräch" hatte dunkle Schrift, weil `.kopf-nav a` die
  Knopfregel überschrieben hat. Jetzt weiß, auch beim Hover.
- **Rückmeldung innerhalb von 48 Stunden** steht an vier Stellen: Hero, Ablauf,
  Formular und Danke-Ansicht, dazu in der FAQ.
- **Ablauf** Schritt 2 und 3 mit deinen Formulierungen gefüllt.
- **Schienen-Kachel** hat das KeySplint-Bild.
- **Print Green** ist jetzt einspaltig, ohne Bildfläche.
- **Der Abschnitt „Die Menschen, mit denen Sie sprechen" ist komplett entfernt**, weil
  keine passenden Porträts vorliegen. Damit sind auch der Navigationspunkt „Labor" und
  der Fußzeilenlink weg. Die Namen von Kanthack und Staab stehen weiter im
  Nutzenabschnitt und in der FAQ, dort tragen sie die Aussage zur festen Ansprechperson.
  Wenn später Porträts kommen, baue ich den Abschnitt wieder ein.
- **Öffnungszeiten** aus dem Google-Unternehmensprofil: Mo–Fr 08:00–17:00 Uhr,
  Samstag und Sonntag geschlossen. Steht im Fuß, in der FAQ und im Schema.
  Die Koordinaten im Schema sind jetzt die echten des Gebäudes.
- **Impressum und Datenschutz** verweisen auf boesing-dental.de. Die beiden
  Entwurfsseiten `impressum.html` und `datenschutz.html` sind nicht mehr verlinkt.
  **Wichtig:** Die Datenschutzerklärung auf der Hauptseite muss das Formular dieser
  Landingpage und deren Hosting abdecken. Wenn dort nur die Hauptseite beschrieben
  ist, braucht sie eine Ergänzung, sonst deckt der Link die Seite nicht.
- **Kurierdienst** ist aus dem Text raus, auch die FAQ-Frage dazu. Falls es einen
  Kurier gibt, ist das für Praxen ein starkes Argument, dann baue ich es wieder ein.

## Google-Bewertung im Hero

Unter den beiden Knöpfen sitzt ein Vertrauenspunkt mit dem Google-Wert:
**4,3 von 5,0 bei 11 Google-Rezensionen**, verlinkt auf das Unternehmensprofil.
Die Sterne sind zu 86 Prozent gefüllt, also nicht geschummelt.

Wichtig und bewusst so: Der Wert steht **nicht** als `aggregateRating` im Schema.
Fremdbewertungen von Google als eigene Bewertungen auszuzeichnen verstößt gegen die
Google-Richtlinien und kann eine manuelle Maßnahme auslösen. Wenn Bewertungen einmal
direkt auf der Seite liegen, zum Beispiel als eigene Praxisstimmen mit Datum, kann man
das nachziehen.

Der Wert ist ein Stand vom 3. September 2026 und aktualisiert sich nicht selbst.
Bei neuen Rezensionen bitte Zahl und Sterne im HTML anpassen, es sind zwei Stellen.

## Feste Leiste auf dem Handy

Unter 820 px Breite liegt am unteren Rand eine Leiste mit „Erstgespräch" und
„Anrufen". Damit ist die Anfrage aus jeder Scrollposition ein Fingertipp weit weg,
was bei Meta-Traffic den Unterschied macht. Das Barrierefreiheits-Widget rückt dafür
auf dem Handy nach oben, damit sich beide nicht überlagern.

## Soziale Netzwerke und Barrierefreiheits-Symbol

Im Fuß stehen jetzt Facebook (`facebook.com/BosingDental`) und Instagram
(`instagram.com/boesingdental`), beide öffnen in einem neuen Tab und sind auch als
`sameAs` im Schema hinterlegt. Das hilft Google und den KI-Suchen, die Seite derselben
Firma zuzuordnen. Auf boesing-dental.de ist zusätzlich LinkedIn verlinkt, sag Bescheid,
dann kommt es dazu.

Das Barrierefreiheits-Symbol unten rechts hat einen weißen Ring bekommen. Vorher ging
es auf dem blauen Kontaktabschnitt optisch unter, jetzt hebt es sich auf hellen und auf
dunklen Flächen ab. Die gemeinsame Widget-Datei bleibt unverändert, der Ring steht als
Ergänzung in `assets/stil.css`.

## Rechtliches

- Impressum und Datenschutz sind Entwürfe und keine Rechtsberatung. Beide Seiten
  tragen oben einen Hinweiskasten, offene Angaben sind gelb markiert.
- Die Seite läuft eigenständig, sie erbt Impressum und Datenschutz also nicht von
  boesing-dental.de.
- Keine Preise, keine Lieferzeiten, keine Garantien im Text. Nichts davon ist bestätigt.
- Es ist kein `aggregateRating` im Schema. Google-Rezensionen als eigene Bewertungen
  auszuzeichnen kann eine manuelle Maßnahme auslösen.
- Kein Meta Pixel, kein Analysedienst, keine Cookies. Im `head` steht eine
  vorbereitete Stelle für den Pixel samt Einwilligungslösung.
- Eine Google-Karte ist bewusst nicht eingebunden, weil sie die IP der Besucher überträgt.
  Im Entwurf steht dort ein Bild.

## Für den Livegang

1. Domain festlegen, dann `canonical`, `robots.txt` und `sitemap.xml` eintragen.
2. Formular: Das Formular prüft im Entwurf nur die Pflichtfelder und zeigt die
   Danke-Ansicht. Für den Versand kommt ein PHP-Skript beim Hoster dazu, Empfänger
   und Betreff nach Absprache, dazu ein Honeypot-Feld gegen Spam.
3. Bilder und Videos einsetzen, Alt-Texte prüfen.
4. Barrierefreiheit vor dem Livegang: Screenreader-Test und Kontrastmessung.
5. Search Console und Google-Unternehmensprofil verknüpfen.
6. Erst danach die Meta-Kampagne starten, damit die Anzeigenaussage und der Hero
   dieselbe Sprache sprechen.

## Was ich geprüft habe

Getestet bei 1920, 1512, 1440, 1280, 1241, 1240, 1024, 768 und 390 px:

- kein horizontales Scrollen bei keiner Breite, auch nicht mit künstlich
  verbreiterter Schrift
- Burger-Menü ab 1040 px, öffnet und schließt, schließt mit Escape,
  `aria-expanded` wird gesetzt, offenes Menü verdeckt Logo und Burger nicht
- genau eine h1, jeder Abschnitt mit `aria-labelledby`, Sprunglinks zu Inhalt und Formular
- Formular: Pflichtfeldprüfung greift, Danke-Ansicht erscheint, Datenschutz-Häkchen ist Pflicht
- kein `href="#"` in der Abgabe
- keine Gedankenstriche im Fließtext, nach AO Schreibstil
- Barrierefreiheits-Widget vorhanden, Einstellungen bleiben über Unterseiten erhalten

Offen bis Bilder und Videos da sind: Hover-Zustände auf echten Bildkacheln,
Ladeverhalten des Hero-Bildes, Vorschaubilder der Videos.
