# Create Desktop Shortcut for Equipment Rental System Launcher
# Run this as Administrator to create a desktop shortcut

param(
    [switch]$CreateShortcut = $false
)

# Get desktop path
$DesktopPath = [Environment]::GetFolderPath("Desktop")
$ProjectPath = "C:\laragon\www\peminjaman-alat"
$BatchFile = "$ProjectPath\START.bat"
$ShortcutPath = "$DesktopPath\Equipment Rental System.lnk"

# Check if batch file exists
if (-not (Test-Path $BatchFile)) {
    Write-Host "❌ Error: START.bat not found at $BatchFile" -ForegroundColor Red
    Write-Host "Please ensure the project path is correct."
    exit 1
}

try {
    # Create shell object
    $Shell = New-Object -ComObject WScript.Shell
    
    # Create shortcut
    $Shortcut = $Shell.CreateShortcut($ShortcutPath)
    $Shortcut.TargetPath = $BatchFile
    $Shortcut.WorkingDirectory = $ProjectPath
    $Shortcut.Description = "Equipment Rental System - Vue.js + Laravel Development"
    $Shortcut.IconLocation = "C:\Windows\System32\cmd.exe,0"
    $Shortcut.Save()
    
    Write-Host ""
    Write-Host "================================================" -ForegroundColor Green
    Write-Host "✅ DESKTOP SHORTCUT CREATED SUCCESSFULLY!" -ForegroundColor Green
    Write-Host "================================================" -ForegroundColor Green
    Write-Host ""
    Write-Host "📍 Location: $ShortcutPath" -ForegroundColor Cyan
    Write-Host ""
    Write-Host "✨ You can now:" -ForegroundColor Yellow
    Write-Host "   • Double-click the icon on Desktop to launch"
    Write-Host "   • Pin it to Taskbar for quick access"
    Write-Host "   • Rename it to something you like"
    Write-Host ""
    Write-Host "================================================" -ForegroundColor Green
    Write-Host ""
    
} catch {
    Write-Host "❌ Error creating shortcut: $_" -ForegroundColor Red
    exit 1
}
