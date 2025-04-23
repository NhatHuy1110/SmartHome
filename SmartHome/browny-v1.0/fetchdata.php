<?php
$apiKey = "aio_xuJo80uSQdTMHREJriV43dcBIrEY";
$url = "https://io.adafruit.com/api/v2/anhtanggroup1/feeds/light-level";
$url1 = "https://io.adafruit.com/api/v2/anhtanggroup1/feeds/temper";
$url2 = "https://io.adafruit.com/api/v2/anhtanggroup1/feeds/movement";

// Fetch data from light feed
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ["X-AIO-Key: $apiKey"]);
$response = curl_exec($ch);
curl_close($ch);
$data = json_decode($response, true);

// Fetch data from temper feed
$ch1 = curl_init($url1);
curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch1, CURLOPT_HTTPHEADER, ["X-AIO-Key: $apiKey"]);
$response1 = curl_exec($ch1);
curl_close($ch1);
$data1 = json_decode($response1, true);

// Fetch data from movement feed
$ch2 = curl_init($url2);
curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch2, CURLOPT_HTTPHEADER, ["X-AIO-Key: $apiKey"]);
$response2 = curl_exec($ch2);
curl_close($ch2);
$data2 = json_decode($response2, true);

// Extract the latest values
$lum = isset($data["last_value"]) ? $data["last_value"] : null; // Light feed
$temp = isset($data1["last_value"]) ? $data1["last_value"] : null; // Temper feed
$pres = isset($data2["last_value"]) ? $data2["last_value"] : null; // Movement feed

// Convert presence value to integers
if ($pres === "Yes") {
    $pres = 1;
} elseif ($pres === "No") {
    $pres = 0;
} else {
    $pres = -1;
}

// Extract timestamps
$dateTimeLight = isset($data["updated_at"]) ? date("Y-m-d H:i:s", strtotime($data["updated_at"])) : null;
$dateTimeTemper = isset($data1["updated_at"]) ? date("Y-m-d H:i:s", strtotime($data1["updated_at"])) : null;
$dateTimePres = isset($data2["updated_at"]) ? date("Y-m-d H:i:s", strtotime($data2["updated_at"])) : null;

// Use the most recent timestamp
$dateTime = max($dateTimeLight, $dateTimeTemper, $dateTimePres);

$mysqli = new mysqli("localhost:3307", "root", "", "smarthome");
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Insert the latest data into the database
$stmt = $mysqli->prepare("INSERT INTO sensors (RID, DateTime, Luminosity, Temperature, Presence) VALUES (1, ?, ?, ?, ?)");
$stmt->bind_param("sddd", $dateTime, $lum, $temp, $pres);
$stmt->execute();

$stmt->close();
$mysqli->close();

echo json_encode(['status' => 'success', 'message' => 'Data fetched and inserted successfully']);
