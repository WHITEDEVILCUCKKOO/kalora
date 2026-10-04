<?php
include "admin_access/db_config.php";

?>

<div class="fey-wrapper">
    <div class="fey-container">

        <div class="fey-carousel-stage" id="feyStage">
            <button class="fey-arrow fey-arrow-left" id="feyPrevBtn" aria-label="Previous">
                <svg viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                </svg>
            </button>

            <div class="fey-cards-container" id="feyCardsContainer">

                <?php
                $qasdas = "SELECT * FROM `products` ORDER BY RAND() LIMIT 6";
                $result = mysqli_query($mydb, $qasdas);
                ?>

                <?php if ($result && mysqli_num_rows($result) > 0): ?>

                    <?php $index = 0; ?>

                    <?php while ($product = mysqli_fetch_assoc($result)): ?>

                        <div class="fey-card" data-index="<?php echo $index; ?>">

                            <img
                                src="<?php echo htmlspecialchars($product['product_image'] ?? ''); ?>"
                                alt="<?php echo htmlspecialchars($product['product_name'] ?? 'Product'); ?>"
                                draggable="false">

                            <div class="fey-overlay">
                                <span class="fey-card-title">
                                    <?php echo htmlspecialchars($product['product_name'] ?? 'Product'); ?>
                                </span>
                            </div>

                        </div>

                        <?php $index++; ?>

                    <?php endwhile; ?>

                <?php endif; ?>

              
            </div>

            <button class="fey-arrow fey-arrow-right" id="feyNextBtn" aria-label="Next">
                <svg viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                </svg>
            </button>
        </div>

    </div>
</div>

<style>
    .fey-wrapper {
        font-family: Arial, sans-serif;
        /* background-color: #ffffff; */
        background: #fdf6ea !important;
        color: #111111;
        padding: 60px 0;
        box-sizing: border-box;
        width: 100%;
        overflow: hidden;
        user-select: none;
    }

    .fey-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 20px;
        box-sizing: border-box;
    }

    .fey-carousel-stage {
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        min-height: 520px;
    }

    .fey-cards-container {
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        width: 100%;
        height: 500px;
        perspective: 1000px;
        cursor: grab;
        touch-action: pan-y;
    }

    .fey-cards-container:active {
        cursor: grabbing;
    }

    .fey-card {
        position: absolute;
        width: 340px;
        height: 460px;
        border-radius: 4px;
        overflow: hidden;
        background-color: #eaeaea;
        transition: transform 0.6s cubic-bezier(0.25, 1, 0.5, 1), opacity 0.6s ease, filter 0.6s ease, width 0.6s ease, height 0.6s ease;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.12);
        opacity: 0.4;
        filter: brightness(0.7);
        pointer-events: none;
        will-change: transform, opacity;
    }

    .fey-card img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        pointer-events: none;
    }

    /* Active Center Card Style */
    .fey-card.fey-active {
        width: 400px;
        height: 500px;
        z-index: 5;
        opacity: 1;
        filter: brightness(1);
        transform: translateX(0) scale(1) rotate(0);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.25);
        pointer-events: auto;
    }

    /* Left Side Card (Properly rotated/placed to the left) */
    .fey-card.fey-prev {
        z-index: 3;
        opacity: 0.85;
        filter: brightness(0.9);
        transform: translateX(-340px) scale(0.88) rotate3d(0, 1, 0, 30deg);
        pointer-events: auto;
    }

    /* Right Side Card (Properly rotated/placed to the right) */
    .fey-card.fey-next {
        z-index: 3;
        opacity: 0.85;
        filter: brightness(0.9);
        transform: translateX(340px) scale(0.88) rotate3d(0, 1, 0, -30deg);
        pointer-events: auto;
    }

    /* Far Left / Right Hidden or Peek Cards */
    .fey-card.fey-far-prev {
        z-index: 1;
        opacity: 0.2;
        transform: translateX(-620px) scale(0.75) rotate3d(0, 1, 0, 10deg);
    }

    .fey-card.fey-far-next {
        z-index: 1;
        opacity: 0.2;
        transform: translateX(620px) scale(0.75) rotate3d(0, 1, 0, -10deg);
    }

    .fey-overlay {
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 35%;
        background: linear-gradient(to top, rgba(0, 0, 0, 0.65) 0%, rgba(0, 0, 0, 0) 100%);
        display: flex;
        align-items: flex-end;
        justify-content: center;
        padding-bottom: 25px;
    }

    .fey-card-title {
        color: #ffffff;
        font-size: 13px;
        letter-spacing: 3px;
        font-weight: 500;
        text-align: center;
        /* border-bottom: 1.5px solid #ffffff; */
        padding-bottom: 4px;
        text-transform: uppercase;
    }

    /* Navigation Arrows */
    .fey-arrow {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        background: #ffffff;
        border: 1px solid #dcdcdc;
        width: 44px;
        height: 44px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        z-index: 20;
        transition: background-color 0.2s ease, color 0.2s ease, transform 0.2s ease;
    }

    .fey-arrow:hover {
        background-color: #111111;
        color: #ffffff;
        border-color: #111111;
        transform: translateY(-50%) scale(1.08);
    }

    .fey-arrow svg {
        width: 18px;
        height: 18px;
        fill: none;
        stroke: currentColor;
        stroke-width: 2;
    }

    .fey-arrow-left {
        left: 20px;
    }

    .fey-arrow-right {
        right: 20px;
    }

    @media (max-width: 1024px) {
        .fey-card.fey-prev {
            transform: translateX(-260px) scale(0.85);
        }

        .fey-card.fey-next {
            transform: translateX(260px) scale(0.85);
        }

        .fey-card.fey-active {
            width: 320px;
            height: 440px;
        }
    }

    @media (max-width: 768px) {

        .fey-card.fey-prev,
        .fey-card.fey-far-prev {
            transform: translateX(-180px) scale(0.75);
            opacity: 0;
        }

        .fey-card.fey-next,
        .fey-card.fey-far-next {
            transform: translateX(180px) scale(0.75);
            opacity: 0;
        }

        .fey-card.fey-active {
            width: 90vw;
            height: 400px;
        }

        .fey-container{
            padding: 0 5px;
        }
    }
