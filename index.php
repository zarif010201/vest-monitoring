<?php
// Correct DB connection
$link = mysqli_connect("localhost", "root", "", "sensor_data");

// Check connection
if (!$link) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Fetch actual data from sensor_readings table
$test = array();
$res = mysqli_query($link, "SELECT fsr, timestamp FROM sensor_readings ORDER BY timestamp ASC");

$start_time = null;
while ($row = mysqli_fetch_assoc($res)) {
    if ($start_time === null) {
        $start_time = strtotime($row["timestamp"]);
    }
    $current_time = strtotime($row["timestamp"]);
    $seconds = $current_time - $start_time;
    
    // Only include data points within 60 seconds
    if ($seconds <= 60) {
        $test[] = array(
            "x" => $seconds,
            "y" => (float)$row["fsr"]
        );
    }
}
?>
<!DOCTYPE HTML>
<html>
<head>
<title>Sensor Chart</title>
<script>
let chart;
let startTime;

window.onload = function () {
    startTime = new Date().getTime();
    
    chart = new CanvasJS.Chart("chartContainer", {
        title: {
            text: "Force vs Time"
        },
        axisY: {
            title: "Force (FSR Value)"
        },
        axisX: {
            title: "Time (seconds)",
            minimum: 0,
            maximum: 60,
            interval: 5
        },
        data: [{
            type: "line",
            dataPoints: <?php echo json_encode($test, JSON_NUMERIC_CHECK); ?>
        }]
    });
    chart.render();

    // Start real-time updates
    setInterval(updateChart, 1000); // Update every second
}

function updateChart() {
    fetch('fetch_realtime_data.php')
        .then(response => response.json())
        .then(data => {
            if (data && data.length > 0) {
                // Calculate seconds elapsed
                const currentTime = new Date().getTime();
                const secondsElapsed = Math.floor((currentTime - startTime) / 1000);
                
                // Only add points within 60 seconds
                if (secondsElapsed <= 60) {
                    // Add new data point
                    chart.options.data[0].dataPoints.push({
                        x: secondsElapsed,
                        y: data[0].fsr
                    });
                    
                    // Remove points older than 60 seconds
                    while (chart.options.data[0].dataPoints[0].x < secondsElapsed - 60) {
                        chart.options.data[0].dataPoints.shift();
                    }
                    
                    chart.render();
                }
            }
        })
        .catch(error => console.error('Error fetching data:', error));
}
</script>
</head>
<body>
<div id="chartContainer" style="height: 370px; width: 100%;"></div>
<script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>
</body>
</html>
