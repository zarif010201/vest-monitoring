<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sensor_data";

$fsr = $_POST['fsr'];
$acc_x = $_POST['acc_x'];
$acc_y = $_POST['acc_y'];
$acc_z = $_POST['acc_z'];
$gyro_x = $_POST['gyro_x'];
$gyro_y = $_POST['gyro_y'];
$gyro_z = $_POST['gyro_z'];
$velocity = $_POST['velocity'];

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$sql = "INSERT INTO sensor_readings (fsr, acc_x, acc_y, acc_z, gyro_x, gyro_y, gyro_z, velocity)
        VALUES ('$fsr', '$acc_x', '$acc_y', '$acc_z', '$gyro_x', '$gyro_y', '$gyro_z', '$velocity')";

if ($conn->query($sql) === TRUE) {
  echo "Data inserted successfully";
} else {
  echo "Error: " . $sql . "<br>" . $conn->error;
}
$conn->close();
?>
