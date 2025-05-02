<?php
require_once 'Connection2.php';
require_once 'config.php';
$db = new DBConn();

function fetchDataFromFeed($url, $apiKey)
{
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ["X-AIO-Key: $apiKey"]);
    $response = curl_exec($ch);
    curl_close($ch);
    return json_decode($response, true);
}

// Fetch data from different feeds
$data = fetchDataFromFeed($lightLevelFeedUrl, $adaApiKey); // Light feed
$data1 = fetchDataFromFeed($temperatureFeedUrl, $adaApiKey); // Temperature feed
$data2 = fetchDataFromFeed($motionFeedUrl, $adaApiKey); // Movement feed
$data3 = fetchDataFromFeed($fanControlFeedUrl, $adaApiKey); // Fan feed
$data4 = fetchDataFromFeed($ledControlFeedUrl, $adaApiKey); // LED feed

// Extract the latest values
function getLastValue($data)
{
    return isset($data["last_value"]) ? $data["last_value"] : null;
}
$lum  = getLastValue($data);   // Light feed
$temp = getLastValue($data1);  // Temperature feed
$pres = getLastValue($data2);  // Movement feed
$Fan  = getLastValue($data3);  // Fan feed
$LED  = getLastValue($data4);  // LED feed
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
function getFormattedTimestamp($data)
{
    return isset($data["updated_at"]) ? date("Y-m-d H:i:s", strtotime($data["updated_at"])) : null;
}
$dateTimeLight  = getFormattedTimestamp($data);
$dateTimeTemper = getFormattedTimestamp($data1);
$dateTimePres   = getFormattedTimestamp($data2);
$dateTimeFan    = getFormattedTimestamp($data3);
$dateTimeLED    = getFormattedTimestamp($data4);
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
