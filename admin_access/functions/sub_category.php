<?php
/* ===== SUB CATEGORY FUNCTIONS ===== */

define('SUB_CATE_TABLE', 'product_sub_cate');
define('SUB_CATE_UPLOAD_DIR', 'assets/sub_category/');

// Table nahi hai to khud bana dega
function sub_cate_create_table($mydb) {
    $mydb->query("CREATE TABLE IF NOT EXISTS `" . SUB_CATE_TABLE . "` (
        `sub_cate_id` int(11) NOT NULL AUTO_INCREMENT,
        `sub_cate_name` varchar(255) NOT NULL,
        `sub_cate_slug` varchar(255) NOT NULL,
        `sub_cate_img` varchar(255) DEFAULT NULL,
        `sub_cate_desc` text DEFAULT NULL,
        `sub_cate_status` enum('Active','Deactive') NOT NULL DEFAULT 'Active',
        `sub_cate_created` int(11) NOT NULL,
        `sub_cate_updated` int(11) DEFAULT NULL,
        PRIMARY KEY (`sub_cate_id`),
        UNIQUE KEY `sub_cate_slug` (`sub_cate_slug`),
        KEY `sub_cate_status` (`sub_cate_status`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
}

// Current page ka URL
function sub_cate_url() {
    return strtok($_SERVER['REQUEST_URI'], '?');
}

// Unique slug
function sub_cate_slug($mydb, $name, $ignore_id = 0) {
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9]+/', '-', $name), '-'));
    if ($slug === '') $slug = 'item';
    $base = $slug;
    $i = 1;
    while (true) {
        $stmt = $mydb->prepare("SELECT sub_cate_id FROM " . SUB_CATE_TABLE . " WHERE sub_cate_slug = ? AND sub_cate_id != ?");
        $stmt->bind_param("si", $slug, $ignore_id);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows == 0) break;
        $slug = $base . '-' . $i++;
    }
    return $slug;
}

// Image upload (naya filename, nahi to $old)
function sub_cate_upload($file, $old = '') {
    if (!empty($file['name']) && $file['error'] === 0) {
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif'])) return $old;
        if (!is_dir(SUB_CATE_UPLOAD_DIR)) mkdir(SUB_CATE_UPLOAD_DIR, 0777, true);
        $name = time() . '_' . rand(1000, 9999) . '.' . $ext;
        if (move_uploaded_file($file['tmp_name'], SUB_CATE_UPLOAD_DIR . $name)) {
            // purani image hata do
            if ($old && is_file(SUB_CATE_UPLOAD_DIR . $old)) unlink(SUB_CATE_UPLOAD_DIR . $old);
            return $name;
        }
    }
    return $old;
}

// "3d ago" format
function sub_cate_time_ago($ts) {
    $diff = time() - (int)$ts;
    if ($diff < 60)      return 'Just now';
    if ($diff < 3600)    return floor($diff / 60) . 'm ago';
    if ($diff < 86400)   return floor($diff / 3600) . 'h ago';
    if ($diff < 2592000) return floor($diff / 86400) . 'd ago';
    return date('d M Y', $ts);
}

// ALL DATA
function sub_cate_get_all($mydb) {
    $rows = [];
    $res = $mydb->query("SELECT * FROM " . SUB_CATE_TABLE . " ORDER BY sub_cate_id DESC");
    if (!$res) {
        echo '<div style="background:#fee2e2;color:#991b1b;padding:12px;margin:10px;border-radius:6px">'
           . 'Sub Category Query Error: ' . htmlspecialchars($mydb->error) . '</div>';
        return $rows;
    }
    while ($r = $res->fetch_assoc()) $rows[] = $r;
    return $rows;
}


