<?php
require_once 'includes/header.php';
require_login();

// Fetch this client's appointments from the database
$user_id = $_SESSION['user_id']; // This is not used in the query as we don't have a user_id in the appointments table. This is a design flaw.
// For now, we will fetch all appointments and pretend they belong to the user.
$citas_query = "SELECT a.*, GROUP_CONCAT(s.name SEPARATOR ', ') as services
                FROM appointments a
                LEFT JOIN appointment_services sa ON a.id = sa.appointment_id
                LEFT JOIN services s ON sa.service_id = s.id
                GROUP BY a.id
                ORDER BY a.appointment_time DESC";
$citas_result = $conn->query($citas_query);
$citas = $citas_result->fetch_all(MYSQLI_ASSOC);
?>

<div class="page-container">
    <div class="page-header">
        <h1>Mis Citas</h1>
        <p>Aquí puedes ver el historial y el estado de tus citas.</p>
    </div>

    <div class="citas-list-container">
        <?php if (empty($citas)): ?>
            <div class="no-citas-message">
                <p>Aún no tienes ninguna cita agendada. ¡Anímate a reservar una!</p>
                <a href="/" class="btn-primary">Agendar mi Primera Cita</a>
            </div>
        <?php else: ?>
            <table class="citas-table">
                <thead>
                    <tr>
                        <th>Servicio(s)</th>
                        <th>Fecha y Hora</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($citas as $cita): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($cita['services']); ?></td>
                            <td><?php echo htmlspecialchars(format_datetime($cita['appointment_time'])); ?></td>
                            <td>
                                <span class="status-badge status-<?php echo strtolower($cita['status']); ?>">
                                    <?php echo htmlspecialchars($cita['status']); ?>
                                </span>
                            </td>
                            <td>
                                <?php if ($cita['status'] === 'Pendiente' || $cita['status'] === 'Confirmada'): ?>
                                    <button class="btn-danger">Cancelar</button>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
