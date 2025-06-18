// GANTI FUNGSI LAMA INI DENGAN YANG BARU
async function updateNavbar() {
    const navbarMenu = document.getElementById('navbarResponsive')?.querySelector('ul');
    if (!navbarMenu) {
        console.error('index.js: Navbar menu element not found!');
        return;
    }

    try {
        // Bertanya ke server tentang status sesi login
        const response = await fetch('../api/auth_api.php?action=get_session');
        const result = await response.json();

        // Kosongkan menu lama
        navbarMenu.innerHTML = `<li class="nav-item"><a class="nav-link px-lg-3 py-3 py-lg-4" href="index.php">Beranda</a></li>`;

        if (result.success && result.data) {
            // JIKA USER SUDAH LOGIN (Server merespon dengan data sesi)
            const currentUser = result.data;
            
            // Tambahkan menu lengkap
            navbarMenu.innerHTML += `
                <li class="nav-item dropdown" id="budayaNav">
                    <a class="nav-link dropdown-toggle px-lg-3 py-3 py-lg-4" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Budaya
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="batik.html">Batik</a></li>
                        <li><a class="dropdown-item" href="musik.html">Alat Musik</a></li>
                        <li><a class="dropdown-item" href="tari.html">Tari Tradisional</a></li>
                    </ul>
                </li>
                <li class="nav-item"><a class="nav-link px-lg-3 py-3 py-lg-4" href="sejarah.html">Sejarah</a></li>
                <li class="nav-item"><a class="nav-link px-lg-3 py-3 py-lg-4" href="contact.html">Kontak</a></li>
            `;

            if (currentUser.role === 'admin') {
                navbarMenu.innerHTML += `
                    <li class="nav-item"><a class="nav-link px-lg-3 py-3 py-lg-4" href="admin.php">Admin Dashboard</a></li>
                `;
            }

            navbarMenu.innerHTML += `
                <li class="nav-item dropdown" id="authNav">
                    <a class="nav-link dropdown-toggle px-lg-3 py-3 py-lg-4" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-user-circle me-1"></i>${currentUser.name}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><span class="dropdown-item-text"><small class="text-muted">Role: ${currentUser.role.charAt(0).toUpperCase() + currentUser.role.slice(1)}</small></span></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="#" id="logoutBtn"><i class="fas fa-sign-out-alt me-1"></i>Logout</a></li>
                    </ul>
                </li>
            `;
        } else {
            // JIKA USER BELUM LOGIN
            navbarMenu.innerHTML += `
                <li class="nav-item" id="authNav">
                    <a class="nav-link px-lg-3 py-3 py-lg-4" href="login.php">Login</a>
                </li>
            `;
        }
    } catch (error) {
        console.error("Error updating navbar:", error);
        // Tampilkan menu default (tamu) jika ada error jaringan
        navbarMenu.innerHTML = `
            <li class="nav-item"><a class="nav-link px-lg-3 py-3 py-lg-4" href="index.php">Beranda</a></li>
            <li class="nav-item"><a class="nav-link px-lg-3 py-3 py-lg-4" href="login.php">Login</a></li>
        `;
    }
}