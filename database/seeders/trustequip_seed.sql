SET NAMES utf8mb4;
SET time_zone = '+07:00';

-- Seed data untuk database `db_peminjaman`.
-- Jalankan setelah migration selesai.
-- Opsional reset (hapus komentar jika diperlukan):
-- SET FOREIGN_KEY_CHECKS=0;
-- TRUNCATE TABLE activity_logs;
-- TRUNCATE TABLE notifications;
-- TRUNCATE TABLE borrowings;
-- TRUNCATE TABLE tools;
-- TRUNCATE TABLE categories;
-- TRUNCATE TABLE users;
-- TRUNCATE TABLE feedback_entries;
-- SET FOREIGN_KEY_CHECKS=1;

-- =========================================================
-- USERS
-- Password semua user seed: password123
-- =========================================================
INSERT INTO users (id, nama, email, password_hash, role, status, telepon, created_at, updated_at) VALUES
(1, 'Admin Utama', 'admin@trustequip.test', '$2y$10$XIc/YF3JrkpTnz5wp59e/OPRRWwNEJMzQCzNGmz3CHLy9MSuS0Up6', 1, 1, '0812-1111-1111', '2026-04-01 09:00:00', '2026-04-01 09:00:00'),
(2, 'Owner Perusahaan', 'owner@trustequip.test', '$2y$10$XIc/YF3JrkpTnz5wp59e/OPRRWwNEJMzQCzNGmz3CHLy9MSuS0Up6', 2, 1, '0812-2222-2222', '2026-04-01 09:05:00', '2026-04-01 09:05:00'),
(3, 'Raka Staff', 'raka.staff@trustequip.test', '$2y$10$XIc/YF3JrkpTnz5wp59e/OPRRWwNEJMzQCzNGmz3CHLy9MSuS0Up6', 3, 1, '0812-3333-3333', '2026-04-01 09:10:00', '2026-04-10 10:00:00'),
(4, 'Dina Staff', 'dina.staff@trustequip.test', '$2y$10$XIc/YF3JrkpTnz5wp59e/OPRRWwNEJMzQCzNGmz3CHLy9MSuS0Up6', 3, 1, '0812-4444-4444', '2026-04-01 09:12:00', '2026-04-09 08:30:00'),
(5, 'Sari Staff', 'sari.staff@trustequip.test', '$2y$10$XIc/YF3JrkpTnz5wp59e/OPRRWwNEJMzQCzNGmz3CHLy9MSuS0Up6', 3, 1, '0812-5555-5551', '2026-04-01 09:14:00', '2026-04-11 14:20:00'),
(6, 'Budi Peminjam', 'budi@trustequip.test', '$2y$10$XIc/YF3JrkpTnz5wp59e/OPRRWwNEJMzQCzNGmz3CHLy9MSuS0Up6', 4, 1, '0812-6666-6666', '2026-04-02 11:00:00', '2026-04-02 11:00:00'),
(7, 'Siti Peminjam', 'siti@trustequip.test', '$2y$10$XIc/YF3JrkpTnz5wp59e/OPRRWwNEJMzQCzNGmz3CHLy9MSuS0Up6', 4, 1, '0812-7777-7777', '2026-04-03 14:20:00', '2026-04-03 14:20:00'),
(8, 'Andi Peminjam', 'andi@trustequip.test', '$2y$10$XIc/YF3JrkpTnz5wp59e/OPRRWwNEJMzQCzNGmz3CHLy9MSuS0Up6', 4, 2, '0812-8888-8888', '2026-04-04 08:00:00', '2026-04-15 09:10:00'),
(9, 'Maya Peminjam', 'maya@trustequip.test', '$2y$10$XIc/YF3JrkpTnz5wp59e/OPRRWwNEJMzQCzNGmz3CHLy9MSuS0Up6', 4, 1, '0812-9999-9999', '2026-04-05 16:45:00', '2026-04-05 16:45:00'),
(10, 'Eko Peminjam', 'eko@trustequip.test', '$2y$10$XIc/YF3JrkpTnz5wp59e/OPRRWwNEJMzQCzNGmz3CHLy9MSuS0Up6', 4, 1, '0812-1010-1010', '2026-04-06 10:15:00', '2026-04-06 10:15:00');

