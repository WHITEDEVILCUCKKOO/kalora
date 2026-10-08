<?php include"admin_access/db_config.php" ?>

<div class="klr-rec-wrapper">
    <div class="klr-rec-container">

        <h2 class="klr-rec-title">SHOP BY RECIPIENT</h2>

        <div class="klr-rec-grid_sdasdi">


           <?php

            $klr_recipient_query = "SELECT home_jar_img_1, home_jar_img_2 FROM extra_imgs LIMIT 1";

            $klr_recipient_stmt = mysqli_prepare($mydb, $klr_recipient_query);

            if ($klr_recipient_stmt) {

                mysqli_stmt_execute($klr_recipient_stmt);

                $klr_recipient_result = mysqli_stmt_get_result($klr_recipient_stmt);

                if ($klr_recipient_result && mysqli_num_rows($klr_recipient_result) > 0) {

                    $klr_recipient_row = mysqli_fetch_assoc($klr_recipient_result);

            ?>


            <!-- Card 1: Gifts For Her -->
            <div class="klr-rec-card_12" onclick="window.location.href='sab_cate_products.php?slug=mystery-jar'">
                <div class="klr-rec-img-box">
                    <img src="assets/extra_home_img/<?php echo htmlspecialchars($klr_recipient_row['home_jar_img_1']); ?>" alt="Gifts For Her">
                </div>
                <!-- <div class="klr-rec-footer">
                    <span class="klr-rec-label">Mystery Jar</span>
                    <span class="klr-rec-arrow">
                        <svg viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                        </svg>
                    </span>
                </div> -->
            </div>

            <!-- Card 2: Gifts For Him -->
            <div class="klr-rec-card_12" onclick="window.location.href='sab_cate_products.php?slug=mystery-box'">
                <div class="klr-rec-img-box">
                    <img src="assets/extra_home_img/<?php echo htmlspecialchars($klr_recipient_row['home_jar_img_2']); ?>" alt="Gifts For Him">
                </div>
                <!-- <div class="klr-rec-footer">
                    <span class="klr-rec-label">Mystery Box</span>
                    <span class="klr-rec-arrow">
                        <svg viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                        </svg>
                    </span>
                </div> -->
            </div>


  <?php

                } else {

                    echo '<p style="text-align:center;">No recipient images found.</p>';

                }

                mysqli_stmt_close($klr_recipient_stmt);
            }

            ?>

        </div>

    </div>
</div>

<style>
    .klr-rec-wrapper {
        font-family: Arial, sans-serif;
        /* background-color: #ffffff; */
        background: #fdf6ea !important;
        color: #111111;
        padding: 60px 20px;
        box-sizing: border-box;
        width: 100%;
    }

    .klr-rec-container {
        max-width: 1300px;
        margin: 0 auto;
        box-sizing: border-box;
    }

    .klr-rec-title {
        text-align: center;
        font-size: 13px;
        letter-spacing: 3px;
        font-weight: 500;
        margin-bottom: 40px;
        color: #333333;
        animation: klrRecFadeDown 0.8s ease-out;
    }

    .klr-rec-grid_sdasdi {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 30px;
    }

    .klr-rec-card_12 {
        /* background-color: #f6f5f0;*/
        background: linear-gradient(145deg, #0f2c1f 0%, #071911 100%);
        border-radius: 4px;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        cursor: pointer;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
        transition: transform 0.4s cubic-bezier(0.165, 0.84, 0.44, 1), box-shadow 0.4s ease;
        animation: klrRecScaleUp 0.6s ease-out backwards;
        padding: 18px;
        border-radius: 15px;
    }

    .klr-rec-card_12:nth-child(1) {
        animation-delay: 0.1s;
    }

    .klr-rec-card_12:nth-child(2) {
        animation-delay: 0.2s;
    }

    .klr-rec-card_12:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
    }

    .klr-rec-img-box {
        position: relative;
        width: 100%;
        padding-top: 75%;
        /* 4:3 Aspect Ratio */
        overflow: hidden;
        /* background-color: #eae6df; */
        background-color: #FDF6EA;
        border-radius: 15px;
    }

    .klr-rec-img-box img {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.6s ease;
    }

    .klr-rec-card_12:hover .klr-rec-img-box img {
        transform: scale(1.04);
    }

    .klr-rec-footer {
        padding: 22px 20px;
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 8px;
        /* background-color: #f7f5f0; */
        background: linear-gradient(145deg, #0f2c1f 0%, #071911 100%);
    }

    .klr-rec-label {
        font-size: 18px;
        font-weight: 500;
        letter-spacing: 0.5px;
        color: #fffefe;
    }

    .klr-rec-arrow {
        width: 18px;
        height: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: transform 0.3s ease;
    }

    .klr-rec-card_12:hover .klr-rec-arrow {
        transform: translateX(4px);
    }

    .klr-rec-arrow svg {
        width: 14px;
        height: 14px;
        fill: none;
        stroke: #1a1a1a;
        stroke-width: 2;
    }

    @keyframes klrRecFadeDown {
        from {
            opacity: 0;
            transform: translateY(-15px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes klrRecScaleUp {
        from {
            opacity: 0;
            transform: scale(0.97);
        }

        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    @media (max-width: 768px) {
        .klr-rec-grid_sdasdi {
            grid-template-columns:1fr 1fr;
            gap: 24px;
        }

        .klr-rec-title {
            font-size: 12px;
        }

        .klr-rec-img-box{
            height: 150px;
        }

        .klr-rec-wrapper{
            padding: 10px 10px;
        }
        .fey-wrapper{
            padding: 10px 10px !important;
        }

        .klr-rec-card_12 {
            padding: 0;
            background: none;
            /* gap: 5px; */
        }
.klr-rec-grid_sdasdi{
    gap: 8px;

}
        
        
    }
</style>