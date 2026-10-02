<?php
/* =====================================================
   HERO SLIDER - DB se banner images (home_bane_img_1..4)
   Jitni images upload hain utni hi slides dikhengi
   (4 ho to 4, 3 ho to 3, 2 ho to 2, 1 ho to 1, 0 ho to kuch nahi)
===================================================== */

include_once "admin_access/db_config.php";
include_once "admin_access/functions/extra_home.php";

$pmhData = get_extra_home_images($mydb);
$pmhPath = "assets/extra_home_img/";

$pmhImages = [];

for ($i = 1; $i <= 4; $i++) {
    $col = 'home_bane_img_' . $i;

    if (!empty($pmhData[$col])) {
        $pmhImages[] = $pmhData[$col];
    }
}

$pmhTotal = count($pmhImages);
?>

<?php if ($pmhTotal > 0): ?>

<style>
  .pmh {
    --pmh-time: 5000ms;
    --pmh-ratio: 16 / 6;
    --pmh-mobile-height: 26rem;
    --pmh-mobile-pos: 83% center;
    position: relative;
    display: block;
    width: 100%;
    overflow: hidden;
  }

  .pmh__viewport {
    position: relative;
    display: grid;
    grid-template-columns: minmax(0, 1fr);
    width: 100%;
    touch-action: pan-y pinch-zoom;
  }

  .pmh__slide {
    position: relative;
    grid-area: 1 / 1;
    overflow: hidden;
    opacity: 0;
    visibility: hidden;
    transition: opacity 1s ease, visibility 0s linear 1s;
  }

  .pmh__slide.is-active {
    opacity: 1;
    visibility: visible;
    z-index: 1;
    transition: opacity 1s ease, visibility 0s;
  }

  .pmh__picture {
    display: block;
    width: 100%;
    aspect-ratio: var(--pmh-ratio);
    overflow: hidden;
  }

  .pmh__img {
    display: block;
    width: 100%;
    height: 100%;
    max-width: none;
    object-fit: cover;
    object-position: center;
    transform: scale(1.06);
    transition: transform 0s linear 1s;
  }

  .pmh__slide.is-active .pmh__img {
    transform: scale(1);
    transition: transform 8s ease-out;
  }

  .pmh__arrow {
    position: absolute;
    top: 50%;
    width: 40px;
    height: 40px;
    margin: -20px 0 0;
    padding: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 0;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.92);
    color: #1a1a1a;
    cursor: pointer;
    z-index: 5;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.18);
    -webkit-tap-highlight-color: transparent;
    transition: transform 0.25s ease, box-shadow 0.25s ease, background 0.25s ease;
  }

  .pmh__arrow--prev { left: 12px; }
  .pmh__arrow--next { right: 12px; }

  .pmh__arrow svg { width: 18px; height: 18px; transition: transform 0.25s ease; }
  .pmh__arrow:active { transform: scale(0.92); }

  .pmh__dots {
    position: absolute;
    left: 0;
    right: 0;
    bottom: 10px;
    display: flex;
    justify-content: center;
    z-index: 5;
  }

  .pmh__dot {
    position: relative;
    width: 30px;
    height: 20px;
    padding: 0;
    border: 0;
    background: none;
    cursor: pointer;
    -webkit-tap-highlight-color: transparent;
    transition: width 0.35s ease;
  }

  .pmh__dot.is-active { width: 54px; }

  .pmh__dot::before {
    content: '';
    position: absolute;
    left: 4px;
    right: 4px;
    top: 50%;
    height: 4px;
    margin-top: -2px;
    border-radius: 4px;
    background: rgba(255, 255, 255, 0.5);
    box-shadow: 0 0 4px rgba(0, 0, 0, 0.25);
    transition: background 0.25s ease;
  }

  .pmh__fill {
    position: absolute;
    left: 4px;
    right: 4px;
    top: 50%;
    height: 4px;
    margin-top: -2px;
    border-radius: 4px;
    background: #ffffff;
    transform: scaleX(0);
    transform-origin: left center;
  }

  .pmh__dot.is-active .pmh__fill { animation: pmhFill var(--pmh-time) linear forwards; }
  .pmh.is-paused .pmh__dot.is-active .pmh__fill { animation-play-state: paused; }

  /* Sirf 1 image ho to arrows + dots nahi dikhenge */
  .pmh--single .pmh__arrow,
  .pmh--single .pmh__dots { display: none; }

  .pmh__arrow:focus-visible,
  .pmh__dot:focus-visible { outline: 2px solid #ffffff; outline-offset: 2px; }

  @media (hover: hover) {
    .pmh__arrow:hover { background: #ffffff; transform: scale(1.12); box-shadow: 0 6px 16px rgba(0, 0, 0, 0.28); }
    .pmh__arrow--prev:hover svg { transform: translateX(-2px); }
    .pmh__arrow--next:hover svg { transform: translateX(2px); }
    .pmh__dot:hover::before { background: rgba(255, 255, 255, 0.8); }
  }

  @keyframes pmhFill { from { transform: scaleX(0); } to { transform: scaleX(1); } }

  @media (max-width: 991px) {
    .pmh__arrow { width: 36px; height: 36px; margin-top: -18px; }
    .pmh__arrow svg { width: 16px; height: 16px; }
  }

  @media (max-width: 767px) {
    .pmh__arrow { width: 30px; height: 30px; margin-top: -15px; }
    .pmh__arrow svg { width: 14px; height: 14px; }
    .pmh__arrow--prev { left: 8px; }
    .pmh__arrow--next { right: 8px; }
    .pmh__dots { bottom: 4px; }
    .pmh__dot { width: 24px; }
    .pmh__dot.is-active { width: 42px; }

    .pmh__picture { aspect-ratio: auto; height: var(--pmh-mobile-height); }

    .pmh__img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      object-position: var(--pmh-mobile-pos);
    }
  }

  @media (prefers-reduced-motion: reduce) {
    .pmh__slide, .pmh__img, .pmh__fill, .pmh__arrow, .pmh__arrow svg, .pmh__dot, .pmh__dot::before {
      transition: none;
      animation: none;
    }
  }
</style>


<section
  class="pmh<?php echo $pmhTotal === 1 ? ' pmh--single' : ''; ?>"
  data-interval="3000"
  aria-roledescription="carousel"
  aria-label="Offers banner"
>
  <div class="pmh__viewport">

    <?php foreach ($pmhImages as $pmhIndex => $pmhImg): ?>

      <!-- SLIDE <?php echo $pmhIndex + 1; ?> -->
      <div
        class="pmh__slide<?php echo $pmhIndex === 0 ? ' is-active' : ''; ?>"
        aria-roledescription="slide"
        aria-label="<?php echo $pmhIndex + 1; ?> of <?php echo $pmhTotal; ?>"
        aria-hidden="<?php echo $pmhIndex === 0 ? 'false' : 'true'; ?>"
      >
        <picture class="pmh__picture">
          <img
            class="pmh__img"
            src="<?php echo htmlspecialchars($pmhPath . $pmhImg); ?>"
            alt="Kalora Banner <?php echo $pmhIndex + 1; ?>"
            loading="<?php echo $pmhIndex === 0 ? 'eager' : 'lazy'; ?>"
          >
        </picture>
      </div>

    <?php endforeach; ?>

    <?php if ($pmhTotal > 1): ?>

      <!-- ARROWS -->
      <button class="pmh__arrow pmh__arrow--prev" type="button" aria-label="Previous slide">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
          <polyline points="15 5 8 12 15 19" />
        </svg>
      </button>

      <button class="pmh__arrow pmh__arrow--next" type="button" aria-label="Next slide">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
          <polyline points="9 5 16 12 9 19" />
        </svg>
      </button>

      <!-- DOTS (JS khud banayega) -->
      <div class="pmh__dots"></div>

    <?php endif; ?>

  </div>
</section>


<script>
  (function () {
    "use strict";

    var roots = document.querySelectorAll('.pmh');
    Array.prototype.forEach.call(roots, initSlider);

    function initSlider(root) {
      var slides = Array.prototype.slice.call(root.querySelectorAll('.pmh__slide'));
      var dotsWrap = root.querySelector('.pmh__dots');
      var prevBtn = root.querySelector('.pmh__arrow--prev');
      var nextBtn = root.querySelector('.pmh__arrow--next');
      var viewport = root.querySelector('.pmh__viewport');
      var total = slides.length;

      /* 1 ya 0 slide ho to slider ki jaruat nahi */
      if (total < 2 || !dotsWrap) return;

      var interval = parseInt(root.getAttribute('data-interval'), 10) || 5000;
      root.style.setProperty('--pmh-time', interval + 'ms');

      var current = 0;
      slides.forEach(function (s, i) {
        if (s.classList.contains('is-active')) current = i;
      });

      /* dots (har dot ke andar progress fill) */
      var dots = [];
      slides.forEach(function (_, i) {
        var dot = document.createElement('button');
        dot.type = 'button';
        dot.className = 'pmh__dot';
        dot.setAttribute('aria-label', 'Go to slide ' + (i + 1));

        var fill = document.createElement('span');
        fill.className = 'pmh__fill';
        dot.appendChild(fill);

        dot.addEventListener('click', function () { go(i); });

        /* progress bar poori hone par agli slide */
        fill.addEventListener('animationend', function () {
          if (dot.classList.contains('is-active')) go(current + 1);
        });

        dotsWrap.appendChild(dot);
        dots.push(dot);
      });

      function render() {
        slides.forEach(function (s, i) {
          var on = i === current;
          s.classList.toggle('is-active', on);
          s.setAttribute('aria-hidden', on ? 'false' : 'true');
        });
        dots.forEach(function (d, i) {
          d.classList.toggle('is-active', i === current);
        });
      }

      function go(i) {
        var next = (i + total) % total;
        if (next === current) return;
        current = next;
        render();
      }

      if (prevBtn) prevBtn.addEventListener('click', function () { go(current - 1); });
      if (nextBtn) nextBtn.addEventListener('click', function () { go(current + 1); });

      /* pause / resume */
      var hoverPause = false, offscreen = false, tabHidden = false;

      function updatePause() {
        root.classList.toggle('is-paused', hoverPause || offscreen || tabHidden);
      }

      root.addEventListener('pointerenter', function (e) {
        if (e.pointerType === 'mouse') { hoverPause = true; updatePause(); }
      });
      root.addEventListener('pointerleave', function (e) {
        if (e.pointerType === 'mouse') { hoverPause = false; updatePause(); }
      });
      root.addEventListener('focusin', function (e) {
        var keyboard = false;
        try { keyboard = e.target.matches(':focus-visible'); } catch (err) {}
        if (keyboard) { hoverPause = true; updatePause(); }
      });
      root.addEventListener('focusout', function () { hoverPause = false; updatePause(); });

      document.addEventListener('visibilitychange', function () {
        tabHidden = document.hidden;
        updatePause();
      });

      if ('IntersectionObserver' in window) {
        new IntersectionObserver(function (entries) {
          offscreen = !entries[0].isIntersecting;
          updatePause();
        }, { threshold: 0.2 }).observe(root);
      }

      /* swipe (mobile) */
      var startX = 0, startY = 0, touching = false;

      viewport.addEventListener('touchstart', function (e) {
        touching = true;
        startX = e.touches[0].clientX;
        startY = e.touches[0].clientY;
        hoverPause = true;
        updatePause();
      }, { passive: true });

      viewport.addEventListener('touchend', function (e) {
        if (!touching) return;
        touching = false;
        var dx = e.changedTouches[0].clientX - startX;
        var dy = e.changedTouches[0].clientY - startY;
        if (Math.abs(dx) > 40 && Math.abs(dx) > Math.abs(dy)) go(dx < 0 ? current + 1 : current - 1);
        hoverPause = false;
        updatePause();
      }, { passive: true });

      viewport.addEventListener('touchcancel', function () {
        touching = false;
        hoverPause = false;
        updatePause();
      }, { passive: true });

      /* keyboard arrows */
      root.addEventListener('keydown', function (e) {
        if (e.key === 'ArrowLeft') go(current - 1);
        if (e.key === 'ArrowRight') go(current + 1);
      });

      render();
    }
  })();
</script>

<?php endif; ?>