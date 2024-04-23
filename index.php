<?php
include ('db_connection.php');
session_start();
error_reporting(0);

if (isset($_POST["submit"])) {
    $username = mysqli_real_escape_string($con, $_POST["username"]); // Sanitize user input
    $password = mysqli_real_escape_string($con, $_POST["password"]);

    $check_query = "SELECT * FROM users WHERE username='$username'";
    $result = mysqli_query($con, $check_query);

    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);

        if (password_verify($password, $row['password'])) { // Compare hashed passwords
            $_SESSION["username"] = $username;
            $_SESSION["user_id"] = $row["user_id"];
            header("location: dashboard.php");
            exit(); // Add this line to prevent further execution
        } else {
            $error = "Invalid username or password. Please try again.";
        }
    } else {
        $error = "Invalid username or password. Please try again.";
    }
    mysqli_close($con); // Close the database connection
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css">
</head>

<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h3 class="text-center mb-4">Welcome Back</h3>
                        <!-- SIGN IN -->
                        <form class="form sign-in" method="post" action="">
                            <?php if (isset($error)) { ?>
                                <div class="alert alert-danger" role="alert">
                                    <?php echo $error; ?>
                                </div>
                            <?php } ?>
                            <div class="form-group">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                                    </div>
                                    <input type="text" class="form-control" name="username" placeholder="Username"
                                        required>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                    </div>
                                    <input type="password" class="form-control" name="password" placeholder="Password"
                                        required>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary btn-block" name="submit">Sign in</button>

                            <p class="mt-3 text-center">
                                <a href="forgot.php">Forgot password?</a>
                            </p>

                            <p class="text-center">
                                Don't have an account? <a href="register.php">Sign up here</a>
                            </p>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>