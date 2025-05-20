<?php
require 'common/check_login.php';
require 'common/check_role.php';
require_role('admin');
require 'db_connect.php';
include 'common/layout.php';

// Fetch teachers
$teachers = $pdo->query("SELECT t.id, u.fullname FROM teachers t JOIN users u ON t.user_id=u.id WHERE u.archived=0")->fetchAll();
// Fetch all subjects
$subjects = $pdo->query("SELECT s.*, st.strand_name, yl.year_level, sem.semester_name FROM subjects s
    JOIN strands st ON s.strand_id=st.id
    JOIN year_levels yl ON s.year_level_id=yl.id
    JOIN semesters sem ON s.semester_id=sem.id
    WHERE s.archived=0
    ORDER BY st.strand_name, yl.year_level, sem.semester_name, s.subject_name")->fetchAll();

// ADD
if (isset($_POST['add_assignment'])) {
    $subject_id = $_POST['subject_id'];
    $teacher_id = $_POST['teacher_id'];
    $day = $_POST['day_of_week'];
    $start = $_POST['time_start'];
    $end = $_POST['time_end'];
    $room = $_POST['room'];
    $pdo->prepare("INSERT INTO subject_assignments (subject_id, teacher_id, day_of_week, time_start, time_end, room) VALUES (?, ?, ?, ?, ?, ?)")
        ->execute([$subject_id, $teacher_id, $day, $start, $end, $room]);
}

// UPDATE
if (isset($_POST['update_assignment'])) {
    $id = $_POST['edit_id'];
    $subject_id = $_POST['edit_subject_id'];
    $teacher_id = $_POST['edit_teacher_id'];
    $day = $_POST['edit_day_of_week'];
    $start = $_POST['edit_time_start'];
    $end = $_POST['edit_time_end'];
    $room = $_POST['edit_room'];
    $pdo->prepare("UPDATE subject_assignments SET subject_id=?, teacher_id=?, day_of_week=?, time_start=?, time_end=?, room=? WHERE id=?")
        ->execute([$subject_id, $teacher_id, $day, $start, $end, $room, $id]);
}

// DELETE (Archive)
if (isset($_GET['archive'])) {
    $id = $_GET['archive'];
    // Optionally add an 'archived' column to subject_assignments if you want soft-delete
    // $pdo->prepare("UPDATE subject_assignments SET archived=1 WHERE id=?")->execute([$id]);
    // For hard delete:
    $pdo->prepare("DELETE FROM subject_assignments WHERE id=?")->execute([$id]);
}

// Get all assignments
$assignments = $pdo->query(
    "SELECT sa.*, s.subject_name, st.strand_name, yl.year_level, sem.semester_name, u.fullname as teacher
    FROM subject_assignments sa
    JOIN subjects s ON sa.subject_id=s.id
    JOIN strands st ON s.strand_id=st.id
    JOIN year_levels yl ON s.year_level_id=yl.id
    JOIN semesters sem ON s.semester_id=sem.id
    JOIN teachers t ON sa.teacher_id=t.id
    JOIN users u ON t.user_id=u.id
    ORDER BY st.strand_name, yl.year_level, sem.semester_name, s.subject_name, sa.day_of_week, sa.time_start"
)->fetchAll();

$edit_id = $_GET['edit'] ?? null;
$edit = null;
if ($edit_id) {
    $stmt = $pdo->prepare("SELECT * FROM subject_assignments WHERE id=?");
    $stmt->execute([$edit_id]);
    $edit = $stmt->fetch();
}
?>
<h2>Assign Teacher and Schedule to Subject</h2>
<?php if (!$edit): ?>
<form method="post" class="mb-3 row g-2 align-items-end">
    <div class="col-md-3">
        <select name="subject_id" class="form-select" required>
            <option value="">Subject</option>
            <?php foreach ($subjects as $s): ?>
            <option value="<?=$s['id']?>"><?=htmlspecialchars($s['subject_name'].' / '.$s['strand_name'].' / '.$s['year_level'].' / '.$s['semester_name'])?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-md-2">
        <select name="teacher_id" class="form-select" required>
            <option value="">Teacher</option>
            <?php foreach ($teachers as $t): ?>
            <option value="<?=$t['id']?>"><?=htmlspecialchars($t['fullname'])?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-md-2">
        <select name="day_of_week" class="form-select" required>
            <option value="">Day</option>
            <option>Monday</option><option>Tuesday</option><option>Wednesday</option>
            <option>Thursday</option><option>Friday</option>
        </select>
    </div>
    <div class="col-md-1">
        <input type="time" name="time_start" class="form-control" required>
    </div>
    <div class="col-md-1">
        <input type="time" name="time_end" class="form-control" required>
    </div>
    <div class="col-md-2">
        <input type="text" name="room" class="form-control" placeholder="Room" required>
    </div>
    <div class="col-md-1">
        <button type="submit" name="add_assignment" class="btn btn-green">Add</button>
    </div>
</form>
<?php else: ?>
<form method="post" class="mb-3 row g-2 align-items-end">
    <input type="hidden" name="edit_id" value="<?=$edit['id']?>">
    <div class="col-md-3">
        <select name="edit_subject_id" class="form-select" required>
            <option value="">Subject</option>
            <?php foreach ($subjects as $s): ?>
            <option value="<?=$s['id']?>" <?=$edit['subject_id']==$s['id']?'selected':''?>><?=htmlspecialchars($s['subject_name'].' / '.$s['strand_name'].' / '.$s['year_level'].' / '.$s['semester_name'])?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-md-2">
        <select name="edit_teacher_id" class="form-select" required>
            <option value="">Teacher</option>
            <?php foreach ($teachers as $t): ?>
            <option value="<?=$t['id']?>" <?=$edit['teacher_id']==$t['id']?'selected':''?>><?=htmlspecialchars($t['fullname'])?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-md-2">
        <select name="edit_day_of_week" class="form-select" required>
            <option value="">Day</option>
            <?php foreach (["Monday","Tuesday","Wednesday","Thursday","Friday"] as $d): ?>
            <option <?=$edit['day_of_week']==$d?'selected':''?>><?=$d?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-md-1">
        <input type="time" name="edit_time_start" class="form-control" value="<?=htmlspecialchars($edit['time_start'])?>" required>
    </div>
    <div class="col-md-1">
        <input type="time" name="edit_time_end" class="form-control" value="<?=htmlspecialchars($edit['time_end'])?>" required>
    </div>
    <div class="col-md-2">
        <input type="text" name="edit_room" class="form-control" value="<?=htmlspecialchars($edit['room'])?>" required>
    </div>
    <div class="col-md-1">
        <button type="submit" name="update_assignment" class="btn btn-green">Update</button>
        <a href="admin-assign_subjects.php" class="btn btn-secondary">Cancel</a>
    </div>
</form>
<?php endif; ?>

<table class="table table-bordered table-responsive">
<thead>
<tr>
    <th>Subject</th>
    <th>Strand</th>
    <th>Year</th>
    <th>Semester</th>
    <th>Teacher</th>
    <th>Day</th>
    <th>Start</th>
    <th>End</th>
    <th>Room</th>
    <th>Action</th>
</tr>
</thead>
<tbody>
<?php foreach ($assignments as $a): ?>
<tr>
    <td><?=htmlspecialchars($a['subject_name'])?></td>
    <td><?=htmlspecialchars($a['strand_name'])?></td>
    <td><?=htmlspecialchars($a['year_level'])?></td>
    <td><?=htmlspecialchars($a['semester_name'])?></td>
    <td><?=htmlspecialchars($a['teacher'])?></td>
    <td><?=htmlspecialchars($a['day_of_week'])?></td>
    <td><?=htmlspecialchars(substr($a['time_start'],0,5))?></td>
    <td><?=htmlspecialchars(substr($a['time_end'],0,5))?></td>
    <td><?=htmlspecialchars($a['room'])?></td>
    <td>
        <a href="?edit=<?=$a['id']?>" class="btn btn-sm btn-warning">Edit</a>
        <a href="?archive=<?=$a['id']?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this assignment?')">Delete</a>
    </td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
</main></div></div></body></html>