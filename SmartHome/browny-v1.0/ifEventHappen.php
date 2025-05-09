<?php
//work in progress, currently unused
require_once 'Connection2.php';

$db = new DBConn();
$conn = $db->getConnection();

// Current datetime
$now = date('Y-m-d H:i:s');

// Query events where current time >= start time and not yet notified
$events = $db->selectWhere(
    'Event',
    ['Status' => 'on', 'Notified' => false],
    'Start_Time',
    0
);

if ($events) {
    foreach ($events as $row) {
        $startDateTime = $row['EDate'] . ' ' . $row['Start_Time'];
        if (strtotime($startDateTime) <= strtotime($now)) {
            $eid = $row['EID'];
            // Insert notification
            $msg = "Event triggered at $now";
            $notification = [
                'EID' => $eid,
                'DateTime' => $now,
                'Error_Message' => $msg
            ];
            $db->insert('Notification', $notification);

            // Mark event as notified
            $update = [
                'Notified' => true
            ];
            $conditions = ['EID' => $eid];
            $db->update('Event', $update, $conditions);
        }
    }
}
