<?php
include 'includes/db.php';
$res = $conn->query("SELECT * FROM top_flight_routes");
$out = "";
while($row = $res->fetch_assoc()) {
    $out .= $row['city_name'] . " -> " . $row['image_path'] . "\n";
}
file_put_contents('routes_report.txt', $out);
?>
