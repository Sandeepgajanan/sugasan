<?php
include ('db_connection.php');
session_start();
error_reporting(0);

// Get the user ID from the session
$user_id = $_SESSION['user_id'];

// Prepare the SQL query with a WHERE clause to filter products by user ID
$query = "SELECT * FROM products WHERE user_id = ?";
$stmt = mysqli_prepare($con, $query);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Products</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/css/bootstrap.min.css"
        crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        crossorigin="anonymous">
</head>

<body>
    <?php include 'header.php'; ?>

    <div class="container-fluid">
        <div class="row">
            <?php include 'sidebar.php'; ?>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 mb-4">
                <div class="container p-4">
                    <h2 class="mb-4 text-center font-weight-bold"><i class="fas fa-list"></i> View Products</h2>

                    <!-- Product table -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="bg-light">
                                <tr>
                                    <th scope="col">Product Name</th>
                                    <th scope="col">HSN Number</th>
                                    <th scope="col">Unit</th>
                                    <th scope="col">Price</th>
                                    <th scope="col">CGST %</th>
                                    <th scope="col">SGST %</th>
                                    <th scope="col">CGST</th>
                                    <th scope="col">SGST</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                while ($row = mysqli_fetch_assoc($result)) {
                                    $product_id = $row['product_id'];
                                    $unit_query = mysqli_query($con, "SELECT * FROM product_units WHERE product_id=$product_id");

                                    // Get the number of units for this product
                                    $num_units = mysqli_num_rows($unit_query);
                                    $unit_counter = 0;

                                    while ($unit_data = mysqli_fetch_assoc($unit_query)) {
                                        $price = $unit_data['price'];
                                        $cgst = $unit_data['cgst'];
                                        $sgst = $unit_data['sgst'];
                                        $vcgst = ($price * $cgst) / 100;
                                        $vsgst = ($price * $sgst) / 100;
                                        echo '<tr>';

                                        // Only display Product Name and HSN Number for the first row
                                        if ($unit_counter == 0) {
                                            echo '<td rowspan="' . $num_units . '">' . $row['product_name'] . '</td>';
                                            echo '<td rowspan="' . $num_units . '">' . $row['hsn_number'] . '</td>';
                                        }

                                        echo '<td>' . $unit_data['unit_of_measurement'] . '</td>';
                                        echo '<td>' . $unit_data['price'] . '</td>';
                                        echo '<td>' . $cgst . '%' . '</td>';
                                        echo '<td>' . $unit_data['sgst'] . '</td>';
                                        echo '<td>' . $vcgst . '</td>';
                                        echo '<td>' . $vsgst . '</td>';

                                        // Only display Actions for the first row
                                        if ($unit_counter == 0) {
                                            echo '<td rowspan="' . $num_units . '" class="text-center">
                                                    <a href="editproduct.php?id=' . $row['product_id'] . '" class="btn btn-primary btn-sm"><i class="fas fa-edit"></i> Edit</a>
                                                    <a href="delete_product.php?id=' . $row['product_id'] . '" class="btn btn-danger btn-sm delete-button" data-product-id="' . $row['product_id'] . '"><i class="fas fa-trash"></i> Delete</a>
                                                </td>';
                                        }

                                        echo '</tr>';
                                        $unit_counter++;
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
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
    <script>
        $(document).ready(function () {
            $('.delete-button').click(function (event) {
                event.preventDefault();
                var productId = $(this).data('product-id');
                var confirmDelete = confirm("Are you sure you want to delete this product?");

                if (confirmDelete) {
                    window.location.href = 'delete_product.php?id=' + productId;
                }
            });
        });
    </script>
</body>

</html>