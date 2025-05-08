<?php
header('Content-Type: application/json');
session_start();
// Include the Connection2.php file
require_once 'Connection2.php';

// Create an instance of DBConn
$db = new DBConn();
$conn = $db->getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['uid'])) {
        http_response_code(401);
        echo json_encode(['success' => false, 'message' => 'User not authenticated']);
        exit;
    }
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        echo json_encode(['success' => false, 'message' => 'Invalid JSON input']);
        exit;
    }

    // Access the event data
    $eventDate = $data['EDate'] ?? null;
    $startTime = $data['Start_time'] ?? null;
    $duration = $data['Duration'] ?? null;
    $repeat = $data['ERepeat'] ?? null;
    $status = 'on';

    // Validate required fields
    if (!$eventDate || !$startTime || !$duration || !$repeat) {
        echo json_encode(['success' => false, 'message' => 'Missing required fields']);
        exit;
    }

    // Insert the event into the database
    $query = "INSERT INTO event (EDate, Start_time, Duration, ERepeat, Status) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ssiss', $eventDate, $startTime, $duration, $repeat, $status);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Event added successfully']);
    } else {
        // Log the error message
        $error = $stmt->error; // Get the error message from the statement
        $queryError = $conn->error; // Get the error message from the connection
        echo json_encode([
            'success' => false,
            'message' => 'Failed to add event',
            'stmt_error' => $error,
            'query_error' => $queryError
        ]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
