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
                        placeholder="3-30 karakter"
                        maxlength="30"
                        minlength="3"
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
                    <label for="phone">Nomor Telepon</label>
                    <input 
                        type="tel" 
                        id="phone" 
                        name="phone" 
                        placeholder="08xxxxxxxxxx"
                        maxlength="20"
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
                            placeholder="Minimal 6 karakter"
                            maxlength="128"
                            minlength="6"
                            required
                        >
                    </div>
                    <span class="error-message" id="passwordError"></span>
                </div>

                <div class="form-group">
                    <label for="password_confirmation">Konfirmasi Password</label>
                    <input 
                        type="password" 
                        id="password_confirmation" 
                        name="password_confirmation" 
                        placeholder="Ulangi password Anda"
                        maxlength="128"
                        required
                    >
                    <span class="error-message" id="confirmPasswordError"></span>
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
</body>
</html>
            
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
    </script>
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
