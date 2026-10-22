<?php
$page_title = "Calendar Events";
include '../includes/header.php';
checkRole('admin');

// FR-E04: maintain project calendar events and schedules
if (isset($_POST['add'])) {
    $pid = (int)$_POST['project_id'];
    $title = $conn->real_escape_string($_POST['title']);
    $desc = $conn->real_escape_string($_POST['description']);
    $date = $_POST['event_date'];
    $time = $_POST['event_time'] ?: null;
    $uid = $_SESSION['user_id'];
    $timeVal = $time ? "'$time'" : "NULL";
    $conn->query("INSERT INTO calendar_events (project_id,title,description,event_date,event_time,created_by) VALUES (" . ($pid ?: "NULL") . ",'$title','$desc','$date',$timeVal,$uid)");
    $msg = "Event added!";
}

if (isset($_GET['delete'])) {
    $conn->query("DELETE FROM calendar_events WHERE id=".(int)$_GET['delete']);
    header("Location: calendar.php"); exit;
}

$projects = $conn->query("SELECT id,name FROM projects");
?>
<?php if(isset($msg)) echo "<div class='alert alert-success'>$msg</div>"; ?>

<div class="card">
    <div class="card-header"><h2>Add Calendar Event</h2></div>
    <form method="POST">
        <div class="form-row">
            <div class="form-group"><label>Project (optional)</label>
                <select name="project_id"><option value="">-- General --</option><?php while($p=$projects->fetch_assoc()) echo "<option value='{$p['id']}'>{$p['name']}</option>"; ?></select>
            </div>
            <div class="form-group"><label>Title</label><input type="text" name="title" required></div>
        </div>
        <div class="form-group"><label>Description</label><textarea name="description" rows="2"></textarea></div>
        <div class="form-row">
            <div class="form-group"><label>Date</label><input type="date" name="event_date" required></div>
            <div class="form-group"><label>Time (optional)</label><input type="time" name="event_time"></div>
        </div>
        <button type="submit" name="add" class="btn-primary">Add Event</button>
    </form>
</div>

<div class="card">
    <div class="card-header"><h2>Upcoming & Past Events</h2></div>
    <table>
        <tr><th>Title</th><th>Project</th><th>Date</th><th>Time</th><th>Description</th><th>Actions</th></tr>
        <?php $r = $conn->query("SELECT ce.*, p.name pname FROM calendar_events ce LEFT JOIN projects p ON ce.project_id=p.id ORDER BY ce.event_date ASC");
        while($e = $r->fetch_assoc()): ?>
        <tr>
            <td><?=$e['title']?></td>
            <td><?=$e['pname'] ?? 'General'?></td>
            <td><?=$e['event_date']?></td>
            <td><?=$e['event_time'] ?? '-'?></td>
            <td><?=$e['description']?></td>
            <td><a href="calendar.php?delete=<?=$e['id']?>" onclick="return confirm('Delete?')" class="btn btn-delete btn-sm">Delete</a></td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>
<?php include '../includes/footer.php'; ?>
