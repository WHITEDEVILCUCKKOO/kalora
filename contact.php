<?php include_once 'includes/header.php' ?>

<style>
    main {
        overflow: hidden;
    }
</style>


<!--
=======================================================================================================================
                                                        Main
=======================================================================================================================
 -->
<main>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;600;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

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
        <a href="#kcForm" class="kc-hero__btn">Write to us</a>
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

<!-- ============ section 2 : CONTACT INFO CARDS ============ -->
<style>
    .kc-info {
        --k-green: #0f2b20;
        --k-cream: #fcf5ea;
        --k-gold: #d4a85a;
        --k-ink: #2b2b2b;
        background: var(--k-cream);
        padding: 90px 20px;
        font-family: 'Poppins', sans-serif;
        color: var(--k-ink)
    }

    .kc-info * {
        box-sizing: border-box
    }

    .kc-info__wrap {
        max-width: 1180px;
        margin: 0 auto
    }

    .kc-info__head {
        text-align: center;
        margin-bottom: 54px
    }

    .kc-info__head h2 {
        font-family: 'Cormorant Garamond', serif;
        font-size: clamp(30px, 5vw, 48px);
        margin: 0 0 10px;
        color: var(--k-green);
        letter-spacing: .06em
    }

    .kc-info__head p {
        margin: 0;
        font-weight: 300;
        color: #6b6b6b
    }

    .kc-info__line {
        display: block;
        height: 1px;
        width: 0;
        background: var(--k-gold);
        margin: 18px auto 0;
        transition: width 1.1s ease
    }

    .kc-info.is-in .kc-info__line {
        width: 120px
    }

    .kc-info__grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 22px;
        perspective: 900px
    }

    .kc-card {
        background: var(--k-green);
        color: var(--k-cream);
        border-radius: 14px;
        padding: 34px 26px;
        text-decoration: none;
        display: block;
        position: relative;
        overflow: hidden;
        clip-path: inset(0 0 100% 0);
        transition: clip-path .9s cubic-bezier(.2, .7, .2, 1), transform .25s ease, box-shadow .3s;
        border: 1px solid rgba(212, 168, 90, .25);
        transition-delay: calc(var(--i)*.12s), 0s, 0s;
        will-change: transform
    }

    .kc-info.is-in .kc-card {
        clip-path: inset(0 0 0 0)
    }

    .kc-card:hover {
        box-shadow: 0 22px 40px -18px rgba(15, 43, 32, .7)
    }

    .kc-card::after {
        content: "";
        position: absolute;
        width: 180px;
        height: 180px;
        border-radius: 50%;
        right: -70px;
        top: -70px;
        background: radial-gradient(circle, rgba(212, 168, 90, .35), transparent 70%);
        transition: transform .6s
    }

    .kc-card:hover::after {
        transform: scale(1.8)
    }

    .kc-card__icon {
        width: 52px;
        height: 52px;
        border-radius: 50%;
        border: 1px solid var(--k-gold);
        display: grid;
        place-items: center;
        margin-bottom: 22px;
        color: var(--k-gold)
    }

    .kc-card__icon svg {
        width: 22px;
        height: 22px;
        stroke: currentColor;
        fill: none;
        stroke-width: 1.6;
        stroke-linecap: round;
        stroke-linejoin: round
    }

    .kc-card h3 {
        font-family: 'Cormorant Garamond', serif;
        font-size: 24px;
        margin: 0 0 8px;
        letter-spacing: .05em
    }

    .kc-card p {
        margin: 0 0 14px;
        font-size: 13px;
        font-weight: 300;
        line-height: 1.7;
        color: rgba(252, 245, 234, .75)
    }

    .kc-card strong {
        font-weight: 500;
        font-size: 14px;
        color: var(--k-gold);
        word-break: break-word
    }

    @media(max-width:1000px) {
        .kc-info__grid {
            grid-template-columns: repeat(2, 1fr)
        }
    }

    @media(max-width:560px) {
        .kc-info {
            padding: 64px 16px
        }

        .kc-info__grid {
            grid-template-columns: 1fr
        }
    }

    @media(prefers-reduced-motion:reduce) {
        .kc-card {
            clip-path: none !important;
            transition: none
        }
    }
