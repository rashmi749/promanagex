<?php
$page_title = "Time Log";
include '../includes/header.php';
checkRole('employee');
$uid = $_SESSION['user_id'];

// FR-S01/S02: record time spent on assigned tasks, linked to project/task
if (isset($_POST['log'])) {
    $tid = (int)$_POST['task_id'];
    $hours = (float)$_POST['hours'];
    $date = $_POST['log_date'];
    $notes = $conn->real_escape_string($_POST['notes']);
    $owns = $conn->query("SELECT id FROM tasks WHERE id=$tid AND assigned_to=$uid")->num_rows;
    if ($owns && $hours > 0) {
        $conn->query("INSERT INTO time_logs (task_id,user_id,hours,log_date,notes) VALUES ($tid,$uid,$hours,'$date','$notes')");
        $msg = "Time logged!";
    }
}

if (isset($_GET['delete'])) {
    $conn->query("DELETE FROM time_logs WHERE id=".(int)$_GET['delete']." AND user_id=$uid");
    header("Location: timelog.php"); exit;
}

$myTasks = $conn->query("SELECT id,title FROM tasks WHERE assigned_to=$uid");
$totalHours = $conn->query("SELECT COALESCE(SUM(hours),0) h FROM time_logs WHERE user_id=$uid")->fetch_assoc()['h'];
?>
<?php if(isset($msg)) echo "<div class='alert alert-success'>$msg</div>"; ?>

<div class="stats-grid">
    <div class="stat-card"><h3>Total Hours Logged</h3><div class="value"><?=$totalHours?></div></div>
</div>

<div class="card">
    <div class="card-header"><h2>Log Time</h2></div>
    <form method="POST">
        <div class="form-row">
            <div class="form-group"><label>Task</label>
                <select name="task_id" required><?php while($t=$myTasks->fetch_assoc()) echo "<option value='{$t['id']}'>{$t['title']}</option>"; ?></select>
            </div>
            <div class="form-group"><label>Hours</label><input type="number" step="0.25" min="0.25" name="hours" required></div>
        </div>
        <div class="form-row">
            <div class="form-group"><label>Date</label><input type="date" name="log_date" required></div>
            <div class="form-group"><label>Notes</label><input type="text" name="notes"></div>
        </div>
        <button type="submit" name="log" class="btn-primary">Log Time</button>
    </form>
</div>

<!-- FR-S03: include time data in reports -->
<div class="card">
    <div class="card-header"><h2>My Time Log History</h2></div>
    <table>
        <tr><th>Task</th><th>Hours</th><th>Date</th><th>Notes</th><th>Actions</th></tr>
        <?php $r = $conn->query("SELECT tl.*, t.title FROM time_logs tl JOIN tasks t ON tl.task_id=t.id WHERE tl.user_id=$uid ORDER BY tl.log_date DESC");
        while($l = $r->fetch_assoc()): ?>
        <tr>
            <td><?=$l['title']?></td>
            <td><?=$l['hours']?></td>
            <td><?=$l['log_date']?></td>
            <td><?=$l['notes']?></td>
            <td><a href="timelog.php?delete=<?=$l['id']?>" onclick="return confirm('Delete?')" class="btn btn-delete btn-sm">Delete</a></td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>
<?php include '../includes/footer.php'; ?>
