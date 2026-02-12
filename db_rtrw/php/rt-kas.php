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
    // Get kas RT transactions
    $stmt = $conn->prepare("
        SELECT kr.*, kk.nama_kategori
        FROM kas_rt kr
        LEFT JOIN kategori_keuangan kk ON kr.id_kategori = kk.id_kategori
        WHERE kr.id_rt = ?
        ORDER BY kr.tanggal DESC
    ");
    
    $stmt->bind_param('i', $id_rt);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    
    echo json_encode(['success' => true, 'data' => $data]);
}

elseif ($method === 'GET' && $action === 'summary') {
    // Get kas RT summary
    $stmt = $conn->prepare("
        SELECT 
            SUM(CASE WHEN jenis_transaksi = 'pemasukan' THEN nominal ELSE 0 END) as total_pemasukan,
            SUM(CASE WHEN jenis_transaksi = 'pengeluaran' THEN nominal ELSE 0 END) as total_pengeluaran,
            SUM(CASE WHEN jenis_transaksi = 'pemasukan' THEN nominal ELSE -nominal END) as saldo_akhir
        FROM kas_rt
        WHERE id_rt = ?
    ");
    
    $stmt->bind_param('i', $id_rt);
    $stmt->execute();
    $result = $stmt->get_result();
    $summary = $result->fetch_assoc();
    $stmt->close();
    
    echo json_encode(['success' => true, 'data' => $summary]);
}

elseif ($method === 'GET' && $action === 'categories') {
    // Get kategori keuangan
    $result = $conn->query("SELECT * FROM kategori_keuangan ORDER BY tipe, nama_kategori");
    $data = $result->fetch_all(MYSQLI_ASSOC);
    
    echo json_encode(['success' => true, 'data' => $data]);
}

elseif ($method === 'POST' && $action === 'add') {
    // Add transaksi kas
    $input = json_decode(file_get_contents('php://input'), true);
    $tanggal = $input['tanggal'] ?? date('Y-m-d');
    $jenis = $input['jenis_transaksi'] ?? 'pemasukan';
    $kategori = $input['id_kategori'] ?? 1;
    $nominal = floatval($input['nominal'] ?? 0);
    $keterangan = $input['keterangan'] ?? '';
    $id_user = $_SESSION['id_user'];
    
    if (!$nominal || $nominal <= 0) {
        echo json_encode(['success' => false, 'message' => 'Nominal harus diisi dan lebih besar dari 0']);
        exit;
    }
    
    $stmt = $conn->prepare("
        INSERT INTO kas_rt 
        (id_rt, id_kategori, tanggal, jenis_transaksi, nominal, keterangan, id_user_input)
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ");
    
    $stmt->bind_param('iissdssi', $id_rt, $kategori, $tanggal, $jenis, $nominal, $keterangan, $id_user);
    
    if ($stmt->execute()) {
        $id_kas = $stmt->insert_id;
        echo json_encode([
            'success' => true,
            'message' => 'Transaksi kas berhasil ditambahkan',
            'id_kas' => $id_kas
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
