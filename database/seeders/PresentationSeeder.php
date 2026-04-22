<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class PresentationSeeder extends Seeder
{
    public function run(): void
    {
        $now = now()->toDateTimeString();

        $this->seedCategories($now);
        $this->seedUsers($now);
        $this->seedTools($now);
    }

    private function seedCategories(string $now): void
    {
        $categories = [
            ['id' => 1, 'nama_kategori' => 'Elektronik', 'kode_kategori' => 'ELK', 'deskripsi' => 'Peralatan elektronik untuk pengukuran dan troubleshooting.'],
            ['id' => 2, 'nama_kategori' => 'Mekanik', 'kode_kategori' => 'MEK', 'deskripsi' => 'Peralatan mekanik untuk perawatan dan perakitan.'],
            ['id' => 3, 'nama_kategori' => 'Kalibrasi', 'kode_kategori' => 'KLB', 'deskripsi' => 'Perangkat kalibrasi dan standar pengukuran.'],
            ['id' => 4, 'nama_kategori' => 'Safety', 'kode_kategori' => 'SFT', 'deskripsi' => 'Perlengkapan keselamatan kerja dan proteksi lapangan.'],
            ['id' => 5, 'nama_kategori' => 'Audio', 'kode_kategori' => 'AUD', 'deskripsi' => 'Perangkat audio untuk meeting dan presentasi.'],
            ['id' => 6, 'nama_kategori' => 'Komputer', 'kode_kategori' => 'KOM', 'deskripsi' => 'Peralatan komputer untuk administrasi dan produktivitas.'],
            ['id' => 7, 'nama_kategori' => 'Dokumentasi', 'kode_kategori' => 'DOC', 'deskripsi' => 'Peralatan dokumentasi dan pencetakan data.'],
            ['id' => 8, 'nama_kategori' => 'Presentasi', 'kode_kategori' => 'PRS', 'deskripsi' => 'Perangkat presentasi untuk briefing dan rapat.'],
            ['id' => 9, 'nama_kategori' => 'Network', 'kode_kategori' => 'NET', 'deskripsi' => 'Perangkat jaringan dan konektivitas.'],
            ['id' => 10, 'nama_kategori' => 'Perlengkapan Umum', 'kode_kategori' => 'GEN', 'deskripsi' => 'Perlengkapan umum untuk kebutuhan operasional harian.'],
        ];

        foreach ($categories as $category) {
            DB::table('categories')->updateOrInsert(
                ['id' => $category['id']],
                [
                    'nama_kategori' => $category['nama_kategori'],
                    'kode_kategori' => $category['kode_kategori'],
                    'status' => 1,
                    'deskripsi' => $category['deskripsi'],
                    'gambar' => null,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );
        }
    }

    private function seedUsers(string $now): void
    {
        $users = [
            [
                'id' => 1,
                'nama' => 'Admin Utama',
                'email' => 'admin@trustequip.id',
                'role' => 1,
                'telepon' => '0812-1111-0001',
                'gambar' => '/uploads/users/admin-utama.svg',
                'initials' => 'A',
                'bg' => ['0F172A', '155E75'],
                'text' => 'F8FAFC',
            ],
            [
                'id' => 2,
                'nama' => 'Owner Utama',
                'email' => 'owner@trustequip.id',
                'role' => 2,
                'telepon' => '0812-2222-0002',
                'gambar' => '/uploads/users/owner-utama.svg',
                'initials' => 'O',
                'bg' => ['0F766E', '134E4A'],
                'text' => 'F8FAFC',
            ],
            [
                'id' => 3,
                'nama' => 'Staff Operasional',
                'email' => 'staff@trustequip.id',
                'role' => 3,
                'telepon' => '0812-3333-0003',
                'gambar' => '/uploads/users/staff-operasional.svg',
                'initials' => 'S',
                'bg' => ['1D4ED8', '0F172A'],
                'text' => 'F8FAFC',
            ],
            [
                'id' => 4,
                'nama' => 'Budi Peminjam',
                'email' => 'budi@trustequip.id',
                'role' => 4,
                'telepon' => '0812-4444-0004',
                'gambar' => null,
            ],
            [
                'id' => 5,
                'nama' => 'Siti Peminjam',
                'email' => 'siti@trustequip.id',
                'role' => 4,
                'telepon' => '0812-5555-0005',
                'gambar' => null,
            ],
            [
                'id' => 6,
                'nama' => 'Maya Peminjam',
                'email' => 'maya@trustequip.id',
                'role' => 4,
                'telepon' => '0812-6666-0006',
                'gambar' => null,
            ],
            [
                'id' => 7,
                'nama' => 'Eko Peminjam',
                'email' => 'eko@trustequip.id',
                'role' => 4,
                'telepon' => '0812-7777-0007',
                'gambar' => null,
            ],
        ];

        foreach ($users as $user) {
            if (!empty($user['gambar'])) {
                $this->writeAvatarSvg($user['gambar'], $user['initials'], $user['bg'][0], $user['bg'][1], $user['text']);
            }

            DB::table('users')->updateOrInsert(
                ['id' => $user['id']],
                [
                    'nama' => $user['nama'],
                    'email' => $user['email'],
                    'password_hash' => Hash::make('password123'),
                    'role' => $user['role'],
                    'status' => 1,
                    'telepon' => $user['telepon'],
                    'gambar' => $user['gambar'] ?? null,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );
        }

        DB::table('users')
            ->whereIn('role', [1, 2, 3])
            ->whereNotIn('id', [1, 2, 3])
            ->update([
                'role' => 4,
                'status' => 1,
                'updated_at' => $now,
            ]);
    }

    private function seedTools(string $now): void
    {
        $categoryIds = DB::table('categories')->pluck('id', 'kode_kategori');

        $tools = [
            [
                'id' => 1,
                'category_code' => 'ELK',
                'nama_alat' => 'Multimeter Digital',
                'deskripsi' => 'Alat ukur listrik untuk tegangan, arus, dan resistansi.',
                'harga_asli' => 350000,
                'stok' => 10,
                'kondisi' => 1,
                'status' => 1,
                'lokasi' => 'Gudang Elektronik',
                'gambar' => '/uploads/tools/multimeter-digital.svg',
                'art' => 'multimeter',
                'bg' => ['0EA5E9', '155E75'],
                'accent' => 'E0F2FE',
            ],
            [
                'id' => 2,
                'category_code' => 'ELK',
                'nama_alat' => 'Oscilloscope 100MHz',
                'deskripsi' => 'Perangkat analisis sinyal untuk pengujian rangkaian elektronik.',
                'harga_asli' => 12500000,
                'stok' => 2,
                'kondisi' => 2,
                'status' => 3,
                'lokasi' => 'Lab Elektronik',
                'gambar' => '/uploads/tools/oscilloscope-100mhz.svg',
                'art' => 'oscilloscope',
                'bg' => ['6366F1', '0F172A'],
                'accent' => 'E0E7FF',
            ],
            [
                'id' => 3,
                'category_code' => 'MEK',
                'nama_alat' => 'Bor Listrik',
                'deskripsi' => 'Bor listrik serbaguna untuk pekerjaan perakitan dan perawatan.',
                'harga_asli' => 850000,
                'stok' => 4,
                'kondisi' => 1,
                'status' => 1,
                'lokasi' => 'Workshop',
                'gambar' => '/uploads/tools/bor-listrik.svg',
                'art' => 'drill',
                'bg' => ['F59E0B', '7C2D12'],
                'accent' => 'FEF3C7',
            ],
            [
                'id' => 4,
                'category_code' => 'MEK',
                'nama_alat' => 'Kunci Torsi 1/2',
                'deskripsi' => 'Kunci torsi presisi untuk pengencangan baut sesuai spesifikasi.',
                'harga_asli' => 450000,
                'stok' => 3,
                'kondisi' => 2,
                'status' => 1,
                'lokasi' => 'Workshop',
                'gambar' => '/uploads/tools/kunci-torsi-1-2.svg',
                'art' => 'wrench',
                'bg' => ['10B981', '134E4A'],
                'accent' => 'D1FAE5',
            ],
            [
                'id' => 5,
                'category_code' => 'KLB',
                'nama_alat' => 'Caliper Digital',
                'deskripsi' => 'Alat ukur digital untuk pengukuran dimensi yang presisi.',
                'harga_asli' => 300000,
                'stok' => 5,
                'kondisi' => 1,
                'status' => 1,
                'lokasi' => 'Lab Kalibrasi',
                'gambar' => '/uploads/tools/caliper-digital.svg',
                'art' => 'caliper',
                'bg' => ['8B5CF6', '312E81'],
                'accent' => 'EDE9FE',
            ],
            [
                'id' => 6,
                'category_code' => 'SFT',
                'nama_alat' => 'Helm Safety',
                'deskripsi' => 'Helm pelindung untuk menunjang keselamatan kerja di lapangan.',
                'harga_asli' => 175000,
                'stok' => 20,
                'kondisi' => 1,
                'status' => 1,
                'lokasi' => 'Gudang Safety',
                'gambar' => '/uploads/tools/helm-safety.svg',
                'art' => 'helmet',
                'bg' => ['F97316', '7C2D12'],
                'accent' => 'FFEDD5',
            ],
            [
                'id' => 7,
                'category_code' => 'AUD',
                'nama_alat' => 'Speaker Portable',
                'deskripsi' => 'Speaker portabel untuk kebutuhan audio meeting dan presentasi.',
                'harga_asli' => 900000,
                'stok' => 6,
                'kondisi' => 1,
                'status' => 1,
                'lokasi' => 'Gudang Audio',
                'gambar' => '/uploads/tools/speaker-portable.svg',
                'art' => 'speaker',
                'bg' => ['EC4899', '831843'],
                'accent' => 'FCE7F3',
            ],
            [
                'id' => 8,
                'category_code' => 'KOM',
                'nama_alat' => 'Laptop Kantor',
                'deskripsi' => 'Laptop kerja untuk administrasi, dokumentasi, dan presentasi.',
                'harga_asli' => 9500000,
                'stok' => 8,
                'kondisi' => 1,
                'status' => 2,
                'lokasi' => 'Ruang IT',
                'gambar' => '/uploads/tools/laptop-kantor.svg',
                'art' => 'laptop',
                'bg' => ['64748B', '0F172A'],
                'accent' => 'E2E8F0',
            ],
            [
                'id' => 9,
                'category_code' => 'PRS',
                'nama_alat' => 'Proyektor HD',
                'deskripsi' => 'Proyektor untuk menampilkan materi rapat dan presentasi.',
                'harga_asli' => 6500000,
                'stok' => 3,
                'kondisi' => 1,
                'status' => 1,
                'lokasi' => 'Gudang Presentasi',
                'gambar' => '/uploads/tools/proyektor-hd.svg',
                'art' => 'projector',
                'bg' => ['38BDF8', '1E3A8A'],
                'accent' => 'DBEAFE',
            ],
            [
                'id' => 10,
                'category_code' => 'NET',
                'nama_alat' => 'Router WiFi 6',
                'deskripsi' => 'Router jaringan untuk kebutuhan konektivitas ruang kerja.',
                'harga_asli' => 1100000,
                'stok' => 2,
                'kondisi' => 3,
                'status' => 3,
                'lokasi' => 'Ruang IT',
                'gambar' => '/uploads/tools/router-wifi-6.svg',
                'art' => 'router',
                'bg' => ['14B8A6', '0F766E'],
                'accent' => 'CCFBF1',
            ],
        ];

        foreach ($tools as $tool) {
            $this->writeToolSvg($tool['gambar'], $tool['art'], $tool['bg'][0], $tool['bg'][1], $tool['accent']);

            DB::table('tools')->updateOrInsert(
                ['id' => $tool['id']],
                [
                    'category_id' => $categoryIds[$tool['category_code']] ?? 1,
                    'nama_alat' => $tool['nama_alat'],
                    'deskripsi' => $tool['deskripsi'],
                    'harga_asli' => $tool['harga_asli'],
                    'stok' => $tool['stok'],
                    'kondisi' => $tool['kondisi'],
                    'status' => $tool['status'],
                    'lokasi' => $tool['lokasi'],
                    'gambar' => $tool['gambar'],
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );
        }
    }

    private function writeAvatarSvg(string $relativePath, string $initial, string $bgStart, string $bgEnd, string $textColor): void
    {
        $path = public_path(ltrim($relativePath, '/'));
        File::ensureDirectoryExists(dirname($path));

        $svg = <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
  <defs>
    <linearGradient id="bg" x1="0" y1="0" x2="1" y2="1">
      <stop offset="0%" stop-color="#{$bgStart}" />
      <stop offset="100%" stop-color="#{$bgEnd}" />
    </linearGradient>
  </defs>
  <rect width="512" height="512" rx="128" fill="url(#bg)" />
  <circle cx="256" cy="196" r="108" fill="rgba(255,255,255,0.12)" />
  <text x="256" y="298" text-anchor="middle" font-family="Arial, Helvetica, sans-serif" font-size="180" font-weight="700" fill="#{$textColor}">{$initial}</text>
</svg>
SVG;

        File::put($path, $svg);
    }

    private function writeToolSvg(string $relativePath, string $variant, string $bgStart, string $bgEnd, string $accent): void
    {
        $path = public_path(ltrim($relativePath, '/'));
        File::ensureDirectoryExists(dirname($path));

        $icon = $this->buildToolIcon($variant, $accent);

        $svg = <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 800">
  <defs>
    <linearGradient id="bg" x1="0" y1="0" x2="1" y2="1">
      <stop offset="0%" stop-color="#{$bgStart}" />
      <stop offset="100%" stop-color="#{$bgEnd}" />
    </linearGradient>
    <filter id="shadow" x="-20%" y="-20%" width="140%" height="140%">
      <feDropShadow dx="0" dy="24" stdDeviation="24" flood-color="#020617" flood-opacity="0.28" />
    </filter>
  </defs>
  <rect width="1200" height="800" fill="url(#bg)" />
  <circle cx="190" cy="170" r="110" fill="rgba(255,255,255,0.10)" />
  <circle cx="1020" cy="610" r="150" fill="rgba(255,255,255,0.08)" />
  <rect x="128" y="104" width="944" height="592" rx="48" fill="rgba(255,255,255,0.10)" stroke="rgba(255,255,255,0.18)" />
  <g filter="url(#shadow)">
    <rect x="236" y="172" width="728" height="456" rx="42" fill="rgba(255,255,255,0.18)" stroke="rgba(255,255,255,0.24)" />
    {$icon}
  </g>
</svg>
SVG;

        File::put($path, $svg);
    }

    private function buildToolIcon(string $variant, string $accent): string
    {
        $accentHex = "#{$accent}";

        return match ($variant) {
            'multimeter' => <<<SVG
<g>
  <rect x="440" y="240" width="320" height="240" rx="28" fill="#0F172A" opacity="0.92" />
  <rect x="490" y="284" width="220" height="74" rx="14" fill="#{$accent}" opacity="0.92" />
  <circle cx="600" cy="412" r="42" fill="none" stroke="{$accentHex}" stroke-width="14" />
  <circle cx="600" cy="412" r="14" fill="{$accentHex}" />
  <path d="M470 386 C420 390, 394 426, 362 470" fill="none" stroke="{$accentHex}" stroke-width="10" stroke-linecap="round" />
  <path d="M730 386 C780 390, 806 426, 838 470" fill="none" stroke="{$accentHex}" stroke-width="10" stroke-linecap="round" />
  <circle cx="350" cy="480" r="18" fill="{$accentHex}" />
  <circle cx="850" cy="480" r="18" fill="{$accentHex}" />
</g>
SVG,
            'oscilloscope' => <<<SVG
<g>
  <rect x="390" y="218" width="420" height="280" rx="28" fill="#0F172A" opacity="0.94" />
  <rect x="430" y="258" width="340" height="180" rx="20" fill="#{$accent}" opacity="0.95" />
  <path d="M454 354 L494 354 L520 314 L548 392 L584 282 L618 370 L652 344 L690 344 L716 314" fill="none" stroke="#0F172A" stroke-width="12" stroke-linecap="round" stroke-linejoin="round" />
  <rect x="428" y="454" width="344" height="24" rx="12" fill="#{$accent}" opacity="0.8" />
  <circle cx="458" cy="518" r="22" fill="#{$accent}" />
  <circle cx="748" cy="518" r="22" fill="#{$accent}" />
</g>
SVG,
            'drill' => <<<SVG
<g transform="translate(0,8)">
  <rect x="360" y="320" width="300" height="110" rx="55" fill="#111827" opacity="0.95" />
  <rect x="540" y="280" width="180" height="150" rx="30" fill="#{$accent}" opacity="0.95" />
  <rect x="680" y="338" width="112" height="34" rx="17" fill="#111827" />
  <path d="M792 355 L900 355" stroke="#{$accent}" stroke-width="28" stroke-linecap="round" />
  <path d="M900 355 L948 332" stroke="#{$accent}" stroke-width="16" stroke-linecap="round" />
  <path d="M900 355 L948 378" stroke="#{$accent}" stroke-width="16" stroke-linecap="round" />
  <circle cx="448" cy="372" r="34" fill="#{$accent}" />
  <circle cx="448" cy="372" r="14" fill="#111827" />
</g>
SVG,
            'wrench' => <<<SVG
<g>
  <path d="M320 372 L616 372" stroke="#{$accent}" stroke-width="28" stroke-linecap="round" />
  <path d="M612 372 L812 240" stroke="#{$accent}" stroke-width="28" stroke-linecap="round" />
  <circle cx="848" cy="214" r="62" fill="none" stroke="#{$accent}" stroke-width="26" />
  <path d="M326 372 C288 372, 256 404, 256 442 C256 480, 288 512, 326 512 L376 512" fill="none" stroke="#111827" stroke-width="28" stroke-linecap="round" />
  <circle cx="366" cy="512" r="30" fill="#111827" />
</g>
SVG,
            'caliper' => <<<SVG
<g>
  <path d="M340 250 L340 560" stroke="#{$accent}" stroke-width="28" stroke-linecap="round" />
  <path d="M340 250 L760 250" stroke="#{$accent}" stroke-width="28" stroke-linecap="round" />
  <path d="M760 250 L760 548" stroke="#{$accent}" stroke-width="28" stroke-linecap="round" />
  <path d="M520 250 L520 520" stroke="#111827" stroke-width="18" stroke-linecap="round" />
  <path d="M520 520 L700 520" stroke="#111827" stroke-width="18" stroke-linecap="round" />
  <path d="M430 330 L520 330" stroke="#111827" stroke-width="18" stroke-linecap="round" />
  <path d="M760 430 L668 430" stroke="#111827" stroke-width="18" stroke-linecap="round" />
  <circle cx="748" cy="470" r="22" fill="#111827" />
</g>
SVG,
            'helmet' => <<<SVG
<g>
  <path d="M368 424 C368 318, 452 236, 560 236 C668 236, 752 318, 752 424 L752 476 L368 476 Z" fill="#{$accent}" />
  <path d="M410 410 C410 332, 472 278, 560 278 C648 278, 710 332, 710 410" fill="none" stroke="#111827" stroke-width="18" stroke-linecap="round" />
  <rect x="350" y="476" width="420" height="48" rx="24" fill="#111827" />
  <rect x="510" y="322" width="100" height="96" rx="18" fill="#111827" />
</g>
SVG,
            'speaker' => <<<SVG
<g>
  <rect x="412" y="232" width="292" height="336" rx="36" fill="#111827" opacity="0.96" />
  <circle cx="558" cy="336" r="70" fill="#{$accent}" opacity="0.95" />
  <circle cx="558" cy="336" r="34" fill="#111827" />
  <circle cx="558" cy="488" r="50" fill="#{$accent}" opacity="0.85" />
  <path d="M314 342 C286 360, 286 448, 314 466" fill="none" stroke="#{$accent}" stroke-width="18" stroke-linecap="round" />
  <path d="M244 300 C200 340, 200 468, 244 508" fill="none" stroke="#{$accent}" stroke-width="12" stroke-linecap="round" opacity="0.9" />
</g>
SVG,
            'laptop' => <<<SVG
<g>
  <rect x="392" y="236" width="416" height="250" rx="24" fill="#111827" opacity="0.96" />
  <rect x="416" y="260" width="368" height="202" rx="16" fill="#{$accent}" opacity="0.92" />
  <path d="M360 520 L840 520" stroke="#111827" stroke-width="32" stroke-linecap="round" />
  <path d="M420 520 L780 520" stroke="#{$accent}" stroke-width="10" stroke-linecap="round" opacity="0.9" />
  <circle cx="558" cy="362" r="24" fill="#111827" />
</g>
SVG,
            'projector' => <<<SVG
<g>
  <rect x="388" y="286" width="404" height="172" rx="34" fill="#111827" opacity="0.95" />
  <circle cx="558" cy="372" r="60" fill="#{$accent}" opacity="0.95" />
  <circle cx="558" cy="372" r="24" fill="#111827" />
  <path d="M792 350 L952 290" stroke="#{$accent}" stroke-width="18" stroke-linecap="round" />
  <path d="M792 382 L972 382" stroke="#{$accent}" stroke-width="18" stroke-linecap="round" />
  <path d="M792 414 L952 474" stroke="#{$accent}" stroke-width="18" stroke-linecap="round" />
  <rect x="470" y="466" width="176" height="20" rx="10" fill="#{$accent}" />
</g>
SVG,
            'router' => <<<SVG
<g>
  <rect x="392" y="382" width="416" height="118" rx="28" fill="#111827" opacity="0.95" />
  <rect x="428" y="412" width="344" height="14" rx="7" fill="#{$accent}" opacity="0.95" />
  <circle cx="490" cy="454" r="16" fill="#{$accent}" />
  <circle cx="538" cy="454" r="16" fill="#{$accent}" />
  <circle cx="586" cy="454" r="16" fill="#{$accent}" />
  <path d="M486 382 L462 294" stroke="#{$accent}" stroke-width="14" stroke-linecap="round" />
  <path d="M714 382 L738 294" stroke="#{$accent}" stroke-width="14" stroke-linecap="round" />
  <path d="M430 294 C448 270, 474 256, 506 250" fill="none" stroke="#{$accent}" stroke-width="10" stroke-linecap="round" />
  <path d="M770 294 C752 270, 726 256, 694 250" fill="none" stroke="#{$accent}" stroke-width="10" stroke-linecap="round" />
</g>
SVG,
            default => '<g />',
        };
    }
}
