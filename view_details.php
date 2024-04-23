<?php
include ('db_connection.php');
session_start();
error_reporting(0);

// Get the user ID from the session
$user_id = $_SESSION['user_id'];

// Query to fetch shop details associated with the current user
$query = "SELECT * FROM shop_details WHERE user_id = $user_id";
$result = mysqli_query($con, $query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Details</title>
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
    <?php include 'header.php'; ?>

    <div class="container-fluid">
        <div class="row">
            <?php include 'sidebar.php'; ?>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 mb-4">
                <div class="container p-4">
                    <h2 class="mb-4 text-center"><i class="fas fa-store"></i> View Details</h2>
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Shop Name</th>
                                <th>Shop Address</th>
                                <th>Phone Number</th>
                                <th>Bank Account Name</th>
                                <th>Account Number</th>
                                <th>IFSC Code</th>
                                <th>Status</th>
                                <th>Change Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                <tr>
                                    <td><?php echo $row['shop_name']; ?></td>
                                    <td><?php echo $row['shop_address']; ?></td>
                                    <td><?php echo $row['phone_number']; ?></td>
                                    <td><?php echo $row['bank_account_name']; ?></td>
                                    <td><?php echo $row['account_number']; ?></td>
                                    <td><?php echo $row['ifsc_code']; ?></td>
                                    <td>
                                        <?php if ($row['active'] == 1): ?>
                                            <span class="badge bg-success">Activated</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger">Deactivated</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <form action="activate_shop.php" method="post">
                                            <input type="hidden" name="shop_id" value="<?php echo $row['shop_id']; ?>">
                                            <?php if ($row['active'] == 1): ?>
                                                <button type="submit" name="deactivate"
                                                    class="btn btn-warning btn-sm">Deactivate</button>
                                            <?php else: ?>
                                                <button type="submit" name="activate"
                                                    class="btn btn-success btn-sm">Activate</button>
                                            <?php endif; ?>
                                        </form>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="edit_details.php?id=<?php echo $row['shop_id']; ?>"
                                                class="btn btn-primary btn-sm">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                            <button class="btn btn-danger btn-sm delete-button"
                                                data-shop-id="<?php echo $row['shop_id']; ?>">
                                                <i class="fas fa-trash"></i> Delete
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
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
            // Delete shop button functionality
            $('.delete-button').click(function () {
                var shopId = $(this).data('shop-id');
                var confirmDelete = confirm("Are you sure you want to delete this shop?");
                if (confirmDelete) {
                    window.location.href = 'delete_shop.php?id=' + shopId;
                }
            });
        });
    </script>
</body>

</html>