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
    // Get iuran warga dari KK
    $id_warga = $_SESSION['id_warga'];
    
    // Get KK dari warga
    $warga_stmt = $conn->prepare("SELECT id_kk FROM warga WHERE id_warga = ?");
    $warga_stmt->bind_param('i', $id_warga);
    $warga_stmt->execute();
    $warga_result = $warga_stmt->get_result();
    $warga = $warga_result->fetch_assoc();
    $warga_stmt->close();
    
    if (!$warga) {
        echo json_encode(['success' => false, 'message' => 'Warga not found']);
        exit;
    }
    
    $id_kk = $warga['id_kk'];
    
    $stmt = $conn->prepare("
        SELECT * FROM iuran_warga
        WHERE id_kk = ?
        ORDER BY tahun DESC, bulan DESC
    ");
    
    $stmt->bind_param('i', $id_kk);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    
    echo json_encode(['success' => true, 'data' => $data]);
}

elseif ($method === 'POST' && $action === 'bayar') {
    // Record payment
    $input = json_decode(file_get_contents('php://input'), true);
    $id_iuran = $input['id_iuran'] ?? null;
    $metode = $input['metode_bayar'] ?? 'tunai';
    
    if (!$id_iuran) {
        echo json_encode(['success' => false, 'message' => 'ID iuran harus diisi']);
        exit;
    }
    
    // Get iuran data
    $check_stmt = $conn->prepare("SELECT id_kk FROM iuran_warga WHERE id_iuran = ?");
    $check_stmt->bind_param('i', $id_iuran);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();
    $iuran = $check_result->fetch_assoc();
    $check_stmt->close();
    
    if (!$iuran) {
        echo json_encode(['success' => false, 'message' => 'Iuran not found']);
        exit;
    }
    
    // Update iuran status
    $update_stmt = $conn->prepare("
        UPDATE iuran_warga 
        SET status_bayar = 'lunas', tanggal_bayar = NOW(), metode_bayar = ?
        WHERE id_iuran = ?
    ");
    
    $update_stmt->bind_param('si', $metode, $id_iuran);
    
    if ($update_stmt->execute()) {
        echo json_encode([
            'success' => true,
            'message' => 'Pembayaran berhasil tercatat'
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $update_stmt->error]);
    }
    $update_stmt->close();
}

else {
    echo json_encode(['success' => false, 'message' => 'Invalid action']);
}

$conn->close();
?>
