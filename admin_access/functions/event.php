<?php

/* =========================================================
   KALORA EVENT FUNCTIONS
   ========================================================= */


/* ---------------------------------------------------------
   EVENT IMAGE DIRECTORY
--------------------------------------------------------- */
function kalora_event_image_directory()
{
    return dirname(__DIR__, 2) . '/assets/events/';
}


/* ---------------------------------------------------------
   ADD EVENT
--------------------------------------------------------- */
function add_kalora_event($mydb, $data, $file)
{
    $event_name = trim($data['event_name'] ?? '');
    $event_slug = trim($data['event_slug'] ?? '');
    $start_at = trim($data['start_at'] ?? '');
    $end_at = trim($data['end_at'] ?? '');
    $event_status = $data['event_status'] ?? 'Scheduled';
    $event_link = trim($data['event_link'] ?? '');
    $display_order = (int)($data['display_order'] ?? 0);

    if ($event_name === '') {
        return [
            'status' => false,
            'message' => 'Event name is required.'
        ];
    }

    if ($start_at === '' || $end_at === '') {
        return [
            'status' => false,
            'message' => 'Start date/time and end date/time are required.'
        ];
    }

    if (strtotime($end_at) <= strtotime($start_at)) {
        return [
            'status' => false,
            'message' => 'End date/time must be greater than start date/time.'
        ];
    }


    /* Generate slug automatically */
    if ($event_slug === '') {

        $event_slug = strtolower($event_name);

        $event_slug = preg_replace(
            '/[^a-z0-9]+/i',
            '-',
            $event_slug
        );

        $event_slug = trim($event_slug, '-');

    } else {

        $event_slug = strtolower($event_slug);

        $event_slug = preg_replace(
            '/[^a-z0-9-]+/i',
            '-',
            $event_slug
        );

        $event_slug = trim($event_slug, '-');
    }


    /* Check slug */
    $slug_check = mysqli_prepare(
        $mydb,
        "SELECT event_id FROM events WHERE event_slug = ? LIMIT 1"
    );

    mysqli_stmt_bind_param(
        $slug_check,
        "s",
        $event_slug
    );

    mysqli_stmt_execute($slug_check);

    $slug_result = mysqli_stmt_get_result($slug_check);

    if (mysqli_num_rows($slug_result) > 0) {

        mysqli_stmt_close($slug_check);

        return [
            'status' => false,
            'message' => 'This event slug already exists.'
        ];
    }

    mysqli_stmt_close($slug_check);


    /* Image */
    if (
        !isset($file['event_image']) ||
        $file['event_image']['error'] !== UPLOAD_ERR_OK
    ) {
        return [
            'status' => false,
            'message' => 'Please upload an event image.'
        ];
    }


    $allowed_extensions = [
        'jpg',
        'jpeg',
        'png',
        'webp'
    ];

    $extension = strtolower(
        pathinfo(
            $file['event_image']['name'],
            PATHINFO_EXTENSION
        )
    );

    if (!in_array($extension, $allowed_extensions, true)) {

        return [
            'status' => false,
            'message' => 'Only JPG, JPEG, PNG and WEBP images are allowed.'
        ];
    }


    $upload_directory = kalora_event_image_directory();


    if (!is_dir($upload_directory)) {

        mkdir(
            $upload_directory,
            0755,
            true
        );
    }


    $image_name =
        'event_' .
        time() .
        '_' .
        bin2hex(random_bytes(4)) .
        '.' .
        $extension;


    if (
        !move_uploaded_file(
            $file['event_image']['tmp_name'],
            $upload_directory . $image_name
        )
    ) {

        return [
            'status' => false,
            'message' => 'Unable to upload event image.'
        ];
    }


    $created_at = time();


    $query = mysqli_prepare(
        $mydb,
        "INSERT INTO events
        (
            event_name,
            event_slug,
            event_image,
            start_at,
            end_at,
            event_status,
            event_link,
            display_order,
            created_at
        )
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)"
    );


    mysqli_stmt_bind_param(
        $query,
        "sssssssii",
        $event_name,
        $event_slug,
        $image_name,
        $start_at,
        $end_at,
        $event_status,
        $event_link,
        $display_order,
        $created_at
    );


    if (mysqli_stmt_execute($query)) {

        mysqli_stmt_close($query);

        return [
            'status' => true,
            'message' => 'Event added successfully.'
        ];
    }


    mysqli_stmt_close($query);


    /* Remove image if database insert failed */
    if (
        file_exists(
            $upload_directory . $image_name
        )
    ) {
        unlink(
            $upload_directory . $image_name
        );
    }


    return [
        'status' => false,
        'message' => 'Unable to add event.'
    ];
}


