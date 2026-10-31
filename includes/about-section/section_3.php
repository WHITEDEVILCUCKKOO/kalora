


<!-- ============ section 3 : STATS ============ -->
<style>
    .ka-stats {
        --k-green: #0f2b20;
        --k-cream: #fcf5ea;
        --k-gold: #d4a85a;
        background: var(--k-green);
        color: var(--k-cream);
        padding: 80px 6vw;
        font-family: 'Poppins', sans-serif
    }

    .ka-stats * {
        box-sizing: border-box
    }

    .ka-stats__grid {
        max-width: 1100px;
        margin: 0 auto;
        display: grid;
        grid-template-columns: repeat(4, 1fr)
    }

    .ka-stat {
        text-align: center;
        padding: 10px 16px;
        border-right: 1px solid rgba(212, 168, 90, .25)
    }

    .ka-stat:last-child {
        border-right: 0
    }

    .ka-stat b {
        display: block;
        font-family: 'Cormorant Garamond', serif;
        font-weight: 600;
        font-size: clamp(40px, 6vw, 74px);
        line-height: 1;
        color: var(--k-gold)
    }

    .ka-stat span {
        display: block;
        margin-top: 12px;
        font-size: 13px;
        font-weight: 300;
        letter-spacing: .06em;
        color: rgba(252, 245, 234, .8)
    }

    @media(max-width:700px) {
        .ka-stats {
            padding: 60px 20px
        }

        .ka-stats__grid {
            grid-template-columns: 1fr 1fr;
            row-gap: 36px
        }

        .ka-stat:nth-child(2) {
            border-right: 0
        }
    }
</style>
<section class="ka-stats" id="kaStats">
    <div class="ka-stats__grid">
        <div class="ka-stat"><b data-to="10000" data-suffix="+">0</b><span>Happy customers</span></div>
        <div class="ka-stat"><b data-to="300" data-suffix="+">0</b><span>Designs launched</span></div>
        <div class="ka-stat"><b data-to="150" data-suffix="+">0</b><span>Cities delivered</span></div>
        <div class="ka-stat"><b data-to="99" data-prefix="₹">0</b><span>Where prices start</span></div>
    </div>
</section>
<script>
    (function() {
        var sec = document.getElementById('kaStats'),
            nums = sec.querySelectorAll('b'),
            reduce = window.matchMedia('(prefers-reduced-motion:reduce)').matches;

        function fmt(n, el) {
            return (el.dataset.prefix || '') + n.toLocaleString('en-IN') + (el.dataset.suffix || '')
        }

        function count(el) {
            var to = +el.dataset.to,
                dur = 1800,
                s = null;
            if (reduce) {
                el.textContent = fmt(to, el);
                return
            }
            (function step(ts) {
                if (!s) s = ts;
                var p = Math.min((ts - s) / dur, 1),
                    e = 1 - Math.pow(1 - p, 4);
                el.textContent = fmt(Math.round(to * e), el);
                if (p < 1) requestAnimationFrame(step)
            })(performance.now());
        }
        new IntersectionObserver(function(e, o) {
            if (e[0].isIntersecting) {
                nums.forEach(count);
                o.disconnect()
            }
        }, {
            threshold: .4
        }).observe(sec);
    })();
</script>