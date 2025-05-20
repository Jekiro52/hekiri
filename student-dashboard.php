<?php
require 'common/check_login.php';
require 'common/check_role.php';
require_role('student');
include 'common/layout.php';
?>
<h2>Student Dashboard</h2>
<p>Welcome, <?=htmlspecialchars($_SESSION['fullname'])?> (Student)!</p>
<ul>
    <li><a href="student-enroll.php">Enroll</a></li>
    <li><a href="student-view_subjects.php">My Subjects</a></li>
</ul>
</main></div></div></body></html>