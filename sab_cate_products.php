<?php
include_once 'admin_access/db_config.php';
?>

<?php include_once 'includes/header.php' ?>

<style>
    main { overflow: hidden; }
</style>
 
<main>
    

<?php
include_once "admin_access/db_config.php";


/* =========================================================
   URL SE SUB CATEGORY SLUG
========================================================= */

$klr_sub_slug = isset($_GET['slug'])
    ? trim($_GET['slug'])
    : '';


/* =========================================================
   HELPER FUNCTIONS
========================================================= */

if (!function_exists('klr_sub_h')) {

    function klr_sub_h($value)
    {
        return htmlspecialchars(
            (string)$value,
            ENT_QUOTES,
            'UTF-8'
        );
    }
}


if (!function_exists('klr_sub_money')) {

    function klr_sub_money($value)
    {
        return '₹ ' . number_format(
            (float)$value,
            2
        );
    }
}


/* =========================================================
   DEFAULT VALUES
========================================================= */

$klr_sub_products = [];

$klr_sub_category_name = '';


/* =========================================================
   SLUG AVAILABLE HAI TO DATA FETCH KARO
========================================================= */

if ($klr_sub_slug !== '') {


    /* =====================================================
       SUB CATEGORY + PRODUCTS QUERY

       Maximum 15 RANDOM products
    ===================================================== */

    $klr_sub_sql = "

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

        LIMIT 15

    ";


    /* =====================================================
       PREPARE
    ===================================================== */

    $klr_sub_stmt = mysqli_prepare(
        $mydb,
        $klr_sub_sql
    );


    if ($klr_sub_stmt) {


        /* =================================================
           BIND SLUG
        ================================================= */

        mysqli_stmt_bind_param(
            $klr_sub_stmt,
            "s",
            $klr_sub_slug
        );


        /* =================================================
           EXECUTE
        ================================================= */

        mysqli_stmt_execute(
            $klr_sub_stmt
        );


        /* =================================================
           GET RESULT
        ================================================= */

        $klr_sub_result =
            mysqli_stmt_get_result(
                $klr_sub_stmt
            );


        /* =================================================
           FETCH PRODUCTS
        ================================================= */

        if ($klr_sub_result) {

            while (
                $klr_sub_row =
                mysqli_fetch_assoc(
                    $klr_sub_result
                )
            ) {


                /* =========================================
                   CATEGORY NAME
                ========================================= */

                if (
                    $klr_sub_category_name === ''
                    &&
                    isset(
                        $klr_sub_row['sub_cate_name']
                    )
                ) {

                    $klr_sub_category_name =
                        $klr_sub_row['sub_cate_name'];
                }


                /* =========================================
                   PRODUCT ARRAY
                ========================================= */

                $klr_sub_products[] =
                    $klr_sub_row;
            }
        }


        /* =================================================
           CLOSE STATEMENT
        ================================================= */

        mysqli_stmt_close(
            $klr_sub_stmt
        );
    }


    /* =====================================================
       AGAR CATEGORY NAME PRODUCT QUERY SE NA AAYA
       TO ALAG SE CATEGORY NAME FETCH KARO
    ===================================================== */

    if ($klr_sub_category_name === '') {


        $klr_sub_name_sql = "

            SELECT
                sub_cate_name

            FROM product_sub_cate

            WHERE
                sub_cate_slug = ?

                AND sub_cate_status = 'Active'

            LIMIT 1

        ";


        $klr_sub_name_stmt =
            mysqli_prepare(
                $mydb,
                $klr_sub_name_sql
            );


        if ($klr_sub_name_stmt) {


            mysqli_stmt_bind_param(
                $klr_sub_name_stmt,
                "s",
                $klr_sub_slug
            );


            mysqli_stmt_execute(
                $klr_sub_name_stmt
            );


            $klr_sub_name_result =
                mysqli_stmt_get_result(
                    $klr_sub_name_stmt
                );


            if (
                $klr_sub_name_result
                &&
                mysqli_num_rows(
                    $klr_sub_name_result
                ) > 0
            ) {

                $klr_sub_name_row =
                    mysqli_fetch_assoc(
                        $klr_sub_name_result
                    );


                $klr_sub_category_name =
                    $klr_sub_name_row[
                        'sub_cate_name'
                    ];
            }


            mysqli_stmt_close(
                $klr_sub_name_stmt
            );
        }
    }
}


