<?php
/* =====================================================
   EXTRA HOME IMAGES - FUNCTIONS
   Save as: admin_access/functions/extra_home.php
   (db_config.php / event.php wale folder me)
===================================================== */

/* Table ka naam: khali rakho to apne aap dhoondh lega (home_bane_img_1 column wali table).
   Chaho to yahan seedha naam likh sakte ho, jaise 'extra_home' */
if (!defined('EXTRA_HOME_TABLE')) {
    define('EXTRA_HOME_TABLE', '');
}

/* Image max size (bytes) = 100 MB */
if (!defined('EXTRA_HOME_MAX_SIZE')) {
    define('EXTRA_HOME_MAX_SIZE', 100 * 1024 * 1024);
}


/* Table ka sahi naam nikalo (auto detect) */
function extra_home_table($mydb)
{
    static $found = null;

    if ($found !== null) {
        return $found;
    }

    if (EXTRA_HOME_TABLE !== '') {
        return $found = EXTRA_HOME_TABLE;
    }

    $res = mysqli_query(
        $mydb,
        "SELECT TABLE_NAME FROM information_schema.COLUMNS
         WHERE TABLE_SCHEMA = DATABASE() AND COLUMN_NAME = 'home_bane_img_1'
         LIMIT 1"
    );

    if ($res && ($row = mysqli_fetch_assoc($res))) {
        return $found = $row['TABLE_NAME'];
    }

    return $found = 'extra_home_img';
}


/* Saare image columns + label (group ke hisaab se) */
function extra_home_columns()
{
    return [
        'Banner Images' => [
            'home_bane_img_1' => 'Banner Image 1',
            'home_bane_img_2' => 'Banner Image 2',
            'home_bane_img_3' => 'Banner Image 3',
            'home_bane_img_4' => 'Banner Image 4',
        ],
        'Journey (Jar) Images' => [
            'home_jar_img_1' => 'Jar Image 1',
            'home_jar_img_2' => 'Jar Image 2',
            'home_jar_img_3' => 'Jar Image 3',
        ],
        'Event Images' => [
            'home_event_img_1' => 'Event Image 1',
            'home_event_img_2' => 'Event Image 2',
        ],
    ];
}


/* Flat list: sirf column names */
function extra_home_column_names()
{
    $names = [];
    foreach (extra_home_columns() as $group) {
        foreach ($group as $col => $label) {
            $names[] = $col;
        }
    }
    return $names;
}


/* Upload folder ka physical path (site root/assets/extra_home_img/) */
function extra_home_upload_dir()
{
    /* is file se 2 level upar = site root (admin_access/functions -> root) */
    $root = dirname(__DIR__, 2);
    return $root . '/assets/extra_home_img/';
}


/* Row lo. Row nahi hai to khali row bana do */
function get_extra_home_images($mydb)
{
    $table = extra_home_table($mydb);

    $res = mysqli_query($mydb, "SELECT * FROM `$table` ORDER BY extra_id ASC LIMIT 1");

    if ($res && mysqli_num_rows($res) > 0) {
        return mysqli_fetch_assoc($res);
    }

    /* Row nahi mili -> khali row insert karo */
    $cols   = extra_home_column_names();
    $fields = '`' . implode('`,`', $cols) . '`';
    $values = "'" . implode("','", array_fill(0, count($cols), '')) . "'";

    mysqli_query($mydb, "INSERT INTO `$table` ($fields) VALUES ($values)");

    $res = mysqli_query($mydb, "SELECT * FROM `$table` ORDER BY extra_id ASC LIMIT 1");

    return ($res && mysqli_num_rows($res) > 0) ? mysqli_fetch_assoc($res) : [];
}


/* Purani image file delete karo */
function extra_home_delete_file($fileName)
{
    $fileName = basename((string)$fileName);

    if ($fileName === '') {
        return;
    }

    $path = extra_home_upload_dir() . $fileName;

    if (is_file($path)) {
        @unlink($path);
    }
}


/* PHP upload error code -> message */
function extra_home_upload_error_text($code)
{
    switch ($code) {
        case UPLOAD_ERR_INI_SIZE:
        case UPLOAD_ERR_FORM_SIZE:
            return 'File server ki limit se badi hai (upload_max_filesize badhao).';
        case UPLOAD_ERR_PARTIAL:
            return 'File adhuri upload hui, dobara try karo.';
        case UPLOAD_ERR_NO_TMP_DIR:
            return 'Server me temp folder nahi mila.';
        case UPLOAD_ERR_CANT_WRITE:
            return 'Server disk par file nahi likh pa raha.';
        default:
            return 'Upload fail ho gaya.';
    }
}


/*
 * Ek image upload karo.
 * Return: ['ok' => bool, 'name' => saved file name, 'error' => msg]
 */
