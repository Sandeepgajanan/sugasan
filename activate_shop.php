<?php
include ('db_connection.php');
session_start();
error_reporting(0);

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate and sanitize input
    $shop_id = (int) $_POST['shop_id']; // Assuming shop_id is an integer

    // Check if either 'activate' or 'deactivate' is set in the POST data
    if (isset($_POST['activate']) || isset($_POST['deactivate'])) {
        // Use prepared statements for better security
        $stmt = mysqli_prepare($con, "UPDATE shop_details SET active = ? WHERE shop_id = ?");
        if ($stmt) {
            // Determine the value of 'active' based on which button was clicked
            $active = isset($_POST['activate']) ? 1 : 0;

            // Bind parameters and execute the statement
            mysqli_stmt_bind_param($stmt, "ii", $active, $shop_id);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);

            // Redirect with success message
            header('Location: view_details.php?success=1');
            exit(); // Stop script execution after redirect
        } else {
            // Error handling for query preparation
            header('Location: view_details.php?error=1');
            exit(); // Stop script execution after redirect
        }
    } else {
        // Invalid POST request
        header('Location: view_details.php?error=1');
        exit(); // Stop script execution after redirect
    }
} else {
    // Redirect if accessed directly without a POST request
    header('Location: view_details.php');
    exit(); // Stop script execution after redirect
}
?>