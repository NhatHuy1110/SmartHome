<?php
require 'Connection.php';
$conn = Connect();

$rid = $_GET['rid'];
$datetime = $_GET['datetime'];

$sql = "DELETE FROM sensors WHERE RID = ? AND DateTime = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $rid, $datetime);
if ($stmt->execute()) {
    header("Location: Table.php"); // or wherever your main table is
} else {
    echo "Error deleting record: " . $stmt->error;
}
$conn->close();
