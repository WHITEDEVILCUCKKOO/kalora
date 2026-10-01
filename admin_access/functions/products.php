<?php

// =========================================================
// GET ALL PRODUCT DATA
// (Root Category + Brand name + pehli image join karke)
// =========================================================
function get_product_info($mydb)
{
    $query = "SELECT
                p.*,
                c.root_name,
                b.brand_name,
                pi.*
              FROM products p
              LEFT JOIN root_categories c ON p.root_id = c.root_id
              LEFT JOIN brands b ON p.brand_id = b.brand_id
              LEFT JOIN products_images pi ON p.product_id = pi.product_id
              ORDER BY p.product_id DESC";

    $result = mysqli_query($mydb, $query);

    if (!$result) {
        return [];
    }

    $product_info = [];

    while ($row = mysqli_fetch_assoc($result)) {
        $product_info[] = $row;
    }

    return $product_info;
}


// =========================================================
// GET RANDOM PRODUCTS (home page ya kisi bhi widget ke liye)
// =========================================================
function get_random_products($mydb, $limit = 6)
{
    $limit = (int) $limit;

    $query = "SELECT
                p.*,
                c.root_name,
                b.brand_name,
                pi.product_img_1
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

    $random_products = [];

    while ($row = mysqli_fetch_assoc($result)) {
        $random_products[] = $row;
    }

    return $random_products;
}


// =========================================================
// ADD NEW PRODUCT (product_image khali chhod ke insert hota
// hai - kyunki ID milne ke baad hi folder banega)
// =========================================================
function add_product_info($mydb, $data)
{
    $query = "INSERT INTO products (
    root_id,
    brand_id,
    product_name,
    product_slug,
    product_sku,
    product_color,
    product_size,
    product_image,
    product_description,
    meta_title,
    meta_description,
    meta_keywords,
    canonical_url,
    og_title,
    og_description,
    product_other_info_desc,
    original_price,
    sale_price,
    discount_visibility,
    product_status,
    product_views,
    created_at
) VALUES (
    ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?
)";

$stmt = mysqli_prepare($mydb, $query);

if (!$stmt) {
    return false;
}

$created_at    = time();
$product_views = 0;

mysqli_stmt_bind_param(
    $stmt,
    "ii" . str_repeat("s", 14) . "ddssii",

    $data['root_id'],
    $data['brand_id'],
    $data['product_name'],
    $data['product_slug'],
    $data['product_sku'],
    $data['product_color'],
    $data['product_size'],
    $data['product_image'],
    $data['product_description'],
    $data['meta_title'],
    $data['meta_description'],
    $data['meta_keywords'],
    $data['canonical_url'],
    $data['og_title'],
    $data['og_description'],
    $data['product_other_info_desc'],
    $data['original_price'],
    $data['sale_price'],
    $data['discount_visibility'],
    $data['product_status'],
    $product_views,
    $created_at
);

$executed = mysqli_stmt_execute($stmt);

$new_product_id = $executed ? mysqli_insert_id($mydb) : false;

mysqli_stmt_close($stmt);

return $new_product_id;
}


// =========================================================
// PRODUCT KA MAIN IMAGE PATH UPDATE KARO (upload ke baad)
// =========================================================
function update_product_main_image($mydb, $product_id, $image_path)
{
    $query = "UPDATE products SET product_image = ? WHERE product_id = ?";

    $stmt = mysqli_prepare($mydb, $query);

    if (!$stmt) {
        return false;
    }

    mysqli_stmt_bind_param($stmt, "si", $image_path, $product_id);

    $result = mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);

    return $result;
}


