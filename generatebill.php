<?php
include ('db_connection.php');
session_start();
error_reporting(0);

// Generate a transaction ID
$transaction_id = substr(time(), -3) . rand(100, 999);


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generate Bill</title>
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
                    <h2 class="mb-4 text-center font-weight-bold"><i class="fas fa-file-invoice"></i> Generate Bill</h2>

                    <!-- Generate bill form -->
                    <form id="generateBillForm" action="generate_bill_backend.php" method="post">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="customerName" class="form-label"><i class="fas fa-user"></i> Customer
                                        Name</label>
                                    <!-- Use a select dropdown for customer name -->
                                    <select class="form-select" id="customerName" name="customerName" required>
                                        <option value="">Select Customer</option>
                                        <?php
                                        // Fetch existing customers associated with the current user from the database
                                        $user_id = $_SESSION['user_id'];
                                        $customer_query = mysqli_query($con, "SELECT custname, pno FROM customer WHERE user_id = $user_id");
                                        while ($row = mysqli_fetch_assoc($customer_query)) {
                                            $customerName = $row['custname'];
                                            $contactNumber = $row['pno'];
                                            // Add data-contact attribute to store contact number
                                            echo "<option value='$customerName' data-contact='$contactNumber'>$customerName</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="transaction_id" class="form-label"><i class="fas fa-id-card"></i> Bill
                                        ID</label>
                                    <!-- Generate a transaction ID -->
                                    <input type="text" class="form-control" id="transaction_id" name="transaction_id"
                                        value="<?php echo $transaction_id; ?>" required readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="contactNumber" class="form-label"><i class="fas fa-phone"></i> Contact
                                        Number</label>
                                    <!-- Contact number input field will be dynamically populated -->
                                    <input type="tel" class="form-control" id="contactNumber" name="contactNumber"
                                        placeholder="Contact Number" required readonly>
                                </div>
                                <div class="mb-3">
                                    <label for="product" class="form-label"><i class="fas fa-shopping-cart"></i>
                                        Product</label>
                                    <!-- Use a select dropdown for products -->
                                    <select class="form-select" id="product" name="product[]" required multiple>
                                        <!-- Options will be dynamically populated using JavaScript -->
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Quantity inputs will be dynamically generated here -->
                        <div id="quantityInputs"></div>

                        <div class="text-center mt-4">
                            <button type="submit" name="submit" class="btn btn-primary"
                                style="background-color: #333; border: none; border-radius: 5px; font-weight: bold;"><i
                                    class="fas fa-check"></i> Generate Bill</button>
                        </div>
                    </form>

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
            // Populate product options dynamically using AJAX
            $.ajax({
                url: 'get_available_products.php',
                type: 'GET',
                success: function (response) {
                    var optionsHtml = '';
                    response.forEach(function (product) {
                        optionsHtml += '<option value="' + product.product_id + '[' + product.product_unit_id + ']">' + product.product_name + ' (' + product.unit_of_measurement + ')</option>';
                    });
                    $('#product').html(optionsHtml);
                }
            });

            // Handle product selection
            $('#product').change(function () {
                var selectedProducts = $(this).val();
                var quantityInputs = '';

                selectedProducts.forEach(function (product) {
                    var [product_id, unit_id] = product.split('[');
                    unit_id = unit_id.slice(0, -1);

                    // Fetch the product name for the current product
                    var productName = $('#product option[value="' + product + '"]').text();

                    quantityInputs += '<div class="mb-3">';
                    quantityInputs += '<label for="quantity[' + product_id + '][' + unit_id + ']" class="form-label">Quantity for ' + productName + '</label>';
                    quantityInputs += '<input type="number" class="form-control" id="quantity[' + product_id + '][' + unit_id + ']" name="quantity[' + product_id + '][' + unit_id + ']" placeholder="Enter Quantity" required min="1">';
                    quantityInputs += '</div>';
                });

                $('#quantityInputs').html(quantityInputs);
            });

            // Populate contact number when customer is selected
            $('#customerName').change(function () {
                var selectedContact = $(this).find(':selected').data('contact');
                $('#contactNumber').val(selectedContact);
            });
        });
    </script>
</body>

</html>