<?php
$page_title = "File Management";
include '../includes/header.php';
checkRole('admin');

if (!file_exists('../uploads')) mkdir('../uploads', 0777, true);

if (isset($_POST['upload']) && !empty($_FILES['file']['name'])) {
    $pid = (int)$_POST['project_id'];
    $orig = basename($_FILES['file']['name']);
    $fname = time() . '_' . $orig;
    $target = '../uploads/' . $fname;
    if (move_uploaded_file($_FILES['file']['tmp_name'], $target)) {
        $size = $_FILES['file']['size'];
        $uid = $_SESSION['user_id'];
        $conn->query("INSERT INTO files (project_id,filename,original_name,file_size,uploaded_by) VALUES ($pid,'$fname','$orig',$size,$uid)");
        $msg = "File uploaded!";
    }
}

if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $f = $conn->query("SELECT filename FROM files WHERE id=$id")->fetch_assoc();
    if ($f) @unlink('../uploads/' . $f['filename']);
    $conn->query("DELETE FROM files WHERE id=$id");
    header("Location: files.php"); exit;
}

$projects = $conn->query("SELECT id,name FROM projects");
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
    <div class="card-header"><h2>All Files</h2></div>
    <table>
        <tr><th>File</th><th>Project</th><th>Size</th><th>Actions</th></tr>
        <?php $r = $conn->query("SELECT f.*, p.name pname FROM files f LEFT JOIN projects p ON f.project_id=p.id ORDER BY f.id DESC");
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
