<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bookstore"; // Replace with your actual database name

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['submit'])) {
    $bookTitle = $_POST['bookTitle'];
    $ISBN = $_POST['ISBN'];
    $price = $_POST['price'];
    $author = $_POST['author'];
    $type = $_POST['type'];

    // File upload handling
    $targetDirectory = "image/";
    $targetFile = $targetDirectory . basename($_FILES["image"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    // Check if the image file is a actual image or fake image
    $check = getimagesize($_FILES["image"]["tmp_name"]);
    if ($check === false) {
        echo "File is not an image.";
        $uploadOk = 0;
    }

    // Check if file already exists
    if (file_exists($targetFile)) {
        echo "Sorry, file already exists.";
        $uploadOk = 0;
    }

    // Check file size (you can adjust the size limit)
    if ($_FILES["image"]["size"] > 500000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Allow certain file formats (you can add more formats if needed)
    $allowedFormats = array("jpg", "jpeg", "png", "gif");
    if (!in_array($imageFileType, $allowedFormats)) {
        echo "Sorry, only JPG, JPEG, PNG, and GIF files are allowed.";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
    } else {
        // If everything is ok, try to upload the file
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
            echo "The file " . htmlspecialchars(basename($_FILES["image"]["name"])) . " has been uploaded.";

            // Insert data into the database
            $sql = "INSERT INTO Book ( BookTitle, ISBN, Price, Author, Type, Image) 
                    VALUES ( '$bookTitle', '$ISBN', $price, '$author', '$type', '$targetFile')";

            if ($conn->query($sql) === TRUE) {
                echo "Product added successfully.";
                header("Location: add_product.php");
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
}

$conn->close();
