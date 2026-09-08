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

- Domain eintragen (canonical im `<head>`, `robots.txt`, `sitemap.xml`,
  Video-Schema) – steht überall noch `DOMAIN-FOLGT.de`
- Formular scharf schalten: PHP-Skript beim Hoster, Empfängeradresse, Honeypot
- Datenschutzhinweis: Das Formular und die Videos liegen auf dieser Seite.
  Der verlinkte Hinweis der Hauptseite deckt das noch nicht ab.
- `doku/htaccess-vorlage.txt` mit der echten Domain als `website/.htaccess` einsetzen
