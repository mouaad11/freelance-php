<?php
session_start();
// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: seconnect.php");
    exit();
}
$user_id = $_SESSION['user_id'];

include 'config.php'; // Include the file where the database connection is established

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if all fields are set and not empty
    if (isset($_POST['publication_id']) && isset($_POST['admin_id']) && isset($_POST['report_description'])) {
        $publication_id = $_POST['publication_id'];
        $admin_id = $_POST['admin_id'];
        $report_description = $_POST['report_description'];

        // Prepare and execute the SQL statement to insert data into reports table
        $stmt = $connection->prepare("INSERT INTO reports (ReporterID, ReportedPublicationID, ReportedUserID, Reason) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiis", $user_id, $publication_id, $admin_id, $report_description);
        $stmt->execute();

        // Redirect user to a success page or perform other actions
        header("Location: report.php");
        exit();
    } else {
        // Handle form validation errors
        echo "Please fill in all required fields.";
    }
}

// Fetch publications created by the user
$stmt = $connection->prepare("SELECT PublicationID, Title FROM publications WHERE ClientID = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$publications = $result->fetch_all(MYSQLI_ASSOC);
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


    <title>report</title>
</head>

<body>
    <div class="wrapper">
        <?php include 'sidebar.php'; ?>
        <div class="main">
            <?php include 'nav.php'; ?>
            <div class="container">
                <div class="row m-auto  text-center ">
                    <div class="col-lg-4   p-5 text-white">
                        <div class="infobord p-2">
                            <div class="d-flex flex-column gap-3">
                                <h4>phone : </h4>
                                <h6 class="text-white-50">06-66-66-66-66</h6>
                            </div>
                            <div class="d-flex flex-column gap-3">
                                <h4>email:</h4>
                                <h6 class="text-white-50">test@gmail.com</h6>
                            </div>
                            <div class="d-flex flex-column gap-3">
                                <h4>address:</h4>
                                <h6 class="text-white-50">fff ffff , maroc</h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-8 text-center ">
                        <form action="report.php" method="post" class="d-flex flex-column gap-3 text-white  m-auto mt-5 p-5">
                            <div class="row ">
                                <div class="col-lg-6">
                                    <select name="publication_id" class="form-select w-100" aria-label="Default select example">
                                        <option value="" selected>Choisissez la  publication</option>
                                        <?php foreach ($publications as $publication): ?>
                                            <option value="<?php echo $publication['PublicationID']; ?>"><?php echo $publication['Title']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-lg-6">
                                    <input type="text" name="admin_id" class="form-control" placeholder="admin-id">
                                </div>
                            </div>
                            <textarea name="report_description" class="form-control" placeholder="Description du rapport" id="myTextarea"></textarea>

                            <input type="submit" class="btn btn-primary" value="Valider le rapport">
                        </form>
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
    <script src="scripts/addproj.js">

    </script>

</body>

</html>
