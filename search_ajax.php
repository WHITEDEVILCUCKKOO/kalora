<?php

header('Content-Type: application/json; charset=utf-8');

include "admin_access/db_config.php";

$q = trim($_GET['q'] ?? '');

if (mb_strlen($q) < 1) {
    echo '[]';
    exit;
}

// LIKE ke special characters (% _) ko escape karo
$like = '%' . addcslashes($q, '%_\\') . '%';

$sql = "SELECT 
            products.product_id,
            products.product_name,
            products.product_slug,
            products.product_image,
            products.product_color,
            products.product_size,
            products.original_price,
            products.sale_price,

            product_sub_cate.sub_cate_id,
            product_sub_cate.sub_cate_name,
            product_sub_cate.sub_cate_slug,
            product_sub_cate.sub_cate_img

        FROM products

        LEFT JOIN product_sub_cate
            ON products.sub_cate_id = product_sub_cate.sub_cate_id

        WHERE products.product_status = 'Active'

        AND (
               products.product_name   LIKE ?
            OR products.product_slug   LIKE ?
            OR products.product_color  LIKE ?
            OR products.product_size   LIKE ?
            OR products.sale_price     LIKE ?
            OR products.original_price LIKE ?

            OR product_sub_cate.sub_cate_name LIKE ?
            OR product_sub_cate.sub_cate_slug LIKE ?
        )

        ORDER BY products.product_views DESC

        LIMIT 20";

$stmt = mysqli_prepare($mydb, $sql);

mysqli_stmt_bind_param(
    $stmt,
    "ssssssss",
    $like,
    $like,
    $like,
    $like,
    $like,
    $like,
    $like,
    $like
);

mysqli_stmt_execute($stmt);

$res = mysqli_stmt_get_result($stmt);

$out = [];

while ($row = mysqli_fetch_assoc($res)) {
    $out[] = $row;
}

echo json_encode($out);

?>