<?php
include '../includes/db.php';
$res = $conn->query("SELECT DISTINCT(booking_type) FROM hotels");
while($r = $res->fetch_assoc()){
    echo "TYPE: >" . $r['booking_type'] . "<\n";
}
?>
