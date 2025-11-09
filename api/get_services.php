<?php
header('Content-Type: application/json');
require_once '../includes/db.php';

$result = $conn->query("SELECT id, name, duration, price FROM services WHERE status = 'active'");
$services = $result->fetch_all(MYSQLI_ASSOC);

echo json_encode($services);