</style>
<section class="kc-info" id="kcInfo">
    <div class="kc-info__wrap">
        <div class="kc-info__head">
            <h2>Reach us your way</h2>
            <p>Pick the channel that suits you best.</p>
            <span class="kc-info__line"></span>
        </div>
        <div class="kc-info__grid">
            <a class="kc-card" style="--i:0" href="https://wa.me/910000000000" target="_blank" rel="noopener">
                <div class="kc-card__icon"><svg viewBox="0 0 24 24">
                        <path d="M21 12a9 9 0 1 1-4-7.5L21 3l-1 4.5A9 9 0 0 1 21 12z" />
                        <path d="M9 9c0 3 3 6 6 6l1.5-1.5-2-1-1 .8c-1-.4-2-1.4-2.4-2.4l.8-1-1-2z" />
                    </svg></div>
                <h3>WhatsApp</h3>
                <p>Quickest way to check order status or get styling help.</p><strong>+91 00000 00000</strong>
            </a>
            <a class="kc-card" style="--i:1" href="mailto:support@kalora.in">
                <div class="kc-card__icon"><svg viewBox="0 0 24 24">
                        <rect x="3" y="5" width="18" height="14" rx="2" />
                        <path d="m3 7 9 6 9-6" />
                    </svg></div>
                <h3>Email</h3>
                <p>For returns, exchanges, bulk orders and collaborations.</p><strong>support@kalora.in</strong>
            </a>
            <a class="kc-card" style="--i:2" href="tel:+910000000000">
                <div class="kc-card__icon"><svg viewBox="0 0 24 24">
                        <path d="M5 4h4l2 5-2.5 1.5a11 11 0 0 0 5 5L15 13l5 2v4a2 2 0 0 1-2 2A16 16 0 0 1 3 6a2 2 0 0 1 2-2z" />
                    </svg></div>
                <h3>Call</h3>
                <p>Mon to Sat, 10:00 am to 7:00 pm IST.</p><strong>+91 00000 00000</strong>
            </a>
            <a class="kc-card" style="--i:3" href="https://maps.google.com/?q=Koregaon+Park+Annexe+Mundhwa+Pune" target="_blank" rel="noopener">
                <div class="kc-card__icon"><svg viewBox="0 0 24 24">
                        <path d="M12 21s7-6.2 7-12a7 7 0 0 0-14 0c0 5.8 7 12 7 12z" />
                        <circle cx="12" cy="9" r="2.5" />
                    </svg></div>
                <h3>Office</h3>
                <p>Kalorali Fashion Pvt Ltd, Koregaon Park Annexe, Mundhwa, Pune 411036.</p><strong>Open in Maps</strong>
            </a>
        </div>
    </div>
</section>
<script>
    (function() {
        var sec = document.getElementById('kcInfo');
        new IntersectionObserver(function(e, o) {
            if (e[0].isIntersecting) {
                sec.classList.add('is-in');
                o.disconnect()
            }
        }, {
            threshold: .2
        }).observe(sec);
        if (window.matchMedia('(hover:hover)').matches) {
            sec.querySelectorAll('.kc-card').forEach(function(c) {
                c.addEventListener('mousemove', function(ev) {
                    var r = c.getBoundingClientRect(),
                        x = (ev.clientX - r.left) / r.width - .5,
                        y = (ev.clientY - r.top) / r.height - .5;
                    c.style.transform = 'rotateY(' + x * 10 + 'deg) rotateX(' + (-y * 10) + 'deg) translateY(-6px)';
                });
                c.addEventListener('mouseleave', function() {
                    c.style.transform = ''
                });
            });
        }
    })();
</script>

