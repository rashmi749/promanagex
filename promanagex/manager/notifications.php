<?php
$page_title = "Notifications";
include '../includes/header.php';
checkRole(['manager','admin']);
$uid = $_SESSION['user_id'];

// FR-31/FR-33: notify users about assignments/updates (scoped: employees on manager's projects)
if (isset($_POST['send'])) {
    $target = (int)$_POST['user_id'];
    $title = $conn->real_escape_string($_POST['title']);
    $msg = $conn->real_escape_string($_POST['message']);
    $allowed = $conn->query("SELECT DISTINCT t.assigned_to FROM tasks t JOIN projects p ON t.project_id=p.id WHERE p.manager_id=$uid AND t.assigned_to=$target")->num_rows;
    if ($allowed) {
        $conn->query("INSERT INTO notifications (user_id,title,message) VALUES ($target,'$title','$msg')");
        $success = "Notification sent!";
    } else {
        $success = "Could not send: employee is not on one of your projects.";
    }
}

$employees = $conn->query("SELECT DISTINCT u.id,u.name FROM users u JOIN tasks t ON t.assigned_to=u.id JOIN projects p ON t.project_id=p.id WHERE p.manager_id=$uid");
?>
<?php if(isset($success)) echo "<div class='alert alert-success'>$success</div>"; ?>
<div class="card">
    <div class="card-header"><h2>Send Notification to My Team</h2></div>
    <form method="POST">
        <div class="form-group"><label>Recipient</label>
            <select name="user_id"><?php while($u=$employees->fetch_assoc()) echo "<option value='{$u['id']}'>{$u['name']}</option>"; ?></select>
        </div>
        <div class="form-group"><label>Title</label><input type="text" name="title" required></div>
        <div class="form-group"><label>Message</label><textarea name="message" rows="4" required></textarea></div>
        <button type="submit" name="send" class="btn-primary">Send</button>
    </form>
</div>
<!-- FR-34: view notifications -->
<div class="card">
    <div class="card-header"><h2>My Notifications</h2></div>
    <table><tr><th>Title</th><th>Message</th><th>Date</th></tr>
    <?php $r = $conn->query("SELECT * FROM notifications WHERE user_id=$uid ORDER BY id DESC LIMIT 30");
    while($n=$r->fetch_assoc()): ?>
    <tr><td><?=$n['title']?></td><td><?=$n['message']?></td><td><?=$n['created_at']?></td></tr>
    <?php endwhile; ?></table>
</div>
<?php include '../includes/footer.php'; ?>
