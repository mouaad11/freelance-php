<?php
require 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    // Check if email already exists
    $sql = "SELECT * FROM users WHERE Email = ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        header("Location: seconnect.php?error=email_exists");
    } else {
        $insert_sql = "INSERT INTO users (Username, Email, Password) VALUES (?, ?, ?)";
        $stmt_insert = $connection->prepare($insert_sql);
        $stmt_insert->bind_param("sss", $name, $email, $password);
        
        if ($stmt_insert->execute()) {
            $user_id = $stmt_insert->insert_id;
            $insert_chat_sql = "INSERT INTO user_chat (user_id, fname, email, password) VALUES (?, ?, ?, ?)";
            $stmt_insert_chat = $connection->prepare($insert_chat_sql);
            $stmt_insert_chat->bind_param("isss", $user_id, $name, $email, $password);

            if ($stmt_insert_chat->execute()) {
                header("Location: seconnect.php?success=account_created");
            } else {
                header("Location: seconnect.php?error=signup_failed");
            }
        } else {
            header("Location: seconnect.php?error=signup_failed");
        }
    }
}
?>
