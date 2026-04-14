<?php
header('Content-Type: application/json');
include 'includes/db.php';

$sql = "SELECT * FROM app_hotels WHERE availability = 1 ORDER BY id DESC";
$result = $conn->query($sql);

$hotels = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $hotels[] = $row;
    }
}

echo json_encode(["status" => "success", "data" => $hotels]);
?>