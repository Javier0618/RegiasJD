<?php
require_once '../includes/header.php';
require_admin();

// Handle form submissions for add/edit/delete
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_schedule'])) {
        // Add logic
    } elseif (isset($_POST['edit_schedule'])) {
        // Edit logic
    } elseif (isset($_POST['delete_schedule'])) {
        // Delete logic
    }
}


// Fetch real schedules from the database
$horarios_query = "SELECT * FROM schedules ORDER BY date, start_time";
$horarios_result = $conn->query($horarios_query);
$horarios = $horarios_result->fetch_all(MYSQLI_ASSOC);
?>

<div class="admin-container">
    <div class="page-header">
        <h1>Configurar Horarios</h1>
        <p>Define tus días y horas de trabajo disponibles para las citas.</p>
    </div>

    <div class="horarios-container">
        <div class="horarios-form-wrapper">
            <h3>Agregar Nuevo Horario</h3>
            <form class="styled-form" method="POST">
                <div class="form-group">
                    <label for="date">Fecha</label>
                    <input type="date" id="date" name="date" required>
                </div>
                <div class="form-group">
                    <label for="start_time">Hora de Inicio</label>
                    <input type="time" id="start_time" name="start_time" required>
                </div>
                <div class="form-group">
                    <label for="end_time">Hora de Fin</label>
                    <input type="time" id="end_time" name="end_time" required>
                </div>
                <button type="submit" name="add_schedule" class="btn-primary">Guardar Horario</button>
            </form>
        </div>
        <div class="horarios-list-wrapper">
            <h3>Horarios Existentes</h3>
            <div class="admin-table-container">
                <table class="citas-table">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Desde</th>
                            <th>Hasta</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($horarios as $horario): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($horario['date']); ?></td>
                            <td><?php echo htmlspecialchars(date('h:i A', strtotime($horario['start_time']))); ?></td>
                            <td><?php echo htmlspecialchars(date('h:i A', strtotime($horario['end_time']))); ?></td>
                            <td>
                                <span class="status-badge <?php echo $horario['is_available'] ? 'status-activo' : 'status-inactivo'; ?>">
                                    <?php echo $horario['is_available'] ? 'Disponible' : 'No Disponible'; ?>
                                </span>
                            </td>
                            <td class="action-buttons">
                                <button title="Editar" class="btn-icon btn-edit"><i data-lucide="edit"></i></button>
                                <button title="Eliminar" class="btn-icon btn-delete"><i data-lucide="trash-2"></i></button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
