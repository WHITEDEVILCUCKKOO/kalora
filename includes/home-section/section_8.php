<div class="klr-conf-wrapper">
    <div class="klr-conf-container">
        
        <h2 class="klr-conf-title">SHOP WITH CONFIDENCE</h2>

        <div class="klr-conf-grid">
            <!-- Feature 1: Skin Safe -->
            <div class="klr-conf-card">
                <div class="klr-conf-icon-box">
                    <svg viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.182 15.182a4.5 4.5 0 01-6.364 0M21 12a9 9 0 11-18 0 9 9 0 0118 0zM9.75 9.75c0 .414-.168.75-.375.75S9 10.164 9 9.75 9.168 9 9.375 9s.375.336.375.75zm5.25 0c0 .414-.168.75-.375.75s-.375-.336-.375-.75.168-.75.375-.75.375.336.375.75z" fill="none" stroke="currentColor" stroke-width="1.5"/></svg>
                </div>
                <h3 class="klr-conf-heading">SKIN SAFE</h3>
                <p class="klr-conf-desc">Our jewelry is hypoallergenic and skin-safe, crafted with care to ensure comfort for all skin types. Enjoy beautiful, irritation-free wear every day, knowing each piece is designed with your well-being in mind.</p>
            </div>

            <!-- Feature 2: 18K Gold Vermeil -->
            <div class="klr-conf-card">
                <div class="klr-conf-icon-box">
                    <svg viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 7.5l-9-5.25L3 7.5m18 0l-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9" fill="none" stroke="currentColor" stroke-width="1.5"/></svg>
                </div>
                <h3 class="klr-conf-heading">18K GOLD VERMEIL</h3>
                <p class="klr-conf-desc">Our jewelry is crafted from premium metals like surgical steel, sterling silver, and thick 18k gold plating, ensuring durability and lasting shine. Experience luxury and quality with every piece, designed to stand the test of time.</p>
            </div>

            <!-- Feature 3: Authentic Diamonds -->
            <div class="klr-conf-card">
                <div class="klr-conf-icon-box">
                    <svg viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 7.5l-2.25-4.5h-13.5L3 7.5l9 12 9-12zM3 7.5h18M12 19.5v-12m-6.75 3h13.5" fill="none" stroke="currentColor" stroke-width="1.5"/></svg>
                </div>
                <h3 class="klr-conf-heading">AUTHENTIC DIAMONDS</h3>
                <p class="klr-conf-desc">Our lab-grown diamonds are SGL Certified, ensuring the highest standards of quality and authenticity same like natural diamonds. Each diamond undergoes rigorous testing to guarantee its brilliance and ethical origins. Shine with confidence in every sparkly moment.</p>
            </div>
        </div>

    </div>
</div>

<style>
    .klr-conf-wrapper {
        font-family: Arial, sans-serif;
        background-color: #f7f6f2;
        color: #111111;
        padding: 70px 20px;
        box-sizing: border-box;
        width: 100%;
    }

    .klr-conf-container {
        max-width: 1300px;
        margin: 0 auto;
        box-sizing: border-box;
    }

    .klr-conf-title {
        text-align: center;
        font-size: 15px;
        letter-spacing: 2.5px;
        font-weight: 500;
        margin-bottom: 50px;
        color: #1a1a1a;
        animation: klrConfFadeDown 0.8s ease-out;
    }

    .klr-conf-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 40px;
    }

    .klr-conf-card {
        background-color: transparent;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        padding: 10px;
        transition: transform 0.4s ease;
        animation: klrConfScaleUp 0.6s ease-out backwards;
    }

    .klr-conf-card:nth-child(1) { animation-delay: 0.1s; }
    .klr-conf-card:nth-child(2) { animation-delay: 0.2s; }
    .klr-conf-card:nth-child(3) { animation-delay: 0.3s; }

    .klr-conf-card:hover {
        transform: translateY(-4px);
    }

    .klr-conf-icon-box {
        width: 50px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 20px;
    }

    .klr-conf-icon-box svg {
        width: 40px;
        height: 40px;
        stroke: #111111;
        transition: transform 0.3s ease;
    }

    .klr-conf-card:hover .klr-conf-icon-box svg {
        transform: scale(1.1);
    }

    .klr-conf-heading {
        font-size: 14px;
        letter-spacing: 1.5px;
        font-weight: 600;
        color: #111111;
        margin: 0 0 16px 0;
    }

    .klr-conf-desc {
        font-size: 12px;
        line-height: 1.7;
        color: #555555;
        margin: 0;
        max-width: 360px;
    }

    @keyframes klrConfFadeDown {
        from { opacity: 0; transform: translateY(-15px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @keyframes klrConfScaleUp {
        from { opacity: 0; transform: scale(0.97); }
        to { opacity: 1; transform: scale(1); }
    }

    @media (max-width: 1024px) {
        .klr-conf-grid {
            grid-template-columns: 1fr;
            gap: 40px;
        }
        .klr-conf-desc {
            max-width: 500px;
        }
    }
</style>