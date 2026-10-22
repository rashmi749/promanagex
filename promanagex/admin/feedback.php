<?php
$page_title = "Client Feedback";
include '../includes/header.php';
checkRole('admin');

if (isset($_GET['delete'])) {
    $conn->query("DELETE FROM client_feedback WHERE id=".(int)$_GET['delete']);
    header("Location: feedback.php"); exit;
}

$projects = $conn->query("SELECT id,name FROM projects");
?>
<div class="card">
    <div class="card-header"><h2>Client Feedback Link Generator</h2></div>
    <p style="color:#8fa5c5; margin-bottom:15px;">Share this link with a client so they can submit feedback for a project (no login required):</p>
    <div class="form-group"><label>Project</label>
        <select id="fbProject" onchange="document.getElementById('fbLink').value='<?=BASE_URL?>feedback.php?project='+this.value">
            <option value="">Select a project...</option>
            <?php while($p=$projects->fetch_assoc()) echo "<option value='{$p['id']}'>{$p['name']}</option>"; ?>
        </select>
    </div>
    <div class="form-group"><label>Shareable Link</label><input type="text" id="fbLink" readonly onclick="this.select()"></div>
</div>

<!-- FR-E03: clients submit project feedback -->
<div class="card">
    <div class="card-header"><h2>Received Feedback</h2></div>
    <table>
        <tr><th>Project</th><th>Client Name</th><th>Rating</th><th>Feedback</th><th>Date</th><th>Actions</th></tr>
        <?php $r = $conn->query("SELECT cf.*, p.name pname FROM client_feedback cf LEFT JOIN projects p ON cf.project_id=p.id ORDER BY cf.id DESC");
        while($f = $r->fetch_assoc()): ?>
        <tr>
            <td><?=$f['pname']?></td>
            <td><?=$f['client_name']?></td>
            <td><?php for($i=0;$i<5;$i++) echo $i < $f['rating'] ? '★' : '☆'; ?></td>
            <td><?=$f['feedback']?></td>
            <td><?=$f['created_at']?></td>
            <td><a href="feedback.php?delete=<?=$f['id']?>" onclick="return confirm('Delete?')" class="btn btn-delete btn-sm">Delete</a></td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>
<?php include '../includes/footer.php'; ?>
