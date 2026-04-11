<?php
include 'includes/db.php';
$sql = "CREATE TABLE IF NOT EXISTS flight_searches (
    id INT AUTO_INCREMENT PRIMARY KEY,
    from_city VARCHAR(100),
    to_city VARCHAR(100),
    depart_date DATE,
    trip_type VARCHAR(50),
    adults INT,
    children INT,
    infants INT,
    travel_class VARCHAR(50),
    search_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
if($conn->query($sql)) echo "Table Created";
else echo "Error: " . $conn->error;
?>
