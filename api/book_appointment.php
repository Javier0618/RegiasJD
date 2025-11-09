<?php
header('Content-Type: application/json');
require_once '../includes/db.php';
require_once '../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
    exit();
}

$name = sanitize_input($_POST['name'] ?? '');
$phone = sanitize_input($_POST['phone'] ?? '');
$datetime = sanitize_input($_POST['datetime'] ?? '');
$service_ids = $_POST['services'] ?? [];
$comments = sanitize_input($_POST['comments'] ?? '');

// Calculate total duration and price from the database
$total_duration = 0;
$total_price = 0;
$service_names = [];
if (!empty($service_ids)) {
    $ids_placeholder = implode(',', array_fill(0, count($service_ids), '?'));
    $stmt = $conn->prepare("SELECT name, duration, price FROM services WHERE id IN ($ids_placeholder)");
    $stmt->bind_param(str_repeat('i', count($service_ids)), ...$service_ids);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($service = $result->fetch_assoc()) {
        $total_duration += $service['duration'];
        $total_price += $service['price'];
        $service_names[] = $service['name'];
    }
}

$conn->begin_transaction();
try {
    $stmt = $conn->prepare("INSERT INTO appointments (client_name, client_phone, appointment_time, total_duration, total_price, comments) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssiis", $name, $phone, $datetime, $total_duration, $total_price, $comments);
    $stmt->execute();
    $appointment_id = $stmt->insert_id;

    $stmt = $conn->prepare("INSERT INTO appointment_services (appointment_id, service_id) VALUES (?, ?)");
    foreach ($service_ids as $service_id) {
        $stmt->bind_param("ii", $appointment_id, $service_id);
        $stmt->execute();
    }

    $conn->commit();

    $message = "🎉 Nueva cita agendada:\n\n"
             . "👤 Nombre: " . $name . "\n"
             . "📱 Celular: " . $phone . "\n"
             . "💅 Servicio(s): " . implode(', ', $service_names) . "\n"
             . "🗓️ Fecha y hora: " . format_datetime($datetime) . "\n"
             . "💬 Comentario: " . ($comments ?: 'Ninguno');
    send_telegram_message($message);

    echo json_encode(['success' => true, 'message' => '¡Cita Agendada!']);

} catch (Exception $e) {
    $conn->rollback();
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Error al guardar la cita.']);
}
?>
