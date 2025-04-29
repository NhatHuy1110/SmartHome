<?php
header('Content-Type: application/json');
session_start();
// Include the Connection2.php file
require_once 'Connection2.php';

// Create an instance of DBConn
$db = new DBConn();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['uid'])) {
        http_response_code(401);
        echo json_encode(['success' => false, 'message' => 'User not authenticated']);
        exit;
    }
    $data = json_decode(file_get_contents('php://input'), true);
    // Validate input
    if (
        isset($data['EName'], $data['EDate'], $data['Start_time'], $data['Duration'], $data['Temp_Upper'], $data['Temp_Lower'], $data['Lum_Upper'], $data['Lum_Lower'], $data['ERepeat'])
    ) {
        $EName = $data['EName'];
        $EDate = $data['EDate'];
        $Start_time = $data['Start_time'];
        $Duration = $data['Duration'];
        $Temp_Upper = $data['Temp_Upper'];
        $Temp_Lower = $data['Temp_Lower'];
        $Lum_Upper = $data['Lum_Upper'];
        $Lum_Lower = $data['Lum_Lower'];
        $ERepeat = $data['ERepeat'];
        $UID = $_SESSION['uid']; // Assuming user ID is stored in the session

        // Check for duplicate events
        $checkQuery = [
            'UID' => $UID,
            'EName' => $EName,
            'EDate' => $EDate
        ];
        $checkStmt = $db->insert('sensors', $checkQuery, "iss");
        $result = $checkStmt->get_result();

        if ($result->num_rows > 0) {
            echo json_encode(['success' => false, 'duplicate' => true, 'message' => 'Duplicate event detected']);
        } else {
            // Insert the event into the database
            $eventData = [
                'UID' => $UID,
                'EName' => $EName,
                'EDate' => $EDate,
                'Start_time' => $Start_time,
                'Duration' => $Duration,
                'Temp_Upper' => $Temp_Upper,
                'Temp_Lower' => $Temp_Lower,
                'Lum_Upper' => $Lum_Upper,
                'Lum_Lower' => $Lum_Lower,
                'ERepeat' => $ERepeat
            ];

            $insertId = $db->insert('event', $eventData, 'isssidddds');
            if ($insertId) {
                echo json_encode(['success' => true, 'message' => 'Event added successfully']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to add event']);
            }
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid input']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
