<?php
include_once "admin_access/db_config.php";


/* =========================================================
   FIXED SUB CATEGORY SLUG
========================================================= */

$klr_trending_slug = "regular";


/* =========================================================
   DEFAULT VALUES
========================================================= */

$klr_trending_products = [];

$klr_trending_name = "Regular";


/* =========================================================
   PRODUCTS + SUB CATEGORY NAME
   (products.* me product_stock aur price already aate hain)
========================================================= */

$klr_trending_sql = "

    SELECT
        products.*,
        root_categories.*,
        brands.*,
        product_sub_cate.*

    FROM products

    INNER JOIN root_categories
        ON products.root_id = root_categories.root_id

    INNER JOIN brands
        ON products.brand_id = brands.brand_id

    INNER JOIN product_sub_cate
        ON products.sub_cate_id =
           product_sub_cate.sub_cate_id

    WHERE
        product_sub_cate.sub_cate_slug = ?

        AND brands.brand_status = 'Active'

        AND product_sub_cate.sub_cate_status = 'Active'

        AND products.product_status = 'Active'

    ORDER BY RAND()

    LIMIT 10

";


/* =========================================================
   PREPARE
========================================================= */

$klr_trending_stmt = mysqli_prepare(
    $mydb,
    $klr_trending_sql
);


if ($klr_trending_stmt) {


    /* =====================================================
       BIND FIXED SLUG
    ===================================================== */

    mysqli_stmt_bind_param(
        $klr_trending_stmt,
        "s",
        $klr_trending_slug
    );


    /* =====================================================
       EXECUTE
    ===================================================== */

    mysqli_stmt_execute(
        $klr_trending_stmt
    );


    /* =====================================================
       RESULT
    ===================================================== */

    $klr_trending_result =
        mysqli_stmt_get_result(
            $klr_trending_stmt
        );


    /* =====================================================
       FETCH DATA
    ===================================================== */

    if ($klr_trending_result) {

        while (
            $klr_trending_row =
            mysqli_fetch_assoc(
                $klr_trending_result
            )
        ) {


            /* =============================================
               SUB CATEGORY NAME
            ============================================== */

            if (
                isset(
                    $klr_trending_row['sub_cate_name']
                )
                &&
                $klr_trending_name ===
                "Instagram Trending"
            ) {

                $klr_trending_name =
                    $klr_trending_row[
                        'sub_cate_name'
                    ];
            }


            /* =============================================
               PRODUCT ARRAY
            ============================================== */

            $klr_trending_products[] =
                $klr_trending_row;
        }
    }


    /* =====================================================
       CLOSE STATEMENT
    ===================================================== */

    mysqli_stmt_close(
        $klr_trending_stmt
    );
}


/* =========================================================
   HTML ESCAPE
========================================================= */

if (!function_exists('klr_trending_h')) {

    function klr_trending_h($value)
    {
        return htmlspecialchars(
            (string)$value,
            ENT_QUOTES,
            'UTF-8'
        );
    }
}

if (!function_exists('klr_trending_money')) {

    function klr_trending_money($value)
    {
        return '₹ ' . number_format(
            (float)$value,
            2
        );
    }
}

?>


<style>

/* =========================================================
   MAIN SECTION
========================================================= */

.klr-instagram-trending-x91 {

    width: 100%;

    padding: 55px 20px;

    background: #fdf6ea;

    box-sizing: border-box;

    overflow: hidden;
}


.klr-instagram-trending-container-x91 {

    width: 100%;

    max-width: 1300px;

    margin: 0 auto;

    box-sizing: border-box;
}


/* =========================================================
   CATEGORY NAME
========================================================= */

.klr-instagram-trending-title-x91 {

    margin: 0 0 35px;

    text-align: center;

    font-size: 24px;

    font-weight: 500;

    letter-spacing: 2px;

    text-transform: uppercase;

    color: #111111;
}


/* =========================================================
   DESKTOP - 4 PRODUCTS
========================================================= */

.klr-instagram-trending-grid-x91 {

    display: grid;

    grid-template-columns:
        repeat(4, minmax(0, 1fr));

    gap: 24px;

    width: 100%;
}


/* =========================================================
   PRODUCT CARD
========================================================= */

