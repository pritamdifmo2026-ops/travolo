<?php
include "includes/db.php";
$res = $conn->query("SHOW COLUMNS FROM hotels");
while($row = $res->fetch_assoc()) {
    echo $row["Field"] . "\n";
}
?>
