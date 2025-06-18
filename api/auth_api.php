<?php
// api/auth_api.php

require 'db_connect.php'; // Menggunakan koneksi database yang sudah ada
session_start(); // Memulai sesi di setiap permintaan

$action = isset($_GET['action']) ? $_GET['action'] : '';

switch ($action) {
    case 'register':
        registerUser($conn);
        break;
    case 'login':
        loginUser($conn);
        break;

    // --- PENAMBAHAN KODE DIMULAI DI SINI ---
    case 'get_session':
        // Cek apakah ada sesi yang aktif
        if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
            // Jika ada, kirim kembali data sesi sebagai JSON
            echo json_encode([
                'success' => true,
                'data' => [
                    'user_id' => $_SESSION['user_id'],
                    'name' => $_SESSION['name'],
                    'role' => $_SESSION['role']
                ]
            ]);
        } else {
            // Jika tidak ada sesi
            echo json_encode(['success' => false, 'message' => 'Tidak ada sesi aktif.']);
        }
        break;
    // --- PENAMBAHAN KODE BERAKHIR DI SINI ---

    default:
        echo json_encode(['success' => false, 'message' => 'Aksi tidak valid']);
        break;
}

function registerUser($conn) {
    $data = json_decode(file_get_contents('php://input'), true);

    // Validasi di sisi server
    if (empty($data['name']) || empty($data['email']) || empty($data['password'])) {
        echo json_encode(['success' => false, 'message' => 'Semua field harus diisi.']);
        return;
    }
    if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'message' => 'Format email tidak valid.']);
        return;
    }
    if (strlen($data['password']) < 6) {
        echo json_encode(['success' => false, 'message' => 'Password minimal 6 karakter.']);
        return;
    }

    $name = $data['name'];
    $email = $data['email'];
    // SANGAT PENTING: Hash password sebelum disimpan ke database
    $password_hash = password_hash($data['password'], PASSWORD_BCRYPT);
    $role = 'user'; // Semua pendaftaran baru otomatis menjadi 'user'

    // Cek apakah email sudah ada
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        echo json_encode(['success' => false, 'message' => 'Email sudah terdaftar.']);
        $stmt->close();
        return;
    }
    $stmt->close();

    // Masukkan user baru ke database
    $stmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $email, $password_hash, $role);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Registrasi berhasil! Silakan login.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Registrasi gagal, terjadi kesalahan server.']);
    }
    $stmt->close();
}

function loginUser($conn) {
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (empty($data['email']) || empty($data['password'])) {
        echo json_encode(['success' => false, 'message' => 'Email dan password harus diisi.']);
        return;
    }

    $email = $data['email'];
    $password = $data['password'];

    $stmt = $conn->prepare("SELECT id, name, email, password, role FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        
        // Verifikasi password yang di-hash
        if (password_verify($password, $user['password'])) {
            // Jika password benar, buat sesi
            $_SESSION['loggedin'] = true;
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['role'] = $user['role'];

            echo json_encode(['success' => true, 'message' => 'Login berhasil!', 'role' => $user['role']]);
        } else {
            // Password salah
            echo json_encode(['success' => false, 'message' => 'Email atau password salah.']);
        }
    } else {
        // Email tidak ditemukan
        echo json_encode(['success' => false, 'message' => 'Email atau password salah.']);
    }
    $stmt->close();
}

$conn->close();
?>