<?php
// pmbt/login.php
session_start();

// Jika user sudah login, langsung arahkan ke halaman yang sesuai
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    if ($_SESSION['role'] === 'admin') {
        // PERBAIKAN: Arahkan ke admin.php, bukan index.php
        header('Location: index.php'); 
    } else {
        // PERBAIKAN: Arahkan ke root index.php
        header('Location: index.php'); 
    }
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Login - Nusantara Heritage</title>
    <link rel="icon" type="image/x-icon" href="../assets/favicon.ico" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/login.css" rel="stylesheet" /> 
</head>
<body>
    <div class="batik-pattern"></div> 
    <a href="index.php" class="back-to-home btn btn-outline-light">
        <i class="fas fa-arrow-left me-2"></i>Kembali ke Beranda
    </a>
    <div class="container d-flex align-items-center justify-content-center min-vh-100">
        <div class="row w-100 justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="login-container">
                    <div class="login-header">
                        <h2 id="formTitle">Masuk ke Akun</h2>
                        <p id="formSubtitle">Silakan masuk untuk mengakses fitur lengkap</p>
                    </div>
                    
                    <form id="loginForm">
                        <div class="form-floating mb-3"><input type="email" class="form-control" id="loginEmail" placeholder="name@example.com"><label for="loginEmail"><i class="fas fa-envelope me-2"></i>Email</label></div>
                        <div class="form-floating mb-4"><input type="password" class="form-control" id="loginPassword" placeholder="Password"><label for="loginPassword"><i class="fas fa-lock me-2"></i>Password</label></div>
                        <div class="d-grid"><button type="submit" class="btn btn-primary"><i class="fas fa-sign-in-alt me-2"></i>Masuk</button></div>
                    </form>
                    
                    <form id="registerForm" style="display: none;">
                        <div class="form-floating mb-3"><input type="text" class="form-control" id="registerName" placeholder="Nama Lengkap"><label for="registerName"><i class="fas fa-user me-2"></i>Nama Lengkap</label></div>
                        <div class="form-floating mb-3"><input type="email" class="form-control" id="registerEmail" placeholder="name@example.com"><label for="registerEmail"><i class="fas fa-envelope me-2"></i>Email</label></div>
                        <div class="form-floating mb-3"><input type="password" class="form-control" id="registerPassword" placeholder="Password"><label for="registerPassword"><i class="fas fa-lock me-2"></i>Password</label></div>
                        <div class="form-floating mb-4"><input type="password" class="form-control" id="confirmPassword" placeholder="Konfirmasi Password"><label for="confirmPassword"><i class="fas fa-lock me-2"></i>Konfirmasi Password</label></div>
                        <div class="d-grid"><button type="submit" class="btn btn-primary"><i class="fas fa-user-plus me-2"></i>Daftar</button></div>
                    </form>
                    
                    <div class="form-switch-container">
                        <p id="switchText">Belum punya akun?</p>
                        <button type="button" class="btn btn-outline-primary" id="switchFormBtn">Daftar Sekarang</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../js/login.js"></script> 
</body>
</html>