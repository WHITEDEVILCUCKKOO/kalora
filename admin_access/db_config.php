<?php

// Detect local development environment
$isLocal = in_array($_SERVER['SERVER_NAME'] ?? '', [
    'localhost',
    '127.0.0.1'
]);

if ($isLocal) {

    // Local XAMPP / WAMP
    $host = "localhost";
    $user = "root";
    $password = "";
    $database = "kalora_db";

} else {

    // Live / Hosting
    $host = "localhost";
    $user = "daurp0duction_kalora_user";
    $password = "YOUR_DATABASE_PASSWORD";
    $database = "daurp0duction_kalora_db";
}

$mydb = mysqli_connect(
    $host,
    $user,
    $password,
    $database
);

if (!$mydb) {
    die(
        "Database connection failed.<br>" .
        "Host: " . htmlspecialchars($host) . "<br>" .
        "Database: " . htmlspecialchars($database) . "<br>" .
        "Error: " . htmlspecialchars(mysqli_connect_error())
    );
}

mysqli_set_charset($mydb, "utf8mb4");
