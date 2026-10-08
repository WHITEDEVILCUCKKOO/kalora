<?php

include "../db_config.php";


/* Get Selected Sub Category ID */

$sub_cate_id = isset($_GET['sub_cate_id'])
    ? (int)$_GET['sub_cate_id']
    : 0;


/* Check Category ID */

if ($sub_cate_id <= 0) {

    echo '
        <tr>
            <td
                colspan="6"
                style="text-align:center;"
            >
                Invalid category
            </td>
        </tr>
    ';

    exit;
}


/* Get Category Name */

$sub_cate_stmt = mysqli_prepare(
    $mydb,
    "SELECT
        sub_cate_name
     FROM product_sub_cate
     WHERE sub_cate_id = ?
     LIMIT 1"
);


mysqli_stmt_bind_param(
    $sub_cate_stmt,
    "i",
    $sub_cate_id
);


mysqli_stmt_execute(
    $sub_cate_stmt
);


$sub_cate_result =
    mysqli_stmt_get_result(
        $sub_cate_stmt
    );


$sub_cate_data =
    mysqli_fetch_assoc(
        $sub_cate_result
    );


mysqli_stmt_close(
    $sub_cate_stmt
);


/* Category Not Found */

if (!$sub_cate_data) {

    echo '
        <tr>
            <td
                colspan="6"
                style="text-align:center;"
            >
                Category not found
            </td>
        </tr>
    ';

    exit;
}


$sub_cate_name =
    $sub_cate_data['sub_cate_name'];


/* Get Products */

$product_stmt = mysqli_prepare(
    $mydb,
    "SELECT
        product_id,
        product_image,
        product_name,
        product_status
     FROM products
     WHERE sub_cate_id = ?
     ORDER BY product_id DESC"
);


mysqli_stmt_bind_param(
    $product_stmt,
    "i",
    $sub_cate_id
);


mysqli_stmt_execute(
    $product_stmt
);


$product_result =
    mysqli_stmt_get_result(
        $product_stmt
    );


/* No Products */

if (
    mysqli_num_rows(
        $product_result
    ) == 0
) {

    echo '
        <tr>
            <td
                colspan="6"
                style="text-align:center;"
            >
                No products found in this category
            </td>
        </tr>
    ';

    mysqli_stmt_close(
        $product_stmt
    );

    exit;
}


/* Product Rows */

while (
    $product_row =
    mysqli_fetch_assoc(
        $product_result
    )
) {


    /* Product ID */

    $product_id =
        (int)$product_row['product_id'];


    /* Product Name */

    $product_name =
        htmlspecialchars(
            $product_row['product_name'],
            ENT_QUOTES,
            'UTF-8'
        );


    /* Product Image */

    $product_image =
        trim(
            (string)$product_row['product_image']
        );


    /* Product Status */

    $product_status =
        $product_row['product_status'];


    /* Status HTML */

    if (
        $product_status === "Active"
    ) {

        $product_status_html = '
            <span class="all_subcate_status_active_x91">
                Active
            </span>
        ';
    } else {

        $product_status_html = '
            <span class="all_subcate_status_inactive_x91">
                Inactive
            </span>
        ';
    }


    /* Image HTML */

    if (
        $product_image !== ""
    ) {

        $product_image_safe =
            htmlspecialchars(
                $product_image,
                ENT_QUOTES,
                'UTF-8'
            );


        $product_image_html = '
            <img
                src="' . $product_image_safe . '"
                alt="' . $product_name . '"
                class="all_subcate_product_image_x91"
            >
        ';
    } else {

        $product_image_html = '
            <div class="all_subcate_no_image_x91">
                No Image
            </div>
        ';
    }


    /* Output Row */

    echo '

        <tr>

            <!-- Category ID -->

            <td>
                ' . $sub_cate_id . '
            </td>


            <!-- Product ID -->

            <td>
                ' . $product_id . '
            </td>


            <!-- Product Image -->

            <td>
                ' . $product_image_html . '
            </td>


            <!-- Product Name -->

            <td>
                ' . $product_name . '
            </td>


            <!-- Category Name -->

            <td>
                ' .
        htmlspecialchars(
            $sub_cate_name,
            ENT_QUOTES,
            'UTF-8'
        )
        . '
            </td>


            <!-- Product Status -->

            <td>
                ' . $product_status_html . '
            </td>

        </tr>

    ';
}


mysqli_stmt_close(
    $product_stmt
);
