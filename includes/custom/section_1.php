<?php
/* =====================================================================
   section_1.php  -  Custom Box Builder
   Rules:
   1. Pehle sirf "Custom Box" brand ke products dikhte hain.
   2. Box ka product add hote hi box wale products hide, baaki products show
      (upar brand tabs ke saath: All Products / Bracelets / ...).
   3. Total max 6 products (box wala mila ke).
   4. Box wala product remove karo to cart ke baaki products rahenge,
      par naya product tabhi add hoga jab box dobara add ho.
   5. 6 products hone par Checkout -> WhatsApp.
   ===================================================================== */

// ---------- SETTINGS ----------
$WHATSAPP_NUMBER = preg_replace('/\D/', '', $global_info['globle_whatsapp'] ?? '');
$BOX_BRAND_SLUG  = 'custom-box';     // brands table ka brand_slug
$MAX_ITEMS       = 10;                // maximum products (box mila ke)
$MIN_ITEMS       = 2;                // checkout ke liye minimum products (box mila ke)
$BASE_URL        = '';               // agar images ka path root se nahi hai to yahan prefix daalein, e.g. '/kalora/'

// ---------- DB CONNECTION ----------
// header.php me bana hua $mydb (mysqli) use ho raha hai.

// ---------- BOX BRAND ID ----------
$boxBrandId = 0;
$stmt = $mydb->prepare("SELECT brand_id FROM brands WHERE brand_slug = ? AND brand_status = 'Active' LIMIT 1");
$stmt->bind_param('s', $BOX_BRAND_SLUG);
$stmt->execute();
$stmt->bind_result($boxBrandId);
$stmt->fetch();
$stmt->close();

