<?php
// Include the database connection and session management
require_once 'db.php';
require_once 'session.php';

/**
 * Sanitizes user input to prevent XSS attacks.
 * @param string $data The input data to sanitize.
 * @return string The sanitized data.
 */
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}

/**
 * Formats a given date and time into a more readable format.
 * @param string $datetime The timestamp to format.
 * @return string The formatted date and time.
 */
function format_datetime($datetime) {
    $timestamp = strtotime($datetime);
    // Format: Day, Month Date, Year at HH:MM AM/PM
    return date('D, M j, Y \a\t g:i A', $timestamp);
}

/**
 * Sends a notification message to the configured Telegram chat.
 * @param string $message The message to send.
 */
function send_telegram_message($message) {
    $token = TELEGRAM_BOT_TOKEN;
    $chat_id = TELEGRAM_CHAT_ID;
    $url = "https://api.telegram.org/bot" . $token . "/sendMessage?chat_id=" . $chat_id . "&text=" . urlencode($message);

    // Use file_get_contents to send the request
    $response = file_get_contents($url);
    // You could add error handling here if needed
}

// More helper functions can be added below as the application grows.
?>
