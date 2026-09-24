<div class="klr-faq-wrapper">
    <div class="klr-faq-container">
        
        <h2 class="klr-faq-title">FREQUENTLY ASKED QUESTIONS</h2>
        <p class="klr-faq-subtitle">Got questions? We've got answers about our jewellery, care, shipping, and more.</p>

        <div class="klr-faq-list">
            <!-- FAQ Item 1 -->
            <div class="klr-faq-item">
                <button class="klr-faq-question">
                    <span>What is 18K Gold Vermeil and how long does it last?</span>
                    <svg class="klr-faq-icon" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
                </button>
                <div class="klr-faq-answer">
                    <p>Our jewellery is crafted from premium metals like surgical steel and sterling silver with a thick 18k gold plating layer on top. This gold vermeil technique ensures exceptional durability, luxury finish, and a long-lasting shine designed to stand the test of time.</p>
                </div>
            </div>

            <!-- FAQ Item 2 -->
            <div class="klr-faq-item">
                <button class="klr-faq-question">
                    <span>Are your diamonds authentic and certified?</span>
                    <svg class="klr-faq-icon" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
                </button>
                <div class="klr-faq-answer">
                    <p>Yes, absolutely! All our lab-grown diamonds are SGL Certified, guaranteeing the exact same brilliance, physical properties, and optical sparkle as natural mined diamonds, while maintaining ethical and sustainable origins.</p>
                </div>
            </div>

            <!-- FAQ Item 3 -->
            <div class="klr-faq-item">
                <button class="klr-faq-question">
                    <span>Is Kalora jewellery skin-safe and hypoallergenic?</span>
                    <svg class="klr-faq-icon" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
                </button>
                <div class="klr-faq-answer">
                    <p>Yes, every piece is 100% hypoallergenic and skin-safe. We craft our jewellery with utmost care to prevent any irritation, making it completely safe and comfortable for everyday wear, even for sensitive skin types.</p>
                </div>
            </div>

            <!-- FAQ Item 4 -->
            <div class="klr-faq-item">
                <button class="klr-faq-question">
                    <span>What is your Return, Exchange and Lifetime Warranty policy?</span>
                    <svg class="klr-faq-icon" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
                </button>
                <div class="klr-faq-answer">
                    <p>We offer hassle-free returns and exchange policies as per our shipping guidelines. Additionally, we provide Lifetime Warranty and BuyBack policies on eligible fine gold and diamond collections so you can shop with absolute confidence.</p>
                </div>
            </div>

            <!-- FAQ Item 5 -->
            <div class="klr-faq-item">
                <button class="klr-faq-question">
                    <span>Do you offer special gifting options for Him and Her?</span>
                    <svg class="klr-faq-icon" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
                </button>
                <div class="klr-faq-answer">
                    <p>Yes! Explore our dedicated 'Gifts For Her' and 'Gifts For Him' categories featuring stunning rings, bracelets, necklaces, and fine gold mangalsutras tailored perfectly for your loved ones on any special occasion.</p>
                </div>
            </div>
        </div>

    </div>
</div>

<style>
    .klr-faq-wrapper {
        font-family: Arial, sans-serif;
        background-color: #ffffff;
        color: #111111;
        padding: 70px 20px;
        box-sizing: border-box;
        width: 100%;
    }

    .klr-faq-container {
        max-width: 900px;
        margin: 0 auto;
        box-sizing: border-box;
    }

    .klr-faq-title {
        text-align: center;
        font-size: 15px;
        letter-spacing: 2.5px;
        font-weight: 600;
        margin-bottom: 10px;
        color: #111111;
    }

    .klr-faq-subtitle {
        text-align: center;
        font-size: 13px;
        color: #666666;
        margin-bottom: 40px;
    }

    .klr-faq-list {
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    .klr-faq-item {
        border: 1px solid #e0e0e0;
        border-radius: 4px;
        overflow: hidden;
        background-color: #faf9f6;
        transition: border-color 0.3s ease;
    }

    .klr-faq-item.klr-faq-active {
        border-color: #111111;
    }

    .klr-faq-question {
        width: 100%;
        background: transparent;
        border: none;
        padding: 18px 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 14px;
        font-weight: 600;
        color: #1a1a1a;
        text-align: left;
        cursor: pointer;
        outline: none;
    }

    .klr-faq-icon {
        width: 18px;
        height: 18px;
        fill: none;
        stroke: #111111;
        stroke-width: 2;
        transition: transform 0.3s ease;
        flex-shrink: 0;
        margin-left: 10px;
    }

    .klr-faq-item.klr-faq-active .klr-faq-icon {
        transform: rotate(180deg);
    }

    .klr-faq-answer {
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.4s cubic-bezier(0.25, 1, 0.5, 1), padding 0.3s ease;
        padding: 0 20px;
        background-color: #ffffff;
    }

    .klr-faq-item.klr-faq-active .klr-faq-answer {
        max-height: 200px;
        padding: 16px 20px 20px 20px;
        border-top: 1px solid #eeeeee;
    }

    .klr-faq-answer p {
        font-size: 13px;
        line-height: 1.6;
        color: #555555;
        margin: 0;
    }

    @media (max-width: 600px) {
        .klr-faq-question {
            font-size: 13px;
            padding: 14px 16px;
        }
        .klr-faq-answer p {
            font-size: 12px;
        }
    }
</style>

<script>
    const klrFaqItems = document.querySelectorAll('.klr-faq-item');

    klrFaqItems.forEach(item => {
        const questionBtn = item.querySelector('.klr-faq-question');
        
        questionBtn.addEventListener('click', () => {
            // Check if current is already active
            const isActive = item.classList.contains('klr-faq-active');

            // Close all items first (optional: accordion style)
            klrFaqItems.forEach(otherItem => {
                otherItem.classList.remove('klr-faq-active');
            });

            // If it wasn't active, open it
            if (!isActive) {
                item.classList.add('klr-faq-active');
            }
        });
    });
</script>