// =========================================================
// ADD PRODUCT IMAGES ROW (products_images table)
// =========================================================
function add_product_images($mydb, $product_id, $images)
{
    $query = "INSERT INTO products_images (
        product_id,
        product_img_1, product_img_1_alt, product_img_2, product_img_2_alt,
        product_img_3, product_img_3_alt, product_img_4, product_img_4_alt,
        product_img_5, product_img_5_alt, product_img_6, product_img_6_alt,
        product_img_7, product_img_7_alt, product_img_8, product_img_8_alt,
        product_img_9, product_img_9_alt, product_img_10, product_img_10_alt,
        product_brochure, product_video_1
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($mydb, $query);

    if (!$stmt) {
        return false;
    }

    mysqli_stmt_bind_param(
        $stmt,
        "issssssssssssssssssssss",
        $product_id,
        $images['product_img_1'],
        $images['product_img_1_alt'],
        $images['product_img_2'],
        $images['product_img_2_alt'],
        $images['product_img_3'],
        $images['product_img_3_alt'],
        $images['product_img_4'],
        $images['product_img_4_alt'],
        $images['product_img_5'],
        $images['product_img_5_alt'],
        $images['product_img_6'],
        $images['product_img_6_alt'],
        $images['product_img_7'],
        $images['product_img_7_alt'],
        $images['product_img_8'],
        $images['product_img_8_alt'],
        $images['product_img_9'],
        $images['product_img_9_alt'],
        $images['product_img_10'],
        $images['product_img_10_alt'],
        $images['product_brochure'],
        $images['product_video_1']
    );

    $result = mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);

    return $result;
}


// =========================================================
// UPLOAD HELPER - fixed filename se save karta hai
// (jaise "main.jpg", "gallery_1.jpg", "video_1.mp4")
// taaki folder clean rahe aur baad me update bhi easy ho
// =========================================================
function upload_product_file_to_folder($file_field_key, $folder, $allowed_ext, $fixed_name)
{
    if (!isset($_FILES[$file_field_key]) || $_FILES[$file_field_key]['error'] !== UPLOAD_ERR_OK) {
        return '';
    }

    if (!is_dir($folder)) {
        mkdir($folder, 0755, true);
    }

    $file_ext = strtolower(pathinfo($_FILES[$file_field_key]['name'], PATHINFO_EXTENSION));

    if (!in_array($file_ext, $allowed_ext)) {
        return '';
    }

    $target = $folder . $fixed_name . '.' . $file_ext;

    if (move_uploaded_file($_FILES[$file_field_key]['tmp_name'], $target)) {
        return $target;
    }

    return '';
}


