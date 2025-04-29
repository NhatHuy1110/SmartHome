<?php
session_start();
require 'Connection2.php'; // Use DBConn-based connection
$db = new DBConn();
$conn = $db->getConnection();

header('Content-Type: application/json'); // Set the content type to JSON

// Check if the required POST parameters are set
if (isset($_POST['dateStart']) && isset($_POST['dateEnd'])) {
    $dateStart = $_POST['dateStart'] . " 00:00:00"; // Append time to start date
    $dateEnd = $_POST['dateEnd'] . " 23:59:59"; // Append time to end date
    $uid = $_SESSION['uid']; // Get the user ID from the session

    // Query to fetch data based on the provided parameters
    $sql = "SELECT * FROM sensors 
            WHERE DateTime BETWEEN ? AND ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ss', $dateStart, $dateEnd);

    if ($stmt->execute()) {
        $result = $stmt->get_result();
        $data = array();

        // Fetch all rows and add them to the $data array
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        // Return the data as a JSON response
        echo json_encode($data);
    } else {
        // Handle query execution errors
        echo json_encode(['success' => false, 'message' => 'Failed to fetch data']);
    }

    $stmt->close(); // Close the prepared statement
} else {
    // Handle missing parameters
    echo json_encode(['success' => false, 'message' => 'Missing required parameters']);
}

$conn->close(); // Close the database connection
