<?php
require 'common/check_login.php';
require 'common/check_role.php';
require_role('registrar');
require 'db_connect.php';
include 'common/layout.php';

$students = $pdo->query("SELECT * FROM users WHERE role='student' AND archived=0")->fetchAll();
$strands = $pdo->query("SELECT * FROM strands WHERE archived=0")->fetchAll();
$yls = $pdo->query("SELECT * FROM year_levels WHERE archived=0")->fetchAll();
$sems = $pdo->query("SELECT * FROM semesters WHERE archived=0")->fetchAll();

if (isset($_POST['enroll'])) {
    $student_id = $_POST['student_id'];
    $strand_id = $_POST['strand_id'];
    $year_level_id = $_POST['year_level_id'];
    $semester_id = $_POST['semester_id'];
    $date = date('Y-m-d');
    $pdo->prepare("INSERT INTO enrollments (student_id, strand_id, year_level_id, semester_id, date_enrolled) VALUES (?, ?, ?, ?, ?)")
        ->execute([$student_id, $strand_id, $year_level_id, $semester_id, $date]);
    echo '<div class="alert alert-success">Student enrolled!</div>';
}
?>
<h3>Enroll Student</h3>
<form method="post" class="row g-2 mb-4">
    <div class="col-md-3">
        <select name="student_id" class="form-select" required>
            <option value="">Select Student</option>
            <?php foreach ($students as $st): ?>
            <option value="<?=$st['id']?>"><?=htmlspecialchars($st['fullname'])?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-md-2">
        <select name="strand_id" class="form-select" required>
            <option value="">Strand</option>
            <?php foreach ($strands as $s): ?>
            <option value="<?=$s['id']?>"><?=htmlspecialchars($s['strand_name'])?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-md-2">
        <select name="year_level_id" class="form-select" required>
            <option value="">Year Level</option>
            <?php foreach ($yls as $y): ?>
            <option value="<?=$y['id']?>"><?=htmlspecialchars($y['year_level'])?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-md-2">
        <select name="semester_id" class="form-select" required>
            <option value="">Semester</option>
            <?php foreach ($sems as $sem): ?>
            <option value="<?=$sem['id']?>"><?=htmlspecialchars($sem['semester_name'])?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-md-2">
        <button type="submit" name="enroll" class="btn btn-green">Enroll</button>
    </div>
</form>
</main></div></div></body></html>