<!-- ============ section 3 : CONTACT FORM ============ -->
<style>
    .kc-form {
        --k-green: #0f2b20;
        --k-green2: #17402f;
        --k-cream: #fcf5ea;
        --k-gold: #d4a85a;
        --k-err: #b3392f;
        background: #f6ecdb;
        padding: 90px 20px;
        font-family: 'Poppins', sans-serif
    }

    .kc-form * {
        box-sizing: border-box
    }

    .kc-form__wrap {
        max-width: 1180px;
        margin: 0 auto;
        display: grid;
        grid-template-columns: 1.25fr 1fr;
        border-radius: 18px;
        overflow: hidden;
        box-shadow: 0 30px 60px -30px rgba(15, 43, 32, .5);
        opacity: 0;
        transform: translateY(40px) scale(.98);
        transition: all .9s cubic-bezier(.2, .7, .2, 1)
    }

    .kc-form.is-in .kc-form__wrap {
        opacity: 1;
        transform: none
    }

    .kc-form__main {
        background: #fff;
        padding: 54px 48px
    }

    .kc-form__main h2 {
        font-family: 'Cormorant Garamond', serif;
        font-size: clamp(28px, 4vw, 40px);
        color: var(--k-green);
        margin: 0 0 6px
    }

    .kc-form__main>p {
        margin: 0 0 30px;
        color: #777;
        font-weight: 300;
        font-size: 14px
    }

    .kc-field {
        position: relative;
        margin-bottom: 24px
    }

    .kc-field input,
    .kc-field textarea {
        width: 100%;
        border: 0;
        border-bottom: 1px solid #cfc6b4;
        background: transparent;
        padding: 18px 2px 8px;
        font: 400 15px 'Poppins', sans-serif;
        color: #222;
        outline: 0;
        border-radius: 0;
        resize: vertical
    }

    .kc-field label {
        position: absolute;
        left: 2px;
        top: 18px;
        font-size: 14px;
        color: #8a8a8a;
        pointer-events: none;
        transition: all .25s
    }

    .kc-field input:focus+label,
    .kc-field input:not(:placeholder-shown)+label,
    .kc-field textarea:focus+label,
    .kc-field textarea:not(:placeholder-shown)+label {
        top: -2px;
        font-size: 11px;
        color: var(--k-green);
        letter-spacing: .08em
    }

    .kc-field::after {
        content: "";
        position: absolute;
        left: 0;
        bottom: 0;
        height: 2px;
        width: 0;
        background: var(--k-gold);
        transition: width .4s
    }

    .kc-field:focus-within::after {
        width: 100%
    }

    .kc-field.has-error input,
    .kc-field.has-error textarea {
        border-color: var(--k-err)
    }

    .kc-field small {
        display: none;
        color: var(--k-err);
        font-size: 12px;
        margin-top: 6px
    }

    .kc-field.has-error small {
        display: block
    }

    .kc-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 24px
    }

    .kc-topic__label {
        font-size: 12px;
        letter-spacing: .08em;
        color: var(--k-green);
        margin: 0 0 10px;
        display: block
    }

    .kc-chips {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-bottom: 28px
    }

    .kc-chips input {
        position: absolute;
        opacity: 0;
        pointer-events: none
    }

    .kc-chips span {
        display: inline-block;
        padding: 8px 16px;
        border: 1px solid #d8cfbd;
        border-radius: 30px;
        font-size: 13px;
        cursor: pointer;
        transition: all .25s;
        color: #555
    }

    .kc-chips input:checked+span {
        background: var(--k-green);
        color: var(--k-cream);
        border-color: var(--k-green)
    }

    .kc-chips input:focus-visible+span {
        outline: 2px solid var(--k-gold);
        outline-offset: 2px
    }

    .kc-count {
        position: absolute;
        right: 0;
        bottom: -20px;
        font-size: 11px;
        color: #9a9a9a
    }

    .kc-submit {
        position: relative;
        width: 100%;
        border: 0;
        background: var(--k-green);
        color: var(--k-cream);
        padding: 16px;
        font: 500 13px 'Poppins', sans-serif;
        letter-spacing: .25em;
        text-transform: uppercase;
        cursor: pointer;
        border-radius: 4px;
        overflow: hidden;
        transition: background .3s
    }

    .kc-submit:hover {
        background: var(--k-green2)
    }

    .kc-submit.is-loading {
        pointer-events: none
    }

    .kc-submit.is-loading::after {
        content: "";
        position: absolute;
        left: 0;
        bottom: 0;
        height: 3px;
        background: var(--k-gold);
        animation: kcLoad 1.4s forwards
    }

    @keyframes kcLoad {
        from {
            width: 0
        }

        to {
            width: 100%
        }
    }

    .kc-success {
        display: none;
        text-align: center;
        padding: 60px 10px
    }

    .kc-success svg {
        width: 84px;
        height: 84px;
        stroke: var(--k-gold);
        fill: none;
        stroke-width: 2;
        stroke-linecap: round;
        stroke-linejoin: round
    }

    .kc-success circle,
    .kc-success path {
        stroke-dasharray: 200;
        stroke-dashoffset: 200
    }

    .kc-form.is-sent .kc-success {
        display: block
    }

    .kc-form.is-sent .kc-success circle,
    .kc-form.is-sent .kc-success path {
        animation: kcDraw 1s .1s forwards
    }

    .kc-form.is-sent form {
        display: none
    }

    .kc-form.is-sent .kc-form__main>h2,
    .kc-form.is-sent .kc-form__main>p {
        display: none
    }

    @keyframes kcDraw {
        to {
            stroke-dashoffset: 0
        }
    }

    .kc-success h3 {
        font-family: 'Cormorant Garamond', serif;
        font-size: 32px;
        color: var(--k-green);
        margin: 16px 0 6px
    }

    .kc-success p {
        color: #777;
        font-weight: 300;
        margin: 0 0 20px
    }

    .kc-success button {
        background: none;
        border: 1px solid var(--k-green);
        color: var(--k-green);
        padding: 10px 26px;
        cursor: pointer;
        font: 500 12px 'Poppins', sans-serif;
        letter-spacing: .15em
    }

    .kc-form__side {
        background: linear-gradient(160deg, var(--k-green2), var(--k-green));
        color: var(--k-cream);
        padding: 54px 40px;
        position: relative;
        overflow: hidden
    }

    .kc-form__side::before {
        content: "";
        position: absolute;
        width: 320px;
        height: 320px;
        border: 1px solid rgba(212, 168, 90, .3);
        border-radius: 50%;
        right: -120px;
        bottom: -120px
    }

    .kc-form__side h3 {
        font-family: 'Cormorant Garamond', serif;
        font-size: 30px;
        margin: 0 0 22px;
        color: var(--k-gold)
    }

    .kc-form__side ul {
        list-style: none;
        margin: 0 0 34px;
        padding: 0
    }

    .kc-form__side li {
        padding: 14px 0;
        border-bottom: 1px solid rgba(212, 168, 90, .2);
        font-size: 14px;
        font-weight: 300;
        line-height: 1.6;
        display: flex;
        justify-content: space-between;
        gap: 14px
    }

    .kc-form__side li b {
        font-weight: 500;
        color: var(--k-gold);
        white-space: nowrap
    }

    .kc-form__side p {
        font-size: 13px;
        line-height: 1.8;
        font-weight: 300;
        color: rgba(252, 245, 234, .8);
        margin: 0
    }

    @media(max-width:900px) {
        .kc-form__wrap {
            grid-template-columns: 1fr
        }

        .kc-form__main {
            padding: 40px 26px
        }

        .kc-form__side {
            padding: 40px 26px
        }
    }

    @media(max-width:520px) {
        .kc-form {
            padding: 60px 14px
        }

        .kc-row {
            grid-template-columns: 1fr;
            gap: 0
        }
    }

    @media(prefers-reduced-motion:reduce) {
        .kc-form__wrap {
            opacity: 1;
            transform: none;
            transition: none
        }
    }
