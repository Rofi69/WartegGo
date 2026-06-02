<?php
// Script sementara untuk generate hash password
// HAPUS FILE INI SETELAH DIGUNAKAN!
$password = 'admin123';
$hash = password_hash($password, PASSWORD_BCRYPT);
echo "<h2>Hash untuk password: " . htmlspecialchars($password) . "</h2>";
echo "<pre>" . $hash . "</pre>";
echo "<p>Salin hash di atas, lalu update di tabel admin_warung.</p>";
echo "<hr>";
echo "<p>Atau jalankan query ini di phpMyAdmin:</p>";
echo "<pre>UPDATE admin_warung SET password = '" . $hash . "' WHERE username = 'admin';</pre>";
