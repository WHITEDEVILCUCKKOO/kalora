

<!-- ============ section 6 : CTA ============ -->
<style>
    .ka-cta {
        --k-green: #0f2b20;
        --k-cream: #fcf5ea;
        --k-gold: #d4a85a;
        position: relative;
        overflow: hidden;
        background: var(--k-green);
        color: var(--k-cream);
        text-align: center;
        padding: 110px 20px;
        font-family: 'Poppins', sans-serif
    }

    .ka-cta * {
        box-sizing: border-box
    }

    .ka-cta::before,
    .ka-cta::after {
        content: "";
        position: absolute;
        border: 1px solid rgba(212, 168, 90, .3);
        border-radius: 50%;
        left: 50%;
        top: 50%;
        transform: translate(-50%, -50%) scale(var(--s, 1));
        transition: transform .2s linear
    }

    .ka-cta::before {
        width: 520px;
        height: 520px
    }

    .ka-cta::after {
        width: 820px;
        height: 820px;
        border-style: dashed
    }

    .ka-cta__in {
        position: relative;
        z-index: 2;
        max-width: 620px;
        margin: 0 auto;
        opacity: 0;
        transform: translateY(30px);
        transition: all .9s
    }

    .ka-cta.is-in .ka-cta__in {
        opacity: 1;
        transform: none
    }

    .ka-cta h2 {
        font-family: 'Cormorant Garamond', serif;
        font-size: clamp(34px, 6vw, 64px);
        line-height: 1.1;
        margin: 0 0 18px
    }

    .ka-cta h2 em {
        color: var(--k-gold)
    }

    .ka-cta p {
        font-weight: 300;
        line-height: 1.8;
        margin: 0 0 32px;
        color: rgba(252, 245, 234, .8)
    }

    .ka-cta a {
        display: inline-block;
        margin: 6px;
        padding: 14px 36px;
        font-size: 12px;
        letter-spacing: .22em;
        text-transform: uppercase;
        text-decoration: none;
        border: 1px solid var(--k-gold);
        transition: all .35s
    }

    .ka-cta a.ka-fill {
        background: var(--k-gold);
        color: var(--k-green)
    }

    .ka-cta a.ka-fill:hover {
        background: transparent;
        color: var(--k-gold)
    }

    .ka-cta a:not(.ka-fill) {
        color: var(--k-cream)
    }

    .ka-cta a:not(.ka-fill):hover {
        background: var(--k-gold);
        color: var(--k-green)
    }

    @media(max-width:560px) {
        .ka-cta {
            padding: 80px 16px
        }

        .ka-cta::before {
            width: 340px;
            height: 340px
        }

        .ka-cta::after {
            width: 520px;
            height: 520px
        }
    }

    @media(prefers-reduced-motion:reduce) {
        .ka-cta__in {
            opacity: 1;
            transform: none;
            transition: none
        }
    }
</style>
<section class="ka-cta" id="kaCta">
    <div class="ka-cta__in">
        <h2>Find the piece that <em>feels like you</em></h2>
        <p>Explore the newest bracelets, earrings and pendants, starting at ₹99.</p>
        <a href="index.php" class="ka-fill">Shop now</a>
        <a href="contact.php">Talk to us</a>
    </div>
</section>
<script>
    (function() {
        var sec = document.getElementById('kaCta'),
            t = false;
        new IntersectionObserver(function(e, o) {
            if (e[0].isIntersecting) {
                sec.classList.add('is-in');
                o.disconnect()
            }
        }, {
            threshold: .25
        }).observe(sec);
        window.addEventListener('scroll', function() {
            if (t) return;
            t = true;
            requestAnimationFrame(function() {
                var r = sec.getBoundingClientRect(),
                    p = 1 - (r.top / window.innerHeight);
                sec.style.setProperty('--s', (0.8 + Math.max(0, Math.min(1, p)) * 0.4).toFixed(3));
                t = false
            });
        }, {
            passive: true
        });
    })();
</script>
