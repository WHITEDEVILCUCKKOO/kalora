<!-- ============ section 4 : VALUES (expanding panels) ============ -->
<style>
    .ka-values {
        --k-green: #0f2b20;
        --k-green2: #17402f;
        --k-cream: #fcf5ea;
        --k-gold: #d4a85a;
        background: var(--k-cream);
        padding: 100px 6vw;
        font-family: 'Poppins', sans-serif
    }

    .ka-values * {
        box-sizing: border-box
    }

    .ka-values h2 {
        font-family: 'Cormorant Garamond', serif;
        text-align: center;
        font-size: clamp(30px, 5vw, 48px);
        color: var(--k-green);
        margin: 0 0 50px
    }

    .ka-values__row {
        max-width: 1180px;
        margin: 0 auto;
        display: flex;
        gap: 16px;
        height: 380px
    }

    .ka-val {
        flex: 1;
        min-width: 0;
        border-radius: 14px;
        background: var(--k-green);
        color: var(--k-cream);
        padding: 30px 26px;
        position: relative;
        overflow: hidden;
        cursor: pointer;
        transition: flex .6s cubic-bezier(.2, .7, .2, 1), background .4s;
        border: 1px solid rgba(212, 168, 90, .25);
        display: flex;
        flex-direction: column;
        justify-content: flex-end;
        text-align: left;
        font: inherit
    }

    .ka-val.is-active {
        flex: 2.6;
        background: var(--k-green2)
    }

    .ka-val__n {
        position: absolute;
        top: 22px;
        left: 26px;
        font-family: 'Cormorant Garamond', serif;
        font-size: 56px;
        color: rgba(212, 168, 90, .35);
        line-height: 1
    }

    .ka-val h3 {
        font-family: 'Cormorant Garamond', serif;
        font-size: 26px;
        margin: 0 0 10px;
        color: var(--k-gold);
        white-space: nowrap
    }

    .ka-val p {
        margin: 0;
        font-size: 14px;
        line-height: 1.8;
        font-weight: 300;
        max-height: 0;
        opacity: 0;
        overflow: hidden;
        transition: max-height .6s, opacity .5s .1s;
        color: rgba(252, 245, 234, .85)
    }

    .ka-val.is-active p {
        max-height: 160px;
        opacity: 1
    }

    .ka-val:focus-visible {
        outline: 2px solid var(--k-gold);
        outline-offset: 3px
    }

    @media(max-width:760px) {
        .ka-values {
            padding: 70px 16px
        }

        .ka-values__row {
            flex-direction: column;
            height: auto
        }

        .ka-val {
            flex: none;
            min-height: 96px;
            padding: 24px
        }

        .ka-val.is-active {
            flex: none
        }

        .ka-val h3 {
            white-space: normal
        }

        .ka-val__n {
            font-size: 40px
        }
    }
</style>
<section class="ka-values" id="kaValues">
    <h2>What we stand for</h2>
    <div class="ka-values__row">
        <button class="ka-val is-active" aria-expanded="true"><span class="ka-val__n">1</span>
            <h3>Easy on the pocket</h3>
            <p>Pieces start at just ₹99. We keep margins fair so you never have to choose between style and budget.</p>
        </button>
        <button class="ka-val" aria-expanded="false"><span class="ka-val__n">2</span>
            <h3>Made for daily wear</h3>
            <p>Light, comfortable and tested for everyday use, so you can wear it to work and straight to dinner.</p>
        </button>
        <button class="ka-val" aria-expanded="false"><span class="ka-val__n">3</span>
            <h3>Gift ready</h3>
            <p>Every order is packed neatly, with options for a Mistry Box and gifting by recipient.</p>
        </button>
        <button class="ka-val" aria-expanded="false"><span class="ka-val__n">4</span>
            <h3>Easy returns</h3>
            <p>Not the right fit? Our return and exchange policy keeps things simple and quick.</p>
        </button>
    </div>
</section>
<script>
    (function() {
        var vals = document.querySelectorAll('#kaValues .ka-val');

        function open(v) {
            vals.forEach(function(x) {
                var a = x === v;
                x.classList.toggle('is-active', a);
                x.setAttribute('aria-expanded', a)
            })
        }
        vals.forEach(function(v) {
            v.addEventListener('click', function() {
                open(v)
            });
            v.addEventListener('mouseenter', function() {
                if (window.matchMedia('(hover:hover) and (min-width:761px)').matches) open(v)
            });
        });
    })();
</script>