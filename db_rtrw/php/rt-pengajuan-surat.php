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
    // Get pengajuan surat untuk RT ini
    $stmt = $conn->prepare("
        SELECT ps.*, js.nama_surat, js.biaya_admin, w.nama_lengkap, kk.nomor_kk, kk.alamat
        FROM pengajuan_surat ps
        LEFT JOIN jenis_surat js ON ps.id_jenis_surat = js.id_jenis_surat
        LEFT JOIN warga w ON ps.id_warga = w.id_warga
        LEFT JOIN kepala_keluarga kk ON w.id_kk = kk.id_kk
        WHERE kk.id_rt = ?
        ORDER BY ps.tanggal_pengajuan DESC
    ");
    
    $stmt->bind_param('i', $id_rt);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    
    echo json_encode(['success' => true, 'data' => $data]);
}

elseif ($method === 'POST' && $action === 'approve') {
    // Approve surat
    $input = json_decode(file_get_contents('php://input'), true);
    $id_pengajuan = $input['id_pengajuan'] ?? null;
    $catatan = $input['catatan'] ?? '';
    $id_user = $_SESSION['id_user'];
    
    if (!$id_pengajuan) {
        echo json_encode(['success' => false, 'message' => 'ID pengajuan harus diisi']);
        exit;
    }
    
    $stmt = $conn->prepare("
        UPDATE pengajuan_surat
        SET status_pengajuan = 'disetujui_rt',
            tanggal_validasi_rt = NOW(),
            divalidasi_oleh_rt = ?,
            catatan_rt = ?
        WHERE id_pengajuan = ?
    ");
    
    $stmt->bind_param('isi', $id_user, $catatan, $id_pengajuan);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Pengajuan disetujui']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $stmt->error]);
    }
    $stmt->close();
}

elseif ($method === 'POST' && $action === 'reject') {
    // Reject surat
    $input = json_decode(file_get_contents('php://input'), true);
    $id_pengajuan = $input['id_pengajuan'] ?? null;
    $alasan = $input['alasan'] ?? '';
    $id_user = $_SESSION['id_user'];
    
    if (!$id_pengajuan) {
        echo json_encode(['success' => false, 'message' => 'ID pengajuan harus diisi']);
        exit;
    }
    
    $stmt = $conn->prepare("
        UPDATE pengajuan_surat
        SET status_pengajuan = 'ditolak_rt',
            tanggal_validasi_rt = NOW(),
            divalidasi_oleh_rt = ?,
            alasan_penolakan = ?
        WHERE id_pengajuan = ?
    ");
    
    $stmt->bind_param('isi', $id_user, $alasan, $id_pengajuan);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Pengajuan ditolak']);
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
