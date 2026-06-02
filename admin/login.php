<?php
/**
 * WartegGo — Admin Login
 */
require_once '../config/database.php';

// Jika sudah login, redirect ke dashboard
if (isAdminLoggedIn()) {
    header('Location: dashboard.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username && $password) {
        $stmt = $pdo->prepare("SELECT * FROM admin_warung WHERE username = ? LIMIT 1");
        $stmt->execute([$username]);
        $admin = $stmt->fetch();

        if ($admin && password_verify($password, $admin['password'])) {
            session_regenerate_id(true);
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_username'] = $admin['username'];
            header('Location: dashboard.php');
            exit;
        } else {
            $error = 'Username atau password salah!';
        }
    } else {
        $error = 'Mohon isi semua field.';
    }
}

$pageTitle = 'Login Admin - WartegGo';
$cssPath = '../assets/css/style.css';
$jsPath = '../assets/js/app.js';
require_once '../includes/header.php';
?>

<div class="login-wrapper">
    <div class="login-card">
        <div class="login-card__title">🔐 Login Admin</div>
        <div class="login-card__subtitle">Masuk untuk mengelola menu WartegGo</div>

        <?php if ($error): ?>
            <div class="alert alert--error"><?= e($error) ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" class="form-input" placeholder="admin" required autofocus>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" class="form-input" placeholder="••••••••" required>
            </div>
            <button type="submit" class="btn btn--primary btn--block">Masuk</button>
        </form>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
