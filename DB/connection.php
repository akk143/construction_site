<?php
$dbconid = mysqli_connect("localhost", "root", "", "construction_db");

if (!$dbconid) {
    die("Database connection error: " . mysqli_connect_error());
}

// Set charset to UTF-8 to handle special characters
mysqli_set_charset($dbconid, "utf8");

?>
 