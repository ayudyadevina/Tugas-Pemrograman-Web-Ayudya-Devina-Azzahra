(function () {
  "use strict";

  var wrap = document.querySelector(".carousel-wrap");
  if (!wrap) return;

  var win = wrap.querySelector(".carousel-window");
  var track = wrap.querySelector(".carousel-track");
  var prevBtn = wrap.querySelector(".carousel-btn--prev");
  var nextBtn = wrap.querySelector(".carousel-btn--next");
  var DURATION = 450;
  var busy = false;
  var reduceMotion = window.matchMedia("(prefers-reduced-motion: reduce)").matches;

  function stepSize() {
    var card = track.firstElementChild;
    var gap = parseFloat(getComputedStyle(track).columnGap) || 0;
    return card.getBoundingClientRect().width + gap;
  }

  function animateTo(x, done) {
    if (reduceMotion) {
      track.style.transition = "none";
      track.style.transform = "translateX(" + x + "px)";
      done();
      return;
    }
    var finished = false;
    function finish() {
      if (finished) return;
      finished = true;
      track.removeEventListener("transitionend", onEnd);
      done();
    }
    function onEnd(e) {
      if (e.target === track && e.propertyName === "transform") finish();
    }
    track.addEventListener("transitionend", onEnd);
    setTimeout(finish, DURATION + 150); // cadangan bila transitionend tidak terpicu
    track.style.transition = "transform " + DURATION + "ms ease";
    track.style.transform = "translateX(" + x + "px)";
  }

  function go(direction) {
    if (busy || track.children.length < 2) return;
    busy = true;
    var s = stepSize();
    var clone;

    if (direction > 0) {
      // Geser ke kiri: gandakan kartu pertama di ujung kanan
      clone = track.firstElementChild.cloneNode(true);
      clone.setAttribute("aria-hidden", "true");
      track.appendChild(clone);
      animateTo(-s, function () {
        track.style.transition = "none";
        track.removeChild(track.firstElementChild); // kartu asli keluar, salinan jadi kartu asli
        clone.removeAttribute("aria-hidden");
        track.style.transform = "translateX(0)";
        busy = false;
      });
    } else {
      // Geser ke kanan: gandakan kartu terakhir di ujung kiri
      clone = track.lastElementChild.cloneNode(true);
      clone.setAttribute("aria-hidden", "true");
      track.insertBefore(clone, track.firstElementChild);
      track.style.transition = "none";
      track.style.transform = "translateX(" + -s + "px)";
      void track.offsetWidth; // paksa reflow agar posisi awal tercatat
      animateTo(0, function () {
        track.style.transition = "none";
        track.removeChild(track.lastElementChild);
        clone.removeAttribute("aria-hidden");
        busy = false;
      });
    }
  }

  prevBtn.addEventListener("click", function () { go(-1); });
  nextBtn.addEventListener("click", function () { go(1); });

  // Panah keyboard saat fokus berada di dalam carousel
  wrap.addEventListener("keydown", function (e) {
    if (e.key === "ArrowLeft") go(-1);
    else if (e.key === "ArrowRight") go(1);
  });

  // Swipe di layar sentuh / geser dengan pointer
  var startX = null;
  win.addEventListener("pointerdown", function (e) { startX = e.clientX; });
  win.addEventListener("pointerup", function (e) {
    if (startX === null) return;
    var dx = e.clientX - startX;
    startX = null;
    if (Math.abs(dx) > 50) go(dx < 0 ? 1 : -1);
  });
  win.addEventListener("pointercancel", function () { startX = null; });
})();