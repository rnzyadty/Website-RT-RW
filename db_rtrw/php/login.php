<?php
session_start();
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Preflight CORS
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);

if (!isset($input['username']) || !isset($input['password'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Username dan password harus diisi'
    ]);
    exit;
}

$username = trim($input['username']);
$password = trim($input['password']);

// Koneksi DB
require_once __DIR__ . '/db_connect.php';

// Query user dengan password hash SHA2(256)
$stmt = $conn->prepare("SELECT u.id_user, u.username, u.role, u.status, u.id_warga, u.id_rt, u.id_rw, w.nama_lengkap
                        FROM users u
                        LEFT JOIN warga w ON u.id_warga = w.id_warga
                        WHERE u.username = ? AND u.password = SHA2(?, 256)
                        LIMIT 1");

if (!$stmt) {
    echo json_encode([
        'success' => false,
        'message' => 'Query gagal disiapkan: ' . $conn->error
    ]);
    exit;
}

$stmt->bind_param('ss', $username, $password);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

if (!$user) {
    echo json_encode([
        'success' => false,
        'message' => 'Username atau password salah'
    ]);
    exit;
}

if ($user['status'] !== 'aktif') {
    echo json_encode([
        'success' => false,
        'message' => 'Akun nonaktif. Hubungi admin.'
    ]);
    exit;
}

// Set session
$_SESSION['logged_in'] = true;
$_SESSION['id_user'] = $user['id_user'];
$_SESSION['username'] = $user['username'];
$_SESSION['nama'] = $user['nama_lengkap'] ?? 'User';
$_SESSION['role'] = $user['role'];
$_SESSION['id_warga'] = $user['id_warga'];
$_SESSION['id_rt'] = $user['id_rt'];
$_SESSION['id_rw'] = $user['id_rw'];

// Update last login
$update_stmt = $conn->prepare("UPDATE users SET last_login = NOW() WHERE id_user = ?");
$update_stmt->bind_param('i', $user['id_user']);
$update_stmt->execute();
$update_stmt->close();

$conn->close();

// Respons berhasil
echo json_encode([
    'success' => true,
    'data' => [
        'id_user' => $user['id_user'],
        'username' => $user['username'],
        'nama' => $user['nama_lengkap'] ?? 'User',
        'role' => $user['role'],
        'id_warga' => $user['id_warga'],
        'id_rt' => $user['id_rt'],
        'id_rw' => $user['id_rw'],
        'redirect' => getRedirectUrl($user['role'])
    ]
]);

// Helper untuk halaman redirect
function getRedirectUrl($role) {
    switch ($role) {
        case 'warga':
            return 'pages/dashboard-warga.html';
        case 'rt':
            return 'pages/dashboard-rt.html';
        case 'rw':
            return 'pages/dashboard-rw.html';
        case 'admin':
            return 'pages/dashboard-rw.html';
        default:
            return 'index.php';
    }
}
?>
