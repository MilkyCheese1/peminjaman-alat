@echo off
REM ============================================================
REM  Create Desktop Shortcuts
REM  Creates shortcuts for setup and run scripts
REM ============================================================

setlocal enabledelayedexpansion

echo.
echo ============================================================
echo   CREATE DESKTOP SHORTCUTS
echo ============================================================
echo.

REM Get current directory
cd /d "%~dp0"
set PROJECT_DIR=%cd%
set DESKTOP=%USERPROFILE%\Desktop

echo Creating shortcuts on Desktop...
echo Project Folder: %PROJECT_DIR%
echo Desktop Folder: %DESKTOP%
echo.

REM Create shortcuts using PowerShell (more reliable than VBS)

echo Creating "Install Prerequisites" shortcut...
powershell -Command ^
  "$shell = New-Object -ComObject WScript.Shell; " ^
  "$shortcut = $shell.CreateShortcut('%DESKTOP%\1-Install Prerequisites.lnk'); " ^
  "$shortcut.TargetPath = '%PROJECT_DIR%\install-prerequisites.bat'; " ^
  "$shortcut.WorkingDirectory = '%PROJECT_DIR%'; " ^
  "$shortcut.Description = 'Check and install Node.js, PHP, Composer, Git'; " ^
  "$shortcut.IconLocation = 'C:\Windows\System32\cmd.exe,0'; " ^
  "$shortcut.Save()" >nul 2>&1

if %errorlevel% equ 0 (
    echo [OK] Created: 1-Install Prerequisites.lnk
) else (
    echo [ERROR] Failed to create shortcut
)

echo.
echo Creating "Setup Project" shortcut...
powershell -Command ^
  "$shell = New-Object -ComObject WScript.Shell; " ^
  "$shortcut = $shell.CreateShortcut('%DESKTOP%\2-Setup Project.lnk'); " ^
  "$shortcut.TargetPath = '%PROJECT_DIR%\setup-project.bat'; " ^
  "$shortcut.WorkingDirectory = '%PROJECT_DIR%'; " ^
  "$shortcut.Description = 'Install npm and PHP packages, setup database'; " ^
  "$shortcut.IconLocation = 'C:\Windows\System32\cmd.exe,0'; " ^
  "$shortcut.Save()" >nul 2>&1

if %errorlevel% equ 0 (
    echo [OK] Created: 2-Setup Project.lnk
) else (
    echo [ERROR] Failed to create shortcut
)

echo.
echo Creating "Run Website" shortcut...
powershell -Command ^
  "$shell = New-Object -ComObject WScript.Shell; " ^
  "$shortcut = $shell.CreateShortcut('%DESKTOP%\3-Run Website.lnk'); " ^
  "$shortcut.TargetPath = '%PROJECT_DIR%\run-website.bat'; " ^
  "$shortcut.WorkingDirectory = '%PROJECT_DIR%'; " ^
  "$shortcut.Description = 'Start Vite (5175) and Laravel (8000) servers'; " ^
  "$shortcut.IconLocation = 'C:\Windows\System32\cmd.exe,0'; " ^
  "$shortcut.Save()" >nul 2>&1

if %errorlevel% equ 0 (
    echo [OK] Created: 3-Run Website.lnk
) else (
    echo [ERROR] Failed to create shortcut
)

echo.
echo ============================================================
echo   SHORTCUTS CREATED!
echo ============================================================
echo.
echo Check your Desktop for:
echo   1-Install Prerequisites.lnk
echo   2-Setup Project.lnk
echo   3-Run Website.lnk
echo.
echo Usage:
echo   Step 1: Double-click "1-Install Prerequisites"
echo   Step 2: Double-click "2-Setup Project"
echo   Step 3: Double-click "3-Run Website"
echo.
pause
