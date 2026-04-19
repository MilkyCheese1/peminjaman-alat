@echo off
REM Start Laravel and Vite development servers in separate command windows.
cd /d "%~dp0"
start "Laravel" "%SystemRoot%\System32\cmd.exe" /k "php artisan serve --port=8000"
start "Vite" "%SystemRoot%\System32\cmd.exe" /k "npm run dev"
exit
