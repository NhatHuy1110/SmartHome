<?php
session_start();
require 'Connection.php'; // Include database connection
$conn = Connect();

$uid = $_SESSION['uid'];
$sql = "SELECT * FROM event WHERE UID = {$uid}"; // Query to fetch all records from the 'event' table
$result = $conn->query($sql);

$data = array(); // Initialize an empty array

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) { // Loop through all rows
        $data[] = $row; // Add each row to the $data array
    }
}

header('Content-Type: application/json'); // Set the content type to JSON
echo json_encode($data); // Convert the $data array to JSON and echo it
