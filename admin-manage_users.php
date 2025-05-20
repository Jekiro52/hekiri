<?php
require 'common/check_login.php';
require 'common/check_role.php';
require_role('admin');
require 'db_connect.php';
include 'common/layout.php';

if (isset($_POST['add_user'])) {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $role = $_POST['role'];
    $fullname = $_POST['fullname'];
    $pdo->prepare("INSERT INTO users (username, password, role, fullname) VALUES (?, ?, ?, ?)")->execute([$username, $password, $role, $fullname]);
    if ($role === 'teacher') {
        $user_id = $pdo->lastInsertId();
        $pdo->prepare("INSERT INTO teachers (user_id) VALUES (?)")->execute([$user_id]);
    }
}

if (isset($_POST['update_user'])) {
    $id = $_POST['edit_id'];
    $username = trim($_POST['edit_username']);
    $password = $_POST['edit_password'];
    $role = $_POST['edit_role'];
    $fullname = $_POST['edit_fullname'];
    $pdo->prepare("UPDATE users SET username=?, password=?, role=?, fullname=? WHERE id=?")->execute([$username, $password, $role, $fullname, $id]);
}

if (isset($_GET['archive'])) {
    $pdo->prepare("UPDATE users SET archived=1 WHERE id=?")->execute([$_GET['archive']]);
}

$users = $pdo->query("SELECT * FROM users WHERE archived=0")->fetchAll();
$edit_id = $_GET['edit'] ?? null;
$edit_user = null;
if ($edit_id) {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id=?");
    $stmt->execute([$edit_id]);
    $edit_user = $stmt->fetch();
}
?>
<h2>Manage Users</h2>
<?php if (!$edit_user): ?>
<form method="post" class="mb-3 row g-2 align-items-end">
    <div class="col-md-2">
        <input type="text" name="username" class="form-control" placeholder="Username" required>
    </div>
    <div class="col-md-2">
        <input type="password" name="password" class="form-control" placeholder="Password" required>
    </div>
    <div class="col-md-2">
        <select name="role" class="form-select" required>
            <option value="">Role</option>
            <option value="admin">Admin</option>
            <option value="registrar">Registrar</option>
            <option value="teacher">Teacher</option>
            <option value="student">Student</option>
        </select>
    </div>
    <div class="col-md-3">
        <input type="text" name="fullname" class="form-control" placeholder="Fullname" required>
    </div>
    <div class="col-md-2">
        <button type="submit" name="add_user" class="btn btn-green">Add</button>
    </div>
</form>
<?php else: ?>
<form method="post" class="mb-3 row g-2 align-items-end">
    <input type="hidden" name="edit_id" value="<?= $edit_user['id'] ?>">
    <div class="col-md-2">
        <input type="text" name="edit_username" class="form-control" value="<?=htmlspecialchars($edit_user['username'])?>" required>
    </div>
    <div class="col-md-2">
        <input type="text" name="edit_password" class="form-control" value="<?=htmlspecialchars($edit_user['password'])?>" required>
    </div>
    <div class="col-md-2">
        <select name="edit_role" class="form-select" required>
            <option value="">Role</option>
            <option value="admin" <?=$edit_user['role']=='admin'?'selected':''?>>Admin</option>
            <option value="registrar" <?=$edit_user['role']=='registrar'?'selected':''?>>Registrar</option>
            <option value="teacher" <?=$edit_user['role']=='teacher'?'selected':''?>>Teacher</option>
            <option value="student" <?=$edit_user['role']=='student'?'selected':''?>>Student</option>
        </select>
    </div>
    <div class="col-md-3">
        <input type="text" name="edit_fullname" class="form-control" value="<?=htmlspecialchars($edit_user['fullname'])?>" required>
    </div>
    <div class="col-md-2">
        <button type="submit" name="update_user" class="btn btn-green">Update</button>
        <a href="admin-manage_users.php" class="btn btn-secondary">Cancel</a>
    </div>
</form>
<?php endif; ?>
<table class="table table-bordered">
    <thead><tr><th>ID</th><th>Username</th><th>Role</th><th>Fullname</th><th>Action</th></tr></thead>
    <tbody>
        <?php foreach ($users as $u): ?>
        <tr>
            <td><?= $u['id'] ?></td>
            <td><?= htmlspecialchars($u['username']) ?></td>
            <td><?= htmlspecialchars($u['role']) ?></td>
            <td><?= htmlspecialchars($u['fullname']) ?></td>
            <td>
                <a href="?edit=<?= $u['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                <a href="?archive=<?= $u['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Archive?')">Archive</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
</main></div></div></body></html>