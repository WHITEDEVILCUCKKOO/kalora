<!-- KALORA Luxury Price Banner Section -->
<section class="kalora-banner-section">
    <div class="kalora-bg-overlay"></div>
    <div class="kalora-sparkles-container" id="kaloraSparkles"></div>
    
    <div class="kalora-container">
        <!-- Section Header -->
        <div class="kalora-header-wrap">
            <span class="kalora-sub-badge">✨ KALORA EXCLUSIVE FEST ✨</span>

        </div>

        <!-- 4-Card Responsive Grid -->
        <div class="kalora-cards-grid">
            
            <!-- Card 1: Under ₹99 -->
            <div class="kalora-price-card" onclick="window.location.href='offer_products.php?price=99'">
                <div class="kalora-card-glow"></div>
                <div class="kalora-tag-shape">
                    <div class="kalora-tag-ribbon"></div>
                    <div class="kalora-brand-mark">KALORA</div>
                    <div class="kalora-price-label">UNDER</div>
                    <div class="kalora-price-value">₹99</div>
                    <div class="kalora-sparkle-dots">✦ ✦ ✦</div>
                </div>
                <div class="kalora-floating-heart kalora-h1">💚</div>
                <div class="kalora-floating-heart kalora-h2">✨</div>
                <div class="kalora-card-footer">
                    <span class="kalora-explore-btn">Explore Collection <i class="fas fa-arrow-right"></i></span>
                </div>
            </div>

            <!-- Card 2: Under ₹199 -->
            <div class="kalora-price-card" onclick="window.location.href='offer_products.php?price=199'">
                <div class="kalora-card-glow"></div>
                <div class="kalora-tag-shape">
                    <div class="kalora-tag-ribbon"></div>
                    <div class="kalora-brand-mark">KALORA</div>
                    <div class="kalora-price-label">UNDER</div>
                    <div class="kalora-price-value">₹199</div>
                    <div class="kalora-sparkle-dots">✦ ✦ ✦</div>
                </div>
                <div class="kalora-floating-heart kalora-h1">💚</div>
                <div class="kalora-floating-heart kalora-h2">✨</div>
                <div class="kalora-card-footer">
                    <span class="kalora-explore-btn">Explore Collection <i class="fas fa-arrow-right"></i></span>
                </div>
            </div>

            <!-- Card 3: Under ₹299 -->
            <div class="kalora-price-card" onclick="window.location.href='offer_products.php?price=299'">
                <div class="kalora-card-glow"></div>
                <div class="kalora-tag-shape">
                    <div class="kalora-tag-ribbon"></div>
                    <div class="kalora-brand-mark">KALORA</div>
                    <div class="kalora-price-label">UNDER</div>
                    <div class="kalora-price-value">₹299</div>
                    <div class="kalora-sparkle-dots">✦ ✦ ✦</div>
                </div>
                <div class="kalora-floating-heart kalora-h1">💚</div>
                <div class="kalora-floating-heart kalora-h2">✨</div>
                <div class="kalora-card-footer">
                    <span class="kalora-explore-btn">Explore Collection <i class="fas fa-arrow-right"></i></span>
                </div>
            </div>

            <!-- Card 4: Under ₹299 -->
            <div class="kalora-price-card" onclick="window.location.href='offer_products.php?price=399'">
                <div class="kalora-card-glow"></div>
                <div class="kalora-tag-shape">
                    <div class="kalora-tag-ribbon"></div>
                    <div class="kalora-brand-mark">KALORA</div>
                    <div class="kalora-price-label">UNDER</div>
                    <div class="kalora-price-value">₹399</div>
                    <div class="kalora-sparkle-dots">✦ ✦ ✦</div>
                </div>
                <div class="kalora-floating-heart kalora-h1">💚</div>
                <div class="kalora-floating-heart kalora-h2">✨</div>
                <div class="kalora-card-footer">
                    <span class="kalora-explore-btn">Explore Collection <i class="fas fa-arrow-right"></i></span>
                </div>
            </div>

            <!-- Card 5: Under ₹499 -->
            <div class="kalora-price-card" onclick="window.location.href='offer_products.php?price=499'">
                <div class="kalora-card-glow"></div>
                <div class="kalora-tag-shape">
                    <div class="kalora-tag-ribbon"></div>
                    <div class="kalora-brand-mark">KALORA</div>
                    <div class="kalora-price-label">UNDER</div>
                    <div class="kalora-price-value">₹499</div>
                    <div class="kalora-sparkle-dots">✦ ✦ ✦</div>
                </div>
                <div class="kalora-floating-heart kalora-h1">💚</div>
                <div class="kalora-floating-heart kalora-h2">✨</div>
                <div class="kalora-card-footer">
                    <span class="kalora-explore-btn">Explore Collection <i class="fas fa-arrow-right"></i></span>
                </div>
            </div>

        </div>
    </div>

    <!-- Interactive Quick View Modal -->
    <div class="kalora-modal-overlay" id="kaloraModal">
        <div class="kalora-modal-content">
            <button class="kalora-modal-close" onclick="closeKaloraModal()">&times;</button>
            <div class="kalora-modal-header">
                <span class="kalora-modal-tag">KALORA LUXURY</span>
                <h3 id="kaloraModalTitle">Collection Title</h3>
            </div>
            <p class="kalora-modal-desc">Discover our exclusive handcrafted pieces available under this special pricing tier. Limited stock available!</p>
            <div class="kalora-modal-actions">
                <button class="kalora-btn-primary" onclick="alert('Redirecting to KALORA store catalog...')">Shop Now</button>
                <button class="kalora-btn-secondary" onclick="closeKaloraModal()">Close</button>
            </div>
        </div>
    </div>