/* =========================================================
   CATEGORY NAME FALLBACK
========================================================= */

if ($klr_sub_category_name === '') {

    $klr_sub_category_name = 'Products';
}

?>


<style>

/* =========================================================
   MAIN SECTION
========================================================= */

.klr-sub-product-section-x82 {

    width: 100%;

    padding: 55px 20px;

    background: #fdf6ea;

    box-sizing: border-box;

    overflow: hidden;
}


.klr-sub-product-container-x82 {

    width: 100%;

    max-width: 1300px;

    margin: 0 auto;

    box-sizing: border-box;
}


/* =========================================================
   HEADING
========================================================= */

.klr-sub-product-heading-x82 {

    margin: 0 0 35px;

    text-align: center;

    color: #111111;

    font-size: 24px;

    font-weight: 500;

    letter-spacing: 2px;

    text-transform: uppercase;
}


/* =========================================================
   PRODUCT GRID
========================================================= */

.klr-sub-product-grid-x82 {

    display: grid;

    grid-template-columns:
        repeat(4, minmax(0, 1fr));

    gap: 24px;

    width: 100%;
}


/* =========================================================
   PRODUCT CARD
========================================================= */

.klr-sub-product-card-x82 {

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


.klr-sub-product-card-x82:hover {

    transform: translateY(-6px);

    box-shadow:
        0 15px 35px
        rgba(0, 0, 0, 0.12);
}


/* =========================================================
   PRODUCT IMAGE
========================================================= */

.klr-sub-product-image-x82 {

    position: relative;

    width: 100%;

    padding-top: 115%;

    overflow: hidden;

    background: #f7f7f7;

    border-radius: 8px;
}


.klr-sub-product-image-x82 img {

    position: absolute;

    top: 0;

    left: 0;

    width: 100%;

    height: 100%;

    object-fit: cover;

    transition:
        transform 0.5s ease;
}


.klr-sub-product-card-x82:hover
.klr-sub-product-image-x82 img {

    transform: scale(1.05);
}


/* =========================================================
   PRODUCT DETAILS
========================================================= */

.klr-sub-product-details-x82 {

    padding:
        15px
        5px
        5px;

    text-align: center;
}


.klr-sub-product-name-x82 {

    margin:
        0
        0
        8px;

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

.klr-sub-product-price-x82 {

    display: flex;

    justify-content: center;

    align-items: center;

    gap: 8px;
}


.klr-sub-product-current-price-x82 {

    color: #ffffff;

    font-size: 14px;

    font-weight: 600;
}


.klr-sub-product-old-price-x82 {

    color: #e9a4a4;

    font-size: 12px;

    text-decoration: line-through;
}


/* =========================================================
   COMING SOON
========================================================= */

.klr-sub-product-empty-x82 {

    width: 100%;

    padding: 65px 20px;

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

    background: rgba(
        255,
        255,
        255,
        0.25
    );
}


/* =========================================================
   TABLET
========================================================= */

@media (max-width: 1024px) {


    .klr-sub-product-section-x82 {

        padding:
            45px
            18px;
    }


    .klr-sub-product-heading-x82 {

        font-size: 22px;

        margin-bottom: 28px;
    }


    /*
       TABLET PAR GRID KO
       HORIZONTAL SLIDER BANA RAHE HAIN
    */

    .klr-sub-product-grid-x82 {

        display: flex;

        flex-wrap: nowrap;

        overflow-x: auto;

        gap: 18px;

        scroll-behavior: smooth;

        scrollbar-width: none;

        -webkit-overflow-scrolling: touch;

        padding-bottom: 8px;
    }


    .klr-sub-product-grid-x82::-webkit-scrollbar {

        display: none;
    }


    .klr-sub-product-card-x82 {

        flex:
            0 0
            calc(
                (100% - 18px) / 2
            );
    }
}


/* =========================================================
   MOBILE
========================================================= */

@media (max-width: 580px) {


    .klr-sub-product-section-x82 {

        padding:
            40px
            14px;
    }


    .klr-sub-product-heading-x82 {

        font-size: 18px;

        letter-spacing: 1.5px;

        margin-bottom: 24px;
    }


    .klr-sub-product-grid-x82 {

        gap: 14px;
    }


    .klr-sub-product-card-x82 {

        flex:
            0 0
            78%;

        padding: 11px;

        border-radius: 10px;
    }


    .klr-sub-product-name-x82 {

        font-size: 13px;
    }


    .klr-sub-product-current-price-x82 {

        font-size: 13px;
    }


    .klr-sub-product-old-price-x82 {

        font-size: 11px;
    }
}

</style>


<!-- =====================================================
     SECTION
===================================================== -->

<section
    class="klr-sub-product-section-x82"
>

    <div
        class="klr-sub-product-container-x82"
    >


        <!-- =============================================
             CATEGORY NAME
        ============================================== -->

        <h2
            class="klr-sub-product-heading-x82"
        >
            <?= klr_sub_h(
                $klr_sub_category_name
            ); ?>
        </h2>


        <?php if (!empty($klr_sub_products)) { ?>


            <!-- =========================================
                 PRODUCT SLIDER / GRID
            ========================================== -->

            <div
                class="klr-sub-product-grid-x82"
                id="klrSubProductSliderX82"
            >


                <?php foreach (
                    $klr_sub_products
                    as $klr_sub_product
                ) { ?>


                    <?php

                    /* =================================
                       PRODUCT IMAGE
                    ================================= */

                    $klr_sub_product_image =
                        trim(
                            (string)
                            $klr_sub_product[
                                'product_image'
                            ]
                        );


                    /* =================================
                       PRICES
                    ================================= */

                    $klr_sub_original_price =
                        (float)
                        $klr_sub_product[
                            'original_price'
                        ];


                    $klr_sub_sale_price =
                        (float)
                        $klr_sub_product[
                            'sale_price'
                        ];


                    $klr_sub_current_price = 0;

                    $klr_sub_old_price = 0;


                    /* =================================
                       DISCOUNT LOGIC
                    ================================= */

                    if (

                        isset(
                            $klr_sub_product[
                                'discount_visibility'
                            ]
                        )

                        &&

                        $klr_sub_product[
                            'discount_visibility'
                        ] === 'Show'

                        &&

                        $klr_sub_sale_price > 0

                    ) {


                        $klr_sub_current_price =
                            $klr_sub_sale_price;


                        if (

                            $klr_sub_original_price
                            >
                            $klr_sub_sale_price

                        ) {

                            $klr_sub_old_price =
                                $klr_sub_original_price;
                        }


                    } else {


                        $klr_sub_current_price =
                            $klr_sub_original_price;
                    }


                    /* =================================
                       PRODUCT URL
                    ================================= */

                    $klr_sub_product_link =
                        'product_details.php?slug='
                        .
                        urlencode(
                            $klr_sub_product[
                                'product_slug'
                            ]
                        );

                    ?>


                    <!-- =================================
                         PRODUCT CARD
                    ================================== -->

                    <div
                        class="klr-sub-product-card-x82"
                        onclick="window.location.href='<?= klr_sub_h(
                            $klr_sub_product_link
                        ); ?>'"
                    >


                        <!-- IMAGE -->

                        <div
                            class="klr-sub-product-image-x82"
                        >


                            <?php if (
                                $klr_sub_product_image !== ''
                            ) { ?>


                                <img
                                    src="<?= klr_sub_h(
                                        $klr_sub_product_image
                                    ); ?>"
                                    alt="<?= klr_sub_h(
                                        $klr_sub_product[
                                            'product_name'
                                        ]
                                    ); ?>"
                                    loading="lazy"
                                >


                            <?php } ?>


                        </div>


                        <!-- DETAILS -->

                        <div
                            class="klr-sub-product-details-x82"
                        >


                            <p
                                class="klr-sub-product-name-x82"
                            >
                                <?= klr_sub_h(
                                    $klr_sub_product[
                                        'product_name'
                                    ]
                                ); ?>
                            </p>


                            <?php if (
                                $klr_sub_current_price > 0
                            ) { ?>


                                <div
                                    class="klr-sub-product-price-x82"
                                >


                                    <span
                                        class="klr-sub-product-current-price-x82"
                                    >
                                        <?= klr_sub_money(
                                            $klr_sub_current_price
                                        ); ?>
                                    </span>


                                    <?php if (
                                        $klr_sub_old_price > 0
                                    ) { ?>


                                        <span
                                            class="klr-sub-product-old-price-x82"
                                        >
                                            <?= klr_sub_money(
                                                $klr_sub_old_price
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


            <!-- =========================================
                 NO PRODUCT
            ========================================== -->

            <div
                class="klr-sub-product-empty-x82"
            >
                PRODUCT COMING SOON
            </div>


        <?php } ?>


    </div>

</section>


<?php if (!empty($klr_sub_products)) { ?>


<script>

(function () {


    const klrSubSliderX82 =
        document.getElementById(
            'klrSubProductSliderX82'
        );


    if (!klrSubSliderX82) {

        return;
    }


    let klrSubAutoScrollX82 = null;


    /* =================================================
       AUTO SLIDER START
    ================================================= */

    function klrSubStartAutoScrollX82() {


        /*
           Desktop par auto slider nahi.
           Desktop par 4 cards grid rahega.
        */

        if (
            window.innerWidth > 1024
        ) {

            return;
        }


        clearInterval(
            klrSubAutoScrollX82
        );


        klrSubAutoScrollX82 =
            setInterval(
                function () {


                    const
                    klrSubMaxScrollX82 =
                        klrSubSliderX82.scrollWidth
                        -
                        klrSubSliderX82.clientWidth;


                    /*
                       Agar content slider se chhota hai
                    */

                    if (
                        klrSubMaxScrollX82 <= 5
                    ) {

                        return;
                    }


                    /*
                       Last par pahunch gaya
                       to beginning par wapas
                    */

                    if (

                        klrSubSliderX82.scrollLeft
                        >=
                        klrSubMaxScrollX82 - 5

                    ) {


                        klrSubSliderX82.scrollTo({

                            left: 0,

                            behavior: 'smooth'

                        });


                    } else {


                        /*
                           Next products
                        */

                        klrSubSliderX82.scrollBy({

                            left:
                                klrSubSliderX82.clientWidth
                                *
                                0.75,

                            behavior: 'smooth'

                        });

                    }


                },

                3000
            );
    }


    /* =================================================
       AUTO SLIDER STOP
    ================================================= */

    function klrSubStopAutoScrollX82() {

        clearInterval(
            klrSubAutoScrollX82
        );

    }


    /* =================================================
       START
    ================================================= */

    klrSubStartAutoScrollX82();


    /* =================================================
       RESIZE
    ================================================= */

    window.addEventListener(
        'resize',
        function () {


            klrSubStopAutoScrollX82();


            klrSubStartAutoScrollX82();

        }
    );


    /* =================================================
       MOBILE TOUCH
    ================================================= */

    klrSubSliderX82.addEventListener(

        'touchstart',

        function () {

            klrSubStopAutoScrollX82();

        },

        {
            passive: true
        }

    );


    klrSubSliderX82.addEventListener(

        'touchend',

        function () {


            setTimeout(

                function () {

                    klrSubStartAutoScrollX82();

                },

                4000

            );

        },

        {
            passive: true
        }

    );


    /* =================================================
       MOUSE HOVER
    ================================================= */

    klrSubSliderX82.addEventListener(

        'mouseenter',

        function () {

            if (
                window.innerWidth <= 1024
            ) {

                klrSubStopAutoScrollX82();

            }

        }

    );


    klrSubSliderX82.addEventListener(

        'mouseleave',

        function () {

            klrSubStartAutoScrollX82();

        }

    );


})();

</script>


<?php } ?>


</main>

<?php include_once 'includes/footter.php' ?>    