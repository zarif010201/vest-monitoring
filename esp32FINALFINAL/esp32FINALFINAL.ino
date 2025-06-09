#include <WiFi.h>
#include <HTTPClient.h>
#include <Wire.h>
#include <Adafruit_MPU6050.h>
#include <Adafruit_Sensor.h>
#include <math.h>

// Wi-Fi credentials
const char* ssid = "changdol";
const char* password = "12345678";

// Server PHP script URL
const char* serverName = "http://172.20.10.11/FINALYEARPLEASEUSETHISFILE/insert_sensor_data.php";

// Sensor setup
Adafruit_MPU6050 mpu;
const int fsrPin = 34;

void setup() {
  Serial.begin(115200);

  // Connect to WiFi
  Serial.print("Connecting to WiFi");
  WiFi.begin(ssid, password);
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }
  Serial.println("\nConnected to WiFi!");
  Serial.println("IP Address: " + WiFi.localIP().toString());

  // Initialize MPU6050
  Wire.begin(21, 22); // SDA, SCL
  if (!mpu.begin()) {
    Serial.println("Failed to find MPU6050 chip");
    while (1) delay(10);
  }
  Serial.println("MPU6050 initialized!");

  mpu.setAccelerometerRange(MPU6050_RANGE_8_G);
  mpu.setGyroRange(MPU6050_RANGE_500_DEG);
  mpu.setFilterBandwidth(MPU6050_BAND_21_HZ);
}

void loop() {
  // Read FSR
  int fsrValue = analogRead(fsrPin);

  // Read MPU6050
  sensors_event_t a, g, temp;
  mpu.getEvent(&a, &g, &temp);

  // Compute velocity as magnitude of acceleration vector
  float velocity = sqrt(
    a.acceleration.x * a.acceleration.x +
    a.acceleration.y * a.acceleration.y +
    a.acceleration.z * a.acceleration.z
  );

  // Display data
  Serial.println("Sensor Readings:");
  Serial.print("FSR Value: "); Serial.println(fsrValue);
  Serial.print("Velocity: "); Serial.println(velocity);
  Serial.println("------------------------");

  // Reconnect WiFi if disconnected
  if (WiFi.status() != WL_CONNECTED) {
    Serial.println("WiFi Disconnected. Reconnecting...");
    WiFi.begin(ssid, password);
    delay(2000);
  }

  // Send data
  if (WiFi.status() == WL_CONNECTED) {
    HTTPClient http;
    http.begin(serverName);
    http.addHeader("Content-Type", "application/x-www-form-urlencoded");

    String postData = "fsr=" + String(fsrValue) +
                      "&acc_x=" + String(a.acceleration.x) +
                      "&acc_y=" + String(a.acceleration.y) +
                      "&acc_z=" + String(a.acceleration.z) +
                      "&gyro_x=" + String(g.gyro.x) +
                      "&gyro_y=" + String(g.gyro.y) +
                      "&gyro_z=" + String(g.gyro.z) +
                      "&velocity=" + String(velocity);

    int httpResponseCode = http.POST(postData);
    Serial.print("HTTP Response code: ");
    Serial.println(httpResponseCode);
    http.end();
  } else {
    Serial.println("Cannot send data: No WiFi");
  }

  delay(1000);
}
