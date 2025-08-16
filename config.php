<?php
// Bot tokeningiz
$API_KEY = "8406794244:AAEJ6RoKkEH40F0XTKMdNZA9SGz3pmZehXU";

// MySQL sozlamalari
$DB_HOST = "localhost";
$DB_USER = "root";
$DB_PASS = "password";
$DB_NAME = "telegram_bot";

// Admin ID (faqat admin panel uchun)
$ADMIN_ID = 6124077568;

// Baza ulanishi
$conn = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
if ($conn->connect_error) {
    die("MySQL ulanish xatosi: " . $conn->connect_error);
}
?>
