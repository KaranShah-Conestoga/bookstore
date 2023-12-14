<?php

$dbname = "Bookstore";
$servername = "localhost";
$username = "root";
$password = "";
try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


    // Fetch Order and Customer data
    $stmtOrder = $conn->prepare("SELECT `Order`.OrderID, `Order`.UserName, `Order`.ISBN, `Order`.DatePurchase, `Order`.Quantity, `Order`.TotalPrice, `Order`.Status,
        Customer.CustomerName, Customer.CustomerPhone, Customer.CustomerIC, Customer.CustomerEmail, Customer.CustomerAddress, Customer.CustomerGender
        FROM `Order`
        LEFT JOIN Customer ON `Order`.UserName = Customer.UserName");
    $stmtOrder->execute();
    $orders = $stmtOrder->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <style>
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
    <h1>Orders</h1>
    

    <h2>Order and Customer Information Table</h2>
    <table>
        <tr>
            <th>Order ID</th>
            <th>Customer Name</th>
            <th>ISBN</th>
            <th>Date Purchase</th>
            <th>Quantity</th>
            <th>Status</th>
            <th>Customer Phone</th>
            <th>Customer Email</th>
            <th>Customer Address</th>
            <th>Total Price</th>
            
        </tr>
        <?php foreach ($orders as $order) : ?>
            <tr>
                <td><?php echo $order['OrderID']; ?></td>
                <td><?php echo $order['CustomerName']; ?></td>
                <td><?php echo $order['ISBN']; ?></td>
                <td><?php echo $order['DatePurchase']; ?></td>
                <td><?php echo $order['Quantity']; ?></td>
                <td><?php echo $order['Status']; ?></td>
                <td><?php echo $order['CustomerPhone']; ?></td>                
                <td><?php echo $order['CustomerEmail']; ?></td>
                <td><?php echo $order['CustomerAddress']; ?></td>
                <td><?php echo $order['TotalPrice']; ?></td>
                
            </tr>
        <?php endforeach; ?>
    </table>

    <script>
        function redirectToAddProduct() {
            window.location.href = 'add_product.php';
        }
    </script>
</body>

</html>        