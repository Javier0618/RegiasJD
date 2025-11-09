<?php
require_once '../includes/header.php';
require_admin();

// Handle form submissions for add/edit/delete
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_service'])) {
        $name = $_POST['name'];
        $duration = $_POST['duration'];
        $price = $_POST['price'];
        $category = $_POST['category'];

        if (!empty($name) && !empty($duration) && !empty($price) && !empty($category)) {
            $stmt = $conn->prepare("INSERT INTO services (name, duration, price, category) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("sids", $name, $duration, $price, $category);
            
            if ($stmt->execute()) {
                header("Location: servicios.php");
                exit();
            } else {
                $error_message = "Error al guardar el servicio.";
            }
            $stmt->close();
        } else {
            $error_message = "Todos los campos son obligatorios.";
        }
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
        <button id="add-service-btn" class="btn-primary">Agregar Servicio</button>
    </div>

    <!-- Add Service Modal -->
    <div id="add-service-modal" class="modal" style="display: none; position: fixed; z-index: 1; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.4);">
        <div class="modal-content" style="background-color: #fefefe; margin: 15% auto; padding: 20px; border: 1px solid #888; width: 80%; max-width: 500px; border-radius: 8px;">
            <span class="close-button" style="color: #aaa; float: right; font-size: 28px; font-weight: bold; cursor: pointer;">&times;</span>
            <h2>Agregar Nuevo Servicio</h2>
            <form class="styled-form" action="servicios.php" method="POST" style="margin-top: 20px;">
                <div class="form-group">
                    <label for="name">Nombre del Servicio</label>
                    <input type="text" id="name" name="name" required>
                </div>
                <div class="form-group">
                    <label for="duration">Duración (minutos)</label>
                    <input type="number" id="duration" name="duration" required>
                </div>
                <div class="form-group">
                    <label for="price">Precio</label>
                    <input type="number" step="0.01" id="price" name="price" required>
                </div>
                <div class="form-group">
                    <label for="category">Categoría</label>
                    <input type="text" id="category" name="category" required>
                </div>
                <button type="submit" name="add_service" class="btn-primary">Guardar Servicio</button>
            </form>
        </div>
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('add-service-modal');
    const btn = document.getElementById('add-service-btn');
    const span = document.querySelector('#add-service-modal .close-button');

    if (btn) {
        btn.onclick = function() {
            modal.style.display = 'block';
        }
    }

    if (span) {
        span.onclick = function() {
            modal.style.display = 'none';
        }
    }

    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = 'none';
        }
    }
});
</script>
<?php require_once '../includes/footer.php'; ?>
