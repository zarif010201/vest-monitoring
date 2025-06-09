<?php
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

// Get the last 60 data points
$sql = "SELECT velocity, fsr FROM sensor_readings ORDER BY timestamp DESC LIMIT 60";
$result = $conn->query($sql);

$data = array();

if ($result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        // Ensure we have valid numeric values
        $velocity = floatval($row["velocity"]);
        $fsr = floatval($row["fsr"]);
        
        if (!is_nan($velocity) && !is_nan($fsr)) {
            $data[] = array(
                "x" => $velocity,
                "y" => $fsr
            );
        }
    }
}

// Reverse the array to show data in chronological order
$data = array_reverse($data);

// Return data as JSON
header('Content-Type: application/json');
echo json_encode($data, JSON_NUMERIC_CHECK);

$conn->close();
?> 