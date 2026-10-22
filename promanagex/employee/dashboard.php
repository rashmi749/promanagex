<?php
$page_title = "My Dashboard";
include '../includes/header.php';
checkRole('employee');
$uid = $_SESSION['user_id'];
$total = $conn->query("SELECT COUNT(*) c FROM tasks WHERE assigned_to=$uid")->fetch_assoc()['c'];
$done = $conn->query("SELECT COUNT(*) c FROM tasks WHERE assigned_to=$uid AND status='Completed'")->fetch_assoc()['c'];
$pending = $conn->query("SELECT COUNT(*) c FROM tasks WHERE assigned_to=$uid AND status='Pending'")->fetch_assoc()['c'];
?>
<div class="stats-grid">
    <div class="stat-card"><h3>My Tasks</h3><div class="value"><?=$total?></div></div>
    <div class="stat-card"><h3>Completed</h3><div class="value"><?=$done?></div></div>
    <div class="stat-card"><h3>Pending</h3><div class="value"><?=$pending?></div></div>
</div>
<div class="card"><div class="card-header"><h2>My Notifications</h2></div>
<table><tr><th>Title</th><th>Message</th><th>Date</th></tr>
<?php $r = $conn->query("SELECT * FROM notifications WHERE user_id=$uid ORDER BY id DESC LIMIT 10");
while($n=$r->fetch_assoc()): ?>
<tr><td><?=$n['title']?></td><td><?=$n['message']?></td><td><?=$n['created_at']?></td></tr>
<?php endwhile; ?></table></div>
<?php include '../includes/footer.php'; ?>
