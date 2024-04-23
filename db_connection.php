<?php
// Establishing connection to the database
$con = mysqli_connect("localhost", "root", "", "sugasan");

// Checking the connection
if (!$con) {
    // If connection fails, display an error message and terminate script execution
    die("Connection failed: " . mysqli_connect_error());
}

// Optional: Enable mysqli exceptions for better error handling
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
?>