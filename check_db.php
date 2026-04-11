<?php
include 'includes/db.php';
$res = $conn->query("SELECT * FROM top_flight_routes");
if (!$res) die("Table error: " . $conn->error);
while ($r = $res->fetch_assoc()) {
    echo "ID: {$r['id']} | CITY: {$r['city_name']} | IMG: " . substr($r['image_path'], 0, 50) . "... \n";
}
?>