.klr-instagram-trending-card-x91 {

    position: relative;

    min-width: 0;

    background:
        linear-gradient(
            145deg,
            #0f2c1f 0%,
            #071911 100%
        );

    padding: 15px;

    border-radius: 12px;

    box-sizing: border-box;

    cursor: pointer;

    transition:
        transform 0.35s ease,
        box-shadow 0.35s ease;
}


.klr-instagram-trending-card-x91:hover {

    transform: translateY(-6px);

    box-shadow:
        0 15px 35px
        rgba(0, 0, 0, 0.15);
}


/* =========================================================
   IMAGE
========================================================= */

.klr-instagram-trending-image-x91 {

    position: relative;

    width: 100%;

    padding-top: 115%;

    overflow: hidden;

    background: #f7f7f7;

    border-radius: 8px;
}


.klr-instagram-trending-image-x91 img {

    position: absolute;

    top: 0;

    left: 0;

    width: 100%;

    height: 100%;

    object-fit: cover;

    transition:
        transform 0.5s ease;
}


.klr-instagram-trending-card-x91:hover
.klr-instagram-trending-image-x91 img {

    transform: scale(1.05);
}


/* =========================================================
   PRODUCT DETAILS
========================================================= */

.klr-instagram-trending-details-x91 {

    padding:
        15px
        5px
        5px;

    text-align: center;
}


.klr-instagram-trending-name-x91 {

    margin: 0;

    color: #ffffff;

    font-size: 14px;

    line-height: 1.45;

    font-weight: 400;

    display: -webkit-box;

    -webkit-line-clamp: 2;

    -webkit-box-orient: vertical;

    overflow: hidden;
}


/* =========================================================
   PRICE
========================================================= */

.klr-instagram-trending-price-x91 {

    display: flex;

    justify-content: center;

    align-items: center;

    flex-wrap: wrap;

    gap: 8px;

    margin-top: 8px;
}


.klr-instagram-trending-current-x91 {

    color: #ffffff;

    font-size: 14px;

    font-weight: 600;
}


.klr-instagram-trending-old-x91 {

    color: #e9a4a4;

    font-size: 12px;

    text-decoration: line-through;
}


/* =========================================================
   STOCK MESSAGE
========================================================= */

.klr-instagram-trending-stock-x91 {

    display: block;

    margin-top: 8px;

    font-size: 16px;

    font-weight: 600;

    white-space: nowrap;
}


.klr-instagram-trending-stock-low-x91 {

    color: #ff9800;
}


.klr-instagram-trending-stock-out-x91 {

    color: #ff5252;
}


/* =========================================================
   DISCOUNT TAG (image ke top-left me)
========================================================= */

.klr-instagram-trending-tag-x91 {

    position: absolute;

    top: 12px;

    left: 0;

    z-index: 2;

    padding: 6px 22px 6px 10px;

    background: #d62828;

    color: #ffffff;

    font-size: 12px;

    font-weight: 700;

    letter-spacing: 0.5px;

    line-height: 1;

    white-space: nowrap;

    clip-path: polygon(0 0, 84% 0, 100% 50%, 84% 100%, 0 100%);

    overflow: hidden;

    transform-origin: left center;

    animation:
        klrTrendingTagInX91 0.7s ease-out backwards,
        klrTrendingTagPulseX91 2.2s ease-in-out 0.7s infinite;

    transition: background 0.3s ease;
}


/* Chamak (shine) */

.klr-instagram-trending-tag-x91::before {

    content: '';

    position: absolute;

    top: 0;

    left: -60%;

    width: 40%;

    height: 100%;

    background: linear-gradient(
        100deg,
        rgba(255, 255, 255, 0) 0%,
        rgba(255, 255, 255, 0.55) 50%,
        rgba(255, 255, 255, 0) 100%
    );

    transform: skewX(-20deg);

    animation: klrTrendingTagShineX91 2.8s ease-in-out 1s infinite;
}


.klr-instagram-trending-tag-x91::after {

    content: '';

    position: absolute;

    right: 8px;

    top: 50%;

    width: 4px;

    height: 4px;

    background: #ffffff;

    border-radius: 50%;

    transform: translateY(-50%);
}


/* Hover par gehra laal + tez dhadkan */

.klr-instagram-trending-card-x91:hover
.klr-instagram-trending-tag-x91 {

    background: #b71c1c;

    animation:
        klrTrendingTagPulseX91 1s ease-in-out infinite;
}


@keyframes klrTrendingTagInX91 {

    from {
        opacity: 0;
        transform: translateX(-100%);
    }

    to {
        opacity: 1;
        transform: translateX(0);
    }
}


