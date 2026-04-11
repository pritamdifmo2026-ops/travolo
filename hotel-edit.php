<?php
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
header('Location: admin/hotel-edit.php?id=' . $id);
exit;
?>
