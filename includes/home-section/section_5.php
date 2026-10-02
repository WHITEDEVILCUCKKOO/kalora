<?php
include_once 'admin_access/db_config.php';

if (!function_exists('kl_h')) {
    function kl_h($s)
    {
        return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8');
    }
}
if (!function_exists('kl_money')) {
    function kl_money($n)
    {
        return '₹' . number_format((float)$n, 2);
    }
}

$productPage = 'product_details.php'; // product page ka file name
$viewAllPage = 'index.php';        // View All par jo page kholna hai

/* ================= SAARE BRANDS SE RANDOM 8 PRODUCTS ================= */
$klProducts = [];
$kr = mysqli_query($mydb, "SELECT product_name, product_slug, product_image, product_color, product_size,
                                  original_price, sale_price, discount_visibility
                           FROM products
                           WHERE product_status = 'Active'
                           ORDER BY RAND()
                           LIMIT 8");
if ($kr) {
    while ($row = mysqli_fetch_assoc($kr)) {
        $klProducts[] = $row;
    }
}
?>
<div class="klr-wrapper">
    <div class="klr-container">

        <h2 class="klr-title">All Products</h2>

        <!-- viewport: mobile me slider ka visible area -->
        <div class="klr-viewport" id="klrViewport">
            <div class="klr-grid" id="klrGrid">

                <?php if (empty($klProducts)) { ?>
                    <div class="klr-soon">PRODUCT COMING SOON</div>
                <?php } else { ?>
                    <?php foreach ($klProducts as $p) {
                        $orig = (float)$p['original_price'];
                        $sale = (float)$p['sale_price'];
                        $img  = trim((string)$p['product_image']);

                        // Discount "Show" + sale price => sale price (original kata hua)
                        // Discount "Hide" => sirf original price
                        $current = 0;
                        $strike = 0;
                        $pct = 0;
                        if ($p['discount_visibility'] === 'Show' && $sale > 0) {
                            $current = $sale;
                            if ($orig > $sale) {
                                $strike = $orig;
                                $pct = round((($orig - $sale) / $orig) * 100);
                            }
                        } else {
                            $current = $orig > 0 ? $orig : 0;
                        }

                        $color = trim((string)$p['product_color']);
                        $size  = trim((string)$p['product_size']);
                        $link  = $productPage . '?slug=' . urlencode($p['product_slug']);
                    ?>
                        <div class="klr-card" onclick="window.location.href='<?= kl_h($link) ?>'">
                            <div class="klr-img-box">
                                <button type="button" class="klr-wishlist" title="Add to Wishlist" onclick="event.stopPropagation()">
                                    <svg viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                                    </svg>
                                </button>
                                <?php if ($img !== '') { ?>
                                    <img src="<?= kl_h($img) ?>" alt="<?= kl_h($p['product_name']) ?>" loading="lazy" draggable="false">
                                <?php } ?>
                            </div>
                            <div class="klr-details">
                                <p class="klr-product-name"><?= kl_h($p['product_name']) ?></p>

                                <?php if ($current > 0) { ?>
                                    <div class="klr-price-box">
                                        <span class="klr-current-price"><?= kl_money($current) ?></span>
                                        <?php if ($strike > 0) { ?>
                                            <span class="klr-original-price"><?= kl_money($strike) ?></span>
                                        <?php } ?>
                                    </div>
                                <?php } ?>

                                <?php if ($pct > 0) { ?>
                                    <div class="klr-badge-box">
                                        <span class="klr-discount-badge">Flat <?= $pct ?>% off on MRP</span>
                                    </div>
                                <?php } ?>

                                <?php if ($color !== '' || $size !== '') { ?>
                                    <div class="klr-meta-row">
                                        <span class="klr-gold-type"><?= $color !== '' ? kl_h($color) : '' ?></span>
                                        <span class="klr-gold-type"><?= $size !== '' ? kl_h($size) : '' ?></span>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    <?php } ?>
                <?php } ?>

            </div>
        </div>

        <div class="klr-view-all-box">
            <button type="button" class="klr-view-all-btn" onclick="window.location.href='<?= kl_h($viewAllPage) ?>'">View All</button>
        </div>

    </div>
</div>

<style>
    .klr-wrapper {
        font-family: Arial, sans-serif;
        background: #fdf6ea !important;
        color: #111111;
        padding: 50px 20px;
        box-sizing: border-box;
        width: 100%;
    }

    .klr-container {
        max-width: 1300px;
        margin: 0 auto;
        box-sizing: border-box;
    }

    .klr-title {
        text-align: center;
        font-size: 20px;
        letter-spacing: 2px;
        font-weight: 500;
        margin-bottom: 40px;
        color: #222222;
        animation: klrFadeDown 0.8s ease-out;
    }

    .klr-viewport {
        width: 100%;
    }

    .klr-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 24px;
        margin-bottom: 40px;
    }

    .klr-soon {
        grid-column: 1 / -1;
        text-align: center;
        padding: 60px 20px;
        font-size: 18px;
        letter-spacing: 2px;
        color: #5c4d43;
        border: 1px dashed #c9b99f;
        border-radius: 10px;
    }

    .klr-card {
        min-width: 0;
        background: linear-gradient(145deg, #0f2c1f 0%, #071911 100%);
        padding: 10px;
        border-radius: 15px;
        position: relative;
        display: flex;
        flex-direction: column;
        cursor: pointer;
        transition: transform 0.4s cubic-bezier(0.165, 0.84, 0.44, 1), box-shadow 0.4s ease;
        animation: klrScaleUp 0.6s ease-out backwards;
    }

    .klr-card:nth-child(1) {
        animation-delay: 0.05s;
    }

    .klr-card:nth-child(2) {
        animation-delay: 0.1s;
    }

    .klr-card:nth-child(3) {
        animation-delay: 0.15s;
    }

    .klr-card:nth-child(4) {
        animation-delay: 0.2s;
    }

    .klr-card:nth-child(5) {
        animation-delay: 0.25s;
    }

    .klr-card:nth-child(6) {
        animation-delay: 0.3s;
    }

    .klr-card:nth-child(7) {
        animation-delay: 0.35s;
    }

    .klr-card:nth-child(8) {
        animation-delay: 0.4s;
    }

    .klr-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.07);
    }

    .klr-img-box {
        background-color: #f8f8f8;
        position: relative;
        width: 100%;
        padding-top: 115%;
        overflow: hidden;
        border-radius: 10px;
    }

    .klr-img-box img {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.6s ease;
    }

    .klr-card:hover .klr-img-box img {
        transform: scale(1.04);
    }

    .klr-wishlist {
        position: absolute;
        top: 12px;
        right: 12px;
        background: transparent;
        border: none;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        z-index: 2;
        transition: transform 0.2s ease;
    }

    .klr-wishlist:hover {
        transform: scale(1.15);
    }

    .klr-wishlist svg {
        width: 18px;
        height: 18px;
        fill: none;
        stroke: #666666;
        stroke-width: 1.6;
        transition: fill 0.2s ease, stroke 0.2s ease;
    }

    .klr-wishlist:hover svg {
        fill: #ff3b30;
        stroke: #ff3b30;
    }

    .klr-details {
        padding: 14px 6px 0 6px;
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .klr-product-name {
        font-size: 12px;
        line-height: 1.4;
        color: #ffffff;
        font-weight: 400;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        margin: 0;
    }

    .klr-price-box {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 13px;
    }

    .klr-current-price {
        font-weight: 600;
        color: #fcfcfc;
    }

    .klr-original-price {
        text-decoration: line-through;
        color: #f7c7c7;
        font-size: 12px;
    }

    .klr-badge-box {
        margin: 2px 0;
    }

    .klr-discount-badge {
        background-color: #eaf6ec;
        color: #2b7a3e;
        font-size: 10px;
        padding: 3px 8px;
        font-weight: 500;
        display: inline-block;
        border-radius: 2px;
    }

    .klr-meta-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 8px;
        margin-top: 4px;
    }

    .klr-gold-type {
        font-size: 11px;
        color: #b9c4bd;
        font-weight: 500;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .klr-view-all-box {
        text-align: center;
        margin-top: 40px;
    }

    .klr-view-all-btn {
        background-color: #FDF6EA;
        border: 1px solid #555555;
        color: #222222;
        padding: 10px 32px;
        font-size: 12px;
        letter-spacing: 1px;
        cursor: pointer;
        font-weight: 500;
        border-radius: 2px;
        transition: all 0.3s ease;
    }

    .klr-view-all-btn:hover {
        background: linear-gradient(145deg, #0f2c1f 0%, #071911 100%);
        color: #ffffff;
        border-color: #111111;
        transform: translateY(-2px);
    }

    @keyframes klrFadeDown {
        from {
            opacity: 0;
            transform: translateY(-15px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes klrScaleUp {
        from {
            opacity: 0;
            transform: scale(0.97);
        }

        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    /* Tablet: 2 column grid (normal) */
    @media (max-width: 1024px) {
        .klr-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 20px;
        }
    }

    /* ================= PHONE: AUTO SLIDER (2 cards visible) ================= */
    @media (max-width: 580px) {
        .klr-wrapper {
            padding: 30px 12px;
        }

        .klr-title {
            font-size: 18px;
            margin-bottom: 24px;
        }

        .klr-viewport {
            overflow: hidden;
            touch-action: pan-y;
        }

        /* JS ke baad ye class lagti hai */
        .klr-grid.klr-slider-on {
            display: flex;
            flex-wrap: nowrap;
            gap: 12px;
            margin-bottom: 0;
            will-change: transform;
        }

        .klr-grid.klr-slider-on .klr-card {
            flex: 0 0 calc((100% - 12px) / 2);
            /* ek baar me 2 card */
            animation: none;
        }

        /* JS na chale to fallback normal 2-col grid */
        .klr-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 12px;
        }

        .klr-card {
            padding: 6px;
            border-radius: 12px;
        }

        .klr-img-box {
            border-radius: 8px;
        }

        .klr-details {
            padding: 10px 4px 4px 4px;
            gap: 5px;
            min-width: 0;
        }

        .klr-product-name {
            font-size: 11px;
        }

        .klr-price-box {
            flex-wrap: wrap;
            gap: 2px 6px;
            font-size: 12px;
        }

        .klr-original-price {
            font-size: 11px;
        }

        .klr-discount-badge {
            font-size: 9px;
            padding: 2px 6px;
        }

        .klr-meta-row {
            flex-direction: column;
            align-items: flex-start;
            gap: 2px;
        }

        .klr-gold-type {
            font-size: 10px;
            max-width: 100%;
        }

        .klr-wishlist {
            top: 6px;
            right: 6px;
            width: 28px;
            height: 28px;
        }

        .klr-wishlist svg {
            width: 16px;
            height: 16px;
        }
    }
</style>

<script>
    (function() {
        "use strict";

        var grid = document.getElementById('klrGrid');
        var viewport = document.getElementById('klrViewport');
        if (!grid || !viewport) return;

        var AUTO_DELAY = 2500; /* kitne ms baad agla slide */
        var STEP = 2; /* ek baar me 2 card aage */
        var mq = window.matchMedia('(max-width: 580px)');

        var originals = Array.prototype.slice.call(grid.querySelectorAll('.klr-card'));
        var n = originals.length;
        var idx = 0;
        var timer = null;
        var startX = 0,
            startY = 0,
            swiped = false;

        function unit() {
            var c = grid.querySelector('.klr-card');
            var gap = parseFloat(getComputedStyle(grid).columnGap) || 12;
            return c.offsetWidth + gap;
        }

        function setPos(i, animate) {
            grid.style.transition = animate ? 'transform .6s ease' : 'none';
            grid.style.transform = 'translate3d(' + (-i * unit()) + 'px,0,0)';
        }

        function next() {
            idx = Math.min(idx + STEP, n);
            setPos(idx, true);
        }

        function prev() {
            if (idx === 0) {
                idx = n; /* clone par jao (bina animation) */
                setPos(idx, false);
                void grid.offsetWidth; /* reflow */
            }
            idx = Math.max(idx - STEP, 0);
            setPos(idx, true);
        }

        /* Clone tak pahunch gaye to chupke se start par wapas */
        grid.addEventListener('transitionend', function(e) {
            if (e.target !== grid || e.propertyName !== 'transform') return;
            if (idx >= n) {
                idx = 0;
                setPos(0, false);
            }
        });

        function start() {
            stop();
            timer = setInterval(next, AUTO_DELAY);
        }

        function stop() {
            if (timer) {
                clearInterval(timer);
                timer = null;
            }
        }

        function teardown() {
            stop();
            Array.prototype.forEach.call(grid.querySelectorAll('.klr-clone'), function(c) {
                c.remove();
            });
            grid.classList.remove('klr-slider-on');
            grid.style.transform = '';
            grid.style.transition = '';
            idx = 0;
        }

        function setup() {
            teardown();
            if (!mq.matches || n <= STEP) return; /* sirf phone par */

            /* Infinite loop ke liye pehle 2 card end me clone */
            for (var k = 0; k < STEP; k++) {
                var clone = originals[k].cloneNode(true);
                clone.classList.add('klr-clone');
                clone.setAttribute('aria-hidden', 'true');
                grid.appendChild(clone);
            }

            grid.classList.add('klr-slider-on');
            setPos(0, false);
            start();
        }

        /* Swipe support */
        viewport.addEventListener('touchstart', function(e) {
            if (!grid.classList.contains('klr-slider-on')) return;
            startX = e.touches[0].clientX;
            startY = e.touches[0].clientY;
            swiped = false;
            stop();
        }, {
            passive: true
        });

        viewport.addEventListener('touchend', function(e) {
            if (!grid.classList.contains('klr-slider-on')) return;
            var dx = e.changedTouches[0].clientX - startX;
            var dy = e.changedTouches[0].clientY - startY;
            if (Math.abs(dx) > 40 && Math.abs(dx) > Math.abs(dy)) {
                swiped = true;
                dx < 0 ? next() : prev();
            }
            start();
        }, {
            passive: true
        });

        /* Swipe ke baad accidental card click na ho */
        viewport.addEventListener('click', function(e) {
            if (swiped) {
                e.stopPropagation();
                e.preventDefault();
                swiped = false;
            }
        }, true);

        /* Tab hide ho to rok do */
        document.addEventListener('visibilitychange', function() {
            if (!grid.classList.contains('klr-slider-on')) return;
            document.hidden ? stop() : start();
        });

        window.addEventListener('resize', function() {
            if (grid.classList.contains('klr-slider-on')) setPos(idx, false);
        });

        if (mq.addEventListener) mq.addEventListener('change', setup);
        else mq.addListener(setup);

        setup();
    })();
</script>