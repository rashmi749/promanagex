<?php require_once __DIR__ . '/../config/database.php'; require_once __DIR__ . '/auth.php'; checkLogin(); ?>
<!DOCTYPE html>
<html>
<head>
    <title><?= $page_title ?? 'ProManageX' ?></title>
    <link rel="stylesheet" href="<?=BASE_URL?>assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
<div class="app-container">
<?php include __DIR__ . '/sidebar.php'; ?>
<div class="main-content">
    <div class="topbar">
        <h1><?= $page_title ?? 'Dashboard' ?></h1>
        <div class="user-info">
            <i class="fas fa-user-circle"></i> <?= $_SESSION['name'] ?> (<?= ucfirst($_SESSION['role']) ?>)
        </div>
    </div>
