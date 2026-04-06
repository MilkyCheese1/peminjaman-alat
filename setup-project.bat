@echo off
REM ============================================================
REM  Setup Project - Install Dependencies
REM  Installs npm packages, PHP packages, and configures .env
REM ============================================================

setlocal enabledelayedexpansion

echo.
echo ============================================================
echo   PROJECT SETUP
echo   Installing all dependencies...
echo ============================================================
echo.

REM Get current directory
cd /d "%~dp0"
set PROJECT_DIR=%cd%

echo Project Directory: %PROJECT_DIR%
echo.

REM Check if we have the right files
if not exist "package.json" (
    echo ERROR: package.json not found!
    echo Please run this script from project root folder
    echo.
    pause
    exit /b 1
)

if not exist "composer.json" (
    echo ERROR: composer.json not found!
    echo Please run this script from project root folder
    echo.
    pause
    exit /b 1
)

echo.
echo [1/5] Installing Node.js packages (npm)...
echo ============================================================
call npm install
if %errorlevel% neq 0 (
    echo ERROR: npm install failed!
    pause
    exit /b 1
)
echo [OK] npm packages installed
echo.

echo [2/5] Installing PHP packages (Composer)...
echo ============================================================
call composer install
if %errorlevel% neq 0 (
    echo ERROR: composer install failed!
    pause
    exit /b 1
)
echo [OK] PHP packages installed
echo.

echo [3/5] Setting up environment file...
echo ============================================================
if not exist ".env" (
    if exist ".env.example" (
        copy ".env.example" ".env" >nul
        echo [OK] .env file created from .env.example
    ) else (
        echo ERROR: .env.example not found!
        pause
        exit /b 1
    )
) else (
    echo [OK] .env file already exists
)
echo.

echo [4/5] Generating APP_KEY...
echo ============================================================
call php artisan key:generate
if %errorlevel% neq 0 (
    echo ERROR: key:generate failed!
    pause
    exit /b 1
)
echo [OK] APP_KEY generated
echo.

echo [5/5] Testing configuration...
echo ============================================================
echo Database configuration:
findstr "DB_HOST\|DB_PORT\|DB_DATABASE\|DB_USERNAME" .env | findstr /V "^REM"
echo.

REM Optional: Run migrations
echo.
echo NOTE: Database setup
echo If this is a fresh installation, we can run migrations now.
echo.
set /p migrate="Do you want to run database migrations? (Y/N) "
if /i "%migrate%"=="Y" (
    echo.
    echo Running migrations...
    call php artisan migrate --seed
    if %errorlevel% neq 0 (
        echo WARNING: Migration completed but check for errors above
    ) else (
        echo [OK] Database migrated and seeded
    )
)

echo.
echo ============================================================
echo   SETUP COMPLETE!
echo ============================================================
echo.
echo You're all set! Next step:
echo.
echo   Run: run-website.bat
echo.
echo This will start:
echo   - Vite Dev Server (Vue.js) on port 5175
echo   - Laravel Backend (API) on port 8000
echo.
pause
