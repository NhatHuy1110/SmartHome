<?php
session_start();
$error = '';

if (isset($_POST['submit'])) {
	if (empty($_POST['customer_username']) || empty($_POST['customer_password'])) {
		$error = "Username or Password is invalid";
	} else {
		// Define $username and $password
		$customer_username = $_POST['customer_username'];
		$customer_password = $_POST['customer_password'];
		// Establishing Connection with Server by passing server_name, user_id and password as a parameter
		require 'connection.php';
		$conn = Connect();

		// SQL query to fetch information of registerd users and finds user match.
		$query = "SELECT * FROM user WHERE Username=? AND Password=? LIMIT 1";

		// To protect MySQL injection for Security purpose
		$stmt = $conn->prepare($query);
		$stmt->bind_param("ss", $customer_username, $customer_password);
		$stmt->execute();
		$stmt->bind_result($uid, $customer_username, $customer_password);
		$stmt->store_result();

		if ($stmt->fetch())  //fetching the contents of the row
		{
			$_SESSION['login_customer'] = $customer_username; // Initializing Session
			$_SESSION['uid'] = $uid;
			header("location: index.php"); // Redirecting To Other Page
		} else {
			$error = "Username or Password is invalid";
		}
		mysqli_close($conn); // Closing Connection
	}
}
