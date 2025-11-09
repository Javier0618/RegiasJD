<?php
require_once '../includes/header.php';
require_admin(); // Secure this page for admins only

// Fetch real stats from the database
$stats_query = "SELECT
                    COUNT(*) as total,
                    SUM(CASE WHEN status = 'Pendiente' THEN 1 ELSE 0 END) as pendientes,
                    SUM(CASE WHEN status = 'Confirmada' THEN 1 ELSE 0 END) as confirmadas,
                    SUM(CASE WHEN status = 'Completada' THEN 1 ELSE 0 END) as completadas
                FROM appointments";
$stats_result = $conn->query($stats_query);
$stats = $stats_result->fetch_assoc();

// Fetch all appointments from the database
$citas_query = "SELECT * FROM appointments ORDER BY appointment_time DESC";
$citas_result = $conn->query($citas_query);
$all_citas = $citas_result->fetch_all(MYSQLI_ASSOC);
?>

<div class="admin-container">
    <div class="page-header">
        <h1>Administración de Citas</h1>
        <p>Gestiona todas las citas de tus clientas.</p>
    </div>

    <!-- Stats Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <h4>Total de Citas</h4>
            <p><?php echo $stats['total'] ?? 0; ?></p>
        </div>
        <div class="stat-card">
            <h4>Pendientes</h4>
            <p><?php echo $stats['pendientes'] ?? 0; ?></p>
        </div>
        <div class="stat-card">
            <h4>Confirmadas</h4>
            <p><?php echo $stats['confirmadas'] ?? 0; ?></p>
        </div>
        <div class="stat-card">
            <h4>Completadas</h4>
            <p><?php echo $stats['completadas'] ?? 0; ?></p>
        </div>
    </div>

    <!-- Citas Table -->
    <div class="admin-table-container">
        <table class="citas-table">
            <thead>
                <tr>
                    <th>Cliente</th>
                    <th>Teléfono</th>
                    <th>Servicio</th>
                    <th>Fecha y Hora</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($all_citas as $cita): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($cita['client_name']); ?></td>
                        <td><?php echo htmlspecialchars($cita['client_phone']); ?></td>
                        <td><?php /* Service names would be fetched via a JOIN in a real query */ echo 'Servicio'; ?></td>
                        <td><?php echo htmlspecialchars(format_datetime($cita['appointment_time'])); ?></td>
                        <td>
                            <span class="status-badge status-<?php echo strtolower($cita['status']); ?>">
                                <?php echo htmlspecialchars($cita['status']); ?>
                            </span>
                        </td>
                        <td class="action-buttons">
                            <button title="Confirmar" class="btn-icon btn-confirm"><i data-lucide="check"></i></button>
                            <button title="Completar" class="btn-icon btn-complete"><i data-lucide="check-check"></i></button>
                            <button title="Cancelar" class="btn-icon btn-cancel"><i data-lucide="x"></i></button>
                            <button title="Eliminar" class="btn-icon btn-delete"><i data-lucide="trash-2"></i></button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
