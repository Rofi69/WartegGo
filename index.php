<?php
/**
 * WartegGo — Halaman Utama (Dashboard User)
 */
require_once 'config/database.php';

// Ambil data admin (WhatsApp, GMaps, nama warung)
$admin = getAdminData($pdo);
$noWa = $admin['no_whatsapp'] ?? '6281234567890';
$linkGmaps = $admin['link_gmaps'] ?? '#';
$namaWarung = $admin['nama_warung'] ?? 'WartegGo';

// Ambil semua menu, tersedia di atas, habis di bawah
$stmt = $pdo->query("SELECT * FROM menu ORDER BY stok_status DESC, nama_menu ASC");
$menus = $stmt->fetchAll();

$pageTitle = $namaWarung . ' - Menu Digital Warung';
$cssPath = 'assets/css/style.css';
$jsPath = 'assets/js/app.js';
require_once 'includes/header.php';
?>

<body data-whatsapp="<?= e($noWa) ?>">

<!-- Navbar -->
<nav class="navbar" id="navbar">
    <div class="navbar__brand">
        <div>
            <div class="navbar__logo"><?= e($namaWarung) ?></div>
            <div class="navbar__location">
                📍 <a href="<?= e($linkGmaps) ?>" target="_blank" rel="noopener">Lihat Lokasi</a>
            </div>
        </div>
    </div>
    <button class="hamburger" id="hamburger" aria-label="Menu">
        <span></span><span></span><span></span>
    </button>
</nav>

<!-- Sidebar -->
<aside class="sidebar" id="sidebar">
    <p class="sidebar__title">Filter Kategori</p>
    <button class="sidebar__item active" data-filter="semua">🍽️ Semua Menu</button>
    <button class="sidebar__item" data-filter="makanan">🍚 Makanan</button>
    <button class="sidebar__item" data-filter="minuman">🥤 Minuman</button>
    <div class="sidebar__divider"></div>
    <button class="sidebar__wa-btn" id="sidebarWaBtn">💬 Order via WhatsApp</button>
</aside>
<div class="overlay" id="overlay"></div>

<!-- Main Content -->
<main class="container">

    <h1 class="section-title">Menu <span>Hari Ini</span></h1>

    <div class="menu-grid" id="menuGrid">
        <?php foreach ($menus as $menu): ?>
            <?php $isHabis = $menu['stok_status'] == 0; ?>
            <div class="menu-card <?= $isHabis ? 'habis' : '' ?>" data-kategori="<?= e($menu['kategori']) ?>">
                <?php if (!empty($menu['gambar']) && file_exists('assets/uploads/' . $menu['gambar'])): ?>
                    <img src="assets/uploads/<?= e($menu['gambar']) ?>" alt="<?= e($menu['nama_menu']) ?>" class="menu-card__img" loading="lazy">
                <?php else: ?>
                    <div class="menu-card__img-placeholder">
                        <?= $menu['kategori'] === 'makanan' ? '🍚' : '🥤' ?>
                    </div>
                <?php endif; ?>

                <div class="menu-card__body">
                    <div class="menu-card__name"><?= e($menu['nama_menu']) ?></div>
                    <div class="menu-card__kategori"><?= e($menu['kategori']) ?></div>
                    <div class="menu-card__footer">
                        <span class="menu-card__price"><?= formatRupiah($menu['harga']) ?></span>
                        <?php if ($isHabis): ?>
                            <span class="badge badge--habis">Habis</span>
                        <?php else: ?>
                            <button class="btn-pesan" data-order="<?= e($menu['nama_menu']) ?>">Pesan</button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Pre-Order Section -->
    <div class="preorder-banner">
        <div class="preorder-banner__title">📦 Pre-Order Nasi Kotak</div>
        <p class="preorder-banner__text">
            Butuh pesanan dalam jumlah besar untuk acara kampus, rapat, atau kegiatan lainnya?
            Kami menerima pre-order Nasi Kotak mulai dari <strong>50 porsi</strong>.
        </p>
        <div class="preorder-banner__info">⏱️ Estimasi pemrosesan: 1–2 hari kerja</div>
        <br>
        <button class="btn btn--wa" id="preorderWaBtn">💬 Pre-Order via WhatsApp</button>
    </div>

</main>

<!-- Footer -->
<footer class="footer">
    &copy; <?= date('Y') ?> <?= e($namaWarung) ?> &middot; Warung Digital Kampus
</footer>

<!-- Modal Order WhatsApp -->
<div class="modal-overlay" id="orderModal">
    <div class="modal">
        <button class="modal__close" data-close-modal>&times;</button>
        <div class="modal__title">Pesan via WhatsApp</div>
        <div class="modal__subtitle">Menu: <strong id="modalMenuName">-</strong></div>
        <div class="form-group">
            <label for="jamAmbil">Jam Pengambilan</label>
            <input type="text" id="jamAmbil" class="form-input" placeholder="Contoh: 12.30">
        </div>
        <button class="btn btn--wa btn--block" id="sendWaBtn">Kirim Pesanan 💬</button>
    </div>
</div>

<!-- Modal Pre-Order -->
<div class="modal-overlay" id="preorderModal">
    <div class="modal">
        <button class="modal__close" data-close-preorder>&times;</button>
        <div class="modal__title">Pre-Order Nasi Kotak</div>
        <div class="modal__subtitle">Minimum 50 porsi &middot; Estimasi 1-2 hari</div>
        <div class="form-group">
            <label for="preorderJumlah">Jumlah Porsi</label>
            <input type="number" id="preorderJumlah" class="form-input" placeholder="Contoh: 150" min="50">
        </div>
        <div class="form-group">
            <label for="preorderTanggal">Tanggal Dibutuhkan</label>
            <input type="date" id="preorderTanggal" class="form-input">
        </div>
        <button class="btn btn--wa btn--block" id="sendPreorderBtn">Kirim Pre-Order 💬</button>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
