@echo off
REM QUICK START - Equipment Rental System

echo.
echo ======================================
echo     EQUIPMENT RENTAL SYSTEM
echo     Vue.js + Laravel Development
echo ======================================
echo.

REM Set Node.js path
set PATH=C:\laragon\bin\nodejs\node-v22;%PATH%

echo Setting up environment...
echo.

REM Check dependencies
echo Checking installations...
node --version >nul 2>&1
if %errorlevel% neq 0 (
    echo ❌ ERROR: Node.js not found
    echo Please ensure Node.js is in PATH
    pause
    exit /b 1
)

php --version >nul 2>&1
if %errorlevel% neq 0 (
    echo ❌ ERROR: PHP not found
    echo Please ensure PHP is installed
    pause
    exit /b 1
)

echo ✅ Node.js and PHP found
echo.

echo STARTING DEVELOPMENT SERVERS...
echo.
echo ⏳ Backend will start on http://localhost:8000
echo ⏳ Frontend will start on http://localhost:5173
echo.
echo Please wait while both servers are starting...
echo.

REM Start Laravel backend
start "Equipment Rental - Backend (Laravel)" cmd /k "php artisan serve --host=localhost --port=8000"
timeout /t 2 /nobreak

REM Start Vite frontend  
start "Equipment Rental - Frontend (Vue.js + Vite)" cmd /k "npm run dev"

echo.
echo ======================================
echo     SERVERS STARTED
echo ======================================
echo.
echo 🌐 Frontend:  http://localhost:5173
echo 🔗 Backend:   http://localhost:8000
echo 📡 API:       http://localhost:8000/api
echo.
echo To stop servers, close the terminal windows
echo or press Ctrl+C in each
echo.
echo Documentation: README_SETUP.md
echo.
pause
