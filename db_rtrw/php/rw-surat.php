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

if ($method === 'GET' && $action === 'recap') {
    // Get rekap pengajuan surat per RT
    $stmt = $conn->prepare("
        SELECT 
            r.id_rt,
            r.nomor_rt,
            COUNT(ps.id_pengajuan) as total,
            SUM(CASE WHEN ps.status_pengajuan IN ('disetujui_rt', 'disetujui_rw', 'selesai') THEN 1 ELSE 0 END) as disetujui,
            SUM(CASE WHEN ps.status_pengajuan IN ('ditolak_rt', 'ditolak_rw') THEN 1 ELSE 0 END) as ditolak,
            SUM(CASE WHEN ps.status_pengajuan = 'pending' THEN 1 ELSE 0 END) as pending
        FROM rt r
        LEFT JOIN kepala_keluarga kk ON r.id_rt = kk.id_rt
        LEFT JOIN warga w ON kk.id_kk = w.id_kk
        LEFT JOIN pengajuan_surat ps ON w.id_warga = ps.id_warga
        WHERE r.id_rw = ?
        GROUP BY r.id_rt, r.nomor_rt
        ORDER BY r.nomor_rt
    ");
    
    $stmt->bind_param('i', $id_rw);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    
    echo json_encode(['success' => true, 'data' => $data]);
}

elseif ($method === 'GET' && $action === 'surat_pending') {
    // Get all pending surat
    $stmt = $conn->prepare("
        SELECT ps.*, js.nama_surat, w.nama_lengkap, r.nomor_rt
        FROM pengajuan_surat ps
        LEFT JOIN jenis_surat js ON ps.id_jenis_surat = js.id_jenis_surat
        LEFT JOIN warga w ON ps.id_warga = w.id_warga
        LEFT JOIN kepala_keluarga kk ON w.id_kk = kk.id_kk
        LEFT JOIN rt r ON kk.id_rt = r.id_rt
        WHERE r.id_rw = ? AND ps.status_pengajuan IN ('disetujui_rt', 'pending')
        ORDER BY ps.tanggal_pengajuan
    ");
    
    $stmt->bind_param('i', $id_rw);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    
    echo json_encode(['success' => true, 'data' => $data]);
}

elseif ($method === 'POST' && $action === 'approve_surat') {
    // Approve surat di level RW
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
        SET status_pengajuan = 'disetujui_rw',
            tanggal_validasi_rw = NOW(),
            divalidasi_oleh_rw = ?,
            catatan_rw = ?
        WHERE id_pengajuan = ?
    ");
    
    $stmt->bind_param('isi', $id_user, $catatan, $id_pengajuan);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Pengajuan disetujui RW']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $stmt->error]);
    }
    $stmt->close();
}

elseif ($method === 'POST' && $action === 'reject_surat') {
    // Reject surat di level RW
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
        SET status_pengajuan = 'ditolak_rw',
            tanggal_validasi_rw = NOW(),
            divalidasi_oleh_rw = ?,
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
