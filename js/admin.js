// js/admin.js (Versi Stabil dan Sederhana)

class AdminDashboard {
    constructor() {
        this.init();
    }

    init() {
        // Sekarang JS hanya punya 1 tugas: update angka statistik
        this.updateDashboardStats();
    }

    /**
     * Mengambil data statistik dari API dan menampilkannya di kartu.
     */
    async updateDashboardStats() {
        try {
            // Memanggil API yang sudah disederhanakan
            const response = await fetch('../api/dashboard_api.php');
            if (!response.ok) {
                throw new Error(`HTTP Error: ${response.status}`);
            }
            
            const result = await response.json();

            if (result.success) {
                // Update angka sesuai data dari database
                document.getElementById('totalUsers').textContent = result.data.totalUsers;
                document.getElementById('newUsers').textContent = result.data.newUsersToday;
            } else {
                console.error('Gagal memuat statistik:', result.message);
            }
        } catch (error) {
            console.error('Terjadi kesalahan jaringan saat memuat statistik.', error);
        }
    }
}

// Inisialisasi dashboard saat halaman selesai dimuat.
document.addEventListener('DOMContentLoaded', function() {
    window.adminDashboard = new AdminDashboard();
});