-- =========================================================
-- CATEGORIES
-- =========================================================
INSERT INTO categories (id, nama_kategori, kode_kategori, status, deskripsi, gambar, created_at, updated_at) VALUES
(1, 'Elektronik', 'ELK', 1, 'Peralatan elektronik untuk pengukuran dan troubleshooting.', NULL, '2026-04-01 10:00:00', '2026-04-01 10:00:00'),
(2, 'Mekanik', 'MEK', 1, 'Peralatan mekanik untuk perawatan dan perakitan.', NULL, '2026-04-01 10:05:00', '2026-04-01 10:05:00'),
(3, 'Kalibrasi', 'KLB', 1, 'Perangkat kalibrasi dan standar pengukuran.', NULL, '2026-04-01 10:10:00', '2026-04-01 10:10:00'),
(4, 'Safety', 'SFT', 1, 'Perlengkapan keselamatan kerja dan proteksi lapangan.', NULL, '2026-04-01 10:15:00', '2026-04-01 10:15:00'),
(5, 'Audio', 'AUD', 1, 'Perangkat audio untuk meeting dan presentasi.', NULL, '2026-04-01 10:20:00', '2026-04-01 10:20:00'),
(6, 'Komputer', 'KOM', 1, 'Peralatan komputer untuk administrasi dan produktivitas.', NULL, '2026-04-01 10:25:00', '2026-04-01 10:25:00'),
(7, 'Dokumentasi', 'DOC', 1, 'Peralatan dokumentasi dan pencetakan data.', NULL, '2026-04-01 10:30:00', '2026-04-01 10:30:00'),
(8, 'Presentasi', 'PRS', 1, 'Perangkat presentasi untuk briefing dan rapat.', NULL, '2026-04-01 10:35:00', '2026-04-01 10:35:00'),
(9, 'Network', 'NET', 1, 'Perangkat jaringan dan konektivitas.', NULL, '2026-04-01 10:40:00', '2026-04-01 10:40:00'),
(10, 'Nonaktif Demo', 'OFF', 2, 'Kategori nonaktif untuk kebutuhan demo data.', NULL, '2026-04-01 10:45:00', '2026-04-01 10:45:00');

-- =========================================================
-- TOOLS
-- kondisi: 1=Baik,2=PerluKalibrasi,3=RusakRingan,4=RusakBerat
-- status : 1=Tersedia,2=Dipinjam,3=Maintenance
-- =========================================================
INSERT INTO tools (id, category_id, nama_alat, stok, kondisi, status, lokasi, gambar, created_at, updated_at) VALUES
(1, 1, 'Multimeter Digital', 10, 1, 1, 'Gudang Elektronik', NULL, '2026-04-02 09:00:00', '2026-04-02 09:00:00'),
(2, 1, 'Oscilloscope 100MHz', 2, 2, 3, 'Lab Elektronik', NULL, '2026-04-02 09:05:00', '2026-04-18 13:00:00'),
(3, 2, 'Bor Listrik', 4, 1, 1, 'Workshop', NULL, '2026-04-02 09:10:00', '2026-04-02 09:10:00'),
(4, 2, 'Kunci Torsi 1/2', 3, 2, 1, 'Workshop', NULL, '2026-04-02 09:15:00', '2026-04-02 09:15:00'),
(5, 3, 'Caliper Digital', 5, 1, 1, 'Lab Kalibrasi', NULL, '2026-04-02 09:20:00', '2026-04-11 11:00:00'),
(6, 4, 'Helm Safety', 20, 1, 1, 'Gudang Safety', NULL, '2026-04-02 09:25:00', '2026-04-02 09:25:00'),
(7, 5, 'Speaker Portable', 6, 1, 1, 'Gudang Audio', NULL, '2026-04-02 09:30:00', '2026-04-02 09:30:00'),
(8, 6, 'Laptop Kantor', 8, 1, 2, 'Ruang IT', NULL, '2026-04-02 09:35:00', '2026-04-20 09:00:00'),
(9, 8, 'Proyektor HD', 3, 1, 1, 'Gudang Presentasi', NULL, '2026-04-02 09:40:00', '2026-04-02 09:40:00'),
(10, 9, 'Router WiFi 6', 2, 3, 3, 'Ruang IT', NULL, '2026-04-02 09:45:00', '2026-04-16 15:40:00');

-- =========================================================
-- BORROWINGS
-- status: 1=Pending,2=Disetujui,3=Ditolak,4=Dipinjam,5=Dikembalikan,6=Selesai
-- =========================================================
INSERT INTO borrowings (
  id, kode, peminjam_id, nama_peminjam, divisi, alat_id, nama_alat, kategori, petugas_id, petugas_nama,
  keperluan, tgl_pinjam, tgl_kembali_rencana, tgl_kembali_aktual, status, biaya, catatan, gambar, created_at, updated_at
) VALUES
(1, 'PMJ-20260410-001', 6, 'Budi Peminjam', 'Produksi', 1, 'Multimeter Digital', 'Elektronik', 3, 'Raka Staff',
 'Cek tegangan panel', '2026-04-10', '2026-04-12', NULL, 1, 0, 'Menunggu persetujuan staff.', NULL, '2026-04-10 09:00:00', '2026-04-10 09:00:00'),
