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

// Schema Init logic (Simplified version check or skip if tables exist)
// We'll trust the existing schema for now as per user's "everything is perfect" feedback.
?>
