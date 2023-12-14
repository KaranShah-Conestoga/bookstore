<html>
<meta http-equiv="Content-Type"'.' content="text/html; charset=utf8" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="style.css">

<body>
	<?php
	session_start();
	if (isset($_POST['ac'])) {
		$servername = "localhost";
		$username = "root";
		$password = "";

		$conn = new mysqli($servername, $username, $password);

		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}

		$sql = "USE bookstore";
		$conn->query($sql);

		$ISBN = $_POST['ac'];
		$quantity = $_POST['quantity'];

		// Check if quantity is greater than or equal to 1
		if ($quantity < 1) {
			echo "Quantity must be greater than or equal to 1.";
			// You might want to redirect the user or handle the error appropriately
		} else {
			$sql = "SELECT * FROM book WHERE ISBN = '" . $ISBN . "'";
			$result = $conn->query($sql);

			while ($row = $result->fetch_assoc()) {
				$price = $row['Price'];
			}
			$UserName = isset($_SESSION['id']) ? $_SESSION['id'] : null;

			$totalPrice = $price * $quantity;

			if (!$UserName) {
				// redirect to login page
				header("Location: login.php");
			} else {
				// Check if the product is already in the cart
				$checkQuery = "SELECT * FROM cart WHERE UserName = '$UserName' AND ISBN = '$ISBN'";
				$checkResult = $conn->query($checkQuery);

				if ($checkResult->num_rows > 0) {
					// Product already in the cart, update the quantity and price
					$updateQuantityPrice = "UPDATE cart SET Quantity = Quantity + $quantity, Price = '$price', TotalPrice = TotalPrice + $totalPrice WHERE UserName = '$UserName' AND ISBN = '$ISBN'";
					$conn->query($updateQuantityPrice);
				} else {
					// Product not in the cart, insert a new record
					$insertQuery = "INSERT INTO cart (UserName, ISBN, Price, Quantity, TotalPrice) VALUES ('$UserName', '$ISBN', '$price', '$quantity', '$totalPrice')";
					$conn->query($insertQuery);
				}
			}
		}
	}


	if (isset($_POST['delc'])) {
		$servername = "localhost";
		$username = "root";
		$password = "";

		$conn = new mysqli($servername, $username, $password);

		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}

		$sql = "USE bookstore";
		$conn->query($sql);

		$sql = "DELETE FROM cart";
		$conn->query($sql);
	}


	// Delete item from cart when user click delete button with a cross icon on it
	if (isset($_POST['delete'])) {
		$servername = "localhost";
		$username = "root";
		$password = "";

		$conn = new mysqli($servername, $username, $password);

		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}

		$sql = "USE bookstore";
		$conn->query($sql);

		// Assuming 'ISBN' is the unique identifier for items in the cart
		if (isset($_POST['delete'])) {
			$bookIDToDelete = $_POST['delete'];
			$sql = "DELETE FROM cart WHERE ISBN = '$bookIDToDelete'";
			$conn->query($sql);
		}
	}



	$servername = "localhost";
	$username = "root";
	$password = "";

	$conn = new mysqli($servername, $username, $password);

	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	$sql = "USE bookstore";
	$conn->query($sql);

	$sql = "SELECT * FROM book";
	$result = $conn->query($sql);
	?>

	<?php

	echo "<script>
