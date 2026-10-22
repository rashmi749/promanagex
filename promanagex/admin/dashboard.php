<?php
$page_title = "Admin Dashboard";
include '../includes/header.php';
checkRole('admin');

$users = $conn->query("SELECT COUNT(*) c FROM users")->fetch_assoc()['c'];
$projects = $conn->query("SELECT COUNT(*) c FROM projects")->fetch_assoc()['c'];
$tasks = $conn->query("SELECT COUNT(*) c FROM tasks")->fetch_assoc()['c'];
$completed = $conn->query("SELECT COUNT(*) c FROM tasks WHERE status='Completed'")->fetch_assoc()['c'];
?>

<div class="stats-grid">
    <div class="stat-card"><i class="fas fa-users icon"></i><h3>Total Users</h3><div class="value"><?=$users?></div></div>
    <div class="stat-card"><i class="fas fa-briefcase icon"></i><h3>Projects</h3><div class="value"><?=$projects?></div></div>
    <div class="stat-card"><i class="fas fa-tasks icon"></i><h3>Total Tasks</h3><div class="value"><?=$tasks?></div></div>
    <div class="stat-card"><i class="fas fa-check icon"></i><h3>Completed</h3><div class="value"><?=$completed?></div></div>
</div>

<div class="card">
    <div class="card-header"><h2>Recent Projects</h2></div>
    <table>
        <tr><th>Code</th><th>Name</th><th>Client</th><th>Status</th><th>Budget</th></tr>
        <?php $r = $conn->query("SELECT * FROM projects ORDER BY id DESC LIMIT 5");
        while($p = $r->fetch_assoc()): ?>
        <tr>
            <td><?=$p['project_code']?></td>
            <td><?=$p['name']?></td>
            <td><?=$p['client_name']?></td>
            <td><span class="badge badge-info"><?=$p['status']?></span></td>
            <td>$<?=number_format($p['budget'],2)?></td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>

<?php include '../includes/footer.php'; ?>
