<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>Equipment Rental System</title>
    <?php if(app()->environment('production')): ?>
        <?php echo app('Illuminate\Foundation\Vite')(['resources/js/main.js', 'resources/css/landing.css']); ?>
    <?php else: ?>
        <script type="module">
            import RefreshRuntime from 'http://127.0.0.1:5174/@react-refresh'
            RefreshRuntime.injectIntoGlobalHook(window)
            window.$RefreshReg$ = () => {}
            window.$RefreshSig$ = () => (x) => x
        </script>
        <script type="module" src="http://127.0.0.1:5174/<?php echo app('Illuminate\Foundation\Vite')(); ?>/client"></script>
        <script type="module" src="http://127.0.0.1:5174/resources/js/main.js"></script>
    <?php endif; ?>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
        }
    </style>
</head>
<body>
    <div id="app"></div>
</body>
</html>
<?php /**PATH C:\laragon\www\peminjaman-alat\resources\views/welcome.blade.php ENDPATH**/ ?>