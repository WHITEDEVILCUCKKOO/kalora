<div class="klr-blog-wrapper">
    <div class="klr-blog-container">
        
        <h2 class="klr-blog-header-title">BLOGS</h2>

        <div class="klr-blog-grid">
            <!-- Blog Card 1 -->
            <div class="klr-blog-card">
                <div class="klr-blog-img-box">
                    <div class="klr-blog-date-badge">
                        <span class="klr-blog-day">03</span>
                        <span class="klr-blog-month">MAR</span>
                    </div>
                    <img src="https://images.unsplash.com/photo-1605100804763-247f67b3557e?q=80&w=600&auto=format&fit=crop" alt="Lab-Grown Diamonds">
                </div>
                <div class="klr-blog-content">
                    <h3 class="klr-blog-title">Lab-Grown Diamonds: Styling & Care for the Modern Indian Woman</h3>
                    <p class="klr-blog-excerpt">If jewellery had a reality check, lab-grown diamonds would be it. Real, pretty, and completely low-drama. Th...</p>
                </div>
            </div>

            <!-- Blog Card 2 -->
            <div class="klr-blog-card">
                <div class="klr-blog-img-box">
                    <div class="klr-blog-date-badge">
                        <span class="klr-blog-day">02</span>
                        <span class="klr-blog-month">MAR</span>
                    </div>
                    <img src="https://images.unsplash.com/photo-1599643478518-a784e5dc4c8f?q=80&w=600&auto=format&fit=crop" alt="Women's Day Jewellery Guide">
                </div>
                <div class="klr-blog-content">
                    <h3 class="klr-blog-title">The Women's Day Jewellery Guide Nobody Asked For, But Everybody Needed</h3>
                    <p class="klr-blog-excerpt">Beyoncé told us who runs the world. Legally Blonde proved that a woman can ace Harvard Law in pink. Inspecta...</p>
                </div>
            </div>

            <!-- Blog Card 3 -->
            <div class="klr-blog-card">
                <div class="klr-blog-img-box">
                    <div class="klr-blog-date-badge">
                        <span class="klr-blog-day">01</span>
                        <span class="klr-blog-month">MAR</span>
                    </div>
                    <img src="https://images.unsplash.com/photo-1515562141207-7a88fb7ce338?q=80&w=600&auto=format&fit=crop" alt="Gold vs Silver Jewellery">
                </div>
                <div class="klr-blog-content">
                    <h3 class="klr-blog-title">Gold vs Silver Jewellery: How to Choose What Suits You Best</h3>
                    <p class="klr-blog-excerpt">The great debate is always on—gold or silver? That's like asking, chai or coffee, Friends or Seinfeld, or ...</p>
                </div>
            </div>
        </div>

        <div class="klr-blog-view-all-box">
            <button class="klr-blog-view-all-btn">View All</button>
        </div>

    </div>
</div>

<style>
    .klr-blog-wrapper {
        font-family: Arial, sans-serif;
        /* background-color: #ffffff; */
        background: #fdf6ea !important;
        color: #111111;
        padding: 60px 20px;
        box-sizing: border-box;
        width: 100%;
    }

    .klr-blog-container {
        max-width: 1300px;
        margin: 0 auto;
        box-sizing: border-box;
    }

    .klr-blog-header-title {
        text-align: center;
        font-size: 14px;
        letter-spacing: 3px;
        font-weight: 500;
        margin-bottom: 40px;
        color: #333333;
        animation: klrBlogFadeDown 0.8s ease-out;
    }

    .klr-blog-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 30px;
        margin-bottom: 40px;
    }

    .klr-blog-card {
        background-color: #ffffff;
        position: relative;
        display: flex;
        flex-direction: column;
        cursor: pointer;
        transition: transform 0.4s cubic-bezier(0.165, 0.84, 0.44, 1), box-shadow 0.4s ease;
        animation: klrBlogScaleUp 0.6s ease-out backwards;
    }

    .klr-blog-card:nth-child(1) { animation-delay: 0.1s; }
    .klr-blog-card:nth-child(2) { animation-delay: 0.2s; }
    .klr-blog-card:nth-child(3) { animation-delay: 0.3s; }

    .klr-blog-card:hover {
        transform: translateY(-5px);
    }

    .klr-blog-img-box {
        background-color: #f4f4f4;
        position: relative;
        width: 100%;
        padding-top: 75%; /* 4:3 Aspect Ratio */
        overflow: hidden;
        border-radius: 2px;
    }

    .klr-blog-img-box img {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.6s ease;
    }

    .klr-blog-card:hover .klr-blog-img-box img {
        transform: scale(1.05);
    }

    .klr-blog-date-badge {
        position: absolute;
        top: 16px;
        right: 16px;
        background-color: #ffffff;
        width: 48px;
        height: 48px;
        border-radius: 50%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        z-index: 2;
    }

    .klr-blog-day {
        font-size: 13px;
        font-weight: 700;
        color: #111111;
        line-height: 1;
    }

    .klr-blog-month {
        font-size: 9px;
        font-weight: 600;
        color: #666666;
        letter-spacing: 0.5px;
        margin-top: 2px;
    }

    .klr-blog-content {
        padding: 18px 4px 0 4px;
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .klr-blog-title {
        font-size: 15px;
        line-height: 1.4;
        color: #1a1a1a;
        font-weight: 500;
        margin: 0;
        transition: color 0.2s ease;
    }

    .klr-blog-card:hover .klr-blog-title {
        color: #555555;
    }

    .klr-blog-excerpt {
        font-size: 12px;
        line-height: 1.5;
        color: #777777;
        margin: 0;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .klr-blog-view-all-box {
        text-align: center;
        margin-top: 40px;
    }

    .klr-blog-view-all-btn {
        background-color: #ffffff;
        border: 1px solid #333333;
        color: #111111;
        padding: 12px 36px;
        font-size: 12px;
        letter-spacing: 1.5px;
        cursor: pointer;
        font-weight: 500;
        border-radius: 2px;
        transition: all 0.3s ease;
    }

    .klr-blog-view-all-btn:hover {
        background-color: #111111;
        color: #ffffff;
        border-color: #111111;
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(0,0,0,0.15);
    }

    @keyframes klrBlogFadeDown {
        from { opacity: 0; transform: translateY(-15px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @keyframes klrBlogScaleUp {
        from { opacity: 0; transform: scale(0.97); }
        to { opacity: 1; transform: scale(1); }
    }

    @media (max-width: 1024px) {
        .klr-blog-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 24px;
        }
    }

    @media (max-width: 650px) {
        .klr-blog-grid {
            grid-template-columns: 1fr;
            gap: 24px;
        }
        .klr-blog-header-title {
            font-size: 12px;
        }
    }
</style>