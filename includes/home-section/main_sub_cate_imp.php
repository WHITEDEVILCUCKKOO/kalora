<?php include "admin_access/db_config.php"; ?>

<div class="klr-rec-wrapper">
    <div class="klr-rec-container">

        <h2 class="klr-rec-title">Top Collections</h2>

        <div class="klr-rec-grid_sdhi">

            <?php
            $klr_recipient_query = "SELECT *
            FROM product_sub_cate
            WHERE sub_cate_status = 'Active'
            AND sub_cate_slug NOT IN ('regular', 'mystery-box', 'mystery-jar')";

            $klr_recipient_stmt = mysqli_prepare($mydb, $klr_recipient_query);

            if ($klr_recipient_stmt) {

                mysqli_stmt_execute($klr_recipient_stmt);
                $klr_recipient_result = mysqli_stmt_get_result($klr_recipient_stmt);

                if ($klr_recipient_result && mysqli_num_rows($klr_recipient_result) > 0) {

                    while ($klr_recipient_row = mysqli_fetch_assoc($klr_recipient_result)) {
            ?>

                        <div class="klr-rec-card"
                            onclick="window.location.href='sab_cate_products.php?slug=<?php echo urlencode($klr_recipient_row['sub_cate_slug']); ?>'">
                            <div class="klr-rec-img-box">
                                <img src="assets/sub_category/<?php echo htmlspecialchars($klr_recipient_row['sub_cate_img']); ?>"
                                    alt="<?php echo htmlspecialchars($klr_recipient_row['sub_cate_slug']); ?>">
                            </div>
                        </div>

            <?php
                    } // end while

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
        background: #fdf6ea !important;
        color: #111111;
        padding: 50px 20px;
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
        margin-bottom: 35px;
        color: #333333;
        animation: klrRecFadeDown 0.8s ease-out;
    }

    /* PC: 5 cards in one row */
    .klr-rec-grid_sdhi {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 20px;
    }

    .klr-rec-card {
        background: linear-gradient(145deg, #0f2c1f 0%, #071911 100%);
        overflow: hidden;
        display: flex;
        flex-direction: column;
        cursor: pointer;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
        transition: transform 0.4s cubic-bezier(0.165, 0.84, 0.44, 1), box-shadow 0.4s ease;
        animation: klrRecScaleUp 0.6s ease-out backwards;
        padding: 12px;
        border-radius: 14px;
    }

    .klr-rec-card:nth-child(1) {
        animation-delay: 0.1s;
    }

    .klr-rec-card:nth-child(2) {
        animation-delay: 0.2s;
    }

    .klr-rec-card:nth-child(3) {
        animation-delay: 0.3s;
    }

    .klr-rec-card:nth-child(4) {
        animation-delay: 0.4s;
    }

    .klr-rec-card:nth-child(5) {
        animation-delay: 0.5s;
    }

    .klr-rec-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
    }

    .klr-rec-img-box {
        position: relative;
        width: 100%;
        padding-top: 75%;
        /* 4:3 Aspect Ratio */
        overflow: hidden;
        background-color: #FDF6EA;
        border-radius: 10px;
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

    .klr-rec-card:hover .klr-rec-img-box img {
        transform: scale(1.04);
    }

    .klr-rec-footer {
        padding: 22px 20px;
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 8px;
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

    .klr-rec-card:hover .klr-rec-arrow {
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

    /* Tablet: 3 cards in one row */
    @media (max-width: 1024px) {
        .klr-rec-grid_sdhi {
            grid-template-columns: repeat(3, 1fr);
            gap: 18px;
        }
    }

    /* Phone: 2 cards in one row */
    @media (max-width: 600px) {
        .klr-rec-grid_sdhi {
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
        }

        .klr-rec-card:last-child {
            grid-column: 1 / -1;
            justify-self: center;
            width: 100%;
        }

        .klr-rec-card {
            padding: 8px;
            border-radius: 12px;
        }

        .klr-rec-img-box {
            border-radius: 8px;
        }

        .klr-rec-title {
            font-size: 12px;
            margin-bottom: 20px;
        }

        .klr-rec-wrapper {
            padding: 20px 10px;
        }

        .fey-wrapper {
            padding: 10px 10px !important;
        }
    }
</style>