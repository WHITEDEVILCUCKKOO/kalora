<div class="klr-rec-wrapper">
    <div class="klr-rec-container">
        
        <h2 class="klr-rec-title">SHOP BY RECIPIENT</h2>

        <div class="klr-rec-grid">
            <!-- Card 1: Gifts For Her -->
            <div class="klr-rec-card">
                <div class="klr-rec-img-box">
                    <img src="https://images.unsplash.com/photo-1535632066927-ab7c9ab60908?q=80&w=800&auto=format&fit=crop" alt="Gifts For Her">
                </div>
                <div class="klr-rec-footer">
                    <span class="klr-rec-label">Gifts For Her</span>
                    <span class="klr-rec-arrow">
                        <svg viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
                    </span>
                </div>
            </div>

            <!-- Card 2: Gifts For Him -->
            <div class="klr-rec-card">
                <div class="klr-rec-img-box">
                    <img src="https://images.unsplash.com/photo-1617137984095-74e4e5e3613f?q=80&w=800&auto=format&fit=crop" alt="Gifts For Him">
                </div>
                <div class="klr-rec-footer">
                    <span class="klr-rec-label">Gifts For Him</span>
                    <span class="klr-rec-arrow">
                        <svg viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
                    </span>
                </div>
            </div>
        </div>

    </div>
</div>

<style>
    .klr-rec-wrapper {
        font-family: Arial, sans-serif;
        /* background-color: #ffffff; */
        background: #fdf6ea !important;
        color: #111111;
        padding: 60px 20px;
        box-sizing: border-box;
        width: 100%;
    }

    .klr-rec-container {
        max-width: 1300px;
        margin: 0 auto;
        box-sizing: border-box;
    }

    .klr-rec-title {
        text-align: center;
        font-size: 13px;
        letter-spacing: 3px;
        font-weight: 500;
        margin-bottom: 40px;
        color: #333333;
        animation: klrRecFadeDown 0.8s ease-out;
    }

    .klr-rec-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 30px;
    }

    .klr-rec-card {
        background-color: #f6f5f0;
        border-radius: 4px;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        cursor: pointer;
        box-shadow: 0 4px 15px rgba(0,0,0,0.03);
        transition: transform 0.4s cubic-bezier(0.165, 0.84, 0.44, 1), box-shadow 0.4s ease;
        animation: klrRecScaleUp 0.6s ease-out backwards;
        padding: 18px;
        border-radius: 15px;
    }
    
    .klr-rec-card:nth-child(1) { animation-delay: 0.1s; }
    .klr-rec-card:nth-child(2) { animation-delay: 0.2s; }
    
    .klr-rec-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.08);
    }
    
    .klr-rec-img-box {
        position: relative;
        width: 100%;
        padding-top: 75%; /* 4:3 Aspect Ratio */
        overflow: hidden;
        background-color: #eae6df;
        border-radius: 15px;
    }

    .klr-rec-img-box img {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.6s ease;
    }

    .klr-rec-card:hover .klr-rec-img-box img {
        transform: scale(1.04);
    }

    .klr-rec-footer {
        padding: 22px 20px;
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 8px;
        background-color: #f7f5f0;
    }

    .klr-rec-label {
        font-size: 14px;
        font-weight: 500;
        letter-spacing: 0.5px;
        color: #1a1a1a;
    }

    .klr-rec-arrow {
        width: 18px;
        height: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: transform 0.3s ease;
    }

    .klr-rec-card:hover .klr-rec-arrow {
        transform: translateX(4px);
    }

    .klr-rec-arrow svg {
        width: 14px;
        height: 14px;
        fill: none;
        stroke: #1a1a1a;
        stroke-width: 2;
    }

    @keyframes klrRecFadeDown {
        from { opacity: 0; transform: translateY(-15px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @keyframes klrRecScaleUp {
        from { opacity: 0; transform: scale(0.97); }
        to { opacity: 1; transform: scale(1); }
    }

    @media (max-width: 768px) {
        .klr-rec-grid {
            grid-template-columns: 1fr;
            gap: 24px;
        }
        .klr-rec-title {
            font-size: 12px;
        }
    }
</style>