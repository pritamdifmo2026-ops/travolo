<?php
include 'includes/db.php';

// Cab Transfers Table (Domestic)
$conn->query("CREATE TABLE IF NOT EXISTS cab_transfers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    city VARCHAR(100) NOT NULL,
    airport VARCHAR(255) NOT NULL,
    image_path VARCHAR(255) NOT NULL,
    badge_text VARCHAR(50) DEFAULT NULL,
    status TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

// Cab Hourly Rentals Table
$conn->query("CREATE TABLE IF NOT EXISTS cab_hourly (
    id INT AUTO_INCREMENT PRIMARY KEY,
    city VARCHAR(100) NOT NULL,
    location_tag VARCHAR(100) DEFAULT NULL,
    image_path VARCHAR(255) NOT NULL,
    price_per_hr INT NOT NULL,
    status TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

// Cab Overseas Transfers Table
$conn->query("CREATE TABLE IF NOT EXISTS cab_overseas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    city VARCHAR(100) NOT NULL,
    description TEXT NOT NULL,
    image_path VARCHAR(255) NOT NULL,
    price_starts VARCHAR(50) NOT NULL,
    status TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

echo "Cab dynamic tables created successfully.";

// Seed initial data if tables are empty
$check = $conn->query("SELECT id FROM cab_transfers LIMIT 1");
if ($check->num_rows == 0) {
    $conn->query("INSERT INTO cab_transfers (city, airport, image_path, badge_text) VALUES 
    ('Delhi', 'Indira Gandhi International Airport', 'assets/images/delhi_transfer.png', 'Best Value'),
    ('Mumbai', 'Chhatrapati Shivaji International', 'assets/images/mumbai_transfer.png', NULL),
    ('Bangalore', 'Kempegowda International Airport', 'assets/images/bangalore_transfer.png', NULL),
    ('Hyderabad', 'Rajiv Gandhi International Airport', 'assets/images/hyderabad_transfer.png', NULL),
    ('Goa', 'Dabolim & Mopa Airport Shuttle', 'assets/images/goa_transfer.png', NULL),
    ('Chennai', 'Chennai International Airport', 'assets/images/chennai_transfer.png', NULL)");
}

$check = $conn->query("SELECT id FROM cab_hourly LIMIT 1");
if ($check->num_rows == 0) {
    $conn->query("INSERT INTO cab_hourly (city, location_tag, image_path, price_per_hr) VALUES 
    ('Delhi', 'Capital Region', 'assets/images/hourly_delhi.png', 940),
    ('Mumbai', 'Marine Drive', 'assets/images/hourly_mumbai.png', 1100),
    ('Bangalore', 'IT Hub', 'assets/images/bangalore_transfer.png', 1200),
    ('Hyderabad', 'Cyber City', 'assets/images/hyderabad_transfer.png', 1400)");
}

$check = $conn->query("SELECT id FROM cab_overseas LIMIT 1");
if ($check->num_rows == 0) {
    $conn->query("INSERT INTO cab_overseas (city, description, image_path, price_starts) VALUES 
    ('London', 'Heathrow & Gatwick premium airport shuttles.', 'assets/images/overseas_london.png', '£55'),
    ('Dubai', 'DXB & DWC luxury terminal transfers.', 'assets/images/overseas_dubai.png', 'AED 120')");
}

echo " Seeding completed.";
?>
