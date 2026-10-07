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
    /* Versand: erst an das Skript auf dem Server (anfrage-senden.php). Antwortet
       der Server nicht (Vorschau auf GitHub, Versand noch nicht eingerichtet,
       Störung), öffnet sich als Ersatzweg das Mailprogramm mit fertigem Text.
       So geht keine Anfrage verloren. */
    var EMPFAENGER = 'nboesing@boesing-dental.de';
    var ENDPUNKT = 'anfrage-senden.php';
    var zeitfeld = form.querySelector('input[name="zeit"]');
    if (zeitfeld) zeitfeld.value = String(Math.floor(Date.now() / 1000));
    var knopf = form.querySelector('button[type="submit"]');
    var knopfText = knopf ? knopf.textContent : '';

    var wert = function(name){ var f = form.querySelector('[name="' + name + '"]'); return f ? f.value.trim() : ''; };
    var mailText = function(){
      return 'Praxis: ' + wert('praxis') + '\n' +
             'Ansprechperson: ' + wert('person') + '\n' +
             'Ort: ' + wert('ort') + '\n' +
             'Telefon: ' + wert('telefon') + '\n' +
             'E-Mail: ' + wert('mail') + '\n' +
             'Kontaktwunsch: ' + wert('wunsch') + '\n\n' +
             'Nachricht:\n' + wert('nachricht') + '\n';
    };
    var oeffneMail = function(){
      var betreff = 'Anfrage über die Praxis-Seite: ' + wert('praxis');
      window.location.href = 'mailto:' + EMPFAENGER +
        '?subject=' + encodeURIComponent(betreff) +
        '&body=' + encodeURIComponent(mailText());
    };
    var zeigeDanke = function(ersatz){
      if (!danke) return;
      form.hidden = true;
      danke.hidden = false;
      var block = danke.querySelector('#ersatzweg');
      if (block) block.hidden = !ersatz;
      danke.setAttribute('tabindex','-1');
      danke.focus();
      var nochmal = danke.querySelector('#mail-nochmal');
      if (nochmal) nochmal.onclick = function(ev){ ev.preventDefault(); oeffneMail(); };
    };
    var sperre = function(an){
      if (!knopf) return;
      knopf.disabled = an;
      knopf.textContent = an ? 'Wird gesendet …' : knopfText;
    };

    form.addEventListener('submit', function(e){
      e.preventDefault();
      var pflicht = form.querySelectorAll('[required]');
      var alleOk = true, ersterFehler = null;
      Array.prototype.forEach.call(pflicht, function(feld){
        if (!pruefen(feld)) { alleOk = false; if (!ersterFehler) ersterFehler = feld; }
      });
      if (!alleOk) { if (ersterFehler) ersterFehler.focus(); return; }
      /* Honigtopf gefüllt: ein Roboter. Danke anzeigen, nichts verschicken. */
      if (wert('website') !== '') { zeigeDanke(false); return; }

      sperre(true);
      var daten = new FormData(form);
      /* Meta Conversions API: nur mit Zustimmung zu "Marketing". Dann gehen
         eine zufaellige Ereignis-ID (damit Meta Pixel und Server nicht doppelt
         zaehlt) und die Meta-Kennungen _fbp/_fbc mit. Ohne Zustimmung: nichts. */
      var metaId = '';
      try {
        if (window.aoEinwilligung && window.aoEinwilligung.erlaubt('marketing')) {
          metaId = 'lead-' + Date.now() + '-' + Math.random().toString(36).slice(2, 10);
          var keks = function(n){ var m = document.cookie.match('(?:^|; )' + n + '=([^;]*)'); return m ? decodeURIComponent(m[1]) : ''; };
          daten.append('meta_ok', '1');
          daten.append('meta_id', metaId);
          daten.append('meta_fbp', keks('_fbp'));
          daten.append('meta_fbc', keks('_fbc'));
        }
      } catch (e) {}
      var ersatzweg = function(){
        sperre(false);
        zeigeDanke(true);
        window.setTimeout(oeffneMail, 500);
      };
      if (!window.fetch) { ersatzweg(); return; }
      fetch(ENDPUNKT, { method: 'POST', body: daten })
        .then(function(a){ return a.json()['catch'](function(){ return { ok: a.ok }; }); })
        .then(function(a){
          if (a && a.ok) {
            sperre(false); zeigeDanke(false);
            /* Für die Messung (messung.js): nur das Ereignis, keine Inhalte */
            try { document.dispatchEvent(new CustomEvent('ao:anfrage-gesendet', { detail: { id: metaId } })); } catch (e) {}
            return;
          }
          ersatzweg();
        })
        ['catch'](ersatzweg);
    });
    Array.prototype.forEach.call(form.querySelectorAll('[required]'), function(feld){
      feld.addEventListener('blur', function(){ pruefen(feld); });
      feld.addEventListener('change', function(){ pruefen(feld); });
    });
  }
})();
