<?php

/**
 * Aplikasi ini menggunakan Bahasa Indonesia untuk semua messages dan responses
 * Ini adalah file konfigurasi sentral untuk semua pesan sistem
 */

return [
    // ================ BORROWING ================
    'borrowing' => [
        'created' => 'Permohonan peminjaman berhasil dibuat',
        'updated' => 'Peminjaman berhasil diperbarui',
        'approved' => 'Peminjaman berhasil disetujui',
        'rejected' => 'Peminjaman berhasil ditolak',
        'retrieved' => 'Peminjaman berhasil diambil',
        'returned' => 'Peminjaman berhasil dikembalikan',
        'not_found' => 'Peminjaman tidak ditemukan',
        'only_picked_up_can_be_returned' => 'Hanya peminjaman yang sudah diambil yang dapat dikembalikan',
        'insufficient_quantity' => 'Alat tidak tersedia dengan jumlah yang diminta',
        'invalid_date_range' => 'Validasi tanggal gagal',
        'invalid_duration' => 'Durasi peminjaman minimal 1 jam, maksimal 14 hari',
        'validation_failed' => 'Validasi gagal',
        'failed_to_create' => 'Gagal membuat permohonan peminjaman',
        'failed_to_retrieve' => 'Gagal mengambil peminjaman',
        'failed_to_update' => 'Gagal memperbarui peminjaman',
        'failed_to_verify_return' => 'Gagal memverifikasi pengembalian',
    ],

    // ================ EQUIPMENT ================
    'equipment' => [
        'created' => 'Alat berhasil ditambahkan',
        'updated' => 'Alat berhasil diperbarui',
        'deleted' => 'Alat berhasil dihapus',
        'retrieved' => 'Alat berhasil diambil',
        'not_found' => 'Alat tidak ditemukan',
        'validation_failed' => 'Validasi gagal',
        'failed_to_create' => 'Gagal menambahkan alat',
        'failed_to_update' => 'Gagal memperbarui alat',
        'failed_to_delete' => 'Gagal menghapus alat',
        'failed_to_retrieve' => 'Gagal mengambil alat',
        'photo_upload_error' => 'Gagal mengunggah foto',
    ],

    // ================ USER ================
    'user' => [
        'login_success' => 'Login berhasil',
        'register_success' => 'Pendaftaran berhasil',
        'email_not_found' => 'Email tidak terdaftar',
        'invalid_password' => 'Password tidak sesuai',
        'account_inactive' => 'Akun Anda tidak aktif',
        'unauthorized' => 'Tidak diizinkan',
        'validation_failed' => 'Validasi gagal',
        'failed_to_login' => 'Gagal login',
        'failed_to_register' => 'Gagal mendaftar',
        'failed_to_update' => 'Gagal memperbarui profil',
    ],

    // ================ CATEGORY ================
    'category' => [
        'created' => 'Kategori berhasil dibuat',
        'updated' => 'Kategori berhasil diperbarui',
        'deleted' => 'Kategori berhasil dihapus',
        'retrieved' => 'Kategori berhasil diambil',
        'not_found' => 'Kategori tidak ditemukan',
        'validation_failed' => 'Validasi gagal',
    ],

    // ================ NOTIFICATION ================
    'notification' => [
        'created' => 'Notifikasi berhasil dibuat',
        'updated' => 'Notifikasi berhasil diperbarui',
        'sent' => 'Notifikasi berhasil dikirim',
        'user_not_authenticated' => 'User tidak terotentikasi',
        'not_found' => 'Notifikasi tidak ditemukan',
        'validation_failed' => 'Validasi gagal',
    ],

    // ================ GENERAL ================
    'general' => [
        'success' => 'Berhasil',
        'error' => 'Gagal',
        'validation_error' => 'Terjadi kesalahan validasi',
        'server_error' => 'Terjadi kesalahan server',
        'not_found' => 'Tidak ditemukan',
        'unauthorized' => 'Tidak diizinkan',
        'forbidden' => 'Dilarang',
        'created_at' => 'Dibuat pada',
        'updated_at' => 'Diperbarui pada',
    ],
];
