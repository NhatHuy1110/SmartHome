<?php
session_start();
// Establishing Connection with Server by passing server_name, user_id and password as a parameter
require_once 'Connection2.php';
$db = new DBConn();
$error = '';

if (isset($_POST['submit'])) {
	if (empty($_POST['customer_username']) || empty($_POST['customer_password'])) {
		$error = "Username or Password is invalid";
	} else {
		// SQL query to fetch information of registerd users and finds user match.
		$conditions = [
			'Username' => $_POST['customer_username'],
			'Password' => $_POST['customer_password']
		];
		$types = 'ss'; // for two string parameters, Username and Password
		$user = $db->selectWhere('user', $conditions, '', 1, 'DESC', $types);

		if ($user) {
			$_SESSION['login_customer'] = $user[0]['Username'];
			$_SESSION['uid'] = $user[0]['UID']; // Adjust column name as needed
			header("location: index.php");
			exit();
		} else {
			$error = "Username or Password is invalid";
		}
	}
}
