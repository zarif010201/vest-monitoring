<?php
header('Content-Type: application/json');

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sensor_data";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the most recent data point
$sql = "SELECT fsr FROM sensor_readings ORDER BY timestamp DESC LIMIT 1";
$result = $conn->query($sql);

$data = array();

if ($result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $data[] = array(
            "fsr" => floatval($row["fsr"])
        );
    }
}

// Return data as JSON
echo json_encode($data, JSON_NUMERIC_CHECK);

$conn->close();
?> 