<?php
include '../includes/db.php';
echo "<pre>";
$res = $conn->query("DESC hotels");
if ($res) {
    while($row = $res->fetch_assoc()){
        print_r($row);
    }
} else {
    echo "Error: " . $conn->error;
}
echo "</pre>";
?>
