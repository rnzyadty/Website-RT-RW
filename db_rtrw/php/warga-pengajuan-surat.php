<?php
session_start();
header('Content-Type: application/json');

require_once 'db_connect.php';

// Check session
if (!isset($_SESSION['logged_in']) || $_SESSION['role'] !== 'warga') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';

if ($method === 'GET' && $action === 'list') {
    // Get pengajuan surat warga
    $id_warga = $_SESSION['id_warga'];
    
    $stmt = $conn->prepare("
        SELECT ps.*, js.nama_surat, js.biaya_admin
        FROM pengajuan_surat ps
        LEFT JOIN jenis_surat js ON ps.id_jenis_surat = js.id_jenis_surat
        WHERE ps.id_warga = ?
        ORDER BY ps.tanggal_pengajuan DESC
    ");
    
    $stmt->bind_param('i', $id_warga);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    
    echo json_encode(['success' => true, 'data' => $data]);
}

elseif ($method === 'POST' && $action === 'submit') {
    // Submit pengajuan surat
    $input = json_decode(file_get_contents('php://input'), true);
    $id_warga = $_SESSION['id_warga'];
    $id_jenis_surat = $input['id_jenis_surat'] ?? null;
    $tujuan_surat = $input['tujuan_surat'] ?? '';
    $keterangan = $input['keterangan'] ?? null;
    
    if (!$id_jenis_surat) {
        echo json_encode(['success' => false, 'message' => 'Jenis surat harus dipilih']);
        exit;
    }
    
    $stmt = $conn->prepare("
        INSERT INTO pengajuan_surat 
        (id_warga, id_jenis_surat, tujuan_surat, keterangan_tambahan, status_pengajuan)
        VALUES (?, ?, ?, ?, 'pending')
    ");
    
    $stmt->bind_param('iiss', $id_warga, $id_jenis_surat, $tujuan_surat, $keterangan);
    
    if ($stmt->execute()) {
        $id_pengajuan = $stmt->insert_id;
        echo json_encode([
            'success' => true,
            'message' => 'Pengajuan surat berhasil disubmit',
            'id_pengajuan' => $id_pengajuan
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $stmt->error]);
    }
    $stmt->close();
}

elseif ($method === 'GET' && $action === 'types') {
    // Get jenis surat
    $result = $conn->query("SELECT * FROM jenis_surat");
    $data = $result->fetch_all(MYSQLI_ASSOC);
    
    echo json_encode(['success' => true, 'data' => $data]);
}

else {
    echo json_encode(['success' => false, 'message' => 'Invalid action']);
}

$conn->close();
?>
