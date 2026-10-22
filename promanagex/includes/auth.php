<?php
session_start();

function checkLogin() {
    if (!isset($_SESSION['user_id'])) {
        header("Location: " . BASE_URL . "login.php");
        exit;
    }
}

function checkRole($allowed_roles) {
    if (!in_array($_SESSION['role'], (array)$allowed_roles)) {
        header("Location: " . BASE_URL . "login.php");
        exit;
    }
}
?>
