
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

           Top Saling Products

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


                        <!-- PRODUCT NAME -->

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
