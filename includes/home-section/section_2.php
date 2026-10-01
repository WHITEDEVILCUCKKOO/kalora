<!-- ==========================================================================
     PALMONAS CATEGORY SLIDER (Shop by category)
     - Unique class prefix: .pmcat  (kisi aur CSS se takrayega nahi)
     - Koi :root / html / body / * nahi, koi font-family nahi (site ka font inherit hoga)
     - Naya category add karna ho: neeche wale <a class="pmcat__item"> block ko copy karke
       track ke andar paste kar do, bas image path aur naam badalna hai.
     ========================================================================== -->
<?php include "admin_access/db_config.php" ?>
<style>
  .pmcat {
    --pmcat-n: 2.4;
    /* ek baar me kitne items dikhen (fractional = agle item ki jhalak) */
    --pmcat-gap: 14px;
    /* items ke beech ka gap */
    --pmcat-edge: 16px;
    /* left/right side ki padding */
    --pmcat-accent: #ef9a9a;
    /* active dot ka color */
    --pmcat-dot: #d9d9d9;
    /* baaki dots ka color */

    position: relative;
    display: block;
    width: 100%;
    padding: 10px 0 clamp(24px, 3vw, 56px);
    color: #1a1a1a;
    line-height: 1.2;
    text-align: center;
    background: #fdf6ea !important;
  }

  /* ---------- dots (upar) ---------- */
  .pmcat__dots {
    display: flex;
    justify-content: center;
    height: 20px;
    margin: 0 0 clamp(14px, 3vw, 54px);
    opacity: 0;
    pointer-events: none;
    transition: opacity 0.3s ease;
  }

  .pmcat.has-scroll .pmcat__dots {
    opacity: 1;
    pointer-events: auto;
  }

  .pmcat__dot {
    position: relative;
    width: 18px;
    height: 20px;
    padding: 0;
    border: 0;
    background: none;
    cursor: pointer;
    -webkit-tap-highlight-color: transparent;
  }

  .pmcat__dot::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 8px;
    height: 8px;
    margin: -4px 0 0 -4px;
    border-radius: 50%;
    background: var(--pmcat-dot);
    transition: background 0.3s ease, transform 0.3s ease;
  }

  .pmcat__dot.is-active::before {
    background: var(--pmcat-accent);
    transform: scale(1.25);
  }

  /* ---------- wrapper + track ---------- */
  .pmcat__wrap {
    position: relative;
    max-width: 1500px;
    margin: 0 auto;
  }

  .pmcat__track {
    display: flex;
    gap: var(--pmcat-gap);
    margin: -12px 0 -24px;
    /* hover lift/shadow clip na ho, isliye padding + negative margin */
    padding: 12px var(--pmcat-edge) 24px;
    overflow-x: auto;
    overflow-y: hidden;
    scroll-snap-type: x mandatory;
    scroll-padding: 0 var(--pmcat-edge);
    overscroll-behavior-x: contain;
    -webkit-overflow-scrolling: touch;
    scrollbar-width: none;
    -ms-overflow-style: none;
  }

  .pmcat__track::-webkit-scrollbar {
    display: none;
  }

  .pmcat.has-scroll .pmcat__track {
    cursor: grab;
  }

  .pmcat__track.is-dragging {
    scroll-snap-type: none;
    cursor: grabbing;
  }

  /* ---------- item (card) ---------- */
  .pmcat__item {
    flex: 0 0 calc((100% - var(--pmcat-gap) * (var(--pmcat-n) - 1)) / var(--pmcat-n));
    min-width: 0;
    display: block;
    color: inherit;
    text-decoration: none;
    scroll-snap-align: start;
    -webkit-tap-highlight-color: transparent;
    -webkit-user-select: none;
    user-select: none;
  }

  /* entrance animation (JS chalne par hi hidden hota hai, isliye JS band ho to bhi items dikhenge) */
  .pmcat.is-ready .pmcat__item {
    opacity: 0;
    transform: translateY(26px);
    transition: opacity 0.6s ease calc(var(--pmcat-i, 0) * 0ms), transform 0.7s cubic-bezier(0.2, 0.7, 0.2, 1) calc(var(--pmcat-i, 0) * 0ms);
  }

  .pmcat.is-ready.is-in .pmcat__item {
    opacity: 1;
    transform: none;
  }

  .pmcat__media {
    position: relative;
    display: block;
    width: 100%;
    aspect-ratio: 1 / 1;
    overflow: hidden;
    background: #efece6;
    transition: transform 0.35s cubic-bezier(0.2, 0.7, 0.2, 1), box-shadow 0.35s ease;
    border-radius: 50%;
    overflow: hidden;
  }

  .pmcat__img {
    display: block;
    width: 100%;
    height: 100%;
    max-width: none;
    object-fit: cover;
    pointer-events: none;
    -webkit-user-drag: none;
    transition: transform 0.7s cubic-bezier(0.2, 0.7, 0.2, 1);
  }

  /* shine sweep */
  .pmcat__media::after {
    content: '';
    position: absolute;
    top: 0;
    bottom: 0;
    left: -70%;
    width: 40%;
    background: linear-gradient(100deg, rgba(255, 255, 255, 0), rgba(255, 255, 255, 0.5), rgba(255, 255, 255, 0));
    transform: skewX(-15deg);
    pointer-events: none;
  }

  .pmcat__label {
    position: relative;
    display: inline-block;
    margin-top: clamp(8px, 0.8vw, 14px);
    font-size: clamp(14px, 1.25vw, 24px);
    font-weight: 400;
    line-height: 1.25;
  }

  .pmcat__label::after {
    content: '';
    position: absolute;
    left: 50%;
    bottom: -3px;
    width: 0;
    height: 1.5px;
    background: currentColor;
    transition: width 0.3s ease, left 0.3s ease;
  }

  .pmcat__item:active .pmcat__media {
    transform: scale(0.97);
  }

  .pmcat__item:focus-visible {
    outline: 2px solid #1a1a1a;
    outline-offset: 4px;
  }

  /* ---------- arrows (sirf mouse wale bade screen par) ---------- */
  .pmcat__arrow {
    position: absolute;
    top: 50%;
    width: 40px;
    height: 40px;
    margin-top: -40px;
    padding: 0;
    display: none;
    align-items: center;
    justify-content: center;
    border: 0;
    border-radius: 50%;
    background: #ffffff;
    color: #1a1a1a;
    cursor: pointer;
    z-index: 5;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.18);
    opacity: 0;
    transition: opacity 0.3s ease, transform 0.25s ease, box-shadow 0.25s ease;
  }

  .pmcat__arrow--prev {
    left: 8px;
  }

  .pmcat__arrow--next {
    right: 8px;
  }

  .pmcat__arrow svg {
    width: 18px;
    height: 18px;
    transition: transform 0.25s ease;
  }

  .pmcat__arrow:focus-visible {
    outline: 2px solid #1a1a1a;
    outline-offset: 2px;
    opacity: 1;
  }

  /* ==========================================================================
   RESPONSIVE  (items ki sankhya: phone 2.4 -> tablet 4.3 -> laptop 5.3 -> desktop 6)
   ========================================================================== */
  @media (min-width: 576px) {
    .pmcat {
      --pmcat-n: 3.3;
      --pmcat-gap: 16px;
      --pmcat-edge: 20px;
    }
  }

  @media (min-width: 768px) {
    .pmcat {
      --pmcat-n: 4.3;
      --pmcat-gap: 24px;
      --pmcat-edge: 24px;
    }
  }

  @media (min-width: 992px) {
    .pmcat {
      --pmcat-n: 5.3;
      --pmcat-gap: 28px;
      --pmcat-edge: 32px;
    }
  }

  @media (min-width: 1200px) {
    .pmcat {
      --pmcat-n: 6;
      --pmcat-gap: 36px;
      --pmcat-edge: 32px;
    }
  }

  @media (min-width: 1500px) {
    .pmcat {
      --pmcat-gap: 47px;
    }
  }

  /* mouse wale devices (laptop/desktop): hover effects + arrows */
  @media (hover: hover) {
    .pmcat__item:hover .pmcat__media {
      transform: translateY(-5px);
      box-shadow: 0 14px 26px rgba(0, 0, 0, 0.12);
    }

    .pmcat__item:hover .pmcat__img {
      transform: scale(1.08);
    }

    .pmcat__item:hover .pmcat__media::after {
      left: 130%;
      transition: left 0.8s ease;
    }

    .pmcat__item:hover .pmcat__label::after {
      width: 100%;
      left: 0;
    }

    .pmcat__dot:hover::before {
      background: var(--pmcat-accent);
    }
  }

  @media (hover: hover) and (min-width: 768px) {
    .pmcat__wrap {
      padding: 0 56px;
    }

    .pmcat {
      --pmcat-edge: 0px;
    }

    .pmcat.has-scroll .pmcat__arrow {
      display: flex;
    }

    .pmcat.has-scroll .pmcat__wrap:hover .pmcat__arrow {
      opacity: 1;
    }

    .pmcat.has-scroll .pmcat__wrap:hover .pmcat__arrow:disabled {
      opacity: 0.3;
      cursor: default;
    }

    .pmcat__arrow:not(:disabled):hover {
      transform: scale(1.12);
      box-shadow: 0 6px 16px rgba(0, 0, 0, 0.28);
    }

    .pmcat__arrow--prev:not(:disabled):hover svg {
      transform: translateX(-2px);
    }

    .pmcat__arrow--next:not(:disabled):hover svg {
      transform: translateX(2px);
    }
  }

  @media (prefers-reduced-motion: reduce) {

    .pmcat.is-ready .pmcat__item,
    .pmcat__media,
    .pmcat__img,
    .pmcat__label::after,
    .pmcat__arrow,
    .pmcat__arrow svg,
    .pmcat__dot::before {
      transition: none;
      animation: none;
    }

    .pmcat.is-ready .pmcat__item {
      opacity: 1;
      transform: none;
    }

    .pmcat__media::after {
      display: none;
    }
  }
