<?php
$page_title = "Project Files";
include '../includes/header.php';
checkRole(['manager','admin']);
$uid = $_SESSION['user_id'];

if (!file_exists('../uploads')) mkdir('../uploads', 0777, true);

// FR-27: Users shall be able to upload project-related files
if (isset($_POST['upload']) && !empty($_FILES['file']['name'])) {
    $pid = (int)$_POST['project_id'];
    $owns = $conn->query("SELECT id FROM projects WHERE id=$pid AND manager_id=$uid")->num_rows;
    if ($owns) {
        $orig = basename($_FILES['file']['name']);
        $fname = time() . '_' . $orig;
        $target = '../uploads/' . $fname;
        if (move_uploaded_file($_FILES['file']['tmp_name'], $target)) {
            $size = $_FILES['file']['size'];
            $conn->query("INSERT INTO files (project_id,filename,original_name,file_size,uploaded_by) VALUES ($pid,'$fname','$orig',$size,$uid)");
            $msg = "File uploaded!";
        }
    }
}

if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $f = $conn->query("SELECT f.filename FROM files f JOIN projects p ON f.project_id=p.id WHERE f.id=$id AND p.manager_id=$uid")->fetch_assoc();
    if ($f) { @unlink('../uploads/' . $f['filename']); $conn->query("DELETE FROM files WHERE id=$id"); }
    header("Location: files.php"); exit;
}

$projects = $conn->query("SELECT id,name FROM projects WHERE manager_id=$uid");
?>
<?php if(isset($msg)) echo "<div class='alert alert-success'>$msg</div>"; ?>

<div class="card">
    <div class="card-header"><h2>Upload File</h2></div>
    <form method="POST" enctype="multipart/form-data">
        <div class="form-group"><label>Project</label>
            <select name="project_id" required><?php while($p=$projects->fetch_assoc()) echo "<option value='{$p['id']}'>{$p['name']}</option>"; ?></select>
        </div>
        <div class="form-group"><label>File</label><input type="file" name="file" required></div>
        <button type="submit" name="upload" class="btn-primary">Upload</button>
    </form>
</div>

<div class="card">
    <div class="card-header"><h2>Files for My Projects</h2></div>
    <table>
        <tr><th>File</th><th>Project</th><th>Size</th><th>Actions</th></tr>
        <?php $r = $conn->query("SELECT f.*, p.name pname FROM files f JOIN projects p ON f.project_id=p.id WHERE p.manager_id=$uid ORDER BY f.id DESC");
        while($f = $r->fetch_assoc()): ?>
        <tr>
            <td><?=$f['original_name']?></td>
            <td><?=$f['pname']?></td>
            <td><?=round($f['file_size']/1024,2)?> KB</td>
            <td>
                <a href="../uploads/<?=$f['filename']?>" download class="btn btn-view btn-sm">Download</a>
                <a href="files.php?delete=<?=$f['id']?>" onclick="return confirm('Delete?')" class="btn btn-delete btn-sm">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>
<?php include '../includes/footer.php'; ?>
