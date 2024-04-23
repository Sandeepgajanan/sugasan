<?php
include ('db_connection.php');
session_start();
error_reporting(0);
// Get the user ID from the session
$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM customer WHERE user_id = $user_id";
$result = mysqli_query($con, $query);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Customers</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/css/bootstrap.min.css"
        crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        crossorigin="anonymous">
    <style>
        .btn-group .btn {
            margin-right: 5px;
            /* Adjust the margin as needed */
        }
    </style>
</head>

<body>
    <?php
    include 'header.php';
    ?>

    <div class="container-fluid">
        <div class="row">
            <?php include 'sidebar.php'; ?>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 mb-4">
                <div class="container p-4">
                    <h2 class="mb-4 text-center font-weight-bold"><i class="fas fa-list"></i> View Customers</h2>

                    <!-- Customer table -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="bg-light">
                                <tr>
                                    <th scope="col">Customer Name</th>
                                    <th scope="col">Customer Address</th>
                                    <th scope="col">Gst Number</th>
                                    <th scope="col">Phone Number</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                    <tr>
                                        <td><?php echo $row['custname']; ?></td>
                                        <td><?php echo $row['address']; ?></td>
                                        <td><?php echo $row['gstno']; ?></td>
                                        <td><?php echo $row['pno']; ?></td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="edit_customers.php?id=<?php echo $row['id']; ?>"
                                                    class="btn btn-primary btn-sm">
                                                    <i class="fas fa-edit"></i> Edit
                                                </a>
                                                <button class="btn btn-danger btn-sm delete-button"
                                                    data-cust-id="<?php echo $row['id']; ?>">
                                                    <i class="fas fa-trash"></i> Delete
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>

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
                var custId = $(this).data('cust-id');
                var confirmDelete = confirm("Are you sure you want to delete this Customer?");

                if (confirmDelete) {
                    window.location.href = 'delete_customer.php?id=' + custId;
                }
            });
        });
    </script>
</body>

</html>