<?php include_once 'includes/header.php' ?>

<style>
    main {
        overflow: hidden;
    }
</style>


<!--
=======================================================================================================================
                                                        Main
=======================================================================================================================
 -->
<main>


<!-- KALORA Luxury Product Details Section (Clean Seamless BG) -->
<section class="kalora-prod-section">
    <div class="kalora-prod-container">
        
        <!-- Top Main Product Grid -->
        <div class="kalora-prod-main-grid">
            
            <!-- Left: Image Gallery & Advanced Zoom Preview -->
            <div class="kalora-prod-gallery">
                <div class="kalora-prod-main-image-wrap" id="kaloraZoomContainer" onmousemove="zoomKaloraImage(event)" onmouseleave="resetKaloraZoom()">
                    <span class="kalora-prod-badge">Best Seller</span>
                    <img id="kaloraMainImg" src="https://images.unsplash.com/photo-1535632066927-ab7c9ab60908?auto=format&fit=crop&w=1000&q=80" alt="KALORA Luxury Jewellery" class="kalora-prod-main-img">
                    <div class="kalora-prod-zoom-hint"><i class="fas fa-search-plus"></i> Hover to Zoom</div>
                </div>
                
                <!-- Thumbnail List -->
                <div class="kalora-prod-thumbs">
                    <div class="kalora-prod-thumb active" onclick="changeKaloraImage(this, 'https://images.unsplash.com/photo-1535632066927-ab7c9ab60908?auto=format&fit=crop&w=1000&q=80')">
                        <img src="https://images.unsplash.com/photo-1535632066927-ab7c9ab60908?auto=format&fit=crop&w=150&q=80" alt="Thumb 1">
                    </div>
                    <div class="kalora-prod-thumb" onclick="changeKaloraImage(this, 'https://images.unsplash.com/photo-1599643478518-a784e5dc4c8f?auto=format&fit=crop&w=1000&q=80')">
                        <img src="https://images.unsplash.com/photo-1599643478518-a784e5dc4c8f?auto=format&fit=crop&w=150&q=80" alt="Thumb 2">
                    </div>
                    <div class="kalora-prod-thumb" onclick="changeKaloraImage(this, 'https://images.unsplash.com/photo-1611591472151-cb84d12c6eb3?auto=format&fit=crop&w=1000&q=80')">
                        <img src="https://images.unsplash.com/photo-1611591472151-cb84d12c6eb3?auto=format&fit=crop&w=150&q=80" alt="Thumb 3">
                    </div>
                </div>
            </div>

            <!-- Right: Product Info & Actions (Solid Clean Green) -->
            <div class="kalora-prod-details-info">
                <div class="kalora-prod-brand-tag">KALORA LUXURY COLLECTION</div>
                <h1 class="kalora-prod-title">Royal Emerald & Zirconia Drop Earrings</h1>
                
                <!-- Rating -->
                <div class="kalora-prod-rating-row">
                    <div class="kalora-stars">★★★★★</div>
                    <span class="kalora-rating-text">4.9 (142 Verified Reviews)</span>
                </div>

                <!-- Price Block -->
                <div class="kalora-prod-price-box">
                    <span class="kalora-prod-current-price">₹499</span>
                    <span class="kalora-prod-original-price">₹1,499</span>
                    <span class="kalora-prod-discount">67% OFF</span>
                </div>
                <p class="kalora-prod-tax-note">inclusive of all taxes</p>

                <div class="kalora-prod-divider"></div>

                <!-- Variant Selector: Finish -->
                <div class="kalora-prod-option-group">
                    <label class="kalora-prod-option-label">Select Finish: <span id="selectedFinish">Antique Black Rhodium & Gold</span></label>
                    <div class="kalora-prod-finish-swatches">
                        <button class="kalora-prod-swatch active" style="background: linear-gradient(135deg, #1f2937, #d4af37);" onclick="selectFinish(this, 'Antique Black Rhodium & Gold')"></button>
                        <button class="kalora-prod-swatch" style="background: linear-gradient(135deg, #f3e5ab, #d4af37);" onclick="selectFinish(this, '18K Glossy Gold')"></button>
                        <button class="kalora-prod-swatch" style="background: linear-gradient(135deg, #e5e7eb, #9ca3af);" onclick="selectFinish(this, 'Platinum Silver')"></button>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="kalora-prod-cta-row">
                    <button class="kalora-prod-btn-bag" onclick="alert('Added to KALORA shopping bag successfully!')">
                        <i class="fas fa-shopping-bag"></i> Add to Bag
                    </button>
                    <button class="kalora-prod-btn-wishlist" onclick="this.classList.toggle('active')">
                        <i class="fas fa-heart"></i>
                    </button>
                </div>

                <!-- Trust Badges Bar -->
                <div class="kalora-prod-trust-bar">
                    <div class="kalora-trust-item"><i class="fas fa-shield-alt"><span>100% Anti-Tarnish</span></i></div>
                    <div class="kalora-trust-item"><i class="fas fa-water"><span>Water Resistant</span></i></div>
                    <div class="kalora-trust-item"><i class="fas fa-gift"><span>Gift Box Included</span></i></div>
                </div>

            </div>
        </div>

        <!-- Accordion Specifications & Details -->
        <div class="kalora-prod-accordion-section">
            <div class="kalora-prod-accordion-item active">
                <div class="kalora-prod-accordion-header" onclick="toggleKaloraAccordion(this)">
                    <h3>Product Specifications</h3>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="kalora-prod-accordion-body">
                    <ul class="kalora-specs-list">
                        <li><strong>Brand:</strong> KALORA</li>
                        <li><strong>Plating:</strong> Black Rhodium with 18K Gold Highlights</li>
                        <li><strong>Stone:</strong> Simulated Blue Sapphire & Austrian Crystals</li>
                        <li><strong>Lock Type:</strong> Secure Push Back</li>
                    </ul>
                </div>
            </div>
        </div>

    </div>