</style>

<section class="pmcat" aria-label="Shop by category">

  <!-- dots (JS khud banayega) -->
  <div class="pmcat__dots"></div>

  <div class="pmcat__wrap">
    <button class="pmcat__arrow pmcat__arrow--prev" type="button" aria-label="Previous categories">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
        <polyline points="15 5 8 12 15 19" />
      </svg>
    </button>

    <div class="pmcat__track">



      <?php

      $sql8748524 = "SELECT brands.*, root_categories.* FROM brands INNER JOIN root_categories ON brands.root_id = root_categories.root_id WHERE brands.brand_status = 'Active' ";

      $result8748524 = mysqli_query($mydb, $sql8748524);

      if ($result8748524 && mysqli_num_rows($result8748524) > 0) {

        while ($brand8748524 = mysqli_fetch_assoc($result8748524)) {

          $brand_image = $brand8748524['brand_logo'];
          $brand_slug  = $brand8748524['brand_slug'];
          $brand_id  = $brand8748524['brand_id'];
          $barnd_name  = $brand8748524['brand_name'];


      ?>

          <!-- ===== CATEGORY 1 ===== -->
          <a class="pmcat__item" href="categorys_products.php?cate=<?php echo htmlspecialchars($brand_slug); ?>">
            <span class="pmcat__media"><img class="pmcat__img" src="<?php echo htmlspecialchars($brand_image); ?>" alt="Rings" loading="lazy" draggable="false"></span>
            <span class="pmcat__label"><?php echo htmlspecialchars($barnd_name); ?></span>
          </a>

      <?php

        }
      }

      ?>

    </div>

    <button class="pmcat__arrow pmcat__arrow--next" type="button" aria-label="Next categories">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
        <polyline points="9 5 16 12 9 19" />
      </svg>
    </button>
  </div>
