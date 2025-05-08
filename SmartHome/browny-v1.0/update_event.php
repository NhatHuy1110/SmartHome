<?php
require_once 'Connection2.php';
$db = new DBConn();
$conn = $db->getConnection();

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['EID'])) {
    $eid = $data['EID'];
    unset($data['EID']);

    try {
        $db->update('event', $data, ['EID' => $eid]);
        echo json_encode(['success' => true]);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
}
