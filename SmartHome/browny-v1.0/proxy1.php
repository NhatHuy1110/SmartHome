<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $api_key = "aio_itBH53rzbfnwfhiabK1R9p2mAOKx";
    $url = "https://io.adafruit.com/api/v2/anhtanggroup1/feeds/led/data";
    $data = json_encode(array("value" => $_POST["value"]));

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        "X-AIO-Key: $api_key",
        "Content-Type: application/json"
    ));

    $response = curl_exec($ch);
    curl_close($ch);

    echo $response;

}
?>