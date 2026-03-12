<?php
include 'db.php';

echo "<h2>Database Reset & Seeding Started...</h2>";

// 1. Drop existing tables to start clean
$tables = ['flights', 'hotels', 'cabs', 'contact_messages', 'admins', 'app_hotels'];
foreach ($tables as $table) {
    if ($conn->query("DROP TABLE IF EXISTS `$table`")) {
        echo "Dropped table: $table <br>";
    }
}

// 2. Re-run db.php logic to recreate tables (it's already included, but let's ensure it's clean)
// Since db.php uses CREATE TABLE IF NOT EXISTS, we just need to ensure the logic runs.
// Re-including/Calling the logic again (it's already done by the include above if we drop first).

// Re-creating tables explicitly to be sure
$table_queries = [
    "CREATE TABLE flights (
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
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
        booking_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )",
    "CREATE TABLE hotels (
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        check_in VARCHAR(30),
        hotel_search VARCHAR(100),
        accommodations VARCHAR(50),
        phone VARCHAR(20),
        hotel_id INT(6),
        status VARCHAR(30) DEFAULT 'Checked',
        booking_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )",
    "CREATE TABLE cabs (
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
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
        booking_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )",
    "CREATE TABLE contact_messages (
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(50),
        email VARCHAR(50),
        phone VARCHAR(20),
        website VARCHAR(100),
        message TEXT,
        date_sent TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )",
    "CREATE TABLE admins (
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) UNIQUE,
        password VARCHAR(255),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )",
    "CREATE TABLE app_hotels (
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
    )"
];

foreach ($table_queries as $q) {
    if (!$conn->query($q)) {
        echo "Error creating table: " . $conn->error . "<br>";
    }
}
echo "Tables recreated successfully. <br>";

// 3. Seed Data
echo "Seeding data... <br>";

// Seed Admin
$hashed_password = password_hash('password123', PASSWORD_DEFAULT);
$conn->query("INSERT INTO admins (username, password) VALUES ('admin', '$hashed_password')");

// Seed App Hotels (Catalog)
$app_hotels_sql = "INSERT INTO app_hotels (name, location, price, accommodations, image, description, availability) VALUES 
('The Taj Mahal Palace', 'Mumbai', '15000', 'Luxury Suite', 'assets/images/tour-3-550x590.jpg', 'Iconic luxury hotel in Mumbai with sea view.', 1),
('Oberoi Udaivilas', 'Udaipur', '25000', 'Palace Room', 'assets/images/tour-4-550x590.jpg', 'One of the best luxury resorts in the world.', 1),
('Hyatt Regency', 'Delhi', '8000', 'Standard Room', 'assets/images/tour-12-550x590.jpg', 'Modern luxury in the heart of the capital.', 1),
('Wildflower Hall', 'Shimla', '18000', 'Mountain View', 'assets/images/tour-8-550x590.jpg', 'Experience serenity in the Himalayas.', 1)";
$conn->query($app_hotels_sql);

// Seed Flight Bookings (Simulating some recent activity)
$flights_sql = "INSERT INTO flights (trip_type, from_city, to_city, depart_date, return_date, adults, children, infants, travel_class, phone) VALUES 
('One Way', 'Delhi (DEL)', 'Mumbai (BOM)', '2026-03-15', '', 2, 1, 0, 'Economy', '9876543210'),
('Round Trip', 'Bangalore (BLR)', 'Dubai (DXB)', '2026-04-10', '2026-04-20', 1, 0, 0, 'Business', '9988776655'),
('One Way', 'Chennai (MAA)', 'Singapore (SIN)', '2026-03-25', '', 3, 0, 1, 'First Class', '9123456789')";
$conn->query($flights_sql);

// Seed Cab Bookings
$cabs_sql = "INSERT INTO cabs (trip_type, pickup_type, from_city, to_city, pickup_date, pickup_time, return_date, return_time, hours, phone) VALUES 
('Transfer', 'Airport Pickup', 'Mumbai Airport', 'Juhu Beach', '2026-03-12', '10:30 AM', '', '', '', '9812345678'),
('Outstation', 'One Way', 'Delhi', 'Jaipur', '2026-03-14', '06:00 AM', '', '', '', '9555443322'),
('Hourly', 'Local', 'Bangalore', '', '2026-03-10', '09:00 AM', '', '', '8 Hours', '9000112233')";
$conn->query($cabs_sql);

// Seed Hotel Bookings
$hotel_bookings_sql = "INSERT INTO hotels (check_in, hotel_search, accommodations, phone, hotel_id, status) VALUES 
('2026-03-20', 'The Taj Mahal Palace', 'Luxury Suite', '9777888999', 1, 'Confirmed'),
('2026-04-05', 'Hyatt Regency', 'Standard Room', '9666555444', 3, 'Checked')";
$conn->query($hotel_bookings_sql);

// Seed Contact Messages
$contact_sql = "INSERT INTO contact_messages (name, email, phone, website, message) VALUES 
('John Doe', 'john@example.com', '9888777666', 'www.johndoe.com', 'I would like to inquire about group discounts for holiday packages.'),
('Jane Smith', 'jane@test.com', '9555666777', '', 'Need help with my existing flight booking.')";
$conn->query($contact_sql);

echo "<h3>Success! All tables cleared and seeded with fresh data.</h3>";
echo "<a href='index.html'>Go to Homepage</a> | <a href='admin.php'>Go to Admin Panel</a>";
?>
