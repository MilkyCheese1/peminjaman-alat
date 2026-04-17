-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Waktu pembuatan: 17 Apr 2026 pada 12.21
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
  `id_activity` bigint UNSIGNED NOT NULL,
  `id_user` bigint UNSIGNED DEFAULT NULL,
  `action` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `model_type` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `model_id` bigint UNSIGNED DEFAULT NULL,
  `old_values` json DEFAULT NULL,
  `new_values` json DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `borrowings`
--

CREATE TABLE `borrowings` (
  `id_borrowing` bigint UNSIGNED NOT NULL,
  `id_user` bigint UNSIGNED NOT NULL,
  `id_equipment` bigint UNSIGNED NOT NULL,
  `quantity` int NOT NULL DEFAULT '1',
  `borrow_date` datetime NOT NULL,
  `planned_return_date` datetime NOT NULL,
  `actual_return_date` datetime DEFAULT NULL,
  `status` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'applied',
  `pickup_code` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pickup_code_generated_at` datetime DEFAULT NULL,
  `pickup_verified_at` datetime DEFAULT NULL,
  `pickup_photo_before` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fine_amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `fine_paid` tinyint(1) NOT NULL DEFAULT '0',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `kode_verifikasi` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `keperluan` text COLLATE utf8mb4_unicode_ci,
  `durasi_jam` int NOT NULL DEFAULT '24'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `borrowings`
--

INSERT INTO `borrowings` (`id_borrowing`, `id_user`, `id_equipment`, `quantity`, `borrow_date`, `planned_return_date`, `actual_return_date`, `status`, `pickup_code`, `pickup_code_generated_at`, `pickup_verified_at`, `pickup_photo_before`, `fine_amount`, `fine_paid`, `notes`, `created_at`, `updated_at`, `kode_verifikasi`, `keperluan`, `durasi_jam`) VALUES
(14, 3, 1, 1, '2026-04-09 08:00:00', '2026-04-12 17:00:00', NULL, 'picked_up', NULL, NULL, NULL, NULL, 0.00, 0, NULL, '2026-04-09 09:04:51', '2026-04-09 09:04:51', '22414694', NULL, 24),
(15, 3, 2, 1, '2026-03-30 08:00:00', '2026-04-08 17:00:00', NULL, 'picked_up', NULL, NULL, NULL, NULL, 15000.00, 0, NULL, '2026-04-09 09:04:51', '2026-04-09 09:04:51', '80136631', NULL, 72);

-- --------------------------------------------------------

--
-- Struktur dari tabel `borrowing_returns`
--

CREATE TABLE `borrowing_returns` (
  `id_return` bigint UNSIGNED NOT NULL,
  `id_borrowing` bigint UNSIGNED NOT NULL,
  `return_date` datetime NOT NULL,
  `condition` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `condition_notes` text COLLATE utf8mb4_unicode_ci,
  `damage_notes` text COLLATE utf8mb4_unicode_ci,
  `photo_after` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fine_amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `fine_paid` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `categories`
--

CREATE TABLE `categories` (
  `id_category` bigint UNSIGNED NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `categories`
--

INSERT INTO `categories` (`id_category`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Elektronik', 'Peralatan elektronik seperti laptop, proyektor, dan kamera', '2026-04-06 23:50:38', '2026-04-06 23:50:38'),
(2, 'Audio Visual', 'Peralatan audiovisual untuk presentasi dan recording', '2026-04-06 23:50:38', '2026-04-06 23:50:38'),
(3, 'Peralatan Kantor', 'Peralatan umum untuk kantor', '2026-04-06 23:50:38', '2026-04-06 23:50:38'),
(4, 'Perlengkapan Event', 'Peralatan untuk event dan konferensi', '2026-04-06 23:50:38', '2026-04-06 23:50:38');

-- --------------------------------------------------------

--
-- Struktur dari tabel `equipment`
--

CREATE TABLE `equipment` (
  `id_equipment` bigint UNSIGNED NOT NULL,
  `id_category` bigint UNSIGNED DEFAULT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `quantity` int NOT NULL DEFAULT '0',
  `condition` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'good',
  `photo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_available` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `fine_per_day` decimal(12,2) DEFAULT '50000.00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `equipment`
--

INSERT INTO `equipment` (`id_equipment`, `id_category`, `name`, `description`, `quantity`, `condition`, `photo`, `is_available`, `created_at`, `updated_at`, `fine_per_day`) VALUES
(1, 1, 'Laptop Dell XPS 15', 'Laptop gaming high-performance Intel i7, RAM 16GB, SSD 512GB', 5, 'good', 'equipment/La31SLuDxQpTLuynzb02PMdd9qgPRB9Gc42tg2WE.png', 1, '2026-04-06 23:50:38', '2026-04-07 01:48:48', 50000.00),
(2, 1, 'Kamera DSLR Canon 5D Mark IV', 'Kamera profesional dengan resolution 30MP, video 4K', 3, 'good', NULL, 1, '2026-04-06 23:50:38', '2026-04-06 23:50:38', 50000.00),
(3, 2, 'Proyektor 4K Epson', 'Proyektor HD 4K untuk presentasi profesional, brightness 3500 lumens', 4, 'good', NULL, 1, '2026-04-06 23:50:38', '2026-04-06 23:50:38', 50000.00),
(4, 2, 'Microphone Studio Rode NT1', 'Microphone studio condenser dengan pop filter dan shock mount', 6, 'good', NULL, 1, '2026-04-06 23:50:38', '2026-04-06 23:50:38', 50000.00),
(5, 3, 'Monitor 4K LG 27\"', 'Monitor ultra HD IPS panel, 99% sRGB color accuracy', 8, 'good', NULL, 1, '2026-04-06 23:50:38', '2026-04-06 23:50:38', 50000.00),
(6, 3, 'Meja Kerja Ergonomis', 'Meja dapat diatur ketinggian untuk standing desk, kapasitas 50kg', 10, 'good', NULL, 1, '2026-04-06 23:50:38', '2026-04-06 23:50:38', 50000.00),
(7, 4, 'Ring Light Professional', 'Ring light LED 18\" untuk fotografi dan streaming, dengan phone holder', 7, 'good', NULL, 1, '2026-04-06 23:50:38', '2026-04-06 23:50:38', 50000.00),
(8, 4, 'Portable Sound System JBL', 'Speaker portabel Bluetooth dengan bass boost, battery 12 jam', 9, 'good', NULL, 1, '2026-04-06 23:50:38', '2026-04-06 23:50:38', 50000.00),
(9, 1, 'Laptop Dell XPS 15', 'Laptop gaming high-performance Intel i7, RAM 16GB, SSD 512GB', 5, 'good', NULL, 1, '2026-04-15 11:10:46', '2026-04-15 11:10:46', 50000.00),
(10, 1, 'Kamera DSLR Canon 5D Mark IV', 'Kamera profesional dengan resolution 30MP, video 4K', 3, 'good', NULL, 1, '2026-04-15 11:10:46', '2026-04-15 11:10:46', 50000.00),
(11, 2, 'Proyektor 4K Epson', 'Proyektor HD 4K untuk presentasi profesional, brightness 3500 lumens', 4, 'good', NULL, 1, '2026-04-15 11:10:46', '2026-04-15 11:10:46', 50000.00),
(12, 2, 'Microphone Studio Rode NT1', 'Microphone studio condenser dengan pop filter dan shock mount', 6, 'good', NULL, 1, '2026-04-15 11:10:46', '2026-04-15 11:10:46', 50000.00),
(13, 3, 'Monitor 4K LG 27\"', 'Monitor ultra HD IPS panel, 99% sRGB color accuracy', 8, 'good', NULL, 1, '2026-04-15 11:10:46', '2026-04-15 11:10:46', 50000.00),
(14, 3, 'Meja Kerja Ergonomis', 'Meja dapat diatur ketinggian untuk standing desk, kapasitas 50kg', 10, 'good', NULL, 1, '2026-04-15 11:10:46', '2026-04-15 11:10:46', 50000.00),
(15, 4, 'Ring Light Professional', 'Ring light LED 18\" untuk fotografi dan streaming, dengan phone holder', 7, 'good', NULL, 1, '2026-04-15 11:10:46', '2026-04-15 11:10:46', 50000.00),
(16, 4, 'Portable Sound System JBL', 'Speaker portabel Bluetooth dengan bass boost, battery 12 jam', 9, 'good', NULL, 1, '2026-04-15 11:10:46', '2026-04-15 11:10:46', 50000.00);

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
(1, '2026_04_01_000000_create_users_table', 1),
(2, '2026_04_05_000001_add_role_to_users_table', 1),
(3, '2026_04_05_000002_create_roles_table', 1),
(4, '2026_04_05_111800_create_categories_table', 1),
(5, '2026_04_05_111840_create_equipment_table', 1),
(6, '2026_04_05_111841_create_borrowings_table', 1),
(7, '2026_04_06_000000_remove_price_per_day_from_equipment', 1),
(8, '2026_04_06_000001_add_fine_per_day_to_equipment', 1),
(9, '2026_04_06_000002_add_verification_code_to_borrowings', 1),
(10, '2026_04_07_create_borrowing_returns_table', 2),
(11, '2019_12_14_000001_create_personal_access_tokens_table', 3),
(13, '2026_04_10_000010_create_notifications_table_new', 4),
(14, '2026_04_17_000001_create_activity_logs_table', 5),
(15, '2026_04_17_000002_create_borrowing_returns_table', 6),
(16, '2026_04_17_000003_create_notification_logs_table', 7);

-- --------------------------------------------------------

--
-- Struktur dari tabel `notifications`
--

CREATE TABLE `notifications` (
  `id_notification` bigint UNSIGNED NOT NULL,
  `id_user` bigint UNSIGNED NOT NULL,
  `id_borrowing` bigint UNSIGNED DEFAULT NULL,
  `category` enum('approval','return','reminder','system') COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('borrowing_created','borrowing_approved','borrowing_rejected','return_ready','return_submitted','return_verified','return_overdue','return_not_verified','return_reminder_1day','return_reminder_due','return_reminder_overdue','system_announcement','equipment_unavailable','custom') COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `icon` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 0xF09F93AC,
  `color` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'info',
  `action_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `action_label` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT '0',
  `read_at` timestamp NULL DEFAULT NULL,
  `is_archived` tinyint(1) NOT NULL DEFAULT '0',
  `archived_at` timestamp NULL DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `channels` json DEFAULT NULL,
  `channel_status` json DEFAULT NULL,
  `email_address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `email_error` text COLLATE utf8mb4_unicode_ci,
  `phone_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sms_status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `sms_error` text COLLATE utf8mb4_unicode_ci,
  `push_status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `push_error` text COLLATE utf8mb4_unicode_ci,
  `metadata` json DEFAULT NULL,
  `recipient_details` json DEFAULT NULL,
  `priority` enum('low','normal','high','urgent') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'normal',
  `expires_at` timestamp NULL DEFAULT NULL,
  `retention_days` int NOT NULL DEFAULT '30',
  `notification_group` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `parent_notification_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `notifications`
--

INSERT INTO `notifications` (`id_notification`, `id_user`, `id_borrowing`, `category`, `type`, `title`, `message`, `icon`, `color`, `action_url`, `action_label`, `is_read`, `read_at`, `is_archived`, `archived_at`, `is_deleted`, `deleted_at`, `channels`, `channel_status`, `email_address`, `email_status`, `email_error`, `phone_number`, `sms_status`, `sms_error`, `push_status`, `push_error`, `metadata`, `recipient_details`, `priority`, `expires_at`, `retention_days`, `notification_group`, `parent_notification_id`, `created_at`, `updated_at`) VALUES
(1, 8, NULL, 'approval', 'borrowing_created', '📝 Test Notification Created', 'This is a test notification to verify system is working', '📝', 'info', NULL, NULL, 1, '2026-04-10 01:41:38', 1, '2026-04-10 01:41:39', 0, NULL, '[\"in_app\"]', NULL, NULL, 'pending', NULL, NULL, 'pending', NULL, 'pending', NULL, NULL, NULL, 'normal', '2026-05-10 01:41:38', 30, NULL, NULL, '2026-04-10 01:41:38', '2026-04-10 01:41:39'),
(2, 8, NULL, 'reminder', 'return_reminder_1day', '⏰ Return Reminder Test', 'Test reminder notification from service', '⏰', 'warning', NULL, NULL, 0, NULL, 0, NULL, 0, NULL, '[\"in_app\"]', NULL, NULL, 'pending', NULL, NULL, 'pending', NULL, 'pending', NULL, NULL, NULL, 'high', '2026-05-10 01:41:38', 30, NULL, NULL, '2026-04-10 01:41:38', '2026-04-10 01:41:38');

-- --------------------------------------------------------

--
-- Struktur dari tabel `notification_logs`
--

CREATE TABLE `notification_logs` (
  `id_log` bigint UNSIGNED NOT NULL,
  `id_notification` bigint UNSIGNED NOT NULL,
  `id_user` bigint UNSIGNED NOT NULL,
  `action` enum('created','read','unread','archived','unarchived','deleted','restored','email_sent','email_failed','sms_sent','sms_failed','push_sent','push_failed') COLLATE utf8mb4_unicode_ci NOT NULL,
  `details` text COLLATE utf8mb4_unicode_ci,
  `ip_address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `notification_logs`
--

INSERT INTO `notification_logs` (`id_log`, `id_notification`, `id_user`, `action`, `details`, `ip_address`, `user_agent`, `created_at`, `updated_at`) VALUES
(1, 2, 8, 'created', '\"{\\\"channels\\\":[\\\"in_app\\\"]}\"', '127.0.0.1', 'Symfony', '2026-04-10 01:41:38', '2026-04-10 01:41:38'),
(2, 1, 8, 'read', NULL, '127.0.0.1', 'Symfony', '2026-04-10 01:41:39', '2026-04-10 01:41:39'),
(3, 1, 8, 'archived', NULL, '127.0.0.1', 'Symfony', '2026-04-10 01:41:39', '2026-04-10 01:41:39');

-- --------------------------------------------------------

--
-- Struktur dari tabel `notification_preferences`
--

CREATE TABLE `notification_preferences` (
  `id_preference` bigint UNSIGNED NOT NULL,
  `id_user` bigint UNSIGNED NOT NULL,
  `in_app_enabled` tinyint(1) NOT NULL DEFAULT '1',
  `email_enabled` tinyint(1) NOT NULL DEFAULT '1',
  `sms_enabled` tinyint(1) NOT NULL DEFAULT '0',
  `push_enabled` tinyint(1) NOT NULL DEFAULT '0',
  `approval_notifications` tinyint(1) NOT NULL DEFAULT '1',
  `return_notifications` tinyint(1) NOT NULL DEFAULT '1',
  `reminder_notifications` tinyint(1) NOT NULL DEFAULT '1',
  `system_announcements` tinyint(1) NOT NULL DEFAULT '1',
  `email_address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_digest` tinyint(1) NOT NULL DEFAULT '0',
  `phone_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sms_urgent_only` tinyint(1) NOT NULL DEFAULT '1',
  `quiet_hours_enabled` tinyint(1) NOT NULL DEFAULT '0',
  `quiet_hours_start` time DEFAULT NULL,
  `quiet_hours_end` time DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `notification_preferences`
--

INSERT INTO `notification_preferences` (`id_preference`, `id_user`, `in_app_enabled`, `email_enabled`, `sms_enabled`, `push_enabled`, `approval_notifications`, `return_notifications`, `reminder_notifications`, `system_announcements`, `email_address`, `email_digest`, `phone_number`, `sms_urgent_only`, `quiet_hours_enabled`, `quiet_hours_start`, `quiet_hours_end`, `created_at`, `updated_at`) VALUES
(1, 8, 1, 1, 0, 0, 1, 1, 1, 1, NULL, 0, NULL, 1, 0, NULL, NULL, '2026-04-10 01:41:39', '2026-04-10 01:41:39');

-- --------------------------------------------------------

--
-- Struktur dari tabel `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `roles`
--

CREATE TABLE `roles` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `roles`
--

INSERT INTO `roles` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'admin', NULL, NULL),
(2, 'staff', NULL, NULL),
(3, 'customer', NULL, NULL),
(4, 'owner', NULL, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id_user` bigint UNSIGNED NOT NULL,
  `username` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_lengkap` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'customer',
  `alamat` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kota` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `provinsi` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kode_pos` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `foto` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified` tinyint(1) NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id_user`, `username`, `nama_lengkap`, `email`, `phone`, `password`, `role`, `alamat`, `kota`, `provinsi`, `kode_pos`, `foto`, `email_verified`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'Admin TrustEquip', 'admin@trustequip.id', '083456789012', '$2y$12$PGDt8G3gXLNNpQwWkU8imO52qa515HOeZ/z4nqUxtNUS4D5nwgYF6', 'admin', 'Jl. Admin No. 1', NULL, NULL, NULL, NULL, 0, 1, '2026-04-06 23:50:37', '2026-04-15 11:10:44'),
(2, 'staff', 'Staff TrustEquip', 'staff@trustequip.id', '085678901234', '$2y$12$itieGTWdEuvqHfIK5mo7juLOL15eJccSV2OR0dhkXypGgkW5tAO3S', 'staff', 'Jl. Kerja No. 1', NULL, NULL, NULL, NULL, 0, 1, '2026-04-06 23:50:38', '2026-04-15 11:10:45'),
(3, 'customer', 'Customer TrustEquip', 'customer@trustequip.id', '088901234567', '$2y$12$rVHU/5L1azFqbjGTcCiHlOykMB.neg3S3zf2HrEjXJ04.Ug./twrW', 'customer', 'Jl. Pelajar No. 1', NULL, NULL, NULL, NULL, 0, 1, '2026-04-06 23:50:38', '2026-04-15 11:10:45'),
(4, 'owner', 'Owner TrustEquip', 'owner@trustequip.id', '081234567890', '$2y$12$kPi9eWBwwXFdn1itg0LNhOEAl2ZRHih2JKbwxusVCdTG0MtTzZmhS', 'owner', 'Jl. Pendidikan No. 1', NULL, NULL, NULL, NULL, 0, 1, '2026-04-06 23:50:38', '2026-04-15 11:10:45'),
(5, 'testuser', 'Test User', 'test@example.com', NULL, '$2y$12$wfNNRx6L4EG1nafXnNKDueMaBj.BZoVKD8rWBnSOn4Oq6FdJgTGVK', 'customer', NULL, NULL, NULL, NULL, NULL, 0, 1, '2026-04-10 01:39:18', '2026-04-10 01:39:18'),
(7, 'testuser2486', 'Test User', 'test1775810402@example.com', NULL, '$2y$12$8GQPSN94NTxsrQGMOq3Bb./PQ1IqTSEFEUhqcSo3ungVRNdaGofZG', 'customer', NULL, NULL, NULL, NULL, NULL, 0, 1, '2026-04-10 01:40:02', '2026-04-10 01:40:02'),
(8, 'testuser7585', 'Test User', 'test1775810498@example.com', NULL, '$2y$12$viLKnQJ08nHhbtCSix9zAOkAYNaeAB9gqUSCJJhuq9uBNvcHvK0Hm', 'customer', NULL, NULL, NULL, NULL, NULL, 0, 1, '2026-04-10 01:41:38', '2026-04-10 01:41:38'),
(9, '1_1775991319', '1', '1@da.id', '08567890123', '$2y$12$liIBQ8Z2MBsAihReF5s9/uYigYB3wyBzHN9biINcNyqnBEHjPovaS', 'customer', NULL, NULL, NULL, NULL, NULL, 0, 1, '2026-04-12 03:55:19', '2026-04-12 03:55:19');

--
-- Indeks untuk tabel yang dibuang
--

--
-- Indeks untuk tabel `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`id_activity`),
  ADD KEY `activity_logs_id_user_index` (`id_user`),
  ADD KEY `activity_logs_action_index` (`action`),
  ADD KEY `activity_logs_model_type_index` (`model_type`),
  ADD KEY `activity_logs_created_at_index` (`created_at`);

--
-- Indeks untuk tabel `borrowings`
--
ALTER TABLE `borrowings`
  ADD PRIMARY KEY (`id_borrowing`),
  ADD UNIQUE KEY `borrowings_pickup_code_unique` (`pickup_code`),
  ADD UNIQUE KEY `borrowings_kode_verifikasi_unique` (`kode_verifikasi`),
  ADD KEY `borrowings_id_user_foreign` (`id_user`),
  ADD KEY `borrowings_id_equipment_foreign` (`id_equipment`);

--
-- Indeks untuk tabel `borrowing_returns`
--
ALTER TABLE `borrowing_returns`
  ADD PRIMARY KEY (`id_return`),
  ADD KEY `borrowing_returns_id_borrowing_foreign` (`id_borrowing`);

--
-- Indeks untuk tabel `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id_category`),
  ADD UNIQUE KEY `categories_name_unique` (`name`);

--
-- Indeks untuk tabel `equipment`
--
ALTER TABLE `equipment`
  ADD PRIMARY KEY (`id_equipment`),
  ADD KEY `equipment_id_category_foreign` (`id_category`);

--
-- Indeks untuk tabel `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id_notification`),
  ADD KEY `notifications_id_borrowing_foreign` (`id_borrowing`),
  ADD KEY `notifications_id_user_is_read_index` (`id_user`,`is_read`),
  ADD KEY `notifications_id_user_is_archived_index` (`id_user`,`is_archived`),
  ADD KEY `notifications_id_user_created_at_index` (`id_user`,`created_at`),
  ADD KEY `notifications_id_user_is_deleted_index` (`id_user`,`is_deleted`),
  ADD KEY `notifications_category_created_at_index` (`category`,`created_at`),
  ADD KEY `notifications_type_created_at_index` (`type`,`created_at`),
  ADD KEY `notifications_email_status_index` (`email_status`),
  ADD KEY `notifications_sms_status_index` (`sms_status`),
  ADD KEY `notifications_expires_at_index` (`expires_at`),
  ADD KEY `notifications_notification_group_index` (`notification_group`);

--
-- Indeks untuk tabel `notification_logs`
--
ALTER TABLE `notification_logs`
  ADD PRIMARY KEY (`id_log`),
  ADD KEY `notification_logs_id_notification_index` (`id_notification`),
  ADD KEY `notification_logs_id_user_index` (`id_user`),
  ADD KEY `notification_logs_action_index` (`action`),
  ADD KEY `notification_logs_created_at_index` (`created_at`);

--
-- Indeks untuk tabel `notification_preferences`
--
ALTER TABLE `notification_preferences`
  ADD PRIMARY KEY (`id_preference`),
  ADD UNIQUE KEY `notification_preferences_id_user_unique` (`id_user`);

--
-- Indeks untuk tabel `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indeks untuk tabel `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_unique` (`name`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `users_username_unique` (`username`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `id_activity` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `borrowings`
--
ALTER TABLE `borrowings`
  MODIFY `id_borrowing` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT untuk tabel `borrowing_returns`
--
ALTER TABLE `borrowing_returns`
  MODIFY `id_return` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT untuk tabel `categories`
--
ALTER TABLE `categories`
  MODIFY `id_category` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `equipment`
--
ALTER TABLE `equipment`
  MODIFY `id_equipment` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT untuk tabel `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT untuk tabel `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id_notification` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `notification_logs`
--
ALTER TABLE `notification_logs`
  MODIFY `id_log` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `notification_preferences`
--
ALTER TABLE `notification_preferences`
  MODIFY `id_preference` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id_user` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD CONSTRAINT `activity_logs_id_user_foreign` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE SET NULL;

--
-- Ketidakleluasaan untuk tabel `borrowings`
--
ALTER TABLE `borrowings`
  ADD CONSTRAINT `borrowings_id_equipment_foreign` FOREIGN KEY (`id_equipment`) REFERENCES `equipment` (`id_equipment`) ON DELETE CASCADE,
  ADD CONSTRAINT `borrowings_id_user_foreign` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `borrowing_returns`
--
ALTER TABLE `borrowing_returns`
  ADD CONSTRAINT `borrowing_returns_id_borrowing_foreign` FOREIGN KEY (`id_borrowing`) REFERENCES `borrowings` (`id_borrowing`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `equipment`
--
ALTER TABLE `equipment`
  ADD CONSTRAINT `equipment_id_category_foreign` FOREIGN KEY (`id_category`) REFERENCES `categories` (`id_category`) ON DELETE SET NULL;

--
-- Ketidakleluasaan untuk tabel `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_id_borrowing_foreign` FOREIGN KEY (`id_borrowing`) REFERENCES `borrowings` (`id_borrowing`) ON DELETE SET NULL,
  ADD CONSTRAINT `notifications_id_user_foreign` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `notification_logs`
--
ALTER TABLE `notification_logs`
  ADD CONSTRAINT `notification_logs_id_notification_foreign` FOREIGN KEY (`id_notification`) REFERENCES `notifications` (`id_notification`) ON DELETE CASCADE,
  ADD CONSTRAINT `notification_logs_id_user_foreign` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `notification_preferences`
--
ALTER TABLE `notification_preferences`
  ADD CONSTRAINT `notification_preferences_id_user_foreign` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
