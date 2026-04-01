@echo off
setlocal enabledelayedexpansion
color 0B
cls
REM ====================================================
REM Equipment Rental System - Complete Launcher
REM Single Click to Run Backend & Frontend
REM ====================================================

set "PROJECT_DIR=%~dp0"
set "NODEJS_PATH=C:\laragon\bin\nodejs\node-v22"
set "PATH=%NODEJS_PATH%;%PATH%"

REM Title
title Equipment Rental System Launcher

:main
cls
echo.
echo ====================================================
echo.
echo  ^<^^> EQUIPMENT RENTAL SYSTEM LAUNCHER ^<^^>
echo.
echo    Vue.js 3 Frontend + Laravel 10 Backend
echo.
echo ====================================================
echo.

REM Check dependencies
echo Checking dependencies...
echo.

REM Check PHP
php --version >nul 2>&1
if errorlevel 1 (
    echo   [!] PHP not found!
    echo   Please ensure PHP is installed and in PATH
    echo.
    pause
    exit /b 1
) else (
    echo   [OK] PHP detected
)

REM Check Node.js
node --version >nul 2>&1
if errorlevel 1 (
    echo   [!] Node.js not found!
    echo.
    pause
    exit /b 1
) else (
    echo   [OK] Node.js detected
)

REM Check npm
npm --version >nul 2>&1
if errorlevel 1 (
    echo   [!] npm not found!
    echo.
    pause
    exit /b 1
) else (
    echo   [OK] npm detected
)

REM Check node_modules
if not exist "node_modules" (
    echo   [*] Installing npm dependencies (first time only)...
    call npm install >nul 2>&1
    if errorlevel 1 (
        echo   [!] Failed to install npm dependencies
        pause
        exit /b 1
    )
    echo   [OK] npm dependencies installed
) else (
    echo   [OK] npm dependencies found
)

echo.
echo ====================================================
echo.
echo  All dependencies OK. Starting services...
echo.
echo ====================================================
echo.
echo  [*] Backend will start on Port 8000
echo  [*] Frontend will start on Port 5173
echo.
echo  Launching... (please wait)
echo.
REM Small delay for visual effect
timeout /t 2 /nobreak >nul

REM Start backend
echo  [^^] Starting Laravel Backend...
start "BACKEND - Equipment Rental (Laravel on Port 8000)" /d "%PROJECT_DIR%" cmd /c "php artisan serve --host=localhost --port=8000"

REM Wait before starting frontend
timeout /t 3 /nobreak >nul

REM Start frontend with npm
echo  [^^] Starting Vue.js Frontend...
start "FRONTEND - Equipment Rental (Vue.js on Port 5173)" /d "%PROJECT_DIR%" cmd /c "npm run dev"

REM Show success screen
cls
echo.
echo ====================================================
echo.
echo  ^^!^^! SUCCESS - SERVICES STARTED ^^!^^!
echo.
echo ====================================================
echo.
echo  Frontend:  http://localhost:5173
echo  Backend:   http://localhost:8000
echo  APIs:      http://localhost:8000/api
echo.
echo ====================================================
echo.
echo  ^^* Two new terminal windows should open above
echo  ^^* You can minimize this window
echo  ^^* Keep the backend/frontend windows open
echo  ^^* Close them to stop the services
echo.
echo ====================================================
echo.

REM Try to open browser
timeout /t 3 /nobreak >nul

REM Optional: Uncomment to auto-open browser
REM start http://localhost:5173

echo  Attempting to open browser...
start http://localhost:5173 >nul 2>&1

echo.
echo  If browser doesn't open, visit manually:
echo    http://localhost:5173
echo.

REM Keep launcher window open but minimized
echo ====================================================
echo.
echo  This launcher window will stay open.
echo  You can safely minimize it.
echo.
echo  Press any key to close this launcher...
echo  (Services will continue running)
echo.
echo ====================================================
echo.

pause >nul

exit /b 0
