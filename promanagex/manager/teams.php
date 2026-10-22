<?php
$page_title = "Teams";
include '../includes/header.php';
checkRole(['manager','admin']);
$uid = $_SESSION['user_id'];

// FR-23: Project Managers create teams (scoped to their own projects)
if (isset($_POST['add'])) {
    $name = $conn->real_escape_string($_POST['name']);
    $pid = (int)$_POST['project_id'];
    $lid = (int)$_POST['leader_id'];
    $owns = $conn->query("SELECT id FROM projects WHERE id=$pid AND manager_id=$uid")->num_rows;
    if ($owns) {
        $conn->query("INSERT INTO teams (name,project_id,leader_id) VALUES ('$name',$pid,$lid)");
        $msg = "Team created!";
    }
}

if (isset($_GET['delete'])) {
    $conn->query("DELETE FROM teams WHERE id=".(int)$_GET['delete']." AND project_id IN (SELECT id FROM projects WHERE manager_id=$uid)");
    header("Location: teams.php"); exit;
}

// FR-24: assign employees to teams/projects
if (isset($_POST['add_member'])) {
    $tid = (int)$_POST['team_id'];
    $euid = (int)$_POST['user_id'];
    $owns = $conn->query("SELECT t.id FROM teams t JOIN projects p ON t.project_id=p.id WHERE t.id=$tid AND p.manager_id=$uid")->num_rows;
    if ($owns) $conn->query("INSERT IGNORE INTO team_members (team_id,user_id) VALUES ($tid,$euid)");
    header("Location: teams.php?view=$tid"); exit;
}

if (isset($_GET['remove_member'])) {
    $conn->query("DELETE FROM team_members WHERE id=".(int)$_GET['remove_member']);
    header("Location: teams.php?view=".(int)$_GET['team']); exit;
}

$projects = $conn->query("SELECT id,name FROM projects WHERE manager_id=$uid");
$leaders = $conn->query("SELECT id,name FROM users WHERE role IN ('manager','employee')");

$viewTeam = null;
if (isset($_GET['view'])) {
    $viewTeam = $conn->query("SELECT t.* FROM teams t JOIN projects p ON t.project_id=p.id WHERE t.id=".(int)$_GET['view']." AND p.manager_id=$uid")->fetch_assoc();
}
?>
<?php if(isset($msg)) echo "<div class='alert alert-success'>$msg</div>"; ?>
<div class="card">
    <div class="card-header"><h2>Create Team</h2></div>
    <form method="POST">
        <div class="form-group"><label>Team Name</label><input type="text" name="name" required></div>
        <div class="form-row">
            <div class="form-group"><label>Project</label>
                <select name="project_id"><?php while($p=$projects->fetch_assoc()) echo "<option value='{$p['id']}'>{$p['name']}</option>"; ?></select>
            </div>
            <div class="form-group"><label>Team Leader</label>
                <select name="leader_id"><?php while($l=$leaders->fetch_assoc()) echo "<option value='{$l['id']}'>{$l['name']}</option>"; ?></select>
            </div>
        </div>
        <button type="submit" name="add" class="btn-primary">Create</button>
    </form>
</div>
<div class="card">
    <div class="card-header"><h2>My Teams</h2></div>
    <table>
        <tr><th>Team</th><th>Project</th><th>Leader</th><th>Members</th><th>Actions</th></tr>
        <?php $r = $conn->query("SELECT t.*, p.name pname, u.name lname FROM teams t JOIN projects p ON t.project_id=p.id LEFT JOIN users u ON t.leader_id=u.id WHERE p.manager_id=$uid");
        while($t = $r->fetch_assoc()):
            $cnt = $conn->query("SELECT COUNT(*) c FROM team_members WHERE team_id={$t['id']}")->fetch_assoc()['c']; ?>
        <tr><td><?=$t['name']?></td><td><?=$t['pname']?></td><td><?=$t['lname']?></td><td><?=$cnt?> member(s)</td>
        <td>
            <a href="teams.php?view=<?=$t['id']?>" class="btn btn-view btn-sm">Manage Members</a>
            <a href="teams.php?delete=<?=$t['id']?>" onclick="return confirm('Delete?')" class="btn btn-delete btn-sm">Delete</a>
        </td></tr>
        <?php endwhile; ?>
    </table>
</div>

<?php if ($viewTeam): ?>
<div class="card">
    <div class="card-header"><h2>Members of "<?=$viewTeam['name']?>"</h2></div>
    <form method="POST" class="form-row">
        <input type="hidden" name="team_id" value="<?=$viewTeam['id']?>">
        <div class="form-group"><label>Add Employee</label>
            <select name="user_id">
                <?php $emps = $conn->query("SELECT id,name FROM users WHERE role IN ('employee','manager')");
                while($e=$emps->fetch_assoc()) echo "<option value='{$e['id']}'>{$e['name']}</option>"; ?>
            </select>
        </div>
        <div class="form-group" style="align-self:flex-end;"><button type="submit" name="add_member" class="btn-primary">Add to Team</button></div>
    </form>
    <table>
        <tr><th>Name</th><th>Email</th><th>Role in Team</th><th>Actions</th></tr>
        <?php $m = $conn->query("SELECT tm.*, u.name, u.email FROM team_members tm JOIN users u ON tm.user_id=u.id WHERE tm.team_id={$viewTeam['id']}");
        while($mm = $m->fetch_assoc()): ?>
        <tr><td><?=$mm['name']?></td><td><?=$mm['email']?></td><td><?=$mm['role_in_team']?></td>
        <td><a href="teams.php?remove_member=<?=$mm['id']?>&team=<?=$viewTeam['id']?>" onclick="return confirm('Remove member?')" class="btn btn-delete btn-sm">Remove</a></td></tr>
        <?php endwhile; ?>
    </table>
    <a href="teams.php" class="btn btn-view btn-sm">Back to Teams</a>
</div>
<?php endif; ?>
<?php include '../includes/footer.php'; ?>
