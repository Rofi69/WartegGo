<?php
/**
 * WartegGo — Tambah Menu Baru
 */
require_once '../config/database.php';
requireLogin();

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!validateCsrfToken($_POST['csrf_token'] ?? '')) {
        $error = 'Token tidak valid. Silakan coba lagi.';
    } else {
        $nama = trim($_POST['nama_menu'] ?? '');
        $kategori = $_POST['kategori'] ?? '';
        $harga = intval($_POST['harga'] ?? 0);

        if (!$nama || !in_array($kategori, ['makanan', 'minuman']) || $harga <= 0) {
            $error = 'Mohon lengkapi semua field dengan benar.';
        } else {
            $gambar = null;

            // Handle upload gambar
            if (!empty($_FILES['gambar']['name']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
                $tmpName = $_FILES['gambar']['tmp_name'];
                $fileSize = $_FILES['gambar']['size'];
                $fileExt = strtolower(pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION));
                $allowed = ['jpg', 'jpeg', 'png', 'webp'];

                if (!in_array($fileExt, $allowed)) {
                    $error = 'Format gambar harus JPG, PNG, atau WEBP.';
                } elseif ($fileSize > MAX_UPLOAD_SIZE) {
                    $error = 'Ukuran gambar maksimal 2MB.';
                } else {
                    if (!is_dir(UPLOAD_DIR)) {
                        mkdir(UPLOAD_DIR, 0755, true);
                    }
                    $gambar = 'menu_' . time() . '.' . $fileExt;
                    move_uploaded_file($tmpName, UPLOAD_DIR . $gambar);
                }
            }

            if (!$error) {
                $stmt = $pdo->prepare("INSERT INTO menu (nama_menu, kategori, harga, stok_status, gambar) VALUES (?, ?, ?, 1, ?)");
                $stmt->execute([$nama, $kategori, $harga, $gambar]);

                $_SESSION['flash_success'] = 'Menu "' . $nama . '" berhasil ditambahkan!';
                header('Location: dashboard.php');
                exit;
            }
        }
    }
}

$pageTitle = 'Tambah Menu - WartegGo Admin';
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
    <h1 class="section-title" style="margin-top:0">Tambah <span>Menu Baru</span></h1>

    <?php if ($error): ?>
        <div class="alert alert--error"><?= e($error) ?></div>
    <?php endif; ?>

    <div style="max-width:500px;">
        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?= e(generateCsrfToken()) ?>">

            <div class="form-group">
                <label for="nama_menu">Nama Menu</label>
                <input type="text" id="nama_menu" name="nama_menu" class="form-input" placeholder="Contoh: Nasi Ayam Geprek" value="<?= e($_POST['nama_menu'] ?? '') ?>" required>
            </div>

            <div class="form-group">
                <label for="kategori">Kategori</label>
                <select id="kategori" name="kategori" class="form-select" required>
                    <option value="">-- Pilih Kategori --</option>
                    <option value="makanan" <?= ($_POST['kategori'] ?? '') === 'makanan' ? 'selected' : '' ?>>Makanan</option>
                    <option value="minuman" <?= ($_POST['kategori'] ?? '') === 'minuman' ? 'selected' : '' ?>>Minuman</option>
                </select>
            </div>

            <div class="form-group">
                <label for="harga">Harga (Rp)</label>
                <input type="number" id="harga" name="harga" class="form-input" placeholder="15000" value="<?= e($_POST['harga'] ?? '') ?>" min="1" required>
            </div>

            <div class="form-group">
                <label for="gambar">Gambar Menu (opsional)</label>
                <input type="file" id="gambar" name="gambar" class="form-input" accept=".jpg,.jpeg,.png,.webp">
            </div>

            <button type="submit" class="btn btn--primary btn--block">💾 Simpan Menu</button>
        </form>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
