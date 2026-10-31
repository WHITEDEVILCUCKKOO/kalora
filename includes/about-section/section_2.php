<!-- ============ section 2 : OUR STORY ============ -->
<style>
    .ka-story {
        --k-green: #0f2b20;
        --k-cream: #fcf5ea;
        --k-gold: #d4a85a;
        background: var(--k-cream);
        padding: 110px 6vw;
        font-family: 'Poppins', sans-serif;
        color: #333
    }

    .ka-story * {
        box-sizing: border-box
    }

    .ka-story__wrap {
        max-width: 1180px;
        margin: 0 auto;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 70px;
        align-items: start
    }

    .ka-story__media {
        position: sticky;
        top: 90px;
        /* aspect-ratio: 4/5; */
        border-radius: 14px;
        overflow: hidden;
        background: linear-gradient(150deg, #17402f, #0f2b20);
        border: 1px solid rgba(212, 168, 90, .4)
    }

    .ka-story__media::before {
        content: "";
        position: absolute;
        inset: -20%;
        background: radial-gradient(circle at 70% 30%, rgba(212, 168, 90, .45), transparent 50%);
        transform: translateY(var(--p, 0px))
    }

    /* .ka-story__media i {
        position: absolute;
        inset: 14px;
        border: 1px solid rgba(212, 168, 90, .45);
        border-radius: 8px
    }

    .ka-story__media span {
        position: absolute;
        left: 0;
        right: 0;
        bottom: 26px;
        text-align: center;
        font-family: 'Cormorant Garamond', serif;
        font-size: 30px;
        font-style: italic;
        color: var(--k-gold)
    } */

    .ka-story__body h2 {
        font-family: 'Cormorant Garamond', serif;
        font-size: clamp(32px, 4.6vw, 54px);
        color: var(--k-green);
        margin: 0 0 30px;
        line-height: 1.1
    }

    .ka-story__body p {
        font-size: 15px;
        line-height: 2;
        font-weight: 300;
        margin: 0 0 24px;
        max-width: 520px
    }

    .ka-story__body p.ka-r {
        opacity: 0;
        transform: translateX(30px);
        transition: all .8s
    }

    .ka-story__body p.ka-r.is-in {
        opacity: 1;
        transform: none
    }

    .ka-story__quote {
        border-left: 2px solid var(--k-gold);
        padding: 4px 0 4px 22px;
        margin: 34px 0 0;
        font-family: 'Cormorant Garamond', serif;
        font-size: 26px;
        line-height: 1.4;
        color: var(--k-green);
        font-style: italic;
        max-width: 480px
    }

    @media(max-width:860px) {
        .ka-story {
            padding: 70px 22px
        }

        .ka-story__wrap {
            grid-template-columns: 1fr;
            gap: 40px
        }

        .ka-story__media {
            position: relative;
            top: 0;
            aspect-ratio: 16/11
        }
    }

    @media(prefers-reduced-motion:reduce) {
        .ka-story__body p.ka-r {
            opacity: 1;
            transform: none;
            transition: none
        }
    }


    .aseu51{
        width: 100%;
    }
</style>
<section class="ka-story" id="kaStory">
    <div class="ka-story__wrap">
        <div class="ka-story__media">
            <img class="aseu51" src="assets/logos/<?php echo htmlspecialchars($global_info['facion_icon'] ?? ''); ?>" alt="" srcset="">
        </div>
        <div class="ka-story__body">
            <h2>It started with one bracelet</h2>
            <p class="ka-r">We wanted jewellery that goes from office to a party without a second thought, and did not cost a month's salary. So we started designing it ourselves.</p>
            <p class="ka-r">Today Kalora ships from Pune across India. Every piece is checked by hand for finish, fit and shine before it is packed.</p>
            <p class="ka-r">Our Under 99, 199, 299, 399 and 499 collections exist for one reason: good style should be easy to reach.</p>
            <div class="ka-story__quote ka-r">Shine should never feel out of reach.</div>
        </div>
    </div>
</section>
<script>
    (function() {
        var sec = document.getElementById('kaStory'),
            media = sec.querySelector('.ka-story__media'),
            els = sec.querySelectorAll('.ka-r'),
            t = false;
        var io = new IntersectionObserver(function(es) {
            es.forEach(function(e) {
                if (e.isIntersecting) {
                    e.target.classList.add('is-in');
                    io.unobserve(e.target)
                }
            })
        }, {
            threshold: .35
        });
        els.forEach(function(el, i) {
            el.style.transitionDelay = (i % 2 * .1) + 's';
            io.observe(el)
        });
        window.addEventListener('scroll', function() {
            if (t) return;
            t = true;
            requestAnimationFrame(function() {
                var r = sec.getBoundingClientRect();
                media.style.setProperty('--p', (r.top * .12) + 'px');
                t = false
            });
        }, {
            passive: true
        });
    })();
</script>