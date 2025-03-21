<?php
$apiKey = "aio_LIqB169twxN0FnyGFCgju0brabYi";
$feedName = "Light level";
$url = "https://io.adafruit.com/api/v2/anhtanggroup1/feeds/light-level";

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "X-AIO-Key: $apiKey"
]);

$response = curl_exec($ch);
curl_close($ch);

$data = json_decode($response, true);

print_r($data);
?>