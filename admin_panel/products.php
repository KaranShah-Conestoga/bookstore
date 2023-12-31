<?php

$dbname = "Bookstore";
$servername = "localhost";
$username = "root";
$password = "";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["remove_isbn"])) {
        // Fetch the image path before deleting the book
        $stmtImagePath = $conn->prepare("SELECT Image FROM Book WHERE ISBN = :isbn");
        $stmtImagePath->bindParam(':isbn', $_POST["remove_isbn"]);
        $stmtImagePath->execute();
        $imagePath = $stmtImagePath->fetchColumn();

        // Delete the book
        $stmtDelete = $conn->prepare("DELETE FROM Book WHERE ISBN = :isbn");
        $stmtDelete->bindParam(':isbn', $_POST["remove_isbn"]);
        $stmtDelete->execute();

        // Delete the corresponding image file
        if ($imagePath && file_exists($imagePath)) {
            unlink($imagePath);
        }
    }

    // Fetch Book data
    $stmtBook = $conn->prepare("SELECT * FROM Book");
    $stmtBook->execute();
    $books = $stmtBook->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products</title>
    <style>
        *{
            margin: 0;
            padding: 0;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        h1 {
            text-align: center;
            background-color: #4285f4;
            padding: 2rem;
            color: white;
            width:100%

        }

        button {
            background-color: #4285f4;
            color: #fff;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            border-radius: 5px;
            margin-top: 15px;
            transition: background-color 0.3s ease;
            margin-bottom: 1rem;
        }

        button:hover {
            background-color: #3b74d9;
        }

        table {
            width: 80%;
            border-collapse: collapse;
            margin-top: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            background-color: #fff;
            border-radius: 8px;
            overflow: hidden;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #4285f4;
            color: #fff;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        img {
            max-width: 70px;
            max-height: 70px;
        }
        
    </style>
</head>

<body>

    <h1>Products</h1>
    <div>
        <button onclick="redirectToDashboard()">Dashboard</button>
    <button onclick="redirectToAddProduct()">Add Product</button>
    </div>

    <h2>Book Information Table</h2>
    <table>
        <tr>
            <th>ISBN</th>
            <th>Book Title</th>
            <th>Price</th>
            <th>Author</th>
            <th>Type</th>
            <th>Image</th>
            <th>Action</th>
        </tr>
        <?php foreach ($books as $book) : ?>
            <tr>
                <td><?php echo $book['ISBN']; ?></td>
                <td><?php echo $book['BookTitle']; ?></td>
                <td><?php echo $book['Price']; ?></td>
                <td><?php echo $book['Author']; ?></td>
                <td><?php echo $book['Type']; ?></td>
                <td><img src="<?php echo $book['Image']; ?>" alt="Book Image"></td>
                <td>
                    <form method="post" onsubmit="return confirm('Are you sure you want to remove this book?');">
                        <input type="hidden" name="remove_isbn" value="<?php echo $book['ISBN']; ?>">
                        <button type="submit">Remove</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
    <script>
        function redirectToAddProduct() {
            window.location.href = 'add_product.php';
        }

        function redirectToDashboard() {
            window.location.href = "admin_panel.php";
        }

    </script>
</body>

</html>