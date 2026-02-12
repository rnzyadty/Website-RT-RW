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

if ($method === 'GET' && $action === 'keuangan_rt') {
    // Get keuangan per RT
    $stmt = $conn->prepare("
        SELECT 
            r.id_rt,
            r.nomor_rt,
            r.ketua_rt,
            SUM(CASE WHEN kr.jenis_transaksi = 'pemasukan' THEN kr.nominal ELSE 0 END) as total_pemasukan,
            SUM(CASE WHEN kr.jenis_transaksi = 'pengeluaran' THEN kr.nominal ELSE 0 END) as total_pengeluaran,
            SUM(CASE WHEN kr.jenis_transaksi = 'pemasukan' THEN kr.nominal ELSE -kr.nominal END) as saldo_akhir
        FROM rt r
        LEFT JOIN kas_rt kr ON r.id_rt = kr.id_rt
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

elseif ($method === 'GET' && $action === 'iuran_recap') {
    // Get ringkasan iuran per RT
    $stmt = $conn->prepare("
        SELECT 
            r.id_rt,
            r.nomor_rt,
            MONTH(CURDATE()) as bulan,
            YEAR(CURDATE()) as tahun,
            COUNT(DISTINCT kk.id_kk) as total_kk,
            SUM(CASE WHEN iw.status_bayar = 'lunas' THEN 1 ELSE 0 END) as lunas,
            SUM(CASE WHEN iw.status_bayar = 'belum' THEN 1 ELSE 0 END) as belum,
            SUM(iw.nominal) as total_nominal,
            SUM(CASE WHEN iw.status_bayar = 'lunas' THEN iw.nominal ELSE 0 END) as terbayar,
            SUM(CASE WHEN iw.status_bayar = 'belum' THEN iw.nominal ELSE 0 END) as tunggakan
        FROM rt r
        LEFT JOIN kepala_keluarga kk ON r.id_rt = kk.id_rt
        LEFT JOIN iuran_warga iw ON kk.id_kk = iw.id_kk 
            AND iw.bulan = MONTH(CURDATE())
            AND iw.tahun = YEAR(CURDATE())
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

else {
    echo json_encode(['success' => false, 'message' => 'Invalid action']);
}

$conn->close();
?>
