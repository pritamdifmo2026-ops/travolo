<?php
include 'includes/db.php';

echo "<h2>Master Database Setup Started...</h2>";

$tables = [
    "CREATE TABLE IF NOT EXISTS admins (
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) UNIQUE,
        password VARCHAR(255),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )",
    "CREATE TABLE IF NOT EXISTS flights (
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
    )",
    "CREATE TABLE IF NOT EXISTS hotels (
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        user_id INT(6) UNSIGNED DEFAULT 0,
        user_name VARCHAR(100),
        check_in VARCHAR(30),
        check_out VARCHAR(30),
        hotel_search VARCHAR(100),
        accommodations VARCHAR(50),
        phone VARCHAR(20),
        email VARCHAR(100),
        hotel_id INT(6),
        status VARCHAR(30) DEFAULT 'Checked',
        booking_type ENUM('Check', 'Booking') DEFAULT 'Check',
        booking_status VARCHAR(30) DEFAULT 'Requested',
        booking_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )",
    "CREATE TABLE IF NOT EXISTS cabs (
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        user_id INT(6) UNSIGNED DEFAULT 0,
        user_name VARCHAR(100),
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
    )",
    "CREATE TABLE IF NOT EXISTS app_hotels (
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
    )",
    "CREATE TABLE IF NOT EXISTS hotel_rooms (
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
    )",
    "CREATE TABLE IF NOT EXISTS hotel_offers (
        id INT AUTO_INCREMENT PRIMARY KEY,
        badge VARCHAR(50),
        header_small VARCHAR(100),
        header_main VARCHAR(100),
        promo_code VARCHAR(50),
        main_title VARCHAR(255),
        validity_text VARCHAR(100),
        image_path VARCHAR(255),
        theme_color VARCHAR(20),
        status INT DEFAULT 1,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )",
    "CREATE TABLE IF NOT EXISTS top_flight_routes (
        id INT AUTO_INCREMENT PRIMARY KEY,
        city_name VARCHAR(100),
        via_cities VARCHAR(255),
        image_path VARCHAR(255),
        from_query VARCHAR(100),
        to_query VARCHAR(100),
        status TINYINT DEFAULT 1,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )",
    "CREATE TABLE IF NOT EXISTS cab_packages (
        id INT AUTO_INCREMENT PRIMARY KEY,
        package_name VARCHAR(100) NOT NULL,
        hours INT NOT NULL,
        km INT NOT NULL,
        status TINYINT DEFAULT 1,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )",
    "CREATE TABLE IF NOT EXISTS cab_transfers (
        id INT AUTO_INCREMENT PRIMARY KEY,
        city VARCHAR(100) NOT NULL,
        airport VARCHAR(255) NOT NULL,
        image_path VARCHAR(255) NOT NULL,
        badge_text VARCHAR(50) DEFAULT NULL,
        status TINYINT(1) DEFAULT 1,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )",
    "CREATE TABLE IF NOT EXISTS cab_hourly (
        id INT AUTO_INCREMENT PRIMARY KEY,
        city VARCHAR(100) NOT NULL,
        location_tag VARCHAR(100) DEFAULT NULL,
        image_path VARCHAR(255) NOT NULL,
        price_per_hr INT NOT NULL,
        status TINYINT(1) DEFAULT 1,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )",
    "CREATE TABLE IF NOT EXISTS cab_overseas (
        id INT AUTO_INCREMENT PRIMARY KEY,
        city VARCHAR(100) NOT NULL,
        description TEXT NOT NULL,
        image_path VARCHAR(255) NOT NULL,
        price_starts VARCHAR(50) NOT NULL,
        status TINYINT(1) DEFAULT 1,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )",
    "CREATE TABLE IF NOT EXISTS cab_inventory (
        id INT AUTO_INCREMENT PRIMARY KEY,
        category VARCHAR(50) NOT NULL,
        car_name VARCHAR(100) NOT NULL,
        image_path VARCHAR(255) NOT NULL,
        capacity INT DEFAULT 4,
        luggage INT DEFAULT 2,
        base_price INT NOT NULL,
        price_per_km DECIMAL(10,2) DEFAULT 0,
        features TEXT,
        rating DECIMAL(2,1) DEFAULT 4.5,
        status TINYINT(1) DEFAULT 1,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )",
    "CREATE TABLE IF NOT EXISTS cab_offers (
        id INT AUTO_INCREMENT PRIMARY KEY,
        badge VARCHAR(50),
        header_small VARCHAR(100),
        header_main VARCHAR(100),
        promo_code VARCHAR(20),
        main_title VARCHAR(255),
        validity_text VARCHAR(100),
        image_path VARCHAR(255),
        theme_color VARCHAR(20) DEFAULT '#00a79d',
        status TINYINT DEFAULT 1
    )",
    "CREATE TABLE IF NOT EXISTS cab_outstation (
        id INT AUTO_INCREMENT PRIMARY KEY,
        city VARCHAR(100) NOT NULL,
        thumbnail VARCHAR(255) NOT NULL,
        destinations TEXT NOT NULL,
        status TINYINT DEFAULT 1,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )",
    "CREATE TABLE IF NOT EXISTS cab_cities_suggestions (
        id INT AUTO_INCREMENT PRIMARY KEY,
        city_name VARCHAR(100) NOT NULL,
        city_code VARCHAR(20),
        airport_name VARCHAR(255),
        status TINYINT DEFAULT 1,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )",
    "CREATE TABLE IF NOT EXISTS app_offers (
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        image_url VARCHAR(255) NOT NULL,
        badge_text VARCHAR(100) NOT NULL,
        badge_color VARCHAR(50) DEFAULT 'primary',
        title VARCHAR(255) NOT NULL,
        description VARCHAR(255),
        footer_text VARCHAR(255),
        status INT DEFAULT 1,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )",
    "CREATE TABLE IF NOT EXISTS contact_messages (
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(50),
        email VARCHAR(50),
        phone VARCHAR(20),
        website VARCHAR(100),
        message TEXT,
        date_sent TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )"
];

foreach ($tables as $sql) {
    if ($conn->query($sql)) {
        echo "Query successful.<br>";
    } else {
        echo "Error: " . $conn->error . "<br>";
    }
}

// Seed Admin if missing
$check = $conn->query("SELECT id FROM admins WHERE username='admin'");
if ($check && $check->num_rows == 0) {
    $hashed = password_hash('password123', PASSWORD_DEFAULT);
    $conn->query("INSERT INTO admins (username, password) VALUES ('admin', '$hashed')");
    echo "Admin seeded.<br>";
}

echo "<h3>All tables verified/created!</h3>";
?>
