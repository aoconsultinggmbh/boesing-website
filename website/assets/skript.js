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

  /* ------------------------------------- stumme Vorschau in Schleife */
  var ruhig = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  var sparen = navigator.connection && navigator.connection.saveData;
  if (!ruhig && !sparen) {
    Array.prototype.forEach.call(document.querySelectorAll('.video-rahmen[data-vorschau]'), function(rahmen){
      var v = document.createElement('video');
      v.className = 'video-vorschau';
      v.muted = true; v.loop = true; v.playsInline = true; v.autoplay = true;
      v.setAttribute('muted',''); v.setAttribute('playsinline',''); v.setAttribute('aria-hidden','true');
      v.setAttribute('preload','auto');
      var basis = rahmen.getAttribute('data-vorschau');   /* ohne Endung, MP4 und WebM liegen daneben */
      v.innerHTML = '<source src="' + basis + '.mp4" type="video/mp4">' +
                    '<source src="' + basis + '.webm" type="video/webm">';
      v.addEventListener('playing', function(){ v.classList.add('laeuft'); });
      var knopf = rahmen.querySelector('.abspielen');
      rahmen.insertBefore(v, knopf || null);
      /* nur abspielen, solange der Rahmen sichtbar ist */
      if ('IntersectionObserver' in window) {
        new IntersectionObserver(function(eintraege){
          eintraege.forEach(function(e){
            if (e.isIntersecting) { v.play().catch(function(){}); } else { v.pause(); }
          });
        }, { threshold: .25 }).observe(rahmen);
      } else {
        v.play().catch(function(){});
      }
    });
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
