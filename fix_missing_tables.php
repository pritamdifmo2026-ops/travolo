<?php
include 'includes/db.php';

echo "<h2>Fixing Missing Tables...</h2>";

$queries = [
    // 1. Hotel Offers
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

    // 2. Hotel Rooms
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

    // 3. Top Flight Routes
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

    // 4. Cab Packages
    "CREATE TABLE IF NOT EXISTS cab_packages (
        id INT AUTO_INCREMENT PRIMARY KEY,
        package_name VARCHAR(100) NOT NULL,
        hours INT NOT NULL,
        km INT NOT NULL,
        status TINYINT DEFAULT 1,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )"
];

foreach ($queries as $q) {
    if ($conn->query($q)) {
        echo "Query executed successfully.<br>";
    } else {
        echo "Error: " . $conn->error . "<br>";
    }
}

// --- SEEDING INITIAL DATA ---

// Seed Cab Packages if empty
$check = $conn->query("SELECT id FROM cab_packages LIMIT 1");
if ($check->num_rows == 0) {
    $conn->query("INSERT INTO cab_packages (package_name, hours, km) VALUES 
    ('4hrs / 40km', 4, 40),
    ('8hrs / 80km', 8, 80),
    ('12hrs / 120km', 12, 120)");
    echo "Seeded Cab Packages.<br>";
}

// Seed Flight Routes if empty
$check = $conn->query("SELECT id FROM top_flight_routes LIMIT 1");
if ($check->num_rows == 0) {
    $conn->query("INSERT INTO top_flight_routes (city_name, via_cities, image_path, from_query, to_query) VALUES 
    ('Mumbai', 'Delhi, Bengaluru, Chennai', 'assets/images/destinations/mumbai.png', 'Delhi (DEL)', 'Mumbai (BOM)'),
    ('Delhi', 'Mumbai, Pune, Kolkata', 'assets/images/destinations/delhi.png', 'Mumbai (BOM)', 'Delhi (DEL)'),
    ('Bengaluru', 'Hyderabad, Chennai, Goa', 'assets/images/destinations/bangalore.png', 'Delhi (DEL)', 'Bangalore (BLR)')");
    echo "Seeded Flight Routes.<br>";
}

// Seed Hotel Offers if empty
$check = $conn->query("SELECT id FROM hotel_offers LIMIT 1");
if ($check->num_rows == 0) {
    $conn->query("INSERT INTO hotel_offers (badge, header_small, header_main, promo_code, main_title, validity_text, image_path, theme_color) VALUES 
    ('SUMMER SALE', 'Flat 25% Off', 'Luxury Stays', 'SUMMER25', 'Experience ultimate luxury this summer with exclusive deals.', 'Valid till 30th June', 'assets/images/tour-3-550x590.jpg', '#00a79d'),
    ('NEW YEAR', 'Special Discount', 'Resort Collection', 'NY2026', 'Celebrate the new year in style at our partner resorts.', 'Valid till 15th Jan', 'assets/images/tour-4-550x590.jpg', '#F7921E')");
    echo "Seeded Hotel Offers.<br>";
}

echo "<h3>All fixes applied!</h3>";
echo "<a href='index.php'>Go to Homepage</a>";
?>