</style>
<section class="kc-form" id="kcForm">
    <div class="kc-form__wrap">
        <div class="kc-form__main">
            <h2>Send us a message</h2>
            <p>Fill in the details and we will get back to you by email or WhatsApp.</p>
            <form id="kcFormEl" novalidate>
                <span class="kc-topic__label">What is it about?</span>
                <div class="kc-chips">
                    <label><input type="radio" name="topic" value="Order" checked><span>Order</span></label>
                    <label><input type="radio" name="topic" value="Return"><span>Return or exchange</span></label>
                    <label><input type="radio" name="topic" value="Gifting"><span>Gifting</span></label>
                    <label><input type="radio" name="topic" value="Other"><span>Something else</span></label>
                </div>
                <div class="kc-row">
                    <div class="kc-field"><input type="text" id="kcName" name="name" placeholder=" " autocomplete="name" required><label for="kcName">Full name</label><small>Please enter your name.</small></div>
                    <div class="kc-field"><input type="tel" id="kcPhone" name="phone" placeholder=" " autocomplete="tel" inputmode="numeric"><label for="kcPhone">Phone (10 digits)</label><small>Enter a valid 10 digit number.</small></div>
                </div>
                <div class="kc-field"><input type="email" id="kcEmail" name="email" placeholder=" " autocomplete="email" required><label for="kcEmail">Email address</label><small>Enter a valid email address.</small></div>
                <div class="kc-field"><textarea id="kcMsg" name="message" rows="4" maxlength="500" placeholder=" " required></textarea><label for="kcMsg">Your message</label><span class="kc-count" id="kcCount">0 / 500</span><small>Tell us a little more (10 characters or more).</small></div>
                <button type="submit" class="kc-submit" id="kcSubmit">Send message</button>
            </form>
            <div class="kc-success" role="status">
                <svg viewBox="0 0 80 80">
                    <circle cx="40" cy="40" r="34" />
                    <path d="m26 41 10 10 18-21" />
                </svg>
                <h3>Message sent</h3>
                <p>Thank you. We will reply within one working day.</p>
                <button type="button" id="kcAgain">Send another</button>
            </div>
        </div>
        <aside class="kc-form__side">
            <h3>Good to know</h3>
            <ul>
                <li>Reply time <b>Within 24 hrs</b></li>
                <li>Support hours <b>Mon to Sat, 10 to 7</b></li>
                <li>Returns <b>Easy exchange</b></li>
            </ul>
            <p>Include your order number in the message if you are asking about an existing order. It helps us answer faster.</p>
        </aside>
    </div>
