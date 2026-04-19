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
            if (darkMode === 'true') {
                document.documentElement.classList.add('dark')
            } else if (darkMode === 'false') {
                document.documentElement.classList.remove('dark')
            } else {
                // Default: light mode if not set
                localStorage.setItem('darkMode', 'false')
                document.documentElement.classList.remove('dark')
            }
        </script>
    </head>
    <body class="min-h-screen bg-slate-50 text-slate-900 dark:bg-slate-950 dark:text-slate-100">
        <div id="app"></div>
    </body>
</html>
<?php /**PATH C:\laragon\www\peminjaman-alat\resources\views/welcome.blade.php ENDPATH**/ ?>