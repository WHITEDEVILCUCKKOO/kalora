<?php
// $mydb = mysqli_connect("host","user_name","password","database_name");

// $mydb = mysqli_connect("localhost", "shivamuniform_shivamuniform_user", "shivamuniform_db_user", "shivamuniform_shivamuniform_db");
$mydb = mysqli_connect("localhost", "root", "", "kalora_db");

if (!$mydb) {
    die("Database connection failed: " . mysqli_connect_error());
}
