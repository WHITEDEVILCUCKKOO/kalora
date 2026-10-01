<?php
header('Content-Type: application/json; charset=utf-8');
include "admin_access/db_config.php";

$q = trim($_GET['q'] ?? '');
if (mb_strlen($q) < 2) {
    echo '[]';
    exit;
}

// LIKE ke special characters (% _) ko escape karo
$like = '%' . addcslashes($q, '%_\\') . '%';

$sql = "SELECT product_id, product_name, product_slug, product_image,
               product_color, product_size, original_price, sale_price
        FROM products
        WHERE product_status = 'Active'
          AND (product_name  LIKE ?
            OR product_color LIKE ?
            OR product_size  LIKE ?
            OR product_sku   LIKE ?)
        ORDER BY product_views DESC
        LIMIT 8";

$stmt = mysqli_prepare($mydb, $sql);
mysqli_stmt_bind_param($stmt, "ssss", $like, $like, $like, $like);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);

$out = [];
while ($row = mysqli_fetch_assoc($res)) {
    $out[] = $row;
}
echo json_encode($out);