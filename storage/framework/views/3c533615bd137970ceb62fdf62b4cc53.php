<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?php echo e(config('app.name', 'Laravel Vue')); ?></title>
        <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
        <script>
            // Initialize dark mode from localStorage
            const darkMode = localStorage.getItem('darkMode');
            const applyTheme = (isDark) => {
                document.documentElement.classList.toggle('dark', isDark)

                if (isDark) {
                    document.documentElement.setAttribute('data-theme', 'dark')
                } else {
                    document.documentElement.removeAttribute('data-theme')
                }
            }

            if (darkMode === 'true') {
                applyTheme(true)
            } else if (darkMode === 'false') {
                applyTheme(false)
            } else {
                // Default: light mode if not set
                localStorage.setItem('darkMode', 'false')
                applyTheme(false)
            }
        </script>
    </head>
    <body class="min-h-screen bg-slate-50 text-slate-900 dark:bg-slate-950 dark:text-slate-100">
        <div id="app">
            <div style="padding:16px;font-family:system-ui,-apple-system,Segoe UI,Roboto,Ubuntu,Cantarell,Noto Sans,sans-serif;">
                Memuat aplikasi...
                <noscript>
                    <div style="margin-top:12px;color:#b91c1c;">JavaScript nonaktif. Aktifkan JavaScript untuk menggunakan aplikasi.</div>
                </noscript>
                <div style="margin-top:12px;color:#64748b;font-size:14px;line-height:1.4;">
                    Jika layar tetap putih, pastikan Vite berjalan (<code>npm run dev</code>) atau jalankan build dan hapus <code>public/hot</code>.
                </div>
            </div>
        </div>
    </body>
</html>
<?php /**PATH C:\laragon\www\peminjaman-alat\resources\views/welcome.blade.php ENDPATH**/ ?>