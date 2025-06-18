// js/login.js

document.addEventListener('DOMContentLoaded', function() {
    const loginForm = document.getElementById('loginForm');
    const registerForm = document.getElementById('registerForm');
    const switchFormBtn = document.getElementById('switchFormBtn');
    const formTitle = document.getElementById('formTitle');
    const formSubtitle = document.getElementById('formSubtitle');
    const switchText = document.getElementById('switchText');
    
    let isLoginForm = true;

    // --- Form Switching Logic ---
    switchFormBtn.addEventListener('click', function() {
        clearAlerts();
        if (isLoginForm) {
            loginForm.style.display = 'none';
            registerForm.style.display = 'block';
            formTitle.textContent = 'Buat Akun Baru';
            formSubtitle.textContent = 'Bergabunglah dengan komunitas pelestari budaya';
            switchText.textContent = 'Sudah punya akun?';
            switchFormBtn.textContent = 'Masuk Sekarang';
            isLoginForm = false;
        } else {
            registerForm.style.display = 'none';
            loginForm.style.display = 'block';
            formTitle.textContent = 'Masuk ke Akun';
            formSubtitle.textContent = 'Silakan masuk untuk mengakses fitur lengkap';
            switchText.textContent = 'Belum punya akun?';
            switchFormBtn.textContent = 'Daftar Sekarang';
            isLoginForm = true;
        }
    });

    // --- Login Form Handler ---
    loginForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        const email = document.getElementById('loginEmail').value;
        const password = document.getElementById('loginPassword').value;

        const response = await fetch('../api/auth_api.php?action=login', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ email: email, password: password })
        });
        const result = await response.json();

        if (result.success) {
            showAlert('success', result.message);
            setTimeout(() => {
                if (result.role === 'admin') {
                    window.location.href = 'index.php'; // 
                } else {
                    window.location.href = 'index.php'; // 
                }
            }, 1500);
        } else {
            showAlert('danger', result.message);
        }
    });

    // --- Register Form Handler ---
    registerForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        const name = document.getElementById('registerName').value;
        const email = document.getElementById('registerEmail').value;
        const password = document.getElementById('registerPassword').value;
        const confirmPassword = document.getElementById('confirmPassword').value;

        // Client-side validation
        if (password !== confirmPassword) {
            showAlert('danger', 'Password dan konfirmasi password tidak cocok!');
            return;
        }

        const response = await fetch('../api/auth_api.php?action=register', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ name, email, password })
        });
        const result = await response.json();

        if (result.success) {
            showAlert('success', result.message);
            setTimeout(() => {
                // Switch back to login form
                switchFormBtn.click();
            }, 1500);
        } else {
            showAlert('danger', result.message);
        }
    });

    // --- Alert Functions ---
    function showAlert(type, message) {
        clearAlerts();
        const alert = document.createElement('div');
        alert.className = `alert alert-${type} alert-dismissible fade show`;
        alert.style.position = 'fixed';
        alert.style.top = '20px';
        alert.style.right = '20px';
        alert.style.zIndex = '9999';
        alert.style.minWidth = '300px';
        alert.innerHTML = `
            <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'} me-2"></i>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        document.body.appendChild(alert);

        setTimeout(clearAlerts, 5000);
    }

    function clearAlerts() {
        const existingAlert = document.querySelector('.alert');
        if (existingAlert) {
            existingAlert.remove();
        }
    }
});