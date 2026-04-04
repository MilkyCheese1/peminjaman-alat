<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>Equipment Rental System</title>
    
    <!-- Vite Asset Loading -->
    <?php echo app('Illuminate\Foundation\Vite')(['resources/js/main.js', 'resources/css/landing.css']); ?>
    
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