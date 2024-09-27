<?php
session_start();
require 'config.php';

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: seconnect.php");
    exit();
}

// Get the logged-in user's ID
$user_id = $_SESSION['user_id'];

$projects_query = "SELECT SUM(p.Price) * 0.05 AS TotalApprovedProjectPrice
FROM publications p
JOIN work w ON p.PublicationID = w.PublicationID
WHERE w.Status = 'Approved';";

$projects_result = mysqli_query($connection, $projects_query);

if ($projects_result) {
    $row = mysqli_fetch_assoc($projects_result);
    $total_approved_project_price = $row['TotalApprovedProjectPrice'];
} else {
    $total_approved_project_price = 0;
}

$project_query = "SELECT * from publications";
$project_result = mysqli_query($connection,$project_query);

if ($project_result) {
    $total_project = mysqli_num_rows($project_result);
} else {
    $total_project = 0;
}

$report_query = "SELECT * from reports";
$report_result = mysqli_query($connection,$report_query);

if ($report_result) {
    $total_report = mysqli_num_rows($report_result);
} else {
    $total_report = 0;
}

$nbadmin_query = "SELECT * from users where role = 'admin'";
$nbadmin_result = mysqli_query($connection,$nbadmin_query);

if ($nbadmin_result) {
    $nbadmin = mysqli_num_rows($nbadmin_result);
} else {
    $nbadmin = 0;
}

$nbusers_query = "SELECT * from users";
$nbusers_result = mysqli_query($connection,$nbusers_query);

if ($nbusers_result) {
    $nbusers = mysqli_num_rows($nbusers_result);
} else {
    $nbusers = 0;
}

$admins_query = "
    SELECT UserID,ReportNumber,AcceptedProjectsCount,DeclinedProjectsCount FROM users where role ='admin'
";

$admins_result = mysqli_query($connection, $admins_query);

if(isset($_POST['delete_admin'])) {
    $admin_id = $_POST['delete_admin'];
    $delete_query = "DELETE FROM users WHERE UserID = $admin_id";
    mysqli_query($connection, $delete_query);
}



$works_query = "
    SELECT 
        w.WorkID,
        w.PublicationID,
        p.ClientID,
        w.DeveloperID,
        w.DateSubmitted,
        w.WorkLink
    FROM work w
    JOIN publications p ON w.PublicationID = p.PublicationID
";

$works_result = mysqli_query($connection, $works_query);


if(isset($_POST['accept_work'])) {
    $work_id = $_POST['accept_work'];
    $update_query = "UPDATE work SET Status = 'Approved' WHERE WorkID = $work_id";
    mysqli_query($connection, $update_query);
}

if(isset($_POST['delete_work'])) {
    $work_id = $_POST['delete_work'];
    $delete_query = "DELETE FROM work WHERE WorkID = $work_id";
    mysqli_query($connection, $delete_query);
}

$works_query = "
    SELECT 
        w.WorkID,
        w.PublicationID,
        p.ClientID,
        w.DeveloperID,
        w.DateSubmitted,
        w.WorkLink
    FROM work w
    JOIN publications p ON w.PublicationID = p.PublicationID
";

$works_result = mysqli_query($connection, $works_query);


if(isset($_POST['accept_work'])) {
    $work_id = $_POST['accept_work'];
    $update_query = "UPDATE work SET Status = 'Approved' WHERE WorkID = $work_id";
    mysqli_query($connection, $update_query);
    $increment_query = "UPDATE work SET AcceptedProjectsCount = AcceptedProjectsCount + 1 WHERE WorkID = $work_id";
    mysqli_query($connection, $increment_query);
}

if(isset($_POST['delete_work'])) {
    $work_id = $_POST['delete_work'];
    $delete_query = "DELETE FROM work WHERE WorkID = $work_id";
    mysqli_query($connection, $delete_query);
}

$reports_query = "
    SELECT ReportID,ReporterID,ReportedUserID,Reason
    FROM reports
";

$reports_result = mysqli_query($connection, $reports_query);



?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://kit.fontawesome.com/ae360af17e.js" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <link rel="stylesheet" href="styles/addrpoj.css">
    <link rel="stylesheet" href="styles/boss-dash.css">
    <link rel="stylesheet" href="styles/dashboard.css">
    <title>bass dashboard</title>
