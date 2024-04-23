<?php
include ('db_connection.php');
session_start();
error_reporting(0);

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $cust_id = $_GET['id'];
    $result = mysqli_query($con, "SELECT * FROM customer WHERE id = $cust_id");

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $custname = htmlspecialchars($row['custname']);
        $address = htmlspecialchars($row['address']);
        $gstno = htmlspecialchars($row['gstno']);
        $pno = htmlspecialchars($row['pno']);
    } else {
        echo "Customer details not found.";
        exit;
    }
}

if (isset($_POST["submit"])) {
    $custname = $_POST["custName"];
    $address = $_POST["Address"];
    $pno = $_POST["phoneNumber"];

    $update_query = "UPDATE customer SET 
       custname='$custname', 
       address='$address', 
       pno='$pno'
       WHERE id = $cust_id";

    if (mysqli_query($con, $update_query)) {
        $alertStyle = "alert alert-success";
        $statusMsg = "Customer details edited successfully!";
    } else {
        $alertStyle = "alert alert-danger";
        $statusMsg = "An error occurred while editing Customer details.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Customers</title>
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
                    <h2 class="mb-4 text-center"><i class="fas fa-store"></i> Edit Customers</h2>
                    <form method="post" action="">
                        <!-- Your form content here -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="custName" class="form-label font-weight-bold"><i
                                            class="fas fa-store"></i> Customer Name</label>
                                    <input type="text" class="form-control" id="custName" name="custName"
                                        placeholder="Enter Customer Name" required pattern=".*\S+.*"
                                        title="Please enter a valid customer name." value="<?php echo $custname; ?>" />
                                </div>

                                <div class="mb-3">
                                    <label for="phoneNumber" class="form-label font-weight-bold"><i
                                            class="fas fa-phone"></i> Phone Number</label>
                                    <input type="tel" class="form-control" id="phoneNumber" name="phoneNumber"
                                        placeholder="Enter Phone Number" required pattern="[0-9]{10}"
                                        title="Please enter a valid 10-digit phone number."
                                        value="<?php echo $pno; ?>" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="shopAddress" class="form-label font-weight-bold"><i
                                            class="fas fa-map-marker-alt"></i>Address</label>
                                    <input type="text" class="form-control" id="Address" name="Address"
                                        placeholder="Enter Address" required value="<?php echo $address; ?>" />
                                </div>
                                <div class="mb-3">
                                    <label for="customergst" class="form-label font-weight-bold"><i
                                            class="fas fa-map-marker-alt"></i> Customergst Number</label>
                                    <input type="text" class="form-control" id="custgst" name="custgst"
                                        placeholder="Enter Customergst Number" required value="<?php echo $gstno; ?>"
                                        readonly />
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
                            window.location.href = 'view_customers.php';
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