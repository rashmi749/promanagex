<?php
$page_title = "My Profile";
include '../includes/header.php';
checkRole(['manager','admin']);
$uid = $_SESSION['user_id'];

// FR-46: update profile information
if (isset($_POST['update_profile'])) {
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $conn->query("UPDATE users SET name='$name',email='$email',phone='$phone' WHERE id=$uid");
    $_SESSION['name'] = $_POST['name'];
    $msg = "Profile updated!";
}

// FR-45: change password
if (isset($_POST['change_pass'])) {
    $user = $conn->query("SELECT password FROM users WHERE id=$uid")->fetch_assoc();
    if (password_verify($_POST['current_password'], $user['password'])) {
        $new = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
        $conn->query("UPDATE users SET password='$new' WHERE id=$uid");
        $msg = "Password changed!";
    } else {
        $msg = "Current password is incorrect.";
    }
}

$me = $conn->query("SELECT * FROM users WHERE id=$uid")->fetch_assoc();
?>
<?php if(isset($msg)) echo "<div class='alert alert-success'>$msg</div>"; ?>
<div class="card">
    <div class="card-header"><h2>Profile Information</h2></div>
    <form method="POST">
        <div class="form-row">
            <div class="form-group"><label>Name</label><input type="text" name="name" value="<?=$me['name']?>" required></div>
            <div class="form-group"><label>Email</label><input type="email" name="email" value="<?=$me['email']?>" required></div>
        </div>
        <div class="form-group"><label>Phone</label><input type="text" name="phone" value="<?=$me['phone']??''?>"></div>
        <button type="submit" name="update_profile" class="btn-primary">Save Profile</button>
    </form>
</div>
<div class="card">
    <div class="card-header"><h2>Change Password</h2></div>
    <form method="POST">
        <div class="form-group"><label>Current Password</label><input type="password" name="current_password" required></div>
        <div class="form-group"><label>New Password</label><input type="password" name="new_password" required></div>
        <button type="submit" name="change_pass" class="btn-primary">Change Password</button>
    </form>
</div>
<?php include '../includes/footer.php'; ?>