(2, 'PMJ-20260410-002', 7, 'Siti Peminjam', 'QA', 5, 'Caliper Digital', 'Kalibrasi', 4, 'Dina Staff',
 'Pengukuran sampel batch 42', '2026-04-10', '2026-04-11', NULL, 2, 0, 'Disetujui, siap diambil.', NULL, '2026-04-10 10:00:00', '2026-04-10 10:15:00'),
(3, 'PMJ-20260411-001', 7, 'Siti Peminjam', 'QA', 5, 'Caliper Digital', 'Kalibrasi', 4, 'Dina Staff',
 'Pengukuran lanjutan', '2026-04-11', '2026-04-12', NULL, 4, 0, 'Sedang dipinjam.', NULL, '2026-04-11 08:30:00', '2026-04-11 08:45:00'),
(4, 'PMJ-20260411-002', 9, 'Maya Peminjam', 'Maintenance', 3, 'Bor Listrik', 'Mekanik', 3, 'Raka Staff',
 'Perbaikan pompa', '2026-04-11', '2026-04-13', '2026-04-13', 5, 0, 'Dikembalikan dan dicek ulang.', NULL, '2026-04-11 09:15:00', '2026-04-13 16:30:00'),
(5, 'PMJ-20260412-001', 10, 'Eko Peminjam', 'R&D', 9, 'Proyektor HD', 'Presentasi', 5, 'Sari Staff',
 'Demo proposal proyek', '2026-04-12', '2026-04-14', '2026-04-14', 6, 0, 'Selesai tanpa kendala.', NULL, '2026-04-12 09:10:00', '2026-04-14 17:10:00'),
(6, 'PMJ-20260412-002', 8, 'Andi Peminjam', 'Produksi', 4, 'Kunci Torsi 1/2', 'Mekanik', 3, 'Raka Staff',
 'Kalibrasi baut mesin', '2026-04-12', '2026-04-13', NULL, 3, 0, 'Ditolak karena alat sedang dibutuhkan tim lain.', NULL, '2026-04-12 11:20:00', '2026-04-12 11:45:00'),
(7, 'PMJ-20260413-001', NULL, 'Guest Vendor', 'Tamu', 6, 'Helm Safety', 'Safety', 4, 'Dina Staff',
 'Kunjungan area produksi', '2026-04-13', '2026-04-13', '2026-04-13', 6, 0, 'Transaksi tamu selesai di hari yang sama.', NULL, '2026-04-13 08:00:00', '2026-04-13 17:00:00'),
(8, 'PMJ-20260414-001', 6, 'Budi Peminjam', 'Produksi', 8, 'Laptop Kantor', 'Komputer', 5, 'Sari Staff',
 'Presentasi internal tim', '2026-04-14', '2026-04-16', NULL, 2, 0, 'Disetujui, menunggu serah terima.', NULL, '2026-04-14 10:00:00', '2026-04-14 10:20:00'),
(9, 'PMJ-20260415-001', 9, 'Maya Peminjam', 'QC', 7, 'Speaker Portable', 'Audio', 3, 'Raka Staff',
 'Briefing lapangan', '2026-04-15', '2026-04-17', NULL, 4, 0, 'Sedang dipinjam.', NULL, '2026-04-15 13:30:00', '2026-04-15 13:45:00'),
(10, 'PMJ-20260416-001', 10, 'Eko Peminjam', 'IT', 10, 'Router WiFi 6', 'Network', 4, 'Dina Staff',
 'Uji koneksi ruangan meeting', '2026-04-16', '2026-04-18', NULL, 1, 0, 'Menunggu verifikasi kelengkapan.', NULL, '2026-04-16 09:45:00', '2026-04-16 09:45:00');

-- =========================================================
-- NOTIFICATIONS
-- tipe: 1=Info,2=Success,3=Warning,4=Error
-- =========================================================
INSERT INTO notifications (id, user_id, judul, pesan, tipe, is_read, created_at) VALUES
(1, 3, 'Permintaan Baru', 'Ada permintaan peminjaman baru: PMJ-20260410-001', 1, 0, '2026-04-10 09:01:00'),
(2, 4, 'Permintaan Disetujui', 'Transaksi PMJ-20260410-002 disetujui.', 2, 1, '2026-04-10 10:16:00'),
(3, 5, 'Peminjaman Aktif', 'Ada alat yang sedang dipinjam dan menunggu pengembalian.', 1, 0, '2026-04-11 08:50:00'),
(4, 6, 'Status Disetujui', 'Permintaan Anda telah disetujui, silakan ambil alat.', 2, 1, '2026-04-14 10:25:00'),
(5, 7, 'Perlu Tindakan', 'Permintaan Anda masih menunggu keputusan staff.', 3, 0, '2026-04-12 11:25:00'),
(6, 8, 'Akun Nonaktif', 'Akun Anda saat ini nonaktif. Hubungi admin.', 4, 0, '2026-04-15 09:10:00'),
(7, 9, 'Pengembalian Berhasil', 'Alat pada transaksi PMJ-20260411-002 telah dikembalikan.', 2, 1, '2026-04-13 16:31:00'),
(8, 10, 'Menunggu Verifikasi', 'Permintaan Anda sedang diverifikasi oleh staff.', 1, 0, '2026-04-16 09:50:00'),
(9, 1, 'Laporan Harian', 'Rekap aktivitas hari ini berhasil diperbarui.', 1, 1, '2026-04-16 18:00:00'),
(10, 2, 'Ringkasan Sistem', 'Dashboard sudah memuat data peminjaman terbaru.', 2, 1, '2026-04-16 18:05:00');

