<?php
// Database configuration
define('DB_HOST', 'localhost');
define('DB_USERNAME', 'regias_jd');
define('DB_PASSWORD', 'ZGJTi336TFwzTekJ');
define('DB_NAME', 'regias_jd');

// Create a database connection
$conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Check the connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Set character set to utf8mb4 for full Unicode support
$conn->set_charset("utf8mb4");
?>
