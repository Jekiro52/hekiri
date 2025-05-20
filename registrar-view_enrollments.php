<?php
require 'common/check_login.php';
require 'common/check_role.php';
require_role('registrar');
require 'db_connect.php';
include 'common/layout.php';

// ARCHIVE enrollment (soft delete)
if (isset($_GET['archive'])) {
    $id = $_GET['archive'];
    $pdo->prepare("UPDATE enrollments SET archived=1 WHERE id=?")->execute([$id]);
    echo '<div class="alert alert-success">Enrollment archived.</div>';
}

// VIEW enrollment
$view_id = $_GET['view'] ?? null;
$view = null;
if ($view_id) {
    $stmt = $pdo->prepare("SELECT e.*, u.fullname, u.username, s.strand_name, yl.year_level, sem.semester_name
                           FROM enrollments e
                           JOIN users u ON e.student_id = u.id
                           JOIN strands s ON e.strand_id = s.id
                           JOIN year_levels yl ON e.year_level_id = yl.id
                           JOIN semesters sem ON e.semester_id = sem.id
                           WHERE e.id=?");
    $stmt->execute([$view_id]);
    $view = $stmt->fetch();
}

// EDIT enrollment
$edit_id = $_GET['edit'] ?? null;
$edit = null;
if ($edit_id) {
    $stmt = $pdo->prepare("SELECT * FROM enrollments WHERE id=?");
    $stmt->execute([$edit_id]);
    $edit = $stmt->fetch();
}
$strands = $pdo->query("SELECT * FROM strands WHERE archived=0")->fetchAll();
$yls = $pdo->query("SELECT * FROM year_levels WHERE archived=0")->fetchAll();
$sems = $pdo->query("SELECT * FROM semesters WHERE archived=0")->fetchAll();

if (isset($_POST['update_enrollment'])) {
    $id = $_POST['enrollment_id'];
    $strand_id = $_POST['strand_id'];
    $year_level_id = $_POST['year_level_id'];
    $semester_id = $_POST['semester_id'];
    $pdo->prepare("UPDATE enrollments SET strand_id=?, year_level_id=?, semester_id=? WHERE id=?")
        ->execute([$strand_id, $year_level_id, $semester_id, $id]);
    echo '<div class="alert alert-success">Enrollment updated.</div>';
    $edit = null;
    $edit_id = null;
}

// SEARCH and display enrollments
$search = $_GET['search'] ?? '';
$where = $search ? "AND u.fullname LIKE ?" : '';
$params = $search ? ["%$search%"] : [];

$sql = "SELECT e.id AS enrollment_id, u.id AS student_id, u.fullname, s.strand_name, yl.year_level, sem.semester_name, e.date_enrolled
        FROM enrollments e
        JOIN users u ON e.student_id = u.id
        JOIN strands s ON e.strand_id = s.id
        JOIN year_levels yl ON e.year_level_id = yl.id
        JOIN semesters sem ON e.semester_id = sem.id
        WHERE u.archived=0 AND e.archived=0 $where
        ORDER BY e.date_enrolled DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$enrollments = $stmt->fetchAll();
?>
<h3>Student Enrollments</h3>
<form class="mb-3">
    <input type="text" name="search" value="<?=htmlspecialchars($search)?>" placeholder="Search student..." class="form-control d-inline" style="width:300px;display:inline-block">
    <button class="btn btn-outline-success btn-sm">Search</button>
</form>

<?php if ($view): ?>
<!-- View Enrollment Details -->
<div class="card mb-3">
    <div class="card-header">View Enrollment Details</div>
    <div class="card-body">
        <table class="table table-borderless">
            <tr><th>Student Name:</th><td><?=htmlspecialchars($view['fullname'])?></td></tr>
            <tr><th>Username:</th><td><?=htmlspecialchars($view['username'])?></td></tr>
            <tr><th>Strand:</th><td><?=htmlspecialchars($view['strand_name'])?></td></tr>
            <tr><th>Year Level:</th><td><?=htmlspecialchars($view['year_level'])?></td></tr>
            <tr><th>Semester:</th><td><?=htmlspecialchars($view['semester_name'])?></td></tr>
            <tr><th>Date Enrolled:</th><td><?=htmlspecialchars($view['date_enrolled'])?></td></tr>
        </table>
        <a href="registrar-view_enrollments.php" class="btn btn-secondary">Close</a>
    </div>
</div>
<?php endif; ?>

<?php if ($edit): ?>
<!-- Edit Enrollment Form -->
<div class="card mb-3">
    <div class="card-header">Edit Enrollment for Student ID: <?=$edit['student_id']?></div>
    <div class="card-body">
        <form method="post" class="row g-2 align-items-end">
            <input type="hidden" name="enrollment_id" value="<?=$edit['id']?>">
            <div class="col-md-3">
                <label>Strand</label>
                <select name="strand_id" class="form-select" required>
                    <?php foreach ($strands as $s): ?>
                    <option value="<?=$s['id']?>" <?=$edit['strand_id']==$s['id']?'selected':''?>><?=htmlspecialchars($s['strand_name'])?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
                <label>Year Level</label>
                <select name="year_level_id" class="form-select" required>
                    <?php foreach ($yls as $y): ?>
                    <option value="<?=$y['id']?>" <?=$edit['year_level_id']==$y['id']?'selected':''?>><?=htmlspecialchars($y['year_level'])?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
                <label>Semester</label>
                <select name="semester_id" class="form-select" required>
                    <?php foreach ($sems as $sem): ?>
                    <option value="<?=$sem['id']?>" <?=$edit['semester_id']==$sem['id']?'selected':''?>><?=htmlspecialchars($sem['semester_name'])?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" name="update_enrollment" class="btn btn-success">Update</button>
                <a href="registrar-view_enrollments.php" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
<?php endif; ?>

<table class="table table-bordered table-sm">
    <thead>
        <tr>
            <th>#</th>
            <th>Student</th>
            <th>Strand</th>
            <th>Year</th>
            <th>Semester</th>
            <th>Date</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($enrollments as $row): ?>
        <tr>
            <td><?= $row['enrollment_id'] ?></td>
            <td><?= htmlspecialchars($row['fullname']) ?></td>
            <td><?= htmlspecialchars($row['strand_name']) ?></td>
            <td><?= htmlspecialchars($row['year_level']) ?></td>
            <td><?= htmlspecialchars($row['semester_name']) ?></td>
            <td><?= htmlspecialchars($row['date_enrolled']) ?></td>
            <td>
                <a href="registrar-view_enrollments.php?view=<?=$row['enrollment_id']?>" class="btn btn-info btn-sm">View</a>
                <a href="registrar-view_enrollments.php?edit=<?=$row['enrollment_id']?>" class="btn btn-warning btn-sm">Edit</a>
                <a href="registrar-view_enrollments.php?archive=<?=$row['enrollment_id']?>" class="btn btn-danger btn-sm" onclick="return confirm('Archive this enrollment?')">Archive</a>
            </td>
        </tr>
    <?php endforeach ?>
    </tbody>
</table>
</main></div></div></body></html>