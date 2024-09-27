<?php
session_start();
include 'config.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

if(empty($_SESSION['user_id']) ){
    $user_id = '0' ;

}else{
    $user_id = $_SESSION['user_id'];
}
$query = "SELECT publications.*, users.Username, images.ImageURL 
          FROM publications 
          INNER JOIN users ON publications.ClientID = users.UserID 
          LEFT JOIN images ON publications.PublicationID = images.PublicationID"; 

$result = mysqli_query($connection, $query);


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <link rel="stylesheet" href="styles/style.css">
    <link rel="stylesheet" href="styles/project.css">
    <link rel="stylesheet" href="styles/footer.css">
    <script src="https://kit.fontawesome.com/ae360af17e.js" crossorigin="anonymous"></script>
</head>
<body>
    <div class="wrapper">
        <?php include 'sidebar.php'; ?>
        <div class="main">
            <?php include 'nav.php'; ?>
            <?php include 'menu.php'; ?>
        </div>
    </div>
    <?php include 'footer.php'; ?>
</body>
</html>
