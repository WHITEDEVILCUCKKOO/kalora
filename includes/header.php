<?php
include "admin_access/db_config.php";
include "admin_access/functions/global_info.php";
$global_info = get_global_info($mydb);
/* ================= BRANDS FETCH (DB se) ================= */
$brands = [];
$res = mysqli_query($mydb, "SELECT brand_id, brand_name, brand_slug, brand_logo
                            FROM brands
                            WHERE brand_status = 'Active'
                            ORDER BY brand_name ASC");
if ($res) {
    while ($row = mysqli_fetch_assoc($res)) {
        $brands[] = $row;
    }
}
// Logo folder ka path (apne project ke hisab se change karo)
$brandLogoPath = "admin_access/uploads/brands/";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="assets/logos/<?php echo htmlspecialchars($global_info['facion_icon'] ?? ''); ?>">
    <title>Kalora</title>

    <link rel="stylesheet" href="assets/css/globle.css">
    <link rel="stylesheet" href="assets/css/header.css">
    <link rel="stylesheet" href="assets/css/footer.css">

    <!-- Brands dropdown CSS (chaho to header.css me move kar do) -->
    <style>
        .pm-dd { position: relative; display: inline-block; }
        .pm-dd-btn {
            background: none; border: 0; cursor: pointer; font: inherit; color: inherit;
            display: inline-flex; align-items: center; gap: 4px; padding: 0;
        }
        .pm-dd-menu {
            display: none; position: absolute; top: 100%; left: 0;
            min-width: 220px; max-height: 320px; overflow-y: auto;
            background: #fff; list-style: none; margin: 0; padding: 8px 0;
            box-shadow: 0 6px 20px rgba(0,0,0,.12); z-index: 9999; border-radius: 6px;
        }
        .pm-dd:hover .pm-dd-menu, .pm-dd.open .pm-dd-menu { display: block; }
        .pm-dd-menu a {
            display: flex; align-items: center; gap: 10px;
            padding: 9px 16px; color: #222; text-decoration: none; font-size: 14px; white-space: nowrap;
        }
        .pm-dd-menu a:hover { background: #f5f5f5; }
        .pm-dd-menu img { width: 24px; height: 24px; object-fit: contain; }

        /* ---------- Search results dropdown ---------- */
        .pm-search { position: relative; }
        .pm-search-results {
            display: none; position: absolute; top: 100%; left: 0; right: 0;
            background: #fff; border-radius: 6px; margin-top: 6px;
            box-shadow: 0 6px 20px rgba(0,0,0,.15); z-index: 99999;
            max-height: 380px; overflow-y: auto; text-align: left;
        }
        .pm-search-results.show { display: block; }
        .pm-sr-item {
            display: flex; gap: 10px; align-items: center; padding: 10px 12px;
            text-decoration: none; color: #222; border-bottom: 1px solid #f0f0f0;
        }
        .pm-sr-item:hover { background: #f7f7f7; }
        .pm-sr-item img { width: 48px; height: 48px; object-fit: cover; border-radius: 4px; background: #eee; flex-shrink: 0; }
        .pm-sr-info { display: flex; flex-direction: column; gap: 2px; font-size: 13px; min-width: 0; }
        .pm-sr-name { font-weight: 600; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .pm-sr-meta { color: #777; font-size: 12px; }
        .pm-sr-price { font-size: 13px; }
        .pm-sr-price s { color: #999; margin-left: 6px; font-size: 12px; }
        .pm-sr-empty { padding: 14px; font-size: 13px; color: #777; }

        /* ---------- Mobile menu: Brands left sidebar me ---------- */
        .pm-sb-brands { display: none; flex-direction: column; background: #fff; }
        .pm-sb-brands.show { display: flex; }
        .pm-sb-brands a {
            padding: 10px 12px 10px 26px; font-size: 13px; color: #222;
            text-decoration: none; border-bottom: 1px solid #f0f0f0;
        }
        .pm-sb-brands a:active { background: #f3f3f3; }
        .pm-menu-body.brands-open .pm-menu-grid-wrap { display: none; }
        .pm-menu-body.brands-open .pm-menu-sidebar { flex: 1 1 100%; width: 100%; }
    </style>
</head>

<body>

    <!-- ======================= HEADER ======================= -->
    <header>
        <div class="pm-nav">
            <div class="pm-topbar not_display">Buy Rs.99</div>

            <!-- ======================= DESKTOP HEADER ======================= -->
            <div class="pm-desktop">
                <div class="pm-desktop-top">
                    <button onclick="window.location.href='index.php'" class="pm-logo" type="button" data-pm-action="go-home">KALORA</button>
                    <div class="pm-search">
                        <input type="text" placeholder="Search products..." id="pmSearchDesktop" autocomplete="off" />
                        <button class="pm-icon-btn" style="width:auto;height:auto" type="button" data-pm-action="search" aria-label="Search">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="11" cy="11" r="7" />
                                <line x1="21" y1="21" x2="16.65" y2="16.65" />
                            </svg>
                        </button>
                        <div class="pm-search-results" id="pmResultsDesktop"></div>
                    </div>
                    <div class="pm-right">
                        <button class="pm-pincode not_display" type="button" data-pm-action="pincode">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 10c0 6-9 12-9 12s-9-6-9-12a9 9 0 0 1 18 0z" />
                                <circle cx="12" cy="10" r="3" />
                            </svg>
                            Enter Pincode
                        </button>
                        <button class="pm-icon-btn not_display" type="button" data-pm-action="store-locator" aria-label="Store locator">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M3 9l1-5h16l1 5" />
                                <path d="M4 9v10h16V9" />
                                <path d="M9 21v-6h6v6" />
                            </svg>
                        </button>
                        <button class="pm-icon-btn not_display" type="button" data-pm-action="wishlist" aria-label="Wishlist">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.6l-1-1a5.5 5.5 0 0 0-7.8 7.8l1 1L12 21l7.8-7.6 1-1a5.5 5.5 0 0 0 0-7.8z" />
                            </svg>
                        </button>
                        <button onclick="window.location.href='login.php'" class="pm-icon-btn" type="button" data-pm-action="account" aria-label="Account">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="8" r="4" />
                                <path d="M4 21c1.5-4 5-6 8-6s6.5 2 8 6" />
                            </svg>
                        </button>
                        <button class="pm-icon-btn not_display" type="button" data-pm-action="cart" aria-label="Cart">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="9" cy="21" r="1" />
                                <circle cx="19" cy="21" r="1" />
                                <path d="M2 3h2l2.4 12.2a2 2 0 0 0 2 1.8h8.6a2 2 0 0 0 2-1.6L21 7H6" />
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="pm-links-row">
                    <nav class="pm-links" id="pmLinksDesktop">
                        <button onclick="window.location.href='index.php'" type="button" data-pm-action="nav-link">Home</button>
                        <button class="not_display" type="button" data-pm-action="nav-link">New Arrivals</button>
                        <button class="not_display" type="button" data-pm-action="nav-link">HomeSellers</button>
                        <button class="not_display" type="button" data-pm-action="nav-link">Best Sellers</button>
                        <button class="not_display" type="button" data-pm-action="nav-dropdown" data-pm-dropdown="fine-silver">Fine Silver
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9" /></svg>
                        </button>
                        <button class="not_display" type="button" data-pm-action="nav-dropdown" data-pm-dropdown="demifine">Demifine ® Collection
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9" /></svg>
                        </button>
                        <button class="not_display" type="button" data-pm-action="nav-link">Offers</button>
                        <button class="not_display" type="button" data-pm-action="nav-link">Shraddha's Favourite</button>
                        <button class="not_display" type="button" data-pm-action="nav-link">Emily In Paris</button>

                        <!-- ============ BRANDS DROPDOWN (DB se) ============ -->
                        <div class="pm-dd">
                            <button type="button" class="pm-dd-btn" data-pm-dropdown="brands">Brands
                                <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9" /></svg>
                            </button>
                            <ul class="pm-dd-menu">
                                <?php foreach ($brands as $b) { ?>
                                    <li>
                                        <a href="categorys_products.php?slug=<?= urlencode($b['brand_slug']) ?>">
                                            <?php if (!empty($b['brand_logo'])) { ?>
                                                <img src="<?= htmlspecialchars($b['brand_logo']) ?>" alt="">
                                            <?php } ?>
                                            <?= htmlspecialchars($b['brand_name']) ?>
                                        </a>
                                    </li>
                                <?php } ?>
                                <?php if (empty($brands)) { ?>
                                    <li><a href="#">No brands found</a></li>
                                <?php } ?>
                            </ul>
                        </div>

                        <button type="button" data-pm-action="nav-link">POP</button>
                        <button class="not_display" type="button" data-pm-action="nav-dropdown" data-pm-dropdown="about">About Us
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9" /></svg>
                        </button>
                    </nav>
                </div>

                <div class="pm-pill-wrap" style="display: none;">
                    <div class="pm-pill" id="pmPillDesktop">
                        <button type="button" class="is-active" data-pm-tab="home">Home</button>
                        <button type="button" data-pm-tab="demifine">Demifine® Jewellery</button>
                        <button type="button" data-pm-tab="gold">Gold Jewellery</button>
                    </div>
                </div>
            </div>

            <!-- ======================= MOBILE / TABLET HEADER ======================= -->
            <div class="pm-mobile">
                <div class="pm-mobile-top">
                    <button class="pm-hamburger" type="button" id="pmHamburger" aria-label="Open menu" aria-expanded="false">
                        <span></span><span></span><span></span>
                    </button>
                    <button onclick="window.location.href='index.php'" class="pm-logo" type="button" style="font-size:19px;letter-spacing:4px" data-pm-action="go-home">KALORA</button>
                    <div class="pm-right">
                        <button class="pm-icon-btn not_display" type="button" data-pm-action="store-locator" aria-label="Store locator">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M3 9l1-5h16l1 5" />
                                <path d="M4 9v10h16V9" />
                                <path d="M9 21v-6h6v6" />
                            </svg>
                        </button>
                        <button class="pm-icon-btn not_display" type="button" data-pm-action="wishlist" aria-label="Wishlist">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.6l-1-1a5.5 5.5 0 0 0-7.8 7.8l1 1L12 21l7.8-7.6 1-1a5.5 5.5 0 0 0 0-7.8z" />
                            </svg>
                        </button>
                        <button onclick="window.location.href='login.php'" class="pm-icon-btn" type="button" data-pm-action="account" aria-label="Account">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="8" r="4" />
                                <path d="M4 21c1.5-4 5-6 8-6s6.5 2 8 6" />
                            </svg>
                        </button>
                    </div>
                </div>
                <div class="pm-mobile-search-wrap">
                    <div class="pm-search">
                        <input type="text" placeholder="Search Products..." id="pmSearchMobile" autocomplete="off" />
                        <button class="pm-icon-btn" style="width:auto;height:auto" type="button" data-pm-action="search" aria-label="Search">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="11" cy="11" r="7" />
                                <line x1="21" y1="21" x2="16.65" y2="16.65" />
                            </svg>
                        </button>
                        <div class="pm-search-results" id="pmResultsMobile"></div>
                    </div>
                </div>
                <button class="pm-mobile-pincode not_display" type="button" data-pm-action="pincode">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 10c0 6-9 12-9 12s-9-6-9-12a9 9 0 0 1 18 0z" />
                        <circle cx="12" cy="10" r="3" />
                    </svg>
                    Enter Pincode
                </button>
                <div class="pm-mobile-divider"></div>
                <div class="pm-pill-wrap" style="display: none;">
                    <div class="pm-pill" id="pmPillMobile">
                        <button type="button" class="is-active" data-pm-tab="home">Home</button>
                        <button type="button" data-pm-tab="demifine">Demifine® Jewellery</button>
                        <button type="button" data-pm-tab="gold">Gold Jewellery</button>
                    </div>
                </div>
            </div>

            <!-- ======================= FULLSCREEN MOBILE MENU ======================= -->
            <div class="pm-menu-overlay" id="pmMenuOverlay" role="dialog" aria-modal="true" aria-label="Menu">
                <div class="pm-menu-head">
                    <span onclick="window.location.href='index.php'" class="pm-logo" style="font-size:19px;letter-spacing:4px">KALORA</span>
                    <button class="pm-menu-close" type="button" id="pmMenuClose" aria-label="Close menu">&times;</button>
                </div>
                <div class="pm-menu-toggle-wrap not_display">
                    <div class="pm-menu-toggle" id="pmMenuToggle">
                        <button type="button" class="is-active" data-pm-menu-tab="demifine">Demifine®</button>
                        <button type="button" data-pm-menu-tab="gold">Gold</button>
                    </div>
                </div>
                <div class="pm-menu-body">
                    <div class="pm-menu-sidebar" id="pmMenuSidebar">
                        <button type="button" class="is-active " onclick="window.location.href='index.php'" data-pm-cat="category">Home</button>
                        <button class="not_display" type="button" data-pm-cat="gender">Gender</button>
                        <button class="not_display" type="button" data-pm-cat="collection">Collection</button>
                        <button class="not_display" type="button" data-pm-cat="style">Style</button>
                        <button class="not_display" type="button" data-pm-cat="gifting">Gifting</button>

                        <!-- NEW: Brands (DB se) -->
                        <button type="button" data-pm-cat="brands">Brands</button>
                        <div class="pm-sb-brands" id="pmSbBrands">
                            <?php foreach ($brands as $b) { ?>
                                <a href="categorys_products.php?slug=<?= urlencode($b['brand_slug']) ?>"><?= htmlspecialchars($b['brand_name']) ?></a>
                            <?php } ?>
                            <?php if (empty($brands)) { ?><a href="#">No brands found</a><?php } ?>
                        </div>

                        <button type="button" data-pm-cat="fine-silver">Fine Silver <span class="pm-badge">LUXE</span></button>
                        <button type="button" data-pm-cat="fine-silver-collection">Fine Silver Collection</button>
                        <button type="button" data-pm-cat="platinum">Platinum Plated Silver
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6" />
                                <polyline points="15 3 21 3 21 9" />
                                <line x1="10" y1="14" x2="21" y2="3" />
                            </svg>
                        </button>
                        <button class="not_display" type="button" data-pm-cat="tanya-ghavri">Tanya Ghavri
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6" />
                                <polyline points="15 3 21 3 21 9" />
                                <line x1="10" y1="14" x2="21" y2="3" />
                            </svg>
                        </button>
                        <button class="not_display" type="button" data-pm-cat="new-arrivals">New Arrivals
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6" />
                                <polyline points="15 3 21 3 21 9" />
                                <line x1="10" y1="14" x2="21" y2="3" />
                            </svg>
                        </button>
                        <button class="not_display" type="button" data-pm-cat="best-sellers">Best Sellers
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6" />
                                <polyline points="15 3 21 3 21 9" />
                                <line x1="10" y1="14" x2="21" y2="3" />
                            </svg>
                        </button>
                    </div>
                    <div class="pm-menu-grid-wrap">
                        <div class="pm-menu-grid" id="pmMenuGrid">
                            <button class="pm-cat-card" type="button" data-pm-action="category-card"><span class="pm-cat-img">img</span><span>Rings</span></button>
                            <button class="pm-cat-card" type="button" data-pm-action="category-card"><span class="pm-cat-img">img</span><span>Mangalsutras</span></button>
                            <button class="pm-cat-card" type="button" data-pm-action="category-card"><span class="pm-cat-img">img</span><span>Bracelets</span></button>
                            <button class="pm-cat-card" type="button" data-pm-action="category-card"><span class="pm-cat-img">img</span><span>Earrings</span></button>
                            <button class="pm-cat-card" type="button" data-pm-action="category-card"><span class="pm-cat-img">img</span><span>Mens</span></button>
                            <button class="pm-cat-card" type="button" data-pm-action="category-card"><span class="pm-cat-img">img</span><span>Necklaces</span></button>
                        </div>
                        <button class="pm-view-all" type="button" data-pm-action="view-all">View all</button>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- ======================= BRANDS + SEARCH JS ======================= -->
    <script>
        const pmBrands = <?= json_encode($brands, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;
        const pmProductImgPath = "admin_access/uploads/products/"; // apne folder ke hisab se change karo

        function pmEsc(s) {
            const d = document.createElement('div');
            d.textContent = s == null ? '' : s;
            return d.innerHTML;
        }

        /* ---------- Desktop Brands dropdown ---------- */
        document.querySelectorAll('.pm-dd-btn').forEach(function (btn) {
            btn.addEventListener('click', function (e) {
                e.stopPropagation();
                btn.parentElement.classList.toggle('open');
            });
        });
        document.addEventListener('click', function () {
            document.querySelectorAll('.pm-dd.open').forEach(function (d) { d.classList.remove('open'); });
        });

        /* ---------- Mobile: Brands left sidebar me, right wala grid hide ---------- */
        (function () {
            const brandsBtn = document.querySelector('[data-pm-cat="brands"]');
            const list = document.getElementById('pmSbBrands');
            const body = document.querySelector('.pm-menu-body');
            if (!brandsBtn || !list || !body) return;

            function closeBrands() {
                list.classList.remove('show');
                body.classList.remove('brands-open');
            }

            brandsBtn.addEventListener('click', function () {
                const open = !list.classList.contains('show');
                setTimeout(function () {
                    if (open) {
                        document.querySelectorAll('#pmMenuSidebar button').forEach(function (b) { b.classList.remove('is-active'); });
                        brandsBtn.classList.add('is-active');
                        list.classList.add('show');
                        body.classList.add('brands-open');
                    } else {
                        closeBrands();
                    }
                }, 0);
            });

            // Dusre sidebar button par click => brands band, grid wapas
            document.querySelectorAll('#pmMenuSidebar button').forEach(function (b) {
                if (b !== brandsBtn) b.addEventListener('click', closeBrands);
            });
        })();

        /* ---------- Live Search (name / color / size / sku - LIKE) ---------- */
        function pmSetupSearch(inputId, boxId) {
            const input = document.getElementById(inputId);
            const box = document.getElementById(boxId);
            if (!input || !box) return;
            let timer = null, ctrl = null;

            function render(items) {
                if (!items.length) {
                    box.innerHTML = '<div class="pm-sr-empty">Koi product nahi mila</div>';
                } else {
                    box.innerHTML = items.map(function (p) {
                        const sale = parseFloat(p.sale_price), orig = parseFloat(p.original_price);
                        const price = sale > 0 ? sale : orig;
                        const strike = (sale > 0 && orig > sale) ? '<s>\u20B9' + orig.toFixed(0) + '</s>' : '';
                        const img = p.product_image ?  p.product_image : '';
                        const meta = [p.product_color ? 'Color: ' + p.product_color : '', p.product_size ? 'Size: ' + p.product_size : '']
                            .filter(Boolean).map(pmEsc).join(' &bull; ');
                        return '<a class="pm-sr-item" href="product_details.php?slug=' + encodeURIComponent(p.product_slug) + '">' +
                            '<img src="' + pmEsc(img) + '" alt="">' +
                            '<span class="pm-sr-info">' +
                            '<span class="pm-sr-name">' + pmEsc(p.product_name) + '</span>' +
                            (meta ? '<span class="pm-sr-meta">' + meta + '</span>' : '') +
                            '<span class="pm-sr-price">\u20B9' + (price ? price.toFixed(0) : '-') + strike + '</span>' +
                            '</span></a>';
                    }).join('');
                }
                box.classList.add('show');
            }

            input.addEventListener('input', function () {
                clearTimeout(timer);
                const q = input.value.trim();
                if (q.length < 2) { box.classList.remove('show'); box.innerHTML = ''; return; }
                timer = setTimeout(function () {
                    if (ctrl) ctrl.abort();
                    ctrl = new AbortController();
                    fetch('search_ajax.php?q=' + encodeURIComponent(q), { signal: ctrl.signal })
                        .then(function (r) { return r.json(); })
                        .then(render)
                        .catch(function () {});
                }, 300);
            });

            // Enter ya search icon dabane par results hide ho jayenge (sirf typing ke time dikhenge)
            function hideResults() {
                clearTimeout(timer);
                if (ctrl) ctrl.abort();
                box.classList.remove('show');
            }
            input.addEventListener('keydown', function (e) {
                if (e.key === 'Enter') { e.preventDefault(); hideResults(); input.blur(); }
            });
            const btn = input.parentElement.querySelector('[data-pm-action="search"]');
            if (btn) btn.addEventListener('click', hideResults);

            box.addEventListener('click', function (e) { e.stopPropagation(); });
            document.addEventListener('click', function (e) {
                if (!input.parentElement.contains(e.target)) box.classList.remove('show');
            });
        }
        pmSetupSearch('pmSearchDesktop', 'pmResultsDesktop');
        pmSetupSearch('pmSearchMobile', 'pmResultsMobile');
    </script>

</body>

</html>