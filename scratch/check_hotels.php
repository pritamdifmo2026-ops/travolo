<?php
include 'includes/db.php';
$res = $conn->query("SELECT * FROM hotels ORDER BY id DESC LIMIT 5");
if ($res) {
    while ($row = $res->fetch_assoc()) {
        print_r($row);
        echo "\n---\n";
    }
} else {
    echo "Query failed: " . $conn->error;
}
?>
