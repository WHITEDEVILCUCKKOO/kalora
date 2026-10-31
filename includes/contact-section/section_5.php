
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
        <a href="index.php">Shop now</a>
        <a href="https://wa.me/<?php echo htmlspecialchars($global_info['globle_whatsapp'] ?? ''); ?>" target="_blank" rel="noopener">Chat on WhatsApp</a>
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