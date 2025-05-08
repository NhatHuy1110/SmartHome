<?php
session_start();
require_once 'Connection2.php'; // Use DBConn-based connection
$db = new DBConn();
$conn = $db->getConnection();

header('Content-Type: application/json'); // Set the content type to JSON

// Check if the required POST parameters are set
if (isset($_POST['dateStart']) && isset($_POST['dateEnd'])) {
    $dateStart = $_POST['dateStart'] . " 00:00:00"; // Append time to start date
    $dateEnd = $_POST['dateEnd'] . " 23:59:59"; // Append time to end date

    $data = [
        'light' => [],
        'fan' => []
    ];

    // Query to fetch data from the 'light' table
    $sqlLight = "SELECT * FROM light WHERE DateTime BETWEEN ? AND ?";
    $stmtLight = $conn->prepare($sqlLight);
    $stmtLight->bind_param('ss', $dateStart, $dateEnd);

    if ($stmtLight->execute()) {
        $resultLight = $stmtLight->get_result();
        while ($row = $resultLight->fetch_assoc()) {
            $data['light'][] = $row;
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to fetch light data']);
        exit;
    }
    $stmtLight->close();

    // Query to fetch data from the 'fan' table
    $sqlFan = "SELECT * FROM fan WHERE DateTime BETWEEN ? AND ?";
    $stmtFan = $conn->prepare($sqlFan);
    $stmtFan->bind_param('ss', $dateStart, $dateEnd);

    if ($stmtFan->execute()) {
        $resultFan = $stmtFan->get_result();
        while ($row = $resultFan->fetch_assoc()) {
            $data['fan'][] = $row;
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to fetch fan data']);
        exit;
    }
    $stmtFan->close();

    // Return the combined data as a JSON response
    echo json_encode($data);
} else {
    // Handle missing parameters
    echo json_encode(['success' => false, 'message' => 'Missing required parameters']);
}

//$conn->close(); // Close the database connection
