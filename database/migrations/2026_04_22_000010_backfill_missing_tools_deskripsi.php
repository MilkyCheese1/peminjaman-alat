<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $tools = DB::table('tools')
            ->leftJoin('categories', 'tools.category_id', '=', 'categories.id')
            ->whereNull('tools.deskripsi')
            ->orWhere('tools.deskripsi', '=', '')
            ->select('tools.id', 'tools.nama_alat', 'tools.stok', 'tools.status', 'categories.nama_kategori as kategori')
            ->get();

        foreach ($tools as $tool) {
            $kategori = trim((string) ($tool->kategori ?? 'Peralatan')) ?: 'Peralatan';
            $namaAlat = trim((string) $tool->nama_alat);
            $stok = (int) ($tool->stok ?? 0);
            $status = (int) ($tool->status ?? 1);

            $statusLabel = match ($status) {
                2 => 'sedang dipinjam',
                3 => 'sedang maintenance',
                default => 'siap dipinjam',
            };

            $deskripsi = sprintf(
                '%s kategori %s untuk kebutuhan operasional. Tersedia %d stok dan saat ini %s.',
                $namaAlat ?: 'Alat',
                $kategori,
                $stok,
                $statusLabel
            );

            DB::table('tools')
                ->where('id', $tool->id)
                ->update(['deskripsi' => $deskripsi]);
        }
    }

    public function down(): void
    {
        DB::table('tools')->update(['deskripsi' => null]);
    }
};
