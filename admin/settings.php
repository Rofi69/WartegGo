<?php
/**
 * WartegGo — Pengaturan Akun Admin
 */
require_once '../config/database.php';
requireLogin();

$admin = getAdminData($pdo);
$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!validateCsrfToken($_POST['csrf_token'] ?? '')) {
        $error = 'Token tidak valid.';
    } else {
        $action = $_POST['action'] ?? '';

        // Update info warung
        if ($action === 'update_info') {
            $noWa = trim($_POST['no_whatsapp'] ?? '');
            $linkGmaps = trim($_POST['link_gmaps'] ?? '');
            $namaWarung = trim($_POST['nama_warung'] ?? '');

            if (!$noWa || !$namaWarung) {
                $error = 'Nomor WhatsApp dan nama warung harus diisi.';
            } else {
                $stmt = $pdo->prepare("UPDATE admin_warung SET no_whatsapp = ?, link_gmaps = ?, nama_warung = ? WHERE id = ?");
                $stmt->execute([$noWa, $linkGmaps, $namaWarung, $admin['id']]);
                $success = 'Informasi warung berhasil diperbarui!';
                $admin = getAdminData($pdo); // Refresh data
            }
        }

        // Ganti password
        if ($action === 'update_password') {
            $oldPass = $_POST['old_password'] ?? '';
            $newPass = $_POST['new_password'] ?? '';
            $confirmPass = $_POST['confirm_password'] ?? '';

            if (!$oldPass || !$newPass || !$confirmPass) {
                $error = 'Semua field password harus diisi.';
            } elseif ($newPass !== $confirmPass) {
                $error = 'Konfirmasi password tidak cocok.';
            } elseif (strlen($newPass) < 6) {
                $error = 'Password baru minimal 6 karakter.';
            } elseif (!password_verify($oldPass, $admin['password'])) {
                $error = 'Password lama salah.';
            } else {
                $hash = password_hash($newPass, PASSWORD_BCRYPT);
                $stmt = $pdo->prepare("UPDATE admin_warung SET password = ? WHERE id = ?");
                $stmt->execute([$hash, $admin['id']]);
                $success = 'Password berhasil diperbarui!';
            }
        }
    }
}

$pageTitle = 'Pengaturan - WartegGo Admin';
$cssPath = '../assets/css/style.css';
$jsPath = '../assets/js/app.js';
require_once '../includes/header.php';
?>

<header class="admin-header">
    <div class="admin-header__title">⚙️ WartegGo Admin</div>
    <nav class="admin-header__nav">
        <a href="dashboard.php" class="btn btn--outline btn--sm">← Kembali</a>
    </nav>
</header>

<div class="admin-content">
    <h1 class="section-title" style="margin-top:0">⚙️ <span>Pengaturan</span></h1>

    <?php if ($success): ?>
        <div class="alert alert--success"><?= e($success) ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="alert alert--error"><?= e($error) ?></div>
    <?php endif; ?>

    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(320px,1fr));gap:24px;">

        <!-- Informasi Warung -->
        <div style="background:var(--dark-card);border:1px solid var(--border);border-radius:var(--radius);padding:24px;">
            <h3 style="font-family:var(--font-heading);margin-bottom:16px;">📱 Informasi Warung</h3>
            <form method="POST">
                <input type="hidden" name="csrf_token" value="<?= e(generateCsrfToken()) ?>">
                <input type="hidden" name="action" value="update_info">

                <div class="form-group">
                    <label for="nama_warung">Nama Warung</label>
                    <input type="text" id="nama_warung" name="nama_warung" class="form-input" value="<?= e($admin['nama_warung']) ?>" required>
                </div>

                <div class="form-group">
                    <label for="no_whatsapp">Nomor WhatsApp</label>
                    <input type="text" id="no_whatsapp" name="no_whatsapp" class="form-input" value="<?= e($admin['no_whatsapp']) ?>" placeholder="6281234567890" required>
                    <small style="color:var(--text-muted);font-size:0.75rem;">Format: 628xxx (tanpa + atau 0)</small>
                </div>

                <div class="form-group">
                    <label for="link_gmaps">Link Google Maps</label>
                    <input type="url" id="link_gmaps" name="link_gmaps" class="form-input" value="<?= e($admin['link_gmaps']) ?>" placeholder="https://maps.app.goo.gl/xxx">
                </div>

                <button type="submit" class="btn btn--primary btn--block">💾 Simpan Info</button>
            </form>
        </div>

        <!-- Ganti Password -->
        <div style="background:var(--dark-card);border:1px solid var(--border);border-radius:var(--radius);padding:24px;">
            <h3 style="font-family:var(--font-heading);margin-bottom:16px;">🔒 Ganti Password</h3>
            <form method="POST">
                <input type="hidden" name="csrf_token" value="<?= e(generateCsrfToken()) ?>">
                <input type="hidden" name="action" value="update_password">

                <div class="form-group">
                    <label for="old_password">Password Lama</label>
                    <input type="password" id="old_password" name="old_password" class="form-input" required>
                </div>

                <div class="form-group">
                    <label for="new_password">Password Baru</label>
                    <input type="password" id="new_password" name="new_password" class="form-input" placeholder="Minimal 6 karakter" required>
                </div>

                <div class="form-group">
                    <label for="confirm_password">Konfirmasi Password Baru</label>
                    <input type="password" id="confirm_password" name="confirm_password" class="form-input" required>
                </div>

                <button type="submit" class="btn btn--success btn--block">🔐 Ubah Password</button>
            </form>
        </div>

    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
