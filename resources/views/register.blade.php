<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Aplikasi Peminjaman Alat</title>
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dark-mode.css') }}">
</head>
<body>
    <a href="/" style="position: fixed; top: 20px; left: 20px; display: flex; align-items: center; justify-content: center; text-decoration: none; z-index: 1000; transition: all 0.3s ease; width: 40px; height: 40px; border-radius: 50%;" onmouseover="this.style.backgroundColor='rgba(255,255,255,0.2)'" onmouseout="this.style.backgroundColor='transparent'">
        <span style="font-size: 28px; font-weight: bold; color: white;">←</span>
    </a>
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

    <script src="{{ asset('js/dark-mode.js') }}"></script>
    <script src="{{ asset('js/auth.js') }}"></script>
</body>
</html>