function redirectToCheckout() {
	window.location.href = 'checkout.php';
}
</script>";
	$UserName = isset($_SESSION['id']) ? $_SESSION['id'] : null;
	if ($UserName != null) {
		echo '<header>';
		echo '<blockquote>';
		echo '<a href="index.php"><img src="image/logo.png"></a>';
		echo '<form class="hf" action="logout.php"><input class="hi" type="submit" name="submitButton" value="Logout"></form>';
		echo '<form class="hf" action="edituser.php"><input class="hi" type="submit" name="submitButton" value="Edit Profile"></form>';
		echo '</blockquote>';
		echo '</header>';
	}

	if ($UserName == null) {
		echo '<header>';
		echo '<blockquote>';
		echo '<a href="index.php"><img src="image/logo.png"></a>';
		echo '<form class="hf" action="Register.php"><input class="hi" type="submit" name="submitButton" value="Register"></form>';
		echo '<form class="hf" action="login.php"><input class="hi" type="submit" name="submitButton" value="Login"></form>';
		echo '</blockquote>';
		echo '</header>';
	}
	echo '<blockquote>';
	echo '<div class="container-home">';
	echo "<table id='myTable' style='width:80%; float:left'>";
	echo "<tr>";
	while ($row = $result->fetch_assoc()) {
		echo "<td>";
		echo "<table>";
		echo '<tr><td>' . '<img src="' . $row["Image"] . '"width="80%">' . '</td></tr><tr><td style="padding: 5px;">Title: ' . $row["BookTitle"] . '</td></tr><tr><td style="padding: 5px;">ISBN: ' . $row["ISBN"] . '</td></tr><tr><td style="padding: 5px;">Author: ' . $row["Author"] . '</td></tr><tr><td style="padding: 5px;">Type: ' . $row["Type"] . '</td></tr><tr><td style="padding: 5px;">$' . $row["Price"] . '</td></tr><tr><td style="padding: 5px;">
	   	<form action="" method="post">
	   	Quantity: <input type="number" value="1" name="quantity" style="width: 20%"/><br>
	   	<input type="hidden" value="' . $row['ISBN'] . '" name="ac"/>
	   	<input class="button" type="submit" value="Add to Cart"/>
	   	</form></td></tr>';
		echo "</table>";
		echo "</td>";
	}
	echo "</tr>";
	echo "</table>";

	// $sql = "SELECT book.ISBN, book.BookTitle, book.Image, cart.Price, cart.Quantity, cart.TotalPrice FROM book,cart WHERE book.ISBN = cart.ISBN AND cart.UserName = '" . $UserName . "'";
	if ($UserName == null) {
		$sql = "SELECT book.ISBN, book.BookTitle, book.Image, cart.Price, cart.Quantity, cart.TotalPrice FROM book,cart WHERE book.ISBN = cart.ISBN";
	} else {
		$sql = "SELECT book.ISBN, book.BookTitle, book.Image, cart.Price, cart.Quantity, cart.TotalPrice FROM book,cart WHERE book.ISBN = cart.ISBN AND cart.UserName = '" . $UserName . "'";
	}

	$result = $conn->query($sql);

	
	
	echo "<table class='cart-table'>";
	echo "<th><i class='fa fa-shopping-cart'></i> Cart <form class='empty-cart-form' action='' method='post'><input class='cbtn' name='delc' type='submit' value='Empty Cart'></form></th>";
	
	$total = 0;
	if ($result->num_rows > 0) {
	
		while ($row = $result->fetch_assoc()) {
			echo '<tr><td class="cart-item">';
			echo '<div class="item-details">';
			echo '<img src="' . $row["Image"] . '" alt="Book Image" class="book-image"><br>';
			echo '<div class="item-info">';
			echo '<span class="book-title">' . $row['BookTitle'] . '</span><br>';
			echo '<span class="price">$' . $row['Price'] . '</span><br>';
			echo '<span class="quantity">Quantity: ' . $row['Quantity'] . '</span><br>';
			echo '<span class="total-price">Total Price: $' . $row['TotalPrice'] . '</span>';
			echo '</div>'; // End item-info
			echo '</div>'; // End item-details
	
			// Add delete button with a cross icon
			echo '<form action="" method="post">';
			echo '<input type="hidden" name="delete" value="' . $row['ISBN'] . '">';
			echo '<button type="submit" class="delete-button" title="Delete">&#10005;</button>';
			echo '</form>';
	
			echo "</td></tr>";
			$total += $row['TotalPrice'];
		}
	
		echo "</table>";
	} else {
		// Display empty cart image and message below the "Empty Cart" button
		echo '<tr><td class="cart-item">';
		echo '<div class="item-details">';
		echo '<div class="empty-cart-message">';
		echo '<img src="https://mir-s3-cdn-cf.behance.net/projects/404/95974e121862329.Y3JvcCw5MjIsNzIxLDAsMTM5.png" alt="Empty Cart Image">';
		echo '</div>';
		echo '</div>';
		echo "</td></tr>";
		echo "</table>";
	}
	
	
	
	
	echo "<tr><td style='text-align: right;background-color: #f2f2f2;''>";
	// echo "Total: <b>$" . $total . "</b><center><form action='checkout.php' method='post'><input class='button' type='submit' name='checkout' disabled value='CHECKOUT'></form></center>";
	// input filed shoud be disabled if cart is empty
	// if ($total == 0) {
	// 	echo "Total: <b>$" . $total . "</b><center><form action='checkout.php' method='post'><input class='button' type='submit' name='checkout' disabled value='CHECKOUT'></form></center>";
	// } else {
	// 	echo "Total: <b>$" . $total . "</b><center><form action='checkout.php' method='post'><input class='button' type='submit' name='checkout' value='CHECKOUT'></form></center>";
	// }

	if ($total > 0) {
		echo "<center><form id='checkoutForm' action='checkout.php' method='post'>";
		echo "<button class='button' type='button' onclick='redirectToCheckout()'>CHECKOUT</button>";
		echo "</form></center>";
	} else {
		echo "<center><form id='checkoutForm' action='checkout.php' method='post'>";
		echo "<button class='button' type='button' onclick='redirectToCheckout()' disabled>CHECKOUT</button>";
		echo "</form></center>";
	}
	echo "</td></tr>";
	echo "</table>";
	echo '</div>';
	echo '</blockquote>';
	?>
	<footer style="background-color:#000000;" class="mt-8">
		<div style="margin: 0 auto; background-color: #000000" class="container-footer">
			<h1 class="text-2xl"><a href="index.php"><img src="image/logo.png"></a></h1>
			<p> &copy; Copyright 2023, Book Store</p>
		</div>
	</footer>
</body>


</body>

</html>