// =========================================================
// HANDLE ADD PRODUCT
// Flow: pehle product insert (ID milta hai) -> phir usi ID
// se folder assets/products/product_<ID>/ banta hai ->
// image/gallery/video/brochure wahin save hote hain
// =========================================================
function handle_product_add($mydb)
{
    $response = [
        'success_msg' => '',
        'error_msg'   => '',
    ];

    if (!isset($_POST['add_product'])) {
        return $response;
    }

    // ---------------------------------------------------------
    // Agar file(s) ka size php.ini ki limit (post_max_size /
    // upload_max_filesize) se zyada ho jaye, to PHP poora
    // $_POST khali kar deta hai - isse pehchan lo taaki
    // confusing "fields required" error na dikhe
    // ---------------------------------------------------------
    if (empty($_POST) && empty($_FILES) && (int)($_SERVER['CONTENT_LENGTH'] ?? 0) > 0) {
        $response['error_msg'] = "File size limit se zyada bada hai (php.ini ki upload_max_filesize / post_max_size badhao). Chhoti file try karo.";
        return $response;
    }

    $root_id                 = (int) ($_POST['root_id'] ?? 0);
    $brand_id                = (int) ($_POST['brand_id'] ?? 0);
    $product_name            = trim($_POST['product_name'] ?? '');
    $product_slug            = trim($_POST['product_slug'] ?? '');
    $product_sku             = trim($_POST['product_sku'] ?? '');
    $product_color           = trim($_POST['product_color'] ?? '');
    $product_size           = trim($_POST['product_size'] ?? '');
    $product_description     = trim($_POST['product_description'] ?? '');
    $meta_title              = trim($_POST['meta_title'] ?? '');
    $meta_description        = trim($_POST['meta_description'] ?? '');
    $meta_keywords           = trim($_POST['meta_keywords'] ?? '');
    $canonical_url           = trim($_POST['canonical_url'] ?? '');
    $og_title                = trim($_POST['og_title'] ?? '');
    $og_description          = trim($_POST['og_description'] ?? '');
    $product_other_info_desc = trim($_POST['product_other_info_desc'] ?? '');
    $original_price          = (float) ($_POST['original_price'] ?? 0);
    $sale_price              = (float) ($_POST['sale_price'] ?? 0);
    $discount_visibility     = trim($_POST['discount_visibility'] ?? 'Hide');
    $product_status          = trim($_POST['product_status'] ?? 'Active');

    if ($root_id <= 0 || $brand_id <= 0 || $product_name === '' || $product_slug === '') {
        $response['error_msg'] = "Root Category, Brand, Product Name aur Slug zaroori hain.";
        return $response;
    }

    $product_data = [
        'root_id'                 => $root_id,
        'brand_id'                => $brand_id,
        'product_name'            => $product_name,
        'product_slug'            => $product_slug,
        'product_sku'             => $product_sku,
        'product_color'             => $product_color,
        'product_size'             => $product_size,
        'product_description'     => $product_description,
        'meta_title'              => $meta_title,
        'meta_description'        => $meta_description,
        'meta_keywords'           => $meta_keywords,
        'canonical_url'           => $canonical_url,
        'og_title'                => $og_title,
        'og_description'          => $og_description,
        'product_other_info_desc' => $product_other_info_desc,
        'original_price'          => $original_price,
        'sale_price'              => $sale_price,
        'discount_visibility'     => $discount_visibility,
        'product_status'          => $product_status,
    ];

    // Step 1: Pehle product insert karo (image ke bina) - ID milegi
    $new_product_id = add_product_info($mydb, $product_data);

    if (!$new_product_id) {
        $response['error_msg'] = "Product add fail ho gaya, dubara try karo.";
        return $response;
    }

    // Step 2: Ab isi ID se folder banao
    $product_folder = "assets/products/product_" . $new_product_id . "/";

    $image_ext = ['jpg', 'jpeg', 'png', 'webp', 'gif'];

    // Main image
    $main_image = upload_product_file_to_folder('product_image', $product_folder, $image_ext, 'main');

    if ($main_image !== '') {
        update_product_main_image($mydb, $new_product_id, $main_image);
    }

    // Gallery images (1 se 10 tak)
    $images = [];

    for ($i = 1; $i <= 10; $i++) {
        $images['product_img_' . $i] = upload_product_file_to_folder(
            'product_img_' . $i,
            $product_folder,
            $image_ext,
            'gallery_' . $i
        );
        $images['product_img_' . $i . '_alt'] = trim($_POST['product_img_' . $i . '_alt'] ?? '');
    }

    // Brochure (PDF)
    $images['product_brochure'] = upload_product_file_to_folder(
        'product_brochure',
        $product_folder,
        ['pdf'],
        'brochure'
    );

    // Video (ab file upload hai, URL text nahi)
    $images['product_video_1'] = upload_product_file_to_folder(
        'product_video_1',
        $product_folder,
        ['mp4', 'webm', 'mov', 'ogg'],
        'video_1'
    );

    add_product_images($mydb, $new_product_id, $images);

    $response['success_msg'] = "Product added successfully!";

    return $response;
}


// =========================================================
// UPDATE PRODUCT (basic fields, products table)
// =========================================================
function update_product_info($mydb, $product_id, $data)
{
    $query = "UPDATE products SET
                root_id = ?, brand_id = ?, product_name = ?, product_slug = ?,
                product_sku = ?, product_color = ? ,product_size = ? ,product_description = ?, meta_title = ?,
                meta_description = ?, meta_keywords = ?, canonical_url = ?,
                og_title = ?, og_description = ?, product_other_info_desc = ?,
                original_price = ?, sale_price = ?, discount_visibility = ?,
                product_status = ?, updated_at = ?
              WHERE product_id = ?";

    $stmt = mysqli_prepare($mydb, $query);

    if (!$stmt) {
        return false;
    }

    $updated_at = time();

    // Total 21 variables ke liye correct type string
    $types = 'ii' . str_repeat('s', 13) . 'dd' . 'ssii';

    mysqli_stmt_bind_param(
        $stmt,
        $types,
        $data['root_id'],
        $data['brand_id'],
        $data['product_name'],
        $data['product_slug'],
        $data['product_sku'],
        $data['product_color'],
        $data['product_size'],
        $data['product_description'],
        $data['meta_title'],
        $data['meta_description'],
        $data['meta_keywords'],
        $data['canonical_url'],
        $data['og_title'],
        $data['og_description'],
        $data['product_other_info_desc'],
        $data['original_price'],
        $data['sale_price'],
        $data['discount_visibility'],
        $data['product_status'],
        $updated_at,
        $product_id
    );

    $result = mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);

    return $result;
}


