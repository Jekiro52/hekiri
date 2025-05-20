<?php
require 'common/check_login.php';
require 'common/check_role.php';
require_role('admin');
require 'db_connect.php';
include 'common/layout.php';

if (isset($_POST['add_yl'])) {
    $name = trim($_POST['year_level']);
    $pdo->prepare("INSERT INTO year_levels (year_level) VALUES (?)")->execute([$name]);
}

if (isset($_POST['update_yl'])) {
    $id = $_POST['edit_id'];
    $name = trim($_POST['edit_year_level']);
    $pdo->prepare("UPDATE year_levels SET year_level=? WHERE id=?")->execute([$name, $id]);
}

if (isset($_GET['archive'])) {
    $pdo->prepare("UPDATE year_levels SET archived=1 WHERE id=?")->execute([$_GET['archive']]);
}

$yls = $pdo->query("SELECT * FROM year_levels WHERE archived=0")->fetchAll();
$edit_id = $_GET['edit'] ?? null;
$edit_yl = null;
if ($edit_id) {
    $stmt = $pdo->prepare("SELECT * FROM year_levels WHERE id=?");
    $stmt->execute([$edit_id]);
    $edit_yl = $stmt->fetch();
}
?>
<h2>Manage Year Levels</h2>
<?php if (!$edit_yl): ?>
<form method="post" class="mb-3 d-flex gap-2">
    <input type="text" name="year_level" class="form-control" placeholder="Year Level" required>
    <button type="submit" name="add_yl" class="btn btn-green">Add</button>
</form>
<?php else: ?>
<form method="post" class="mb-3 d-flex gap-2">
    <input type="hidden" name="edit_id" value="<?= $edit_yl['id'] ?>">
    <input type="text" name="edit_year_level" class="form-control" value="<?= htmlspecialchars($edit_yl['year_level']) ?>" required>
    <button type="submit" name="update_yl" class="btn btn-green">Update</button>
    <a href="admin-manage_year_levels.php" class="btn btn-secondary">Cancel</a>
</form>
<?php endif; ?>
<table class="table table-bordered">
    <thead><tr><th>ID</th><th>Year Level</th><th>Action</th></tr></thead>
    <tbody>
        <?php foreach ($yls as $y): ?>
        <tr>
            <td><?= $y['id'] ?></td>
            <td><?= htmlspecialchars($y['year_level']) ?></td>
            <td>
                <a href="?edit=<?= $y['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                <a href="?archive=<?= $y['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Archive?')">Archive</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
</main></div></div></body></html>