-- =========================================================
-- ACTIVITY LOGS
-- =========================================================
INSERT INTO activity_logs (id, user_id, aksi, entitas, entitas_id, deskripsi, ip_address, created_at) VALUES
(1, 1, 'CREATE', 'users', 3, 'Membuat akun staff: Raka Staff', INET6_ATON('192.168.1.10'), '2026-04-01 09:10:10'),
(2, 1, 'CREATE', 'users', 4, 'Membuat akun staff: Dina Staff', INET6_ATON('192.168.1.10'), '2026-04-01 09:12:10'),
(3, 1, 'CREATE', 'users', 5, 'Membuat akun staff: Sari Staff', INET6_ATON('192.168.1.10'), '2026-04-01 09:14:10'),
(4, 1, 'CREATE', 'categories', 1, 'Menambah kategori Elektronik', INET6_ATON('192.168.1.10'), '2026-04-01 10:00:10'),
(5, 3, 'CREATE', 'borrowings', 1, 'Menerima request PMJ-20260410-001', INET6_ATON('192.168.1.20'), '2026-04-10 09:00:30'),
(6, 4, 'UPDATE', 'borrowings', 2, 'Menyetujui transaksi PMJ-20260410-002', INET6_ATON('192.168.1.21'), '2026-04-10 10:15:30'),
(7, 4, 'UPDATE', 'borrowings', 4, 'Mencatat pengembalian PMJ-20260411-002', INET6_ATON('192.168.1.21'), '2026-04-13 16:30:30'),
(8, 5, 'UPDATE', 'tools', 8, 'Menandai laptop kantor untuk dipinjam', INET6_ATON('192.168.1.22'), '2026-04-14 10:20:00'),
(9, 3, 'UPDATE', 'borrowings', 9, 'Meninjau transaksi PMJ-20260415-001', INET6_ATON('192.168.1.20'), '2026-04-15 13:45:00'),
(10, 2, 'UPDATE', 'feedback_entries', 1, 'Menampilkan feedback Budi Peminjam', INET6_ATON('192.168.1.30'), '2026-04-16 18:10:00');

-- =========================================================
-- FEEDBACK ENTRIES
-- status: 1=Pending,2=Ditampilkan,3=Ditolak
-- =========================================================
INSERT INTO feedback_entries (id, nama, email, stars, pesan, status, created_at) VALUES
(1, 'Budi Peminjam', 'budi@trustequip.test', 5, 'Proses peminjaman cepat dan jelas.', 2, '2026-04-12 12:00:00'),
(2, 'Siti Peminjam', 'siti@trustequip.test', 4, 'UI-nya enak dipakai, tapi mohon tambah filter alat.', 1, '2026-04-13 09:30:00'),
(3, 'Maya Peminjam', 'maya@trustequip.test', 5, 'Petugas sangat membantu dan komunikatif.', 2, '2026-04-13 18:00:00'),
(4, 'Andi Peminjam', 'andi@trustequip.test', 2, 'Akun saya nonaktif, mohon dicek kembali.', 1, '2026-04-15 09:20:00'),
(5, 'Eko Peminjam', 'eko@trustequip.test', 5, 'Sistemnya rapi dan mudah dipahami.', 2, '2026-04-16 12:10:00'),
(6, 'Guest Vendor', 'guest@vendor.test', 4, 'Petugas membantu saat kunjungan lapangan.', 2, '2026-04-13 17:30:00'),
(7, 'Rina', 'rina@example.test', 3, 'Tampilan bagus, tapi beberapa istilah masih membingungkan.', 1, '2026-04-14 10:00:00'),
(8, 'Yusuf', 'yusuf@example.test', 5, 'Proses approval sangat cepat.', 2, '2026-04-15 14:15:00'),
(9, 'Nadia', 'nadia@example.test', 1, 'Saya belum bisa mengakses semua fitur.', 3, '2026-04-16 08:40:00'),
(10, 'Farhan', 'farhan@example.test', 4, 'Secara umum aplikasi sudah bagus dan stabil.', 1, '2026-04-16 17:25:00');
