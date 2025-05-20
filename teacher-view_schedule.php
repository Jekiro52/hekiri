<?php
require 'common/check_login.php';
require 'common/check_role.php';
require_role('teacher');
require 'db_connect.php';
include 'common/layout.php';

$teacher_id = $pdo->query("SELECT id FROM teachers WHERE user_id = ?", [$_SESSION['user_id']])->fetchColumn();
$scheds = $pdo->prepare(
    "SELECT sa.*, sub.subject_name, st.strand_name, y.year_level, sem.semester_name
    FROM subject_assignments sa
    JOIN subjects sub ON sa.subject_id=sub.id
    JOIN strands st ON sub.strand_id=st.id
    JOIN year_levels y ON sub.year_level_id=y.id
    JOIN semesters sem ON sub.semester_id=sem.id
    WHERE sa.teacher_id = ?
    ORDER BY sa.day_of_week, sa.time_start"
);
$scheds->execute([$teacher_id]);
$scheds = $scheds->fetchAll();
?>
<h2>My Class Schedule</h2>
<table class="table table-bordered">
<thead>
<tr>
    <th>Subject</th><th>Strand</th><th>Year</th><th>Semester</th><th>Day</th><th>Start</th><th>End</th><th>Room</th>
</tr>
</thead>
<tbody>
<?php foreach ($scheds as $s): ?>
<tr>
    <td><?=htmlspecialchars($s['subject_name'])?></td>
    <td><?=htmlspecialchars($s['strand_name'])?></td>
    <td><?=htmlspecialchars($s['year_level'])?></td>
    <td><?=htmlspecialchars($s['semester_name'])?></td>
    <td><?=htmlspecialchars($s['day_of_week'])?></td>
    <td><?=htmlspecialchars(substr($s['time_start'],0,5))?></td>
    <td><?=htmlspecialchars(substr($s['time_end'],0,5))?></td>
    <td><?=htmlspecialchars($s['room'])?></td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
</main></div></div></body></html>