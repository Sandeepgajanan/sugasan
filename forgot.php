<?php
include ('db_connection.php');
session_start();
error_reporting(0);

if (isset($_POST["submit"])) {
    $username = mysqli_real_escape_string($con, $_POST["username"]);
    $security_question = $_POST["security_question"];
    $security_answer = mysqli_real_escape_string($con, $_POST["security_answer"]);
    $new_password = mysqli_real_escape_string($con, $_POST["new_password"]);

    // Server-side validation
    if (empty($username) || empty($security_question) || empty($security_answer) || empty($new_password)) {
        echo '<script>
                alert("Please fill in all fields.");
              </script>';
    } else {
        // Check if the username and security answer match
        $check_query = "SELECT * FROM users WHERE username='$username' AND security_question_id='$security_question' AND security_answer='$security_answer'";
        $result = mysqli_query($con, $check_query);

        if (mysqli_num_rows($result) > 0) {
            // Update the password
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $update_query = "UPDATE users SET password='$hashed_password' WHERE username='$username'";

            if (mysqli_query($con, $update_query)) {
                echo '<script>
                        alert("Password changed successfully!");
                        window.location.href = "index.php";
                      </script>';
                exit();
            } else {
                echo '<script>
                        alert("Error updating password: ' . mysqli_error($con) . '");
                      </script>';
            }
        } else {
            echo '<script>
                    alert("Invalid credentials. Please check your username, security question, and answer.");
                  </script>';
        }
    }
}

mysqli_close($con);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Your Password?</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header text-center">
                        <h1 class="mb-4">Forgot Your Password?</h1>
                    </div>
                    <div class="card-body">
                        <form method="post" action="forgot.php">
                            <div class="form-group">
                                <label for="username">Username</label>
                                <input type="text" class="form-control" name="username" required>
                            </div>

                            <div class="form-group">
                                <label for="security_question">Security Question</label>
                                <select class="form-control" name="security_question" required>
                                    <option value="0">Select one question</option>
                                    <option value="1">Favorite color</option>
                                    <option value="2">Favorite Actor</option>
                                    <option value="3">Nick Name</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="security_answer">Security Answer</label>
                                <input type="text" class="form-control" name="security_answer"
                                    placeholder="Type the answer" required>
                            </div>

                            <div class="form-group">
                                <label for="new_password">New Password</label>
                                <input type="password" class="form-control" name="new_password" required>
                            </div>

                            <button type="submit" class="btn btn-primary btn-block" name="submit">Submit</button>
                        </form>
                    </div>
                    <div class="card-footer text-center">
                        <p class="mt-3">
                            <span>Remember your password?</span>
                            <a href="index.php" class="btn-link">Log in here</a>
                        </p>
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