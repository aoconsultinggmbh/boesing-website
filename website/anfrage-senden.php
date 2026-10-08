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
$cc    = 'tofik@ao-consult.de';            // Kopie an die AO Consulting (Fehlersuche Okt. 2026, auf Wunsch Awan)
$kopie = 'anfrage@boesing-dentallabor.de';  // Sicherheitskopie jeder Anfrage (Bcc) ins eigene
                                            // Postfach auf demselben Server. Grund: Das Postfach des
                                            // Empfaengers liegt bei Microsoft 365, dort kamen Anfragen
                                            // nicht an (Okt. 2026). Hier geht nichts verloren.

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

/* --- Versand per SMTP mit Anmeldung am Postfach ----------------------------
   Auf diesem Konto verschickt die PHP-Funktion mail() nichts, obwohl sie
   Erfolg meldet (geprueft am 08.10.2026). Darum meldet sich das Skript wie
   ein Mailprogramm am Postfach $von an und liefert die Mail direkt beim
   Mailserver des Kontos ein. Das Passwort schreibt der Livegang-Ablauf aus
   dem GitHub-Secret FORMULAR_SMTP_PASSWORT in die Datei smtp-zugang.php;
   es steht nie im Projekt. Fehlt die Datei, bleibt es beim alten Weg mail().
   Rueckgabe: '' bei Erfolg, sonst der Fehlertext (fuer das Fehlerprotokoll). */
function smtp_senden($von, $an_liste, $betreff_kodiert, $text, $kopfzeilen) {
  $datei = __DIR__ . '/smtp-zugang.php';
  if (!is_file($datei)) return 'keine Zugangsdatei';
  $konf = include $datei;
  $passwort = is_array($konf) && isset($konf['passwort']) ? (string) $konf['passwort'] : '';
  $server   = is_array($konf) && isset($konf['server']) && $konf['server'] !== '' ? $konf['server'] : 'w02227af.kasserver.com';
  if ($passwort === '') return 'kein Passwort';

  $fehler = 0; $meldung = '';
  $fp = @stream_socket_client('ssl://' . $server . ':465', $fehler, $meldung, 10);
  if (!$fp) return "Verbindung: $fehler $meldung";
  stream_set_timeout($fp, 10);

  $lies = function () use ($fp) {
    $antwort = '';
    while (($zeile = fgets($fp, 515)) !== false) {
      $antwort .= $zeile;
      if (strlen($zeile) < 4 || $zeile[3] !== '-') break;   /* letzte Zeile einer Mehrzeilen-Antwort */
    }
    return $antwort;
  };
  $sag = function ($befehl, $erwartet) use ($fp, $lies) {
    fwrite($fp, $befehl . "\r\n");
    $antwort = $lies();
    if (strpos($antwort, (string) $erwartet) !== 0) throw new RuntimeException(trim($befehl === '' ? '' : strtok($befehl, ' ')) . ': ' . trim($antwort));
    return $antwort;
  };

  try {
    if (strpos($lies(), '220') !== 0) throw new RuntimeException('keine Begruessung');
    $sag('EHLO www.boesing-dentallabor.de', 250);
    $sag('AUTH LOGIN', 334);
    $sag(base64_encode($von), 334);
    $sag(base64_encode($passwort), 235);
    $sag('MAIL FROM:<' . $von . '>', 250);
    foreach ($an_liste as $empf) { $sag('RCPT TO:<' . $empf . '>', 250); }
    $sag('DATA', 354);
    $mail  = $kopfzeilen . "\r\n";
    $mail .= 'Subject: ' . $betreff_kodiert . "\r\n";
    $mail .= 'Date: ' . date('r') . "\r\n";
    $mail .= 'Message-ID: <' . bin2hex(random_bytes(12)) . '@boesing-dentallabor.de>' . "\r\n";
    $mail .= "MIME-Version: 1.0\r\n";
    $mail .= "\r\n";
    $mail .= preg_replace('/^\./m', '..', str_replace("\n", "\r\n", str_replace("\r\n", "\n", $text)));
    $sag($mail . "\r\n.", 250);
    fwrite($fp, "QUIT\r\n");
    fclose($fp);
    return '';
  } catch (RuntimeException $e) {
    @fclose($fp);
    return $e->getMessage();
  }
}

/* --- Eingangsbestaetigung an die anfragende Praxis ----------------------------
   Geht nach erfolgreichem Versand der Anfrage an die im Formular angegebene
   Adresse. Absender ist das Postfach $von, Antworten landen bei $an.
   Klappt die Bestaetigung nicht, ist das kein Fehler fuer den Besucher: die
   Anfrage selbst ist da schon verschickt. (Wunsch Awan, 08.10.2026) */
