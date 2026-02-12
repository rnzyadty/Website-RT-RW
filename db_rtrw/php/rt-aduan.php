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

if ($method === 'GET' && $action === 'list') {
    // Get aduan untuk RT ini
    $stmt = $conn->prepare("
        SELECT a.*, w.nama_lengkap, kk.id_rt
        FROM aduan a
        LEFT JOIN warga w ON a.id_warga = w.id_warga
        LEFT JOIN kepala_keluarga kk ON w.id_kk = kk.id_kk
        WHERE kk.id_rt = ?
        ORDER BY CASE a.prioritas 
                  WHEN 'urgent' THEN 1
                  WHEN 'tinggi' THEN 2
                  WHEN 'sedang' THEN 3
                  ELSE 4 END,
                  a.tanggal_aduan DESC
    ");
    
    $stmt->bind_param('i', $id_rt);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    
    echo json_encode(['success' => true, 'data' => $data]);
}

elseif ($method === 'POST' && $action === 'update_status') {
    // Update aduan status
    $input = json_decode(file_get_contents('php://input'), true);
    $id_aduan = $input['id_aduan'] ?? null;
    $status = $input['status'] ?? 'diproses';
    $catatan = $input['catatan'] ?? '';
    $id_user = $_SESSION['id_user'];
    
    if (!$id_aduan) {
        echo json_encode(['success' => false, 'message' => 'ID aduan harus diisi']);
        exit;
    }
    
    $stmt = $conn->prepare("
        UPDATE aduan
        SET status = ?,
            tanggal_proses = NOW(),
            ditangani_oleh = ?,
            catatan_penanganan = ?
        WHERE id_aduan = ?
    ");
    
    $stmt->bind_param('sisi', $status, $id_user, $catatan, $id_aduan);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Status aduan diupdate']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $stmt->error]);
    }
    $stmt->close();
}

elseif ($method === 'POST' && $action === 'escalate') {
    // Escalate aduan ke RW
    $input = json_decode(file_get_contents('php://input'), true);
    $id_aduan = $input['id_aduan'] ?? null;
    
    if (!$id_aduan) {
        echo json_encode(['success' => false, 'message' => 'ID aduan harus diisi']);
        exit;
    }
    
    $stmt = $conn->prepare("
        UPDATE aduan
        SET status = 'diproses',
            prioritas = 'urgent'
        WHERE id_aduan = ?
    ");
    
    $stmt->bind_param('i', $id_aduan);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Aduan diteruskan ke RW']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $stmt->error]);
    }
    $stmt->close();
}

else {
    echo json_encode(['success' => false, 'message' => 'Invalid action']);
}

$conn->close();
?>
