(function () {
  "use strict";

  var root = document.querySelector('.pm-nav');
  /* 0) STICKY HEADER + HIDE TOPBAR ON SCROLL */
  function handleScroll() {
    if (window.scrollY > 10) {
      root.classList.add('pm-scrolled');
    } else {
      root.classList.remove('pm-scrolled');
    }
  }
  window.addEventListener('scroll', handleScroll, { passive: true });
  handleScroll(); // initial check (page reload ke case me)


  if (!root) return;

  function $(sel, ctx) { return (ctx || root).querySelector(sel); }
  function $all(sel, ctx) { return Array.prototype.slice.call((ctx || root).querySelectorAll(sel)); }

  /* 1) HAMBURGER -> FULLSCREEN MENU OPEN/CLOSE */
  var hamburger = $('#pmHamburger');
  var overlay = $('#pmMenuOverlay');
  var closeBtn = $('#pmMenuClose');

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
  hamburger.addEventListener('click', function () {
    overlay.classList.contains('is-open') ? closeMenu() : openMenu();
  });
  closeBtn.addEventListener('click', closeMenu);
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape' && overlay.classList.contains('is-open')) closeMenu();
  });
  window.addEventListener('resize', function () {
    if (window.innerWidth >= 993 && overlay.classList.contains('is-open')) closeMenu();
  });

  /* 2) PILL TABS: Home / Demifine® Jewellery / Gold Jewellery */
  function wirePillTabs(pillId) {
    var pill = $(pillId);
    if (!pill) return;
    $all('button', pill).forEach(function (btn) {
      btn.addEventListener('click', function () {
        $all('button', pill).forEach(function (b) { b.classList.remove('is-active'); });
        btn.classList.add('is-active');
        // TODO: selected tab (btn.dataset.pmTab) ke hisaab se content switch karo
        console.log('Tab selected:', btn.dataset.pmTab);
      });
    });
  }
  wirePillTabs('#pmPillDesktop');
  wirePillTabs('#pmPillMobile');

  /* 3) DESKTOP NAV DROPDOWNS */
  $all('[data-pm-action="nav-dropdown"]').forEach(function (btn) {
    btn.addEventListener('click', function () {
      var key = btn.dataset.pmDropdown;
      var panel = $('[data-pm-panel="' + key + '"]');
      var isOpen = panel.classList.contains('is-open');
      $all('[data-pm-action="nav-dropdown"]').forEach(function (b) { b.classList.remove('is-open'); });
      $all('.pm-dropdown-panel').forEach(function (p) { p.classList.remove('is-open'); });
      if (!isOpen) { btn.classList.add('is-open'); panel.classList.add('is-open'); }
      // TODO: panel ke andar real submenu links/images daalo
    });
  });
  document.addEventListener('click', function (e) {
    if (!e.target.closest('.pm-links-row')) {
      $all('[data-pm-action="nav-dropdown"]').forEach(function (b) { b.classList.remove('is-open'); });
      $all('.pm-dropdown-panel').forEach(function (p) { p.classList.remove('is-open'); });
    }
  });

  /* 4) MOBILE MENU — Demifine® / Gold TOGGLE */
  var menuToggle = $('#pmMenuToggle');
  $all('button', menuToggle).forEach(function (btn) {
    btn.addEventListener('click', function () {
      $all('button', menuToggle).forEach(function (b) { b.classList.remove('is-active'); });
      btn.classList.add('is-active');
      // TODO: btn.dataset.pmMenuTab ke hisaab se sidebar + grid replace karo
      console.log('Menu top toggle:', btn.dataset.pmMenuTab);
    });
  });

  /* 5) MOBILE MENU SIDEBAR CATEGORY CLICK */
  var sidebar = $('#pmMenuSidebar');
  $all('button', sidebar).forEach(function (btn) {
    btn.addEventListener('click', function () {
      $all('button', sidebar).forEach(function (b) { b.classList.remove('is-active'); });
      btn.classList.add('is-active');
      // TODO: btn.dataset.pmCat ke hisaab se right grid load karo
      console.log('Sidebar category:', btn.dataset.pmCat);
    });
  });

  /* 6) CATEGORY CARD + "View all" */
  $all('[data-pm-action="category-card"]').forEach(function (btn) {
    btn.addEventListener('click', function () {
      // TODO: category product-listing page par navigate karo
      console.log('Category card clicked:', btn.textContent.trim());
    });
  });
  var viewAllBtn = $('[data-pm-action="view-all"]');
  if (viewAllBtn) {
    viewAllBtn.addEventListener('click', function () {
      // TODO: pura catalogue page open karo
      console.log('View all clicked');
    });
  }

  /* 7) SEARCH */
  function wireSearch(inputId) {
    var input = $(inputId);
    if (!input) return;
    input.addEventListener('keydown', function (e) {
      if (e.key === 'Enter' && input.value.trim() !== '') {
        // TODO: actual search API call / redirect
        console.log('Search submitted:', input.value.trim());
      }
    });
  }
  wireSearch('#pmSearchDesktop');
  wireSearch('#pmSearchMobile');
  $all('[data-pm-action="search"]').forEach(function (btn) {
    btn.addEventListener('click', function () {
      var input = btn.closest('.pm-search').querySelector('input');
      if (input && input.value.trim() !== '') {
        console.log('Search icon clicked, value:', input.value.trim());
      } else if (input) {
        input.focus();
      }
    });
  });

  /* 8) SIMPLE ICON CTAs */
  $all('[data-pm-action="pincode"]').forEach(function (btn) {
    btn.addEventListener('click', function () { console.log('Pincode CTA clicked'); /* TODO: pincode modal */ });
  });
  $all('[data-pm-action="store-locator"]').forEach(function (btn) {
    btn.addEventListener('click', function () { console.log('Store locator CTA clicked'); /* TODO */ });
  });
  $all('[data-pm-action="wishlist"]').forEach(function (btn) {
    btn.addEventListener('click', function () {
      btn.classList.add('pm-pulse');
      setTimeout(function () { btn.classList.remove('pm-pulse'); }, 350);
      console.log('Wishlist CTA clicked'); /* TODO: wishlist logic */
    });
  });
  $all('[data-pm-action="account"]').forEach(function (btn) {
    btn.addEventListener('click', function () { console.log('Account CTA clicked'); /* TODO */ });
  });
  $all('[data-pm-action="cart"]').forEach(function (btn) {
    btn.addEventListener('click', function () { console.log('Cart CTA clicked'); /* TODO: cart drawer */ });
  });
  $all('[data-pm-action="go-home"]').forEach(function (btn) {
    btn.addEventListener('click', function () { console.log('Logo clicked -> go home'); /* TODO */ });
  });
  $all('[data-pm-action="nav-link"]').forEach(function (btn) {
    btn.addEventListener('click', function () { console.log('Nav link clicked:', btn.textContent.trim()); /* TODO */ });
  });
})();