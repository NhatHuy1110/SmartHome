<?php

function Connect()
{
	$dbhost = "localhost:3307";
	$dbuser = "root";
	$dbpass = "";
	$dbname = "smarthome";

	//Create Connection
	$conn = new mysqli($dbhost, $dbuser, $dbpass, $dbname) or die($conn->connect_error);

	return $conn;
}
?>