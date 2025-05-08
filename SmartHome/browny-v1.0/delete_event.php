<?php
require_once 'Connection2.php';
$db = new DBConn();

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['EID'])) {
    try {
        $db->delete('event', ['EID' => $data['EID']]);
        echo json_encode(['success' => true]);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
}