// =========================================================
// UPDATE PRODUCT IMAGES ROW (products_images table)
// (Insert nahi, UPDATE - kyunki row already add time bani thi)
// =========================================================
function update_product_images($mydb, $product_id, $images)
{
    $query = "UPDATE products_images SET
                product_img_1 = ?, product_img_1_alt = ?,
                product_img_2 = ?, product_img_2_alt = ?,
                product_img_3 = ?, product_img_3_alt = ?,
                product_img_4 = ?, product_img_4_alt = ?,
                product_img_5 = ?, product_img_5_alt = ?,
                product_img_6 = ?, product_img_6_alt = ?,
                product_img_7 = ?, product_img_7_alt = ?,
                product_img_8 = ?, product_img_8_alt = ?,
                product_img_9 = ?, product_img_9_alt = ?,
                product_img_10 = ?, product_img_10_alt = ?,
                product_brochure = ?, product_video_1 = ?
              WHERE product_id = ?";

    $stmt = mysqli_prepare($mydb, $query);

    if (!$stmt) {
        return false;
    }

    // 22 string fields (10 images + 10 alt + brochure + video) + product_id (int)
    $types = str_repeat('s', 22) . 'i';

    mysqli_stmt_bind_param(
        $stmt,
        $types,
        $images['product_img_1'],
        $images['product_img_1_alt'],
        $images['product_img_2'],
        $images['product_img_2_alt'],
        $images['product_img_3'],
        $images['product_img_3_alt'],
        $images['product_img_4'],
        $images['product_img_4_alt'],
        $images['product_img_5'],
        $images['product_img_5_alt'],
        $images['product_img_6'],
        $images['product_img_6_alt'],
        $images['product_img_7'],
        $images['product_img_7_alt'],
        $images['product_img_8'],
        $images['product_img_8_alt'],
        $images['product_img_9'],
        $images['product_img_9_alt'],
        $images['product_img_10'],
        $images['product_img_10_alt'],
        $images['product_brochure'],
        $images['product_video_1'],
        $product_id
    );

    $result = mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);

    return $result;
}


