<?php
// 404 status code header.php se PEHLE set hona zaruri hai
http_response_code(404);
include_once 'includes/header.php';
?>

<style>
    main { overflow: hidden; }

    .nf-section {
        background: #fdf6ea;
        font-family: 'Times New Roman', Times, serif;
        color: #0f2d22;
        min-height: 70vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 64px 16px;
    }
    .nf-card {
        width: 100%;
        max-width: 560px;
        text-align: center;
        padding: 44px 28px 40px;
        border-radius: 14px;
        background: linear-gradient(180deg, #0f2d22 0%, #0a1f17 100%);
        box-shadow: 0 10px 30px rgba(15, 45, 34, .25);
        color: #fff;
    }
    .nf-icon { width: 120px; height: 120px; margin: 0 auto 8px; display: block; color: #e6c97a; }
    .nf-icon .nf-pendant { transform-box: fill-box; transform-origin: 50% 0; animation: nfSwing 3s ease-in-out infinite; }
    .nf-code {
        margin: 0;
        font-size: clamp(4rem, 14vw, 6.5rem);
        font-weight: 600;
        line-height: 1;
        letter-spacing: .08em;
        color: #e6c97a;
        text-shadow: 0 0 18px rgba(230, 201, 122, .35);
    }
    .nf-title { margin: 14px 0 8px; font-size: 1.6rem; font-weight: 400; }
    .nf-text { margin: 0 auto 28px; max-width: 420px; color: rgba(255, 255, 255, .75); font-size: 1.05rem; line-height: 1.5; }
    .nf-actions { display: flex; flex-wrap: wrap; gap: 12px; justify-content: center; }
    .nf-btn {
        display: inline-block;
        padding: 12px 24px;
        border-radius: 999px;
        font-family: inherit;
        font-size: 1rem;
        font-weight: 600;
        text-decoration: none;
        transition: transform .2s, background .2s, color .2s;
    }
    .nf-btn:hover { transform: translateY(-2px); }
    .nf-btn:focus-visible { outline: 3px solid #e6c97a; outline-offset: 3px; }
    .nf-btn-primary { background: #e6c97a; color: #0f2d22; }
    .nf-btn-primary:hover { background: #f1d98f; }
    .nf-btn-ghost { border: 1.5px solid #e6c97a; color: #e6c97a; }
    .nf-btn-ghost:hover { background: #e6c97a; color: #0f2d22; }

    @keyframes nfSwing { 0%, 100% { transform: rotate(-6deg); } 50% { transform: rotate(6deg); } }
    @media (prefers-reduced-motion: reduce) { .nf-icon .nf-pendant { animation: none; } }
</style>

<main>
    <section class="nf-section">
        <div class="nf-card">

            <!-- tooti hui chain + pendant ka icon -->
            <svg class="nf-icon" viewBox="0 0 120 120" fill="none" stroke="currentColor"
                 stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M14 20 Q30 8 46 22"></path>
                <path d="M106 20 Q90 8 74 22"></path>
                <g class="nf-pendant">
                    <circle cx="60" cy="30" r="5"></circle>
                    <path d="M60 35 V52"></path>
                    <path d="M60 52 L43 78 Q43 96 60 96 Q77 96 77 78 Z"></path>
                    <path d="M60 56 L51 78 M60 56 L69 78 M60 56 V94" stroke-width="1" opacity=".6"></path>
                </g>
            </svg>

            <p class="nf-code" aria-hidden="true">404</p>
            <h1 class="nf-title">Page not found</h1>
            <p class="nf-text">The page you are looking for may have been moved, renamed or does not exist.</p>

            <div class="nf-actions">
                <a href="index.php" class="nf-btn nf-btn-primary">Back to Home</a>
                
            </div>
        </div>
    </section>
</main>

<?php include_once 'includes/footter.php' ?>