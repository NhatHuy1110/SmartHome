<?php
require_once 'Connection2.php';
$conn = new DBConn();

$notifs = $conn->selectWhere(
    'notification', // table name
    [],              // empty conditions (no WHERE clause)
    'DateTime',      // order by this field
    0,              // limit
    'DESC',          // order direction
    '',              // types (ignored when no conditions)
    []               // columns (optional, empty means all)
);

$notifications = [];
if ($notifs) {
    foreach ($notifs as $notif) {
        $notifications[] = [
            'Error_Message' => $notif['Error_Message'],
            'DateTime' => $notif['DateTime']
        ];
    }
}

echo json_encode($notifications);
