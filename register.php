<?php
include ('db_connection.php');
session_start();
error_reporting(0);

if (isset($_POST["submit"])) {
    $username = mysqli_real_escape_string($con, $_POST["username"]);
    $password = mysqli_real_escape_string($con, $_POST["password"]);
    $confirm_password = mysqli_real_escape_string($con, $_POST["confirm_password"]);
    $security_question = mysqli_real_escape_string($con, $_POST["security_question"]);
    $security_answer = mysqli_real_escape_string($con, $_POST["security_answer"]);

    // Check if passwords match
    if ($password !== $confirm_password) {
        echo '<script>alert("Passwords do not match");</script>';
    } else {
        // Check if the username already exists
        $check_query = "SELECT * FROM users WHERE username='$username'";
        $result = mysqli_query($con, $check_query);

        if (mysqli_num_rows($result) > 0) {
            echo '<script>alert("Username already exists. Please choose a different username.");</script>';
        } else {
            // Hash the password for storage
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insert user data into the database
            $insert_query = "INSERT INTO users (username, password, security_question_id, security_answer) 
                             VALUES ('$username', '$hashed_password', '$security_question', '$security_answer')";
            if (mysqli_query($con, $insert_query)) {
                echo '<script>alert("Registration done! Redirecting to login..."); window.location.href = "index.php";</script>';
            } else {
                echo "Error: " . $insert_query . "<br>" . mysqli_error($con);
            }
        }
    }

    mysqli_close($con);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css">
</head>

<body>
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header text-center">
                        <h1>Welcome to our platform</h1>
                    </div>
                    <div class="card-body">
                        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>"
                            onsubmit="return validateForm()">
                            <div class="form-group">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="username" placeholder="Username"
                                        required>
                                    <div class="input-group-append">
                                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="input-group">
                                    <input type="password" class="form-control" id="password" name="password"
                                        placeholder="Password" required minlength="8"
                                        pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}"
                                        title="Must contain at least one number, one uppercase, and one lowercase letter, and at least 8 or more characters">
                                    <div class="input-group-append">
                                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="input-group">
                                    <input type="password" class="form-control" id="confirm_password"
                                        name="confirm_password" placeholder="Confirm password" required>
                                    <div class="input-group-append">
                                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <select class="form-control" id="security_question" name="security_question" required>
                                    <option value="0">Select one question</option>
                                    <option value="1">Favorite color</option>
                                    <option value="2">Favorite Actor</option>
                                    <option value="3">Nick Name</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="security_answer"
                                        placeholder="Type the answer" required>
                                    <div class="input-group-append">
                                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary btn-block" name="submit">Create
                                Account</button>
                        </form>
                    </div>
                    <div class="card-footer text-center">
                        <p>
                            <span>Already have an account?</span>
                            <a href="index.php" class="btn-link">Sign in here</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script type="text/javascript">
        function validateForm() {
            var password = document.getElementById("password").value;
            var confirmPassword = document.getElementById("confirm_password").value;
            var securityQuestion = document.getElementById("security_question").value;

            if (password !== confirmPassword) {
                alert("Passwords do not match");
                return false;
            }

            if (securityQuestion === "0") {
                alert("Please select a security question");
                return false;
            }

            return true;
        }
    </script>
</body>

</html>