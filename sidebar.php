<?php
require 'config.php';

$query = "SELECT Username, Role FROM users WHERE UserID = '$user_id'";
$result = mysqli_query($connection, $query);
$user = mysqli_fetch_assoc($result);

$role = isset($user['Role']) ? $user['Role'] : "";

// Function to check if the user is admin
function isUserAdmin($role) {
    return strtolower($role) === "admin";
}
function isUserBoss($role) {
    return strtolower($role) === "boss";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://kit.fontawesome.com/ae360af17e.js" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <link rel="stylesheet" href="styles/style.css">
    <link rel="stylesheet" href="styles/footer.css">
    <title>Sidebar</title>
</head>

<body>
    <aside id="sidebar">
        <div class="h-100">
            <div class="sidebar-logo">
                <img src="images/image-ensa.png" class="img-fluid" alt="">
            </div>
            <!-- Sidebar Navigation -->
            <ul class="sidebar-nav">
                <li class="sidebar-header">
                    Service client
                </li>
                <li class="sidebar-item">
                    <a href="home.php" class="sidebar-link">
                        <i class="fa-solid fa-list pe-2"></i>
                        Home
                    </a>
                </li>
                <!-- <li class="sidebar-item">
                    <a href="#" class="sidebar-link collapsed" data-bs-toggle="collapse" data-bs-target="#pages" aria-expanded="false" aria-controls="pages">
                        <i class="fa-regular fa-file-lines pe-2"></i>
                        Categorie
                    </a>
                    <ul id="pages" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
                        <li class="sidebar-item">
                            <a href="#" class="sidebar-link">Web</a>
                        </li>
                        <li class="sidebar-item">
                            <a href="#" class="sidebar-link">Mobile</a>
                        </li>
                        <li class="sidebar-item">
                            <a href="#" class="sidebar-link">Design</a>
                        </li>
                    </ul>
                </li> -->
                <li class="sidebar-item">
                    <a href="messenger/users.php?id=<?=$user_id ?>" class="sidebar-link">
                        <i class="fa-solid fa-comments me-2"></i>
                        Chat
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="profile.php" class="sidebar-link">
                        <i class="fa-regular fa-user  me-2"></i>
                        profile
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="addproj.php" class="sidebar-link">
                        <i class="fa-solid fa-plus pe-2"></i>
                        addproject
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="report.php" class="sidebar-link">
                        <i class="fa-regular fa-flag pe-2"></i>
                        reports
                    </a>
                </li>
                <?php if (isset($_SESSION['user_id'])) : ?>
                <li class="sidebar-item">
                    <a href="logout.php" class="sidebar-link">
                        <i class="fa-solid fa-arrow-right-from-bracket"></i>
                        sign out
                    </a>
                </li>
                <?php endif ?>
                <?php if (!isset($_SESSION['user_id'])) : ?>
                <li class="sidebar-item">
                    <a href="#" class="sidebar-link collapsed" data-bs-toggle="collapse" data-bs-target="#auth" aria-expanded="false" aria-controls="auth">
                        <i class="fa-regular fa-user pe-2"></i>
                        Login
                    </a>
                    <ul id="auth" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
                        <li class="sidebar-item">
                            <a href="seconnect.php" class="sidebar-link">Login</a>
                        </li>
                    </ul>
                </li>
                <?php endif ?>
                <?php if ((isUserBoss($role) || isUserAdmin($role))) : ?>
                <li class="sidebar-header">
                    Admin space
                </li>
                <li class="sidebar-item">
                    <a href="#" class="sidebar-link collapsed" data-bs-toggle="collapse" data-bs-target="#multi" aria-expanded="false" aria-controls="multi">
                        <i class="fa-solid fa-share-nodes pe-2"></i>
                        Dashboard
                    </a>
                    <ul id="multi" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
                        <li class="sidebar-item">
                            <a href="#" class="sidebar-link collapsed" data-bs-toggle="collapse" data-bs-target="#multi-two" aria-expanded="false" aria-controls="multi-two">
                                Admin Panel
                            </a>
                            <ul id="multi-two" class="sidebar-dropdown list-unstyled collapse">
                                <li class="sidebar-item">
                                    <a href="dash.php" class="sidebar-link">admin board</a>
                                </li>
                                <?php if ((isUserBoss($role))) : ?>
                                <li class="sidebar-item">
                                    <a href="bossdash.php" class="sidebar-link">boss board</a>
                                </li>
                                <?php endif ?>
                            </ul>
                        </li>
                    </ul>
                    <?php endif ?>
                </li>
            </ul>
        </div>
    </aside>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
</body>

</html>
