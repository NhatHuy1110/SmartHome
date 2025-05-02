<?php
// Include the Connection2.php file
session_start();
require_once 'Connection2.php';

// Create an instance of DBConn
$db = new DBConn();

// Fetch data from Sensors table (last 10 rows, ordered by DateTime)
$sensorsData = $db->selectWhere(
    'Sensors',
    [],
    'DateTime',
    10,
    'DESC',
    '',
    []
);

// Fetch data from Fan table (last 10 rows, ordered by DateTime)
$fanData = $db->selectWhere(
    'Fan',
    [],
    'DateTime',
    10,
    'DESC',
    '',
    []
);

// Fetch data from Light table (last 10 rows, ordered by DateTime)
$lightData = $db->selectWhere(
    'Light',
    [],
    'DateTime',
    10,
    'DESC',
    '',
    []
);

// Close the database connection
//$db->close();
?>
<!DOCTYPE html>
<html lang="en">

<?php
$pageTitle = "Database Logs";
include 'head.php';
?>

<body>

    <?php include 'navbar.php'; ?>

    <div class="clearfix"></div>

    <div class="container" style="margin-top: 100px;">
        <h2>Sensors Table</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>RID</th>
                    <th>DateTime</th>
                    <th>Luminosity</th>
                    <th>Temperature</th>
                    <th>Presence</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($sensorsData) {
                    foreach ($sensorsData as $row) {
                        echo "<tr>
                                <td>{$row['RID']}</td>
                                <td>{$row['DateTime']}</td>
                                <td>{$row['Luminosity']}</td>
                                <td>{$row['Temperature']}</td>
                                <td>" . ($row['Presence'] ? 'Yes' : 'No') . "</td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>No data available</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <h2>Fan Table</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>RID</th>
                    <th>FID</th>
                    <th>DateTime</th>
                    <th>Fan Speed</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($fanData) {
                    foreach ($fanData as $row) {
                        echo "<tr>
                                <td>{$row['RID']}</td>
                                <td>{$row['FID']}</td>
                                <td>{$row['DateTime']}</td>
                                <td>{$row['Fan_Speed']}</td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>No data available</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <h2>Light Table</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>RID</th>
                    <th>LID</th>
                    <th>DateTime</th>
                    <th>Intensity</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($lightData) {
                    foreach ($lightData as $row) {
                        echo "<tr>
                                <td>{$row['RID']}</td>
                                <td>{$row['LID']}</td>
                                <td>{$row['DateTime']}</td>
                                <td>{$row['Intensity']}</td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>No data available</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

</body>

</html>