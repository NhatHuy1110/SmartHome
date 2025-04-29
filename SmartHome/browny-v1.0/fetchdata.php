<?php
require_once 'Connection2.php';
$db = new DBConn();

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



// Insert into sensors table
$sensorData = [
    'RID' => 1,
    'DateTime' => $dateTime,
    'Luminosity' => $lum,
    'Temperature' => $temp,
    'Presence' => $pres
];
$sensorId = $db->insert('sensors', $sensorData, "isddd");

// Insert into fan table (with ON DUPLICATE KEY UPDATE)
$fanData = [
    'RID' => 1,
    'FID' => 1,
    'DateTime' => $dateTimeFan,
    'Fan_Speed' => $Fan
];
$fanId = $db->insert('fan', $fanData, "iisd");

// Insert into light table (with ON DUPLICATE KEY UPDATE)
$lightData = [
    'RID' => 1,
    'LID' => 1,
    'DateTime' => $dateTimeLED,
    'Intensity' => $LED
];
$lightId = $db->insert('light', $lightData, "iisd");

// Close the database connection
//$db->close();

echo json_encode(['status' => 'success', 'message' => 'Data fetched and inserted successfully']);
