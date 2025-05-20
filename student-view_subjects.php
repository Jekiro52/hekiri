<?php
require 'common/check_login.php';
require 'common/check_role.php';
require_role('student');
require 'db_connect.php';
include 'common/layout.php';

$user_id = $_SESSION['user_id'];
$enrollments = $pdo->prepare("SELECT e.*, s.strand_name, y.year_level, sem.semester_name 
FROM enrollments e
JOIN strands s ON e.strand_id=s.id
JOIN year_levels y ON e.year_level_id=y.id
JOIN semesters sem ON e.semester_id=sem.id
WHERE e.student_id=? ORDER BY e.date_enrolled DESC");
$enrollments->execute([$user_id]);
$enrollments = $enrollments->fetchAll();
?>
<h2>My Enrolled Subjects</h2>
<?php foreach ($enrollments as $en): ?>
<div class="card mb-3">
<div class="card-header">
    <?=htmlspecialchars($en['strand_name'])?> - <?=htmlspecialchars($en['year_level'])?> - <?=htmlspecialchars($en['semester_name'])?> (<?=htmlspecialchars($en['date_enrolled'])?>)
</div>
<div class="card-body">
<?php
$subs = $pdo->prepare("SELECT sa.day_of_week, sa.time_start, sa.time_end, sa.room, sub.subject_name, u.fullname as teacher
FROM student_subjects ss
JOIN subject_assignments sa ON ss.subject_assignment_id=sa.id
JOIN subjects sub ON sa.subject_id=sub.id
JOIN teachers t ON sa.teacher_id=t.id
JOIN users u ON t.user_id=u.id
WHERE ss.enrollment_id=?");
$subs->execute([$en['id']]);
$subs = $subs->fetchAll();
if ($subs) {
    echo "<table class='table table-bordered'><thead><tr><th>Subject</th><th>Teacher</th><th>Schedule</th><th>Room</th></tr></thead><tbody>";
    foreach ($subs as $s) {
        echo "<tr>
            <td>".htmlspecialchars($s['subject_name'])."</td>
            <td>".htmlspecialchars($s['teacher'])."</td>
            <td>".htmlspecialchars($s['day_of_week']).' '.substr($s['time_start'],0,5).' - '.substr($s['time_end'],0,5)."</td>
            <td>".htmlspecialchars($s['room'])."</td>
        </tr>";
    }
    echo "</tbody></table>";
} else {
    echo "<div class='alert alert-warning'>No subjects for this enrollment.</div>";
}
?>
</div>
</div>
<?php endforeach; ?>
</main></div></div></body></html>