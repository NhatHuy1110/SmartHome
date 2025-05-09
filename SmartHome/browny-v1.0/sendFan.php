<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $value = $_POST["value"];
    $data = json_encode(["value" => $value]);
    $ch = curl_init($fanDataUrl);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "X-AIO-Key: $adaApiKey",
        "Content-Type: application/json"
    ]);

    $response = curl_exec($ch);
    curl_close($ch);

    echo $response;
}
?>