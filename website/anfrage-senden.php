<?php
/* ============================================================================
   Kontaktformular der Bösing Dental GmbH & Co. KG (Praxis-Seite).
   Nimmt die Anfrage vom Formular entgegen und schickt sie als E-Mail weiter.

   WAS HIER EINGESTELLT WIRD  (und sonst nichts):
     $an        Wer die Anfragen bekommt.
     $von       Absenderadresse. MUSS ein echtes Postfach auf derselben Domain
                sein, auf der diese Webseite laeuft, sonst stuft der Empfaenger
                die Mail als Spam ein (SPF-Pruefung). Zum Antworten zaehlt
                ohnehin das Reply-To weiter unten, dort steht die Adresse der
                anfragenden Praxis.

   VOR DEM LIVEGANG: $von auf die echte Adresse setzen und das Postfach dazu
   beim Hoster anlegen. Solange dort noch DOMAIN-FOLGT steht, verschickt dieses
   Skript nichts und meldet einen Fehler; das Formular oeffnet dann als
   Ersatzweg das Mailprogramm, es geht keine Anfrage verloren.

   SICHERHEIT
   - Honigtopf: ein fuer Menschen unsichtbares Feld ("website"). Fuellt es
     jemand aus, war es ein Roboter, und wir tun so, als waere alles gut.
   - Zeitsperre: wer das Formular in unter drei Sekunden absendet, ist keiner.
   - Alle Werte werden von Zeilenumbruechen befreit, bevor sie in den Kopf der
     Mail kommen (sonst koennte jemand fremde Empfaenger einschmuggeln).
   - Es wird nichts gespeichert, keine Datenbank, keine Datei, kein Cookie.
   ============================================================================ */

$an  = 'nboesing@boesing-dental.de';   // Empfaenger der Anfragen (abgestimmt am 01.10.2026)
$von = 'anfrage@boesing-dentallabor.de';   // Postfach bei All-Inkl, Konto w02227af

header('Content-Type: application/json; charset=utf-8');

function ende($ok, $text = '') {
  echo json_encode(array('ok' => $ok, 'text' => $text), JSON_UNESCAPED_UNICODE);
  exit;
}
function sauber($feld) {
  $wert = isset($_POST[$feld]) ? (string) $_POST[$feld] : '';
  $wert = trim($wert);
  return mb_substr($wert, 0, 3000);
}
function einzeilig($wert) {
  return trim(preg_replace('/[\r\n]+/', ' ', $wert));
}

/* --- Meta Conversions API: Ereignis "Lead" vom Server an Meta ---------------
   Nur wenn der Besucher im Einwilligungsfenster "Marketing" zugestimmt hat
   (skript.js schickt dann meta_ok=1) UND der Zugriffsschluessel auf dem Server
   liegt. Den Schluessel schreibt der Livegang-Ablauf aus dem GitHub-Secret
   META_CAPI_TOKEN in die Datei meta-capi.php; er steht nie im Projekt.
   Uebermittelt werden: Ereignisname, Zeitpunkt, Seitenadresse, Ereignis-ID,
   IP-Adresse, Browserkennung und die Meta-Kennungen _fbp/_fbc.
   KEINE Formularinhalte (kein Name, keine Mail, keine Telefonnummer).
   Klappt der Aufruf nicht, merkt der Besucher nichts: die Anfrage ist da schon
   verschickt. */
