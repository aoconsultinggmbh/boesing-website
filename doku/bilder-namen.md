# Bilder und Videos: Ablage und Dateinamen

## Kurz vorweg

**Du musst nichts umbenennen.** Lege die Originale einfach so ab, wie picdrop sie
ausgibt, also `Bösing_Dental_98.jpg`. Ich benenne um, erzeuge WebP plus JPG, setze
Breite und Höhe und schreibe die Alt-Texte. Die Liste hier ist nur der Zielzustand,
damit du siehst, was aus welcher Nummer wird.

Ablage:

```
img-original/   Originale aus picdrop, beliebige Namen
videos/         die beiden MP4-Dateien
img/            hier landen die fertigen WebP und JPG, das mache ich
```

## Namensregel

Klein, mit Bindestrichen, Stichwort vorn, Ort hinten, keine Umlaute.
Aus `ö` wird `oe`, aus `ä` wird `ae`. Jedes Motiv bekommt eine `.webp` und eine `.jpg`
mit demselben Namen, eingebunden über `<picture>`.

## Hero und Vertrauen

| picdrop | Dateiname | Rolle auf der Seite |
|---|---|---|
| 98 | begruessung-zahnarztpraxis-dentallabor-bingen | Hero, Hauptbild |
| 99 | handschlag-praxis-labor-dentallabor-bingen | Hero, Alternative |
| 100 | gespraech-flur-dentallabor-bingen | Hero, Alternative |
| 107 | team-boesing-dental-bingen | Abschnitt Labor |
| 105 | fuehrungsteam-dentallabor-bingen | Abschnitt Labor |

## Nutzen und Abstimmung

| picdrop | Dateiname | Rolle |
|---|---|---|
| 41 | abstimmung-zahnarztpraxis-dentallabor-bingen | Leistungskachel Service |
| 42 | beratung-zahnersatz-dentallabor-bingen | Alternative |
| 43 | fachgespraech-zahntechnik-dentallabor-bingen | Alternative |
| 4 | beratung-am-bildschirm-dentallabor-bingen | Nutzenabschnitt |

## Digitale Fertigung

| picdrop | Dateiname | Rolle |
|---|---|---|
| 8 | cad-konstruktion-zahnersatz-dentallabor-bingen | Kachel festsitzender ZE, eingebaut |
| 87 | cad-cam-konstruktion-zahnersatz-dentallabor-bingen | Alternative |
| 94 | digitale-zahnersatz-konstruktion-dentallabor-bingen | Kachel SimplyDent |
| 36 | exocad-konstruktion-implantatprothetik-dentallabor-bingen | Alternative |
| 37 | digitale-planung-zahnersatz-dentallabor-bingen | Alternative |
| 30 | cad-cam-fraesmaschine-zirkon-dentallabor-bingen | Kachel Digitalisierung |
| 32 | fraesen-zirkonoxid-rohling-dentallabor-bingen | Alternative |
| 23 | fraeswerkzeuge-cad-cam-dentallabor-bingen | Detailbild |
| 27 | fertigung-fraesmaschine-dentallabor-bingen | Ablauf, Fertigung |
| 28 | bedienung-fraesmaschine-dentallabor-bingen | Alternative |
| 9 | digitalisierung-arbeitsplatz-dentallabor-bingen | Erreichbarkeit |
| 10 | intraoralscan-daten-dentallabor-bingen | Alternative |

## 3D-Druck und Print Green

| picdrop | Dateiname | Rolle |
|---|---|---|
| 19 | metallgeruest-ausarbeitung-strahlkabine-dentallabor-bingen | zeigt die Strahlkabine, nicht den 3D-Druck. Liegt fertig in `img/`, aktuell nicht eingebaut |
| 20 | 3d-drucker-aligner-modelle-dentallabor-bingen | Abschnitt Print Green |
| 22 | 3d-druck-schienen-dentallabor-bingen | Alternative |

## Prothetik und Handarbeit

| picdrop | Dateiname | Rolle |
|---|---|---|
| 53 | totalprothese-ausarbeitung-dentallabor-bingen | Kachel herausnehmbarer ZE |
| 55 | prothetik-handarbeit-dentallabor-bingen | Alternative |
| 57 | zahntechnikerin-prothetik-dentallabor-bingen | Alternative |
| 60 | verblendung-prothese-detailarbeit-dentallabor-bingen | Detailbild |
| 62 | zahnersatz-feinarbeit-dentallabor-bingen | Alternative |
| 16 | zahntechnik-arbeitsplatz-dentallabor-bingen | Nutzenabschnitt |
| 17 | zahntechnik-handarbeit-dentallabor-bingen | Alternative |

## Versand und Region

| picdrop | Dateiname | Rolle |
|---|---|---|
| 48 | auftragsbox-zahnarztpraxis-dentallabor-bingen | Ablauf, Versand |
| 49 | transportbox-praxis-dentallabor-bingen | Alternative |
| Drohne_1 | standort-luftbild-dentallabor-bingen-am-rhein | Abschnitt Region |
| Drohne_2 | luftbild-franz-kirsten-strasse-bingen | Alternative |
| Drohne_4 | luftbild-rhein-einzugsgebiet-bingen | Alternative |

## Porträts

Hier brauche ich noch die Zuordnung, wer auf welchem Bild ist. Dann heißen die
Dateien nach der Person, das ist für die Bildsuche deutlich besser als „Porträt".

| picdrop | Dateiname, sobald die Person bekannt ist |
|---|---|
| 101 | zeigt den Sohn des Inhabers mit einem Kunden, kommt auf die zweite Seite, nicht als Porträt |
| 102 | portraet-<vorname>-<nachname>-dentallabor-bingen |
| 39 | ansprechpartner-buero-dentallabor-bingen |
| 40 | kundenbetreuung-telefon-dentallabor-bingen |

## Stand der Bilder

Eingebaut sind 98 im Hero, 8, 53, 94, 30 und 41 bei den Leistungen sowie Drohne_1 in der
Region. Bild 19 ist aufbereitet, aber nicht eingebaut, weil es die Strahlkabine zeigt und
nicht den 3D-Druck.

Es fehlen noch: **20 oder 22** für die Schienen-Kachel und den Print-Green-Abschnitt,
**105 und 107** für das Team, **102, 39 und 40** für die Porträts.

## Videos: erledigt

Die beiden UT-Fassungen lagen schon im Ordner. Ich habe daraus die Web-Fassungen
gebaut, die Vorschaubilder gezogen und beides eingebunden. Die Originale liegen
unverändert daneben.

Fertige Dateien:

```
videos/imagevideo-dentallabor-bingen.mp4                      17 MB, 1:02 min
videos/testimonial-zahnarztpraxis-dentallabor-bingen.mp4      16 MB, 1:17 min
img/imagevideo-poster-dentallabor-bingen.webp / .jpg
img/testimonial-poster-dentallabor-bingen.webp / .jpg
```

Wenn die Reels im Hochformat auch auf die Seite sollen, sag es. Dann bekommt die
Handy-Fassung das 9:16-Video und der Desktop das 16:9.
