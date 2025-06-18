// js/auth.js
class AuthSystem {
    constructor() {
        // Load users from localStorage, or initialize with a default admin
        this.users = JSON.parse(localStorage.getItem('users')) || [
            {
                id: 1, // Unique ID for the admin
                name: 'Administrator',
                email: 'admin@nusantara.com',
                password: 'admin123',
                role: 'admin'
            }
        ];
        // Ensure default admin user is saved if 'users' was empty
        if (!localStorage.getItem('users')) {
            localStorage.setItem('users', JSON.stringify(this.users));
        }

        // Load current logged-in user from localStorage
        this.currentUser = JSON.parse(localStorage.getItem('currentUser')) || null;
        
        // Initialize all necessary components when AuthSystem is created
        this.init();
    }

    init() {
        this.renderNavbar();
        this.setupEventListeners();
        // this.populateUserTable(); // This might only be needed on admin.html
    }

    /**
     * Renders the navbar dynamically based on user's login status and role.
     * Updates the #navbarMenu element.
     */
    renderNavbar() {
        const navbarMenu = document.getElementById('navbarMenu');
        if (!navbarMenu) return; // Ensure the navbarMenu element exists

        if (!this.currentUser) {
            // User not logged in - show Home and Login
            navbarMenu.innerHTML = `
                <li class="nav-item">
                    <a class="nav-link px-lg-3 py-3 py-lg-4" href="index.html">
                        <i class="fas fa-home me-1"></i>Beranda
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-lg-3 py-3 py-lg-4" href="#" data-bs-toggle="modal" data-bs-target="#loginModal" id="loginLink">
                        <i class="fas fa-sign-in-alt me-1"></i>Login
                    </a>
                </li>
            `;
        } else {
            // User logged in - show all links
            let menuItems = `
                <li class="nav-item">
                    <a class="nav-link px-lg-3 py-3 py-lg-4" href="index.html">
                        <i class="fas fa-home me-1"></i>Beranda
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-lg-3 py-3 py-lg-4" href="batik.html">
                        <i class="fas fa-palette me-1"></i>Batik
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-lg-3 py-3 py-lg-4" href="musik.html">
                        <i class="fas fa-music me-1"></i>Alat Musik
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-lg-3 py-3 py-lg-4" href="tari.html">
                        <i class="fas fa-hands me-1"></i>Tari Tradisional
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-lg-3 py-3 py-lg-4" href="sejarah.html">
                        <i class="fas fa-info-circle me-1"></i>About
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-lg-3 py-3 py-lg-4" href="contact.html">
                        <i class="fas fa-envelope me-1"></i>Kontak
                    </a>
                </li>
            `;

            // Add Admin Dashboard link if user is admin
            if (this.currentUser.role === 'admin') {
                menuItems += `
                    <li class="nav-item">
                        <a class="nav-link px-lg-3 py-3 py-lg-4" href="#" data-bs-toggle="modal" data-bs-target="#userManagementModal">
                            <i class="fas fa-users-cog me-1"></i>Admin Dashboard
                        </a>
                    </li>
                `;
            }

            // Add user dropdown (profile/logout)
            menuItems += `
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle px-lg-3 py-3 py-lg-4" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-user-circle me-1"></i>${this.currentUser.name}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><span class="dropdown-item-text">
                            <small class="text-muted">Role: ${this.currentUser.role}</small>
                        </span></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="#" id="logoutBtn">
                            <i class="fas fa-sign-out-alt me-1"></i>Logout
                        </a></li>
                    </ul>
                </li>
            `;
            navbarMenu.innerHTML = menuItems;
        }
    }

    /**
     * Sets up event listeners for login, register, logout, and modal interactions.
     */
    setupEventListeners() {
        // Event listeners for login/register form toggling within the modal
        document.getElementById('showRegister')?.addEventListener('click', (e) => {
            e.preventDefault();
            this.toggleForms('register');
        });

        document.getElementById('showLogin')?.addEventListener('click', (e) => {
            e.preventDefault();
            this.toggleForms('login');
        });

        // Event listeners for form submissions
        document.getElementById('loginForm')?.addEventListener('submit', (e) => {
            e.preventDefault();
            this.handleLogin();
        });

        document.getElementById('registerForm')?.addEventListener('submit', (e) => {
            e.preventDefault();
            this.handleRegister();
        });

        // Event listener for Logout button (uses event delegation for dynamically added elements)
        document.addEventListener('click', (e) => {
            if (e.target.id === 'logoutBtn') {
                e.preventDefault();
                this.handleLogout();
            }
        });

        // Reset the login modal state when it's hidden
        const loginModal = document.getElementById('loginModal');
        loginModal?.addEventListener('hidden.bs.modal', () => {
            this.resetModal();
        });

        // Handle user management modal (if present on the page, e.g., on admin.html or index.html)
        const userManagementModal = document.getElementById('userManagementModal');
        if (userManagementModal) {
            userManagementModal.addEventListener('show.bs.modal', (event) => {
                // Prevent modal from showing if user is not admin
                if (!this.isAdmin()) {
                    event.preventDefault(); // Stop modal from opening
                    this.showAlert('danger', 'Akses ditolak! Hanya admin yang dapat mengakses fitur ini.');
                    return false;
                }
                this.populateUserTable(); // Populate table when modal is about to be shown
            });
        }

        // Handle 'Daftar Sekarang' button click in the hero section (from index.html)
        const registerHeroBtn = document.querySelector('.btn-light.btn-lg'); // Assuming this is the 'Daftar Sekarang' button
        if (registerHeroBtn) {
            registerHeroBtn.addEventListener('click', () => {
                if (!this.isLoggedIn()) {
                    const loginModalInstance = new bootstrap.Modal(document.getElementById('loginModal'));
                    loginModalInstance.show();
                    setTimeout(() => {
                        this.toggleForms('register'); // Switch to register form after modal opens
                    }, 300); // Small delay to allow modal to render
                } else {
                    this.showAlert('info', `Anda sudah login sebagai ${this.currentUser.name}!`);
                }
            });
        }
    }

