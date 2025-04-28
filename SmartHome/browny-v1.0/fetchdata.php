<?php
$apiKey = "aio_itBH53rzbfnwfhiabK1R9p2mAOKx";
$url = "https://io.adafruit.com/api/v2/anhtanggroup1/feeds/light-level"; 
$url1 = "https://io.adafruit.com/api/v2/anhtanggroup1/feeds/temper";
$url2 = "https://io.adafruit.com/api/v2/anhtanggroup1/feeds/movement";
$url3 = "https://io.adafruit.com/api/v2/anhtanggroup1/feeds/fan-control";
$url4 = "https://io.adafruit.com/api/v2/anhtanggroup1/feeds/led";

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

// Fetch data from fan feed
$ch3 = curl_init($url3);
curl_setopt($ch3, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch3, CURLOPT_HTTPHEADER, ["X-AIO-Key: $apiKey"]);
$response3 = curl_exec($ch3);
curl_close($ch3);
$data3 = json_decode($response3, true);


// Fetch data from LED feed
$ch4 = curl_init($url4);
curl_setopt($ch4, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch4, CURLOPT_HTTPHEADER, ["X-AIO-Key: $apiKey"]);
$response4 = curl_exec($ch4);
curl_close($ch4);
$data4 = json_decode($response4, true);

// Extract the latest values
$lum = isset($data["last_value"]) ? $data["last_value"] : null; // Light feed
$temp = isset($data1["last_value"]) ? $data1["last_value"] : null; // Temper feed
$pres = isset($data2["last_value"]) ? $data2["last_value"] : null; // Movement feed
$Fan = isset($data3["last_value"]) ? $data3["last_value"] : null; // fan feed
$LED = isset($data4["last_value"]) ? $data4["last_value"] : null; // led feed
print_r($Fan);


// Convert presence value to integers
if ($pres === "Yes") {
    $pres = 1;
} elseif ($pres === "No") {
    $pres = 0;
} else {
    $pres = -1;
}

// Extract timestamps
date_default_timezone_set("Asia/Ho_Chi_Minh");
$dateTimeLight = isset($data["updated_at"]) ? date("Y-m-d H:i:s", strtotime($data["updated_at"])) : null;
$dateTimeTemper = isset($data1["updated_at"]) ? date("Y-m-d H:i:s", strtotime($data1["updated_at"])) : null;
$dateTimePres = isset($data2["updated_at"]) ? date("Y-m-d H:i:s", strtotime($data2["updated_at"])) : null;
$dateTimeFan = isset($data3["updated_at"]) ? date("Y-m-d H:i:s", strtotime($data3["updated_at"])) : null;
$dateTimeLED = isset($data4["updated_at"]) ? date("Y-m-d H:i:s", strtotime($data4["updated_at"])) : null;
print_r($dateTimeFan);


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


$stmt = $mysqli->prepare("INSERT INTO fan (RID, FID, DateTime, Fan_Speed) VALUES (1, 1, ?, ?) ON DUPLICATE KEY UPDATE Fan_Speed = VALUES(Fan_Speed)");
$stmt->bind_param("si", $dateTimeFan, $Fan);
$stmt->execute();
$stmt->close();


$stmt = $mysqli->prepare("INSERT INTO light (RID, LID, DateTime, Intensity) VALUES (1, 1, ?, ?) ON DUPLICATE KEY UPDATE Intensity = VALUES(Intensity)");
$stmt->bind_param("sd", $dateTimeLED, $LED);
$stmt->execute();
$stmt->close();



$mysqli->close();

echo json_encode(['status' => 'success', 'message' => 'Data fetched and inserted successfully']);
