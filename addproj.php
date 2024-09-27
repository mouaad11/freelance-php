<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: seconnect.php");
    exit();
}

$user_id = $_SESSION['user_id'];

include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if all fields are set and not empty
    if (!empty($_POST['title']) && !empty($_POST['price']) && !empty($_POST['category']) && !empty($_POST['max_date'])) {
        $title = htmlspecialchars($_POST['title']);
        $description = htmlspecialchars($_POST['description']);
        $price = htmlspecialchars($_POST['price']);
        $category = htmlspecialchars($_POST['category']);
        $max_date = htmlspecialchars($_POST['max_date']);

        // Prepare the SQL statement for the publication
        $stmt = $connection->prepare("INSERT INTO publications (ClientID, Title, Description, Price, Category, MaxDate) VALUES (?, ?, ?, ?, ?, ?)");
        
        if ($stmt === false) {
            die("Prepare failed: " . htmlspecialchars($connection->error));
        }

        // Bind parameters
        $stmt->bind_param("issdss", $user_id, $title, $description, $price, $category, $max_date);

        // Execute the statement
        if ($stmt->execute()) {
            // Get the ID of the newly inserted publication
            $publication_id = $stmt->insert_id;

            // Handle file uploads
            if (!empty($_FILES['product_images']['name'][0])) {
                $upload_dir = 'uploads/'; // Directory to save the uploaded files

                // Create directory if it doesn't exist
                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }

                foreach ($_FILES['product_images']['tmp_name'] as $key => $tmp_name) {
                    $file_name = basename($_FILES['product_images']['name'][$key]);
                    $file_path = $upload_dir . $file_name;
                    if (move_uploaded_file($tmp_name, $file_path)) {
                        // Insert the image path into the images table
                        $stmt_img = $connection->prepare("INSERT INTO images (PublicationID, ImageURL) VALUES (?, ?)");
                        if ($stmt_img === false) {
                            die("Prepare failed: " . htmlspecialchars($connection->error));
                        }
                        $stmt_img->bind_param("is", $publication_id, $file_path);
                        $stmt_img->execute();
                        $stmt_img->close();
                    } else {
                        echo "Failed to upload image: " . htmlspecialchars($file_name) . "<br>";
                    }
                }
            }

            // Redirect users to a success page or perform other actions
            header("Location: home.php");
            exit();
        } else {
            echo "Error: " . htmlspecialchars($stmt->error);
        }

        // Close the statement
        $stmt->close();
    } else {
        // Handle form validation errors
        echo "Please fill in all required fields.";
    }
}

// Close the connection
$connection->close();
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
    <link rel="stylesheet" href="styles/addrpoj.css">
    <title>addprojet</title>
</head>
<body>
    <div class="wrapper">
        <?php include 'sidebar.php'; ?>
        <div class="main">
            <?php include 'nav.php'; ?>
            <div class="container-fluid">
                <form method="post" action="addproj.php" class="d-flex flex-column gap-3 text-white w-75 m-auto mt-5 p-5" enctype="multipart/form-data">
                    <label for="formFile" class="form-label text-white-50 h4">Entrer les informations de votre projet</label>
                    <input type="file" name="product_images[]" accept="image/*" class="form-control" multiple>
                    <div id="image-preview"></div>
                    <input type="text" name="title" class="form-control" placeholder="Titre" maxlength="100">
                    <input type="number" name="price" class="form-control" placeholder="Prix">
                    <select name="category" class="form-control">
                        <option value="">Sélectionner une catégorie</option>
                        <option value="webdev">Web</option>
                        <option value="design">Design</option>
                        <option value="mobile">Mobile</option>
                    </select>
                    <input type="date" name="max_date" class="form-control" placeholder="Date maximum">
                    <textarea name="description" placeholder="Description" class="form-control" id="myTextarea"></textarea>
                    <input type="submit" value="Valider" class="btn btn-primary">
                </form>
            </div>
        </div>
    </div>
    <?php include 'footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
    <script src="scripts/addproj.js"></script>
</body>
</html>
