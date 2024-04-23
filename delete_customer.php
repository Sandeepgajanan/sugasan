<?php
include ('db_connection.php');
session_start();
error_reporting(0);

if (isset($_GET['id'])) {
    // Sanitize input
    $cust_id = (int) $_GET['id'];
    $delete_query = "DELETE FROM customer WHERE id =?";

    $stmt = mysqli_prepare($con, $delete_query);
    mysqli_stmt_bind_param($stmt, 'i', $cust_id);

    if (mysqli_stmt_execute($stmt)) {
        $statusMsg = "Customer deleted successfully!";
        $alertStyle = "alert alert-success";
    } else {
        $statusMsg = "An error occurred while deleting Customer. Please try again later.";
        $alertStyle = "alert alert-danger";
        // Log or display detailed error message for debugging
        error_log("Error deleting customer: " . mysqli_error($con));
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
    window.location.href = 'view_customers.php';
</script>