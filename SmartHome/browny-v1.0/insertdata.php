<?php
#redundant, already merged into fetchdate
$mysqli = new mysqli("localhost", "root", "", "smarthome");

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

foreach ($data as $item) {
    #$intensity = $item["Intensity"];
    #$datetime = $item["DateTime"];
    $intensity = $data["last_value"];
    $datetime = date("Y-m-d H:i:s", strtotime($data["updated_at"]));

    $stmt = $mysqli->prepare("INSERT INTO light (RID,LID,Intensity, DateTime) VALUES (1,1,?, ?)");
    $stmt->bind_param("ds", $intensity, $datetime);
    $stmt->execute();
}

$stmt->close();
$mysqli->close();
?>