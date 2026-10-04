<?php
include_once 'admin_access/db_config.php';

if (!function_exists('ts_h')) {
    function ts_h($s) { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
}
if (!function_exists('ts_money')) {
    function ts_money($n) { return '₹ ' . number_format((float)$n, 2); }
}

/* ================= BRANDS + HAR BRAND KE 8 PRODUCTS ================= */
$tsBrands = [];
$br = mysqli_query($mydb, "SELECT brand_id, brand_name, brand_slug
                           FROM brands
                           WHERE brand_status = 'Active'
                           ORDER BY brand_name ASC");
if ($br) {
    while ($row = mysqli_fetch_assoc($br)) {
        $tsBrands[] = $row;
    }
}

$tsStmt = mysqli_prepare($mydb, "SELECT product_name, product_slug, product_image,
                                        original_price, sale_price, discount_visibility
                                 FROM products
                                 WHERE brand_id = ? AND product_status = 'Active'
                                 ORDER BY product_id DESC
                                 LIMIT 8");

foreach ($tsBrands as $i => $b) {
    $tsBrands[$i]['products'] = [];
    if ($tsStmt) {
        $bid = (int)$b['brand_id'];
        mysqli_stmt_bind_param($tsStmt, "i", $bid);
        mysqli_stmt_execute($tsStmt);
        $pr = mysqli_stmt_get_result($tsStmt);
        while ($prow = mysqli_fetch_assoc($pr)) {
            $tsBrands[$i]['products'][] = $prow;
        }
    }
}

$productPage = 'product_details.php'; // product page ka file name
?>
<style>
    .palmonas-wrapper { font-family: Arial, sans-serif; background: #fdf6ea !important; color: #111111; padding: 40px 20px; box-sizing: border-box; width: 100%; }
    .palmonas-container { max-width: 1280px; margin: 0 auto; box-sizing: border-box; }
    .palmonas-title { text-align: center; font-size: 24px; letter-spacing: 2px; font-weight: 500; margin-bottom: 30px; color: #000000; animation: palmonasFadeDown 0.8s ease-out; }
    .palmonas-nav { display: flex; justify-content: center; align-items: center; flex-wrap: wrap; gap: 10px; margin-bottom: 40px; animation: palmonasFadeIn 1s ease-out; }
    .palmonas-nav-btn { background-color: #FDF6EA; border: 1px solid #111111; color: #111111; padding: 10px 22px; font-size: 13px; letter-spacing: 1px; cursor: pointer; transition: all 0.3s ease; font-weight: 500; text-transform: uppercase; }
    .palmonas-nav-btn:hover { background-color: #f4f4f4; transform: translateY(-2px); }
    .palmonas-nav-btn.palmonas-active { background: linear-gradient(145deg, #0f2c1f 0%, #071911 100%); color: #ffffff; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2); }

    .palmonas-grid { display: none; grid-template-columns: repeat(4, 1fr); gap: 24px; margin-bottom: 40px; }
    .palmonas-grid.palmonas-show { display: grid; }

    .palmonas-soon { grid-column: 1 / -1; text-align: center; padding: 60px 20px; font-size: 18px; letter-spacing: 2px; color: #5c4d43; border: 1px dashed #c9b99f; border-radius: 10px; }

    .palmonas-card { background: linear-gradient(145deg, #0f2c1f 0%, #071911 100%); padding: 15px; border-radius: 10px; position: relative; display: flex; flex-direction: column; cursor: pointer; transition: transform 0.4s cubic-bezier(0.165, 0.84, 0.44, 1), box-shadow 0.4s ease; animation: palmonasScaleUp 0.6s ease-out backwards; }
    .palmonas-card:nth-child(1) { animation-delay: 0.1s; }
    .palmonas-card:nth-child(2) { animation-delay: 0.2s; }
    .palmonas-card:nth-child(3) { animation-delay: 0.3s; }
    .palmonas-card:nth-child(4) { animation-delay: 0.4s; }
    .palmonas-card:hover { transform: translateY(-6px); box-shadow: 0 12px 30px rgba(0, 0, 0, 0.08); }

    .palmonas-img-box { background-color: #f7f7f7; position: relative; width: 100%; padding-top: 115%; overflow: hidden; }
    .palmonas-img-box img { position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover; transition: transform 0.6s ease; }
    .palmonas-card:hover .palmonas-img-box img { transform: scale(1.05); }

    .palmonas-wishlist { position: absolute; bottom: 12px; left: 12px; background: #ffffff; border: none; width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1); transition: transform 0.2s ease, background-color 0.2s ease; z-index: 2; }
    .palmonas-wishlist:hover { transform: scale(1.1); background-color: #fff0f0; }
    .palmonas-wishlist svg { width: 16px; height: 16px; fill: none; stroke: #111111; stroke-width: 1.8; transition: fill 0.2s ease, stroke 0.2s ease; }
    .palmonas-wishlist:hover svg { fill: #ff3b30; stroke: #ff3b30; }

    .palmonas-bag-btn { position: absolute; bottom: 12px; right: 12px; background-color: #ffffff; border: 1px solid #e0e0e0; color: #333333; font-size: 10px; font-weight: 600; letter-spacing: 0.5px; padding: 6px 10px; cursor: pointer; opacity: 0; transform: translateY(10px); transition: all 0.3s ease; z-index: 2; }
    .palmonas-card:hover .palmonas-bag-btn { opacity: 1; transform: translateY(0); }
    .palmonas-bag-btn:hover { background: linear-gradient(145deg, #0f2c1f 0%, #071911 100%); color: #ffffff; border-color: #111111; }

    .palmonas-details { padding: 14px 4px 0 4px; text-align: center; }
    .palmonas-product-name { font-size: 13px; line-height: 1.4; color: #ffffff; margin-bottom: 8px; font-weight: 400; }
    .palmonas-price-box { display: flex; justify-content: center; align-items: center; gap: 8px; font-size: 13px; }
    .palmonas-current-price { font-weight: 600; color: #fffdfd; }
    .palmonas-original-price { text-decoration: line-through; color: #f1a5a5; font-size: 12px; }

    .palmonas-view-all-box { text-align: center; margin-top: 30px; }
    .palmonas-view-all-btn { background-color: #FDF6EA; border: 1px solid #111111; color: #111111; padding: 12px 36px; font-size: 12px; letter-spacing: 1.5px; cursor: pointer; font-weight: 600; transition: all 0.3s ease; }
    .palmonas-view-all-btn:hover { background: linear-gradient(145deg, #0f2c1f 0%, #071911 100%); color: #ffffff; transform: translateY(-2px); box-shadow: 0 6px 15px rgba(0, 0, 0, 0.15); }

    @keyframes palmonasFadeDown { from { opacity: 0; transform: translateY(-20px); } to { opacity: 1; transform: translateY(0); } }
    @keyframes palmonasFadeIn { from { opacity: 0; } to { opacity: 1; } }
    @keyframes palmonasScaleUp { from { opacity: 0; transform: scale(0.95); } to { opacity: 1; transform: scale(1); } }

    @media (max-width: 1024px) { .palmonas-grid { grid-template-columns: repeat(2, 1fr); gap: 20px; } }
    @media (max-width: 580px) {
        .palmonas-grid { grid-template-columns:1fr 1fr; gap: 24px; }
        .palmonas-title { font-size: 20px; }
        .palmonas-nav-btn { padding: 8px 14px; font-size: 11px; }
    }
</style>

<?php if ($tsBrands) { ?>
<div class="palmonas-wrapper" style="display: none;">
    <div class="palmonas-container">

        <h2 class="palmonas-title">KALORA TOP STYLES</h2>

        <!-- Brands (DB se) -->
        <div class="palmonas-nav">
            <?php foreach ($tsBrands as $i => $b) { ?>
                <button type="button"
                        class="palmonas-nav-btn <?= $i === 0 ? 'palmonas-active' : '' ?>"
                        data-ts-brand="<?= (int)$b['brand_id'] ?>"
                        data-ts-slug="<?= ts_h($b['brand_slug']) ?>">
                    <?= ts_h($b['brand_name']) ?>
                </button>
            <?php } ?>
        </div>

        <!-- Har brand ka apna grid (max 8 products) -->
        <?php foreach ($tsBrands as $i => $b) { ?>
            <div class="palmonas-grid <?= $i === 0 ? 'palmonas-show' : '' ?>" data-ts-panel="<?= (int)$b['brand_id'] ?>">

                <?php if (empty($b['products'])) { ?>
                    <div class="palmonas-soon">PRODUCT COMING SOON</div>
                <?php } else { ?>
                    <?php foreach ($b['products'] as $p) {
                        $orig = (float)$p['original_price'];
                        $sale = (float)$p['sale_price'];
                        $img  = trim((string)$p['product_image']);

                        // Discount "Show" ho aur sale price ho => sale price (aur original kata hua)
                        // Discount "Hide" ho => sirf original price
                        $current = 0; $strike = 0;
                        if ($p['discount_visibility'] === 'Show' && $sale > 0) {
                            $current = $sale;
                            if ($orig > $sale) $strike = $orig;
                        } else {
                            $current = $orig > 0 ? $orig : 0;
                        }
                        $link = $productPage . '?slug=' . urlencode($p['product_slug']);
                    ?>
                        <div class="palmonas-card" onclick="window.location.href='<?= ts_h($link) ?>'">
                            <div class="palmonas-img-box">
                                <?php if ($img !== '') { ?>
                                    <img src="<?= ts_h($img) ?>" alt="<?= ts_h($p['product_name']) ?>" loading="lazy">
                                <?php } ?>
                                <button type="button" class="palmonas-wishlist" title="Add to Wishlist" onclick="event.stopPropagation()">
                                    <svg viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                                    </svg>
                                </button>
                                <button type="button" class="palmonas-bag-btn" onclick="event.stopPropagation()">ADD TO BAG</button>
                            </div>
                            <div class="palmonas-details">
                                <p class="palmonas-product-name"><?= ts_h($p['product_name']) ?></p>
                                <?php if ($current > 0) { ?>
                                    <div class="palmonas-price-box">
                                        <span class="palmonas-current-price"><?= ts_money($current) ?></span>
                                        <?php if ($strike > 0) { ?>
                                            <span class="palmonas-original-price"><?= ts_money($strike) ?></span>
                                        <?php } ?>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    <?php } ?>
                <?php } ?>

            </div>
        <?php } ?>

        <div class="palmonas-view-all-box">
            <button type="button" class="palmonas-view-all-btn" id="tsViewAll"
                    data-slug="<?= ts_h($tsBrands[0]['brand_slug']) ?>">VIEW ALL</button>
        </div>

    </div>
</div>

<script>
    (function () {
        const btns = document.querySelectorAll('[data-ts-brand]');
        const panels = document.querySelectorAll('[data-ts-panel]');
        const viewAll = document.getElementById('tsViewAll');

        btns.forEach(function (btn) {
            btn.addEventListener('click', function () {
                const id = btn.getAttribute('data-ts-brand');
                btns.forEach(function (b) { b.classList.remove('palmonas-active'); });
                btn.classList.add('palmonas-active');
                panels.forEach(function (p) {
                    p.classList.toggle('palmonas-show', p.getAttribute('data-ts-panel') === id);
                });
                viewAll.setAttribute('data-slug', btn.getAttribute('data-ts-slug'));
            });
        });

        viewAll.addEventListener('click', function () {
            window.location.href = 'brand.php?slug=' + encodeURIComponent(viewAll.getAttribute('data-slug'));
        });
    })();
</script>
<?php } ?>