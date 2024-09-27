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

// Fetch user information
$user_query = "SELECT * FROM users WHERE UserID = '$user_id'";
$user_result = mysqli_query($connection, $user_query);
$user = mysqli_fetch_assoc($user_result);

$userchat_query = "SELECT * FROM user_chat WHERE user_id = '$user_id'";
$userchat_result = mysqli_query($connection, $userchat_query);
$userchat = mysqli_fetch_assoc($userchat_result);


// Handle profile update
if (isset($_POST['save'])) {
    $update_query = "UPDATE users SET ";
    $updates = [];

    if (!empty($_POST['name'])) {
        $new_name = mysqli_real_escape_string($connection, $_POST['name']);
        $updates[] = "Username = '$new_name'";
    }
    if (!empty($_POST['phone'])) {
        $new_phone = mysqli_real_escape_string($connection, $_POST['phone']);
        $updates[] = "PhoneNum = '$new_phone'";
    }
    if (!empty($_POST['email'])) {
        $new_email = mysqli_real_escape_string($connection, $_POST['email']);
        $updates[] = "Email = '$new_email'";
    }

    if (!empty($updates)) {
        $update_query .= implode(", ", $updates);
        $update_query .= " WHERE UserID = '$user_id'";
        mysqli_query($connection, $update_query);

        // Update user_chat table
        $update_chat_query = "UPDATE user_chat SET ";

        if (!empty($_POST['name'])) {
            $new_name = mysqli_real_escape_string($connection, $_POST['name']);
            $update_chat_query .= "fname = '$new_name', ";
        }
        if (!empty($_POST['email'])) {
            $new_email = mysqli_real_escape_string($connection, $_POST['email']);
            $update_chat_query .= "email = '$new_email' ";
        }

        $update_chat_query = rtrim($update_chat_query, ", ");

        $update_chat_query .= " WHERE user_id = '$user_id'";

        mysqli_query($connection, $update_chat_query);
    }

    header("Location: profile.php");
    exit();
}

