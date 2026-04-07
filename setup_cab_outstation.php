<?php
include 'db.php';

// Drop table if exists for fresh start
$conn->query("DROP TABLE IF EXISTS cab_outstation");

$sql = "CREATE TABLE cab_outstation (
    id INT AUTO_INCREMENT PRIMARY KEY,
    city VARCHAR(100) NOT NULL,
    thumbnail VARCHAR(255) NOT NULL,
    destinations TEXT NOT NULL,
    status TINYINT DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if ($conn->query($sql)) {
    echo "Table cab_outstation created successfully.<br>";
    
    // Insert some initial data from the screenshot
    $data = [
        ['Delhi', 'assets/images/outstation/delhi.jpg', 'Agra, Bareilly, Dehradun'],
        ['Mumbai', 'assets/images/outstation/mumbai.jpg', 'Shirdi, Pune, Lonavala'],
        ['Chennai', 'assets/images/outstation/chennai.jpg', 'Hosur, Tirupati, Bengaluru'],
        ['Bengaluru', 'assets/images/outstation/bengaluru.jpg', 'Madikeri, Tirupati, Ooty'],
        ['Agra', 'assets/images/outstation/agra.jpg', 'Delhi, Lucknow, Jaipur'],
        ['Rishikesh', 'assets/images/outstation/rishikesh.jpg', 'Delhi, Haridwar, Nainital']
    ];
    
    foreach ($data as $item) {
        $city = $conn->real_escape_string($item[0]);
        $thumb = $conn->real_escape_string($item[1]);
        $dests = $conn->real_escape_string($item[2]);
        $conn->query("INSERT INTO cab_outstation (city, thumbnail, destinations) VALUES ('$city', '$thumb', '$dests')");
    }
    echo "Initial data inserted.";
} else {
    echo "Error creating table: " . $conn->error;
}
?>
