<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Aplikasi Peminjaman Alat</title>
    <link rel="stylesheet" href="<?php echo e(asset('css/auth.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/dark-mode.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/splash-loading.css')); ?>">
</head>
<body>
    <!-- Loading Screen -->
    <div id="loadingScreen" class="loading-screen">
        <div class="loading-content">
            <div class="loading-spinner"></div>
            <div class="loading-text">LOADING...</div>
        </div>
    </div>

    <!-- Loading Bar -->
    <div id="loadingBar" class="loading-bar">
        <div class="loading-bar-progress"></div>
    </div>

    <a href="/" style="position: fixed; top: 20px; left: 20px; display: flex; align-items: center; justify-content: center; text-decoration: none; z-index: 1000; transition: all 0.3s ease; width: 40px; height: 40px; border-radius: 50%;" onmouseover="this.style.backgroundColor='rgba(255,255,255,0.2)'" onmouseout="this.style.backgroundColor='transparent'">
        <span style="font-size: 28px; font-weight: bold; color: white;">←</span>
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

    <script src="<?php echo e(asset('js/dark-mode.js')); ?>"></script>
    <script src="<?php echo e(asset('js/splash-loading.js')); ?>"></script>
    <script src="<?php echo e(asset('js/auth.js')); ?>"></script>
</body>
</html>
<?php /**PATH C:\laragon\www\peminjaman-alat\resources\views/login.blade.php ENDPATH**/ ?>