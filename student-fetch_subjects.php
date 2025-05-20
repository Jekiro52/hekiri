<?php
require 'db_connect.php';
$strand_id = $_GET['strand_id'] ?? '';
$year_level_id = $_GET['year_level_id'] ?? '';
$semester_id = $_GET['semester_id'] ?? '';
if ($strand_id && $year_level_id && $semester_id) {
    $stmt = $pdo->prepare("SELECT sa.id as assign_id, sub.subject_name, u.fullname as teacher, sa.day_of_week, sa.time_start, sa.time_end, sa.room
        FROM subject_assignments sa
        JOIN subjects sub ON sa.subject_id=sub.id
        JOIN teachers t ON sa.teacher_id=t.id
        JOIN users u ON t.user_id=u.id
        WHERE sub.strand_id=? AND sub.year_level_id=? AND sub.semester_id=? AND sub.archived=0");
    $stmt->execute([$strand_id, $year_level_id, $semester_id]);
    $subjects = $stmt->fetchAll();
    if ($subjects) {
        echo '<div class="card p-3" style="background:#f7fff7;border:1px solid #43cea2;">';
        echo '<div class="mb-2 fw-bold" style="color:#219150;">Available Subjects:</div>';
        echo '<div class="table-responsive"><table class="table">';
        echo '<thead><tr><th></th><th>Subject</th><th>Teacher</th><th>Schedule</th><th>Room</th></tr></thead><tbody>';
        foreach ($subjects as $s) {
            echo '<tr>
                <td><input type="checkbox" name="subject_assignment_ids[]" value="'.$s['assign_id'].'" class="form-check-input" required></td>
                <td>'.htmlspecialchars($s['subject_name']).'</td>
                <td>'.htmlspecialchars($s['teacher']).'</td>
                <td>'.htmlspecialchars($s['day_of_week']).' '.substr($s['time_start'],0,5).' - '.substr($s['time_end'],0,5).'</td>
                <td>'.htmlspecialchars($s['room']).'</td>
            </tr>';
        }
        echo '</tbody></table></div></div>';
    } else {
        echo '<div class="alert alert-warning">No subjects found for this selection.</div>';
    }
}
?>