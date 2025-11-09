<?php
require_once 'includes/db.php';
require_once 'includes/functions.php';

// Start the session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Ensure this is a POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: login.php');
    exit();
}

// Sanitize user input
$email = sanitize_input($_POST['email']);
$password = $_POST['password'];

// Prepare the SQL statement
$stmt = $conn->prepare("SELECT id, full_name, password, role FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();

    // Verify the password using password_verify()
    if (password_verify($password, $user['password'])) {
        // Password is correct, set session variables
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['full_name'];
        $_SESSION['user_role'] = $user['role'];

        // Redirect based on role
        if ($user['role'] === 'admin') {
            header('Location: admin/index.php');
        } else {
            header('Location: index.php');
        }
        exit();
    }
}

// If login fails, redirect back to login page
header('Location: login.php?error=1');
exit();
?>
