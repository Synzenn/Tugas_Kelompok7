<?php
// api/logout.php
session_start();

// Hapus semua variabel sesi
$_SESSION = array();

// Hancurkan sesi
session_destroy();

// Arahkan kembali ke halaman login
header("location: ../login.php");
exit;
?>