</style>

<script>
    const feyCards = document.querySelectorAll('.fey-card');
    const feyPrevBtn = document.getElementById('feyPrevBtn');
    const feyNextBtn = document.getElementById('feyNextBtn');
    const feyStage = document.getElementById('feyStage');
    const feyCardsContainer = document.getElementById('feyCardsContainer');

    let feyCurrentIndex = 2;
    let feyAutoPlayTimer;

    let feyStartX = 0;
    let feyIsDragging = false;
    let feyThreshold = 40; // Balanced threshold for precise control

    function feyUpdateCarousel() {
        feyCards.forEach((card, index) => {
            card.className = 'fey-card';

            let total = feyCards.length;
            let diff = (index - feyCurrentIndex + total) % total;

            if (diff === 0) {
                card.classList.add('fey-active');
            } else if (diff === 1 || diff === -(total - 1)) {
                card.classList.add('fey-next');
            } else if (diff === total - 1 || diff === -1) {
                card.classList.add('fey-prev');
            } else if (diff === 2) {
                card.classList.add('fey-far-next');
            } else {
                card.classList.add('fey-far-prev');
            }
        });
    }

    function feyNextSlide() {
        feyCurrentIndex = (feyCurrentIndex + 1) % feyCards.length;
        feyUpdateCarousel();
    }

    function feyPrevSlide() {
        feyCurrentIndex = (feyCurrentIndex - 1 + feyCards.length) % feyCards.length;
        feyUpdateCarousel();
    }

    feyNextBtn.addEventListener('click', () => {
        feyNextSlide();
        feyResetAutoPlay();
    });

    feyPrevBtn.addEventListener('click', () => {
        feyPrevSlide();
        feyResetAutoPlay();
    });

    // Clean and controlled Drag & Swipe handlers (Fixed speed/trigger issue)
    feyCardsContainer.addEventListener('mousedown', (e) => {
        feyIsDragging = true;
        feyStartX = e.clientX;
        clearInterval(feyAutoPlayTimer);
    });

    window.addEventListener('mouseup', (e) => {
        if (!feyIsDragging) return;
        let feyDiffX = e.clientX - feyStartX;

        if (Math.abs(feyDiffX) > feyThreshold) {
            if (feyDiffX > 0) {
                feyPrevSlide(); // Right drag -> Previous slide
            } else {
                feyNextSlide(); // Left drag -> Next slide
            }
        }
        feyIsDragging = false;
        feyStartAutoPlay();
    });

    // Touch Support for Mobile
    feyCardsContainer.addEventListener('touchstart', (e) => {
        feyStartX = e.touches[0].clientX;
        clearInterval(feyAutoPlayTimer);
    }, {
        passive: true
    });

    feyCardsContainer.addEventListener('touchend', (e) => {
        let feyDiffX = e.changedTouches[0].clientX - feyStartX;

        if (Math.abs(feyDiffX) > feyThreshold) {
            if (feyDiffX > 0) {
                feyPrevSlide();
            } else {
                feyNextSlide();
            }
        }
        feyStartAutoPlay();
    });

    feyCards.forEach((card, index) => {
        card.addEventListener('click', () => {
            feyCurrentIndex = index;
            feyUpdateCarousel();
            feyResetAutoPlay();
        });
    });

    function feyStartAutoPlay() {
        feyAutoPlayTimer = setInterval(feyNextSlide, 4000);
    }

    function feyResetAutoPlay() {
        clearInterval(feyAutoPlayTimer);
        feyStartAutoPlay();
    }

    feyStage.addEventListener('mouseenter', () => clearInterval(feyAutoPlayTimer));
    feyStage.addEventListener('mouseleave', feyStartAutoPlay);

    feyUpdateCarousel();
    feyStartAutoPlay();
</script>