</section>

<!-- Unique Encapsulated Styles (No body or universal selectors used) -->
<style>
    .kalora-banner-section {
        position: relative;
        /* background: linear-gradient(135deg, #05130d 0%, #0b2217 50%, #04100a 100%); */
        background: #fdf6ea !important;
        padding: 80px 20px;
        font-family: 'Inter', sans-serif;
        color: #f3f4f6;
        overflow: hidden;
        isolation: isolate;
    }

    .kalora-bg-overlay {
        position: absolute;
        inset: 0;
        background: radial-gradient(circle at 50% 30%, rgba(16, 185, 129, 0.08) 0%, transparent 70%);
        pointer-events: none;
        z-index: 1;
    }

    .kalora-sparkles-container {
        position: absolute;
        inset: 0;
        pointer-events: none;
        z-index: 1;
    }

    .kalora-sparkle {
        position: absolute;
        background: #d4af37;
        border-radius: 50%;
        animation: kaloraTwinkle 3s infinite ease-in-out;
    }

    @keyframes kaloraTwinkle {
        0%, 100% { opacity: 0.2; transform: scale(0.8); }
        50% { opacity: 0.8; transform: scale(1.2); }
    }

    .kalora-container {
        max-width: 1200px;
        margin: 0 auto;
        position: relative;
        z-index: 2;
    }

    .kalora-header-wrap {
        text-align: center;
        margin-bottom: 50px;
    }

    .kalora-sub-badge {
        display: inline-block;
        font-size: 13px;
        letter-spacing: 3px;
        color: #141414;
        text-transform: uppercase;
        font-weight: 600;
        margin-bottom: 12px;
        background: rgba(212, 175, 55, 0.1);
        padding: 6px 16px;
        border-radius: 20px;
        border: 1px solid rgba(212, 175, 55, 0.2);
    }

    .kalora-main-title {
        font-size: clamp(28px, 4vw, 42px);
        font-weight: 700;
        color: #161616;
        margin: 0 0 15px 0;
        font-family: serif;
        letter-spacing: 0.5px;
    }

    .kalora-title-divider {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 15px;
        margin-bottom: 15px;
    }

    .kalora-divider-gem {
        color: #0c0c0c;
        font-size: 14px;
        animation: kaloraPulseGem 2s infinite ease-in-out;
    }

    @keyframes kaloraPulseGem {
        0%, 100% { transform: scale(1); opacity: 0.7; }
        50% { transform: scale(1.25); opacity: 1; }
    }

    .kalora-subtitle {
        color: #9ca3af;
        font-size: 15px;
        max-width: 600px;
        margin: 0 auto;
        line-height: 1.6;
    }

    .kalora-cards-grid {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 25px;
    }

    @media (max-width: 1024px) {
        .kalora-cards-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 640px) {
        .kalora-cards-grid {
            grid-template-columns: 1fr 1fr;
        }
        .kalora-price-card:last-child {
        grid-column: 1 / -1;
        justify-self: center;
    }
    }

    .kalora-price-card {
        position: relative;
        background: linear-gradient(145deg, #0f2c1f 0%, #071911 100%);
        border-radius: 20px;
        padding: 24px;
        border: 1px solid rgba(212, 175, 55, 0.2);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.4), inset 0 1px 0 rgba(255, 255, 255, 0.1);
        cursor: pointer;
        transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
        overflow: hidden;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .kalora-price-card:hover {
        transform: translateY(-8px);
        border-color: rgba(212, 175, 55, 0.6);
        box-shadow: 0 22px 45px rgba(0, 0, 0, 0.6), 0 0 25px rgba(16, 185, 129, 0.2);
    }

    .kalora-card-glow {
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(212, 175, 55, 0.08), transparent);
        transition: 0.6s;
    }

    .kalora-price-card:hover .kalora-card-glow {
        left: 100%;
    }

    .kalora-tag-shape {
        background: linear-gradient(135deg, #fffbf0 0%, #f4ebd0 100%);
        color: #111827;
        width: 100%;
        padding: 32px 20px;
        border-radius: 14px;
        text-align: center;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.25);
        transform: rotate(-1.5deg);
        transition: transform 0.4s ease;
        position: relative;
    }

    .kalora-price-card:hover .kalora-tag-shape {
        transform: rotate(0deg) scale(1.03);
    }

    .kalora-tag-ribbon {
        position: absolute;
        top: -8px;
        left: 50%;
        transform: translateX(-50%);
        width: 40px;
        height: 12px;
        background: #d4af37;
        border-radius: 4px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.2);
    }

    .kalora-brand-mark {
        font-size: 10px;
        letter-spacing: 2px;
        color: #6b7280;
        text-transform: uppercase;
        margin-bottom: 8px;
        font-weight: 700;
    }

    .kalora-price-label {
        font-size: 13px;
        font-weight: 800;
        letter-spacing: 1.5px;
        color: #1f2937;
        margin-bottom: 4px;
    }

    .kalora-price-value {
        font-size: clamp(34px, 4vw, 42px);
        font-weight: 900;
        color: #0f172a;
        line-height: 1;
        letter-spacing: -1px;
    }

    .kalora-sparkle-dots {
        margin-top: 12px;
        font-size: 12px;
        color: #d4af37;
        letter-spacing: 4px;
    }

    .kalora-floating-heart {
        position: absolute;
        font-size: 18px;
        animation: kaloraFloatAnim 3.5s infinite ease-in-out;
        opacity: 0.85;
    }

    .kalora-h1 {
        top: 15px;
        right: 18px;
        animation-delay: 0s;
    }

    .kalora-h2 {
        bottom: 55px;
        left: 18px;
        animation-delay: 1.5s;
    }

    @keyframes kaloraFloatAnim {
        0%, 100% { transform: translateY(0) rotate(0deg); }
        50% { transform: translateY(-8px) rotate(6deg); }
    }

    .kalora-card-footer {
        margin-top: 20px;
        width: 100%;
        text-align: center;
    }

    .kalora-explore-btn {
        font-size: 13px;
        color: #ffffff;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: gap 0.3s ease, color 0.3s ease;
    }

    .kalora-price-card:hover .kalora-explore-btn {
        color: #b9a010;
        gap: 10px;
    }

    /* Modal Styles */
    .kalora-modal-overlay {
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.75);
        backdrop-filter: blur(5px);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 9999;
        padding: 20px;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .kalora-modal-overlay.kalora-active {
        display: flex;
        opacity: 1;
    }

    .kalora-modal-content {
        background: linear-gradient(145deg, #0f2c1f 0%, #071911 100%);
        border: 1px solid rgba(212, 175, 55, 0.4);
        border-radius: 20px;
        padding: 35px;
        max-width: 450px;
        width: 100%;
        position: relative;
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.8);
        transform: scale(0.9);
        transition: transform 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }

    .kalora-modal-overlay.kalora-active .kalora-modal-content {
        transform: scale(1);
    }

    .kalora-modal-close {
        position: absolute;
        top: 15px;
        right: 20px;
        background: none;
        border: none;
        color: #9ca3af;
        font-size: 26px;
        cursor: pointer;
        transition: color 0.2s;
    }

    .kalora-modal-close:hover {
        color: #ffffff;
    }

    .kalora-modal-tag {
        font-size: 11px;
        letter-spacing: 2px;
        color: #d4af37;
        font-weight: 700;
        text-transform: uppercase;
    }

    .kalora-modal-header h3 {
        font-size: 24px;
        color: #ffffff;
        margin: 8px 0 12px 0;
        font-family: serif;
    }

    .kalora-modal-desc {
        color: #d1d5db;
        font-size: 14px;
        line-height: 1.6;
        margin-bottom: 25px;
    }

    .kalora-modal-actions {
        display: flex;
        gap: 12px;
    }

    .kalora-btn-primary {
        flex: 1;
        background: linear-gradient(135deg, #d4af37 0%, #aa820a 100%);
        color: #0f172a;
        font-weight: 700;
        border: none;
        padding: 12px 20px;
        border-radius: 10px;
        cursor: pointer;
        transition: filter 0.2s, transform 0.2s;
    }

    .kalora-btn-primary:hover {
        filter: brightness(1.1);
        transform: translateY(-2px);
    }

    .kalora-btn-secondary {
        background: transparent;
        color: #d1d5db;
        border: 1px solid rgba(255, 255, 255, 0.2);
        padding: 12px 20px;
        border-radius: 10px;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.2s, color 0.2s;
    }

    .kalora-btn-secondary:hover {
        background: rgba(255, 255, 255, 0.05);
        color: #ffffff;
    }
</style>

<!-- Interactive Script -->
<script>
    // Generate background gold sparkles dynamically
    document.addEventListener("DOMContentLoaded", () => {
        const container = document.getElementById("kaloraSparkles");
        if(container && container.children.length === 0) {
            for (let i = 0; i < 20; i++) {
                const sparkle = document.createElement("div");
                sparkle.className = "kalora-sparkle";
                sparkle.style.top = Math.random() * 100 + "%";
                sparkle.style.left = Math.random() * 100 + "%";
                const size = Math.random() * 3 + 2 + "px";
                sparkle.style.width = size;
                sparkle.style.height = size;
                sparkle.style.animationDuration = (Math.random() * 3 + 2) + "s";
                sparkle.style.animationDelay = (Math.random() * 2) + "s";
                container.appendChild(sparkle);
            }
        }
    });

    function openKaloraModal(title, price) {
        const modal = document.getElementById("kaloraModal");
        document.getElementById("kaloraModalTitle").innerText = title;
        modal.classList.add("kalora-active");
    }

    function closeKaloraModal() {
        const modal = document.getElementById("kaloraModal");
        modal.classList.remove("kalora-active");
    }

    // Close modal on outside click
    window.addEventListener("click", (e) => {
        const modal = document.getElementById("kaloraModal");
        if (e.target === modal) {
            closeKaloraModal();
        }
    });
</script>