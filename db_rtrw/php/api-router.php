<?php
// Generic API router - handles all role-based requests

session_start();
header('Content-Type: application/json');

// Define allowed endpoints and their role requirements
$endpoints = [
    // Warga endpoints
    'warga-pengajuan-surat.php' => ['warga'],
    'warga-aduan.php' => ['warga'],
    'warga-iuran.php' => ['warga'],
    'warga-payment.php' => ['warga'],
    
    // RT endpoints
    'rt-pengajuan-surat.php' => ['rt'],
    'rt-aduan.php' => ['rt'],
    'rt-kas.php' => ['rt'],
    'rt-data-warga.php' => ['rt'],
    
    // RW endpoints
    'rw-surat.php' => ['rw', 'admin'],
    'rw-aduan.php' => ['rw', 'admin'],
    'rw-keuangan.php' => ['rw', 'admin'],
    'rw-statistik.php' => ['rw', 'admin'],
    
    // Public endpoints
    'public-content.php' => ['*'],
    'check_session.php' => ['*']
];

// Get request info
$script = basename($_SERVER['PHP_SELF']);
$method = $_SERVER['REQUEST_METHOD'];

// Check if endpoint exists and validate access
if (isset($endpoints[$script])) {
    $allowed_roles = $endpoints[$script];
    
    // Check if public endpoint
    if (!in_array('*', $allowed_roles)) {
        // Check session
        if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'Unauthorized: Not logged in']);
            exit;
        }
        
        // Check role
        if (!in_array($_SESSION['role'], $allowed_roles)) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Forbidden: Invalid role']);
            exit;
        }
    }
}

// Add CORS headers
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Handle preflight
if ($method === 'OPTIONS') {
    exit;
}
?>
