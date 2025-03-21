<?php
$mysqli = new mysqli("localhost", "root", "", "smarthome");

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

foreach ($data as $item) {
    $intensity = $item["Intensity"];
    $datetime = $item["DateTime"];

    $stmt = $mysqli->prepare("INSERT INTO light (Intensity, DateTime) VALUES (?, ?)");
    $stmt->bind_param("ss", $intensity, $datetime);
    $stmt->execute();
}

$stmt->close();
$mysqli->close();
?>