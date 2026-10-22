<?php
$page_title = "My Notifications";
include '../includes/header.php';
checkRole('employee');
$uid = $_SESSION['user_id'];

// mark as read when viewed
if (isset($_GET['read'])) {
    $conn->query("UPDATE notifications SET is_read=1 WHERE id=".(int)$_GET['read']." AND user_id=$uid");
    header("Location: notifications.php"); exit;
}
?>
<div class="card">
    <div class="card-header"><h2>My Notifications</h2></div>
    <table>
        <tr><th>Title</th><th>Message</th><th>Date</th><th>Status</th><th></th></tr>
        <?php $r = $conn->query("SELECT * FROM notifications WHERE user_id=$uid ORDER BY id DESC");
        while($n = $r->fetch_assoc()): ?>
        <tr>
            <td><?=$n['title']?></td>
            <td><?=$n['message']?></td>
            <td><?=$n['created_at']?></td>
            <td><span class="badge <?=$n['is_read']?'badge-info':'badge-warning'?>"><?=$n['is_read']?'Read':'Unread'?></span></td>
            <td><?php if(!$n['is_read']): ?><a href="notifications.php?read=<?=$n['id']?>" class="btn btn-view btn-sm">Mark Read</a><?php endif; ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>
<?php include '../includes/footer.php'; ?>
