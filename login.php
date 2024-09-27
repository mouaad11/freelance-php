<?php
session_start();
require 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if user exists
    $sql = "SELECT * FROM users WHERE Email = ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['Password'])) {
        // Start session and register unique_id same as UserID
        $_SESSION['user_id'] = $user['UserID'];
        $_SESSION['unique_id'] = $user['UserID']; // Assuming unique_id is same as UserID
        header("Location: home.php");
        exit(); // Always exit after header redirect
    } else {
        header("Location: seconnect.php?error=invalid_login");
        exit(); // Always exit after header redirect
    }
}
?>
