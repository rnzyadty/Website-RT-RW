<?php
session_start();

// Hapus semua session
session_unset();
session_destroy();

// Return JSON response
echo json_encode([
    'success' => true,
    'message' => 'Logout berhasil',
    'redirect' => '../index.php'
]);
?>
