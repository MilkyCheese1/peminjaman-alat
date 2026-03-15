<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Aplikasi Peminjaman Alat</title>
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
</head>
<body>
    <div class="auth-container">
        <div class="auth-box">
            <div class="auth-header">
                <h1>Daftar</h1>
                <p>Buat akun baru Anda</p>
            </div>

            <form id="registerForm" class="auth-form">
                @csrf
                <div class="form-group">
                    <label for="username">Username</label>
                    <input 
                        type="text" 
                        id="username" 
                        name="username" 
                        placeholder="3-30 karakter, huruf/angka/underscore"
                        maxlength="30"
                        minlength="3"
                        pattern="[a-zA-Z0-9_]{3,30}"
                        required
                    >
                    <span class="error-message" id="usernameError"></span>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        placeholder="email@example.com"
                        maxlength="100"
                        required
                    >
                    <span class="error-message" id="emailError"></span>
                </div>

                <div class="form-group">
                    <label for="phone">Nomor Telepon (Opsional)</label>
                    <input 
                        type="tel" 
                        id="phone" 
                        name="phone" 
                        placeholder="08xxxxxxxxxx atau +62xxx"
                        maxlength="15"
                    >
                    <span class="error-message" id="phoneError"></span>
                </div>

                <div class="form-group">
                    <label for="alamat">Alamat</label>
                    <textarea 
                        id="alamat" 
                        name="alamat" 
                        placeholder="Masukkan alamat lengkap Anda"
                        maxlength="500"
                        minlength="5"
                        rows="3"
                        required
                    ></textarea>
                    <span class="error-message" id="alamatError"></span>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="password-wrapper">
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            placeholder="Minimal 8 karakter"
                            maxlength="128"
                            minlength="8"
                            required
                        >
                        <button type="button" class="toggle-password" data-target="password">
                            <svg class="eye-icon-open" viewBox="0 0 24 24" width="20" height="20">
                                <path d="M12 2C6.48 2 1.73 5.1 1 10c.73 4.9 5.48 8 11 8s10.27-3.1 11-8c-.73-4.9-5.48-8-11-8zm0 14c-3.31 0-6-2.69-6-6s2.69-6 6-6 6 2.69 6 6-2.69 6-6 6zm0-10c-2.21 0-4 1.79-4 4s1.79 4 4 4 4-1.79 4-4-1.79-4-4-4z" fill="currentColor"/>
                            </svg>
                            <svg class="eye-icon-closed" viewBox="0 0 24 24" width="20" height="20" style="display:none;">
                                <path d="M11.83 9L15.23 12.39c.75-.52 1.25-1.3 1.25-2.39 0-2.21-1.79-4-4-4-.99 0-1.87.35-2.65 1.02m7.58 9.58L19.73 21 21 19.73l-9-9L5.27 3 4 4.27l3.58 3.58A10 10 0 0 0 1 10c.73 4.9 5.48 8 11 8 1.66 0 3.25-.3 4.72-.91m-2.04-2.06c-.5.13-1.03.2-1.58.2-3.31 0-6-2.69-6-6 0-.55.07-1.08.2-1.58l2.07 2.07c0 2.21 1.79 4 4 4l2.31 2.31z" fill="currentColor"/>
                            </svg>
                        </button>
                    </div>
                    <span class="error-message" id="passwordError"></span>
                    <div class="password-strength">
                        <div class="strength-bar" id="strengthBar"></div>
                        <small id="strengthText">Kekuatan password: lemah</small>
                    </div>
                </div>

                <div class="form-group">
                    <label for="confirmPassword">Konfirmasi Password</label>
                    <input 
                        type="password" 
                        id="confirmPassword" 
                        name="confirmPassword" 
                        placeholder="Ulangi password Anda"
                        maxlength="128"
                        required
                    >
                    <span class="error-message" id="confirmPasswordError"></span>
                </div>

                <div class="form-group checkbox">
                    <input type="checkbox" id="agreeTerms" name="agreeTerms" required>
                    <label for="agreeTerms">Saya setuju dengan <a href="/terms">Syarat & Ketentuan</a></label>
                    <span class="error-message" id="agreeTermsError"></span>
                </div>

                <button type="submit" class="btn btn-primary">Daftar</button>

                <div id="successMessage" class="success-message"></div>
                <div id="errorMessage" class="error-message"></div>
            </form>

            <div class="auth-footer">
                <p>Sudah punya akun? <a href="/login">Login di sini</a></p>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/auth.js') }}"></script>
    <script>
        const passwordInput = document.getElementById('password');
        const confirmPasswordInput = document.getElementById('confirmPassword');
        const registerForm = document.getElementById('registerForm');

        // Monitor password strength
        passwordInput.addEventListener('input', checkPasswordStrength);

        // Monitor password match
        confirmPasswordInput.addEventListener('input', checkPasswordMatch);

        registerForm.addEventListener('submit', handleRegister);

        function checkPasswordStrength() {
            const password = passwordInput.value;
            const strengthBar = document.getElementById('strengthBar');
            const strengthText = document.getElementById('strengthText');
            
            let strength = 0;
            const patterns = {
                lowercase: /[a-z]/,
                uppercase: /[A-Z]/,
                numbers: /\d/,
                special: /[!@#$%^&*]/
            };

            if (password.length >= 8) strength++;
            if (password.length >= 12) strength++;
            if (patterns.lowercase.test(password)) strength++;
            if (patterns.uppercase.test(password)) strength++;
            if (patterns.numbers.test(password)) strength++;
            if (patterns.special.test(password)) strength++;

            const strengthLevels = ['lemah', 'lemah', 'sedang', 'kuat', 'sangat kuat', 'sangat kuat'];
            const colors = ['#e74c3c', '#e74c3c', '#f39c12', '#27ae60', '#27ae60', '#27ae60'];
            const widths = ['20%', '40%', '60%', '80%', '100%', '100%'];

            strengthBar.style.background = colors[strength];
            strengthBar.style.width = widths[strength];
            strengthText.textContent = `Kekuatan password: ${strengthLevels[strength]}`;
        }

        function checkPasswordMatch() {
            const password = passwordInput.value;
            const confirmPassword = confirmPasswordInput.value;
            const error = document.getElementById('confirmPasswordError');

            if (confirmPassword && password !== confirmPassword) {
                error.textContent = 'Password tidak cocok';
            } else {
                error.textContent = '';
            }
        }

        async function handleRegister(e) {
            e.preventDefault();

            const username = document.getElementById('username').value.trim();
            const email = document.getElementById('email').value.trim().toLowerCase();
            const phone = document.getElementById('phone').value.trim();
            const alamat = document.getElementById('alamat').value.trim();
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirmPassword').value;
            const agreeTerms = document.getElementById('agreeTerms').checked;

            // Validasi
            if (!username || !email || !alamat || !password || !confirmPassword) {
                showError('Semua field wajib diisi');
                return;
            }

            if (username.length < 3 || username.length > 30) {
                showError('Username harus 3-30 karakter');
                return;
            }

            if (!/^[a-zA-Z0-9_]{3,30}$/.test(username)) {
                showError('Username hanya boleh mengandung huruf, angka, dan underscore');
                return;
            }

            if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                showError('Format email tidak valid');
                return;
            }

            if (phone && !/^(\+62|62|0)[0-9]{9,12}$/.test(phone.replace(/[- ]/g, ''))) {
                showError('Format nomor telepon tidak valid');
                return;
            }

            if (password.length < 8) {
                showError('Password minimal 8 karakter');
                return;
            }

            if (password !== confirmPassword) {
                showError('Password tidak cocok');
                return;
            }

            if (!agreeTerms) {
                showError('Anda harus setuju dengan Syarat & Ketentuan');
                return;
            }

            try {
                const response = await fetch('/api/register', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                    },
                    body: JSON.stringify({
                        username: username,
                        email: email,
                        phone: phone,
                        password: password,
                        alamat: alamat
                    })
                });

                const data = await response.json();

                if (response.ok) {
                    showSuccess('Pendaftaran berhasil! Mengarahkan ke login...');
                    setTimeout(() => {
                        window.location.href = '/login';
                    }, 1500);
                } else {
                    showError(data.message || 'Pendaftaran gagal');
                }
            } catch (error) {
                showError('Terjadi kesalahan: ' + error.message);
            }
        }

        function showError(message) {
            const errorDiv = document.getElementById('errorMessage');
            errorDiv.textContent = message;
            errorDiv.style.display = 'block';
        }

        function showSuccess(message) {
            const successDiv = document.getElementById('successMessage');
            successDiv.textContent = message;
            successDiv.style.display = 'block';
        }
    </script>
</body>
</html>
