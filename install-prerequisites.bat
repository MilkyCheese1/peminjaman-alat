@echo off
REM ============================================================
REM  Install Prerequisites for Peminjaman Alat Website
REM  Downloads and installs: Node.js, PHP, Composer, Git
REM ============================================================

setlocal enabledelayedexpansion

echo.
echo ============================================================
echo   INSTALL PREREQUISITES
echo   This will download and install required tools
echo ============================================================
echo.

REM Check if running as administrator
net session >nul 2>&1
if %errorlevel% neq 0 (
    echo ERROR: Please run this script as Administrator!
    echo Right-click and select "Run as administrator"
    pause
    exit /b 1
)

echo Checking for required tools...
echo.

REM Check Node.js
node --version >nul 2>&1
if %errorlevel% equ 0 (
    echo [OK] Node.js already installed: 
    node --version
) else (
    echo [MISSING] Node.js not found
    echo.
    echo To install Node.js:
    echo 1. Go to: https://nodejs.org/
    echo 2. Download LTS version
    echo 3. Run installer and follow instructions
    echo 4. After install, restart this script
    echo.
    pause
)

echo.

REM Check PHP
php --version >nul 2>&1
if %errorlevel% equ 0 (
    echo [OK] PHP already installed:
    php --version | findstr /R "PHP [0-9]"
) else (
    echo [MISSING] PHP not found
    echo.
    echo To install PHP:
    echo Option A - Using Laragon (Recommended):
    echo   1. Go to: https://laragon.org/
    echo   2. Download and install
    echo   3. Open Laragon, it includes PHP + MySQL
    echo.
    echo Option B - Using XAMPP:
    echo   1. Go to: https://www.apachefriends.org/
    echo   2. Download and install
    echo   3. Enable PHP module
    echo.
    pause
)

echo.

REM Check Composer
composer --version >nul 2>&1
if %errorlevel% equ 0 (
    echo [OK] Composer already installed:
    composer --version | findstr /C:"Composer"
) else (
    echo [MISSING] Composer not found
    echo.
    echo To install Composer:
    echo 1. Go to: https://getcomposer.org/download/
    echo 2. Download installer for Windows
    echo 3. Run installer (select PHP automatically)
    echo 4. After install, restart this script
    echo.
    pause
)

echo.

REM Check Git
git --version >nul 2>&1
if %errorlevel% equ 0 (
    echo [OK] Git already installed:
    git --version
) else (
    echo [MISSING] Git not found
    echo.
    echo To install Git:
    echo 1. Go to: https://git-scm.com/download/win
    echo 2. Download and run installer
    echo 3. Use default settings
    echo 4. After install, restart this script
    echo.
    pause
)

echo.
echo ============================================================
echo   VERIFICATION COMPLETE
echo ============================================================
echo.
echo Next steps:
echo 1. If all tools show [OK], you're ready!
echo    Run: setup-project.bat
echo.
echo 2. If any tool shows [MISSING], install it first
echo    Then come back and run this script again
echo.
pause
