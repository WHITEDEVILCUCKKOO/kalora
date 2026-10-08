<?php
if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
    session_start();
}

define('PRODUCT_BASE_DIR', 'assets/products/');


/* =========================================================
   HELPERS
   ========================================================= */

// products_images ke saare columns (10 img + 10 alt + brochure + video)
function product_image_fields()
{
    $f = [];
    for ($i = 1; $i <= 10; $i++) {
        $f[] = 'product_img_' . $i;
        $f[] = 'product_img_' . $i . '_alt';
    }
    $f[] = 'product_brochure';
    $f[] = 'product_video_1';
    return $f;
}

function product_image_columns()
{
    $cols = [];
    foreach (product_image_fields() as $f) {
        $cols[] = 'pi.' . $f;
    }
    return implode(', ', $cols);
}

// products table me sub_cate_id nahi hai to khud add kar dega
function product_ensure_sub_cate_column($mydb)
{
    $r = mysqli_query($mydb, "SHOW COLUMNS FROM products LIKE 'sub_cate_id'");
    if ($r && mysqli_num_rows($r) === 0) {
        mysqli_query($mydb, "ALTER TABLE products ADD COLUMN sub_cate_id INT(11) NOT NULL DEFAULT 0 AFTER brand_id");
    }
}

// File sirf product folder ke andar ki hi delete hogi
function product_delete_file($path)
{
    if ($path !== '' && strpos($path, PRODUCT_BASE_DIR) === 0 && strpos($path, '..') === false && is_file($path)) {
        unlink($path);
    }
}

function delete_folder_recursive($folder)
{
    if (!is_dir($folder)) {
        return;
    }
    foreach (array_diff(scandir($folder), ['.', '..']) as $item) {
        $path = $folder . DIRECTORY_SEPARATOR . $item;
        is_dir($path) ? delete_folder_recursive($path) : unlink($path);
    }
    rmdir($folder);
}


/* =========================================================
   GET DATA
   ========================================================= */

// ALL products (root, brand, sub category ka naam + images)
function get_product_info($mydb)
{
    $query = "SELECT p.*, c.root_name, b.brand_name, sc.sub_cate_name, " . product_image_columns() . "
              FROM products p
              LEFT JOIN root_categories c ON p.root_id = c.root_id
              LEFT JOIN brands b ON p.brand_id = b.brand_id
              LEFT JOIN product_sub_cate sc ON p.sub_cate_id = sc.sub_cate_id
              LEFT JOIN products_images pi ON p.product_id = pi.product_id
              ORDER BY p.product_id DESC";

    $result = mysqli_query($mydb, $query);
    if (!$result) {
        return [];
    }

    $rows = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }
    return $rows;
}

// SINGLE product
function get_product_single($mydb, $product_id)
{
    $query = "SELECT p.*, " . product_image_columns() . "
              FROM products p
              LEFT JOIN products_images pi ON p.product_id = pi.product_id
              WHERE p.product_id = ? LIMIT 1";

    $stmt = mysqli_prepare($mydb, $query);
    if (!$stmt) {
        return null;
    }
    mysqli_stmt_bind_param($stmt, "i", $product_id);
    mysqli_stmt_execute($stmt);
    $row = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
    mysqli_stmt_close($stmt);
    return $row ?: null;
}

// RANDOM products (home page widget)
function get_random_products($mydb, $limit = 6)
{
    $limit = (int) $limit;

    $query = "SELECT p.*, c.root_name, b.brand_name, pi.product_img_1
              FROM products p
              LEFT JOIN root_categories c ON p.root_id = c.root_id
              LEFT JOIN brands b ON p.brand_id = b.brand_id
              LEFT JOIN products_images pi ON p.product_id = pi.product_id
              WHERE p.product_status = 'Active'
              ORDER BY RAND()
              LIMIT " . $limit;

    $result = mysqli_query($mydb, $query);
    if (!$result) {
        return [];
    }

    $rows = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }
    return $rows;
}

