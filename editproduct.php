<?php
include ('db_connection.php');
session_start();
error_reporting(0);

if (isset($_GET['id'])) {
    $product_id = $_GET['id'];

    $product_query = mysqli_prepare($con, "SELECT * FROM products WHERE product_id=?");
    mysqli_stmt_bind_param($product_query, "i", $product_id);
    mysqli_stmt_execute($product_query);
    $product_result = mysqli_stmt_get_result($product_query);
    $product_data = mysqli_fetch_assoc($product_result);

    if (!$product_data) {
        header("Location: viewproduct.php");
        exit();
    }

    $unit_query = mysqli_prepare($con, "SELECT * FROM product_units WHERE product_id=?");
    mysqli_stmt_bind_param($unit_query, "i", $product_id);
    mysqli_stmt_execute($unit_query);
    $unit_result = mysqli_stmt_get_result($unit_query);
    $unit_data = mysqli_fetch_all($unit_result, MYSQLI_ASSOC);
} else {
    header("Location: viewproduct.php");
    exit();
}

if (isset($_POST["submit"])) {
    // Sanitize and validate inputs

    $product_name = mysqli_real_escape_string($con, $_POST["productName"]);
    $hsn_number = mysqli_real_escape_string($con, $_POST["hsn"]);

    // Update product details in the database
    $update_product_query = "UPDATE products SET product_name=?, hsn_number=? WHERE product_id=?";
    $update_product_stmt = mysqli_prepare($con, $update_product_query);
    mysqli_stmt_bind_param($update_product_stmt, "ssi", $product_name, $hsn_number, $product_id);
    mysqli_stmt_execute($update_product_stmt);

    $units = $_POST["unit"];

    foreach ($units as $unit) {
        $price = $_POST["pricePer" . $unit];
        $cgst = $_POST["cgstPer" . $unit];
        $sgst = $_POST["sgstPer" . $unit];

        // Check if the unit already exists for this product
        $check_unit_query = mysqli_prepare($con, "SELECT * FROM product_units WHERE product_id=? AND unit_of_measurement=?");
        mysqli_stmt_bind_param($check_unit_query, "is", $product_id, $unit);
        mysqli_stmt_execute($check_unit_query);
        $check_unit_result = mysqli_stmt_get_result($check_unit_query);

        if (mysqli_num_rows($check_unit_result) > 0) {
            // If unit already exists, update the details
            $update_unit_query = "UPDATE product_units SET price=?, cgst=?, sgst=? WHERE product_id=? AND unit_of_measurement=?";
            $update_unit_stmt = mysqli_prepare($con, $update_unit_query);
            mysqli_stmt_bind_param($update_unit_stmt, "ddiis", $price, $cgst, $sgst, $product_id, $unit);
            mysqli_stmt_execute($update_unit_stmt);
        } else {
            // If unit does not exist, insert new record
            $insert_unit_query = "INSERT INTO product_units (product_id, unit_of_measurement, price, cgst, sgst) VALUES (?, ?, ?, ?, ?)";
            $insert_unit_stmt = mysqli_prepare($con, $insert_unit_query);
            mysqli_stmt_bind_param($insert_unit_stmt, "isdii", $product_id, $unit, $price, $cgst, $sgst);
            mysqli_stmt_execute($insert_unit_stmt);
        }
    }

    $alertStyle = "alert alert-success";
    $statusMsg = "Product '$product_name' updated successfully!";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
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

                    <h2 class="mb-4 text-center font-weight-bold"><i class="fas fa-edit"></i> Edit Product</h2>

                    <form method="post" action="">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="productName" class="form-label"><i class="fas fa-cube"></i> Product
                                        Name</label>
                                    <input type="text" class="form-control" id="productName" name="productName"
                                        placeholder="Enter Product Name"
                                        value="<?php echo $product_data['product_name']; ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="hsn" class="form-label"><i class="fas fa-barcode"></i> HSN
                                        Number</label>
                                    <input type="text" class="form-control" id="hsn" name="hsn"
                                        placeholder="Enter HSN Number"
                                        value="<?php echo $product_data['hsn_number']; ?>" required>

                                    <div class="mb-3">
                                        <label for="unit" class="form-label"><i class="fas fa-balance-scale"></i> Unit
                                            of Measurement</label>
                                        <select class="form-select" id="unit" name="unit[]" required multiple>
                                            <?php
                                            $unitOptions = ["kg", "bag", "liter", "quantity", "packet", "foot", "inch"];
                                            foreach ($unitOptions as $option) {
                                                $disabled = in_array($option, array_column($unit_data, 'unit_of_measurement')) ? '' : 'disabled';
                                                $selected = in_array($option, array_column($unit_data, 'unit_of_measurement')) ? 'selected' : '';
                                                echo '<option value="' . $option . '" ' . $disabled . ' ' . $selected . '>' . $option . '</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div id="unitFields">
                                <?php
                                foreach ($unit_data as $unit) {
                                    echo '<div class="row unitRow">';
                                    echo '<div class="col-md-4">';
                                    echo '<div class="mb-3">';
                                    echo '<label for="pricePer' . $unit['unit_of_measurement'] . '" class="form-label"><i class="fas fa-money-bill"></i> Price per ' . $unit['unit_of_measurement'] . '</label>';
                                    echo '<input type="number" class="form-control" id="pricePer' . $unit['unit_of_measurement'] . '" name="pricePer' . $unit['unit_of_measurement'] . '" placeholder="Enter Price per ' . $unit['unit_of_measurement'] . '" min="0" step="0.01" value="' . (isset($_POST['pricePer' . $unit['unit_of_measurement']]) ? $_POST['pricePer' . $unit['unit_of_measurement']] : $unit['price']) . '" required>';
                                    echo '</div>';
                                    echo '</div>';
                                    echo '<div class="col-md-4">';
                                    echo '<div class="mb-3">';
                                    echo '<label for="cgstPer' . $unit['unit_of_measurement'] . '" class="form-label"><i class="fas fa-percent"></i> CGST per ' . $unit['unit_of_measurement'] . '</label>';
                                    echo '<input type="number" class="form-control" id="cgstPer' . $unit['unit_of_measurement'] . '" name="cgstPer' . $unit['unit_of_measurement'] . '" placeholder="Enter CGST per ' . $unit['unit_of_measurement'] . '" min="0" max="100" value="' . (isset($_POST['cgstPer' . $unit['unit_of_measurement']]) ? $_POST['cgstPer' . $unit['unit_of_measurement']] : $unit['cgst']) . '" required>';
                                    echo '</div>';
                                    echo '</div>';
                                    echo '<div class="col-md-4">';
                                    echo '<div class="mb-3">';
                                    echo '<label for="sgstPer' . $unit['unit_of_measurement'] . '" class="form-label"><i class="fas fa-percent"></i> SGST per ' . $unit['unit_of_measurement'] . '</label>';
                                    echo '<input type="number" class="form-control" id="sgstPer' . $unit['unit_of_measurement'] . '" name="sgstPer' . $unit['unit_of_measurement'] . '" placeholder="Enter SGST per ' . $unit['unit_of_measurement'] . '" min="0" max="100" value="' . (isset($_POST['sgstPer' . $unit['unit_of_measurement']]) ? $_POST['sgstPer' . $unit['unit_of_measurement']] : $unit['sgst']) . '" required>';
                                    echo '</div>';
                                    echo '</div>';
                                    echo '</div>';
                                }
                                ?>
                            </div>

                            <div>
                                <button type="submit" name="submit" class="btn btn-primary"><i class="fas fa-check"></i>
                                    Save Changes</button>
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
    <script type="text/javascript">

    </script>

</body>

</html>