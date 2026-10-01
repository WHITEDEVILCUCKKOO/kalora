<?php

/* =========================================================
   GET GLOBAL INFO
========================================================= */

function get_global_info($mydb)
{
    $query = mysqli_query(
        $mydb,
        "SELECT *
         FROM global_info
         WHERE globle_info_id = 1
         LIMIT 1"
    );

    if (!$query) {
        die("Global Info Query Error: " . mysqli_error($mydb));
    }

    return mysqli_fetch_assoc($query);
}


/* =========================================================
   UPDATE GLOBAL INFO
========================================================= */

function update_global_info($mydb)
{
    $globle_info_id = 1;


    /* =====================================================
       GET OLD DATA
    ===================================================== */

    $old_query = mysqli_query(
        $mydb,
        "SELECT *
         FROM global_info
         WHERE globle_info_id = 1
         LIMIT 1"
    );

    if (!$old_query) {
        return [
            'status' => false,
            'message' => 'Unable to get current global information.'
        ];
    }

    $old_data = mysqli_fetch_assoc($old_query);

    if (!$old_data) {
        return [
            'status' => false,
            'message' => 'Global information record not found.'
        ];
    }


    /* =====================================================
       OLD IMAGE VALUES
    ===================================================== */

    $facion_icon = $old_data['facion_icon'];
    $logo        = $old_data['logo'];


    /* =====================================================
       FORM VALUES
    ===================================================== */

    $globle_call_phone = trim(
        $_POST['globle_call_phone'] ?? ''
    );

    $globle_whatsapp = trim(
        $_POST['globle_whatsapp'] ?? ''
    );

    $footer_address = trim(
        $_POST['footer_address'] ?? ''
    );

    $footer_email_1 = trim(
        $_POST['footer_email_1'] ?? ''
    );

    $footer_email_2 = trim(
        $_POST['footer_email_2'] ?? ''
    );

    $footer_phone_1 = trim(
        $_POST['footer_phone_1'] ?? ''
    );

    $footer_phone_2 = trim(
        $_POST['footer_phone_2'] ?? ''
    );

    $globle_instagram = trim(
        $_POST['globle_instagram'] ?? ''
    );

    $globle_yt = trim(
        $_POST['globle_yt'] ?? ''
    );

    $globle_linkedin = trim(
        $_POST['globle_linkedin'] ?? ''
    );

    $map_link = trim(
        $_POST['map_link'] ?? ''
    );


    /* =====================================================
       IMAGE DIRECTORY
    ===================================================== */

    $upload_dir = __DIR__ . '/../../assets/logos/';


    if (!is_dir($upload_dir)) {

        if (!mkdir($upload_dir, 0777, true)) {

            return [
                'status' => false,
                'message' => 'Image folder could not be created.'
            ];
        }
    }


    /* =====================================================
       FACION ICON
    ===================================================== */

    if (
        isset($_FILES['facion_icon']) &&
        $_FILES['facion_icon']['error'] === UPLOAD_ERR_OK
    ) {

        $file_name = $_FILES['facion_icon']['name'];
        $tmp_name  = $_FILES['facion_icon']['tmp_name'];

        $extension = strtolower(
            pathinfo($file_name, PATHINFO_EXTENSION)
        );

        $allowed_extensions = [
            'jpg',
            'jpeg',
            'png',
            'webp',
            'ico'
        ];

        if (!in_array($extension, $allowed_extensions)) {

            return [
                'status' => false,
                'message' => 'Invalid Facion Icon file type.'
            ];
        }

        $new_file_name =
            'facion_icon_' .
            date('YmdHis') .
            '_' .
            bin2hex(random_bytes(3)) .
            '.' .
            $extension;

        $destination = $upload_dir . $new_file_name;

        if (!move_uploaded_file($tmp_name, $destination)) {

            return [
                'status' => false,
                'message' => 'Facion Icon upload failed.'
            ];
        }

        $facion_icon = $new_file_name;
    }


    /* =====================================================
       LOGO
    ===================================================== */

    if (
        isset($_FILES['logo']) &&
        $_FILES['logo']['error'] === UPLOAD_ERR_OK
    ) {

        $file_name = $_FILES['logo']['name'];
        $tmp_name  = $_FILES['logo']['tmp_name'];

        $extension = strtolower(
            pathinfo($file_name, PATHINFO_EXTENSION)
        );

        $allowed_extensions = [
            'jpg',
            'jpeg',
            'png',
            'webp',
            'svg'
        ];

        if (!in_array($extension, $allowed_extensions)) {

            return [
                'status' => false,
                'message' => 'Invalid Logo file type.'
            ];
        }

        $new_file_name =
            'logo_' .
            date('YmdHis') .
            '_' .
            bin2hex(random_bytes(3)) .
            '.' .
            $extension;

        $destination = $upload_dir . $new_file_name;

        if (!move_uploaded_file($tmp_name, $destination)) {

            return [
                'status' => false,
                'message' => 'Logo upload failed.'
            ];
        }

        $logo = $new_file_name;
    }


    /* =====================================================
       UPDATE DATABASE
    ===================================================== */

    $update_query = "
        UPDATE global_info
        SET
            facion_icon = ?,
            logo = ?,
            globle_call_phone = ?,
            globle_whatsapp = ?,
            footer_address = ?,
            footer_email_1 = ?,
            footer_email_2 = ?,
            footer_phone_1 = ?,
            footer_phone_2 = ?,
            globle_instagram = ?,
            globle_yt = ?,
            globle_linkedin = ?,
            map_link = ?

        WHERE globle_info_id = ?
    ";


    $stmt = mysqli_prepare(
        $mydb,
        $update_query
    );


    if (!$stmt) {

        return [
            'status' => false,
            'message' => 'Database prepare error: ' . mysqli_error($mydb)
        ];
    }


    mysqli_stmt_bind_param(
        $stmt,
        "sssssssssssssi",

        $facion_icon,
        $logo,

        $globle_call_phone,
        $globle_whatsapp,

        $footer_address,

        $footer_email_1,
        $footer_email_2,

        $footer_phone_1,
        $footer_phone_2,

        $globle_instagram,
        $globle_yt,
        $globle_linkedin,

        $map_link,

        $globle_info_id
    );


    if (!mysqli_stmt_execute($stmt)) {

        $error = mysqli_stmt_error($stmt);

        mysqli_stmt_close($stmt);

        return [
            'status' => false,
            'message' => 'Database update failed: ' . $error
        ];
    }


    mysqli_stmt_close($stmt);


    return [
        'status' => true,
        'message' => 'Global information updated successfully.'
    ];
}

?>