<?php
include ('db_connection.php');
session_start();
error_reporting(0);

if (isset($_GET['id']) && is_numeric($_GET['id'])) { // Ensure 'id' is a valid numeric value
    $shop_id = $_GET['id'];
    $delete_query = "DELETE FROM shop_details WHERE shop_id = ?";

    $stmt = mysqli_prepare($con, $delete_query);
    mysqli_stmt_bind_param($stmt, 'i', $shop_id);

    if (mysqli_stmt_execute($stmt)) {
        $statusMsg = "Shop deleted successfully!";
        $alertStyle = "alert alert-success";
    } else {
        $statusMsg = "An error occurred while deleting shop.";
        $alertStyle = "alert alert-danger";
    }
    mysqli_stmt_close($stmt);
} else {
    $statusMsg = "Invalid Request!";
    $alertStyle = "alert alert-danger";
}

mysqli_close($con);
?>

<!-- JavaScript to show alert and redirect after OK -->
<script>
    alert("<?php echo $statusMsg; ?>");
    window.location.href = 'view_details.php';
</script>