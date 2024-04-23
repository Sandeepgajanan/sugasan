<?php
require ('tcpdf/tcpdf.php');

// Start session
session_start();

// Get session data
$customerName = $_SESSION["customerName"];
$contactNumber = $_SESSION["contactNumber"];
$billDate = $_SESSION["billDate"];
$transaction_id = $_SESSION["transaction_id"];

// Connect to the database
$con = mysqli_connect("localhost", "root", "", "sugasan");

// Check the connection
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch shop information from the database
$shopInfoQuery = "SELECT * FROM shop_details WHERE active='1'";
$shopInfoResult = mysqli_query($con, $shopInfoQuery);

// Check if shop information exists
if (mysqli_num_rows($shopInfoResult) > 0) {
    $shopInfo = mysqli_fetch_assoc($shopInfoResult);

    // Extract shop information
    $shopName = $shopInfo['shop_name'];
    $shopAddress = $shopInfo['shop_address'];
    $ownerNumber = $shopInfo['phone_number'];
    $accountName = $shopInfo['bank_account_name'];
    $accountNumber = $shopInfo['account_number'];
    $ifsCode = $shopInfo['ifsc_code'];
} else {
    die("Shop information not found!");
}

// Create instance of TCPDF
$pdf = new TCPDF();
$pdf->AddPage();
$pdf->SetFont('times', '', 12);

// Shop Details
$pdf->SetFillColor(100, 100, 255); // Background color for the title
$pdf->SetTextColor(255, 255, 255); // Text color for the title
$pdf->Cell(190, 15, strtoupper($shopName), 0, 1, 'C', true);
$pdf->Ln(10);

// Owner Details
$pdf->SetTextColor(0, 0, 0); // Text color for owner details
$pdf->Cell(190, 10, "OWNER DETAILS", 0, 1, 'L');
$pdf->Cell(95, 10, "Address: $shopAddress", 0, 0, 'L');
$pdf->Cell(95, 10, "Phone Number: $ownerNumber", 0, 1, 'L');
$pdf->Cell(95, 10, "Account Name: $accountName", 0, 0, 'L');
$pdf->Cell(95, 10, "Account Number: $accountNumber", 0, 1, 'L');
$pdf->Cell(95, 10, "IFS Code: $ifsCode", 0, 1, 'L');

// Add a decorative border around owner details
$pdf->SetDrawColor(100, 100, 255); // Border color
$pdf->SetLineWidth(0.5);
$pdf->Rect(10, $pdf->GetY(), 190, 60);

// Customer Details
$pdf->Cell(190, 10, 'BILL TO', 0, 1, 'L');
$pdf->Cell(95, 10, "Customer Name: $customerName", 0, 0, 'L');
$pdf->Cell(95, 10, "Contact Number: $contactNumber", 0, 1, 'L');
$pdf->Cell(95, 10, "Bill Date: $billDate", 0, 0, 'L');
$pdf->Cell(95, 10, "Bill Number: $transaction_id", 0, 1, 'L');

// Add a decorative border around customer details
$pdf->Rect(10, $pdf->GetY(), 190, 50);

// Table Header
$pdf->SetFillColor(200, 220, 255); // Background color for the table header
$pdf->Cell(30, 10, 'Product Name', 1, 0, 'C', 1);
$pdf->Cell(40, 10, 'Unit of Measurement', 1, 0, 'C', 1);
$pdf->Cell(30, 10, 'Quantity', 1, 0, 'C', 1);
$pdf->Cell(30, 10, 'Price', 1, 0, 'C', 1);
$pdf->Cell(20, 10, 'CGST', 1, 0, 'C', 1);
$pdf->Cell(20, 10, 'SGST', 1, 0, 'C', 1);
$pdf->Cell(20, 10, 'Total Price', 1, 1, 'C', 1);

// Fetch bill details from the database
$product_query = "SELECT * FROM bills WHERE transaction_id = '$transaction_id'";
$product_result = mysqli_query($con, $product_query);

// Loop through each product and display in the table
while ($row = mysqli_fetch_assoc($product_result)) {
    $pdf->Cell(30, 10, $row['product_name'], 1);
    $pdf->Cell(40, 10, $row['unit_of_measurement'], 1);
    $pdf->Cell(30, 10, $row['quantity'], 1);
    $pdf->Cell(30, 10, $row['price'], 1);
    $pdf->Cell(20, 10, $row['cgst'], 1);
    $pdf->Cell(20, 10, $row['sgst'], 1);
    $pdf->Cell(20, 10, $row['total_price'], 1);
    $pdf->Ln();
}

// Calculate grand total
$grandTotalQuery = "SELECT SUM(total_price) as grand_total FROM bills WHERE transaction_id = '$transaction_id'";
$grandTotalResult = mysqli_query($con, $grandTotalQuery);
$grandTotalDetails = mysqli_fetch_assoc($grandTotalResult);
$grandTotal = $grandTotalDetails['grand_total'];

// Display grand total
$pdf->Cell(170, 10, 'Grand Total', 1, 0, 'R', 1);
$pdf->Cell(20, 10, $grandTotal, 1, 1, 'C');

// Thank you message and owner's signature
$pdf->Ln(10);
$pdf->Cell(90, 10, 'Thank you for your business!', 0, 0, 'L');
$pdf->Cell(90, 10, "Owner's Signature", 0, 1, 'R');

// Get the PDF content as a string
$pdfContent = $pdf->Output('', 'S');
echo $pdfContent; // Output the PDF content

// Close the database connection
mysqli_close($con);
?>