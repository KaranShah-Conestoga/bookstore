<html>

<body style="font-family:Arial; margin: 0 auto; background-color: #f2f2f2;">
	<header>
		<blockquote>
			<img src="image/logo.png">
			<input class="hi" style="float: right; margin: 2%;" type="button" name="cancel" value="Home" onClick="window.location='index.php';" />
		</blockquote>
	</header>
	<?php
	session_start();

	if (isset($_SESSION['id'])) {
		$servername = "localhost";
		$username = "root";
		$password = "";

		$conn = new mysqli($servername, $username, $password);

		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}

		$sql = "USE bookstore";
		$conn->query($sql);

		$sql = "UPDATE cart SET UserName = '" . $_SESSION['id'] . "' WHERE 1";
		$conn->query($sql);

		$total = 0;

		$sql = "SELECT * FROM cart";
		$result = $conn->query($sql);
		while ($row = $result->fetch_assoc()) {
			$total += $row['TotalPrice'];
			$sql = "INSERT INTO `order`(UserName, ISBN, DatePurchase, Quantity, TotalPrice, Status) 
					VALUES('" . $_SESSION['id'] . "', '" . $row['ISBN'] . "', CURRENT_TIME, " . $row['Quantity'] . ", " . $row['TotalPrice'] . ", 'Not Completed')";
			$conn->query($sql);
		}

		$sql = "DELETE FROM cart";
		$conn->query($sql);
		$sql = "SELECT customer.CustomerName, customer.CustomerIC, customer.CustomerGender, customer.CustomerAddress, customer.CustomerEmail, customer.CustomerPhone, book.BookTitle, book.Price, book.Image, `order`.`DatePurchase`, `order`.`Quantity`, `order`.`TotalPrice`
		FROM customer, book, `order`
		WHERE `order`.`UserName` = customer.UserName AND `order`.`ISBN` = book.ISBN AND `order`.`Status` = 'N' AND `order`.UserName = '" . $_SESSION['id'] . "'";
		$result = $conn->query($sql);

		echo '<div class="container">';
		echo '<blockquote>';

		if ($result && $result->num_rows > 0) {
			$row = $result->fetch_assoc();
			echo '<input class="button" style="float: right;" type="button" name="cancel" value="Continue Shopping" onClick="window.location=\'index.php\';" />';
			echo '<h2 style="color: #000;">Order Successful</h2>';
			echo "<table style='width:100%'>";
			echo "<tr><th>Order Summary</th>";
			echo "<th></th></tr>";
			echo "<tr><td>Name: </td><td>" . $row['CustomerName'] . "</td></tr>";
			echo "<tr><td>No.Number: </td><td>" . $row['CustomerIC'] . "</td></tr>";
			echo "<tr><td>E-mail: </td><td>" . $row['CustomerEmail'] . "</td></tr>";
			echo "<tr><td>Mobile Number: </td><td>" . $row['CustomerPhone'] . "</td></tr>";
			echo "<tr><td>Gender: </td><td>" . $row['CustomerGender'] . "</td></tr>";
			echo "<tr><td>Address: </td><td>" . $row['CustomerAddress'] . "</td></tr>";
			echo "<tr><td>Date: </td><td>" . $row['DatePurchase'] . "</td></tr>";
			echo "</table>";
		} else {
			echo '<p>No results found</p>';
		}

		echo "</blockquote>";
		echo "</div>";
		$cID = ""; // Initialize $cID outside the block

		if (isset($_POST['submitButton'])) {
			// Your existing code to validate and insert data into the database

			// Update the value of $cID
			$sql = "SELECT UserName from customer WHERE CustomerName = '" . $name . "' AND CustomerIC = '" . $ic . "'";
			$result = $conn->query($sql);
			while ($row = $result->fetch_assoc()) {
				$cID = $row['UserName'];
			}

			// Rest of your existing code
		}



		// Now $cID is defined and can be safely used outside the block

		// Example usage in the subsequent SQL query


		$sql = "SELECT customer.CustomerName, customer.CustomerIC, customer.CustomerGender, customer.CustomerAddress, customer.CustomerEmail, customer.CustomerPhone, book.BookTitle, book.Price, book.Image, `order`.`DatePurchase`, `order`.`Quantity`, `order`.`TotalPrice`
				FROM customer, book, `order`
				WHERE `order`.`UserName` = customer.UserName AND `order`.`ISBN` = book.ISBN AND `order`.`Status` = 'N' AND `order`.`UserName` = " . $cID . "";
		$result = $conn->query($sql);

		// ... rest of your code ...

		echo "<tr><td style='background-color: #ccc;'></td><td style='text-align: right;background-color: #ccc;''>Total Price: <b>$ " . $total . "</b></td></tr>";

		echo "</table>";
		echo "</div>";

		$sql = "UPDATE `order` SET Status = 'y' WHERE UserName = " . $cID . "";
		$conn->query($sql);
	}

	$nameErr = $emailErr = $genderErr = $addressErr = $icErr = $contactErr = "";
	$name = $email = $gender = $address = $ic = $contact = "";
	$cID;


	if (isset($_POST['submitButton'])) {
		if (empty($_POST["name"])) {
			$nameErr = "Please enter your name";
		} else {
			if (!preg_match("/^[a-zA-Z ]*$/", $name)) {
				$nameErr = "Only letters and white space allowed";
				$name = "";
			} else {
				$name = $_POST['name'];
				if (empty($_POST["ic"])) {
					$icErr = "Please enter your IC number";
				} else {
					if (!preg_match("/^[0-9 -]*$/", $ic)) {
						$icErr = "Please enter a valid IC number";
						$ic = "";
					} else {
						$ic = $_POST['ic'];
						if (empty($_POST["email"])) {
							$emailErr = "Please enter your email address";
						} else {
							if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
								$emailErr = "Invalid email format";
								$email = "";
							} else {
								$email = $_POST['email'];
								if (empty($_POST["contact"])) {
									$contactErr = "Please enter your phone number";
								} else {
									if (!preg_match("/^[0-9 -]*$/", $contact)) {
										$contactErr = "Please enter a valid phone number";
										$contact = "";
									} else {
										$contact = $_POST['contact'];
										if (empty($_POST["gender"])) {
											$genderErr = "* Gender is required!";
											$gender = "";
										} else {
											$gender = $_POST['gender'];
											if (empty($_POST["address"])) {
												$addressErr = "Please enter your address";
												$address = "";
											} else {
												$address = $_POST['address'];

												$servername = "localhost";
												$username = "root";
												$password = "";

												$conn = new mysqli($servername, $username, $password);

												if ($conn->connect_error) {
													die("Connection failed: " . $conn->connect_error);
												}

												$sql = "USE bookstore";
												$conn->query($sql);

												$sql = "INSERT INTO customer(CustomerName, CustomerPhone, CustomerIC, CustomerEmail, CustomerAddress, CustomerGender) 
											VALUES('" . $name . "', '" . $contact . "', '" . $ic . "', '" . $email . "', '" . $address . "', '" . $gender . "')";
												$conn->query($sql);

												$sql = "SELECT UserName from customer WHERE CustomerName = '" . $name . "' AND CustomerIC = '" . $ic . "'";
												$result = $conn->query($sql);
												while ($row = $result->fetch_assoc()) {
													$cID = $row['UserName'];
												}

												$sql = "UPDATE cart SET UserName = '" .  $_SESSION['id'] . "' WHERE 1";

												$conn->query($sql);

												$sql = "SELECT * FROM cart";
												$result = $conn->query($sql);
												while ($row = $result->fetch_assoc()) {
													$sql = "INSERT INTO `order`(UserName, ISBN, DatePurchase, Quantity, TotalPrice, Status) 
												VALUES(" . $row['UserName'] . ", '" . $row['ISBN']
														. "', CURRENT_TIME, " . $row['Quantity'] . ", " . $row['TotalPrice'] . ", 'N')";
													$conn->query($sql);
												}
												$sql = "DELETE FROM cart";
												$conn->query($sql);
											}
										}
									}
								}
							}
						}
					}
				}
			}
		}
	}
	// Assuming $_SESSION['id'] contains the current user's ID
	if (isset($_SESSION['id'])) {
		// ... your existing code

		// Retrieve the latest order data for the current user
		$latestOrderQuery = "SELECT * FROM `order` WHERE UserName = '" . $_SESSION['id'] . "' ORDER BY DatePurchase DESC LIMIT 1";
		$latestOrderResult = $conn->query($latestOrderQuery);

		if ($latestOrderResult && $latestOrderResult->num_rows > 0) {
			$latestOrder = $latestOrderResult->fetch_assoc();

			// Display the latest order data
			echo "<h2>Latest Order Details</h2>";
			echo "<p>Order ID: " . $latestOrder['OrderID'] . "</p>";
			echo "<p>Order Date: " . $latestOrder['DatePurchase'] . "</p>";
			// ... display other order details as needed
		} else {
			echo "<p>No recent orders found.</p>";
		}

		// ... rest of your code
	}

	function test_input($data)
	{
		$data = trim($data);
		$data = stripcslashes($data);
		$data = htmlspecialchars($data);
		return $data;
	}
	?>
	<style>
		header {
			background-color: rgb(0, 51, 102);
			width: 100%;
		}

		header img {
			margin: 1%;
		}

		header .hi {
			background-color: #fff;
			border: none;
			border-radius: 20px;
			text-align: center;
			transition-duration: 0.5s;
			padding: 8px 30px;
			cursor: pointer;
			color: #000;
			margin-top: 15%;
		}

		header .hi:hover {
			background-color: #ccc;
		}

		form {
			margin-top: 1%;
			float: left;
			width: 40%;
			color: #000;
		}

		input[type=text] {
			padding: 5px;
			border-radius: 3px;
			box-sizing: border-box;
			border: 2px solid #ccc;
			transition: 0.5s;
			outline: none;
		}

		input[type=text]:focus {
			border: 2px solid rgb(0, 51, 102);
		}

		textarea {
			outline: none;
			border: 2px solid #ccc;
		}

		textarea:focus {
			border: 2px solid rgb(0, 51, 102);
		}

		.button {
			background-color: rgb(0, 51, 102);
			border: none;
			border-radius: 20px;
			text-align: center;
			transition-duration: 0.5s;
			padding: 8px 30px;
			cursor: pointer;
			color: #fff;
		}

		.button:hover {
			background-color: rgb(102, 255, 255);
			color: #000;
		}

		table {
			border-collapse: collapse;
			width: 60%;
			float: right;
		}

		th,
		td {
			text-align: left;
			padding: 8px;
		}

		tr {
			background-color: #fff;
		}

		th {
			background-color: rgb(0, 51, 102);
			color: white;
		}

		.container {
			width: 50%;
			border-radius: 5px;
			background-color: #f2f2f2;
			padding: 20px;
			margin: 0 auto;
		}
	</style>
	<blockquote>
		<?php
		if (!isset($_SESSION['id'])) {
			echo "<form method='post'  action=''>";

			echo 'Name:<br><input type="text" name="name" placeholder="Full Name">';
			echo '<span class="error" style="color: red; font-size: 0.8em;"><?php echo $nameErr;?></span><br><br>';

			echo 'IC Number:<br><input type="text" name="ic" placeholder="xxxxxx-xx-xxxx">';
			echo '<span class="error" style="color: red; font-size: 0.8em;"><?php echo $icErr;?></span><br><br>';

			echo 'E-mail:<br><input type="text" name="email" placeholder="example@email.com">';
			echo '<span class="error" style="color: red; font-size: 0.8em;"><?php echo $emailErr;?></span><br><br>';

			echo 'Mobile Number:<br><input type="text" name="contact" placeholder="012-3456789">';
			echo '<span class="error" style="color: red; font-size: 0.8em;"><?php echo $contactErr;?></span><br><br>';

			echo '<label>Gender:</label><br>';
			echo '<input type="radio" name="gender" if (isset($gender) && $gender == "Male") echo "checked"; value="Male">Male';
			echo '<input type="radio" name="gender" if (isset($gender) && $gender == "Female") echo "checked"; value="Female">Female';
			echo '<span class="error" style="color: red; font-size: 0.8em;"><?php echo $genderErr;?></span><br><br>';

			echo '<label>Address:</label><br>';
			echo '<textarea name="address" cols="30" rows="5" placeholder="Address"></textarea>';
			echo '<span class="error" style="color: red; font-size: 0.8em;"><?php echo $addressErr;?></span><br><br>';
		?>
			<input class="button" type="button" name="cancel" value="Cancel" onClick="window.location='index.php';" />
		<?php
			echo '<input class="button" type="submit" name="submitButton" value="CHECKOUT">';
			echo '</form><br><br>';
		}

		if (isset($_POST['submitButton'])) {
			$servername = "localhost";
			$username = "root";
			$password = "";

			$conn = new mysqli($servername, $username, $password);

			if ($conn->connect_error) {
				die("Connection failed: " . $conn->connect_error);
			}

			$sql = "USE bookstore";
			$conn->query($sql);

			$sql = "SELECT customer.CustomerName, customer.CustomerIC, customer.CustomerGender, customer.CustomerAddress, customer.CustomerEmail, customer.CustomerPhone, book.BookTitle, book.Price, book.Image, `order`.`DatePurchase`, `order`.`Quantity`, `order`.`TotalPrice`
		FROM customer, book, `order`
		WHERE `order`.`UserName` = customer.UserName AND `order`.`ISBN` = book.ISBN AND `order`.`Status` = 'N' AND `order`.`UserName` = " . $cID . "";
			$result = $conn->query($sql);

			echo '<table style="width: 40%">';
			echo "<tr><th>Order Summary</th>";
			echo "<th></th></tr>";
			$row = $result->fetch_assoc();
			echo "<tr><td>Name: </td><td>" . $row['CustomerName'] . "</td></tr>";
			echo "<tr><td>No.Number: </td><td>" . $row['CustomerIC'] . "</td></tr>";
			echo "<tr><td>E-mail: </td><td>" . $row['CustomerEmail'] . "</td></tr>";
			echo "<tr><td>Mobile Number: </td><td>" . $row['CustomerPhone'] . "</td></tr>";
			echo "<tr><td>Gender: </td><td>" . $row['CustomerGender'] . "</td></tr>";
			echo "<tr><td>Address: </td><td>" . $row['CustomerAddress'] . "</td></tr>";
			echo "<tr><td>Date: </td><td>" . $row['DatePurchase'] . "</td></tr>";

			$sql = "SELECT customer.CustomerName, customer.CustomerIC, customer.CustomerGender, customer.CustomerAddress, customer.CustomerEmail, customer.CustomerPhone, book.BookTitle, book.Price, book.Image, `order`.`DatePurchase`, `order`.`Quantity`, `order`.`TotalPrice`
		FROM customer, book, `order`
		WHERE `order`.`UserName` = customer.UserName AND `order`.`ISBN` = book.ISBN AND `order`.`Status` = 'N' AND `order`.`UserName` = " . $cID . "";
			$result = $conn->query($sql);
			$total = 0;
			while ($row = $result->fetch_assoc()) {
				echo "<tr><td style='border-top: 2px solid #ccc;'>";
				echo '<img src="' . $row["Image"] . '"width="20%"></td><td style="border-top: 2px solid #ccc;">';
				echo $row['BookTitle'] . "<br>RM" . $row['Price'] . "<br>";
				echo "Quantity: " . $row['Quantity'] . "<br>";
				echo "</td></tr>";
				$total += $row['TotalPrice'];
			}
			echo "<tr><td style='background-color: #ccc;'></td><td style='text-align: right;background-color: #ccc;'>Total Price: <b>RM" . $total . "</b></td></tr>";
			echo "</table>";

			$sql = "UPDATE `order` SET Status = 'y' WHERE UserName = " . $cID . "";
			$conn->query($sql);
		}
		?>
	</blockquote>

</body>

</html>