<?php
require 'config/database.php';
session_start();

if (isset($_POST['login'])) {
    $email = $conn->real_escape_string($_POST['email']);
    $password = $_POST['password'];

    $result = $conn->query("SELECT * FROM users WHERE email='$email' AND status='active'");
    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role'];

            if ($user['role'] == 'admin') header("Location: admin/dashboard.php");
            elseif ($user['role'] == 'manager') header("Location: manager/dashboard.php");
            else header("Location: employee/dashboard.php");
            exit;
        }
    }
    $error = "Invalid email or password!";
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login - ProManageX</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
<div class="login-wrapper">
    <div class="login-box">
        <h2><i class="fas fa-project-diagram"></i> ProManageX</h2>
        <p style="text-align:center; color:#8fa5c5; margin-bottom:20px;">Smart Project Management</p>
        <?php if(isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
        <form method="POST">
            <input type="email" name="email" placeholder="Email Address" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" name="login" class="btn-primary" style="width:100%; margin-top:10px;">LOGIN</button>
        </form>
        <p style="text-align:center; margin-top:20px; color:#8fa5c5; font-size:13px;">
            Demo: admin@promanagex.com / admin123
        </p>
    </div>
</div>
</body>
</html>