// Handle password change
if (isset($_POST['change_password'])) {
    $old_password = $_POST['old_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Fetch current password from database
    $password_query = "SELECT Password FROM users WHERE UserID = '$user_id'";
    $password_result = mysqli_query($connection, $password_query);
    $user_data = mysqli_fetch_assoc($password_result);
    $current_password = $user_data['Password'];

    if (password_verify($old_password, $current_password)) {
        if ($new_password === $confirm_password) {
            $new_password_hash = password_hash($new_password, PASSWORD_DEFAULT);
            $update_password_query = "UPDATE users SET Password = '$new_password_hash' WHERE UserID = '$user_id'";
            mysqli_query($connection, $update_password_query);
            echo "<script>alert('Password changed successfully!');</script>";
        } else {
            echo "<script>alert('New password and confirm password do not match!');</script>";
        }
    } else {
        echo "<script>alert('Old password is incorrect!');</script>";
    }
}

// Handle avatar change
if (isset($_POST['change_avatar'])) {
    $avatar = $_FILES['avatar']['name'];
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($avatar);
    move_uploaded_file($_FILES["avatar"]["tmp_name"], $target_file);
    $update_avatar_query = "UPDATE users SET ProfileImageURL = '$target_file' WHERE UserID = '$user_id'";
    mysqli_query($connection, $update_avatar_query);

    $update_avatarchat_query = "UPDATE user_chat SET img = '$target_file' WHERE user_id = '$user_id'";
    mysqli_query($connection, $update_avatarchat_query);
    header("Location: profile.php");
    exit();
}

// Fetch publications created by the user
$publication_query = "SELECT PublicationID, Title FROM publications WHERE ClientID = '$user_id'";
$publication_result = mysqli_query($connection, $publication_query);



if (isset($_POST['delete_publication'])) {
    $publication_id = $_POST['delete_publication'];
    $delete_query = "DELETE FROM publications WHERE PublicationID = '$publication_id' ";
    mysqli_query($connection, $delete_query);
    // Redirect back to the same page after deletion
    header("Location: {$_SERVER['PHP_SELF']}");
    exit();
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
    <link rel="stylesheet" href="styles/profile.css">    
    <link rel="stylesheet" href="styles/boss-dash.css">
    <link rel="stylesheet" href="styles/dashboard.css">
    <title>Profile</title>
</head>

<body>
    <div class="wrapper">
        <?php include 'sidebar.php'; ?>
        <div class="main">
            <?php include 'nav.php'; ?>
            <div class="content px-3 py-2">
                <div class="container-fluid mt-5 p-5">
                    <div class="row">
                        <div class="col-lg-5 mb-5 profile1 text-center">
                            <div class="">
                                <div class="card-body d-flex flex-column ">
                                   

                                    <?php $image_path = isset($userchat['img']) ? htmlspecialchars($userchat['img']) : 'images/avatar.PNG'; ?>
                                    <img src="<?= $image_path ?>"  alt="" class="text-center img-fluid mt-3 mb-5">
                                    <div class="d-flex justify-content-between text-black">
                                        <h5>My Profile</h5>
                                        <h6 class="w-50 text-black-50">If you want to change any information:</h6>
                                    </div>
                                    <form class="mt-4 d-flex flex-column" action="profile.php" method="POST">
                                        <div class="d-flex justify-content-between gap-4">
                                            <input type="text" name="name" placeholder="<?php echo isset($user['Username']) ? $user['Username'] : ''; ?>" class="form-control">
                                            <input type="number" name="phone" placeholder="<?php echo isset($user['PhoneNum']) ? $user['PhoneNum'] : ''; ?>" class="form-control">
                                        </div>
                                        <input type="email" name="email" class="form-control mt-2" placeholder="<?php echo isset($user['Email']) ? $user['Email'] : ''; ?>">
                                        <input type="submit" value="Confirm" class="btn btn-primary mt-3 w-50" name="save">
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-1"></div>
                        <div class="col-lg-6 d-flex flex-column text-center profile1">
                            <div class="h-50 mt-4"> 
                                <form class="d-flex flex-column gap-3 text-black" action="profile.php" method="POST">
                                    <label class="mb-3 mt-5 text-black-50" for="">Change Password</label>
                                    <input type="password" name="old_password" class="form-control" placeholder="Old Password">
                                    <input type="password" name="new_password" class="form-control" placeholder="New Password">
                                    <input type="password" name="confirm_password" class="form-control" placeholder="Confirm Password">
                                    <input type="submit" class="btn btn-primary mb-5" value="Confirm" name="change_password">
                                </form>
                            </div>
                            <div class="h-50 mb-4">
                                <form action="profile.php" method="POST" enctype="multipart/form-data" class="d-flex flex-column gap-4 mb-5">
                                    <label class="text-black-50" for="">Change Avatar:</label>
                                    <input type="file" name="avatar" accept="image/*" class="form-control">
                                    <input type="submit" class="btn btn-primary mb-4" value="Modify" name="change_avatar">
                                </form>
                            </div>
                        </div>
                        <div class="container mt-5 mb-5">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card crudtab">
                                        <div class="card-header">
                                            <h4 class="mt-2">My publications: 
                                                <a class="float-end" href="#">
                                                    <button class="btn btn-primary btn-lg">Add publication</button>
                                                </a>
                                            </h4>
                                        </div>
                                        <div class="card-body">
                                            <table class="table table-bordered text-center table-dark custom-table">
                                                <thead>
                                                    <tr>
                                                        <th>Publication ID</th>
                                                        <th>Title</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <!-- PHP loop to display publications -->
                                                    <?php while ($publication = mysqli_fetch_assoc($publication_result)) { ?>
                                                        <tr>
                                                            <td><?php echo $publication['PublicationID']; ?></td>
                                                            <td><?php echo $publication['Title']; ?></td>
                                                            <td class="d-flex gap-2">
                                                                <div>
                                                                    <a href="#" class="btn btn-info btn-sm">View publication</a>
                                                                </div>
                                                                <div>
                                                                    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" class="d-inline">
                                                                        <button type="submit" name="delete_publication" value="<?php echo $publication['PublicationID']; ?>" class="btn btn-danger btn-sm">Delete publication</button>
                                                                    </form>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    <?php } ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>     
                </div>
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
    <script src="scripts/script.js"></script>
</body>

</html>