@keyframes klrTrendingTagPulseX91 {

    0%, 100% {
        transform: scale(1);
    }

    50% {
        transform: scale(1.08);
    }
}


@keyframes klrTrendingTagShineX91 {

    0% {
        left: -60%;
    }

    60%, 100% {
        left: 140%;
    }
}


@media (prefers-reduced-motion: reduce) {

    .klr-instagram-trending-tag-x91,
    .klr-instagram-trending-tag-x91::before {

        animation: none;
    }
}


/* =========================================================
   EMPTY MESSAGE
========================================================= */

.klr-instagram-trending-empty-x91 {

    width: 100%;

    padding:
        60px
        20px;

    box-sizing: border-box;

    text-align: center;

    color: #5c4d43;

    font-size: 17px;

    font-weight: 500;

    letter-spacing: 2px;

    border:
        1px dashed
        #c9b99f;

    border-radius: 12px;

    background:
        rgba(
            255,
            255,
            255,
            0.25
        );
}


/* =========================================================
   TABLET
   2 PRODUCTS AT ONE TIME
========================================================= */

@media (max-width: 1024px) {

    .klr-instagram-trending-x91 {

        padding:
            45px
            18px;
    }


    .klr-instagram-trending-title-x91 {

        font-size: 22px;

        margin-bottom: 28px;
    }


    .klr-instagram-trending-grid-x91 {

        display: flex;

        flex-wrap: nowrap;

        overflow-x: auto;

        gap: 18px;

        scroll-behavior: smooth;

        scrollbar-width: none;

        -webkit-overflow-scrolling: touch;

        padding-bottom: 8px;
    }


    .klr-instagram-trending-grid-x91::-webkit-scrollbar {

        display: none;
    }


    .klr-instagram-trending-card-x91 {

        flex:
            0 0
            calc(
                (100% - 18px) / 2
            );

        width:
            calc(
                (100% - 18px) / 2
            );
    }
}


/* =========================================================
   MOBILE
   2 PRODUCTS AT ONE TIME
========================================================= */

@media (max-width: 580px) {

    .klr-instagram-trending-x91 {

        padding:
            40px
            14px;
    }


    .klr-instagram-trending-title-x91 {

        font-size: 18px;

        letter-spacing: 1.5px;

        margin-bottom: 24px;
    }


    .klr-instagram-trending-grid-x91 {

        gap: 12px;
    }


    .klr-instagram-trending-card-x91 {

        flex:
            0 0
            calc(
                (100% - 12px) / 2
            );

        width:
            calc(
                (100% - 12px) / 2
            );

        padding: 10px;

        border-radius: 10px;
    }


    .klr-instagram-trending-name-x91 {

        font-size: 12px;
    }


    .klr-instagram-trending-current-x91 {

        font-size: 13px;
    }


    .klr-instagram-trending-old-x91 {

        font-size: 11px;
    }


    .klr-instagram-trending-tag-x91 {

        top: 8px;

        font-size: 10px;

        padding: 5px 18px 5px 8px;
    }
}

</style>


<!-- =====================================================
     MAIN SECTION
===================================================== -->

<section
    class="klr-instagram-trending-x91"