// =========================================================
// HANDLE UPDATE PRODUCT
// Agar nayi file select ki hai to purani (same fixed naam
// wali file) replace ho jayegi, warna existing_* hidden
// fields se purana path wapas use ho jayega. Agar "Remove"
// dabaya tha to hidden field khali aayega -> image bhi khali
// save hogi (matlab reference hat gayi).
// =========================================================
function handle_product_update($mydb)
{
    $response = [
        'success_msg' => '',
        'error_msg'   => '',
    ];

    if (!isset($_POST['update_product'])) {
        return $response;
    }

    // Bada file size ki wajah se $_POST khali hone wali detection
    if (empty($_POST) && empty($_FILES) && (int)($_SERVER['CONTENT_LENGTH'] ?? 0) > 0) {
        $response['error_msg'] = "File size limit se zyada bada hai (php.ini ki upload_max_filesize / post_max_size badhao).";
        return $response;
    }

    $product_id = (int) ($_POST['product_id'] ?? 0);

    $root_id                 = (int) ($_POST['root_id'] ?? 0);
    $brand_id                = (int) ($_POST['brand_id'] ?? 0);
    $product_name            = trim($_POST['product_name'] ?? '');
    $product_slug            = trim($_POST['product_slug'] ?? '');
    $product_sku             = trim($_POST['product_sku'] ?? '');
    $product_color             = trim($_POST['product_color'] ?? '');
    $product_size             = trim($_POST['product_size'] ?? '');
    $product_description     = trim($_POST['product_description'] ?? '');
    $meta_title              = trim($_POST['meta_title'] ?? '');
    $meta_description        = trim($_POST['meta_description'] ?? '');
    $meta_keywords           = trim($_POST['meta_keywords'] ?? '');
    $canonical_url           = trim($_POST['canonical_url'] ?? '');
    $og_title                = trim($_POST['og_title'] ?? '');
    $og_description          = trim($_POST['og_description'] ?? '');
    $product_other_info_desc = trim($_POST['product_other_info_desc'] ?? '');
    $original_price          = (float) ($_POST['original_price'] ?? 0);
    $sale_price              = (float) ($_POST['sale_price'] ?? 0);
    $discount_visibility     = trim($_POST['discount_visibility'] ?? 'Hide');
    $product_status          = trim($_POST['product_status'] ?? 'Active');

    if ($product_id <= 0 || $root_id <= 0 || $brand_id <= 0 || $product_name === '' || $product_slug === '') {
        $response['error_msg'] = "Root Category, Brand, Product Name aur Slug zaroori hain.";
        return $response;
    }

    $product_data = [
        'root_id'                 => $root_id,
        'brand_id'                => $brand_id,
        'product_name'            => $product_name,
        'product_slug'            => $product_slug,
        'product_sku'             => $product_sku,
        'product_color'           => $product_color,
        'product_size'            => $product_size,
        'product_description'     => $product_description,
        'meta_title'              => $meta_title,
        'meta_description'        => $meta_description,
        'meta_keywords'           => $meta_keywords,
        'canonical_url'           => $canonical_url,
        'og_title'                => $og_title,
        'og_description'          => $og_description,
        'product_other_info_desc' => $product_other_info_desc,
        'original_price'          => $original_price,
        'sale_price'              => $sale_price,
        'discount_visibility'     => $discount_visibility,
        'product_status'          => $product_status,
    ];

    $updated = update_product_info($mydb, $product_id, $product_data);

    if (!$updated) {
        $response['error_msg'] = "Product update fail ho gaya, dubara try karo.";
        return $response;
    }

    $product_folder = "assets/products/product_" . $product_id . "/";
    $image_ext      = ['jpg', 'jpeg', 'png', 'webp', 'gif'];

    // ---------------------------------------------------------
    // Main image: nayi ho to upload karo, warna existing rakho
    // (existing khali hai to matlab user ne "Remove" dabaya tha)
    // ---------------------------------------------------------
    $existing_main = trim($_POST['existing_main_image'] ?? '');
    $new_main      = upload_product_file_to_folder('product_image', $product_folder, $image_ext, 'main');
    $main_image    = $new_main !== '' ? $new_main : $existing_main;

    update_product_main_image($mydb, $product_id, $main_image);

    // ---------------------------------------------------------
    // Gallery images (1 se 10 tak)
    // ---------------------------------------------------------
    $images = [];

    for ($i = 1; $i <= 10; $i++) {

        $existing_img = trim($_POST['existing_img_' . $i] ?? '');
        $new_img      = upload_product_file_to_folder(
            'product_img_' . $i,
            $product_folder,
            $image_ext,
            'gallery_' . $i
        );

        $images['product_img_' . $i]          = $new_img !== '' ? $new_img : $existing_img;
        $images['product_img_' . $i . '_alt'] = trim($_POST['product_img_' . $i . '_alt'] ?? '');
    }

    // Brochure
    $existing_brochure       = trim($_POST['existing_brochure'] ?? '');
    $new_brochure            = upload_product_file_to_folder('product_brochure', $product_folder, ['pdf'], 'brochure');
    $images['product_brochure'] = $new_brochure !== '' ? $new_brochure : $existing_brochure;

    // Video
    $existing_video             = trim($_POST['existing_video_1'] ?? '');
    $new_video                  = upload_product_file_to_folder('product_video_1', $product_folder, ['mp4', 'webm', 'mov', 'ogg'], 'video_1');
    $images['product_video_1']  = $new_video !== '' ? $new_video : $existing_video;

    update_product_images($mydb, $product_id, $images);

    $response['success_msg'] = "Product updated successfully!";

    return $response;
}


