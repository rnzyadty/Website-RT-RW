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

if ($method === 'GET' && $action === 'list') {
    // Get aduan untuk RW ini (escalated)
    $stmt = $conn->prepare("
        SELECT a.*, w.nama_lengkap, r.nomor_rt
        FROM aduan a
        LEFT JOIN warga w ON a.id_warga = w.id_warga
        LEFT JOIN kepala_keluarga kk ON w.id_kk = kk.id_kk
        LEFT JOIN rt r ON kk.id_rt = r.id_rt
        WHERE r.id_rw = ? AND a.prioritas = 'urgent'
        ORDER BY a.tanggal_aduan DESC
    ");
    
    $stmt->bind_param('i', $id_rw);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    
    echo json_encode(['success' => true, 'data' => $data]);
}

elseif ($method === 'POST' && $action === 'update') {
    // Update aduan status
    $input = json_decode(file_get_contents('php://input'), true);
    $id_aduan = $input['id_aduan'] ?? null;
    $status = $input['status'] ?? 'diproses';
    $solusi = $input['solusi'] ?? '';
    $id_user = $_SESSION['id_user'];
    
    if (!$id_aduan) {
        echo json_encode(['success' => false, 'message' => 'ID aduan harus diisi']);
        exit;
    }
    
    $stmt = $conn->prepare("
        UPDATE aduan
        SET status = ?,
            tanggal_selesai = NOW(),
            ditangani_oleh = ?,
            solusi = ?
        WHERE id_aduan = ?
    ");
    
    $stmt->bind_param('sisi', $status, $id_user, $solusi, $id_aduan);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Aduan diupdate']);
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
