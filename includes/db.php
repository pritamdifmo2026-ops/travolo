<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "travelo";

// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create database if not exists
$sql = "CREATE DATABASE IF NOT EXISTS $dbname";
if ($conn->query($sql) === TRUE) {
    $conn->select_db($dbname);
} else {
    die("Error creating database: " . $conn->error);
}

// Optimization: Using proper Collation for Emoji/Special Chars support
$conn->set_charset("utf8mb4");

// Schema Init logic: Ensure faqs table exists with category column
$conn->query("CREATE TABLE IF NOT EXISTS faqs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category VARCHAR(50) DEFAULT 'General',
    question TEXT NOT NULL,
    answer TEXT NOT NULL,
    sort_order INT DEFAULT 0,
    status TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");
$check_cat = $conn->query("SHOW COLUMNS FROM faqs LIKE 'category'");
if ($check_cat && $check_cat->num_rows == 0) {
    $conn->query("ALTER TABLE faqs ADD category VARCHAR(50) DEFAULT 'General' AFTER id");
}
?>
