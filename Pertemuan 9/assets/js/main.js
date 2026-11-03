// Mode terang/gelap
(function () {
  var b = document.getElementById('themeBtn');
  if (b) b.addEventListener('click', function () {
    var r = document.documentElement, n = r.dataset.theme === 'dark' ? 'light' : 'dark';
    r.dataset.theme = n; try { localStorage.setItem('theme', n); } catch (e) {}
  });
})();

// Slider headline
(function () {
  var s = document.querySelector('.slider'); if (!s) return;
  var slides = s.querySelectorAll('.slide'), i = 0, t;
  function go(n) { slides[i].classList.remove('on'); i = (n + slides.length) % slides.length; slides[i].classList.add('on'); }
  function auto() { clearInterval(t); if (!matchMedia('(prefers-reduced-motion: reduce)').matches) t = setInterval(function () { go(i + 1); }, 6000); }
  s.querySelectorAll('[data-dir]').forEach(function (b) {
    b.addEventListener('click', function () { go(i + Number(b.dataset.dir)); auto(); });
  });
  s.addEventListener('mouseenter', function () { clearInterval(t); });
  s.addEventListener('mouseleave', auto);
  auto();
})();

// Validasi sisi klien (komentar & login)
document.querySelectorAll('form[data-validate]').forEach(function (f) {
  f.addEventListener('submit', function (ev) {
    var bad = null;
    f.querySelectorAll('[required]').forEach(function (el) {
      var ok = el.value.trim() !== '' && el.checkValidity();
      el.classList.toggle('invalid', !ok);
      if (!ok && !bad) bad = el;
    });
    if (bad) { ev.preventDefault(); bad.focus(); var m = f.querySelector('.js-msg'); if (m) { m.hidden = false; m.textContent = 'Lengkapi kolom yang ditandai.'; } }
  });
});
