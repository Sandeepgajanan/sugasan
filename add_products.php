<?php
include ('db_connection.php');
session_start();
error_reporting(0);

if (isset($_POST["submit"])) {
    // Sanitize input data
    $product_name = mysqli_real_escape_string($con, $_POST["productName"]);
    $hsn_number = mysqli_real_escape_string($con, $_POST["hsn"]);
    $user_id = $_SESSION['user_id'];

    // Check if the product already exists
    $check_query = "SELECT * FROM products WHERE product_name='$product_name' OR hsn_number='$hsn_number'";
    $result = mysqli_query($con, $check_query);

    if (mysqli_num_rows($result) > 0) {
        echo '<script>alert("Product with the same name and HSN number already exists.");</script>';
    } else {
        // Insert into products table
        $insert_product_query = "INSERT INTO products (user_id, product_name, hsn_number) 
                               VALUES ('$user_id', '$product_name', '$hsn_number')";
        mysqli_query($con, $insert_product_query);

        // Now, fetch the product_id for the newly inserted product
        $product_id = mysqli_insert_id($con);

        // Validate and sanitize unit data
        if (isset($_POST["unit"]) && is_array($_POST["unit"])) {
            foreach ($_POST["unit"] as $unit) {
                $price = floatval($_POST["pricePer" . $unit]);
                $cgst = floatval($_POST["cgstPer" . $unit]);
                $sgst = floatval($_POST["sgstPer" . $unit]);

                // Insert into product_units table
                $insert_unit_query = "INSERT INTO product_units (product_id, user_id, unit_of_measurement, price, cgst, sgst) 
                                    VALUES ('$product_id', '$user_id', '$unit', $price, $cgst, $sgst)";
                mysqli_query($con, $insert_unit_query);
            }
            echo '<script>alert("Product \'' . $product_name . '\' added successfully!");</script>';
        } else {
            echo '<script>alert("Invalid unit data provided.");</script>';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Products</title>
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
                    <?php if (isset($statusMsg)): ?>
                        <div class="alert <?php echo $alertStyle; ?> alert-dismissible fade show" role="alert">
                            <?php echo $statusMsg; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <h2 class="mb-4 text-center font-weight-bold"><i class="fas fa-plus"></i> Add Product</h2>
                    <form method="post" action="">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="productName" class="form-label"><i class="fas fa-cube"></i> Product
                                        Name</label>
                                    <input type="text" class="form-control" id="productName" name="productName"
                                        placeholder="Enter Product Name" required>
                                    <div class="invalid-feedback">
                                        Please enter a product name.
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="hsn" class="form-label"><i class="fas fa-barcode"></i> HSN
                                        Number</label>
                                    <input type="text" class="form-control" id="hsn" name="hsn"
                                        placeholder="Enter HSN Number" required>
                                    <div class="invalid-feedback">
                                        Please enter an HSN number.
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="unit" class="form-label"><i class="fas fa-balance-scale"></i> Unit of
                                        Measurement</label>
                                    <select class="form-select" id="unit" name="unit[]" required multiple>
                                        <option value="kg">Kilogram</option>
                                        <option value="bag">Bag</option>
                                        <option value="liter">Liter</option>
                                        <option value="quantity">Quantity</option>
                                        <option value="packet">Packet</option>
                                        <option value="foot">Foot</option>
                                        <option value="inch">Inches</option>
                                    </select>
                                    <div class="invalid-feedback">
                                        Please select at least one unit of measurement.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="unitFields">
                            <!-- Dynamic fields will be inserted here -->
                        </div>

                        <div>
                            <button type="submit" name="submit" class="btn btn-primary"><i class="fas fa-check"></i> Add
                                Product</button>
                        </div>
                    </form>
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
            $('#unit').change(function () {
                var selectedUnits = $(this).val();
                var unitFieldsHtml = '';

                selectedUnits.forEach(function (unit) {
                    unitFieldsHtml += '<div class="row">';
                    unitFieldsHtml += '<div class="col-md-4">';
                    unitFieldsHtml += '<div class="mb-3">';
                    unitFieldsHtml += '<label for="pricePer' + unit + '" class="form-label"><i class="fas fa-money-bill"></i> Price per ' + unit + '</label>';
                    unitFieldsHtml += '<input type="number" class="form-control" id="pricePer' + unit + '" name="pricePer' + unit + '" placeholder="Enter Price per ' + unit + '" min="0" step="0.01" required>';
                    unitFieldsHtml += '<div class="invalid-feedback">';
                    unitFieldsHtml += 'Please enter a valid price.';
                    unitFieldsHtml += '</div>';
                    unitFieldsHtml += '</div>';
                    unitFieldsHtml += '</div>';
                    unitFieldsHtml += '<div class="col-md-4">';
                    unitFieldsHtml += '<div class="mb-3">';
                    unitFieldsHtml += '<label for="cgstPer' + unit + '" class="form-label"><i class="fas fa-percent"></i> CGST per ' + unit + '</label>';
                    unitFieldsHtml += '<input type="number" class="form-control" id="cgstPer' + unit + '" name="cgstPer' + unit + '" placeholder="Enter CGST per ' + unit + '" min="0" max="100" required>';
                    unitFieldsHtml += '<div class="invalid-feedback">';
                    unitFieldsHtml += 'Please enter a valid CGST percentage (0-100).';
                    unitFieldsHtml += '</div>';
                    unitFieldsHtml += '</div>';
                    unitFieldsHtml += '</div>';
                    unitFieldsHtml += '<div class="col-md-4">';
                    unitFieldsHtml += '<div class="mb-3">';
                    unitFieldsHtml += '<label for="sgstPer' + unit + '" class="form-label"><i class="fas fa-percent"></i> SGST per ' + unit + '</label>';
                    unitFieldsHtml += '<input type="number" class="form-control" id="sgstPer' + unit + '" name="sgstPer' + unit + '" placeholder="Enter SGST per ' + unit + '" min="0" max="100" required>';
                    unitFieldsHtml += '<div class="invalid-feedback">';
                    unitFieldsHtml += 'Please enter a valid SGST percentage (0-100).';
                    unitFieldsHtml += '</div>';
                    unitFieldsHtml += '</div>';
                    unitFieldsHtml += '</div>';
                    unitFieldsHtml += '</div>';
                });

                $('#unitFields').html(unitFieldsHtml);
            });
        });
    </script>
</body>

</html>