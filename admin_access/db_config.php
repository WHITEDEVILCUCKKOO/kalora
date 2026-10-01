<?php

$connections = [
    [
        "host" => "localhost",
        "user" => "root",
        "password" => "",
        "database" => "kalora_db"
    ],
    [
        "host" => "localhost",
        "user" => "daurp0duction_kalora_user",
        "password" => "kalora@@1327",
        "database" => "daurp0duction_kalora_db"
    ]
];

$mydb = false;

$errors = [];

foreach ($connections as $config) {

    $connection = @mysqli_connect(
        $config["host"],
        $config["user"],
        $config["password"],
        $config["database"]
    );

    if ($connection) {
        $mydb = $connection;
        break;
    }

    $errors[] = mysqli_connect_error();
}

if (!$mydb) {
    die("Database connection failed.<br><br>" .
        implode("<br>", $errors));
}

// Optional: UTF-8 support
mysqli_set_charset($mydb, "utf8mb4");