// ---------- PRODUCTS ----------
$products = [];
$res = $mydb->query("SELECT product_id, brand_id, product_name, product_image, original_price, sale_price
                     FROM products
                     WHERE product_status = 'Active'
                     ORDER BY product_id DESC");
while ($row = $res->fetch_assoc()) {
    $price = ((float)$row['sale_price'] > 0) ? (float)$row['sale_price'] : (float)$row['original_price'];
    $row['price']    = $price;
    $row['is_brand'] = ((int)$row['brand_id'] === (int)$boxBrandId) ? 1 : 0;
    $products[] = $row;
}

// ---------- BRAND TABS ----------
// Saare Active brands (Custom Box chhod kar), chahe unme abhi product ho ya na ho
$tabs = [];
$r = $mydb->query("SELECT brand_id, brand_name FROM brands WHERE brand_status = 'Active' ORDER BY brand_id ASC");
while ($b = $r->fetch_assoc()) {
    if ((int)$b['brand_id'] !== (int)$boxBrandId) {
        $tabs[(int)$b['brand_id']] = $b['brand_name'];
    }
}

// ---------- PRICE BUTTONS ----------
$PRICE_STEPS = [99, 199, 299, 399, 499];
?>

<style>
    .mb-section { background: #fdf6ea; font-family: 'Times New Roman', Times, serif; color: #0f2d22; }
    .mb-wrap { max-width: 1200px; margin: 0 auto; padding: 56px 16px 96px; }
    .mb-head { display: flex; flex-wrap: wrap; align-items: end; justify-content: space-between; gap: 12px; margin-bottom: 28px; }
    .mb-head h2 { margin: 0; font-size: clamp(1.6rem, 3vw, 2.4rem); line-height: 1.15; }
    .mb-hint { margin: 6px 0 0; color: #4a5a52; font-size: 1.05rem; }
    .mb-progress { font-weight: 600; font-size: 1rem; padding: 8px 16px; border: 1.5px solid #0f2d22; border-radius: 999px; white-space: nowrap; }

    /* filters: brand tabs + price buttons */
    .mb-filters { margin-bottom: 22px; display: flex; flex-direction: column; gap: 10px; }
    .mb-filters[hidden] { display: none; }
    .mb-row { display: flex; gap: 10px; align-items: center; overflow-x: auto; padding: 4px 2px; }
    .mb-row-label { flex: 0 0 auto; font-weight: 600; margin-right: 4px; }
    .mb-tab, .mb-chip { flex: 0 0 auto; border: 1.5px solid #0f2d22; background: transparent; color: #0f2d22; font-family: inherit; font-size: 1rem;
              padding: 8px 18px; border-radius: 999px; cursor: pointer; transition: background .2s, color .2s; white-space: nowrap; }
    .mb-chip { padding: 6px 16px; font-size: .95rem; border-style: dashed; }
    .mb-tab:hover, .mb-chip:hover { background: rgba(15,45,34,.08); }
    .mb-tab.active, .mb-chip.active { background: #0f2d22; color: #e6c97a; border-style: solid; }
    .mb-tab:focus-visible, .mb-chip:focus-visible { outline: 3px solid #2563eb; outline-offset: 2px; }

    .mb-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap: 30px; }
    .mb-card { border-radius: 12px; padding: 19px 19px 20px; background: linear-gradient(180deg, #0f2d22 0%, #0a1f17 100%); display: flex; flex-direction: column; box-shadow: 0 6px 18px rgba(15,45,34,.18); transition: box-shadow .2s; }
    .mb-card:hover { box-shadow: 0 10px 26px rgba(15,45,34,.3); }
    .mb-card[hidden] { display: none; }
    .mb-card img { width: 100%; aspect-ratio: 1 / 1.15; object-fit: cover; background: #e9e9e9; display: block; border-radius: 10px; }
    .mb-card-body { padding: 16px 4px 0; display: flex; flex-direction: column; gap: 8px; flex: 1; text-align: center; }
    .mb-name { margin: 0; font-size: 1.02rem; font-weight: 400; color: #fff; line-height: 1.35; }
    .mb-price { margin: 0; color: #e6c97a; }
    .mb-btn { margin-top: auto; border: 0; border-radius: 8px; padding: 10px 14px; font-family: inherit; font-weight: 600; cursor: pointer; background: #e6c97a; color: #0f2d22; font-size: 1rem; }
    .mb-btn:hover:not(:disabled) { background: #f1d98f; }
    .mb-btn:disabled { background: rgba(255,255,255,.18); color: rgba(255,255,255,.65); cursor: not-allowed; }
    .mb-btn:focus-visible, .mb-fab:focus-visible, .mb-remove:focus-visible, .mb-close:focus-visible { outline: 3px solid #2563eb; outline-offset: 2px; }
    .mb-empty { grid-column: 1 / -1; padding: 40px 0; text-align: center; color: #4a5a52; }

    /* floating cart button */
    .mb-fab { position: fixed; top: 40%; transform: translateY(-50%); z-index: 1040; border: 0; cursor: pointer;
              background: #0f2d22; color: #fff; font-family: 'Times New Roman', Times, serif; padding: 14px 16px; border-radius: 12px 0 0 12px; right: 0; font-weight: 600; box-shadow: 0 4px 16px rgba(0,0,0,.25); }
    .mb-fab span { display: inline-block; min-width: 22px; padding: 1px 6px; margin-left: 6px; background: #e6c97a; color: #0f2d22; border-radius: 999px; font-size: .85rem; text-align: center; }

    /* side cart */
    .mb-overlay { position: fixed; inset: 0; background: rgba(0,0,0,.45); z-index: 1050; opacity: 0; visibility: hidden; transition: opacity .2s; }
    .mb-overlay.open { opacity: 1; visibility: visible; }
    .mb-drawer { position: fixed; top: 0; right: 0; height: 100%; width: min(400px, 92vw); background: #fdf6ea; font-family: 'Times New Roman', Times, serif; color: #0f2d22; z-index: 1060; display: flex; flex-direction: column;
                 transform: translateX(100%); transition: transform .25s ease; box-shadow: -6px 0 24px rgba(0,0,0,.2); }
    .mb-drawer.open { transform: translateX(0); }
    .mb-drawer-head { display: flex; justify-content: space-between; align-items: center; padding: 16px 18px; border-bottom: 1px solid #eee; }
    .mb-drawer-head h3 { margin: 0; font-size: 1.15rem; }
    .mb-close { background: none; border: 0; font-size: 1.6rem; line-height: 1; cursor: pointer; }
    .mb-items { flex: 1; overflow-y: auto; padding: 8px 18px; margin: 0; list-style: none; }
    .mb-item { display: flex; gap: 12px; align-items: center; padding: 12px 0; border-bottom: 1px solid #f0f0f0; }
    .mb-item img { width: 56px; height: 56px; border-radius: 8px; object-fit: cover; background: #f3f3f3; }
    .mb-item-info { flex: 1; min-width: 0; }
    .mb-item-info strong { display: block; font-size: .95rem; }
    .mb-item-info small { color: #666; }
    .mb-tag { display: inline-block; font-size: .72rem; background: #fff3cd; color: #7a5b00; padding: 1px 7px; border-radius: 999px; margin-left: 6px; }
    .mb-remove { background: none; border: 1px solid #d33; color: #d33; border-radius: 6px; padding: 5px 10px; cursor: pointer; font-size: .85rem; }
    .mb-remove:hover { background: #d33; color: #fff; }
    .mb-drawer-foot { padding: 16px 18px; border-top: 1px solid #eee; display: flex; flex-direction: column; gap: 10px; }
    .mb-total { display: flex; justify-content: space-between; font-weight: 700; font-size: 1.05rem; }
    .mb-note { margin: 0; font-size: .88rem; color: #666; }
    .mb-checkout { display: none; background: #0f2d22; color: #fff; }
    .mb-checkout:hover:not(:disabled) { background: #0a1f17; }
    .mb-checkout.show { display: block; }
    .mb-none { padding: 30px 0; text-align: center; color: #777; }

    @media (prefers-reduced-motion: reduce) { .mb-drawer, .mb-overlay { transition: none; } }
</style>

<div class="mb-section">
<section class="mb-wrap" id="mbSection">
    <div class="mb-head">
        <div>
            <h2 id="mbTitle">Choose your box</h2>
            <p class="mb-hint" id="mbHint"></p>
        </div>
        <div class="mb-progress" id="mbProgress">0 / <?= (int)$MAX_ITEMS ?> selected</div>
    </div>

    <!-- Filters (box select hone ke baad dikhte hain) -->
    <div class="mb-filters" id="mbTabs" hidden>
        <div class="mb-row" role="group" aria-label="Filter by category">
            <button type="button" class="mb-tab active" data-filter="all" aria-pressed="true">All Products</button>
            <?php foreach ($tabs as $tid => $tname): ?>
                <button type="button" class="mb-tab" data-filter="<?= (int)$tid ?>" aria-pressed="false"><?= htmlspecialchars($tname) ?></button>
            <?php endforeach; ?>
        </div>
        <div class="mb-row" role="group" aria-label="Filter by price">
            <span class="mb-row-label">Budget</span>
            <button type="button" class="mb-chip active" data-price="all" aria-pressed="true">Any price</button>
            <?php foreach ($PRICE_STEPS as $step): ?>
                <button type="button" class="mb-chip" data-price="<?= (int)$step ?>" aria-pressed="false">₹<?= (int)$step ?></button>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="mb-grid" id="mbGrid">
        <?php foreach ($products as $p):
            $img = $p['product_image'] ? $BASE_URL . ltrim($p['product_image'], '/') : '';
        ?>
            <article class="mb-card"
                     data-id="<?= (int)$p['product_id'] ?>"
                     data-brand="<?= (int)$p['is_brand'] ?>"
                     data-brandid="<?= (int)$p['brand_id'] ?>"
                     data-name="<?= htmlspecialchars($p['product_name'], ENT_QUOTES) ?>"
                     data-price="<?= htmlspecialchars((string)$p['price'], ENT_QUOTES) ?>"
                     data-image="<?= htmlspecialchars($img, ENT_QUOTES) ?>">
                <?php if ($img): ?>
                    <img src="<?= htmlspecialchars($img, ENT_QUOTES) ?>" alt="<?= htmlspecialchars($p['product_name'], ENT_QUOTES) ?>" loading="lazy">
                <?php endif; ?>
                <div class="mb-card-body">
                    <h3 class="mb-name"><?= htmlspecialchars($p['product_name']) ?></h3>
                    <p class="mb-price">₹<?= number_format($p['price'], 2) ?></p>
                    <button type="button" class="mb-btn mb-add">Add to cart</button>
                </div>
            </article>
        <?php endforeach; ?>
        <p class="mb-empty" id="mbEmpty" hidden>No products available right now.</p>
    </div>
</section>
</div>

<!-- Floating cart button -->
<button type="button" class="mb-fab" id="mbFab" aria-label="Open cart">Cart <span id="mbFabCount">0</span></button>

<!-- Side cart -->
<div class="mb-overlay" id="mbOverlay"></div>
<aside class="mb-drawer" id="mbDrawer" aria-label="Your cart" aria-hidden="true">
    <div class="mb-drawer-head">
        <h3>Your cart</h3>
        <button type="button" class="mb-close" id="mbClose" aria-label="Close cart">&times;</button>
    </div>
    <ul class="mb-items" id="mbItems"></ul>
    <div class="mb-drawer-foot">
        <div class="mb-total"><span>Total</span><span id="mbTotal">₹0.00</span></div>
        <p class="mb-note" id="mbNote"></p>
        <button type="button" class="mb-btn mb-checkout" id="mbCheckout">Checkout on WhatsApp</button>
    </div>
</aside>

<script>
(function () {
    var MAX = <?= (int)$MAX_ITEMS ?>;
    var MIN = <?= (int)$MIN_ITEMS ?>;
    var WA_NUMBER = <?= json_encode($WHATSAPP_NUMBER) ?>;
    var KEY = 'mb_cart_v1';

    var cards     = Array.prototype.slice.call(document.querySelectorAll('#mbGrid .mb-card'));
    var elHint    = document.getElementById('mbHint');
    var elTitle   = document.getElementById('mbTitle');
    var elProg    = document.getElementById('mbProgress');
    var elEmpty   = document.getElementById('mbEmpty');
    var elFab     = document.getElementById('mbFab');
    var elFabCnt  = document.getElementById('mbFabCount');
    var elItems   = document.getElementById('mbItems');
    var elTotal   = document.getElementById('mbTotal');
    var elNote    = document.getElementById('mbNote');
    var elCheck   = document.getElementById('mbCheckout');
    var elDrawer  = document.getElementById('mbDrawer');
    var elOverlay = document.getElementById('mbOverlay');

    // ---- filters: brand tabs + price buttons ----
    var PRICE_STEPS = <?= json_encode($PRICE_STEPS) ?>;
    var elTabs   = document.getElementById('mbTabs');
    var tabBtns  = Array.prototype.slice.call(document.querySelectorAll('#mbTabs .mb-tab'));
    var chipBtns = Array.prototype.slice.call(document.querySelectorAll('#mbTabs .mb-chip'));
    var filter = 'all';        // brand
    var priceFilter = 'all';   // price band
    tabBtns.forEach(function (t) {
        t.addEventListener('click', function () { filter = t.dataset.filter; render(); });
    });
    chipBtns.forEach(function (c) {
        c.addEventListener('click', function () { priceFilter = c.dataset.price; render(); });
    });

    // price kis band me aati hai: <=99 -> 99, 100-199 -> 199, 200-299 -> 299 ...
    function bandOf(price) {
        for (var k = 0; k < PRICE_STEPS.length; k++) {
            if (price <= PRICE_STEPS[k]) return String(PRICE_STEPS[k]);
        }
        return 'above';
    }

    // ---- cart storage (temporary, browser me save) ----
    function load() {
        try {
            var data = JSON.parse(localStorage.getItem(KEY) || '[]');
            return Array.isArray(data) ? data : [];
        } catch (e) { return []; }
    }
    function save() {
        try { localStorage.setItem(KEY, JSON.stringify(cart)); } catch (e) {}
        window.dispatchEvent(new Event('mb-cart-change'));   // footer ke button ka count update
    }
    var cart = load();

    // purane/inactive products cart se hata do
    var validIds = cards.map(function (c) { return c.dataset.id; });
    cart = cart.filter(function (i) { return validIds.indexOf(String(i.id)) !== -1; });

    function inCart(id) { return cart.some(function (i) { return String(i.id) === String(id); }); }
    function hasBrand() { return cart.some(function (i) { return i.brand; }); }
    function money(n) { return '₹' + Number(n).toFixed(2); }

    // ---- actions ----
    function add(card) {
        var id = card.dataset.id;
        var isBrand = card.dataset.brand === '1';
        if (cart.length >= MAX || inCart(id)) return;
        if (!hasBrand() && !isBrand) return;   // pehle box ka product zaruri
        if (hasBrand() && isBrand) return;     // box ka sirf ek product
        cart.push({
            id: id,
            name: card.dataset.name,
            price: parseFloat(card.dataset.price) || 0,
            image: card.dataset.image,
            brand: isBrand
        });
        save(); render();
    }
    function remove(id) {
        cart = cart.filter(function (i) { return String(i.id) !== String(id); });
        save(); render();
    }

    // ---- render ----
    function render() {
        var brandIn = hasBrand();
        var full = cart.length >= MAX;
        if (!brandIn) { filter = 'all'; priceFilter = 'all'; }   // box hat gaya to filters reset

        // product cards visibility
        var visible = 0;
        cards.forEach(function (card) {
            var isBrand = card.dataset.brand === '1';
            var show = brandIn
                ? (!isBrand
                    && (filter === 'all' || card.dataset.brandid === filter)
                    && (priceFilter === 'all' || bandOf(parseFloat(card.dataset.price) || 0) === priceFilter))
                : isBrand;
            card.hidden = !show;
            var btn = card.querySelector('.mb-add');
            var added = inCart(card.dataset.id);
            btn.disabled = added || full;
            btn.textContent = added ? 'Added' : 'Add to cart';
            if (show) visible++;
        });
        elEmpty.hidden = visible > 0;
        elEmpty.textContent = brandIn ? 'No products found for this selection.' : 'No products available right now.';

        // tabs
        elTabs.hidden = !brandIn;
        tabBtns.forEach(function (t) {
            var on = t.dataset.filter === filter;
            t.classList.toggle('active', on);
            t.setAttribute('aria-pressed', on ? 'true' : 'false');
        });
        chipBtns.forEach(function (c) {
            var on = c.dataset.price === priceFilter;
            c.classList.toggle('active', on);
            c.setAttribute('aria-pressed', on ? 'true' : 'false');
        });

        // texts
        elProg.textContent = cart.length + ' / ' + MAX + ' selected';
        if (full) {
            elTitle.textContent = 'Your box is ready';
            elHint.textContent = 'You have picked all ' + MAX + ' products. Open the cart and checkout.';
        } else if (!brandIn) {
            elTitle.textContent = 'Choose your box';
            elHint.textContent = cart.length
                ? 'Pick a box again to continue adding products.'
                : 'Start by adding one box. Then you can add other products.';
        } else {
            elTitle.textContent = 'Add your products';
            elHint.textContent = 'You can add ' + (MAX - cart.length) + ' more product' + (MAX - cart.length === 1 ? '' : 's') + '.';
        }

        // drawer list
        elItems.innerHTML = '';
        if (!cart.length) {
            var li = document.createElement('li');
            li.className = 'mb-none';
            li.textContent = 'Your cart is empty.';
            elItems.appendChild(li);
        }
        var total = 0;
        cart.forEach(function (i) {
            total += i.price;
            var li = document.createElement('li');
            li.className = 'mb-item';

            if (i.image) {
                var img = document.createElement('img');
                img.src = i.image; img.alt = '';
                li.appendChild(img);
            }
            var info = document.createElement('div');
            info.className = 'mb-item-info';
            var strong = document.createElement('strong');
            strong.textContent = i.name;
            if (i.brand) {
                var tag = document.createElement('span');
                tag.className = 'mb-tag'; tag.textContent = 'Box';
                strong.appendChild(tag);
            }
            var small = document.createElement('small');
            small.textContent = money(i.price);
            info.appendChild(strong); info.appendChild(small);

            var rm = document.createElement('button');
            rm.type = 'button'; rm.className = 'mb-remove'; rm.textContent = 'Remove';
            rm.addEventListener('click', function () { remove(i.id); });

            li.appendChild(info); li.appendChild(rm);
            elItems.appendChild(li);
        });
        elTotal.textContent = money(total);
        elFabCnt.textContent = cart.length;

        // note + checkout
        var canCheckout = brandIn && cart.length >= MIN;
        if (full) {
            elNote.textContent = 'All ' + MAX + ' products selected.';
        } else if (!brandIn && cart.length) {
            elNote.textContent = 'Box removed. Add a box again to select more products.';
        } else if (canCheckout) {
            elNote.textContent = 'You can checkout now, or add up to ' + (MAX - cart.length) + ' more.';
        } else {
            elNote.textContent = 'Add at least ' + MIN + ' products (including the box) to checkout.';
        }
        elCheck.classList.toggle('show', canCheckout);
    }

    // ---- drawer ----
    function openCart() {
        elDrawer.classList.add('open'); elOverlay.classList.add('open');
        elDrawer.setAttribute('aria-hidden', 'false');
    }
    function closeCart() {
        elDrawer.classList.remove('open'); elOverlay.classList.remove('open');
        elDrawer.setAttribute('aria-hidden', 'true');
    }
    elFab.addEventListener('click', openCart);
    elOverlay.addEventListener('click', closeCart);
    document.getElementById('mbClose').addEventListener('click', closeCart);
    document.addEventListener('keydown', function (e) { if (e.key === 'Escape') closeCart(); });

    cards.forEach(function (card) {
        card.querySelector('.mb-add').addEventListener('click', function () { add(card); });
    });

    // ---- WhatsApp checkout ----
    elCheck.addEventListener('click', function () {
        if (cart.length < MIN || !hasBrand()) return;

        // number set nahi hai to cart clear mat karo
        if (!WA_NUMBER) {
            alert('WhatsApp number is not set yet. Please contact the store.');
            return;
        }

        var total = 0;
        var lines = cart.map(function (i, idx) {
            total += i.price;
            return (idx + 1) + '. ' + i.name + (i.brand ? ' (Box)' : '') + ' - ' + money(i.price);
        });
        var msg = 'Hello, I want to order this custom box:\n\n' + lines.join('\n') + '\n\nTotal: ' + money(total);
        window.open('https://wa.me/' + WA_NUMBER + '?text=' + encodeURIComponent(msg), '_blank');

        // checkout ke baad cart clear
        cart = [];
        save();
        closeCart();
        render();
    });

    render();
})();
</script>