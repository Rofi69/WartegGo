-- ============================================
-- WartegGo Database Schema
-- Aplikasi Menu Digital Warung Makan
-- ============================================

CREATE DATABASE IF NOT EXISTS warteggo_db
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_general_ci;

USE warteggo_db;

-- ============================================
-- Tabel Menu
-- ============================================
CREATE TABLE IF NOT EXISTS menu (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_menu VARCHAR(100) NOT NULL,
    kategori ENUM('makanan', 'minuman') NOT NULL,
    harga INT NOT NULL,
    stok_status TINYINT(1) DEFAULT 1 COMMENT '1=tersedia, 0=habis',
    gambar VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- Tabel Admin Warung
-- ============================================
CREATE TABLE IF NOT EXISTS admin_warung (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL COMMENT 'bcrypt hash',
    no_whatsapp VARCHAR(20) NOT NULL,
    link_gmaps TEXT DEFAULT NULL,
    nama_warung VARCHAR(100) DEFAULT 'WartegGo',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- Seed Data: Admin Default
-- Password: admin123 (SEGERA GANTI setelah deploy!)
-- ============================================
INSERT INTO admin_warung (username, password, no_whatsapp, link_gmaps, nama_warung)
VALUES (
    'admin',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    '6281234567890',
    'https://maps.app.goo.gl/contohlink',
    'WartegGo'
);

-- ============================================
-- Seed Data: Sample Menu
-- ============================================
INSERT INTO menu (nama_menu, kategori, harga, stok_status, gambar) VALUES
('Nasi Ayam Geprek',    'makanan', 15000, 1, NULL),
('Nasi Rendang',        'makanan', 18000, 1, NULL),
('Nasi Ayam Bakar',     'makanan', 17000, 1, NULL),
('Nasi Pecel Lele',     'makanan', 14000, 1, NULL),
('Nasi Telur Dadar',    'makanan', 10000, 1, NULL),
('Mie Goreng',          'makanan', 12000, 1, NULL),
('Nasi Goreng Spesial', 'makanan', 15000, 0, NULL),
('Es Teh Manis',        'minuman', 5000,  1, NULL),
('Es Jeruk',            'minuman', 7000,  1, NULL),
('Kopi Hitam',          'minuman', 5000,  1, NULL),
('Jus Alpukat',         'minuman', 12000, 0, NULL),
('Air Mineral',         'minuman', 4000,  1, NULL);
