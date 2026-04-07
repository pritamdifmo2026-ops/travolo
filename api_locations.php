<?php
header('Content-Type: application/json');
include 'db.php';

$query = isset($_GET['q']) ? $conn->real_escape_string($_GET['q']) : '';
$type = isset($_GET['type']) ? $_GET['type'] : 'Transfer';
$results = [];

if (empty($query)) {
    echo json_encode([]);
    exit;
}

if ($type === 'Transfer') {
    // Search City and Airport for domestic transfers
    $sql = "SELECT DISTINCT city as name, airport as detail, 'Domestic' as cat FROM cab_transfers WHERE status=1 AND (city LIKE '%$query%' OR airport LIKE '%$query%')
            UNION
            SELECT DISTINCT city as name, description as detail, 'Overseas' as cat FROM cab_overseas WHERE status=1 AND city LIKE '%$query%'";
} elseif ($type === 'Hourly') {
    $sql = "SELECT DISTINCT city as name, location_tag as detail, 'Hourly' as cat FROM cab_hourly WHERE status=1 AND city LIKE '%$query%'";
} elseif ($type === 'Outstation') {
    $sql = "SELECT DISTINCT city as name, destinations as detail, 'Outstation' as cat FROM cab_outstation WHERE status=1 AND (city LIKE '%$query%' OR destinations LIKE '%$query%')";
} else {
    $sql = "SELECT DISTINCT city as name, 'All' as cat FROM (
        SELECT city FROM cab_transfers UNION 
        SELECT city FROM cab_hourly UNION 
        SELECT city FROM cab_outstation UNION 
        SELECT city FROM cab_overseas
    ) as t WHERE city LIKE '%$query%'";
}

$res = $conn->query($sql);
if ($res) {
    while ($row = $res->fetch_assoc()) {
        $results[] = $row;
    }
}

echo json_encode($results);
?>
