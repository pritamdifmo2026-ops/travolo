<?php
include '../includes/db.php';
echo "<pre>";
$res = $conn->query("SELECT * FROM hotels ORDER BY id DESC LIMIT 5");
if ($res) {
    while($row = $res->fetch_assoc()){
        print_r($row);
    }
} else {
    echo "Error: " . $conn->error;
}
echo "</pre>";
?>
