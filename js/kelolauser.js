// js/kelolauser.js (Versi dengan Tampilan dan Konfirmasi Baru)

class KelolaUserManager {
    constructor() {
        this.allUsers = [];
        this.filteredUsers = [];
        this.currentAdminId = null;
        this.currentPage = 1;
        this.rowsPerPage = 10;
        this.init();
    }

    async init() {
        await this.fetchAdminSession();
        this.setupEventListeners();
        this.loadUsersAndPopulateTable();
    }

    async fetchAdminSession() {
        try {
            const response = await fetch('../api/auth_api.php?action=get_session');
            if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
            const result = await response.json();
            if (result.success && result.data) {
                this.currentAdminId = parseInt(result.data.user_id, 10);
            }
        } catch (error) {
            console.error('Error fetching admin session:', error);
            this.showAlert('error', 'Gagal memuat sesi admin.');
        }
    }

    setupEventListeners() {
        document.querySelector('.sidebar-toggle')?.addEventListener('click', this.toggleSidebar.bind(this));
        document.querySelector('.topbar .menu-toggle')?.addEventListener('click', this.toggleSidebar.bind(this));
        document.getElementById('userFormElement').addEventListener('submit', (e) => this.handleUserFormSubmit(e));
        document.getElementById('prevBtn').addEventListener('click', () => this.previousPage());
        document.getElementById('nextBtn').addEventListener('click', () => this.nextPage());
        document.getElementById('searchUser').addEventListener('keyup', () => this.applyFilters());
        document.getElementById('roleFilter').addEventListener('change', () => this.applyFilters());
    }
    
    toggleSidebar() { document.body.classList.toggle('sidebar-closed'); }

    async loadUsersAndPopulateTable() {
        try {
            const response = await fetch('../api/user_api.php?action=get_users');
            if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
            const result = await response.json();
            if (result.success) {
                this.allUsers = result.data;
                this.applyFilters();
            } else {
                this.showAlert('error', 'Gagal memuat data user: ' + result.message);
            }
        } catch (error) {
            this.showAlert('error', 'Gagal memuat data user. Periksa Network Tab.');
        }
    }
    
    applyFilters() {
        const searchTerm = document.getElementById('searchUser').value.toLowerCase();
        const roleFilter = document.getElementById('roleFilter').value;
        this.filteredUsers = this.allUsers.filter(user => 
            (user.name.toLowerCase().includes(searchTerm) || user.email.toLowerCase().includes(searchTerm)) &&
            (roleFilter === '' || user.role === roleFilter)
        );
        this.currentPage = 1;
        this.populateUserTable();
    }

    populateUserTable() {
        const tableBody = document.getElementById('usersTableBody');
        tableBody.innerHTML = '';
        const startIndex = (this.currentPage - 1) * this.rowsPerPage;
        const usersToDisplay = this.filteredUsers.slice(startIndex, startIndex + this.rowsPerPage);

        if (usersToDisplay.length === 0) {
            tableBody.innerHTML = `<tr><td colspan="8" class="text-center py-4">Tidak ada data user.</td></tr>`;
        } else {
            usersToDisplay.forEach(user => {
                const isCurrentUser = this.currentAdminId === parseInt(user.id);
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${user.id}</td>
                    <td><div class="user-avatar-small"><i class="fas fa-user"></i></div></td>
                    <td><strong>${user.name}</strong></td>
                    <td>${user.email}</td>
                    <td>
                        <select class="form-select form-select-sm" onchange="kelolaUser.confirmChangeRole(event, ${user.id}, this.value, '${user.role}')" ${isCurrentUser ? 'disabled' : ''}>
                            <option value="user" ${user.role === 'user' ? 'selected' : ''}>User</option>
                            <option value="admin" ${user.role === 'admin' ? 'selected' : ''}>Admin</option>
                        </select>
                    </td>
                    <td><span class="badge ${isCurrentUser ? 'bg-success' : 'bg-secondary'}">${isCurrentUser ? 'Online' : 'Offline'}</span></td>
                    <td>${new Date(user.joined_date).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' })}</td>
                    <td>
                        <button class="btn btn-sm btn-success me-1" title="Edit" onclick="kelolaUser.showEditUserForm(${user.id})"><i class="fas fa-edit"></i></button>
                        <button class="btn btn-sm btn-danger" title="Hapus" onclick="kelolaUser.confirmDelete(${user.id}, '${user.name.replace(/'/g, "\\'")}')" ${isCurrentUser ? 'disabled' : ''}><i class="fas fa-trash"></i></button>
                    </td>
                `;
                tableBody.appendChild(row);
            });
        }
        this.updatePaginationControls();
    }
    
    // PERUBAHAN: Konfirmasi untuk Tambah/Update User
    async handleUserFormSubmit(event) {
        event.preventDefault();
        
        Swal.fire({
            title: 'Konfirmasi Simpan',
            text: "Apakah Anda yakin ingin menyimpan perubahan ini?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Simpan!',
            cancelButtonText: 'Batal'
        }).then(async (result) => {
            if (result.isConfirmed) {
                const userId = document.getElementById('userId').value;
                const isUpdate = !!userId;
                const userData = {
                    name: document.getElementById('userName').value,
                    email: document.getElementById('userEmail').value,
                    password: document.getElementById('userPassword').value,
                    role: document.getElementById('userRole').value,
                    id: isUpdate ? userId : undefined
                };
                const url = isUpdate ? '../api/user_api.php?action=update_user' : '../api/user_api.php?action=add_user';
                
                try {
                    const response = await fetch(url, { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(userData) });
                    const res = await response.json();
                    this.showAlert(res.success ? 'success' : 'error', res.message);
                    if (res.success) {
                        this.hideUserForm();
                        this.loadUsersAndPopulateTable();
                    }
                } catch (error) {
                    this.showAlert('error', 'Terjadi kesalahan jaringan.');
                }
            }
        });
    }

    // PERUBAHAN: Konfirmasi untuk Hapus User
    confirmDelete(userId, userName) {
        Swal.fire({
            title: 'Anda Yakin?',
            html: `User <strong>${userName}</strong> akan dihapus secara permanen.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then(async (result) => {
            if (result.isConfirmed) {
                try {
                    const response = await fetch('../api/user_api.php?action=delete_user', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ id: userId })
                    });
                    const res = await response.json();
                    this.showAlert(res.success ? 'success' : 'error', res.message);
                    if(res.success) this.loadUsersAndPopulateTable();
                } catch (error) {
                    this.showAlert('error', 'Gagal menghapus user.');
                }
            }
        });
    }

