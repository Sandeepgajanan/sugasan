<?php
include ('db_connection.php');
session_start();
error_reporting(0);

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect or handle the case where the user is not logged in
    header('Location: login.php');
    exit();
}

// Get the user ID from the session
$user_id = $_SESSION['user_id'];

// Query to fetch products associated with the current user
$product_query = "SELECT products.product_id, products.product_name, product_units.unit_of_measurement, product_units.product_unit_id
                    FROM products 
                    LEFT JOIN product_units 
                    ON products.product_id = product_units.product_id
                    WHERE products.user_id = $user_id";

$products_result = mysqli_query($con, $product_query);

$available_products = array();

while ($row = mysqli_fetch_assoc($products_result)) {
    $available_products[] = array(
        'product_id' => $row['product_id'],
        'product_name' => $row['product_name'],
        'unit_of_measurement' => $row['unit_of_measurement'],
        'product_unit_id' => $row['product_unit_id'],
    );
}

// Set response header to JSON
header('Content-Type: application/json');

// Encode the array as JSON and echo it
echo json_encode($available_products);
?>