<?php
$page_title = "Manager Dashboard";
include '../includes/header.php';
checkRole(['manager','admin']);
$uid = $_SESSION['user_id'];
$myp = $conn->query("SELECT COUNT(*) c FROM projects WHERE manager_id=$uid")->fetch_assoc()['c'];
$myt = $conn->query("SELECT COUNT(*) c FROM tasks t JOIN projects p ON t.project_id=p.id WHERE p.manager_id=$uid")->fetch_assoc()['c'];
?>
<div class="stats-grid">
    <div class="stat-card"><h3>My Projects</h3><div class="value"><?=$myp?></div></div>
    <div class="stat-card"><h3>Total Tasks</h3><div class="value"><?=$myt?></div></div>
</div>
<div class="card"><div class="card-header"><h2>My Projects</h2></div>
<table><tr><th>Name</th><th>Client</th><th>Status</th></tr>
<?php $r = $conn->query("SELECT * FROM projects WHERE manager_id=$uid");
while($p=$r->fetch_assoc()): ?>
<tr><td><?=$p['name']?></td><td><?=$p['client_name']?></td><td><?=$p['status']?></td></tr>
<?php endwhile; ?></table></div>
<?php include '../includes/footer.php'; ?>
