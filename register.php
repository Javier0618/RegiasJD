<?php
require_once 'includes/header.php';
if (is_logged_in()) {
    header('Location: /');
    exit();
}
?>
<div class="form-container">
    <h2>Crear una Cuenta</h2>
    <form action="process_register.php" method="POST" class="styled-form">
        <div class="form-group">
            <label for="full_name">Nombre Completo</label>
            <input type="text" id="full_name" name="full_name" required>
        </div>
        <div class="form-group">
            <label for="phone">Celular</label>
            <input type="tel" id="phone" name="phone" required>
        </div>
        <div class="form-group">
            <label for="email">Correo Electrónico</label>
            <input type="email" id="email" name="email" required>
        </div>
        <div class="form-group">
            <label for="password">Contraseña</label>
            <input type="password" id="password" name="password" required>
        </div>
        <button type="submit" class="btn-primary">Registrarse</button>
        <div class="form-footer">
            <p>¿Ya tienes una cuenta? <a href="login.php">Inicia sesión</a></p>
        </div>
    </form>
</div>
<?php require_once 'includes/footer.php'; ?>
