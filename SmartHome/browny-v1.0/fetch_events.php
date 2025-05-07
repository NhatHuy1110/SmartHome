<?php
session_start();
require_once 'Connection2.php'; // Use the updated connection class
$db = new DBConn();

// Fetch all columns from the 'event' table without constraints
$result = $db->selectWhere('event', [], '', 0, 'DESC');

$data = array();

if ($result) {
    $data = $result; // Already an array of associative arrays
}

header('Content-Type: application/json');
echo json_encode($data);
