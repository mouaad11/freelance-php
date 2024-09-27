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

$work_query = "SELECT * from work";
$work_result = mysqli_query($connection,$work_query);

if ($work_result) {
    $total_work = mysqli_num_rows($work_result);
} else {
    $total_work = 0;
}


$report_query = "SELECT * from reports";
$report_result = mysqli_query($connection,$report_query);

if ($report_result) {
    $total_report = mysqli_num_rows($report_result);
} else {
    $total_report = 0;
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
    <link rel="stylesheet" href="styles/dashboard.css">
    <link rel="stylesheet" href="styles/dashboard.css">
    
    <title>dashboard</title>
</head>
<body>
<div class="wrapper">
    <?php include 'sidebar.php'; ?>
    <div class="main">
    <?php include 'nav.php'; ?>    
    <div class="container  mt-5  mb-5">
                    <div class="row  ">
                        <div class="col-lg-4">
                            <div class="card text-uppercase numproj mb-3">
                                <div class="card-body">
                                    <h4>Profit : </h4>
                                    <h1 class="text-center mt-3 text-black-50"><?php echo number_format($total_approved_project_price, 2); ?>$</h1>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="card text-uppercase profit mb-3">
                                <div class="card-body">
                                    <h4>Project number  : </h4>
                                    <h1 class="text-center mt-3 text-black-50"><?php echo number_format($total_work, 0); ?></h1>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 text-uppercase proj-num mb-3">
                            <div class="card numproj">
                                <div class="card-body">
                                    <h4>reports : </h4>
                                    <h1 class="text-center mt-3 text-black-50"><?php echo number_format($total_report, 0); ?></h1>
                                </div>
                            </div>
                        </div>
                        
                        
                    </div>
                </div>
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card crudtab">
                                <div class="card-header">
                                    <h4>details
                                       
                                        <select class="form-select float-end" aria-label="Default select example">
                                            <option selected>ALL</option>
                                            <option value="1">Last week</option>
                                            <option value="2">Last months</option>
                                            <option value="3">sponsoring requests</option>
                                            <option value="4">no freelancers</option>
                                          </select>
                                    </h4>
                                </div>
                                <div class="card-body  ">
            
                                    <table class="table table-bordered  text-center  table-dark custom-table">
                                        <thead>
                                            <tr>
                                                <th>PROJ-ID</th>
                                                <th>User-id</th>
                                                <th>Freelancer-id</th>
                                                <th>input-date</th>
                                                <th >Action</th>
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
    </div>
</div>
<?php include 'footer.php'; ?>    

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe"
        crossorigin="anonymous"></script>
        
 <script src="scripts/script.js"></script>

</body>


</html>