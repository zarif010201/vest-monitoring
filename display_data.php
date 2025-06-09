<!DOCTYPE html>
<html>
<head>
  <title>Live Sensor Data with Countdown Timer</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 20px;
      background-color: #F0F3F5;
      color: #263238;
    }
    h2 {
      text-align: center;
      font-size: 4em;
      color: #263238;
      margin-bottom: 30px;
    }
    .main-container {
      display: flex;
      justify-content: center;
      gap: 20px;
      margin: 30px auto;
      max-width: 1200px;
      align-items: center;
      background-color: #FFFFFF;
      padding: 20px;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
      border: 2px solid #90CAF9;
      border-radius: 8px;
    }
    .timer-container {
      max-width: 400px;
      margin: 0;
      padding: 20px;
      height: 280px;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      border: 2px solid #90CAF9;
      border-radius: 8px;
      background-color: #FFFFFF;
    }
    #timer {
      font-size: 3em;
      text-align: center;
      margin-bottom: 20px;
      color: #1565C0;
      padding: 10px;
      border: 2px solid #90CAF9;
      border-radius: 8px;
      background-color: #F8F9FA;
    }
    .button-container {
      text-align: center;
      display: flex;
      flex-direction: column;
      gap: 10px;
      width: 100%;
    }
    button {
      padding: 10px 20px;
      font-size: 1em;
      cursor: pointer;
      background-color: #1565C0;
      color: #FFFFFF;
      border: none;
      border-radius: 4px;
      transition: background-color 0.3s ease;
      font-weight: bold;
      width: 100%;
    }
    button:hover {
      background-color: #0D47A1;
    }
    table {
      width: 100%;
      max-width: 500px;
      border-collapse: collapse;
      height: 320px;
      border: 2px solid #90CAF9;
      border-radius: 8px;
      background-color: #FFFFFF;
    }
    th, td {
      padding: 12px;
      text-align: center;
      border: 1px solid #90CAF9;
      color: #263238;
      font-size: 1.2em;
    }
    th {
      background-color: #90CAF9;
      color: #FFFFFF;
      font-weight: bold;
      font-size: 1.4em;
    }
    .chart-container {
      display: none;
      max-width: 1000px;
      margin: 30px auto;
      background: #FFFFFF;
      padding: 20px;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
      border: 2px solid #90CAF9;
    }
    .chart-title {
      text-align: center;
      color: #263238;
      font-size: 1.2em;
      margin-bottom: 15px;
      font-weight: bold;
    }
    #chartContainer {
      height: 370px;
      width: 100%;
    }
    .validity-container {
      display: flex;
      flex-direction: column;
      gap: 10px;
      align-items: center;
      padding: 20px;
      border: 2px solid #90CAF9;
      border-radius: 8px;
      background-color: #FFFFFF;
      width: 100%;
      height: 280px;
    }
    .validity-title {
      color: #263238;
      font-size: 1.4em;
      font-weight: bold;
      margin-bottom: 5px;
    }
    .validity-boxes {
      display: flex;
      gap: 20px;
    }
    .validity-box {
      width: 200px;
      height: 60px;
      background-color: #FFFFFF;
      border: 2px solid #90CAF9;
      border-radius: 8px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.4em;
      font-weight: bold;
      transition: all 0.3s ease;
      color: #263238;
    }
    .validity-box.invalid {
      background-color: #C62828;
      color: #FFFFFF;
      border-color: #C62828;
    }
    .validity-box.valid {
      background-color: #1565C0;
      color: #FFFFFF;
      border-color: #1565C0;
    }
    .validity-accuracy {
      margin-top: 10px;
      padding: 15px;
      background-color: #FFFFFF;
      width: 100%;
    }
    .validity-accuracy-title {
      color: #263238;
      font-size: 1.4em;
      font-weight: bold;
      margin-bottom: 10px;
      text-align: center;
    }
    .validity-counts {
      display: flex;
      justify-content: space-around;
      gap: 10px;
      width: 100%;
    }
    .count-box {
      text-align: center;
      padding: 10px;
      border: 3px solid #90CAF9;
      border-radius: 4px;
      min-width: 180px;
    }
    .count-label {
      font-size: 1.2em;
      color: #263238;
      margin-bottom: 5px;
    }
    .count-value {
      font-size: 1.8em;
      font-weight: bold;
      color: #1565C0;
    }
  </style>
  <script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>
