<?php
$page_title = "Employees";
include '../includes/header.php';
checkRole('admin');

// FR-09: Admin adds employee details
if (isset($_POST['add'])) {
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $dept = $conn->real_escape_string($_POST['department']);
    $pass = password_hash('employee123', PASSWORD_DEFAULT);
    $conn->query("INSERT INTO users (name,email,phone,department,password,role) VALUES ('$name','$email','$phone','$dept','$pass','employee')");
    $msg = "Employee added! Default password: employee123";
}

// FR-10: Admin edits employee information
if (isset($_POST['update'])) {
    $id = (int)$_POST['id'];
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $dept = $conn->real_escape_string($_POST['department']);
    $conn->query("UPDATE users SET name='$name',email='$email',phone='$phone',department='$dept' WHERE id=$id AND role='employee'");
    $msg = "Employee updated!";
}

// FR-11: Admin removes employee records
if (isset($_GET['delete'])) {
    $conn->query("DELETE FROM users WHERE id=".(int)$_GET['delete']." AND role='employee'");
    header("Location: employees.php"); exit;
}

$edit = null;
if (isset($_GET['edit'])) {
    $edit = $conn->query("SELECT * FROM users WHERE id=".(int)$_GET['edit']." AND role='employee'")->fetch_assoc();
}
?>
<?php if(isset($msg)) echo "<div class='alert alert-success'>$msg</div>"; ?>

<div class="card">
    <div class="card-header"><h2><?=$edit ? 'Edit Employee' : 'Add New Employee'?></h2></div>
    <form method="POST">
        <?php if($edit): ?><input type="hidden" name="id" value="<?=$edit['id']?>"><?php endif; ?>
        <div class="form-row">
            <div class="form-group"><label>Name</label><input type="text" name="name" value="<?=$edit['name']??''?>" required></div>
            <div class="form-group"><label>Email</label><input type="email" name="email" value="<?=$edit['email']??''?>" required></div>
        </div>
        <div class="form-row">
            <div class="form-group"><label>Phone</label><input type="text" name="phone" value="<?=$edit['phone']??''?>"></div>
            <div class="form-group"><label>Department</label><input type="text" name="department" value="<?=$edit['department']??''?>"></div>
        </div>
        <?php if($edit): ?>
            <button type="submit" name="update" class="btn-primary">Update Employee</button>
            <a href="employees.php" class="btn btn-view btn-sm">Cancel</a>
        <?php else: ?>
            <button type="submit" name="add" class="btn-primary">Add Employee</button>
        <?php endif; ?>
    </form>
</div>

<!-- FR-12: Maintain employee profiles -->
<div class="card">
    <div class="card-header"><h2>All Employees</h2></div>
    <table>
        <tr><th>ID</th><th>Name</th><th>Email</th><th>Phone</th><th>Department</th><th>Actions</th></tr>
        <?php $r = $conn->query("SELECT * FROM users WHERE role='employee' ORDER BY id DESC");
        while($u = $r->fetch_assoc()): ?>
        <tr>
            <td><?=$u['id']?></td>
            <td><?=$u['name']?></td>
            <td><?=$u['email']?></td>
            <td><?=$u['phone']??'-'?></td>
            <td><?=$u['department']??'-'?></td>
            <td>
                <a href="employees.php?edit=<?=$u['id']?>" class="btn btn-edit btn-sm">Edit</a>
                <a href="employees.php?delete=<?=$u['id']?>" onclick="return confirm('Delete this employee?')" class="btn btn-delete btn-sm">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>
<?php include '../includes/footer.php'; ?>
