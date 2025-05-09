<?php
require_once 'Connection2.php'; // Use the DBConn class
require_once 'config.php';

$db = new DBConn();

$conn = $db->getConnection();

function fetchLatestFeedValue($url, $apiKey)
{
    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => ["X-AIO-Key: $apiKey"]
    ]);

    $response = curl_exec($ch);
    $result = null;

    if (curl_errno($ch)) {
        echo 'Error fetching data from ' . $url . ': ' . curl_error($ch);
    } else {
        $data = json_decode($response, true);
        $result = $data['last_value'] ?? null;
    }

    curl_close($ch);
    return $result;
}

$latest_value = fetchLatestFeedValue($fanControlFeedUrl, $adaApiKey);
$latest_value1 = fetchLatestFeedValue($ledControlFeedUrl, $adaApiKey);
