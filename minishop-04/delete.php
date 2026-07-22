<?php
require 'config.php';

// Chỉ chấp nhận POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    die('❌ Method not allowed');
}

$id = (int)($_POST['id'] ?? 0);

if ($id <= 0) {
    header('Location: list.php?error=1');
    exit;
}

try {
    $stmt = db()->prepare('DELETE FROM categories WHERE id = ?');
    $stmt->execute([$id]);
    
    // Redirect về list.php
    header('Location: list.php?success=1');
} catch (PDOException $e) {
    header('Location: list.php?error=2');
}

exit;