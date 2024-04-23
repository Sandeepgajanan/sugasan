<?php
include ('db_connection.php');
session_start();
error_reporting(0);

if (isset($_GET['id'])) {
    $shop_id = $_GET['id'];
    // Prepare a statement
    $stmt = mysqli_prepare($con, "SELECT * FROM shop_details WHERE shop_id = ?");
    // Bind parameters
    mysqli_stmt_bind_param($stmt, "i", $shop_id);
    // Execute the statement
    mysqli_stmt_execute($stmt);
    // Get the result
    $result = mysqli_stmt_get_result($stmt);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $shop_name = $row['shop_name'];
        $shop_address = $row['shop_address'];
        $phone_number = $row['phone_number'];
        $bank_account_name = $row['bank_account_name'];
        $account_number = $row['account_number'];
        $ifsc_code = $row['ifsc_code'];
    } else {
        echo "Shop details not found.";
        exit;
    }
}

if (isset($_POST["submit"])) {
    // Sanitize user input
    $shop_name = mysqli_real_escape_string($con, $_POST["shopName"]);
    $shop_address = mysqli_real_escape_string($con, $_POST["shopAddress"]);
    $phone_number = mysqli_real_escape_string($con, $_POST["phoneNumber"]);
    $bank_account_name = mysqli_real_escape_string($con, $_POST["bankName"]);
    $account_number = mysqli_real_escape_string($con, $_POST["accNo"]);
    $ifsc_code = mysqli_real_escape_string($con, $_POST["ifsc"]);

    $update_query = "UPDATE shop_details SET 
        shop_name='$shop_name', 
        shop_address='$shop_address', 
        phone_number='$phone_number', 
        bank_account_name='$bank_account_name', 
        account_number='$account_number', 
        ifsc_code='$ifsc_code' 
        WHERE shop_id = $shop_id";

    if (mysqli_query($con, $update_query)) {
        $alertStyle = "alert alert-success";
        $statusMsg = "Shop details edited successfully!";
    } else {
        $alertStyle = "alert alert-danger";
        $statusMsg = "An error occurred while editing shop details.";
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Details</title>
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
                    <h2 class="mb-4 text-center"><i class="fas fa-store"></i> Edit Details</h2>
                    <form method="post" action="">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="shopName" class="form-label font-weight-bold"><i
                                            class="fas fa-store"></i> Shop Name</label>
                                    <input type="text" class="form-control" id="shopName" name="shopName"
                                        placeholder="Enter Shop Name" required pattern=".*\S+.*"
                                        title="Please enter a valid shop name." value="<?php echo $shop_name; ?>" />
                                </div>
                                <div class="mb-3">
                                    <label for="shopAddress" class="form-label font-weight-bold"><i
                                            class="fas fa-map-marker-alt"></i> Shop Address</label>
                                    <input type="text" class="form-control" id="shopAddress" name="shopAddress"
                                        placeholder="Enter Shop Address" required
                                        value="<?php echo $shop_address; ?>" />
                                </div>
                                <div class="mb-3">
                                    <label for="phoneNumber" class="form-label font-weight-bold"><i
                                            class="fas fa-phone"></i> Phone Number</label>
                                    <input type="tel" class="form-control" id="phoneNumber" name="phoneNumber"
                                        placeholder="Enter Phone Number" required pattern="[0-9]{10}"
                                        title="Please enter a valid 10-digit phone number."
                                        value="<?php echo $phone_number; ?>" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="bankDetails" class="form-label font-weight-bold"><i
                                            class="fas fa-university"></i> Bank Account Details</label>
                                    <div style="border: 1px solid #ccc; padding: 10px; border-radius: 5px;">
                                        <input type="text" class="form-control mb-2" id="bankName" name="bankName"
                                            placeholder="Account Name" required
                                            value="<?php echo $bank_account_name; ?>" />
                                        <input type="text" class="form-control mb-2" id="accNo" name="accNo"
                                            placeholder="Account Number" required pattern="[0-9]{9,18}"
                                            title="Please enter a valid account number (9-18 digits)."
                                            value="<?php echo $account_number; ?>" />
                                        <input type="text" class="form-control" id="ifsc" name="ifsc"
                                            placeholder="IFSC Code" required pattern="[A-Za-z]{4}[0-9]{7}"
                                            title="Please enter a valid IFSC code (4 letters followed by 7 digits)."
                                            value="<?php echo $ifsc_code; ?>" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div>
                            <button type="submit" name="submit" class="btn btn-primary"
                                style="background-color: #333; border: none; border-radius: 5px; font-weight: bold;"><i
                                    class="fas fa-save"></i> Save Details</button>
                        </div>
                    </form>

                    <div class="<?php echo $alertStyle; ?>">
                        <?php echo $statusMsg; ?>
                    </div>

                    <script>
                        <?php if (isset($statusMsg)): ?>
                            alert("<?php echo $statusMsg; ?>");
                            window.location.href = 'view_details.php';
                        <?php endif; ?>
                    </script>

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