</section>
<script>
    (function() {
        var sec = document.getElementById('kcForm'),
            form = document.getElementById('kcFormEl'),
            btn = document.getElementById('kcSubmit'),
            msg = document.getElementById('kcMsg'),
            cnt = document.getElementById('kcCount');
        new IntersectionObserver(function(e, o) {
            if (e[0].isIntersecting) {
                sec.classList.add('is-in');
                o.disconnect()
            }
        }, {
            threshold: .12
        }).observe(sec);
        msg.addEventListener('input', function() {
            cnt.textContent = msg.value.length + ' / 500'
        });
        var rules = {
            kcName: function(v) {
                return v.trim().length > 1
            },
            kcPhone: function(v) {
                return v === '' || /^[6-9]\d{9}$/.test(v.replace(/\s/g, ''))
            },
            kcEmail: function(v) {
                return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v)
            },
            kcMsg: function(v) {
                return v.trim().length >= 10
            }
        };

        function check(id) {
            var el = document.getElementById(id),
                ok = rules[id](el.value);
            el.parentNode.classList.toggle('has-error', !ok);
            return ok
        }
        Object.keys(rules).forEach(function(id) {
            var el = document.getElementById(id);
            el.addEventListener('blur', function() {
                check(id)
            });
            el.addEventListener('input', function() {
                if (el.parentNode.classList.contains('has-error')) check(id)
            });
        });
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            var ok = Object.keys(rules).map(check).every(Boolean);
            if (!ok) {
                var f = form.querySelector('.has-error input,.has-error textarea');
                if (f) f.focus();
                return
            }
            btn.classList.add('is-loading');
            btn.textContent = 'Sending...';
            /* TODO: send to your backend, e.g. fetch('contact-submit.php',{method:'POST',body:new FormData(form)}) */
            setTimeout(function() {
                sec.classList.add('is-sent');
                btn.classList.remove('is-loading');
                btn.textContent = 'Send message';
                form.reset();
                cnt.textContent = '0 / 500'
            }, 1500);
        });
        document.getElementById('kcAgain').addEventListener('click', function() {
            sec.classList.remove('is-sent')
        });
    })();
</script>

