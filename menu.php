<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include 'config.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);




$query = "SELECT publications.*, users.Username, MIN(images.ImageURL) AS ImageURL
          FROM publications
          INNER JOIN users ON publications.ClientID = users.UserID
          LEFT JOIN images ON publications.PublicationID = images.PublicationID
          GROUP BY publications.PublicationID";
    

$result = mysqli_query($connection, $query);


?>

<div class="projects mt-5 mb-5">
    <?php while ($row = mysqli_fetch_assoc($result)) : ?>
         <div class="project1 d-flex justify-content-between pe-1" id="<?= htmlspecialchars($row['PublicationID']) ?>" onclick="redirectToDetails(<?= $row['PublicationID'] ?>)">
            <div class="d-flex gap-3">
                <?php
                $imageURL = isset($row['ImageURL']) ? htmlspecialchars($row['ImageURL']) : 'images/gray-img.png';
                ?>
                <img src="<?= $imageURL ?>" class="img-fluid p-1 projectimg" alt="">
                <div class="mt-3 desc text-white d-flex flex-column">
                    <p class="title"><?= htmlspecialchars($row['Title']) ?></p>
                    <p class="title2">Created by: <?= htmlspecialchars($row['Username']) ?></p>
                    <div class="prix title"><?= htmlspecialchars($row['Price']) ?> $</div>
                </div>
            </div>
        </div>
    <?php endwhile; ?>
</div>

<script>
function redirectToDetails(proj_id) {
    window.location.href = 'details.php?proj_id=' + proj_id;
}
</script>

