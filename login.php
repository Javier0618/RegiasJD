<?php
require_once 'includes/header.php';

// If user is already logged in, redirect them to the home page
if (is_logged_in()) {
    header('Location: /');
    exit();
}
?>

<div class="form-container">
    <h2>Iniciar Sesión</h2>
    <form action="process_login.php" method="POST" class="styled-form">
        <div class="form-group">
            <label for="email">Correo Electrónico</label>
            <input type="email" id="email" name="email" required>
        </div>
        <div class="form-group">
            <label for="password">Contraseña</label>
            <input type="password" id="password" name="password" required>
        </div>
        <button type="submit" class="btn-primary">Ingresar</button>
        <div class="form-footer">
            <p>¿No tienes una cuenta? <a href="register.php">Regístrate aquí</a></p>
        </div>
    </form>
</div>

<?php require_once 'includes/footer.php'; ?>
