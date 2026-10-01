<style>
    .palmonas-wrapper {
        font-family: Arial, sans-serif;
        /* background-color: #ffffff; */
        background: #fdf6ea !important;
        color: #111111;
        padding: 40px 20px;
        box-sizing: border-box;
        width: 100%;
    }

    .palmonas-container {
        max-width: 1280px;
        margin: 0 auto;
        box-sizing: border-box;
    }

    .palmonas-title {
        text-align: center;
        font-size: 24px;
        letter-spacing: 2px;
        font-weight: 500;
        margin-bottom: 30px;
        color: #000000;
        animation: palmonasFadeDown 0.8s ease-out;
    }

    .palmonas-nav {
        display: flex;
        justify-content: center;
        align-items: center;
        flex-wrap: wrap;
        gap: 10px;
        margin-bottom: 40px;
        animation: palmonasFadeIn 1s ease-out;
    }

    .palmonas-nav-btn {
        /* background-color: #ffffff; */
        background-color: #FDF6EA;

        border: 1px solid #111111;
        color: #111111;
        padding: 10px 22px;
        font-size: 13px;
        letter-spacing: 1px;
        cursor: pointer;
        transition: all 0.3s ease;
        font-weight: 500;
    }

    .palmonas-nav-btn:hover {
        background-color: #f4f4f4;
        transform: translateY(-2px);
    }

    .palmonas-nav-btn.palmonas-active {
        /* background-color: #000000; */
        background: linear-gradient(145deg, #0f2c1f 0%, #071911 100%);
        color: #ffffff;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
    }

    .palmonas-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 24px;
        margin-bottom: 40px;
    }

    .palmonas-card {
        /* background-color: #ffffff; */
        background: linear-gradient(145deg, #0f2c1f 0%, #071911 100%);
        padding: 15px;
        border-radius: 10px;
        position: relative;
        display: flex;
        flex-direction: column;
        transition: transform 0.4s cubic-bezier(0.165, 0.84, 0.44, 1), box-shadow 0.4s ease;
        animation: palmonasScaleUp 0.6s ease-out backwards;
    }

    .palmonas-card:nth-child(1) {
        animation-delay: 0.1s;
    }

    .palmonas-card:nth-child(2) {
        animation-delay: 0.2s;
    }

    .palmonas-card:nth-child(3) {
        animation-delay: 0.3s;
    }

    .palmonas-card:nth-child(4) {
        animation-delay: 0.4s;
    }

    .palmonas-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.08);
    }

    .palmonas-img-box {
        background-color: #f7f7f7;
        position: relative;
        width: 100%;
        padding-top: 115%;
        overflow: hidden;
    }

    .palmonas-img-box img {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.6s ease;
    }

    .palmonas-card:hover .palmonas-img-box img {
        transform: scale(1.05);
    }

    .palmonas-badge {
        position: absolute;
        top: 10px;
        left: 0;
        background-color: #e8ded5;
        color: #5c4d43;
        font-size: 10px;
        letter-spacing: 0.5px;
        padding: 4px 12px 4px 8px;
        border-radius: 0 4px 4px 0;
        font-weight: 500;
    }

    .palmonas-wishlist {
        position: absolute;
        bottom: 12px;
        left: 12px;
        background: #ffffff;
        border: none;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        transition: transform 0.2s ease, background-color 0.2s ease;
    }

    .palmonas-wishlist:hover {
        transform: scale(1.1);
        background-color: #fff0f0;
    }

    .palmonas-wishlist svg {
        width: 16px;
        height: 16px;
        fill: none;
        stroke: #111111;
        stroke-width: 1.8;
        transition: fill 0.2s ease, stroke 0.2s ease;
    }

    .palmonas-wishlist:hover svg {
        fill: #ff3b30;
        stroke: #ff3b30;
    }

    .palmonas-bag-btn {
        position: absolute;
        bottom: 12px;
        right: 12px;
        background-color: #ffffff;
        border: 1px solid #e0e0e0;
        color: #333333;
        font-size: 10px;
        font-weight: 600;
        letter-spacing: 0.5px;
        padding: 6px 10px;
        cursor: pointer;
        opacity: 0;
        transform: translateY(10px);
        transition: all 0.3s ease;
    }

    .palmonas-card:hover .palmonas-bag-btn {
        opacity: 1;
        transform: translateY(0);
    }

    .palmonas-bag-btn:hover {
        /* background-color: #111111; */
        background: linear-gradient(145deg, #0f2c1f 0%, #071911 100%);
        color: #ffffff;
        border-color: #111111;
    }

    .palmonas-details {
        padding: 14px 4px 0 4px;
        text-align: center;
    }

    .palmonas-product-name {
        font-size: 13px;
        line-height: 1.4;
        color: #ffffff;
        margin-bottom: 8px;
        font-weight: 400;
    }

    .palmonas-price-box {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 8px;
        font-size: 13px;
    }

    .palmonas-current-price {
        font-weight: 600;
        color: #fffdfd;
    }

    .palmonas-original-price {
        text-decoration: line-through;
        color: #f1a5a5;
        font-size: 12px;
    }

    .palmonas-view-all-box {
        text-align: center;
        margin-top: 30px;
    }

    .palmonas-view-all-btn {
        /* background-color: #ffffff; */
        background-color: #FDF6EA;
        border: 1px solid #111111;
        color: #111111;
        padding: 12px 36px;
        font-size: 12px;
        letter-spacing: 1.5px;
        cursor: pointer;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .palmonas-view-all-btn:hover {
        /* background-color: #111111;
        */
        background: linear-gradient(145deg, #0f2c1f 0%, #071911 100%);
        color: #ffffff;
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(0, 0, 0, 0.15);
    }

    @keyframes palmonasFadeDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes palmonasFadeIn {
        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }
    }

    @keyframes palmonasScaleUp {
        from {
            opacity: 0;
            transform: scale(0.95);
        }

        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    @media (max-width: 1024px) {
        .palmonas-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }
    }

    @media (max-width: 580px) {
        .palmonas-grid {
            grid-template-columns: 1fr;
            gap: 24px;
        }

        .palmonas-title {
            font-size: 20px;
        }

        .palmonas-nav-btn {
            padding: 8px 14px;
            font-size: 11px;
        }
    }
</style>


<div class="palmonas-wrapper">
    <div class="palmonas-container">

        <h2 class="palmonas-title">KALORA TOP STYLES</h2>

        <div class="palmonas-nav">
            <button class="palmonas-nav-btn">ALL</button>
            <button class="palmonas-nav-btn">NECKLACES</button>
            <button class="palmonas-nav-btn">BRACELETS</button>
            <button class="palmonas-nav-btn">EARRINGS</button>
            <button class="palmonas-nav-btn">RINGS</button>
            <button class="palmonas-nav-btn">MENS</button>
            <button class="palmonas-nav-btn palmonas-active">MANGALSUTRA</button>
        </div>

        <div class="palmonas-grid">
            <!-- Card 1 -->
            <div class="palmonas-card" onclick="window.location.href='product_details.php'">
                <div class="palmonas-img-box">
                    <span class="palmonas-badge">New Arrival</span>
                    <img src="https://images.unsplash.com/photo-1599643478518-a784e5dc4c8f?q=80&w=600&auto=format&fit=crop" alt="Solitaire Station Mangalsutra">
                    <button class="palmonas-wishlist" title="Add to Wishlist">
                        <svg viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                        </svg>
                    </button>
                    <button class="palmonas-bag-btn">ADD TO BAG</button>
                </div>
                <div class="palmonas-details">
                    <p class="palmonas-product-name">Solitaire Station 925 Sterling Silver Mangalsutra</p>
                    <div class="palmonas-price-box">
                        <span class="palmonas-current-price">₹ 5,914.00</span>
                        <span class="palmonas-original-price">₹ 7,399.00</span>
                    </div>
                </div>
            </div>

            <!-- Card 2 -->
            <div class="palmonas-card">
                <div class="palmonas-img-box">
                    <span class="palmonas-badge">New Arrival</span>
                    <img src="https://images.unsplash.com/photo-1605100804763-247f67b3557e?q=80&w=600&auto=format&fit=crop" alt="Round Solitaire Mangalsutra">
                    <button class="palmonas-wishlist" title="Add to Wishlist">
                        <svg viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                        </svg>
                    </button>
                    <button class="palmonas-bag-btn">ADD TO BAG</button>
                </div>
                <div class="palmonas-details">
                    <p class="palmonas-product-name">Round Solitaire 925 Sterling Silver Mangalsutra</p>
                    <div class="palmonas-price-box">
                        <span class="palmonas-current-price">₹ 6,227.00</span>
                        <span class="palmonas-original-price">₹ 7,799.00</span>
                    </div>
                </div>
            </div>

            <!-- Card 3 -->
            <div class="palmonas-card">
                <div class="palmonas-img-box">
                    <span class="palmonas-badge">New Arrival</span>
                    <img src="https://images.unsplash.com/photo-1515562141207-7a88fb7ce338?q=80&w=600&auto=format&fit=crop" alt="Swan Heart Mangalsutra">
                    <button class="palmonas-wishlist" title="Add to Wishlist">
                        <svg viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                        </svg>
                    </button>
                    <button class="palmonas-bag-btn">ADD TO BAG</button>
                </div>
                <div class="palmonas-details">
                    <p class="palmonas-product-name">Swan Heart 925 Sterling Silver Mangalsutra</p>
                    <div class="palmonas-price-box">
                        <span class="palmonas-current-price">₹ 5,337.00</span>
                        <span class="palmonas-original-price">₹ 6,699.00</span>
                    </div>
                </div>
            </div>

            <!-- Card 4 -->
            <div class="palmonas-card">
                <div class="palmonas-img-box">
                    <span class="palmonas-badge">New Arrival</span>
                    <img src="https://images.unsplash.com/photo-1535632066927-ab7c9ab60908?q=80&w=600&auto=format&fit=crop" alt="Layered Chevron Mangalsutra">
                    <button class="palmonas-wishlist" title="Add to Wishlist">
                        <svg viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                        </svg>
                    </button>
                    <button class="palmonas-bag-btn">ADD TO BAG</button>
                </div>
                <div class="palmonas-details">
                    <p class="palmonas-product-name">Layered Chevron 925 Sterling Silver Mangalsutra</p>
                    <div class="palmonas-price-box">
                        <span class="palmonas-current-price">₹ 5,842.00</span>
                        <span class="palmonas-original-price">₹ 7,399.00</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="palmonas-view-all-box">
            <button class="palmonas-view-all-btn">VIEW ALL</button>
        </div>

    </div>
</div>