<?php
include 'includes/db.php';
$res = $conn->query("SELECT * FROM top_flight_routes");
while($row = $res->fetch_assoc()) {
    echo $row['city_name'] . " -> " . $row['image_path'] . "\n";
}
?>
