<!-- ============ section 1 : HERO ============ -->
<style>
    .ka-hero {
        --k-green: #0f2b20;
        --k-green2: #17402f;
        --k-cream: #fcf5ea;
        --k-gold: #d4a85a;
        position: relative;
        overflow: hidden;
        min-height: 88vh;
        display: grid;
        grid-template-columns: 1.1fr .9fr;
        align-items: center;
        gap: 40px;
        padding: 100px 6vw 80px;
        background: radial-gradient(ellipse at 20% 10%, var(--k-green2), var(--k-green) 70%);
        color: var(--k-cream);
        font-family: 'Poppins', sans-serif
    }

    .ka-hero * {
        box-sizing: border-box
    }

    .ka-hero__tag {
        font-size: 12px;
        letter-spacing: .35em;
        color: var(--k-gold);
        text-transform: uppercase;
        opacity: 0;
        animation: kaUp .8s .1s forwards
    }

    .ka-hero__title {
        font-family: 'Cormorant Garamond', serif;
        font-weight: 600;
        font-size: clamp(44px, 7.4vw, 98px);
        line-height: 1.02;
        margin: 22px 0 24px
    }

    .ka-hero__title .ka-line {
        display: block;
        overflow: hidden
    }

    .ka-hero__title .ka-line span {
        display: block;
        transform: translateY(110%);
        animation: kaRise 1s cubic-bezier(.2, .7, .2, 1) forwards
    }

    .ka-hero__title .ka-line:nth-child(1) span {
        animation-delay: .3s
    }

    .ka-hero__title .ka-line:nth-child(2) span {
        animation-delay: .48s;
        color: var(--k-gold);
        font-style: italic
    }

    .ka-hero__title .ka-line:nth-child(3) span {
        animation-delay: .66s
    }

    .ka-hero__text {
        max-width: 480px;
        font-weight: 300;
        line-height: 1.9;
        font-size: 15px;
        color: rgba(252, 245, 234, .8);
        opacity: 0;
        animation: kaUp .8s 1.1s forwards
    }

    .ka-hero__art {
        position: relative;
        height: min(62vh, 520px);
        opacity: 0;
        animation: kaUp 1s .5s forwards
    }

    .ka-frame {
        position: absolute;
        border-radius: 200px 200px 14px 14px;
        border: 1px solid rgba(212, 168, 90, .55);
        overflow: hidden;
        will-change: transform
    }

    .ka-frame--a {
        inset: 0 18% 12% 0;
        background: linear-gradient(160deg, #1f5640, #0b1f17)
    }

    .ka-frame--b {
        inset: 22% 0 0 38%;
        background: linear-gradient(200deg, #e6c883, #a67a30);
        border-radius: 14px 14px 200px 200px
    }

    .ka-frame::after {
        content: "";
        position: absolute;
        inset: 0;
        background: radial-gradient(circle at 30% 20%, rgba(255, 255, 255, .22), transparent 55%)
    }

    .ka-hero__badge {
        position: absolute;
        left: 4%;
        bottom: 6%;
        z-index: 3;
        width: 120px;
        height: 120px;
        border-radius: 50%;
        background: var(--k-cream);
        color: var(--k-green);
        display: grid;
        place-items: center;
        text-align: center;
        font-family: 'Cormorant Garamond', serif;
        font-weight: 600;
        font-size: 15px;
        line-height: 1.2;
        box-shadow: 0 14px 30px -10px rgba(0, 0, 0, .5);
        animation: kaFloat 5s ease-in-out infinite
    }

    .ka-hero__badge b {
        display: block;
        font-size: 30px
    }

    @keyframes kaRise {
        to {
            transform: none
        }
    }

    @keyframes kaUp {
        from {
            opacity: 0;
            transform: translateY(18px)
        }

        to {
            opacity: 1;
            transform: none
        }
    }

    @keyframes kaFloat {
        50% {
            transform: translateY(-12px)
        }
    }

    @media(max-width:860px) {
        .ka-hero {
            grid-template-columns: 1fr;
            padding: 90px 22px 60px;
            min-height: auto
        }

        .ka-hero__art {
            height: 340px;
            order: -1;
            margin-bottom: 10px
        }
    }

    @media(prefers-reduced-motion:reduce) {
        .ka-hero * {
            animation-duration: .01s !important;
            animation-delay: 0s !important
        }
    }

    .asdjo54656 {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        display: block;
        object-fit: cover;
        object-position: center;
    }

    .ka-frame--a .asdjo54656 {
        object-position: center 30%;
    }

    .ka-frame--b .asdjo54656 {
        object-position: center center;
    }
</style>
<section class="ka-hero" id="kaHero">
    <div>
        <span class="ka-hero__tag">About Kalora</span>
        <h1 class="ka-hero__title" aria-label="Jewellery that feels like you">
            <span class="ka-line"><span>Jewellery</span></span>
            <span class="ka-line"><span>that feels</span></span>
            <span class="ka-line"><span>like you</span></span>
        </h1>
        <p class="ka-hero__text">Kalora makes everyday jewellery with a little drama in it. Bracelets, earrings and pendants that look special and stay easy on the pocket.</p>
    </div>
    <div class="ka-hero__art" aria-hidden="true">
        <!-- Replace these two frames with <img> or background-image of your products -->
        <div class="ka-frame ka-frame--a" data-speed="0.08">
            <img src="assets/imgas_kits/img_2.jpg" class="asdjo54656" alt="">
        </div>
        <div class="ka-frame ka-frame--b" data-speed="-0.06">
            <img src="assets/imgas_kits/751897933_18071749682430727_5760232853498588725_n.jpg" class="asdjo54656" alt="">
        </div>
        <div class="ka-hero__badge">
            <div><b>2+</b>years of<br>shine</div>
        </div>
    </div>
</section>
<script>
    (function() {
        var frames = document.querySelectorAll('#kaHero .ka-frame'),
            t = false;

        function run() {
            var y = window.pageYOffset;
            if (y < window.innerHeight * 1.3) frames.forEach(function(f) {
                f.style.translate = '0 ' + (y * parseFloat(f.dataset.speed) * -1) + 'px'
            });
            t = false
        }
        window.addEventListener('scroll', function() {
            if (!t) {
                requestAnimationFrame(run);
                t = true
            }
        }, {
            passive: true
        });
    })();
</script>