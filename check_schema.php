<?php
include 'includes/db.php';
$res = $conn->query("SHOW CREATE TABLE app_hotels");
if ($res) {
    echo "Schema for app_hotels:\n";
    print_r($res->fetch_assoc());
}
?>
