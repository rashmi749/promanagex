<?php
$page_title = "Project Management";
include '../includes/header.php';
checkRole('admin');

if (isset($_POST['add'])) {
    $code = 'PRJ' . rand(1000,9999);
    $name = $conn->real_escape_string($_POST['name']);
    $desc = $conn->real_escape_string($_POST['description']);
    $client = $conn->real_escape_string($_POST['client']);
    $budget = (float)$_POST['budget'];
    $start = $_POST['start_date'];
    $end = $_POST['end_date'];
    $status = $_POST['status'];
    $mgr = (int)$_POST['manager_id'];
    $conn->query("INSERT INTO projects (project_code,name,description,client_name,budget,start_date,end_date,status,manager_id) VALUES ('$code','$name','$desc','$client',$budget,'$start','$end','$status',$mgr)");
    $msg = "Project created!";
}

// FR-14: editing project information
if (isset($_POST['update'])) {
    $id = (int)$_POST['id'];
    $name = $conn->real_escape_string($_POST['name']);
    $desc = $conn->real_escape_string($_POST['description']);
    $client = $conn->real_escape_string($_POST['client']);
    $budget = (float)$_POST['budget'];
    $start = $_POST['start_date'];
    $end = $_POST['end_date'];
    $status = $_POST['status'];
    $mgr = (int)$_POST['manager_id'];
    $conn->query("UPDATE projects SET name='$name',description='$desc',client_name='$client',budget=$budget,start_date='$start',end_date='$end',status='$status',manager_id=$mgr WHERE id=$id");
    $msg = "Project updated!";
}

// FR-15: deleting projects
if (isset($_GET['delete'])) {
    $conn->query("DELETE FROM projects WHERE id=".(int)$_GET['delete']);
    header("Location: projects.php"); exit;
}

$edit = null;
if (isset($_GET['edit'])) {
    $edit = $conn->query("SELECT * FROM projects WHERE id=".(int)$_GET['edit'])->fetch_assoc();
}

$managers = $conn->query("SELECT id,name FROM users WHERE role IN ('admin','manager')");
?>

<?php if(isset($msg)) echo "<div class='alert alert-success'>$msg</div>"; ?>

<div class="card">
    <div class="card-header"><h2><?=$edit ? 'Edit Project' : 'Create New Project'?></h2></div>
    <form method="POST">
        <?php if($edit): ?><input type="hidden" name="id" value="<?=$edit['id']?>"><?php endif; ?>
        <div class="form-row">
            <div class="form-group"><label>Project Name</label><input type="text" name="name" value="<?=$edit['name']??''?>" required></div>
            <div class="form-group"><label>Client Name</label><input type="text" name="client" value="<?=$edit['client_name']??''?>"></div>
        </div>
        <div class="form-group"><label>Description</label><textarea name="description" rows="3"><?=$edit['description']??''?></textarea></div>
        <div class="form-row">
            <div class="form-group"><label>Budget ($)</label><input type="number" step="0.01" name="budget" value="<?=$edit['budget']??''?>"></div>
            <div class="form-group"><label>Manager</label>
                <select name="manager_id"><?php $managers->data_seek(0); while($m=$managers->fetch_assoc()) { $sel = (isset($edit['manager_id']) && $edit['manager_id']==$m['id']) ? 'selected' : ''; echo "<option value='{$m['id']}' $sel>{$m['name']}</option>"; } ?></select>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group"><label>Start Date</label><input type="date" name="start_date" value="<?=$edit['start_date']??''?>"></div>
            <div class="form-group"><label>End Date</label><input type="date" name="end_date" value="<?=$edit['end_date']??''?>"></div>
        </div>
        <div class="form-group"><label>Status</label>
            <select name="status">
                <?php foreach(['Not Started','In Progress','Completed','On Hold','Cancelled'] as $s):
                    $sel = (isset($edit['status']) && $edit['status']==$s) ? 'selected' : ''; ?>
                <option <?=$sel?>><?=$s?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <?php if($edit): ?>
            <button type="submit" name="update" class="btn-primary">Update Project</button>
            <a href="projects.php" class="btn btn-view btn-sm">Cancel</a>
        <?php else: ?>
            <button type="submit" name="add" class="btn-primary">Create Project</button>
        <?php endif; ?>
    </form>
</div>

<div class="card">
    <div class="card-header"><h2>All Projects</h2></div>
    <table>
        <tr><th>Code</th><th>Name</th><th>Client</th><th>Budget</th><th>Status</th><th>Actions</th></tr>
        <?php $r = $conn->query("SELECT * FROM projects ORDER BY id DESC");
        while($p = $r->fetch_assoc()): ?>
        <tr>
            <td><?=$p['project_code']?></td>
            <td><?=$p['name']?></td>
            <td><?=$p['client_name']?></td>
            <td>$<?=number_format($p['budget'],2)?></td>
            <td><span class="badge badge-info"><?=$p['status']?></span></td>
            <td>
                <a href="projects.php?edit=<?=$p['id']?>" class="btn btn-edit btn-sm">Edit</a>
                <a href="projects.php?delete=<?=$p['id']?>" onclick="return confirm('Delete?')" class="btn btn-delete btn-sm">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>

<?php include '../includes/footer.php'; ?>
