<?php
/**
 * API ENDPOINT UNTUK SISTEM RT/RW
 * Contoh REST API sederhana menggunakan PHP
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

require_once 'config.php';

// Ambil request method dan endpoint
$method = $_SERVER['REQUEST_METHOD'];
$request = isset($_GET['action']) ? $_GET['action'] : '';

// Router
switch ($request) {
    case 'login':
        if ($method === 'POST') {
            handleLogin();
        }
        break;
    
    case 'pengajuan-surat':
        if ($method === 'GET') {
            getPengajuanSurat();
        } elseif ($method === 'POST') {
            createPengajuanSurat();
        }
        break;
    
    case 'iuran':
        if ($method === 'GET') {
            getIuranWarga();
        }
        break;
    
    case 'pengumuman':
        if ($method === 'GET') {
            getPengumuman();
        }
        break;
    
    case 'aduan':
        if ($method === 'GET') {
            getAduan();
        } elseif ($method === 'POST') {
            createAduan();
        }
        break;
    
    case 'dashboard-warga':
        if ($method === 'GET') {
            getDashboardWarga();
        }
        break;
    
    case 'dashboard-rt':
        if ($method === 'GET') {
            getDashboardRT();
        }
        break;
    
    case 'dashboard-rw':
        if ($method === 'GET') {
            getDashboardRW();
        }
        break;
    
    default:
        sendResponse(404, 'Endpoint not found');
        break;
}

// ==============================================
// FUNGSI HANDLER
// ==============================================

/**
 * Login User
 * POST /api.php?action=login
 * Body: { "username": "budi_santoso", "password": "password123" }
 */
function handleLogin() {
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($data['username']) || !isset($data['password'])) {
        sendResponse(400, 'Username dan password harus diisi');
        return;
    }
    
    $result = loginUser($data['username'], $data['password']);
    
    if ($result['success']) {
        sendResponse(200, 'Login berhasil', $result['user']);
    } else {
        sendResponse(401, $result['message']);
    }
}

/**
 * Get Pengajuan Surat
 * GET /api.php?action=pengajuan-surat&id_warga=1
 */
function getPengajuanSurat() {
    if (!isset($_GET['id_warga'])) {
        sendResponse(400, 'Parameter id_warga harus diisi');
        return;
    }
    
    $id_warga = $_GET['id_warga'];
    $pdo = connectPDO();
    
    $stmt = $pdo->prepare("SELECT ps.*, js.nama_surat, js.biaya_admin
                           FROM pengajuan_surat ps
                           JOIN jenis_surat js ON ps.id_jenis_surat = js.id_jenis_surat
                           WHERE ps.id_warga = :id_warga
                           ORDER BY ps.tanggal_pengajuan DESC");
    $stmt->execute(['id_warga' => $id_warga]);
    $data = $stmt->fetchAll();
    
    sendResponse(200, 'Data berhasil diambil', $data);
}

/**
 * Create Pengajuan Surat
 * POST /api.php?action=pengajuan-surat
 * Body: { "id_warga": 1, "id_jenis_surat": 2, "tujuan_surat": "...", "keterangan_tambahan": "..." }
 */
function createPengajuanSurat() {
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($data['id_warga']) || !isset($data['id_jenis_surat'])) {
        sendResponse(400, 'Data tidak lengkap');
        return;
    }
    
    $pdo = connectPDO();
    
    // Generate nomor surat (simplified)
    $tahun = date('Y');
    $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM pengajuan_surat WHERE YEAR(tanggal_pengajuan) = :tahun");
    $stmt->execute(['tahun' => $tahun]);
    $count = $stmt->fetch()['total'] + 1;
    $nomor_surat = sprintf("SRT/RT05/%03d/%s", $count, $tahun);
    
    // Insert pengajuan
    $sql = "INSERT INTO pengajuan_surat (id_warga, id_jenis_surat, nomor_surat, tujuan_surat, keterangan_tambahan, status_pengajuan)
            VALUES (:id_warga, :id_jenis_surat, :nomor_surat, :tujuan_surat, :keterangan_tambahan, 'pending')";
    
    $stmt = $pdo->prepare($sql);
    $result = $stmt->execute([
        'id_warga' => $data['id_warga'],
        'id_jenis_surat' => $data['id_jenis_surat'],
        'nomor_surat' => $nomor_surat,
        'tujuan_surat' => $data['tujuan_surat'] ?? '',
        'keterangan_tambahan' => $data['keterangan_tambahan'] ?? ''
    ]);
    
    if ($result) {
        sendResponse(201, 'Pengajuan surat berhasil dibuat', [
            'id_pengajuan' => $pdo->lastInsertId(),
            'nomor_surat' => $nomor_surat
        ]);
    } else {
        sendResponse(500, 'Gagal membuat pengajuan');
    }
}

