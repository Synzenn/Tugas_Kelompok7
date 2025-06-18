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
    <title>Kelola User - Admin Panel</title>
    <link rel="icon" type="image/x-icon" href="../assets/favicon.ico" />
    <link rel="stylesheet" href="../css/admin.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    </head>
<body>
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <h3><i class="fas fa-crown"></i> Admin Panel</h3>
            <button class="sidebar-toggle" onclick="kelolaUser.toggleSidebar()"><i class="fas fa-bars"></i></button>
        </div>
        <nav class="sidebar-nav">
            <ul>
                <li class="nav-item"><a href="admin.php" class="nav-link"><i class="fas fa-tachometer-alt"></i><span>Dashboard</span></a></li>
                <li class="nav-item active"><a href="kelolauser.php" class="nav-link"><i class="fas fa-users"></i><span>Kelola User</span></a></li>
                <li class="nav-item"><a href="#" onclick="kelolaUser.showAlert('info', 'Fitur ini akan dikembangkan.')" class="nav-link"><i class="fas fa-file-alt"></i><span>Kelola Konten</span></a></li>
                <li class="nav-item"><a href="#" onclick="kelolaUser.showAlert('info', 'Fitur ini akan dikembangkan.')" class="nav-link"><i class="fas fa-cog"></i><span>Pengaturan</span></a></li>
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
            <div class="topbar-left">
                <button class="menu-toggle" onclick="kelolaUser.toggleSidebar()"><i class="fas fa-bars"></i></button>
                <h1 class="page-title">Kelola User</h1>
            </div>
            <div class="topbar-right">
                <div class="admin-info">
                    <span>Selamat datang, <span><?php echo htmlspecialchars($_SESSION['name']); ?></span></span>
                    <div class="admin-avatar"><i class="fas fa-user-shield"></i></div>
                </div>
            </div>
        </div>

        <div id="users-section" class="content-section active">
            <div class="section-header">
                <h2>Daftar User</h2>
                <button class="btn btn-primary" onclick="kelolaUser.showAddUserForm()"><i class="fas fa-plus"></i> Tambah User</button>
            </div>
            <div id="userForm" class="form-container" style="display: none;">
                <div class="form-header">
                    <h3 id="userFormTitle"></h3>
                    <button class="close-btn" onclick="kelolaUser.hideUserForm()"><i class="fas fa-times"></i></button>
                </div>
                <form id="userFormElement">
                    <input type="hidden" id="userId" name="userId">
                    <div class="form-row">
                        <div class="form-group"><label for="userName">Nama Lengkap</label><input type="text" id="userName" name="userName" required></div>
                        <div class="form-group"><label for="userEmail">Email</label><input type="email" id="userEmail" name="userEmail" required></div>
                    </div>
                    <div class="form-row">
                        <div class="form-group"><label for="userPassword">Password</label><input type="password" id="userPassword" name="userPassword"><small class="text-muted" id="passwordHint">Kosongkan jika tidak ingin mengubah.</small></div>
                        <div class="form-group"><label for="userRole">Role</label><select id="userRole" name="userRole" required><option value="user">User</option><option value="admin">Admin</option></select></div>
                    </div>
                    <div class="form-actions"><button type="button" class="btn btn-secondary" onclick="kelolaUser.hideUserForm()">Batal</button><button type="submit" class="btn btn-primary">Simpan</button></div>
                </form>
            </div>
            <div class="table-container">
                <div class="table-header">
                    <div class="table-filters">
                        <input type="text" id="searchUser" placeholder="Cari user..."><select id="roleFilter"><option value="">Semua Role</option><option value="admin">Admin</option><option value="user">User</option></select>
                        <button class="btn btn-secondary" onclick="kelolaUser.loadUsersAndPopulateTable()"><i class="fas fa-sync-alt"></i> Refresh</button>
                    </div>
                </div>
                <table class="data-table" id="usersTable">
                    <thead>
                        <tr>
                            <th>ID</th><th>Avatar</th><th>Nama</th><th>Email</th><th>Role</th><th>Status</th><th>Bergabung</th><th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="usersTableBody"></tbody>
                </table>
            </div>
            <div class="pagination">
                <button class="btn btn-secondary" id="prevBtn"><i class="fas fa-chevron-left"></i> Sebelumnya</button>
                <span id="pageInfo">Halaman 1 dari 1</span>
                <button class="btn btn-secondary" id="nextBtn">Selanjutnya <i class="fas fa-chevron-right"></i></button>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../js/kelolauser.js"></script>
</body>
</html>