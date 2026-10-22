<?php
$page_title = "Settings";
include '../includes/header.php';
checkRole('admin');

if (isset($_POST['update'])) {
    foreach ($_POST['settings'] as $k => $v) {
        $k = $conn->real_escape_string($k);
        $v = $conn->real_escape_string($v);
        $conn->query("INSERT INTO settings (setting_key,setting_value) VALUES ('$k','$v') ON DUPLICATE KEY UPDATE setting_value='$v'");
    }
    $msg = "Settings updated!";
}

if (isset($_POST['change_pass'])) {
    $new = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
    $conn->query("UPDATE users SET password='$new' WHERE id=".$_SESSION['user_id']);
    $msg = "Password changed!";
}

$settings = [];
$r = $conn->query("SELECT * FROM settings");
while($s = $r->fetch_assoc()) $settings[$s['setting_key']] = $s['setting_value'];
?>
<?php if(isset($msg)) echo "<div class='alert alert-success'>$msg</div>"; ?>
<div class="card">
    <div class="card-header"><h2>System Settings</h2></div>
    <form method="POST">
        <div class="form-group"><label>Site Name</label><input type="text" name="settings[site_name]" value="<?=$settings['site_name']??''?>"></div>
        <div class="form-group"><label>Admin Email</label><input type="email" name="settings[site_email]" value="<?=$settings['site_email']??''?>"></div>
        <button type="submit" name="update" class="btn-primary">Save</button>
    </form>
</div>
<div class="card">
    <div class="card-header"><h2>Change Password</h2></div>
    <form method="POST">
        <div class="form-group"><label>New Password</label><input type="password" name="new_password" required></div>
        <button type="submit" name="change_pass" class="btn-primary">Change Password</button>
    </form>
</div>
<?php include '../includes/footer.php'; ?>
