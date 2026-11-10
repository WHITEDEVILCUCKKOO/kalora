<!-- ============ SECTION 3 : CONTACT FORM ============ -->

<?php
$kcWhatsappX91 = preg_replace(
    '/\D+/',
    '',
    (string)($global_info['globle_whatsapp'] ?? '')
);
?>

<style>
    .kc-form {
        --k-green: #0f2b20;
        --k-green2: #17402f;
        --k-cream: #fcf5ea;
        --k-gold: #d4a85a;
        --k-err: #b3392f;
        background: #f6ecdb;
        padding: 90px 20px;
        font-family: 'Poppins', sans-serif;
    }

    .kc-form * {
        box-sizing: border-box;
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
        transition: all .9s cubic-bezier(.2, .7, .2, 1);
    }

    .kc-form.is-in .kc-form__wrap {
        opacity: 1;
        transform: none;
    }

    .kc-form__main {
        background: #fff;
        padding: 54px 48px;
    }

    .kc-form__main h2 {
        font-family: 'Cormorant Garamond', serif;
        font-size: clamp(28px, 4vw, 40px);
        color: var(--k-green);
        margin: 0 0 6px;
    }

    .kc-form__main > p {
        margin: 0 0 30px;
        color: #777;
        font-weight: 300;
        font-size: 14px;
    }

    .kc-field {
        position: relative;
        margin-bottom: 24px;
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
        resize: vertical;
    }

    .kc-field label {
        position: absolute;
        left: 2px;
        top: 18px;
        font-size: 14px;
        color: #8a8a8a;
        pointer-events: none;
        transition: all .25s;
    }

    .kc-field input:focus + label,
    .kc-field input:not(:placeholder-shown) + label,
    .kc-field textarea:focus + label,
    .kc-field textarea:not(:placeholder-shown) + label {
        top: -2px;
        font-size: 11px;
        color: var(--k-green);
        letter-spacing: .08em;
    }

    .kc-field::after {
        content: "";
        position: absolute;
        left: 0;
        bottom: 0;
        height: 2px;
        width: 0;
        background: var(--k-gold);
        transition: width .4s;
    }

    .kc-field:focus-within::after {
        width: 100%;
    }

    .kc-field.has-error input,
    .kc-field.has-error textarea {
        border-color: var(--k-err);
    }

    .kc-field small {
        display: none;
        color: var(--k-err);
        font-size: 12px;
        margin-top: 6px;
    }

    .kc-field.has-error small {
        display: block;
    }

    .kc-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 24px;
    }

    .kc-topic__label {
        font-size: 12px;
        letter-spacing: .08em;
        color: var(--k-green);
        margin: 0 0 10px;
        display: block;
    }

    .kc-chips {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-bottom: 28px;
    }

    .kc-chips label {
        cursor: pointer;
    }

    .kc-chips input {
        position: absolute;
        opacity: 0;
        pointer-events: none;
    }

    .kc-chips span {
        display: inline-block;
        padding: 8px 16px;
        border: 1px solid #d8cfbd;
        border-radius: 30px;
        font-size: 13px;
        cursor: pointer;
        transition: all .25s;
        color: #555;
    }

    .kc-chips input:checked + span {
        background: var(--k-green);
        color: var(--k-cream);
        border-color: var(--k-green);
    }

    .kc-chips input:focus-visible + span {
        outline: 2px solid var(--k-gold);
        outline-offset: 2px;
    }

    .kc-count {
        position: absolute;
        right: 0;
        bottom: -20px;
        font-size: 11px;
        color: #9a9a9a;
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
        transition: background .3s;
    }

    .kc-submit:hover {
        background: var(--k-green2);
    }

    .kc-submit:disabled {
        opacity: .7;
        cursor: not-allowed;
    }

    .kc-form__notice {
        display: none;
        margin-top: 16px;
        padding: 12px 14px;
        border-radius: 5px;
        font-size: 13px;
        line-height: 1.6;
    }

    .kc-form__notice.is-error {
        display: block;
        color: #9e2922;
        background: #fff0ee;
        border: 1px solid #f0c5c0;
    }

    .kc-form__notice.is-success {
        display: block;
        color: #17402f;
        background: #edf7ef;
        border: 1px solid #c8e4ce;
    }

    .kc-form__side {
        background: linear-gradient(160deg, var(--k-green2), var(--k-green));
        color: var(--k-cream);
        padding: 54px 40px;
        position: relative;
        overflow: hidden;
    }

    .kc-form__side::before {
        content: "";
        position: absolute;
        width: 320px;
        height: 320px;
        border: 1px solid rgba(212, 168, 90, .3);
        border-radius: 50%;
        right: -120px;
        bottom: -120px;
    }

    .kc-form__side h3 {
        font-family: 'Cormorant Garamond', serif;
        font-size: 30px;
        margin: 0 0 22px;
        color: var(--k-gold);
    }

    .kc-form__side ul {
        list-style: none;
        margin: 0 0 34px;
        padding: 0;
    }

    .kc-form__side li {
        padding: 14px 0;
        border-bottom: 1px solid rgba(212, 168, 90, .2);
        font-size: 14px;
        font-weight: 300;
        line-height: 1.6;
        display: flex;
        justify-content: space-between;
        gap: 14px;
    }

    .kc-form__side li b {
        font-weight: 500;
        color: var(--k-gold);
        white-space: nowrap;
    }

    .kc-form__side p {
        font-size: 13px;
        line-height: 1.8;
        font-weight: 300;
        color: rgba(252, 245, 234, .8);
        margin: 0;
    }

    @media(max-width:900px) {
        .kc-form__wrap {
            grid-template-columns: 1fr;
        }

        .kc-form__main {
            padding: 40px 26px;
        }

        .kc-form__side {
            padding: 40px 26px;
        }
    }

    @media(max-width:520px) {
        .kc-form {
            padding: 60px 14px;
        }

        .kc-row {
            grid-template-columns: 1fr;
            gap: 0;
        }
    }

    @media(prefers-reduced-motion:reduce) {
        .kc-form__wrap {
            opacity: 1;
            transform: none;
            transition: none;
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

                    <label>
                        <input type="radio" name="topic" value="Order" checked>
                        <span>Order</span>
                    </label>

                    <label>
                        <input type="radio" name="topic" value="Gifting">
                        <span>Gifting</span>
                    </label>

                    <label>
                        <input type="radio" name="topic" value="Other">
                        <span>Something else</span>
                    </label>

                </div>

                <div class="kc-row">

                    <div class="kc-field">
                        <input
                            type="text"
                            id="kcName"
                            name="name"
                            placeholder=" "
                            autocomplete="name"
                            maxlength="80"
                            required
                        >
                        <label for="kcName">Full name</label>
                        <small>Please enter a valid name (2–80 characters).</small>
                    </div>

                    <div class="kc-field">
                        <input
                            type="tel"
                            id="kcPhone"
                            name="phone"
                            placeholder=" "
                            autocomplete="tel"
                            inputmode="numeric"
                            maxlength="10"
                            required
                        >
                        <label for="kcPhone">Phone (10 digits)</label>
                        <small>Enter a valid 10-digit Indian mobile number.</small>
                    </div>

                </div>

                <div class="kc-field">
                    <input
                        type="email"
                        id="kcEmail"
                        name="email"
                        placeholder=" "
                        autocomplete="email"
                        maxlength="254"
                        required
                    >
                    <label for="kcEmail">Email address</label>
                    <small>Enter a valid email address.</small>
                </div>

                <div class="kc-field">
                    <textarea
                        id="kcMsg"
                        name="message"
                        rows="4"
                        maxlength="500"
                        placeholder=" "
                        required
                    ></textarea>

                    <label for="kcMsg">Your message</label>

                    <span class="kc-count" id="kcCount">0 / 500</span>

                    <small>Please enter a message between 10 and 500 characters.</small>
                </div>

                <button type="submit" class="kc-submit" id="kcSubmit">
                    Send message
                </button>

                <div class="kc-form__notice" id="kcNotice" role="status" aria-live="polite"></div>

            </form>

        </div>


        <aside class="kc-form__side">

            <h3>Good to know</h3>

            <ul>
                <li>
                    Reply time
                    <b>Within 24 hrs</b>
                </li>

                <li>
                    Support hours
                    <b>Mon to Sat, 10 to 7</b>
                </li>
            </ul>

            <p>
                Include your order number in the message if you are asking about an existing order.
                It helps us answer faster.
            </p>

        </aside>

    </div>

</section>


<script>
(function () {

    var secX91 = document.getElementById('kcForm');
    var formX91 = document.getElementById('kcFormEl');
    var btnX91 = document.getElementById('kcSubmit');
    var nameX91 = document.getElementById('kcName');
    var phoneX91 = document.getElementById('kcPhone');
    var emailX91 = document.getElementById('kcEmail');
    var msgX91 = document.getElementById('kcMsg');
    var countX91 = document.getElementById('kcCount');
    var noticeX91 = document.getElementById('kcNotice');

    // WhatsApp number from PHP global_info
    var whatsappNumberX91 = <?= json_encode($kcWhatsappX91) ?>;


    // Section reveal
    if ('IntersectionObserver' in window) {

        new IntersectionObserver(function (entriesX91, observerX91) {

            if (entriesX91[0].isIntersecting) {
                secX91.classList.add('is-in');
                observerX91.disconnect();
            }

        }, {
            threshold: 0.12
        }).observe(secX91);

    } else {
        secX91.classList.add('is-in');
    }


    // Message character counter
    msgX91.addEventListener('input', function () {
        countX91.textContent = msgX91.value.length + ' / 500';
        clearFieldErrorX91(msgX91);
    });


    // Only numbers in phone field
    phoneX91.addEventListener('input', function () {

        phoneX91.value = phoneX91.value
            .replace(/\D/g, '')
            .slice(0, 10);

        clearFieldErrorX91(phoneX91);

    });


    // Show validation error
    function showFieldErrorX91(fieldX91, messageX91) {

        var parentX91 = fieldX91.closest('.kc-field');

        if (!parentX91) return;

        parentX91.classList.add('has-error');

        var smallX91 = parentX91.querySelector('small');

        if (smallX91 && messageX91) {
            smallX91.textContent = messageX91;
        }

    }


    // Clear validation error
    function clearFieldErrorX91(fieldX91) {

        var parentX91 = fieldX91.closest('.kc-field');

        if (parentX91) {
            parentX91.classList.remove('has-error');
        }

    }


    // Name validation
    function validateNameX91() {

        var valueX91 = nameX91.value.trim();

        // Allows letters, spaces, dots, apostrophes and hyphens
        var namePatternX91 = /^[\p{L}\p{M}][\p{L}\p{M}\s.'’-]*$/u;

        if (
            valueX91.length < 2 ||
            valueX91.length > 80 ||
            !namePatternX91.test(valueX91)
        ) {
            showFieldErrorX91(
                nameX91,
                'Please enter a valid name (2–80 characters).'
            );
            return false;
        }

        clearFieldErrorX91(nameX91);
        return true;

    }


    // Phone validation
    function validatePhoneX91() {

        var valueX91 = phoneX91.value.replace(/\D/g, '');

        if (!/^[6-9]\d{9}$/.test(valueX91)) {

            showFieldErrorX91(
                phoneX91,
                'Enter a valid 10-digit Indian mobile number.'
            );

            return false;
        }

        clearFieldErrorX91(phoneX91);
        return true;

    }


    // Email validation
    function validateEmailX91() {

        var valueX91 = emailX91.value.trim();

        var emailPatternX91 = /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/;

        if (
            valueX91.length > 254 ||
            !emailPatternX91.test(valueX91)
        ) {

            showFieldErrorX91(
                emailX91,
                'Enter a valid email address.'
            );

            return false;
        }

        clearFieldErrorX91(emailX91);
        return true;

    }


    // Message validation
    function validateMessageX91() {

        var valueX91 = msgX91.value.trim();

        if (valueX91.length < 10 || valueX91.length > 500) {

            showFieldErrorX91(
                msgX91,
                'Please enter a message between 10 and 500 characters.'
            );

            return false;
        }

        clearFieldErrorX91(msgX91);
        return true;

    }


    // Validate fields while leaving them
    nameX91.addEventListener('blur', validateNameX91);
    phoneX91.addEventListener('blur', validatePhoneX91);
    emailX91.addEventListener('blur', validateEmailX91);
    msgX91.addEventListener('blur', validateMessageX91);


    // Remove error while typing
    nameX91.addEventListener('input', function () {
        if (nameX91.closest('.kc-field').classList.contains('has-error')) {
            validateNameX91();
        }
    });

    emailX91.addEventListener('input', function () {
        if (emailX91.closest('.kc-field').classList.contains('has-error')) {
            validateEmailX91();
        }
    });

    msgX91.addEventListener('input', function () {
        if (msgX91.closest('.kc-field').classList.contains('has-error')) {
            validateMessageX91();
        }
    });


    // Show general notice
    function showNoticeX91(messageX91, typeX91) {

        noticeX91.textContent = messageX91;
        noticeX91.className = 'kc-form__notice is-' + typeX91;

    }


    // Form submit
    formX91.addEventListener('submit', function (eventX91) {

        eventX91.preventDefault();

        noticeX91.className = 'kc-form__notice';
        noticeX91.textContent = '';


        // Validate all fields
        var nameValidX91 = validateNameX91();
        var phoneValidX91 = validatePhoneX91();
        var emailValidX91 = validateEmailX91();
        var messageValidX91 = validateMessageX91();


        // Topic validation
        var selectedTopicX91 = formX91.querySelector(
            'input[name="topic"]:checked'
        );

        if (!selectedTopicX91) {

            showNoticeX91(
                'Please select what your message is about.',
                'error'
            );

            return;
        }


        // Stop if any field is invalid
        if (
            !nameValidX91 ||
            !phoneValidX91 ||
            !emailValidX91 ||
            !messageValidX91
        ) {

            var firstErrorX91 = formX91.querySelector(
                '.kc-field.has-error input, .kc-field.has-error textarea'
            );

            if (firstErrorX91) {
                firstErrorX91.focus();
            }

            return;
        }


        // Check WhatsApp number from database
        if (!whatsappNumberX91) {

            showNoticeX91(
                'WhatsApp number is not configured. Please contact the website administrator.',
                'error'
            );

            return;
        }


        // Get all form values
        var topicValueX91 = selectedTopicX91.value;
        var nameValueX91 = nameX91.value.trim();
        var phoneValueX91 = phoneX91.value.trim();
        var emailValueX91 = emailX91.value.trim();
        var messageValueX91 = msgX91.value.trim();


        // Prepare complete WhatsApp message
        var whatsappMessageX91 =
            "New Website Enquiry\n\n" +
            "Topic: " + topicValueX91 + "\n" +
            "Name: " + nameValueX91 + "\n" +
            "Phone: " + phoneValueX91 + "\n" +
            "Email: " + emailValueX91 + "\n" +
            "Message: " + messageValueX91;


        // Create WhatsApp URL
        var whatsappURLX91 =
            "https://wa.me/" +
            whatsappNumberX91 +
            "?text=" +
            encodeURIComponent(whatsappMessageX91);


        // Open WhatsApp
        btnX91.disabled = true;
        btnX91.textContent = 'Opening WhatsApp...';

        var whatsappWindowX91 = window.open(
            whatsappURLX91,
            '_blank'
        );


        if (whatsappWindowX91) {

            showNoticeX91(
                'Your details are ready in WhatsApp. Please press Send there to submit your enquiry.',
                'success'
            );

        } else {

            showNoticeX91(
                'WhatsApp could not open automatically. Please allow pop-ups and try again.',
                'error'
            );

        }


        btnX91.disabled = false;
        btnX91.textContent = 'Send message';

    });


})();
</script>