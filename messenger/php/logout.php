<?php
    session_start();
    if(isset($_SESSION['user_id'])){
        include_once "config.php";
        $logout_id = mysqli_real_escape_string($conn, $_GET['logout_id']);
        if(isset($logout_id)){
            $status = "Offline now";
            $sql = mysqli_query($conn, "UPDATE user_chat SET status = '{$status}' WHERE user_id={$_GET['logout_id']}");
            if($sql){
                session_unset();
                session_destroy();
                header("location: ../../seconnect.php");
            }
        }else{
            header("location: ../users.php");
        }
    }else{  
        header("location: ../../seconnect.php");
    }
?>