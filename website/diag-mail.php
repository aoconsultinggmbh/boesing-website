<?php
/* Temporaere Diagnose fuer den Mailversand (AO Consulting, 08.10.2026). Wird danach geloescht. */
header('Content-Type: text/plain; charset=utf-8');
if (!isset($_GET['k']) || $_GET['k'] !== 'ao-diag-3f8e2c') { http_response_code(404); exit('nicht gefunden'); }
echo "PHP: " . phpversion() . "\n";
echo "sendmail_path: " . ini_get('sendmail_path') . "\n";
echo "mail.add_x_header: " . ini_get('mail.add_x_header') . "\n";
echo "mail.force_extra_parameters: " . ini_get('mail.force_extra_parameters') . "\n";
echo "disable_functions: " . ini_get('disable_functions') . "\n";
echo "SERVER_NAME: " . ($_SERVER['SERVER_NAME'] ?? '') . "  HOST: " . gethostname() . "\n";
echo "Script-Pfad: " . __DIR__ . "\n";
echo "mail-Funktion vorhanden: " . (function_exists('mail') ? 'ja' : 'nein') . "\n\n";
foreach (array(array('w02227af.kasserver.com', 587), array('w02227af.kasserver.com', 465), array('localhost', 25)) as $z) {
  $e = 0; $s = ''; $t = microtime(true);
  $fp = @fsockopen(($z[1] == 465 ? 'ssl://' : '') . $z[0], $z[1], $e, $s, 5);
  echo "Verbindung {$z[0]}:{$z[1]}: " . ($fp ? 'OK ' . trim(fgets($fp, 256)) : "FEHLER $e $s") . ' (' . round(microtime(true) - $t, 1) . "s)\n";
  if ($fp) fclose($fp);
}
echo "\n";
$an = 'tofik@ao-consult.de';
$von = 'anfrage@boesing-dentallabor.de';
$kopf = "From: Diagnose <$von>\r\nContent-Type: text/plain; charset=UTF-8";
error_clear_last();
$ok1 = mail($an, 'Diagnose 1 (mit -f) ' . date('H:i:s'), "Testmail 1 aus diag-mail.php mit -f Parameter.\n", $kopf, '-f' . $von);
$f1 = error_get_last();
echo "mail() mit -f:   " . ($ok1 ? 'true' : 'false') . ($f1 ? '  Fehler: ' . $f1['message'] : '') . "\n";
error_clear_last();
$ok2 = mail($an, 'Diagnose 2 (ohne -f) ' . date('H:i:s'), "Testmail 2 aus diag-mail.php ohne -f Parameter.\n", $kopf);
$f2 = error_get_last();
echo "mail() ohne -f:  " . ($ok2 ? 'true' : 'false') . ($f2 ? '  Fehler: ' . $f2['message'] : '') . "\n";
error_clear_last();
$ok3 = mail($an, 'Diagnose 3 (nackt) ' . date('H:i:s'), "Testmail 3 ohne eigene Kopfzeilen.\n");
$f3 = error_get_last();
echo "mail() nackt:    " . ($ok3 ? 'true' : 'false') . ($f3 ? '  Fehler: ' . $f3['message'] : '') . "\n";
