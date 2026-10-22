<?php
$page_title = "Announcements";
include '../includes/header.php';
checkRole('admin');

// FR-E05: Admin/PM publish announcements to relevant users
if (isset($_POST['post'])) {
    $title = $conn->real_escape_string($_POST['title']);
    $message = $conn->real_escape_string($_POST['message']);
    $audience = $_POST['audience'];
    $uid = $_SESSION['user_id'];
    $conn->query("INSERT INTO announcements (title,message,posted_by,audience) VALUES ('$title','$message',$uid,'$audience')");
    $msg = "Announcement posted!";
}

if (isset($_GET['delete'])) {
    $conn->query("DELETE FROM announcements WHERE id=".(int)$_GET['delete']);
    header("Location: announcements.php"); exit;
}
?>
<?php if(isset($msg)) echo "<div class='alert alert-success'>$msg</div>"; ?>

<div class="card">
    <div class="card-header"><h2>Post Announcement</h2></div>
    <form method="POST">
        <div class="form-group"><label>Title</label><input type="text" name="title" required></div>
        <div class="form-group"><label>Message</label><textarea name="message" rows="4" required></textarea></div>
        <div class="form-group"><label>Audience</label>
            <select name="audience"><option value="all">Everyone</option><option value="manager">Managers Only</option><option value="employee">Employees Only</option></select>
        </div>
        <button type="submit" name="post" class="btn-primary">Post Announcement</button>
    </form>
</div>

<div class="card">
    <div class="card-header"><h2>All Announcements</h2></div>
    <table>
        <tr><th>Title</th><th>Message</th><th>Audience</th><th>Posted By</th><th>Date</th><th>Actions</th></tr>
        <?php $r = $conn->query("SELECT a.*, u.name FROM announcements a LEFT JOIN users u ON a.posted_by=u.id ORDER BY a.id DESC");
        while($a = $r->fetch_assoc()): ?>
        <tr>
            <td><?=$a['title']?></td>
            <td><?=$a['message']?></td>
            <td><span class="badge badge-info"><?=ucfirst($a['audience'])?></span></td>
            <td><?=$a['name']?></td>
            <td><?=$a['created_at']?></td>
            <td><a href="announcements.php?delete=<?=$a['id']?>" onclick="return confirm('Delete?')" class="btn btn-delete btn-sm">Delete</a></td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>
<?php include '../includes/footer.php'; ?>
