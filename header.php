<?php
include ('db_connection.php');
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
error_reporting(0);

if (!isset($_SESSION["username"])) {
    header("location: index.php");
    exit();
}

$username = $_SESSION["username"];
?>
<nav class="navbar navbar-light bg-light p-3">
    <div class="container-fluid">
        <a class="navbar-brand" href="#" style="font-weight: bold; color: black;">SUGASAN</a>
        <button class="navbar-toggler d-md-none collapsed" type="button" data-toggle="collapse" data-target="#sidebar"
            aria-controls="sidebar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="col-md-5 col-lg-8 d-flex align-items-center justify-content-md-end mt-3 mt-md-0">
            <div class="dropdown">
                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton"
                    data-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-user"></i> Hello, <?php echo $username; ?><!-- Added user icon -->
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <li><a class="dropdown-item" href="logout.php"><i class="fas fa-sign-out-alt"></i> Sign out</a></li>

                </ul>
            </div>
        </div>
    </div>
</nav>