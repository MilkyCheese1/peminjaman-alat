<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="application-name" content="{{ config('app.name', 'TrustEquip.id') }}">
        <meta name="theme-color" content="#ffffff">
        <link rel="icon" type="image/png" sizes="any" href="/trustequip-logo.png">
        <link rel="icon" type="image/x-icon" href="/favicon.ico">
        <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
        <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
        <link rel="manifest" href="/site.webmanifest">
        <title>{{ config('app.name', 'TrustEquip.id') }}</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
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
