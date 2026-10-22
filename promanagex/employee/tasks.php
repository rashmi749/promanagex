<?php
$page_title = "My Tasks";
include '../includes/header.php';
checkRole('employee');
$uid = $_SESSION['user_id'];

if (isset($_POST['update'])) {
    $id = (int)$_POST['id'];
    $status = $_POST['status'];
    $prog = (int)$_POST['progress'];
    $conn->query("UPDATE tasks SET status='$status', progress=$prog WHERE id=$id AND assigned_to=$uid");
    $msg = "Task updated!";
}
?>
<?php if(isset($msg)) echo "<div class='alert alert-success'>$msg</div>"; ?>
<div class="card"><div class="card-header"><h2>My Assigned Tasks</h2></div>
<table><tr><th>Task</th><th>Priority</th><th>Deadline</th><th>Status</th><th>Progress</th><th>Update</th></tr>
<?php $r = $conn->query("SELECT * FROM tasks WHERE assigned_to=$uid ORDER BY id DESC");
while($t=$r->fetch_assoc()): ?>
<tr>
    <td><?=$t['title']?></td>
    <td><?=$t['priority']?></td>
    <td><?=$t['deadline']?></td>
    <td><span class="badge badge-info"><?=$t['status']?></span></td>
    <td><?=$t['progress']?>%</td>
    <td>
        <form method="POST" style="display:flex; gap:5px;">
            <input type="hidden" name="id" value="<?=$t['id']?>">
            <select name="status" style="padding:5px;">
                <option <?=$t['status']=='Pending'?'selected':''?>>Pending</option>
                <option <?=$t['status']=='In Progress'?'selected':''?>>In Progress</option>
                <option <?=$t['status']=='Completed'?'selected':''?>>Completed</option>
            </select>
            <input type="number" name="progress" value="<?=$t['progress']?>" min="0" max="100" style="width:60px; padding:5px;">
            <button type="submit" name="update" class="btn btn-edit btn-sm">Save</button>
        </form>
    </td>
</tr>
<?php endwhile; ?></table></div>
<?php include '../includes/footer.php'; ?>