</section>

<!-- Scoped CSS with #fbf2e3 Background and Solid Green Containers -->
<style>
    .kalora-prod-section {
        background-color: #fbf2e3;
        padding: 60px 20px;
        font-family: 'Inter', sans-serif;
        color: #1f2937;
        isolation: isolate;
    }

    .kalora-prod-container {
        max-width: 1200px;
        margin: 0 auto;
    }

    .kalora-prod-main-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 40px;
        align-items: start;
    }

    @media (max-width: 900px) {
        .kalora-prod-main-grid {
            grid-template-columns: 1fr;
        }
    }

    /* Gallery & Advanced Zoom Styles */
    .kalora-prod-gallery {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .kalora-prod-main-image-wrap {
        position: relative;
        background: #ffffff;
        border-radius: 20px;
        overflow: hidden;
        border: none;
        box-shadow: 0 10px 30px rgba(0,0,0,0.06);
        cursor: crosshair;
    }

    .kalora-prod-badge {
        position: absolute;
        top: 15px;
        left: 15px;
        background: #d4af37;
        color: #ffffff;
        font-size: 11px;
        font-weight: 700;
        padding: 5px 12px;
        border-radius: 20px;
        letter-spacing: 1px;
        z-index: 3;
        pointer-events: none;
    }

    .kalora-prod-main-img {
        width: 100%;
        height: 440px;
        object-fit: cover;
        display: block;
        transform-origin: center center;
        transition: transform 0.1s ease-out;
    }

    .kalora-prod-zoom-hint {
        position: absolute;
        bottom: 15px;
        right: 15px;
        background: rgba(0, 0, 0, 0.7);
        backdrop-filter: blur(4px);
        color: #d4af37;
        font-size: 11px;
        padding: 5px 10px;
        border-radius: 8px;
        z-index: 3;
        pointer-events: none;
    }

    .kalora-prod-thumbs {
        display: flex;
        gap: 12px;
    }

    .kalora-prod-thumb {
        width: 75px;
        height: 75px;
        border-radius: 12px;
        overflow: hidden;
        border: 2px solid transparent;
        cursor: pointer;
        transition: all 0.3s ease;
        background: #ffffff;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }

    .kalora-prod-thumb.active, .kalora-prod-thumb:hover {
        border-color: #d4af37;
        transform: translateY(-3px);
    }

    .kalora-prod-thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    /* Product Info Styles (Solid Clean Green without gradient/border) */
    .kalora-prod-details-info {
        background: #071a12;
        border: none;
        border-radius: 20px;
        padding: 30px;
        box-shadow: 0 15px 35px rgba(0,0,0,0.1);
        color: #f3f4f6;
    }

    .kalora-prod-brand-tag {
        font-size: 11px;
        letter-spacing: 2.5px;
        color: #d4af37;
        font-weight: 700;
        text-transform: uppercase;
        margin-bottom: 8px;
    }

    .kalora-prod-title {
        font-size: clamp(22px, 3vw, 28px);
        font-weight: 700;
        color: #ffffff;
        margin: 0 0 12px 0;
        font-family: serif;
    }

    .kalora-prod-rating-row {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 20px;
    }

    .kalora-stars {
        color: #d4af37;
        letter-spacing: 2px;
        font-size: 14px;
    }

    .kalora-rating-text {
        font-size: 13px;
        color: #9ca3af;
    }

    .kalora-prod-price-box {
        display: flex;
        align-items: baseline;
        gap: 12px;
        margin-bottom: 4px;
    }

    .kalora-prod-current-price {
        font-size: 32px;
        font-weight: 900;
        color: #ffffff;
    }

    .kalora-prod-original-price {
        font-size: 18px;
        color: #9ca3af;
        text-decoration: line-through;
    }

    .kalora-prod-discount {
        font-size: 13px;
        color: #34d399;
        font-weight: 700;
        background: rgba(52, 211, 153, 0.15);
        padding: 3px 8px;
        border-radius: 6px;
    }

    .kalora-prod-tax-note {
        font-size: 12px;
        color: #9ca3af;
        margin-bottom: 20px;
    }

    .kalora-prod-divider {
        height: 1px;
        background: rgba(255, 255, 255, 0.1);
        margin: 20px 0;
    }

    .kalora-prod-option-group {
        margin-bottom: 20px;
    }

    .kalora-prod-option-label {
        display: block;
        font-size: 13px;
        font-weight: 600;
        color: #e5e7eb;
        margin-bottom: 10px;
    }

    .kalora-prod-finish-swatches {
        display: flex;
        gap: 12px;
    }

    .kalora-prod-swatch {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        border: 2px solid transparent;
        cursor: pointer;
        transition: transform 0.2s, border-color 0.2s;
    }

    .kalora-prod-swatch.active, .kalora-prod-swatch:hover {
        transform: scale(1.15);
        border-color: #d4af37;
    }

    .kalora-prod-cta-row {
        display: flex;
        gap: 15px;
        margin-bottom: 25px;
    }

    .kalora-prod-btn-bag {
        flex: 1;
        background: linear-gradient(135deg, #d4af37 0%, #aa820a 100%);
        color: #0f172a;
        font-weight: 700;
        border: none;
        padding: 15px;
        border-radius: 12px;
        cursor: pointer;
        font-size: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        transition: filter 0.2s, transform 0.2s;
        box-shadow: 0 10px 20px rgba(212, 175, 55, 0.2);
    }

    .kalora-prod-btn-bag:hover {
        filter: brightness(1.1);
        transform: translateY(-2px);
    }

    .kalora-prod-btn-wishlist {
        width: 50px;
        background: rgba(255, 255, 255, 0.08);
        border: none;
        border-radius: 12px;
        color: #d1d5db;
        font-size: 18px;
        cursor: pointer;
        transition: all 0.2s;
    }

    .kalora-prod-btn-wishlist:hover, .kalora-prod-btn-wishlist.active {
        background: rgba(239, 68, 68, 0.2);
        color: #ef4444;
    }

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

    /* Accordion Section (Solid Clean Green) */
    .kalora-prod-accordion-section {
        margin-top: 30px;
    }

    .kalora-prod-accordion-item {
        background: #071a12;
        border: none;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }

    .kalora-prod-accordion-header {
        padding: 18px 25px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        cursor: pointer;
    }

    .kalora-prod-accordion-header h3 {
        margin: 0;
        font-size: 16px;
        color: #ffffff;
        font-weight: 600;
    }

    .kalora-prod-accordion-header i {
        color: #d4af37;
        transition: transform 0.3s ease;
    }

    .kalora-prod-accordion-item.active .kalora-prod-accordion-header i {
        transform: rotate(180deg);
    }

    .kalora-prod-accordion-body {
        padding: 0 25px 20px 25px;
        color: #9ca3af;
        font-size: 14px;
        line-height: 1.6;
    }

    .kalora-specs-list {
        margin: 0;
        padding-left: 20px;
    }

    .kalora-specs-list li {
        margin-bottom: 6px;
    }
</style>

<!-- Interactive Scripts for Zoom & Gallery -->
<script>
    function changeKaloraImage(thumbElement, imgSrc) {
        document.getElementById('kaloraMainImg').src = imgSrc;
        document.querySelectorAll('.kalora-prod-thumb').forEach(el => el.classList.remove('active'));
        thumbElement.classList.add('active');
    }

    function selectFinish(element, finishName) {
        document.getElementById('selectedFinish').innerText = finishName;
        document.querySelectorAll('.kalora-prod-swatch').forEach(el => el.classList.remove('active'));
        element.classList.add('active');
    }

    function zoomKaloraImage(event) {
        const container = event.currentTarget;
        const img = document.getElementById('kaloraMainImg');
        const rect = container.getBoundingClientRect();
        
        const x = ((event.clientX - rect.left) / container.offsetWidth) * 100;
        const y = ((event.clientY - rect.top) / container.offsetHeight) * 100;
        
        img.style.transformOrigin = `${x}% ${y}%`;
        img.style.transform = "scale(2.2)";
    }

    function resetKaloraZoom() {
        const img = document.getElementById('kaloraMainImg');
        img.style.transformOrigin = "center center";
        img.style.transform = "scale(1)";
    }

    function toggleKaloraAccordion(headerElement) {
        const item = headerElement.parentElement;
        item.classList.toggle('active');
    }
</script>


</main>


<?php include_once 'includes/footter.php' ?>