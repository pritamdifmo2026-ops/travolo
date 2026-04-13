<?php
/**
 * Database Setup and Migration Script
 * This script ensures all necessary tables exist and are seeded with initial data.
 */
include_once __DIR__ . '/db.php';

if (!isset($conn)) {
    die("Database connection not found.");
}

// Create tables
$table_flight = "CREATE TABLE IF NOT EXISTS flights (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT(6) UNSIGNED DEFAULT 0,
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
) engine=InnoDB DEFAULT CHARSET=utf8;";
$conn->query($table_flight);

$table_hotel = "CREATE TABLE IF NOT EXISTS hotels (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT(6) UNSIGNED DEFAULT 0,
    check_in VARCHAR(30),
    check_out VARCHAR(30),
    hotel_search VARCHAR(100),
    accommodations VARCHAR(50),
    phone VARCHAR(20),
    hotel_id INT(6),
    status VARCHAR(30) DEFAULT 'Checked',
    user_name VARCHAR(50),
    email VARCHAR(50),
    booking_type ENUM('Check', 'Booking') DEFAULT 'Check',
    booking_status VARCHAR(30) DEFAULT 'Requested',
    booking_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) engine=InnoDB DEFAULT CHARSET=utf8;";
$conn->query($table_hotel);

$table_cab = "CREATE TABLE IF NOT EXISTS cabs (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT(6) UNSIGNED DEFAULT 0,
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
) engine=InnoDB DEFAULT CHARSET=utf8;";
$conn->query($table_cab);

// Add columns if they don't exist (Migration)
$tables_to_check = ['flights', 'hotels', 'cabs'];
foreach ($tables_to_check as $table) {
    $cols = [
        'user_id' => 'INT(6) UNSIGNED DEFAULT 0 AFTER id',
        'email' => 'VARCHAR(100) AFTER phone',
        'booking_status' => "VARCHAR(30) DEFAULT 'Requested' AFTER " . ($table == 'hotels' ? 'booking_type' : ($table == 'flights' ? 'phone' : 'phone'))
    ];
    if ($table == 'hotels') {
        $cols['phone'] = 'VARCHAR(20) AFTER accommodations';
        $cols['user_name'] = 'VARCHAR(50) AFTER status';
        $cols['check_out'] = 'VARCHAR(30) AFTER check_in';
    } else {
        $cols['user_name'] = 'VARCHAR(50) AFTER id';
    }
    
    foreach ($cols as $col => $def) {
        $check = $conn->query("SHOW COLUMNS FROM `$table` LIKE '$col'");
        if ($check && $check->num_rows == 0) {
            $conn->query("ALTER TABLE `$table` ADD `$col` $def");
        }
    }
}

$table_contact = "CREATE TABLE IF NOT EXISTS contact_messages (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50),
    email VARCHAR(50),
    phone VARCHAR(20),
    website VARCHAR(100),
    message TEXT,
    date_sent TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)";
$conn->query($table_contact);

$table_admin = "CREATE TABLE IF NOT EXISTS admins (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE,
    password VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
$conn->query($table_admin);

$table_users = "CREATE TABLE IF NOT EXISTS users (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    email VARCHAR(100),
    phone VARCHAR(20) UNIQUE NOT NULL,
    otp VARCHAR(10),
    otp_expiry DATETIME,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
$conn->query($table_users);

$table_app_hotels = "CREATE TABLE IF NOT EXISTS app_hotels (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    location VARCHAR(100),
    price VARCHAR(50),
    accommodations VARCHAR(50),
    image VARCHAR(255),
    description TEXT,
    availability TINYINT(1) DEFAULT 1,
    available_dates TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
$conn->query($table_app_hotels);

// Seed admin data (username: admin, password: password123)
$admin_check = $conn->query("SELECT * FROM admins WHERE username='admin'");
if ($admin_check && $admin_check->num_rows == 0) {
    $hashed_password = password_hash('password123', PASSWORD_DEFAULT);
    $conn->query("INSERT INTO admins (username, password) VALUES ('admin', '$hashed_password')");
}

// Seed hotel data
$hotel_check = $conn->query("SELECT * FROM app_hotels");
if ($hotel_check && $hotel_check->num_rows == 0) {
    $seed_hotels = [
        "('Grand Luxury Resort', 'Mumbai', '5000', 'Classic Tent', 'assets/images/tour-3-550x590.jpg', 'Experience the best luxury tent stay.', 1)",
        "('Forest Retreat', 'Bangalore', '3000', 'Forest Camping', 'assets/images/tour-4-550x590.jpg', 'A wonderful retreat in the heart of the forest.', 1)",
        "('Mountain View', 'Manali', '4500', 'Tree House Tent', 'assets/images/tour-12-550x590.jpg', 'Beautiful mountain views from your tree house.', 1)",
    ];
    foreach ($seed_hotels as $hotel_values) {
        $conn->query("INSERT INTO app_hotels (name, location, price, accommodations, image, description, availability) VALUES $hotel_values");
    }
}

// Create offers table
$table_app_offers = "CREATE TABLE IF NOT EXISTS app_offers (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    image_url VARCHAR(255) NOT NULL,
    badge_text VARCHAR(100) NOT NULL,
    badge_color VARCHAR(50) DEFAULT 'primary',
    title VARCHAR(255) NOT NULL,
    description VARCHAR(255),
    footer_text VARCHAR(255),
    status INT DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
$conn->query($table_app_offers);

// Create hotel images gallery table
$table_hotel_images = "CREATE TABLE IF NOT EXISTS hotel_images (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    hotel_id INT(6) UNSIGNED,
    image_path VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (hotel_id) REFERENCES app_hotels(id) ON DELETE CASCADE
)";
$conn->query($table_hotel_images);

// Create hotel rooms table
$table_hotel_rooms = "CREATE TABLE IF NOT EXISTS hotel_rooms (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    hotel_id INT(6) UNSIGNED,
    room_name VARCHAR(100) NOT NULL,
    room_price INT(10) NOT NULL,
    capacity VARCHAR(50),
    bed_type VARCHAR(50),
    features TEXT, -- JSON encoded list of features
    room_image VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (hotel_id) REFERENCES app_hotels(id) ON DELETE CASCADE
)";
$conn->query($table_hotel_rooms);

// Seed offer data
$offer_check = $conn->query("SELECT * FROM app_offers");
if ($offer_check->num_rows == 0) {
    $seed_offers = [
        "('assets/images/tour-12-550x590.jpg', 'ICICICC | KOTAKCC', 'primary', 'Up to 25% off', 'on Domestic Flights!', 'Valid on Credit Card & EMI', 1)",
        "('assets/images/tour-3-550x590.jpg', 'SUMMERSALE', 'danger', 'Flat 10% off', 'on all domestic flights', 'No minimum booking amount', 1)",
        "('assets/images/slider-1.jpg', 'INTDOTD', 'success', 'Flat 15% off', 'on International Flights', 'Valid on limited routes', 1)",
        "('assets/images/slider-2.jpg', 'CTNOV', 'warning', 'Up to 20% off', 'on flights', 'Special Holiday Discount', 1)"
    ];
    foreach ($seed_offers as $offer_values) {
        $conn->query("INSERT INTO app_offers (image_url, badge_text, badge_color, title, description, footer_text, status) VALUES $offer_values");
    }
}

echo "<div style='font-family: sans-serif; padding: 20px; color: green;'>
        <h2>✅ Migration Successful!</h2>
        <p>Database tables have been updated with <b>booking_status</b> and <b>user_id</b> columns.</p>
        <a href='../admin/admin.php' style='background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Back to Admin</a>
      </div>";
?>
