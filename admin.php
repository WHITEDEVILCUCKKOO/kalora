<?php

session_start();

if (!isset($_SESSION['user_id'])) {

    header("Location: login.php");
    exit();
}

?>


<!-- 




-->


<?php include "admin_access/db_config.php" ?>


<?php

function timeAgo($timestamp)
{
    $time = time() - $timestamp;

    if ($time < 60) {
        return $time . "sec ago";
    } elseif ($time < 3600) {
        return floor($time / 60) . "m ago";
    } elseif ($time < 86400) {
        return floor($time / 3600) . "h ago";
    } elseif ($time < 2592000) {
        return floor($time / 86400) . "d ago";
    } elseif ($time < 31536000) {
        return floor($time / 2592000) . "m ago";
    } else {
        return floor($time / 31536000) . "y ago";
    }
}

?>

<!-- loging -->
<?php

$email = $_SESSION['email'];

$result = mysqli_query(
    $mydb,
    "SELECT * FROM login_users WHERE user_email='$email'"
);

$get_data = mysqli_fetch_assoc($result);

// email change

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['change_email_btn'])) {

    $new_email = mysqli_real_escape_string(
        $mydb,
        trim($_POST['change_email'])
    );

    $current_password = mysqli_real_escape_string(
        $mydb,
        trim($_POST['email_current_password'])
    );

    if ($current_password != $get_data['user_password']) {

        echo "<script>alert('Current password is incorrect.');</script>";
    } else {

        $check_email = mysqli_query(
            $mydb,
            "SELECT user_id
                    FROM login_users
                    WHERE user_email='$new_email'
                    AND user_email != '$email'"
        );

        if (mysqli_num_rows($check_email) > 0) {

            echo "<script>alert('Email already exists.');</script>";
        } else {

            $update = mysqli_query(
                $mydb,
                "UPDATE login_users
                        SET user_email='$new_email'
                        WHERE user_email='$email'"
            );

            if ($update) {

                session_unset();
                session_destroy();

                echo "
                        <script>
                            alert('Email updated successfully. Please login again.');
                            window.location='login.php';
                        </script>";
                exit;
            }
        }
    }
}

// password chnange

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['change_password_btn'])) {

    $current_password = mysqli_real_escape_string(
        $mydb,
        trim($_POST['current_password'])
    );

    $new_password = mysqli_real_escape_string(
        $mydb,
        trim($_POST['new_password'])
    );

    $confirm_password = mysqli_real_escape_string(
        $mydb,
        trim($_POST['confirm_password'])
    );

    if ($current_password != $get_data['user_password']) {

        echo "<script>alert('Current password is incorrect.');</script>";
    } elseif ($new_password != $confirm_password) {

        echo "<script>alert('New password and confirm password do not match.');</script>";
    } else {

        $update = mysqli_query(
            $mydb,
            "UPDATE login_users
                    SET user_password='$new_password'
                    WHERE user_email='$email'"
        );

        if ($update) {

            session_unset();
            session_destroy();

            echo "
                    <script>
                        alert('Password updated successfully. Please login again.');
                        window.location='login.php';
                    </script>";
            exit;
        }
    }
}
?>



<!-- blog add new -->
<?php

if (isset($_POST['form_action']) && $_POST['form_action'] === 'add_blog') {

    // Form Data
    $blog_title   = mysqli_real_escape_string($mydb, $_POST['blog_title']);
    $blog_author  = mysqli_real_escape_string($mydb, $_POST['blog_author']);
    $blog_content = mysqli_real_escape_string($mydb, $_POST['blog_content1252']);

    // Slug Generate
    $blog_slug = strtolower(trim($blog_title));
    $blog_slug = preg_replace('/[^a-z0-9-]+/', '-', $blog_slug);
    $blog_slug = trim($blog_slug, '-');

    // Created Time
    $created_at = time();

    // Image Upload
    $blog_img = "";

    if (isset($_FILES['blog_image']) && $_FILES['blog_image']['error'] == 0) {
        $upload_dir = "assets/blog/";

        $file_name = $_FILES['blog_image']['name'];
        $tmp_name  = $_FILES['blog_image']['tmp_name'];

        $ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        // Original filename without extension
        $base_name = pathinfo($file_name, PATHINFO_FILENAME);

        $new_file_name = $base_name . "." . $ext;

        // Check if file already exists
        if (file_exists($upload_dir . $new_file_name)) {
            $new_file_name = $base_name . "_" . time() . "_" . rand(1000, 9999) . "." . $ext;
        }

        if (move_uploaded_file($tmp_name, $upload_dir . $new_file_name)) {
            $blog_img = $new_file_name;
        }
    }

    // Save karne se pehle clean karo
    $blog_content = str_replace(['<!--StartFragment-->', '<!--EndFragment-->'], '', $blog_content);
    $blog_content = trim($blog_content);

    // Meta Data
    $blog_meta_title = $blog_title;
    $blog_meta_desc  = substr(strip_tags($blog_content), 0, 160);

    // Insert Query
    $sql = "INSERT INTO blog
        (
            blog_title,
            blog_slug,
            blog_content,
            blog_img,
            blog_author,
            blog_meta_title,
            blog_meta_desc,
            created_at
        )
        VALUES
        (
            '$blog_title',
            '$blog_slug',
            '$blog_content',
            '$blog_img',
            '$blog_author',
            '$blog_meta_title',
            '$blog_meta_desc',
            '$created_at'
        )";

    $result = mysqli_query($mydb, $sql);

    if ($result) {
        echo "<script>alert('Blog Added Successfully!'); window.location.href='admin.php';</script>";
    } else {
        echo "<script>alert('Error: " . addslashes(mysqli_error($mydb)) . "');</script>";
    }
    exit();
}
?>

<!-- update blog -->
<?php
if (isset($_POST['form_action']) && $_POST['form_action'] === 'update_blog') {

    $blog_id      = mysqli_real_escape_string($mydb, $_POST['blog_id_name']);
    $blog_title   = mysqli_real_escape_string($mydb, $_POST['blog_title2']);
    $blog_author  = mysqli_real_escape_string($mydb, $_POST['blog_author2']);
    $blog_content = mysqli_real_escape_string($mydb, $_POST['blog_content2']);
    $updated_at   = time();

    // Clean content
    $blog_content = str_replace(['<!--StartFragment-->', '<!--EndFragment-->'], '', $blog_content);
    $blog_content = trim($blog_content);

    // Meta
    $blog_meta_title = $blog_title;
    $blog_meta_desc  = substr(strip_tags($blog_content), 0, 160);

    // Purana data fetch karo
    $old_result = mysqli_query($mydb, "SELECT blog_title, blog_img, blog_slug FROM blog WHERE blog_id = '$blog_id'");
    $old_data   = mysqli_fetch_assoc($old_result);

    if (!$old_data) {
        echo "<script>alert('Blog not found!'); window.location.href='admin.php';</script>";
        exit();
    }

    $old_img    = $old_data['blog_img'];
    $old_title  = $old_data['blog_title'];
    $old_slug   = $old_data['blog_slug'];

    // Slug — sirf tab change karo jab title change hua ho
    if (trim($blog_title) !== trim($old_title)) {
        $blog_slug = strtolower(trim($blog_title));
        $blog_slug = preg_replace('/[^a-z0-9-]+/', '-', $blog_slug);
        $blog_slug = trim($blog_slug, '-');
    } else {
        $blog_slug = $old_slug; // purana slug hi rakho
    }

    // Image handle
    $upload_dir = "assets/blog/";
    $blog_img   = $old_img; // default: purani image

    if (isset($_FILES['blog_image2']) && $_FILES['blog_image2']['error'] == 0) {

        $file_name = $_FILES['blog_image2']['name'];
        $tmp_name  = $_FILES['blog_image2']['tmp_name'];
        $ext       = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $base_name = pathinfo($file_name, PATHINFO_FILENAME);

        $new_file_name = $base_name . "." . $ext;

        // Same name pahle se hai to rename
        if (file_exists($upload_dir . $new_file_name)) {
            $new_file_name = $base_name . "_" . time() . "_" . rand(1000, 9999) . "." . $ext;
        }

        if (move_uploaded_file($tmp_name, $upload_dir . $new_file_name)) {

            // Purani image delete karo
            if (!empty($old_img) && file_exists($upload_dir . $old_img)) {
                unlink($upload_dir . $old_img);
            }

            $blog_img = $new_file_name;
        }
    }

    // Update Query
    $sql = "UPDATE blog SET 
                blog_title      = '$blog_title',
                blog_slug       = '$blog_slug',
                blog_content    = '$blog_content',
                blog_img        = '$blog_img',
                blog_author     = '$blog_author',
                blog_meta_title = '$blog_meta_title',
                blog_meta_desc  = '$blog_meta_desc',
                updated_at      = '$updated_at'
            WHERE blog_id = '$blog_id'";

    $result = mysqli_query($mydb, $sql);

    if ($result) {
        echo "<script>alert('Blog Updated Successfully!'); window.location.href='admin.php';</script>";
    } else {
        echo "<script>alert('Error: " . addslashes(mysqli_error($mydb)) . "');</script>";
    }
    exit();
}
?>


<!-- functions -->
<?php
include './admin_access/functions/footer.php';



?>

<!-- update globle info -->
<?php
include "./admin_access/functions/global_info.php";
?>
<?php

/* =========================================================
   UPDATE GLOBAL INFO FORM SUBMIT
========================================================= */

