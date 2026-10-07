/* ============================================================================
   KONFIGURATION fuer Einwilligungsbanner und Messung.
   DIESE DATEI IST DIE EINZIGE, DIE PRO KUNDE ANGEPASST WIRD.

   VOR DEM LIVEGANG EINTRAGEN:
     ga4        Messkennung aus dem Google-Analytics-Konto DES KUNDEN ('G-...')
     metaPixel  Pixel-ID aus dem Meta-Werbekonto DES KUNDEN (nur Ziffern)

   Solange beide leer sind, wird nichts geladen und nichts gemessen.
   Genau so bleibt es auf der Vorschau.

   NICHT VERGESSEN (liegt beim Kunden, nicht bei uns):
     - eigenes Google-Konto und Auftragsverarbeitung mit Google
     - Datenschutzerklaerung muss zu dem passen, was hier eingeschaltet wird
   ============================================================================ */

window.AO_MESSUNG = {
  ga4: '',
  metaPixel: '1019905141107119'   // Meta-Pixel im Werbekonto von Bösing Dental, eingetragen 02.10.2026
};

window.AO_EINWILLIGUNG = {
  datenschutz: '/datenschutz.html',
  // Haupttext des Fensters. Muss zu den eingesetzten Diensten passen (hier: nur Meta-Pixel).
  text: 'Diese Website nutzt nur, was für den Betrieb nötig ist, und zählt Besuche anonym und ohne Cookies. Mit Ihrer Zustimmung laden wir zusätzlich den Meta-Pixel. Er misst, ob unsere Anzeigen bei Facebook und Instagram zu Anfragen führen. Dabei werden Daten wie Ihre IP-Adresse an Meta übertragen, auch in die USA. Deshalb fragen wir vorher.',
  fein: 'Ohne Zustimmung wird keine Verbindung zu Meta aufgebaut. Ihre Wahl gilt 12 Monate und lässt sich jederzeit über „Cookie-Einstellungen" in der Fußzeile ändern.',
  impressum: '/impressum.html',
  kategorien: [
    {
      id: 'notwendig',
      name: 'Notwendig',
      kurz: 'Hält die Website funktionsfähig und speichert Ihre Entscheidung aus diesem Fenster. Ohne diese Funktionen lässt sich die Seite nicht sinnvoll anzeigen.',
      pflicht: true,
      dienste: [{
        name: 'Einwilligungsspeicher',
        anbieter: 'Bösing Dental GmbH & Co. KG, Franz-Kirsten-Straße 1, 55411 Bingen am Rhein',
        zweck: 'Speichert, welchen Diensten Sie zugestimmt haben, damit Sie nicht bei jedem Aufruf erneut gefragt werden. Gespeichert werden nur die gewählten Kategorien und der Zeitpunkt, keine Kennung.',
        art: 'Lokaler Speicher im Browser, kein Cookie',
        dauer: '12 Monate'
      }]
    },
  ]
};

/* Die Kategorien Statistik und Marketing erscheinen im Fenster nur dann, wenn oben
   auch wirklich eine Kennung eingetragen ist. Eine leere Kategorie anzubieten waere
   irrefuehrend — und solange nichts eingetragen ist, erscheint gar kein Banner.
   nurWennEingetragen */
(function () {
  var m = window.AO_MESSUNG || {};
  var k = window.AO_EINWILLIGUNG.kategorien;
  if (String(m.ga4 || '').trim()) {
    k.push({
      id: 'statistik',
      name: 'Statistik',
      kurz: 'Hilft uns zu verstehen, welche Seiten gelesen werden und wo Besucher nicht weiterkommen. Erst mit Ihrer Zustimmung wird dafür Google Analytics geladen.',
      dienste: [{
        name: 'Google Analytics 4',
        anbieter: 'Google Ireland Limited, Gordon House, Barrow Street, Dublin 4, Irland',
        zweck: 'Zählt Besuche und Seitenaufrufe, zeigt Herkunft, Gerät und ungefähre Region.',
        art: 'Cookies und Kennungen im Browser. Ihre IP-Adresse wird gekürzt. Eine Verarbeitung in den USA ist möglich; Google beruft sich dafür auf das EU-US Data Privacy Framework.',
        dauer: 'Bis zu 14 Monate'
      }]
    });
  }
  if (String(m.metaPixel || '').trim()) {
    k.push({
      id: 'marketing',
      name: 'Marketing',
      kurz: 'Misst, ob eine Anzeige bei Facebook oder Instagram zu einem Besuch oder einer Anfrage geführt hat, und ermöglicht passendere Werbung. Erst mit Ihrer Zustimmung wird dafür der Meta-Pixel geladen.',
      dienste: [
        {
          name: 'Meta-Pixel (Facebook, Instagram)',
          anbieter: 'Meta Platforms Ireland Limited, Merrion Road, Dublin 4, D04 X2K5, Irland',
          zweck: 'Erkennt, ob ein Besuch aus einer Anzeige kam, und zählt erfolgreich abgeschickte Anfragen. Der Inhalt Ihrer Anfrage wird nicht an Meta übermittelt.',
          art: 'Cookies und Kennungen im Browser. IP-Adresse, Geräte- und Seitendaten gehen an Meta; wir und Meta sind dafür gemeinsam verantwortlich. Verarbeitung auch in den USA möglich (EU-US Data Privacy Framework).',
          dauer: 'Cookies bis zu 90 Tage'
        }
      ]
    });
  }
})();
