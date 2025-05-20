<?php
require 'common/check_login.php';
require 'common/check_role.php';
require_role('admin');
include 'common/layout.php';
?>
<h2>Admin Dashboard</h2>
<div class="row g-4">
    <div class="col-md-4">
        <div class="card text-bg-success mb-3">
            <div class="card-body">
                <h5 class="card-title">Manage Strands</h5>
                <p class="card-text">Add, update, or archive strands.</p>
                <a href="admin-manage_strands.php" class="btn btn-light">Go</a>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-bg-success mb-3">
            <div class="card-body">
                <h5 class="card-title">Manage Year Levels</h5>
                <p class="card-text">Add, update, or archive year levels.</p>
                <a href="admin-manage_year_levels.php" class="btn btn-light">Go</a>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-bg-success mb-3">
            <div class="card-body">
                <h5 class="card-title">Manage Semesters</h5>
                <p class="card-text">Add, update, or archive semesters.</p>
                <a href="admin-manage_semesters.php" class="btn btn-light">Go</a>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-bg-success mb-3">
            <div class="card-body">
                <h5 class="card-title">Manage Subjects</h5>
                <p class="card-text">Add, update, or archive subjects.</p>
                <a href="admin-manage_subjects.php" class="btn btn-light">Go</a>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-bg-success mb-3">
            <div class="card-body">
                <h5 class="card-title">Manage Users</h5>
                <p class="card-text">Add, update, or archive users.</p>
                <a href="admin-manage_users.php" class="btn btn-light">Go</a>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-bg-success mb-3">
            <div class="card-body">
                <h5 class="card-title">Assign Teacher/Schedule</h5>
                <p class="card-text">Assign teachers and schedules to subjects.</p>
                <a href="admin-assign_subjects.php" class="btn btn-light">Go</a>
            </div>
        </div>
    </div>
</div>
</main></div></div></body></html>