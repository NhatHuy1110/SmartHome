<?php

require_once 'Connection2.php';
$conn = new DBConn();

$notifs = $conn->selectNRows('notification', 'DateTime', 10);

?>

<?php
$notifications = [];
foreach ($notifs as $notif) {
    $notifications[] = [
        'Error_Message' => $notif['Error_Message'],
        'DateTime' => $notif['DateTime']
    ];
}

echo json_encode($notifications);
?>