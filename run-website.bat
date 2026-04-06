@echo off
REM ============================================================
REM  Run Website - Start Dev Servers
REM  Starts Vite (Frontend) and Laravel (Backend) simultaneously
REM ============================================================

setlocal enabledelayedexpansion

echo.
echo ============================================================
echo   STARTING WEBSITE
echo   Starting development servers...
echo ============================================================
echo.

REM Get current directory
cd /d "%~dp0"
set PROJECT_DIR=%cd%

echo Project Directory: %PROJECT_DIR%
echo.

REM Check if dependencies are installed
if not exist "node_modules" (
    echo ERROR: node_modules not found!
    echo Please run setup-project.bat first
    echo.
    pause
    exit /b 1
)

if not exist "vendor" (
    echo ERROR: vendor folder not found!
    echo Please run setup-project.bat first
    echo.
    pause
    exit /b 1
)

echo.
echo Checking port availability...
echo.

REM Check if ports are in use
netstat -ano | findstr ":5175" >nul
if %errorlevel% equ 0 (
    echo [WARNING] Port 5175 is already in use
    echo Vite will try to use next available port (5176, 5177, etc)
)

netstat -ano | findstr ":8000" >nul
if %errorlevel% equ 0 (
    echo [WARNING] Port 8000 is already in use
    echo Laravel will try to use next available port (8001, 8002, etc)
)

echo.
echo Starting servers...
echo.
echo ============================================================
echo   TERMINAL 1: Laravel Backend (Press Ctrl+C to stop)
echo ============================================================
echo.

REM Start Laravel in a new window
start "Laravel Backend - Port 8000" cmd /k "cd /d %PROJECT_DIR% && php artisan serve"

REM Wait a bit for Laravel to start
timeout /t 3 /nobreak

echo.
echo ============================================================
echo   TERMINAL 2: Vite Frontend (Press Ctrl+C to stop)
echo ============================================================
echo.

REM Start Vite in a new window
start "Vite Frontend - Port 5175" cmd /k "cd /d %PROJECT_DIR% && npm run dev"

echo.
echo ============================================================
echo   SERVERS STARTED!
echo ============================================================
echo.
echo Frontend: http://localhost:5175
echo Backend:  http://localhost:8000/api
echo.
echo Two new terminals will open shortly. Let them run in background.
echo.
echo To stop servers: Close their terminal windows
echo.
echo LOGIN CREDENTIALS:
echo   Email:    customer@trustequip.id
echo   Password: password
echo.
pause

echo.
echo Opening browser...
timeout /t 2 /nobreak
start http://localhost:5175
