<?php
require 'common/check_login.php';
require 'common/check_role.php';
require_role('admin');
require 'db_connect.php';
include 'common/layout.php';

$strands = $pdo->query("SELECT * FROM strands WHERE archived=0")->fetchAll();
$yls = $pdo->query("SELECT * FROM year_levels WHERE archived=0")->fetchAll();
$sems = $pdo->query("SELECT * FROM semesters WHERE archived=0")->fetchAll();

if (isset($_POST['add_subj'])) {
    $name = trim($_POST['subject_name']);
    $strand = $_POST['strand_id'];
    $yl = $_POST['year_level_id'];
    $sem = $_POST['semester_id'];
    $pdo->prepare("INSERT INTO subjects (subject_name, strand_id, year_level_id, semester_id) VALUES (?, ?, ?, ?)")->execute([$name, $strand, $yl, $sem]);
}

if (isset($_POST['update_subj'])) {
    $id = $_POST['edit_id'];
    $name = trim($_POST['edit_subject_name']);
    $strand = $_POST['edit_strand_id'];
    $yl = $_POST['edit_year_level_id'];
    $sem = $_POST['edit_semester_id'];
    $pdo->prepare("UPDATE subjects SET subject_name=?, strand_id=?, year_level_id=?, semester_id=? WHERE id=?")->execute([$name, $strand, $yl, $sem, $id]);
}

if (isset($_GET['archive'])) {
    $pdo->prepare("UPDATE subjects SET archived=1 WHERE id=?")->execute([$_GET['archive']]);
}

$subjects = $pdo->query("SELECT s.*, st.strand_name, yl.year_level, sem.semester_name FROM subjects s JOIN strands st ON s.strand_id=st.id JOIN year_levels yl ON s.year_level_id=yl.id JOIN semesters sem ON s.semester_id=sem.id WHERE s.archived=0")->fetchAll();

$edit_id = $_GET['edit'] ?? null;
$edit_subject = null;
if ($edit_id) {
    $stmt = $pdo->prepare("SELECT * FROM subjects WHERE id=?");
    $stmt->execute([$edit_id]);
    $edit_subject = $stmt->fetch();
}
?>
<h2>Manage Subjects</h2>
<?php if (!$edit_subject): ?>
<form method="post" class="mb-3 row g-2 align-items-end">
    <div class="col-md-3">
        <input type="text" name="subject_name" class="form-control" placeholder="Subject name" required>
    </div>
    <div class="col-md-2">
        <select name="strand_id" class="form-select" required>
            <option value="">Strand</option>
            <?php foreach($strands as $st): ?>
            <option value="<?=$st['id']?>"><?=htmlspecialchars($st['strand_name'])?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-md-2">
        <select name="year_level_id" class="form-select" required>
            <option value="">Year</option>
            <?php foreach($yls as $y): ?>
            <option value="<?=$y['id']?>"><?=htmlspecialchars($y['year_level'])?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-md-2">
        <select name="semester_id" class="form-select" required>
            <option value="">Semester</option>
            <?php foreach($sems as $sem): ?>
            <option value="<?=$sem['id']?>"><?=htmlspecialchars($sem['semester_name'])?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-md-2">
        <button type="submit" name="add_subj" class="btn btn-green">Add</button>
    </div>
</form>
<?php else: ?>
<form method="post" class="mb-3 row g-2 align-items-end">
    <input type="hidden" name="edit_id" value="<?= $edit_subject['id'] ?>">
    <div class="col-md-3">
        <input type="text" name="edit_subject_name" class="form-control" value="<?=htmlspecialchars($edit_subject['subject_name'])?>" required>
    </div>
    <div class="col-md-2">
        <select name="edit_strand_id" class="form-select" required>
            <option value="">Strand</option>
            <?php foreach($strands as $st): ?>
            <option value="<?=$st['id']?>" <?=$edit_subject['strand_id']==$st['id']?'selected':''?>><?=htmlspecialchars($st['strand_name'])?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-md-2">
        <select name="edit_year_level_id" class="form-select" required>
            <option value="">Year</option>
            <?php foreach($yls as $y): ?>
            <option value="<?=$y['id']?>" <?=$edit_subject['year_level_id']==$y['id']?'selected':''?>><?=htmlspecialchars($y['year_level'])?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-md-2">
        <select name="edit_semester_id" class="form-select" required>
            <option value="">Semester</option>
            <?php foreach($sems as $sem): ?>
            <option value="<?=$sem['id']?>" <?=$edit_subject['semester_id']==$sem['id']?'selected':''?>><?=htmlspecialchars($sem['semester_name'])?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-md-2">
        <button type="submit" name="update_subj" class="btn btn-green">Update</button>
        <a href="admin-manage_subjects.php" class="btn btn-secondary">Cancel</a>
    </div>
</form>
<?php endif; ?>

<table class="table table-bordered">
<thead>
<tr>
    <th>ID</th><th>Subject</th><th>Strand</th><th>Year</th><th>Semester</th><th>Action</th>
</tr>
</thead>
<tbody>
<?php foreach ($subjects as $s): ?>
<tr>
    <td><?=$s['id']?></td>
    <td><?=htmlspecialchars($s['subject_name'])?></td>
    <td><?=htmlspecialchars($s['strand_name'])?></td>
    <td><?=htmlspecialchars($s['year_level'])?></td>
    <td><?=htmlspecialchars($s['semester_name'])?></td>
    <td>
        <a href="?edit=<?=$s['id']?>" class="btn btn-sm btn-warning">Edit</a>
        <a href="?archive=<?=$s['id']?>" class="btn btn-sm btn-danger" onclick="return confirm('Archive?')">Archive</a>
    </td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
</main></div></div></body></html>