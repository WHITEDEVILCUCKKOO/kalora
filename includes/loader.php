<!-- ============================================================
     KALORA Loader  (includes/loader.php)
     Isse header.php me <body> ke TURANT baad include karein:
         <?php include_once __DIR__ . '/loader.php'; ?>
     ============================================================ -->

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;600&family=Inter:wght@400&display=swap" rel="stylesheet">

<style>
/* ============================================================
   KALORA Loading Page
   Theme: Gold + Black Luxury
   ============================================================ */

/* Reset sirf loader ke andar (poori website par asar nahi) */
.loader-screen,
.loader-screen *,
.loader-screen *::before,
.loader-screen *::after {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

:root {
    --bg-deep: #0a0a0a;
    --bg-soft: #141414;
    --gold-light: #f4d47c;
    --gold: #d4af37;
    --gold-dark: #9a7b1f;
    --gold-deep: #6b540f;
    --cream: #faf6f0;
    --text-muted: rgba(212, 175, 55, 0.55);
}

/* Loading ke dauran site scroll na ho */
html.kalora-loading,
html.kalora-loading body {
    overflow: hidden;
}

/* ----- Loading Screen ----- */
.loader-screen {
    position: fixed;
    inset: 0;
    z-index: 99999;
    display: flex;
    align-items: center;
    justify-content: center;
    font-family: 'Inter', sans-serif;
    color: var(--gold-light);
    background:
        radial-gradient(ellipse at center, var(--bg-soft) 0%, var(--bg-deep) 70%),
        var(--bg-deep);
    overflow: hidden;
    transition: opacity 0.6s ease-out, visibility 0.6s ease-out;
}

.loader-screen.fade-out {
    opacity: 0;
    visibility: hidden;
}

/* ----- Mandala Background Pattern ----- */
.bg-pattern {
    position: absolute;
    inset: 0;
    overflow: hidden;
    pointer-events: none;
}

.mandala {
    position: absolute;
    width: 620px;
    height: 620px;
    max-width: 90vmin;
    max-height: 90vmin;
}

.mandala-1 {
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    animation: rotate-slow 60s linear infinite;
}

.mandala-2 {
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 820px;
    height: 820px;
    max-width: 120vmin;
    max-height: 120vmin;
    animation: rotate-reverse 90s linear infinite;
}

@keyframes rotate-slow {
    from { transform: translate(-50%, -50%) rotate(0deg); }
    to   { transform: translate(-50%, -50%) rotate(360deg); }
}

@keyframes rotate-reverse {
    from { transform: translate(-50%, -50%) rotate(360deg); }
    to   { transform: translate(-50%, -50%) rotate(0deg); }
}

/* ----- Vignette ----- */
.vignette {
    position: absolute;
    inset: 0;
    background: radial-gradient(ellipse at center, transparent 30%, rgba(0, 0, 0, 0.6) 100%);
    pointer-events: none;
    z-index: 2;
}

/* ----- Corner Ornaments ----- */
.corner-ornament {
    position: absolute;
    width: 90px;
    height: 90px;
    z-index: 3;
    opacity: 0.5;
}

.corner-ornament::before,
.corner-ornament::after {
    content: '';
    position: absolute;
    background: linear-gradient(135deg, var(--gold-light), var(--gold-dark));
}

.corner-ornament::before { width: 2px; height: 50px; }
.corner-ornament::after  { width: 50px; height: 2px; }

.top-left     { top: 32px;    left: 32px;  }
.top-left::before,  .top-left::after  { top: 0; left: 0; }

.top-right    { top: 32px;    right: 32px; }
.top-right::before, .top-right::after { top: 0; right: 0; }

.bottom-left  { bottom: 32px; left: 32px;  }
.bottom-left::before,  .bottom-left::after  { bottom: 0; left: 0; }

.bottom-right { bottom: 32px; right: 32px; }
.bottom-right::before, .bottom-right::after { bottom: 0; right: 0; }

/* ----- Center Content ----- */
.loader-content {
    position: relative;
    z-index: 5;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 1.4rem;
    text-align: center;
}

/* ----- Necklace ----- */
.necklace-wrapper {
    width: 180px;
    height: 220px;
    transform: translateY(-110vh) rotate(-8deg);
    animation: necklace-drop 1.8s cubic-bezier(0.34, 1.56, 0.64, 1) 0.3s forwards,
               necklace-sway 3s ease-in-out 2.1s infinite;
    filter: drop-shadow(0 8px 20px rgba(212, 175, 55, 0.35));
}

.necklace {
    width: 100%;
    height: 100%;
    display: block;
}

.necklace .pendant {
    animation: pendant-glow 2s ease-in-out 2s infinite;
    transform-origin: 100px 200px;
}

@keyframes necklace-drop {
    0%   { transform: translateY(-110vh) rotate(-12deg); }
    60%  { transform: translateY(20px) rotate(6deg); }
    75%  { transform: translateY(-10px) rotate(-3deg); }
    88%  { transform: translateY(5px) rotate(2deg); }
    100% { transform: translateY(0) rotate(0deg); }
}

@keyframes necklace-sway {
    0%, 100% { transform: translateY(0) rotate(-2deg); }
    50%      { transform: translateY(0) rotate(2deg); }
}

@keyframes pendant-glow {
    0%, 100% { filter: drop-shadow(0 0 4px rgba(244, 212, 124, 0.5)); }
    50%      { filter: drop-shadow(0 0 14px rgba(244, 212, 124, 0.95)); }
}

/* ----- KALORA Logo ----- */
.brand {
    font-family: 'Cormorant Garamond', serif;
    font-size: clamp(2.8rem, 8vw, 4.5rem);
    font-weight: 600;
    letter-spacing: 0.32em;
    padding-left: 0.32em;
    display: flex;
    color: var(--gold-light);
    text-shadow:
        0 0 14px rgba(244, 212, 124, 0.55),
        0 0 5px rgba(212, 175, 55, 0.8),
        0 2px 0 rgba(154, 123, 31, 0.45);
    animation: logo-glow 3s ease-in-out 2.5s infinite;
    margin-top: 0.3rem;
}

@keyframes logo-glow {
    0%, 100% {
        text-shadow:
            0 0 10px rgba(244, 212, 124, 0.4),
            0 0 3px rgba(212, 175, 55, 0.6),
            0 2px 0 rgba(154, 123, 31, 0.4);
    }
    50% {
        text-shadow:
            0 0 22px rgba(244, 212, 124, 0.85),
            0 0 8px rgba(255, 248, 220, 0.7),
            0 2px 0 rgba(154, 123, 31, 0.5);
    }
}

.logo-letter {
    display: inline-block;
    opacity: 0;
    transform: translateY(18px);
    animation: letter-rise 0.6s cubic-bezier(0.22, 1, 0.36, 1) forwards;
    animation-delay: calc(0.07s * var(--i) + 1.4s);
}

@keyframes letter-rise {
    to { opacity: 1; transform: translateY(0); }
}

/* ----- Tagline ----- */
.tagline {
    font-family: 'Inter', sans-serif;
    font-size: 0.82rem;
    font-weight: 400;
    letter-spacing: 0.28em;
    text-transform: uppercase;
    color: rgba(244, 212, 124, 0.75);
    text-shadow: 0 0 8px rgba(244, 212, 124, 0.3);
    opacity: 0;
    animation: fade-up 1s ease-out 2.1s forwards;
}

@keyframes fade-up {
    from { opacity: 0; transform: translateY(8px); }
    to   { opacity: 1; transform: translateY(0); }
}

/* ----- Progress Bar ----- */
.progress-track {
    width: 240px;
    height: 3px;
    background: rgba(212, 175, 55, 0.22);
    border-radius: 2px;
    overflow: hidden;
    margin-top: 0.3rem;
    opacity: 0;
    animation: fade-up 0.8s ease-out 1.8s forwards;
}

.progress-fill {
    width: 0%;
    height: 100%;
    background: linear-gradient(90deg, var(--gold-dark), var(--gold-light), var(--gold));
    box-shadow: 0 0 8px rgba(244, 212, 124, 0.7);
    transition: width 0.15s linear;
}

/* ----- Percentage ----- */
.progress-text {
    font-family: 'Cormorant Garamond', serif;
    font-size: 1rem;
    font-weight: 400;
    letter-spacing: 0.15em;
    color: var(--gold-light);
    opacity: 0;
    animation: fade-up 0.8s ease-out 1.8s forwards;
}

.percent-symbol {
    color: var(--text-muted);
    margin-left: 2px;
}

/* ----- Sparkles ----- */
.sparkles {
    position: absolute;
    inset: 0;
    pointer-events: none;
    z-index: 4;
}

.sparkle {
    position: absolute;
    width: 4px;
    height: 4px;
    background: var(--gold-light);
    border-radius: 50%;
    box-shadow: 0 0 6px var(--gold-light), 0 0 12px var(--gold);
    animation: sparkle-fade 1.4s ease-out forwards;
}

.sparkle::before,
.sparkle::after {
    content: '';
    position: absolute;
    background: linear-gradient(transparent, var(--gold-light), transparent);
}

.sparkle::before { width: 1px; height: 14px; left: 1.5px; top: -5px; }

.sparkle::after {
    width: 14px;
    height: 1px;
    top: 1.5px;
    left: -5px;
    background: linear-gradient(90deg, transparent, var(--gold-light), transparent);
}

@keyframes sparkle-fade {
    0%   { opacity: 0; transform: scale(0) rotate(0deg); }
    20%  { opacity: 1; transform: scale(1) rotate(45deg); }
    100% { opacity: 0; transform: scale(0.3) rotate(90deg); }
}

/* ----- Responsive ----- */
@media (max-width: 600px) {
    .necklace-wrapper { width: 130px; height: 160px; }
    .brand { font-size: 2.4rem; letter-spacing: 0.26em; padding-left: 0.26em; }
    .progress-track { width: 170px; }
    .corner-ornament { width: 60px; height: 60px; }
    .corner-ornament::before { height: 32px; }
    .corner-ornament::after  { width: 32px; }
    .top-left, .top-right       { top: 20px; }
    .bottom-left, .bottom-right { bottom: 20px; }
    .top-left, .bottom-left     { left: 20px; }
    .top-right, .bottom-right   { right: 20px; }
}

/* ----- Reduced motion ----- */
@media (prefers-reduced-motion: reduce) {
    .mandala-1, .mandala-2 { animation: none; }
    .necklace-wrapper { animation: fade-up 0.8s ease-out 0.2s forwards; }
    .brand { animation: none; }
    .necklace .pendant { animation: none; }
}
</style>

<!-- Loading ke dauran scroll band (loader hone par wapas chalu ho jata hai) -->
<script>document.documentElement.classList.add('kalora-loading');</script>

<!-- ===== Loading Screen ===== -->
<div class="loader-screen" id="loaderScreen">

    <!-- Mandala pattern background -->
    <div class="bg-pattern" aria-hidden="true">
        <svg class="mandala mandala-1" viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
            <g fill="none" stroke="#d4af37" stroke-width="0.6" opacity="0.18">
                <circle cx="100" cy="100" r="90" />
                <circle cx="100" cy="100" r="75" />
                <circle cx="100" cy="100" r="60" />
                <circle cx="100" cy="100" r="45" />
                <circle cx="100" cy="100" r="30" />
                <circle cx="100" cy="100" r="15" />
                <g class="petals">
                    <path d="M100 10 Q110 50 100 90 Q90 50 100 10" />
                    <path d="M100 10 Q110 50 100 90 Q90 50 100 10" transform="rotate(30 100 100)" />
                    <path d="M100 10 Q110 50 100 90 Q90 50 100 10" transform="rotate(60 100 100)" />
                    <path d="M100 10 Q110 50 100 90 Q90 50 100 10" transform="rotate(90 100 100)" />
                    <path d="M100 10 Q110 50 100 90 Q90 50 100 10" transform="rotate(120 100 100)" />
                    <path d="M100 10 Q110 50 100 90 Q90 50 100 10" transform="rotate(150 100 100)" />
                    <path d="M100 10 Q110 50 100 90 Q90 50 100 10" transform="rotate(180 100 100)" />
                    <path d="M100 10 Q110 50 100 90 Q90 50 100 10" transform="rotate(210 100 100)" />
                    <path d="M100 10 Q110 50 100 90 Q90 50 100 10" transform="rotate(240 100 100)" />
                    <path d="M100 10 Q110 50 100 90 Q90 50 100 10" transform="rotate(270 100 100)" />
                    <path d="M100 10 Q110 50 100 90 Q90 50 100 10" transform="rotate(300 100 100)" />
                    <path d="M100 10 Q110 50 100 90 Q90 50 100 10" transform="rotate(330 100 100)" />
                </g>
                <line x1="10" y1="100" x2="190" y2="100" />
                <line x1="100" y1="10" x2="100" y2="190" />
                <line x1="30" y1="30" x2="170" y2="170" />
                <line x1="170" y1="30" x2="30" y2="170" />
            </g>
        </svg>
        <svg class="mandala mandala-2" viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
            <g fill="none" stroke="#d4af37" stroke-width="0.5" opacity="0.12">
                <circle cx="100" cy="100" r="95" />
                <circle cx="100" cy="100" r="70" />
                <circle cx="100" cy="100" r="40" />
                <g>
                    <path d="M100 5 L105 25 L100 45 L95 25 Z" />
                    <path d="M100 5 L105 25 L100 45 L95 25 Z" transform="rotate(45 100 100)" />
                    <path d="M100 5 L105 25 L100 45 L95 25 Z" transform="rotate(90 100 100)" />
                    <path d="M100 5 L105 25 L100 45 L95 25 Z" transform="rotate(135 100 100)" />
                    <path d="M100 5 L105 25 L100 45 L95 25 Z" transform="rotate(180 100 100)" />
                    <path d="M100 5 L105 25 L100 45 L95 25 Z" transform="rotate(225 100 100)" />
                    <path d="M100 5 L105 25 L100 45 L95 25 Z" transform="rotate(270 100 100)" />
                    <path d="M100 5 L105 25 L100 45 L95 25 Z" transform="rotate(315 100 100)" />
                </g>
            </g>
        </svg>
    </div>

    <!-- Sparkle particles container -->
    <div class="sparkles" id="sparkles" aria-hidden="true"></div>

    <!-- Center content -->
    <div class="loader-content">

        <!-- Necklace SVG (drops from top) -->
        <div class="necklace-wrapper" id="necklaceWrapper">
            <svg class="necklace" viewBox="0 0 200 240" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <linearGradient id="goldGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                        <stop offset="0%" stop-color="#f4d47c" />
                        <stop offset="50%" stop-color="#d4af37" />
                        <stop offset="100%" stop-color="#9a7b1f" />
                    </linearGradient>
                    <linearGradient id="gemGrad" x1="0%" y1="0%" x2="0%" y2="100%">
                        <stop offset="0%" stop-color="#fff4d6" />
                        <stop offset="40%" stop-color="#f4d47c" />
                        <stop offset="100%" stop-color="#b8860b" />
                    </linearGradient>
                    <radialGradient id="gemShine" cx="35%" cy="30%" r="40%">
                        <stop offset="0%" stop-color="#ffffff" stop-opacity="0.9" />
                        <stop offset="100%" stop-color="#ffffff" stop-opacity="0" />
                    </radialGradient>
                    <filter id="glow">
                        <feGaussianBlur stdDeviation="2.5" result="blur" />
                        <feMerge>
                            <feMergeNode in="blur" />
                            <feMergeNode in="SourceGraphic" />
                        </feMerge>
                    </filter>
                </defs>

                <!-- Chain -->
                <path d="M100 30 Q40 90 100 170" fill="none" stroke="url(#goldGrad)" stroke-width="2.2" stroke-linecap="round" filter="url(#glow)" />
                <path d="M100 30 Q160 90 100 170" fill="none" stroke="url(#goldGrad)" stroke-width="2.2" stroke-linecap="round" filter="url(#glow)" />

                <!-- Beads -->
                <g fill="url(#goldGrad)" filter="url(#glow)">
                    <circle cx="80" cy="62" r="1.6" />
                    <circle cx="65" cy="88" r="1.6" />
                    <circle cx="62" cy="115" r="1.6" />
                    <circle cx="72" cy="142" r="1.6" />
                    <circle cx="120" cy="62" r="1.6" />
                    <circle cx="135" cy="88" r="1.6" />
                    <circle cx="138" cy="115" r="1.6" />
                    <circle cx="128" cy="142" r="1.6" />
                </g>

                <!-- Pendant bail -->
                <ellipse cx="100" cy="170" rx="6" ry="9" fill="none" stroke="url(#goldGrad)" stroke-width="2" filter="url(#glow)" />

                <!-- Pendant -->
                <g class="pendant" filter="url(#glow)">
                    <path d="M100 178 L75 215 Q75 232 100 232 Q125 232 125 215 Z" fill="none" stroke="url(#goldGrad)" stroke-width="2.2" />
                    <path d="M100 182 L79 216 Q79 228 100 228 Q121 228 121 216 Z" fill="url(#gemGrad)" />
                    <path d="M100 182 L89 215 M100 182 L111 215 M100 182 L100 228" fill="none" stroke="#9a7b1f" stroke-width="0.6" opacity="0.5" />
                    <ellipse cx="91" cy="200" rx="6" ry="9" fill="url(#gemShine)" />
                </g>

                <!-- Clasp ring -->
                <circle cx="100" cy="28" r="4" fill="none" stroke="url(#goldGrad)" stroke-width="2" filter="url(#glow)" />
            </svg>
        </div>

        <!-- KALORA Logo -->
        <div class="brand" id="brand">
            <span class="logo-letter" style="--i:0">K</span>
            <span class="logo-letter" style="--i:1">A</span>
            <span class="logo-letter" style="--i:2">L</span>
            <span class="logo-letter" style="--i:3">O</span>
            <span class="logo-letter" style="--i:4">R</span>
            <span class="logo-letter" style="--i:5">A</span>
        </div>

        <p class="tagline" id="tagline">Kaur Collection</p>

        <!-- Progress bar -->
        <div class="progress-track">
            <div class="progress-fill" id="progressFill"></div>
        </div>

        <!-- Percentage -->
        <div class="progress-text">
            <span id="percentText">0</span><span class="percent-symbol">%</span>
        </div>
    </div>

    <!-- Corner ornaments -->
    <div class="corner-ornament top-left" aria-hidden="true"></div>
    <div class="corner-ornament top-right" aria-hidden="true"></div>
    <div class="corner-ornament bottom-left" aria-hidden="true"></div>
    <div class="corner-ornament bottom-right" aria-hidden="true"></div>

    <!-- Vignette -->
    <div class="vignette" aria-hidden="true"></div>
</div>

<script>
/* ============================================================
   KALORA Loader Script
   - % asli loading se judha hai (images, stylesheets, fonts)
   - window load ke baad 3 second ruk kar site dikhata hai
   ============================================================ */
(function () {
    'use strict';

    // ---- Config ----
    var HOLD_AFTER_LOAD  = 3000;    // page load hone ke baad 3s ruko, phir site dikhao
    var MAX_WAIT         = 30000;   // failsafe: 30s me bhi load na ho to loader hata do
    var SPARKLE_INTERVAL = 280;
    var SPARKLE_START    = 1800;

    // ---- Elements ----
    var root         = document.documentElement;
    var loaderScreen = document.getElementById('loaderScreen');
    var progressFill = document.getElementById('progressFill');
    var percentText  = document.getElementById('percentText');
    var sparklesEl   = document.getElementById('sparkles');

    // ---- State ----
    var domReady = false, windowLoaded = false, finished = false;
    var total = 0, loadedCount = 0;
    var shown = 0;                   // screen par dikhne wala %

    // ---- Real loading track ----
    function watch(el, alreadyDone) {
        total++;
        if (alreadyDone) { loadedCount++; return; }
        var fired = false;
        function ok() { if (!fired) { fired = true; loadedCount++; } }
        el.addEventListener('load', ok);
        el.addEventListener('error', ok);
    }

    function scan() {
        document.querySelectorAll('img').forEach(function (img) {
            if (img.loading === 'lazy') return;      // lazy images window load ko nahi rokti
            watch(img, img.complete);
        });
        document.querySelectorAll('link[rel="stylesheet"]').forEach(function (l) {
            watch(l, !!l.sheet);
        });
        if (document.fonts && document.fonts.ready) {
            total++;
            document.fonts.ready.then(function () { loadedCount++; });
        }
    }

    function getTarget() {
        if (windowLoaded) return 100;
        if (!domReady) return 10;                    // HTML abhi aa raha hai
        var ratio = total ? loadedCount / total : 1;
        return Math.min(25 + 70 * ratio, 95);        // 95 par ruka rahega jab tak window load na ho
    }

    // ---- % animation (real value ki taraf smoothly) ----
    function tick() {
        if (finished) return;
        var target = getTarget();
        if (shown < target) {
            shown += Math.max((target - shown) * 0.08, 0.25);
            if (shown > target) shown = target;
        }
        var p = Math.round(shown);
        progressFill.style.width = p + '%';
        percentText.textContent = p;
        requestAnimationFrame(tick);
    }

    // ---- Sparkles ----
    var sparkleTimer = null;

    function createSparkle() {
        var sparkle = document.createElement('div');
        sparkle.className = 'sparkle';
        var cx = window.innerWidth / 2;
        var cy = window.innerHeight / 2 + 60;
        var angle = Math.random() * Math.PI * 2;
        var radius = 20 + Math.random() * 70;
        sparkle.style.left = (cx + Math.cos(angle) * radius) + 'px';
        sparkle.style.top  = (cy + Math.sin(angle) * radius) + 'px';
        sparkle.style.transform = 'scale(' + (0.6 + Math.random() * 0.9) + ')';
        sparklesEl.appendChild(sparkle);
        setTimeout(function () {
            if (sparkle.parentNode) sparkle.parentNode.removeChild(sparkle);
        }, 1500);
    }

    function startSparkles() {
        if (finished || sparkleTimer) return;
        createSparkle();
        sparkleTimer = setInterval(createSparkle, SPARKLE_INTERVAL);
    }

    function stopSparkles() {
        if (sparkleTimer) { clearInterval(sparkleTimer); sparkleTimer = null; }
    }

    // ---- Finish ----
    function finish() {
        if (finished) return;
        finished = true;
        stopSparkles();
        progressFill.style.width = '100%';
        percentText.textContent = '100';
        loaderScreen.classList.add('fade-out');
        root.classList.remove('kalora-loading');     // scroll wapas chalu
        setTimeout(function () {
            if (loaderScreen.parentNode) loaderScreen.parentNode.removeChild(loaderScreen);
        }, 700);
    }

    // ---- Events ----
    function onDomReady() {
        if (domReady) return;
        domReady = true;
        scan();
    }
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', onDomReady);
    } else {
        onDomReady();
    }

    function onWindowLoad() {
        windowLoaded = true;                         // % ab 100 ki taraf jayega
        setTimeout(finish, HOLD_AFTER_LOAD);         // 3s baad site dikhao
    }
    if (document.readyState === 'complete') {
        onWindowLoad();
    } else {
        window.addEventListener('load', onWindowLoad);
    }

    setTimeout(finish, MAX_WAIT);                    // failsafe
    setTimeout(startSparkles, SPARKLE_START);
    requestAnimationFrame(tick);

    window.KALORA_Loader = { hide: finish };
})();
</script>