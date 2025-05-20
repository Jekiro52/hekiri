<?php
require 'common/check_login.php';
require 'common/check_role.php';
require_role('teacher');
include 'common/layout.php';
?>
<h2>Teacher Dashboard</h2>
<p>Welcome, <?=htmlspecialchars($_SESSION['fullname'])?> (Teacher)!</p>
<ul>
    <li><a href="teacher-view_schedule.php">My Schedule</a></li>
</ul>
</main></div></div></body></html>