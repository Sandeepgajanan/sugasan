<?php
include ('db_connection.php');
session_start();
error_reporting(0);

if (isset($_POST["submit"])) {
    // Sanitize input data
    $customerName = mysqli_real_escape_string($con, $_POST["customerName"]);
    $contactNumber = mysqli_real_escape_string($con, $_POST["contactNumber"]);
    $transaction_id = mysqli_real_escape_string($con, $_POST["transaction_id"]);
    $products = isset($_POST["product"]) ? $_POST["product"] : array(); // Check if product array is set
    $user_id = $_SESSION['user_id'];

    // Check if the transaction ID already exists
    $checkQuery = "SELECT COUNT(*) as count FROM bills WHERE transaction_id = '$transaction_id'";
    $checkResult = mysqli_query($con, $checkQuery);
    $checkData = mysqli_fetch_assoc($checkResult);

    if ($checkData['count'] > 0) {
        echo '<script>alert("Bill with transaction ID ' . $transaction_id . ' already exists.");</script>';
        echo '<script>window.location.href="generatebill.php";</script>';
        exit;
    } else {
        $grandTotal = 0;

        foreach ($products as $product) {
            // Extract product_id and unit_id from the product string
            list($product_id, $unit_id) = explode('[', $product);
            $unit_id = rtrim($unit_id, ']');

            // Sanitize and validate quantity input
            $quantity = isset($_POST["quantity"][$product_id][$unit_id]) ? intval($_POST["quantity"][$product_id][$unit_id]) : 0;
            if ($quantity <= 0) {
                echo '<script>alert("Invalid quantity for product with ID ' . $product_id . '");</script>';
                echo '<script>window.location.href="generatebill.php";</script>';
                exit;
            }

            // Fetch product details from the database
            $product_query = "SELECT product_name, unit_of_measurement FROM products LEFT JOIN product_units ON products.product_id = product_units.product_id WHERE products.product_id = $product_id AND product_unit_id = $unit_id";
            $product_result = mysqli_query($con, $product_query);
            $product_details = mysqli_fetch_assoc($product_result);
            $productName = $product_details['product_name'];
            $unitOfMeasurement = $product_details['unit_of_measurement'];

            // Fetch unit price and taxes from the database
            $unitPriceQuery = "SELECT price, cgst, sgst FROM product_units WHERE product_id = $product_id AND product_unit_id = $unit_id";
            $unitPriceResult = mysqli_query($con, $unitPriceQuery);
            $unitPriceDetails = mysqli_fetch_assoc($unitPriceResult);
            $price = $unitPriceDetails['price'];
            $cgst = $unitPriceDetails['cgst'];
            $sgst = $unitPriceDetails['sgst'];

            // Calculate total price including taxes
            $tPrice = $quantity * $price;
            $totalCgst = ($tPrice * $cgst) / 100;
            $totalSgst = ($tPrice * $sgst) / 100;
            $totalPrice = $tPrice + $totalCgst + $totalSgst;

            // Insert bill details into the database
            $billDate = date('Y-m-d');
            $insertBillQuery = "INSERT INTO bills (user_id, customer_name, contact_number, product_name, unit_of_measurement, quantity, price, cgst, sgst, total_price, bill_date, transaction_id) 
                                VALUES ('$user_id', '$customerName', '$contactNumber', '$productName', '$unitOfMeasurement', $quantity, $price, $cgst, $sgst, $totalPrice, '$billDate', '$transaction_id')";

            mysqli_query($con, $insertBillQuery);

            // Calculate grand total
            $grandTotal += $totalPrice;
        }

        // Store relevant information in session for invoice generation
        $_SESSION["customerName"] = $customerName;
        $_SESSION["contactNumber"] = $contactNumber;
        $_SESSION["billDate"] = $billDate;
        $_SESSION["transaction_id"] = $transaction_id;

        // Generate invoice PDF
        ob_start();
        include ('generate_invoice.php');
        $pdfContent = ob_get_clean();

        // Output PDF for download
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="invoice.pdf"');
        echo $pdfContent;

        exit;
    }
}

mysqli_close($con);
?>