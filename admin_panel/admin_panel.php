<?php
session_start();

// Check if the user is logged in as an admin
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    // Redirect to the login page or handle unauthorized access
    header("Location: login.php");
    exit();
}

include "connectDB.php";

// Query to get the total number of products
$sqlProducts = "SELECT COUNT(*) AS totalProducts FROM Book";
$stmtProducts = $pdo->query($sqlProducts);
$totalProducts = $stmtProducts->fetch(PDO::FETCH_ASSOC)['totalProducts'];

// Query to get the total number of orders
$sqlOrders = "SELECT COUNT(*) AS totalOrders FROM `Order`"; // Note: Order is a reserved keyword, use backticks
$stmtOrders = $pdo->query($sqlOrders);
$totalOrders = $stmtOrders->fetch(PDO::FETCH_ASSOC)['totalOrders'];

// Query to get the total number of customers
$sqlCustomers = "SELECT COUNT(*) AS totalCustomers FROM Users";
$stmtCustomers = $pdo->query($sqlCustomers);
$totalCustomers = $stmtCustomers->fetch(PDO::FETCH_ASSOC)['totalCustomers'];

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f9f9f9;
            display: flex;
        }

        .sidebar {
            height: 100%;
            width: 200px;
            position: fixed;
            background-color: #4285f4;
            color: white;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding-top: 20px;
            justify-content: center;
            transition: width 0.3s;
        }

        .sidebar a {
            padding: 15px;
            text-decoration: none;
            color: white;
            font-size: 18px;
            transition: background-color 0.3s;
            width: 100%;
            box-sizing: border-box;
            text-align: center;
        }

        .sidebar a:hover {
            background-color: #555;
        }

        .main-content {
            margin-left: 200px;
            padding: 20px;
            flex-grow: 1;
        }

        .heading {
            padding: 1.5rem;
            color: white;
            background-color: #4285f4;
            justify-content: center;
            margin: 0;
            text-align: center;
            margin-bottom: 2rem;
        }

        .data-container {
            display: flex;
            justify-content: space-between;
            justify-items: center;
            width: 80%;
            margin: 0 auto;
        }

        .dtacon {
            padding: 4rem;
            text-align: center;
            border-radius: 8px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }

        .data-label {
            font-weight: bold;
        }
    </style>
</head>

<body>

<div class="sidebar">
    <a href="admin_panel.php">Dashboard</a>
    <a href="products.php">Products</a>
    <a href="orders.php">Orders</a>
    <a href="customers.php">Customers</a>
    <!-- Add more links as needed -->
</div>

<div class="main-content">
    <div class="heading">
        <h1>Welcome to the Admin Panel</h1>
    </div>

    <div class="data-container">
        <div class="dtacon">
            <p class="data-label">Total Products:</p>
            <p><?php echo $totalProducts; ?></p>
        </div>

        <div class="dtacon">
            <p class="data-label">Total Orders:</p>
            <p><?php echo $totalOrders; ?></p>
        </div>

        <div class="dtacon">
            <p class="data-label">Total Users:</p>
            <p><?php echo $totalCustomers; ?></p>
        </div>
    </div>
</div>

</body>

</html>
