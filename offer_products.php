<?php
include_once 'admin_access/db_config.php';

if (!function_exists('kl_h')) {
    function kl_h($s) { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
}
if (!function_exists('kl_money')) {
    function kl_money($n) { return '₹' . number_format((float)$n, 2); }
}

$productPage = 'product_details.php'; // product page ka file name

/* ================= PRICE OFFER (?price=99 / 199 / 299 / 399 / 499) =================
   Jo bhi price aaye, usse KAM ya BARABAR price wale saare Active products dikhenge.
   - Discount "Show" aur sale price hai  => sale price se check + discount % dikhega
   - Discount "Hide"                     => original price se check + sirf original price dikhega
*/
$priceParam = trim($_GET['price'] ?? '');
$offerPrice = ($priceParam !== '' && ctype_digit($priceParam)) ? (int)$priceParam : 0;
$klProducts = [];

if ($offerPrice > 0) {
    $sql = "SELECT product_name, product_slug, product_image, product_color, product_size,
                   original_price, sale_price, discount_visibility
            FROM products
            WHERE product_status = 'Active'
              AND (CASE WHEN discount_visibility = 'Show' AND sale_price > 0
                        THEN sale_price ELSE original_price END) > 0
              AND (CASE WHEN discount_visibility = 'Show' AND sale_price > 0
                        THEN sale_price ELSE original_price END) <= ?
            ORDER BY product_id DESC";

    $stmt = mysqli_prepare($mydb, $sql);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, 'd', $offerPrice);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        while ($res && ($row = mysqli_fetch_assoc($res))) {
            $klProducts[] = $row;
        }
        mysqli_stmt_close($stmt);
    }
}
?>
<?php include_once 'includes/header.php' ?>

<style>
    main { overflow: hidden; }
</style>

<main>