</head>
<body>
  <h2>Taekwondo Vest Training Monitoring</h2>

  <div class="main-container">
    <div class="timer-container">
      <div id="timer">01:00</div>
      <div class="button-container">
        <button onclick="startTimer()">Start</button>
        <button onclick="stopTimer()">Stop</button>
        <button onclick="resetTimer()">Reset</button>
      </div>
    </div>

    <div class="validity-container">
      <div class="validity-title">Impact Validity</div>
      <div class="validity-boxes">
        <div class="validity-box" id="invalidBox">Invalid</div>
        <div class="validity-box" id="validBox">Valid</div>
      </div>
      <div class="validity-accuracy">
        <div class="validity-accuracy-title">Validity Accuracy</div>
        <div class="validity-counts">
          <div class="count-box">
            <div class="count-label">Valid Forces</div>
            <div class="count-value" id="validCount">0</div>
          </div>
          <div class="count-box">
            <div class="count-label">Valid Force %</div>
            <div class="count-value" id="validPercentage">0%</div>
          </div>
        </div>
      </div>
    </div>

    <table>
      <thead>
        <tr>
          <th>FSR</th>
          <th>Velocity</th>
          <th>Timestamp</th>
        </tr>
      </thead>
      <tbody id="data-body">
        <!-- Updated by JS -->
      </tbody>
    </table>
  </div>

  <div class="chart-container">
    <div class="chart-title">Force vs Time</div>
    <div id="chartContainer"></div>
  </div>

  <script>
    let countdown;
    let timeLeft = 60;
    let timerRunning = false;
    let chart;
    let startTime;
    let validCount = 0;
    let totalCount = 0;

    // Initialize chart with data from PHP
    window.onload = function() {
      // Initialize timer display
      updateTimerDisplay();
      showZeroData();
      updateValidityCounts();

      // Initialize chart
      chart = new CanvasJS.Chart("chartContainer", {
        
        backgroundColor: "#FFFFFF",
        axisY: {
          title: "Force (FSR Value)",
          minimum: 0,
          maximum: 4000,
          interval: 500,
          titleFontColor: "#263238",
          labelFontColor: "#263238",
          gridColor: "#90CAF9",
          lineColor: "#90CAF9"
        },
        axisX: {
          title: "Time (seconds)",
          minimum: 0,
          maximum: 60,
          interval: 5,
          titleFontColor: "#263238",
          labelFontColor: "#263238",
          gridColor: "#90CAF9",
          lineColor: "#90CAF9"
        },
        data: [{
          type: "line",
          dataPoints: [],
          color: "#1565C0",
          lineThickness: 2
        }]
      });
      chart.render();
    }

    function updateTimerDisplay() {
      const minutes = Math.floor(timeLeft / 60);
      const seconds = timeLeft % 60;
      document.getElementById("timer").textContent =
        `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
    }

    function updateValidity(fsrValue) {
      const invalidBox = document.getElementById('invalidBox');
      const validBox = document.getElementById('validBox');
      
      // Remove all classes first
      invalidBox.classList.remove('invalid', 'valid');
      validBox.classList.remove('invalid', 'valid');
      
      if (timerRunning) {
        totalCount++;
        if (fsrValue < 1500) {
          invalidBox.classList.add('invalid');
        } else if (fsrValue >= 1501) {
          validBox.classList.add('valid');
          validCount++;
        }
        updateValidityCounts();
      }
    }

    function updateValidityCounts() {
      document.getElementById('validCount').textContent = validCount;
      const percentage = totalCount > 0 ? Math.round((validCount / totalCount) * 100) : 0;
      document.getElementById('validPercentage').textContent = percentage + '%';
    }

    function fetchLatestData() {
      if (!timerRunning) return;

      fetch("fetch_latest_data.php")
        .then(response => response.text())
        .then(data => {
          // Update table data
          document.getElementById("data-body").innerHTML = data;
          
          // Parse FSR value from the first row
          const rows = document.getElementById("data-body").getElementsByTagName("tr");
          if (rows.length > 0) {
            const cells = rows[0].getElementsByTagName("td");
            if (cells.length >= 1) {
              const fsrValue = parseFloat(cells[0].textContent);
              const velocityValue = parseFloat(cells[1].textContent);
              
              if (!isNaN(fsrValue)) {
                updateValidity(fsrValue);
                
                // Update chart with new data point
                if (!isNaN(velocityValue)) {
                  const secondsElapsed = 60 - timeLeft; // Calculate seconds elapsed from timer
                  chart.options.data[0].dataPoints.push({
                    x: secondsElapsed,
                    y: fsrValue
                  });
                  
                  // Keep only points within 60 seconds
                  while (chart.options.data[0].dataPoints[0].x < secondsElapsed - 60) {
                    chart.options.data[0].dataPoints.shift();
                  }
                  
                  chart.render();
                }
              }
            }
          }
        })
        .catch(error => console.error("Error fetching data:", error));
    }

    function showZeroData() {
      document.getElementById("data-body").innerHTML = `
        <tr>
          <td>0</td>
          <td>0</td>
          <td>No data</td>
        </tr>`;
      
      // Reset validity boxes
      const invalidBox = document.getElementById('invalidBox');
      const validBox = document.getElementById('validBox');
      invalidBox.classList.remove('invalid', 'valid');
      validBox.classList.remove('invalid', 'valid');

      // Clear chart data
      if (chart) {
        chart.options.data[0].dataPoints = [];
        chart.render();
      }
    }

    function startTimer() {
      if (timerRunning) return;
      timerRunning = true;
      console.log("Timer started"); // Debug log

      document.querySelector(".chart-container").style.display = "block"; // Show chart
      fetchLatestData();

      countdown = setInterval(() => {
        if (timeLeft <= 0) {
          clearInterval(countdown);
          timerRunning = false;
          document.getElementById("timer").textContent = "00:00";
        } else {
          timeLeft--;
          updateTimerDisplay();
          fetchLatestData();
        }
      }, 1000);
    }

    function stopTimer() {
      clearInterval(countdown);
      timerRunning = false;
      document.querySelector(".chart-container").style.display = "block"; // Keep chart visible
    }

    function resetTimer() {
      clearInterval(countdown);
      timerRunning = false;
      timeLeft = 60; // Reset timer to initial value
      validCount = 0;
      totalCount = 0;
      updateTimerDisplay();
      updateValidityCounts();
      showZeroData(); // Clear table, chart, and validity
      document.querySelector(".chart-container").style.display = "none"; // Hide chart again
    }
  </script>
</body>
</html>