<div class="klr-vid-wrapper">
    <div class="klr-vid-container">
        
        <!-- Top Subtitle / Tagline -->
        <h4 class="klr-vid-top-title">BECAUSE YOU DESERVE TO SHINE</h4>

        <!-- Video Banner Box -->
        <div class="klr-vid-player-box">
            <!-- Aap yahan apni video ka src daal sakte hain, jaise: src="your-video.mp4" -->
            <video class="klr-vid-element" autoplay muted loop playsinline poster="https://images.unsplash.com/photo-1515562141207-7a88fb7ce338?q=80&w=1200&auto=format&fit=crop">
                <source src="assets/videos/7c35e328e36c8dac04d61901ce4e38c7.mp4" type="video/mp4">
                Your browser does not support the video tag.
            </video>

            <!-- Overlay & Center Brand Logo / Text -->
            <div class="klr-vid-overlay">
                <h2 class="klr-vid-brand-logo">KALORA</h2>
            </div>

           
        </div>

    </div>
</div>

<style>
    .klr-vid-wrapper {
        font-family: Arial, sans-serif;
        /* background-color: #ffffff; */
        background: #fdf6ea !important; 
        color: #111111;
        padding: 40px 0px;
        box-sizing: border-box;
        width: 100%;
    }

    .klr-vid-container {
        /* max-width: 1300px; */
        margin: 0 auto;
        display: flex;
        flex-direction: column;
        align-items: center;
        box-sizing: border-box;
    }

    .klr-vid-top-title {
        font-size: 13px;
        letter-spacing: 3.5px;
        font-weight: 500;
        color: #222222;
        margin: 0 0 25px 0;
        text-align: center;
    }

    .klr-vid-player-box {
        position: relative;
        width: 100%;
        height: 600px;
        /* border-radius: 8px; */
        overflow: hidden;
        background-color: #111111;
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.12);
        box-sizing: border-box;
    }

    .klr-vid-element {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .klr-vid-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.25); /* Light dark tint for text clarity */
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .klr-vid-brand-logo {
        color: #ffffff;
        font-size: 38px;
        font-weight: 400;
        letter-spacing: 8px;
        margin: 0;
        text-transform: uppercase;
        text-shadow: 0 4px 15px rgba(0,0,0,0.3);
    }

    .klr-vid-scroll-btn {
        position: absolute;
        bottom: 25px;
        left: 50%;
        transform: translateX(-50%);
        width: 44px;
        height: 44px;
        background-color: #ffffff;
        border: none;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        transition: transform 0.3s ease, background-color 0.3s ease;
        z-index: 3;
    }

    .klr-vid-scroll-btn:hover {
        background-color: #f0f0f0;
        transform: translateX(-50%) translateY(-3px);
    }

    .klr-vid-scroll-btn svg {
        width: 18px;
        height: 18px;
        fill: none;
        stroke: #111111;
        stroke-width: 2.2;
    }

    /* Fully Responsive Breakpoints for Mobile & Tablet */
    @media (max-width: 1024px) {
        .klr-vid-player-box {
            height: 450px;
        }
        .klr-vid-brand-logo {
            font-size: 30px;
            letter-spacing: 6px;
        }
    }

    @media (max-width: 600px) {
        .klr-vid-wrapper {
            padding: 20px 10px;
        }
        .klr-vid-player-box {
            height: 320px;
            border-radius: 6px;
        }
        .klr-vid-top-title {
            font-size: 11px;
            letter-spacing: 2px;
            margin-bottom: 15px;
        }
        .klr-vid-brand-logo {
            font-size: 22px;
            letter-spacing: 4px;
        }
        .klr-vid-scroll-btn {
            width: 36px;
            height: 36px;
            bottom: 15px;
        }
        .klr-vid-scroll-btn svg {
            width: 14px;
            height: 14px;
        }
    }
</style>