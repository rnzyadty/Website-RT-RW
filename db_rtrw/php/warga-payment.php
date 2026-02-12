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

if ($method === 'GET' && $action === 'generate_receipt') {
    // Generate receipt HTML
    $id_iuran = $_GET['id_iuran'] ?? null;
    
    if (!$id_iuran) {
        echo json_encode(['success' => false, 'message' => 'ID iuran harus diisi']);
        exit;
    }
    
    // Get iuran data
    $stmt = $conn->prepare("
        SELECT iw.*, kk.nomor_kk, kk.nama_kepala_keluarga, kk.alamat
        FROM iuran_warga iw
        LEFT JOIN kepala_keluarga kk ON iw.id_kk = kk.id_kk
        WHERE iw.id_iuran = ?
    ");
    
    $stmt->bind_param('i', $id_iuran);
    $stmt->execute();
    $result = $stmt->get_result();
    $iuran = $result->fetch_assoc();
    $stmt->close();
    
    if (!$iuran) {
        echo json_encode(['success' => false, 'message' => 'Iuran not found']);
        exit;
    }
    
    // Generate receipt HTML
    $html = generateReceipt($iuran);
    
    echo json_encode(['success' => true, 'html' => $html]);
}

elseif ($method === 'GET' && $action === 'payment_history') {
    // Get payment history
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
        WHERE id_kk = ? AND status_bayar = 'lunas'
        ORDER BY tahun DESC, bulan DESC
    ");
    
    $stmt->bind_param('i', $id_kk);
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

// Generate receipt HTML
function generateReceipt($iuran) {
    $nomor_kk = $iuran['nomor_kk'];
    $nama = $iuran['nama_kepala_keluarga'];
    $alamat = $iuran['alamat'];
    $bulan = intval($iuran['bulan']);
    $tahun = intval($iuran['tahun']);
    $nominal = number_format($iuran['nominal'], 0, ',', '.');
    $metode = $iuran['metode_bayar'] ?? 'Tidak diketahui';
    $tanggal = date('d F Y', strtotime($iuran['tanggal_bayar']));
    
    // Get month name
    $bulan_nama = [
        'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    ][$bulan - 1] ?? 'Bulan ' . $bulan;
    
    $html = <<<HTML
    <html>
    <head>
        <meta charset="UTF-8">
        <title>Bukti Pembayaran Iuran</title>
        <style>
            body { font-family: Arial, sans-serif; padding: 20px; }
            .receipt { max-width: 600px; border: 1px solid #333; padding: 30px; }
            .header { text-align: center; margin-bottom: 30px; font-weight: bold; font-size: 18px; }
            .content { margin: 20px 0; }
            .row { display: flex; margin: 10px 0; }
            .label { width: 150px; font-weight: bold; }
            .value { flex: 1; }
            .footer { text-align: center; margin-top: 30px; font-size: 12px; color: #666; }
            .seal { text-align: center; margin-top: 30px; font-size: 50px; }
        </style>
    </head>
    <body>
        <div class="receipt">
            <div class="header">BUKTI PEMBAYARAN IURAN RT/RW</div>
            
            <div class="content">
                <div class="row">
                    <div class="label">Nomor KK:</div>
                    <div class="value">$nomor_kk</div>
                </div>
                <div class="row">
                    <div class="label">Nama Penerima:</div>
                    <div class="value">$nama</div>
                </div>
                <div class="row">
                    <div class="label">Alamat:</div>
                    <div class="value">$alamat</div>
                </div>
                <div class="row">
                    <div class="label">Bulan:</div>
                    <div class="value">$bulan_nama $tahun</div>
                </div>
                <div class="row">
                    <div class="label">Nominal:</div>
                    <div class="value" style="font-weight: bold; font-size: 16px;">Rp $nominal</div>
                </div>
                <div class="row">
                    <div class="label">Metode Bayar:</div>
                    <div class="value">$metode</div>
                </div>
                <div class="row">
                    <div class="label">Tanggal Bayar:</div>
                    <div class="value">$tanggal</div>
                </div>
            </div>
            
            <div class="seal">✓</div>
            
            <div class="footer">
                <p>Bukti pembayaran ini adalah dokumen resmi yang sah.</p>
                <p>Dicetak pada: {DATE}</p>
            </div>
        </div>
    </body>
    </html>
    HTML;
    
    return str_replace('{DATE}', date('d F Y H:i'), $html);
}
?>
