<?php 

include_once'./admin_access/functions/global_info.php';
include_once'./admin_access/db_config.php';

$global_info = get_global_info($mydb);
?>


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
            <a class="kc-card" style="--i:0" href="https://wa.me//<?php echo htmlspecialchars($global_info['globle_whatsapp'] ?? ''); ?>" target="_blank" rel="noopener">
                <div class="kc-card__icon"><svg viewBox="0 0 24 24">
                        <path d="M21 12a9 9 0 1 1-4-7.5L21 3l-1 4.5A9 9 0 0 1 21 12z" />
                        <path d="M9 9c0 3 3 6 6 6l1.5-1.5-2-1-1 .8c-1-.4-2-1.4-2.4-2.4l.8-1-1-2z" />
                    </svg></div>
                <h3>WhatsApp</h3>
                <p>Quickest way to check order status or get styling help.</p><strong><?php echo htmlspecialchars($global_info['globle_whatsapp'] ?? ''); ?></strong>
            </a>
            <a class="kc-card" style="--i:1" href="mailto:<?php echo htmlspecialchars($global_info['footer_email_1'] ?? ''); ?>">
                <div class="kc-card__icon"><svg viewBox="0 0 24 24">
                        <rect x="3" y="5" width="18" height="14" rx="2" />
                        <path d="m3 7 9 6 9-6" />
                    </svg></div>
                <h3>Email</h3>
                <p>For returns, exchanges, bulk orders and collaborations.</p><strong><?php echo htmlspecialchars($global_info['footer_email_1'] ?? ''); ?></strong>
            </a>
            <a class="kc-card" style="--i:2" href="tel:<?php echo htmlspecialchars($global_info['globle_call_phone'] ?? ''); ?>">
                <div class="kc-card__icon"><svg viewBox="0 0 24 24">
                        <path d="M5 4h4l2 5-2.5 1.5a11 11 0 0 0 5 5L15 13l5 2v4a2 2 0 0 1-2 2A16 16 0 0 1 3 6a2 2 0 0 1 2-2z" />
                    </svg></div>
                <h3>Call</h3>
                <p>Mon to Sat, 10:00 am to 7:00 pm IST.</p><strong><?php echo htmlspecialchars($global_info['globle_call_phone'] ?? ''); ?></strong>
            </a>
            <a class="kc-card" style="--i:3" href="<?php echo htmlspecialchars($global_info['map_link'] ?? ''); ?>" target="_blank" rel="noopener">
                <div class="kc-card__icon"><svg viewBox="0 0 24 24">
                        <path d="M12 21s7-6.2 7-12a7 7 0 0 0-14 0c0 5.8 7 12 7 12z" />
                        <circle cx="12" cy="9" r="2.5" />
                    </svg></div>
                <h3>Office</h3>
                <p><?php echo htmlspecialchars($global_info['footer_address'] ?? ''); ?></p><strong>Open in Maps</strong>
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
