<?php
include ('db_connection.php');
session_start();

// Get the user ID from the session if available, otherwise redirect to login
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    header('Location: login.php');
    exit;
}

// Initialize counts to 0
$shop_count = 0;
$product_count = 0;
$customer_count = 0;

// Function to safely escape data for SQL statements
function escape($con, $value)
{
    return mysqli_real_escape_string($con, $value);
}

// Fetch counts related to the particular user
$shops_query = mysqli_query($con, "SELECT COUNT(*) as shop_count FROM shop_details WHERE user_id = $user_id");
if ($shops_query && mysqli_num_rows($shops_query) > 0) {
    $shop_count_row = mysqli_fetch_assoc($shops_query);
    $shop_count = (int) $shop_count_row['shop_count'];
}

$products_query = mysqli_query($con, "SELECT COUNT(*) as product_count FROM products WHERE user_id = $user_id");
if ($products_query && mysqli_num_rows($products_query) > 0) {
    $product_count_row = mysqli_fetch_assoc($products_query);
    $product_count = (int) $product_count_row['product_count'];
}

$customers_query = mysqli_query($con, "SELECT COUNT(*) as customer_count FROM customer WHERE user_id = $user_id");
if ($customers_query && mysqli_num_rows($customers_query) > 0) {
    $customer_count_row = mysqli_fetch_assoc($customers_query);
    $customer_count = (int) $customer_count_row['customer_count'];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/css/bootstrap.min.css"
        crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        crossorigin="anonymous">
    <style>
        /* Add your custom styles here */
    </style>
</head>

<body>
    <?php include 'header.php'; ?>

    <div class="container-fluid">
        <div class="row">
            <?php include 'sidebar.php'; ?>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 mb-4">
                <div class="container mt-5 text-center">
                    <h2 class="mb-4">Your Business Made Easy</h2>
                    <div class="row justify-content-center">
                        <div class="col-md-4">
                            <div class="card text-white bg-info mb-3 custom-card">
                                <div class="card-body">
                                    <h5 class="card-title">Shops</h5>
                                    <p class="card-text"><?php echo $shop_count; ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card text-white bg-primary mb-3 custom-card">
                                <div class="card-body">
                                    <h5 class="card-title">Products</h5>
                                    <p class="card-text"><?php echo $product_count; ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card text-white bg-success mb-3 custom-card">
                                <div class="card-body">
                                    <h5 class="card-title">Customers</h5>
                                    <p class="card-text"><?php echo $customer_count; ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>

            <?php include 'footer.php'; ?>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.10.2/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/js/bootstrap.min.js"
        crossorigin="anonymous"></script>

</body>

</html>