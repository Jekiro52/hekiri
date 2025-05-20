<?php
require 'common/check_login.php';
require 'common/check_role.php';
require_role('registrar');
include 'common/layout.php';
?>
<h2>Registrar Dashboard</h2>
<div class="row g-4">
    <div class="col-md-4">
        <div class="card text-bg-info mb-3 shadow">
            <div class="card-body">
                <h5 class="card-title">Enroll Student</h5>
                <p class="card-text">Assign a student to a strand, year, and semester.</p>
                <a href="registrar-enroll_student.php" class="btn btn-light">Go</a>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-bg-info mb-3 shadow">
            <div class="card-body">
                <h5 class="card-title">View Enrollments</h5>
                <p class="card-text">View/search all enrolled students, details, and subjects.</p>
                <a href="registrar-view_enrollments.php" class="btn btn-light">Go</a>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-bg-info mb-3 shadow">
            <div class="card-body">
                <h5 class="card-title">Assign Subject & Schedule</h5>
                <p class="card-text">Assign subjects and schedules to students.</p>
                <a href="registrar-assign_subjects.php" class="btn btn-light">Go</a>
            </div>
        </div>
    </div>
</div>
</main></div></div></body></html>