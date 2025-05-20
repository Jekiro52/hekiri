<?php
require 'common/check_login.php';
require 'common/check_role.php';
require_role('admin');
require 'db_connect.php';
include 'common/layout.php';

if (isset($_POST['add_strand'])) {
    $name = trim($_POST['strand_name']);
    $pdo->prepare("INSERT INTO strands (strand_name) VALUES (?)")->execute([$name]);
}

if (isset($_POST['update_strand'])) {
    $id = $_POST['edit_id'];
    $name = trim($_POST['edit_strand_name']);
    $pdo->prepare("UPDATE strands SET strand_name=? WHERE id=?")->execute([$name, $id]);
}

if (isset($_GET['archive'])) {
    $pdo->prepare("UPDATE strands SET archived=1 WHERE id=?")->execute([$_GET['archive']]);
}

$strands = $pdo->query("SELECT * FROM strands WHERE archived=0")->fetchAll();
$edit_id = $_GET['edit'] ?? null;
$edit_strand = null;
if ($edit_id) {
    $stmt = $pdo->prepare("SELECT * FROM strands WHERE id=?");
    $stmt->execute([$edit_id]);
    $edit_strand = $stmt->fetch();
}
?>
<h2>Manage Strands</h2>
<?php if (!$edit_strand): ?>
<form method="post" class="mb-3 d-flex gap-2">
    <input type="text" name="strand_name" class="form-control" placeholder="Strand name" required>
    <button type="submit" name="add_strand" class="btn btn-green">Add</button>
</form>
<?php else: ?>
<form method="post" class="mb-3 d-flex gap-2">
    <input type="hidden" name="edit_id" value="<?= $edit_strand['id'] ?>">
    <input type="text" name="edit_strand_name" class="form-control" value="<?= htmlspecialchars($edit_strand['strand_name']) ?>" required>
    <button type="submit" name="update_strand" class="btn btn-green">Update</button>
    <a href="admin-manage_strands.php" class="btn btn-secondary">Cancel</a>
</form>
<?php endif; ?>
<table class="table table-bordered">
    <thead><tr><th>ID</th><th>Name</th><th>Action</th></tr></thead>
    <tbody>
        <?php foreach ($strands as $s): ?>
        <tr>
            <td><?= $s['id'] ?></td>
            <td><?= htmlspecialchars($s['strand_name']) ?></td>
            <td>
                <a href="?edit=<?= $s['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                <a href="?archive=<?= $s['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Archive?')">Archive</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
</main></div></div></body></html>