function extra_home_save_upload($file, $column)
{
    if (!isset($file['error']) || $file['error'] !== UPLOAD_ERR_OK) {
        $code = isset($file['error']) ? $file['error'] : -1;
        return ['ok' => false, 'name' => '', 'error' => extra_home_upload_error_text($code)];
    }

    if ($file['size'] > EXTRA_HOME_MAX_SIZE) {
        return ['ok' => false, 'name' => '', 'error' => 'Image 100MB se badi hai.'];
    }

    /* Real mime type check */
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime  = $finfo->file($file['tmp_name']);

    $allowed = [
        'image/jpeg' => 'jpg',
        'image/png'  => 'png',
        'image/webp' => 'webp',
        'image/gif'  => 'gif',
    ];

    if (!isset($allowed[$mime])) {
        return ['ok' => false, 'name' => '', 'error' => 'Sirf JPG, PNG, WEBP ya GIF allowed hai.'];
    }

    if (@getimagesize($file['tmp_name']) === false) {
        return ['ok' => false, 'name' => '', 'error' => 'Yeh valid image nahi hai.'];
    }

    $dir = extra_home_upload_dir();

    if (!is_dir($dir) && !mkdir($dir, 0755, true)) {
        return ['ok' => false, 'name' => '', 'error' => 'Folder nahi ban paya: assets/extra_home_img/'];
    }

    $newName = $column . '_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $allowed[$mime];

    if (!move_uploaded_file($file['tmp_name'], $dir . $newName)) {
        return ['ok' => false, 'name' => '', 'error' => 'File save nahi ho payi (folder permission check karo).'];
    }

    return ['ok' => true, 'name' => $newName, 'error' => ''];
}


/*
 * Form submit hone par saari images update karo.
 * $_FILES[column] se new image, $_POST['remove_column'] se hatana.
 * Return: ['success' => bool, 'messages' => [..], 'errors' => [..]]
 */
function update_extra_home_images($mydb)
{
    $table   = extra_home_table($mydb);
    $current = get_extra_home_images($mydb);

    $result = ['success' => false, 'messages' => [], 'errors' => []];

    /* File post_max_size se badi ho to PHP $_POST aur $_FILES dono khali kar deta hai */
    if (empty($_POST) && empty($_FILES) && !empty($_SERVER['CONTENT_LENGTH'])) {
        $result['errors'][] = 'File server ki limit se badi hai. php.ini / .htaccess me post_max_size aur upload_max_filesize badhao.';
        return $result;
    }

    if (empty($current) || !isset($current['extra_id'])) {
        $result['errors'][] = 'Table me row nahi mili.';
        return $result;
    }

    $setParts = [];
    $values   = [];
    $types    = '';
    $oldFiles = [];   /* update success hone ke baad delete honge */

    foreach (extra_home_column_names() as $col) {

        $old = isset($current[$col]) ? (string)$current[$col] : '';

        /* 1) Nayi image upload hui */
        if (isset($_FILES[$col]) && $_FILES[$col]['error'] !== UPLOAD_ERR_NO_FILE) {

            $up = extra_home_save_upload($_FILES[$col], $col);

            if ($up['ok']) {
                $setParts[] = "`$col` = ?";
                $values[]   = $up['name'];
                $types     .= 's';
                if ($old !== '') {
                    $oldFiles[] = $old;
                }
            } else {
                $result['errors'][] = $col . ': ' . $up['error'];
            }

            continue;
        }

        /* 2) Image hatani hai */
        if (!empty($_POST['remove_' . $col]) && $old !== '') {
            $setParts[] = "`$col` = ?";
            $values[]   = '';
            $types     .= 's';
            $oldFiles[] = $old;
        }
    }

    if (empty($setParts)) {
        if (empty($result['errors'])) {
            $result['errors'][] = 'Koi change nahi mila.';
        }
        return $result;
    }

    $sql  = "UPDATE `$table` SET " . implode(', ', $setParts) . " WHERE extra_id = ?";
    $stmt = mysqli_prepare($mydb, $sql);

    if (!$stmt) {
        $result['errors'][] = 'Database error: ' . mysqli_error($mydb);
        return $result;
    }

    $types   .= 'i';
    $values[] = (int)$current['extra_id'];

    mysqli_stmt_bind_param($stmt, $types, ...$values);

    if (mysqli_stmt_execute($stmt)) {

        /* DB update ho gaya -> ab purani files delete karo */
        foreach ($oldFiles as $f) {
            extra_home_delete_file($f);
        }

        $result['success']    = true;
        $result['messages'][] = 'Image successfully added!';

    } else {
        $result['errors'][] = 'Update fail: ' . mysqli_stmt_error($stmt);
    }

    mysqli_stmt_close($stmt);

    return $result;
}