<?php
/**
 * WartegGo — Admin Dashboard (Kelola Menu)
 */
require_once '../config/database.php';
requireLogin();

// Pesan sukses dari add/edit/delete
$success = $_SESSION['flash_success'] ?? '';
unset($_SESSION['flash_success']);

// Ambil semua menu
$stmt = $pdo->query("SELECT * FROM menu ORDER BY kategori ASC, nama_menu ASC");
$menus = $stmt->fetchAll();

$pageTitle = 'Dashboard Admin - WartegGo';
$cssPath = '../assets/css/style.css';
$jsPath = '../assets/js/app.js';
require_once '../includes/header.php';
?>

<body data-csrf="<?= e(generateCsrfToken()) ?>">

<!-- Admin Header -->
<header class="admin-header">
    <div class="admin-header__title">⚙️ WartegGo Admin</div>
    <nav class="admin-header__nav">
        <a href="settings.php" class="btn btn--outline btn--sm">Pengaturan</a>
        <a href="logout.php" class="btn btn--danger btn--sm">Keluar</a>
    </nav>
</header>

<div class="admin-content">

    <?php if ($success): ?>
        <div class="alert alert--success"><?= e($success) ?></div>
    <?php endif; ?>

    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;flex-wrap:wrap;gap:10px;">
        <h1 class="section-title" style="margin:0">Kelola <span>Menu</span></h1>
        <a href="add_menu.php" class="btn btn--primary btn--sm">+ Tambah Menu</a>
    </div>

    <div class="admin-table-wrap">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Gambar</th>
                    <th>Nama Menu</th>
                    <th>Kategori</th>
                    <th>Harga</th>
                    <th>Stok</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($menus)): ?>
                    <tr><td colspan="6" style="text-align:center;padding:32px;color:var(--text-muted);">Belum ada menu. Tambahkan menu pertama Anda!</td></tr>
                <?php endif; ?>

                <?php foreach ($menus as $menu): ?>
                <tr>
                    <td>
                        <?php if (!empty($menu['gambar']) && file_exists('../assets/uploads/' . $menu['gambar'])): ?>
                            <img src="../assets/uploads/<?= e($menu['gambar']) ?>" alt="" class="admin-table__img">
                        <?php else: ?>
                            <div class="admin-table__img" style="display:flex;align-items:center;justify-content:center;font-size:1.5rem;background:var(--dark-surface);">
                                <?= $menu['kategori'] === 'makanan' ? '🍚' : '🥤' ?>
                            </div>
                        <?php endif; ?>
                    </td>
                    <td><strong><?= e($menu['nama_menu']) ?></strong></td>
                    <td style="text-transform:capitalize;"><?= e($menu['kategori']) ?></td>
                    <td><?= formatRupiah($menu['harga']) ?></td>
                    <td>
                        <label class="toggle">
                            <input type="checkbox" class="toggle-stok" data-id="<?= $menu['id'] ?>"
                                <?= $menu['stok_status'] ? 'checked' : '' ?>>
                            <span class="toggle__slider"></span>
                        </label>
                    </td>
                    <td>
                        <div class="admin-table__actions">
                            <a href="edit_menu.php?id=<?= $menu['id'] ?>" class="btn btn--outline btn--sm">Edit</a>
                            <a href="delete_menu.php?id=<?= $menu['id'] ?>&csrf=<?= e(generateCsrfToken()) ?>" class="btn btn--danger btn--sm" data-delete>Hapus</a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</div>

<footer class="footer">WartegGo Admin Panel</footer>

<?php require_once '../includes/footer.php'; ?>
