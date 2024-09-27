<?php
session_start();
require 'config.php';

if(empty($_SESSION['user_id']) ){
    $user_id = '0';
} else {
    $user_id = $_SESSION['user_id'];
}

if (isset($_GET['proj_id'])) {
    $proj_id = intval($_GET['proj_id']);

    // Query to fetch project details
    $project_query = "SELECT * FROM publications WHERE PublicationID = $proj_id";
    $project_result = mysqli_query($connection, $project_query);

    if ($project_result && mysqli_num_rows($project_result) > 0) {
        $project = mysqli_fetch_assoc($project_result);

        // Fetch WorkLink associated with the project
        $work_link_query = "SELECT WorkLink FROM work WHERE PublicationID = $proj_id";
        $work_link_result = mysqli_query($connection, $work_link_query);

        if ($work_link_result && mysqli_num_rows($work_link_result) > 0) {
            $work_link_row = mysqli_fetch_assoc($work_link_result);
            $work_link = $work_link_row['WorkLink'];
        } else {
            $work_link = ''; // Set default value if WorkLink is not found
        }

        // Fetch images associated with the project
        $images_query = "SELECT ImageURL FROM images WHERE PublicationID = $proj_id";
        $images_result = mysqli_query($connection, $images_query);
        $images = [];

        if ($images_result && mysqli_num_rows($images_result) > 0) {
            while ($row = mysqli_fetch_assoc($images_result)) {
                $images[] = $row['ImageURL'];
            }
        }
    } else {
        echo "Project not found.";
        exit();
    }
} else {
    echo "No project selected.";
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <link rel="stylesheet" href="styles/SD-style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <title>Details</title>
</head>

<body>
    <div class="wrapper">
        <?php include 'sidebar.php'; ?>
        <div class="main">
            <?php include 'nav.php'; ?>
            <div class="content px-3 py-2">
                <div class="service-details">
                    <h2 class="text-white-50 text-center"><?php echo $project['Title']; ?></h2>
                    <div class="image-slider">
                        <div class="slides">
                            <?php foreach ($images as $index => $image) { ?>
                                <img src="<?php echo htmlspecialchars($image); ?>" alt="Slide <?php echo $index + 1; ?>">
                            <?php } ?>
                        </div>
                        <button class="prev" onclick="changeSlide(-1)">&#10094;</button>
                        <button class="next" onclick="changeSlide(1)">&#10095;</button>
                    </div>
                    <div class="mini-pictures">
                        <?php foreach ($images as $index => $image) { ?>
                            <img src="<?php echo htmlspecialchars($image); ?>" alt="Mini Slide <?php echo $index + 1; ?>" onclick="showSlide(<?php echo $index; ?>)">
                        <?php } ?>
                    </div>
                    <div class="description-box">
                        <div class="description">
                            <p class="text-white">Description:</p>
                            <p class="text-white-50"><?php echo $project['Description']; ?></p>
                        </div>
                        <div class="price text-white">
                            <p>Price: $<?php echo $project['Price']; ?></p>
                        </div>
                    </div>
                    <div class="link-input">
                        <label for="link">Input Link: </label>
                        <input type="url" id="link" name="link" placeholder="" value="<?php echo isset($work_link) ? $work_link : ''; ?>">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include 'footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
    <script src="scripts/script.js"></script>
    <script src="scripts/details.js"></script>
</body>

</html>
