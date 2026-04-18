<?php
$servername = "127.0.0.1";
$username = "root";
$password = "";

// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully\n";

// Create database
$sql = "CREATE DATABASE IF NOT EXISTS sms_db";
if ($conn->query($sql) === TRUE) {
  echo "Database sms_db created successfully or already exists\n";
} else {
  echo "Error creating database: " . $conn->error . "\n";
}

$conn->close();
?>
