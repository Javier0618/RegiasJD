<?php
require_once 'includes/header.php';
require_login(); // Ensure user is logged in

// Fetch user data from the database
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT full_name, email, phone FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

?>

<div class="page-container">
    <div class="page-header">
        <h1>Mi Perfil</h1>
        <p>Aquí puedes ver y actualizar tu información.</p>
    </div>

    <div class="profile-container">
        <div class="profile-info-card">
            <h3>Información de la Cuenta</h3>
            <p><strong>Nombre:</strong> <?php echo htmlspecialchars($user['full_name']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
            <p><strong>Celular:</strong> <?php echo htmlspecialchars($user['phone']); ?></p>
        </div>

        <div class="profile-update-card">
            <h3>Cambiar Contraseña</h3>
            <form action="process_update_profile.php" method="POST" class="styled-form">
                <div class="form-group">
                    <label for="current_password">Contraseña Actual</label>
                    <input type="password" id="current_password" name="current_password" required>
                </div>
                <div class="form-group">
                    <label for="new_password">Nueva Contraseña</label>
                    <input type="password" id="new_password" name="new_password" required>
                </div>
                <div class="form-group">
                    <label for="confirm_password">Confirmar Nueva Contraseña</label>
                    <input type="password" id="confirm_password" name="confirm_password" required>
                </div>
                <button type="submit" class="btn-primary">Actualizar Contraseña</button>
            </form>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>