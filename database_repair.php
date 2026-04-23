<?php
/**
 * Travolo Database Repair & Sync Script
 * Run this script to ensure all tables and columns match the application logic.
 */
include_once 'includes/db.php';

echo "<body style='font-family:sans-serif; background:#f4f7f6; padding:20px;'>";
echo "<div style='max-width:800px; margin:auto; background:white; padding:30px; border-radius:10px; box-shadow:0 5px 15px rgba(0,0,0,0.1);'>";
echo "<h2 style='color:#133a25; border-bottom:2px solid #F7921E; padding-bottom:10px;'>🛠️ Travolo Database Repair Tool</h2>";

function execute($sql, $desc) {
    global $conn;
    if ($conn->query($sql)) {
        echo "<div style='color:green; margin-bottom:10px;'>✅ Success: $desc</div>";
    } else {
        echo "<div style='color:red; margin-bottom:10px;'>❌ Error ($desc): " . $conn->error . "</div>";
    }
}

function addColumn($table, $column, $definition) {
    global $conn;
    $check = $conn->query("SHOW COLUMNS FROM `$table` LIKE '$column'");
    if ($check && $check->num_rows == 0) {
        if ($conn->query("ALTER TABLE `$table` ADD `$column` $definition")) {
            echo "<div style='color:blue; margin-bottom:10px;'>🔹 Added Column: `$column` to `$table`</div>";
        } else {
            echo "<div style='color:red; margin-bottom:10px;'>❌ Error adding `$column` to `$table`: " . $conn->error . "</div>";
        }
    }
}

// 1. SYSTEM TABLES (Core)
execute("CREATE TABLE IF NOT EXISTS users (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    email VARCHAR(100),
    phone VARCHAR(20) UNIQUE NOT NULL,
    otp VARCHAR(10),
    otp_expiry DATETIME,
    social_id VARCHAR(255) DEFAULT NULL,
    social_type VARCHAR(50) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)", "Users Table");

execute("CREATE TABLE IF NOT EXISTS admins (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE,
    password VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)", "Admins Table");

// 2. BOOKING TABLES
execute("CREATE TABLE IF NOT EXISTS flights (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT(6) UNSIGNED DEFAULT 0,
    user_name VARCHAR(100),
    trip_type VARCHAR(30),
    from_city VARCHAR(50),
    to_city VARCHAR(50),
    depart_date VARCHAR(30),
    return_date VARCHAR(30),
    adults INT,
    children INT,
    infants INT,
    travel_class VARCHAR(30),
    phone VARCHAR(20),
    email VARCHAR(100),
    booking_status VARCHAR(30) DEFAULT 'Requested',
    booking_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) engine=InnoDB DEFAULT CHARSET=utf8mb4;", "Flights Booking Table");

execute("CREATE TABLE IF NOT EXISTS hotels (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT(6) UNSIGNED DEFAULT 0,
    user_name VARCHAR(100),
    hotel_id INT(6),
    hotel_search VARCHAR(100),
    check_in VARCHAR(30),
    check_out VARCHAR(30),
    accommodations VARCHAR(50),
    room_type VARCHAR(100),
    guests VARCHAR(50),
    price DECIMAL(10,2) DEFAULT 0,
    phone VARCHAR(20),
    email VARCHAR(100),
    payment_status VARCHAR(30) DEFAULT 'Pending',
    booking_type ENUM('Check', 'Booking') DEFAULT 'Check',
    booking_status VARCHAR(30) DEFAULT 'Requested',
    booking_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) engine=InnoDB DEFAULT CHARSET=utf8mb4;", "Hotels Booking Table");

execute("CREATE TABLE IF NOT EXISTS cabs (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT(6) UNSIGNED DEFAULT 0,
    user_name VARCHAR(100),
    cab_id INT(6) DEFAULT 0,
    trip_type VARCHAR(30),
    pickup_type VARCHAR(50),
    from_city VARCHAR(100),
    to_city VARCHAR(100),
    pickup_date VARCHAR(30),
    pickup_time VARCHAR(20),
    return_date VARCHAR(30),
    return_time VARCHAR(20),
    hours VARCHAR(20),
    phone VARCHAR(20),
    email VARCHAR(100),
    booking_status VARCHAR(30) DEFAULT 'Requested',
    booking_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) engine=InnoDB DEFAULT CHARSET=utf8mb4;", "Cabs Booking Table");

