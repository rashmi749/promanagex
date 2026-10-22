<?php
$page_title = "Task Management";
include '../includes/header.php';
checkRole('admin');

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

// FR-14/FR-22 style editing for tasks
if (isset($_POST['update'])) {
    $id = (int)$_POST['id'];
    $pid = (int)$_POST['project_id'];
    $title = $conn->real_escape_string($_POST['title']);
    $desc = $conn->real_escape_string($_POST['description']);
    $assign = (int)$_POST['assigned_to'];
    $prio = $_POST['priority'];
    $deadline = $_POST['deadline'];
    $status = $_POST['status'];
    $progress = (int)$_POST['progress'];
    $conn->query("UPDATE tasks SET project_id=$pid,title='$title',description='$desc',assigned_to=$assign,priority='$prio',deadline='$deadline',status='$status',progress=$progress WHERE id=$id");
    $msg = "Task updated!";
}

if (isset($_GET['delete'])) {
    $conn->query("DELETE FROM tasks WHERE id=".(int)$_GET['delete']);
    header("Location: tasks.php"); exit;
}

$edit = null;
if (isset($_GET['edit'])) {
    $edit = $conn->query("SELECT * FROM tasks WHERE id=".(int)$_GET['edit'])->fetch_assoc();
}

$projects = $conn->query("SELECT id,name FROM projects");
$employees = $conn->query("SELECT id,name FROM users WHERE role='employee'");
?>

<?php if(isset($msg)) echo "<div class='alert alert-success'>$msg</div>"; ?>

<div class="card">
    <div class="card-header"><h2><?=$edit ? 'Edit Task' : 'Create Task'?></h2></div>
    <form method="POST">
        <?php if($edit): ?><input type="hidden" name="id" value="<?=$edit['id']?>"><?php endif; ?>
        <div class="form-row">
            <div class="form-group"><label>Project</label>
                <select name="project_id" required><?php $projects->data_seek(0); while($p=$projects->fetch_assoc()) { $sel = (isset($edit['project_id']) && $edit['project_id']==$p['id'])?'selected':''; echo "<option value='{$p['id']}' $sel>{$p['name']}</option>"; } ?></select>
            </div>
            <div class="form-group"><label>Assign To</label>
                <select name="assigned_to" required><?php $employees->data_seek(0); while($e=$employees->fetch_assoc()) { $sel = (isset($edit['assigned_to']) && $edit['assigned_to']==$e['id'])?'selected':''; echo "<option value='{$e['id']}' $sel>{$e['name']}</option>"; } ?></select>
            </div>
        </div>
        <div class="form-group"><label>Task Title</label><input type="text" name="title" value="<?=$edit['title']??''?>" required></div>
        <div class="form-group"><label>Description</label><textarea name="description" rows="3"><?=$edit['description']??''?></textarea></div>
        <div class="form-row">
            <div class="form-group"><label>Priority</label><select name="priority">
                <?php foreach(['Low','Medium','High'] as $pr) { $sel=(isset($edit['priority'])&&$edit['priority']==$pr)?'selected':''; echo "<option $sel>$pr</option>"; } ?>
            </select></div>
            <div class="form-group"><label>Deadline</label><input type="date" name="deadline" value="<?=$edit['deadline']??''?>" required></div>
        </div>
        <?php if($edit): ?>
        <div class="form-row">
            <div class="form-group"><label>Status</label><select name="status">
                <?php foreach(['Pending','In Progress','Completed'] as $st) { $sel=($edit['status']==$st)?'selected':''; echo "<option $sel>$st</option>"; } ?>
            </select></div>
            <div class="form-group"><label>Progress (%)</label><input type="number" name="progress" min="0" max="100" value="<?=$edit['progress']?>"></div>
        </div>
        <button type="submit" name="update" class="btn-primary">Update Task</button>
        <a href="tasks.php" class="btn btn-view btn-sm">Cancel</a>
        <?php else: ?>
        <button type="submit" name="add" class="btn-primary">Create Task</button>
        <?php endif; ?>
    </form>
</div>

<div class="card">
    <div class="card-header"><h2>All Tasks</h2></div>
    <table>
        <tr><th>Title</th><th>Project</th><th>Assigned</th><th>Priority</th><th>Status</th><th>Deadline</th><th>Actions</th></tr>
        <?php $r = $conn->query("SELECT t.*, p.name pname, u.name uname FROM tasks t LEFT JOIN projects p ON t.project_id=p.id LEFT JOIN users u ON t.assigned_to=u.id ORDER BY t.id DESC");
        while($t = $r->fetch_assoc()): ?>
        <tr>
            <td><?=$t['title']?></td>
            <td><?=$t['pname']?></td>
            <td><?=$t['uname']?></td>
            <td><span class="badge badge-warning"><?=$t['priority']?></span></td>
            <td><span class="badge badge-info"><?=$t['status']?></span></td>
            <td><?=$t['deadline']?></td>
            <td>
                <a href="tasks.php?edit=<?=$t['id']?>" class="btn btn-edit btn-sm">Edit</a>
                <a href="tasks.php?delete=<?=$t['id']?>" onclick="return confirm('Delete?')" class="btn btn-delete btn-sm">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>

<?php include '../includes/footer.php'; ?>
