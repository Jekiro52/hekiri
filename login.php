<?php
session_start();
require 'db_connect.php';
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? AND archived = 0");
    $stmt->execute([$username]);
    $user = $stmt->fetch();
    if ($user && ($password === $user['password'] || password_verify($password, $user['password']))) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['fullname'] = $user['fullname'];
        if ($user['role']=='admin') header('Location: admin-dashboard.php');
        elseif ($user['role']=='registrar') header('Location: registrar-dashboard.php');
        elseif ($user['role']=='teacher') header('Location: teacher-dashboard.php');
        else header('Location: student-dashboard.php');
        exit;
    } else {
        $error = "Invalid username or password!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>SHS Enrollment System - Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: linear-gradient(135deg, #43cea2 0%, #185a9d 100%); min-height: 100vh; display: flex; align-items: center; justify-content: center; }
        .login-card { border-radius: 18px; box-shadow: 0 0 40px 0 rgba(34,139,34,0.15), 0 1.5px 5px 0 rgba(0,0,0,.05); background: #fff; padding: 2.5rem 2rem 2rem 2rem; max-width: 380px; width: 100%; }
        .login-title { font-weight: 700; color: #219150; margin-bottom: 1.2rem; }
        .form-control:focus { border-color: #43cea2; box-shadow: 0 0 0 0.2rem rgba(34,139,34,.15);}
        .btn-green { background: linear-gradient(90deg, #43cea2 0%, #219150 100%); color: #fff; font-weight: 600; border: none;}
        .btn-green:hover, .btn-green:focus { background: linear-gradient(90deg, #219150 0%, #43cea2 100%); color: #fff;}
    </style>
</head>
<body>
<div class="login-card">
    <div class="login-title">SHS Enrollment System</div>
    <form method="post" action="">
        <div class="mb-3">
            <label class="form-label" for="username">Username</label>
            <input type="text" class="form-control shadow-sm" id="username" name="username" required autofocus>
        </div>
        <div class="mb-3">
            <label class="form-label" for="password">Password</label>
            <input type="password" class="form-control shadow-sm" id="password" name="password" required>
        </div>
        <button type="submit" class="btn btn-green w-100 py-2 mt-2">Login</button>
        <?php if ($error): ?>
            <div class="alert alert-danger mt-3 mb-0 py-2 px-2 text-center" style="font-size:0.95rem;"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
    </form>
</div>
</body>
</html>