<?php
/* =====================================================
   KALORA LIVE EVENT SECTION
   CSS : assets/css/event-section.css
   JS  : assets/js/event-section.js
===================================================== */

include_once "admin_access/db_config.php";
include_once "admin_access/functions/event.php";

/* India timezone (strtotime isi me chalega) */
date_default_timezone_set('Asia/Kolkata');

/* Pehle status update karo */
update_kalora_event_status($mydb);

/* Saare events lo */
$kalora_event_list   = get_kalora_events($mydb);
$kalora_active_event = null;

if (!empty($kalora_event_list)) {
    foreach ($kalora_event_list as $kalora_event_row) {
        if (
            isset($kalora_event_row['event_status']) &&
            $kalora_event_row['event_status'] === 'Active'
        ) {
            $kalora_active_event = $kalora_event_row;
            break;
        }
    }
}

/* end_at (datetime string) -> milliseconds */
$kalora_end_ms = 0;

if ($kalora_active_event) {
    $kalora_ts = strtotime($kalora_active_event['end_at']);
    if ($kalora_ts) {
        $kalora_end_ms = $kalora_ts * 1000;
    }
}
?>

<style>
    .kalora-live-event-section-x91 {
        width: 100%;
    }

    .kalora-live-event-box-x91 {
        position: relative;
        width: 100%;
        min-height: 278px;
        overflow: hidden;
        background: #111;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .kalora-live-event-image-x91 {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        z-index: 1;
    }

    .kalora-live-event-overlay-x91 {
        position: absolute;
        inset: 0;
        background: rgba(0, 0, 0, .35);
        z-index: 2;
    }

    .kalora-live-event-content-x91 {
        position: relative;
        z-index: 3;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-wrap: wrap;
        gap: 20px;
        padding: 20px 15px;
        color: #fff;
    }

    .kalora-live-event-name-x91 {
        font-size: 22px;
        font-weight: 700;
        text-shadow: 0 1px 5px rgba(0, 0, 0, .4);
    }

    .kalora-live-event-countdown-x91 {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .kalora-live-event-countdown-x91 b {
        font-size: 20px;
        color: #fff;
    }

    .kalora-live-time-box-x91 {
        min-width: 58px;
        padding: 8px 6px;
        background: rgba(255, 255, 255, .96);
        border-radius: 8px;
        text-align: center;
        line-height: 1;
    }

    .kalora-live-time-box-x91 strong {
        display: block;
        color: #111;
        font-size: 22px;
        font-weight: 800;
    }

    .kalora-live-time-box-x91 span {
        display: block;
        margin-top: 5px;
        color: #777;
        font-size: 9px;
        letter-spacing: .5px;
    }

    /* Mobile */
    @media (max-width: 700px) {
        .kalora-live-event-content-x91 {
            gap: 10px;
            padding: 14px 10px;
        }

        .kalora-live-event-name-x91 {
            font-size: 15px;
            width: 100%;
            text-align: center;
        }

        .kalora-live-time-box-x91 {
            min-width: 46px;
            padding: 6px 4px;
        }

        .kalora-live-time-box-x91 strong {
            font-size: 17px;
        }

        .kalora-live-time-box-x91 span {
            font-size: 7px;
        }
    }
</style>

<?php if ($kalora_active_event && $kalora_end_ms > 0): ?>

    <section
        id="kaloraLiveEventSectionX91"
        class="kalora-live-event-section-x91"
        data-end="<?php echo (int)$kalora_end_ms; ?>">

        <div class="kalora-live-event-box-x91">

            <?php if (!empty($kalora_active_event['event_image'])): ?>
                <img
                    src="assets/events/<?php echo htmlspecialchars($kalora_active_event['event_image']); ?>"
                    alt="<?php echo htmlspecialchars($kalora_active_event['event_name']); ?>"
                    class="kalora-live-event-image-x91">
            <?php endif; ?>

            <div class="kalora-live-event-overlay-x91"></div>

            <div class="kalora-live-event-content-x91">

                <div class="kalora-live-event-name-x91">
                    <?php echo htmlspecialchars($kalora_active_event['event_name']); ?>
                </div>

                <div class="kalora-live-event-countdown-x91">

                    <div class="kalora-live-time-box-x91">
                        <strong id="kaloraLiveDaysX91">00</strong>
                        <span>DAYS</span>
                    </div>

                    <b>:</b>

                    <div class="kalora-live-time-box-x91">
                        <strong id="kaloraLiveHoursX91">00</strong>
                        <span>HRS</span>
                    </div>

                    <b>:</b>

                    <div class="kalora-live-time-box-x91">
                        <strong id="kaloraLiveMinutesX91">00</strong>
                        <span>MIN</span>
                    </div>

                    <b>:</b>

                    <div class="kalora-live-time-box-x91">
                        <strong id="kaloraLiveSecondsX91">00</strong>
                        <span>SEC</span>
                    </div>

                </div>
            </div>
        </div>

    </section>

    <script>
        (function() {
            "use strict";

            var section = document.getElementById("kaloraLiveEventSectionX91");
            if (!section) return;

            var endTime = parseInt(section.getAttribute("data-end"), 10);
            if (!endTime) {
                section.remove();
                return;
            }

            var elD = document.getElementById("kaloraLiveDaysX91");
            var elH = document.getElementById("kaloraLiveHoursX91");
            var elM = document.getElementById("kaloraLiveMinutesX91");
            var elS = document.getElementById("kaloraLiveSecondsX91");
            var timer = null;

            function pad(n) {
                return String(n).padStart(2, "0");
            }

            function tick() {
                var diff = endTime - Date.now();

                if (diff <= 0) {
                    if (timer) clearInterval(timer);
                    section.remove();
                    return;
                }

                var day = 86400000,
                    hr = 3600000,
                    min = 60000;

                elD.textContent = pad(Math.floor(diff / day));
                elH.textContent = pad(Math.floor((diff % day) / hr));
                elM.textContent = pad(Math.floor((diff % hr) / min));
                elS.textContent = pad(Math.floor((diff % min) / 1000));
            }

            tick(); /* turant pehli value */
            timer = setInterval(tick, 1000);
        })();
    </script>

<?php endif; ?>