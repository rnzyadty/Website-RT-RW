<?php
session_start();
header('Content-Type: application/json');

// Check if logged in
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header('Location: ../index.php');
    exit;
}

$_SESSION_DATA = [
    'id_user' => $_SESSION['id_user'] ?? null,
    'username' => $_SESSION['username'] ?? '',
    'nama' => $_SESSION['nama'] ?? '',
    'role' => $_SESSION['role'] ?? '',
    'id_warga' => $_SESSION['id_warga'] ?? null,
    'id_rt' => $_SESSION['id_rt'] ?? null,
    'id_rw' => $_SESSION['id_rw'] ?? null
];

// Check role access
$requested_role = $_GET['role'] ?? '';
if ($requested_role && $_SESSION['role'] !== $requested_role) {
    header('Location: ../index.php');
    exit;
}
?>