</head>

<body>
    <div class="wrapper">
        <?php include 'sidebar.php'; ?>
        <div class="main">
            <?php include 'nav.php'; ?>
            <div class="container mt-5 mb-5">
                <div class="row">
                    <div class="col-lg-4">
                        <div class="card text-uppercase numproj mb-3">
                            <div class="card-body">
                                <h4>Profit:</h4>
                                <h1 class="text-center mt-3 text-black-50"><?php echo number_format($total_approved_project_price, 2); ?>$</h1>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="card text-uppercase profit mb-3">
                            <div class="card-body">
                                <h4>Project number:</h4>
                                <h1 class="text-center mt-3 text-black-50"><?php echo number_format($total_project, 0); ?></h1>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 text-uppercase proj-num mb-4">
                        <div class="card numproj">
                            <div class="card-body">
                                <h4>Reports:</h4>
                                <h1 class="text-center mt-3 text-black-50"><?php echo number_format($total_report, 0); ?></h1>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="card text-uppercase numproj mb-3">
                            <div class="card-body">
                                <h4>Admin number:</h4>
                                <h1 class="text-center mt-3 text-black-50"><?php echo number_format($nbadmin, 0); ?></h1>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="card text-uppercase profit mb-3">
                            <div class="card-body">
                                <h4>Users number:</h4>
                                <h1 class="text-center mt-3 text-black-50"><?php echo number_format($nbusers, 0); ?></h1>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 text-uppercase proj-num mb-3">
                        <div class="card numproj">
                            <div class="card-body">
                                <h4>Satisfaction:</h4>
                                <h1 class="text-center mt-3 text-black-50">3</h1>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="container mb-5 mt-5">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card crudtab">
                            <div class="card-header d-flex justify-content-between">
                                <h4>Details Admins</h4>
                                <div class="float-end">
                                    <button class="btn btn-primary w-50">Add Admin</button>
                                    <select class="form-select float-end w-50" aria-label="Default select example">
                                        <option selected>ALL</option>
                                        <option value="1">Report Descending</option>
                                        <option value="2">Report Ascending</option>
                                        <option value="3">Accept Ascending</option>
                                        <option value="4">Accept Descending</option>
                                    </select>
                                </div>
                            </div>
                            <div class="card-body">
                                <table class="table table-bordered text-center table-dark custom-table">
                                    <thead>
                                        <tr>
                                            <th>Admin ID</th>
                                            <th>Report Number</th>
                                            <th>Accept Number</th>
                                            <th>Decline Number</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                                if ($admins_result && mysqli_num_rows($admins_result) > 0) {
                                                    while ($row = mysqli_fetch_assoc($admins_result)) {
                                                        echo "<tr>";
                                                        echo "<td>" . $row['UserID'] . "</td>";
                                                        echo "<td>" . $row['ReportNumber'] . "</td>";
                                                        echo "<td>" . $row['AcceptedProjectsCount'] . "</td>";
                                                        echo "<td>" . $row['DeclinedProjectsCount'] . "</td>";
                                                        echo "<td class='d-flex gap-2'>
                                                                <div>
                                                                <a href='profile.php?userid ='" . $row['UserID'] . "' class='mb-2 btn btn-info btn-sm'>View-Admin</a>
                                                                <a href='' class='mb-2 btn btn-info btn-sm' target='_blank'>View-Reports</a>
                                                                </div>
                                                                <div>
                                                                    <form action='' method='POST' class='d-inline'>
                                                                        <button type='submit' name='delete_admin' value='" . $row['UserID'] . "' class='btn btn-danger btn-sm mb-2'>Delete Access</button>
                                                                    </form>
                                                                </div>

                                                            </td>";
                                                        echo "</tr>";
                                                    }
                                                } else {
                                                    echo "<tr><td colspan='5'>No works found</td></tr>";
                                                }
                                            ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="container mb-5">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card crudtab">
                            <div class="card-header">
                                <h4>Details Projects
                                    <select class="form-select float-end" aria-label="Default select example">
                                        <option selected>ALL</option>
                                        <option value="1">Last Week</option>
                                        <option value="2">Last Month</option>
                                        <option value="3">Sponsoring Requests</option>
                                        <option value="4">No Freelancers</option>
                                    </select>
                                </h4>
                            </div>
                            <div class="card-body">
                                <table class="table table-bordered text-center table-dark custom-table">
                                    <thead>
                                        <tr>
                                            <th>Proj ID</th>
                                            <th>User ID</th>
                                            <th>Freelancer ID</th>
                                            <th>Input Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                                if ($works_result && mysqli_num_rows($works_result) > 0) {
                                                    while ($row = mysqli_fetch_assoc($works_result)) {
                                                        echo "<tr>";
                                                        echo "<td>" . $row['PublicationID'] . "</td>";
                                                        echo "<td>" . $row['ClientID'] . "</td>";
                                                        echo "<td>" . $row['DeveloperID'] . "</td>";
                                                        echo "<td>" . $row['DateSubmitted'] . "</td>";
                                                        echo "<td class='d-flex gap-2'>
                                                                <div>
                                                                <a href='details.php?proj_id=" . $row['PublicationID'] . "' class='mb-2 btn btn-info btn-sm'>View-project</a>
                                                                <a href='" . $row['WorkLink'] . "' class='mb-2 btn btn-info btn-sm' target='_blank'>View-input</a>
                                                                </div>
                                                                <div>
                                                                    <form action='' method='POST' class='d-inline'>
                                                                        <button type='submit' name='accept_work' value='" . $row['WorkID'] . "' class='btn btn-success btn-sm mb-2'>Accept</button>
                                                                    </form>
                                                                    <form action='' method='POST' class='d-inline'>
                                                                        <button type='submit' name='delete_work' value='" . $row['WorkID'] . "' class='btn btn-danger btn-sm mb-2'>Delete</button>
                                                                    </form>
                                                                </div>

                                                            </td>";
                                                        echo "</tr>";
                                                    }
                                                } else {
                                                    echo "<tr><td colspan='5'>No works found</td></tr>";
                                                }
                                            ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="container mb-5">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card crudtab">
                            <div class="card-header">
                                <h4>Reports
                                    <select class="form-select float-end" aria-label="Default select example">
                                        <option selected>ALL</option>
                                        <option value="1">Account</option>
                                        <option value="2">Product</option>
                                        <option value="3">Sponsoring Requests</option>
                                        <option value="4">No Freelancers</option>
                                    </select>
                                </h4>
                            </div>
                            <div class="card-body">
                                <table class="table table-bordered text-center table-dark custom-table">
                                    <thead>
                                        <tr>
                                            <th>User ID</th>
                                            <th>Admin ID</th>
                                            <th>Report Type</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                                if ($reports_result && mysqli_num_rows($reports_result) > 0) {
                                                    while ($row = mysqli_fetch_assoc($reports_result)) {
                                                        echo "<tr>";
                                                        echo "<td>" . $row['ReporterID'] . "</td>";
                                                        echo "<td>" . $row['ReportedUserID'] . "</td>";
                                                        echo "<td>" . $row['Reason'] . "</td>";
                                                        echo "<td class='d-flex gap-2'>
                                                                <div>
                                                                <a href='' class='mb-2 btn btn-info btn-sm' target='_blank'>View Report</a>
                                                                <a href='' class='mb-2 btn btn-info btn-sm' target='_blank'>View Admin</a>
                                                                </div>
                                                                <div>
                                                                    <form action='' method='POST' class='d-inline'>
                                                                        <button type='submit' name='respod' value='report_id' class='btn btn-success btn-sm mb-2'>Respond</button>
                                                                    </form>
                                                                    <form action='' method='POST' class='d-inline'>
                                                                        <button type='submit' name='decline' value='report_id' class='btn btn-danger btn-sm mb-2'>Declines</button>
                                                                    </form>
                                                                </div>

                                                            </td>";
                                                        echo "</tr>";
                                                    }
                                                } else {
                                                    echo "<tr><td colspan='5'>No works found</td></tr>";
                                                }
                                            ?>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
    <script src="scripts/script.js"></script>
</body>

<?php include 'footer.php'; ?>

</html>