    /**
     * Toggles visibility between login and register forms within the modal.
     * @param {string} form - 'login' or 'register'
     */
    toggleForms(form) {
        const loginForm = document.getElementById('loginForm');
        const registerForm = document.getElementById('registerForm');
        const modalTitle = document.getElementById('modalTitle');

        if (!loginForm || !registerForm || !modalTitle) return;

        if (form === 'register') {
            loginForm.style.display = 'none';
            registerForm.style.display = 'block';
            modalTitle.textContent = 'Daftar Akun Baru';
        } else {
            loginForm.style.display = 'block';
            registerForm.style.display = 'none';
            modalTitle.textContent = 'Masuk ke Akun';
        }
        this.clearAlerts(); // Clear alerts when switching forms
    }

    /**
     * Resets the modal state to the login form and clears form inputs.
     */
    resetModal() {
        this.toggleForms('login');
        document.getElementById('loginForm')?.reset();
        document.getElementById('registerForm')?.reset();
        this.clearAlerts();
    }

    /**
     * Handles user login process.
     */
    handleLogin() {
        const emailInput = document.getElementById('loginEmail');
        const passwordInput = document.getElementById('loginPassword');

        const email = emailInput?.value;
        const password = passwordInput?.value;

        if (!email || !password) {
            this.showAlert('danger', 'Email dan password harus diisi!');
            return;
        }

        // Ensure users array is up-to-date from localStorage
        this.users = JSON.parse(localStorage.getItem('users')) || [];
        const user = this.users.find(u => u.email === email && u.password === password);

        if (user) {
            this.currentUser = user;
            localStorage.setItem('currentUser', JSON.stringify(user));
            
            // Close the modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('loginModal'));
            if (modal) modal.hide();
            
            // Update navbar and show success message
            this.renderNavbar();
            this.showAlert('success', `Selamat datang, ${user.name}!`);

            // Optional: Redirect after successful login, if not on index.html
            // For this setup, we close the modal and update navbar, staying on the current page.
            // If you *always* want to redirect after login (e.g., from admin.html to admin.html, or any page to index.html),
            // you'd add window.location.href logic here based on currentUser.role.
            // Example: setTimeout(() => { window.location.href = user.role === 'admin' ? 'admin.html' : 'index.html'; }, 1000);
        } else {
            this.showAlert('danger', 'Email atau password salah!');
        }
    }

    /**
     * Handles user registration process.
     */
    handleRegister() {
        const nameInput = document.getElementById('registerName');
        const emailInput = document.getElementById('registerEmail');
        const passwordInput = document.getElementById('registerPassword');
        const confirmPasswordInput = document.getElementById('confirmPassword');

        const name = nameInput?.value;
        const email = emailInput?.value;
        const password = passwordInput?.value;
        const confirmPassword = confirmPasswordInput?.value;

        if (!name || !email || !password || !confirmPassword) {
            this.showAlert('danger', 'Semua kolom harus diisi!');
            return;
        }

        if (password !== confirmPassword) {
            this.showAlert('danger', 'Password tidak cocok!');
            return;
        }
        if (password.length < 6) {
            this.showAlert('danger', 'Password minimal 6 karakter!');
            return;
        }

        // Ensure users array is up-to-date from localStorage
        this.users = JSON.parse(localStorage.getItem('users')) || [];
        if (this.users.find(u => u.email === email)) {
            this.showAlert('danger', 'Email sudah terdaftar!');
            return;
        }

        // Create new user
        const newUser = {
            id: Date.now(), // Simple unique ID
            name: name,
            email: email,
            password: password, // In a real app, hash this password!
            role: 'user' // Default role for new registrations
        };

        this.users.push(newUser);
        localStorage.setItem('users', JSON.stringify(this.users)); // Save updated users array

        // Auto-login the new user
        this.currentUser = newUser;
        localStorage.setItem('currentUser', JSON.stringify(newUser));

        // Close the modal and update UI
        const modal = bootstrap.Modal.getInstance(document.getElementById('loginModal'));
        if (modal) modal.hide();

        this.renderNavbar();
        this.populateUserTable(); // Update user table if admin modal is open
        this.showAlert('success', `Pendaftaran berhasil! Selamat datang, ${newUser.name}!`);
    }

