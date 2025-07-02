<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Nusantara Heritage</title>
    <link rel="icon" type="image/x-icon" href="../assets/favicon.ico" />
    <link rel="stylesheet" href="../css/admin.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <h3><i class="fas fa-crown"></i> Admin Panel</h3>
        </div>
        <nav class="sidebar-nav">
            <ul>
                <li class="nav-item active"><a href="admin.php" class="nav-link"><i class="fas fa-tachometer-alt"></i><span>Dashboard</span></a></li>
                <li class="nav-item"><a href="kelolauser.php" class="nav-link"><i class="fas fa-users"></i><span>Kelola User</span></a></li>
            </ul>
        </nav>
        <div class="sidebar-footer">
            <div class="user-info">
                <div class="user-avatar"><i class="fas fa-user-shield"></i></div>
                <div class="user-details">
                    <span class="user-name"><?php echo htmlspecialchars($_SESSION['name']); ?></span>
                    <span class="user-role"><?php echo ucfirst(htmlspecialchars($_SESSION['role'])); ?></span>
                </div>
            </div>
            <a href="index.php" class="btn-logout"><i class="fas fa-sign-out-alt"></i><span>Keluar</span></a>
        </div>
    </div>

    <div class="main-content">
        <div class="topbar">
            <h1 class="page-title">Selamat datang di Admin Dashboard</h1>
            <div class="topbar-right">
                <div class="admin-info">
                    <span>Selamat datang, <span><?php echo htmlspecialchars($_SESSION['name']); ?></span></span>
                    <div class="admin-avatar"><i class="fas fa-user-shield"></i></div>
                </div>
            </div>
        </div>

        <div id="dashboard-section" class="content-section active" style="background-color: transparent; box-shadow: none; padding: 0;">
            <div class="row g-4">
                <div class="col-lg-3 col-md-6">
                    <div class="stat-card">
                        <div class="stat-icon" style="background-color: #eaf2ff; color: #007bff;"><i class="fas fa-users"></i></div>
                        <div class="stat-info"><h3 id="totalUsers">0</h3><p>Total User</p></div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="stat-card">
                        <div class="stat-icon" style="background-color: #eaf9f2; color: #28a745;"><i class="fas fa-user-plus"></i></div>
                        <div class="stat-info"><h3 id="newUsers">0</h3><p>User Baru (Hari Ini)</p></div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="stat-card">
                        <div class="stat-icon" style="background-color: #fff9e6; color: #ffc107;"><i class="fas fa-eye"></i></div>
                        <div class="stat-info"><h3>1,234</h3><p>Pengunjung</p></div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="stat-card">
                        <div class="stat-icon" style="background-color: #e8f7fa; color: #17a2b8;"><i class="fas fa-file-alt"></i></div>
                        <div class="stat-info"><h3>45</h3><p>Artikel</p></div>
                    </div>
                </div>
            </div>

            <div class="welcome-card mt-4">
                <h2>Selamat datang di Admin Dashboard</h2>
                <p>Kelola sistem Nusantara Heritage dengan mudah melalui panel admin ini.</p>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../js/admin.js"></script>
</body>
</html>