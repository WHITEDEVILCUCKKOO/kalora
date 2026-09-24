<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kalora</title>

    <link rel="stylesheet" href="assets/css/globle.css">
    <link rel="stylesheet" href="assets/css/header.css">
    <link rel="stylesheet" href="assets/css/footer.css">
</head>

<body>


    <!--
=======================================================================================================================
                                                        Header
=======================================================================================================================
 -->

    <header>
        <div class="pm-nav">
            <div class="pm-topbar">Buy 1 Get 1 Free | Use Code - B1G1</div>

            <!-- ======================= DESKTOP HEADER ======================= -->
            <div class="pm-desktop">
                <div class="pm-desktop-top">
                    <button class="pm-logo" type="button" data-pm-action="go-home">KALORA</button>
                    <div class="pm-search">
                        <input type="text" placeholder="Search products..." id="pmSearchDesktop" />
                        <button class="pm-icon-btn" style="width:auto;height:auto" type="button" data-pm-action="search" aria-label="Search">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="11" cy="11" r="7" />
                                <line x1="21" y1="21" x2="16.65" y2="16.65" />
                            </svg>
                        </button>
                    </div>
                    <div class="pm-right">
                        <button class="pm-pincode" type="button" data-pm-action="pincode">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 10c0 6-9 12-9 12s-9-6-9-12a9 9 0 0 1 18 0z" />
                                <circle cx="12" cy="10" r="3" />
                            </svg>
                            Enter Pincode
                        </button>
                        <button class="pm-icon-btn" type="button" data-pm-action="store-locator" aria-label="Store locator">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M3 9l1-5h16l1 5" />
                                <path d="M4 9v10h16V9" />
                                <path d="M9 21v-6h6v6" />
                            </svg>
                        </button>
                        <button class="pm-icon-btn" type="button" data-pm-action="wishlist" aria-label="Wishlist">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.6l-1-1a5.5 5.5 0 0 0-7.8 7.8l1 1L12 21l7.8-7.6 1-1a5.5 5.5 0 0 0 0-7.8z" />
                            </svg>
                        </button>
                        <button class="pm-icon-btn" type="button" data-pm-action="account" aria-label="Account">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="8" r="4" />
                                <path d="M4 21c1.5-4 5-6 8-6s6.5 2 8 6" />
                            </svg>
                        </button>
                        <button class="pm-icon-btn" type="button" data-pm-action="cart" aria-label="Cart">
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
                        <button type="button" data-pm-action="nav-link">New Arrivals</button>
                        <button type="button" data-pm-action="nav-link">Best Sellers</button>
                        <button type="button" data-pm-action="nav-dropdown" data-pm-dropdown="fine-silver">Fine Silver
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="6 9 12 15 18 9" />
                            </svg>
                        </button>
                        <button type="button" data-pm-action="nav-dropdown" data-pm-dropdown="demifine">Demifine ® Collection
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="6 9 12 15 18 9" />
                            </svg>
                        </button>
                        <button type="button" data-pm-action="nav-link">Offers</button>
                        <button type="button" data-pm-action="nav-link">Shraddha's Favourite</button>
                        <button type="button" data-pm-action="nav-link">Emily In Paris</button>
                        <button type="button" data-pm-action="nav-dropdown" data-pm-dropdown="gifting">Gifting
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="6 9 12 15 18 9" />
                            </svg>
                        </button>
                        <button type="button" data-pm-action="nav-link">POP</button>
                        <button type="button" data-pm-action="nav-dropdown" data-pm-dropdown="about">About Us
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="6 9 12 15 18 9" />
                            </svg>
                        </button>
                    </nav>
                    <div class="pm-dropdown-panel" data-pm-panel="fine-silver">
                        <p style="font-size:13px;color:#888">Fine Silver submenu — apna content yaha add karo.</p>
                    </div>
                    <div class="pm-dropdown-panel" data-pm-panel="demifine">
                        <p style="font-size:13px;color:#888">Demifine® Collection submenu — apna content yaha add karo.</p>
                    </div>
                    <div class="pm-dropdown-panel" data-pm-panel="gifting">
                        <p style="font-size:13px;color:#888">Gifting submenu — apna content yaha add karo.</p>
                    </div>
                    <div class="pm-dropdown-panel" data-pm-panel="about">
                        <p style="font-size:13px;color:#888">About Us submenu — apna content yaha add karo.</p>
                    </div>
                </div>

                <div class="pm-pill-wrap">
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
                    <button class="pm-logo" type="button" style="font-size:19px;letter-spacing:4px" data-pm-action="go-home">KALORA</button>
                    <div class="pm-right">
                        <button class="pm-icon-btn" type="button" data-pm-action="store-locator" aria-label="Store locator">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M3 9l1-5h16l1 5" />
                                <path d="M4 9v10h16V9" />
                                <path d="M9 21v-6h6v6" />
                            </svg>
                        </button>
                        <button class="pm-icon-btn" type="button" data-pm-action="wishlist" aria-label="Wishlist">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.6l-1-1a5.5 5.5 0 0 0-7.8 7.8l1 1L12 21l7.8-7.6 1-1a5.5 5.5 0 0 0 0-7.8z" />
                            </svg>
                        </button>
                        <button class="pm-icon-btn" type="button" data-pm-action="account" aria-label="Account">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="8" r="4" />
                                <path d="M4 21c1.5-4 5-6 8-6s6.5 2 8 6" />
                            </svg>
                        </button>
                    </div>
                </div>
                <div class="pm-mobile-search-wrap">
                    <div class="pm-search">
                        <input type="text" placeholder="Search Products..." id="pmSearchMobile" />
                        <button class="pm-icon-btn" style="width:auto;height:auto" type="button" data-pm-action="search" aria-label="Search">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="11" cy="11" r="7" />
                                <line x1="21" y1="21" x2="16.65" y2="16.65" />
                            </svg>
                        </button>
                    </div>
                </div>
                <button class="pm-mobile-pincode" type="button" data-pm-action="pincode">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 10c0 6-9 12-9 12s-9-6-9-12a9 9 0 0 1 18 0z" />
                        <circle cx="12" cy="10" r="3" />
                    </svg>
                    Enter Pincode
                </button>
                <div class="pm-mobile-divider"></div>
                <div class="pm-pill-wrap">
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
                    <span class="pm-logo" style="font-size:19px;letter-spacing:4px">KALORA</span>
                    <button class="pm-menu-close" type="button" id="pmMenuClose" aria-label="Close menu">&times;</button>
                </div>
                <div class="pm-menu-toggle-wrap">
                    <div class="pm-menu-toggle" id="pmMenuToggle">
                        <button type="button" class="is-active" data-pm-menu-tab="demifine">Demifine®</button>
                        <button type="button" data-pm-menu-tab="gold">Gold</button>
                    </div>
                </div>
                <div class="pm-menu-body">
                    <div class="pm-menu-sidebar" id="pmMenuSidebar">
                        <button type="button" class="is-active" data-pm-cat="category">Category</button>
                        <button type="button" data-pm-cat="gender">Gender</button>
                        <button type="button" data-pm-cat="collection">Collection</button>
                        <button type="button" data-pm-cat="style">Style</button>
                        <button type="button" data-pm-cat="gifting">Gifting</button>
                        <button type="button" data-pm-cat="fine-silver">Fine Silver <span class="pm-badge">LUXE</span></button>
                        <button type="button" data-pm-cat="fine-silver-collection">Fine Silver Collection</button>
                        <button type="button" data-pm-cat="platinum">Platinum Plated Silver
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6" />
                                <polyline points="15 3 21 3 21 9" />
                                <line x1="10" y1="14" x2="21" y2="3" />
                            </svg>
                        </button>
                        <button type="button" data-pm-cat="tanya-ghavri">Tanya Ghavri
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6" />
                                <polyline points="15 3 21 3 21 9" />
                                <line x1="10" y1="14" x2="21" y2="3" />
                            </svg>
                        </button>
                        <button type="button" data-pm-cat="new-arrivals">New Arrivals
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6" />
                                <polyline points="15 3 21 3 21 9" />
                                <line x1="10" y1="14" x2="21" y2="3" />
                            </svg>
                        </button>
                        <button type="button" data-pm-cat="best-sellers">Best Sellers
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