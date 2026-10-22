<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$db   = 'promanagex';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");

// Base URL - CHANGE this to your folder name
define('BASE_URL', 'http://localhost/promanagex/');
define('UPLOAD_DIR', __DIR__ . '/../uploads/');
?>
