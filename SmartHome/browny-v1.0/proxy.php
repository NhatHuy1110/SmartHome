<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $value = $_POST["value"] ?? null;
    $device = $_POST["device"] ?? null;

    if ($value === null) {
        http_response_code(400);
        echo "Missing value.";
        exit();
    }

    // Choose correct URL based on device
    switch (strtolower($device)) {
        case 'led':
            $dataUrl = $ledDataUrl;
            break;
        case 'fan':
            $dataUrl = $fanDataUrl;
            break;
        default:
            http_response_code(400);
            echo "Unknown device type.";
            exit();
    }

    $data = json_encode(["value" => $value]); // Force numeric format

    $ch = curl_init($dataUrl);
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
