<?php
require_once '../includes/header.php';
require_admin();

// Handle form submissions for add/edit/delete
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_service'])) {
        // Add logic
    } elseif (isset($_POST['edit_service'])) {
        // Edit logic
    } elseif (isset($_POST['delete_service'])) {
        // Delete logic
    }
}


// Fetch real services from the database
$services_query = "SELECT * FROM services";
$services_result = $conn->query($services_query);
$services = $services_result->fetch_all(MYSQLI_ASSOC);
?>

<div class="admin-container">
    <div class="page-header">
        <h1>Administración de Servicios</h1>
        <button class="btn-primary">Agregar Servicio</button>
    </div>

    <div class="admin-table-container">
        <table class="citas-table">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Duración (min)</th>
                    <th>Precio</th>
                    <th>Categoría</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($services as $service): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($service['name']); ?></td>
                        <td><?php echo htmlspecialchars($service['duration']); ?></td>
                        <td>$<?php echo htmlspecialchars(number_format($service['price'], 0, ',', '.')); ?></td>
                        <td><?php echo htmlspecialchars($service['category']); ?></td>
                        <td>
                             <span class="status-badge status-<?php echo strtolower($service['status']); ?>">
                                <?php echo htmlspecialchars($service['status']); ?>
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

<?php require_once '../includes/footer.php'; ?>
