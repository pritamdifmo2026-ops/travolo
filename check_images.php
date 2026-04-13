<?php
include 'includes/db.php';
$res = $conn->query("SELECT id, image_path FROM hotel_offers");
echo "<h2>Hotel Offers Image Paths:</h2>";
while($row = $res->fetch_assoc()) {
    echo "ID: " . $row['id'] . " | Path: [" . $row['image_path'] . "] | File Exists: " . (file_exists($row['image_path']) ? 'YES' : 'NO') . "<br>";
}
?>
