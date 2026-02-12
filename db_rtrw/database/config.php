<!--
FILE KONFIGURASI KONEKSI DATABASE
Contoh koneksi untuk PHP menggunakan MySQLi dan PDO
-->

<?php
// ==============================================
// KONFIGURASI DATABASE
// ==============================================

// Ganti nilai ini sesuai dengan konfigurasi MySQL Anda
define('DB_HOST', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'db_rtrw');
define('DB_PORT', 3306);

// ==============================================
// KONEKSI MENGGUNAKAN MySQLi
// ==============================================

function connectMySQLi() {
    $conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME, DB_PORT);
    
    // Cek koneksi
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    // Set charset ke UTF-8
    $conn->set_charset("utf8mb4");
    
    return $conn;
}

// ==============================================
// KONEKSI MENGGUNAKAN PDO
// ==============================================

function connectPDO() {
    try {
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4;port=" . DB_PORT;
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];
        
        $pdo = new PDO($dsn, DB_USERNAME, DB_PASSWORD, $options);
        return $pdo;
        
    } catch(PDOException $e) {
        die("Connection failed: " . $e->getMessage());
    }
}

// ==============================================
// CONTOH PENGGUNAAN
// ==============================================

// Contoh 1: Menggunakan MySQLi
$mysqli = connectMySQLi();
echo "Connected successfully with MySQLi!<br>";

// Query example dengan MySQLi
$sql = "SELECT * FROM users WHERE role = 'warga' LIMIT 5";
$result = $mysqli->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "Username: " . $row["username"] . "<br>";
    }
}

$mysqli->close();

// Contoh 2: Menggunakan PDO
$pdo = connectPDO();
echo "Connected successfully with PDO!<br>";

// Query example dengan PDO (prepared statement)
$stmt = $pdo->prepare("SELECT * FROM users WHERE role = :role LIMIT 5");
$stmt->execute(['role' => 'warga']);
$users = $stmt->fetchAll();

foreach ($users as $user) {
    echo "Username: " . $user['username'] . "<br>";
}

// ==============================================
// FUNGSI LOGIN (CONTOH)
// ==============================================

function loginUser($username, $password) {
    $pdo = connectPDO();
    
    // Query untuk cek user
    $sql = "SELECT u.*, w.nama_lengkap, rt.nomor_rt, rw.nomor_rw
            FROM users u
            LEFT JOIN warga w ON u.id_warga = w.id_warga
            LEFT JOIN rt ON u.id_rt = rt.id_rt
            LEFT JOIN rw ON u.id_rw = rw.id_rw
            WHERE u.username = :username 
              AND u.password = SHA2(:password, 256)
              AND u.status = 'aktif'";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'username' => $username,
        'password' => $password
    ]);
    
    $user = $stmt->fetch();
    
    if ($user) {
        // Login berhasil
        session_start();
        $_SESSION['user_id'] = $user['id_user'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['nama'] = $user['nama_lengkap'] ?? 'Admin';
        
        return [
            'success' => true,
            'message' => 'Login berhasil',
            'user' => $user
        ];
    } else {
        // Login gagal
        return [
            'success' => false,
            'message' => 'Username atau password salah'
        ];
    }
}

// ==============================================
// FUNGSI HELPER
// ==============================================

// Fungsi untuk escape string (mencegah SQL injection)
function escapeString($conn, $string) {
    return $conn->real_escape_string($string);
}

// Fungsi untuk mendapatkan data warga berdasarkan ID
function getWargaById($id_warga) {
    $pdo = connectPDO();
    
    $stmt = $pdo->prepare("SELECT w.*, kk.nomor_kk, kk.alamat, kk.nama_kepala_keluarga
                           FROM warga w
                           JOIN kepala_keluarga kk ON w.id_kk = kk.id_kk
                           WHERE w.id_warga = :id_warga");
    $stmt->execute(['id_warga' => $id_warga]);
    
    return $stmt->fetch();
}

// Fungsi untuk mendapatkan pengajuan surat warga
function getPengajuanSuratWarga($id_warga) {
    $pdo = connectPDO();
    
    $stmt = $pdo->prepare("SELECT ps.*, js.nama_surat
                           FROM pengajuan_surat ps
                           JOIN jenis_surat js ON ps.id_jenis_surat = js.id_jenis_surat
                           WHERE ps.id_warga = :id_warga
                           ORDER BY ps.tanggal_pengajuan DESC");
    $stmt->execute(['id_warga' => $id_warga]);
    
    return $stmt->fetchAll();
}

// Fungsi untuk mendapatkan iuran warga
function getIuranWarga($id_kk, $tahun = null) {
    $pdo = connectPDO();
    
    if ($tahun) {
        $stmt = $pdo->prepare("SELECT * FROM iuran_warga 
                              WHERE id_kk = :id_kk AND tahun = :tahun
                              ORDER BY tahun DESC, bulan DESC");
        $stmt->execute(['id_kk' => $id_kk, 'tahun' => $tahun]);
    } else {
        $stmt = $pdo->prepare("SELECT * FROM iuran_warga 
                              WHERE id_kk = :id_kk
                              ORDER BY tahun DESC, bulan DESC");
        $stmt->execute(['id_kk' => $id_kk]);
    }
    
    return $stmt->fetchAll();
}

?>
