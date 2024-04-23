<?php
include ('db_connection.php');
session_start();
error_reporting(0); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generate Monthly Report</title>
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
                <div class="container p-4 mt-5">
                    <h2 class="mb-4 text-center font-weight-bold"><i class="fas fa-file"></i> Generate Monthly Report
                    </h2>
                    <!-- Generate report form -->
                    <form action="process_report.php" method="post" class="mx-auto" style="max-width: 500px;">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="month" class="form-label"><i class="fas fa-calendar"></i> Month</label>
                                    <select class="form-select" id="month" name="month" required>
                                        <option value="" disabled selected>Select Month</option>
                                        <option value="01">January</option>
                                        <option value="02">February</option>
                                        <option value="03">March</option>
                                        <option value="04">April</option>
                                        <option value="05">May</option>
                                        <option value="06">June</option>
                                        <option value="07">July</option>
                                        <option value="08">August</option>
                                        <option value="09">September</option>
                                        <option value="10">October</option>
                                        <option value="11">November</option>
                                        <option value="12">December</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="year" class="form-label"><i class="fas fa-calendar"></i> Year</label>
                                    <input type="text" id="year" name="year" class="form-control"
                                        placeholder="Enter Year" required>
                                </div>
                            </div>
                        </div>

                        <div class="text-center mt-3">
                            <button type="submit" class="btn btn-primary"><i class="fas fa-file"></i> Generate
                                Report</button>
                        </div>
                    </form>
                </div>
            </main>
            <?php include 'footer.php'; ?>
        </div>
    </div>

    <?php
    if (isset($_GET['message']) && $_GET['message'] !== '') {
        echo '<script>alert("' . $_GET['message'] . '")</script>';
    }
    ?>
</body>

</html>