function meta_lead() {
  if (sauber('meta_ok') !== '1') return;
  $datei = __DIR__ . '/meta-capi.php';
  if (!is_file($datei)) return;
  $konf = include $datei;
  $token = is_array($konf) && isset($konf['token']) ? trim($konf['token']) : '';
  if ($token === '' || !function_exists('curl_init')) return;

  $pixel   = '1019905141107119';   // Pixel "Landeseite Kundengewinnung" (wie in ao-konfiguration.js)
  $version = 'v24.0';              // Graph-API-Version; bei Fehlermeldungen von Meta hier erhoehen

  $nutzer = array(
    'client_ip_address' => isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '',
    'client_user_agent' => isset($_SERVER['HTTP_USER_AGENT']) ? mb_substr($_SERVER['HTTP_USER_AGENT'], 0, 500) : '',
  );
  $fbp = einzeilig(sauber('meta_fbp'));
  $fbc = einzeilig(sauber('meta_fbc'));
  if (preg_match('/^fb\.\d\.\d+\.[A-Za-z0-9_.-]+$/', $fbp)) $nutzer['fbp'] = $fbp;
  if (preg_match('/^fb\.\d\.\d+\.[A-Za-z0-9_.-]+$/', $fbc)) $nutzer['fbc'] = $fbc;

  $id = preg_replace('/[^A-Za-z0-9_-]/', '', sauber('meta_id'));
  $ereignis = array(
    'event_name'       => 'Lead',
    'event_time'       => time(),
    'action_source'    => 'website',
    'event_source_url' => 'https://www.boesing-dentallabor.de/',
    'user_data'        => $nutzer,
  );
  if ($id !== '') $ereignis['event_id'] = $id;
  $daten = array('data' => json_encode(array($ereignis)), 'access_token' => $token);
  if (isset($konf['test']) && $konf['test'] !== '') $daten['test_event_code'] = $konf['test'];

  $c = curl_init('https://graph.facebook.com/' . $version . '/' . $pixel . '/events');
  curl_setopt_array($c, array(
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => http_build_query($daten),
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_CONNECTTIMEOUT => 3,
    CURLOPT_TIMEOUT => 5,
  ));
  $antwort = curl_exec($c);
  $code = curl_getinfo($c, CURLINFO_HTTP_CODE);
  curl_close($c);
  if ($code !== 200) { error_log('Meta CAPI: HTTP ' . $code . ' ' . mb_substr((string) $antwort, 0, 300)); }
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') { http_response_code(405); ende(false, 'Nur POST.'); }

/* --- Noch nicht scharf geschaltet: ehrlich Fehler melden, Ersatzweg greift --- */
if (strpos($von, 'DOMAIN-FOLGT') !== false) { http_response_code(503); ende(false, 'Der Versand ist noch nicht eingerichtet.'); }

/* --- Roboterpruefung: stiller Erfolg, damit der Absender nichts lernt --- */
if (sauber('website') !== '') { ende(true); }
$start = (int) sauber('zeit');
if ($start > 0 && (time() - $start) < 3) { ende(true); }

/* --- Felder (Namen wie im Formular) --- */
$praxis    = einzeilig(sauber('praxis'));
$person    = einzeilig(sauber('person'));
$ort       = einzeilig(sauber('ort'));
$telefon   = einzeilig(sauber('telefon'));
$email     = einzeilig(sauber('mail'));
$wunsch    = einzeilig(sauber('wunsch'));
$nachricht = sauber('nachricht');
$zustimmung = sauber('datenschutz');

if ($praxis === '' || $person === '' || $ort === '' || $telefon === '') { http_response_code(400); ende(false, 'Bitte füllen Sie die Pflichtfelder aus.'); }
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) { http_response_code(400); ende(false, 'Bitte prüfen Sie die E-Mail-Adresse.'); }
if ($zustimmung === '') { http_response_code(400); ende(false, 'Ohne Zustimmung zum Datenschutz können wir die Anfrage nicht bearbeiten.'); }

/* --- Mail bauen --- */
$betreff = 'Anfrage über die Praxis-Seite: ' . $praxis . ' - ' . $person;
$text  = "Neue Anfrage über die Praxis-Seite von Bösing Dental\n\n";
$text .= "Praxis:          $praxis\n";
$text .= "Ansprechperson:  $person\n";
$text .= "Ort:             $ort\n";
$text .= "Telefon:         $telefon\n";
$text .= "E-Mail:          $email\n";
if ($wunsch !== '') { $text .= "Kontaktwunsch:   $wunsch\n"; }
if ($nachricht !== '') { $text .= "\nNachricht:\n$nachricht\n"; }
$text .= "\nDatenschutz:     zugestimmt\n";
$text .= "\n---\nGesendet am " . date('d.m.Y, H:i') . " Uhr.\n";
$text .= "Antworten Sie einfach auf diese Mail, das geht direkt an die Praxis.\n";

$kopf  = 'From: Praxis-Seite Boesing Dental <' . $von . ">\r\n";   /* ohne Umlaut, sonst muesste der Name kodiert werden */
$kopf .= 'Reply-To: ' . $person . ' <' . $email . ">\r\n";
$kopf .= "Content-Type: text/plain; charset=UTF-8\r\n";
$kopf .= "X-Mailer: PHP/" . phpversion();

$betreff_kodiert = '=?UTF-8?B?' . base64_encode($betreff) . '?=';

if (@mail($an, $betreff_kodiert, $text, $kopf, '-f' . $von)) {
  meta_lead();
  ende(true);
}
http_response_code(500);
ende(false, 'Die Anfrage konnte nicht verschickt werden. Bitte rufen Sie uns an: 06721 491680');