/* ---------------------------------------------------------
   UPDATE EVENT
--------------------------------------------------------- */
function update_kalora_event($mydb, $data, $file)
{
    $event_id = (int)($data['event_id'] ?? 0);

    $event_name = trim($data['event_name'] ?? '');
    $event_slug = trim($data['event_slug'] ?? '');
    $start_at = trim($data['start_at'] ?? '');
    $end_at = trim($data['end_at'] ?? '');
    $event_status = $data['event_status'] ?? 'Scheduled';
    $event_link = trim($data['event_link'] ?? '');
    $display_order = (int)($data['display_order'] ?? 0);


    if ($event_id <= 0) {
        return [
            'status' => false,
            'message' => 'Invalid event.'
        ];
    }


    if ($event_name === '') {
        return [
            'status' => false,
            'message' => 'Event name is required.'
        ];
    }


    if (
        $start_at === '' ||
        $end_at === ''
    ) {
        return [
            'status' => false,
            'message' => 'Start and end date/time are required.'
        ];
    }


    if (
        strtotime($end_at) <=
        strtotime($start_at)
    ) {
        return [
            'status' => false,
            'message' => 'End date/time must be greater than start date/time.'
        ];
    }


    if ($event_slug === '') {

        $event_slug = strtolower($event_name);

        $event_slug = preg_replace(
            '/[^a-z0-9]+/i',
            '-',
            $event_slug
        );

        $event_slug = trim($event_slug, '-');

    } else {

        $event_slug = strtolower($event_slug);

        $event_slug = preg_replace(
            '/[^a-z0-9-]+/i',
            '-',
            $event_slug
        );

        $event_slug = trim($event_slug, '-');
    }


    /* Get old image */
    $old_query = mysqli_prepare(
        $mydb,
        "SELECT event_image
         FROM events
         WHERE event_id = ?
         LIMIT 1"
    );

    mysqli_stmt_bind_param(
        $old_query,
        "i",
        $event_id
    );

    mysqli_stmt_execute($old_query);

    $old_result =
        mysqli_stmt_get_result($old_query);

    $old_event =
        mysqli_fetch_assoc($old_result);

    mysqli_stmt_close($old_query);


    if (!$old_event) {

        return [
            'status' => false,
            'message' => 'Event not found.'
        ];
    }


    $image_name =
        $old_event['event_image'];


    /* New image */
    if (
        isset($file['event_image']) &&
        $file['event_image']['error'] === UPLOAD_ERR_OK
    ) {

        $extension = strtolower(
            pathinfo(
                $file['event_image']['name'],
                PATHINFO_EXTENSION
            )
        );


        $allowed_extensions = [
            'jpg',
            'jpeg',
            'png',
            'webp'
        ];


        if (
            !in_array(
                $extension,
                $allowed_extensions,
                true
            )
        ) {

            return [
                'status' => false,
                'message' => 'Invalid image format.'
            ];
        }


        $upload_directory =
            kalora_event_image_directory();


        if (!is_dir($upload_directory)) {

            mkdir(
                $upload_directory,
                0755,
                true
            );
        }


        $new_image_name =
            'event_' .
            time() .
            '_' .
            bin2hex(random_bytes(4)) .
            '.' .
            $extension;


        if (
            !move_uploaded_file(
                $file['event_image']['tmp_name'],
                $upload_directory .
                $new_image_name
            )
        ) {

            return [
                'status' => false,
                'message' => 'Unable to upload new image.'
            ];
        }


        /* Delete old image */
        if (
            $image_name !== '' &&
            file_exists(
                $upload_directory .
                $image_name
            )
        ) {

            unlink(
                $upload_directory .
                $image_name
            );
        }


        $image_name =
            $new_image_name;
    }


    $updated_at = time();


    $query = mysqli_prepare(
        $mydb,
        "UPDATE events SET
            event_name = ?,
            event_slug = ?,
            event_image = ?,
            start_at = ?,
            end_at = ?,
            event_status = ?,
            event_link = ?,
            display_order = ?,
            updated_at = ?
         WHERE event_id = ?"
    );


    mysqli_stmt_bind_param(
        $query,
        "sssssssiii",
        $event_name,
        $event_slug,
        $image_name,
        $start_at,
        $end_at,
        $event_status,
        $event_link,
        $display_order,
        $updated_at,
        $event_id
    );


    if (mysqli_stmt_execute($query)) {

        mysqli_stmt_close($query);

        return [
            'status' => true,
            'message' => 'Event updated successfully.'
        ];
    }


    mysqli_stmt_close($query);


    return [
        'status' => false,
        'message' => 'Unable to update event.'
    ];
}


