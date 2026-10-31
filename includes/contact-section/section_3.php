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
