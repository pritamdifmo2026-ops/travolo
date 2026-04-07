<?php
include 'db.php';

// Drop table if exists for fresh start
$conn->query("DROP TABLE IF EXISTS cab_offers");

$sql = "CREATE TABLE cab_offers (
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
)";

if ($conn->query($sql)) {
    echo "Table cab_offers created.<br>";
    
    // Insert initial data from screenshot
    $data = [
        ['NEW LAUNCH', 'Luxury', 'Premium Fleet', 'TVIP50', 'Upgrade Your Travel with Travolo Elite Fleet', 'Valid till: 30th Jun, 2026', 'assets/images/image-01.jpg', '#00a79d'],
        ['POPULAR', 'Special Offer on', 'Airport Drop', 'TFLY10', 'Never Miss a Flight with Our Punctual Airport Express', 'Enjoy Special Rates', 'assets/images/image-02.jpg', '#007bb5'],
        ['VERIFIED', 'on', 'Intercity Rides', 'TCAB250', 'Plan Your Weekend Getaway with Safe Intercity Transfers', 'Offer Expires: 30th Jun, 2026', 'assets/images/image-03.jpg', '#133a25'],
        ['ELITE CAB', 'on', 'Hourly Package', 'TRAVOLO15', 'Unlock Premium Comfort for Your City Explorations', 'Offer Expires: 30th Jun, 2026', 'assets/images/image-04.jpg', '#00a79d']
    ];
    
    foreach ($data as $d) {
        $badge = $conn->real_escape_string($d[0]);
        $h_small = $conn->real_escape_string($d[1]);
        $h_main = $conn->real_escape_string($d[2]);
        $promo = $conn->real_escape_string($d[3]);
        $title = $conn->real_escape_string($d[4]);
        $valid = $conn->real_escape_string($d[5]);
        $img = $conn->real_escape_string($d[6]);
        $color = $conn->real_escape_string($d[7]);
        
        $conn->query("INSERT INTO cab_offers (badge, header_small, header_main, promo_code, main_title, validity_text, image_path, theme_color) 
                      VALUES ('$badge', '$h_small', '$h_main', '$promo', '$title', '$valid', '$img', '$color')");
    }
    echo "Initial data inserted.";
} else {
    echo "Error: " . $conn->error;
}
?>
