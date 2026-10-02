<?php

include "admin_access/db_config.php";
include "admin_access/functions/global_info.php";
include "admin_access/functions/event.php";

$global_info = get_global_info($mydb);

/* ================= BRANDS ================= */
$brands = [];

$res = mysqli_query(
    $mydb,
    "SELECT brand_id, brand_name, brand_slug, brand_logo
     FROM brands
     WHERE brand_status = 'Active'
     ORDER BY brand_name ASC"
);

if ($res) {
    while ($row = mysqli_fetch_assoc($res)) {
        $brands[] = $row;
    }
}

/* ================= ACTIVE EVENT ================= */
update_kalora_event_status($mydb);

$kalora_front_events = get_kalora_events($mydb);
$kalora_front_active_event = null;

foreach ($kalora_front_events as $kalora_front_event) {
    if (
        isset($kalora_front_event['event_status']) &&
        $kalora_front_event['event_status'] === 'Active'
    ) {
        $kalora_front_active_event = $kalora_front_event;
        break;
    }
}

$kalora_event_end_time = '';
if ($kalora_front_active_event) {
    $kalora_event_end_time = $kalora_front_active_event['end_at'];
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="icon" type="image/x-icon"
        href="assets/logos/<?php echo htmlspecialchars($global_info['facion_icon'] ?? ''); ?>">

    <title>Kalora</title>

    <link rel="stylesheet" href="assets/css/globle.css">
    <link rel="stylesheet" href="assets/css/header.css">
    <link rel="stylesheet" href="assets/css/footer.css">
</head>

<body>

    <!-- =====================================================
     STICKY WRAPPER: Event bar + Header ek saath chipakte hain
====================================================== -->
    <div class="kalora-sticky-top">

        <?php if ($kalora_front_active_event): ?>
            <div class="kalora-event-bar-x72"
                id="kaloraEventBarX72"
                data-event-end="<?php echo htmlspecialchars($kalora_event_end_time); ?>">

                <div class="kalora-event-bar-inner-x72">

                    <img src="assets/events/<?php echo htmlspecialchars($kalora_front_active_event['event_image']); ?>"
                        alt="<?php echo htmlspecialchars($kalora_front_active_event['event_name']); ?>"
                        class="kalora-event-banner-x72">

                    <div class="kalora-event-overlay-x72"></div>

                    <div class="kalora-event-content-x72">

                        <div class="kalora-event-name-x72">
                            <?php echo htmlspecialchars($kalora_front_active_event['event_name']); ?>
                        </div>

                        <div class="kalora-event-countdown-x72" id="kaloraEventCountdownX72">

                            <div class="kalora-event-time-box-x72">
                                <span class="kalora-event-time-number-x72" id="kaloraEventDaysX72">00</span>
                                <span class="kalora-event-time-label-x72">Days</span>
                            </div>

                            <span class="kalora-event-colon-x72">:</span>

                            <div class="kalora-event-time-box-x72">
                                <span class="kalora-event-time-number-x72" id="kaloraEventHoursX72">00</span>
                                <span class="kalora-event-time-label-x72">Hrs</span>
                            </div>

                            <span class="kalora-event-colon-x72">:</span>

                            <div class="kalora-event-time-box-x72">
                                <span class="kalora-event-time-number-x72" id="kaloraEventMinutesX72">00</span>
                                <span class="kalora-event-time-label-x72">Min</span>
                            </div>

                            <span class="kalora-event-colon-x72">:</span>

                            <div class="kalora-event-time-box-x72">
                                <span class="kalora-event-time-number-x72" id="kaloraEventSecondsX72">00</span>
                                <span class="kalora-event-time-label-x72">Sec</span>
                            </div>

                        </div>
                    </div>

                    <button type="button"
                        class="kalora-event-close-x72"
                        id="kaloraEventCloseX72"
                        aria-label="Close event">&times;</button>

                </div>
            </div>
        <?php endif; ?>


        <!-- =====================================================
         HEADER
    ====================================================== -->
        <header>
            <div class="pm-nav">

                <div class="pm-topbar not_display">Buy Rs.99</div>

                <!-- ============ DESKTOP ============ -->
                <div class="pm-desktop">

                    <div class="pm-desktop-top">

                        <button onclick="window.location.href='index.php'" class="pm-logo"
                            type="button" data-pm-action="go-home">KALORA</button>

                        <div class="pm-search">
                            <input type="text" placeholder="Search products..."
                                id="pmSearchDesktop" autocomplete="off" />

                            <button class="pm-icon-btn" style="width:auto;height:auto"
                                type="button" data-pm-action="search" aria-label="Search">
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

                            <button class="pm-icon-btn not_display" type="button"
                                data-pm-action="store-locator" aria-label="Store locator">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M3 9l1-5h16l1 5" />
                                    <path d="M4 9v10h16V9" />
                                    <path d="M9 21v-6h6v6" />
                                </svg>
                            </button>

                            <button class="pm-icon-btn not_display" type="button"
                                data-pm-action="wishlist" aria-label="Wishlist">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.6l-1-1a5.5 5.5 0 0 0-7.8 7.8l1 1L12 21l7.8-7.6 1-1a5.5 5.5 0 0 0 0-7.8z" />
                                </svg>
                            </button>

                            <button onclick="window.location.href='login.php'" class="pm-icon-btn"
                                type="button" data-pm-action="account" aria-label="Account">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="12" cy="8" r="4" />
                                    <path d="M4 21c1.5-4 5-6 8-6s6.5 2 8 6" />
                                </svg>
                            </button>

                            <button class="pm-icon-btn not_display" type="button"
                                data-pm-action="cart" aria-label="Cart">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="9" cy="21" r="1" />
                                    <circle cx="19" cy="21" r="1" />
                                    <path d="M2 3h2l2.4 12.2a2 2 0 0 0 2 1.8h8.6a2 2 0 0 0 2-1.6L21 7H6" />
                                </svg>
                            </button>

                        </div>
                    </div>

                    <!-- NAVIGATION -->
                    <div class="pm-links-row">
                        <nav class="pm-links" id="pmLinksDesktop">

                            <button onclick="window.location.href='index.php'" type="button"
                                data-pm-action="nav-link">Home</button>

                            <button class="not_display" type="button" data-pm-action="nav-link">New Arrivals</button>
                            <button class="not_display" type="button" data-pm-action="nav-link">HomeSellers</button>
                            <button class="not_display" type="button" data-pm-action="nav-link">Best Sellers</button>

                            <button class="not_display" type="button" data-pm-action="nav-dropdown"
                                data-pm-dropdown="fine-silver">
                                Fine Silver
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="6 9 12 15 18 9" />
                                </svg>
                            </button>

                            <button class="not_display" type="button" data-pm-action="nav-dropdown"
                                data-pm-dropdown="demifine">
                                Demifine ® Collection
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="6 9 12 15 18 9" />
                                </svg>
                            </button>

                            <button class="not_display" type="button" data-pm-action="nav-link">Offers</button>
                            <button class="not_display" type="button" data-pm-action="nav-link">Shraddha's Favourite</button>
                            <button class="not_display" type="button" data-pm-action="nav-link">Emily In Paris</button>

                            <!-- BRANDS -->
                            <div class="pm-dd">
                                <button type="button" class="pm-dd-btn" data-pm-dropdown="brands">
                                    Brands
                                    <svg viewBox="0 0 24 24" width="14" height="14" fill="none"
                                        stroke="currentColor" stroke-width="2">
                                        <polyline points="6 9 12 15 18 9" />
                                    </svg>
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

                            <button class="not_display" type="button" data-pm-action="nav-dropdown"
                                data-pm-dropdown="about">
                                About Us
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="6 9 12 15 18 9" />
                                </svg>
                            </button>

                        </nav>
                    </div>

                    <div class="pm-pill-wrap" style="display:none;">
                        <div class="pm-pill" id="pmPillDesktop">
                            <button type="button" class="is-active" data-pm-tab="home">Home</button>
                            <button type="button" data-pm-tab="demifine">Demifine® Jewellery</button>
                            <button type="button" data-pm-tab="gold">Gold Jewellery</button>
                        </div>
                    </div>

                </div>


                <!-- ============ MOBILE / TABLET ============ -->
                <div class="pm-mobile">

                    <div class="pm-mobile-top">

                        <button class="pm-hamburger" type="button" id="pmHamburger"
                            aria-label="Open menu" aria-expanded="false">
                            <span></span><span></span><span></span>
                        </button>

                        <button onclick="window.location.href='index.php'" class="pm-logo" type="button"
                            style="font-size:19px;letter-spacing:4px" data-pm-action="go-home">KALORA</button>

                        <div class="pm-right">

                            <button class="pm-icon-btn not_display" type="button"
                                data-pm-action="store-locator" aria-label="Store locator">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M3 9l1-5h16l1 5" />
                                    <path d="M4 9v10h16V9" />
                                    <path d="M9 21v-6h6v6" />
                                </svg>
                            </button>

                            <button class="pm-icon-btn not_display" type="button"
                                data-pm-action="wishlist" aria-label="Wishlist">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.6l-1-1a5.5 5.5 0 0 0-7.8 7.8l1 1L12 21l7.8-7.6 1-1a5.5 5.5 0 0 0 0-7.8z" />
                                </svg>
                            </button>

                            <button onclick="window.location.href='login.php'" class="pm-icon-btn"
                                type="button" data-pm-action="account" aria-label="Account">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="12" cy="8" r="4" />
                                    <path d="M4 21c1.5-4 5-6 8-6s6.5 2 8 6" />
                                </svg>
                            </button>

                        </div>
                    </div>

                    <div class="pm-mobile-search-wrap">
                        <div class="pm-search">
                            <input type="text" placeholder="Search Products..."
                                id="pmSearchMobile" autocomplete="off" />

                            <button class="pm-icon-btn" style="width:auto;height:auto"
                                type="button" data-pm-action="search" aria-label="Search">
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

                    <div class="pm-pill-wrap" style="display:none;">
                        <div class="pm-pill" id="pmPillMobile">
                            <button type="button" class="is-active" data-pm-tab="home">Home</button>
                            <button type="button" data-pm-tab="demifine">Demifine® Jewellery</button>
                            <button type="button" data-pm-tab="gold">Gold Jewellery</button>
                        </div>
                    </div>

                </div>


                <!-- ============ FULLSCREEN MOBILE MENU ============ -->
                <div class="pm-menu-overlay" id="pmMenuOverlay"
                    role="dialog" aria-modal="true" aria-label="Menu">

                    <div class="pm-menu-head">
                        <span onclick="window.location.href='index.php'" class="pm-logo"
                            style="font-size:19px;letter-spacing:4px">KALORA</span>

                        <button class="pm-menu-close" type="button" id="pmMenuClose"
                            aria-label="Close menu">&times;</button>
                    </div>

                    <div class="pm-menu-toggle-wrap not_display">
                        <div class="pm-menu-toggle" id="pmMenuToggle">
                            <button type="button" class="is-active" data-pm-menu-tab="demifine">Demifine®</button>
                            <button type="button" data-pm-menu-tab="gold">Gold</button>
                        </div>
                    </div>

                    <div class="pm-menu-body">

                        <div class="pm-menu-sidebar" id="pmMenuSidebar">

                            <button type="button" class="is-active"
                                onclick="window.location.href='index.php'"
                                data-pm-cat="category">Home</button>

                            <button class="not_display" type="button" data-pm-cat="gender">Gender</button>
                            <button class="not_display" type="button" data-pm-cat="collection">Collection</button>
                            <button class="not_display" type="button" data-pm-cat="style">Style</button>
                            <button class="not_display" type="button" data-pm-cat="gifting">Gifting</button>

                            <!-- BRANDS -->
                            <button type="button" data-pm-cat="brands">Brands</button>

                            <div class="pm-sb-brands" id="pmSbBrands">
                                <?php foreach ($brands as $b) { ?>
                                    <a href="categorys_products.php?slug=<?= urlencode($b['brand_slug']) ?>">
                                        <?= htmlspecialchars($b['brand_name']) ?>
                                    </a>
                                <?php } ?>

                                <?php if (empty($brands)) { ?>
                                    <a href="#">No brands found</a>
                                <?php } ?>
                            </div>

                            <button type="button" data-pm-cat="fine-silver">
                                Fine Silver <span class="pm-badge">LUXE</span>
                            </button>

                            <button type="button" data-pm-cat="fine-silver-collection">Fine Silver Collection</button>

                            <button type="button" data-pm-cat="platinum">
                                Platinum Plated Silver
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6" />
                                    <polyline points="15 3 21 3 21 9" />
                                    <line x1="10" y1="14" x2="21" y2="3" />
                                </svg>
                            </button>

                            <button class="not_display" type="button" data-pm-cat="tanya-ghavri">Tanya Ghavri</button>
                            <button class="not_display" type="button" data-pm-cat="new-arrivals">New Arrivals</button>
                            <button class="not_display" type="button" data-pm-cat="best-sellers">Best Sellers</button>

                        </div>

                        <div class="pm-menu-grid-wrap">
                            <div class="pm-menu-grid" id="pmMenuGrid">
                                <button class="pm-cat-card" type="button" data-pm-action="category-card">
                                    <span class="pm-cat-img">img</span><span>Rings</span>
                                </button>
                                <button class="pm-cat-card" type="button" data-pm-action="category-card">
                                    <span class="pm-cat-img">img</span><span>Mangalsutras</span>
                                </button>
                                <button class="pm-cat-card" type="button" data-pm-action="category-card">
                                    <span class="pm-cat-img">img</span><span>Bracelets</span>
                                </button>
                                <button class="pm-cat-card" type="button" data-pm-action="category-card">
                                    <span class="pm-cat-img">img</span><span>Earrings</span>
                                </button>
                                <button class="pm-cat-card" type="button" data-pm-action="category-card">
                                    <span class="pm-cat-img">img</span><span>Mens</span>
                                </button>
                                <button class="pm-cat-card" type="button" data-pm-action="category-card">
                                    <span class="pm-cat-img">img</span><span>Necklaces</span>
                                </button>
                            </div>

                            <button class="pm-view-all" type="button" data-pm-action="view-all">View all</button>
                        </div>

                    </div>
                </div>

            </div>
        </header>

    </div>
    <!-- /.kalora-sticky-top -->