// =========================================================
// FOLDER KO SAARI FILES SAHIT DELETE KARO (recursive)
// =========================================================
function delete_folder_recursive($folder)
{
    if (!is_dir($folder)) {
        return;
    }

    $items = array_diff(scandir($folder), ['.', '..']);

    foreach ($items as $item) {
        $path = $folder . DIRECTORY_SEPARATOR . $item;

        if (is_dir($path)) {
            delete_folder_recursive($path);
        } else {
            unlink($path);
        }
    }

    rmdir($folder);
}


// =========================================================
// DELETE SINGLE PRODUCT
// products + products_images row hatata hai, aur uska
// pura upload folder bhi delete kar deta hai
// =========================================================
function delete_product_info($mydb, $product_id)
{
    // Pehle images table se row hatao
    $stmt1 = mysqli_prepare($mydb, "DELETE FROM products_images WHERE product_id = ?");
    if ($stmt1) {
        mysqli_stmt_bind_param($stmt1, "i", $product_id);
        mysqli_stmt_execute($stmt1);
        mysqli_stmt_close($stmt1);
    }

    // Ab products table se row hatao
    $stmt2 = mysqli_prepare($mydb, "DELETE FROM products WHERE product_id = ?");
    if (!$stmt2) {
        return false;
    }

    mysqli_stmt_bind_param($stmt2, "i", $product_id);
    $result = mysqli_stmt_execute($stmt2);
    mysqli_stmt_close($stmt2);

    // Uska upload folder bhi delete kar do
    $product_folder = "assets/products/product_" . $product_id . "/";
    delete_folder_recursive($product_folder);

    return $result;
}


// =========================================================
// HANDLE DELETE (single product)
// =========================================================
function handle_product_delete($mydb)
{
    $response = [
        'success_msg' => '',
        'error_msg'   => '',
    ];

    if (!isset($_POST['delete_single_product'])) {
        return $response;
    }

    $product_id = (int) ($_POST['delete_product_id'] ?? 0);

    if ($product_id <= 0) {
        $response['error_msg'] = "Invalid product.";
        return $response;
    }

    if (delete_product_info($mydb, $product_id)) {
        $response['success_msg'] = "Product deleted successfully!";
    } else {
        $response['error_msg'] = "Product delete fail ho gaya, dubara try karo.";
    }

    return $response;
}


// =========================================================
// DELETE MULTIPLE PRODUCTS (checkbox se select kiye hue)
// =========================================================
function delete_multiple_products($mydb, $product_ids)
{
    $deleted_count = 0;

    foreach ($product_ids as $product_id) {

        $product_id = (int) $product_id;

        if ($product_id > 0 && delete_product_info($mydb, $product_id)) {
            $deleted_count++;
        }
    }

    return $deleted_count;
}


// =========================================================
// HANDLE DELETE (multiple products - bulk delete)
// =========================================================
function handle_product_bulk_delete($mydb)
{
    $response = [
        'success_msg' => '',
        'error_msg'   => '',
    ];

    if (!isset($_POST['delete_selected_products'])) {
        return $response;
    }

    $ids_raw = trim($_POST['selected_product_ids'] ?? '');

    if ($ids_raw === '') {
        $response['error_msg'] = "Koi product select nahi kiya gaya.";
        return $response;
    }

    $product_ids = array_filter(array_map('intval', explode(',', $ids_raw)));

    if (empty($product_ids)) {
        $response['error_msg'] = "Koi valid product select nahi hua.";
        return $response;
    }

    $deleted_count = delete_multiple_products($mydb, $product_ids);

    if ($deleted_count > 0) {
        $response['success_msg'] = $deleted_count . " product(s) deleted successfully!";
    } else {
        $response['error_msg'] = "Delete fail ho gaya, dubara try karo.";
    }

    return $response;
}