/* ---------------------------------------------------------
   DELETE EVENT
--------------------------------------------------------- */
function delete_kalora_event($mydb, $event_id)
{
    $event_id = (int)$event_id;


    if ($event_id <= 0) {

        return [
            'status' => false,
            'message' => 'Invalid event.'
        ];
    }


    $query = mysqli_prepare(
        $mydb,
        "SELECT event_image
         FROM events
         WHERE event_id = ?
         LIMIT 1"
    );


    mysqli_stmt_bind_param(
        $query,
        "i",
        $event_id
    );


    mysqli_stmt_execute($query);


    $result =
        mysqli_stmt_get_result($query);


    $event =
        mysqli_fetch_assoc($result);


    mysqli_stmt_close($query);


    if (!$event) {

        return [
            'status' => false,
            'message' => 'Event not found.'
        ];
    }


    $delete_query = mysqli_prepare(
        $mydb,
        "DELETE FROM events WHERE event_id = ?"
    );


    mysqli_stmt_bind_param(
        $delete_query,
        "i",
        $event_id
    );


    if (
        !mysqli_stmt_execute(
            $delete_query
        )
    ) {

        mysqli_stmt_close($delete_query);

        return [
            'status' => false,
            'message' => 'Unable to delete event.'
        ];
    }


    mysqli_stmt_close($delete_query);


    /* Delete image */
    $image_path =
        kalora_event_image_directory() .
        $event['event_image'];


    if (
        $event['event_image'] !== '' &&
        file_exists($image_path)
    ) {

        unlink($image_path);
    }


    return [
        'status' => true,
        'message' => 'Event deleted successfully.'
    ];
}


/* ---------------------------------------------------------
   AUTOMATIC STATUS UPDATE
--------------------------------------------------------- */
function update_kalora_event_status($mydb)
{
    mysqli_query(
        $mydb,
        "UPDATE events
         SET event_status = 'Active'
         WHERE start_at <= NOW()
         AND end_at > NOW()
         AND event_status != 'Inactive'"
    );


    mysqli_query(
        $mydb,
        "UPDATE events
         SET event_status = 'Expired'
         WHERE end_at <= NOW()
         AND event_status = 'Active'"
    );


    mysqli_query(
        $mydb,
        "UPDATE events
         SET event_status = 'Scheduled'
         WHERE start_at > NOW()
         AND event_status != 'Inactive'"
    );
}


/* ---------------------------------------------------------
   GET ALL EVENTS
   ACTIVE FIRST
--------------------------------------------------------- */
function get_kalora_events($mydb)
{
    $query = mysqli_query(
        $mydb,
        "SELECT *
         FROM events
         ORDER BY
            CASE
                WHEN event_status = 'Active' THEN 1
                WHEN event_status = 'Scheduled' THEN 2
                WHEN event_status = 'Inactive' THEN 3
                WHEN event_status = 'Expired' THEN 4
                ELSE 5
            END,
            start_at ASC,
            event_id DESC"
    );


    $events = [];


    if ($query) {

        while (
            $row = mysqli_fetch_assoc($query)
        ) {

            $events[] = $row;
        }
    }


    return $events;
}