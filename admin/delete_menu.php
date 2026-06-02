<?php
/**
 * WartegGo — Hapus Menu
 */
require_once '../config/database.php';
requireLogin();

$id = intval($_GET['id'] ?? 0);
$csrf = $_GET['csrf'] ?? '';

if (!$id || !validateCsrfToken($csrf)) {
    header('Location: dashboard.php');
    exit;
}

// Ambil data menu untuk hapus gambar
$stmt = $pdo->prepare("SELECT gambar FROM menu WHERE id = ?");
$stmt->execute([$id]);
$menu = $stmt->fetch();

if ($menu) {
    // Hapus file gambar jika ada
    if (!empty($menu['gambar']) && file_exists(UPLOAD_DIR . $menu['gambar'])) {
        unlink(UPLOAD_DIR . $menu['gambar']);
    }

    // Hapus dari database
    $stmt = $pdo->prepare("DELETE FROM menu WHERE id = ?");
    $stmt->execute([$id]);

    $_SESSION['flash_success'] = 'Menu berhasil dihapus!';
}

header('Location: dashboard.php');
exit;
