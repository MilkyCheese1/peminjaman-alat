<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Aplikasi Peminjaman Alat</title>
    <link rel="stylesheet" href="<?php echo e(asset('css/auth.css')); ?>">
</head>
<body>
    <a href="/" style="position: fixed; top: 20px; left: 20px; display: flex; align-items: center; gap: 8px; text-decoration: none; color: #007bff; font-weight: bold; font-size: 14px; z-index: 1000; padding: 8px 12px; border-radius: 4px; transition: all 0.3s ease;" onmouseover="this.style.backgroundColor='#f0f0f0'" onmouseout="this.style.backgroundColor='transparent'">
        <span style="font-size: 20px;">←</span>
        <span>Kembali</span>
    </a>
    <div class="auth-container">
        <div class="auth-box">
            <div class="auth-header">
                <h1>Login</h1>
                <p>Masuk ke akun Anda</p>
            </div>

            <form id="loginForm" class="auth-form">
                <?php echo csrf_field(); ?>
                <div class="form-group">
                    <label for="usernameOrEmail">Username atau Email</label>
                    <input 
                        type="text" 
                        id="usernameOrEmail" 
                        name="usernameOrEmail" 
                        placeholder="Masukkan username atau email Anda"
                        maxlength="100"
                        required
                    >
                    <span class="error-message" id="usernameOrEmailError"></span>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="password-wrapper">
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            placeholder="Masukkan password Anda"
                            maxlength="128"
                            required
                        >
                    </div>
                    <span class="error-message" id="passwordError"></span>
                </div>

                <div class="form-group checkbox">
                    <input type="checkbox" id="rememberMe" name="rememberMe">
                    <label for="rememberMe">Ingat saya</label>
                </div>

                <button type="submit" class="btn btn-primary">Login</button>

                <div id="successMessage" class="success-message"></div>
                <div id="errorMessage" class="error-message"></div>
            </form>

            <div class="auth-footer">
                <p>Belum punya akun? <a href="/register">Daftar di sini</a></p>
                <p><a href="/forgot-password">Lupa password?</a></p>
            </div>
        </div>
    </div>

    <script src="<?php echo e(asset('js/auth.js')); ?>"></script>
</body>
</html>
<?php /**PATH C:\laragon\www\peminjaman-alat\resources\views/login.blade.php ENDPATH**/ ?>