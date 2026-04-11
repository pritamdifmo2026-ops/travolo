<?php
include 'includes/db.php';

// Cab Inventory Table for detailed results
$conn->query("CREATE TABLE IF NOT EXISTS cab_inventory (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category VARCHAR(50) NOT NULL, -- Sedan, SUV, Luxury, Hatchback
    car_name VARCHAR(100) NOT NULL,
    image_path VARCHAR(255) NOT NULL,
    capacity INT DEFAULT 4,
    luggage INT DEFAULT 2,
    base_price INT NOT NULL,
    price_per_km DECIMAL(10,2) DEFAULT 0,
    features TEXT, -- AC, Music System, GPS, etc (comma separated)
    rating DECIMAL(2,1) DEFAULT 4.5,
    status TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

// Seed some data
$check = $conn->query("SELECT id FROM cab_inventory LIMIT 1");
if ($check->num_rows == 0) {
    $conn->query("INSERT INTO cab_inventory (category, car_name, image_path, capacity, luggage, base_price, price_per_km, features) VALUES 
    ('Hatchback', 'Maruti Suzuki Swift', 'assets/images/cars/swift.png', 4, 1, 940, 11.00, 'AC, Music System, Power Windows'),
    ('Sedan', 'Toyota Etios', 'assets/images/cars/etios.png', 4, 2, 1200, 13.50, 'AC, Large Boot, Music System, GPS'),
    ('Sedan', 'Hyundai Verna', 'assets/images/cars/verna.png', 4, 2, 1400, 15.00, 'AC, Premium Audio, GPS, ABS'),
    ('SUV', 'Maruti Suzuki Ertiga', 'assets/images/cars/ertiga.png', 6, 3, 1800, 18.00, 'AC, 7 Seater, Music System, GPS'),
    ('SUV', 'Toyota Innova Crysta', 'assets/images/cars/innova.png', 7, 4, 2400, 22.00, 'AC, Luxury Seating, GPS, ABS, 7 Seater'),
    ('Luxury', 'Mercedes-Benz E-Class', 'assets/images/cars/mercedes.png', 4, 2, 5500, 45.00, 'AC, Leather Interior, GPS, Premium Audio, Sunroof'),
    ('Tempo Traveller', 'Force Traveller', 'assets/images/cars/tempo.png', 12, 10, 4500, 28.00, 'AC, 12 Seater, Large Boot, Music System')");
}

echo "Cab Inventory table created and seeded successfully.";
?>
