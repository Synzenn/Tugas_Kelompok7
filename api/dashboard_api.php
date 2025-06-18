<?php
// api/dashboard_api.php (Versi Sederhana)

require 'db_connect.php';
session_start();

// Hanya admin yang bisa mengakses
if (!isset($_SESSION['loggedin']) || $_SESSION['role'] !== 'admin') {
    echo json_encode(['success' => false, 'message' => 'Akses ditolak']);
    exit;
}

// Ambil total user
$totalUsersResult = $conn->query("SELECT COUNT(id) as total FROM users");
$totalUsers = $totalUsersResult->fetch_assoc()['total'];

// Ambil user baru hari ini
$newUsersResult = $conn->query("SELECT COUNT(id) as total FROM users WHERE DATE(joined_date) = CURDATE()");
$newUsersToday = $newUsersResult->fetch_assoc()['total'];

// Kirim data sebagai JSON
echo json_encode([
    'success' => true,
    'data' => [
        'totalUsers' => $totalUsers,
        'newUsersToday' => $newUsersToday
    ]
]);

$conn->close();
?>