<?php
$page_title = "Tasks";
include '../includes/header.php';
checkRole(['manager','admin']);
$uid = $_SESSION['user_id'];

if (isset($_POST['add'])) {
    $pid = (int)$_POST['project_id'];
    $title = $conn->real_escape_string($_POST['title']);
    $desc = $conn->real_escape_string($_POST['description']);
    $assign = (int)$_POST['assigned_to'];
    $prio = $_POST['priority'];
    $deadline = $_POST['deadline'];
    $conn->query("INSERT INTO tasks (project_id,title,description,assigned_to,priority,deadline) VALUES ($pid,'$title','$desc',$assign,'$prio','$deadline')");
    $conn->query("INSERT INTO notifications (user_id,title,message) VALUES ($assign,'New Task Assigned','You have a new task: $title')");
    $msg = "Task created!";
}

if (isset($_GET['delete'])) {
    $conn->query("DELETE FROM tasks WHERE id=".(int)$_GET['delete']." AND project_id IN (SELECT id FROM projects WHERE manager_id=$uid)");
    header("Location: tasks.php"); exit;
}

$projects = $conn->query("SELECT id,name FROM projects WHERE manager_id=$uid");
$employees = $conn->query("SELECT id,name FROM users WHERE role='employee'");
?>
<?php if(isset($msg)) echo "<div class='alert alert-success'>$msg</div>"; ?>

<div class="card">
    <div class="card-header"><h2>Create Task</h2></div>
    <form method="POST">
        <div class="form-row">
            <div class="form-group"><label>Project</label>
                <select name="project_id" required><?php while($p=$projects->fetch_assoc()) echo "<option value='{$p['id']}'>{$p['name']}</option>"; ?></select>
            </div>
            <div class="form-group"><label>Assign To</label>
                <select name="assigned_to" required><?php while($e=$employees->fetch_assoc()) echo "<option value='{$e['id']}'>{$e['name']}</option>"; ?></select>
            </div>
        </div>
        <div class="form-group"><label>Task Title</label><input type="text" name="title" required></div>
        <div class="form-group"><label>Description</label><textarea name="description" rows="3"></textarea></div>
        <div class="form-row">
            <div class="form-group"><label>Priority</label><select name="priority"><option>Low</option><option>Medium</option><option>High</option></select></div>
            <div class="form-group"><label>Deadline</label><input type="date" name="deadline" required></div>
        </div>
        <button type="submit" name="add" class="btn-primary">Create Task</button>
    </form>
</div>

<div class="card">
    <div class="card-header"><h2>Tasks for My Projects</h2></div>
    <table>
        <tr><th>Title</th><th>Project</th><th>Assigned</th><th>Priority</th><th>Status</th><th>Deadline</th><th>Actions</th></tr>
        <?php $r = $conn->query("SELECT t.*, p.name pname, u.name uname FROM tasks t LEFT JOIN projects p ON t.project_id=p.id LEFT JOIN users u ON t.assigned_to=u.id WHERE p.manager_id=$uid ORDER BY t.id DESC");
        while($t = $r->fetch_assoc()): ?>
        <tr>
            <td><?=$t['title']?></td>
            <td><?=$t['pname']?></td>
            <td><?=$t['uname']?></td>
            <td><span class="badge badge-warning"><?=$t['priority']?></span></td>
            <td><span class="badge badge-info"><?=$t['status']?></span></td>
            <td><?=$t['deadline']?></td>
            <td><a href="tasks.php?delete=<?=$t['id']?>" onclick="return confirm('Delete?')" class="btn btn-delete btn-sm">Delete</a></td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>
<?php include '../includes/footer.php'; ?>
