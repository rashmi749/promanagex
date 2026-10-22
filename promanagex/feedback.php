<?php
require 'config/database.php';

$pid = (int)($_GET['project'] ?? $_POST['project_id'] ?? 0);
$project = $pid ? $conn->query("SELECT * FROM projects WHERE id=$pid")->fetch_assoc() : null;

$done = false;
if (isset($_POST['submit']) && $project) {
    $name = $conn->real_escape_string($_POST['client_name']);
    $rating = (int)$_POST['rating'];
    $feedback = $conn->real_escape_string($_POST['feedback']);
    $conn->query("INSERT INTO client_feedback (project_id,client_name,rating,feedback) VALUES ($pid,'$name',$rating,'$feedback')");
    $done = true;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Project Feedback - ProManageX</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<div class="login-wrapper">
    <div class="login-box" style="max-width:480px;">
        <h2><i class="fas fa-comment-dots"></i> Project Feedback</h2>
        <?php if (!$project): ?>
            <p style="text-align:center; color:#8fa5c5;">Invalid or missing project link.</p>
        <?php elseif ($done): ?>
            <div class="alert alert-success" style="text-align:center;">Thank you! Your feedback for "<?=$project['name']?>" has been submitted.</div>
        <?php else: ?>
            <p style="text-align:center; color:#8fa5c5; margin-bottom:20px;">Project: <strong><?=$project['name']?></strong></p>
            <form method="POST">
                <input type="hidden" name="project_id" value="<?=$pid?>">
                <input type="text" name="client_name" placeholder="Your Name" required>
                <select name="rating" required style="margin-top:10px;">
                    <option value="5">★★★★★ Excellent</option>
                    <option value="4">★★★★☆ Good</option>
                    <option value="3">★★★☆☆ Average</option>
                    <option value="2">★★☆☆☆ Poor</option>
                    <option value="1">★☆☆☆☆ Very Poor</option>
                </select>
                <textarea name="feedback" placeholder="Your feedback..." rows="4" style="margin-top:10px; width:100%; padding:10px;" required></textarea>
                <button type="submit" name="submit" class="btn-primary" style="width:100%; margin-top:10px;">Submit Feedback</button>
            </form>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
