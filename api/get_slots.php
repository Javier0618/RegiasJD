<?php
header('Content-Type: application/json');
require_once '../includes/db.php';

$date = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');

// Get schedule for the selected date
$stmt = $conn->prepare("SELECT start_time, end_time FROM schedules WHERE date = ? AND is_available = 1");
$stmt->bind_param("s", $date);
$stmt->execute();
$schedule_result = $stmt->get_result();

if ($schedule_result->num_rows === 0) {
    echo json_encode([]); // No schedule for this day
    exit();
}
$schedule = $schedule_result->fetch_assoc();

// Get existing appointments for the selected date
$stmt = $conn->prepare("SELECT appointment_time, total_duration FROM appointments WHERE DATE(appointment_time) = ?");
$stmt->bind_param("s", $date);
$stmt->execute();
$appointments_result = $stmt->get_result();
$booked_slots = [];
while ($row = $appointments_result->fetch_assoc()) {
    $booked_slots[] = strtotime($row['appointment_time']);
}

$slots = [];
$slot_duration = 30; // 30-minute slots
$current_time = strtotime($date . ' ' . $schedule['start_time']);
$end_time = strtotime($date . ' ' . $schedule['end_time']);

while ($current_time < $end_time) {
    $is_booked = false;
    foreach ($booked_slots as $booked_time) {
        if ($current_time >= $booked_time && $current_time < ($booked_time + ($slot_duration * 60))) {
            $is_booked = true;
            break;
        }
    }

    $slots[] = [
        'time' => date('h:i A', $current_time),
        'available' => !$is_booked,
    ];

    $current_time = strtotime("+$slot_duration minutes", $current_time);
}

echo json_encode($slots);
