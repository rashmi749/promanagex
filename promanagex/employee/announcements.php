<?php
$page_title = "Announcements & Calendar";
include '../includes/header.php';
checkRole('employee');
$uid = $_SESSION['user_id'];
?>
<div class="card">
    <div class="card-header"><h2>Announcements</h2></div>
    <table>
        <tr><th>Title</th><th>Message</th><th>Date</th></tr>
        <?php $r = $conn->query("SELECT * FROM announcements WHERE audience IN ('all','employee') ORDER BY id DESC LIMIT 20");
        while($a = $r->fetch_assoc()): ?>
        <tr><td><?=$a['title']?></td><td><?=$a['message']?></td><td><?=$a['created_at']?></td></tr>
        <?php endwhile; ?>
    </table>
</div>

<div class="card">
    <div class="card-header"><h2>Calendar Events for My Projects</h2></div>
    <table>
        <tr><th>Title</th><th>Project</th><th>Date</th><th>Time</th><th>Description</th></tr>
        <?php $r = $conn->query("SELECT DISTINCT ce.*, p.name pname FROM calendar_events ce LEFT JOIN projects p ON ce.project_id=p.id WHERE ce.project_id IS NULL OR ce.project_id IN (SELECT project_id FROM tasks WHERE assigned_to=$uid) ORDER BY ce.event_date ASC");
        while($e = $r->fetch_assoc()): ?>
        <tr><td><?=$e['title']?></td><td><?=$e['pname'] ?? 'General'?></td><td><?=$e['event_date']?></td><td><?=$e['event_time'] ?? '-'?></td><td><?=$e['description']?></td></tr>
        <?php endwhile; ?>
    </table>
</div>
<?php include '../includes/footer.php'; ?>
