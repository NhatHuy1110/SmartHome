<?php
session_start();
require_once 'Connection2.php';
$db = new DBConn();
$error = '';
$success = '';

if (isset($_POST['login_submit'])) {
	// Login logic
	if (empty($_POST['customer_username']) || empty($_POST['customer_password'])) {
		$error = "Please enter both username and password.";
	} else {
		$conditions = [
			'Username' => $_POST['customer_username']
		];
		$types = 's'; // for one string parameter, Username
		$user = $db->selectWhere('user', $conditions, '', 1, 'DESC', $types);

		if ($user) {
			// Username exists, now check the password
			if ($user[0]['Password'] === $_POST['customer_password']) {
				$_SESSION['login_customer'] = $user[0]['Username'];
				$_SESSION['uid'] = $user[0]['UID']; // Adjust column name as needed
				header("location: index.php");
				exit();
			} else {
				// Incorrect password
				$error = "Incorrect password. Please try again.";
			}
		} else {
			// Username doesn't exist
			$error = "Username doesn't exist. Please check your username or sign up.";
		}
	}
} elseif (isset($_POST['signup_submit'])) {
	// Signup logic
	$username = $db->validate($_POST['customer_username'] ?? '');
	$password = $db->validate($_POST['customer_password'] ?? '');

	if (empty($username) || empty($password)) {
		$error = "Please fill in both username and password.";
	} else {
		$existing = $db->selectWhere("user", ["Username" => $username], '', 1);

		if ($existing) {
			$error = "Username is already taken. Please choose another.";
		} else {
			$inserted = $db->insert("user", [
				"Username" => $username,
				"Password" => $password
			]);

			if ($inserted) {
				$success = "Account created successfully! You can now log in.";
			} else {
				$error = "Something went wrong. Please try again later.";
			}
		}
	}
}
