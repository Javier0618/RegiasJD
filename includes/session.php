<?php
// Start the session if it's not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Function to check if a user is logged in
function is_logged_in() {
    return isset($_SESSION['user_id']);
}

// Function to check if the logged-in user is an admin
function is_admin() {
    return is_logged_in() && isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
}

// Function to require login for a page
function require_login() {
    if (!is_logged_in()) {
        // Redirect to login page if not logged in
        header('Location: /login.php');
        exit();
    }
}

// Function to require admin privileges for a page
function require_admin() {
    if (!is_admin()) {
        // Redirect to home page or show an error if not an admin
        header('Location: /');
        exit();
    }
}
?>
