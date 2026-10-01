<?php
include_once 'admin_access/db_config.php';

/* ================= HELPERS ================= */
function kp_h($s) { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }

// "red, blue | green" => ['red','blue','green']
function kp_list($s) {
    $parts = preg_split('/[,\n|]+/', (string)$s);
    return array_values(array_filter(array_map('trim', $parts), 'strlen'));
}

// Admin editor ka HTML ho to safe tags allow, plain text ho to nl2br
function kp_rich($s) {
    $s = trim((string)$s);
    if ($s === '') return '';
    if (strip_tags($s) === $s) return nl2br(kp_h($s));
    return strip_tags($s, '<p><br><ul><ol><li><strong><b><em><i><h3><h4><span><a>');
}

// YouTube / Vimeo link => iframe, direct file => <video>
function kp_video($v, $videoPath) {
    $v = trim((string)$v);
    if ($v === '') return '';
    if (preg_match('~(?:youtu\.be/|youtube\.com/(?:watch\?v=|embed/|shorts/))([\w-]{11})~', $v, $m)) {
        return '<iframe src="https://www.youtube.com/embed/' . $m[1] . '" allowfullscreen loading="lazy"></iframe>';
    }
    if (preg_match('~vimeo\.com/(\d+)~', $v, $m)) {
        return '<iframe src="https://player.vimeo.com/video/' . $m[1] . '" allowfullscreen loading="lazy"></iframe>';
    }
    $src = preg_match('~^https?://~i', $v) ? $v : $videoPath . $v;
    return '<video controls preload="metadata" src="' . htmlspecialchars($src, ENT_QUOTES, 'UTF-8') . '"></video>';
}

function kp_money($n) { return '₹' . number_format((float)$n, 0); }

/* ================= PRODUCT FETCH ================= */
$slug = trim($_GET['slug'] ?? '');
$p = null;

if ($slug !== '') {
    $sql = "SELECT p.product_id, p.product_name, p.product_slug, p.product_color, p.product_size,
                   p.product_image, p.product_description, p.product_other_info_desc,
                   p.original_price, p.sale_price, p.discount_visibility,
                   b.brand_name, b.brand_slug
            FROM products p
            LEFT JOIN brands b ON b.brand_id = p.brand_id
            WHERE p.product_slug = ? AND p.product_status = 'Active'
            LIMIT 1";
    $stmt = mysqli_prepare($mydb, $sql);
    mysqli_stmt_bind_param($stmt, "s", $slug);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $p = mysqli_fetch_assoc($res) ?: null;
}

if (!$p) {
    http_response_code(404);
}

// DB me poora path save hai (jaise assets/products/product_3/main.jpeg), isliye prefix khali hai
$productImgPath = "";
$mediaImgPath   = "";
$brochurePath   = "";
$videoPath      = "";
$mediaTable     = "";   // khali chhodo = auto-detect (jis table me product_img_1 column ho)

