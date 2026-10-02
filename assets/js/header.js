(function () {
  "use strict";

  var root = document.querySelector('.pm-nav');
  if (!root) return;

  function $(sel, ctx) { return (ctx || root).querySelector(sel); }
  function $all(sel, ctx) { return Array.prototype.slice.call((ctx || root).querySelectorAll(sel)); }

  var sticky = document.querySelector('.kalora-sticky-top');

  /* ================= 0) SCROLL: topbar/pill collapse + shadow ================= */
  function handleScroll() {
    var scrolled = window.scrollY > 10;
    root.classList.toggle('pm-scrolled', scrolled);
    if (sticky) sticky.classList.toggle('is-scrolled', scrolled);
  }
  window.addEventListener('scroll', handleScroll, { passive: true });
  handleScroll();

  /* ================= 1) EVENT COUNTDOWN ================= */
  (function () {
    var bar = document.getElementById('kaloraEventBarX72');
    if (!bar) return;

    var endStr = bar.dataset.eventEnd || '';
    var endTime = new Date(endStr.replace(' ', 'T')).getTime();

    var elD = document.getElementById('kaloraEventDaysX72');
    var elH = document.getElementById('kaloraEventHoursX72');
    var elM = document.getElementById('kaloraEventMinutesX72');
    var elS = document.getElementById('kaloraEventSecondsX72');
    var closeBtn = document.getElementById('kaloraEventCloseX72');
    var timer = null;

    function pad(n) { return String(n).padStart(2, '0'); }

    function hideBar() {
      bar.style.display = 'none';
      if (timer) clearInterval(timer);
    }

    function tick() {
      var diff = endTime - Date.now();
      if (isNaN(endTime) || diff <= 0) { hideBar(); return; }

      var day = 1000 * 60 * 60 * 24;
      var hr  = 1000 * 60 * 60;
      var min = 1000 * 60;

      elD.textContent = pad(Math.floor(diff / day));
      elH.textContent = pad(Math.floor((diff % day) / hr));
      elM.textContent = pad(Math.floor((diff % hr) / min));
      elS.textContent = pad(Math.floor((diff % min) / 1000));
    }

    if (closeBtn) closeBtn.addEventListener('click', hideBar);

    tick();
    timer = setInterval(tick, 1000);
  })();

  /* ================= 2) HAMBURGER -> FULLSCREEN MENU ================= */
  var hamburger = $('#pmHamburger');
  var overlay   = $('#pmMenuOverlay');
  var closeBtnMenu = $('#pmMenuClose');

  function openMenu() {
    overlay.classList.add('is-open');
    hamburger.classList.add('is-active');
    hamburger.setAttribute('aria-expanded', 'true');
    document.body.style.overflow = 'hidden';
  }
  function closeMenu() {
    overlay.classList.remove('is-open');
    hamburger.classList.remove('is-active');
    hamburger.setAttribute('aria-expanded', 'false');
    document.body.style.overflow = '';
  }

  if (hamburger && overlay) {
    hamburger.addEventListener('click', function () {
      overlay.classList.contains('is-open') ? closeMenu() : openMenu();
    });
    if (closeBtnMenu) closeBtnMenu.addEventListener('click', closeMenu);
    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape' && overlay.classList.contains('is-open')) closeMenu();
    });
    window.addEventListener('resize', function () {
      if (window.innerWidth >= 993 && overlay.classList.contains('is-open')) closeMenu();
    });
  }

  /* ================= 3) PILL TABS ================= */
  function wirePillTabs(pillId) {
    var pill = $(pillId);
    if (!pill) return;
    $all('button', pill).forEach(function (btn) {
      btn.addEventListener('click', function () {
        $all('button', pill).forEach(function (b) { b.classList.remove('is-active'); });
        btn.classList.add('is-active');
      });
    });
  }
  wirePillTabs('#pmPillDesktop');
  wirePillTabs('#pmPillMobile');

  /* ================= 4) DESKTOP NAV DROPDOWNS (panel ho to) ================= */
  function closeAllPanels() {
    $all('[data-pm-action="nav-dropdown"]').forEach(function (b) { b.classList.remove('is-open'); });
    $all('.pm-dropdown-panel').forEach(function (p) { p.classList.remove('is-open'); });
  }
  $all('[data-pm-action="nav-dropdown"]').forEach(function (btn) {
    btn.addEventListener('click', function () {
      var panel = $('[data-pm-panel="' + btn.dataset.pmDropdown + '"]');
      if (!panel) return;                       // panel nahi hai to error nahi
      var isOpen = panel.classList.contains('is-open');
      closeAllPanels();
      if (!isOpen) { btn.classList.add('is-open'); panel.classList.add('is-open'); }
    });
  });
  document.addEventListener('click', function (e) {
    if (!e.target.closest('.pm-links-row')) closeAllPanels();
  });

  /* ================= 5) DESKTOP BRANDS DROPDOWN ================= */
  $all('.pm-dd-btn').forEach(function (btn) {
    btn.addEventListener('click', function (e) {
      e.stopPropagation();
      btn.parentElement.classList.toggle('open');
    });
  });
  document.addEventListener('click', function () {
    $all('.pm-dd.open').forEach(function (d) { d.classList.remove('open'); });
  });

  /* ================= 6) MOBILE MENU TOGGLE (Demifine / Gold) ================= */
  var menuToggle = $('#pmMenuToggle');
  if (menuToggle) {
    $all('button', menuToggle).forEach(function (btn) {
      btn.addEventListener('click', function () {
        $all('button', menuToggle).forEach(function (b) { b.classList.remove('is-active'); });
        btn.classList.add('is-active');
      });
    });
  }

  /* ================= 7) MOBILE SIDEBAR + BRANDS LIST ================= */
  var sidebar   = $('#pmMenuSidebar');
  var brandsBtn = $('[data-pm-cat="brands"]');
  var brandList = $('#pmSbBrands');
  var menuBody  = $('.pm-menu-body');

  function closeBrands() {
    if (brandList) brandList.classList.remove('show');
    if (menuBody) menuBody.classList.remove('brands-open');
  }

  if (sidebar) {
    $all('button', sidebar).forEach(function (btn) {
      btn.addEventListener('click', function () {
        var isBrands = (btn === brandsBtn);
        var willOpen = isBrands && brandList && !brandList.classList.contains('show');

        $all('button', sidebar).forEach(function (b) { b.classList.remove('is-active'); });

        if (isBrands) {
          if (willOpen) {
            btn.classList.add('is-active');
            brandList.classList.add('show');
            menuBody.classList.add('brands-open');
          } else {
            closeBrands();
          }
        } else {
          btn.classList.add('is-active');
          closeBrands();
        }
      });
    });
  }

  /* ================= 8) WISHLIST PULSE ================= */
  $all('[data-pm-action="wishlist"]').forEach(function (btn) {
    btn.addEventListener('click', function () {
      btn.classList.add('pm-pulse');
      setTimeout(function () { btn.classList.remove('pm-pulse'); }, 350);
    });
  });

  /* ================= 9) LIVE SEARCH ================= */
  function pmEsc(s) {
    var d = document.createElement('div');
    d.textContent = (s == null ? '' : s);
    return d.innerHTML;
  }

  function pmSetupSearch(inputId, boxId) {
    var input = document.getElementById(inputId);
    var box   = document.getElementById(boxId);
    if (!input || !box) return;

    var timer = null, ctrl = null;

    function render(items) {
      if (!items || !items.length) {
        box.innerHTML = '<div class="pm-sr-empty">Koi product nahi mila</div>';
      } else {
        box.innerHTML = items.map(function (p) {
          var sale = parseFloat(p.sale_price);
          var orig = parseFloat(p.original_price);
          var price = sale > 0 ? sale : orig;
          var strike = (sale > 0 && orig > sale) ? '<s>₹' + orig.toFixed(0) + '</s>' : '';
          var img = p.product_image ? p.product_image : '';

          var meta = [
            p.product_color ? 'Color: ' + p.product_color : '',
            p.product_size  ? 'Size: '  + p.product_size  : ''
          ].filter(Boolean).map(pmEsc).join(' &bull; ');

          return '<a class="pm-sr-item" href="product_details.php?slug=' + encodeURIComponent(p.product_slug) + '">' +
            '<img src="' + pmEsc(img) + '" alt="">' +
            '<span class="pm-sr-info">' +
              '<span class="pm-sr-name">' + pmEsc(p.product_name) + '</span>' +
              (meta ? '<span class="pm-sr-meta">' + meta + '</span>' : '') +
              '<span class="pm-sr-price">₹' + (price ? price.toFixed(0) : '-') + strike + '</span>' +
            '</span></a>';
        }).join('');
      }
      box.classList.add('show');
    }

    function hideResults() {
      clearTimeout(timer);
      if (ctrl) ctrl.abort();
      box.classList.remove('show');
    }

    input.addEventListener('input', function () {
      clearTimeout(timer);
      var q = input.value.trim();

      if (q.length < 2) {
        box.classList.remove('show');
        box.innerHTML = '';
        return;
      }

      timer = setTimeout(function () {
        if (ctrl) ctrl.abort();
        ctrl = new AbortController();

        fetch('search_ajax.php?q=' + encodeURIComponent(q), { signal: ctrl.signal })
          .then(function (r) { return r.json(); })
          .then(render)
          .catch(function () {});
      }, 300);
    });

    input.addEventListener('keydown', function (e) {
      if (e.key === 'Enter') {
        e.preventDefault();
        hideResults();
        input.blur();
      }
    });

    var btn = input.parentElement.querySelector('[data-pm-action="search"]');
    if (btn) {
      btn.addEventListener('click', function () {
        if (input.value.trim() === '') input.focus();
        else hideResults();
      });
    }

    box.addEventListener('click', function (e) { e.stopPropagation(); });

    document.addEventListener('click', function (e) {
      if (!input.parentElement.contains(e.target)) box.classList.remove('show');
    });
  }

  pmSetupSearch('pmSearchDesktop', 'pmResultsDesktop');
  pmSetupSearch('pmSearchMobile', 'pmResultsMobile');

})();