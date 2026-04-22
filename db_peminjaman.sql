-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Waktu pembuatan: 22 Apr 2026 pada 08.51
-- Versi server: 8.4.3
-- Versi PHP: 8.3.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Basis data: `db_peminjaman`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `activity_logs`
--

CREATE TABLE `activity_logs` (
  `id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED DEFAULT NULL,
  `aksi` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `entitas` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `entitas_id` int UNSIGNED DEFAULT NULL,
  `deskripsi` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ip_address` varbinary(16) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `activity_logs`
--

INSERT INTO `activity_logs` (`id`, `user_id`, `aksi`, `entitas`, `entitas_id`, `deskripsi`, `ip_address`, `created_at`) VALUES
(1, 1, 'CREATE', 'users', 3, 'Membuat akun staff: Raka Staff', 0xc0a8010a, '2026-04-01 02:10:10'),
(2, 1, 'CREATE', 'users', 4, 'Membuat akun staff: Dina Staff', 0xc0a8010a, '2026-04-01 02:12:10'),
(3, 1, 'CREATE', 'users', 5, 'Membuat akun staff: Sari Staff', 0xc0a8010a, '2026-04-01 02:14:10'),
(4, 1, 'CREATE', 'categories', 1, 'Menambah kategori Elektronik', 0xc0a8010a, '2026-04-01 03:00:10'),
(5, 3, 'CREATE', 'borrowings', 1, 'Menerima request PMJ-20260410-001', 0xc0a80114, '2026-04-10 02:00:30'),
(6, 4, 'UPDATE', 'borrowings', 2, 'Menyetujui transaksi PMJ-20260410-002', 0xc0a80115, '2026-04-10 03:15:30'),
(7, 4, 'UPDATE', 'borrowings', 4, 'Mencatat pengembalian PMJ-20260411-002', 0xc0a80115, '2026-04-13 09:30:30'),
(8, 5, 'UPDATE', 'tools', 8, 'Menandai laptop kantor untuk dipinjam', 0xc0a80116, '2026-04-14 03:20:00'),
(9, 3, 'UPDATE', 'borrowings', 9, 'Meninjau transaksi PMJ-20260415-001', 0xc0a80114, '2026-04-15 06:45:00'),
(10, 2, 'UPDATE', 'feedback_entries', 1, 'Menampilkan feedback Budi Peminjam', 0xc0a8011e, '2026-04-16 11:10:00');

-- --------------------------------------------------------

--
-- Struktur dari tabel `borrowings`
--

CREATE TABLE `borrowings` (
  `id` int UNSIGNED NOT NULL,
  `kode` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `peminjam_id` int UNSIGNED DEFAULT NULL,
  `nama_peminjam` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `divisi` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alat_id` int UNSIGNED DEFAULT NULL,
  `alat_harga_asli` int UNSIGNED NOT NULL DEFAULT '0',
  `nama_alat` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kategori` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `petugas_id` int UNSIGNED DEFAULT NULL,
  `petugas_nama` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `keperluan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tgl_pinjam` date NOT NULL,
  `tgl_kembali_rencana` date NOT NULL,
  `tgl_kembali_aktual` date DEFAULT NULL,
  `status_pengembalian` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kondisi_pengembalian` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `laporan_peminjam` text COLLATE utf8mb4_unicode_ci,
  `laporan_staff` text COLLATE utf8mb4_unicode_ci,
  `status` tinyint UNSIGNED NOT NULL DEFAULT '1' COMMENT '1=Pending,2=Disetujui,3=Ditolak,4=Dipinjam,5=Dikembalikan,6=Selesai',
  `biaya` int UNSIGNED NOT NULL DEFAULT '0',
  `denda_kerusakan` int UNSIGNED NOT NULL DEFAULT '0',
  `denda_kehilangan` int UNSIGNED NOT NULL DEFAULT '0',
  `denda_keterlambatan` int UNSIGNED NOT NULL DEFAULT '0',
  `catatan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gambar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bukti_pengambilan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bukti_pengembalian` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `borrowings`
--

INSERT INTO `borrowings` (`id`, `kode`, `peminjam_id`, `nama_peminjam`, `divisi`, `alat_id`, `alat_harga_asli`, `nama_alat`, `kategori`, `petugas_id`, `petugas_nama`, `keperluan`, `tgl_pinjam`, `tgl_kembali_rencana`, `tgl_kembali_aktual`, `status_pengembalian`, `kondisi_pengembalian`, `laporan_peminjam`, `laporan_staff`, `status`, `biaya`, `denda_kerusakan`, `denda_kehilangan`, `denda_keterlambatan`, `catatan`, `gambar`, `bukti_pengambilan`, `bukti_pengembalian`, `created_at`, `updated_at`) VALUES
(1, 'PMJ-20260410-001', 6, 'Budi Peminjam', 'Produksi', 1, 0, 'Multimeter Digital', 'Elektronik', 3, 'Raka Staff', 'Cek tegangan panel', '2026-04-10', '2026-04-12', NULL, NULL, NULL, NULL, NULL, 1, 0, 0, 0, 0, 'Menunggu persetujuan staff.', NULL, NULL, NULL, '2026-04-10 02:00:00', '2026-04-10 02:00:00'),
(2, 'PMJ-20260410-002', 7, 'Siti Peminjam', 'QA', 5, 0, 'Caliper Digital', 'Kalibrasi', 4, 'Dina Staff', 'Pengukuran sampel batch 42', '2026-04-10', '2026-04-11', NULL, NULL, NULL, NULL, NULL, 2, 0, 0, 0, 0, 'Disetujui, siap diambil.', NULL, NULL, NULL, '2026-04-10 03:00:00', '2026-04-10 03:15:00'),
(3, 'PMJ-20260411-001', 7, 'Siti Peminjam', 'QA', 5, 0, 'Caliper Digital', 'Kalibrasi', 4, 'Dina Staff', 'Pengukuran lanjutan', '2026-04-11', '2026-04-12', NULL, 'Belum Dikembalikan', NULL, NULL, 'Sedang dipinjam.', 4, 0, 0, 0, 0, 'Sedang dipinjam.', NULL, NULL, NULL, '2026-04-11 01:30:00', '2026-04-21 23:11:24'),
(4, 'PMJ-20260411-002', 9, 'Maya Peminjam', 'Maintenance', 3, 0, 'Bor Listrik', 'Mekanik', 3, 'Raka Staff', 'Perbaikan pompa', '2026-04-11', '2026-04-13', '2026-04-13', NULL, NULL, NULL, NULL, 5, 0, 0, 0, 0, 'Dikembalikan dan dicek ulang.', NULL, NULL, NULL, '2026-04-11 02:15:00', '2026-04-13 09:30:00'),
(5, 'PMJ-20260412-001', 10, 'Eko Peminjam', 'R&D', 9, 0, 'Proyektor HD', 'Presentasi', 5, 'Sari Staff', 'Demo proposal proyek', '2026-04-12', '2026-04-14', '2026-04-14', NULL, NULL, NULL, NULL, 6, 0, 0, 0, 0, 'Selesai tanpa kendala.', NULL, NULL, NULL, '2026-04-12 02:10:00', '2026-04-14 10:10:00'),
(6, 'PMJ-20260412-002', 8, 'Andi Peminjam', 'Produksi', 4, 0, 'Kunci Torsi 1/2', 'Mekanik', 3, 'Raka Staff', 'Kalibrasi baut mesin', '2026-04-12', '2026-04-13', NULL, NULL, NULL, NULL, NULL, 3, 0, 0, 0, 0, 'Ditolak karena alat sedang dibutuhkan tim lain.', NULL, NULL, NULL, '2026-04-12 04:20:00', '2026-04-12 04:45:00'),
(7, 'PMJ-20260413-001', NULL, 'Guest Vendor', 'Tamu', 6, 0, 'Helm Safety', 'Safety', 4, 'Dina Staff', 'Kunjungan area produksi', '2026-04-13', '2026-04-13', '2026-04-13', NULL, NULL, NULL, NULL, 6, 0, 0, 0, 0, 'Transaksi tamu selesai di hari yang sama.', NULL, NULL, NULL, '2026-04-13 01:00:00', '2026-04-13 10:00:00'),
(8, 'PMJ-20260414-001', 6, 'Budi Peminjam', 'Produksi', 8, 0, 'Laptop Kantor', 'Komputer', 5, 'Sari Staff', 'Presentasi internal tim', '2026-04-14', '2026-04-16', NULL, NULL, NULL, NULL, NULL, 2, 0, 0, 0, 0, 'Disetujui, menunggu serah terima.', NULL, NULL, NULL, '2026-04-14 03:00:00', '2026-04-14 03:20:00'),
(9, 'PMJ-20260415-001', 9, 'Maya Peminjam', 'QC', 7, 0, 'Speaker Portable', 'Audio', 3, 'Raka Staff', 'Briefing lapangan', '2026-04-15', '2026-04-17', '2026-04-22', 'Dikembalikan', 'Hilang', NULL, 'Sedang dipinjam.', 5, 0, 0, 0, 0, 'Sedang dipinjam.', NULL, NULL, NULL, '2026-04-15 06:30:00', '2026-04-21 23:23:39'),
(10, 'PMJ-20260416-001', 10, 'Eko Peminjam', 'IT', 10, 0, 'Router WiFi 6', 'Network', 4, 'Dina Staff', 'Uji koneksi ruangan meeting', '2026-04-16', '2026-04-18', NULL, NULL, NULL, NULL, NULL, 1, 0, 0, 0, 0, 'Menunggu verifikasi kelengkapan.', NULL, NULL, NULL, '2026-04-16 02:45:00', '2026-04-16 02:45:00'),
(11, 'PMJ-20260421-001', NULL, 'Budi Peminjam', 'Internal', NULL, 0, 'Proyektor HD', 'Presentasi', NULL, 'Belum Ditugaskan', 'untuk rapat hari ini', '2026-04-21', '2026-04-22', NULL, NULL, NULL, NULL, NULL, 2, 0, 0, 0, 0, 'Permintaan telah disetujui dan siap diserahkan.', NULL, NULL, NULL, '2026-04-21 05:29:59', '2026-04-21 05:34:43');

-- --------------------------------------------------------

--
-- Struktur dari tabel `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `categories`
--

CREATE TABLE `categories` (
  `id` int UNSIGNED NOT NULL,
  `nama_kategori` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kode_kategori` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint UNSIGNED NOT NULL DEFAULT '1' COMMENT '1=Aktif,2=Nonaktif',
  `deskripsi` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `gambar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `categories`
--

INSERT INTO `categories` (`id`, `nama_kategori`, `kode_kategori`, `status`, `deskripsi`, `gambar`, `created_at`, `updated_at`) VALUES
(1, 'Elektronik', 'ELK', 1, 'Peralatan elektronik untuk pengukuran dan troubleshooting.', NULL, '2026-04-22 01:26:34', '2026-04-22 01:26:34'),
(2, 'Mekanik', 'MEK', 1, 'Peralatan mekanik untuk perawatan dan perakitan.', NULL, '2026-04-22 01:26:34', '2026-04-22 01:26:34'),
(3, 'Kalibrasi', 'KLB', 1, 'Perangkat kalibrasi dan standar pengukuran.', NULL, '2026-04-22 01:26:34', '2026-04-22 01:26:34'),
(4, 'Safety', 'SFT', 1, 'Perlengkapan keselamatan kerja dan proteksi lapangan.', NULL, '2026-04-22 01:26:34', '2026-04-22 01:26:34'),
(5, 'Audio', 'AUD', 1, 'Perangkat audio untuk meeting dan presentasi.', NULL, '2026-04-22 01:26:34', '2026-04-22 01:26:34'),
(6, 'Komputer', 'KOM', 1, 'Peralatan komputer untuk administrasi dan produktivitas.', NULL, '2026-04-22 01:26:34', '2026-04-22 01:26:34'),
(7, 'Dokumentasi', 'DOC', 1, 'Peralatan dokumentasi dan pencetakan data.', NULL, '2026-04-22 01:26:34', '2026-04-22 01:26:34'),
(8, 'Presentasi', 'PRS', 1, 'Perangkat presentasi untuk briefing dan rapat.', NULL, '2026-04-22 01:26:34', '2026-04-22 01:26:34'),
(9, 'Network', 'NET', 1, 'Perangkat jaringan dan konektivitas.', NULL, '2026-04-22 01:26:34', '2026-04-22 01:26:34'),
(10, 'Perlengkapan Umum', 'GEN', 1, 'Perlengkapan umum untuk kebutuhan operasional harian.', NULL, '2026-04-22 01:26:34', '2026-04-22 01:26:34');

-- --------------------------------------------------------

--
-- Struktur dari tabel `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `feedback_entries`
--

CREATE TABLE `feedback_entries` (
  `id` int UNSIGNED NOT NULL,
  `nama` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `stars` tinyint UNSIGNED DEFAULT NULL,
  `pesan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint UNSIGNED NOT NULL DEFAULT '1' COMMENT '1=Pending,2=Ditampilkan,3=Ditolak',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `feedback_entries`
--

INSERT INTO `feedback_entries` (`id`, `nama`, `email`, `stars`, `pesan`, `status`, `created_at`) VALUES
(1, 'Budi Peminjam', 'budi@trustequip.test', 5, 'Proses peminjaman cepat dan jelas.', 2, '2026-04-12 05:00:00'),
(2, 'Siti Peminjam', 'siti@trustequip.test', 4, 'UI-nya enak dipakai, tapi mohon tambah filter alat.', 1, '2026-04-13 02:30:00'),
(3, 'Maya Peminjam', 'maya@trustequip.test', 5, 'Petugas sangat membantu dan komunikatif.', 2, '2026-04-13 11:00:00'),
(4, 'Andi Peminjam', 'andi@trustequip.test', 2, 'Akun saya nonaktif, mohon dicek kembali.', 1, '2026-04-15 02:20:00'),
(5, 'Eko Peminjam', 'eko@trustequip.test', 5, 'Sistemnya rapi dan mudah dipahami.', 2, '2026-04-16 05:10:00'),
(6, 'Guest Vendor', 'guest@vendor.test', 4, 'Petugas membantu saat kunjungan lapangan.', 2, '2026-04-13 10:30:00'),
(7, 'Rina', 'rina@example.test', 3, 'Tampilan bagus, tapi beberapa istilah masih membingungkan.', 1, '2026-04-14 03:00:00'),
(8, 'Yusuf', 'yusuf@example.test', 5, 'Proses approval sangat cepat.', 2, '2026-04-15 07:15:00'),
(9, 'Nadia', 'nadia@example.test', 1, 'Saya belum bisa mengakses semua fitur.', 3, '2026-04-16 01:40:00'),
(10, 'Farhan', 'farhan@example.test', 4, 'Secara umum aplikasi sudah bagus dan stabil.', 1, '2026-04-16 10:25:00'),
(11, 'Ilyas', 'ilyasardia@gmail.com', 5, 'alatnya beragam', 2, '2026-04-21 05:24:19');

-- --------------------------------------------------------

--
-- Struktur dari tabel `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_04_21_000001_create_categories_table', 1),
(5, '2026_04_21_000002_create_tools_table', 1),
(6, '2026_04_21_000003_create_borrowings_table', 1),
(7, '2026_04_21_000004_create_notifications_table', 1),
(8, '2026_04_21_000005_create_activity_logs_table', 1),
(9, '2026_04_21_000006_create_feedback_entries_table', 1),
(10, '2026_04_21_000007_add_gambar_to_users_table', 2),
(11, '2026_04_22_000008_add_deskripsi_to_tools_table', 3),
(12, '2026_04_22_000009_backfill_tools_deskripsi', 3),
(13, '2026_04_22_000010_backfill_missing_tools_deskripsi', 4),
(14, '2026_04_22_000011_add_profile_fields_to_users_table', 5),
(15, '2026_04_22_000012_add_pricing_and_return_fields', 6),
(16, '2026_04_22_000013_add_evidence_fields_to_borrowings_table', 7);

-- --------------------------------------------------------

--
-- Struktur dari tabel `notifications`
--

CREATE TABLE `notifications` (
  `id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `judul` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pesan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipe` tinyint UNSIGNED NOT NULL DEFAULT '1' COMMENT '1=Info,2=Success,3=Warning,4=Error',
  `is_read` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `judul`, `pesan`, `tipe`, `is_read`, `created_at`) VALUES
(1, 3, 'Permintaan Baru', 'Ada permintaan peminjaman baru: PMJ-20260410-001', 1, 0, '2026-04-10 02:01:00'),
(2, 4, 'Permintaan Disetujui', 'Transaksi PMJ-20260410-002 disetujui.', 2, 1, '2026-04-10 03:16:00'),
(3, 5, 'Peminjaman Aktif', 'Ada alat yang sedang dipinjam dan menunggu pengembalian.', 1, 0, '2026-04-11 01:50:00'),
(4, 6, 'Status Disetujui', 'Permintaan Anda telah disetujui, silakan ambil alat.', 2, 1, '2026-04-14 03:25:00'),
(5, 7, 'Perlu Tindakan', 'Permintaan Anda masih menunggu keputusan staff.', 3, 0, '2026-04-12 04:25:00'),
(6, 8, 'Akun Nonaktif', 'Akun Anda saat ini nonaktif. Hubungi admin.', 4, 0, '2026-04-15 02:10:00'),
(7, 9, 'Pengembalian Berhasil', 'Alat pada transaksi PMJ-20260411-002 telah dikembalikan.', 2, 1, '2026-04-13 09:31:00'),
(8, 10, 'Menunggu Verifikasi', 'Permintaan Anda sedang diverifikasi oleh staff.', 1, 0, '2026-04-16 02:50:00'),
(9, 1, 'Laporan Harian', 'Rekap aktivitas hari ini berhasil diperbarui.', 1, 1, '2026-04-16 11:00:00'),
(10, 2, 'Ringkasan Sistem', 'Dashboard sudah memuat data peminjaman terbaru.', 2, 1, '2026-04-16 11:05:00');

-- --------------------------------------------------------

--
-- Struktur dari tabel `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('Cg6T3RYHNaRSpcvLBZsknGMyGOGPy0dxms1Msj1H', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJGb01nbHlrOXVFVW5tc1BDSXc3RmhlbFhETG9BVG5nUTd1aUJwa3haIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwIiwicm91dGUiOm51bGx9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19', 1776824375),
('gsPStp2EciiePr6wApZzRAbviQaqqabjJhsjsJEa', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJmemlzdGQ3bHRLOXp0anVGdTU0Y3U1c3VxTHQ5dlVMWWlaZ0kzc3U0IiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9tYW5hZ2VtZW50LXBlbWluamFtYW4iLCJyb3V0ZSI6bnVsbH0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=', 1776846322);

-- --------------------------------------------------------

--
-- Struktur dari tabel `tools`
--

CREATE TABLE `tools` (
  `id` int UNSIGNED NOT NULL,
  `category_id` int UNSIGNED NOT NULL,
  `nama_alat` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `harga_asli` int UNSIGNED NOT NULL DEFAULT '0',
  `deskripsi` text COLLATE utf8mb4_unicode_ci,
  `stok` smallint UNSIGNED NOT NULL DEFAULT '0',
  `kondisi` tinyint UNSIGNED NOT NULL DEFAULT '1' COMMENT '1=Baik,2=PerluKalibrasi,3=RusakRingan,4=RusakBerat',
  `status` tinyint UNSIGNED NOT NULL DEFAULT '1' COMMENT '1=Tersedia,2=Dipinjam,3=Maintenance',
  `lokasi` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL,
  `gambar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `tools`
--

INSERT INTO `tools` (`id`, `category_id`, `nama_alat`, `harga_asli`, `deskripsi`, `stok`, `kondisi`, `status`, `lokasi`, `gambar`, `created_at`, `updated_at`) VALUES
(1, 1, 'Multimeter Digital', 350000, 'Alat ukur listrik untuk tegangan, arus, dan resistansi.', 10, 1, 1, 'Gudang Elektronik', '/uploads/tools/multimeter-digital.svg', '2026-04-22 01:26:34', '2026-04-22 01:26:34'),
(2, 1, 'Oscilloscope 100MHz', 12500000, 'Perangkat analisis sinyal untuk pengujian rangkaian elektronik.', 2, 2, 3, 'Lab Elektronik', '/uploads/tools/oscilloscope-100mhz.svg', '2026-04-22 01:26:34', '2026-04-22 01:26:34'),
(3, 2, 'Bor Listrik', 850000, 'Bor listrik serbaguna untuk pekerjaan perakitan dan perawatan.', 4, 1, 1, 'Workshop', '/uploads/tools/bor-listrik.svg', '2026-04-22 01:26:34', '2026-04-22 01:26:34'),
(4, 2, 'Kunci Torsi 1/2', 450000, 'Kunci torsi presisi untuk pengencangan baut sesuai spesifikasi.', 3, 2, 1, 'Workshop', '/uploads/tools/kunci-torsi-1-2.svg', '2026-04-22 01:26:34', '2026-04-22 01:26:34'),
(5, 3, 'Caliper Digital', 300000, 'Alat ukur digital untuk pengukuran dimensi yang presisi.', 5, 1, 1, 'Lab Kalibrasi', '/uploads/tools/caliper-digital.svg', '2026-04-22 01:26:34', '2026-04-22 01:26:34'),
(6, 4, 'Helm Safety', 175000, 'Helm pelindung untuk menunjang keselamatan kerja di lapangan.', 20, 1, 1, 'Gudang Safety', '/uploads/tools/helm-safety.svg', '2026-04-22 01:26:34', '2026-04-22 01:26:34'),
(7, 5, 'Speaker Portable', 900000, 'Speaker portabel untuk kebutuhan audio meeting dan presentasi.', 6, 1, 1, 'Gudang Audio', '/uploads/tools/speaker-portable.svg', '2026-04-22 01:26:34', '2026-04-22 01:26:34'),
(8, 6, 'Laptop Kantor', 9500000, 'Laptop kerja untuk administrasi, dokumentasi, dan presentasi.', 8, 1, 2, 'Ruang IT', '/uploads/tools/laptop-kantor.svg', '2026-04-22 01:26:34', '2026-04-22 01:26:34'),
(9, 8, 'Proyektor HD', 6500000, 'Proyektor untuk menampilkan materi rapat dan presentasi.', 3, 1, 1, 'Gudang Presentasi', '/uploads/tools/proyektor-hd.svg', '2026-04-22 01:26:34', '2026-04-22 01:26:34'),
(10, 9, 'Router WiFi 6', 1100000, 'Router jaringan untuk kebutuhan konektivitas ruang kerja.', 2, 3, 3, 'Ruang IT', '/uploads/tools/router-wifi-6.svg', '2026-04-22 01:26:34', '2026-04-22 01:26:34');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int UNSIGNED NOT NULL,
  `nama` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nik` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password_hash` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` tinyint UNSIGNED NOT NULL COMMENT '1=Admin,2=Owner,3=Staff,4=Peminjam',
  `status` tinyint UNSIGNED NOT NULL DEFAULT '1' COMMENT '1=Aktif,2=Nonaktif,3=Ditangguhkan',
  `telepon` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jenis_kelamin` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tempat_lahir` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `alamat` text COLLATE utf8mb4_unicode_ci,
  `gambar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `nama`, `nik`, `email`, `password_hash`, `role`, `status`, `telepon`, `jenis_kelamin`, `tempat_lahir`, `tanggal_lahir`, `alamat`, `gambar`, `created_at`, `updated_at`) VALUES
(1, 'Admin Utama', NULL, 'admin@trustequip.id', '$2y$12$B0kF3lu87vYRN6KCzy/2kuh024Eipct//GtMVRsj3s/k34EjC2rne', 1, 1, '0812-1111-0001', NULL, NULL, NULL, NULL, '/uploads/users/admin-utama.svg', '2026-04-22 01:26:34', '2026-04-22 01:26:34'),
(2, 'Owner Utama', NULL, 'owner@trustequip.id', '$2y$12$s.aGLOi7h3QWoN0oBeEIH.OvulqTaKyD9tK7Nl8v7DCZDtzrAT6f6', 2, 1, '0812-2222-0002', NULL, NULL, NULL, NULL, '/uploads/users/owner-utama.svg', '2026-04-22 01:26:34', '2026-04-22 01:26:34'),
(3, 'Staff Operasional', NULL, 'staff@trustequip.id', '$2y$12$Oamz4IK4gQoTsoWEuB0PRusIw6JwJjdd82ZWqgCYkgsnfclhAs3yu', 3, 1, '0812-3333-0003', NULL, NULL, NULL, NULL, '/uploads/users/staff-operasional.svg', '2026-04-22 01:26:34', '2026-04-22 01:26:34'),
(4, 'Budi Peminjam', NULL, 'budi@trustequip.id', '$2y$12$GJ5.OMi0MiJpOQc0pwurY.3O/D4KOsx.1eMXiyfdNKMvzpLLCRhiG', 4, 1, '0812-4444-0004', NULL, NULL, NULL, NULL, NULL, '2026-04-22 01:26:34', '2026-04-22 01:26:34'),
(5, 'Siti Peminjam', NULL, 'siti@trustequip.id', '$2y$12$BG4Jv/gK2mi0oPFqr1jQ3.HI9FM5SN2WcDM7qeVSrQ46A9HxESyfq', 4, 1, '0812-5555-0005', NULL, NULL, NULL, NULL, NULL, '2026-04-22 01:26:34', '2026-04-22 01:26:34'),
(6, 'Maya Peminjam', NULL, 'maya@trustequip.id', '$2y$12$KNIj1xBWOm0wO5mBmmWij.KUdXsJf19PVo/PG60cUyApwiJUg5jru', 4, 1, '0812-6666-0006', NULL, NULL, NULL, NULL, NULL, '2026-04-22 01:26:34', '2026-04-22 01:26:34'),
(7, 'Eko Peminjam', NULL, 'eko@trustequip.id', '$2y$12$471U2LP0lSbp2puDEvrKFOH04Iz0IrRt.yOwVVepc1mWrj9zZ3NyW', 4, 1, '0812-7777-0007', NULL, NULL, NULL, NULL, NULL, '2026-04-22 01:26:34', '2026-04-22 01:26:34'),
(8, 'Andi Peminjam', NULL, 'andi@trustequip.test', '$2y$10$XIc/YF3JrkpTnz5wp59e/OPRRWwNEJMzQCzNGmz3CHLy9MSuS0Up6', 4, 2, '0812-8888-8888', NULL, NULL, NULL, NULL, NULL, '2026-04-04 01:00:00', '2026-04-15 02:10:00'),
(9, 'Maya Peminjam', NULL, 'maya@trustequip.test', '$2y$10$XIc/YF3JrkpTnz5wp59e/OPRRWwNEJMzQCzNGmz3CHLy9MSuS0Up6', 4, 1, '0812-9999-9999', NULL, NULL, NULL, NULL, NULL, '2026-04-05 09:45:00', '2026-04-05 09:45:00'),
(10, 'Eko Peminjam', NULL, 'eko@trustequip.test', '$2y$10$XIc/YF3JrkpTnz5wp59e/OPRRWwNEJMzQCzNGmz3CHLy9MSuS0Up6', 4, 1, '0812-1010-1010', NULL, NULL, NULL, NULL, NULL, '2026-04-06 03:15:00', '2026-04-06 03:15:00');

--
-- Indeks untuk tabel yang dibuang
--

--
-- Indeks untuk tabel `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_logs_user_time` (`user_id`,`created_at`),
  ADD KEY `idx_logs_entity` (`entitas`,`entitas_id`);

--
-- Indeks untuk tabel `borrowings`
--
ALTER TABLE `borrowings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_borrowings_kode` (`kode`),
  ADD KEY `idx_borrowings_status_date` (`status`,`tgl_pinjam`),
  ADD KEY `idx_borrowings_peminjam` (`peminjam_id`),
  ADD KEY `idx_borrowings_alat` (`alat_id`),
  ADD KEY `idx_borrowings_petugas` (`petugas_id`),
  ADD KEY `idx_borrowings_search` (`nama_peminjam`,`nama_alat`,`kategori`,`petugas_nama`),
  ADD KEY `idx_borrowings_return_status` (`status_pengembalian`,`tgl_kembali_rencana`);

--
-- Indeks untuk tabel `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indeks untuk tabel `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indeks untuk tabel `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_categories_code` (`kode_kategori`),
  ADD KEY `idx_categories_status` (`status`),
  ADD KEY `idx_categories_name` (`nama_kategori`);

--
-- Indeks untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indeks untuk tabel `feedback_entries`
--
ALTER TABLE `feedback_entries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_feedback_status` (`status`,`created_at`);

--
-- Indeks untuk tabel `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indeks untuk tabel `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_notifications_user_read` (`user_id`,`is_read`,`created_at`);

--
-- Indeks untuk tabel `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indeks untuk tabel `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indeks untuk tabel `tools`
--
ALTER TABLE `tools`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_tools_category` (`category_id`),
  ADD KEY `idx_tools_status_kondisi` (`status`,`kondisi`),
  ADD KEY `idx_tools_name` (`nama_alat`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_users_email` (`email`),
  ADD KEY `idx_users_role_status` (`role`,`status`),
  ADD KEY `idx_users_name` (`nama`),
  ADD KEY `idx_users_nik` (`nik`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `borrowings`
--
ALTER TABLE `borrowings`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT untuk tabel `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `feedback_entries`
--
ALTER TABLE `feedback_entries`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT untuk tabel `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT untuk tabel `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `tools`
--
ALTER TABLE `tools`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD CONSTRAINT `fk_logs_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `borrowings`
--
ALTER TABLE `borrowings`
  ADD CONSTRAINT `fk_borrowings_alat` FOREIGN KEY (`alat_id`) REFERENCES `tools` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_borrowings_peminjam` FOREIGN KEY (`peminjam_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_borrowings_petugas` FOREIGN KEY (`petugas_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `fk_notifications_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `tools`
--
ALTER TABLE `tools`
  ADD CONSTRAINT `fk_tools_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
