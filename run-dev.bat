@echo off
REM Equipment Rental System - Start Development Environment
REM This script starts both Laravel backend and Vue.js frontend

echo ====================================
echo Equipment Rental System - DEV Setup
echo ====================================
echo.

REM Set up environment paths
set PATH=C:\laragon\bin\nodejs\node-v22;%PATH%

REM Check if node_modules exists
if not exist "node_modules" (
    echo Installing npm dependencies...
    call npm install
    echo.
)

echo Starting Laravel Backend (Port 8000)...
echo Starting in new terminal window...
start "Laravel Backend" cmd /k "php artisan serve --host=localhost --port=8000"

timeout /t 3 /nobreak

echo Starting Vue.js Frontend (Port 5173)...
echo Starting in new terminal window...
start "Vue.js Frontend" cmd /k "npm run dev"

echo.
echo ====================================
echo Development servers should now start
echo Frontend: http://localhost:5173
echo Backend: http://localhost:8000
echo ====================================
echo.
echo Press Ctrl+C in each terminal to stop the servers
echo.
pause
