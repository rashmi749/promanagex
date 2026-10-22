<?php
$page_title = "Notifications";
include '../includes/header.php';
checkRole('admin');

if (isset($_POST['send'])) {
    $uid = (int)$_POST['user_id'];
    $title = $conn->real_escape_string($_POST['title']);
    $msg = $conn->real_escape_string($_POST['message']);
    if ($uid == 0) {
        $users = $conn->query("SELECT id FROM users");
        while($u = $users->fetch_assoc()) {
            $conn->query("INSERT INTO notifications (user_id,title,message) VALUES ({$u['id']},'$title','$msg')");
        }
    } else {
        $conn->query("INSERT INTO notifications (user_id,title,message) VALUES ($uid,'$title','$msg')");
    }
    $success = "Notification sent!";
}

$users = $conn->query("SELECT id,name FROM users");
?>
<?php if(isset($success)) echo "<div class='alert alert-success'>$success</div>"; ?>
<div class="card">
    <div class="card-header"><h2>Send Notification</h2></div>
    <form method="POST">
        <div class="form-group"><label>Recipient</label>
            <select name="user_id"><option value="0">All Users</option>
            <?php while($u=$users->fetch_assoc()) echo "<option value='{$u['id']}'>{$u['name']}</option>"; ?></select>
        </div>
        <div class="form-group"><label>Title</label><input type="text" name="title" required></div>
        <div class="form-group"><label>Message</label><textarea name="message" rows="4" required></textarea></div>
        <button type="submit" name="send" class="btn-primary">Send</button>
    </form>
</div>
<div class="card">
    <div class="card-header"><h2>All Notifications</h2></div>
    <table><tr><th>User</th><th>Title</th><th>Message</th><th>Date</th></tr>
    <?php $r = $conn->query("SELECT n.*, u.name FROM notifications n LEFT JOIN users u ON n.user_id=u.id ORDER BY n.id DESC LIMIT 50");
    while($n=$r->fetch_assoc()): ?>
    <tr><td><?=$n['name']?></td><td><?=$n['title']?></td><td><?=$n['message']?></td><td><?=$n['created_at']?></td></tr>
    <?php endwhile; ?></table>
</div>
<?php include '../includes/footer.php'; ?>
