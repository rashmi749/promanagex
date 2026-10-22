<?php
$page_title = "Performance Management";
include '../includes/header.php';
checkRole('admin');

// FR-40: maintain performance records -> snapshot generator
if (isset($_POST['generate'])) {
    $emps = $conn->query("SELECT id FROM users WHERE role='employee'");
    while ($e = $emps->fetch_assoc()) {
        $uid = $e['id'];
        $stats = $conn->query("SELECT COUNT(*) total, SUM(CASE WHEN status='Completed' THEN 1 ELSE 0 END) done FROM tasks WHERE assigned_to=$uid")->fetch_assoc();
        $total = (int)$stats['total'];
        $done = (int)$stats['done'];
        $score = $total > 0 ? round(($done / $total) * 100, 2) : 0;
        $conn->query("INSERT INTO performance_records (user_id,total_tasks,completed_tasks,performance_score) VALUES ($uid,$total,$done,$score)");
    }
    $msg = "Performance snapshot generated for all employees!";
}
?>
<?php if(isset($msg)) echo "<div class='alert alert-success'>$msg</div>"; ?>

<!-- FR-38/FR-39: evaluate & display performance summaries -->
<div class="card">
    <div class="card-header">
        <h2>Current Employee Performance</h2>
        <form method="POST"><button type="submit" name="generate" class="btn btn-primary btn-sm">Generate Snapshot</button></form>
    </div>
    <table>
        <tr><th>Employee</th><th>Total Tasks</th><th>Completed</th><th>Performance %</th></tr>
        <?php $r = $conn->query("SELECT u.id,u.name, COUNT(t.id) total, SUM(CASE WHEN t.status='Completed' THEN 1 ELSE 0 END) completed FROM users u LEFT JOIN tasks t ON t.assigned_to=u.id WHERE u.role='employee' GROUP BY u.id");
        while($e=$r->fetch_assoc()):
            $perf = $e['total'] > 0 ? round(($e['completed']/$e['total'])*100) : 0; ?>
        <tr><td><?=$e['name']?></td><td><?=$e['total']?></td><td><?=$e['completed']?></td><td><?=$perf?>%</td></tr>
        <?php endwhile; ?>
    </table>
</div>

<div class="card">
    <div class="card-header"><h2>Performance Record History</h2></div>
    <table>
        <tr><th>Employee</th><th>Total Tasks</th><th>Completed</th><th>Score</th><th>Generated At</th></tr>
        <?php $r = $conn->query("SELECT pr.*, u.name FROM performance_records pr JOIN users u ON pr.user_id=u.id ORDER BY pr.id DESC LIMIT 50");
        while($p=$r->fetch_assoc()): ?>
        <tr><td><?=$p['name']?></td><td><?=$p['total_tasks']?></td><td><?=$p['completed_tasks']?></td><td><?=$p['performance_score']?>%</td><td><?=$p['generated_at']?></td></tr>
        <?php endwhile; ?>
    </table>
</div>
<?php include '../includes/footer.php'; ?>