/**
 * Get Iuran Warga
 * GET /api.php?action=iuran&id_kk=1&tahun=2025
 */
function getIuranWarga() {
    if (!isset($_GET['id_kk'])) {
        sendResponse(400, 'Parameter id_kk harus diisi');
        return;
    }
    
    $id_kk = $_GET['id_kk'];
    $tahun = $_GET['tahun'] ?? date('Y');
    
    $pdo = connectPDO();
    
    $stmt = $pdo->prepare("SELECT * FROM iuran_warga 
                          WHERE id_kk = :id_kk AND tahun = :tahun
                          ORDER BY bulan DESC");
    $stmt->execute(['id_kk' => $id_kk, 'tahun' => $tahun]);
    $data = $stmt->fetchAll();
    
    // Hitung total
    $total_tagihan = 0;
    $total_terbayar = 0;
    $total_tunggakan = 0;
    
    foreach ($data as $iuran) {
        $total_tagihan += $iuran['nominal'];
        if ($iuran['status_bayar'] === 'lunas') {
            $total_terbayar += $iuran['nominal'];
        } else {
            $total_tunggakan += $iuran['nominal'];
        }
    }
    
    sendResponse(200, 'Data berhasil diambil', [
        'iuran' => $data,
        'summary' => [
            'total_tagihan' => $total_tagihan,
            'total_terbayar' => $total_terbayar,
            'total_tunggakan' => $total_tunggakan
        ]
    ]);
}

/**
 * Get Pengumuman
 * GET /api.php?action=pengumuman&id_rt=5
 */
function getPengumuman() {
    $pdo = connectPDO();
    
    $sql = "SELECT * FROM pengumuman 
            WHERE status = 'published'
              AND (tanggal_berakhir IS NULL OR tanggal_berakhir >= CURDATE())
            ORDER BY prioritas DESC, tanggal_mulai DESC
            LIMIT 10";
    
    if (isset($_GET['id_rt'])) {
        $sql = "SELECT * FROM pengumuman 
                WHERE status = 'published'
                  AND (tanggal_berakhir IS NULL OR tanggal_berakhir >= CURDATE())
                  AND (target_audience = 'semua' OR id_rt = :id_rt)
                ORDER BY prioritas DESC, tanggal_mulai DESC
                LIMIT 10";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id_rt' => $_GET['id_rt']]);
    } else {
        $stmt = $pdo->query($sql);
    }
    
    $data = $stmt->fetchAll();
    sendResponse(200, 'Data berhasil diambil', $data);
}

/**
 * Get Aduan
 * GET /api.php?action=aduan&id_warga=1
 */
function getAduan() {
    if (!isset($_GET['id_warga'])) {
        sendResponse(400, 'Parameter id_warga harus diisi');
        return;
    }
    
    $pdo = connectPDO();
    
    $stmt = $pdo->prepare("SELECT * FROM aduan 
                          WHERE id_warga = :id_warga
                          ORDER BY tanggal_aduan DESC");
    $stmt->execute(['id_warga' => $_GET['id_warga']]);
    $data = $stmt->fetchAll();
    
    sendResponse(200, 'Data berhasil diambil', $data);
}

/**
 * Create Aduan
 * POST /api.php?action=aduan
 * Body: { "id_warga": 1, "judul_aduan": "...", "kategori": "infrastruktur", "isi_aduan": "...", "lokasi": "..." }
 */
function createAduan() {
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($data['id_warga']) || !isset($data['judul_aduan']) || !isset($data['kategori']) || !isset($data['isi_aduan'])) {
        sendResponse(400, 'Data tidak lengkap');
        return;
    }
    
    $pdo = connectPDO();
    
    $sql = "INSERT INTO aduan (id_warga, judul_aduan, kategori, isi_aduan, lokasi, status, prioritas)
            VALUES (:id_warga, :judul_aduan, :kategori, :isi_aduan, :lokasi, 'baru', 'sedang')";
    
    $stmt = $pdo->prepare($sql);
    $result = $stmt->execute([
        'id_warga' => $data['id_warga'],
        'judul_aduan' => $data['judul_aduan'],
        'kategori' => $data['kategori'],
        'isi_aduan' => $data['isi_aduan'],
        'lokasi' => $data['lokasi'] ?? ''
    ]);
    
    if ($result) {
        sendResponse(201, 'Aduan berhasil dibuat', ['id_aduan' => $pdo->lastInsertId()]);
    } else {
        sendResponse(500, 'Gagal membuat aduan');
    }
}

/**
 * Dashboard Warga
 * GET /api.php?action=dashboard-warga&id_warga=1
 */
function getDashboardWarga() {
    if (!isset($_GET['id_warga'])) {
        sendResponse(400, 'Parameter id_warga harus diisi');
        return;
    }
    
    $id_warga = $_GET['id_warga'];
    $pdo = connectPDO();
    
    // Get data warga
    $warga = getWargaById($id_warga);
    
    // Get pengajuan surat (3 terbaru)
    $stmt = $pdo->prepare("SELECT ps.*, js.nama_surat
                           FROM pengajuan_surat ps
                           JOIN jenis_surat js ON ps.id_jenis_surat = js.id_jenis_surat
                           WHERE ps.id_warga = :id_warga
                           ORDER BY ps.tanggal_pengajuan DESC
                           LIMIT 3");
    $stmt->execute(['id_warga' => $id_warga]);
    $pengajuan_surat = $stmt->fetchAll();
    
    // Get iuran (tahun ini)
    $tahun = date('Y');
    $stmt = $pdo->prepare("SELECT * FROM iuran_warga 
                          WHERE id_kk = :id_kk AND tahun = :tahun
                          ORDER BY bulan DESC");
    $stmt->execute(['id_kk' => $warga['id_kk'], 'tahun' => $tahun]);
    $iuran = $stmt->fetchAll();
    
    // Get pengumuman terbaru
    $stmt = $pdo->prepare("SELECT * FROM pengumuman 
                          WHERE status = 'published'
                            AND (tanggal_berakhir IS NULL OR tanggal_berakhir >= CURDATE())
                          ORDER BY prioritas DESC, tanggal_mulai DESC
                          LIMIT 3");
    $stmt->execute();
    $pengumuman = $stmt->fetchAll();
    
    sendResponse(200, 'Dashboard warga berhasil diambil', [
        'warga' => $warga,
        'pengajuan_surat' => $pengajuan_surat,
        'iuran' => $iuran,
        'pengumuman' => $pengumuman
    ]);
}

/**
 * Dashboard RT
 * GET /api.php?action=dashboard-rt&id_rt=5
 */
function getDashboardRT() {
    if (!isset($_GET['id_rt'])) {
        sendResponse(400, 'Parameter id_rt harus diisi');
        return;
    }
    
    $id_rt = $_GET['id_rt'];
    $pdo = connectPDO();
    
    // Permohonan surat pending
    $stmt = $pdo->prepare("SELECT ps.*, w.nama_lengkap, js.nama_surat, kk.alamat, kk.nomor_kk
                          FROM pengajuan_surat ps
                          JOIN warga w ON ps.id_warga = w.id_warga
                          JOIN kepala_keluarga kk ON w.id_kk = kk.id_kk
                          JOIN jenis_surat js ON ps.id_jenis_surat = js.id_jenis_surat
                          WHERE kk.id_rt = :id_rt AND ps.status_pengajuan = 'pending'
                          ORDER BY ps.tanggal_pengajuan ASC");
    $stmt->execute(['id_rt' => $id_rt]);
    $permohonan_pending = $stmt->fetchAll();
    
    // Aduan warga
    $stmt = $pdo->prepare("SELECT a.*, w.nama_lengkap
                          FROM aduan a
                          JOIN warga w ON a.id_warga = w.id_warga
                          JOIN kepala_keluarga kk ON w.id_kk = kk.id_kk
                          WHERE kk.id_rt = :id_rt
                          ORDER BY a.prioritas DESC, a.tanggal_aduan DESC
                          LIMIT 5");
    $stmt->execute(['id_rt' => $id_rt]);
    $aduan = $stmt->fetchAll();
    
    // Statistik warga
    $stmt = $pdo->prepare("SELECT COUNT(DISTINCT kk.id_kk) as jumlah_kk,
                                  COUNT(w.id_warga) as jumlah_warga
                          FROM kepala_keluarga kk
                          LEFT JOIN warga w ON kk.id_kk = w.id_kk AND w.status_hidup = 'Hidup'
                          WHERE kk.id_rt = :id_rt");
    $stmt->execute(['id_rt' => $id_rt]);
    $statistik = $stmt->fetch();
    
    // Tugas hari ini
    $stmt = $pdo->prepare("SELECT * FROM tugas_rt 
                          WHERE id_rt = :id_rt 
                            AND status != 'selesai'
                            AND (deadline IS NULL OR deadline >= CURDATE())
                          ORDER BY prioritas DESC, deadline ASC
                          LIMIT 5");
    $stmt->execute(['id_rt' => $id_rt]);
    $tugas = $stmt->fetchAll();
    
    sendResponse(200, 'Dashboard RT berhasil diambil', [
        'permohonan_pending' => $permohonan_pending,
        'aduan' => $aduan,
        'statistik' => $statistik,
        'tugas' => $tugas
    ]);
}

/**
 * Dashboard RW
 * GET /api.php?action=dashboard-rw&id_rw=1
 */
function getDashboardRW() {
    if (!isset($_GET['id_rw'])) {
        sendResponse(400, 'Parameter id_rw harus diisi');
        return;
    }
    
    $id_rw = $_GET['id_rw'];
    $pdo = connectPDO();
    
    // Statistik per RT
    $stmt = $pdo->prepare("SELECT * FROM view_statistik_warga_per_rt 
                          WHERE id_rt IN (SELECT id_rt FROM rt WHERE id_rw = :id_rw)");
    $stmt->execute(['id_rw' => $id_rw]);
    $statistik_rt = $stmt->fetchAll();
    
    // Rekap surat per RT
    $stmt = $pdo->prepare("SELECT * FROM view_status_surat_per_rt
                          WHERE id_rt IN (SELECT id_rt FROM rt WHERE id_rw = :id_rw)");
    $stmt->execute(['id_rw' => $id_rw]);
    $rekap_surat = $stmt->fetchAll();
    
    // Summary minggu ini
    $stmt = $pdo->prepare("SELECT 
                            COUNT(ps.id_pengajuan) as total_permohonan,
                            SUM(CASE WHEN ps.status_pengajuan IN ('disetujui_rt', 'disetujui_rw', 'selesai') THEN 1 ELSE 0 END) as disetujui,
                            SUM(CASE WHEN ps.status_pengajuan IN ('ditolak_rt', 'ditolak_rw') THEN 1 ELSE 0 END) as ditolak,
                            SUM(CASE WHEN ps.status_pengajuan = 'pending' THEN 1 ELSE 0 END) as pending
                          FROM pengajuan_surat ps
                          JOIN warga w ON ps.id_warga = w.id_warga
                          JOIN kepala_keluarga kk ON w.id_kk = kk.id_kk
                          JOIN rt ON kk.id_rt = rt.id_rt
                          WHERE rt.id_rw = :id_rw
                            AND ps.tanggal_pengajuan >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)");
    $stmt->execute(['id_rw' => $id_rw]);
    $summary = $stmt->fetch();
    
    sendResponse(200, 'Dashboard RW berhasil diambil', [
        'statistik_rt' => $statistik_rt,
        'rekap_surat' => $rekap_surat,
        'summary_minggu_ini' => $summary
    ]);
}

// ==============================================
// HELPER FUNCTION
// ==============================================

function sendResponse($status, $message, $data = null) {
    http_response_code($status);
    
    $response = [
        'status' => $status,
        'message' => $message
    ];
    
    if ($data !== null) {
        $response['data'] = $data;
    }
    
    echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    exit;
}

?>
