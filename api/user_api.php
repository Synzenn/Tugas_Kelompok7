<?php
// api/user_api.php

require 'db_connect.php';

// PENTING: Untuk aplikasi nyata, implementasikan sistem sesi (login) di sini
// untuk memastikan hanya admin yang bisa melakukan operasi ini.
// Contoh sederhana:
// session_start();
// if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
//     echo json_encode(['success' => false, 'message' => 'Akses ditolak']);
//     exit();
// }


$action = isset($_GET['action']) ? $_GET['action'] : '';

switch ($action) {
    case 'get_users':
        getUsers($conn);
        break;
    case 'add_user':
        addUser($conn);
        break;
    case 'update_user':
        updateUser($conn);
        break;
    case 'delete_user':
        deleteUser($conn);
        break;
    default:
        echo json_encode(['success' => false, 'message' => 'Aksi tidak valid']);
        break;
}

// Fungsi untuk READ (Membaca semua user)
function getUsers($conn) {
    $result = $conn->query("SELECT id, name, email, role, joined_date FROM users ORDER BY id DESC");
    $users = [];
    while($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
    echo json_encode(['success' => true, 'data' => $users]);
}

// Fungsi untuk CREATE (Menambah user)
function addUser($conn) {
    $data = json_decode(file_get_contents('php://input'), true);

    $name = $data['name'];
    $email = $data['email'];
    $password = password_hash($data['password'], PASSWORD_BCRYPT); // Hashing password
    $role = $data['role'];

    $stmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $email, $password, $role);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'User berhasil ditambahkan']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Gagal menambahkan user: ' . $stmt->error]);
    }
    $stmt->close();
}

// Fungsi untuk UPDATE (Mengubah user)
function updateUser($conn) {
    $data = json_decode(file_get_contents('php://input'), true);
    
    $id = $data['id'];
    $name = $data['name'];
    $email = $data['email'];
    $role = $data['role'];

    // Hanya update password jika diisi
    if (!empty($data['password'])) {
        $password = password_hash($data['password'], PASSWORD_BCRYPT);
        $stmt = $conn->prepare("UPDATE users SET name=?, email=?, role=?, password=? WHERE id=?");
        $stmt->bind_param("ssssi", $name, $email, $role, $password, $id);
    } else {
        $stmt = $conn->prepare("UPDATE users SET name=?, email=?, role=? WHERE id=?");
        $stmt->bind_param("sssi", $name, $email, $role, $id);
    }

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'User berhasil diupdate']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Gagal mengupdate user: ' . $stmt->error]);
    }
    $stmt->close();
}

// Fungsi untuk DELETE (Menghapus user)
function deleteUser($conn) {
    $data = json_decode(file_get_contents('php://input'), true);
    $id = $data['id'];

    $stmt = $conn->prepare("DELETE FROM users WHERE id=?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'User berhasil dihapus']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Gagal menghapus user']);
    }
    $stmt->close();
}

$conn->close();
?>