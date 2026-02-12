<?php
session_start();
header('Content-Type: application/json');

require_once 'db_connect.php';

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';

if ($method === 'GET' && $action === 'pengumuman') {
    // Get pengumuman publik
    $result = $conn->query("
        SELECT * FROM pengumuman
        WHERE status = 'published' 
        AND (tanggal_mulai IS NULL OR tanggal_mulai <= CURDATE())
        AND (tanggal_berakhir IS NULL OR tanggal_berakhir >= CURDATE())
        ORDER BY prioritas DESC, created_at DESC
        LIMIT 20
    ");
    
    $data = $result->fetch_all(MYSQLI_ASSOC);
    echo json_encode(['success' => true, 'data' => $data]);
}

elseif ($method === 'GET' && $action === 'galeri') {
    // Get galeri
    $result = $conn->query("
        SELECT * FROM galeri
        WHERE status = 'published'
        ORDER BY tanggal_upload DESC
        LIMIT 50
    ");
    
    $data = $result->fetch_all(MYSQLI_ASSOC);
    echo json_encode(['success' => true, 'data' => $data]);
}

else {
    echo json_encode(['success' => false, 'message' => 'Invalid action']);
}

$conn->close();
?>
