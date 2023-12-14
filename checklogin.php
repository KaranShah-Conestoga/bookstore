<?php
session_start();

if (isset($_POST['username']) && isset($_POST['pwd'])) {
    $username = $_POST['username'];
    $pwd = $_POST['pwd'];

    include "connectDB.php";

    $sql = "SELECT * FROM Users WHERE UserName=:username";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(':username' => $username));

    if ($stmt->rowCount() > 0) {
        // Fetch the user data
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $stored_hashed_pass = $row['Password'];

        // Assuming you're using password_hash() for hashing passwords
        if (password_verify($pwd, $stored_hashed_pass)) {
            // Password is correct, set session variable and redirect to index.php
            $_SESSION['id'] = $row['UserName'];
            header("Location: index.php");
            exit();
        } else {
            // Password is incorrect
            // Handle the authentication failure
            echo '<span style="color: red;">Login Fail</span>';
            header("Location: login.php?errcode=1");
            exit();
        }
    } else {
        // User not found
        // Handle the case where the username doesn't exist
        echo '<span style="color: red;">Login Fail</span>';
        header("Location: login.php?errcode=1");
        exit();
    }
}
?>
