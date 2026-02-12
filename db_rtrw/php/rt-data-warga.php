<?php
session_start();
header('Content-Type: application/json');

require_once 'db_connect.php';

// Check session
if (!isset($_SESSION['logged_in']) || $_SESSION['role'] !== 'rt') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';
$id_rt = $_SESSION['id_rt'];

if ($method === 'GET' && $action === 'summary') {
    // Get statistik warga
    $stmt = $conn->prepare("
        SELECT 
            COUNT(DISTINCT kk.id_kk) as jumlah_kk,
            COUNT(w.id_warga) as jumlah_warga,
            SUM(CASE WHEN w.jenis_kelamin = 'Laki-laki' THEN 1 ELSE 0 END) as jumlah_laki,
            SUM(CASE WHEN w.jenis_kelamin = 'Perempuan' THEN 1 ELSE 0 END) as jumlah_perempuan
        FROM kepala_keluarga kk
        LEFT JOIN warga w ON kk.id_kk = w.id_kk AND w.status_hidup = 'Hidup'
        WHERE kk.id_rt = ?
    ");
    
    $stmt->bind_param('i', $id_rt);
    $stmt->execute();
    $result = $stmt->get_result();
    $summary = $result->fetch_assoc();
    $stmt->close();
    
    echo json_encode(['success' => true, 'data' => $summary]);
}

elseif ($method === 'GET' && $action === 'kepala_keluarga') {
    // Get list kepala keluarga
    $stmt = $conn->prepare("
        SELECT kk.*, COUNT(w.id_warga) as jumlah_jiwa
        FROM kepala_keluarga kk
        LEFT JOIN warga w ON kk.id_kk = w.id_kk AND w.status_hidup = 'Hidup'
        WHERE kk.id_rt = ?
        GROUP BY kk.id_kk
        ORDER BY kk.nama_kepala_keluarga
    ");
    
    $stmt->bind_param('i', $id_rt);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    
    echo json_encode(['success' => true, 'data' => $data]);
}

elseif ($method === 'GET' && $action === 'warga_detail') {
    // Get warga by KK
    $id_kk = $_GET['id_kk'] ?? 0;
    
    $stmt = $conn->prepare("
        SELECT * FROM warga
        WHERE id_kk = ?
        ORDER BY status_dalam_keluarga
    ");
    
    $stmt->bind_param('i', $id_kk);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    
    echo json_encode(['success' => true, 'data' => $data]);
}

else {
    echo json_encode(['success' => false, 'message' => 'Invalid action']);
}

$conn->close();
?>