function bestaetigung_senden($von, $an, $email, $person, $praxis, $ort, $telefon, $wunsch, $nachricht) {
  $betreff = 'Ihre Anfrage bei Bösing Dental ist angekommen';
  $text  = "Guten Tag " . $person . ",\n\n";
  $text .= "vielen Dank für Ihre Anfrage über unsere Praxis-Seite. Sie ist bei uns angekommen.\n";
  $text .= "Wir melden uns innerhalb von 48 Stunden bei Ihnen und klären am Telefon, ob wir zu Ihrer Praxis passen.\n\n";
  $text .= "Ihre Angaben im Überblick:\n";
  $text .= "Praxis:          $praxis\n";
  $text .= "Ansprechperson:  $person\n";
  $text .= "Ort:             $ort\n";
  $text .= "Telefon:         $telefon\n";
  $text .= "E-Mail:          $email\n";
  if ($wunsch !== '') { $text .= "Kontaktwunsch:   $wunsch\n"; }
  if ($nachricht !== '') { $text .= "\nIhre Nachricht:\n$nachricht\n"; }
  $text .= "\nSie möchten nicht warten? Rufen Sie uns einfach an: 06721 491680\n\n";
  $text .= "Herzliche Grüße\n";
  $text .= "Ihr Team von Bösing Dental\n\n";
  $text .= "Bösing Dental GmbH & Co. KG, Bingen am Rhein\n";
  $text .= "www.boesing-dentallabor.de\n";

  $kopf  = 'From: Boesing Dental <' . $von . ">\r\n";
  $kopf .= 'Reply-To: Boesing Dental <' . $an . ">\r\n";
  $kopf .= "Content-Type: text/plain; charset=UTF-8\r\n";
  $kopf .= "Content-Transfer-Encoding: 8bit";
  $betreff_kodiert = '=?UTF-8?B?' . base64_encode($betreff) . '?=';
  if (!@mail($email, $betreff_kodiert, $text, $kopf, '-f' . $von)) { error_log('Formular: Eingangsbestaetigung an ' . $email . ' fehlgeschlagen'); }
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
if ($cc !== '') { $kopf .= 'Cc: ' . $cc . "\r\n"; }
if ($kopie !== '' && $kopie !== $an) { $kopf .= 'Bcc: ' . $kopie . "\r\n"; }
$kopf .= "Content-Type: text/plain; charset=UTF-8\r\n";
$kopf .= "X-Mailer: PHP/" . phpversion();

$betreff_kodiert = '=?UTF-8?B?' . base64_encode($betreff) . '?=';

/* Erst SMTP mit Anmeldung (zuverlaessig), nur ohne Zugangsdatei der alte Weg mail(). */
$empfaenger = array($an);
if ($cc !== '') { $empfaenger[] = $cc; }
if ($kopie !== '' && $kopie !== $an) { $empfaenger[] = $kopie; }
$kopf_smtp  = 'From: Praxis-Seite Boesing Dental <' . $von . ">\r\n";
$kopf_smtp .= 'To: ' . $an . "\r\n";
if ($cc !== '') { $kopf_smtp .= 'Cc: ' . $cc . "\r\n"; }
$kopf_smtp .= 'Reply-To: ' . $person . ' <' . $email . ">\r\n";
$kopf_smtp .= "Content-Type: text/plain; charset=UTF-8\r\n";
$kopf_smtp .= "Content-Transfer-Encoding: 8bit\r\n";
$kopf_smtp .= 'X-Mailer: Praxis-Seite Boesing Dental';   /* Bcc absichtlich nicht im Kopf */

$smtp_fehler = smtp_senden($von, $empfaenger, $betreff_kodiert, $text, $kopf_smtp);
if ($smtp_fehler === '') { bestaetigung_senden($von, $an, $email, $person, $praxis, $ort, $telefon, $wunsch, $nachricht); meta_lead(); ende(true); }
if ($smtp_fehler !== 'keine Zugangsdatei') { error_log('Formular SMTP: ' . $smtp_fehler); }
if (sauber('diag') === 'ao-diag-3f8e2c') { http_response_code(500); ende(false, 'SMTP: ' . $smtp_fehler); }   /* nur fuer die Fehlersuche durch die AO Consulting */

if (@mail($an, $betreff_kodiert, $text, $kopf, '-f' . $von)) {
  bestaetigung_senden($von, $an, $email, $person, $praxis, $ort, $telefon, $wunsch, $nachricht);
  meta_lead();
  ende(true);
}
http_response_code(500);
ende(false, 'Die Anfrage konnte nicht verschickt werden. Bitte rufen Sie uns an: 06721 491680');
