<?php
require 'common/check_login.php';
require 'common/check_role.php';
require_role('student');
require 'db_connect.php';
include 'common/layout.php';

$user_id = $_SESSION['user_id'];
$strands = $pdo->query("SELECT * FROM strands WHERE archived=0")->fetchAll();
$year_levels = $pdo->query("SELECT * FROM year_levels WHERE archived=0")->fetchAll();
$semesters = $pdo->query("SELECT * FROM semesters WHERE archived=0")->fetchAll();

$success = $error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['subject_assignment_ids'])) {
    $strand_id = $_POST['strand_id'];
    $year_level_id = $_POST['year_level_id'];
    $semester_id = $_POST['semester_id'];
    $subject_assignments = $_POST['subject_assignment_ids'];
    $pdo->beginTransaction();
    try {
        $stmt = $pdo->prepare("INSERT INTO enrollments (student_id, strand_id, year_level_id, semester_id, date_enrolled) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$user_id, $strand_id, $year_level_id, $semester_id, date('Y-m-d')]);
        $enrollment_id = $pdo->lastInsertId();
        $stmt2 = $pdo->prepare("INSERT INTO student_subjects (enrollment_id, subject_assignment_id) VALUES (?, ?)");
        foreach ($subject_assignments as $assign_id) {
            $stmt2->execute([$enrollment_id, $assign_id]);
        }
        $pdo->commit();
        $success = "Enrollment submitted!";
    } catch (Exception $e) {
        $pdo->rollBack();
        $error = "Error: ".htmlspecialchars($e->getMessage());
    }
}
?>
<h2>Student Enrollment</h2>
<?php if ($success): ?><div class="alert alert-success"><?=$success?></div><?php endif; ?>
<?php if ($error): ?><div class="alert alert-danger"><?=$error?></div><?php endif; ?>
<form method="post" id="enrollForm">
  <div class="row g-3 mb-3">
    <div class="col-md-4">
      <label class="form-label">Strand</label>
      <select name="strand_id" id="strand_id" class="form-select" required>
        <option value="">Select...</option>
        <?php foreach ($strands as $s): ?>
        <option value="<?=$s['id']?>"><?=htmlspecialchars($s['strand_name'])?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="col-md-4">
      <label class="form-label">Year Level</label>
      <select name="year_level_id" id="year_level_id" class="form-select" required>
        <option value="">Select...</option>
        <?php foreach ($year_levels as $y): ?>
        <option value="<?=$y['id']?>"><?=htmlspecialchars($y['year_level'])?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="col-md-4">
      <label class="form-label">Semester</label>
      <select name="semester_id" id="semester_id" class="form-select" required>
        <option value="">Select...</option>
        <?php foreach ($semesters as $sem): ?>
        <option value="<?=$sem['id']?>"><?=htmlspecialchars($sem['semester_name'])?></option>
        <?php endforeach; ?>
      </select>
    </div>
  </div>
  <div id="subjectsArea" class="mb-3"></div>
  <button type="submit" class="btn btn-green px-4" id="submitBtn" style="display:none;">Submit Enrollment</button>
</form>
<script>
function fetchSubjects() {
    let strand = document.getElementById('strand_id').value;
    let year = document.getElementById('year_level_id').value;
    let sem = document.getElementById('semester_id').value;
    let area = document.getElementById('subjectsArea');
    let btn = document.getElementById('submitBtn');
    if (strand && year && sem) {
        fetch('student-fetch_subjects.php?strand_id='+strand+'&year_level_id='+year+'&semester_id='+sem)
        .then(response => response.text())
        .then(data => {
            area.innerHTML = data;
            btn.style.display = 'block';
        });
    } else {
        area.innerHTML = '';
        btn.style.display = 'none';
    }
}
document.getElementById('strand_id').addEventListener('change', fetchSubjects);
document.getElementById('year_level_id').addEventListener('change', fetchSubjects);
document.getElementById('semester_id').addEventListener('change', fetchSubjects);
</script>
</main></div></div></body></html>