if (
    isset($_POST['update_global_info'])
) {

    $global_update_result = update_global_info($mydb);

    if ($global_update_result['status']) {

        echo '<script>
            alert("' .
            htmlspecialchars(
                $global_update_result['message'],
                ENT_QUOTES
            ) .
            '");
            window.location.href = window.location.href;
        </script>';

        exit;
    } else {

        echo '<script>
            alert("' .
            htmlspecialchars(
                $global_update_result['message'],
                ENT_QUOTES
            ) .
            '");
        </script>';
    }
}


/* =========================================================
   GET CURRENT GLOBAL INFO
========================================================= */

$global_info = get_global_info($mydb);

?>

<!-- categor code -->
<?php
include "./admin_access/functions/category_info.php";
?>
<?php

if (isset($_POST['add_root_category'])) {

    $root_name = trim($_POST['root_name']);
    $root_slug = trim($_POST['root_slug']);
    $root_description = trim($_POST['root_description']);

    $meta_title = trim($_POST['meta_title']);
    $meta_description = trim($_POST['meta_description']);
    $meta_keywords = trim($_POST['meta_keywords']);

    $root_status = $_POST['root_status'];

    $result = add_category_info(
        $mydb,
        $root_name,
        $root_slug,
        $root_description,
        $meta_title,
        $meta_description,
        $meta_keywords,
        $root_status
    );

    if ($result) {
        echo "
<script>
    window.location.href='admin.php'
</script>
";
    } else {
        echo "Category could not be added";
    }
}

?>

<?php

if (isset($_POST['delete_category'])) {

    $root_id = intval($_POST['root_id']);

    $result = delete_category_info($mydb, $root_id);

    if ($result) {

        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    } else {

        echo "Item delete nahi ho saka.";
    }
}

?>

<?php

if (isset($_GET['get_category'])) {

    $root_id = intval($_GET['get_category']);

    $category_single_data = get_category_by_id($mydb, $root_id);
}

?>


<?php

if (isset($_POST['update_root_category'])) {

    $root_id = intval($_POST['root_id']);

    $root_name = trim($_POST['root_name']);
    $root_slug = trim($_POST['root_slug']);
    $root_description = trim($_POST['root_description']);

    $meta_title = trim($_POST['meta_title']);
    $meta_description = trim($_POST['meta_description']);
    $meta_keywords = trim($_POST['meta_keywords']);

    $root_status = $_POST['root_status'];


    $result = update_category_info(
        $mydb,
        $root_id,
        $root_name,
        $root_slug,
        $root_description,
        $meta_title,
        $meta_description,
        $meta_keywords,
        $root_status
    );


    if ($result) {

        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    } else {

        echo "Category update nahi ho saki.";
    }
}

?>

<?php

// =========================================================
// UPDATE CATEGORY - DIRECT (No function.php, No API call)
// =========================================================

// Poora update-handling logic ab admin_access/functions/category_info.php
// ke andar handle_category_update() function me hai - yahan sirf call.
$update_result        = handle_category_update($mydb);
$category_single_data = $update_result['category_single_data'];
$update_success_msg   = $update_result['success_msg'];
$update_error_msg     = $update_result['error_msg'];

?>



<!-- brand -->
<?php include 'admin_access/functions/brand.php' ?>
<?php

$brand_add_result      = handle_brand_add($mydb);
$brand_update_result   = handle_brand_update($mydb);
$brand_success_msg     = $brand_add_result['success_msg'] ?: $brand_update_result['success_msg'];
$brand_error_msg       = $brand_add_result['error_msg'] ?: $brand_update_result['error_msg'];
$root_category_options = get_category_info($mydb); // dropdown ke liye


$brand_delete_result = handle_brand_delete($mydb);
$brand_success_msg    = $brand_add_result['success_msg'] ?: $brand_update_result['success_msg'] ?: $brand_delete_result['success_msg'];
$brand_error_msg      = $brand_add_result['error_msg'] ?: $brand_update_result['error_msg'] ?: $brand_delete_result['error_msg'];

?>



<!-- product  -->
<?php include_once 'admin_access/functions/products.php' ?>
<?php include_once 'admin_access/functions/category_info.php' ?>
<?php include_once 'admin_access/functions/brand.php' ?>
<?php

$product_add_result         = handle_product_add($mydb);
$product_update_result      = handle_product_update($mydb);
$product_delete_result      = handle_product_delete($mydb);
$product_bulk_delete_result = handle_product_bulk_delete($mydb);

$product_success_msg = $product_add_result['success_msg']
    ?: $product_update_result['success_msg']
    ?: $product_delete_result['success_msg']
    ?: $product_bulk_delete_result['success_msg'];

$product_error_msg = $product_add_result['error_msg']
    ?: $product_update_result['error_msg']
    ?: $product_delete_result['error_msg']
    ?: $product_bulk_delete_result['error_msg'];

$product_info          = get_product_info($mydb);
$root_category_options = get_category_info($mydb);
$brand_options          = get_brand_info($mydb);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="assets/logos/<?php echo htmlspecialchars($global_info['facion_icon'] ?? ''); ?>">
    <title>Kalora Admin</title>
    <link rel="stylesheet" href="admin_access/admin.css">
    <link rel="stylesheet" href="admin_access/css/category.css">

    <!-- <script src="assets/js/blog.js"></script> -->
</head>

<body>


    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
    </style>

    <style>
        .title_admin {
            color: #0284c7;
        }
    </style>


    <main class="contact-main">



        <section class="application_section">

            <!-- left side  -->
            <section class="left_section_1" id="left_section_box">

                <h1 class="title_admin">Kalora</h1>
                <h6>Getting Started</h6>

                <ul class="top_nave_appli">
                    <li class="subbox_link active" id="overview_btn_124" data-set="overview" onclick="show_this_box(this); close_all_sub_links_ha()">
                        <span class="svg_icon_box">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" class="sub_set_iocn_svg svg_icon_color-active" data-subset="icon_1">
                                <path d="M320.5 437.1C295.3 405.4 280.4 377.7 275.5 353.9C253 265.9 388.1 265.9 365.6 353.9C360.2 378.1 345.3 405.9 320.6 437.1L320.5 437.1zM458.7 510.3C416.6 528.6 375 499.4 339.4 459.8C443.3 329.7 385.5 259.8 320.6 259.8C265.7 259.8 235.4 306.3 247.3 360.3C254.2 389.5 272.5 422.7 301.7 459.8C269.2 495.8 241.2 512.5 216.5 514.7C166.5 522.1 127.4 473.6 145.2 423.6C160.3 384.4 256.9 192.4 261.1 182C276.9 151.9 286.7 124.6 320.5 124.6C352.8 124.6 363.9 150.5 380.9 184.5C416.9 255.1 470.3 362 495.7 423.6C508.9 456.7 494.3 494.9 458.7 510.2zM505.7 374.2C376.8 99.9 369.7 96 320.6 96C275.1 96 255.7 127.7 235.9 168.8C129.7 381.1 119.5 411.2 118.6 413.8C93.4 483.1 145.3 544 208.2 544C229.9 544 268.8 537.9 320.6 481.6C379.3 545.4 421.9 544 433 544C495.9 544.1 547.9 483.1 522.6 413.8C522.6 409.9 505.8 374.9 505.8 374.2L505.8 374.2z" />
                            </svg>
                        </span>

                        Global Infomtions
                    </li>

                    <li class="subbox_link romove_device_links" id="journeys_btn_124" data-set="journeys" onclick="window.show_this_box(this); show_sub_link(this);">
                        <span class="svg_icon_box">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" class="sub_set_iocn_svg" data-subset="icon_12">
                                <path d="M288 88C288 74.7 298.7 64 312 64C457.8 64 576 182.2 576 328C576 341.3 565.3 352 552 352C538.7 352 528 341.3 528 328C528 208.7 431.3 112 312 112C298.7 112 288 101.3 288 88zM144 160C170.5 160 192 181.5 192 208L192 432C192 458.5 213.5 480 240 480C266.5 480 288 458.5 288 432C288 405.5 266.5 384 240 384C231.2 384 224 376.8 224 368L224 304C224 295.2 231.2 288 240 288C319.5 288 384 352.5 384 432C384 511.5 319.5 576 240 576C160.5 576 96 511.5 96 432L96 208C96 181.5 117.5 160 144 160zM312 160C404.8 160 480 235.2 480 328C480 341.3 469.3 352 456 352C442.7 352 432 341.3 432 328C432 261.7 378.3 208 312 208C298.7 208 288 197.3 288 184C288 170.7 298.7 160 312 160z" />
                            </svg>
                        </span>
                        Blog
                    </li>

                    <!-- Journeys -->
                    <div class="sub_links_of_allawn" id="journeys_sub_box" data-set="journeys-sub" style="display: none;">
                        <ul>
                            <li class="sole78m romove_device_links" id="contect__59" onclick="link_sole_action(this)" data-set="contect-59">Journey Campaign</li>
                            <li class="sole78m romove_device_links" id="contect__60" onclick="link_sole_action(this)" data-set="contect-60">Journey Reports</li>
                        </ul>
                    </div>

                    <li class="subbox_link romove_device_links" id="dashbord_btn_124" data-set="dashbord" onclick="show_this_box(this); close_all_sub_links_ha()">
                        <span class="svg_icon_box">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" class="sub_set_iocn_svg" data-subset="icon_3">
                                <path d="M96 96C113.7 96 128 110.3 128 128L128 464C128 472.8 135.2 480 144 480L544 480C561.7 480 576 494.3 576 512C576 529.7 561.7 544 544 544L144 544C99.8 544 64 508.2 64 464L64 128C64 110.3 78.3 96 96 96zM208 288C225.7 288 240 302.3 240 320L240 384C240 401.7 225.7 416 208 416C190.3 416 176 401.7 176 384L176 320C176 302.3 190.3 288 208 288zM352 224L352 384C352 401.7 337.7 416 320 416C302.3 416 288 401.7 288 384L288 224C288 206.3 302.3 192 320 192C337.7 192 352 206.3 352 224zM432 256C449.7 256 464 270.3 464 288L464 384C464 401.7 449.7 416 432 416C414.3 416 400 401.7 400 384L400 288C400 270.3 414.3 256 432 256zM576 160L576 384C576 401.7 561.7 416 544 416C526.3 416 512 401.7 512 384L512 160C512 142.3 526.3 128 544 128C561.7 128 576 142.3 576 160z" />
                            </svg>
                        </span>
                        Courses
                    </li>
                </ul>

                <div class="line_rola"></div>

                <div class="bottom_nave_apli">
                    <h6>Products infomation</h6>
                    <ul class="mosd82">
                        <li class="subbox_link" id="sms_btn_124" onclick="show_this_box(this); show_sub_link(this);" data-set="sms">
                            <span class="svg_icon_box">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" class="sub_set_iocn_svg" data-subset="icon_4">
                                    <path d="M341.8 72.6C329.5 61.2 310.5 61.2 298.3 72.6L74.3 280.6C64.7 289.6 61.5 303.5 66.3 315.7C71.1 327.9 82.8 336 96 336L112 336L112 512C112 547.3 140.7 576 176 576L464 576C499.3 576 528 547.3 528 512L528 336L544 336C557.2 336 569 327.9 573.8 315.7C578.6 303.5 575.4 289.5 565.8 280.6L341.8 72.6zM304 384L336 384C362.5 384 384 405.5 384 432L384 528L256 528L256 432C256 405.5 277.5 384 304 384z" />
                                </svg>
                            </span>
                            Products

                        </li>

                        <!-- sms sub link -->
                        <div class="sub_links_of_allawn" id="sms_sub_box" data-set="sms-sub">
                            <ul>
                                <li class="sole78m" id="contect__1" onclick="link_sole_action(this)" data-set="contect-1">Categorys</li>
                                <li class="sole78m" id="contect__2" onclick="link_sole_action(this)" data-set="contect-2">Sub Categorys</li>
                                <li class="sole78m" id="contect__3" onclick="link_sole_action(this)" data-set="contect-3">Products</li>
                                <li class="sole78m romove_device_links" id="contect__4" onclick="link_sole_action(this)" data-set="contect-4">Manage Sender ID</li>
                                <li class="sole78m romove_device_links" id="contect__5" onclick="link_sole_action(this)" data-set="contect-5">Manage Template</li>
                                <li class="sole78m romove_device_links" id="contect__6" onclick="link_sole_action(this)" data-set="contect-6">My Routes</li>
                                <li class="sole78m romove_device_links" id="contect__7" onclick="link_sole_action(this)" data-set="contect-7">Contact Manager</li>
                                <li class="sole78m romove_device_links" id="contect__8" onclick="link_sole_action(this)" data-set="contect-8">Manage Group</li>
                                <li class="sole78m romove_device_links" id="contect__9" onclick="link_sole_action(this)" data-set="contect-9">Blacklist Numbers</li>
                                <li class="sole78m romove_device_links" id="contect__10" onclick="link_sole_action(this)" data-set="contect-10">SMS Reports</li>
                                <li class="sole78m romove_device_links" id="contect__11" onclick="link_sole_action(this)" data-set="contect-11">SMS API Integration</li>
                            </ul>
                        </div>

                        <li class="subbox_link romove_device_links" id="rcs_btn_124" data-set="rcs" onclick="show_this_box(this); show_sub_link(this);">
                            <span class="svg_icon_box"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" class="sub_set_iocn_svg" data-subset="icon_5">
                                    <path d="M144 128C144 92.7 172.7 64 208 64L432 64C467.3 64 496 92.7 496 128L496 512C496 547.3 467.3 576 432 576L208 576C172.7 576 144 547.3 144 512L144 128zM256 504C256 517.3 266.7 528 280 528L360 528C373.3 528 384 517.3 384 504C384 490.7 373.3 480 360 480L280 480C266.7 480 256 490.7 256 504zM432 128L208 128L208 432L432 432L432 128z" />
                                </svg></span>
                            RCS
                        </li>

                        <!-- Rcs sub link -->
                        <div class="sub_links_of_allawn" id="rcs_sub_box" data-set="rcs-sub">
                            <ul>
                                <li class="sole78m romove_device_links" id="contect__12" onclick="link_sole_action(this)" data-set="contect-12">RCS Dashbord</li>
                                <li class="sole78m romove_device_links" id="contect__13" onclick="link_sole_action(this)" data-set="contect-13">RCS Agent</li>
                                <li class="sole78m romove_device_links" id="contect__14" onclick="link_sole_action(this)" data-set="contect-14">Templates</li>
                                <li class="sole78m romove_device_links" id="contect__15" onclick="link_sole_action(this)" data-set="contect-15">Create Campaign</li>
                                <li class="sole78m romove_device_links" id="contect__16" onclick="link_sole_action(this)" data-set="contect-16">Manage Group</li>
                                <li class="sole78m romove_device_links" id="contect__17" onclick="link_sole_action(this)" data-set="contect-17">Blacklist Numbers</li>
                                <li class="sole78m romove_device_links" id="contect__18" onclick="link_sole_action(this)" data-set="contect-18">RCS Reports</li>
                                <li class="sole78m romove_device_links" id="contect__19" onclick="link_sole_action(this)" data-set="contect-19">Chatbot Automation</li>
                            </ul>
                        </div>

                        <li class="subbox_link romove_device_links" id="whatsapp_btn_124" data-set="whatsapp" onclick="show_this_box(this); show_sub_link(this);">
                            <span class="svg_icon_box"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" class="sub_set_iocn_svg" data-subset="icon_6">
                                    <path d="M476.9 161.1C435 119.1 379.2 96 319.9 96C197.5 96 97.9 195.6 97.9 318C97.9 357.1 108.1 395.3 127.5 429L96 544L213.7 513.1C246.1 530.8 282.6 540.1 319.8 540.1L319.9 540.1C442.2 540.1 544 440.5 544 318.1C544 258.8 518.8 203.1 476.9 161.1zM319.9 502.7C286.7 502.7 254.2 493.8 225.9 477L219.2 473L149.4 491.3L168 423.2L163.6 416.2C145.1 386.8 135.4 352.9 135.4 318C135.4 216.3 218.2 133.5 320 133.5C369.3 133.5 415.6 152.7 450.4 187.6C485.2 222.5 506.6 268.8 506.5 318.1C506.5 419.9 421.6 502.7 319.9 502.7zM421.1 364.5C415.6 361.7 388.3 348.3 383.2 346.5C378.1 344.6 374.4 343.7 370.7 349.3C367 354.9 356.4 367.3 353.1 371.1C349.9 374.8 346.6 375.3 341.1 372.5C308.5 356.2 287.1 343.4 265.6 306.5C259.9 296.7 271.3 297.4 281.9 276.2C283.7 272.5 282.8 269.3 281.4 266.5C280 263.7 268.9 236.4 264.3 225.3C259.8 214.5 255.2 216 251.8 215.8C248.6 215.6 244.9 215.6 241.2 215.6C237.5 215.6 231.5 217 226.4 222.5C221.3 228.1 207 241.5 207 268.8C207 296.1 226.9 322.5 229.6 326.2C232.4 329.9 268.7 385.9 324.4 410C359.6 425.2 373.4 426.5 391 423.9C401.7 422.3 423.8 410.5 428.4 397.5C433 384.5 433 373.4 431.6 371.1C430.3 368.6 426.6 367.2 421.1 364.5z" />
                                </svg></span>
                            WhatsApp
                        </li>

                        <!-- whatsapp sub link -->
                        <div class="sub_links_of_allawn" id="whatsapp_sub_box" data-set="whatsapp-sub">
                            <ul>
                                <li class="sole78m romove_device_links" id="contect__20" onclick="link_sole_action(this)" data-set="contect-20">WhatsApp Onboarding</li>
                                <li class="sole78m romove_device_links" id="contect__21" onclick="link_sole_action(this)" data-set="contect-21">Dashbord</li>
                                <li class="sole78m romove_device_links" id="contect__22" onclick="link_sole_action(this)" data-set="contect-22">Templates</li>
                                <li class="sole78m romove_device_links" id="contect__23" onclick="link_sole_action(this)" data-set="contect-23">Campaign</li>
                                <li class="sole78m romove_device_links" id="contect__24" onclick="link_sole_action(this)" data-set="contect-24">Reports</li>
                                <li class="sole78m romove_device_links" id="contect__25" onclick="link_sole_action(this)" data-set="contect-25">Agent</li>
                                <li class="sole78m romove_device_links" id="contect__26" onclick="link_sole_action(this)" data-set="contect-26">Chatbot Builder</li>
                                <li class="sole78m romove_device_links" id="contect__27" onclick="link_sole_action(this)" data-set="contect-27">Payment</li>
                                <li class="sole78m romove_device_links" id="contect__28" onclick="link_sole_action(this)" data-set="contect-28">Catalogue</li>
                            </ul>
                        </div>




                        <li class="subbox_link romove_device_links" id="voice_btn_124" data-set="voice" onclick="show_this_box(this); show_sub_link(this);">
                            <span class="svg_icon_box"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" class="sub_set_iocn_svg" data-subset="icon_7">
                                    <path d="M376 32C504.1 32 608 135.9 608 264C608 277.3 597.3 288 584 288C570.7 288 560 277.3 560 264C560 162.4 477.6 80 376 80C362.7 80 352 69.3 352 56C352 42.7 362.7 32 376 32zM384 224C401.7 224 416 238.3 416 256C416 273.7 401.7 288 384 288C366.3 288 352 273.7 352 256C352 238.3 366.3 224 384 224zM352 152C352 138.7 362.7 128 376 128C451.1 128 512 188.9 512 264C512 277.3 501.3 288 488 288C474.7 288 464 277.3 464 264C464 215.4 424.6 176 376 176C362.7 176 352 165.3 352 152zM176.1 65.4C195.8 60 216.4 70.1 224.2 88.9L264.7 186.2C271.6 202.7 266.8 221.8 252.9 233.2L208.8 269.3C241.3 340.9 297.8 399.3 368.1 434.2L406.7 387C418 373.1 437.1 368.4 453.7 375.2L551 415.8C569.8 423.6 579.9 444.2 574.5 463.9L573 469.4C555.4 534.1 492.9 589.3 416.6 573.2C241.6 536.1 103.9 398.4 66.8 223.4C50.7 147.1 105.9 84.6 170.5 66.9L176 65.4z" />
                                </svg></span>
                            Voice
                        </li>

                        <!-- voice sub link -->
                        <div class="sub_links_of_allawn" id="voice_sub_box" data-set="voice-sub">
                            <ul>
                                <li class="sole78m romove_device_links" id="contect__29" onclick="link_sole_action(this)" data-set="contect-29">Voice Dashbord</li>
                                <li class="sole78m romove_device_links" id="contect__30" onclick="link_sole_action(this)" data-set="contect-30">Voice Files</li>
                                <li class="sole78m romove_device_links" id="contect__31" onclick="link_sole_action(this)" data-set="contect-31">Components</li>
                                <li class="sole78m romove_device_links" id="contect__32" onclick="link_sole_action(this)" data-set="contect-32">Voice Campaign Management</li>
                                <li class="sole78m romove_device_links" id="contect__33" onclick="link_sole_action(this)" data-set="contect-33">Agent Monitoring</li>
                                <li class="sole78m romove_device_links" id="contect__34" onclick="link_sole_action(this)" data-set="contect-34">Report</li>
                                <li class="sole78m romove_device_links" id="contect__35" onclick="link_sole_action(this)" data-set="contect-35">Manage Agents</li>
                                <li class="sole78m romove_device_links" id="contect__36" onclick="link_sole_action(this)" data-set="contect-36">Manage Remarks</li>
                            </ul>
                        </div>

                        <li class="subbox_link romove_device_links" id="ivr_btn_124" data-set="ivr" onclick="show_this_box(this); show_sub_link(this);">
                            <span class="svg_icon_box"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" class="sub_set_iocn_svg" data-subset="icon_8">
                                    <path d="M224.2 89C216.3 70.1 195.7 60.1 176.1 65.4L170.6 66.9C106 84.5 50.8 147.1 66.9 223.3C104 398.3 241.7 536 416.7 573.1C493 589.3 555.5 534 573.1 469.4L574.6 463.9C580 444.2 569.9 423.6 551.1 415.8L453.8 375.3C437.3 368.4 418.2 373.2 406.8 387.1L368.2 434.3C297.9 399.4 241.3 341 208.8 269.3L253 233.3C266.9 222 271.6 202.9 264.8 186.3L224.2 89z" />
                                </svg></span>
                            IVR
                        </li>

                        <!-- IVR / Campaign -->
                        <div class="sub_links_of_allawn" id="ivr_sub_box" data-set="ivr-sub">
                            <ul>
                                <li class="sole78m romove_device_links" id="contect__37" onclick="link_sole_action(this)" data-set="contect-37">Dashboard</li>
                                <li class="sole78m romove_device_links" id="contect__38" onclick="link_sole_action(this)" data-set="contect-38">Campaign</li>
                                <li class="sole78m romove_device_links" id="contect__39" onclick="link_sole_action(this)" data-set="contect-39">Voice Files</li>
                                <li class="sole78m romove_device_links" id="contect__40" onclick="link_sole_action(this)" data-set="contect-40">Components</li>
                                <li class="sole78m romove_device_links" id="contect__41" onclick="link_sole_action(this)" data-set="contect-41">Manage Agent</li>
                                <li class="sole78m romove_device_links" id="contect__42" onclick="link_sole_action(this)" data-set="contect-42">Manage Remarks</li>
                                <li class="sole78m romove_device_links" id="contect__43" onclick="link_sole_action(this)" data-set="contect-43">Report</li>
                            </ul>
                        </div>

                        <li class="subbox_link romove_device_links" id="click_to_call_btn_124" data-set="click_to_call" onclick="show_this_box(this); show_sub_link(this);">
                            <span class="svg_icon_box">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" class="sub_set_iocn_svg" data-subset="icon_9">
                                    <path d="M224.2 89C216.3 70.1 195.7 60.1 176.1 65.4L170.6 66.9C106 84.5 50.8 147.1 66.9 223.3C104 398.3 241.7 536 416.7 573.1C493 589.3 555.5 534 573.1 469.4L574.6 463.9C580 444.2 569.9 423.6 551.1 415.8L453.8 375.3C437.3 368.4 418.2 373.2 406.8 387.1L368.2 434.3C297.9 399.4 241.3 341 208.8 269.3L253 233.3C266.9 222 271.6 202.9 264.8 186.3L224.2 89z" />
                                </svg>
                            </span>
                            Click To Call
                        </li>

                        <!-- Click To Call -->
                        <div class="sub_links_of_allawn" id="click_to_call_sub_box" data-set="click_to_call-sub">
                            <ul>
                                <li class="sole78m romove_device_links" id="contect__44" onclick="link_sole_action(this)" data-set="contect-44">Dashboard</li>
                                <li class="sole78m romove_device_links" id="contect__45" onclick="link_sole_action(this)" data-set="contect-45">Manage Agent</li>
                                <li class="sole78m romove_device_links" id="contect__46" onclick="link_sole_action(this)" data-set="contect-46">Campaign</li>
                                <li class="sole78m romove_device_links" id="contect__47" onclick="link_sole_action(this)" data-set="contect-47">Agent Monitoring</li>
                                <li class="sole78m romove_device_links" id="contect__48" onclick="link_sole_action(this)" data-set="contect-48">Manage Remarks</li>
                                <li class="sole78m romove_device_links" id="contect__49" onclick="link_sole_action(this)" data-set="contect-49">Report</li>
                            </ul>
                        </div>

                        <li class="subbox_link romove_device_links" id="text_to_speech_btn_124" data-set="text_to_speech" onclick="show_this_box(this); show_sub_link(this);">
                            <span class="svg_icon_box"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" class="sub_set_iocn_svg" data-subset="icon_10">
                                    <path d="M112 416L160 416L294.1 535.2C300.5 540.9 308.7 544 317.2 544C336.4 544 352 528.4 352 509.2L352 130.8C352 111.6 336.4 96 317.2 96C308.7 96 300.5 99.1 294.1 104.8L160 224L112 224C85.5 224 64 245.5 64 272L64 368C64 394.5 85.5 416 112 416zM505.1 171C494.8 162.6 479.7 164.2 471.3 174.5C462.9 184.8 464.5 199.9 474.8 208.3C507.3 234.7 528 274.9 528 320C528 365.1 507.3 405.3 474.8 431.8C464.5 440.2 463 455.3 471.3 465.6C479.6 475.9 494.8 477.4 505.1 469.1C548.3 433.9 576 380.2 576 320.1C576 260 548.3 206.3 505.1 171.1zM444.6 245.5C434.3 237.1 419.2 238.7 410.8 249C402.4 259.3 404 274.4 414.3 282.8C425.1 291.6 432 305 432 320C432 335 425.1 348.4 414.3 357.3C404 365.7 402.5 380.8 410.8 391.1C419.1 401.4 434.3 402.9 444.6 394.6C466.1 376.9 480 350.1 480 320C480 289.9 466.1 263.1 444.5 245.5z" />
                                </svg></span>
                            Text To Speech
                        </li>

                        <!-- Text To Speech -->
                        <div class="sub_links_of_allawn" id="text_to_speech_sub_box" data-set="text_to_speech-sub">
                            <ul>
                                <li class="sole78m romove_device_links" id="contect__50" onclick="link_sole_action(this)" data-set="contect-50">Dashboard</li>
                                <li class="sole78m romove_device_links" id="contect__51" onclick="link_sole_action(this)" data-set="contect-51">Voice Files</li>
                                <li class="sole78m romove_device_links" id="contect__52" onclick="link_sole_action(this)" data-set="contect-52">Manage TTS Template</li>
                                <li class="sole78m romove_device_links" id="contect__53" onclick="link_sole_action(this)" data-set="contect-53">Create Campaign</li>
                                <li class="sole78m romove_device_links" id="contect__54" onclick="link_sole_action(this)" data-set="contect-54">Report</li>
                                <li class="sole78m romove_device_links" id="contect__55" onclick="link_sole_action(this)" data-set="contect-55">Manage Remarks</li>
                            </ul>
                        </div>

                        <li class="subbox_link romove_device_links" id="ai_agent_btn_124" data-set="ai_agent" onclick="show_this_box(this); show_sub_link(this);">
                            <span class="svg_icon_box"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" class="sub_set_iocn_svg" data-subset="icon_11">
                                    <path d="M352 64C352 46.3 337.7 32 320 32C302.3 32 288 46.3 288 64L288 128L192 128C139 128 96 171 96 224L96 448C96 501 139 544 192 544L448 544C501 544 544 501 544 448L544 224C544 171 501 128 448 128L352 128L352 64zM160 432C160 418.7 170.7 408 184 408L216 408C229.3 408 240 418.7 240 432C240 445.3 229.3 456 216 456L184 456C170.7 456 160 445.3 160 432zM280 432C280 418.7 290.7 408 304 408L336 408C349.3 408 360 418.7 360 432C360 445.3 349.3 456 336 456L304 456C290.7 456 280 445.3 280 432zM400 432C400 418.7 410.7 408 424 408L456 408C469.3 408 480 418.7 480 432C480 445.3 469.3 456 456 456L424 456C410.7 456 400 445.3 400 432zM224 240C250.5 240 272 261.5 272 288C272 314.5 250.5 336 224 336C197.5 336 176 314.5 176 288C176 261.5 197.5 240 224 240zM368 288C368 261.5 389.5 240 416 240C442.5 240 464 261.5 464 288C464 314.5 442.5 336 416 336C389.5 336 368 314.5 368 288zM64 288C64 270.3 49.7 256 32 256C14.3 256 0 270.3 0 288L0 384C0 401.7 14.3 416 32 416C49.7 416 64 401.7 64 384L64 288zM608 256C590.3 256 576 270.3 576 288L576 384C576 401.7 590.3 416 608 416C625.7 416 640 401.7 640 384L640 288C640 270.3 625.7 256 608 256z" />
                                </svg></span>
                            Ai Agent
                        </li>


                        <!-- AI Agent -->
                        <div class="sub_links_of_allawn" id="ai_agent_sub_box" data-set="ai_agent-sub">
                            <ul>
                                <li class="sole78m romove_device_links" id="contect__56" onclick="link_sole_action(this)" data-set="contect-56">Dashboard</li>
                                <li class="sole78m romove_device_links" id="contect__57" onclick="link_sole_action(this)" data-set="contect-57">AI Agent Campaign</li>
                                <li class="sole78m romove_device_links" id="contect__58" onclick="link_sole_action(this)" data-set="contect-58">Report</li>
                            </ul>
                        </div>

                    </ul>
                </div>

                <div class="line_rola"></div>

                <h6>Security</h6>
                <ul class="top_nave_appli">


                    <li class="subbox_link" id="login_btn_124" data-set="login" onclick="show_this_box(this); close_all_sub_links_ha()">
                        <span class="svg_icon_box">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" class="sub_set_iocn_svg" data-subset="icon_2">
                                <path d="M256 160L256 224L384 224L384 160C384 124.7 355.3 96 320 96C284.7 96 256 124.7 256 160zM192 224L192 160C192 89.3 249.3 32 320 32C390.7 32 448 89.3 448 160L448 224C483.3 224 512 252.7 512 288L512 512C512 547.3 483.3 576 448 576L192 576C156.7 576 128 547.3 128 512L128 288C128 252.7 156.7 224 192 224z" />
                            </svg>
                        </span>
                        Login
                    </li>

                </ul>

            </section>

            <span class="hammer_btn" id="haamer_id_adase">
                <div id="haw_linw969">
                    &#9776;
                </div>
                <div id="close_linw969">
                    &#10005;
                </div>
            </span>

            <!-- right side  -->
            <section class="right_section_1 widh_wmob">


                <style>
                    .globle_info {
                        display: flex;
                        width: 80%;
                        margin: auto;
                    }

                    .logos_sd {
                        display: grid;
                        grid-template-columns: repeat(2, 1fr);
                    }
                </style>

                <!-- section 1 overview -->

                <section
                    id="overview_contect_box"
                    class="section_sub_with all_sejmca8974 show_active">

                    <div class="globle_info">

                        <form
                            class="global-info-form"
                            method="POST"
                            enctype="multipart/form-data">

                            <div class="logos_sd">

                                <div class="global-image-field">

                                    <label>
                                        Change Facion Icon
                                    </label>


                                    <div class="global-old-image">

                                        <img
                                            id="facion_icon_preview"
                                            src="assets/logos/<?php echo htmlspecialchars($global_info['facion_icon'] ?? ''); ?>"
                                            alt="Current Facion Icon">

                                    </div>


                                    <input
                                        type="file"
                                        name="facion_icon"
                                        id="facion_icon_input"
                                        accept=".jpg,.jpeg,.png,.webp,.ico">


                                    <small>
                                        Leave empty to keep the old icon.
                                    </small>

                                </div>

                                <div class="global-image-field">

                                    <label>
                                        Change Logo
                                    </label>


                                    <div class="global-old-image global-logo-preview">

                                        <img
                                            id="global_logo_preview"
                                            src="assets/logos/<?php echo htmlspecialchars($global_info['logo'] ?? ''); ?>"
                                            alt="Current Logo">

                                    </div>


                                    <input
                                        type="file"
                                        name="logo"
                                        id="global_logo_input"
                                        accept=".jpg,.jpeg,.png,.webp,.svg">


                                    <small>
                                        Leave empty to keep the old logo.
                                    </small>

                                </div>

                            </div>

                            <div class="global-contact-fields">

                                <div class="global-input-field">

                                    <label for="global_call_phone">
                                        Change Phone Number Fixed Button
                                    </label>


                                    <input
                                        type="text"
                                        id="global_call_phone"
                                        name="globle_call_phone"
                                        value="<?php echo htmlspecialchars($global_info['globle_call_phone'] ?? ''); ?>"
                                        placeholder="Enter phone number">

                                </div>

                                <div class="global-input-field">

                                    <label for="global_whatsapp">
                                        Change WhatsApp Number Fixed Button
                                    </label>


                                    <input
                                        type="text"
                                        id="global_whatsapp"
                                        name="globle_whatsapp"
                                        value="<?php echo htmlspecialchars($global_info['globle_whatsapp'] ?? ''); ?>"
                                        placeholder="Enter WhatsApp number">

                                </div>

                            </div>


                            <div class="global-footer-fields">

                                <div class="global-input-field global-full-field">

                                    <label for="footer_address">
                                        Footer Address
                                    </label>

                                    <textarea
                                        id="footer_address"
                                        name="footer_address"
                                        placeholder="Enter footer address"><?php echo htmlspecialchars($global_info['footer_address'] ?? ''); ?></textarea>

                                </div>

                                <div class="global-input-field">

                                    <label for="footer_email_1">
                                        Footer Email 1
                                    </label>

                                    <input
                                        type="email"
                                        id="footer_email_1"
                                        name="footer_email_1"
                                        value="<?php echo htmlspecialchars($global_info['footer_email_1'] ?? ''); ?>"
                                        placeholder="Enter email">

                                </div>

                                <div class="global-input-field">

                                    <label for="footer_email_2">
                                        Footer Email 2
                                    </label>

                                    <input
                                        type="email"
                                        id="footer_email_2"
                                        name="footer_email_2"
                                        value="<?php echo htmlspecialchars($global_info['footer_email_2'] ?? ''); ?>"
                                        placeholder="Enter email">

                                </div>

                                <div class="global-input-field">

                                    <label for="footer_phone_1">
                                        Footer Phone 1
                                    </label>

                                    <input
                                        type="text"
                                        id="footer_phone_1"
                                        name="footer_phone_1"
                                        value="<?php echo htmlspecialchars($global_info['footer_phone_1'] ?? ''); ?>"
                                        placeholder="Enter phone number">

                                </div>

                                <div class="global-input-field">

                                    <label for="footer_phone_2">
                                        Footer Phone 2
                                    </label>

                                    <input
                                        type="text"
                                        id="footer_phone_2"
                                        name="footer_phone_2"
                                        value="<?php echo htmlspecialchars($global_info['footer_phone_2'] ?? ''); ?>"
                                        placeholder="Enter phone number">

                                </div>

                                <div class="global-input-field">

                                    <label for="globle_instagram">
                                        Instagram Link
                                    </label>

                                    <input
                                        type="text"
                                        id="globle_instagram"
                                        name="globle_instagram"
                                        value="<?php echo htmlspecialchars($global_info['globle_instagram'] ?? ''); ?>"
                                        placeholder="Enter Instagram link">

                                </div>


                                <div class="global-input-field">

                                    <label for="globle_yt">
                                        YouTube Link
                                    </label>

                                    <input
                                        type="text"
                                        id="globle_yt"
                                        name="globle_yt"
                                        value="<?php echo htmlspecialchars($global_info['globle_yt'] ?? ''); ?>"
                                        placeholder="Enter YouTube link">

                                </div>


                                <div class="global-input-field">

                                    <label for="globle_linkedin">
                                        LinkedIn Link
                                    </label>

                                    <input
                                        type="text"
                                        id="globle_linkedin"
                                        name="globle_linkedin"
                                        value="<?php echo htmlspecialchars($global_info['globle_linkedin'] ?? ''); ?>"
                                        placeholder="Enter LinkedIn link">

                                </div>


                                <div class="global-input-field global-full-field">

                                    <label for="map_link">
                                        Google Map Link
                                    </label>

                                    <input
                                        type="text"
                                        id="map_link"
                                        name="map_link"
                                        value="<?php echo htmlspecialchars($global_info['map_link'] ?? ''); ?>"
                                        placeholder="Enter Google Map link">

                                </div>

                            </div>

                            <div class="global-form-submit">

                                <button
                                    type="submit"
                                    name="update_global_info"
                                    value="1">
                                    Update Global Information
                                </button>

                            </div>

                        </form>

                    </div>



                    <!--  -->

                </section>

                <script>
                    const facionIconInput =
                        document.getElementById('facion_icon_input');

                    const facionIconPreview =
                        document.getElementById('facion_icon_preview');


                    if (facionIconInput && facionIconPreview) {

                        facionIconInput.addEventListener('change', function() {

                            const file = this.files[0];

                            if (!file) {
                                return;
                            }

                            const imageUrl =
                                URL.createObjectURL(file);

                            facionIconPreview.src = imageUrl;

                        });

                    }


                    /* =========================================================
                       LOGO PREVIEW
                    ========================================================= */

                    const globalLogoInput =
                        document.getElementById('global_logo_input');

                    const globalLogoPreview =
                        document.getElementById('global_logo_preview');


                    if (globalLogoInput && globalLogoPreview) {

                        globalLogoInput.addEventListener('change', function() {

                            const file = this.files[0];

                            if (!file) {
                                return;
                            }

                            const imageUrl =
                                URL.createObjectURL(file);

                            globalLogoPreview.src = imageUrl;

                        });

                    }
                </script>

                <!-- section 2 Login -->
                <section id="login_contect_box" class="section_sub_with all_sejmca8974 ">

                    <div class="bhaum-settings-card-2026">

                        <!-- =====================
                                CHANGE EMAIL
                            ====================== -->

                        <form method="post" class="bhaum-settings-form-2026">

                            <h3 class="bhaum-settings-title-2026">
                                Change Email
                            </h3>

                            <div class="bhaum-field-group-2026">

                                <input
                                    type="email"
                                    name="change_email"
                                    value="<?php echo htmlspecialchars($get_data['user_email']); ?>"
                                    placeholder="Enter New Email"
                                    required>

                            </div>

                            <div class="bhaum-field-group-2026">

                                <div class="bhaum-password-wrap-2026">

                                    <input
                                        type="password"
                                        name="email_current_password"
                                        value="<?php echo htmlspecialchars($get_data['user_password']); ?>"
                                        placeholder="Current Password"
                                        class="bhaum-email-pass-field-2026"
                                        required>

                                    <button
                                        type="button"
                                        class="bhaum-password-toggle-2026 bhaum-email-pass-toggle-2026">
                                        Show
                                    </button>

                                </div>

                            </div>

                            <button
                                type="submit"
                                name="change_email_btn"
                                class="bhaum-save-btn-2026">
                                Change Email
                            </button>

                        </form>


                        <!-- =====================
                                CHANGE PASSWORD
                            ====================== -->

                        <form method="post" class="bhaum-settings-form-2026">

                            <h3 class="bhaum-settings-title-2026">
                                Change Password
                            </h3>

                            <div class="bhaum-field-group-2026">

                                <div class="bhaum-password-wrap-2026">

                                    <input
                                        type="password"
                                        name="current_password"
                                        placeholder="Current Password"
                                        class="bhaum-current-pass-field-2026"
                                        required>

                                    <button
                                        type="button"
                                        class="bhaum-password-toggle-2026 bhaum-current-pass-toggle-2026">
                                        Show
                                    </button>

                                </div>

                            </div>

                            <div class="bhaum-field-group-2026">

                                <div class="bhaum-password-wrap-2026">

                                    <input
                                        type="password"
                                        name="new_password"
                                        placeholder="New Password"
                                        class="bhaum-new-pass-field-2026"
                                        required>

                                    <button
                                        type="button"
                                        class="bhaum-password-toggle-2026 bhaum-new-pass-toggle-2026">
                                        Show
                                    </button>

                                </div>

                            </div>

                            <div class="bhaum-field-group-2026">

                                <div class="bhaum-password-wrap-2026">

                                    <input
                                        type="password"
                                        name="confirm_password"
                                        placeholder="Confirm New Password"
                                        class="bhaum-confirm-pass-field-2026"
                                        required>

                                    <button
                                        type="button"
                                        class="bhaum-password-toggle-2026 bhaum-confirm-pass-toggle-2026">
                                        Show
                                    </button>

                                </div>

                            </div>

                            <button
                                type="submit"
                                name="change_password_btn"
                                class="bhaum-save-btn-2026">
                                Change Password
                            </button>

                        </form>

                    </div>

                    <button class="logout_btn noselect" onclick="window.location.href='logout.php'">
                        <span class="text">Logout</span>
                        <span class="icon">
                            <!-- <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                <path d="M24 20.188l-8.315-8.209 8.2-8.282-3.697-3.697-8.212 8.318-8.31-8.203-3.666 3.666 8.321 8.24-8.206 8.313 3.666 3.666 8.237-8.318 8.285 8.203z"></path>
                            </svg> -->
                            <svg viewBox="0 0 512 512" width="24" height="24">
                                <path d="M377.9 105.9L500.7 228.7c7.2 7.2 11.3 17.1 11.3 27.3s-4.1 20.1-11.3 27.3L377.9 406.1c-6.4 6.4-15 9.9-24 9.9c-18.7 0-33.9-15.2-33.9-33.9l0-62.1-128 0c-17.7 0-32-14.3-32-32l0-64c0-17.7 14.3-32 32-32l128 0 0-62.1c0-18.7 15.2-33.9 33.9-33.9c9 0 17.6 3.6 24 9.9zM160 96L96 96c-17.7 0-32 14.3-32 32l0 256c0 17.7 14.3 32 32 32l64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32l-64 0c-53 0-96-43-96-96L0 128C0 75 43 32 96 32l64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32z"></path>
                            </svg>
                        </span>
                    </button>

                    <script>
                        document.addEventListener('DOMContentLoaded', function() {

                            function bhaumPasswordToggle2026(
                                inputSelector,
                                buttonSelector
                            ) {

                                const input = document.querySelector(inputSelector);
                                const button = document.querySelector(buttonSelector);

                                if (!input || !button) return;

                                button.addEventListener('click', function() {

                                    if (input.type === 'password') {

                                        input.type = 'text';
                                        button.innerText = 'Hide';

                                    } else {

                                        input.type = 'password';
                                        button.innerText = 'Show';

                                    }

                                });

                            }

                            bhaumPasswordToggle2026(
                                '.bhaum-email-pass-field-2026',
                                '.bhaum-email-pass-toggle-2026'
                            );

                            bhaumPasswordToggle2026(
                                '.bhaum-current-pass-field-2026',
                                '.bhaum-current-pass-toggle-2026'
                            );

                            bhaumPasswordToggle2026(
                                '.bhaum-new-pass-field-2026',
                                '.bhaum-new-pass-toggle-2026'
                            );

                            bhaumPasswordToggle2026(
                                '.bhaum-confirm-pass-field-2026',
                                '.bhaum-confirm-pass-toggle-2026'
                            );

                        });
                    </script>



                </section>

                <!-- section 3 Dashbord -->
                <section id="dashbord_contect_box" class="section_sub_with all_sejmca8974 ">
                    3
                </section>

                <!-- section 4 home -->
                <?php include_once 'admin_access/functions/products.php' ?>
                <?php

                $random_products = get_random_products($mydb, 6);

                ?>
                <section id="sms_contect_box" class="section_sub_with all_sejmca8974 ">

                    <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap:18px; padding:10px;">

                        <?php if (!empty($random_products)) { ?>

                            <?php foreach ($random_products as $rp) { ?>

                                <div style="border:1px solid #e5e7eb; border-radius:10px; overflow:hidden; background:#fff; box-shadow:0 1px 3px rgba(0,0,0,0.06);">

                                    <?php if (!empty($rp['product_image'])) { ?>
                                        <img src="<?php echo htmlspecialchars($rp['product_image']); ?>"
                                            alt="<?php echo htmlspecialchars($rp['product_name']); ?>"
                                            style="width:100%; height:160px; object-fit:cover;">
                                    <?php } else { ?>
                                        <div style="width:100%; height:160px; background:#f3f4f6; display:flex; align-items:center; justify-content:center; color:#9ca3af; font-size:13px;">
                                            No Image
                                        </div>
                                    <?php } ?>

                                    <div style="padding:12px;">

                                        <h4 style="margin:0 0 6px; font-size:15px; color:#111827;">
                                            <?php echo htmlspecialchars($rp['product_name']); ?>
                                        </h4>

                                        <p style="margin:0 0 4px; font-size:13px; color:#6b7280;">
                                            <?php echo htmlspecialchars($rp['root_name'] ?? '-'); ?>
                                            <?php if (!empty($rp['brand_name'])) { ?>
                                                &middot; <?php echo htmlspecialchars($rp['brand_name']); ?>
                                            <?php } ?>
                                        </p>

                                        <p style="margin:0; font-size:14px; font-weight:600; color:#111827;">
                                            <?php if (!empty($rp['sale_price']) && $rp['sale_price'] != $rp['original_price']) { ?>
                                                <span style="text-decoration:line-through; color:#9ca3af; font-weight:400; margin-right:6px;">
                                                    ₹<?php echo htmlspecialchars($rp['original_price']); ?>
                                                </span>
                                                ₹<?php echo htmlspecialchars($rp['sale_price']); ?>
                                            <?php } else { ?>
                                                ₹<?php echo htmlspecialchars($rp['original_price']); ?>
                                            <?php } ?>
                                        </p>

                                    </div>

                                </div>

                            <?php } ?>

                        <?php } else { ?>

                            <p style="text-align:center; color:#9ca3af; grid-column: 1 / -1;">No products found</p>

                        <?php } ?>

                    </div>

                    <div style="display:flex; justify-content:center; margin-top:24px;">
                        <button style="min-height: 50px;padding: 0 30px;border: none;border-radius: 8px;background: #055096;color: #fff;font-size: 15px;font-weight: 600;cursor: pointer" type="button" class="ihdw9641" onclick="event.preventDefault(); document.getElementById('contect__3').click(); document.getElementById('sms_btn_124').classList.add('active'); document.getElementById('sms_sub_box').classList.add('sub_links_of_show_box'); ">
                            Click here to edit product
                        </button>
                        <!-- <button
                            type="button"
                            class="ihdw9641"
                            onclick="goToEditProductsSection()">
                            Click here to edit product
                        </button> -->
                    </div>

                    <script>
                        // ===========================================================
                        // Products wale section (contect_3_contect_box) pe le jaata hai.
                        // Assumption: tabs "show_active" class se switch hote hain
                        // (jaisa sidebar ke sections me dikha tha). Agar tumhara
                        // actual tab-switch mechanism alag hai, isko replace kar dena
                        // usi function se jo sidebar "Products" link click par chalta hai.
                        // ===========================================================
                        function goToEditProductsSection() {

                            var allSections = document.querySelectorAll('.section_sub_with');

                            allSections.forEach(function(sec) {
                                sec.classList.remove('show_active');
                            });

                            var targetSection = document.getElementById('contect_3_contect_box');

                            if (targetSection) {
                                targetSection.classList.add('show_active');
                                targetSection.scrollIntoView({
                                    behavior: 'smooth'
                                });
                            }
                        }
                    </script>

                </section>
                <!-- section 5 RCS -->
                <section id="rcs_contect_box" class="section_sub_with all_sejmca8974 ">
                    5
                </section>

                <!-- section 6 Whatsapp -->
                <section id="whatsapp_contect_box" class="section_sub_with all_sejmca8974 ">
                    6
                </section>

                <!-- section 7 Voice -->
                <section id="voice_contect_box" class="section_sub_with all_sejmca8974 ">
                    7
                </section>

                <!-- section 8 IVR -->
                <section id="ivr_contect_box" class="section_sub_with all_sejmca8974 ">
                    8
                </section>

                <!-- section 9 Click To Call -->
                <section id="click_to_call_contect_box" class="section_sub_with all_sejmca8974 ">
                    9
                </section>

                <!-- section 10 Text To Speech -->
                <section id="text_to_speech_contect_box" class="section_sub_with all_sejmca8974 ">
                    10
                </section>

                <!-- section 11 Ai Agent -->
                <section id="ai_agent_contect_box" class="section_sub_with all_sejmca8974 ">
                    11
                </section>

                <!-- section 12 blog -->
                <section id="journeys_contect_box" class="section_sub_with all_sejmca8974 ">



                </section>

                <!-- sublink box 1 -->
                <section id="contect_1_contect_box" class="section_sub_with all_sejmca8974 auto_margin_notwa">
                    <!-- Custom CSS matching your Sidebar Theme -->

                    <?php if ($update_success_msg): ?>
                        <div class="kd-root-alert kd-root-alert-success">
                            <?php echo htmlspecialchars($update_success_msg); ?>
                        </div>
                    <?php endif; ?>

                    <?php if ($update_error_msg): ?>
                        <div class="kd-root-alert kd-root-alert-error">
                            <?php echo htmlspecialchars($update_error_msg); ?>
                        </div>
                    <?php endif; ?>

                    <!-- Show table Data -->
                    <div class="custom-table-container <?php echo empty($category_single_data) ? '' : 'hidden_class'; ?>" id="show_category_table">

                        <span class="sdao97452">

                            <h2 class="table-title" style="margin:auto 5px;">Category Management</h2>
                            <button class="ihdw9641" id="add_cate_btn" onclick="show_add_categoryform()">
                                Add new
                            </button>
                        </span>
                        <!-- Responsive Table Wrapper -->
                        <div class="custom-table-wrapper">

                            <?php

                            $category_info = get_category_info($mydb);

                            ?>
                            <table class="custom-table">

                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Item Name</th>
                                        <th>Category</th>
                                        <th>Status</th>
                                        <th>Add Date</th>
                                        <th>Update Date</th>
                                        <th style="text-align: center;">Actions</th>
                                    </tr>
                                </thead>

                                <tbody id="dataTableBody">

                                    <?php if (!empty($category_info)) { ?>

                                        <?php foreach ($category_info as $category) { ?>

                                            <tr>

                                                <td style="font-weight: 600; color: #111827;">
                                                    #<?php echo $category['root_id']; ?>
                                                </td>

                                                <td>
                                                    <?php echo htmlspecialchars($category['root_name']); ?>
                                                </td>

                                                <td>
                                                    <?php echo htmlspecialchars($category['root_slug']); ?>
                                                </td>

                                                <td>
                                                    <?php echo htmlspecialchars($category['root_status']); ?>
                                                </td>
                                                <td>
                                                    <?php //echo htmlspecialchars($brand['created_at']); 
                                                    ?>
                                                    <?php echo timeAgo((int)$category['created_at']); ?>
                                                </td>
                                                <td>
                                                    <?php if (empty($category['updated_at'])): ?>
                                                        <span style="color: green; font-weight: 100;">
                                                            Not Update Yet
                                                        </span>
                                                    <?php else: ?>
                                                        <?php echo timeAgo((int)$category['updated_at']); ?>
                                                    <?php endif; ?>
                                                </td>

                                                <td style="text-align: center;">

                                                    <button
                                                        type="button"
                                                        class="btn-update"
                                                        onclick="updateItem(<?php echo (int)$category['root_id']; ?>)">
                                                        Update
                                                    </button>

                                                    <button
                                                        type="button"
                                                        class="btn-delete"
                                                        onclick="doubleCheckDelete(<?php echo (int)$category['root_id']; ?>)">
                                                        Delete
                                                    </button>

                                                </td>

                                            </tr>

                                        <?php } ?>

                                    <?php } else { ?>

                                        <tr>
                                            <td colspan="5" style="text-align: center;">
                                                No categories found
                                            </td>
                                        </tr>

                                    <?php } ?>

                                </tbody>

                            </table>
                        </div>
                    </div>

                    <!-- add category From -->
                    <div class="kd-root-add-wrapper hidden_class" id="add_category_form">

                        <div class="kd-root-add-header">
                            <div>
                                <h4>Add Category Form</h4>
                            </div>
                        </div>

                        <form action="" method="POST" class="kd-root-add-form">

                            <div class="kd-root-add-grid">

                                <!-- Root Name -->
                                <div class="kd-root-field">
                                    <label for="kd-root-name">
                                        Root Name <span>*</span>
                                    </label>

                                    <input
                                        type="text"
                                        id="kd-root-name"
                                        name="root_name"
                                        placeholder="Enter root category name"
                                        required>

                                    <div class="kd-root-error" id="kd-root-name-error"></div>
                                </div>

                                <!-- Root Slug -->
                                <div class="kd-root-field">
                                    <label for="kd-root-slug">
                                        Root Slug <span>*</span>
                                    </label>

                                    <input
                                        type="text"
                                        id="kd-root-slug"
                                        name="root_slug"
                                        placeholder="example-category"
                                        pattern="[a-z0-9-]+"
                                        title="Only lowercase letters, numbers and hyphens are allowed"
                                        required
                                        data-sql-check
                                        readonly>
                                </div>

                                <!-- Root Description -->
                                <div class="kd-root-field kd-root-full">
                                    <label for="kd-root-description">
                                        Root Description
                                    </label>

                                    <textarea
                                        id="kd-root-description"
                                        name="root_description"
                                        placeholder="Enter root category description"
                                        rows="5"
                                        data-sql-check></textarea>
                                </div>

                                <!-- Meta Title -->
                                <div class="kd-root-field kd-root-full">
                                    <label for="kd-root-meta-title">
                                        Meta Title
                                    </label>

                                    <input
                                        type="text"
                                        id="kd-root-meta-title"
                                        name="meta_title"
                                        placeholder="Enter SEO meta title"
                                        maxlength="60"
                                        data-sql-check
                                        readonly>
                                </div>
                                <!-- Meta Description -->

                                <div class="kd-root-field kd-root-full">
                                    <label for="kd-root-meta-description">
                                        Meta Description
                                    </label>

                                    <textarea
                                        id="kd-root-meta-description"
                                        name="meta_description"
                                        placeholder="Enter SEO meta description"
                                        rows="4"
                                        data-sql-check readonly></textarea>
                                </div>

                                <!-- Meta Keywords -->
                                <div class="kd-root-field kd-root-full">
                                    <label for="kd-root-meta-keywords">
                                        Meta Keywords
                                    </label>

                                    <textarea
                                        id="kd-root-meta-keywords"
                                        name="meta_keywords"
                                        placeholder="keyword 1, keyword 2, keyword 3"
                                        rows="3" data-sql-check></textarea>
                                </div>

                                <!-- Status -->
                                <div class="kd-root-field">
                                    <label for="kd-root-status">
                                        Status <span>*</span>
                                    </label>

                                    <select
                                        id="kd-root-status"
                                        name="root_status"
                                        required>
                                        <option value="Active">Active</option>
                                        <option value="Inactive">Inactive</option>
                                    </select>
                                </div>

                            </div>

                            <div class="kd-root-form-actions">

                                <button onclick="show_add_categoryform()"
                                    type="reset"
                                    class="kd-root-reset-btn">
                                    Cancel
                                </button>

                                <button
                                    type="reset"
                                    class="kd-root-reset-btn">
                                    Reset
                                </button>

                                <button
                                    type="submit"
                                    name="add_root_category"
                                    class="kd-root-submit-btn">
                                    <i class="fa-solid fa-plus"></i>
                                    Add Root Category
                                </button>

                            </div>

                        </form>

                    </div>

                    <!-- Update category form (DIRECT SQL, no function.php call) -->
                    <div class="kd-root-add-wrapper hidden_class" id="kd-root-update-box">

                        <div class="kd-root-add-header">

                            <div>

                                <h2 class="kd-root-add-title">
                                    Update Root Category
                                </h2>

                                <p class="kd-root-add-subtitle">
                                    Update root category with SEO details
                                </p>

                            </div>

                        </div>


                        <form
                            action=""
                            method="POST"
                            class="kd-root-add-form">

                            <input
                                type="hidden"
                                id="kd-update-root-id"
                                name="root_id"
                                value="<?php echo (int)($category_single_data['root_id'] ?? 0); ?>">


                            <div class="kd-root-add-grid">


                                <!-- Root Name -->

                                <div class="kd-root-field">

                                    <label for="kd-update-root-name">
                                        Root Name <span>*</span>
                                    </label>

                                    <input
                                        type="text"
                                        id="kd-update-root-name"
                                        name="root_name"
                                        value="<?php echo htmlspecialchars($category_single_data['root_name'] ?? ''); ?>"
                                        placeholder="Enter root category name"
                                        required>

                                </div>


                                <!-- Root Slug -->

                                <div class="kd-root-field">

                                    <label for="kd-update-root-slug">
                                        Root Slug <span>*</span>
                                    </label>

                                    <input
                                        type="text"
                                        id="kd-update-root-slug"
                                        name="root_slug"
                                        value="<?php echo htmlspecialchars($category_single_data['root_slug'] ?? ''); ?>"
                                        placeholder="example-category"
                                        required>

                                </div>


                                <!-- Root Description -->

                                <div class="kd-root-field kd-root-full">

                                    <label for="kd-update-root-description">
                                        Root Description
                                    </label>

                                    <textarea
                                        id="kd-update-root-description"
                                        name="root_description"
                                        placeholder="Enter root category description"
                                        rows="5"><?php echo htmlspecialchars($category_single_data['root_description'] ?? ''); ?></textarea>

                                </div>


                                <!-- Meta Title -->

                                <div class="kd-root-field kd-root-full">

                                    <label for="kd-update-root-meta-title">
                                        Meta Title
                                    </label>

                                    <input
                                        type="text"
                                        id="kd-update-root-meta-title"
                                        name="meta_title"
                                        value="<?php echo htmlspecialchars($category_single_data['meta_title'] ?? ''); ?>"
                                        placeholder="Enter SEO meta title">

                                </div>


                                <!-- Meta Description -->

                                <div class="kd-root-field kd-root-full">

                                    <label for="kd-update-root-meta-description">
                                        Meta Description
                                    </label>

                                    <textarea
                                        id="kd-update-root-meta-description"
                                        name="meta_description"
                                        placeholder="Enter SEO meta description"
                                        rows="4"><?php echo htmlspecialchars($category_single_data['meta_description'] ?? ''); ?></textarea>

                                </div>


                                <!-- Meta Keywords -->

                                <div class="kd-root-field kd-root-full">

                                    <label for="kd-update-root-meta-keywords">
                                        Meta Keywords
                                    </label>

                                    <textarea
                                        id="kd-update-root-meta-keywords"
                                        name="meta_keywords"
                                        placeholder="keyword 1, keyword 2, keyword 3"
                                        rows="3"><?php echo htmlspecialchars($category_single_data['meta_keywords'] ?? ''); ?></textarea>

                                </div>


                                <!-- Status -->

                                <div class="kd-root-field">

                                    <label for="kd-update-root-status">
                                        Status <span>*</span>
                                    </label>

                                    <select
                                        id="kd-update-root-status"
                                        name="root_status"
                                        required>

                                        <option value="Active" <?php echo (($category_single_data['root_status'] ?? '') === 'Active') ? 'selected' : ''; ?>>
                                            Active
                                        </option>

                                        <option value="Inactive" <?php echo (($category_single_data['root_status'] ?? '') === 'Inactive') ? 'selected' : ''; ?>>
                                            Inactive
                                        </option>

                                    </select>

                                </div>


                            </div>


                            <div class="kd-root-form-actions">

                                <!-- Cancel -->

                                <button
                                    type="button"
                                    class="kd-root-reset-btn"
                                    onclick="cancelUpdateCategory()">
                                    Cancel
                                </button>


                                <!-- Update -->

                                <button
                                    type="submit"
                                    name="update_root_category"
                                    class="kd-root-submit-btn">

                                    <i class="fa-solid fa-pen"></i>

                                    Update Root Category

                                </button>

                            </div>

                        </form>

                    </div>

                    <script>
                        var categoryDataList = <?php echo json_encode($category_info); ?>;

                        function updateItem(id) {

                            var cat = categoryDataList.find(function(item) {
                                return parseInt(item.root_id) === parseInt(id);
                            });

                            if (!cat) {
                                return;
                            }

                            document.getElementById('kd-update-root-id').value = cat.root_id || '';
                            document.getElementById('kd-update-root-name').value = cat.root_name || '';
                            document.getElementById('kd-update-root-slug').value = cat.root_slug || '';
                            document.getElementById('kd-update-root-description').value = cat.root_description || '';
                            document.getElementById('kd-update-root-meta-title').value = cat.meta_title || '';
                            document.getElementById('kd-update-root-meta-description').value = cat.meta_description || '';
                            document.getElementById('kd-update-root-meta-keywords').value = cat.meta_keywords || '';
                            document.getElementById('kd-update-root-status').value = cat.root_status || 'Active';

                            // Table hide, update form show - koi reload nahi
                            document.getElementById('show_category_table').classList.add('hidden_class');
                            document.getElementById('kd-root-update-box').classList.remove('hidden_class');
                        }

                        function cancelUpdateCategory() {
                            document.getElementById('kd-root-update-box').classList.add('hidden_class');
                            document.getElementById('show_category_table').classList.remove('hidden_class');
                        }
                    </script>

                </section>

                <!-- sublink box 2 -->

                <form action="" method="POST" id="delete_brand_form" style="display:none;">
                    <input type="hidden" name="delete_brand_id" id="delete_brand_id_input" value="">
                    <input type="hidden" name="delete_single_brand" value="1">
                </form>
                <section id="contect_2_contect_box" class="section_sub_with all_sejmca8974 auto_margin_notwa ">

                    <?php if ($brand_success_msg): ?>
                        <div class="kd-root-alert kd-root-alert-success" style="text-align:center;color:green;">
                            <?php echo htmlspecialchars($brand_success_msg); ?>
                        </div>
                    <?php endif; ?>

                    <?php if ($brand_error_msg): ?>
                        <div class="kd-root-alert kd-root-alert-error" style="text-align:center;color:Red;">
                            <?php echo htmlspecialchars($brand_error_msg); ?>
                        </div>
                    <?php endif; ?>

                    <div class="custom-table-container" id="show_brand_table">

                        <span class="sdao97452">

                            <h2 class="table-title" style="margin:auto 5px;">Brand Management</h2>
                            <button class="ihdw9641" id="add_brand_btn" onclick="show_add_brandform()">
                                Add new
                            </button>
                        </span>

                        <div class="custom-table-wrapper">

                            <?php

                            $brand_info = get_brand_info($mydb);

                            ?>
                            <table class="custom-table">

                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Logo</th>
                                        <th>Brand Name</th>
                                        <th>Root Category</th>
                                        <th>Status</th>
                                        <th>Add Date</th>
                                        <th>Update Date</th>
                                        <th style="text-align: center;">Actions</th>
                                    </tr>
                                </thead>

                                <tbody id="brandTableBody">

                                    <?php if (!empty($brand_info)) { ?>

                                        <?php foreach ($brand_info as $brand) { ?>

                                            <tr>

                                                <td style="font-weight: 600; color: #111827;">
                                                    #<?php echo $brand['brand_id']; ?>
                                                </td>

                                                <td>
                                                    <?php if (!empty($brand['brand_logo'])) { ?>
                                                        <img src="<?php echo htmlspecialchars($brand['brand_logo']); ?>"
                                                            alt="<?php echo htmlspecialchars($brand['brand_name']); ?>"
                                                            style="width:45px; height:45px; object-fit:cover; border-radius:6px; border:1px solid #ddd;">
                                                    <?php } else { ?>
                                                        -
                                                    <?php } ?>
                                                </td>

                                                <td>
                                                    <?php echo htmlspecialchars($brand['brand_name']); ?>
                                                </td>

                                                <td>
                                                    <?php echo htmlspecialchars($brand['root_name'] ?? '-'); ?>
                                                </td>


                                                <td>
                                                    <?php echo htmlspecialchars($brand['brand_status']); ?>
                                                </td>

                                                <td>
                                                    <?php //echo htmlspecialchars($brand['created_at']); 
                                                    ?>
                                                    <?php echo timeAgo((int)$brand['created_at']); ?>
                                                </td>
                                                <td>
                                                    <?php if (empty($brand['updated_at'])): ?>
                                                        <span style="color: green; font-weight: 100;">
                                                            Not Update Yet
                                                        </span>
                                                    <?php else: ?>
                                                        <?php echo timeAgo((int)$brand['updated_at']); ?>
                                                    <?php endif; ?>
                                                </td>
                                                <td style="text-align: center;">

                                                    <button
                                                        type="button"
                                                        class="btn-update"
                                                        onclick="updateBrandItem(<?php echo (int)$brand['brand_id']; ?>)">
                                                        Update
                                                    </button>

                                                    <button
                                                        type="button"
                                                        class="btn-delete"
                                                        onclick="doubleCheckDeleteBrand(<?php echo (int)$brand['brand_id']; ?>)">
                                                        Delete
                                                    </button>

                                                </td>

                                            </tr>

                                        <?php } ?>

                                    <?php } else { ?>

                                        <tr>
                                            <td colspan="7" style="text-align: center;">
                                                No brands found
                                            </td>
                                        </tr>

                                    <?php } ?>

                                </tbody>

                            </table>
                        </div>
                    </div>

                    <!-- Add Brand Form -->
                    <div class="kd-root-add-wrapper hidden_class" id="add_brand_form">

                        <div class="kd-root-add-header">
                            <div>
                                <h4>Add Brand Form</h4>
                            </div>
                        </div>

                        <form action="" method="POST" class="kd-root-add-form" enctype="multipart/form-data">

                            <div class="kd-root-add-grid">

                                <!-- Root Category -->
                                <div class="kd-root-field">
                                    <label for="kd-brand-root-id">
                                        Root Category <span>*</span>
                                    </label>

                                    <select id="kd-brand-root-id" name="root_id" required>
                                        <option value="">Select Category</option>
                                        <?php foreach ($root_category_options as $root_cat) { ?>
                                            <option value="<?php echo (int)$root_cat['root_id']; ?>">
                                                <?php echo htmlspecialchars($root_cat['root_name']); ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>

                                <!-- Brand Name -->
                                <div class="kd-root-field">
                                    <label for="kd-brand-name">
                                        Brand Name <span>*</span>
                                    </label>

                                    <input
                                        type="text"
                                        id="kd-brand-name"
                                        name="brand_name"
                                        placeholder="Enter brand name"
                                        oninput="autoFillFromBrandName(this.value)"
                                        required>
                                </div>

                                <!-- Brand Slug -->
                                <div class="kd-root-field">
                                    <label for="kd-brand-slug">
                                        Brand Slug <span>*</span>
                                    </label>

                                    <input
                                        type="text"
                                        id="kd-brand-slug"
                                        name="brand_slug"
                                        placeholder="example-brand"
                                        pattern="[a-z0-9-]+"
                                        title="Only lowercase letters, numbers and hyphens are allowed"
                                        oninput="markSlugAsManual()"
                                        required>
                                </div>

                                <!-- Brand Logo -->
                                <div class="kd-root-field">
                                    <label for="kd-brand-logo">
                                        Brand Logo
                                    </label>

                                    <input
                                        type="file"
                                        id="kd-brand-logo"
                                        name="brand_logo"
                                        accept="image/*"
                                        onchange="previewBrandLogo(this)">

                                    <div style="margin-top:8px;">
                                        <img
                                            id="kd-brand-logo-preview"
                                            src=""
                                            alt="Selected logo preview"
                                            class="hidden_class"
                                            style="max-width:120px; max-height:120px; border:1px solid #ddd; border-radius:6px; object-fit:cover;">
                                        <p id="kd-brand-logo-filename" style="font-size:12px; color:#666; margin-top:4px;"></p>
                                    </div>
                                </div>

                                <!-- Brand Description -->
                                <div class="kd-root-field kd-root-full">
                                    <label for="kd-brand-description">
                                        Brand Description
                                    </label>

                                    <textarea
                                        id="kd-brand-description"
                                        name="brand_description"
                                        placeholder="Enter brand description"
                                        rows="5"
                                        oninput="autoFillMetaDescription(this.value)"></textarea>
                                </div>

                                <!-- Meta Title -->
                                <div class="kd-root-field kd-root-full">
                                    <label for="kd-brand-meta-title">
                                        Meta Title
                                    </label>

                                    <input
                                        type="text"
                                        id="kd-brand-meta-title"
                                        name="meta_title"
                                        placeholder="Enter SEO meta title"
                                        maxlength="60"
                                        oninput="markMetaTitleAsManual()">
                                </div>

                                <!-- Meta Description -->
                                <div class="kd-root-field kd-root-full">
                                    <label for="kd-brand-meta-description">
                                        Meta Description
                                    </label>

                                    <textarea
                                        id="kd-brand-meta-description"
                                        name="meta_description"
                                        placeholder="Enter SEO meta description"
                                        rows="4"
                                        maxlength="160"
                                        oninput="markMetaDescAsManual()"></textarea>
                                </div>

                                <!-- Meta Keywords -->
                                <div class="kd-root-field kd-root-full">
                                    <label for="kd-brand-meta-keywords">
                                        Meta Keywords
                                    </label>

                                    <textarea
                                        id="kd-brand-meta-keywords"
                                        name="meta_keywords"
                                        placeholder="keyword 1, keyword 2, keyword 3"
                                        rows="3"></textarea>
                                </div>

                                <!-- Status -->
                                <div class="kd-root-field">
                                    <label for="kd-brand-status">
                                        Status <span>*</span>
                                    </label>

                                    <select id="kd-brand-status" name="brand_status" required>
                                        <option value="Active">Active</option>
                                        <option value="Inactive">Inactive</option>
                                    </select>
                                </div>

                            </div>

                            <div class="kd-root-form-actions">

                                <button onclick="show_add_brandform()" type="button" class="kd-root-reset-btn">
                                    Cancel
                                </button>

                                <button type="button" onclick="resetBrandForm()" class="kd-root-reset-btn">
                                    Reset
                                </button>

                                <button type="submit" name="add_brand" class="kd-root-submit-btn">
                                    <i class="fa-solid fa-plus"></i>
                                    Add Brand
                                </button>

                            </div>

                        </form>

                    </div>

                    <!-- Update Brand Form -->
                    <div class="kd-root-add-wrapper hidden_class" id="update_brand_form">

                        <div class="kd-root-add-header">
                            <div>
                                <h2 class="kd-root-add-title">Update Brand</h2>
                                <p class="kd-root-add-subtitle">Update brand with SEO details</p>
                            </div>
                        </div>

                        <form action="" method="POST" class="kd-root-add-form" enctype="multipart/form-data">

                            <input type="hidden" id="kd-update-brand-id" name="brand_id" value="">
                            <input type="hidden" id="kd-update-brand-existing-logo" name="existing_logo" value="">

                            <div class="kd-root-add-grid">

                                <!-- Root Category -->
                                <div class="kd-root-field">
                                    <label for="kd-update-brand-root-id">
                                        Root Category <span>*</span>
                                    </label>

                                    <select id="kd-update-brand-root-id" name="root_id" required>
                                        <option value="">Select Category</option>
                                        <?php foreach ($root_category_options as $root_cat) { ?>
                                            <option value="<?php echo (int)$root_cat['root_id']; ?>">
                                                <?php echo htmlspecialchars($root_cat['root_name']); ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>

                                <!-- Brand Name -->
                                <div class="kd-root-field">
                                    <label for="kd-update-brand-name">
                                        Brand Name <span>*</span>
                                    </label>

                                    <input
                                        type="text"
                                        id="kd-update-brand-name"
                                        name="brand_name"
                                        placeholder="Enter brand name"
                                        oninput="autoFillFromBrandNameUpdate(this.value)"
                                        required>
                                </div>

                                <!-- Brand Slug -->
                                <div class="kd-root-field">
                                    <label for="kd-update-brand-slug">
                                        Brand Slug <span>*</span>
                                    </label>

                                    <input
                                        type="text"
                                        id="kd-update-brand-slug"
                                        name="brand_slug"
                                        placeholder="example-brand"
                                        pattern="[a-z0-9-]+"
                                        oninput="markSlugAsManualUpdate()"
                                        required>
                                </div>

                                <!-- Brand Logo -->
                                <div class="kd-root-field">
                                    <label for="kd-update-brand-logo">
                                        Brand Logo
                                    </label>

                                    <input
                                        type="file"
                                        id="kd-update-brand-logo"
                                        name="brand_logo"
                                        accept="image/*"
                                        onchange="previewBrandLogoUpdate(this)">

                                    <div style="margin-top:8px;">
                                        <img
                                            id="kd-update-brand-logo-preview"
                                            src=""
                                            alt="Current logo"
                                            class="hidden_class"
                                            style="max-width:120px; max-height:120px; border:1px solid #ddd; border-radius:6px; object-fit:cover;">
                                        <p id="kd-update-brand-logo-filename" style="font-size:12px; color:#666; margin-top:4px;"></p>
                                    </div>
                                </div>

                                <!-- Brand Description -->
                                <div class="kd-root-field kd-root-full">
                                    <label for="kd-update-brand-description">
                                        Brand Description
                                    </label>

                                    <textarea
                                        id="kd-update-brand-description"
                                        name="brand_description"
                                        placeholder="Enter brand description"
                                        rows="5"
                                        oninput="autoFillMetaDescriptionUpdate(this.value)"></textarea>
                                </div>

                                <!-- Meta Title -->
                                <div class="kd-root-field kd-root-full">
                                    <label for="kd-update-brand-meta-title">
                                        Meta Title
                                    </label>

                                    <input
                                        type="text"
                                        id="kd-update-brand-meta-title"
                                        name="meta_title"
                                        placeholder="Enter SEO meta title"
                                        maxlength="60"
                                        oninput="markMetaTitleAsManualUpdate()">
                                </div>

                                <!-- Meta Description -->
                                <div class="kd-root-field kd-root-full">
                                    <label for="kd-update-brand-meta-description">
                                        Meta Description
                                    </label>

                                    <textarea
                                        id="kd-update-brand-meta-description"
                                        name="meta_description"
                                        placeholder="Enter SEO meta description"
                                        rows="4"
                                        maxlength="160"
                                        oninput="markMetaDescAsManualUpdate()"></textarea>
                                </div>

                                <!-- Meta Keywords -->
                                <div class="kd-root-field kd-root-full">
                                    <label for="kd-update-brand-meta-keywords">
                                        Meta Keywords
                                    </label>

                                    <textarea
                                        id="kd-update-brand-meta-keywords"
                                        name="meta_keywords"
                                        placeholder="keyword 1, keyword 2, keyword 3"
                                        rows="3"></textarea>
                                </div>

                                <!-- Status -->
                                <div class="kd-root-field">
                                    <label for="kd-update-brand-status">
                                        Status <span>*</span>
                                    </label>

                                    <select id="kd-update-brand-status" name="brand_status" required>
                                        <option value="Active">Active</option>
                                        <option value="Inactive">Inactive</option>
                                    </select>
                                </div>

                            </div>

                            <div class="kd-root-form-actions">

                                <button type="button" class="kd-root-reset-btn" onclick="cancelUpdateBrand()">
                                    Cancel
                                </button>

                                <button type="submit" name="update_brand" class="kd-root-submit-btn">
                                    <i class="fa-solid fa-pen"></i>
                                    Update Brand
                                </button>

                            </div>

                        </form>

                    </div>

                    <script>
                        function doubleCheckDeleteBrand(id) {
                            if (confirm("Brand #" + id + " delete karna hai? Ye action wapas nahi ho sakta.")) {
                                document.getElementById('delete_brand_id_input').value = id;
                                document.getElementById('delete_brand_form').submit();
                            }
                        }
                        var brandDataList = <?php echo json_encode($brand_info); ?>;

                        function show_add_brandform() {
                            document.getElementById('show_brand_table').classList.toggle('hidden_class');
                            document.getElementById('add_brand_form').classList.toggle('hidden_class');
                        }

                        // Select ki hui image ka turant preview dikhata hai (upload se pehle)
                        function previewBrandLogo(input) {

                            var previewImg = document.getElementById('kd-brand-logo-preview');
                            var fileNameTag = document.getElementById('kd-brand-logo-filename');

                            if (input.files && input.files[0]) {

                                var reader = new FileReader();

                                reader.onload = function(e) {
                                    previewImg.src = e.target.result;
                                    previewImg.classList.remove('hidden_class');
                                };

                                reader.readAsDataURL(input.files[0]);

                                fileNameTag.textContent = "Selected: " + input.files[0].name;

                            } else {
                                previewImg.src = '';
                                previewImg.classList.add('hidden_class');
                                fileNameTag.textContent = '';
                            }
                        }

                        // ---------------------------------------------------------
                        // AUTO-FILL: Brand Name -> Slug + Meta Title
                        // Description -> Meta Description
                        // Agar user ne khud slug/meta title/meta desc me type kiya
                        // ho to us field ko auto-fill overwrite nahi karega.
                        // ---------------------------------------------------------
                        var isSlugManual = false;
                        var isMetaTitleManual = false;
                        var isMetaDescManual = false;

                        function slugify(text) {
                            return text
                                .toString()
                                .toLowerCase()
                                .trim()
                                .normalize('NFD').replace(/[\u0300-\u036f]/g, '') // accents hatao
                                .replace(/[^a-z0-9\s-]/g, '') // invalid characters hatao
                                .replace(/[\s_-]+/g, '-') // spaces/underscore -> hyphen
                                .replace(/^-+|-+$/g, ''); // shuru/end ke hyphen hatao
                        }

                        function autoFillFromBrandName(value) {

                            if (!isSlugManual) {
                                document.getElementById('kd-brand-slug').value = slugify(value);
                            }

                            if (!isMetaTitleManual) {
                                document.getElementById('kd-brand-meta-title').value = value.substring(0, 60);
                            }
                        }

                        function autoFillMetaDescription(value) {
                            if (!isMetaDescManual) {
                                document.getElementById('kd-brand-meta-description').value = value.substring(0, 160);
                            }
                        }

                        function markSlugAsManual() {
                            isSlugManual = true;
                        }

                        function markMetaTitleAsManual() {
                            isMetaTitleManual = true;
                        }

                        function markMetaDescAsManual() {
                            isMetaDescManual = true;
                        }

                        // Reset button - form fields ke saath saath image preview
                        // aur auto-fill flags bhi clear karta hai
                        function resetBrandForm() {

                            document.querySelector('#add_brand_form form').reset();

                            // Image preview clear karo
                            var previewImg = document.getElementById('kd-brand-logo-preview');
                            var fileNameTag = document.getElementById('kd-brand-logo-filename');
                            previewImg.src = '';
                            previewImg.classList.add('hidden_class');
                            fileNameTag.textContent = '';

                            // Auto-fill flags wapas se on kar do
                            isSlugManual = false;
                            isMetaTitleManual = false;
                            isMetaDescManual = false;
                        }

                        // ===========================================================
                        // UPDATE BRAND - bina reload form kholta hai, already loaded
                        // brandDataList se fields fill karta hai
                        // ===========================================================
                        function updateBrandItem(id) {

                            var brand = brandDataList.find(function(item) {
                                return parseInt(item.brand_id) === parseInt(id);
                            });

                            if (!brand) {
                                return;
                            }

                            document.getElementById('kd-update-brand-id').value = brand.brand_id || '';
                            document.getElementById('kd-update-brand-existing-logo').value = brand.brand_logo || '';
                            document.getElementById('kd-update-brand-root-id').value = brand.root_id || '';
                            document.getElementById('kd-update-brand-name').value = brand.brand_name || '';
                            document.getElementById('kd-update-brand-slug').value = brand.brand_slug || '';
                            document.getElementById('kd-update-brand-description').value = brand.brand_description || '';
                            document.getElementById('kd-update-brand-meta-title').value = brand.meta_title || '';
                            document.getElementById('kd-update-brand-meta-description').value = brand.meta_description || '';
                            document.getElementById('kd-update-brand-meta-keywords').value = brand.meta_keywords || '';
                            document.getElementById('kd-update-brand-status').value = brand.brand_status || 'Active';

                            // File input hamesha khali shuru hota hai (browser security)
                            document.getElementById('kd-update-brand-logo').value = '';
                            document.getElementById('kd-update-brand-logo-filename').textContent = '';

                            // Current logo dikhao agar hai
                            var updatePreview = document.getElementById('kd-update-brand-logo-preview');
                            if (brand.brand_logo) {
                                updatePreview.src = brand.brand_logo;
                                updatePreview.classList.remove('hidden_class');
                            } else {
                                updatePreview.src = '';
                                updatePreview.classList.add('hidden_class');
                            }

                            // Auto-fill flags is form ke liye manual mode me daal do
                            isSlugManualUpdate = false;
                            isMetaTitleManualUpdate = false;
                            isMetaDescManualUpdate = false;

                            document.getElementById('show_brand_table').classList.add('hidden_class');
                            document.getElementById('update_brand_form').classList.remove('hidden_class');
                        }

                        function cancelUpdateBrand() {
                            document.getElementById('update_brand_form').classList.add('hidden_class');
                            document.getElementById('show_brand_table').classList.remove('hidden_class');
                        }

                        // Update form ki apni image preview (jab nayi file select ho)
                        function previewBrandLogoUpdate(input) {

                            var previewImg = document.getElementById('kd-update-brand-logo-preview');
                            var fileNameTag = document.getElementById('kd-update-brand-logo-filename');

                            if (input.files && input.files[0]) {

                                var reader = new FileReader();

                                reader.onload = function(e) {
                                    previewImg.src = e.target.result;
                                    previewImg.classList.remove('hidden_class');
                                };

                                reader.readAsDataURL(input.files[0]);

                                fileNameTag.textContent = "Selected: " + input.files[0].name;

                            } else {
                                fileNameTag.textContent = '';
                            }
                        }

                        // Update form ke apne auto-fill flags (add form se alag)
                        var isSlugManualUpdate = false;
                        var isMetaTitleManualUpdate = false;
                        var isMetaDescManualUpdate = false;

                        function autoFillFromBrandNameUpdate(value) {

                            if (!isSlugManualUpdate) {
                                document.getElementById('kd-update-brand-slug').value = slugify(value);
                            }

                            if (!isMetaTitleManualUpdate) {
                                document.getElementById('kd-update-brand-meta-title').value = value.substring(0, 60);
                            }
                        }

                        function autoFillMetaDescriptionUpdate(value) {
                            if (!isMetaDescManualUpdate) {
                                document.getElementById('kd-update-brand-meta-description').value = value.substring(0, 160);
                            }
                        }

                        function markSlugAsManualUpdate() {
                            isSlugManualUpdate = true;
                        }

                        function markMetaTitleAsManualUpdate() {
                            isMetaTitleManualUpdate = true;
                        }

                        function markMetaDescAsManualUpdate() {
                            isMetaDescManualUpdate = true;
                        }
                    </script>

                </section>

                <!-- sublink box 3 -->
                <section id="contect_3_contect_box" class="section_sub_with all_sejmca8974 auto_margin_notwa ">

                    <?php if ($product_success_msg): ?>
                        <div class="kd-root-alert kd-root-alert-success" style="color: green;text-align:center;">
                            <?php echo htmlspecialchars($product_success_msg); ?>
                        </div>
                    <?php endif; ?>

                    <?php if ($product_error_msg): ?>
                        <div class="kd-root-alert kd-root-alert-error" style="color: red;text-align:center;>
                            <?php echo htmlspecialchars($product_error_msg); ?>
                        </div>
                    <?php endif; ?>

                    <div class=" custom-table-container" id="show_product_table">

                            <span class="sdao97452">

                                <h2 class="table-title" style="margin:auto 5px;">Product Management</h2>

                                <div style="display:flex; gap:10px; align-items:center;">

                                    <!-- Ye button sirf tabhi active hoga jab kam se kam 1 checkbox select ho -->
                                    <button
                                        class="ihdw9641"
                                        id="delete_selected_btn"
                                        onclick="deleteSelectedProducts()"
                                        disabled
                                        style="background:#dc2626; opacity:0.5; cursor:not-allowed;">
                                        Delete Selected (<span id="selected_count">0</span>)
                                    </button>

                                    <button class="ihdw9641" id="add_product_btn" onclick="show_add_productform()">
                                        Add new
                                    </button>

                                </div>
                            </span>

                            <div class="custom-table-wrapper">

                                <table class="custom-table">

                                    <thead>
                                        <tr>
                                            <th style="width:36px;">
                                                <input type="checkbox" id="select_all_products" onclick="toggleAllProducts(this)">
                                            </th>
                                            <th>Image</th>
                                            <th>ID</th>
                                            <th>Product Name</th>
                                            <th>Category</th>
                                            <th>Brand</th>
                                            <th>Price</th>
                                            <th>Status</th>
                                            <th>Add Product</th>
                                            <th>Last Update</th>
                                            <th style="text-align: center;">Actions</th>
                                        </tr>
                                    </thead>

                                    <tbody id="productTableBody">

                                        <?php if (!empty($product_info)) { ?>

                                            <?php foreach ($product_info as $product) { ?>

                                                <tr>

                                                    <td>
                                                        <input
                                                            type="checkbox"
                                                            class="product-select-checkbox"
                                                            value="<?php echo (int)$product['product_id']; ?>"
                                                            onclick="updateSelectedCount()">
                                                    </td>

                                                    <td>
                                                        <?php if (!empty($product['product_image'])) { ?>
                                                            <img src="<?php echo htmlspecialchars($product['product_image']); ?>"
                                                                alt="<?php echo htmlspecialchars($product['product_name']); ?>"
                                                                style="width:45px; height:45px; object-fit:cover; border-radius:6px; border:1px solid #ddd;">
                                                        <?php } else { ?>
                                                            -
                                                        <?php } ?>
                                                    </td>

                                                    <td style="font-weight: 600; color: #111827;">
                                                        #<?php echo $product['product_id']; ?>
                                                    </td>

                                                    <td>
                                                        <?php echo htmlspecialchars($product['product_name']); ?>
                                                    </td>

                                                    <td>
                                                        <?php echo htmlspecialchars($product['root_name'] ?? '-'); ?>
                                                    </td>

                                                    <td>
                                                        <?php echo htmlspecialchars($product['brand_name'] ?? '-'); ?>
                                                    </td>

                                                    <td>
                                                        <?php if (!empty($product['sale_price']) && $product['sale_price'] != $product['original_price']) { ?>
                                                            <span style="text-decoration:line-through; color:#999;">₹<?php echo htmlspecialchars($product['original_price']); ?></span>
                                                            <span style="color:#111827; font-weight:600;"> ₹<?php echo htmlspecialchars($product['sale_price']); ?></span>
                                                        <?php } else { ?>
                                                            ₹<?php echo htmlspecialchars($product['original_price']); ?>
                                                        <?php } ?>
                                                    </td>

                                                    <td>
                                                        <?php echo htmlspecialchars($product['product_status']); ?>
                                                    </td>
                                                    <td>
                                                        <?php echo timeAgo((int)$product['created_at']); ?>
                                                    </td>

                                                    <td>
                                                        <?php if (empty($brand['updated_at'])): ?>
                                                            <span style="color: green; font-weight: 100;">
                                                                Not Update Yet
                                                            </span>
                                                        <?php else: ?>
                                                            <?php echo timeAgo((int)$brand['updated_at']); ?>
                                                        <?php endif; ?>
                                                    </td>

                                                    <td style="text-align: center;">

                                                        <button
                                                            type="button"
                                                            class="btn-update"
                                                            onclick="updateProductItem(<?php echo (int)$product['product_id']; ?>)">
                                                            Update
                                                        </button>

                                                        <button
                                                            type="button"
                                                            class="btn-delete"
                                                            onclick="doubleCheckDeleteProduct(<?php echo (int)$product['product_id']; ?>)">
                                                            Delete
                                                        </button>

                                                    </td>

                                                </tr>

                                            <?php } ?>

                                        <?php } else { ?>

                                            <tr>
                                                <td colspan="10" style="text-align: center;">
                                                    No products found
                                                </td>
                                            </tr>

                                        <?php } ?>

                                    </tbody>

                                </table>
                            </div>
                        </div>

                        <!-- =====================================================
                         ADD PRODUCT FORM (fresh)
                    ====================================================== -->
                        <div class="kd-root-add-wrapper hidden_class" id="add_product_form">

                            <div class="kd-root-add-header">
                                <div>
                                    <h4>Add Product Form</h4>
                                </div>
                            </div>

                            <form action="" method="POST" class="kd-root-add-form" enctype="multipart/form-data">

                                <div class="kd-root-add-grid">

                                    <!-- Root Category -->
                                    <div class="kd-root-field">
                                        <label for="kd-product-root-id">Root Category <span>*</span></label>
                                        <select id="kd-product-root-id" name="root_id" required>
                                            <option value="">Select Category</option>
                                            <?php foreach ($root_category_options as $root_cat) { ?>
                                                <option value="<?php echo (int)$root_cat['root_id']; ?>">
                                                    <?php echo htmlspecialchars($root_cat['root_name']); ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>

                                    <!-- Brand -->
                                    <div class="kd-root-field">
                                        <label for="kd-product-brand-id">Brand <span>*</span></label>
                                        <select id="kd-product-brand-id" name="brand_id" required>
                                            <option value="">Select Brand</option>
                                            <?php foreach ($brand_options as $brand_opt) { ?>
                                                <option value="<?php echo (int)$brand_opt['brand_id']; ?>">
                                                    <?php echo htmlspecialchars($brand_opt['brand_name']); ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>

                                    <!-- Product Name -->
                                    <div class="kd-root-field">
                                        <label for="kd-product-name">Product Name <span>*</span></label>
                                        <input type="text" id="kd-product-name" name="product_name"
                                            placeholder="Enter product name"
                                            oninput="autoFillFromProductName(this.value)" required>
                                    </div>

                                    <!-- Product Slug -->
                                    <div class="kd-root-field">
                                        <label for="kd-product-slug">Product Slug <span>*</span></label>
                                        <input type="text" id="kd-product-slug" name="product_slug"
                                            placeholder="example-product"
                                            pattern="[a-z0-9-]+"
                                            oninput="markProductSlugAsManual()" required>
                                    </div>

                                    <!-- SKU -->
                                    <div class="kd-root-field" style="display: none;">
                                        <label for="kd-product-sku">Product SKU <span>*</span></label>
                                        <input type="text" id="kd-product-sku" name="product_sku"
                                            placeholder="e.g. PSG2740">
                                    </div>

                                    <!-- color -->
                                    <div class="kd-root-field">
                                        <label for="kd-product-color">Product Color <span>*</span></label>
                                        <input type="text" id="kd-product-color" name="product_color"
                                            placeholder="yellow">
                                    </div>

                                    <!-- size -->
                                    <div class="kd-root-field">
                                        <label for="kd-product-size">Product Size <span>*</span></label>
                                        <input type="text" id="kd-product-size" name="product_size"
                                            placeholder="M/34">
                                    </div>

                                    <!-- Status -->
                                    <div class="kd-root-field">
                                        <label for="kd-product-status">Status <span>*</span></label>
                                        <select id="kd-product-status" name="product_status" required>
                                            <option value="Active">Active</option>
                                            <option value="Inactive">Inactive</option>
                                        </select>
                                    </div>

                                    <!-- Original Price -->
                                    <div class="kd-root-field">
                                        <label for="kd-product-original-price">Original Price <span>*</span></label>
                                        <input type="number" step="0.01" id="kd-product-original-price"
                                            name="original_price" placeholder="0.00" required>
                                    </div>

                                    <!-- Sale Price -->
                                    <div class="kd-root-field">
                                        <label for="kd-product-sale-price">Sale Price</label>
                                        <input type="number" step="0.01" id="kd-product-sale-price"
                                            name="sale_price" placeholder="0.00">
                                    </div>

                                    <!-- Discount Visibility -->
                                    <div class="kd-root-field">
                                        <label for="kd-product-discount-visibility">Discount Visibility</label>
                                        <select id="kd-product-discount-visibility" name="discount_visibility">
                                            <option value="Hide">Hide</option>
                                            <option value="Show">Show</option>
                                        </select>
                                    </div>

                                    <!-- Main Product Image -->
                                    <div class="kd-root-field">
                                        <label for="kd-product-image">Main Product Image</label>
                                        <input type="file" id="kd-product-image" name="product_image"
                                            accept="image/*"
                                            onchange="previewSelectedFile(this, 'preview-product-image', 'image')">

                                        <div id="preview-product-image" style="margin-top:8px;"></div>

                                        <button type="button" class="kd-root-reset-btn"
                                            style="margin-top:6px; padding:4px 10px; font-size:12px;"
                                            onclick="removeSelectedFile('kd-product-image', 'preview-product-image')">
                                            Remove
                                        </button>
                                    </div>

                                    <!-- Product Description -->
                                    <div class="kd-root-field kd-root-full">
                                        <label for="kd-product-description">Product Description</label>
                                        <textarea id="kd-product-description" name="product_description" rows="5"
                                            placeholder="Enter product description"
                                            oninput="autoFillProductMetaDescription(this.value)"></textarea>
                                    </div>

                                    <!-- Other Info Description -->
                                    <div class="kd-root-field kd-root-full">
                                        <label for="kd-product-other-info-desc">Other Info Description</label>
                                        <textarea id="kd-product-other-info-desc" name="product_other_info_desc" rows="4"
                                            placeholder="Specifications, warranty info, etc."></textarea>
                                    </div>

                                    <!-- Meta Title -->
                                    <div class="kd-root-field kd-root-full">
                                        <label for="kd-product-meta-title">Meta Title</label>
                                        <input type="text" id="kd-product-meta-title" name="meta_title"
                                            maxlength="60" oninput="markProductMetaTitleAsManual()">
                                    </div>

                                    <!-- Meta Description -->
                                    <div class="kd-root-field kd-root-full">
                                        <label for="kd-product-meta-description">Meta Description</label>
                                        <textarea id="kd-product-meta-description" name="meta_description" rows="3"
                                            maxlength="160" oninput="markProductMetaDescAsManual()"></textarea>
                                    </div>

                                    <!-- Meta Keywords -->
                                    <div class="kd-root-field kd-root-full">
                                        <label for="kd-product-meta-keywords">Meta Keywords</label>
                                        <textarea id="kd-product-meta-keywords" name="meta_keywords" rows="2"
                                            placeholder="keyword 1, keyword 2"></textarea>
                                    </div>

                                    <!-- Canonical URL -->
                                    <div class="kd-root-field kd-root-full">
                                        <label for="kd-product-canonical-url">Canonical URL</label>
                                        <input type="text" id="kd-product-canonical-url" name="canonical_url"
                                            placeholder="https://yoursite.com/products/example-product">
                                    </div>

                                    <!-- OG Title -->
                                    <div class="kd-root-field">
                                        <label for="kd-product-og-title">OG Title</label>
                                        <input type="text" id="kd-product-og-title" name="og_title" maxlength="60">
                                    </div>

                                    <!-- OG Description -->
                                    <div class="kd-root-field kd-root-full">
                                        <label for="kd-product-og-description">OG Description</label>
                                        <textarea id="kd-product-og-description" name="og_description" rows="3" maxlength="160"></textarea>
                                    </div>

                                    <!-- Gallery Images (1 to 10) -->
                                    <div class="kd-root-field kd-root-full">
                                        <label>Gallery Images (upto 10)</label>

                                        <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap:12px; margin-top:8px;">

                                            <?php for ($i = 1; $i <= 10; $i++) { ?>
                                                <div style="border:1px solid #eee; border-radius:6px; padding:10px;">
                                                    <label style="font-size:13px; font-weight:600;">Image <?php echo $i; ?></label>

                                                    <input type="file" id="kd-product-img-<?php echo $i; ?>"
                                                        name="product_img_<?php echo $i; ?>" accept="image/*"
                                                        style="width:100%; margin-top:4px;"
                                                        onchange="previewSelectedFile(this, 'preview-gallery-<?php echo $i; ?>', 'image')">

                                                    <div id="preview-gallery-<?php echo $i; ?>" style="margin-top:6px;"></div>

                                                    <input type="text" name="product_img_<?php echo $i; ?>_alt"
                                                        placeholder="Alt text" style="width:100%; margin-top:6px;">

                                                    <button type="button" class="kd-root-reset-btn"
                                                        style="margin-top:6px; padding:3px 8px; font-size:11px;"
                                                        onclick="removeSelectedFile('kd-product-img-<?php echo $i; ?>', 'preview-gallery-<?php echo $i; ?>')">
                                                        Remove
                                                    </button>
                                                </div>
                                            <?php } ?>

                                        </div>
                                    </div>

                                    <!-- Brochure -->
                                    <div class="kd-root-field">
                                        <label for="kd-product-brochure">Brochure (PDF)</label>
                                        <input type="file" id="kd-product-brochure" name="product_brochure" accept="application/pdf"
                                            onchange="previewSelectedFile(this, 'preview-brochure', 'file')">

                                        <div id="preview-brochure" style="margin-top:8px;"></div>

                                        <button type="button" class="kd-root-reset-btn"
                                            style="margin-top:6px; padding:4px 10px; font-size:12px;"
                                            onclick="removeSelectedFile('kd-product-brochure', 'preview-brochure')">
                                            Remove
                                        </button>
                                    </div>

                                    <!-- Video Upload (file, with preview + remove) -->
                                    <div class="kd-root-field">
                                        <label for="kd-product-video-1">Product Video</label>
                                        <input type="file" id="kd-product-video-1" name="product_video_1" accept="video/*"
                                            onchange="previewSelectedFile(this, 'preview-video', 'video')">

                                        <div id="preview-video" style="margin-top:8px;"></div>

                                        <button type="button" class="kd-root-reset-btn"
                                            style="margin-top:6px; padding:4px 10px; font-size:12px;"
                                            onclick="removeSelectedFile('kd-product-video-1', 'preview-video')">
                                            Remove
                                        </button>
                                    </div>

                                </div>

                                <div class="kd-root-form-actions">

                                    <button type="button" onclick="show_add_productform()" class="kd-root-reset-btn">Cancel</button>
                                    <button type="button" onclick="resetProductForm()" class="kd-root-reset-btn">Reset</button>

                                    <button type="submit" name="add_product" class="kd-root-submit-btn">
                                        <i class="fa-solid fa-plus"></i>
                                        Add Product
                                    </button>

                                </div>

                            </form>

                        </div>

                        <!-- =====================================================
                         UPDATE PRODUCT FORM (fresh)
                    ====================================================== -->
                        <div class="kd-root-add-wrapper hidden_class" id="update_product_form">

                            <div class="kd-root-add-header">
                                <div>
                                    <h2 class="kd-root-add-title">Update Product</h2>
                                    <p class="kd-root-add-subtitle">Update product details, images & video</p>
                                </div>
                            </div>

                            <form action="" method="POST" class="kd-root-add-form" enctype="multipart/form-data">

                                <input type="hidden" id="kd-update-product-id" name="product_id" value="">
                                <input type="hidden" id="kd-update-existing-main-image" name="existing_main_image" value="">
                                <input type="hidden" id="kd-update-existing-brochure" name="existing_brochure" value="">
                                <input type="hidden" id="kd-update-existing-video-1" name="existing_video_1" value="">
                                <?php for ($i = 1; $i <= 10; $i++) { ?>
                                    <input type="hidden" id="kd-update-existing-img-<?php echo $i; ?>" name="existing_img_<?php echo $i; ?>" value="">
                                <?php } ?>

                                <div class="kd-root-add-grid">

                                    <!-- Root Category -->
                                    <div class="kd-root-field">
                                        <label for="kd-update-product-root-id">Root Category <span>*</span></label>
                                        <select id="kd-update-product-root-id" name="root_id" required>
                                            <option value="">Select Category</option>
                                            <?php foreach ($root_category_options as $root_cat) { ?>
                                                <option value="<?php echo (int)$root_cat['root_id']; ?>">
                                                    <?php echo htmlspecialchars($root_cat['root_name']); ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>

                                    <!-- Brand -->
                                    <div class="kd-root-field">
                                        <label for="kd-update-product-brand-id">Brand <span>*</span></label>
                                        <select id="kd-update-product-brand-id" name="brand_id" required>
                                            <option value="">Select Brand</option>
                                            <?php foreach ($brand_options as $brand_opt) { ?>
                                                <option value="<?php echo (int)$brand_opt['brand_id']; ?>">
                                                    <?php echo htmlspecialchars($brand_opt['brand_name']); ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>

                                    <!-- Product Name -->
                                    <div class="kd-root-field">
                                        <label for="kd-update-product-name">Product Name <span>*</span></label>
                                        <input type="text" id="kd-update-product-name" name="product_name"
                                            placeholder="Enter product name"
                                            oninput="autoFillFromProductNameUpdate(this.value)" required>
                                    </div>

                                    <!-- Product Slug -->
                                    <div class="kd-root-field">
                                        <label for="kd-update-product-slug">Product Slug <span>*</span></label>
                                        <input type="text" id="kd-update-product-slug" name="product_slug"
                                            placeholder="example-product"
                                            pattern="[a-z0-9-]+"
                                            oninput="markProductSlugAsManualUpdate()" required>
                                    </div>

                                    <!-- SKU -->
                                    <div class="kd-root-field" style="display: none;">
                                        <label for="kd-update-product-sku">Product SKU</label>
                                        <input type="text" id="kd-update-product-sku" name="product_sku"
                                            placeholder="e.g. PSG2740">
                                    </div>

                                    <!-- color -->
                                    <div class="kd-root-field">
                                        <label for="kd-update-product-color">Product Color</label>
                                        <input type="text" id="kd-update-product-color" name="product_color"
                                            placeholder="red">
                                    </div>

                                    <!-- size -->
                                    <div class="kd-root-field">
                                        <label for="kd-update-product-size">Product Size</label>
                                        <input type="text" id="kd-update-product-size" name="product_size"
                                            placeholder="M / 32">
                                    </div>

                                    <!-- Status -->
                                    <div class="kd-root-field">
                                        <label for="kd-update-product-status">Status <span>*</span></label>
                                        <select id="kd-update-product-status" name="product_status" required>
                                            <option value="Active">Active</option>
                                            <option value="Inactive">Inactive</option>
                                        </select>
                                    </div>

                                    <!-- Original Price -->
                                    <div class="kd-root-field">
                                        <label for="kd-update-product-original-price">Original Price <span>*</span></label>
                                        <input type="number" step="0.01" id="kd-update-product-original-price"
                                            name="original_price" placeholder="0.00" required>
                                    </div>

                                    <!-- Sale Price -->
                                    <div class="kd-root-field">
                                        <label for="kd-update-product-sale-price">Sale Price</label>
                                        <input type="number" step="0.01" id="kd-update-product-sale-price"
                                            name="sale_price" placeholder="0.00">
                                    </div>

                                    <!-- Discount Visibility -->
                                    <div class="kd-root-field">
                                        <label for="kd-update-product-discount-visibility">Discount Visibility</label>
                                        <select id="kd-update-product-discount-visibility" name="discount_visibility">
                                            <option value="Hide">Hide</option>
                                            <option value="Show">Show</option>
                                        </select>
                                    </div>

                                    <!-- Main Product Image -->
                                    <div class="kd-root-field">
                                        <label for="kd-update-product-image">Main Product Image</label>
                                        <input type="file" id="kd-update-product-image" name="product_image"
                                            accept="image/*"
                                            onchange="previewSelectedFile(this, 'update-preview-product-image', 'image')">

                                        <div id="update-preview-product-image" style="margin-top:8px;"></div>

                                        <button type="button" class="kd-root-reset-btn"
                                            style="margin-top:6px; padding:4px 10px; font-size:12px;"
                                            onclick="removeSelectedFileUpdate('kd-update-product-image', 'update-preview-product-image', 'kd-update-existing-main-image')">
                                            Remove
                                        </button>
                                    </div>

                                    <!-- Product Description -->
                                    <div class="kd-root-field kd-root-full">
                                        <label for="kd-update-product-description">Product Description</label>
                                        <textarea id="kd-update-product-description" name="product_description" rows="5"
                                            placeholder="Enter product description"
                                            oninput="autoFillProductMetaDescriptionUpdate(this.value)"></textarea>
                                    </div>

                                    <!-- Other Info Description -->
                                    <div class="kd-root-field kd-root-full">
                                        <label for="kd-update-product-other-info-desc">Other Info Description</label>
                                        <textarea id="kd-update-product-other-info-desc" name="product_other_info_desc" rows="4"
                                            placeholder="Specifications, warranty info, etc."></textarea>
                                    </div>

                                    <!-- Meta Title -->
                                    <div class="kd-root-field kd-root-full">
                                        <label for="kd-update-product-meta-title">Meta Title</label>
                                        <input type="text" id="kd-update-product-meta-title" name="meta_title"
                                            maxlength="60" oninput="markProductMetaTitleAsManualUpdate()">
                                    </div>

                                    <!-- Meta Description -->
                                    <div class="kd-root-field kd-root-full">
                                        <label for="kd-update-product-meta-description">Meta Description</label>
                                        <textarea id="kd-update-product-meta-description" name="meta_description" rows="3"
                                            maxlength="160" oninput="markProductMetaDescAsManualUpdate()"></textarea>
                                    </div>

                                    <!-- Meta Keywords -->
                                    <div class="kd-root-field kd-root-full">
                                        <label for="kd-update-product-meta-keywords">Meta Keywords</label>
                                        <textarea id="kd-update-product-meta-keywords" name="meta_keywords" rows="2"
                                            placeholder="keyword 1, keyword 2"></textarea>
                                    </div>

                                    <!-- Canonical URL -->
                                    <div class="kd-root-field kd-root-full">
                                        <label for="kd-update-product-canonical-url">Canonical URL</label>
                                        <input type="text" id="kd-update-product-canonical-url" name="canonical_url"
                                            placeholder="https://yoursite.com/products/example-product">
                                    </div>

                                    <!-- OG Title -->
                                    <div class="kd-root-field">
                                        <label for="kd-update-product-og-title">OG Title</label>
                                        <input type="text" id="kd-update-product-og-title" name="og_title" maxlength="60">
                                    </div>

                                    <!-- OG Description -->
                                    <div class="kd-root-field kd-root-full">
                                        <label for="kd-update-product-og-description">OG Description</label>
                                        <textarea id="kd-update-product-og-description" name="og_description" rows="3" maxlength="160"></textarea>
                                    </div>

                                    <!-- Gallery Images (1 to 10) -->
                                    <div class="kd-root-field kd-root-full">
                                        <label>Gallery Images (upto 10)</label>

                                        <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap:12px; margin-top:8px;">

                                            <?php for ($i = 1; $i <= 10; $i++) { ?>
                                                <div style="border:1px solid #eee; border-radius:6px; padding:10px;">
                                                    <label style="font-size:13px; font-weight:600;">Image <?php echo $i; ?></label>

                                                    <input type="file" id="kd-update-product-img-<?php echo $i; ?>"
                                                        name="product_img_<?php echo $i; ?>" accept="image/*"
                                                        style="width:100%; margin-top:4px;"
                                                        onchange="previewSelectedFile(this, 'update-preview-gallery-<?php echo $i; ?>', 'image')">

                                                    <div id="update-preview-gallery-<?php echo $i; ?>" style="margin-top:6px;"></div>

                                                    <input type="text" id="kd-update-product-img-<?php echo $i; ?>-alt"
                                                        name="product_img_<?php echo $i; ?>_alt"
                                                        placeholder="Alt text" style="width:100%; margin-top:6px;">

                                                    <button type="button" class="kd-root-reset-btn"
                                                        style="margin-top:6px; padding:3px 8px; font-size:11px;"
                                                        onclick="removeSelectedFileUpdate('kd-update-product-img-<?php echo $i; ?>', 'update-preview-gallery-<?php echo $i; ?>', 'kd-update-existing-img-<?php echo $i; ?>')">
                                                        Remove
                                                    </button>
                                                </div>
                                            <?php } ?>

                                        </div>
                                    </div>

                                    <!-- Brochure -->
                                    <div class="kd-root-field">
                                        <label for="kd-update-product-brochure">Brochure (PDF)</label>
                                        <input type="file" id="kd-update-product-brochure" name="product_brochure" accept="application/pdf"
                                            onchange="previewSelectedFile(this, 'update-preview-brochure', 'file')">

                                        <div id="update-preview-brochure" style="margin-top:8px;"></div>

                                        <button type="button" class="kd-root-reset-btn"
                                            style="margin-top:6px; padding:4px 10px; font-size:12px;"
                                            onclick="removeSelectedFileUpdate('kd-update-product-brochure', 'update-preview-brochure', 'kd-update-existing-brochure')">
                                            Remove
                                        </button>
                                    </div>

                                    <!-- Video Upload -->
                                    <div class="kd-root-field">
                                        <label for="kd-update-product-video-1">Product Video</label>
                                        <input type="file" id="kd-update-product-video-1" name="product_video_1" accept="video/*"
                                            onchange="previewSelectedFile(this, 'update-preview-video', 'video')">

                                        <div id="update-preview-video" style="margin-top:8px;"></div>

                                        <button type="button" class="kd-root-reset-btn"
                                            style="margin-top:6px; padding:4px 10px; font-size:12px;"
                                            onclick="removeSelectedFileUpdate('kd-update-product-video-1', 'update-preview-video', 'kd-update-existing-video-1')">
                                            Remove
                                        </button>
                                    </div>

                                </div>

                                <div class="kd-root-form-actions">

                                    <button type="button" onclick="cancelUpdateProduct()" class="kd-root-reset-btn">Cancel</button>

                                    <button type="submit" name="update_product" class="kd-root-submit-btn">
                                        <i class="fa-solid fa-pen"></i>
                                        Update Product
                                    </button>

                                </div>

                            </form>

                        </div>

                        <script>
                            var productDataList = <?php echo json_encode($product_info); ?>;

                            // ===========================================================
                            // MULTI-SELECT CHECKBOX LOGIC (design/UI abhi ready hai)
                            // Delete backend query baad me add hogi
                            // ===========================================================

                            function toggleAllProducts(sourceCheckbox) {

                                var checkboxes = document.querySelectorAll('.product-select-checkbox');

                                checkboxes.forEach(function(cb) {
                                    cb.checked = sourceCheckbox.checked;
                                });

                                updateSelectedCount();
                            }

                            function updateSelectedCount() {

                                var checkboxes = document.querySelectorAll('.product-select-checkbox');
                                var checkedBoxes = document.querySelectorAll('.product-select-checkbox:checked');
                                var deleteBtn = document.getElementById('delete_selected_btn');
                                var countLabel = document.getElementById('selected_count');
                                var selectAllBox = document.getElementById('select_all_products');

                                countLabel.textContent = checkedBoxes.length;

                                if (checkedBoxes.length > 0) {
                                    deleteBtn.disabled = false;
                                    deleteBtn.style.opacity = '1';
                                    deleteBtn.style.cursor = 'pointer';
                                } else {
                                    deleteBtn.disabled = true;
                                    deleteBtn.style.opacity = '0.5';
                                    deleteBtn.style.cursor = 'not-allowed';
                                }

                                // Select-all checkbox ko sync rakho
                                selectAllBox.checked = (checkedBoxes.length === checkboxes.length && checkboxes.length > 0);
                            }

                            function deleteSelectedProducts() {

                                var checkedBoxes = document.querySelectorAll('.product-select-checkbox:checked');

                                if (checkedBoxes.length === 0) {
                                    return;
                                }

                                var selectedIds = Array.from(checkedBoxes).map(function(cb) {
                                    return cb.value;
                                });

                                if (!confirm(selectedIds.length + " product(s) delete karne hain? Ye action wapas nahi ho sakta.")) {
                                    return;
                                }

                                document.getElementById('selected_product_ids_input').value = selectedIds.join(',');
                                document.getElementById('bulk_delete_product_form').submit();
                            }

                            function doubleCheckDeleteProduct(id) {
                                if (confirm("Product #" + id + " delete karna hai? Ye action wapas nahi ho sakta.")) {
                                    document.getElementById('delete_product_id_input').value = id;
                                    document.getElementById('delete_product_form').submit();
                                }
                            }

                            function updateProductItem(id) {

                                var product = productDataList.find(function(item) {
                                    return parseInt(item.product_id) === parseInt(id);
                                });

                                if (!product) {
                                    return;
                                }

                                document.getElementById('kd-update-product-id').value = product.product_id || '';
                                document.getElementById('kd-update-product-root-id').value = product.root_id || '';
                                document.getElementById('kd-update-product-brand-id').value = product.brand_id || '';
                                document.getElementById('kd-update-product-name').value = product.product_name || '';
                                document.getElementById('kd-update-product-slug').value = product.product_slug || '';
                                document.getElementById('kd-update-product-sku').value = product.product_sku || '';
                                document.getElementById('kd-update-product-color').value = product.product_color || '';
                                document.getElementById('kd-update-product-size').value = product.product_size || '';
                                document.getElementById('kd-update-product-status').value = product.product_status || 'Active';
                                document.getElementById('kd-update-product-original-price').value = product.original_price || '';
                                document.getElementById('kd-update-product-sale-price').value = product.sale_price || '';
                                document.getElementById('kd-update-product-discount-visibility').value = product.discount_visibility || 'Hide';
                                document.getElementById('kd-update-product-description').value = product.product_description || '';
                                document.getElementById('kd-update-product-other-info-desc').value = product.product_other_info_desc || '';
                                document.getElementById('kd-update-product-meta-title').value = product.meta_title || '';
                                document.getElementById('kd-update-product-meta-description').value = product.meta_description || '';
                                document.getElementById('kd-update-product-meta-keywords').value = product.meta_keywords || '';
                                document.getElementById('kd-update-product-canonical-url').value = product.canonical_url || '';
                                document.getElementById('kd-update-product-og-title').value = product.og_title || '';
                                document.getElementById('kd-update-product-og-description').value = product.og_description || '';

                                // File inputs hamesha khali shuru hote hain, hidden fields me purana path
                                document.getElementById('kd-update-product-image').value = '';
                                document.getElementById('kd-update-existing-main-image').value = product.product_image || '';
                                renderExistingPreview(product.product_image, 'update-preview-product-image', 'image');

                                document.getElementById('kd-update-product-brochure').value = '';
                                document.getElementById('kd-update-existing-brochure').value = product.product_brochure || '';
                                renderExistingPreview(product.product_brochure, 'update-preview-brochure', 'file');

                                document.getElementById('kd-update-product-video-1').value = '';
                                document.getElementById('kd-update-existing-video-1').value = product.product_video_1 || '';
                                renderExistingPreview(product.product_video_1, 'update-preview-video', 'video');

                                for (var i = 1; i <= 10; i++) {
                                    var imgKey = 'product_img_' + i;
                                    var altKey = 'product_img_' + i + '_alt';

                                    document.getElementById('kd-update-product-img-' + i).value = '';
                                    document.getElementById('kd-update-existing-img-' + i).value = product[imgKey] || '';
                                    document.getElementById('kd-update-product-img-' + i + '-alt').value = product[altKey] || '';
                                    renderExistingPreview(product[imgKey], 'update-preview-gallery-' + i, 'image');
                                }

                                // Is form ke auto-fill flags manual mode me (fields already sahi bhari hain)
                                isProductSlugManualUpdate = false;
                                isProductMetaTitleManualUpdate = false;
                                isProductMetaDescManualUpdate = false;

                                document.getElementById('show_product_table').classList.add('hidden_class');
                                document.getElementById('update_product_form').classList.remove('hidden_class');
                            }

                            function cancelUpdateProduct() {
                                document.getElementById('update_product_form').classList.add('hidden_class');
                                document.getElementById('show_product_table').classList.remove('hidden_class');
                            }

                            // Existing (DB me pehle se saved) file ka preview dikhata hai
                            function renderExistingPreview(url, previewWrapperId, kind) {

                                var wrapper = document.getElementById(previewWrapperId);
                                wrapper.innerHTML = '';

                                if (!url) {
                                    return;
                                }

                                if (kind === 'image') {
                                    wrapper.innerHTML =
                                        '<img src="' + url + '" style="max-width:120px; max-height:120px; ' +
                                        'border:1px solid #ddd; border-radius:6px; object-fit:cover;">';
                                } else if (kind === 'video') {
                                    wrapper.innerHTML =
                                        '<video src="' + url + '" controls style="max-width:240px; max-height:150px; ' +
                                        'border:1px solid #ddd; border-radius:6px;"></video>';
                                } else {
                                    wrapper.innerHTML =
                                        '<span style="font-size:13px; color:#333;">📄 ' + url.split('/').pop() + '</span>';
                                }
                            }

                            // Update form ke "Remove" button - naya selection + preview
                            // + existing hidden field (purana path) sab clear karta hai
                            function removeSelectedFileUpdate(inputId, previewWrapperId, hiddenExistingId) {
                                document.getElementById(inputId).value = '';
                                document.getElementById(previewWrapperId).innerHTML = '';
                                document.getElementById(hiddenExistingId).value = '';
                            }

                            // Update form ke apne auto-fill flags (add form se alag)
                            var isProductSlugManualUpdate = false;
                            var isProductMetaTitleManualUpdate = false;
                            var isProductMetaDescManualUpdate = false;

                            function autoFillFromProductNameUpdate(value) {

                                if (!isProductSlugManualUpdate) {
                                    document.getElementById('kd-update-product-slug').value = slugifyProduct(value);
                                }

                                if (!isProductMetaTitleManualUpdate) {
                                    document.getElementById('kd-update-product-meta-title').value = value.substring(0, 60);
                                    document.getElementById('kd-update-product-og-title').value = value.substring(0, 60);
                                }
                            }

                            function autoFillProductMetaDescriptionUpdate(value) {
                                if (!isProductMetaDescManualUpdate) {
                                    document.getElementById('kd-update-product-meta-description').value = value.substring(0, 160);
                                    document.getElementById('kd-update-product-og-description').value = value.substring(0, 160);
                                }
                            }

                            function markProductSlugAsManualUpdate() {
                                isProductSlugManualUpdate = true;
                            }

                            function markProductMetaTitleAsManualUpdate() {
                                isProductMetaTitleManualUpdate = true;
                            }

                            function markProductMetaDescAsManualUpdate() {
                                isProductMetaDescManualUpdate = true;
                            }

                            function show_add_productform() {
                                document.getElementById('show_product_table').classList.toggle('hidden_class');
                                document.getElementById('add_product_form').classList.toggle('hidden_class');
                            }

                            // ===========================================================
                            // AUTO-FILL: Product Name -> Slug + Meta Title + OG Title
                            // Description -> Meta Description + OG Description
                            // ===========================================================
                            var isProductSlugManual = false;
                            var isProductMetaTitleManual = false;
                            var isProductMetaDescManual = false;

                            function slugifyProduct(text) {
                                return text
                                    .toString()
                                    .toLowerCase()
                                    .trim()
                                    .normalize('NFD').replace(/[\u0300-\u036f]/g, '')
                                    .replace(/[^a-z0-9\s-]/g, '')
                                    .replace(/[\s_-]+/g, '-')
                                    .replace(/^-+|-+$/g, '');
                            }

                            function autoFillFromProductName(value) {

                                if (!isProductSlugManual) {
                                    document.getElementById('kd-product-slug').value = slugifyProduct(value);
                                }

                                if (!isProductMetaTitleManual) {
                                    document.getElementById('kd-product-meta-title').value = value.substring(0, 60);
                                    document.getElementById('kd-product-og-title').value = value.substring(0, 60);
                                }
                            }

                            function autoFillProductMetaDescription(value) {
                                if (!isProductMetaDescManual) {
                                    document.getElementById('kd-product-meta-description').value = value.substring(0, 160);
                                    document.getElementById('kd-product-og-description').value = value.substring(0, 160);
                                }
                            }

                            function markProductSlugAsManual() {
                                isProductSlugManual = true;
                            }

                            function markProductMetaTitleAsManual() {
                                isProductMetaTitleManual = true;
                            }

                            function markProductMetaDescAsManual() {
                                isProductMetaDescManual = true;
                            }

                            // ===========================================================
                            // GENERIC FILE PREVIEW + REMOVE
                            // kind: 'image' | 'video' | 'file'
                            // ===========================================================
                            function previewSelectedFile(inputEl, previewWrapperId, kind) {

                                var wrapper = document.getElementById(previewWrapperId);
                                wrapper.innerHTML = '';

                                if (!inputEl.files || !inputEl.files[0]) {
                                    return;
                                }

                                var file = inputEl.files[0];
                                var url = URL.createObjectURL(file);

                                if (kind === 'image') {
                                    wrapper.innerHTML =
                                        '<img src="' + url + '" style="max-width:120px; max-height:120px; ' +
                                        'border:1px solid #ddd; border-radius:6px; object-fit:cover;">';
                                } else if (kind === 'video') {
                                    wrapper.innerHTML =
                                        '<video src="' + url + '" controls style="max-width:240px; max-height:150px; ' +
                                        'border:1px solid #ddd; border-radius:6px;"></video>';
                                } else {
                                    wrapper.innerHTML =
                                        '<span style="font-size:13px; color:#333;">📄 ' + file.name + '</span>';
                                }
                            }

                            function removeSelectedFile(inputId, previewWrapperId) {
                                document.getElementById(inputId).value = '';
                                document.getElementById(previewWrapperId).innerHTML = '';
                            }

                            // Reset button - poora form + saari previews clear
                            function resetProductForm() {

                                document.querySelector('#add_product_form form').reset();

                                document.getElementById('preview-product-image').innerHTML = '';
                                document.getElementById('preview-brochure').innerHTML = '';
                                document.getElementById('preview-video').innerHTML = '';

                                for (var i = 1; i <= 10; i++) {
                                    var el = document.getElementById('preview-gallery-' + i);
                                    if (el) {
                                        el.innerHTML = '';
                                    }
                                }

                                isProductSlugManual = false;
                                isProductMetaTitleManual = false;
                                isProductMetaDescManual = false;
                            }
                        </script>

                        <!-- Hidden form - single product delete ke liye
                         (JS ise fill karke submit karta hai) -->
                        <form action="" method="POST" id="delete_product_form" style="display:none;">
                            <input type="hidden" name="delete_product_id" id="delete_product_id_input" value="">
                            <input type="hidden" name="delete_single_product" value="1">
                        </form>

                        <!-- Hidden form - multiple products (bulk) delete ke liye -->
                        <form action="" method="POST" id="bulk_delete_product_form" style="display:none;">
                            <input type="hidden" name="selected_product_ids" id="selected_product_ids_input" value="">
                            <input type="hidden" name="delete_selected_products" value="1">
                        </form>

                </section>

                <!-- sublink box 4 -->
                <section id="contect_4_contect_box" class="section_sub_with all_sejmca8974 auto_margin_notwa">
                    4
                </section>

                <!-- sublink box 5 -->
                <section id="contect_5_contect_box" class="section_sub_with all_sejmca8974 auto_margin_notwa">
                    5
                </section>

                <!-- sublink box 6 -->
                <section id="contect_6_contect_box" class="section_sub_with all_sejmca8974 auto_margin_notwa">
                    6
                </section>

                <!-- sublink box 7 -->
                <section id="contect_7_contect_box" class="section_sub_with all_sejmca8974 auto_margin_notwa">
                    7
                </section>

                <!-- sublink box 8 -->
                <section id="contect_8_contect_box" class="section_sub_with all_sejmca8974 auto_margin_notwa">
                    8
                </section>

                <!-- sublink box 9 -->
                <section id="contect_9_contect_box" class="section_sub_with all_sejmca8974 auto_margin_notwa">
                    9
                </section>

                <!-- sublink box 10 -->
                <section id="contect_10_contect_box" class="section_sub_with all_sejmca8974 auto_margin_notwa">
                    10
                </section>

                <!-- sublink box 11 -->
                <section id="contect_11_contect_box" class="section_sub_with all_sejmca8974 auto_margin_notwa">
                    11
                </section>

                <!-- sublink box 12 -->
                <section id="contect_12_contect_box" class="section_sub_with all_sejmca8974 auto_margin_notwa">
                    12
                </section>

                <!-- sublink box 13 -->
                <section id="contect_13_contect_box" class="section_sub_with all_sejmca8974 auto_margin_notwa">
                    13
                </section>

                <!-- sublink box 14 -->
                <section id="contect_14_contect_box" class="section_sub_with all_sejmca8974 auto_margin_notwa">
                    14
                </section>

                <!-- sublink box 15 -->
                <section id="contect_15_contect_box" class="section_sub_with all_sejmca8974 auto_margin_notwa">
                    15
                </section>

                <!-- sublink box 16 -->
                <section id="contect_16_contect_box" class="section_sub_with all_sejmca8974 auto_margin_notwa">
                    16
                </section>

                <!-- sublink box 17 -->
                <section id="contect_17_contect_box" class="section_sub_with all_sejmca8974 auto_margin_notwa">
                    17
                </section>

                <!-- sublink box 18 -->
                <section id="contect_18_contect_box" class="section_sub_with all_sejmca8974 auto_margin_notwa">
                    18
                </section>

                <!-- sublink box 19 -->
                <section id="contect_19_contect_box" class="section_sub_with all_sejmca8974 auto_margin_notwa">
                    19
                </section>

                <!-- sublink box 20 -->
                <section id="contect_20_contect_box" class="section_sub_with all_sejmca8974 auto_margin_notwa">
                    20
                </section>

                <!-- sublink box 21 -->
                <section id="contect_21_contect_box" class="section_sub_with all_sejmca8974 auto_margin_notwa">
                    21
                </section>

                <!-- sublink box 22 -->
                <section id="contect_22_contect_box" class="section_sub_with all_sejmca8974 auto_margin_notwa">
                    22
                </section>

                <!-- sublink box 23 -->
                <section id="contect_23_contect_box" class="section_sub_with all_sejmca8974 auto_margin_notwa">
                    23
                </section>

                <!-- sublink box 24 -->
                <section id="contect_24_contect_box" class="section_sub_with all_sejmca8974 auto_margin_notwa">
                    24
                </section>

                <!-- sublink box 25 -->
                <section id="contect_25_contect_box" class="section_sub_with all_sejmca8974 auto_margin_notwa">
                    25
                </section>

                <!-- sublink box 26 -->
                <section id="contect_26_contect_box" class="section_sub_with all_sejmca8974 auto_margin_notwa">
                    26
                </section>

                <!-- sublink box 27 -->
                <section id="contect_27_contect_box" class="section_sub_with all_sejmca8974 auto_margin_notwa">
                    27
                </section>

                <!-- sublink box 28 -->
                <section id="contect_28_contect_box" class="section_sub_with all_sejmca8974 auto_margin_notwa">
                    28
                </section>

                <!-- sublink box 29 -->
                <section id="contect_29_contect_box" class="section_sub_with all_sejmca8974 auto_margin_notwa">
                    29
                </section>

                <!-- sublink box 30 -->
                <section id="contect_30_contect_box" class="section_sub_with all_sejmca8974 auto_margin_notwa">
                    30
                </section>

                <!-- sublink box 31 -->
                <section id="contect_31_contect_box" class="section_sub_with all_sejmca8974 auto_margin_notwa">
                    31
                </section>

                <!-- sublink box 32 -->
                <section id="contect_32_contect_box" class="section_sub_with all_sejmca8974 auto_margin_notwa">
                    32
                </section>

                <!-- sublink box 33 -->
                <section id="contect_33_contect_box" class="section_sub_with all_sejmca8974 auto_margin_notwa">
                    33
                </section>

                <!-- sublink box 34 -->
                <section id="contect_34_contect_box" class="section_sub_with all_sejmca8974 auto_margin_notwa">
                    34
                </section>

                <!-- sublink box 35 -->
                <section id="contect_35_contect_box" class="section_sub_with all_sejmca8974 auto_margin_notwa">
                    35
                </section>

                <!-- sublink box 36 -->
                <section id="contect_36_contect_box" class="section_sub_with all_sejmca8974 auto_margin_notwa">
                    36
                </section>

                <!-- sublink box 37 -->
                <section id="contect_37_contect_box" class="section_sub_with all_sejmca8974 auto_margin_notwa">
                    37
                </section>

                <!-- sublink box 38 -->
                <section id="contect_38_contect_box" class="section_sub_with all_sejmca8974 auto_margin_notwa">
                    38
                </section>

                <!-- sublink box 39 -->
                <section id="contect_39_contect_box" class="section_sub_with all_sejmca8974 auto_margin_notwa">
                    39
                </section>

                <!-- sublink box 40 -->
                <section id="contect_40_contect_box" class="section_sub_with all_sejmca8974 auto_margin_notwa">
                    40
                </section>

                <!-- sublink box 41 -->
                <section id="contect_41_contect_box" class="section_sub_with all_sejmca8974 auto_margin_notwa">
                    41
                </section>

                <!-- sublink box 42 -->
                <section id="contect_42_contect_box" class="section_sub_with all_sejmca8974 auto_margin_notwa">
                    42
                </section>

                <!-- sublink box 43 -->
                <section id="contect_43_contect_box" class="section_sub_with all_sejmca8974 auto_margin_notwa">
                    43
                </section>

                <!-- sublink box 44 -->
                <section id="contect_44_contect_box" class="section_sub_with all_sejmca8974 auto_margin_notwa">
                    44
                </section>

                <!-- sublink box 45 -->
                <section id="contect_45_contect_box" class="section_sub_with all_sejmca8974 auto_margin_notwa">
                    45
                </section>

                <!-- sublink box 46 -->
                <section id="contect_46_contect_box" class="section_sub_with all_sejmca8974 auto_margin_notwa">
                    46
                </section>

                <!-- sublink box 47 -->
                <section id="contect_47_contect_box" class="section_sub_with all_sejmca8974 auto_margin_notwa">
                    47
                </section>

                <!-- sublink box 48 -->
                <section id="contect_48_contect_box" class="section_sub_with all_sejmca8974 auto_margin_notwa">
                    48
                </section>

                <!-- sublink box 49 -->
                <section id="contect_49_contect_box" class="section_sub_with all_sejmca8974 auto_margin_notwa">
                    49
                </section>

                <!-- sublink box 50 -->
                <section id="contect_50_contect_box" class="section_sub_with all_sejmca8974 auto_margin_notwa">
                    50
                </section>

                <!-- sublink box 51 -->
                <section id="contect_51_contect_box" class="section_sub_with all_sejmca8974 auto_margin_notwa">
                    51
                </section>

                <!-- sublink box 52 -->
                <section id="contect_52_contect_box" class="section_sub_with all_sejmca8974 auto_margin_notwa">
                    52
                </section>

                <!-- sublink box 53 -->
                <section id="contect_53_contect_box" class="section_sub_with all_sejmca8974 auto_margin_notwa">
                    53
                </section>

                <!-- sublink box 54 -->
                <section id="contect_54_contect_box" class="section_sub_with all_sejmca8974 auto_margin_notwa">
                    54
                </section>

                <!-- sublink box 55 -->
                <section id="contect_55_contect_box" class="section_sub_with all_sejmca8974 auto_margin_notwa">
                    55
                </section>

                <!-- sublink box 56 -->
                <section id="contect_56_contect_box" class="section_sub_with all_sejmca8974 auto_margin_notwa">
                    56
                </section>

                <!-- sublink box 57 -->
                <section id="contect_57_contect_box" class="section_sub_with all_sejmca8974 auto_margin_notwa">
                    57
                </section>

                <!-- sublink box 58 -->
                <section id="contect_58_contect_box" class="section_sub_with all_sejmca8974 auto_margin_notwa">
                    58
                </section>

                <!-- sublink box 59 -->
                <section id="contect_59_contect_box" class="section_sub_with all_sejmca8974 auto_margin_notwa">
                    59
                </section>

                <!-- sublink box 60 -->
                <section id="contect_60_contect_box" class="section_sub_with all_sejmca8974 auto_margin_notwa">
                    60
                </section>


            </section>

        </section>



    </main>


    <!-- Image Preview -->
    <style>
        .qunike-img-preview {
            position: fixed;
            inset: 0;
            z-index: 999999;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 30px;
            background: rgba(0, 0, 0, 0.78);
            backdrop-filter: blur(3px);
        }

        .qunike-img-preview.qunike-img-preview-show {
            display: flex;
        }

        .qunike-img-preview-box {
            width: 90%;
            height: 90%;
            max-width: 1600px;
            background: #fff;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 70px rgba(0, 0, 0, 0.35);
            animation: qunikeImageOpen 0.3s ease forwards;
        }

        .qunike-img-preview-top {
            height: 58px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 22px;
            background: #fff;
            border-bottom: 1px solid #e8e8e8;
        }

        .qunike-img-preview-title {
            font-size: 17px;
            font-weight: 600;
            color: #20283a;
        }

        .qunike-img-preview-close {
            width: 38px;
            height: 38px;
            border: none;
            border-radius: 50%;
            background: #f2f3f5;
            color: #20283a;
            font-size: 28px;
            line-height: 1;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: 0.25s ease;
        }

        .qunike-img-preview-close:hover {
            background: #20283a;
            color: #fff;
            transform: rotate(90deg);
        }

        .qunike-img-preview-content {
            width: 100%;
            height: calc(100% - 58px);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 25px;
            box-sizing: border-box;
            background: #f8f9fa;
        }

        .qunike-img-preview-content img {
            max-width: 100%;
            max-height: 100%;
            width: auto;
            height: auto;
            object-fit: contain;
            border-radius: 8px;
            animation: qunikeImageZoom 0.35s ease forwards;
        }

        @keyframes qunikeImageOpen {
            from {
                opacity: 0;
                transform: scale(0.94) translateY(15px);
            }

            to {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
        }

        @keyframes qunikeImageZoom {
            from {
                opacity: 0;
                transform: scale(0.95);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }


        /* Mobile */

        @media (max-width: 768px) {

            .qunike-img-preview {
                padding: 15px;
            }

            .qunike-img-preview-box {
                width: 96%;
                height: 85%;
                border-radius: 14px;
            }

            .qunike-img-preview-top {
                height: 52px;
                padding: 0 15px;
            }

            .qunike-img-preview-title {
                font-size: 15px;
            }

            .qunike-img-preview-content {
                height: calc(100% - 52px);
                padding: 12px;
            }
        }
    </style>
    <div class="qunike-img-preview" id="qunikeImgPreview">
        <div class="qunike-img-preview-box">

            <div class="qunike-img-preview-top">
                <span class="qunike-img-preview-title">Image Preview</span>

                <button type="button"
                    class="qunike-img-preview-close"
                    onclick="qunikeCloseImage()">
                    ×
                </button>
            </div>

            <div class="qunike-img-preview-content">
                <img id="qunikePreviewImage" src="" alt="Preview">
            </div>

        </div>
    </div>
    <script>
        function qunikeOpenImage(img) {

            const previewBox = document.getElementById("qunikeImgPreview");
            const previewImage = document.getElementById("qunikePreviewImage");

            if (!previewBox || !previewImage) return;

            previewImage.src = img.currentSrc || img.src;
            previewImage.alt = img.alt || "Image Preview";

            previewBox.classList.add("qunike-img-preview-show");

            document.body.style.overflow = "hidden";
        }


        function qunikeCloseImage() {

            const previewBox = document.getElementById("qunikeImgPreview");
            const previewImage = document.getElementById("qunikePreviewImage");

            if (!previewBox) return;

            previewBox.classList.remove("qunike-img-preview-show");

            if (previewImage) {
                previewImage.src = "";
            }

            document.body.style.overflow = "";
        }


        /* Automatically make every image clickable */

        document.addEventListener("click", function(e) {

            const clickedImage = e.target.closest("img");

            if (!clickedImage) return;

            /*
               Preview ke andar wali image par dobara open nahi hoga
            */
            if (clickedImage.id === "qunikePreviewImage") return;

            qunikeOpenImage({
                currentSrc: clickedImage.currentSrc,
                src: clickedImage.src,
                alt: clickedImage.alt
            });

        });


        /* Outside click se close */

        document.getElementById("qunikeImgPreview").addEventListener("click", function(e) {

            if (e.target === this) {
                qunikeCloseImage();
            }

        });


        /* ESC se close */

        document.addEventListener("keydown", function(e) {

            if (e.key === "Escape") {
                qunikeCloseImage();
            }

        });
    </script>

    <script src="admin_access/admin.js"></script>
    <script src="admin_access/js/category.js"></script>
</body>

</html>