>

    <div
        class="klr-instagram-trending-container-x91"
    >


        <!-- =================================================
             SUB CATEGORY NAME
        ================================================== -->

        <h2
            class="klr-instagram-trending-title-x91"
        >

            Top Selling Products

        </h2>


        <?php if (
            !empty(
                $klr_trending_products
            )
        ) { ?>


            <!-- =================================================
                 PRODUCT GRID / MOBILE SLIDER
            ================================================== -->

            <div
                class="klr-instagram-trending-grid-x91"
                id="klrInstagramTrendingSliderX91"
            >


                <?php foreach (
                    $klr_trending_products
                    as $klr_trending_product
                ) { ?>


                    <?php

                    /* =========================================
                       PRODUCT IMAGE
                    ========================================== */

                    $klr_trending_image =
                        trim(
                            (string)
                            $klr_trending_product[
                                'product_image'
                            ]
                        );


                    /* =========================================
                       DISCOUNT % (sirf jab discount Show ho)
                    ========================================== */

                    $klr_trending_orig =
                        (float)
                        ($klr_trending_product[
                            'original_price'
                        ] ?? 0);


                    $klr_trending_sale =
                        (float)
                        ($klr_trending_product[
                            'sale_price'
                        ] ?? 0);


                    $klr_trending_pct = 0;


                    if (

                        ($klr_trending_product[
                            'discount_visibility'
                        ] ?? '') === 'Show'

                        &&

                        $klr_trending_sale > 0

                        &&

                        $klr_trending_orig > $klr_trending_sale

                    ) {

                        $klr_trending_pct =
                            (int) round(
                                (
                                    ($klr_trending_orig - $klr_trending_sale)
                                    /
                                    $klr_trending_orig
                                ) * 100
                            );
                    }


                    /* =========================================
                       PRICE
                       Show + sale price => sale price (original kata hua)
                       Hide              => sirf original price
                    ========================================== */

                    $klr_trending_current = 0;

                    $klr_trending_old = 0;


                    if (

                        ($klr_trending_product[
                            'discount_visibility'
                        ] ?? '') === 'Show'

                        &&

                        $klr_trending_sale > 0

                    ) {

                        $klr_trending_current =
                            $klr_trending_sale;

                        if ($klr_trending_orig > $klr_trending_sale) {

                            $klr_trending_old =
                                $klr_trending_orig;
                        }

                    } else {

                        $klr_trending_current =
                            $klr_trending_orig;
                    }


                    /* =========================================
                       STOCK MESSAGE
                       0      => Out of stock
                       1 - 9  => Only X left
                       10+    => kuch nahi
                    ========================================== */

                    $klr_trending_stock =
                        (int)
                        ($klr_trending_product[
                            'product_stock'
                        ] ?? 0);


                    $klr_trending_stock_msg = '';

                    $klr_trending_stock_class = '';


                    if ($klr_trending_stock <= 0) {

                        $klr_trending_stock_msg =
                            'Out of stock';

                        $klr_trending_stock_class =
                            'klr-instagram-trending-stock-out-x91';

                    } elseif ($klr_trending_stock < 10) {

                        $klr_trending_stock_msg =
                            'Only '
                            . $klr_trending_stock
                            . ' left';

                        $klr_trending_stock_class =
                            'klr-instagram-trending-stock-low-x91';
                    }


                    /* =========================================
                       PRODUCT URL
                    ========================================== */

                    $klr_trending_link =
                        "product_details.php?slug="
                        .
                        urlencode(
                            $klr_trending_product[
                                'product_slug'
                            ]
                        );

                    ?>


                    <!-- =====================================
                         PRODUCT CARD
                    ====================================== -->

                    <div
                        class="klr-instagram-trending-card-x91"

                        onclick="window.location.href='<?= klr_trending_h(
                            $klr_trending_link
                        ); ?>'"
                    >


                        <!-- PRODUCT IMAGE -->

                        <div
                            class="klr-instagram-trending-image-x91"
                        >

                            <?php if ($klr_trending_pct > 0) { ?>

                                <span class="klr-instagram-trending-tag-x91">
                                    <?= $klr_trending_pct; ?>% OFF
                                </span>

                            <?php } ?>


                            <?php if (
                                $klr_trending_image !== ''
                            ) { ?>

                                <img
                                    src="<?= klr_trending_h(
                                        $klr_trending_image
                                    ); ?>"

                                    alt="<?= klr_trending_h(
                                        $klr_trending_product[
                                            'product_name'
                                        ]
                                    ); ?>"

                                    loading="lazy"
                                >

                            <?php } ?>

                        </div>


                        <!-- PRODUCT NAME + STOCK -->

                        <div
                            class="klr-instagram-trending-details-x91"
                        >

                            <p
                                class="klr-instagram-trending-name-x91"
                            >

                                <?= klr_trending_h(
                                    $klr_trending_product[
                                        'product_name'
                                    ]
                                ); ?>

                            </p>


                            <?php if ($klr_trending_current > 0) { ?>

                                <div class="klr-instagram-trending-price-x91">

                                    <span class="klr-instagram-trending-current-x91">
                                        <?= klr_trending_money(
                                            $klr_trending_current
                                        ); ?>
                                    </span>

                                    <?php if ($klr_trending_old > 0) { ?>

                                        <span class="klr-instagram-trending-old-x91">
                                            <?= klr_trending_money(
                                                $klr_trending_old
                                            ); ?>
                                        </span>

                                    <?php } ?>

                                    <?php if (
                                $klr_trending_stock_msg !== ''
                            ) { ?>

                                <span style="margin: 0;"
                                    class="klr-instagram-trending-stock-x91 <?= $klr_trending_stock_class; ?>"
                                >
                                    <?= klr_trending_h(
                                        $klr_trending_stock_msg
                                    ); ?>
                                </span>

                            <?php } ?>

                                </div>

                            <?php } ?>


                            

                        </div>


                    </div>


                <?php } ?>


            </div>


        <?php } else { ?>


            <!-- =================================================
                 NO PRODUCTS
            ================================================== -->

            <div
                class="klr-instagram-trending-empty-x91"
            >

                PRODUCT COMING SOON

            </div>


        <?php } ?>


    </div>

