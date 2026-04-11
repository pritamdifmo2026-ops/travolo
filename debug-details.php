<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'includes/db.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
// Fetching details if available
$res = $conn->query("SELECT * FROM app_hotels WHERE id = $id");
$hotel = $res ? $res->fetch_assoc() : null;

if (!$hotel) {
    echo "Hotel ID $id not found in app_hotels table. <a href='hotel.php'>Go Back</a>";
    // Check if availability=1 is hiding it
    $res2 = $conn->query("SELECT * FROM app_hotels WHERE id = $id");
    if($res2 && $res2->num_rows > 0) {
        echo "<br>Hotel exists but availability is 0.";
    }
    exit;
}

echo "<h1>Debug: Hotel Found - " . $hotel['name'] . "</h1>";
?>
<!DOCTYPE html>
<html lang="zxx">
<head>
    <title>Debug Page</title>
    <link rel="stylesheet" href="assets/vendor/bootstrap/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <div class="alert alert-success">If you see this, the page is loading correctly.</div>
        <pre><?php print_r($hotel); ?></pre>
        <a href="hotel.php" class="btn btn-primary">Back to Hotels</a>
    </div>
</body>
</html>
