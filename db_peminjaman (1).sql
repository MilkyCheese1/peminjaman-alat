-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Waktu pembuatan: 07 Apr 2026 pada 10.34
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
(1, 3, 1, 1, '2026-04-07 00:00:00', '2026-04-09 00:00:00', NULL, 'applied', NULL, NULL, NULL, NULL, 0.00, 0, 'dwad', '2026-04-07 02:23:43', '2026-04-07 02:23:43', '58304419', 'wda', 48);

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
(8, 4, 'Portable Sound System JBL', 'Speaker portabel Bluetooth dengan bass boost, battery 12 jam', 9, 'good', NULL, 1, '2026-04-06 23:50:38', '2026-04-06 23:50:38', 50000.00);

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
(9, '2026_04_06_000002_add_verification_code_to_borrowings', 1);

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
(1, 'admin', 'Admin TrustEquip', 'admin@trustequip.id', '083456789012', '$2y$12$Z5fLnurbqnPCEex6fgjWUOZE0OEcXjHwzMGMZfn/zJyfkOvhbpBVG', 'admin', 'Jl. Admin No. 1', NULL, NULL, NULL, NULL, 0, 1, '2026-04-06 23:50:37', '2026-04-07 00:56:57'),
(2, 'staff', 'Staff TrustEquip', 'staff@trustequip.id', '085678901234', '$2y$12$rlrid9meXBBBd/gNkmlsx.OG3/kKD1Ijm4ZMo5m0ZwN0.mK9H6rYS', 'staff', 'Jl. Kerja No. 1', NULL, NULL, NULL, NULL, 0, 1, '2026-04-06 23:50:38', '2026-04-07 00:54:02'),
(3, 'customer', 'Customer TrustEquip', 'customer@trustequip.id', '088901234567', '$2y$12$bZ2rGMlQvmbIYfik153jKuuiIWE32rrMXktpd8HYFnfgOen3t4R9K', 'customer', 'Jl. Pelajar No. 1', NULL, NULL, NULL, NULL, 0, 1, '2026-04-06 23:50:38', '2026-04-07 00:54:02'),
(4, 'owner', 'Owner TrustEquip', 'owner@trustequip.id', '081234567890', '$2y$12$tyPRbvMSNjX6FGEAtMFYNOOimk28q8UsgPek56Q5X7n/UX8NLJBaa', 'owner', 'Jl. Pendidikan No. 1', NULL, NULL, NULL, NULL, 0, 1, '2026-04-06 23:50:38', '2026-04-07 00:54:03');

--
-- Indeks untuk tabel yang dibuang
--

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
-- AUTO_INCREMENT untuk tabel `borrowings`
--
ALTER TABLE `borrowings`
  MODIFY `id_borrowing` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `categories`
--
ALTER TABLE `categories`
  MODIFY `id_category` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `equipment`
--
ALTER TABLE `equipment`
  MODIFY `id_equipment` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT untuk tabel `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id_user` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `borrowings`
--
ALTER TABLE `borrowings`
  ADD CONSTRAINT `borrowings_id_equipment_foreign` FOREIGN KEY (`id_equipment`) REFERENCES `equipment` (`id_equipment`) ON DELETE CASCADE,
  ADD CONSTRAINT `borrowings_id_user_foreign` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `equipment`
--
ALTER TABLE `equipment`
  ADD CONSTRAINT `equipment_id_category_foreign` FOREIGN KEY (`id_category`) REFERENCES `categories` (`id_category`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
