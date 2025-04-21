<?php
$apiKey = "aio_eKEI39ToPIcKaNgfs6mN5WV1mbs7";
$feedName = "Light level";
$url = "https://io.adafruit.com/api/v2/anhtanggroup1/feeds/light-level";
$url1 = "https://io.adafruit.com/api/v2/anhtanggroup1/feeds/temper";
$url2 = "https://io.adafruit.com/api/v2/anhtanggroup1/feeds/movement";
#light
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "X-AIO-Key: $apiKey"
]);
#temp
$ch1 = curl_init($url1);
curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch1, CURLOPT_HTTPHEADER, [
    "X-AIO-Key: $apiKey"
]);
#pres
$ch2 = curl_init($url2);
curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch2, CURLOPT_HTTPHEADER, [
    "X-AIO-Key: $apiKey"
]);

$response = curl_exec($ch);
curl_close($ch);
$data = json_decode($response, true);
print_r($data);

$response1 = curl_exec($ch1);
curl_close($ch1);
$data1 = json_decode($response1, true);
print_r($data1);

$response2 = curl_exec($ch2);
curl_close($ch2);
$data2 = json_decode($response2, true);
print_r($data2);

$mysqli = new mysqli("localhost:3307", "root", "", "smarthome");

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}
date_default_timezone_set("Asia/Ho_Chi_Minh");
foreach ($data as $item) {
    #$intensity = $item["Intensity"];
    #$datetime = $item["DateTime"];
    $lum = $data["last_value"];
    $datetime = date("Y-m-d H:i:s", strtotime($data["updated_at"]));

    $temp = $data1["last_value"];

    $pres = $data2["last_value"];

    if ($pres === "Yes") {
        $pres = 1;
    } elseif ($pres === "No") {
        $pres = 0;
    } else {
        $pres = -1;
    }



    $stmt = $mysqli->prepare("INSERT INTO sensors (RID,DateTime,Luminosity, Temperature,Presence) VALUES (1,?,?,?, ?)");
    $stmt->bind_param("sddd", $datetime, $lum, $temp, $pres);
    $stmt->execute();
}
$stmt->close();
$mysqli->close();
echo json_encode(['status' => 'success', 'message' => 'Data fetched and inserted successfully']);