</section>

<script>
  /* PALMONAS CATEGORY SLIDER — sirf .pmcat elements ke andar kaam karta hai */
  (function() {
    "use strict";

    var roots = document.querySelectorAll('.pmcat');
    Array.prototype.forEach.call(roots, initCat);

    function initCat(root) {
      var track = root.querySelector('.pmcat__track');
      var dotsWrap = root.querySelector('.pmcat__dots');
      var prevBtn = root.querySelector('.pmcat__arrow--prev');
      var nextBtn = root.querySelector('.pmcat__arrow--next');
      if (!track) return;

      var items = Array.prototype.slice.call(track.querySelectorAll('.pmcat__item'));
      var reduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
      var behavior = reduce ? 'auto' : 'smooth';
      var dots = [];
      var ticking = false;

      /* ---- entrance animation (stagger) ---- */
      items.forEach(function(it, i) {
        it.style.setProperty('--pmcat-i', Math.min(i, 7));
      });
      root.classList.add('is-ready');
      if ('IntersectionObserver' in window) {
        var io = new IntersectionObserver(function(entries) {
          if (entries[0].isIntersecting) {
            root.classList.add('is-in');
            io.disconnect();
          }
        }, {
          threshold: 0.3
        });
        io.observe(root);
      } else {
        root.classList.add('is-in');
      }

      /* ---- helpers ---- */
      function maxScroll() {
        return track.scrollWidth - track.clientWidth;
      }

      function updateState() {
        var max = maxScroll();
        var left = track.scrollLeft;
        var active = 0;
        if (dots.length > 1 && max > 0) active = Math.round((left / max) * (dots.length - 1));
        dots.forEach(function(d, i) {
          d.classList.toggle('is-active', i === active);
          if (i === active) d.setAttribute('aria-current', 'true');
          else d.removeAttribute('aria-current');
        });
        if (prevBtn) prevBtn.disabled = left <= 2;
        if (nextBtn) nextBtn.disabled = left >= max - 2;
      }

      function buildDots() {
        var hasScroll = maxScroll() > 2;
        root.classList.toggle('has-scroll', hasScroll);
        var count = hasScroll ? Math.ceil(track.scrollWidth / track.clientWidth) : 0;
        if (count !== dots.length) {
          dotsWrap.innerHTML = '';
          dots = [];
          for (var i = 0; i < count; i++) {
            (function(idx) {
              var b = document.createElement('button');
              b.type = 'button';
              b.className = 'pmcat__dot';
              b.setAttribute('aria-label', 'Go to page ' + (idx + 1));
              b.addEventListener('click', function() {
                var m = maxScroll();
                var target = dots.length > 1 ? m * (idx / (dots.length - 1)) : 0;
                track.scrollTo({
                  left: target,
                  behavior: behavior
                });
              });
              dotsWrap.appendChild(b);
              dots.push(b);
            })(i);
          }
        }
        updateState();
      }

      function step(dir) {
        var first = items[0];
        if (!first) return;
        var gap = parseFloat(window.getComputedStyle(track).columnGap) || 0;
        var w = first.getBoundingClientRect().width + gap;
        var visible = Math.max(1, Math.floor(track.clientWidth / w));
        track.scrollBy({
          left: dir * w * visible,
          behavior: behavior
        });
      }

      if (prevBtn) prevBtn.addEventListener('click', function() {
        step(-1);
      });
      if (nextBtn) nextBtn.addEventListener('click', function() {
        step(1);
      });

      track.addEventListener('scroll', function() {
        if (ticking) return;
        ticking = true;
        window.requestAnimationFrame(function() {
          ticking = false;
          updateState();
        });
      }, {
        passive: true
      });

      if ('ResizeObserver' in window) {
        new ResizeObserver(buildDots).observe(track);
      } else {
        window.addEventListener('resize', buildDots);
      }
      window.addEventListener('load', buildDots);

      /* ---- mouse drag (touch me native swipe chalta hai) ---- */
      var down = false,
        moved = false,
        startX = 0,
        startLeft = 0;
      track.addEventListener('pointerdown', function(e) {
        if (e.pointerType !== 'mouse' || e.button !== 0) return;
        down = true;
        moved = false;
        startX = e.clientX;
        startLeft = track.scrollLeft;
      });
      window.addEventListener('pointermove', function(e) {
        if (!down) return;
        var dx = e.clientX - startX;
        if (!moved && Math.abs(dx) > 5) {
          moved = true;
          track.classList.add('is-dragging');
        }
        if (moved) track.scrollLeft = startLeft - dx;
      });

      function endDrag() {
        if (!down) return;
        down = false;
        track.classList.remove('is-dragging');
      }
      window.addEventListener('pointerup', endDrag);
      window.addEventListener('pointercancel', endDrag);
      // drag ke baad galti se link na khule
      track.addEventListener('click', function(e) {
        if (moved) {
          e.preventDefault();
          e.stopPropagation();
          moved = false;
        }
      }, true);
      track.addEventListener('dragstart', function(e) {
        e.preventDefault();
      });

      buildDots();
    }
  })();
</script>