<?php
require_once 'Connection2.php'; // Include database connection using DBConn class
$db = new DBConn();

// Use the selectWhere function to fetch data
$whereConditions = ['RID' => 1];
$columns = ['Luminosity', 'Temperature', 'Presence', 'DateTime'];
$result = $db->selectWhere(
    'Room',
    $whereConditions,
    '',
    0,
    'DESC',
    'i',
    $columns
);
$data = array();
if ($result) {
    $data = $result[0]; // Get the first (and only) row from the result array
}

echo json_encode($data); // Return the data in JSON format
