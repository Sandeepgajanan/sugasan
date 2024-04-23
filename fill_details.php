<?php
include ('db_connection.php');
session_start();
error_reporting(0);

$alertStyle = "";
$statusMsg = "";

if (isset($_POST["submit"])) {
    $shop_name = mysqli_real_escape_string($con, $_POST["shopName"]);
    $shop_address = mysqli_real_escape_string($con, $_POST["shopAddress"]);
    $phone_number = mysqli_real_escape_string($con, $_POST["phoneNumber"]);
    $bank_account_name = mysqli_real_escape_string($con, $_POST["bankName"]);
    $account_number = mysqli_real_escape_string($con, $_POST["accNo"]);
    $ifsc_code = mysqli_real_escape_string($con, $_POST["ifsc"]);
    $user_id = $_SESSION['user_id']; // Make sure user_id is properly set in the session


    $query = "INSERT INTO shop_details (user_id, shop_name, shop_address, phone_number, bank_account_name, account_number, ifsc_code) 
                  VALUES ('$user_id', '$shop_name', '$shop_address', '$phone_number', '$bank_account_name', '$account_number', '$ifsc_code')";

    if (mysqli_query($con, $query)) {
        $alertStyle = "alert alert-success";
        $statusMsg = "Shop created and details saved successfully!";
    } else {
        $alertStyle = "alert alert-danger";
        $statusMsg = "An error occurred while saving shop details.";
    }

}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fill Details</title>
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
                    <?php if (!empty($statusMsg)): ?>
                        <div class="<?php echo $alertStyle; ?> alert-dismissible fade show" role="alert">
                            <?php echo $statusMsg; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <h2 class="mb-4 text-center"><i class="fas fa-store"></i> Fill Details</h2>
                    <form method="post" action="">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="shopName" class="form-label font-weight-bold"><i
                                            class="fas fa-store"></i> Shop Name</label>
                                    <input type="text" class="form-control" id="shopName" name="shopName"
                                        placeholder="Enter Shop Name" required pattern="[a-zA-Z0-9\s]+"
                                        title="Only letters, numbers, and spaces are allowed."
                                        value="<?php echo isset($_POST['shopName']) ? $_POST['shopName'] : ''; ?>" />
                                </div>
                                <div class="mb-3">
                                    <label for="shopAddress" class="form-label font-weight-bold"><i
                                            class="fas fa-map-marker-alt"></i> Shop Address</label>
                                    <input type="text" class="form-control" id="shopAddress" name="shopAddress"
                                        placeholder="Enter Shop Address" required
                                        value="<?php echo isset($_POST['shopAddress']) ? $_POST['shopAddress'] : ''; ?>" />
                                </div>
                                <div class="mb-3">
                                    <label for="phoneNumber" class="form-label font-weight-bold"><i
                                            class="fas fa-phone"></i> Phone Number</label>
                                    <input type="tel" class="form-control" id="phoneNumber" name="phoneNumber"
                                        placeholder="Enter Phone Number" required pattern="[0-9]{10}"
                                        title="Please enter a valid 10-digit phone number."
                                        value="<?php echo isset($_POST['phoneNumber']) ? $_POST['phoneNumber'] : ''; ?>" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="bankDetails" class="form-label font-weight-bold"><i
                                            class="fas fa-university"></i> Bank Account Details</label>
                                    <div style="border: 1px solid #ccc; padding: 10px; border-radius: 5px;">
                                        <input type="text" class="form-control mb-2" id="bankName" name="bankName"
                                            placeholder="Account Name" required
                                            value="<?php echo isset($_POST['bankName']) ? $_POST['bankName'] : ''; ?>" />
                                        <input type="text" class="form-control mb-2" id="accNo" name="accNo"
                                            placeholder="Account Number" required pattern="[0-9]{9,18}"
                                            title="Please enter a valid account number (9-18 digits)."
                                            value="<?php echo isset($_POST['accNo']) ? $_POST['accNo'] : ''; ?>" />
                                        <input type="text" class="form-control" id="ifsc" name="ifsc"
                                            placeholder="IFSC Code" required pattern="[A-Za-z]{4}[0-9]{7}"
                                            title="Please enter a valid IFSC code (4 letters followed by 7 digits)."
                                            value="<?php echo isset($_POST['ifsc']) ? $_POST['ifsc'] : ''; ?>" />
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