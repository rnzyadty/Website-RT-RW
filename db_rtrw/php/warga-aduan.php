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
    // Get aduan warga
    $id_warga = $_SESSION['id_warga'];
    
    $stmt = $conn->prepare("
        SELECT * FROM aduan
        WHERE id_warga = ?
        ORDER BY tanggal_aduan DESC
    ");
    
    $stmt->bind_param('i', $id_warga);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    
    echo json_encode(['success' => true, 'data' => $data]);
}

elseif ($method === 'POST' && $action === 'submit') {
    // Submit aduan
    $input = json_decode(file_get_contents('php://input'), true);
    $id_warga = $_SESSION['id_warga'];
    $judul = $input['judul_aduan'] ?? '';
    $kategori = $input['kategori'] ?? 'lainnya';
    $isi = $input['isi_aduan'] ?? '';
    $lokasi = $input['lokasi'] ?? null;
    
    if (!$judul || !$isi) {
        echo json_encode(['success' => false, 'message' => 'Judul dan isi aduan harus diisi']);
        exit;
    }
    
    $stmt = $conn->prepare("
        INSERT INTO aduan 
        (id_warga, judul_aduan, kategori, isi_aduan, lokasi, status, prioritas)
        VALUES (?, ?, ?, ?, ?, 'baru', 'sedang')
    ");
    
    $stmt->bind_param('issss', $id_warga, $judul, $kategori, $isi, $lokasi);
    
    if ($stmt->execute()) {
        $id_aduan = $stmt->insert_id;
        echo json_encode([
            'success' => true,
            'message' => 'Aduan berhasil disubmit',
            'id_aduan' => $id_aduan
        ]);
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
