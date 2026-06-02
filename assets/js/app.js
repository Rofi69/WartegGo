/**
 * WartegGo — Frontend JavaScript
 */
document.addEventListener('DOMContentLoaded', () => {

    // ============================================
    // Hamburger Menu & Sidebar
    // ============================================
    const hamburger = document.getElementById('hamburger');
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('overlay');

    function toggleSidebar() {
        hamburger?.classList.toggle('active');
        sidebar?.classList.toggle('open');
        overlay?.classList.toggle('show');
    }

    hamburger?.addEventListener('click', toggleSidebar);
    overlay?.addEventListener('click', toggleSidebar);

    // ============================================
    // Filter Kategori
    // ============================================
    const filterBtns = document.querySelectorAll('[data-filter]');
    const menuCards = document.querySelectorAll('.menu-card');

    filterBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            const filter = btn.dataset.filter;

            // Update active state
            filterBtns.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');

            // Filter cards
            menuCards.forEach(card => {
                if (filter === 'semua' || card.dataset.kategori === filter) {
                    card.style.display = '';
                } else {
                    card.style.display = 'none';
                }
            });

            // Close sidebar on mobile
            if (sidebar?.classList.contains('open')) {
                toggleSidebar();
            }
        });
    });

    // ============================================
    // Modal Order WhatsApp
    // ============================================
    const orderModal = document.getElementById('orderModal');
    const menuNameEl = document.getElementById('modalMenuName');
    const jamInput = document.getElementById('jamAmbil');
    const sendBtn = document.getElementById('sendWaBtn');
    const closeBtns = document.querySelectorAll('[data-close-modal]');

    // Open modal when "Pesan" clicked
    document.querySelectorAll('[data-order]').forEach(btn => {
        btn.addEventListener('click', () => {
            const name = btn.dataset.order;
            if (menuNameEl) menuNameEl.textContent = name;
            if (jamInput) jamInput.value = '';
            orderModal?.classList.add('show');
        });
    });

    // Close modal
    closeBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            orderModal?.classList.remove('show');
        });
    });

    orderModal?.addEventListener('click', (e) => {
        if (e.target === orderModal) orderModal.classList.remove('show');
    });

    // Send WhatsApp message
    sendBtn?.addEventListener('click', () => {
        const namaMenu = menuNameEl?.textContent || '';
        const jam = jamInput?.value || '';

        if (!jam) {
            jamInput?.focus();
            return;
        }

        const noWa = document.body.dataset.whatsapp || '';
        const pesan = `Halo WartegGo, saya ingin memesan ${namaMenu}. Saya akan mengambilnya jam ${jam}. Mohon disiapkan.`;
        const url = `https://wa.me/${noWa}?text=${encodeURIComponent(pesan)}`;

        window.open(url, '_blank');
        orderModal?.classList.remove('show');
    });

    // ============================================
    // Sidebar "Order via WhatsApp" button
    // ============================================
    const sidebarWaBtn = document.getElementById('sidebarWaBtn');
    sidebarWaBtn?.addEventListener('click', () => {
        if (menuNameEl) menuNameEl.textContent = '...';
        if (jamInput) jamInput.value = '';
        if (sidebar?.classList.contains('open')) toggleSidebar();
        orderModal?.classList.add('show');
    });

    // ============================================
    // Pre-Order WhatsApp
    // ============================================
    const preorderBtn = document.getElementById('preorderWaBtn');
    const preorderModal = document.getElementById('preorderModal');
    const preorderClose = document.querySelectorAll('[data-close-preorder]');
    const sendPreorderBtn = document.getElementById('sendPreorderBtn');

    preorderBtn?.addEventListener('click', () => {
        preorderModal?.classList.add('show');
    });

    preorderClose.forEach(btn => {
        btn.addEventListener('click', () => {
            preorderModal?.classList.remove('show');
        });
    });

    preorderModal?.addEventListener('click', (e) => {
        if (e.target === preorderModal) preorderModal.classList.remove('show');
    });

    sendPreorderBtn?.addEventListener('click', () => {
        const jumlah = document.getElementById('preorderJumlah')?.value || '';
        const tanggal = document.getElementById('preorderTanggal')?.value || '';

        if (!jumlah || !tanggal) {
            alert('Mohon lengkapi jumlah porsi dan tanggal.');
            return;
        }

        const noWa = document.body.dataset.whatsapp || '';
        const pesan = `Halo WartegGo, saya ingin melakukan pre-order:\n- Menu: Nasi Kotak\n- Jumlah: ${jumlah} porsi\n- Tanggal dibutuhkan: ${tanggal}\nMohon informasi ketersediaan dan total biayanya. Terima kasih.`;
        const url = `https://wa.me/${noWa}?text=${encodeURIComponent(pesan)}`;

        window.open(url, '_blank');
        preorderModal?.classList.remove('show');
    });

    // ============================================
    // Admin: Toggle Stok (AJAX)
    // ============================================
    document.querySelectorAll('.toggle-stok').forEach(toggle => {
        toggle.addEventListener('change', async () => {
            const menuId = toggle.dataset.id;
            const newStatus = toggle.checked ? 1 : 0;

            try {
                const res = await fetch('toggle_stock.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `id=${menuId}&stok_status=${newStatus}&csrf_token=${document.body.dataset.csrf || ''}`
                });
                const data = await res.json();
                if (!data.success) {
                    alert('Gagal mengubah stok!');
                    toggle.checked = !toggle.checked;
                }
            } catch {
                alert('Koneksi gagal!');
                toggle.checked = !toggle.checked;
            }
        });
    });

    // ============================================
    // Admin: Delete Confirmation
    // ============================================
    document.querySelectorAll('[data-delete]').forEach(btn => {
        btn.addEventListener('click', (e) => {
            if (!confirm('Yakin ingin menghapus menu ini?')) {
                e.preventDefault();
            }
        });
    });

});
