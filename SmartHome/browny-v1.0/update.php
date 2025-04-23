<?php
require 'Connection.php';
$conn = Connect();

$rid = $_GET['rid'];
$datetime = $_GET['datetime'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $luminosity = $_POST["luminosity"];
    $temperature = $_POST["temperature"];
    $presence = $_POST["presence"];

    $sql = "UPDATE sensors SET Luminosity = ?, Temperature = ?, Presence = ? WHERE RID = ? AND DateTime = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ddiss", $luminosity, $temperature, $presence, $rid, $datetime);

    if ($stmt->execute()) {
        header("Location: Table.php");
    } else {
        echo "Update failed: " . $stmt->error;
    }
} else {
    $stmt = $conn->prepare("SELECT * FROM sensors WHERE RID = ? AND DateTime = ?");
    $stmt->bind_param("ss", $rid, $datetime);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
?>
    <form method="POST">
        Luminosity: <input type="number" step="0.01" name="luminosity" value="<?= $row['Luminosity'] ?>"><br>
        Temperature: <input type="number" step="0.01" name="temperature" value="<?= $row['Temperature'] ?>"><br>
        Presence: <input type="text" name="presence" value="<?= $row['Presence'] ?>"><br>
        <input type="submit" value="Update">
    </form>
<?php } ?>