/* ================= DERIVED VALUES (sirf jo data hai) ================= */
if ($p) {
    $orig = (float)$p['original_price'];
    $sale = (float)$p['sale_price'];

    $currentPrice  = $sale > 0 ? $sale : ($orig > 0 ? $orig : 0);
    $strikePrice   = ($sale > 0 && $orig > $sale) ? $orig : 0;
    $discountPct   = ($strikePrice > 0 && $p['discount_visibility'] === 'Show')
                        ? round((($strikePrice - $currentPrice) / $strikePrice) * 100) : 0;

    $colors   = kp_list($p['product_color']);
    $sizes    = kp_list($p['product_size']);
    $descHtml = kp_rich($p['product_description']);
    $otherHtml = kp_rich($p['product_other_info_desc']);
    $img      = trim((string)$p['product_image']);
    $brand    = trim((string)$p['brand_name']);

    /* ---- Extra images / brochure / video (alag table se) ---- */
    $m = [];
    try {
        // Table ka naam auto-detect: jis table me product_img_1 column ho
        if ($mediaTable === "") {
            $dr = mysqli_query($mydb, "SELECT TABLE_NAME FROM information_schema.COLUMNS
                                       WHERE TABLE_SCHEMA = DATABASE() AND COLUMN_NAME = 'product_img_1'
                                       LIMIT 1");
            if ($dr && ($drow = mysqli_fetch_assoc($dr))) {
                $mediaTable = $drow['TABLE_NAME'];
            }
        }
        if ($mediaTable !== "") {
            $ms = mysqli_prepare($mydb, "SELECT * FROM `" . str_replace('`', '', $mediaTable) . "` WHERE product_id = ? LIMIT 1");
            if ($ms) {
                $pid = (int)$p['product_id'];
                mysqli_stmt_bind_param($ms, "i", $pid);
                mysqli_stmt_execute($ms);
                $mr = mysqli_stmt_get_result($ms);
                $m = mysqli_fetch_assoc($mr) ?: [];
            }
        }
    } catch (Throwable $e) {
        $m = [];
    }

    // Gallery: pehle main image, phir img_1..img_10 (duplicate skip)
    $gallery = [];
    $seen = [];
    if ($img !== '') {
        $gallery[] = ['src' => $productImgPath . $img, 'alt' => $p['product_name']];
        $seen[$img] = true;
    }
    for ($i = 1; $i <= 10; $i++) {
        $f = trim((string)($m["product_img_$i"] ?? ''));
        if ($f === '' || isset($seen[$f])) continue;
        $seen[$f] = true;
        $alt = trim((string)($m["product_img_{$i}_alt"] ?? ''));
        $gallery[] = ['src' => $mediaImgPath . $f, 'alt' => $alt !== '' ? $alt : $p['product_name']];
    }

    $brochure  = trim((string)($m['product_brochure'] ?? ''));
    $videoHtml = kp_video($m['product_video_1'] ?? '', $videoPath);
}
?>
<?php include_once 'includes/header.php' ?>

<style>
    main { overflow: hidden; }
</style>

<main>

<?php if (!$p) { ?>

    <section class="kalora-prod-section">
        <div class="kalora-prod-container" style="text-align:center;padding:80px 0">
            <h2 style="font-family:serif">Product nahi mila</h2>
            <p>Ye product available nahi hai ya hata diya gaya hai.</p>
            <a href="index.php" style="color:#aa820a;font-weight:600">Home par jao</a>
        </div>
    </section>

<?php } else { ?>

<section class="kalora-prod-section">
    <div class="kalora-prod-container">

        <div class="kalora-prod-main-grid">

            <!-- Left: Images -->
            <div class="kalora-prod-gallery">
                <div class="kalora-prod-main-image-wrap" id="kaloraZoomContainer">
                    <?php if ($gallery) { ?>
                        <img id="kaloraMainImg" src="<?= kp_h($gallery[0]['src']) ?>" alt="<?= kp_h($gallery[0]['alt']) ?>" class="kalora-prod-main-img">
                        <div class="kalora-prod-zoom-hint"><i class="fas fa-search-plus"></i> Hover to Zoom</div>
                    <?php } else { ?>
                        <div class="kalora-prod-noimg">Image available nahi hai</div>
                    <?php } ?>
                </div>

                <?php if (count($gallery) > 1) { ?>
                <div class="kalora-prod-thumbs">
                    <?php foreach ($gallery as $i => $g) { ?>
                        <div class="kalora-prod-thumb <?= $i === 0 ? 'active' : '' ?>"
                             onclick="changeKaloraImage(this, '<?= kp_h(addslashes($g['src'])) ?>', '<?= kp_h(addslashes($g['alt'])) ?>')">
                            <img src="<?= kp_h($g['src']) ?>" alt="<?= kp_h($g['alt']) ?>">
                        </div>
                    <?php } ?>
                </div>
                <?php } ?>

                <?php if ($videoHtml !== '') { ?>
                    <div class="kalora-prod-video"><?= $videoHtml ?></div>
                <?php } ?>
            </div>

            <!-- Right: Details -->
            <div class="kalora-prod-details-info">

                <?php if ($brand !== '') { ?>
                    <div class="kalora-prod-brand-tag">
                        <?php if (!empty($p['brand_slug'])) { ?>
                            <a href="brand.php?slug=<?= urlencode($p['brand_slug']) ?>" style="color:inherit;text-decoration:none"><?= kp_h($brand) ?></a>
                        <?php } else { echo kp_h($brand); } ?>
                    </div>
                <?php } ?>

                <h1 class="kalora-prod-title"><?= kp_h($p['product_name']) ?></h1>

                <?php if ($currentPrice > 0) { ?>
                    <div class="kalora-prod-price-box">
                        <span class="kalora-prod-current-price"><?= kp_money($currentPrice) ?></span>
                        <?php if ($strikePrice > 0) { ?>
                            <span class="kalora-prod-original-price"><?= kp_money($strikePrice) ?></span>
                        <?php } ?>
                        <?php if ($discountPct > 0) { ?>
                            <span class="kalora-prod-discount"><?= $discountPct ?>% OFF</span>
                        <?php } ?>
                    </div>
                    <p class="kalora-prod-tax-note">inclusive of all taxes</p>
                <?php } ?>

               

                <?php if ($colors || $sizes) { ?>
                    <div class="kalora-prod-divider"></div>
                <?php } ?>

                <?php if ($colors) { ?>
                    <div class="kalora-prod-option-group">
                        <label class="kalora-prod-option-label">Color</label>
                        <div class="kalora-chips">
                            <?php foreach ($colors as $c) { ?><span class="kalora-chip"><?= kp_h($c) ?></span><?php } ?>
                        </div>
                    </div>
                <?php } ?>

                <?php if ($sizes) { ?>
                    <div class="kalora-prod-option-group">
                        <label class="kalora-prod-option-label">Size</label>
                        <div class="kalora-chips">
                            <?php foreach ($sizes as $sz) { ?><span class="kalora-chip"><?= kp_h($sz) ?></span><?php } ?>
                        </div>
                    </div>
                <?php } ?>

                <div class="kalora-prod-divider"></div>

                <div class="kalora-prod-cta-row">
                    <button class="kalora-prod-btn-bag" type="button" onclick="alert('Added to KALORA shopping bag successfully!')">
                        <i class="fas fa-shopping-bag"></i> Add to Bag
                    </button>
                    <button style="display: none;" class="kalora-prod-btn-wishlist" type="button" onclick="this.classList.toggle('active')">
                        <i class="fas fa-heart"></i>
                    </button>
                </div>
                <br>
                 <!-- Trust Badges Bar -->
                <div class="kalora-prod-trust-bar">
                    <div class="kalora-trust-item"><i class="fas fa-shield-alt"><span>100% Anti-Tarnish</span></i></div>
                    <div class="kalora-trust-item"><i class="fas fa-water"><span>Water Resistant</span></i></div>
                    <div class="kalora-trust-item"><i class="fas fa-gift"><span>Gift Box Included</span></i></div>
                </div>

                <?php if ($brochure !== '') { ?>
                    <a class="kalora-prod-brochure" href="<?= kp_h($brochurePath . $brochure) ?>" target="_blank" download>
                        <i class="fas fa-file-download"></i> Download Brochure
                    </a>
                <?php } ?>

            </div>
        </div>

        <!-- Accordions (sirf jinki value hai) -->
        <?php if ($descHtml !== '' || $otherHtml !== '') { ?>
        <div class="kalora-prod-accordion-section">

            <?php if ($descHtml !== '') { ?>
            <div class="kalora-prod-accordion-item active">
                <div class="kalora-prod-accordion-header" onclick="toggleKaloraAccordion(this)">
                    <h3>Product Description</h3>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="kalora-prod-accordion-body"><?= $descHtml ?></div>
                <div class="kalora-prod-accordion-header" onclick="toggleKaloraAccordion(this)">
                    <h3>Other Information</h3>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div style="display: block;" class="kalora-prod-accordion-body"><?= $otherHtml ?></div>
            </div>
            <?php } ?>

            <?php if ($otherHtml !== '') { ?>
            <div class="kalora-prod-accordion-item">
                
            </div>
            <?php } ?>

        </div>
        <?php } ?>

    </div>
</section>

<?php } ?>

<style>
    .kalora-prod-section { background-color: #fbf2e3; padding: 60px 20px; font-family: 'Inter', sans-serif; color: #1f2937; isolation: isolate; }
    .kalora-prod-container { max-width: 1200px; margin: 0 auto; }
    .kalora-prod-main-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 40px; align-items: start; }
    @media (max-width: 900px) { .kalora-prod-main-grid { grid-template-columns: 1fr; } }

    .kalora-prod-gallery { display: flex; flex-direction: column; gap: 15px; }
    .kalora-prod-main-image-wrap { position: relative; background: #ffffff; border-radius: 20px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.06); cursor: crosshair; }
    .kalora-prod-main-img { width: 100%; height: 440px; object-fit: cover; display: block; transform-origin: center center; transition: transform 0.1s ease-out; }
    .kalora-prod-noimg { height: 440px; display: flex; align-items: center; justify-content: center; color: #9ca3af; font-size: 14px; }
    .kalora-prod-zoom-hint { position: absolute; bottom: 15px; right: 15px; background: rgba(0,0,0,0.7); backdrop-filter: blur(4px); color: #d4af37; font-size: 11px; padding: 5px 10px; border-radius: 8px; z-index: 3; pointer-events: none; }

    .kalora-prod-thumbs { display: flex; flex-wrap: wrap; gap: 12px; }
    .kalora-prod-thumb { width: 75px; height: 75px; border-radius: 12px; overflow: hidden; border: 2px solid transparent; cursor: pointer; transition: all 0.3s ease; background: #fff; box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
    .kalora-prod-thumb.active, .kalora-prod-thumb:hover { border-color: #d4af37; transform: translateY(-3px); }
    .kalora-prod-thumb img { width: 100%; height: 100%; object-fit: cover; }
    .kalora-prod-video { border-radius: 16px; overflow: hidden; background: #000; box-shadow: 0 10px 30px rgba(0,0,0,0.08); }
    .kalora-prod-video iframe, .kalora-prod-video video { width: 100%; aspect-ratio: 16/9; border: 0; display: block; }
    .kalora-prod-brochure { display: flex; align-items: center; justify-content: center; gap: 10px; margin-top: 15px; padding: 13px; border: 1px solid #d4af37; border-radius: 12px; color: #d4af37; text-decoration: none; font-weight: 600; font-size: 14px; transition: background 0.2s; }
    .kalora-prod-brochure:hover { background: rgba(212,175,55,0.12); }

    .kalora-prod-details-info { background: #071a12; border-radius: 20px; padding: 30px; box-shadow: 0 15px 35px rgba(0,0,0,0.1); color: #f3f4f6; }
    .kalora-prod-brand-tag { font-size: 11px; letter-spacing: 2.5px; color: #d4af37; font-weight: 700; text-transform: uppercase; margin-bottom: 8px; }
    .kalora-prod-title { font-size: clamp(22px, 3vw, 28px); font-weight: 700; color: #ffffff; margin: 0 0 16px 0; font-family: serif; }

    .kalora-prod-price-box { display: flex; align-items: baseline; flex-wrap: wrap; gap: 12px; margin-bottom: 4px; }
    .kalora-prod-current-price { font-size: 32px; font-weight: 900; color: #ffffff; }
    .kalora-prod-original-price { font-size: 18px; color: #9ca3af; text-decoration: line-through; }
    .kalora-prod-discount { font-size: 13px; color: #34d399; font-weight: 700; background: rgba(52,211,153,0.15); padding: 3px 8px; border-radius: 6px; }
    .kalora-prod-tax-note { font-size: 12px; color: #9ca3af; margin: 0 0 4px; }
    .kalora-prod-divider { height: 1px; background: rgba(255,255,255,0.1); margin: 20px 0; }

    .kalora-prod-option-group { margin-bottom: 18px; }
    .kalora-prod-option-label { display: block; font-size: 13px; font-weight: 600; color: #e5e7eb; margin-bottom: 10px; }
    .kalora-chips { display: flex; flex-wrap: wrap; gap: 8px; }
    .kalora-chip { border: 1px solid rgba(212,175,55,0.5); color: #f3f4f6; padding: 6px 14px; border-radius: 20px; font-size: 13px; }

    .kalora-prod-cta-row { display: flex; gap: 15px; }
    .kalora-prod-btn-bag { flex: 1; background: linear-gradient(135deg, #d4af37 0%, #aa820a 100%); color: #0f172a; font-weight: 700; border: none; padding: 15px; border-radius: 12px; cursor: pointer; font-size: 15px; display: flex; align-items: center; justify-content: center; gap: 10px; transition: filter 0.2s, transform 0.2s; box-shadow: 0 10px 20px rgba(212,175,55,0.2); }
    .kalora-prod-btn-bag:hover { filter: brightness(1.1); transform: translateY(-2px); }
    .kalora-prod-btn-wishlist { width: 50px; background: rgba(255,255,255,0.08); border: none; border-radius: 12px; color: #d1d5db; font-size: 18px; cursor: pointer; transition: all 0.2s; }
    .kalora-prod-btn-wishlist:hover, .kalora-prod-btn-wishlist.active { background: rgba(239,68,68,0.2); color: #ef4444; }

    .kalora-prod-accordion-section { margin-top: 30px; display: flex; flex-direction: column; gap: 15px; }
    .kalora-prod-accordion-item { background: #071a12; border-radius: 15px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.1); }
    .kalora-prod-accordion-header { padding: 18px 25px; display: flex; justify-content: space-between; align-items: center; cursor: pointer; }
    .kalora-prod-accordion-header h3 { margin: 0; font-size: 16px; color: #ffffff; font-weight: 600; }
    .kalora-prod-accordion-header i { color: #d4af37; transition: transform 0.3s ease; }
    .kalora-prod-accordion-item.active .kalora-prod-accordion-header i { transform: rotate(180deg); }
    .kalora-prod-accordion-body { display: none; padding: 0 25px 20px 25px; color: #9ca3af; font-size: 14px; line-height: 1.7; }
    .kalora-prod-accordion-item.active .kalora-prod-accordion-body { display: block; }
    .kalora-prod-accordion-body ul, .kalora-prod-accordion-body ol { padding-left: 20px; }
    .kalora-prod-trust-bar {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 10px;
        background: #05130d;
        padding: 12px;
        border-radius: 12px;
        text-align: center;
    }

    .kalora-trust-item {
        font-size: 11px;
        color: #d4af37;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 4px;
    }

    .kalora-trust-item span {
        color: #9ca3af;
    }

</style>

<script>
    function changeKaloraImage(thumb, src, alt) {
        const img = document.getElementById('kaloraMainImg');
        if (!img) return;
        resetKaloraZoom();
        img.src = src;
        img.alt = alt;
        document.querySelectorAll('.kalora-prod-thumb').forEach(function (el) { el.classList.remove('active'); });
        thumb.classList.add('active');
    }

    /* ---------- Zoom + Pan: PC par mouse se, phone par finger se ---------- */
    const kZoom = { scale: 2.2, on: false, tx: 0, ty: 0 };

    function kApply(animate) {
        const img = document.getElementById('kaloraMainImg');
        if (!img) return;
        img.style.transition = animate ? 'transform 0.2s ease-out' : 'none';
        img.style.transform = kZoom.on
            ? 'translate(' + kZoom.tx + 'px,' + kZoom.ty + 'px) scale(' + kZoom.scale + ')'
            : 'translate(0,0) scale(1)';
        const box = document.getElementById('kaloraZoomContainer');
        if (box) box.style.touchAction = kZoom.on ? 'none' : 'pan-y';
    }

    function kClamp(box) {
        const mx = (kZoom.scale - 1) * box.offsetWidth / 2;
        const my = (kZoom.scale - 1) * box.offsetHeight / 2;
        kZoom.tx = Math.max(-mx, Math.min(mx, kZoom.tx));
        kZoom.ty = Math.max(-my, Math.min(my, kZoom.ty));
    }

    function resetKaloraZoom() {
        kZoom.on = false; kZoom.tx = 0; kZoom.ty = 0;
        kApply(true);
    }

    (function () {
        const box = document.getElementById('kaloraZoomContainer');
        const img = document.getElementById('kaloraMainImg');
        if (!box || !img) return;

        box.style.touchAction = 'pan-y';
        img.draggable = false;

        const hint = box.querySelector('.kalora-prod-zoom-hint');
        if (hint && window.matchMedia('(hover: none)').matches) {
            hint.innerHTML = '<i class="fas fa-search-plus"></i> Tap to Zoom, drag to move';
        }

        let down = false, moved = false, lastX = 0, lastY = 0, startX = 0, startY = 0;

        box.addEventListener('pointermove', function (e) {
            const r = box.getBoundingClientRect();

            if (e.pointerType === 'mouse') {
                // PC: mouse jidhar le jao, image udhar move hogi
                const px = (e.clientX - r.left) / r.width - 0.5;
                const py = (e.clientY - r.top) / r.height - 0.5;
                kZoom.on = true;
                kZoom.tx = -px * 2 * (kZoom.scale - 1) * r.width / 2;
                kZoom.ty = -py * 2 * (kZoom.scale - 1) * r.height / 2;
                kApply(false);
                return;
            }

            // Phone: zoomed ho to finger se drag
            if (!down) return;
            const dx = e.clientX - lastX, dy = e.clientY - lastY;
            if (Math.abs(e.clientX - startX) > 6 || Math.abs(e.clientY - startY) > 6) moved = true;
            lastX = e.clientX; lastY = e.clientY;
            if (kZoom.on) {
                kZoom.tx += dx; kZoom.ty += dy;
                kClamp(box);
                kApply(false);
            }
        });

        box.addEventListener('pointerleave', function (e) {
            if (e.pointerType === 'mouse') resetKaloraZoom();
        });

        box.addEventListener('pointerdown', function (e) {
            if (e.pointerType === 'mouse') return;
            down = true; moved = false;
            lastX = startX = e.clientX; lastY = startY = e.clientY;
            try { box.setPointerCapture(e.pointerId); } catch (err) {}
        });

        box.addEventListener('pointerup', function (e) {
            if (e.pointerType === 'mouse') return;
            down = false;
            if (moved) return;                 // drag tha, tap nahi
            if (kZoom.on) { resetKaloraZoom(); return; }
            // Tap: jis jagah tap kiya wahi se zoom
            const r = box.getBoundingClientRect();
            const px = (e.clientX - r.left) / r.width - 0.5;
            const py = (e.clientY - r.top) / r.height - 0.5;
            kZoom.on = true;
            kZoom.tx = -px * kZoom.scale * r.width;
            kZoom.ty = -py * kZoom.scale * r.height;
            kClamp(box);
            kApply(true);
        });

        box.addEventListener('pointercancel', function () { down = false; });
    })();

    function toggleKaloraAccordion(headerElement) {
        headerElement.parentElement.classList.toggle('active');
    }
</script>

</main>

<?php include_once 'includes/footter.php' ?>