


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