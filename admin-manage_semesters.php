<?php
require 'common/check_login.php';
require 'common/check_role.php';
require_role('admin');
require 'db_connect.php';
include 'common/layout.php';

if (isset($_POST['add_sem'])) {
    $name = trim($_POST['semester_name']);
    $pdo->prepare("INSERT INTO semesters (semester_name) VALUES (?)")->execute([$name]);
}

if (isset($_POST['update_sem'])) {
    $id = $_POST['edit_id'];
    $name = trim($_POST['edit_semester_name']);
    $pdo->prepare("UPDATE semesters SET semester_name=? WHERE id=?")->execute([$name, $id]);
}

if (isset($_GET['archive'])) {
    $pdo->prepare("UPDATE semesters SET archived=1 WHERE id=?")->execute([$_GET['archive']]);
}

$sems = $pdo->query("SELECT * FROM semesters WHERE archived=0")->fetchAll();
$edit_id = $_GET['edit'] ?? null;
$edit_sem = null;
if ($edit_id) {
    $stmt = $pdo->prepare("SELECT * FROM semesters WHERE id=?");
    $stmt->execute([$edit_id]);
    $edit_sem = $stmt->fetch();
}
?>
<h2>Manage Semesters</h2>
<?php if (!$edit_sem): ?>
<form method="post" class="mb-3 d-flex gap-2">
    <input type="text" name="semester_name" class="form-control" placeholder="Semester (e.g. 1st, 2nd)" required>
    <button type="submit" name="add_sem" class="btn btn-green">Add</button>
</form>
<?php else: ?>
<form method="post" class="mb-3 d-flex gap-2">
    <input type="hidden" name="edit_id" value="<?= $edit_sem['id'] ?>">
    <input type="text" name="edit_semester_name" class="form-control" value="<?= htmlspecialchars($edit_sem['semester_name']) ?>" required>
    <button type="submit" name="update_sem" class="btn btn-green">Update</button>
    <a href="admin-manage_semesters.php" class="btn btn-secondary">Cancel</a>
</form>
<?php endif; ?>
<table class="table table-bordered">
    <thead><tr><th>ID</th><th>Semester</th><th>Action</th></tr></thead>
    <tbody>
        <?php foreach ($sems as $s): ?>
        <tr>
            <td><?= $s['id'] ?></td>
            <td><?= htmlspecialchars($s['semester_name']) ?></td>
            <td>
                <a href="?edit=<?= $s['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                <a href="?archive=<?= $s['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Archive?')">Archive</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
</main></div></div></body></html>