<?php
require ('tcpdf/tcpdf.php');
include ('db_connection.php');
session_start();
error_reporting(0);

if (isset($_POST["month"]) && isset($_POST["year"])) {
    $selectedMonth = $_POST["month"];
    $selectedYear = $_POST["year"];
    $selectedDate = "$selectedYear-$selectedMonth";
    $user_id = $_SESSION['user_id'];
    $reportQuery = "SELECT * FROM bills WHERE DATE_FORMAT(bill_date, '%Y-%m') = '$selectedDate' and user_id=$user_id";
    $reportResult = mysqli_query($con, $reportQuery);

    $pdf = new TCPDF();
    $pdf->SetMargins(15, 12, 15); // Set left, top, and right margins to 15mm
    $pdf->SetAutoPageBreak(true, 15); // Set auto page break with a bottom margin of 15mm

    $pdf->AddPage();
    $pdf->SetFont('helvetica', '', 12);

    $pdf->SetFont('helvetica', 'B', 16);
    $pdf->Cell(190, 10, "Monthly Report", 0, 1, 'C');

    $dateObj = DateTime::createFromFormat('!m', $selectedMonth);
    $monthName = $dateObj->format('F');
    $pdf->SetFont('helvetica', 'I', 14);
    $pdf->Cell(190, 10, "Month: $monthName, Year: $selectedYear", 0, 1, 'C');

    if (mysqli_num_rows($reportResult) > 0) {
        $transactionData = array();

        while ($row = mysqli_fetch_assoc($reportResult)) {
            $transactionId = $row['transaction_id'];
            $customerName = $row['customer_name'];
            $contactNumber = $row['contact_number'];
            $billDate = date('d/m/Y', strtotime($row['bill_date']));

            $productName = $row['product_name'];
            $unitOfMeasurement = $row['unit_of_measurement'];
            $quantity = $row['quantity'];
            $price = $row['price'];
            $cgst = $row['cgst'];
            $sgst = $row['sgst'];
            $totalPrice = $row['total_price'];

            if (!isset($transactionData[$transactionId])) {
                $transactionData[$transactionId] = array(
                    'customer_name' => $customerName,
                    'contact_number' => $contactNumber,
                    'bill_date' => $billDate,
                    'purchases' => array(),
                );
            }

            $transactionData[$transactionId]['purchases'][] = array(
                'product_name' => $productName,
                'unit_of_measurement' => $unitOfMeasurement,
                'quantity' => $quantity,
                'price' => $price,
                'cgst' => $cgst,
                'sgst' => $sgst,
                'total_price' => $totalPrice,
            );
        }

        foreach ($transactionData as $transactionId => $transactionDetails) {
            $pdf->SetFont('helvetica', 'B', 14);
            $pdf->Cell(190, 10, "Transaction ID: $transactionId", 0, 1, 'L');
            $pdf->SetFont('helvetica', '', 12);
            $pdf->Cell(190, 10, "Customer Name: {$transactionDetails['customer_name']}", 0, 1, 'L');
            $pdf->Cell(95, 10, "Contact Number: {$transactionDetails['contact_number']}", 0, 1, 'L');
            $pdf->Cell(95, 10, "Bill Date: {$transactionDetails['bill_date']}", 0, 1, 'L');

            $pdf->Cell(30, 10, 'Product Name', 1, 0, 'C');
            $pdf->Cell(40, 10, 'Unit of Measurement', 1, 0, 'C');
            $pdf->Cell(30, 10, 'Quantity', 1, 0, 'C');
            $pdf->Cell(30, 10, 'Price', 1, 0, 'C');
            $pdf->Cell(20, 10, 'CGST', 1, 0, 'C');
            $pdf->Cell(20, 10, 'SGST', 1, 0, 'C');
            $pdf->Cell(20, 10, 'Total Price', 1, 1, 'C');

            foreach ($transactionDetails['purchases'] as $purchase) {
                $pdf->Cell(30, 10, $purchase['product_name'], 1, 0, 'L');
                $pdf->Cell(40, 10, $purchase['unit_of_measurement'], 1, 0, 'L');
                $pdf->Cell(30, 10, $purchase['quantity'], 1, 0, 'C');
                $pdf->Cell(30, 10, $purchase['price'], 1, 0, 'C');
                $pdf->Cell(20, 10, $purchase['cgst'], 1, 0, 'C');
                $pdf->Cell(20, 10, $purchase['sgst'], 1, 0, 'C');
                $pdf->Cell(20, 10, $purchase['total_price'], 1, 1, 'C');
            }

            $pdf->Cell(190, 10, "", 0, 1); // Add space between customers
        }

        $totalSales = array_reduce($transactionData, function ($carry, $item) {
            return $carry + array_sum(array_column($item['purchases'], 'total_price'));
        }, 0);
        $customerCount = count($transactionData);

        $pdf->SetFont('helvetica', 'B', 14);
        $pdf->Cell(190, 10, "Total Sales: $totalSales", 0, 1, 'L');
        $pdf->Cell(190, 10, "Total Customers: $customerCount", 0, 1, 'L');

        $pdfContent = $pdf->Output('', 'S');

        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="monthly_report.pdf"');
        echo $pdfContent;
    } else {
        header("Location: generatereport.php?message=No data available for the selected month/year.");
        exit();
    }
}



mysqli_close($con);
?>