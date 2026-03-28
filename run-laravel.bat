@echo off
REM Shortcut untuk menjalankan Laravel Server
REM Dibuat untuk Sistem Peminjaman Alat

title Laravel - Sistem Peminjaman Alat
color 0A

echo.
echo ====================================
echo   LARAVEL SERVER - PEMINJAMAN ALAT
echo ====================================
echo.
echo Starting Laravel Development Server...
echo Server akan tersedia di: http://localhost:8000
echo.
echo Tekan CTRL+C untuk menghentikan server
echo.

cd /d "%~dp0"
php artisan serve

pause
