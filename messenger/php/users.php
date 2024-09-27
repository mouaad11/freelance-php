<?php
session_start();
include_once "config.php";

// Get the unique ID of the logged-in user
$outgoing_id = $_SESSION['user_id'];

// Fetch user_chat from the database who have interacted with the logged-in user
$sql = "SELECT user_chat.*
        FROM user_chat
        JOIN user_interactions ON (user_chat.user_id = user_interactions.user_id_1 AND user_interactions.user_id_2 = $outgoing_id)
                               OR (user_chat.user_id = user_interactions.user_id_2 AND user_interactions.user_id_1 = $outgoing_id)
        WHERE user_chat.user_id != $outgoing_id
        ORDER BY user_chat.user_id DESC";
$query = mysqli_query($conn, $sql);

$output = "";

if(mysqli_num_rows($query) > 0) {
   
    include_once "data.php";
} else {
    $output .= "No users are available to chat";
}

echo $output;
?>