function product_slug_exists($mydb, $slug, $ignore_id = 0)
{
    $stmt = mysqli_prepare($mydb, "SELECT product_id FROM products WHERE product_slug = ? AND product_id <> ? LIMIT 1");
    if (!$stmt) {
        return false;
    }
    mysqli_stmt_bind_param($stmt, "si", $slug, $ignore_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    $exists = mysqli_stmt_num_rows($stmt) > 0;
    mysqli_stmt_close($stmt);
    return $exists;
}


/* =========================================================
   DB WRITE FUNCTIONS
   ========================================================= */

// ADD product (image khali, ID milne ke baad folder banega)
function add_product_info($mydb, $d)
{
    $text = [
        'product_name', 'product_slug', 'product_sku', 'product_color', 'product_size', 'product_image',
        'product_description', 'meta_title', 'meta_description', 'meta_keywords', 'canonical_url',
        'og_title', 'og_description', 'product_other_info_desc'
    ];

    $cols = array_merge(['root_id', 'brand_id', 'sub_cate_id'], $text, [
        'original_price', 'sale_price', 'discount_visibility', 'product_status', 'product_views', 'created_at', 'updated_at'
    ]);

    $vals = [$d['root_id'], $d['brand_id'], $d['sub_cate_id']];
    foreach ($text as $k) {
        $vals[] = $d[$k] ?? '';
    }
    array_push($vals, $d['original_price'], $d['sale_price'], $d['discount_visibility'], $d['product_status'], 0, time(), '');

    $sql = "INSERT INTO products (" . implode(', ', $cols) . ") VALUES (" . implode(', ', array_fill(0, count($cols), '?')) . ")";

    $stmt = mysqli_prepare($mydb, $sql);
    if (!$stmt) {
        return false;
    }

    mysqli_stmt_bind_param($stmt, 'iii' . str_repeat('s', 14) . 'ddssiss', ...$vals);
    $ok = mysqli_stmt_execute($stmt);
    $new_id = $ok ? mysqli_insert_id($mydb) : false;
    mysqli_stmt_close($stmt);

    return $new_id;
}

// UPDATE product (basic fields)
function update_product_info($mydb, $product_id, $d)
{
    $text = [
        'product_name', 'product_slug', 'product_sku', 'product_color', 'product_size',
        'product_description', 'meta_title', 'meta_description', 'meta_keywords', 'canonical_url',
        'og_title', 'og_description', 'product_other_info_desc'
    ];

    $set  = ['root_id = ?', 'brand_id = ?', 'sub_cate_id = ?'];
    $vals = [$d['root_id'], $d['brand_id'], $d['sub_cate_id']];

    foreach ($text as $k) {
        $set[]  = $k . ' = ?';
        $vals[] = $d[$k] ?? '';
    }

    $set = array_merge($set, ['original_price = ?', 'sale_price = ?', 'discount_visibility = ?', 'product_status = ?', 'updated_at = ?']);
    array_push($vals, $d['original_price'], $d['sale_price'], $d['discount_visibility'], $d['product_status'], time(), $product_id);

    $stmt = mysqli_prepare($mydb, "UPDATE products SET " . implode(', ', $set) . " WHERE product_id = ?");
    if (!$stmt) {
        return false;
    }

    mysqli_stmt_bind_param($stmt, 'iii' . str_repeat('s', 13) . 'ddssii', ...$vals);
    $ok = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    return $ok;
}

function update_product_main_image($mydb, $product_id, $image_path)
{
    $stmt = mysqli_prepare($mydb, "UPDATE products SET product_image = ? WHERE product_id = ?");
    if (!$stmt) {
        return false;
    }
    mysqli_stmt_bind_param($stmt, "si", $image_path, $product_id);
    $ok = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $ok;
}

function product_images_row_exists($mydb, $product_id)
{
    $stmt = mysqli_prepare($mydb, "SELECT product_id FROM products_images WHERE product_id = ? LIMIT 1");
    if (!$stmt) {
        return false;
    }
    mysqli_stmt_bind_param($stmt, "i", $product_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    $exists = mysqli_stmt_num_rows($stmt) > 0;
    mysqli_stmt_close($stmt);
    return $exists;
}

// ADD products_images row
function add_product_images($mydb, $product_id, $images)
{
    $fields = product_image_fields();

    $sql = "INSERT INTO products_images (product_id, " . implode(', ', $fields) . ")
            VALUES (?, " . implode(', ', array_fill(0, count($fields), '?')) . ")";

    $stmt = mysqli_prepare($mydb, $sql);
    if (!$stmt) {
        return false;
    }

    $vals = [$product_id];
    foreach ($fields as $f) {
        $vals[] = $images[$f] ?? '';
    }

    mysqli_stmt_bind_param($stmt, 'i' . str_repeat('s', count($fields)), ...$vals);
    $ok = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    return $ok;
}

// UPDATE products_images row
function update_product_images($mydb, $product_id, $images)
{
    $fields = product_image_fields();

    $set  = [];
    $vals = [];
    foreach ($fields as $f) {
        $set[]  = $f . ' = ?';
        $vals[] = $images[$f] ?? '';
    }
    $vals[] = $product_id;

    $stmt = mysqli_prepare($mydb, "UPDATE products_images SET " . implode(', ', $set) . " WHERE product_id = ?");
    if (!$stmt) {
        return false;
    }

    mysqli_stmt_bind_param($stmt, str_repeat('s', count($fields)) . 'i', ...$vals);
    $ok = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    return $ok;
}


/* =========================================================
   FILE UPLOAD
   ========================================================= */

// Fixed naam se save (main.jpg, gallery_1.jpg ...). Naya upload hone par purani file hat jaati hai.
function upload_product_file_to_folder($file_field_key, $folder, $allowed_ext, $fixed_name, $old_path = '')
{
    if (!isset($_FILES[$file_field_key]) || $_FILES[$file_field_key]['error'] !== UPLOAD_ERR_OK) {
        return '';
    }

    $ext = strtolower(pathinfo($_FILES[$file_field_key]['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, $allowed_ext)) {
        return '';
    }

    if (!is_dir($folder)) {
        mkdir($folder, 0755, true);
    }

    $target = $folder . $fixed_name . '.' . $ext;

    if (move_uploaded_file($_FILES[$file_field_key]['tmp_name'], $target)) {
        if ($old_path !== '' && $old_path !== $target) {
            product_delete_file($old_path);
        }
        return $target;
    }

    return '';
}

// Nayi file > purani rakho > Remove dabaya to khali (aur file delete)
function product_resolve_file($key, $folder, $allowed, $name, $old, $keep_post)
{
    $new = upload_product_file_to_folder($key, $folder, $allowed, $name, $old);
    if ($new !== '') {
        return $new;
    }

    if (trim($keep_post) === '') {
        product_delete_file($old);
        return '';
    }

    return $old;
}


/* =========================================================
   FORM INPUT + VALIDATION
   ========================================================= */

function product_collect_input()
{
    $t = function ($key) {
        return trim($_POST[$key] ?? '');
    };

    $slug   = trim(preg_replace('/[^a-z0-9]+/', '-', strtolower($t('product_slug'))), '-');
    $disc   = $t('discount_visibility') === 'Show' ? 'Show' : 'Hide';
    $status = $t('product_status') === 'Inactive' ? 'Inactive' : 'Active';

    return [
        'root_id'                 => (int) ($_POST['root_id'] ?? 0),
        'brand_id'                => (int) ($_POST['brand_id'] ?? 0),
        'sub_cate_id'             => (int) ($_POST['sub_cate_id'] ?? 0),
        'product_name'            => $t('product_name'),
        'product_slug'            => $slug,
        'product_sku'             => $t('product_sku'),
        'product_color'           => $t('product_color'),
        'product_size'            => $t('product_size'),
        'product_description'     => $t('product_description'),
        'meta_title'              => $t('meta_title'),
        'meta_description'        => $t('meta_description'),
        'meta_keywords'           => $t('meta_keywords'),
        'canonical_url'           => $t('canonical_url'),
        'og_title'                => $t('og_title'),
        'og_description'          => $t('og_description'),
        'product_other_info_desc' => $t('product_other_info_desc'),
        'original_price'          => (float) ($_POST['original_price'] ?? 0),
        'sale_price'              => (float) ($_POST['sale_price'] ?? 0),
        'discount_visibility'     => $disc,
        'product_status'          => $status,
        'product_image'           => '',
    ];
}

function product_validate($d)
{
    if ($d['root_id'] <= 0 || $d['brand_id'] <= 0 || $d['sub_cate_id'] <= 0 || $d['product_name'] === '' || $d['product_slug'] === '') {
        return "Root Category, Brand, Sub Category, Product Name aur Slug zaroori hain.";
    }
    return '';
}


/* =========================================================
   HANDLERS
   ========================================================= */

function handle_product_add($mydb)
{
    $res = ['success_msg' => '', 'error_msg' => ''];

    $d = product_collect_input();

    if ($err = product_validate($d)) {
        $res['error_msg'] = $err;
        return $res;
    }

    if (product_slug_exists($mydb, $d['product_slug'])) {
        $res['error_msg'] = "Ye slug pehle se hai, doosra slug daalo.";
        return $res;
    }

    $new_id = add_product_info($mydb, $d);
    if (!$new_id) {
        $res['error_msg'] = "Product add fail ho gaya: " . mysqli_error($mydb);
        return $res;
    }

    $folder  = PRODUCT_BASE_DIR . "product_" . $new_id . "/";
    $img_ext = ['jpg', 'jpeg', 'png', 'webp', 'gif'];

    $main = upload_product_file_to_folder('product_image', $folder, $img_ext, 'main');
    if ($main !== '') {
        update_product_main_image($mydb, $new_id, $main);
    }

    $images = [];
    for ($i = 1; $i <= 10; $i++) {
        $images['product_img_' . $i]          = upload_product_file_to_folder('product_img_' . $i, $folder, $img_ext, 'gallery_' . $i);
        $images['product_img_' . $i . '_alt'] = trim($_POST['product_img_' . $i . '_alt'] ?? '');
    }
    $images['product_brochure'] = upload_product_file_to_folder('product_brochure', $folder, ['pdf'], 'brochure');
    $images['product_video_1']  = upload_product_file_to_folder('product_video_1', $folder, ['mp4', 'webm', 'mov', 'ogg'], 'video_1');

    add_product_images($mydb, $new_id, $images);

    $res['success_msg'] = "Product added successfully!";
    return $res;
}

function handle_product_update($mydb)
{
    $res = ['success_msg' => '', 'error_msg' => ''];

    $product_id = (int) ($_POST['product_id'] ?? 0);
    $old = $product_id > 0 ? get_product_single($mydb, $product_id) : null;

    if (!$old) {
        $res['error_msg'] = "Product nahi mila.";
        return $res;
    }

    $d = product_collect_input();

    if ($err = product_validate($d)) {
        $res['error_msg'] = $err;
        return $res;
    }

    if (product_slug_exists($mydb, $d['product_slug'], $product_id)) {
        $res['error_msg'] = "Ye slug pehle se hai, doosra slug daalo.";
        return $res;
    }

    if (!update_product_info($mydb, $product_id, $d)) {
        $res['error_msg'] = "Product update fail ho gaya: " . mysqli_error($mydb);
        return $res;
    }

    $folder  = PRODUCT_BASE_DIR . "product_" . $product_id . "/";
    $img_ext = ['jpg', 'jpeg', 'png', 'webp', 'gif'];

    // Main image
    $main = product_resolve_file('product_image', $folder, $img_ext, 'main',
        (string) ($old['product_image'] ?? ''), $_POST['existing_main_image'] ?? '');
    update_product_main_image($mydb, $product_id, $main);

    // Gallery
    $images = [];
    for ($i = 1; $i <= 10; $i++) {
        $images['product_img_' . $i] = product_resolve_file('product_img_' . $i, $folder, $img_ext, 'gallery_' . $i,
            (string) ($old['product_img_' . $i] ?? ''), $_POST['existing_img_' . $i] ?? '');
        $images['product_img_' . $i . '_alt'] = trim($_POST['product_img_' . $i . '_alt'] ?? '');
    }

    // Brochure + Video
    $images['product_brochure'] = product_resolve_file('product_brochure', $folder, ['pdf'], 'brochure',
        (string) ($old['product_brochure'] ?? ''), $_POST['existing_brochure'] ?? '');
    $images['product_video_1'] = product_resolve_file('product_video_1', $folder, ['mp4', 'webm', 'mov', 'ogg'], 'video_1',
        (string) ($old['product_video_1'] ?? ''), $_POST['existing_video_1'] ?? '');

    if (product_images_row_exists($mydb, $product_id)) {
        update_product_images($mydb, $product_id, $images);
    } else {
        add_product_images($mydb, $product_id, $images);
    }

    $res['success_msg'] = "Product updated successfully!";
    return $res;
}

// DELETE single (row + images row + folder)
function delete_product_info($mydb, $product_id)
{
    $stmt1 = mysqli_prepare($mydb, "DELETE FROM products_images WHERE product_id = ?");
    if ($stmt1) {
        mysqli_stmt_bind_param($stmt1, "i", $product_id);
        mysqli_stmt_execute($stmt1);
        mysqli_stmt_close($stmt1);
    }

    $stmt2 = mysqli_prepare($mydb, "DELETE FROM products WHERE product_id = ?");
    if (!$stmt2) {
        return false;
    }
    mysqli_stmt_bind_param($stmt2, "i", $product_id);
    $ok = mysqli_stmt_execute($stmt2);
    mysqli_stmt_close($stmt2);

    delete_folder_recursive(PRODUCT_BASE_DIR . "product_" . (int) $product_id . "/");

    return $ok;
}

function handle_product_delete($mydb)
{
    $res = ['success_msg' => '', 'error_msg' => ''];

    $product_id = (int) ($_POST['delete_product_id'] ?? 0);

    if ($product_id <= 0) {
        $res['error_msg'] = "Invalid product.";
    } elseif (delete_product_info($mydb, $product_id)) {
        $res['success_msg'] = "Product deleted successfully!";
    } else {
        $res['error_msg'] = "Product delete fail ho gaya, dubara try karo.";
    }

    return $res;
}

function delete_multiple_products($mydb, $product_ids)
{
    $count = 0;
    foreach ($product_ids as $id) {
        $id = (int) $id;
        if ($id > 0 && delete_product_info($mydb, $id)) {
            $count++;
        }
    }
    return $count;
}

function handle_product_bulk_delete($mydb)
{
    $res = ['success_msg' => '', 'error_msg' => ''];

    $ids = array_filter(array_map('intval', explode(',', trim($_POST['selected_product_ids'] ?? ''))));

    if (empty($ids)) {
        $res['error_msg'] = "Koi product select nahi kiya gaya.";
        return $res;
    }

    $count = delete_multiple_products($mydb, $ids);

    if ($count > 0) {
        $res['success_msg'] = $count . " product(s) deleted successfully!";
    } else {
        $res['error_msg'] = "Delete fail ho gaya, dubara try karo.";
    }

    return $res;
}


/* =========================================================
   MAIN ENTRY - admin.php me sirf ye call karna hai
   Success ke baad redirect hota hai (refresh par dobara submit nahi hoga)
   ========================================================= */
function handle_product_request($mydb)
{
    $res = ['success_msg' => '', 'error_msg' => ''];
    $session_ok = session_status() === PHP_SESSION_ACTIVE;

    product_ensure_sub_cate_column($mydb);

    // Redirect ke baad ka message
    if ($session_ok && isset($_SESSION['product_flash'])) {
        $res['success_msg'] = $_SESSION['product_flash'];
        unset($_SESSION['product_flash']);
    }

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        return $res;
    }

    // File bahut badi ho to PHP $_POST khali kar deta hai
    if (empty($_POST) && empty($_FILES) && (int) ($_SERVER['CONTENT_LENGTH'] ?? 0) > 0) {
        $res['error_msg'] = "File size limit se zyada bada hai (php.ini ki upload_max_filesize / post_max_size badhao).";
        return $res;
    }

    if (isset($_POST['add_product'])) {
        $r = handle_product_add($mydb);
    } elseif (isset($_POST['update_product'])) {
        $r = handle_product_update($mydb);
    } elseif (isset($_POST['delete_single_product'])) {
        $r = handle_product_delete($mydb);
    } elseif (isset($_POST['delete_selected_products'])) {
        $r = handle_product_bulk_delete($mydb);
    } else {
        return $res;
    }

    // Success: message session me rakho aur redirect
    if ($r['success_msg'] !== '' && $session_ok) {
        $_SESSION['product_flash'] = $r['success_msg'];
        $url = $_SERVER['REQUEST_URI'];

        if (!headers_sent()) {
            header("Location: " . $url);
        } else {
            echo '<meta http-equiv="refresh" content="0;url=' . htmlspecialchars($url) . '">';
        }
        exit;
    }

    return $r;
}



// SEARCH - LIKE se matching product IDs (id, name, sku, color, category, sub category, brand)
function search_product_ids($mydb, $q)
{
    $like = '%' . addcslashes($q, '%_\\') . '%';

    $sql = "SELECT p.product_id
            FROM products p
            LEFT JOIN root_categories c ON p.root_id = c.root_id
            LEFT JOIN brands b ON p.brand_id = b.brand_id
            LEFT JOIN product_sub_cate sc ON p.sub_cate_id = sc.sub_cate_id
            WHERE p.product_name LIKE ?
               OR p.product_id LIKE ?
               OR p.product_sku LIKE ?
               OR p.product_color LIKE ?
               OR c.root_name LIKE ?
               OR b.brand_name LIKE ?
               OR sc.sub_cate_name LIKE ?";

    $stmt = mysqli_prepare($mydb, $sql);
    if (!$stmt) {
        return [];
    }
    mysqli_stmt_bind_param($stmt, 'sssssss', $like, $like, $like, $like, $like, $like, $like);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);

    $ids = [];
    while ($row = mysqli_fetch_assoc($res)) {
        $ids[] = (int) $row['product_id'];
    }
    mysqli_stmt_close($stmt);
    return $ids;
}