</section>


<?php if (
    !empty(
        $klr_trending_products
    )
) { ?>


<script>

(function () {


    /* =====================================================
       SLIDER ELEMENT
    ===================================================== */

    const
    klrInstagramTrendingSliderX91 =
        document.getElementById(
            'klrInstagramTrendingSliderX91'
        );


    if (
        !klrInstagramTrendingSliderX91
    ) {

        return;

    }


    let
    klrInstagramTrendingAutoX91 =
        null;


    /* =====================================================
       START AUTO SLIDER
    ===================================================== */

    function
    klrInstagramTrendingStartX91()
    {


        /*
         * DESKTOP PAR AUTO SLIDER NAHI
         * DESKTOP PAR 4 CARD GRID HOGA
         */

        if (
            window.innerWidth > 1024
        ) {

            return;

        }


        /*
         * PURANA INTERVAL CLEAR
         */

        clearInterval(
            klrInstagramTrendingAutoX91
        );


        /*
         * 2 SECOND AUTO SLIDER
         */

        klrInstagramTrendingAutoX91 =
            setInterval(
                function ()
                {


                    /*
                     * TOTAL SCROLL
                     */

                    const
                    klrInstagramTrendingMaxScrollX91 =
                        klrInstagramTrendingSliderX91
                        .scrollWidth
                        -
                        klrInstagramTrendingSliderX91
                        .clientWidth;


                    /*
                     * AGAR SLIDER KI NEED NAHI HAI
                     */

                    if (
                        klrInstagramTrendingMaxScrollX91
                        <=
                        5
                    ) {

                        return;

                    }


                    /*
                     * LAST PRODUCTS PAR HAI
                     */

                    if (

                        klrInstagramTrendingSliderX91
                        .scrollLeft
                        >=
                        klrInstagramTrendingMaxScrollX91
                        -
                        5

                    ) {


                        /*
                         * WAPAS FIRST 2 PRODUCTS
                         */

                        klrInstagramTrendingSliderX91.scrollTo({

                            left: 0,

                            behavior: 'smooth'

                        });


                    } else {


                        /*
                         * NEXT 2 PRODUCTS
                         */

                        klrInstagramTrendingSliderX91.scrollBy({

                            left:
                                klrInstagramTrendingSliderX91
                                .clientWidth,

                            behavior: 'smooth'

                        });

                    }


                },

                2000
            );

    }


    /* =====================================================
       STOP AUTO SLIDER
    ===================================================== */

    function
    klrInstagramTrendingStopX91()
    {

        clearInterval(
            klrInstagramTrendingAutoX91
        );

    }


    /* =====================================================
       INITIAL START
    ===================================================== */

    klrInstagramTrendingStartX91();


    /* =====================================================
       WINDOW RESIZE
    ===================================================== */

    window.addEventListener(
        'resize',
        function ()
        {

            klrInstagramTrendingStopX91();

            klrInstagramTrendingStartX91();

        }
    );


    /* =====================================================
       MOBILE / TABLET TOUCH
    ===================================================== */

    klrInstagramTrendingSliderX91.addEventListener(

        'touchstart',

        function ()
        {

            /*
             * USER SWIPE KAR RAHA HAI
             * AUTO SLIDER STOP
             */

            klrInstagramTrendingStopX91();

        },

        {
            passive: true
        }

    );


    klrInstagramTrendingSliderX91.addEventListener(

        'touchend',

        function ()
        {

            /*
             * USER KE SWIPE KE 4 SECOND BAAD
             * AUTO SLIDER PHIR START
             */

            setTimeout(
                function ()
                {

                    klrInstagramTrendingStartX91();

                },
                4000
            );

        },

        {
            passive: true
        }

    );


})();

</script>


<?php } ?>