param(
  [switch]$ImportDatabase
)

$ErrorActionPreference = 'Stop'

Set-Location $PSScriptRoot

Write-Host 'TrustEquip.id setup helper' -ForegroundColor Cyan

if (!(Test-Path '.env')) {
  Copy-Item '.env.example' '.env'
  Write-Host '.env dibuat dari .env.example' -ForegroundColor Green
}

if (Get-Command composer -ErrorAction SilentlyContinue) {
  Write-Host 'Menjalankan composer install...' -ForegroundColor Yellow
  composer install
} else {
  Write-Host 'Composer tidak ditemukan. Install dependency PHP secara manual.' -ForegroundColor DarkYellow
}

if (Get-Command npm -ErrorAction SilentlyContinue) {
  Write-Host 'Menjalankan npm install...' -ForegroundColor Yellow
  npm install
} else {
  Write-Host 'npm tidak ditemukan. Install dependency frontend secara manual.' -ForegroundColor DarkYellow
}

if (Get-Command php -ErrorAction SilentlyContinue) {
  Write-Host 'Membuat APP_KEY...' -ForegroundColor Yellow
  php artisan key:generate
} else {
  Write-Host 'PHP tidak ditemukan. Lewati artisan key:generate.' -ForegroundColor DarkYellow
}

if ($ImportDatabase) {
  $sqlPath = Join-Path $PSScriptRoot 'db_peminjaman.sql'
  if (!(Test-Path $sqlPath)) {
    Write-Host 'File db_peminjaman.sql tidak ditemukan.' -ForegroundColor Red
    exit 1
  }

  Write-Host 'Database dump tersedia di db_peminjaman.sql.' -ForegroundColor Green
  Write-Host 'Import manual:' -ForegroundColor Cyan
  Write-Host '  mysql -u root -p nama_database < db_peminjaman.sql'
}

Write-Host ''
Write-Host 'Langkah berikutnya:' -ForegroundColor Cyan
Write-Host '1. Sesuaikan DB_* di .env'
Write-Host '2. Jalankan php artisan migrate jika perlu'
Write-Host '3. Jalankan start-dev.bat atau npm run start'
