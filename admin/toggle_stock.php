<?php
/**
 * WartegGo — Toggle Stok Status (AJAX Endpoint)
 */
require_once '../config/database.php';

header('Content-Type: application/json');

if (!isAdminLoggedIn()) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

$id = intval($_POST['id'] ?? 0);
$stokStatus = intval($_POST['stok_status'] ?? 0);
$csrf = $_POST['csrf_token'] ?? '';

if (!$id || !validateCsrfToken($csrf)) {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    exit;
}

// Pastikan stok_status hanya 0 atau 1
$stokStatus = $stokStatus ? 1 : 0;

try {
    $stmt = $pdo->prepare("UPDATE menu SET stok_status = ? WHERE id = ?");
    $stmt->execute([$stokStatus, $id]);

    echo json_encode(['success' => true, 'stok_status' => $stokStatus]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Database error']);
}