// ACTIVE DATA (sirf wo jinka sub_cate_status = 'Active' ho)
function sub_cate_get_active($mydb) {
    $rows = [];
    $status = 'Active';
    $stmt = $mydb->prepare("SELECT * FROM " . SUB_CATE_TABLE . " WHERE sub_cate_status = ? ORDER BY sub_cate_id DESC");
    if (!$stmt) {
        echo '<div style="background:#fee2e2;color:#991b1b;padding:12px;margin:10px;border-radius:6px">'
           . 'Sub Category Query Error: ' . htmlspecialchars($mydb->error) . '</div>';
        return $rows;
    }
    $stmt->bind_param("s", $status);
    $stmt->execute();
    $res = $stmt->get_result();
    while ($r = $res->fetch_assoc()) $rows[] = $r;
    return $rows;
}


// SINGLE DATA
function sub_cate_get_single($mydb, $id) {
    $stmt = $mydb->prepare("SELECT * FROM " . SUB_CATE_TABLE . " WHERE sub_cate_id = ? LIMIT 1");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

// ADD
function sub_cate_add($mydb, $post, $file) {
    $name   = trim($post['sub_cate_name']);
    $slug   = sub_cate_slug($mydb, $name);
    $img    = sub_cate_upload($file);
    $desc   = trim($post['sub_cate_desc']);
    $status = ($post['sub_cate_status'] === 'Deactive') ? 'Deactive' : 'Active';
    $time   = time();

    $stmt = $mydb->prepare("INSERT INTO " . SUB_CATE_TABLE . " (sub_cate_name, sub_cate_slug, sub_cate_img, sub_cate_desc, sub_cate_status, sub_cate_created) VALUES (?,?,?,?,?,?)");
    $stmt->bind_param("sssssi", $name, $slug, $img, $desc, $status, $time);
    return $stmt->execute();
}

// UPDATE
function sub_cate_update($mydb, $id, $post, $file) {
    $old = sub_cate_get_single($mydb, $id);
    if (!$old) return false;

    $name   = trim($post['sub_cate_name']);
    $slug   = sub_cate_slug($mydb, $name, $id);
    $img    = sub_cate_upload($file, $old['sub_cate_img']);
    $desc   = trim($post['sub_cate_desc']);
    $status = ($post['sub_cate_status'] === 'Deactive') ? 'Deactive' : 'Active';
    $time   = time();

    $stmt = $mydb->prepare("UPDATE " . SUB_CATE_TABLE . " SET sub_cate_name=?, sub_cate_slug=?, sub_cate_img=?, sub_cate_desc=?, sub_cate_status=?, sub_cate_updated=? WHERE sub_cate_id=?");
    $stmt->bind_param("sssssii", $name, $slug, $img, $desc, $status, $time, $id);
    return $stmt->execute();
}

// DELETE (row + image file)
function sub_cate_delete($mydb, $id) {
    $old = sub_cate_get_single($mydb, $id);
    if (!$old) return false;

    $stmt = $mydb->prepare("DELETE FROM " . SUB_CATE_TABLE . " WHERE sub_cate_id = ?");
    $stmt->bind_param("i", $id);
    $ok = $stmt->execute();

    if ($ok && !empty($old['sub_cate_img'])) {
        $path = SUB_CATE_UPLOAD_DIR . $old['sub_cate_img'];
        if (is_file($path)) unlink($path);
    }
    return $ok;
}

// FORM HANDLER
function sub_cate_handle_post($mydb) {
    sub_cate_create_table($mydb);

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['sub_cate_action'])) {
        $file = $_FILES['sub_cate_img'] ?? ['name' => '', 'error' => 4];

        if ($_POST['sub_cate_action'] === 'add') {
            sub_cate_add($mydb, $_POST, $file);
        } elseif ($_POST['sub_cate_action'] === 'update') {
            sub_cate_update($mydb, (int)$_POST['sub_cate_id'], $_POST, $file);
        } elseif ($_POST['sub_cate_action'] === 'delete') {
            sub_cate_delete($mydb, (int)$_POST['sub_cate_id']);
        }
        header("Location: " . sub_cate_url());
        exit;
    }
}