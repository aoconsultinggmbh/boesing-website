/* Bösing Dental Landingpage — Entwurf AO Consulting
   Jeder Block prüft, ob seine Elemente vorhanden sind, damit Unterseiten
   ohne Formular oder Video keinen Fehler werfen. */
(function(){
  'use strict';

  /* ---------------------------------------------------------- Navigation */
  var burger = document.querySelector('.burger');
  var nav = document.getElementById('hauptnavigation');
  if (burger && nav) {
    var zu = function(){ nav.classList.remove('offen'); burger.setAttribute('aria-expanded','false'); };
    burger.addEventListener('click', function(){
      var offen = nav.classList.toggle('offen');
      burger.setAttribute('aria-expanded', offen ? 'true' : 'false');
    });
    nav.addEventListener('click', function(e){ if (e.target.tagName === 'A') zu(); });
    document.addEventListener('keydown', function(e){
      if (e.key === 'Escape' && nav.classList.contains('offen')) { zu(); burger.focus(); }
    });
    window.addEventListener('resize', function(){ if (window.innerWidth > 1040) zu(); });
  }

  /* ------------------------------------------------- Video erst auf Klick */
  var knoepfe = document.querySelectorAll('.abspielen');
  Array.prototype.forEach.call(knoepfe, function(btn){
    btn.addEventListener('click', function(){
      var rahmen = btn.closest('.video-rahmen');
      if (!rahmen) return;
      var quelle = rahmen.getAttribute('data-video');
      var poster = rahmen.getAttribute('data-poster') || '';
      if (!quelle) {                       /* Entwurf: Datei liegt noch nicht vor */
        btn.disabled = true;
        btn.insertAdjacentHTML('afterend',
          '<p class="video-hinweis" style="position:absolute;bottom:.6rem;left:0;right:0;text-align:center;color:#fff">' +
          'Videodatei folgt, Platzhalter im Entwurf.</p>');
        return;
      }
      var v = document.createElement('video');
      v.setAttribute('controls','');
      v.setAttribute('playsinline','');
      v.setAttribute('preload','metadata');
      if (poster) v.setAttribute('poster', poster);
      v.style.width = '100%';
      v.style.display = 'block';
      v.innerHTML = '<source src="' + quelle + '" type="video/mp4">' +
        'Ihr Browser kann dieses Video nicht abspielen. ' +
        '<a href="' + quelle + '">Video herunterladen</a>';
      v.addEventListener('error', function(){
        rahmen.innerHTML = '<div class="bildplatz" style="border:none;border-radius:0;aspect-ratio:16/9;' +
          'background:linear-gradient(135deg,#0b2330,#134a63);color:#dff1f9">' +
          '<b>Videodatei nicht gefunden</b><small>Der Ordner videos muss neben dieser Datei liegen</small></div>';
      });
      rahmen.innerHTML = '';
      rahmen.appendChild(v);
      v.play().catch(function(){ /* Autoplay verweigert, Nutzer startet selbst */ });
    });
  });

  /* ------------------------------------------------------------ Formular */
  var form = document.getElementById('anfrage-formular');
  if (form) {
    var danke = document.getElementById('danke');
    var pruefen = function(feld){
      var wrap = feld.closest('.feld') || feld.closest('.zustimmung-block');
      var ok = feld.type === 'checkbox' ? feld.checked : feld.value.trim() !== '';
      if (ok && feld.type === 'email') ok = /^[^\s@]+@[^\s@]+\.[a-z]{2,}$/i.test(feld.value.trim());
      if (wrap) wrap.classList.toggle('hat-fehler', !ok);
      return ok;
    };
    form.addEventListener('submit', function(e){
      e.preventDefault();                 /* Entwurf verschickt nichts */
      var pflicht = form.querySelectorAll('[required]');
      var alleOk = true, ersterFehler = null;
      Array.prototype.forEach.call(pflicht, function(feld){
        if (!pruefen(feld)) { alleOk = false; if (!ersterFehler) ersterFehler = feld; }
      });
      if (!alleOk) { if (ersterFehler) ersterFehler.focus(); return; }
      if (danke) {
        form.hidden = true;
        danke.hidden = false;
        danke.setAttribute('tabindex','-1');
        danke.focus();
      }
    });
    Array.prototype.forEach.call(form.querySelectorAll('[required]'), function(feld){
      feld.addEventListener('blur', function(){ pruefen(feld); });
      feld.addEventListener('change', function(){ pruefen(feld); });
    });
  }
})();
