<?php
if (session_status() == PHP_SESSION_NONE) session_start();
$role = $_SESSION['role'] ?? '';
$fullname = $_SESSION['fullname'] ?? '';
function active_nav($file) { return (basename($_SERVER['PHP_SELF']) == $file) ? 'active' : ''; }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>SHS Enrollment System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #e6f2ec; }
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(180deg, #43cea2 0%, #219150 100%);
            color: #fff;
            padding-top: 30px;
        }
        .sidebar .nav-link {
            color: #fff;
            font-weight: 500;
            border-radius: 8px;
            margin-bottom: 4px;
        }
        .sidebar .nav-link.active, .sidebar .nav-link:hover {
            background: rgba(255,255,255,0.20);
            color: #fff !important;
        }
        .sidebar .sidebar-title {
            font-size: 1.3rem;
            font-weight: 700;
            margin-bottom: 2rem;
            color: #fff;
            letter-spacing: 1px;
        }
        .main-content {
            background: #fff;
            border-radius: 14px;
            margin: 30px 0;
            padding: 2rem 2.5rem;
            min-height: 85vh;
            box-shadow: 0 0 20px #43cea235;
        }
        @media (max-width: 991.98px) {
            .sidebar {
                min-height: auto;
                padding-top: 15px;
            }
            .main-content {
                padding: 1rem 1rem;
            }
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark" style="background: linear-gradient(90deg, #43cea2 0%, #219150 100%);">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold" href="#">SHS Enrollment System</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarNav" aria-controls="sidebarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <span class="navbar-text text-white ms-auto d-none d-lg-inline">
            <?php if ($fullname) echo "Hello, $fullname!"; ?>
            <a class="btn btn-light btn-sm ms-3" href="logout.php" style="color:#219150 !important;">Logout</a>
        </span>
    </div>
</nav>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <nav class="sidebar col-lg-2 col-md-3 d-md-block d-none">
            <div class="sidebar-title text-center mb-4">Menu</div>
            <ul class="nav flex-column">
                <?php if ($role == 'admin'): ?>
                    <li class="nav-item"><a class="nav-link <?=active_nav('admin-dashboard.php')?>" href="admin-dashboard.php">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link <?=active_nav('admin-manage_strands.php')?>" href="admin-manage_strands.php">Strands</a></li>
                    <li class="nav-item"><a class="nav-link <?=active_nav('admin-manage_year_levels.php')?>" href="admin-manage_year_levels.php">Year Levels</a></li>
                    <li class="nav-item"><a class="nav-link <?=active_nav('admin-manage_semesters.php')?>" href="admin-manage_semesters.php">Semesters</a></li>
                    <li class="nav-item"><a class="nav-link <?=active_nav('admin-manage_subjects.php')?>" href="admin-manage_subjects.php">Subjects</a></li>
                    <li class="nav-item"><a class="nav-link <?=active_nav('admin-manage_users.php')?>" href="admin-manage_users.php">Users</a></li>
                    <li class="nav-item"><a class="nav-link <?=active_nav('admin-assign_subjects.php')?>" href="admin-assign_subjects.php">Assign Teacher/Schedule</a></li>
                <?php elseif ($role == 'registrar'): ?>
                    <li class="nav-item"><a class="nav-link <?=active_nav('registrar-dashboard.php')?>" href="registrar-dashboard.php">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link <?=active_nav('registrar-enroll_student.php')?>" href="registrar-enroll_student.php">Enroll Student</a></li>
                    <li class="nav-item"><a class="nav-link <?=active_nav('registrar-view_enrollments.php')?>" href="registrar-view_enrollments.php">View Enrollments</a></li>
                    <li class="nav-item"><a class="nav-link <?=active_nav('registrar-assign_subjects.php')?>" href="registrar-assign_subjects.php">Assign Subject & Schedule</a></li>
                <?php elseif ($role == 'teacher'): ?>
                    <li class="nav-item"><a class="nav-link <?=active_nav('teacher-dashboard.php')?>" href="teacher-dashboard.php">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link <?=active_nav('teacher-view_schedule.php')?>" href="teacher-view_schedule.php">My Schedule</a></li>
                <?php elseif ($role == 'student'): ?>
                    <li class="nav-item"><a class="nav-link <?=active_nav('student-dashboard.php')?>" href="student-dashboard.php">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link <?=active_nav('student-enroll.php')?>" href="student-enroll.php">Enroll</a></li>
                    <li class="nav-item"><a class="nav-link <?=active_nav('student-view_subjects.php')?>" href="student-view_subjects.php">My Subjects</a></li>
                <?php endif; ?>
            </ul>
        </nav>
        <!-- Sidebar Offcanvas for mobile -->
        <div class="offcanvas offcanvas-start d-lg-none" tabindex="-1" id="sidebarNav">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title">Menu</h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
            </div>
            <div class="offcanvas-body">
                <ul class="nav flex-column">
                    <?php if ($role == 'admin'): ?>
                        <li class="nav-item"><a class="nav-link <?=active_nav('admin-dashboard.php')?>" href="admin-dashboard.php">Dashboard</a></li>
                        <li class="nav-item"><a class="nav-link <?=active_nav('admin-manage_strands.php')?>" href="admin-manage_strands.php">Strands</a></li>
                        <li class="nav-item"><a class="nav-link <?=active_nav('admin-manage_year_levels.php')?>" href="admin-manage_year_levels.php">Year Levels</a></li>
                        <li class="nav-item"><a class="nav-link <?=active_nav('admin-manage_semesters.php')?>" href="admin-manage_semesters.php">Semesters</a></li>
                        <li class="nav-item"><a class="nav-link <?=active_nav('admin-manage_subjects.php')?>" href="admin-manage_subjects.php">Subjects</a></li>
                        <li class="nav-item"><a class="nav-link <?=active_nav('admin-manage_users.php')?>" href="admin-manage_users.php">Users</a></li>
                        <li class="nav-item"><a class="nav-link <?=active_nav('admin-assign_subjects.php')?>" href="admin-assign_subjects.php">Assign Teacher/Schedule</a></li>
                    <?php elseif ($role == 'registrar'): ?>
                        <li class="nav-item"><a class="nav-link <?=active_nav('registrar-dashboard.php')?>" href="registrar-dashboard.php">Dashboard</a></li>
                        <li class="nav-item"><a class="nav-link <?=active_nav('registrar-enroll_student.php')?>" href="registrar-enroll_student.php">Enroll Student</a></li>
                        <li class="nav-item"><a class="nav-link <?=active_nav('registrar-view_enrollments.php')?>" href="registrar-view_enrollments.php">View Enrollments</a></li>
                        <li class="nav-item"><a class="nav-link <?=active_nav('registrar-assign_subjects.php')?>" href="registrar-assign_subjects.php">Assign Subject & Schedule</a></li>
                    <?php elseif ($role == 'teacher'): ?>
                        <li class="nav-item"><a class="nav-link <?=active_nav('teacher-dashboard.php')?>" href="teacher-dashboard.php">Dashboard</a></li>
                        <li class="nav-item"><a class="nav-link <?=active_nav('teacher-view_schedule.php')?>" href="teacher-view_schedule.php">My Schedule</a></li>
                    <?php elseif ($role == 'student'): ?>
                        <li class="nav-item"><a class="nav-link <?=active_nav('student-dashboard.php')?>" href="student-dashboard.php">Dashboard</a></li>
                        <li class="nav-item"><a class="nav-link <?=active_nav('student-enroll.php')?>" href="student-enroll.php">Enroll</a></li>
                        <li class="nav-item"><a class="nav-link <?=active_nav('student-view_subjects.php')?>" href="student-view_subjects.php">My Subjects</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
        <!-- Main content -->
        <main class="main-content col-lg-10 col-md-9 ms-auto">
            <div class="d-block d-lg-none mb-3">
                <?php if ($fullname): ?>
                    <span class="fw-bold">Hello, <?=$fullname?>!</span>
                    <a class="btn btn-outline-success btn-sm ms-2" href="logout.php">Logout</a>
                <?php endif; ?>
                <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
            </div>