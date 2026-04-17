<?php

namespace App\Helpers;

/**
 * Helper untuk mapping kolom database ke nama-nama Indonesia
 * Memastikan API responses menggunakan Bahasa Indonesia
 */
class ColumnMapper
{
    /**
     * Mapping kolom database ke nama Indonesia
     */
    private static $columnMappings = [
        // Users
        'id_user' => 'id_pengguna',
        'nama_lengkap' => 'nama_lengkap',
        'email' => 'email',
        'phone' => 'telepon',
        'password' => 'kata_sandi',
        'role' => 'peran',
        'is_active' => 'aktif',
        'email_verified' => 'email_terverifikasi',
        'created_at' => 'dibuat_pada',
        'updated_at' => 'diperbarui_pada',

        // Equipment
        'id_equipment' => 'id_alat',
        'nama_alat' => 'nama_alat',
        'deskripsi' => 'deskripsi',
        'quantity' => 'jumlah',
        'total_stok' => 'total_stok',
        'condition' => 'kondisi',
        'photo' => 'foto',
        'is_available' => 'tersedia',
        'fine_per_day' => 'denda_per_hari',

        // Borrowing
        'id_borrowing' => 'id_peminjaman',
        'borrow_date' => 'tanggal_pinjam',
        'planned_return_date' => 'tanggal_rencana_kembali',
        'actual_return_date' => 'tanggal_kembali_aktual',
        'status' => 'status',
        'quantity' => 'jumlah',
        'fine_amount' => 'jumlah_denda',
        'fine_paid' => 'denda_dibayar',
        'notes' => 'catatan',
        'keperluan' => 'keperluan',

        // Category
        'id_category' => 'id_kategori',
        'name' => 'nama',
        'description' => 'deskripsi',

        // Notification
        'id_notification' => 'id_notifikasi',
        'title' => 'judul',
        'message' => 'pesan',
        'is_read' => 'sudah_dibaca',
        'read_at' => 'dibaca_pada',
        'is_archived' => 'diarsipkan',
        'archived_at' => 'diarsipkan_pada',
        'priority' => 'prioritas',
    ];

    /**
     * Transform array dari database kolom ke nama Indonesia
     */
    public static function transform(array $data): array
    {
        $transformed = [];
        foreach ($data as $key => $value) {
            $indonesianKey = self::$columnMappings[$key] ?? $key;
            if (is_array($value) && !empty($value) && is_array(reset($value))) {
                // Jika nested array, transform setiap item
                $transformed[$indonesianKey] = array_map(fn($item) => self::transform($item), $value);
            } elseif (is_array($value)) {
                $transformed[$indonesianKey] = self::transform($value);
            } else {
                $transformed[$indonesianKey] = $value;
            }
        }
        return $transformed;
    }

    /**
     * Dapatkan mapping untuk kolom spesifik
     */
    public static function get($column): string
    {
        return self::$columnMappings[$column] ?? $column;
    }

    /**
     * Cek apakah kolom perlu di-map
     */
    public static function needsMapping($column): bool
    {
        return isset(self::$columnMappings[$column]);
    }
}
