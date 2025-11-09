<?php
header('Content-Type: application/json');
require_once '../includes/functions.php';
require_login(); // User must be logged in to cancel

// Ensure this is a POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
    exit();
}

$appointment_id = $_POST['appointment_id'] ?? null;
$reason = sanitize_input($_POST['reason'] ?? 'No especificado');
$client_name = $_SESSION['user_name']; // Get client name from session

// --- DATABASE LOGIC ---
// In a real app, you would:
// 1. Verify the appointment belongs to the logged-in user.
// 2. Update the appointment status to 'Cancelada'.
// 3. Log the cancellation reason.
// For now, we simulate success.
$simulated_success = true;
$mock_appointment_datetime = '2025-11-22 14:00:00'; // Example datetime
// --- END DATABASE LOGIC ---

if ($simulated_success) {
    // Format the message for Telegram
    $message = "🚫 Cita cancelada:\n\n"
             . "👤 Cliente: " . $client_name . "\n"
             . "🗓️ Fecha y hora: " . format_datetime($mock_appointment_datetime) . "\n"
             . "Reason️ Motivo: " . $reason;

    send_telegram_message($message);

    echo json_encode(['success' => true, 'message' => 'Cita cancelada correctamente.']);
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Error al cancelar la cita.']);
}
?>
