<?php

// =========================================================
// GET ALL CATEGORY DATA
// =========================================================
function get_category_info($mydb)
{
    $query = "SELECT * FROM root_categories ORDER BY root_id DESC";

    $result = mysqli_query($mydb, $query);

    if (!$result) {
        return [];
    }

    $category_info = [];

    while ($row = mysqli_fetch_assoc($result)) {
        $category_info[] = $row;
    }

    return $category_info;
}


// =========================================================
// ADD NEW CATEGORY
// =========================================================
function add_category_info(
    $mydb,
    $root_name,
    $root_slug,
    $root_description,
    $meta_title,
    $meta_description,
    $meta_keywords,
    $root_status
) {

    $query = "INSERT INTO root_categories (
        root_name,
        root_slug,
        root_description,
        meta_title,
        meta_description,
        meta_keywords,
        root_status,
        created_at
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($mydb, $query);

    if (!$stmt) {
        return false;
    }

    // Current timestamp
    $created_at = time();

    mysqli_stmt_bind_param(
        $stmt,
        "sssssssi",
        $root_name,
        $root_slug,
        $root_description,
        $meta_title,
        $meta_description,
        $meta_keywords,
        $root_status,
        $created_at
    );

    $result = mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);

    return $result;
}


// =========================================================
// GET SINGLE CATEGORY
// =========================================================
function get_category_by_id($mydb, $root_id)
{
    $query = "SELECT * FROM root_categories WHERE root_id = ? LIMIT 1";

    $stmt = mysqli_prepare($mydb, $query);

    if (!$stmt) {
        return [];
    }

    mysqli_stmt_bind_param($stmt, "i", $root_id);

    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    if (!$result) {
        mysqli_stmt_close($stmt);
        return [];
    }

    $category = mysqli_fetch_assoc($result);

    mysqli_stmt_close($stmt);

    return $category ?: [];
}


// =========================================================
// UPDATE CATEGORY
// =========================================================
function update_category_info(
    $mydb,
    $root_id,
    $root_name,
    $root_slug,
    $root_description,
    $meta_title,
    $meta_description,
    $meta_keywords,
    $root_status
) {

    $query = "UPDATE root_categories SET
                root_name = ?,
                root_slug = ?,
                root_description = ?,
                meta_title = ?,
                meta_description = ?,
                meta_keywords = ?,
                root_status = ?,
                updated_at = ?
              WHERE root_id = ?";

    $stmt = mysqli_prepare($mydb, $query);

    if (!$stmt) {
        return false;
    }

    // Current update timestamp
    $updated_at = time();

    mysqli_stmt_bind_param(
        $stmt,
        "sssssssii",
        $root_name,
        $root_slug,
        $root_description,
        $meta_title,
        $meta_description,
        $meta_keywords,
        $root_status,
        $updated_at,
        $root_id
    );

    $result = mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);

    return $result;
}


// =========================================================
// HANDLE UPDATE CATEGORY (reads $_POST khud, direct SQL)
// Main page isko sirf ek line me call karega taaki page
// clean rahe.
// =========================================================
function handle_category_update($mydb)
{
    $response = [
        'category_single_data' => [],
        'success_msg'          => '',
        'error_msg'            => '',
    ];

    if (!isset($_POST['update_root_category'])) {
        return $response;
    }

    $root_id          = (int) ($_POST['root_id'] ?? 0);
    $root_name        = trim($_POST['root_name'] ?? '');
    $root_slug        = trim($_POST['root_slug'] ?? '');
    $root_description = trim($_POST['root_description'] ?? '');
    $meta_title       = trim($_POST['meta_title'] ?? '');
    $meta_description = trim($_POST['meta_description'] ?? '');
    $meta_keywords    = trim($_POST['meta_keywords'] ?? '');
    $root_status      = trim($_POST['root_status'] ?? 'Active');
    $updated_at       = time();

    if (!($root_id > 0 && $root_name !== '' && $root_slug !== '')) {
        $response['error_msg'] = "Root Name aur Slug dono zaroori hain.";
        return $response;
    }

    $query = "UPDATE root_categories SET
                root_name        = ?,
                root_slug        = ?,
                root_description = ?,
                meta_title       = ?,
                meta_description = ?,
                meta_keywords    = ?,
                root_status      = ?,
                updated_at       = ?
              WHERE root_id = ?";

    $stmt = mysqli_prepare($mydb, $query);

    if (!$stmt) {
        $response['error_msg'] = "Query prepare fail ho gayi.";
        return $response;
    }

    mysqli_stmt_bind_param(
        $stmt,
        "sssssssii",
        $root_name,
        $root_slug,
        $root_description,
        $meta_title,
        $meta_description,
        $meta_keywords,
        $root_status,
        $updated_at,
        $root_id
    );

    if (mysqli_stmt_execute($stmt)) {

        $response['success_msg'] = "Category updated successfully!";

        $response['category_single_data'] = [
            'root_id'          => $root_id,
            'root_name'        => $root_name,
            'root_slug'        => $root_slug,
            'root_description' => $root_description,
            'meta_title'       => $meta_title,
            'meta_description' => $meta_description,
            'meta_keywords'    => $meta_keywords,
            'root_status'      => $root_status,
        ];
    } else {
        $response['error_msg'] = "Update fail ho gaya, dubara try karo.";
    }

    mysqli_stmt_close($stmt);

    return $response;
}


// =========================================================
// DELETE CATEGORY
// =========================================================
function delete_category_info($mydb, $root_id)
{
    $query = "DELETE FROM root_categories WHERE root_id = ?";

    $stmt = mysqli_prepare($mydb, $query);

    if (!$stmt) {
        return false;
    }

    mysqli_stmt_bind_param($stmt, "i", $root_id);

    $result = mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);

    return $result;
}

?>