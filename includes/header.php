<?php require_once 'functions.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RegiasJD - Salón de Belleza</title>
    <link rel="stylesheet" href="/css/styles.css">
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body>

<nav class="navbar">
    <div class="navbar-container">
        <a href="/" class="navbar-logo">
            <div class="logo-icon-wrapper">
                <i data-lucide="sparkles"></i>
            </div>
            <span>RegiasJD</span>
        </a>

        <div class="menu-icon" onclick="toggleMenu()">
            <i data-lucide="menu"></i>
        </div>

        <div class="navbar-links">
            <a href="/index.php" class="nav-link">Agendar Cita</a>

            <?php if (is_logged_in()): ?>
                <a href="/mis_citas.php" class="nav-link">Mis Citas</a>
            <?php endif; ?>

            <?php if (is_admin()): ?>
                <a href="/admin/index.php" class="nav-link">Admin Citas</a>
                <a href="/admin/servicios.php" class="nav-link">Servicios</a>
                <a href="/admin/horarios.php" class="nav-link">Horarios</a>
            <?php endif; ?>

            <div class="nav-right">
                <?php if (is_logged_in()): ?>
                    <div class="dropdown">
                        <button class="dropdown-toggle">
                            <i data-lucide="user"></i>
                        </button>
                        <div class="dropdown-menu">
                            <a href="/perfil.php">Mi Perfil</a>
                            <a href="/logout.php">Cerrar Sesión</a>
                        </div>
                    </div>
                <?php else: ?>
                    <a href="/login.php" class="nav-link-login" title="Iniciar Sesión">
                        <i data-lucide="log-in"></i>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>

<main class="main-container">
