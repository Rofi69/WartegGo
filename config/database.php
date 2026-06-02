<?php
/**
 * WartegGo - Database Configuration
 * Koneksi PDO ke MySQL
 */

// ============================================
// Konfigurasi Database
// Sesuaikan dengan hosting Anda
// ============================================
define('DB_HOST', 'localhost');
define('DB_NAME', 'warteggo_db');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

// ============================================
// Base URL (sesuaikan dengan domain Anda)
// ============================================
define('BASE_URL', '/');
define('UPLOAD_DIR', __DIR__ . '/../assets/uploads/');
define('MAX_UPLOAD_SIZE', 3 * 1024 * 1024); // 3MB

// ============================================
// Koneksi PDO
// ============================================
try {
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];
    $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
} catch (PDOException $e) {
    die('<div style="text-align:center;padding:50px;font-family:sans-serif;">
        <h2>⚠️ Koneksi Database Gagal</h2>
        <p>Pastikan MySQL aktif dan konfigurasi database sudah benar.</p>
        <p style="color:#999;font-size:12px;">' . htmlspecialchars($e->getMessage()) . '</p>
    </div>');
}

// ============================================
// Helper Functions
// ============================================

/**
 * Format harga ke Rupiah
 */
function formatRupiah($angka) {
    return 'Rp ' . number_format($angka, 0, ',', '.');
}

/**
 * Escape output untuk mencegah XSS
 */
function e($string) {
    return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8');
}

/**
 * Generate CSRF token
 */
function generateCsrfToken() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Validasi CSRF token
 */
function validateCsrfToken($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Ambil data admin warung
 */
function getAdminData($pdo) {
    $stmt = $pdo->query("SELECT * FROM admin_warung LIMIT 1");
    return $stmt->fetch();
}

/**
 * Cek apakah admin sudah login
 */
function isAdminLoggedIn() {
    return isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
}

/**
 * Redirect jika belum login
 */
function requireLogin() {
    if (!isAdminLoggedIn()) {
        header('Location: login.php');
        exit;
    }
}

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