// 3. CATALOG & INVENTORY TABLES
execute("CREATE TABLE IF NOT EXISTS app_hotels (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    location VARCHAR(100),
    price VARCHAR(50),
    accommodations VARCHAR(50),
    category VARCHAR(50) DEFAULT 'Standard',
    image VARCHAR(255),
    description TEXT,
    availability TINYINT(1) DEFAULT 1,
    available_dates TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)", "App Hotels Catalog");

execute("CREATE TABLE IF NOT EXISTS hotel_rooms (
    id INT AUTO_INCREMENT PRIMARY KEY,
    hotel_id INT NOT NULL,
    room_name VARCHAR(100) NOT NULL,
    capacity VARCHAR(50) DEFAULT '2 Adults, 1 Child',
    bed_type VARCHAR(50) DEFAULT 'King Bed',
    features TEXT,
    room_price DECIMAL(10,2) NOT NULL,
    room_image VARCHAR(255),
    status TINYINT(1) DEFAULT 1,
    CREATED_AT TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)", "Hotel Rooms Table");

execute("CREATE TABLE IF NOT EXISTS cab_inventory (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category VARCHAR(50) NOT NULL,
    car_name VARCHAR(100) NOT NULL,
    image_path VARCHAR(255) NOT NULL,
    capacity INT DEFAULT 4,
    luggage INT DEFAULT 2,
    base_price INT NOT NULL,
    hourly_price INT DEFAULT 0,
    airport_price INT DEFAULT 0,
    outstation_price INT DEFAULT 0,
    price_per_km DECIMAL(10,2) DEFAULT 0,
    features TEXT,
    rating DECIMAL(2,1) DEFAULT 4.5,
    status TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)", "Cab Inventory Table");

execute("CREATE TABLE IF NOT EXISTS flight_searches (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT DEFAULT 0,
    from_city VARCHAR(100),
    to_city VARCHAR(100),
    depart_date DATE,
    trip_type VARCHAR(50),
    adults INT,
    children INT,
    infants INT,
    travel_class VARCHAR(50),
    mobile VARCHAR(20),
    email VARCHAR(100),
    search_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)", "Flight Searches Table");

execute("CREATE TABLE IF NOT EXISTS cab_cities_suggestions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    city_name VARCHAR(100),
    city_code VARCHAR(10),
    airport_name VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)", "Cab City Suggestions Table");

// 4. MIGRATIONS (Adding missing columns to existing tables)
echo "<h3>🔧 Column Migrations...</h3>";

addColumn('users', 'social_id', 'VARCHAR(255) DEFAULT NULL');
addColumn('users', 'social_type', 'VARCHAR(50) DEFAULT NULL');

addColumn('hotels', 'room_type', 'VARCHAR(100) AFTER accommodations');
addColumn('hotels', 'guests', 'VARCHAR(50) AFTER room_type');
addColumn('hotels', 'price', 'DECIMAL(10,2) DEFAULT 0 AFTER guests');
addColumn('hotels', 'payment_status', "VARCHAR(30) DEFAULT 'Pending' AFTER email");

addColumn('flights', 'user_name', 'VARCHAR(100) AFTER user_id');

addColumn('cabs', 'user_name', 'VARCHAR(100) AFTER user_id');
addColumn('cabs', 'cab_id', 'INT(6) DEFAULT 0 AFTER user_name');

addColumn('cab_inventory', 'hourly_price', 'INT DEFAULT 0 AFTER base_price');
addColumn('cab_inventory', 'airport_price', 'INT DEFAULT 0 AFTER hourly_price');
addColumn('cab_inventory', 'outstation_price', 'INT DEFAULT 0 AFTER airport_price');

echo "<h3 style='color:green;'>🎉 Database is now fully synchronized!</h3>";
echo "<p>All missing tables and columns have been added. The Admin Panel should now work perfectly.</p>";
echo "<a href='admin/admin.php' style='display:inline-block; background:#F7921E; color:white; padding:10px 20px; text-decoration:none; border-radius:5px;'>Go to Admin Panel</a>";
echo "</div></body>";
?>
