<?php
// api/db_connect.php

$host = 'localhost';
$db_user = 'root';      // User default XAMPP
$db_pass = '';      // Password default XAMPP (kosong)
$db_name = 'batik'; // <-- GANTI DENGAN NAMA DATABASE ANDA

$conn = new mysqli($host, $db_user, $db_pass, $db_name);

// Cek koneksi
if ($conn->connect_error) {
    // Hentikan eksekusi dan kirim pesan error dalam format JSON
    header('Content-Type: application/json');
    die(json_encode(['success' => false, 'message' => 'Koneksi database gagal: ' . $conn->connect_error]));
}

// Atur header default untuk output JSON di file yang menggunakan file ini
header('Content-Type: application/json');
?>