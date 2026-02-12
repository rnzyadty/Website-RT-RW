<?php
session_start();
header('Content-Type: application/json');

require_once 'db_connect.php';

// Check session
if (!isset($_SESSION['logged_in']) || ($_SESSION['role'] !== 'rw' && $_SESSION['role'] !== 'admin')) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';
$id_rw = $_SESSION['id_rw'];

if ($method === 'GET' && $action === 'warga_stats') {
    // Get statistik warga per RT
    $stmt = $conn->prepare("
        SELECT 
            r.id_rt,
            r.nomor_rt,
            r.ketua_rt,
            COUNT(DISTINCT kk.id_kk) as jumlah_kk,
            COUNT(w.id_warga) as jumlah_warga,
            SUM(CASE WHEN w.jenis_kelamin = 'Laki-laki' THEN 1 ELSE 0 END) as jumlah_laki,
            SUM(CASE WHEN w.jenis_kelamin = 'Perempuan' THEN 1 ELSE 0 END) as jumlah_perempuan
        FROM rt r
        LEFT JOIN kepala_keluarga kk ON r.id_rt = kk.id_rt
        LEFT JOIN warga w ON kk.id_kk = w.id_kk AND w.status_hidup = 'Hidup'
        WHERE r.id_rw = ?
        GROUP BY r.id_rt, r.nomor_rt, r.ketua_rt
        ORDER BY r.nomor_rt
    ");
    
    $stmt->bind_param('i', $id_rw);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    
    echo json_encode(['success' => true, 'data' => $data]);
}

elseif ($method === 'GET' && $action === 'summary') {
    // Get summary statistik RW
    $stmt = $conn->prepare("
        SELECT 
            COUNT(DISTINCT r.id_rt) as jumlah_rt,
            COUNT(DISTINCT kk.id_kk) as jumlah_kk,
            COUNT(w.id_warga) as jumlah_warga,
            SUM(CASE WHEN w.jenis_kelamin = 'Laki-laki' THEN 1 ELSE 0 END) as jumlah_laki,
            SUM(CASE WHEN w.jenis_kelamin = 'Perempuan' THEN 1 ELSE 0 END) as jumlah_perempuan
        FROM rt r
        LEFT JOIN kepala_keluarga kk ON r.id_rt = kk.id_rt
        LEFT JOIN warga w ON kk.id_kk = w.id_kk AND w.status_hidup = 'Hidup'
        WHERE r.id_rw = ?
    ");
    
    $stmt->bind_param('i', $id_rw);
    $stmt->execute();
    $result = $stmt->get_result();
    $summary = $result->fetch_assoc();
    $stmt->close();
    
    echo json_encode(['success' => true, 'data' => $summary]);
}

else {
    echo json_encode(['success' => false, 'message' => 'Invalid action']);
}

$conn->close();
?>
