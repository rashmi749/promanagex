<?php
$page_title = "Financial Records";
include '../includes/header.php';
checkRole('admin');

// FR-E01: maintain project financial records (income, expenses, transactions)
if (isset($_POST['add'])) {
    $pid = (int)$_POST['project_id'];
    $type = $_POST['type'];
    $desc = $conn->real_escape_string($_POST['description']);
    $amount = (float)$_POST['amount'];
    $date = $_POST['record_date'];
    $uid = $_SESSION['user_id'];
    $conn->query("INSERT INTO financial_records (project_id,type,description,amount,record_date,created_by) VALUES ($pid,'$type','$desc',$amount,'$date',$uid)");
    $msg = "Financial record added!";
}

if (isset($_GET['delete'])) {
    $conn->query("DELETE FROM financial_records WHERE id=".(int)$_GET['delete']);
    header("Location: financials.php"); exit;
}

$projects = $conn->query("SELECT id,name FROM projects");
$filter = isset($_GET['project']) ? (int)$_GET['project'] : 0;
?>
<?php if(isset($msg)) echo "<div class='alert alert-success'>$msg</div>"; ?>

<div class="card">
    <div class="card-header"><h2>Add Financial Record</h2></div>
    <form method="POST">
        <div class="form-row">
            <div class="form-group"><label>Project</label>
                <select name="project_id" required><?php while($p=$projects->fetch_assoc()) echo "<option value='{$p['id']}'>{$p['name']}</option>"; ?></select>
            </div>
            <div class="form-group"><label>Type</label>
                <select name="type"><option>Income</option><option>Expense</option></select>
            </div>
        </div>
        <div class="form-group"><label>Description</label><input type="text" name="description"></div>
        <div class="form-row">
            <div class="form-group"><label>Amount ($)</label><input type="number" step="0.01" name="amount" required></div>
            <div class="form-group"><label>Date</label><input type="date" name="record_date" required></div>
        </div>
        <button type="submit" name="add" class="btn-primary">Add Record</button>
    </form>
</div>

<?php
$sumR = $conn->query("SELECT
    SUM(CASE WHEN type='Income' THEN amount ELSE 0 END) income,
    SUM(CASE WHEN type='Expense' THEN amount ELSE 0 END) expense
    FROM financial_records" . ($filter ? " WHERE project_id=$filter" : ""));
$sums = $sumR->fetch_assoc();
$income = $sums['income'] ?? 0; $expense = $sums['expense'] ?? 0;
?>
<div class="stats-grid">
    <div class="stat-card"><h3>Total Income</h3><div class="value">$<?=number_format($income,2)?></div></div>
    <div class="stat-card"><h3>Total Expense</h3><div class="value">$<?=number_format($expense,2)?></div></div>
    <div class="stat-card"><h3>Net</h3><div class="value">$<?=number_format($income-$expense,2)?></div></div>
</div>

<div class="card">
    <div class="card-header"><h2>All Financial Records</h2></div>
    <table>
        <tr><th>Project</th><th>Type</th><th>Description</th><th>Amount</th><th>Date</th><th>Actions</th></tr>
        <?php $r = $conn->query("SELECT f.*, p.name pname FROM financial_records f LEFT JOIN projects p ON f.project_id=p.id ORDER BY f.record_date DESC");
        while($f = $r->fetch_assoc()): ?>
        <tr>
            <td><?=$f['pname']?></td>
            <td><span class="badge <?=$f['type']=='Income'?'badge-info':'badge-warning'?>"><?=$f['type']?></span></td>
            <td><?=$f['description']?></td>
            <td>$<?=number_format($f['amount'],2)?></td>
            <td><?=$f['record_date']?></td>
            <td><a href="financials.php?delete=<?=$f['id']?>" onclick="return confirm('Delete?')" class="btn btn-delete btn-sm">Delete</a></td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>
<?php include '../includes/footer.php'; ?>
