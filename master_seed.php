<?php
include 'includes/db.php';

echo "<h2>Seeding Sample Data...</h2>";

// 1. Seed App Hotels (Inventory)
$check = $conn->query("SELECT id FROM app_hotels LIMIT 1");
if ($check->num_rows == 0) {
    $conn->query("INSERT INTO app_hotels (name, location, price, accommodations, image, description, availability) VALUES 
    ('The Taj Mahal Palace', 'Mumbai', '15000', 'Luxury Suite', 'assets/images/tour-3-550x590.jpg', 'Iconic luxury hotel in Mumbai with sea view.', 1),
    ('Oberoi Udaivilas', 'Udaipur', '25000', 'Palace Room', 'assets/images/tour-4-550x590.jpg', 'One of the best luxury resorts in the world.', 1),
    ('Hyatt Regency', 'Delhi', '8000', 'Standard Room', 'assets/images/tour-12-550x590.jpg', 'Modern luxury in the heart of the capital.', 1),
    ('Wildflower Hall', 'Shimla', '18000', 'Mountain View', 'assets/images/tour-8-550x590.jpg', 'Experience serenity in the Himalayas.', 1)");
    echo "Seeded App Hotels.<br>";
}

// 2. Seed Flight Bookings
$check = $conn->query("SELECT id FROM flights LIMIT 1");
if ($check->num_rows == 0) {
    $conn->query("INSERT INTO flights (trip_type, from_city, to_city, depart_date, return_date, adults, children, infants, travel_class, phone, user_name, email) VALUES 
    ('One Way', 'Delhi (DEL)', 'Mumbai (BOM)', '2026-03-15', '', 2, 1, 0, 'Economy', '9876543210', 'John Doe', 'john@example.com'),
    ('Round Trip', 'Bangalore (BLR)', 'Dubai (DXB)', '2026-04-10', '2026-04-20', 1, 0, 0, 'Business', '9988776655', 'Jane Smith', 'jane@smith.com')");
    echo "Seeded Flight Bookings.<br>";
}

// 3. Seed Cab Bookings
$check = $conn->query("SELECT id FROM cabs LIMIT 1");
if ($check->num_rows == 0) {
    $conn->query("INSERT INTO cabs (trip_type, pickup_type, from_city, to_city, pickup_date, pickup_time, return_date, return_time, hours, phone, user_name, email) VALUES 
    ('Transfer', 'Airport Pickup', 'Mumbai Airport', 'Juhu Beach', '2026-03-12', '10:30 AM', '', '', '', '9812345678', 'Alice Brown', 'alice@gmail.com'),
    ('Outstation', 'One Way', 'Delhi', 'Jaipur', '2026-03-14', '06:00 AM', '', '', '', '9555443322', 'Bob White', 'bob@yahoo.com')");
    echo "Seeded Cab Bookings.<br>";
}

// 4. Seed Hotel Bookings
$check = $conn->query("SELECT id FROM hotels LIMIT 1");
if ($check->num_rows == 0) {
    $conn->query("INSERT INTO hotels (check_in, hotel_search, accommodations, phone, hotel_id, status, user_name, email) VALUES 
    ('2026-03-20', 'The Taj Mahal Palace', 'Luxury Suite', '9777888999', 1, 'Confirmed', 'Charlie Green', 'charlie@outlook.com')");
    echo "Seeded Hotel Bookings.<br>";
}

// 5. Seed Routes
$check = $conn->query("SELECT id FROM top_flight_routes LIMIT 1");
if ($check->num_rows == 0) {
    $conn->query("INSERT INTO top_flight_routes (city_name, via_cities, image_path, from_query, to_query) VALUES 
    ('Mumbai', 'Delhi, Bengaluru, Chennai', 'assets/images/destinations/mumbai.png', 'Delhi (DEL)', 'Mumbai (BOM)'),
    ('Delhi', 'Mumbai, Pune, Kolkata', 'assets/images/destinations/delhi.png', 'Mumbai (BOM)', 'Delhi (DEL)')");
    echo "Seeded Routes.<br>";
}

echo "<h3>Sample data seeded successfully!</h3>";
?>
