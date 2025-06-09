<?php
$conn = new mysqli("localhost", "root", "", "sensor_data");
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM sensor_readings ORDER BY timestamp DESC LIMIT 1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
  $row = $result->fetch_assoc();
  echo "<tr>";
  echo "<td>{$row['fsr']}</td>";
  echo "<td>{$row['velocity']}</td>";
  echo "<td>{$row['timestamp']}</td>";
  echo "</tr>";
} else {
  echo "<tr><td colspan='9'>No data available</td></tr>";
}

$conn->close();
?>
