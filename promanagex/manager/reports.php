<?php
$page_title = "Reports";
include '../includes/header.php';
checkRole(['manager','admin']);
$uid = $_SESSION['user_id'];
$type = $_GET['type'] ?? 'projects';
?>
<div class="card">
    <div class="card-header">
        <h2>Reports</h2>
        <div>
            <a href="?type=projects" class="btn btn-view btn-sm">Projects</a>
            <a href="?type=tasks" class="btn btn-view btn-sm">Tasks</a>
            <a href="?type=performance" class="btn btn-view btn-sm">Performance</a>
            <button onclick="window.print()" class="btn btn-edit btn-sm">Print/PDF</button>
        </div>
    </div>

    <?php if($type=='projects'): ?>
    <h3 style="color:#40e0ff; margin-bottom:15px;">Project Progress Report (My Projects)</h3>
    <table><tr><th>Project</th><th>Client</th><th>Status</th><th>Budget</th><th>Deadline</th></tr>
    <?php $r = $conn->query("SELECT * FROM projects WHERE manager_id=$uid");
    while($p=$r->fetch_assoc()): ?>
    <tr><td><?=$p['name']?></td><td><?=$p['client_name']?></td><td><?=$p['status']?></td><td>$<?=number_format($p['budget'],2)?></td><td><?=$p['end_date']?></td></tr>
    <?php endwhile; ?></table>

    <?php elseif($type=='tasks'): ?>
    <h3 style="color:#40e0ff; margin-bottom:15px;">Task Completion Report</h3>
    <table><tr><th>Task</th><th>Assigned</th><th>Status</th><th>Priority</th><th>Deadline</th></tr>
    <?php $r = $conn->query("SELECT t.*, u.name uname FROM tasks t LEFT JOIN users u ON t.assigned_to=u.id JOIN projects p ON t.project_id=p.id WHERE p.manager_id=$uid");
    while($t=$r->fetch_assoc()): ?>
    <tr><td><?=$t['title']?></td><td><?=$t['uname']?></td><td><?=$t['status']?></td><td><?=$t['priority']?></td><td><?=$t['deadline']?></td></tr>
    <?php endwhile; ?></table>

    <?php else: ?>
    <h3 style="color:#40e0ff; margin-bottom:15px;">Employee Performance Report (My Projects)</h3>
    <table><tr><th>Employee</th><th>Total Tasks</th><th>Completed</th><th>Performance %</th></tr>
    <?php $r = $conn->query("SELECT u.id,u.name, COUNT(t.id) total, SUM(CASE WHEN t.status='Completed' THEN 1 ELSE 0 END) completed FROM users u JOIN tasks t ON t.assigned_to=u.id JOIN projects p ON t.project_id=p.id WHERE p.manager_id=$uid AND u.role='employee' GROUP BY u.id");
    while($e=$r->fetch_assoc()):
        $perf = $e['total'] > 0 ? round(($e['completed']/$e['total'])*100) : 0; ?>
    <tr><td><?=$e['name']?></td><td><?=$e['total']?></td><td><?=$e['completed']?></td><td><?=$perf?>%</td></tr>
    <?php endwhile; ?></table>
    <?php endif; ?>
</div>
<?php include '../includes/footer.php'; ?>
