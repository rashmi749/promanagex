<?php
$page_title = "Client Invoices";
include '../includes/header.php';
checkRole('admin');

// FR-E02: maintain client invoice information for client projects
if (isset($_POST['add'])) {
    $pid = (int)$_POST['project_id'];
    $num = 'INV' . rand(10000,99999);
    $amount = (float)$_POST['amount'];
    $status = $_POST['status'];
    $issue = $_POST['issue_date'];
    $due = $_POST['due_date'];
    $conn->query("INSERT INTO client_invoices (project_id,invoice_number,amount,status,issue_date,due_date) VALUES ($pid,'$num',$amount,'$status','$issue','$due')");
    $msg = "Invoice created!";
}

if (isset($_POST['update_status'])) {
    $id = (int)$_POST['id'];
    $status = $_POST['status'];
    $conn->query("UPDATE client_invoices SET status='$status' WHERE id=$id");
    $msg = "Invoice status updated!";
}

if (isset($_GET['delete'])) {
    $conn->query("DELETE FROM client_invoices WHERE id=".(int)$_GET['delete']);
    header("Location: invoices.php"); exit;
}

$projects = $conn->query("SELECT id,name,client_name FROM projects");
?>
<?php if(isset($msg)) echo "<div class='alert alert-success'>$msg</div>"; ?>

<div class="card">
    <div class="card-header"><h2>Create Invoice</h2></div>
    <form method="POST">
        <div class="form-row">
            <div class="form-group"><label>Project / Client</label>
                <select name="project_id" required><?php while($p=$projects->fetch_assoc()) echo "<option value='{$p['id']}'>{$p['name']} ({$p['client_name']})</option>"; ?></select>
            </div>
            <div class="form-group"><label>Amount ($)</label><input type="number" step="0.01" name="amount" required></div>
        </div>
        <div class="form-row">
            <div class="form-group"><label>Issue Date</label><input type="date" name="issue_date" required></div>
            <div class="form-group"><label>Due Date</label><input type="date" name="due_date" required></div>
        </div>
        <div class="form-group"><label>Status</label>
            <select name="status"><option>Draft</option><option>Sent</option><option>Paid</option><option>Overdue</option></select>
        </div>
        <button type="submit" name="add" class="btn-primary">Create Invoice</button>
    </form>
</div>

<div class="card">
    <div class="card-header"><h2>All Invoices</h2></div>
    <table>
        <tr><th>Invoice #</th><th>Project</th><th>Client</th><th>Amount</th><th>Due</th><th>Status</th><th>Actions</th></tr>
        <?php $r = $conn->query("SELECT i.*, p.name pname, p.client_name FROM client_invoices i LEFT JOIN projects p ON i.project_id=p.id ORDER BY i.id DESC");
        while($i = $r->fetch_assoc()): ?>
        <tr>
            <td><?=$i['invoice_number']?></td>
            <td><?=$i['pname']?></td>
            <td><?=$i['client_name']?></td>
            <td>$<?=number_format($i['amount'],2)?></td>
            <td><?=$i['due_date']?></td>
            <td>
                <form method="POST" style="display:flex; gap:5px;">
                    <input type="hidden" name="id" value="<?=$i['id']?>">
                    <select name="status" style="padding:5px;">
                        <?php foreach(['Draft','Sent','Paid','Overdue'] as $s) { $sel=($i['status']==$s)?'selected':''; echo "<option $sel>$s</option>"; } ?>
                    </select>
                    <button type="submit" name="update_status" class="btn btn-edit btn-sm">Save</button>
                </form>
            </td>
            <td><a href="invoices.php?delete=<?=$i['id']?>" onclick="return confirm('Delete?')" class="btn btn-delete btn-sm">Delete</a></td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>
<?php include '../includes/footer.php'; ?>
