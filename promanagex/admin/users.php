<?php
$page_title = "User Management";
include '../includes/header.php';
checkRole('admin');

if (isset($_POST['add'])) {
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $pass = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $conn->real_escape_string($_POST['role']);
    $conn->query("INSERT INTO users (name,email,password,role) VALUES ('$name','$email','$pass','$role')");
    $msg = "User added successfully!";
}

if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $conn->query("DELETE FROM users WHERE id=$id AND role!='admin'");
    header("Location: users.php"); exit;
}

if (isset($_POST['update'])) {
    $id = (int)$_POST['id'];
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $role = $conn->real_escape_string($_POST['role']);
    $conn->query("UPDATE users SET name='$name',email='$email',role='$role' WHERE id=$id");
    $msg = "User updated!";
}
?>

<?php if(isset($msg)) echo "<div class='alert alert-success'>$msg</div>"; ?>

<div class="card">
    <div class="card-header"><h2>Add New User</h2></div>
    <form method="POST">
        <div class="form-row">
            <div class="form-group"><label>Name</label><input type="text" name="name" required></div>
            <div class="form-group"><label>Email</label><input type="email" name="email" required></div>
        </div>
        <div class="form-row">
            <div class="form-group"><label>Password</label><input type="password" name="password" required></div>
            <div class="form-group"><label>Role</label>
                <select name="role"><option value="admin">Admin</option><option value="manager">Manager</option><option value="employee">Employee</option></select>
            </div>
        </div>
        <button type="submit" name="add" class="btn-primary">Add User</button>
    </form>
</div>

<div class="card">
    <div class="card-header"><h2>All Users</h2></div>
    <table>
        <tr><th>ID</th><th>Name</th><th>Email</th><th>Role</th><th>Actions</th></tr>
        <?php $r = $conn->query("SELECT * FROM users ORDER BY id DESC");
        while($u = $r->fetch_assoc()): ?>
        <tr>
            <td><?=$u['id']?></td>
            <td><?=$u['name']?></td>
            <td><?=$u['email']?></td>
            <td><span class="badge badge-info"><?=$u['role']?></span></td>
            <td>
                <a href="users.php?delete=<?=$u['id']?>" onclick="return confirm('Delete?')" class="btn btn-delete btn-sm">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>

<?php include '../includes/footer.php'; ?>
