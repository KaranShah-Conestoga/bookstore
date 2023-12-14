<?php

$dbname = "Bookstore";
$servername = "localhost";
$username = "root";
$password = "";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch Customer data
    $stmtCustomer = $conn->prepare("SELECT * FROM Customer");
    $stmtCustomer->execute();
    $customers = $stmtCustomer->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customers</title>
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
    <h1>Customers</h1>
    <div>
        <button onclick="redirectToDashboard()">Dashboard</button>
    </div>

    <h2>Customer Information Table</h2>
    <table>
        <tr>
            <th>Full Name</th>
            <th>Username</th>
            <th>Customer Email</th>
            <th>Customer Contact</th>
            <th>Customer Address</th>
        </tr>
        <?php foreach ($customers as $customer) : ?>
            <tr>
                <td><?php echo $customer['CustomerName']; ?></td>
                <td><?php echo $customer['UserName']; ?></td>
                <td><?php echo $customer['CustomerEmail']; ?></td>
                <td><?php echo $customer['CustomerPhone']; ?></td>
                <td><?php echo $customer['CustomerAddress']; ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
    <script>
        function redirectToDashboard() {
            window.location.href = "admin_panel.php";
        }
    </script>
</body>

</html>
