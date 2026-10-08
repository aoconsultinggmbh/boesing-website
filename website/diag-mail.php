<?php
/* Temporaere Diagnose 2 fuer den Mailversand (AO Consulting, 08.10.2026). Wird danach geloescht. */
header('Content-Type: text/plain; charset=utf-8');
if (!isset($_GET['k']) || $_GET['k'] !== 'ao-diag-3f8e2c') { http_response_code(404); exit('nicht gefunden'); }
$an  = 'tofik@ao-consult.de';
$von = 'anfrage@boesing-dentallabor.de';
$from = 'From: Praxis-Seite Boesing Dental <' . $von . ">\r\n";
$ct   = "Content-Type: text/plain; charset=UTF-8\r\n";
$reply = "Reply-To: Awan Tofik <tofik@ao-consult.de>\r\n";
$xm   = "X-Mailer: PHP/" . phpversion();
$bcc  = "Bcc: anfrage@boesing-dentallabor.de\r\n";
$betreffU = '=?UTF-8?B?' . base64_encode('Anfrage über die Praxis-Seite: Variante') . '?=';
$kurz = "Kurzer Testtext.\n";
$lang  = "Neue Anfrage über die Praxis-Seite von Bösing Dental\n\n";
$lang .= "Praxis:          Testpraxis\nAnsprechperson:  Awan Tofik\nOrt:             Bruchsal\nTelefon:         0000\nE-Mail:          tofik@ao-consult.de\nKontaktwunsch:   E-Mail\n\nNachricht:\nDiagnose Volltext.\n";
$lang .= "\nDatenschutz:     zugestimmt\n\n---\nGesendet am " . date('d.m.Y, H:i') . " Uhr.\nAntworten Sie einfach auf diese Mail, das geht direkt an die Praxis.\n";
$t = date('H:i:s');
$varianten = array(
  'A Basis'           => array($from . rtrim($ct), 'Diag A Basis ' . $t, $kurz),
  'B + Reply-To'      => array($from . $reply . rtrim($ct), 'Diag B Reply-To ' . $t, $kurz),
  'C + X-Mailer'      => array($from . $ct . $xm, 'Diag C X-Mailer ' . $t, $kurz),
  'D Betreff kodiert' => array($from . rtrim($ct), $betreffU . ' D ' . $t, $kurz),
  'E Volltext'        => array($from . rtrim($ct), 'Diag E Volltext ' . $t, $lang),
  'F + Bcc'           => array($from . $bcc . rtrim($ct), 'Diag F Bcc ' . $t, $kurz),
  'G alles wie Formular' => array($from . $reply . $bcc . $ct . $xm, $betreffU . ' G ' . $t, $lang),
);
foreach ($varianten as $name => $v) {
  error_clear_last();
  $ok = mail($an, $v[1], $v[2], $v[0], '-f' . $von);
  $f = error_get_last();
  echo str_pad($name, 24) . ($ok ? 'true' : 'false') . ($f ? '  Fehler: ' . $f['message'] : '') . "\n";
}
echo "\nUhrzeit: $t\n";
