<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        form {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f9f9f9;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #333;
        }

        input {
            width: 100%;
            padding: 8px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        button {
            background-color: #4caf50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }
    </style>
</head>

<body>
    <h2>Add Product</h2>
    <form action="process_add_product.php" method="post" enctype="multipart/form-data">
     
        <label for="bookTitle">BookTitle:</label>
        <input type="text" name="bookTitle" required><br>

        <label for="ISBN">ISBN:</label>
        <input type="text" name="ISBN" required><br>

        <label for="price">Price:</label>
        <input type="text" name="price" required><br>

        <label for="author">Author:</label>
        <input type="text" name="author" required><br>

        <label for="type">Type:</label>
        <input type="text" name="type" required><br>

        <label for="image">Image:</label>
        <input type="file" name="image" required><br>

        <button type="submit" name="submit">Add Product</button>
    </form>
</body>

</html>