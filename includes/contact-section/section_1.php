<!-- ============ section 1 : HERO ============ -->
<style>
    .kc-hero {
        --k-green: #0f2b20;
        --k-green2: #17402f;
        --k-cream: #fcf5ea;
        --k-gold: #d4a85a;
        position: relative;
        overflow: hidden;
        min-height: 78vh;
        display: grid;
        place-items: center;
        text-align: center;
        padding: 110px 20px 90px;
        background: radial-gradient(ellipse at 70% 20%, var(--k-green2), var(--k-green) 65%);
        color: var(--k-cream);
        font-family: 'Poppins', sans-serif
    }

    .kc-hero * {
        box-sizing: border-box
    }

    .kc-progress {
        position: fixed;
        top: 0;
        left: 0;
        height: 3px;
        width: 0;
        background: linear-gradient(90deg, #b8863b, #f1d58f);
        z-index: 9999
    }

    .kc-hero__ring {
        position: absolute;
        border: 1px solid rgba(212, 168, 90, .35);
        border-radius: 50%;
        pointer-events: none;
        will-change: transform
    }

    .kc-hero__ring--a {
        width: 520px;
        height: 520px;
        right: -140px;
        top: -120px;
        animation: kcSpin 60s linear infinite
    }

    .kc-hero__ring--b {
        width: 340px;
        height: 340px;
        left: -100px;
        bottom: -110px;
        border-style: dashed;
        animation: kcSpin 45s linear infinite reverse
    }

    .kc-hero__ring::after {
        content: "";
        position: absolute;
        top: 8%;
        left: 50%;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: var(--k-gold);
        box-shadow: 0 0 18px var(--k-gold)
    }

    @keyframes kcSpin {
        to {
            transform: rotate(360deg)
        }
    }

    .kc-hero__inner {
        position: relative;
        z-index: 2;
        max-width: 760px
    }

    .kc-hero__tag {
        display: inline-block;
        font-size: 12px;
        letter-spacing: .4em;
        text-transform: uppercase;
        color: var(--k-gold);
        border: 1px solid rgba(212, 168, 90, .5);
        padding: 8px 20px;
        border-radius: 50px;
        opacity: 0;
        transform: translateY(14px);
        animation: kcUp .8s .1s forwards
    }

    .kc-hero__title {
        font-family: 'Cormorant Garamond', serif;
        font-weight: 600;
        font-size: clamp(46px, 9vw, 104px);
        line-height: 1;
        margin: 26px 0 20px;
        letter-spacing: .04em
    }

    .kc-hero__title span {
        display: inline-block;
        opacity: 0;
        transform: translateY(60%) rotate(4deg);
        animation: kcUp .9s forwards
    }

    .kc-hero__title .kc-gold {
        color: var(--k-gold);
        font-style: italic
    }

    .kc-hero__text {
        font-weight: 300;
        font-size: clamp(14px, 2vw, 17px);
        line-height: 1.8;
        max-width: 540px;
        margin: 0 auto 34px;
        color: rgba(252, 245, 234, .8);
        opacity: 0;
        animation: kcUp .8s .9s forwards
    }

    .kc-hero__btn {
        display: inline-block;
        padding: 14px 38px;
        border: 1px solid var(--k-gold);
        color: var(--k-cream);
        text-decoration: none;
        font-size: 13px;
        letter-spacing: .25em;
        text-transform: uppercase;
        position: relative;
        overflow: hidden;
        transition: color .4s;
        opacity: 0;
        animation: kcUp .8s 1.1s forwards
    }

    .kc-hero__btn::before {
        content: "";
        position: absolute;
        inset: 0;
        background: var(--k-gold);
        transform: translateX(-101%);
        transition: transform .45s ease;
        z-index: -1
    }

    .kc-hero__btn:hover {
        color: var(--k-green)
    }

    .kc-hero__btn:hover::before {
        transform: none
    }

    .kc-hero__scroll {
        position: absolute;
        bottom: 26px;
        left: 50%;
        width: 22px;
        height: 36px;
        border: 1px solid rgba(212, 168, 90, .6);
        border-radius: 20px;
        transform: translateX(-50%)
    }

    .kc-hero__scroll::after {
        content: "";
        position: absolute;
        top: 7px;
        left: 50%;
        width: 3px;
        height: 7px;
        margin-left: -1.5px;
        border-radius: 3px;
        background: var(--k-gold);
        animation: kcDot 1.8s infinite
    }

    @keyframes kcDot {
        0% {
            opacity: 1;
            transform: translateY(0)
        }

        100% {
            opacity: 0;
            transform: translateY(14px)
        }
    }

    @keyframes kcUp {
        to {
            opacity: 1;
            transform: none
        }
    }

    @media(max-width:600px) {
        .kc-hero {
            min-height: 70vh;
            padding: 90px 18px 80px
        }

        .kc-hero__ring--a {
            width: 340px;
            height: 340px
        }
    }

    @media(prefers-reduced-motion:reduce) {
        .kc-hero * {
            animation-duration: .01s !important;
            animation-delay: 0s !important
        }
    }
</style>
<section class="kc-hero" id="kcHero">
    <div class="kc-progress" id="kcProgress"></div>
    <div class="kc-hero__ring kc-hero__ring--a" data-speed="0.15"></div>
    <div class="kc-hero__ring kc-hero__ring--b" data-speed="-0.1"></div>
    <div class="kc-hero__inner" id="kcHeroInner">
        <span class="kc-hero__tag">Contact Kalora</span>
        <h1 class="kc-hero__title" aria-label="Let's talk jewellery">
            <span style="animation-delay:.3s">Let's</span>
            <span style="animation-delay:.45s">talk</span>
            <span class="kc-gold" style="animation-delay:.6s">jewellery</span>
        </h1>
        <p class="kc-hero__text">Order help, sizing, gifting or a custom piece. Write to us and our team replies within one working day.</p>
        <a href="about.php" class="kc-hero__btn">Write to us</a>
    </div>
    <div class="kc-hero__scroll"></div>
</section>
<script>
    (function() {
        var bar = document.getElementById('kcProgress'),
            rings = document.querySelectorAll('#kcHero .kc-hero__ring'),
            inner = document.getElementById('kcHeroInner'),
            ticking = false;

        function update() {
            var y = window.pageYOffset,
                h = document.documentElement.scrollHeight - window.innerHeight;
            bar.style.width = (h > 0 ? y / h * 100 : 0) + '%';
            if (y < window.innerHeight * 1.2) {
                rings.forEach(function(r) {
                    r.style.translate = '0 ' + (y * parseFloat(r.dataset.speed)) + 'px'
                });
                inner.style.transform = 'translateY(' + (y * 0.18) + 'px)';
                inner.style.opacity = Math.max(0, 1 - y / (window.innerHeight * 0.8));
            }
            ticking = false;
        }
        window.addEventListener('scroll', function() {
            if (!ticking) {
                requestAnimationFrame(update);
                ticking = true
            }
        }, {
            passive: true
        });
        update();
    })();
</script>