    // PERUBAHAN: Konfirmasi untuk Ganti Role
    confirmChangeRole(event, userId, newRole, oldRole) {
        const selectElement = event.target;
        Swal.fire({
            title: 'Konfirmasi Perubahan Role',
            text: `Ubah role user ini menjadi "${newRole}"?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Ubah!',
            cancelButtonText: 'Batal'
        }).then(async (result) => {
            if (result.isConfirmed) {
                const user = this.allUsers.find(u => u.id == userId);
                if (!user) return;
                try {
                    const response = await fetch('../api/user_api.php?action=update_user', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ id: userId, role: newRole })
                    });
                    const res = await response.json();
                    this.showAlert(res.success ? 'success' : 'error', res.message);
                    if (res.success) {
                        user.role = newRole; 
                    } else {
                        selectElement.value = oldRole; // Kembalikan pilihan jika gagal
                    }
                } catch (error) {
                    this.showAlert('error', 'Gagal mengubah role.');
                    selectElement.value = oldRole; // Kembalikan pilihan jika gagal
                }
            } else {
                // Jika dibatalkan, kembalikan pilihan dropdown ke nilai semula
                selectElement.value = oldRole;
            }
        });
    }

    // PERUBAHAN: Menggunakan SweetAlert2 untuk notifikasi
    showAlert(type, message) {
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer);
                toast.addEventListener('mouseleave', Swal.resumeTimer);
            }
        });

        Toast.fire({
            icon: type, // 'success', 'error', 'warning', 'info', 'question'
            title: message
        });
    }
    
    // Fungsi-fungsi lain tetap sama
    updatePaginationControls() {
        const totalPages = Math.ceil(this.filteredUsers.length / this.rowsPerPage) || 1;
        document.getElementById('pageInfo').textContent = `Halaman ${this.currentPage} dari ${totalPages}`;
        document.getElementById('prevBtn').disabled = this.currentPage === 1;
        document.getElementById('nextBtn').disabled = this.currentPage >= totalPages;
    }
    previousPage() { if (this.currentPage > 1) { this.currentPage--; this.populateUserTable(); } }
    nextPage() { if (this.currentPage < Math.ceil(this.filteredUsers.length / this.rowsPerPage)) { this.currentPage++; this.populateUserTable(); } }
    showAddUserForm() {
        document.getElementById('userForm').style.display = 'block';
        document.getElementById('userFormTitle').textContent = 'Tambah User Baru';
        document.getElementById('userFormElement').reset();
        document.getElementById('userId').value = '';
        document.getElementById('passwordHint').style.display = 'none';
        document.getElementById('userPassword').required = true;
    }
    showEditUserForm(userId) {
        const user = this.allUsers.find(u => u.id == userId);
        if (user) {
            document.getElementById('userForm').style.display = 'block';
            document.getElementById('userFormTitle').textContent = 'Edit User';
            document.getElementById('userId').value = user.id;
            document.getElementById('userName').value = user.name;
            document.getElementById('userEmail').value = user.email;
            document.getElementById('userRole').value = user.role;
            document.getElementById('userPassword').value = '';
            document.getElementById('userPassword').required = false;
            document.getElementById('passwordHint').style.display = 'block';
        }
    }
    hideUserForm() { document.getElementById('userForm').style.display = 'none'; }
}

document.addEventListener('DOMContentLoaded', () => {
    window.kelolaUser = new KelolaUserManager();
});