<!-- ============ section 4 : FAQ ============ -->
<style>
    .kc-faq {
        --k-green: #0f2b20;
        --k-cream: #fcf5ea;
        --k-gold: #d4a85a;
        background: var(--k-cream);
        padding: 90px 20px;
        font-family: 'Poppins', sans-serif
    }

    .kc-faq * {
        box-sizing: border-box
    }

    .kc-faq__wrap {
        max-width: 820px;
        margin: 0 auto
    }

    .kc-faq h2 {
        font-family: 'Cormorant Garamond', serif;
        font-size: clamp(30px, 5vw, 46px);
        text-align: center;
        color: var(--k-green);
        margin: 0 0 40px;
        letter-spacing: .05em
    }

    .kc-q {
        border-bottom: 1px solid rgba(15, 43, 32, .18);
        opacity: 0;
        transform: translateX(-24px);
        transition: opacity .6s, transform .6s;
        transition-delay: calc(var(--i)*.1s)
    }

    .kc-faq.is-in .kc-q {
        opacity: 1;
        transform: none
    }

    .kc-q button {
        all: unset;
        box-sizing: border-box;
        width: 100%;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 16px;
        padding: 22px 4px;
        cursor: pointer;
        font: 500 16px 'Poppins', sans-serif;
        color: var(--k-green)
    }

    .kc-q button:focus-visible {
        outline: 2px solid var(--k-gold);
        outline-offset: 2px
    }

    .kc-q i {
        flex: none;
        width: 28px;
        height: 28px;
        border: 1px solid var(--k-gold);
        border-radius: 50%;
        position: relative;
        transition: transform .4s, background .3s
    }

    .kc-q i::before,
    .kc-q i::after {
        content: "";
        position: absolute;
        left: 50%;
        top: 50%;
        width: 11px;
        height: 1.5px;
        background: var(--k-green);
        transform: translate(-50%, -50%);
        transition: transform .4s
    }

    .kc-q i::after {
        transform: translate(-50%, -50%) rotate(90deg)
    }

    .kc-q.is-open i {
        background: var(--k-gold);
        transform: rotate(180deg)
    }

    .kc-q.is-open i::after {
        transform: translate(-50%, -50%) rotate(0)
    }

    .kc-q__a {
        display: grid;
        grid-template-rows: 0fr;
        transition: grid-template-rows .45s ease
    }

    .kc-q.is-open .kc-q__a {
        grid-template-rows: 1fr
    }

    .kc-q__a div {
        overflow: hidden
    }

    .kc-q__a p {
        margin: 0;
        padding: 0 40px 22px 4px;
        font-size: 14px;
        line-height: 1.9;
        font-weight: 300;
        color: #555
    }

    @media(max-width:560px) {
        .kc-faq {
            padding: 64px 16px
        }

        .kc-q button {
            font-size: 14.5px
        }
    }

    @media(prefers-reduced-motion:reduce) {
        .kc-q {
            opacity: 1;
            transform: none;
            transition: none
        }
    }
</style>
<section class="kc-faq" id="kcFaq">
    <div class="kc-faq__wrap">
        <h2>Quick answers</h2>
        <div class="kc-q" style="--i:0"><button aria-expanded="false">How long does delivery take?<i></i></button>
            <div class="kc-q__a">
                <div>
                    <p>Most orders ship within 1 to 2 working days and reach you in 3 to 7 days, depending on your city.</p>
                </div>
            </div>
        </div>
        <div class="kc-q" style="--i:1"><button aria-expanded="false">Can I return or exchange a piece?<i></i></button>
            <div class="kc-q__a">
                <div>
                    <p>Yes. Unused items in original packaging can be returned or exchanged within the period in our Return and Exchange Policy.</p>
                </div>
            </div>
        </div>
        <div class="kc-q" style="--i:2"><button aria-expanded="false">How do I track my order?<i></i></button>
            <div class="kc-q__a">
                <div>
                    <p>Use Track Order from the menu with your order number, or message us on WhatsApp and we will check it for you.</p>
                </div>
            </div>
        </div>
        <div class="kc-q" style="--i:3"><button aria-expanded="false">Is the jewellery skin friendly?<i></i></button>
            <div class="kc-q__a">
                <div>
                    <p>Our pieces are made for daily wear. Keep them dry and away from perfume to keep the shine longer.</p>
                </div>
            </div>
        </div>
    </div>
</section>
<script>
    (function() {
        var sec = document.getElementById('kcFaq');
        new IntersectionObserver(function(e, o) {
            if (e[0].isIntersecting) {
                sec.classList.add('is-in');
                o.disconnect()
            }
        }, {
            threshold: .15
        }).observe(sec);
        var items = sec.querySelectorAll('.kc-q');
        items.forEach(function(it) {
            var b = it.querySelector('button');
            b.addEventListener('click', function() {
                var open = it.classList.contains('is-open');
                items.forEach(function(o) {
                    o.classList.remove('is-open');
                    o.querySelector('button').setAttribute('aria-expanded', 'false')
                });
                if (!open) {
                    it.classList.add('is-open');
                    b.setAttribute('aria-expanded', 'true')
                }
            });
        });
    })();
</script>

