
<!-- ============ section 5 : JOURNEY TIMELINE ============ -->
<style>
    .ka-flow {
        --k-green: #0f2b20;
        --k-cream: #fcf5ea;
        --k-gold: #d4a85a;
        background: #f6ecdb;
        padding: 100px 6vw;
        font-family: 'Poppins', sans-serif
    }

    .ka-flow * {
        box-sizing: border-box
    }

    .ka-flow h2 {
        font-family: 'Cormorant Garamond', serif;
        text-align: center;
        font-size: clamp(30px, 5vw, 48px);
        color: var(--k-green);
        margin: 0 0 60px
    }

    .ka-flow__list {
        max-width: 760px;
        margin: 0 auto;
        position: relative;
        padding-left: 56px
    }

    .ka-flow__rail {
        position: absolute;
        left: 19px;
        top: 6px;
        bottom: 6px;
        width: 2px;
        background: rgba(15, 43, 32, .15)
    }

    .ka-flow__fill {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        height: 0;
        background: var(--k-gold)
    }

    .ka-step {
        position: relative;
        padding-bottom: 46px;
        opacity: .35;
        transition: opacity .5s
    }

    .ka-step:last-child {
        padding-bottom: 0
    }

    .ka-step.is-on {
        opacity: 1
    }

    .ka-step::before {
        content: "";
        position: absolute;
        left: -46px;
        top: 2px;
        width: 20px;
        height: 20px;
        border-radius: 50%;
        background: #f6ecdb;
        border: 2px solid rgba(15, 43, 32, .25);
        transition: all .4s
    }

    .ka-step.is-on::before {
        background: var(--k-gold);
        border-color: var(--k-green);
        box-shadow: 0 0 0 6px rgba(212, 168, 90, .25)
    }

    .ka-step h3 {
        font-family: 'Cormorant Garamond', serif;
        font-size: 28px;
        margin: 0 0 6px;
        color: var(--k-green)
    }

    .ka-step p {
        margin: 0;
        font-size: 14px;
        line-height: 1.9;
        font-weight: 300;
        color: #555;
        max-width: 520px
    }

    @media(max-width:560px) {
        .ka-flow {
            padding: 70px 18px
        }

        .ka-flow__list {
            padding-left: 44px
        }

        .ka-step::before {
            left: -36px
        }

        .ka-flow__rail {
            left: 13px
        }
    }

    @media(prefers-reduced-motion:reduce) {
        .ka-step {
            opacity: 1;
            transition: none
        }
    }
</style>
<section class="ka-flow" id="kaFlow">
    <h2>From sketch to your doorstep</h2>
    <div class="ka-flow__list" id="kaFlowList">
        <div class="ka-flow__rail">
            <div class="ka-flow__fill" id="kaFill"></div>
        </div>
        <div class="ka-step">
            <h3>We design</h3>
            <p>Ideas come from trends, your feedback and the Instagram looks you save.</p>
        </div>
        <div class="ka-step">
            <h3>We make samples</h3>
            <p>Each design is worn and tested for comfort, weight and finish before it goes live.</p>
        </div>
        <div class="ka-step">
            <h3>We check by hand</h3>
            <p>Every single piece is inspected for shine and clasp strength before packing.</p>
        </div>
        <div class="ka-step">
            <h3>We pack and ship</h3>
            <p>Neat, protective packaging leaves our Pune office within 1 to 2 working days.</p>
        </div>
        <div class="ka-step">
            <h3>You shine</h3>
            <p>Your order arrives, ready to wear or gift. Tell us how you styled it.</p>
        </div>
    </div>
</section>
<script>
    (function() {
        var list = document.getElementById('kaFlowList'),
            fill = document.getElementById('kaFill'),
            steps = list.querySelectorAll('.ka-step'),
            t = false;

        function run() {
            var r = list.getBoundingClientRect(),
                vh = window.innerHeight,
                mid = vh * .6;
            var p = Math.max(0, Math.min(1, (mid - r.top) / r.height));
            fill.style.height = (p * 100) + '%';
            steps.forEach(function(s) {
                s.classList.toggle('is-on', s.getBoundingClientRect().top < mid)
            });
            t = false;
        }
        window.addEventListener('scroll', function() {
            if (!t) {
                requestAnimationFrame(run);
                t = true
            }
        }, {
            passive: true
        });
        window.addEventListener('resize', run);
        run();
    })();
</script>