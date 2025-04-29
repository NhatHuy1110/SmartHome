<?php
session_start();
require 'Connection2.php'; // Use the updated connection class
$db = new DBConn();

$uid = $_SESSION['uid'];

// Define WHERE condition
$whereConditions = ['UID' => $uid];
$types = 'i'; // UID is an integer

// Fetch all columns from the 'event' table for the logged-in user
$result = $db->selectWhere('event', $whereConditions, '', 0, 'DESC', $types);

$data = array();

if ($result) {
    $data = $result; // Already an array of associative arrays
}

header('Content-Type: application/json');
echo json_encode($data);
