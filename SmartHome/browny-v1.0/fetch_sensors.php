<?php
require 'Connection.php'; // Include database connection
$conn = Connect();

$sql = "SELECT Luminosity, Temperature, Presence, DateTime FROM Room WHERE RID = 1";
//"SELECT Luminosity, Temperature, Presence, DateTime FROM Sensors ORDER BY DateTime DESC LIMIT 1"; // Fetch the latest row
$result = $conn->query($sql);

$data = array();
if ($result->num_rows > 0) {
    $data = $result->fetch_assoc(); // Fetch the single latest row as an associative array
}
echo json_encode($data); // Return the data in JSON format
?>
