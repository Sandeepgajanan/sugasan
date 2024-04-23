<?php
include ('db_connection.php');
session_start();
error_reporting(0);

if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_GET['id'])) {
    // Sanitize input
    $product_id = (int) $_GET['id'];

    // Delete the associated units from product_units table first
    $delete_units_query = "DELETE FROM product_units WHERE product_id=?";
    $stmt1 = mysqli_prepare($con, $delete_units_query);
    mysqli_stmt_bind_param($stmt1, "i", $product_id);

    if (mysqli_stmt_execute($stmt1)) {
        // Delete the product from products table
        $delete_query = "DELETE FROM products WHERE product_id=?";
        $stmt2 = mysqli_prepare($con, $delete_query);
        mysqli_stmt_bind_param($stmt2, "i", $product_id);

        if (mysqli_stmt_execute($stmt2)) {
            $statusMsg = "Product and associated units deleted successfully!";
            $alertStyle = "alert alert-success";
        } else {
            $statusMsg = "Error deleting product: " . mysqli_error($con);
            $alertStyle = "alert alert-danger";
        }

        mysqli_stmt_close($stmt2);
    } else {
        $statusMsg = "Error deleting product units: " . mysqli_error($con);
        $alertStyle = "alert alert-danger";
    }

    mysqli_stmt_close($stmt1);
} else {
    $statusMsg = "Invalid Request!";
    $alertStyle = "alert alert-danger";
}

mysqli_close($con);
?>

<script>
    alert("<?php echo $statusMsg; ?>");
    window.location.href = 'viewproduct.php';
</script>