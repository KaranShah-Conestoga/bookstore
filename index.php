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
	$UserName = isset($_SESSION['id']) ? $_SESSION['id'] : null;
	if ($UserName != null) {
		echo '<header>';
		echo '<blockquote>';
		echo '<a href="index.php"><img src="image/Asset 3-8.png"></a>';
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

	echo "<table style='width:20%; float:right;'>";
	echo "<th style='text-align:left;'><i class='fa fa-shopping-cart' style='font-size:24px'></i> Cart <form style='float:right;' action='' method='post'><input type='hidden' name='delc'/><input class='cbtn' type='submit' value='Empty Cart'></form></th>";
	$total = 0;
	while ($row = $result->fetch_assoc()) {
		echo '<tr><td style="display: flex; justify-content: space-between;">';
		echo '<div style="flex: 1;">';
		echo '<img src="' . $row["Image"] . '" width="20%"><br>';
		echo $row['BookTitle'] . "<br>$" . $row['Price'] . "<br>";
		echo "Quantity: " . $row['Quantity'] . "<br>";
		echo "Total Price: $" . $row['TotalPrice'] . "<br>";
		echo '</div>';

		// Add delete button with a cross icon
		echo '<form action="" method="post">';
		echo '<input type="hidden" name="delete" value="' . $row['ISBN'] . '">';
		echo '<button type="submit" class="delete-button" title="Delete">&#10005;</button>';
		echo '</form>';

		echo "</td></tr>";
		$total += $row['TotalPrice'];
	}
	echo "<tr><td style='text-align: right;background-color: #f2f2f2;''>";
	echo "Total: <b>$" . $total . "</b><center><form action='checkout.php' method='post'><input class='button' type='submit' name='checkout' value='CHECKOUT'></form></center>";
	echo "</td></tr>";
	echo "</table>";
	echo '</div>';
	echo '</blockquote>';
	?>
	  <footer style="background-color: #1d262d;" class="mt-8">
        <div style="margin: 0 auto;" class="container-footer flex flex-col justify-center px-3 py-3 pt-6 text-[#657a89] lg:flex-row lg:space-x-1">
            <div style="margin-bottom: 6px;" class="space-y-2px-4 mb-6 justify-center space-y-2 text-center lg:w-2/5 lg:text-left">
                <h2 style="color: #fff;" class="text-center text-xl font-bold">About Lorem</h2>
                <p class="text-sm">
                    Lorem ipsum dolor sit amet, consectetur adipisicing elit. Cum id necessitatibus consequatur
                    praesentium molestias aliquam alias, aspernatur perferendis, possimus illo animi! Et sapiente
                    voluptatum sit, molestiae corrupti quisquam perspiciatis! Omnis, mollitia in. <br /><br />
                    If you have any suggestions for the site, or would like to make a request please contact us at:
                    <a style="color: #3498db; text-decoration: underline;" href="mailto:support@mail.com">support@mail.com</a>
                    we'll do our best to help.
                </p>
            </div>
            <div style="margin: 0 auto;" class="mx-auto flex flex-col space-y-2 lg:w-2/5">
                <div style="margin: 0 auto;" class="mx-auto flex flex-col space-y-2 md:pl-3">
                    <h2 style="color: #fff;" class="flex justify-center text-xl font-bold">Quick Links</h2>
                    <div class="flex gap-11 space-x-8 lg:mx-auto">
                        <ul style="color: #3498db;" class="space-y-2 text-sm">
                            <li class="hover:text-gray-300"><a href="#">Video</a></li>
                            <li class="hover:text-gray-300"><a href="#">Footage</a></li>
                            <li class="hover:text-gray-300"><a href="#">Motion graphics</a></li>
                            <li class="hover:text-gray-300"><a href="#">Video templates</a></li>
                            <li class="hover:text-gray-300"><a href="#">Privacy policy</a></li>
                            <li class="hover:text-gray-300"><a href="#">Terms and conditions</a></li>
                            <li><a href="#">API</a></li>
                        </ul>
                        <ul style="color: #3498db;" class="space-y-2 text-sm">
                            <li class="hover:text-gray-300"><a href="#">Browse</a></li>
                            <li class="hover:text-gray-300"><a href="#">Premium</a></li>
                            <li class="hover:text-gray-300"><a href="#">Affiliates</a></li>
                            <li class="hover:text-gray-300"><a href="#">Blog</a></li>
                            <li class="hover:text-gray-300"><a href="#">Licensing</a></li>
                            <li class="hover:text-gray-300"><a href="#">Contact</a></li>
                            <li class="hover:text-gray-300"><a href="#">Cookies Setting</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div style="margin: 0 auto;" class="space-y-2">
                <h2 style="color: #fff;" class="text-center text-xl font-bold">Social Media</h2>
                <p class="text-center text-sm">For recent updates and news follow our social media feed</p>

                <ul class="flex justify-center gap-4 text-sm">
                    <li>
                        <a href=""><img src="" alt="" />Youtube</a>
                    </li>
                    <li>
                        <a href=""><img src="" alt="" />Facebook</a>
                    </li>
                    <li>
                        <a href=""><img src="" alt="" />Instagram</a>
                    </li>
                    <li>
                        <a href=""><img src="" alt="" />Twitter</a>
                    </li>
                </ul>
            </div>
        </div>

        <div style="margin: 0 auto;" class="container-footer flex flex-col text-center justify-center border-t py-5 lg:flex-row">
            <h1 style="color: #657a89;" class="text-2xl">LOGO</h1>
            <p style="color: #657a89;"> &copy; Copyright 2058, Example Corporation</p>
        </div>
    </footer>
</body>

</html>