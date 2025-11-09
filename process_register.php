<?php
require_once 'includes/db.php';
require_once 'includes/functions.php';

// Ensure this is a POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: register.php');
    exit();
}

// Sanitize user inputs
$full_name = sanitize_input($_POST['full_name']);
$phone = sanitize_input($_POST['phone']);
$email = sanitize_input($_POST['email']);
$password = $_POST['password'];

// Basic validation
if (empty($full_name) || empty($phone) || empty($email) || empty($password)) {
    header('Location: register.php?error=missing_fields');
    exit();
}

// Check if email already exists
$stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
if ($stmt->get_result()->num_rows > 0) {
    header('Location: register.php?error=email_exists');
    exit();
}
$stmt->close();

// Hash the password securely using password_hash()
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Insert the new user into the database
$stmt = $conn->prepare("INSERT INTO users (full_name, phone, email, password, role) VALUES (?, ?, ?, ?, 'client')");
$stmt->bind_param("ssss", $full_name, $phone, $email, $hashed_password);

if ($stmt->execute()) {
    header('Location: login.php?registration=success');
    exit();
} else {
    header('Location: register.php?error=db_error');
    exit();
}

$stmt->close();
$conn->close();
?>