<div class="klr-wrapper">
    <div class="klr-container">

        <h2 class="klr-title"><?= $offerPrice > 0 ? 'UNDER ₹' . $offerPrice : 'OFFERS' ?></h2>

        <div class="klr-grid">

            <?php if (empty($klProducts)) { ?>
                <div class="klr-soon">OFFER WILL START AFTER SOME TIME</div>
            <?php } else { ?>
                <?php foreach ($klProducts as $p) {
                    $orig = (float)$p['original_price'];
                    $sale = (float)$p['sale_price'];
                    $img  = trim((string)$p['product_image']);

                    $current = 0; $strike = 0; $pct = 0;
                    if ($p['discount_visibility'] === 'Show' && $sale > 0) {
                        // Discount ON: sale price + cut price + % off
                        $current = $sale;
                        if ($orig > $sale) {
                            $strike = $orig;
                            $pct = round((($orig - $sale) / $orig) * 100);
                        }
                    } else {
                        // Discount OFF: sirf original price
                        $current = $orig > 0 ? $orig : 0;
                    }

                    $color = trim((string)$p['product_color']);
                    $size  = trim((string)$p['product_size']);
                    $link  = $productPage . '?slug=' . urlencode($p['product_slug']);
                ?>
                    <div class="klr-card" onclick="window.location.href='<?= kl_h($link) ?>'">
                        <div class="klr-img-box">
                            <button type="button" class="klr-wishlist" title="Add to Wishlist" onclick="event.stopPropagation()">
                                <svg viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" /></svg>
                            </button>
                            <?php if ($img !== '') { ?>
                                <img src="<?= kl_h($img) ?>" alt="<?= kl_h($p['product_name']) ?>" loading="lazy">
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

                            <!-- 1 ghante ka countdown -->
                            <div class="klr-timer" data-klr-timer>
                                <span class="klr-timer-label">Offer ends in</span>
                                <span class="klr-timer-time">01:00:00</span>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            <?php } ?>

        </div>

    </div>
</div>

<style>
    .klr-wrapper { font-family: Arial, sans-serif; background-color: #FDF6EA; color: #111111; padding: 50px 20px; box-sizing: border-box; width: 100%; }
    .klr-container { max-width: 1300px; margin: 0 auto; box-sizing: border-box; }
    .klr-title { text-align: center; font-size: 20px; letter-spacing: 2px; font-weight: 500; margin-bottom: 40px; color: #222222; animation: klrFadeDown 0.8s ease-out; }
    .klr-grid { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 24px; margin-bottom: 40px; }
    .klr-soon { grid-column: 1 / -1; text-align: center; padding: 60px 20px; font-size: 18px; letter-spacing: 2px; color: #5c4d43; border: 1px dashed #c9b99f; border-radius: 10px; }

    .klr-card { min-width: 0; background: linear-gradient(145deg, #0f2c1f 0%, #071911 100%); padding: 10px; border-radius: 10px; position: relative; display: flex; flex-direction: column; cursor: pointer; transition: transform 0.4s cubic-bezier(0.165, 0.84, 0.44, 1), box-shadow 0.4s ease; animation: klrScaleUp 0.6s ease-out backwards; }
    .klr-card:hover { transform: translateY(-5px); box-shadow: 0 10px 25px rgba(0, 0, 0, 0.07); }

    .klr-img-box { background-color: #f8f8f8; position: relative; width: 100%; padding-top: 115%; overflow: hidden; border-radius: 10px; }
    .klr-img-box img { position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover; transition: transform 0.6s ease; }
    .klr-card:hover .klr-img-box img { transform: scale(1.04); }

    .klr-wishlist { position: absolute; top: 12px; right: 12px; background: transparent; border: none; width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; z-index: 2; transition: transform 0.2s ease; }
    .klr-wishlist:hover { transform: scale(1.15); }
    .klr-wishlist svg { width: 18px; height: 18px; fill: none; stroke: #666666; stroke-width: 1.6; transition: fill 0.2s ease, stroke 0.2s ease; }
    .klr-wishlist:hover svg { fill: #ff3b30; stroke: #ff3b30; }

    .klr-details { padding: 14px 6px 0 6px; display: flex; flex-direction: column; gap: 6px; min-width: 0; }
    .klr-product-name { font-size: 12px; line-height: 1.4; color: #ffffff; font-weight: 400; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; margin: 0; }
    .klr-price-box { display: flex; align-items: center; gap: 8px; font-size: 13px; }
    .klr-current-price { font-weight: 600; color: #fffefe; }
    .klr-original-price { text-decoration: line-through; color: #fad1d1; font-size: 12px; }
    .klr-badge-box { margin: 2px 0; }
    .klr-discount-badge { background-color: #eaf6ec; color: #2b7a3e; font-size: 10px; padding: 3px 8px; font-weight: 500; display: inline-block; border-radius: 2px; }
    .klr-meta-row { display: flex; justify-content: space-between; align-items: center; gap: 8px; margin-top: 4px; }
    .klr-gold-type { font-size: 11px; color: #b9c4bd; font-weight: 500; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }

    .klr-timer { display: flex; justify-content: space-between; align-items: center; gap: 6px; margin-top: 6px; padding: 6px 8px; border-radius: 6px; background: rgba(212,175,55,0.14); color: #f3d98b; font-size: 11px; }
    .klr-timer-time { font-weight: 700; font-variant-numeric: tabular-nums; letter-spacing: 0.5px; }
    .klr-timer.klr-ended { background: rgba(255,255,255,0.08); color: #b9c4bd; }
    .klr-card.klr-expired .klr-img-box img { filter: grayscale(0.8); opacity: 0.7; }

    @keyframes klrFadeDown { from { opacity: 0; transform: translateY(-15px); } to { opacity: 1; transform: translateY(0); } }
    @keyframes klrScaleUp { from { opacity: 0; transform: scale(0.97); } to { opacity: 1; transform: scale(1); } }

    @media (max-width: 1024px) { .klr-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 20px; } }

    /* Phone: 2-2 products ek row me */
    @media (max-width: 580px) {
        .klr-wrapper { padding: 30px 12px; }
        .klr-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 12px; }
        .klr-title { font-size: 18px; margin-bottom: 24px; }
        .klr-card { padding: 6px; border-radius: 12px; }
        .klr-img-box { border-radius: 8px; }
        .klr-details { padding: 10px 4px 4px 4px; gap: 5px; }
        .klr-product-name { font-size: 11px; }
        .klr-price-box { flex-wrap: wrap; gap: 2px 6px; font-size: 12px; }
        .klr-original-price { font-size: 11px; }
        .klr-discount-badge { font-size: 9px; padding: 2px 6px; }
        .klr-meta-row { flex-direction: column; align-items: flex-start; gap: 2px; }
        .klr-gold-type { font-size: 10px; max-width: 100%; }
        .klr-wishlist { top: 6px; right: 6px; width: 28px; height: 28px; }
        .klr-wishlist svg { width: 16px; height: 16px; }
        .klr-timer { font-size: 10px; padding: 5px 6px; }
    }
</style>

<script>
    // Har offer price ke liye 1 ghante ka countdown.
    // Khatam hone par khud dobara 1 ghante ke liye shuru ho jata hai.
    (function () {
        const DURATION = 60 * 60 * 1000; // 1 hour
        const key = 'kalora_offer_start_<?= (int)$offerPrice ?>';
        const timers = document.querySelectorAll('[data-klr-timer]');
        if (!timers.length) return;

        function pad(n) { return String(n).padStart(2, '0'); }

        function getStart() {
            let s = NaN;
            try { s = parseInt(localStorage.getItem(key), 10); } catch (e) {}
            if (!s || isNaN(s) || Date.now() - s >= DURATION) {
                s = Date.now();
                try { localStorage.setItem(key, String(s)); } catch (e) {}
            }
            return s;
        }

        let start = getStart();

        function tick() {
            let left = start + DURATION - Date.now();
            if (left <= 0) {
                start = getStart();
                left = start + DURATION - Date.now();
            }
            const h = Math.floor(left / 3600000);
            const m = Math.floor((left % 3600000) / 60000);
            const sec = Math.floor((left % 60000) / 1000);

            timers.forEach(function (t) {
                t.querySelector('.klr-timer-label').textContent = 'Offer ends in';
                t.querySelector('.klr-timer-time').textContent = pad(h) + ':' + pad(m) + ':' + pad(sec);
                t.classList.remove('klr-ended');
                const card = t.closest('.klr-card');
                if (card) card.classList.remove('klr-expired');
            });
            setTimeout(tick, 1000);
        }
        tick();
    })();
</script>

</main>

<?php include_once 'includes/footter.php' ?>