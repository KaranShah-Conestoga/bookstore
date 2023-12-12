<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f0f0f0;
            color: #333;
            overflow: hidden;
            margin: 0; /* Resetting the default margin */
        }

        h1 {
            text-align: center;
            background-color: #4285f4;
            padding: 2rem;
            color: white;
            width: 100%;

        }

        form {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #333;
        }

        input {
            width: calc(100% - 16px);
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            background-color: #f9f9f9;
            color: #333;
        }

        input:focus {
            outline: none;
            border-color: #4caf50;
        }

        button {
            background-color: #4285f4;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            box-shadow: 0 4px 8px rgba(66, 133, 244, 0.2);
        }

        button:hover {
            background-color: #3367d6;
        }

        input[type="file"] {
            padding: 10px;
            margin: 5px 0;
        }
    </style>
</head>

<body>
    <h1>Add Product</h1>
    <form action="process_add_product.php" method="post" enctype="multipart/form-data">

        <label for="bookTitle">Book Title:</label>
        <input type="text" name="bookTitle" required>

        <label for="ISBN">ISBN:</label>
        <input type="text" name="ISBN" required>

        <label for="price">Price:</label>
        <input type="text" name="price" required>

        <label for="author">Author:</label>
        <input type="text" name="author" required>

        <label for="type">Type:</label>
        <input type="text" name="type" required>

        <label for="image">Image:</label>
        <input type="file" name="image" required>

        <button type="submit" name="submit">Add Product</button>
    </form>
</body>

</html>