    /**
     * Handles user logout process.
     */
    handleLogout() {
        this.currentUser = null;
        localStorage.removeItem('currentUser'); // Clear session
        this.renderNavbar(); // Update navbar to non-logged-in state
        this.showAlert('info', 'Anda telah logout.');
        // Optional: Redirect to home page after logout if not already there
        // window.location.href = 'index.html'; 
    }

    /**
     * Populates the user management table (for admin use).
     * This function should be called when the #userManagementModal is opened.
     */
    populateUserTable() {
        const tableBody = document.getElementById('userTableBody');
        if (!tableBody) return; // Only run if the table body exists

        // Ensure users array is up-to-date
        this.users = JSON.parse(localStorage.getItem('users')) || [];
        tableBody.innerHTML = '';

        this.users.forEach(user => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${user.name}</td>
                <td>${user.email}</td>
                <td>
                    <select class="form-select form-select-sm" onchange="window.authSystem.changeUserRole(${user.id}, this.value)">
                        <option value="user" ${user.role === 'user' ? 'selected' : ''}>User</option>
                        <option value="admin" ${user.role === 'admin' ? 'selected' : ''}>Admin</option>
                    </select>
                </td>
                <td>
                    ${user.id !== this.currentUser?.id ? // Prevent admin from deleting themselves
                        `<button class="btn btn-sm btn-danger" onclick="window.authSystem.deleteUser(${user.id})">
                            <i class="fas fa-trash"></i> Hapus
                        </button>` : 
                        '<span class="text-muted">Akun Anda</span>'
                    }
                </td>
            `;
            tableBody.appendChild(row);
        });
    }

    /**
     * Changes a user's role (admin privilege required).
     * @param {number} userId - The ID of the user to change.
     * @param {string} newRole - The new role ('user' or 'admin').
     */
    changeUserRole(userId, newRole) {
        if (!this.isAdmin()) {
            this.showAlert('danger', 'Akses ditolak! Anda bukan admin.');
            return;
        }

        const user = this.users.find(u => u.id === userId);
        if (user) {
            user.role = newRole;
            localStorage.setItem('users', JSON.stringify(this.users));
            this.showAlert('success', `Role ${user.name} berhasil diubah menjadi ${newRole}.`);
            // If the current user's role was changed, re-render navbar as well
            if (this.currentUser && this.currentUser.id === userId) {
                this.currentUser.role = newRole; // Update current user object
                localStorage.setItem('currentUser', JSON.stringify(this.currentUser));
                this.renderNavbar();
            }
        }
    }

    /**
     * Deletes a user (admin privilege required).
     * @param {number} userId - The ID of the user to delete.
     */
    deleteUser(userId) {
        if (!this.isAdmin()) {
            this.showAlert('danger', 'Akses ditolak! Anda bukan admin.');
            return;
        }

        if (userId === this.currentUser?.id) { // Prevent admin from deleting themselves
            this.showAlert('danger', 'Tidak dapat menghapus akun Anda sendiri!');
            return;
        }

        if (confirm('Apakah Anda yakin ingin menghapus user ini?')) {
            this.users = this.users.filter(u => u.id !== userId);
            localStorage.setItem('users', JSON.stringify(this.users));
            this.populateUserTable(); // Re-populate table after deletion
            this.showAlert('success', 'User berhasil dihapus.');
        }
    }

    /**
     * Displays a temporary alert message on the page.
     * @param {string} type - Bootstrap alert type (e.g., 'success', 'danger', 'info').
     * @param {string} message - The message to display.
     */
    showAlert(type, message) {
        // Remove existing alerts to prevent stacking
        const existingAlert = document.querySelector('.alert-custom');
        if (existingAlert) {
            existingAlert.remove();
        }

        const alert = document.createElement('div');
        alert.className = `alert alert-${type} alert-dismissible fade show alert-custom`;
        // Position fixed to ensure visibility regardless of scroll
        alert.style.cssText = `
            position: fixed;
            top: 80px; /* Below navbar */
            right: 20px;
            z-index: 9999;
            min-width: 300px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            font-size: 0.9rem;
        `;
        alert.innerHTML = `
            <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'danger' ? 'exclamation-circle' : 'info-circle'} me-2"></i>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;

        document.body.appendChild(alert);

        // Auto-remove after 5 seconds
        setTimeout(() => {
            if (alert && alert.parentNode) {
                alert.remove();
            }
        }, 5000);
    }

    clearAlerts() {
        const existingAlert = document.querySelector('.alert-custom');
        if (existingAlert) {
            existingAlert.remove();
        }
    }

    // Helper methods for external use
    getCurrentUser() {
        return this.currentUser;
    }

    isAdmin() {
        return this.currentUser?.role === 'admin';
    }

    isLoggedIn() {
        return this.currentUser !== null;
    }
}

// Initialize AuthSystem globally when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    window.authSystem = new AuthSystem();
});