<!-- ============ section 5 : MARQUEE + CTA ============ -->
<style>
    .kc-cta {
        --k-green: #0f2b20;
        --k-green2: #17402f;
        --k-cream: #fcf5ea;
        --k-gold: #d4a85a;
        background: var(--k-green);
        color: var(--k-cream);
        padding: 80px 0 70px;
        overflow: hidden;
        font-family: 'Poppins', sans-serif;
        text-align: center
    }

    .kc-cta * {
        box-sizing: border-box
    }

    .kc-marquee {
        display: flex;
        white-space: nowrap;
        overflow: hidden;
        margin-bottom: 46px;
        transform: skewY(0deg);
        transition: transform .3s
    }

    .kc-marquee__track {
        display: flex;
        flex: none;
        animation: kcScroll 28s linear infinite
    }

    .kc-marquee span {
        font-family: 'Cormorant Garamond', serif;
        font-size: clamp(40px, 8vw, 92px);
        font-weight: 600;
        padding: 0 28px;
        color: transparent;
        -webkit-text-stroke: 1px rgba(212, 168, 90, .7);
        letter-spacing: .05em
    }

    .kc-marquee span:nth-child(even) {
        color: var(--k-gold);
        -webkit-text-stroke: 0;
        font-style: italic
    }

    @keyframes kcScroll {
        to {
            transform: translateX(-100%)
        }
    }

    .kc-cta__text {
        max-width: 520px;
        margin: 0 auto 28px;
        padding: 0 20px;
        font-weight: 300;
        line-height: 1.8;
        color: rgba(252, 245, 234, .8);
        font-size: 15px
    }

    .kc-cta__btns {
        display: flex;
        justify-content: center;
        gap: 14px;
        flex-wrap: wrap;
        padding: 0 20px
    }

    .kc-cta__btns a {
        padding: 14px 34px;
        font-size: 12px;
        letter-spacing: .22em;
        text-transform: uppercase;
        text-decoration: none;
        border: 1px solid var(--k-gold);
        transition: all .35s
    }

    .kc-cta__btns a:first-child {
        background: var(--k-gold);
        color: var(--k-green)
    }

    .kc-cta__btns a:first-child:hover {
        background: transparent;
        color: var(--k-gold)
    }

    .kc-cta__btns a:last-child {
        color: var(--k-cream)
    }

    .kc-cta__btns a:last-child:hover {
        background: var(--k-gold);
        color: var(--k-green)
    }

    .kc-cta__text,
    .kc-cta__btns {
        opacity: 0;
        transform: translateY(24px);
        transition: all .8s
    }

    .kc-cta.is-in .kc-cta__text,
    .kc-cta.is-in .kc-cta__btns {
        opacity: 1;
        transform: none
    }

    .kc-cta.is-in .kc-cta__btns {
        transition-delay: .2s
    }

    @media(max-width:560px) {
        .kc-cta {
            padding: 60px 0 56px
        }

        .kc-cta__btns a {
            padding: 13px 26px
        }
    }

    @media(prefers-reduced-motion:reduce) {
        .kc-marquee__track {
            animation: none
        }

        .kc-cta__text,
        .kc-cta__btns {
            opacity: 1;
            transform: none
        }
    }
</style>
<section class="kc-cta" id="kcCta">
    <div class="kc-marquee" id="kcMarquee">
        <div class="kc-marquee__track"><span>Because you deserve to shine</span><span>Kalora</span><span>Because you deserve to shine</span><span>Kalora</span></div>
        <div class="kc-marquee__track" aria-hidden="true"><span>Because you deserve to shine</span><span>Kalora</span><span>Because you deserve to shine</span><span>Kalora</span></div>
    </div>
    <p class="kc-cta__text">Still deciding? Browse the new collection, or chat with us and we will help you pick the right piece.</p>
    <div class="kc-cta__btns">
        <a href="/">Shop now</a>
        <a href="https://wa.me/910000000000" target="_blank" rel="noopener">Chat on WhatsApp</a>
    </div>
</section>
<script>
    (function() {
        var sec = document.getElementById('kcCta'),
            mq = document.getElementById('kcMarquee'),
            last = window.pageYOffset,
            t;
        new IntersectionObserver(function(e, o) {
            if (e[0].isIntersecting) {
                sec.classList.add('is-in');
                o.disconnect()
            }
        }, {
            threshold: .25
        }).observe(sec);
        window.addEventListener('scroll', function() {
            var y = window.pageYOffset,
                v = Math.max(-3, Math.min(3, (y - last) * .15));
            last = y;
            mq.style.transform = 'skewY(' + v + 'deg)';
            clearTimeout(t);
            t = setTimeout(function() {
                mq.style.transform = 'skewY(0deg)'
            }, 120);
        }, {
            passive: true
        });
    })();
</script>

</main>


<?php include_once 'includes/footter.php' ?>