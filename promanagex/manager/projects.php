<?php
$page_title = "My Projects";
include '../includes/header.php';
checkRole(['manager','admin']);
$uid = $_SESSION['user_id'];

if (isset($_POST['add'])) {
    $code = 'PRJ' . rand(1000,9999);
    $name = $conn->real_escape_string($_POST['name']);
    $desc = $conn->real_escape_string($_POST['description']);
    $client = $conn->real_escape_string($_POST['client']);
    $budget = (float)$_POST['budget'];
    $start = $_POST['start_date'];
    $end = $_POST['end_date'];
    $status = $_POST['status'];
    $conn->query("INSERT INTO projects (project_code,name,description,client_name,budget,start_date,end_date,status,manager_id) VALUES ('$code','$name','$desc','$client',$budget,'$start','$end','$status',$uid)");
    $msg = "Project created!";
}

if (isset($_POST['update_status'])) {
    $id = (int)$_POST['id'];
    $status = $_POST['status'];
    $conn->query("UPDATE projects SET status='$status' WHERE id=$id AND manager_id=$uid");
    $msg = "Project updated!";
}
?>
<?php if(isset($msg)) echo "<div class='alert alert-success'>$msg</div>"; ?>

<div class="card">
    <div class="card-header"><h2>Create New Project</h2></div>
    <form method="POST">
        <div class="form-row">
            <div class="form-group"><label>Project Name</label><input type="text" name="name" required></div>
            <div class="form-group"><label>Client Name</label><input type="text" name="client"></div>
        </div>
        <div class="form-group"><label>Description</label><textarea name="description" rows="3"></textarea></div>
        <div class="form-row">
            <div class="form-group"><label>Budget ($)</label><input type="number" step="0.01" name="budget"></div>
            <div class="form-group"><label>Status</label>
                <select name="status">
                    <option>Not Started</option><option>In Progress</option><option>Completed</option><option>On Hold</option><option>Cancelled</option>
                </select>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group"><label>Start Date</label><input type="date" name="start_date"></div>
            <div class="form-group"><label>End Date</label><input type="date" name="end_date"></div>
        </div>
        <button type="submit" name="add" class="btn-primary">Create Project</button>
    </form>
</div>

<div class="card">
    <div class="card-header"><h2>My Projects</h2></div>
    <table>
        <tr><th>Code</th><th>Name</th><th>Client</th><th>Budget</th><th>Status</th><th>Update</th></tr>
        <?php $r = $conn->query("SELECT * FROM projects WHERE manager_id=$uid ORDER BY id DESC");
        while($p = $r->fetch_assoc()): ?>
        <tr>
            <td><?=$p['project_code']?></td>
            <td><?=$p['name']?></td>
            <td><?=$p['client_name']?></td>
            <td>$<?=number_format($p['budget'],2)?></td>
            <td><span class="badge badge-info"><?=$p['status']?></span></td>
            <td>
                <form method="POST" style="display:flex; gap:5px;">
                    <input type="hidden" name="id" value="<?=$p['id']?>">
                    <select name="status" style="padding:5px;">
                        <option <?=$p['status']=='Not Started'?'selected':''?>>Not Started</option>
                        <option <?=$p['status']=='In Progress'?'selected':''?>>In Progress</option>
                        <option <?=$p['status']=='Completed'?'selected':''?>>Completed</option>
                        <option <?=$p['status']=='On Hold'?'selected':''?>>On Hold</option>
                        <option <?=$p['status']=='Cancelled'?'selected':''?>>Cancelled</option>
                    </select>
                    <button type="submit" name="update_status" class="btn btn-edit btn-sm">Save</button>
                </form>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>
<?php include '../includes/footer.php'; ?>
