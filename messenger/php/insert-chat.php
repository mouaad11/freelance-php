<?php 
    session_start();
    if(isset($_SESSION['user_id'])){
        include_once "config.php";
        $outgoing_id = $_SESSION['user_id'];
        $incoming_id = mysqli_real_escape_string($conn, $_POST['incoming_id']);
        $message = mysqli_real_escape_string($conn, $_POST['message']);
        
        // Insert the message into the messages table
        if(!empty($message)){
            $sql = "INSERT INTO messages (incoming_msg_id, outgoing_msg_id, msg)
                    VALUES ($incoming_id, $outgoing_id, '$message')";
            if(mysqli_query($conn, $sql)){
                echo "Message inserted successfully.";
            } else {
                echo "Error: " . mysqli_error($conn);
            }
        }
        
        // Update the user_interaction table to record the interaction between user_chat
        $sql_interaction = "SELECT * FROM user_interactions 
                            WHERE (user_id_1 = $outgoing_id AND user_id_2 = $incoming_id)
                            OR (user_id_1 = $incoming_id AND user_id_2 = $outgoing_id)";
        $result_interaction = mysqli_query($conn, $sql_interaction);
        
        if(mysqli_num_rows($result_interaction) == 0) {
            // If the interaction doesn't exist, insert it into the user_interaction table
            $insert_interaction = "INSERT INTO user_interactions (user_id_1, user_id_2)
                                    VALUES ($outgoing_id, $incoming_id)";
            if(mysqli_query($conn, $insert_interaction)){
                echo "User interaction recorded successfully.";
            } else {
                echo "Error: " . mysqli_error($conn);
            }
        }
    } else {
        header("location: ../login.php");
    }
?>
