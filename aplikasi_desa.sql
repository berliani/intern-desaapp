-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Aug 12, 2025 at 05:36 AM
-- Server version: 10.4.32-MariaDB-log
-- PHP Version: 8.4.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `aplikasi_desa`
--

-- --------------------------------------------------------

--
-- Table structure for table `aparat_desa`
--

CREATE TABLE `aparat_desa` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `struktur_pemerintahan_id` bigint(20) UNSIGNED NOT NULL,
  `nama` varchar(255) NOT NULL,
  `jabatan` varchar(255) NOT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `pendidikan` varchar(255) DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `alamat` varchar(255) DEFAULT NULL,
  `kontak` varchar(255) DEFAULT NULL,
  `periode_jabatan` varchar(255) DEFAULT NULL,
  `urutan` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `aparat_desa`
--

INSERT INTO `aparat_desa` (`id`, `struktur_pemerintahan_id`, `nama`, `jabatan`, `foto`, `pendidikan`, `tanggal_lahir`, `alamat`, `kontak`, `periode_jabatan`, `urutan`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 'Sugiyanto, S.Pd', 'Sekretaris Desa', 'uploads/desa/aparat/default-5.jpg', 'S1', '1975-06-15', 'Dusun Tengah RT 03/02, Desa Sariharjo', '085712345678', '2020-2026', 2, '2025-08-12 02:53:15', '2025-08-12 02:53:15', NULL),
(2, 1, 'Siti Nur Jannah', 'Kepala Urusan Keuangan', 'uploads/desa/aparat/default-5.jpg', 'S1', '1980-11-23', 'Dusun Kramat RT 02/01, Desa Sariharjo', '081287654321', '2020-2026', 3, '2025-08-12 02:53:15', '2025-08-12 02:53:15', NULL),
(3, 1, 'Hadi Sutrisno', 'Kepala Urusan Umum', 'uploads/desa/aparat/default-4.jpg', 'SMA', '1978-04-10', 'Dusun Pesisir RT 01/03, Desa Sariharjo', '087823456789', '2020-2026', 4, '2025-08-12 02:53:15', '2025-08-12 02:53:15', NULL),
(4, 1, 'Ahmad Fauzi, S.T.', 'Kepala Seksi Pemerintahan', 'uploads/desa/aparat/default-5.jpg', 'S1', '1982-08-05', 'Dusun Tengah RT 05/02, Desa Sariharjo', '081234567890', '2020-2026', 5, '2025-08-12 02:53:15', '2025-08-12 02:53:15', NULL),
(5, 1, 'Dewi Safitri', 'Kepala Seksi Kesejahteraan', 'uploads/desa/aparat/default-1.jpg', 'D3', '1985-12-20', 'Dusun Kramat RT 04/01, Desa Sariharjo', '085678901234', '2020-2026', 6, '2025-08-12 02:53:15', '2025-08-12 02:53:15', NULL),
(6, 1, 'Mulyono', 'Kepala Seksi Pelayanan', 'uploads/desa/aparat/default-3.jpg', 'SMA', '1979-03-17', 'Dusun Pesisir RT 02/03, Desa Sariharjo', '089876543210', '2020-2026', 7, '2025-08-12 02:53:15', '2025-08-12 02:53:15', NULL),
(7, 1, 'Joko Widodo', 'Kepala Dusun Kramat', 'uploads/desa/aparat/default-5.jpg', 'SMA', '1970-09-30', 'Dusun Kramat RT 01/01, Desa Sariharjo', '081345678901', '2020-2026', 10, '2025-08-12 02:53:15', '2025-08-12 02:53:15', NULL),
(8, 1, 'Slamet Riyadi', 'Kepala Dusun Tengah', 'uploads/desa/aparat/default-4.jpg', 'SMA', '1972-07-25', 'Dusun Tengah RT 01/02, Desa Sariharjo', '085234567890', '2020-2026', 11, '2025-08-12 02:53:15', '2025-08-12 02:53:15', NULL),
(9, 1, 'Abdul Rahman', 'Kepala Dusun Pesisir', 'uploads/desa/aparat/default-5.jpg', 'SMA', '1974-05-12', 'Dusun Pesisir RT 03/03, Desa Sariharjo', '089234567890', '2020-2026', 12, '2025-08-12 02:53:15', '2025-08-12 02:53:15', NULL),
(10, 1, 'Yulia Maharani', 'Staff Administrasi', 'uploads/desa/aparat/default-5.jpg', 'D3', '1990-02-14', 'Dusun Tengah RT 02/02, Desa Sariharjo', '081789012345', '2020-2026', 15, '2025-08-12 02:53:15', '2025-08-12 02:53:15', NULL),
(11, 1, 'Agus Setiawan', 'Operator Desa', 'uploads/desa/aparat/default-5.jpg', 'S1', '1988-11-05', 'Dusun Kramat RT 03/01, Desa Sariharjo', '085890123456', '2020-2026', 16, '2025-08-12 02:53:15', '2025-08-12 02:53:15', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `bansos`
--

CREATE TABLE `bansos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `id_desa` bigint(20) UNSIGNED NOT NULL,
  `penduduk_id` bigint(20) UNSIGNED NOT NULL,
  `jenis_bansos_id` bigint(20) UNSIGNED NOT NULL,
  `status` enum('Diajukan','Dalam Verifikasi','Diverifikasi','Disetujui','Ditolak','Sudah Diterima','Dibatalkan') NOT NULL,
  `prioritas` enum('Tinggi','Sedang','Rendah') NOT NULL DEFAULT 'Sedang',
  `sumber_pengajuan` enum('admin','warga') NOT NULL DEFAULT 'admin',
  `tanggal_pengajuan` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `tanggal_penerimaan` timestamp NULL DEFAULT NULL,
  `tenggat_pengambilan` timestamp NULL DEFAULT NULL,
  `lokasi_pengambilan` varchar(255) DEFAULT NULL,
  `alasan_pengajuan` text NOT NULL,
  `keterangan` text DEFAULT NULL,
  `dokumen_pendukung` varchar(255) DEFAULT NULL,
  `bukti_penerimaan` varchar(255) DEFAULT NULL,
  `foto_rumah` varchar(255) DEFAULT NULL,
  `diubah_oleh` bigint(20) UNSIGNED DEFAULT NULL,
  `notifikasi_terkirim` tinyint(1) NOT NULL DEFAULT 0,
  `is_urgent` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bansos`
--

INSERT INTO `bansos` (`id`, `id_desa`, `penduduk_id`, `jenis_bansos_id`, `status`, `prioritas`, `sumber_pengajuan`, `tanggal_pengajuan`, `tanggal_penerimaan`, `tenggat_pengambilan`, `lokasi_pengambilan`, `alasan_pengajuan`, `keterangan`, `dokumen_pendukung`, `bukti_penerimaan`, `foto_rumah`, `diubah_oleh`, `notifikasi_terkirim`, `is_urgent`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 12, 6, 'Diajukan', 'Sedang', 'admin', '2025-08-07 02:53:16', NULL, NULL, NULL, 'Memiliki tanggungan anak sekolah yang banyak', 'Pengajuan bantuan sosial oleh petugas desa', NULL, NULL, NULL, 1, 0, 0, '2025-08-12 02:53:16', '2025-08-12 02:53:16', NULL),
(2, 1, 19, 5, 'Diajukan', 'Sedang', 'admin', '2025-08-06 02:53:16', NULL, NULL, NULL, 'Kehilangan pekerjaan utama dan perlu bantuan sementara', 'Pengajuan bantuan sosial oleh petugas desa', NULL, NULL, NULL, 1, 0, 0, '2025-08-12 02:53:16', '2025-08-12 02:53:16', NULL),
(3, 1, 25, 4, 'Diajukan', 'Sedang', 'admin', '2025-08-10 02:53:16', NULL, NULL, NULL, 'Lansia yang hidup sendiri dan membutuhkan bantuan', 'Pengajuan bantuan sosial oleh petugas desa', NULL, NULL, NULL, 1, 0, 0, '2025-08-12 02:53:16', '2025-08-12 02:53:16', NULL),
(4, 1, 8, 1, 'Diajukan', 'Tinggi', 'warga', '2025-08-11 02:53:16', '2025-07-10 08:46:21', '2025-08-22 00:16:16', NULL, 'Rumah dalam kondisi tidak layak huni', 'Pengajuan bantuan sosial oleh warga', NULL, NULL, NULL, 1, 1, 0, '2025-08-12 02:53:16', '2025-08-12 02:53:16', NULL),
(5, 1, 10, 7, 'Diajukan', 'Sedang', 'warga', '2025-08-08 02:53:16', NULL, '2025-08-13 22:57:30', NULL, 'Biaya pengobatan yang tidak tercukupi', 'Pengajuan bantuan sosial oleh warga', NULL, NULL, NULL, 1, 1, 0, '2025-08-12 02:53:16', '2025-08-12 02:53:16', NULL),
(6, 1, 24, 6, 'Diajukan', 'Sedang', 'warga', '2025-08-07 02:53:16', NULL, NULL, NULL, 'Biaya pendidikan anak yang memberatkan', 'Pengajuan bantuan sosial oleh warga', NULL, NULL, NULL, 1, 1, 0, '2025-08-12 02:53:16', '2025-08-12 02:53:16', NULL),
(7, 1, 14, 1, 'Dalam Verifikasi', 'Sedang', 'admin', '2025-07-30 02:53:16', NULL, NULL, NULL, 'Keluarga dengan kondisi ekonomi sulit dan membutuhkan bantuan', 'Pengajuan sedang dalam proses verifikasi', NULL, NULL, NULL, 1, 1, 0, '2025-08-12 02:53:16', '2025-08-12 02:53:16', NULL),
(8, 1, 20, 7, 'Dalam Verifikasi', 'Sedang', 'admin', '2025-07-30 02:53:16', NULL, NULL, NULL, 'Keluarga dengan kondisi ekonomi sulit dan membutuhkan bantuan', 'Pengajuan sedang dalam proses verifikasi', NULL, NULL, NULL, 1, 1, 1, '2025-08-12 02:53:16', '2025-08-12 02:53:16', NULL),
(9, 1, 24, 7, 'Dalam Verifikasi', 'Sedang', 'admin', '2025-08-01 02:53:16', NULL, NULL, NULL, 'Keluarga dengan kondisi ekonomi sulit dan membutuhkan bantuan', 'Pengajuan sedang dalam proses verifikasi', NULL, NULL, NULL, 1, 1, 0, '2025-08-12 02:53:16', '2025-08-12 02:53:16', NULL),
(10, 1, 1, 7, 'Diverifikasi', 'Tinggi', 'admin', '2025-07-23 02:53:16', NULL, NULL, NULL, 'Keluarga dengan tanggungan banyak dan penghasilan tidak mencukupi', 'Data telah diverifikasi dan valid sesuai dengan kriteria penerima bantuan', NULL, NULL, NULL, 1, 1, 0, '2025-08-12 02:53:16', '2025-08-12 02:53:16', NULL),
(11, 1, 3, 3, 'Diverifikasi', 'Rendah', 'admin', '2025-07-21 02:53:16', NULL, NULL, NULL, 'Keluarga dengan tanggungan banyak dan penghasilan tidak mencukupi', 'Data telah diverifikasi dan valid sesuai dengan kriteria penerima bantuan', NULL, NULL, NULL, 1, 1, 0, '2025-08-12 02:53:16', '2025-08-12 02:53:16', NULL),
(12, 1, 7, 5, 'Diverifikasi', 'Tinggi', 'admin', '2025-07-23 02:53:16', NULL, NULL, NULL, 'Keluarga dengan tanggungan banyak dan penghasilan tidak mencukupi', 'Data telah diverifikasi dan valid sesuai dengan kriteria penerima bantuan', NULL, NULL, NULL, 1, 1, 0, '2025-08-12 02:53:16', '2025-08-12 02:53:16', NULL),
(13, 1, 18, 2, 'Diverifikasi', 'Sedang', 'admin', '2025-07-22 02:53:16', NULL, NULL, NULL, 'Keluarga dengan tanggungan banyak dan penghasilan tidak mencukupi', 'Data telah diverifikasi dan valid sesuai dengan kriteria penerima bantuan', NULL, NULL, NULL, 1, 1, 0, '2025-08-12 02:53:16', '2025-08-12 02:53:16', NULL),
(14, 1, 11, 7, 'Disetujui', 'Sedang', 'admin', '2025-06-28 02:53:16', NULL, '2025-08-15 02:53:16', NULL, 'Keluarga dengan kondisi ekonomi sulit akibat PHK dan memiliki tanggungan anak sekolah', 'Pengajuan disetujui untuk menerima bantuan', NULL, NULL, NULL, 1, 1, 0, '2025-08-12 02:53:16', '2025-08-12 02:53:16', NULL),
(15, 1, 13, 2, 'Disetujui', 'Sedang', 'admin', '2025-06-30 02:53:16', NULL, '2025-08-16 02:53:16', NULL, 'Keluarga dengan kondisi ekonomi sulit akibat PHK dan memiliki tanggungan anak sekolah', 'Pengajuan disetujui untuk menerima bantuan', NULL, NULL, NULL, 1, 1, 0, '2025-08-12 02:53:16', '2025-08-12 02:53:16', NULL),
(16, 1, 17, 6, 'Disetujui', 'Sedang', 'admin', '2025-07-03 02:53:16', NULL, '2025-08-25 02:53:16', NULL, 'Keluarga dengan kondisi ekonomi sulit akibat PHK dan memiliki tanggungan anak sekolah', 'Pengajuan disetujui untuk menerima bantuan', NULL, NULL, NULL, 1, 1, 0, '2025-08-12 02:53:16', '2025-08-12 02:53:16', NULL),
(17, 1, 5, 7, 'Sudah Diterima', 'Tinggi', 'admin', '2025-05-31 02:53:16', '2025-08-05 02:53:16', '2025-07-21 02:53:16', NULL, 'Keluarga tidak mampu dengan kondisi rumah yang tidak layak huni', 'Bantuan telah diterima oleh penerima', NULL, 'bansos/bukti/bukti-dummy-2.jpg', NULL, 1, 1, 0, '2025-08-12 02:53:16', '2025-08-12 02:53:16', NULL),
(18, 1, 6, 6, 'Sudah Diterima', 'Rendah', 'admin', '2025-06-12 02:53:16', '2025-08-07 02:53:16', '2025-07-14 02:53:16', NULL, 'Keluarga tidak mampu dengan kondisi rumah yang tidak layak huni', 'Bantuan telah diterima oleh penerima', NULL, 'bansos/bukti/bukti-dummy-4.jpg', NULL, 1, 1, 0, '2025-08-12 02:53:16', '2025-08-12 02:53:16', NULL),
(19, 1, 14, 2, 'Sudah Diterima', 'Sedang', 'admin', '2025-05-29 02:53:16', '2025-08-01 02:53:16', '2025-07-17 02:53:16', NULL, 'Keluarga tidak mampu dengan kondisi rumah yang tidak layak huni', 'Bantuan telah diterima oleh penerima', NULL, 'bansos/bukti/bukti-dummy-4.jpg', NULL, 1, 1, 0, '2025-08-12 02:53:16', '2025-08-12 02:53:16', NULL),
(20, 1, 18, 6, 'Sudah Diterima', 'Rendah', 'admin', '2025-05-31 02:53:16', '2025-07-28 02:53:16', '2025-07-22 02:53:16', NULL, 'Keluarga tidak mampu dengan kondisi rumah yang tidak layak huni', 'Bantuan telah diterima oleh penerima', NULL, 'bansos/bukti/bukti-dummy-5.jpg', NULL, 1, 1, 0, '2025-08-12 02:53:16', '2025-08-12 02:53:16', NULL),
(21, 1, 23, 1, 'Sudah Diterima', 'Rendah', 'admin', '2025-06-11 02:53:16', '2025-07-23 02:53:16', '2025-07-25 02:53:16', NULL, 'Keluarga tidak mampu dengan kondisi rumah yang tidak layak huni', 'Bantuan telah diterima oleh penerima', NULL, 'bansos/bukti/bukti-dummy-4.jpg', NULL, 1, 1, 0, '2025-08-12 02:53:16', '2025-08-12 02:53:16', NULL),
(22, 1, 24, 3, 'Sudah Diterima', 'Rendah', 'admin', '2025-05-22 02:53:16', '2025-08-06 02:53:16', '2025-07-12 02:53:16', NULL, 'Keluarga tidak mampu dengan kondisi rumah yang tidak layak huni', 'Bantuan telah diterima oleh penerima', NULL, 'bansos/bukti/bukti-dummy-3.jpg', NULL, 1, 1, 0, '2025-08-12 02:53:16', '2025-08-12 02:53:16', NULL),
(23, 1, 10, 7, 'Ditolak', 'Rendah', 'admin', '2025-07-14 02:53:16', NULL, NULL, NULL, 'Kesulitan membayar kebutuhan pokok', 'Ditolak: Duplikasi pengajuan', NULL, NULL, NULL, 1, 1, 0, '2025-08-12 02:53:16', '2025-08-12 02:53:16', NULL),
(24, 1, 18, 5, 'Ditolak', 'Rendah', 'admin', '2025-07-21 02:53:16', NULL, NULL, NULL, 'Membutuhkan bantuan untuk memperbaiki rumah', 'Ditolak: Data tidak sesuai dengan kondisi di lapangan', NULL, NULL, NULL, 1, 1, 0, '2025-08-12 02:53:16', '2025-08-12 02:53:16', NULL),
(25, 1, 24, 1, 'Ditolak', 'Rendah', 'admin', '2025-07-14 02:53:16', NULL, NULL, NULL, 'Keluarga kesulitan biaya sekolah anak', 'Ditolak: Sudah menerima bantuan lain', NULL, NULL, NULL, 1, 1, 0, '2025-08-12 02:53:16', '2025-08-12 02:53:16', NULL),
(26, 1, 16, 3, 'Diajukan', 'Tinggi', 'warga', '2025-08-11 02:53:16', NULL, '2025-08-20 18:08:56', NULL, 'Rumah rusak parah akibat bencana', 'Keluarga dalam kondisi darurat ekonomi', NULL, NULL, NULL, 1, 1, 1, '2025-08-12 02:53:16', '2025-08-12 02:53:16', NULL),
(27, 1, 18, 4, 'Diajukan', 'Tinggi', 'warga', '2025-08-11 02:53:16', NULL, '2025-08-15 00:28:10', NULL, 'Lansia tanpa penghasilan dan tanpa keluarga', 'Keluarga dengan balita malnutrisi', NULL, NULL, NULL, 1, 1, 1, '2025-08-12 02:53:16', '2025-08-12 02:53:16', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `bansos_history`
--

CREATE TABLE `bansos_history` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `bansos_id` bigint(20) UNSIGNED NOT NULL,
  `status_lama` varchar(255) DEFAULT NULL,
  `status_baru` varchar(255) NOT NULL,
  `keterangan` text DEFAULT NULL,
  `diubah_oleh` bigint(20) UNSIGNED NOT NULL,
  `waktu_perubahan` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bansos_history`
--

INSERT INTO `bansos_history` (`id`, `bansos_id`, `status_lama`, `status_baru`, `keterangan`, `diubah_oleh`, `waktu_perubahan`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, 'Diajukan', 'Pengajuan bantuan baru oleh admin', 1, '2025-08-07 02:53:16', '2025-08-12 02:53:16', '2025-08-12 02:53:16'),
(2, 2, NULL, 'Diajukan', 'Pengajuan bantuan baru oleh admin', 1, '2025-08-06 02:53:16', '2025-08-12 02:53:16', '2025-08-12 02:53:16'),
(3, 3, NULL, 'Diajukan', 'Pengajuan bantuan baru oleh admin', 1, '2025-08-10 02:53:16', '2025-08-12 02:53:16', '2025-08-12 02:53:16'),
(4, 4, NULL, 'Diajukan', 'Pengajuan bantuan baru oleh warga', 1, '2025-08-11 02:53:16', '2025-08-12 02:53:16', '2025-08-12 02:53:16'),
(5, 5, NULL, 'Diajukan', 'Pengajuan bantuan baru oleh warga', 1, '2025-08-08 02:53:16', '2025-08-12 02:53:16', '2025-08-12 02:53:16'),
(6, 6, NULL, 'Diajukan', 'Pengajuan bantuan baru oleh warga', 1, '2025-08-07 02:53:16', '2025-08-12 02:53:16', '2025-08-12 02:53:16'),
(7, 7, NULL, 'Diajukan', 'Pengajuan bantuan baru', 1, '2025-07-30 02:53:16', '2025-08-12 02:53:16', '2025-08-12 02:53:16'),
(8, 7, 'Diajukan', 'Dalam Verifikasi', 'Pengajuan diproses untuk verifikasi', 1, '2025-08-09 02:53:16', '2025-08-12 02:53:16', '2025-08-12 02:53:16'),
(9, 8, NULL, 'Diajukan', 'Pengajuan bantuan baru', 1, '2025-07-30 02:53:16', '2025-08-12 02:53:16', '2025-08-12 02:53:16'),
(10, 8, 'Diajukan', 'Dalam Verifikasi', 'Pengajuan diproses untuk verifikasi', 1, '2025-08-09 02:53:16', '2025-08-12 02:53:16', '2025-08-12 02:53:16'),
(11, 9, NULL, 'Diajukan', 'Pengajuan bantuan baru', 1, '2025-08-01 02:53:16', '2025-08-12 02:53:16', '2025-08-12 02:53:16'),
(12, 9, 'Diajukan', 'Dalam Verifikasi', 'Pengajuan diproses untuk verifikasi', 1, '2025-08-09 02:53:16', '2025-08-12 02:53:16', '2025-08-12 02:53:16'),
(13, 10, NULL, 'Diajukan', 'Pengajuan bantuan baru', 1, '2025-07-23 02:53:16', '2025-08-12 02:53:16', '2025-08-12 02:53:16'),
(14, 10, 'Diajukan', 'Dalam Verifikasi', 'Pengajuan diproses untuk verifikasi', 1, '2025-07-28 02:53:16', '2025-08-12 02:53:16', '2025-08-12 02:53:16'),
(15, 10, 'Dalam Verifikasi', 'Diverifikasi', 'Data telah diverifikasi dan valid', 1, '2025-08-05 02:53:16', '2025-08-12 02:53:16', '2025-08-12 02:53:16'),
(16, 11, NULL, 'Diajukan', 'Pengajuan bantuan baru', 1, '2025-07-21 02:53:16', '2025-08-12 02:53:16', '2025-08-12 02:53:16'),
(17, 11, 'Diajukan', 'Dalam Verifikasi', 'Pengajuan diproses untuk verifikasi', 1, '2025-07-31 02:53:16', '2025-08-12 02:53:16', '2025-08-12 02:53:16'),
(18, 11, 'Dalam Verifikasi', 'Diverifikasi', 'Data telah diverifikasi dan valid', 1, '2025-08-05 02:53:16', '2025-08-12 02:53:16', '2025-08-12 02:53:16'),
(19, 12, NULL, 'Diajukan', 'Pengajuan bantuan baru', 1, '2025-07-23 02:53:16', '2025-08-12 02:53:16', '2025-08-12 02:53:16'),
(20, 12, 'Diajukan', 'Dalam Verifikasi', 'Pengajuan diproses untuk verifikasi', 1, '2025-08-01 02:53:16', '2025-08-12 02:53:16', '2025-08-12 02:53:16'),
(21, 12, 'Dalam Verifikasi', 'Diverifikasi', 'Data telah diverifikasi dan valid', 1, '2025-08-08 02:53:16', '2025-08-12 02:53:16', '2025-08-12 02:53:16'),
(22, 13, NULL, 'Diajukan', 'Pengajuan bantuan baru', 1, '2025-07-22 02:53:16', '2025-08-12 02:53:16', '2025-08-12 02:53:16'),
(23, 13, 'Diajukan', 'Dalam Verifikasi', 'Pengajuan diproses untuk verifikasi', 1, '2025-07-28 02:53:16', '2025-08-12 02:53:16', '2025-08-12 02:53:16'),
(24, 13, 'Dalam Verifikasi', 'Diverifikasi', 'Data telah diverifikasi dan valid', 1, '2025-08-09 02:53:16', '2025-08-12 02:53:16', '2025-08-12 02:53:16'),
(25, 14, NULL, 'Diajukan', 'Pengajuan bantuan baru', 1, '2025-06-28 02:53:16', '2025-08-12 02:53:16', '2025-08-12 02:53:16'),
(26, 14, 'Diajukan', 'Dalam Verifikasi', 'Pengajuan diproses untuk verifikasi', 1, '2025-07-12 02:53:16', '2025-08-12 02:53:16', '2025-08-12 02:53:16'),
(27, 14, 'Dalam Verifikasi', 'Diverifikasi', 'Data telah diverifikasi dan valid', 1, '2025-07-18 02:53:16', '2025-08-12 02:53:16', '2025-08-12 02:53:16'),
(28, 14, 'Diverifikasi', 'Disetujui', 'Pengajuan disetujui untuk menerima bantuan', 1, '2025-08-03 02:53:16', '2025-08-12 02:53:16', '2025-08-12 02:53:16'),
(29, 15, NULL, 'Diajukan', 'Pengajuan bantuan baru', 1, '2025-06-30 02:53:16', '2025-08-12 02:53:16', '2025-08-12 02:53:16'),
(30, 15, 'Diajukan', 'Dalam Verifikasi', 'Pengajuan diproses untuk verifikasi', 1, '2025-07-12 02:53:16', '2025-08-12 02:53:16', '2025-08-12 02:53:16'),
(31, 15, 'Dalam Verifikasi', 'Diverifikasi', 'Data telah diverifikasi dan valid', 1, '2025-07-19 02:53:16', '2025-08-12 02:53:16', '2025-08-12 02:53:16'),
(32, 15, 'Diverifikasi', 'Disetujui', 'Pengajuan disetujui untuk menerima bantuan', 1, '2025-08-04 02:53:16', '2025-08-12 02:53:16', '2025-08-12 02:53:16'),
(33, 16, NULL, 'Diajukan', 'Pengajuan bantuan baru', 1, '2025-07-03 02:53:16', '2025-08-12 02:53:16', '2025-08-12 02:53:16'),
(34, 16, 'Diajukan', 'Dalam Verifikasi', 'Pengajuan diproses untuk verifikasi', 1, '2025-07-10 02:53:16', '2025-08-12 02:53:16', '2025-08-12 02:53:16'),
(35, 16, 'Dalam Verifikasi', 'Diverifikasi', 'Data telah diverifikasi dan valid', 1, '2025-07-22 02:53:16', '2025-08-12 02:53:16', '2025-08-12 02:53:16'),
(36, 16, 'Diverifikasi', 'Disetujui', 'Pengajuan disetujui untuk menerima bantuan', 1, '2025-08-06 02:53:16', '2025-08-12 02:53:16', '2025-08-12 02:53:16'),
(37, 17, NULL, 'Diajukan', 'Pengajuan bantuan baru', 1, '2025-05-31 02:53:16', '2025-08-12 02:53:16', '2025-08-12 02:53:16'),
(38, 17, 'Diajukan', 'Dalam Verifikasi', 'Pengajuan diproses untuk verifikasi', 1, '2025-06-13 02:53:16', '2025-08-12 02:53:16', '2025-08-12 02:53:16'),
(39, 17, 'Dalam Verifikasi', 'Diverifikasi', 'Data telah diverifikasi dan valid', 1, '2025-07-02 02:53:16', '2025-08-12 02:53:16', '2025-08-12 02:53:16'),
(40, 17, 'Diverifikasi', 'Disetujui', 'Pengajuan disetujui untuk menerima bantuan', 1, '2025-07-06 02:53:16', '2025-08-12 02:53:16', '2025-08-12 02:53:16'),
(41, 17, 'Disetujui', 'Sudah Diterima', 'Bantuan telah diterima oleh penerima', 1, '2025-08-05 02:53:16', '2025-08-12 02:53:16', '2025-08-12 02:53:16'),
(42, 18, NULL, 'Diajukan', 'Pengajuan bantuan baru', 1, '2025-06-12 02:53:16', '2025-08-12 02:53:16', '2025-08-12 02:53:16'),
(43, 18, 'Diajukan', 'Dalam Verifikasi', 'Pengajuan diproses untuk verifikasi', 1, '2025-06-14 02:53:16', '2025-08-12 02:53:16', '2025-08-12 02:53:16'),
(44, 18, 'Dalam Verifikasi', 'Diverifikasi', 'Data telah diverifikasi dan valid', 1, '2025-06-27 02:53:16', '2025-08-12 02:53:16', '2025-08-12 02:53:16'),
(45, 18, 'Diverifikasi', 'Disetujui', 'Pengajuan disetujui untuk menerima bantuan', 1, '2025-07-08 02:53:16', '2025-08-12 02:53:16', '2025-08-12 02:53:16'),
(46, 18, 'Disetujui', 'Sudah Diterima', 'Bantuan telah diterima oleh penerima', 1, '2025-08-07 02:53:16', '2025-08-12 02:53:16', '2025-08-12 02:53:16'),
(47, 19, NULL, 'Diajukan', 'Pengajuan bantuan baru', 1, '2025-05-29 02:53:16', '2025-08-12 02:53:16', '2025-08-12 02:53:16'),
(48, 19, 'Diajukan', 'Dalam Verifikasi', 'Pengajuan diproses untuk verifikasi', 1, '2025-06-20 02:53:16', '2025-08-12 02:53:16', '2025-08-12 02:53:16'),
(49, 19, 'Dalam Verifikasi', 'Diverifikasi', 'Data telah diverifikasi dan valid', 1, '2025-07-01 02:53:16', '2025-08-12 02:53:16', '2025-08-12 02:53:16'),
(50, 19, 'Diverifikasi', 'Disetujui', 'Pengajuan disetujui untuk menerima bantuan', 1, '2025-07-03 02:53:16', '2025-08-12 02:53:16', '2025-08-12 02:53:16'),
(51, 19, 'Disetujui', 'Sudah Diterima', 'Bantuan telah diterima oleh penerima', 1, '2025-08-01 02:53:16', '2025-08-12 02:53:16', '2025-08-12 02:53:16'),
(52, 20, NULL, 'Diajukan', 'Pengajuan bantuan baru', 1, '2025-05-31 02:53:16', '2025-08-12 02:53:16', '2025-08-12 02:53:16'),
(53, 20, 'Diajukan', 'Dalam Verifikasi', 'Pengajuan diproses untuk verifikasi', 1, '2025-06-20 02:53:16', '2025-08-12 02:53:16', '2025-08-12 02:53:16'),
(54, 20, 'Dalam Verifikasi', 'Diverifikasi', 'Data telah diverifikasi dan valid', 1, '2025-06-27 02:53:16', '2025-08-12 02:53:16', '2025-08-12 02:53:16'),
(55, 20, 'Diverifikasi', 'Disetujui', 'Pengajuan disetujui untuk menerima bantuan', 1, '2025-07-12 02:53:16', '2025-08-12 02:53:16', '2025-08-12 02:53:16'),
(56, 20, 'Disetujui', 'Sudah Diterima', 'Bantuan telah diterima oleh penerima', 1, '2025-07-28 02:53:16', '2025-08-12 02:53:16', '2025-08-12 02:53:16'),
(57, 21, NULL, 'Diajukan', 'Pengajuan bantuan baru', 1, '2025-06-11 02:53:16', '2025-08-12 02:53:16', '2025-08-12 02:53:16'),
(58, 21, 'Diajukan', 'Dalam Verifikasi', 'Pengajuan diproses untuk verifikasi', 1, '2025-06-20 02:53:16', '2025-08-12 02:53:16', '2025-08-12 02:53:16'),
(59, 21, 'Dalam Verifikasi', 'Diverifikasi', 'Data telah diverifikasi dan valid', 1, '2025-06-29 02:53:16', '2025-08-12 02:53:16', '2025-08-12 02:53:16'),
(60, 21, 'Diverifikasi', 'Disetujui', 'Pengajuan disetujui untuk menerima bantuan', 1, '2025-07-10 02:53:16', '2025-08-12 02:53:16', '2025-08-12 02:53:16'),
(61, 21, 'Disetujui', 'Sudah Diterima', 'Bantuan telah diterima oleh penerima', 1, '2025-07-23 02:53:16', '2025-08-12 02:53:16', '2025-08-12 02:53:16'),
(62, 22, NULL, 'Diajukan', 'Pengajuan bantuan baru', 1, '2025-05-22 02:53:16', '2025-08-12 02:53:16', '2025-08-12 02:53:16'),
(63, 22, 'Diajukan', 'Dalam Verifikasi', 'Pengajuan diproses untuk verifikasi', 1, '2025-06-14 02:53:16', '2025-08-12 02:53:16', '2025-08-12 02:53:16'),
(64, 22, 'Dalam Verifikasi', 'Diverifikasi', 'Data telah diverifikasi dan valid', 1, '2025-06-28 02:53:16', '2025-08-12 02:53:16', '2025-08-12 02:53:16'),
(65, 22, 'Diverifikasi', 'Disetujui', 'Pengajuan disetujui untuk menerima bantuan', 1, '2025-07-07 02:53:16', '2025-08-12 02:53:16', '2025-08-12 02:53:16'),
(66, 22, 'Disetujui', 'Sudah Diterima', 'Bantuan telah diterima oleh penerima', 1, '2025-08-06 02:53:16', '2025-08-12 02:53:16', '2025-08-12 02:53:16'),
(67, 23, NULL, 'Diajukan', 'Pengajuan bantuan baru', 1, '2025-07-14 02:53:16', '2025-08-12 02:53:16', '2025-08-12 02:53:16'),
(68, 23, 'Diajukan', 'Dalam Verifikasi', 'Pengajuan diproses untuk verifikasi', 1, '2025-08-01 02:53:16', '2025-08-12 02:53:16', '2025-08-12 02:53:16'),
(69, 23, 'Dalam Verifikasi', 'Diverifikasi', 'Data telah diverifikasi', 1, '2025-07-31 02:53:16', '2025-08-12 02:53:16', '2025-08-12 02:53:16'),
(70, 23, 'Diverifikasi', 'Ditolak', 'Duplikasi pengajuan', 1, '2025-07-31 02:53:16', '2025-08-12 02:53:16', '2025-08-12 02:53:16'),
(71, 24, NULL, 'Diajukan', 'Pengajuan bantuan baru', 1, '2025-07-21 02:53:16', '2025-08-12 02:53:16', '2025-08-12 02:53:16'),
(72, 24, 'Diajukan', 'Dalam Verifikasi', 'Pengajuan diproses untuk verifikasi', 1, '2025-07-29 02:53:16', '2025-08-12 02:53:16', '2025-08-12 02:53:16'),
(73, 24, 'Dalam Verifikasi', 'Diverifikasi', 'Data telah diverifikasi', 1, '2025-07-28 02:53:16', '2025-08-12 02:53:16', '2025-08-12 02:53:16'),
(74, 24, 'Diverifikasi', 'Ditolak', 'Data tidak sesuai dengan kondisi di lapangan', 1, '2025-07-28 02:53:16', '2025-08-12 02:53:16', '2025-08-12 02:53:16'),
(75, 25, NULL, 'Diajukan', 'Pengajuan bantuan baru', 1, '2025-07-14 02:53:16', '2025-08-12 02:53:16', '2025-08-12 02:53:16'),
(76, 25, 'Diajukan', 'Ditolak', 'Sudah menerima bantuan lain', 1, '2025-07-18 02:53:16', '2025-08-12 02:53:16', '2025-08-12 02:53:16'),
(77, 26, NULL, 'Diajukan', 'Pengajuan bantuan darurat/mendesak', 1, '2025-08-11 02:53:16', '2025-08-12 02:53:16', '2025-08-12 02:53:16'),
(78, 27, NULL, 'Diajukan', 'Pengajuan bantuan darurat/mendesak', 1, '2025-08-11 02:53:16', '2025-08-12 02:53:16', '2025-08-12 02:53:16');

-- --------------------------------------------------------

--
-- Table structure for table `batas_wilayah_potensi`
--

CREATE TABLE `batas_wilayah_potensi` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `profil_desa_id` bigint(20) UNSIGNED NOT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `luas_wilayah` double DEFAULT NULL,
  `batas_utara` varchar(255) DEFAULT NULL,
  `batas_timur` varchar(255) DEFAULT NULL,
  `batas_selatan` varchar(255) DEFAULT NULL,
  `batas_barat` varchar(255) DEFAULT NULL,
  `keterangan_batas` text DEFAULT NULL,
  `potensi_desa` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`potensi_desa`)),
  `keterangan_potensi` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `batas_wilayah_potensi`
--

INSERT INTO `batas_wilayah_potensi` (`id`, `profil_desa_id`, `created_by`, `luas_wilayah`, `batas_utara`, `batas_timur`, `batas_selatan`, `batas_barat`, `keterangan_batas`, `potensi_desa`, `keterangan_potensi`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 1, 6188500, 'Desa Pakem', 'Desa Ngemplak', 'Desa Mlati', 'Desa Mlati', 'Batas-batas wilayah sesuai dengan peta desa tahun 2025', '[{\"nama\":\"Sumber mata air\",\"kategori\":\"sda\",\"lokasi\":\"Dusun Tambakrejo\",\"deskripsi\":\"Sumber mata air yang digunakan untuk kebutuhan air bersih desa\"},{\"nama\":\"Lahan pertanian subur\",\"kategori\":\"sda\",\"lokasi\":\"Seluruh wilayah desa\",\"deskripsi\":\"Lahan dengan tingkat kesuburan tinggi untuk pertanian\"},{\"nama\":\"Tambak ikan\",\"kategori\":\"peternakan\",\"lokasi\":\"Dusun Ngetiran\",\"satuan\":\"Ha\",\"jumlah\":50,\"deskripsi\":\"Area tambak untuk budidaya ikan dan udang\"},{\"nama\":\"Tanaman padi\",\"kategori\":\"pertanian\",\"lokasi\":\"Area persawahan\",\"satuan\":\"Ha\",\"jumlah\":250,\"deskripsi\":\"Tanaman padi varietas unggul\"},{\"nama\":\"Palawija\",\"kategori\":\"pertanian\",\"lokasi\":\"Dusun Rejodani\",\"satuan\":\"Ha\",\"jumlah\":120,\"deskripsi\":\"Jagung, kacang tanah, dan kedelai\"},{\"nama\":\"Peternakan ayam\",\"kategori\":\"peternakan\",\"lokasi\":\"Dusun Wonorejo\",\"satuan\":\"Ekor\",\"jumlah\":5000,\"deskripsi\":\"Peternakan ayam broiler dan petelur\"},{\"nama\":\"Wisata kuliner laut\",\"kategori\":\"pariwisata\",\"lokasi\":\"Dusun Ngetiran\",\"satuan\":\"Lokasi\",\"jumlah\":1,\"deskripsi\":\"Pusat kuliner seafood segar hasil tangkapan nelayan\"},{\"nama\":\"Kerajinan anyaman bambu\",\"kategori\":\"industri\",\"lokasi\":\"Dusun Ngetiran\",\"satuan\":\"Unit\",\"jumlah\":15,\"deskripsi\":\"Industri rumahan kerajinan bambu\"},{\"nama\":\"Kelompok seni tradisional\",\"kategori\":\"budaya\",\"lokasi\":\"Dusun Rejodani\",\"satuan\":\"Kelompok\",\"jumlah\":2,\"deskripsi\":\"Kelompok seni tradisional yang masih aktif\"}]', 'Potensi desa berdasarkan pemetaan tahun 2025', '2025-08-12 02:53:15', '2025-08-12 03:13:04', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `berita`
--

CREATE TABLE `berita` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `id_desa` bigint(20) UNSIGNED NOT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `judul` varchar(255) NOT NULL,
  `isi` text NOT NULL,
  `kategori` varchar(50) NOT NULL,
  `gambar` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `berita`
--

INSERT INTO `berita` (`id`, `id_desa`, `created_by`, `judul`, `isi`, `kategori`, `gambar`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 1, 'Pengumuman: Sit debitis provident dolorum qui qui velit.', '<p>Dicta nulla quia et itaque beatae. Ipsum unde facere qui. Ea pariatur adipisci non rem quo et iusto molestias. Itaque consequatur possimus nemo magni. Ducimus animi iusto perferendis et earum tenetur.</p><p>Quod est voluptatem amet ut ipsa non neque. Sint nihil veritatis illo dolorem quasi a. Dolores sit ut aut ad. Maxime quaerat autem numquam doloribus.</p><p>Ut sint quidem deleniti dolore accusamus libero. Doloremque et ut corporis nesciunt totam aut. Sunt delectus consequatur et enim. Odit eum et rerum quibusdam exercitationem facilis.</p><p>Est dolores nemo itaque molestiae quibusdam totam nihil dolorum. Animi a sint est saepe dolorem et cum. Fugiat dolore magnam mollitia. Possimus ratione assumenda quia dolor qui enim ab.</p><p>Totam est commodi dolorem. Saepe rerum quia quia aliquam. Totam dicta veniam expedita quos. Et possimus nostrum ex sequi distinctio ipsam fuga.</p>', 'Pengumuman', 'uploads/berita/01K2E4TR0ZPNGPB0RSEP6SECV0.jpg', '2025-08-12 02:53:16', '2025-08-12 03:22:53', NULL),
(2, 1, 1, 'Pengumuman: Saepe magni tenetur asperiores beatae saepe.', '<p>Sapiente quo laborum nesciunt amet accusantium qui illum commodi. Beatae itaque voluptatem quae ut id hic dolorem. Consequatur est dolores repellendus veniam. Similique est est optio esse sit consectetur.</p><p>Totam et ab vel ut velit libero qui. Quidem reprehenderit accusantium inventore corporis omnis. Quia quas vero voluptatem occaecati deleniti.</p><p>Unde numquam dolorem minima vel. Atque in id corporis sed ut rerum quo. Sit voluptatum nihil qui est provident necessitatibus iusto aut.</p><p>Quos praesentium est laboriosam est laudantium. Exercitationem quisquam illo molestias ut nemo dolores sequi. Doloribus in magni quis iste quia. Iste corrupti voluptatum rerum sit voluptates officia natus quod.</p><p>Voluptas et ea inventore sit. Praesentium error voluptas dolore est alias. Numquam nesciunt maiores laborum autem esse.</p>', 'Pengumuman', 'uploads/berita/01K2E4VFETGAEAVF5XVSDX96EJ.jpg', '2025-08-12 02:53:16', '2025-08-12 03:23:17', NULL),
(3, 1, 1, 'Kegiatan: Amet rerum nihil possimus.', '<p>Aut velit earum ipsa iusto ut. Voluptatem repudiandae voluptas quia laudantium. Aut aliquam ab velit nisi voluptates. Quis est tempora eos sapiente.</p><p>Aut repellat corrupti debitis deleniti praesentium. Illo totam animi nobis totam veniam ipsam voluptas. Aut ut laudantium eius enim sit.</p><p>Ipsum sed porro vero perspiciatis totam laudantium alias. Excepturi unde repellat aut sit voluptatum dolores. Sit suscipit aliquam voluptatem repellat optio doloribus laudantium.</p><p>Veritatis nam sed omnis et. Non ut provident expedita est. Quo perferendis at blanditiis tempore. Repudiandae numquam aliquam qui quis accusantium.</p><p>Libero vero labore molestiae veniam qui tempore iure. Et id veritatis aut quidem voluptate atque. Magnam nesciunt inventore consectetur minima velit voluptatem sunt.</p>', 'Kegiatan', 'uploads/berita/01K2E4WZHS750RXTX5GZ2QRJ4Q.jpg', '2025-08-12 02:53:16', '2025-08-12 03:24:06', NULL),
(4, 1, 1, 'Kegiatan: Consequatur ex aut nemo ipsa asperiores.', '<p>Illum itaque et distinctio a repudiandae ipsum omnis rerum. Quibusdam et quae distinctio et rerum officia eos. Eos dolorum aut quam.</p><p>Libero commodi id exercitationem numquam nostrum. Eius deleniti voluptatibus quod accusamus.</p><p>Eos est dolores quia est omnis voluptatum. Quia harum quod unde eaque in non. Mollitia repellendus mollitia architecto voluptas tempora quia.</p><p>Cum non laborum facilis dolorem voluptatibus. Consequatur fuga a id. Sunt placeat mollitia et consequatur labore labore. Corrupti animi modi debitis tenetur non sit.</p><p>Ut officia ipsa rerum quasi quisquam. Sed nemo deleniti culpa ipsum eos. Eum impedit magni et et sed.</p>', 'Kegiatan', 'uploads/berita/01K2E4X40DH89P2HCNHFW36FYZ.jpg', '2025-08-12 02:53:16', '2025-08-12 03:24:11', NULL),
(5, 1, 1, 'Kegiatan: Magni velit voluptas architecto qui a.', '<p>Vel quo aliquid consequuntur similique ea. Molestiae rerum ut numquam. Et exercitationem et nesciunt omnis.</p><p>Enim est et possimus tenetur vero enim quam. Delectus eveniet nihil neque tempora. Rerum laboriosam distinctio nemo distinctio. Dolor nemo consequatur earum inventore commodi aut ut.</p><p>Nihil dicta porro praesentium rerum dolores unde et. Dolore tempora commodi laudantium nihil vero. Aperiam tenetur itaque autem quidem harum fugit praesentium.</p><p>Qui voluptatem sunt repellat quo. Quidem mollitia eligendi facilis dicta eum. Omnis eos aut maiores.</p><p>Veritatis vel ad eveniet maiores odio repellendus voluptatibus. Aspernatur natus velit nisi est aut consequatur.</p>', 'Kegiatan', 'uploads/berita/01K2E4X8ECZ2F81KEKA0M5FAKJ.jpg', '2025-08-12 02:53:16', '2025-08-12 03:24:15', NULL),
(6, 1, 1, 'Inventore officiis incidunt molestias eum expedita et reiciendis.', '<p>Neque nobis ducimus numquam quos illum. Ea ut qui ea aut quia ipsam rem. Eos ipsa voluptas molestias cum laboriosam dicta.</p><p>Qui culpa omnis placeat et corrupti velit. Vero sunt ex provident voluptatem et natus. Suscipit beatae quae perspiciatis placeat totam voluptatem et. Praesentium eligendi et impedit. Odit ut aut quia sit quod dignissimos nesciunt.</p><p>Qui voluptate quis ut aliquid nostrum commodi. Non neque perspiciatis aut aliquam. Laudantium officia temporibus commodi qui maiores et aperiam. Corporis repudiandae quaerat quaerat dicta.</p><p>Nemo sequi praesentium velit fugiat quas odio. Aut molestiae sint officia. Quaerat dignissimos consequatur blanditiis aut praesentium. Ex voluptatem laudantium voluptatibus nisi dolores incidunt eos.</p><p>Reiciendis aut rerum deserunt neque reprehenderit est. Vero debitis corporis delectus nisi recusandae cupiditate optio. Molestiae omnis et corrupti asperiores voluptatem quod tempore amet. Illo ipsum explicabo dolores eos eveniet cum est.</p>', 'Kesehatan', 'uploads/berita/01K2E4XD4S1QX0W7FV03J6PKKW.jpg', '2025-08-12 02:53:16', '2025-08-12 03:24:20', NULL),
(7, 1, 1, 'Illo id assumenda ut omnis temporibus sed.', '<p>Ex ut non eaque vel eos autem. Quasi ut ea dolores deleniti. Commodi consequatur quia soluta quidem voluptate qui pariatur. Autem molestiae aspernatur tempora enim.</p><p>Voluptas rem ipsam reprehenderit et ipsa qui enim. Consequatur aut cum et nisi. Dolor est nobis expedita corporis. Quam quos aut assumenda vitae ad.</p><p>Iste distinctio ut consequatur nostrum. Soluta pariatur non quo ex. Et doloribus sed numquam debitis consequuntur. Id nobis quam qui expedita sed repellat.</p><p>Ut quos officia nobis error ut eos. Ad commodi quidem asperiores tempora eveniet molestiae sequi ut. Cumque error minima voluptas incidunt vel totam rerum neque. In error reprehenderit qui totam voluptate qui.</p><p>In doloribus dignissimos id quasi expedita assumenda et. Quo veniam minima beatae similique delectus voluptates. Autem accusantium maiores nisi illo inventore voluptatibus possimus.</p>', 'Pengumuman', 'uploads/berita/01K2E4XHR3MH5175BKXZA1W59B.jpg', '2025-08-12 02:53:16', '2025-08-12 03:24:25', NULL),
(8, 1, 1, 'Quasi aut occaecati enim amet.', '<p>Deleniti ipsam dolor neque animi animi. Fugiat distinctio maxime voluptatem est quaerat. Qui ad quia incidunt dicta dolorem esse.</p><p>Ducimus dolorem sunt et ut est voluptatem eum autem. Voluptas delectus hic libero explicabo cum qui. Culpa nobis officia quis commodi rerum consequatur.</p><p>Omnis dolor consequatur optio culpa fuga tempora. Aut aspernatur et quia vero. Dolore pariatur aspernatur occaecati corporis facilis distinctio porro.</p><p>Deserunt eaque sed praesentium officia consequatur est. Consequatur dolorem praesentium quam recusandae distinctio maxime error commodi. Dolor labore aliquid accusamus.</p><p>Dolorem molestiae delectus earum voluptates aspernatur quidem ducimus. Quas exercitationem sint nam totam aspernatur labore fugit. Beatae in nihil nesciunt nemo minus velit officia.</p>', 'Infrastruktur', 'uploads/berita/01K2E4XPC4PD9CGQ8Y2ZG0MCR9.jpg', '2025-08-12 02:53:16', '2025-08-12 03:24:29', NULL),
(9, 1, 1, 'Deleniti iste consequatur qui voluptatum ipsam possimus.', '<p>Et omnis deleniti aut neque placeat. Cum amet harum molestiae et est. Quia delectus temporibus fugiat iure id odio.</p><p>Ut eligendi vitae debitis veniam. Numquam et adipisci eum aspernatur debitis earum neque. Quo eum in modi et. Ut rerum quibusdam temporibus corporis id nostrum enim.</p><p>Dignissimos dignissimos autem mollitia ad ab qui voluptate magni. Eum rerum officiis ut voluptatem sequi nam voluptates esse. Neque ad consequatur in excepturi ut.</p><p>Ipsam qui praesentium vel repellendus iste quod. Inventore illo modi asperiores voluptas velit. Quas consectetur possimus provident. Minus non laudantium officia nemo.</p><p>Modi ipsa velit illum deserunt sequi. Labore maiores id modi vel perspiciatis magnam cupiditate ipsum.</p>', 'Umum', 'uploads/berita/01K2E4Y3AX6KSDMQKTS13SJGTM.jpg', '2025-08-12 02:53:16', '2025-08-12 03:24:43', NULL),
(10, 1, 1, 'Facilis accusamus aliquid eveniet optio.', '<p>Non assumenda dolor saepe doloribus minima ut ratione. Eveniet deserunt optio ut cum. Eligendi mollitia quia assumenda et repellendus. Qui rem fugit totam rerum aperiam facere.</p><p>Ut voluptatibus eum temporibus sit. Quis explicabo voluptatem vel cupiditate quia non molestiae. Qui ex voluptatem ut officiis est illo. Cum non quo velit et similique illum recusandae.</p><p>Quod necessitatibus quia architecto ut reiciendis ad eligendi. Quibusdam dolore quod magnam sint. Eveniet consequatur in vel aliquam id.</p><p>Rerum consectetur voluptatem sit fuga impedit. Aut suscipit consequuntur voluptatem in quasi eius facere. Voluptatem qui non molestias harum laborum. Est eum voluptatem magni et aut quos nihil tempore.</p><p>Nostrum fugiat aut esse non non error. Deserunt tempora itaque sit adipisci nihil ab. Ea quo beatae dolor perferendis. Aut alias quod aliquam ea consequuntur et laudantium.</p>', 'Kesehatan', 'uploads/berita/01K2E4YX6CQHWA9EPJ95DZJDDR.jpg', '2025-08-12 02:53:16', '2025-08-12 03:25:09', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('laravel_cache_356a192b7913b04c54574d18c28d46e6395428ab', 'i:2;', 1754969417),
('laravel_cache_356a192b7913b04c54574d18c28d46e6395428ab:timer', 'i:1754969417;', 1754969417),
('laravel_cache_spatie.permission.cache', 'a:3:{s:5:\"alias\";a:4:{s:1:\"a\";s:2:\"id\";s:1:\"b\";s:4:\"name\";s:1:\"c\";s:10:\"guard_name\";s:1:\"r\";s:5:\"roles\";}s:11:\"permissions\";a:36:{i:0;a:4:{s:1:\"a\";i:1;s:1:\"b\";s:12:\"manage users\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:1;a:4:{s:1:\"a\";i:2;s:1:\"b\";s:18:\"manage profil_desa\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:2;a:4:{s:1:\"a\";i:3;s:1:\"b\";s:15:\"manage penduduk\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:3;a:4:{s:1:\"a\";i:4;s:1:\"b\";s:15:\"import penduduk\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:4;a:4:{s:1:\"a\";i:5;s:1:\"b\";s:15:\"export penduduk\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:5;a:4:{s:1:\"a\";i:6;s:1:\"b\";s:16:\"restore penduduk\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:6;a:4:{s:1:\"a\";i:7;s:1:\"b\";s:13:\"manage bansos\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:7;a:4:{s:1:\"a\";i:8;s:1:\"b\";s:14:\"restore bansos\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:8;a:4:{s:1:\"a\";i:9;s:1:\"b\";s:13:\"manage berita\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:9;a:4:{s:1:\"a\";i:10;s:1:\"b\";s:14:\"restore berita\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:10;a:4:{s:1:\"a\";i:11;s:1:\"b\";s:15:\"manage keuangan\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:11;a:4:{s:1:\"a\";i:12;s:1:\"b\";s:16:\"restore keuangan\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:12;a:4:{s:1:\"a\";i:13;s:1:\"b\";s:17:\"manage inventaris\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:13;a:4:{s:1:\"a\";i:14;s:1:\"b\";s:18:\"restore inventaris\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:14;a:4:{s:1:\"a\";i:15;s:1:\"b\";s:16:\"manage pengaduan\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:15;a:4:{s:1:\"a\";i:16;s:1:\"b\";s:17:\"respond pengaduan\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:16;a:4:{s:1:\"a\";i:17;s:1:\"b\";s:16:\"create pengaduan\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:17;a:4:{s:1:\"a\";i:18;s:1:\"b\";s:11:\"manage umkm\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:18;a:4:{s:1:\"a\";i:19;s:1:\"b\";s:12:\"restore umkm\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:19;a:4:{s:1:\"a\";i:20;s:1:\"b\";s:12:\"approve umkm\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:20;a:4:{s:1:\"a\";i:21;s:1:\"b\";s:14:\"manage layanan\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:21;a:4:{s:1:\"a\";i:22;s:1:\"b\";s:15:\"restore layanan\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:22;a:4:{s:1:\"a\";i:23;s:1:\"b\";s:22:\"submit verifikasi data\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:3;i:2;i:4;}}i:23;a:4:{s:1:\"a\";i:24;s:1:\"b\";s:23:\"approve verifikasi data\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:24;a:4:{s:1:\"a\";i:25;s:1:\"b\";s:22:\"manage verifikasi data\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:25;a:4:{s:1:\"a\";i:26;s:1:\"b\";s:10:\"view trash\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:26;a:4:{s:1:\"a\";i:27;s:1:\"b\";s:12:\"force delete\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:27;a:4:{s:1:\"a\";i:28;s:1:\"b\";s:16:\"view own profile\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:28;a:4:{s:1:\"a\";i:29;s:1:\"b\";s:15:\"view own bansos\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:29;a:4:{s:1:\"a\";i:30;s:1:\"b\";s:16:\"apply for bansos\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:30;a:4:{s:1:\"a\";i:31;s:1:\"b\";s:18:\"view own pengaduan\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:31;a:4:{s:1:\"a\";i:32;s:1:\"b\";s:20:\"track layanan status\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:32;a:4:{s:1:\"a\";i:33;s:1:\"b\";s:24:\"view verification status\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:4;}}i:33;a:4:{s:1:\"a\";i:34;s:1:\"b\";s:22:\"edit verification data\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:4;}}i:34;a:4:{s:1:\"a\";i:35;s:1:\"b\";s:19:\"cancel verification\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:4;}}i:35;a:4:{s:1:\"a\";i:36;s:1:\"b\";s:16:\"view profil_desa\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:3;i:2;i:4;}}}s:5:\"roles\";a:4:{i:0;a:3:{s:1:\"a\";i:1;s:1:\"b\";s:11:\"super_admin\";s:1:\"c\";s:3:\"web\";}i:1;a:3:{s:1:\"a\";i:2;s:1:\"b\";s:5:\"admin\";s:1:\"c\";s:3:\"web\";}i:2;a:3:{s:1:\"a\";i:3;s:1:\"b\";s:5:\"warga\";s:1:\"c\";s:3:\"web\";}i:3;a:3:{s:1:\"a\";i:4;s:1:\"b\";s:10:\"unverified\";s:1:\"c\";s:3:\"web\";}}}', 1755055731);

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `exports`
--

CREATE TABLE `exports` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `file_disk` varchar(255) NOT NULL,
  `file_name` varchar(255) DEFAULT NULL,
  `exporter` varchar(255) NOT NULL,
  `processed_rows` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `total_rows` int(10) UNSIGNED NOT NULL,
  `successful_rows` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_import_rows`
--

CREATE TABLE `failed_import_rows` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`data`)),
  `import_id` bigint(20) UNSIGNED NOT NULL,
  `validation_error` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `imports`
--

CREATE TABLE `imports` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `file_name` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `importer` varchar(255) NOT NULL,
  `processed_rows` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `total_rows` int(10) UNSIGNED NOT NULL,
  `successful_rows` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `inventaris`
--

CREATE TABLE `inventaris` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `id_desa` bigint(20) UNSIGNED NOT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `kode_barang` varchar(50) NOT NULL,
  `nama_barang` varchar(255) NOT NULL,
  `kategori` varchar(50) NOT NULL,
  `jumlah` int(11) NOT NULL,
  `kondisi` enum('Baik','Rusak Ringan','Rusak Berat','Hilang') NOT NULL,
  `tanggal_perolehan` date DEFAULT NULL,
  `nominal_harga` bigint(20) NOT NULL DEFAULT 0,
  `sumber_dana` varchar(100) DEFAULT NULL,
  `lokasi` varchar(150) DEFAULT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'Tersedia',
  `keterangan` text DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `inventaris`
--

INSERT INTO `inventaris` (`id`, `id_desa`, `created_by`, `kode_barang`, `nama_barang`, `kategori`, `jumlah`, `kondisi`, `tanggal_perolehan`, `nominal_harga`, `sumber_dana`, `lokasi`, `status`, `keterangan`, `foto`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 1, 'INV001-20250812-0001', 'Becak Motor', 'Elektronik', 9, 'Baik', '2022-03-12', 24106784, 'APBDes', 'Kantor Desa Lantai 2', 'Dipinjam', NULL, NULL, '2025-08-12 02:53:16', '2025-08-12 02:53:16', NULL),
(2, 1, 1, 'INV001-20250812-0002', 'Buku Agenda', 'Elektronik', 10, 'Baik', '2022-04-21', 207223, 'CSR', 'Kantor Desa Lantai 1', 'Tersedia', NULL, NULL, '2025-08-12 02:53:16', '2025-08-12 02:53:16', NULL),
(3, 1, 1, 'INV001-20250812-0003', 'Tikar', 'Elektronik', 18, 'Baik', '2025-02-03', 476145, 'CSR', 'Posyandu', 'Tersedia', 'Qui qui et ex quos facere harum ad.', NULL, '2025-08-12 02:53:16', '2025-08-12 02:53:16', NULL),
(4, 1, 1, 'INV001-20250812-0004', 'Alat Pemadam Api', 'Furnitur', 7, 'Baik', '2023-06-15', 574120, 'Hibah', 'Posko Keamanan', 'Dipinjam', 'Nobis vero possimus dolorem et ut nesciunt sed necessitatibus.', NULL, '2025-08-12 02:53:16', '2025-08-12 02:53:16', NULL),
(5, 1, 1, 'INV001-20250812-0005', 'Mesin Absensi', 'Furnitur', 18, 'Baik', '2024-03-05', 2515633, 'APBD', 'Perpustakaan Desa', 'Tersedia', NULL, NULL, '2025-08-12 02:53:16', '2025-08-12 02:53:16', NULL),
(6, 1, 1, 'INV001-20250812-0006', 'Alat Musik Tradisional', 'Furnitur', 6, 'Baik', '2025-06-15', 891296, 'APBD', 'Posyandu', 'Tersedia', NULL, NULL, '2025-08-12 02:53:16', '2025-08-12 02:53:16', NULL),
(7, 1, 1, 'INV001-20250812-0007', 'Hard Disk External', 'Furnitur', 13, 'Baik', '2021-09-25', 4318625, 'CSR', 'Perpustakaan Desa', 'Dipinjam', NULL, NULL, '2025-08-12 02:53:16', '2025-08-12 02:53:16', NULL),
(8, 1, 1, 'INV001-20250812-0008', 'Sound System', 'Kendaraan', 13, 'Baik', '2024-11-18', 4863002, 'Lainnya', 'Posyandu', 'Tersedia', 'Dignissimos et voluptas mollitia molestiae magni laboriosam ut.', NULL, '2025-08-12 02:53:16', '2025-08-12 02:53:16', NULL),
(9, 1, 1, 'INV001-20250812-0009', 'Papan Pengumuman', 'ATK', 6, 'Baik', '2022-07-07', 1772462, 'Swadaya', 'Aula Desa', 'Tersedia', 'Ut molestiae illo quis qui voluptatem.', NULL, '2025-08-12 02:53:16', '2025-08-12 02:53:16', NULL),
(10, 1, 1, 'INV001-20250812-0010', 'Cangkul', 'ATK', 4, 'Baik', '2023-12-19', 1799429, 'APBD', 'Perpustakaan Desa', 'Dipinjam', 'Iusto quidem quia sequi sit et qui.', NULL, '2025-08-12 02:53:16', '2025-08-12 02:53:16', NULL),
(11, 1, 1, 'INV001-20250812-0011', 'Laptop ASUS', 'Komputer', 20, 'Baik', '2025-01-06', 9638924, 'Swadaya', 'Gudang Desa', 'Tersedia', 'Omnis temporibus maiores ea odit veritatis est.', NULL, '2025-08-12 02:53:16', '2025-08-12 02:53:16', NULL),
(12, 1, 1, 'INV001-20250812-0012', 'Kursi Tamu', 'Komputer', 19, 'Baik', '2024-07-01', 1604638, 'APBN', 'Balai Pertemuan', 'Dipinjam', 'Qui mollitia excepturi quia maxime tenetur velit debitis.', NULL, '2025-08-12 02:53:16', '2025-08-12 02:53:16', NULL),
(13, 1, 1, 'INV001-20250812-0013', 'Tikar', 'Peralatan', 13, 'Baik', '2022-02-12', 488125, 'Swadaya', 'Posko Keamanan', 'Tersedia', 'Sunt est debitis rerum non quo distinctio animi at.', NULL, '2025-08-12 02:53:16', '2025-08-12 02:53:16', NULL),
(14, 1, 1, 'INV001-20250812-0014', 'Terpal Besar', 'Peralatan', 13, 'Baik', '2025-01-09', 213420, 'Hibah', 'Posko Keamanan', 'Dipinjam', NULL, NULL, '2025-08-12 02:53:16', '2025-08-12 02:53:16', NULL),
(15, 1, 1, 'INV001-20250812-0015', 'Meja Kerja', 'Lainnya', 4, 'Baik', '2023-10-08', 325982, 'Swadaya', 'Gudang Desa', 'Tersedia', 'Provident ut et et qui.', NULL, '2025-08-12 02:53:16', '2025-08-12 02:53:16', NULL),
(16, 1, 1, 'INV001-20250812-0016', 'Router WiFi', 'Elektronik', 2, 'Rusak Berat', '2023-03-01', 8101026, 'APBD', 'Aula Desa', 'Dalam Perbaikan', 'Omnis similique voluptatibus nemo nihil minus eligendi.', NULL, '2025-08-12 02:53:16', '2025-08-12 02:53:16', NULL),
(17, 1, 1, 'INV001-20250812-0017', 'Meja Rapat', 'Peralatan', 5, 'Rusak Berat', '2022-10-05', 1512676, 'Swadaya', 'Balai Pertemuan', 'Dalam Perbaikan', 'Dignissimos consectetur iste nesciunt consequatur qui.', NULL, '2025-08-12 02:53:16', '2025-08-12 02:53:16', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `jenis_bansos`
--

CREATE TABLE `jenis_bansos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama_bansos` varchar(100) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `instansi_pemberi` varchar(100) DEFAULT NULL,
  `kategori` varchar(50) NOT NULL,
  `periode` varchar(50) DEFAULT NULL,
  `bentuk_bantuan` varchar(50) DEFAULT NULL,
  `jumlah_per_penerima` decimal(10,2) DEFAULT NULL,
  `satuan` varchar(50) DEFAULT NULL,
  `nominal_standar` bigint(20) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `jenis_bansos`
--

INSERT INTO `jenis_bansos` (`id`, `nama_bansos`, `deskripsi`, `instansi_pemberi`, `kategori`, `periode`, `bentuk_bantuan`, `jumlah_per_penerima`, `satuan`, `nominal_standar`, `is_active`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Bantuan Langsung Tunai (BLT)', 'Bantuan tunai langsung kepada masyarakat miskin dan rentan untuk meningkatkan daya beli dan pemenuhan kebutuhan dasar.', 'Kementerian Sosial', 'Tunai', 'Bulanan', 'uang', NULL, NULL, 300000, 1, '2025-08-12 02:53:16', '2025-08-12 02:53:16', NULL),
(2, 'Program Keluarga Harapan (PKH)', 'Program bantuan bersyarat untuk keluarga miskin dengan ibu hamil, balita, anak sekolah, lansia, atau disabilitas. Bantuan diberikan per 3 bulan.', 'Kementerian Sosial', 'Tunai', 'Triwulan', 'uang', NULL, NULL, 2000000, 1, '2025-08-12 02:53:16', '2025-08-12 02:53:16', NULL),
(3, 'Bantuan Pangan Non-Tunai (BPNT)', 'Bantuan pangan dalam bentuk bahan makanan pokok seperti beras, telur, dan minyak goreng yang diberikan setiap bulan.', 'Kementerian Sosial', 'Pangan', 'Bulanan', 'barang', 10.00, 'kg', 0, 1, '2025-08-12 02:53:16', '2025-08-12 02:53:16', NULL),
(4, 'Sembako untuk Lansia', 'Paket sembako khusus untuk warga lanjut usia yang meliputi bahan pangan bergizi dan suplemen.', 'Pemerintah Desa', 'Sembako', 'Bulanan', 'barang', 1.00, 'paket', 0, 1, '2025-08-12 02:53:16', '2025-08-12 02:53:16', NULL),
(5, 'Program Indonesia Pintar (PIP)', 'Bantuan pendidikan bagi siswa kurang mampu untuk biaya sekolah, seragam, dan perlengkapan belajar.', 'Kementerian Pendidikan', 'Pendidikan', 'Semester', 'uang', NULL, NULL, 750000, 1, '2025-08-12 02:53:16', '2025-08-12 02:53:16', NULL),
(6, 'BPJS PBI (Penerima Bantuan Iuran)', 'Bantuan iuran BPJS Kesehatan untuk masyarakat tidak mampu agar mendapatkan akses layanan kesehatan.', 'BPJS Kesehatan', 'Kesehatan', 'Bulanan', 'jasa', 1.00, 'paket', 0, 1, '2025-08-12 02:53:16', '2025-08-12 02:53:16', NULL),
(7, 'Bantuan Produktif Usaha Mikro (BPUM)', 'Bantuan modal usaha untuk pelaku UMKM yang terdampak pandemi untuk memulai kembali atau mengembangkan usaha.', 'Kementerian UMKM', 'UMKM', 'Sekali', 'bantuan_modal', 1.00, 'paket', 1200000, 1, '2025-08-12 02:53:16', '2025-08-12 02:53:16', NULL),
(8, 'Modal Usaha UMKM', 'Ipsa eos aut cupiditate nesciunt et. Quis sed suscipit ut non fuga sit. Voluptatem sit illo voluptas quam dolore est eum nostrum. Dolores distinctio eaque sequi mollitia enim consequatur eos soluta.', 'Pemerintah Daerah', 'Tunai', 'Semester', 'uang', 90.72, 'paket', 1619561, 0, '2025-08-12 02:53:16', '2025-08-12 02:53:16', NULL),
(9, 'Modal Usaha UMKM', 'Sed necessitatibus nihil enim id tenetur est. Quasi et laborum dolores omnis et. Nostrum enim odit ipsum voluptatem nobis possimus.', 'Lembaga Swadaya Masyarakat', 'Tunai', 'Insidental', 'uang', 9.71, 'unit', 1095441, 1, '2025-08-12 02:53:16', '2025-08-12 02:53:16', NULL),
(10, 'Bantuan Sembako Bencana', 'Officia debitis ipsa eveniet ipsam. Id consequatur quasi aut enim qui. Ullam dolores autem consequatur. Ratione libero placeat corrupti qui molestiae. Sed voluptas ipsa illum.', 'BAZNAS', 'Sembako', 'Insidental', 'barang', 7.07, 'karung', 0, 1, '2025-08-12 02:53:16', '2025-08-12 02:53:16', NULL),
(11, 'Bantuan Kesehatan Masyarakat', 'Accusamus et aut dolorem excepturi enim et. Recusandae in ut aut modi. Impedit est vel tempora error ducimus quae. Incidunt laboriosam est modi consequatur.', 'Lembaga Swadaya Masyarakat', 'Sembako', 'Sekali', 'barang', 11.82, 'kg', 0, 1, '2025-08-12 02:53:16', '2025-08-12 02:53:16', NULL),
(12, 'BPJS PBI', 'Laborum quod a quo delectus saepe eligendi. Error sit numquam minima. Excepturi et occaecati voluptatibus eaque dolores expedita nobis.', 'Dinas Sosial', 'Pendidikan', 'Semester', 'uang', 5.50, 'lembar', 0, 1, '2025-08-12 02:53:16', '2025-08-12 02:53:16', NULL),
(13, 'Program Keluarga Harapan (PKH)', 'Non consectetur dolor eligendi voluptas aut debitis explicabo incidunt. Quis sed incidunt deserunt eius blanditiis eaque rerum. Impedit laborum aut unde dolor esse vitae quisquam.', 'Kementerian Pertanian', 'Tunai', 'Tahunan', 'voucher', 35.40, 'lembar', 0, 1, '2025-08-12 02:53:16', '2025-08-12 02:53:16', NULL),
(14, 'Modal Usaha Keluarga', 'Tempora facere excepturi incidunt voluptas similique. Dolor ipsum occaecati aperiam illo quia. Voluptate saepe veniam voluptatem minima magni. Sed unde dolores voluptatem dicta.', 'Perusahaan (CSR)', 'Lainnya', 'Sekali', 'bantuan_modal', 1.71, 'paket', 0, 1, '2025-08-12 02:53:16', '2025-08-12 02:53:16', NULL),
(15, 'Beasiswa Anak Desa', 'Consequatur sed ex et deleniti totam. Perspiciatis ullam nam molestiae adipisci. Rem quia earum quod doloremque minus perspiciatis inventore. Quas possimus fugit veritatis ea reprehenderit.', 'Pemerintah Desa', 'Pendidikan', 'Triwulan', 'voucher', 55.24, 'unit', 0, 1, '2025-08-12 02:53:16', '2025-08-12 02:53:16', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kartu_keluarga`
--

CREATE TABLE `kartu_keluarga` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `id_desa` bigint(20) UNSIGNED NOT NULL,
  `nomor_kk` varchar(16) NOT NULL,
  `alamat` text NOT NULL,
  `rt_rw` varchar(10) NOT NULL,
  `kepala_keluarga_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `keuangan_desa`
--

CREATE TABLE `keuangan_desa` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `id_desa` bigint(20) UNSIGNED NOT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `jenis` varchar(20) NOT NULL,
  `deskripsi` text NOT NULL,
  `jumlah` bigint(20) NOT NULL,
  `tanggal` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `keuangan_desa`
--

INSERT INTO `keuangan_desa` (`id`, `id_desa`, `created_by`, `jenis`, `deskripsi`, `jumlah`, `tanggal`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 1, 'Pemasukan', 'Sumbangan Pihak Ketiga', 43773389, '2024-12-13', '2025-08-12 02:53:16', '2025-08-12 02:53:16', NULL),
(2, 1, 1, 'Pemasukan', 'Alokasi Dana Desa (ADD)', 24717925, '2025-06-29', '2025-08-12 02:53:16', '2025-08-12 02:53:16', NULL),
(3, 1, 1, 'Pemasukan', 'Bagi Hasil Pajak dan Retribusi', 32391794, '2025-07-29', '2025-08-12 02:53:16', '2025-08-12 02:53:16', NULL),
(4, 1, 1, 'Pemasukan', 'Bantuan Keuangan Kabupaten/Kota', 13262268, '2024-10-18', '2025-08-12 02:53:16', '2025-08-12 02:53:16', NULL),
(5, 1, 1, 'Pemasukan', 'Sumbangan Pihak Ketiga', 35621079, '2025-07-17', '2025-08-12 02:53:16', '2025-08-12 02:53:16', NULL),
(6, 1, 1, 'Pemasukan', 'Sumbangan Pihak Ketiga', 8810781, '2024-11-14', '2025-08-12 02:53:16', '2025-08-12 02:53:16', NULL),
(7, 1, 1, 'Pemasukan', 'Bagi Hasil Pajak dan Retribusi', 1943648, '2025-01-26', '2025-08-12 02:53:16', '2025-08-12 02:53:16', NULL),
(8, 1, 1, 'Pemasukan', 'Alokasi Dana Desa (ADD)', 6683748, '2024-10-30', '2025-08-12 02:53:16', '2025-08-12 02:53:16', NULL),
(9, 1, 1, 'Pengeluaran', 'Perawatan Aset Desa', 5937157, '2025-07-14', '2025-08-12 02:53:16', '2025-08-12 02:53:16', NULL),
(10, 1, 1, 'Pengeluaran', 'Bantuan Sosial', 12933989, '2024-10-23', '2025-08-12 02:53:16', '2025-08-12 02:53:16', NULL),
(11, 1, 1, 'Pengeluaran', 'Bantuan Sosial', 12053705, '2025-02-23', '2025-08-12 02:53:16', '2025-08-12 02:53:16', NULL),
(12, 1, 1, 'Pengeluaran', 'Bantuan Sosial', 20548610, '2025-06-23', '2025-08-12 02:53:16', '2025-08-12 02:53:16', NULL),
(13, 1, 1, 'Pengeluaran', 'Operasional Pemerintah Desa', 27944760, '2024-12-05', '2025-08-12 02:53:16', '2025-08-12 02:53:16', NULL),
(14, 1, 1, 'Pengeluaran', 'Pembinaan Kemasyarakatan', 24310762, '2025-03-24', '2025-08-12 02:53:16', '2025-08-12 02:53:16', NULL),
(15, 1, 1, 'Pengeluaran', 'Pemberdayaan Masyarakat', 1745636, '2024-11-29', '2025-08-12 02:53:16', '2025-08-12 02:53:16', NULL),
(16, 1, 1, 'Pengeluaran', 'Pemberdayaan Masyarakat', 1925134, '2024-12-26', '2025-08-12 02:53:16', '2025-08-12 02:53:16', NULL),
(17, 1, 1, 'Pengeluaran', 'Perawatan Aset Desa', 26674301, '2025-04-03', '2025-08-12 02:53:16', '2025-08-12 02:53:16', NULL),
(18, 1, 1, 'Pengeluaran', 'Belanja Pegawai', 2281886, '2025-03-15', '2025-08-12 02:53:16', '2025-08-12 02:53:16', NULL),
(19, 1, 1, 'Pengeluaran', 'Perawatan Aset Desa', 20441225, '2024-11-26', '2025-08-12 02:53:16', '2025-08-12 02:53:16', NULL),
(20, 1, 1, 'Pengeluaran', 'Perawatan Aset Desa', 6166217, '2025-07-31', '2025-08-12 02:53:16', '2025-08-12 02:53:16', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `layanan_desa`
--

CREATE TABLE `layanan_desa` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `id_desa` bigint(20) UNSIGNED NOT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `kategori` varchar(50) NOT NULL,
  `nama_layanan` varchar(100) NOT NULL,
  `deskripsi` text NOT NULL,
  `biaya` bigint(20) NOT NULL DEFAULT 0,
  `lokasi_layanan` varchar(150) DEFAULT NULL,
  `jadwal_pelayanan` text DEFAULT NULL,
  `kontak_layanan` varchar(100) DEFAULT NULL,
  `persyaratan` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`persyaratan`)),
  `prosedur` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`prosedur`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `layanan_desa`
--

INSERT INTO `layanan_desa` (`id`, `id_desa`, `created_by`, `kategori`, `nama_layanan`, `deskripsi`, `biaya`, `lokasi_layanan`, `jadwal_pelayanan`, `kontak_layanan`, `persyaratan`, `prosedur`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 1, 'Surat', 'Taman Bacaan Desa', '<p>Et ut distinctio quae voluptatem aliquid enim qui. Aut eaque et dolores culpa fugiat consequatur. Quos et natus beatae occaecati id. Libero nobis rerum et laborum repellat nesciunt.</p><p>Est suscipit rerum repudiandae rerum. Nam nemo dolores delectus est. Voluptatum sint in dolor.</p><p>Sed sequi eveniet distinctio dolor. Temporibus ullam nihil officia iure animi corrupti ut. Sed ut iste et velit.</p>', 0, 'Aula Desa', 'Senin, Rabu, Jumat: 09.00-14.00', '(828) 418-6126 (Bu Siti)', '[{\"dokumen\":\"Kartu Tanda Penduduk (KTP)\",\"keterangan\":\"Asli dan fotokopi\"},{\"dokumen\":\"Kartu Keluarga (KK)\",\"keterangan\":\"Asli dan fotokopi\"},{\"dokumen\":\"Surat Keterangan Tidak Mampu (jika diperlukan)\",\"keterangan\":\"Dari desa\\/kelurahan\"},{\"dokumen\":\"Rapor Terakhir\",\"keterangan\":\"Asli dan fotokopi\"},{\"dokumen\":\"Surat Keterangan Aktif Sekolah\",\"keterangan\":\"Dari sekolah terkait\"}]', '[{\"langkah\":\"Pendaftaran di Kantor Desa\",\"keterangan\":\"Mengisi formulir dan menyerahkan berkas\"},{\"langkah\":\"Verifikasi Berkas\",\"keterangan\":\"Petugas akan memeriksa kelengkapan berkas\"},{\"langkah\":\"Pembayaran Biaya Administrasi (jika ada)\",\"keterangan\":\"Sesuai ketentuan yang berlaku\"},{\"langkah\":\"Seleksi Penerima\",\"keterangan\":\"Oleh tim seleksi desa\"},{\"langkah\":\"Pengumuman Hasil\",\"keterangan\":\"Di papan pengumuman desa dan website\"},{\"langkah\":\"Pelaksanaan Program\",\"keterangan\":\"Sesuai jadwal yang ditentukan\"},{\"langkah\":\"Selesai\",\"keterangan\":\"Layanan telah diberikan\"}]', '2025-08-12 02:53:16', '2025-08-12 02:53:16', NULL),
(2, 1, 1, 'Surat', 'Sosialisasi BPJS', '<p>Voluptas odit beatae soluta. Reprehenderit aperiam at qui illum quaerat asperiores esse. Quasi reprehenderit harum voluptas dolores itaque aut omnis. Eveniet delectus veritatis sunt quo aut aspernatur. Modi animi omnis aut dolores nemo ut dignissimos.</p><p>Minima incidunt aspernatur dignissimos labore quis quis doloremque. Dolorem et architecto dolores sit iure. Laudantium et esse quia dolorum sunt corporis excepturi. Est in asperiores fugiat. Sit dolor aspernatur cupiditate qui quam esse voluptas earum.</p><p>Vel ut quidem qui perspiciatis recusandae. Aperiam ipsum et eos. Asperiores rerum minus et earum autem sit in.</p>', 6563, 'Puskesmas Desa', 'Senin, Rabu, Jumat: 09.00-14.00', '480.704.1803 (Pak Budi)', '[{\"dokumen\":\"Kartu Tanda Penduduk (KTP)\",\"keterangan\":\"Asli dan fotokopi\"},{\"dokumen\":\"Kartu Keluarga (KK)\",\"keterangan\":\"Asli dan fotokopi\"},{\"dokumen\":\"Kartu BPJS (jika ada)\",\"keterangan\":\"Asli dan fotokopi\"},{\"dokumen\":\"Surat Rujukan (jika ada)\",\"keterangan\":\"Dari puskesmas atau dokter\"}]', '[{\"langkah\":\"Pendaftaran di Kantor Desa\",\"keterangan\":\"Mengisi formulir dan menyerahkan berkas\"},{\"langkah\":\"Verifikasi Berkas\",\"keterangan\":\"Petugas akan memeriksa kelengkapan berkas\"},{\"langkah\":\"Pembayaran Biaya Administrasi (jika ada)\",\"keterangan\":\"Sesuai ketentuan yang berlaku\"},{\"langkah\":\"Pemeriksaan Awal\",\"keterangan\":\"Oleh petugas kesehatan desa\"},{\"langkah\":\"Pelaksanaan Layanan\",\"keterangan\":\"Sesuai jadwal atau perjanjian\"},{\"langkah\":\"Evaluasi dan Tindak Lanjut\",\"keterangan\":\"Jika diperlukan\"},{\"langkah\":\"Selesai\",\"keterangan\":\"Layanan telah diberikan\"}]', '2025-08-12 02:53:16', '2025-08-12 02:53:16', NULL),
(3, 1, 1, 'Surat', 'Penyaluran Zakat', '<p>Error ipsum molestiae tenetur nulla quam. Corporis mollitia consequatur harum quam itaque voluptatibus et repudiandae.</p><p>Deleniti voluptate numquam iste asperiores laboriosam laborum quas asperiores. Tempore quia ipsum magnam in. Voluptatem quaerat rem ipsam a. Facere molestiae nostrum recusandae ut.</p><p>Quo cumque a labore maiores aut. Fugit nisi architecto odit assumenda ea sequi. Aut totam aut perspiciatis at cupiditate est.</p>', 0, 'Balai Desa', 'Senin, Rabu, Jumat: 09.00-14.00', '+1 (423) 250-5940 (Kantor Desa)', '[{\"dokumen\":\"Kartu Tanda Penduduk (KTP)\",\"keterangan\":\"Asli dan fotokopi\"},{\"dokumen\":\"Kartu Keluarga (KK)\",\"keterangan\":\"Asli dan fotokopi\"},{\"dokumen\":\"Surat Keterangan Tidak Mampu\",\"keterangan\":\"Dari desa\\/kelurahan\"},{\"dokumen\":\"Dokumen Pendukung (sesuai jenis bantuan)\",\"keterangan\":\"Asli dan fotokopi\"}]', '[{\"langkah\":\"Pendaftaran di Kantor Desa\",\"keterangan\":\"Mengisi formulir dan menyerahkan berkas\"},{\"langkah\":\"Verifikasi Berkas\",\"keterangan\":\"Petugas akan memeriksa kelengkapan berkas\"},{\"langkah\":\"Pembayaran Biaya Administrasi (jika ada)\",\"keterangan\":\"Sesuai ketentuan yang berlaku\"},{\"langkah\":\"Survei Kebutuhan\",\"keterangan\":\"Petugas akan melakukan kunjungan\"},{\"langkah\":\"Validasi Data\",\"keterangan\":\"Pengecekan kebenaran informasi\"},{\"langkah\":\"Penyaluran Bantuan\",\"keterangan\":\"Sesuai mekanisme yang berlaku\"},{\"langkah\":\"Selesai\",\"keterangan\":\"Layanan telah diberikan\"}]', '2025-08-12 02:53:16', '2025-08-12 02:53:16', NULL),
(4, 1, 1, 'Kesehatan', 'Renovasi Balai Desa', '<p>Quasi cupiditate et exercitationem optio non. Fuga ipsa dolorem amet saepe enim tenetur. Omnis maxime voluptatibus eum voluptatum vero omnis. Facere ipsa quia in aut necessitatibus qui.</p><p>Iste voluptates delectus quo omnis. Ducimus earum explicabo et ipsum quas. Quisquam eius et et et officiis.</p><p>In porro ad doloribus nihil quos culpa eum. Eum molestiae molestiae nisi rerum. Ipsam maiores voluptate voluptatibus natus repellat rerum occaecati.</p>', 1830945, 'Balai Desa', 'Selasa dan Kamis: 09.00-15.00', '435-400-3756 (Kantor Desa)', '[{\"dokumen\":\"Kartu Tanda Penduduk (KTP)\",\"keterangan\":\"Asli dan fotokopi\"},{\"dokumen\":\"Kartu Keluarga (KK)\",\"keterangan\":\"Asli dan fotokopi\"},{\"dokumen\":\"Proposal Kegiatan\",\"keterangan\":\"Mencakup rencana dan anggaran\"},{\"dokumen\":\"Dokumentasi Lokasi\",\"keterangan\":\"Foto lokasi yang akan dibangun\\/diperbaiki\"},{\"dokumen\":\"Surat Pernyataan Warga\",\"keterangan\":\"Ditandatangani perwakilan warga\"}]', '[{\"langkah\":\"Pendaftaran di Kantor Desa\",\"keterangan\":\"Mengisi formulir dan menyerahkan berkas\"},{\"langkah\":\"Verifikasi Berkas\",\"keterangan\":\"Petugas akan memeriksa kelengkapan berkas\"},{\"langkah\":\"Pembayaran Biaya Administrasi (jika ada)\",\"keterangan\":\"Sesuai ketentuan yang berlaku\"},{\"langkah\":\"Survei Lokasi\",\"keterangan\":\"Oleh tim teknis desa\"},{\"langkah\":\"Musyawarah Perencanaan\",\"keterangan\":\"Bersama warga dan perangkat desa\"},{\"langkah\":\"Pelaksanaan Pembangunan\",\"keterangan\":\"Sesuai jadwal yang disepakati\"},{\"langkah\":\"Monitoring dan Evaluasi\",\"keterangan\":\"Oleh tim pengawas desa\"},{\"langkah\":\"Selesai\",\"keterangan\":\"Layanan telah diberikan\"}]', '2025-08-12 02:53:16', '2025-08-12 02:53:16', NULL),
(5, 1, 1, 'Kesehatan', 'Posyandu Lansia', '<p>Molestiae et quidem voluptatem aut sed. Expedita repudiandae voluptatem nobis corporis sed quia aut. Maiores quibusdam cum inventore in. Sit modi dolorum ad quia cumque.</p><p>Debitis qui hic molestias ut itaque inventore. Laudantium sit facilis et rerum at atque. Ut eius a error aut est.</p><p>Rem rem deserunt sit nostrum omnis. Dicta et cum iure consequatur tempora. Cumque molestias cumque officia in consequuntur placeat velit et. Voluptatem ut enim in voluptatem vero.</p>', 0, 'Posyandu', 'Senin-Sabtu: 08.00-12.00', '+1-574-314-6688 (Kantor Desa)', '[{\"dokumen\":\"Kartu Tanda Penduduk (KTP)\",\"keterangan\":\"Asli dan fotokopi\"},{\"dokumen\":\"Kartu Keluarga (KK)\",\"keterangan\":\"Asli dan fotokopi\"},{\"dokumen\":\"Surat Rujukan (jika ada)\",\"keterangan\":\"Dari puskesmas atau dokter\"},{\"dokumen\":\"Kartu BPJS (jika ada)\",\"keterangan\":\"Asli dan fotokopi\"}]', '[{\"langkah\":\"Pendaftaran di Kantor Desa\",\"keterangan\":\"Mengisi formulir dan menyerahkan berkas\"},{\"langkah\":\"Verifikasi Berkas\",\"keterangan\":\"Petugas akan memeriksa kelengkapan berkas\"},{\"langkah\":\"Pembayaran Biaya Administrasi (jika ada)\",\"keterangan\":\"Sesuai ketentuan yang berlaku\"},{\"langkah\":\"Pemeriksaan Awal\",\"keterangan\":\"Oleh petugas kesehatan desa\"},{\"langkah\":\"Pelaksanaan Layanan\",\"keterangan\":\"Sesuai jadwal atau perjanjian\"},{\"langkah\":\"Evaluasi dan Tindak Lanjut\",\"keterangan\":\"Jika diperlukan\"},{\"langkah\":\"Selesai\",\"keterangan\":\"Layanan telah diberikan\"}]', '2025-08-12 02:53:16', '2025-08-12 02:53:16', NULL),
(6, 1, 1, 'Pendidikan', 'Surat Keterangan Kematian', '<p>Facilis recusandae dignissimos dolore soluta. Eum vel earum quasi minima odio libero quos nemo. Eveniet sunt ut officiis.</p><p>Dolore fuga consectetur error odio enim. Quia provident natus reiciendis recusandae dolorem.</p><p>Explicabo qui iusto hic consequatur omnis. Impedit perspiciatis earum corrupti eos. Occaecati aspernatur cum aspernatur odio explicabo provident est. Assumenda et id culpa sit recusandae.</p>', 16364, 'Balai Desa', 'Selasa dan Kamis: 09.00-15.00', '+1 (480) 733-0988 (Pak Hendra)', '[{\"dokumen\":\"Kartu Tanda Penduduk (KTP)\",\"keterangan\":\"Asli dan fotokopi\"},{\"dokumen\":\"Kartu Keluarga (KK)\",\"keterangan\":\"Asli dan fotokopi\"},{\"dokumen\":\"Pas Foto 3\\u00d74\",\"keterangan\":\"2 lembar (latar belakang merah)\"},{\"dokumen\":\"Surat Pengantar RT\\/RW\",\"keterangan\":\"Asli\"}]', '[{\"langkah\":\"Pendaftaran di Kantor Desa\",\"keterangan\":\"Mengisi formulir dan menyerahkan berkas\"},{\"langkah\":\"Verifikasi Berkas\",\"keterangan\":\"Petugas akan memeriksa kelengkapan berkas\"},{\"langkah\":\"Pembayaran Biaya Administrasi (jika ada)\",\"keterangan\":\"Sesuai ketentuan yang berlaku\"},{\"langkah\":\"Penerbitan Surat\",\"keterangan\":\"Diproses oleh petugas desa\"},{\"langkah\":\"Penandatanganan oleh Kepala Desa\",\"keterangan\":\"Setelah verifikasi dan pembayaran\"},{\"langkah\":\"Pengambilan Surat\",\"keterangan\":\"Sesuai jadwal yang ditetapkan\"},{\"langkah\":\"Selesai\",\"keterangan\":\"Layanan telah diberikan\"}]', '2025-08-12 02:53:16', '2025-08-12 02:53:16', NULL),
(7, 1, 1, 'Sosial', 'Program KB', '<p>Unde est eaque ex autem sint eaque omnis vitae. Qui qui libero id voluptatem dignissimos quia molestiae. Dolore impedit non optio numquam accusantium autem.</p><p>Ratione omnis eaque repellendus veritatis. Veritatis ut blanditiis dolores eum. Fugit itaque quis magnam impedit vitae.</p><p>Soluta nam repellendus quia minima qui mollitia. Et porro nam nobis et deleniti reprehenderit debitis. Est ipsam mollitia nulla similique ducimus maxime excepturi.</p>', 0, 'Balai Kesehatan Desa', 'Selasa dan Kamis: 09.00-15.00', '1-989-407-8206 (Admin Layanan)', '[{\"dokumen\":\"Kartu Tanda Penduduk (KTP)\",\"keterangan\":\"Asli dan fotokopi\"},{\"dokumen\":\"Kartu Keluarga (KK)\",\"keterangan\":\"Asli dan fotokopi\"},{\"dokumen\":\"Surat Rujukan (jika ada)\",\"keterangan\":\"Dari puskesmas atau dokter\"},{\"dokumen\":\"Kartu BPJS (jika ada)\",\"keterangan\":\"Asli dan fotokopi\"}]', '[{\"langkah\":\"Pendaftaran di Kantor Desa\",\"keterangan\":\"Mengisi formulir dan menyerahkan berkas\"},{\"langkah\":\"Verifikasi Berkas\",\"keterangan\":\"Petugas akan memeriksa kelengkapan berkas\"},{\"langkah\":\"Pembayaran Biaya Administrasi (jika ada)\",\"keterangan\":\"Sesuai ketentuan yang berlaku\"},{\"langkah\":\"Pemeriksaan Awal\",\"keterangan\":\"Oleh petugas kesehatan desa\"},{\"langkah\":\"Pelaksanaan Layanan\",\"keterangan\":\"Sesuai jadwal atau perjanjian\"},{\"langkah\":\"Evaluasi dan Tindak Lanjut\",\"keterangan\":\"Jika diperlukan\"},{\"langkah\":\"Selesai\",\"keterangan\":\"Layanan telah diberikan\"}]', '2025-08-12 02:53:16', '2025-08-12 02:53:16', NULL),
(8, 1, 1, 'Infrastruktur', 'Imunisasi Anak', '<p>Qui magnam molestias et et et aliquam. Nihil reprehenderit ipsa consectetur error officia provident repudiandae. Repellendus accusantium est omnis et nesciunt velit.</p><p>Consequatur et quis sapiente sit ipsa hic animi. Eos voluptatem consectetur dolorum temporibus esse. Impedit perspiciatis vel eos aperiam cupiditate mollitia. Perferendis accusamus voluptatem voluptates nihil dolores sed vel quia.</p><p>Distinctio autem assumenda necessitatibus. Quisquam culpa ullam et quis laboriosam id esse. Reiciendis eius odio nisi maiores quisquam.</p>', 0, 'Puskesmas Desa', 'Senin-Jumat: 08.00-15.00', '(915) 843-3540 (Kantor Desa)', '[{\"dokumen\":\"Kartu Tanda Penduduk (KTP)\",\"keterangan\":\"Asli dan fotokopi\"},{\"dokumen\":\"Kartu Keluarga (KK)\",\"keterangan\":\"Asli dan fotokopi\"},{\"dokumen\":\"Kartu Vaksin (jika diperlukan)\",\"keterangan\":\"Asli dan fotokopi\"},{\"dokumen\":\"Surat Rujukan (jika ada)\",\"keterangan\":\"Dari puskesmas atau dokter\"}]', '[{\"langkah\":\"Pendaftaran di Kantor Desa\",\"keterangan\":\"Mengisi formulir dan menyerahkan berkas\"},{\"langkah\":\"Verifikasi Berkas\",\"keterangan\":\"Petugas akan memeriksa kelengkapan berkas\"},{\"langkah\":\"Pembayaran Biaya Administrasi (jika ada)\",\"keterangan\":\"Sesuai ketentuan yang berlaku\"},{\"langkah\":\"Pemeriksaan Awal\",\"keterangan\":\"Oleh petugas kesehatan desa\"},{\"langkah\":\"Pelaksanaan Layanan\",\"keterangan\":\"Sesuai jadwal atau perjanjian\"},{\"langkah\":\"Evaluasi dan Tindak Lanjut\",\"keterangan\":\"Jika diperlukan\"},{\"langkah\":\"Selesai\",\"keterangan\":\"Layanan telah diberikan\"}]', '2025-08-12 02:53:16', '2025-08-12 02:53:16', NULL),
(9, 1, 1, 'Surat', 'Legalisasi Dokumen', '<p>Layanan pengesahan dokumen (legalisasi) untuk keperluan administrasi. Layanan ini tidak dipungut biaya.</p>', 0, 'Aula Desa', 'Selasa dan Kamis: 09.00-15.00', '+1-949-382-1040 (Kantor Desa)', '[{\"dokumen\":\"Kartu Tanda Penduduk (KTP)\",\"keterangan\":\"Asli dan fotokopi\"},{\"dokumen\":\"Surat Pengantar RT\\/RW\",\"keterangan\":\"Asli\"}]', '[{\"langkah\":\"Pendaftaran di Kantor Desa\",\"keterangan\":\"Mengisi formulir dan menyerahkan berkas\"},{\"langkah\":\"Verifikasi Berkas\",\"keterangan\":\"Petugas akan memeriksa kelengkapan berkas\"},{\"langkah\":\"Pembayaran Biaya Administrasi (jika ada)\",\"keterangan\":\"Sesuai ketentuan yang berlaku\"},{\"langkah\":\"Seleksi Penerima\",\"keterangan\":\"Oleh tim seleksi desa\"},{\"langkah\":\"Pengumuman Hasil\",\"keterangan\":\"Di papan pengumuman desa dan website\"},{\"langkah\":\"Pelaksanaan Program\",\"keterangan\":\"Sesuai jadwal yang ditentukan\"},{\"langkah\":\"Selesai\",\"keterangan\":\"Layanan telah diberikan\"}]', '2025-08-12 02:53:16', '2025-08-12 02:53:16', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_03_13_073808_create_permission_tables', 1),
(5, '2025_03_13_073918_create_personal_access_tokens_table', 1),
(6, '2025_03_13_124139_create_penduduks_table', 1),
(7, '2025_03_13_124145_create_jenis_bansos_table', 1),
(8, '2025_03_13_124148_create_bansos_table', 1),
(9, '2025_03_13_124150_create_bansos_history_table', 1),
(10, '2025_03_13_124151_create_layanan_desas_table', 1),
(11, '2025_03_13_124154_create_beritas_table', 1),
(12, '2025_03_13_124158_create_keuangan_desas_table', 1),
(13, '2025_03_13_124201_create_inventaris_table', 1),
(14, '2025_03_13_124204_create_pengaduans_table', 1),
(15, '2025_03_13_124211_create_umkms_table', 1),
(16, '2025_03_13_124313_create_profil_desas_table', 1),
(17, '2025_03_13_151729_create_verifikasi_penduduks_table', 1),
(18, '2025_03_14_023024_create_kartu_keluargas_table', 1),
(19, '2025_03_18_create_batas_wilayah_potensi_table', 1),
(20, '2025_04_30_193013_create_notifications_table', 1),
(21, '2025_04_30_201706_create_imports_table', 1),
(22, '2025_04_30_201707_create_exports_table', 1),
(23, '2025_04_30_201708_create_failed_import_rows_table', 1),
(24, 'yyyy_mm_dd_create_struktur_pemerintahan_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\User', 1),
(2, 'App\\Models\\User', 2),
(4, 'App\\Models\\User', 3);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` char(36) NOT NULL,
  `type` varchar(255) NOT NULL,
  `notifiable_type` varchar(255) NOT NULL,
  `notifiable_id` bigint(20) UNSIGNED NOT NULL,
  `data` text NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `penduduk`
--

CREATE TABLE `penduduk` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `id_desa` bigint(20) UNSIGNED NOT NULL,
  `nik` varchar(16) NOT NULL,
  `kk` varchar(16) NOT NULL,
  `kepala_keluarga_id` bigint(20) UNSIGNED DEFAULT NULL,
  `nama` varchar(100) NOT NULL,
  `alamat` text NOT NULL,
  `rt_rw` varchar(10) NOT NULL,
  `desa_kelurahan` varchar(100) DEFAULT NULL,
  `kecamatan` varchar(100) DEFAULT NULL,
  `kabupaten` varchar(100) DEFAULT NULL,
  `tempat_lahir` varchar(255) DEFAULT NULL,
  `tanggal_lahir` date NOT NULL,
  `jenis_kelamin` varchar(255) DEFAULT NULL,
  `agama` varchar(255) DEFAULT NULL,
  `status_perkawinan` varchar(255) DEFAULT NULL,
  `kepala_keluarga` tinyint(1) NOT NULL DEFAULT 0,
  `pekerjaan` varchar(100) DEFAULT NULL,
  `pendidikan` varchar(100) DEFAULT NULL,
  `no_hp` varchar(20) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `golongan_darah` varchar(5) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `penduduk`
--

INSERT INTO `penduduk` (`id`, `id_desa`, `nik`, `kk`, `kepala_keluarga_id`, `nama`, `alamat`, `rt_rw`, `desa_kelurahan`, `kecamatan`, `kabupaten`, `tempat_lahir`, `tanggal_lahir`, `jenis_kelamin`, `agama`, `status_perkawinan`, `kepala_keluarga`, `pekerjaan`, `pendidikan`, `no_hp`, `email`, `golongan_darah`, `created_at`, `updated_at`, `deleted_at`, `user_id`) VALUES
(1, 1, '1521781811571467', '9307464804118692', 1, 'Mustafa Cormier', '98539 Robin Extensions Suite 187', '016/004', 'Desa Sariharjo', 'Kecamatan Krangkeng', 'Kabupaten Indramayu', 'North Wilfredborough', '2004-06-04', 'L', 'Kristen', 'Cerai Hidup', 1, 'PNS', 'SMP', '081949721322', NULL, 'O', '2025-08-12 02:53:15', '2025-08-12 02:53:15', NULL, NULL),
(2, 1, '5228400802100724', '9307464804118692', 1, 'Ramona Kerluke', '6410 Patricia Coves', '009/008', 'Desa Sariharjo', 'Kecamatan Krangkeng', 'Kabupaten Indramayu', 'New Merritt', '1994-03-15', 'P', 'Katolik', 'Kawin', 0, 'Wiraswasta', 'SMA', NULL, 'amalia.gunarto@example.net', 'A', '2025-08-12 02:53:15', '2025-08-12 02:53:15', NULL, NULL),
(3, 1, '8014382109707306', '9307464804118692', 1, 'Ed D\'Amore', '129 Doyle Island Suite 352', '008/006', 'Desa Sariharjo', 'Kecamatan Krangkeng', 'Kabupaten Indramayu', 'Friesenburgh', '1990-07-03', 'L', 'Kristen', 'Kawin', 0, 'Buruh', 'D1', NULL, NULL, '-', '2025-08-12 02:53:15', '2025-08-12 02:53:15', NULL, NULL),
(4, 1, '8766411104434511', '9307464804118692', 1, 'Erika Williamson', '2794 West Branch', '002/010', 'Desa Sariharjo', 'Kecamatan Krangkeng', 'Kabupaten Indramayu', 'Port Charity', '2009-06-07', 'P', 'Islam', 'Belum Kawin', 0, 'Dokter', 'S2', NULL, 'latupono.bagya@example.net', '-', '2025-08-12 02:53:15', '2025-08-12 02:53:15', NULL, NULL),
(5, 1, '5453352206386357', '9307464804118692', 1, 'Fletcher Bayer', '734 Kennedy Locks', '002/004', 'Desa Sariharjo', 'Kecamatan Krangkeng', 'Kabupaten Indramayu', 'Demariofurt', '1956-11-30', 'L', 'Katolik', 'Belum Kawin', 0, 'PNS', 'D3', NULL, NULL, 'B', '2025-08-12 02:53:15', '2025-08-12 02:53:15', NULL, NULL),
(6, 1, '7274552002578053', '8027938621591356', 6, 'Brad Baumbach', '68535 Hand Hills Apt. 017', '006/009', 'Desa Sariharjo', 'Kecamatan Krangkeng', 'Kabupaten Indramayu', 'Schultzbury', '1987-07-23', 'L', 'Hindu', 'Cerai Mati', 1, 'PNS', 'S2', '083701730093', 'lanang37@example.com', '-', '2025-08-12 02:53:15', '2025-08-12 02:53:15', NULL, NULL),
(7, 1, '7408072609071635', '8027938621591356', 6, 'Daphnee Morar', '764 Gerard Cliff Apt. 939', '013/003', 'Desa Sariharjo', 'Kecamatan Krangkeng', 'Kabupaten Indramayu', 'Connmouth', '1970-08-03', 'P', 'Katolik', 'Belum Kawin', 0, 'Wiraswasta', 'D1', '082898824531', NULL, 'AB', '2025-08-12 02:53:15', '2025-08-12 02:53:15', NULL, NULL),
(8, 1, '7721352005815956', '8027938621591356', 6, 'Naomie Klein', '242 Coleman Canyon', '002/002', 'Desa Sariharjo', 'Kecamatan Krangkeng', 'Kabupaten Indramayu', 'Mafaldaborough', '1990-09-05', 'P', 'Katolik', 'Cerai Mati', 0, 'Petani', 'S1', '085579552818', NULL, 'B', '2025-08-12 02:53:15', '2025-08-12 02:53:15', NULL, NULL),
(9, 1, '1212822706497398', '8027938621591356', 6, 'Kaleb Volkman', '6768 Liza Light', '002/008', 'Desa Sariharjo', 'Kecamatan Krangkeng', 'Kabupaten Indramayu', 'Siennaburgh', '1997-03-16', 'L', 'Buddha', 'Cerai Hidup', 0, 'Buruh', 'D4', NULL, NULL, 'AB', '2025-08-12 02:53:15', '2025-08-12 02:53:15', NULL, NULL),
(10, 1, '4889061707005917', '8027938621591356', 6, 'Avery Christiansen', '6395 Braun Land', '020/001', 'Desa Sariharjo', 'Kecamatan Krangkeng', 'Kabupaten Indramayu', 'Jessycafurt', '1959-04-01', 'L', 'Konghucu', 'Cerai Mati', 0, 'Dokter', 'SMA', NULL, NULL, 'A', '2025-08-12 02:53:15', '2025-08-12 02:53:15', NULL, NULL),
(11, 1, '4786142602728624', '8027938621591356', 6, 'Estella Greenfelder', '4507 Magnolia Camp Apt. 639', '011/006', 'Desa Sariharjo', 'Kecamatan Krangkeng', 'Kabupaten Indramayu', 'Jenkinshaven', '1983-05-16', 'P', 'Katolik', 'Belum Kawin', 0, 'Wiraswasta', 'SMP', '082595352508', NULL, 'O', '2025-08-12 02:53:15', '2025-08-12 02:53:15', NULL, NULL),
(12, 1, '5212090709758994', '2320632580597954', 12, 'Malvina Dooley', '61479 Jamal Curve Suite 182', '011/004', 'Desa Sariharjo', 'Kecamatan Krangkeng', 'Kabupaten Indramayu', 'Lake Estatown', '1995-05-22', 'P', 'Islam', 'Cerai Hidup', 1, 'Petani', 'D4', '089305939573', 'artanto92@example.com', 'O', '2025-08-12 02:53:15', '2025-08-12 02:53:15', NULL, NULL),
(13, 1, '5622731906935006', '2320632580597954', 12, 'Carey Maggio', '977 Wiegand Coves', '001/007', 'Desa Sariharjo', 'Kecamatan Krangkeng', 'Kabupaten Indramayu', 'Littleview', '1953-10-21', 'L', 'Islam', 'Cerai Mati', 0, 'Karyawan Swasta', 'S3', '083192475164', NULL, '-', '2025-08-12 02:53:15', '2025-08-12 02:53:15', NULL, NULL),
(14, 1, '1431502507208637', '2320632580597954', 12, 'Deshaun Stehr', '7362 Kay Street Suite 206', '001/003', 'Desa Sariharjo', 'Kecamatan Krangkeng', 'Kabupaten Indramayu', 'Bayleefurt', '2006-11-28', 'L', 'Katolik', 'Cerai Hidup', 0, 'Nelayan', 'S2', '089894921392', 'icha.sitompul@example.org', '-', '2025-08-12 02:53:15', '2025-08-12 02:53:15', NULL, NULL),
(15, 1, '2064501601836032', '2320632580597954', 12, 'Laisha Gutmann', '82657 Wiza Crescent', '017/005', 'Desa Sariharjo', 'Kecamatan Krangkeng', 'Kabupaten Indramayu', 'East Bernita', '2011-02-05', 'P', 'Hindu', 'Cerai Hidup', 0, 'PNS', 'SMP', NULL, NULL, 'AB', '2025-08-12 02:53:15', '2025-08-12 02:53:15', NULL, NULL),
(16, 1, '6570910801438284', '2320632580597954', 12, 'Seth Price', '4447 Gladys Mill Apt. 630', '010/006', 'Desa Sariharjo', 'Kecamatan Krangkeng', 'Kabupaten Indramayu', 'Nicohaven', '1960-06-01', 'L', 'Konghucu', 'Kawin', 0, 'Karyawan Swasta', 'D1', NULL, 'ilsa16@example.com', 'A', '2025-08-12 02:53:15', '2025-08-12 02:53:15', NULL, NULL),
(17, 1, '8523061308163094', '2320632580597954', 12, 'Dayna Stark', '542 Kaia Spurs Apt. 202', '011/005', 'Desa Sariharjo', 'Kecamatan Krangkeng', 'Kabupaten Indramayu', 'East Hertaview', '2001-11-23', 'P', 'Buddha', 'Kawin', 0, 'PNS', 'SMP', NULL, NULL, 'B', '2025-08-12 02:53:15', '2025-08-12 02:53:15', NULL, NULL),
(18, 1, '5832982209203961', '8186523454033842', 18, 'Neva Klein', '8653 Brown Mountain', '010/003', 'Desa Sariharjo', 'Kecamatan Krangkeng', 'Kabupaten Indramayu', 'Considineview', '1996-10-09', 'P', 'Katolik', 'Kawin', 1, 'Dokter', 'S1', '082416322670', 'mutia69@example.org', '-', '2025-08-12 02:53:15', '2025-08-12 02:53:15', NULL, NULL),
(19, 1, '5038092612943075', '8186523454033842', 18, 'Maverick Rath', '30586 Kristoffer Streets Apt. 598', '020/003', 'Desa Sariharjo', 'Kecamatan Krangkeng', 'Kabupaten Indramayu', 'North D\'angeloland', '1981-02-09', 'L', 'Konghucu', 'Cerai Mati', 0, 'Dokter', 'D4', '082821780488', NULL, 'O', '2025-08-12 02:53:15', '2025-08-12 02:53:15', NULL, NULL),
(20, 1, '3136921408847230', '8186523454033842', 18, 'Ubaldo Bauch', '9292 Marks Drive', '004/008', 'Desa Sariharjo', 'Kecamatan Krangkeng', 'Kabupaten Indramayu', 'Lake Duncanport', '1972-01-12', 'L', 'Hindu', 'Cerai Hidup', 0, 'Pedagang', 'SMA', NULL, NULL, 'O', '2025-08-12 02:53:15', '2025-08-12 02:53:15', NULL, NULL),
(21, 1, '2138371210918110', '7282604082304835', 21, 'Noel Schinner', '741 Lynch Trail', '019/008', 'Desa Sariharjo', 'Kecamatan Krangkeng', 'Kabupaten Indramayu', 'Lake Francisca', '2003-06-17', 'L', 'Katolik', 'Belum Kawin', 1, 'Guru', 'D2', '084009042526', 'nurul35@example.net', 'O', '2025-08-12 02:53:15', '2025-08-12 02:53:15', NULL, NULL),
(22, 1, '3569570911457166', '7282604082304835', 21, 'Hermann Jacobs', '916 Beier Trafficway', '005/004', 'Desa Sariharjo', 'Kecamatan Krangkeng', 'Kabupaten Indramayu', 'Lake Andresberg', '1959-11-10', 'L', 'Katolik', 'Kawin', 0, 'Pedagang', 'SMA', '086007683768', NULL, 'O', '2025-08-12 02:53:15', '2025-08-12 02:53:15', NULL, NULL),
(23, 1, '6901920409027218', '7282604082304835', 21, 'Kacey Runte', '830 Cormier Divide', '017/001', 'Desa Sariharjo', 'Kecamatan Krangkeng', 'Kabupaten Indramayu', 'Beerton', '1994-10-11', 'L', 'Hindu', 'Kawin', 0, 'PNS', 'S3', NULL, NULL, 'AB', '2025-08-12 02:53:16', '2025-08-12 02:53:16', NULL, NULL),
(24, 1, '6904080209816018', '7282604082304835', 21, 'Alexandria Goyette', '371 Edyth Ports', '008/002', 'Desa Sariharjo', 'Kecamatan Krangkeng', 'Kabupaten Indramayu', 'West Angieport', '2003-01-15', 'P', 'Hindu', 'Cerai Mati', 0, 'Dokter', 'SMA', '085017850173', 'nova76@example.net', 'AB', '2025-08-12 02:53:16', '2025-08-12 02:53:16', NULL, NULL),
(25, 1, '4711281612833316', '7282604082304835', 21, 'Harmony Wolff', '639 America Mountains Apt. 089', '020/007', 'Desa Sariharjo', 'Kecamatan Krangkeng', 'Kabupaten Indramayu', 'Lueilwitztown', '1972-03-16', 'P', 'Kristen', 'Belum Kawin', 0, 'Wiraswasta', 'S1', '084294628452', NULL, 'B', '2025-08-12 02:53:16', '2025-08-12 02:53:16', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `pengaduan`
--

CREATE TABLE `pengaduan` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `id_desa` bigint(20) UNSIGNED NOT NULL,
  `penduduk_id` bigint(20) UNSIGNED NOT NULL,
  `judul` varchar(255) NOT NULL,
  `kategori` varchar(255) NOT NULL,
  `prioritas` varchar(255) NOT NULL DEFAULT 'Sedang',
  `deskripsi` text NOT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'Belum Ditangani',
  `is_public` tinyint(1) NOT NULL DEFAULT 1,
  `tanggapan` text DEFAULT NULL,
  `ditangani_oleh` bigint(20) UNSIGNED DEFAULT NULL,
  `tanggal_tanggapan` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pengaduan`
--

INSERT INTO `pengaduan` (`id`, `id_desa`, `penduduk_id`, `judul`, `kategori`, `prioritas`, `deskripsi`, `foto`, `status`, `is_public`, `tanggapan`, `ditangani_oleh`, `tanggal_tanggapan`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 15, 'Pembagian bantuan tidak merata', 'Sosial', 'Rendah', 'Nihil fugit neque commodi amet qui distinctio. Quod aut minus quod. Et voluptas suscipit ea magnam.\n\nQuisquam ea exercitationem exercitationem eveniet tempore consectetur. Dolore sequi ratione cupiditate asperiores nisi. Incidunt molestiae tenetur autem ipsa autem. Minima minus assumenda eaque.\n\nFacere harum totam est ut sequi saepe in. Ullam a possimus aspernatur quisquam eveniet quos vitae. Optio nostrum asperiores commodi alias corrupti. Assumenda distinctio commodi labore.', NULL, 'Belum Ditangani', 1, NULL, NULL, NULL, '2025-07-29 02:53:16', '2025-08-12 02:53:16', NULL),
(2, 1, 12, 'Informasi bantuan tidak transparan', 'Pelayanan Publik', 'Sedang', 'Odio qui non omnis porro. Et quis quisquam possimus eligendi ut aut. Fugit cumque expedita tempore nobis nam et. Voluptatem et rem ut.\n\nMaiores consequatur tempora dignissimos. Explicabo in atque voluptatum temporibus praesentium et. Officia explicabo dolore aut ut necessitatibus accusamus consequatur.', NULL, 'Belum Ditangani', 1, NULL, NULL, NULL, '2025-07-28 02:53:16', '2025-08-12 02:53:16', NULL),
(3, 1, 9, 'Kesulitan ekonomi keluarga janda', 'Sosial', 'Tinggi', 'Nemo dolorum omnis ipsa blanditiis ipsa sed. Itaque architecto itaque ut praesentium. Omnis quis totam facere voluptas itaque tenetur non fugit. Quae dolor sequi sit tempora provident.', NULL, 'Belum Ditangani', 1, NULL, NULL, NULL, '2025-08-06 02:53:16', '2025-08-12 02:53:16', NULL),
(4, 1, 15, 'Tumpukan sampah tidak diangkut', 'Lingkungan', 'Sedang', 'Ex numquam cum quo voluptas ut quas. Consectetur unde in qui. Veniam nihil excepturi necessitatibus nobis excepturi necessitatibus. Rerum quam sunt non magni sit accusamus sed doloribus.', NULL, 'Belum Ditangani', 0, NULL, NULL, NULL, '2025-07-19 02:53:16', '2025-08-12 02:53:16', NULL),
(5, 1, 16, 'Penjambretan di pasar', 'Keamanan', 'Rendah', 'Dolores modi voluptatibus culpa commodi aperiam temporibus. Cumque error ratione ut molestiae molestiae. Voluptas placeat et accusantium.', NULL, 'Belum Ditangani', 1, NULL, NULL, NULL, '2025-07-20 02:53:16', '2025-08-12 02:53:16', NULL),
(6, 1, 13, 'Perbaikan drainase mendesak', 'Infrastruktur', 'Tinggi', 'Et consequuntur ea quaerat. Soluta dolore voluptatem nihil. Consequatur omnis iste porro earum iste ab.', NULL, 'Belum Ditangani', 1, NULL, NULL, NULL, '2025-07-14 02:53:16', '2025-08-12 02:53:16', NULL),
(7, 1, 21, 'Informasi bantuan tidak transparan', 'Pelayanan Publik', 'Sedang', 'Culpa neque quia culpa modi mollitia minima in. Enim pariatur maiores ipsum vel et non. Veniam qui esse ducimus quis.\n\nEst minus aut numquam voluptas ut itaque. Consequatur ut ea quas ad ratione. Dolorem iste inventore quasi quia officiis a.\n\nTempora dolore dolores qui dolorem repudiandae cumque. Maxime ratione aut eum animi cupiditate fugit deleniti. Aut quia doloremque incidunt autem illo asperiores enim. Neque minus ipsam natus excepturi.', NULL, 'Belum Ditangani', 1, NULL, NULL, NULL, '2025-07-27 02:53:16', '2025-08-12 02:53:16', NULL),
(8, 1, 3, 'Layanan posyandu tidak berjalan', 'Kesehatan', 'Sedang', 'Ea quo qui quis deleniti. Fuga ea labore omnis adipisci possimus voluptatibus alias. Nihil minima sint aut suscipit. Molestiae a reprehenderit cumque praesentium fuga totam.\n\nVoluptas minus harum voluptatem. Incidunt quia eos ratione eos. Id recusandae porro sint quia ea excepturi nisi.', NULL, 'Belum Ditangani', 1, NULL, NULL, NULL, '2025-08-10 02:53:16', '2025-08-12 02:53:16', NULL),
(9, 1, 6, 'Sinyal telepon buruk', 'Lainnya', 'Sedang', 'Rerum placeat ipsum consequatur saepe ut et nihil. Omnis voluptas ipsam aut dolore quaerat eius. Est exercitationem quas consequuntur debitis eveniet animi.', NULL, 'Belum Ditangani', 1, NULL, NULL, NULL, '2025-07-15 02:53:16', '2025-08-12 02:53:16', NULL),
(10, 1, 11, 'Keributan di malam hari', 'Keamanan', 'Rendah', 'Voluptas beatae molestiae voluptate aut quasi vel possimus. Eum minima dicta vel. Laboriosam minus qui deleniti.\n\nLaborum repellat dignissimos saepe neque animi cumque saepe. Aut dolore asperiores quia aliquam nostrum laboriosam. Quia libero porro esse aliquid. Dolorum earum quod commodi sint.\n\nAd sed voluptas rem beatae repudiandae dolores. Cupiditate blanditiis quam maxime quisquam provident voluptatem. Laboriosam et ducimus eius commodi in earum.', NULL, 'Belum Ditangani', 1, NULL, NULL, NULL, '2025-07-27 02:53:16', '2025-08-12 02:53:16', NULL),
(11, 1, 13, 'Sinyal telepon buruk', 'Lainnya', 'Tinggi', 'Et officia voluptates et accusantium. Voluptatibus ratione cupiditate enim dolores recusandae sapiente dolorum. Cum fugit corporis quia totam et dolorem.\n\nDolorum magnam dolorum et. Fuga quo nemo et voluptatem delectus tempora ipsum. Quis in cumque dignissimos et.', NULL, 'Belum Ditangani', 0, NULL, NULL, NULL, '2025-08-07 02:53:16', '2025-08-12 02:53:16', NULL),
(12, 1, 10, 'Penyakit kulit mewabah', 'Kesehatan', 'Tinggi', 'Est ducimus quia cupiditate non quidem consequatur itaque nihil. Sequi iure repudiandae provident perferendis repellendus. Eius minus non sed architecto aut minus.', NULL, 'Belum Ditangani', 1, NULL, NULL, NULL, '2025-07-25 02:53:16', '2025-08-12 02:53:16', NULL),
(13, 1, 15, 'Perbaikan drainase mendesak', 'Infrastruktur', 'Tinggi', 'Animi deleniti officiis sit. Soluta aliquid magni repellat ad consequatur. Minima et deserunt doloremque voluptatem. Harum minus ea consequatur aut nulla quis dolorum impedit.\n\nEt sint vero quia voluptate aut consequuntur. Similique possimus eos amet autem. Omnis sit et dolore consectetur facere animi eligendi. Quia repudiandae ea illo laborum.\n\nAt ut quibusdam ut sed nesciunt voluptates. Necessitatibus ut rerum eligendi facilis earum quia illum. Voluptatibus incidunt a est voluptatem.', NULL, 'Belum Ditangani', 1, NULL, NULL, NULL, '2025-07-19 02:53:16', '2025-08-12 02:53:16', NULL),
(14, 1, 7, 'Pelayanan KTP lambat', 'Pelayanan Publik', 'Tinggi', 'Perspiciatis ut praesentium autem magni praesentium non accusamus exercitationem. Eius ducimus at vel labore. Quia debitis dolor non asperiores quasi temporibus dolorem.', NULL, 'Belum Ditangani', 1, NULL, NULL, NULL, '2025-07-26 02:53:16', '2025-08-12 02:53:16', NULL),
(15, 1, 7, 'Pencurian di rumah warga', 'Keamanan', 'Tinggi', 'Dolores molestiae ex veritatis mollitia odit corporis dolorem velit. Fugiat ipsa aliquam perspiciatis voluptate modi ut laudantium. Autem amet rerum unde autem. Nisi consectetur blanditiis et cumque aut eos nam.\n\nEos enim ut cupiditate voluptatem esse qui. Aut ut soluta autem nihil voluptatum dolor et est. Consequatur eum tenetur totam nam sed laudantium ut autem. Nulla quisquam voluptas et porro et quam voluptate asperiores.', NULL, 'Belum Ditangani', 1, NULL, NULL, NULL, '2025-07-13 02:53:16', '2025-08-12 02:53:16', NULL),
(16, 1, 25, 'Bangunan sekolah rusak', 'Infrastruktur', 'Rendah', 'Velit ipsum sapiente architecto velit libero quia ea. Impedit excepturi ut facere in tempora. Aspernatur sint expedita magnam corporis qui id.', NULL, 'Sedang Diproses', 1, 'Maiores minima qui quia. Et adipisci rerum ut sed exercitationem voluptatem et molestiae. Dolore qui nostrum culpa aspernatur. Iusto voluptas aut non minus qui ipsum numquam.', 1, '2025-08-09 02:53:16', '2025-08-03 02:53:16', '2025-08-12 02:53:16', NULL),
(17, 1, 9, 'Gangguan listrik terus menerus', 'Lainnya', 'Sedang', 'Maxime dolores aut corrupti dolor perferendis laboriosam ut minima. Facilis aliquam modi repudiandae minus et consectetur magnam veritatis. Libero nihil consequuntur optio animi fugiat fuga. Facilis necessitatibus at eveniet quia ex quibusdam cupiditate.\n\nEt saepe ducimus perspiciatis voluptatem. Et natus officia et et alias. Quis voluptas labore nemo. Illum temporibus provident voluptate. Alias minus ratione perspiciatis culpa hic veniam.\n\nSunt culpa tempora voluptatum velit. Voluptatem ipsam ullam unde neque possimus. Dignissimos sed ut minima vel temporibus qui minus.', NULL, 'Sedang Diproses', 1, 'Nulla nihil blanditiis qui quasi sed voluptatum quasi. Excepturi hic corrupti magnam iste quibusdam maiores. Cumque et animi fuga et accusantium. Et itaque optio tenetur et.', 1, '2025-08-07 02:53:16', '2025-07-23 02:53:16', '2025-08-12 02:53:16', NULL),
(18, 1, 19, 'Koneksi internet lemah', 'Lainnya', 'Tinggi', 'Eius amet nihil molestiae distinctio accusamus repellat. Quisquam consequatur qui officia laborum asperiores natus. Eum tenetur harum et velit inventore. Minima error amet ut perspiciatis quasi molestiae.', NULL, 'Sedang Diproses', 1, 'Eligendi magni harum consequatur voluptas. Veritatis distinctio totam excepturi consequuntur. Adipisci aut excepturi minus omnis quidem dolor. Eum omnis tempore et enim veritatis voluptatem suscipit.', 1, '2025-08-08 02:53:16', '2025-08-07 02:53:16', '2025-08-12 02:53:16', NULL),
(19, 1, 6, 'Bantuan untuk difabel', 'Sosial', 'Rendah', 'Voluptas quo beatae sint ullam. Qui vel ipsam neque numquam omnis maxime.', NULL, 'Sedang Diproses', 1, 'Explicabo omnis doloremque hic voluptatum. Eligendi et iure laborum et. Sint in et et pariatur. Voluptatem esse veniam eius mollitia.', 1, '2025-08-10 02:53:16', '2025-08-01 02:53:16', '2025-08-12 02:53:16', NULL),
(20, 1, 13, 'Pembagian bantuan tidak merata', 'Sosial', 'Sedang', 'Porro aut laudantium tempore ullam quis sint est. Numquam veritatis atque ratione corrupti quos vitae eos fugit. Possimus tempora et explicabo sapiente esse totam enim. Quaerat aut aperiam soluta quia quia ducimus.\n\nNeque aut voluptatum ad facilis enim qui. Nihil velit libero corrupti molestias. Aliquam sint sed quis qui nihil odio in saepe.', NULL, 'Sedang Diproses', 1, 'Atque iure delectus est maiores. Dolores sit laboriosam cupiditate magnam ex nihil. Quasi consequuntur est est repellendus ut facilis enim et. Autem temporibus eum quia soluta. Rerum alias atque hic magnam odio nesciunt laborum.', 1, '2025-08-08 02:53:16', '2025-08-03 02:53:16', '2025-08-12 02:53:16', NULL),
(21, 1, 21, 'Pertanian gagal panen', 'Lainnya', 'Rendah', 'Quia quasi explicabo vel. Quia quia nesciunt et mollitia iusto qui. Architecto praesentium non ut veritatis maiores. Voluptatibus et ut ipsa molestiae dicta voluptas ea.\n\nQuisquam architecto et sed eaque cumque quisquam nam. A repudiandae maiores quidem sunt ea laboriosam. Omnis dolorem nam qui fuga et. Cum recusandae autem cupiditate harum et.', NULL, 'Sedang Diproses', 1, 'Ducimus provident sunt saepe veritatis nisi. Ea odio eos facere qui quia sunt. Quis cupiditate itaque laboriosam laboriosam voluptatem iste illum consequuntur.', 1, '2025-08-07 02:53:16', '2025-08-04 02:53:16', '2025-08-12 02:53:16', NULL),
(22, 1, 12, 'Saluran air tersumbat', 'Infrastruktur', 'Sedang', 'Possimus ut fuga voluptatem. Quia maxime aspernatur dolorem molestiae veniam perspiciatis quisquam. Dicta dolorem tempore aliquid doloremque perferendis corporis.\n\nEt ut debitis quas quis. Aut rem omnis est est libero placeat. Voluptatibus nostrum et aut distinctio ex. Numquam quam iure officiis earum at. Rerum nam est et quia.\n\nDoloremque recusandae aut voluptas itaque earum beatae. Corrupti cumque itaque nobis facere quaerat aut modi nobis.', NULL, 'Sedang Diproses', 0, 'Ullam aut velit vel qui quo perferendis sit maiores. Blanditiis eum est repellat voluptate possimus numquam saepe. Doloremque enim sed quia explicabo a ea. Sint non voluptatibus accusamus corrupti commodi architecto cupiditate deserunt.', 1, '2025-08-08 02:53:16', '2025-07-29 02:53:16', '2025-08-12 02:53:16', NULL),
(23, 1, 3, 'Pembagian bantuan tidak merata', 'Sosial', 'Tinggi', 'Maxime est sit vel consequuntur doloremque. Quod voluptas est dignissimos sapiente aut quo. Et est veniam libero eligendi. Voluptate rem nemo voluptatem ullam et voluptatibus alias incidunt.\n\nEx dolore quis recusandae exercitationem labore rerum. Cupiditate enim eos nam ut expedita ea soluta. Et est in sed omnis amet.\n\nEaque tempora ea eaque aspernatur et. Quia et consequatur culpa quam animi quos quia. Sint deleniti facilis qui laborum voluptatem voluptatem molestiae. Voluptate repellendus inventore tenetur in et dolorem modi.', NULL, 'Sedang Diproses', 1, 'Quia alias suscipit aut harum hic itaque facilis repudiandae. Numquam perspiciatis reprehenderit et blanditiis molestiae maxime. Harum voluptate laborum velit sed ut sunt.', 1, '2025-08-09 02:53:16', '2025-08-01 02:53:16', '2025-08-12 02:53:16', NULL),
(24, 1, 24, 'Jembatan rusak di dusun timur', 'Infrastruktur', 'Sedang', 'Iure facilis autem eos. Neque sit delectus laudantium eius iusto in. Est quod eum facere aut. Repudiandae qui necessitatibus magni velit.\n\nBlanditiis et velit voluptate ut quis qui autem. Dolor possimus molestias aut et est. Voluptatem architecto tenetur quia. Dolorem delectus hic deserunt pariatur dolore.\n\nSint aut corporis consequatur repudiandae. Sint sunt fugit vel et officiis.', NULL, 'Selesai', 1, 'Pengaduan telah diselesaikan. Placeat id aut sint ex omnis molestias. Id non maxime nihil aperiam reiciendis in. Quasi voluptatibus a iusto sed a illum. Minima earum velit reiciendis ut quia quisquam.', 1, '2025-08-10 02:53:16', '2025-07-29 02:53:16', '2025-08-12 02:53:16', NULL),
(25, 1, 4, 'Ambulans desa rusak', 'Kesehatan', 'Tinggi', 'Quidem blanditiis aut ipsam sit modi cumque possimus est. Corrupti reiciendis est vitae vel voluptas numquam suscipit quod. Nulla ipsa voluptas commodi quo maxime incidunt amet. Omnis et rerum fugit non officia eveniet.\n\nQuia esse amet est eos suscipit et nihil officia. Asperiores recusandae dolorum facere minus optio. Quidem qui id maiores. Sed fuga maxime nostrum.\n\nIllo ut commodi est delectus debitis enim sunt. A sed non recusandae. Est molestiae et voluptatibus commodi. Voluptatem cumque error omnis sit perferendis non.', NULL, 'Selesai', 1, 'Pengaduan telah diselesaikan. Neque delectus sed omnis laborum eaque voluptatem. Quae porro dolorum molestiae laudantium maxime rerum numquam quia. Ipsam laudantium est sequi architecto quod libero numquam.', 1, '2025-08-11 02:53:16', '2025-07-14 02:53:16', '2025-08-12 02:53:16', NULL),
(26, 1, 17, 'Pungutan liar untuk layanan', 'Pelayanan Publik', 'Rendah', 'Voluptatibus omnis qui quo qui temporibus. Eligendi id sunt et vel itaque. Magnam beatae magni asperiores ut. Et labore error quo delectus.\n\nSed officiis reprehenderit doloremque et quia. Dicta aperiam nobis vel fugiat consequatur laboriosam iusto ratione. Non sit molestiae velit rerum. Quia in autem qui hic perferendis. Tempore inventore odio dolorum eos.', NULL, 'Selesai', 1, 'Pengaduan telah diselesaikan. Quos non iste voluptas. Sed earum et cupiditate dolorem quam. Possimus corrupti odio est blanditiis ipsa. Rerum voluptatem sunt soluta quas sit.', 1, '2025-08-10 02:53:16', '2025-07-17 02:53:16', '2025-08-12 02:53:16', NULL),
(27, 1, 10, 'Balap liar di jalan desa', 'Keamanan', 'Tinggi', 'Cumque nisi explicabo aut occaecati voluptatum facilis culpa quo. Vel hic voluptatem rerum ut voluptatibus ut cupiditate eaque. Doloremque dolores at placeat sequi dolorem in est ut.', NULL, 'Selesai', 0, 'Pengaduan telah diselesaikan. Quam et nisi explicabo adipisci aperiam. Id fugiat libero et porro doloribus autem quia. A quos vel magnam voluptas sint consequatur.', 1, '2025-08-11 02:53:16', '2025-07-24 02:53:16', '2025-08-12 02:53:16', NULL),
(28, 1, 9, 'Lahan pertanian tercemar', 'Lingkungan', 'Tinggi', 'Adipisci eaque et minima ducimus voluptatem. Accusantium maxime eum quia. Sit quos est sed voluptate nemo dolor. Cumque fugit quo possimus consequatur maiores. Voluptatum fugiat voluptatem molestias dicta officia impedit error.\n\nEst perferendis deleniti qui ab. Tenetur voluptatem nulla quisquam dolores. Autem molestias perspiciatis repellendus explicabo aut. Magnam dolores qui ut nobis dicta repudiandae. Quasi libero est perferendis eveniet.\n\nSaepe autem est reiciendis laboriosam architecto. Ipsam ab repellendus quia cupiditate mollitia sed quia. Libero vero alias voluptatem incidunt et fuga. Qui ut expedita aspernatur.', NULL, 'Selesai', 1, 'Pengaduan telah diselesaikan. Dignissimos est unde maiores. Sunt vitae expedita sapiente hic. Quis dolor fugiat sunt ut tempora. Saepe molestias minima facere eum dolorem enim.', 1, '2025-08-10 02:53:16', '2025-07-17 02:53:16', '2025-08-12 02:53:16', NULL),
(29, 1, 8, 'Jembatan rusak di dusun timur', 'Infrastruktur', 'Sedang', 'Hic et et et fugiat molestiae qui. Temporibus nihil eos accusantium sunt. Velit cupiditate delectus similique consectetur consectetur tempore reprehenderit. Amet corrupti odit dolorem enim. Est quaerat ratione alias quidem magnam voluptatibus sapiente.', NULL, 'Selesai', 1, 'Pengaduan telah diselesaikan. Et est et nihil id quas iusto ut ad. Aut omnis nihil qui sint. Nostrum commodi et odit quas. Et nihil necessitatibus eligendi est eum ex est. Rerum et et nulla sed dolores quia.', 1, '2025-08-09 02:53:16', '2025-07-25 02:53:16', '2025-08-12 02:53:16', NULL),
(30, 1, 24, 'Koneksi internet lemah', 'Lainnya', 'Sedang', 'Iure aliquam quia doloribus. Ut ut error error modi porro. Dolores ratione qui aliquid animi delectus voluptas.', NULL, 'Selesai', 1, 'Pengaduan telah diselesaikan. At soluta eos ea non. Nulla aut quia et consequatur qui.', 1, '2025-08-11 02:53:16', '2025-07-27 02:53:16', '2025-08-12 02:53:16', NULL),
(31, 1, 19, 'Pemalakan terhadap siswa sekolah', 'Keamanan', 'Tinggi', 'Quos voluptates repudiandae eum sapiente magnam illo. Quos dicta esse et possimus. Eum inventore omnis quo nostrum eos earum vel illo. Veniam quod rerum eaque quia distinctio similique. Fugiat blanditiis earum rerum numquam maiores qui repellat.\n\nOfficiis cumque dolor nostrum ut et eos. Harum vel in assumenda sit dolores. Eum nostrum dolores eveniet nemo et qui esse. Aliquam tenetur ut quae hic cupiditate vel porro. Temporibus dignissimos consectetur rerum exercitationem amet architecto.\n\nDelectus enim quia labore omnis excepturi placeat. Aut itaque itaque et consequuntur dolorum possimus. Laudantium illo sequi eveniet qui accusamus. Rerum nesciunt vitae aspernatur vel aut reiciendis architecto.', NULL, 'Selesai', 0, 'Pengaduan telah diselesaikan. Commodi exercitationem non magnam deleniti laborum velit. Quo atque sit id in. Nihil placeat aut voluptatem est.', 1, '2025-08-10 02:53:16', '2025-08-11 02:53:16', '2025-08-12 02:53:16', NULL),
(32, 1, 18, 'Kekurangan tenaga kesehatan', 'Kesehatan', 'Sedang', 'Quia optio mollitia nemo repellat provident cupiditate. Aut ipsam ut qui iste quam voluptas. Quia qui error qui facilis quia quia maxime non.', NULL, 'Selesai', 1, 'Pengaduan telah diselesaikan. Voluptatem dolorem quia illo labore quia architecto. Delectus quae animi similique cumque. Architecto hic facere est. Nulla quia sunt in fugit dolores assumenda.', 1, '2025-08-11 02:53:16', '2025-08-06 02:53:16', '2025-08-12 02:53:16', NULL),
(33, 1, 1, 'Pencemaran air sungai', 'Lingkungan', 'Rendah', 'Veniam voluptate excepturi dolorem nemo. Ut ut est mollitia. Minima voluptas quidem quasi eum asperiores. Dolores odit soluta nulla delectus impedit totam.', NULL, 'Selesai', 1, 'Pengaduan telah diselesaikan. Nihil praesentium voluptatibus aut vel excepturi sit et. Commodi quo unde consequatur consectetur. Repudiandae vel non odit accusamus voluptas rem at.', 1, '2025-08-10 02:53:16', '2025-07-25 02:53:16', '2025-08-12 02:53:16', NULL),
(34, 1, 11, 'Jalan berlubang di RT 02', 'Infrastruktur', 'Rendah', 'At sunt est reiciendis qui omnis suscipit. Natus excepturi numquam sed minus architecto saepe cum. Harum rerum ea nihil cumque voluptatem molestias.', NULL, 'Selesai', 1, 'Pengaduan telah diselesaikan. Rerum fugiat quas et debitis quis itaque. Aut qui commodi incidunt veniam.', 1, '2025-08-10 02:53:16', '2025-07-24 02:53:16', '2025-08-12 02:53:16', NULL),
(35, 1, 15, 'Informasi bantuan tidak transparan', 'Pelayanan Publik', 'Sedang', 'Quam illum eveniet et beatae quos. At aperiam dolores dignissimos natus. Laborum hic et aliquam praesentium sunt vel. Maiores non illum velit exercitationem et rerum culpa.\n\nDucimus dolorum repellendus nesciunt. Officia aut velit adipisci et rem corrupti. Explicabo laboriosam aspernatur quia blanditiis sed quo repudiandae. Et iure delectus est sint sit rerum.', NULL, 'Selesai', 1, 'Pengaduan telah diselesaikan. Corrupti et nisi cum. In reprehenderit amet excepturi reiciendis id.', 1, '2025-08-09 02:53:16', '2025-08-01 02:53:16', '2025-08-12 02:53:16', NULL),
(36, 1, 2, 'Pencemaran air sungai', 'Lingkungan', 'Tinggi', 'Molestiae eum molestiae velit minima sapiente autem asperiores. Dolorem iure omnis blanditiis. Maiores cumque rerum quia qui vel non rerum. Minima est ut quaerat fugit consequatur.\n\nProvident non sint et qui recusandae. Expedita nemo deserunt consequatur repellendus. Sunt voluptas corrupti dolores totam.\n\nSuscipit vero aut atque magnam ex. Nemo et iste delectus quod. Magni dolores accusamus accusamus distinctio. Enim qui ex totam enim et.', NULL, 'Selesai', 0, 'Pengaduan telah diselesaikan. Distinctio laboriosam hic cum incidunt eaque. Minima dolor ipsum excepturi. Ab sapiente delectus ut assumenda. Ex at non et porro numquam.', 1, '2025-08-11 02:53:16', '2025-07-21 02:53:16', '2025-08-12 02:53:16', NULL),
(37, 1, 14, 'Jam buka kantor desa tidak konsisten', 'Pelayanan Publik', 'Tinggi', 'Quam fugiat quibusdam cum nam. Et praesentium quibusdam voluptas reprehenderit eos harum. Qui molestiae id nihil tempore rerum.\n\nAdipisci qui eos voluptatum autem nesciunt enim rem. Autem enim consequatur et ipsum velit dolorem. Sit laudantium nulla incidunt aut neque nobis vitae. Minima consequatur iusto aut. Ea et culpa pariatur explicabo velit numquam debitis.', NULL, 'Selesai', 0, 'Pengaduan telah diselesaikan. Tempora harum ut blanditiis qui. Exercitationem maxime est in. Autem sint autem quis fuga qui rerum.', 1, '2025-08-10 02:53:16', '2025-07-17 02:53:16', '2025-08-12 02:53:16', NULL),
(38, 1, 9, 'Anak putus sekolah', 'Sosial', 'Tinggi', 'Nihil sit sed non quia. Unde consectetur doloribus aut aut mollitia tempore repellat. Ut et excepturi voluptas voluptatem quo.\n\nMolestiae vel cupiditate nemo non quia. Nobis harum reiciendis nobis enim. Necessitatibus est et aspernatur aut voluptatem distinctio voluptate.', NULL, 'Selesai', 1, 'Pengaduan telah diselesaikan. Sequi enim aut placeat qui. Ut ea ut nostrum qui pariatur. Est eos et aut. Sit culpa quia adipisci voluptatum praesentium maiores.', 1, '2025-08-10 02:53:16', '2025-07-28 02:53:16', '2025-08-12 02:53:16', NULL),
(39, 1, 4, 'Lampu jalan padam', 'Infrastruktur', 'Tinggi', 'Ad omnis veritatis natus ipsa ipsam voluptatum fugit. Cupiditate sed rerum odio sunt voluptatem et amet. Provident ducimus vel molestiae molestias. Dolorem fugit optio odio.\n\nDolor qui non consequatur expedita ut ipsum labore. Et consequatur et quae nemo.', NULL, 'Ditolak', 0, 'Pengaduan ini ditolak karena Sit repellat rerum velit eum.', 1, '2025-08-10 02:53:16', '2025-07-27 02:53:16', '2025-08-12 02:53:16', NULL),
(40, 1, 3, 'Pelayanan KTP lambat', 'Pelayanan Publik', 'Sedang', 'Accusamus ducimus et nesciunt. Ad ipsam tenetur nemo sed sint commodi. Eaque illum architecto fugit.\n\nBeatae et est hic voluptates unde ea. Qui ab asperiores voluptatem et assumenda id necessitatibus. Quasi beatae saepe et quam eos. Delectus ipsam et corporis.\n\nNisi hic et odio eum labore nesciunt maxime. Quia fuga asperiores recusandae sunt. Nemo eum dolor velit nobis tempore eligendi quasi similique.', NULL, 'Ditolak', 1, 'Pengaduan ini ditolak karena Aut debitis ab nihil nostrum voluptatem.', 1, '2025-08-07 02:53:16', '2025-07-15 02:53:16', '2025-08-12 02:53:16', NULL),
(41, 1, 3, 'Bangunan sekolah rusak', 'Infrastruktur', 'Rendah', 'Quam aliquid aut numquam assumenda. Totam dolores est ut quia et at. Quo earum voluptas minus temporibus ipsa blanditiis. Libero modi omnis laudantium nesciunt distinctio ipsam.\n\nCupiditate ipsam eum laudantium et voluptate amet. Atque qui ut sed laudantium sapiente porro. Voluptatem quis in repellat illum quo assumenda sint.', NULL, 'Ditolak', 1, 'Pengaduan ini ditolak karena Architecto quo ex quis eligendi molestias quis.', 1, '2025-08-08 02:53:16', '2025-07-16 02:53:16', '2025-08-12 02:53:16', NULL),
(42, 1, 6, 'Prosedur perizinan rumit', 'Pelayanan Publik', 'Tinggi', 'Officia ratione sed rem ut temporibus. Consequatur et sed consequatur velit et porro. Sed eos et voluptatem.', NULL, 'Ditolak', 0, 'Pengaduan ini ditolak karena Recusandae officia culpa ut unde non.', 1, '2025-08-11 02:53:16', '2025-08-02 02:53:16', '2025-08-12 02:53:16', NULL),
(43, 1, 12, 'Keributan di malam hari', 'Keamanan', 'Sedang', 'Assumenda ut iste nemo sunt et. Pariatur ut sunt repellat qui consequatur. Quidem cupiditate quia sint corporis. Velit ipsa corrupti perspiciatis libero voluptatem blanditiis. Autem et sapiente magnam nihil consequatur laboriosam.', 'uploads/pengaduan/infrastruktur.jpg', 'Ditolak', 1, 'Voluptate tenetur earum in facere velit sit. Et aut nihil vel velit. Et iste sequi consequatur nisi non repudiandae dolor inventore. Dolores mollitia et adipisci et.', 2, '2025-08-02 02:53:16', '2025-08-10 02:53:16', '2025-08-12 02:53:16', NULL),
(44, 1, 16, 'Harga sembako melambung', 'Lainnya', 'Rendah', 'Nesciunt quas suscipit est neque. Vel recusandae dolorem qui eligendi animi aut iste. At architecto sunt autem dicta ipsam omnis.\n\nQuo id ad vel exercitationem qui. Consequatur quo atque voluptatem.\n\nImpedit autem facere qui iusto quasi. Dolor qui est qui ipsam omnis. Qui aut ullam incidunt consectetur laudantium id sit.', 'uploads/pengaduan/sampah.jpg', 'Sedang Diproses', 0, 'Blanditiis deleniti ut numquam nihil ipsa. Sequi qui architecto officia ipsa ex necessitatibus. Fugiat pariatur accusamus omnis sequi aperiam.', 3, '2025-08-08 02:53:16', '2025-07-19 02:53:16', '2025-08-12 02:53:16', NULL),
(45, 1, 15, 'Prosedur perizinan rumit', 'Pelayanan Publik', 'Sedang', 'Id et voluptatem rerum et natus magni ea quaerat. Quia aut dolorum quia quisquam est repellendus. Aut maxime et natus eos.\n\nQuae atque aut ut. Earum rem et inventore numquam ea est. Et ut enim ratione ipsum soluta eos minus.', 'uploads/pengaduan/lampu-jalan.jpg', 'Selesai', 1, 'Distinctio officiis quae quo sint voluptas voluptatem. Dicta eos tempora et recusandae ex officia.', 1, '2025-08-02 02:53:16', '2025-07-28 02:53:16', '2025-08-12 02:53:16', NULL),
(46, 1, 11, 'Sanitasi buruk menyebabkan penyakit', 'Kesehatan', 'Rendah', 'Dolores rerum recusandae dolores cumque deserunt et. Minima nam deserunt qui similique. Eos dolores ad quaerat placeat porro est consequuntur cupiditate. Ut temporibus fugiat vel non deserunt.\n\nAmet assumenda velit dolorum laborum officiis vitae. Praesentium atque pariatur ex sint excepturi cum saepe.', 'uploads/pengaduan/lampu-jalan.jpg', 'Sedang Diproses', 1, 'Et officia eligendi architecto dicta natus. Adipisci inventore voluptatem dolor consequuntur praesentium et occaecati. Ab illo quo ullam et. Minus vero eos cumque.', 2, '2025-08-06 02:53:16', '2025-08-11 02:53:16', '2025-08-12 02:53:16', NULL),
(47, 1, 7, 'Koneksi internet lemah', 'Lainnya', 'Sedang', 'Repellat officia iure enim voluptatem commodi ducimus ipsa. Est sed temporibus autem numquam est reiciendis expedita. Facilis accusantium quam soluta laudantium veniam autem eos.\n\nRerum adipisci qui hic ullam voluptates. In accusantium ut a sequi ut. Voluptatibus saepe consequuntur cum aliquid quo perspiciatis. Iure architecto neque consectetur cupiditate.', 'uploads/pengaduan/lampu-jalan.jpg', 'Ditolak', 1, 'Impedit ea facere et et sunt dolores nisi facere. Soluta ut quisquam quae qui vitae unde voluptatem maxime. Consectetur illo laboriosam quos incidunt.', 1, '2025-08-03 02:53:16', '2025-07-18 02:53:16', '2025-08-12 02:53:16', NULL),
(48, 1, 9, 'Pertanian gagal panen', 'Lainnya', 'Tinggi', 'Est nihil harum pariatur inventore culpa itaque ex. Rem dolor quia nostrum alias expedita dolorum recusandae. Consequuntur quisquam quo aut aperiam fuga voluptatem.', 'uploads/pengaduan/sampah.jpg', 'Selesai', 1, 'Eos quod beatae necessitatibus alias. Dolorum qui ipsum quia qui rerum ad aut. Ipsa nemo incidunt accusamus quisquam quam sint similique. Est qui iste maiores quas quisquam enim assumenda asperiores.', 3, '2025-08-07 02:53:16', '2025-07-22 02:53:16', '2025-08-12 02:53:16', NULL),
(49, 1, 7, 'Bantuan untuk difabel', 'Sosial', 'Rendah', 'Distinctio minima et et autem aut quia. Voluptates voluptatem provident omnis voluptas impedit et ea. Possimus quis et aut dolorem cum ipsa. Harum doloribus similique omnis praesentium est.\n\nPossimus et ut quis eveniet earum. Et magni asperiores debitis autem. Officiis sit facilis autem.', 'uploads/pengaduan/sampah.jpg', 'Sedang Diproses', 1, 'Consequuntur incidunt est autem est. Enim atque exercitationem sapiente reprehenderit ad repudiandae. Sed qui sint error qui laborum.', 2, '2025-08-10 02:53:16', '2025-08-08 02:53:16', '2025-08-12 02:53:16', NULL),
(50, 1, 3, 'Obat di puskesmas habis', 'Kesehatan', 'Tinggi', 'Maiores quia eos autem praesentium. At quod voluptatem distinctio et exercitationem. Qui deserunt placeat ut at et.\n\nUt provident voluptatem dolorum. Odit aperiam optio ipsa ut molestiae esse et. Numquam aspernatur animi est neque quisquam.', 'uploads/pengaduan/infrastruktur.jpg', 'Sedang Diproses', 1, 'Provident aut ut error alias. Voluptatem voluptatem placeat sapiente tempore sint. Deleniti quam nam consequatur sit quisquam tempora. Officiis odit minima sed qui.', 1, '2025-08-09 02:53:16', '2025-07-23 02:53:16', '2025-08-12 02:53:16', NULL),
(51, 1, 20, 'Kebutuhan guru tambahan', 'Lainnya', 'Rendah', 'Sunt sapiente quam qui. Itaque id natus est sit molestiae. Quae fugit voluptatem praesentium doloremque.\n\nEt aut pariatur quibusdam eligendi. Soluta consectetur laudantium dignissimos nihil in ut id. Suscipit a similique amet earum quis quia.', 'uploads/pengaduan/infrastruktur.jpg', 'Sedang Diproses', 1, 'Quis dolor aut aut quia sapiente et. Sunt quis vel voluptas rem dicta distinctio commodi. Et enim officia odio aspernatur iusto voluptatem ducimus. Ut odio nam qui et.', 1, '2025-08-05 02:53:16', '2025-08-08 02:53:16', '2025-08-12 02:53:16', NULL),
(52, 1, 2, 'Pungutan liar untuk layanan', 'Pelayanan Publik', 'Tinggi', 'Cumque deserunt consectetur voluptas et. Accusantium magnam fugit commodi facilis. Consectetur porro harum perspiciatis autem. Soluta porro similique inventore suscipit eaque nisi rerum.', 'uploads/pengaduan/infrastruktur.jpg', 'Selesai', 1, 'Corporis architecto et occaecati quisquam a quo. Quo quaerat ut maiores qui accusamus. Ex cumque error facere. Et optio excepturi ipsa quasi non blanditiis. Ut qui at beatae amet molestiae.', 1, '2025-08-05 02:53:16', '2025-07-24 02:53:16', '2025-08-12 02:53:16', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'manage users', 'web', '2025-08-12 02:53:15', '2025-08-12 02:53:15'),
(2, 'manage profil_desa', 'web', '2025-08-12 02:53:15', '2025-08-12 02:53:15'),
(3, 'manage penduduk', 'web', '2025-08-12 02:53:15', '2025-08-12 02:53:15'),
(4, 'import penduduk', 'web', '2025-08-12 02:53:15', '2025-08-12 02:53:15'),
(5, 'export penduduk', 'web', '2025-08-12 02:53:15', '2025-08-12 02:53:15'),
(6, 'restore penduduk', 'web', '2025-08-12 02:53:15', '2025-08-12 02:53:15'),
(7, 'manage bansos', 'web', '2025-08-12 02:53:15', '2025-08-12 02:53:15'),
(8, 'restore bansos', 'web', '2025-08-12 02:53:15', '2025-08-12 02:53:15'),
(9, 'manage berita', 'web', '2025-08-12 02:53:15', '2025-08-12 02:53:15'),
(10, 'restore berita', 'web', '2025-08-12 02:53:15', '2025-08-12 02:53:15'),
(11, 'manage keuangan', 'web', '2025-08-12 02:53:15', '2025-08-12 02:53:15'),
(12, 'restore keuangan', 'web', '2025-08-12 02:53:15', '2025-08-12 02:53:15'),
(13, 'manage inventaris', 'web', '2025-08-12 02:53:15', '2025-08-12 02:53:15'),
(14, 'restore inventaris', 'web', '2025-08-12 02:53:15', '2025-08-12 02:53:15'),
(15, 'manage pengaduan', 'web', '2025-08-12 02:53:15', '2025-08-12 02:53:15'),
(16, 'respond pengaduan', 'web', '2025-08-12 02:53:15', '2025-08-12 02:53:15'),
(17, 'create pengaduan', 'web', '2025-08-12 02:53:15', '2025-08-12 02:53:15'),
(18, 'manage umkm', 'web', '2025-08-12 02:53:15', '2025-08-12 02:53:15'),
(19, 'restore umkm', 'web', '2025-08-12 02:53:15', '2025-08-12 02:53:15'),
(20, 'approve umkm', 'web', '2025-08-12 02:53:15', '2025-08-12 02:53:15'),
(21, 'manage layanan', 'web', '2025-08-12 02:53:15', '2025-08-12 02:53:15'),
(22, 'restore layanan', 'web', '2025-08-12 02:53:15', '2025-08-12 02:53:15'),
(23, 'submit verifikasi data', 'web', '2025-08-12 02:53:15', '2025-08-12 02:53:15'),
(24, 'approve verifikasi data', 'web', '2025-08-12 02:53:15', '2025-08-12 02:53:15'),
(25, 'manage verifikasi data', 'web', '2025-08-12 02:53:15', '2025-08-12 02:53:15'),
(26, 'view trash', 'web', '2025-08-12 02:53:15', '2025-08-12 02:53:15'),
(27, 'force delete', 'web', '2025-08-12 02:53:15', '2025-08-12 02:53:15'),
(28, 'view own profile', 'web', '2025-08-12 02:53:15', '2025-08-12 02:53:15'),
(29, 'view own bansos', 'web', '2025-08-12 02:53:15', '2025-08-12 02:53:15'),
(30, 'apply for bansos', 'web', '2025-08-12 02:53:15', '2025-08-12 02:53:15'),
(31, 'view own pengaduan', 'web', '2025-08-12 02:53:15', '2025-08-12 02:53:15'),
(32, 'track layanan status', 'web', '2025-08-12 02:53:15', '2025-08-12 02:53:15'),
(33, 'view verification status', 'web', '2025-08-12 02:53:15', '2025-08-12 02:53:15'),
(34, 'edit verification data', 'web', '2025-08-12 02:53:15', '2025-08-12 02:53:15'),
(35, 'cancel verification', 'web', '2025-08-12 02:53:15', '2025-08-12 02:53:15'),
(36, 'view profil_desa', 'web', '2025-08-12 02:53:15', '2025-08-12 02:53:15');

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `profil_desa`
--

CREATE TABLE `profil_desa` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `nama_desa` varchar(100) NOT NULL,
  `kecamatan` varchar(100) NOT NULL,
  `kabupaten` varchar(100) NOT NULL,
  `provinsi` varchar(100) NOT NULL,
  `kode_pos` varchar(10) NOT NULL,
  `thumbnails` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`thumbnails`)),
  `logo` varchar(255) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `telepon` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `visi` text DEFAULT NULL,
  `misi` text DEFAULT NULL,
  `sejarah` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `profil_desa`
--

INSERT INTO `profil_desa` (`id`, `created_by`, `nama_desa`, `kecamatan`, `kabupaten`, `provinsi`, `kode_pos`, `thumbnails`, `logo`, `alamat`, `telepon`, `email`, `website`, `visi`, `misi`, `sejarah`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 'Desa Sariharjo', 'Kapanewon Ngaglik', 'Kabupaten Sleman', 'Daerah Istimewa Yogyakarta', '55581', '[\"uploads\\/desa\\/01K2E56PC193H6MV5RP0MNNW4P.jpg\"]', 'uploads/desa/logo/01K2E56PCBD7F4W7SF9D0S2ECA.png', 'Jl. Palagan Tentara Pelajar, Tegal Rejo, Sariharjo, Kec. Ngaglik, Kabupaten Sleman', '0274-869723', 'sariharjo@cvintermedia.com', 'https://desa.local', 'Mewujudkan Desa Sariharjo yang Maju, Mandiri, dan Sejahtera dengan Pembangunan Berkelanjutan dan Berbasis Kearifan Lokal', '<p>1. Meningkatkan pembangunan infrastruktur yang mendukung perekonomian desa <br>2. Mengoptimalkan pelayanan publik yang transparan dan akuntabel <br>3. Meningkatkan produktivitas pertanian dan perikanan <br>4. Mengembangkan potensi pariwisata dan UMKM <br>5. Melestarikan budaya dan kearifan lokal</p>', '<p>Desa Sariharjo, yang terletak di Kapanewon Ngaglik, Kabupaten Sleman, memiliki sejarah yang kaya. Sebelum tahun 1947, wilayah ini terdiri dari beberapa kelurahan lama, termasuk Rejodani dan Tambakrejo. Nama \"Sariharjo\" sendiri berasal dari bahasa Jawa, di mana \"sari\" berarti inti atau esensi, dan \"harjo\" berarti makmur atau sejahtera, mencerminkan harapan akan kemakmuran dan kesejahteraan wilayah tersebut.&nbsp;<br><br>Sebelum tahun 1947, wilayah Sariharjo terdiri dari beberapa kelurahan lama.&nbsp;Nama \"Sariharjo\" berasal dari bahasa Jawa yang berarti inti atau esensi kemakmuran.&nbsp;Dulunya, wilayah ini merupakan kawasan yang memiliki potensi pertanian yang cukup baik, namun juga memiliki tantangan dalam hal pengembangan.&nbsp;<br><br>Saat ini, Sariharjo telah berkembang menjadi kelurahan yang memiliki beragam mata pencaharian selain pertanian, termasuk bisnis kuliner dan jasa penginapan. Secara administratif, Sariharjo termasuk dalam wilayah Kabupaten Sleman, dan secara geografis terletak di sebelah barat Ibu Kota Kapanewon Ngaglik dengan topografi dataran tinggi.</p>', '2025-08-12 02:53:15', '2025-08-12 03:29:24', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'super_admin', 'web', '2025-08-12 02:53:15', '2025-08-12 02:53:15'),
(2, 'admin', 'web', '2025-08-12 02:53:15', '2025-08-12 02:53:15'),
(3, 'warga', 'web', '2025-08-12 02:53:15', '2025-08-12 02:53:15'),
(4, 'unverified', 'web', '2025-08-12 02:53:15', '2025-08-12 02:53:15');

-- --------------------------------------------------------

--
-- Table structure for table `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_has_permissions`
--

INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(1, 1),
(1, 2),
(2, 1),
(2, 2),
(3, 1),
(3, 2),
(4, 1),
(4, 2),
(5, 1),
(5, 2),
(6, 1),
(6, 2),
(7, 1),
(7, 2),
(8, 1),
(8, 2),
(9, 1),
(9, 2),
(10, 1),
(10, 2),
(11, 1),
(11, 2),
(12, 1),
(12, 2),
(13, 1),
(13, 2),
(14, 1),
(14, 2),
(15, 1),
(15, 2),
(16, 1),
(16, 2),
(17, 1),
(17, 3),
(18, 1),
(18, 3),
(19, 1),
(19, 2),
(20, 1),
(20, 2),
(21, 1),
(21, 2),
(22, 1),
(22, 2),
(23, 1),
(23, 3),
(23, 4),
(24, 1),
(24, 2),
(25, 1),
(26, 1),
(26, 2),
(27, 1),
(28, 1),
(28, 3),
(29, 1),
(29, 3),
(30, 1),
(30, 3),
(31, 1),
(31, 3),
(32, 1),
(32, 3),
(33, 1),
(33, 4),
(34, 1),
(34, 4),
(35, 1),
(35, 4),
(36, 1),
(36, 3),
(36, 4);

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('7p7FvcGSwb7tmjhicCDMvZO9o0gAYo3nkfIItpM7', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'YTo2OntzOjY6Il90b2tlbiI7czo0MDoiVEtwWm1FUjRmWm9OclZodTR2VnBVZlN0UGhjY3Fkd1V5TnAyaWZpNyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MTg6Imh0dHBzOi8vZGVzYS5sb2NhbCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7czoxNzoicGFzc3dvcmRfaGFzaF93ZWIiO3M6NjA6IiQyeSQxMiRIcEZ3SmExbGdxNE55V09FLjRFNmcuVkgvRGZvLmFRdVNXN2xsU01NZDA0andML1FYMmlQRyI7czo4OiJmaWxhbWVudCI7YTowOnt9fQ==', 1754969375),
('lrC1Yv4WbMZloNB8m9MVETS1oIcDUg5DRErHLomf', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiS3pRc3JLWEJOYmp4N3RJUXpIZFF5d1N1Wko3UTZuVzl0UEJ1eGhYMiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjU6Imh0dHBzOi8vZGVzYS5sb2NhbC9wcm9maWwiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1754969498);

-- --------------------------------------------------------

--
-- Table structure for table `struktur_pemerintahan`
--

CREATE TABLE `struktur_pemerintahan` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `profil_desa_id` bigint(20) UNSIGNED NOT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `sambutan_kepala_desa` text DEFAULT NULL,
  `foto_kepala_desa` varchar(255) DEFAULT NULL,
  `nama_kepala_desa` varchar(255) DEFAULT NULL,
  `periode_jabatan` varchar(255) DEFAULT NULL,
  `program_kerja` text DEFAULT NULL,
  `prioritas_program` text DEFAULT NULL,
  `bagan_struktur` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `struktur_pemerintahan`
--

INSERT INTO `struktur_pemerintahan` (`id`, `profil_desa_id`, `created_by`, `sambutan_kepala_desa`, `foto_kepala_desa`, `nama_kepala_desa`, `periode_jabatan`, `program_kerja`, `prioritas_program`, `bagan_struktur`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 1, '<p>Assalamu\'alaikum Wr. Wb.</p>\n                <p>Puji syukur kita panjatkan kehadirat Allah SWT, karena atas berkat dan rahmat-Nya kita masih diberikan kesehatan dan kesempatan untuk menjalankan tugas sebagai pelayan masyarakat di Desa Sariharjo.</p>\n                <p>Sebagai Kepala Desa, saya bersama dengan perangkat desa dan BPD berkomitmen untuk memajukan desa kita dengan program-program yang inovatif dan tepat sasaran. Kami akan fokus pada pembangunan infrastruktur, peningkatan ekonomi warga, dan pelayanan publik yang prima.</p>\n                <p>Website desa ini merupakan salah satu upaya kami untuk meningkatkan transparansi dan kemudahan akses informasi bagi seluruh masyarakat. Melalui website ini, kami berharap masyarakat dapat lebih mudah mendapatkan informasi dan layanan administrasi desa.</p>\n                <p>Mari bersama-sama kita bangun Desa Sariharjo yang lebih baik, maju, dan sejahtera. Dengan kerjasama dan gotong royong, tidak ada yang tidak mungkin untuk kita wujudkan.</p>\n                <p>Wassalamu\'alaikum Wr. Wb.</p>', 'uploads/desa/kepala-desa/01K2E4DFDHF7WPDPA2NZMTERC0.png', 'H. Sarbini, S.Sos', '2021-2026', '<p>Sebagai Kepala Desa Sariharjo, saya berkomitmen untuk memajukan desa kita melalui program-program strategis dalam berbagai bidang:</p>\n                <h3>Pertanian Berkelanjutan</h3>\n                <p>Pengembangan sistem pertanian modern yang ramah lingkungan dan memaksimalkan potensi lahan pertanian desa.</p>\n                \n                <h3>Ekonomi Kreatif</h3>\n                <p>Pemberdayaan ekonomi masyarakat melalui pengembangan UMKM dan produk unggulan desa.</p>\n                \n                <h3>Digitalisasi Desa</h3>\n                <p>Pemanfaatan teknologi untuk meningkatkan kualitas pelayanan publik dan akses informasi bagi seluruh warga.</p>', '<ol>\n                <li><strong>Pembangunan Infrastruktur Pertanian</strong><br>\n                Pengembangan irigasi dan jalan usaha tani untuk mendukung aktivitas pertanian yang lebih produktif.</li>\n                \n                <li><strong>Pengembangan BUMDES</strong><br>\n                Penguatan kapasitas BUMDES dalam pengolahan hasil pertanian untuk meningkatkan nilai tambah produk desa.</li>\n                \n                <li><strong>Pelatihan Digital untuk Pemuda</strong><br>\n                Program pengembangan keterampilan digital bagi generasi muda desa untuk menghadapi era ekonomi digital.</li>\n                \n                <li><strong>Modernisasi Layanan Administrasi</strong><br>\n                Peningkatan sistem administrasi berbasis teknologi untuk pelayanan publik yang lebih efisien.</li>\n                \n                <li><strong>Pengembangan Wisata Desa</strong><br>\n                Pengembangan potensi wisata desa berbasis kearifan lokal dan keunikan budaya setempat.</li>\n              </ol>', NULL, '2025-08-12 02:53:15', '2025-08-12 03:15:38', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `umkm`
--

CREATE TABLE `umkm` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `id_desa` bigint(20) UNSIGNED NOT NULL,
  `penduduk_id` bigint(20) UNSIGNED NOT NULL,
  `nama_usaha` varchar(255) NOT NULL,
  `produk` varchar(255) NOT NULL,
  `kontak_whatsapp` varchar(15) NOT NULL,
  `lokasi` varchar(255) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `kategori` varchar(255) DEFAULT NULL,
  `is_verified` tinyint(1) NOT NULL DEFAULT 0,
  `foto_usaha` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `umkm`
--

INSERT INTO `umkm` (`id`, `id_desa`, `penduduk_id`, `nama_usaha`, `produk`, `kontak_whatsapp`, `lokasi`, `deskripsi`, `kategori`, `is_verified`, `foto_usaha`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 21, 'Kebun facere', 'Buah-buahan', '62828845097', 'RT 8 RW 2, Dusun laborum, East Emmiechester', 'Menjual buah-buahan segar langsung dari kebun. Dijamin kualitas terbaik dan harga bersaing.', 'Pertanian', 1, NULL, '2025-08-03 05:46:09', '2025-08-12 02:53:16', NULL),
(2, 1, 14, 'Pertanian illo', 'Telur Ayam', '62828046206', 'RT 8 RW 4, Dusun ex, Legrosburgh', 'Menjual telur ayam segar langsung dari kebun. Dijamin kualitas terbaik dan harga bersaing.', 'Pertanian', 1, NULL, '2025-06-25 15:55:22', '2025-08-12 02:53:16', NULL),
(3, 1, 12, 'Catering Langosh', 'Gorengan dan Pecel', '62820476779', 'RT 4 RW 3, Dusun deserunt, New Lavada', 'Menyediakan makanan gorengan dan pecel dengan cita rasa khas rumahan. Buka setiap hari dari jam 8 pagi sampai 8 malam.', 'Kuliner', 1, NULL, '2025-07-16 19:29:15', '2025-08-12 02:53:16', NULL),
(4, 1, 1, 'Kerajinan voluptatem', 'Tas Rajut', '62811789705', 'RT 2 RW 1, Dusun voluptatibus, Port Talonhaven', 'Memproduksi tas rajut dengan bahan berkualitas dan dikerjakan oleh pengrajin terampil. Menerima pesanan partai besar dan kecil.', 'Kerajinan', 1, NULL, '2025-06-23 00:39:21', '2025-08-12 02:53:16', NULL),
(5, 1, 13, 'Fashion Swaniawski', 'Tas dan Sepatu', '62878421712', 'RT 1 RW 5, Dusun laboriosam, Volkmanview', 'Menghadirkan tas dan sepatu dengan kualitas terbaik dan harga terjangkau. Tersedia berbagai model dan ukuran untuk semua kalangan.', 'Fashion', 1, NULL, '2025-07-04 07:57:37', '2025-08-12 02:53:16', NULL),
(6, 1, 4, 'Sate Herman', 'Kue dan Roti', '62880939208', 'RT 10 RW 5, Dusun neque, Port Fredrick', 'Menyediakan makanan kue dan roti dengan cita rasa khas rumahan. Buka setiap hari dari jam 8 pagi sampai 8 malam.', 'Kuliner', 1, NULL, '2025-08-02 12:33:55', '2025-08-12 02:53:16', NULL),
(7, 1, 10, 'UMKM Purdy', 'Produk tempora dan et', '62873016629', 'RT 2 RW 2, Dusun recusandae, East Colten', 'UMKM yang bergerak di bidang Lainnya menjual produk tempora dan et. Melayani pesanan untuk berbagai kebutuhan.', 'Lainnya', 1, NULL, '2025-07-30 11:56:16', '2025-08-12 02:53:16', NULL),
(8, 1, 10, 'Ukiran Weber', 'Hiasan Dinding', '62837391197', 'RT 9 RW 1, Dusun aut, Corwinside', 'Memproduksi hiasan dinding dengan bahan berkualitas dan dikerjakan oleh pengrajin terampil. Menerima pesanan partai besar dan kecil.', 'Kerajinan', 1, NULL, '2025-07-21 07:42:20', '2025-08-12 02:53:16', NULL),
(9, 1, 19, 'Sate O\'Hara', 'Martabak', '62886164198', 'RT 10 RW 2, Dusun voluptate, Laneymouth', 'Menyediakan makanan martabak dengan cita rasa khas rumahan. Buka setiap hari dari jam 8 pagi sampai 8 malam.', 'Kuliner', 1, NULL, '2025-07-19 17:41:09', '2025-08-12 02:53:16', NULL),
(10, 1, 22, 'Jahitan Koelpin', 'Kaos Sablon', '62867105676', 'RT 7 RW 2, Dusun rerum, Strosinmouth', 'Menghadirkan kaos sablon dengan kualitas terbaik dan harga terjangkau. Tersedia berbagai model dan ukuran untuk semua kalangan.', 'Fashion', 1, NULL, '2025-07-27 05:51:39', '2025-08-12 02:53:16', NULL),
(11, 1, 10, 'Anyaman Deckow', 'Hiasan Dinding', '62871596024', 'RT 6 RW 4, Dusun qui, Uptonville', 'Memproduksi hiasan dinding dengan bahan berkualitas dan dikerjakan oleh pengrajin terampil. Menerima pesanan partai besar dan kecil.', 'Kerajinan', 1, NULL, '2025-05-26 01:06:59', '2025-08-12 02:53:16', NULL),
(12, 1, 19, 'Anyaman Stanton', 'Ukiran Kayu', '62864385582', 'RT 7 RW 1, Dusun qui, Lake Tillmanfort', 'Memproduksi ukiran kayu dengan bahan berkualitas dan dikerjakan oleh pengrajin terampil. Menerima pesanan partai besar dan kecil.', 'Kerajinan', 1, NULL, '2025-08-05 06:42:49', '2025-08-12 02:53:16', NULL),
(13, 1, 19, 'Tani Dooley', 'Beras', '62883503991', 'RT 7 RW 2, Dusun aut, North Roelhaven', 'Menjual beras segar langsung dari kebun. Dijamin kualitas terbaik dan harga bersaing.', 'Pertanian', 1, NULL, '2025-05-19 22:44:33', '2025-08-12 02:53:16', NULL),
(14, 1, 20, 'Bengkel Jenkins', 'Salon Kecantikan', '62830295614', 'RT 1 RW 5, Dusun repellendus, Johnstonview', 'Melayani salon kecantikan dengan profesional dan harga terjangkau. Kepuasan pelanggan adalah prioritas kami.', 'Jasa', 1, NULL, '2025-06-02 05:05:29', '2025-08-12 02:53:16', NULL),
(15, 1, 5, 'Jasa sint', 'Jasa Antar', '62854597733', 'RT 7 RW 4, Dusun incidunt, North Garett', 'Melayani jasa antar dengan profesional dan harga terjangkau. Kepuasan pelanggan adalah prioritas kami.', 'Jasa', 1, NULL, '2025-08-05 05:58:34', '2025-08-12 02:53:16', NULL),
(16, 1, 7, 'Servis Conn', 'Laundry', '62882762174', 'RT 9 RW 2, Dusun libero, South Albertha', 'Melayani laundry dengan profesional dan harga terjangkau. Kepuasan pelanggan adalah prioritas kami.', 'Jasa', 0, NULL, '2025-06-11 13:52:52', '2025-08-12 02:53:16', NULL),
(17, 1, 24, 'Batik Deckow', 'Hiasan Dinding', '62840845285', 'RT 4 RW 5, Dusun enim, New Elva', 'Memproduksi hiasan dinding dengan bahan berkualitas dan dikerjakan oleh pengrajin terampil. Menerima pesanan partai besar dan kecil.', 'Kerajinan', 0, NULL, '2025-05-14 15:44:56', '2025-08-12 02:53:16', NULL),
(18, 1, 8, 'Warteg Greenfelder', 'Pisang Goreng', '62812104492', 'RT 8 RW 1, Dusun est, Lake Nicholestad', 'Menyediakan makanan pisang goreng dengan cita rasa khas rumahan. Buka setiap hari dari jam 8 pagi sampai 8 malam.', 'Kuliner', 0, NULL, '2025-06-25 10:19:36', '2025-08-12 02:53:16', NULL),
(19, 1, 6, 'Servis Thiel', 'Salon Kecantikan', '62818045711', 'RT 3 RW 3, Dusun corrupti, Port Fredy', 'Melayani salon kecantikan dengan profesional dan harga terjangkau. Kepuasan pelanggan adalah prioritas kami.', 'Jasa', 0, NULL, '2025-07-18 01:19:03', '2025-08-12 02:53:16', NULL),
(20, 1, 24, 'Kerajinan quisquam', 'Gerabah', '62867224042', 'RT 1 RW 3, Dusun omnis, Amaliaborough', 'Memproduksi gerabah dengan bahan berkualitas dan dikerjakan oleh pengrajin terampil. Menerima pesanan partai besar dan kecil.', 'Kerajinan', 0, NULL, '2025-06-28 16:39:54', '2025-08-12 02:53:16', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `nik` varchar(16) DEFAULT NULL,
  `penduduk_id` bigint(20) UNSIGNED DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `profile_photo_path` varchar(255) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `nik`, `penduduk_id`, `email_verified_at`, `password`, `profile_photo_path`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Kepala Desa', 'admin@cvintermedia.com', NULL, NULL, NULL, '$2y$12$HpFwJa1lgq4NyWOE.4E6g.VH/Dfo.aQuSW7llSMMd04jwL/QX2iPG', NULL, NULL, '2025-08-12 02:53:15', '2025-08-12 02:53:15'),
(2, 'Admin Desa', 'admin_desa@desaku.com', NULL, NULL, NULL, '$2y$12$aLOH7mxVAvqfxk/vxtwJf.x3syavw0QXkm3cg9brxxUsdbLj8lBCi', NULL, NULL, '2025-08-12 02:53:15', '2025-08-12 02:53:15'),
(3, 'User Baru', 'new_user@desaku.com', NULL, NULL, NULL, '$2y$12$jX3qrKKiBKu0Q/qpDZAhZO5eaytzfHfyXvfR1JZ6NvAMgpYuyheOO', NULL, NULL, '2025-08-12 02:53:15', '2025-08-12 02:53:15');

-- --------------------------------------------------------

--
-- Table structure for table `verifikasi_penduduk`
--

CREATE TABLE `verifikasi_penduduk` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `penduduk_id` bigint(20) UNSIGNED DEFAULT NULL,
  `id_desa` bigint(20) UNSIGNED NOT NULL,
  `nik` varchar(16) NOT NULL,
  `kk` varchar(16) NOT NULL,
  `kepala_keluarga_id` bigint(20) UNSIGNED DEFAULT NULL,
  `nama` varchar(100) NOT NULL,
  `alamat` text NOT NULL,
  `rt_rw` varchar(10) NOT NULL,
  `tempat_lahir` varchar(255) DEFAULT NULL,
  `tanggal_lahir` date NOT NULL,
  `jenis_kelamin` varchar(255) DEFAULT NULL,
  `agama` varchar(255) DEFAULT NULL,
  `status_perkawinan` varchar(255) DEFAULT NULL,
  `kepala_keluarga` tinyint(1) NOT NULL DEFAULT 0,
  `pekerjaan` varchar(100) DEFAULT NULL,
  `pendidikan` varchar(100) DEFAULT NULL,
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `catatan` text DEFAULT NULL,
  `no_hp` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `golongan_darah` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `verifikasi_penduduk`
--

INSERT INTO `verifikasi_penduduk` (`id`, `user_id`, `penduduk_id`, `id_desa`, `nik`, `kk`, `kepala_keluarga_id`, `nama`, `alamat`, `rt_rw`, `tempat_lahir`, `tanggal_lahir`, `jenis_kelamin`, `agama`, `status_perkawinan`, `kepala_keluarga`, `pekerjaan`, `pendidikan`, `status`, `catatan`, `no_hp`, `email`, `golongan_darah`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 3, NULL, 1, '8331770806073165', '0236029901461765', NULL, 'User Baru', '630 Padberg Wall\nElsieville, MT 88537', '015/003', 'North Cayla', '2022-03-11', 'P', 'Katolik', 'Cerai Hidup', 0, 'Camera Repairer', 'SMP', 'pending', NULL, NULL, NULL, NULL, '2025-08-12 02:53:16', '2025-08-12 02:53:16', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `aparat_desa`
--
ALTER TABLE `aparat_desa`
  ADD PRIMARY KEY (`id`),
  ADD KEY `aparat_desa_struktur_pemerintahan_id_foreign` (`struktur_pemerintahan_id`);

--
-- Indexes for table `bansos`
--
ALTER TABLE `bansos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bansos_penduduk_id_foreign` (`penduduk_id`),
  ADD KEY `bansos_diubah_oleh_foreign` (`diubah_oleh`),
  ADD KEY `bansos_id_desa_penduduk_id_index` (`id_desa`,`penduduk_id`),
  ADD KEY `bansos_jenis_bansos_id_index` (`jenis_bansos_id`),
  ADD KEY `bansos_status_index` (`status`),
  ADD KEY `bansos_tanggal_pengajuan_index` (`tanggal_pengajuan`),
  ADD KEY `bansos_is_urgent_index` (`is_urgent`);

--
-- Indexes for table `bansos_history`
--
ALTER TABLE `bansos_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bansos_history_bansos_id_foreign` (`bansos_id`),
  ADD KEY `bansos_history_diubah_oleh_foreign` (`diubah_oleh`);

--
-- Indexes for table `batas_wilayah_potensi`
--
ALTER TABLE `batas_wilayah_potensi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `batas_wilayah_potensi_profil_desa_id_foreign` (`profil_desa_id`),
  ADD KEY `batas_wilayah_potensi_created_by_foreign` (`created_by`);

--
-- Indexes for table `berita`
--
ALTER TABLE `berita`
  ADD PRIMARY KEY (`id`),
  ADD KEY `berita_id_desa_foreign` (`id_desa`),
  ADD KEY `berita_created_by_foreign` (`created_by`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `exports`
--
ALTER TABLE `exports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `exports_user_id_foreign` (`user_id`);

--
-- Indexes for table `failed_import_rows`
--
ALTER TABLE `failed_import_rows`
  ADD PRIMARY KEY (`id`),
  ADD KEY `failed_import_rows_import_id_foreign` (`import_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `imports`
--
ALTER TABLE `imports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `imports_user_id_foreign` (`user_id`);

--
-- Indexes for table `inventaris`
--
ALTER TABLE `inventaris`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `inventaris_kode_barang_unique` (`kode_barang`),
  ADD KEY `inventaris_id_desa_foreign` (`id_desa`),
  ADD KEY `inventaris_created_by_foreign` (`created_by`);

--
-- Indexes for table `jenis_bansos`
--
ALTER TABLE `jenis_bansos`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kartu_keluarga`
--
ALTER TABLE `kartu_keluarga`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kartu_keluarga_nomor_kk_unique` (`nomor_kk`),
  ADD KEY `kartu_keluarga_id_desa_foreign` (`id_desa`),
  ADD KEY `kartu_keluarga_kepala_keluarga_id_foreign` (`kepala_keluarga_id`);

--
-- Indexes for table `keuangan_desa`
--
ALTER TABLE `keuangan_desa`
  ADD PRIMARY KEY (`id`),
  ADD KEY `keuangan_desa_created_by_foreign` (`created_by`),
  ADD KEY `keuangan_desa_jenis_index` (`jenis`),
  ADD KEY `keuangan_desa_tanggal_index` (`tanggal`),
  ADD KEY `keuangan_desa_id_desa_jenis_index` (`id_desa`,`jenis`),
  ADD KEY `keuangan_desa_jenis_tanggal_index` (`jenis`,`tanggal`);

--
-- Indexes for table `layanan_desa`
--
ALTER TABLE `layanan_desa`
  ADD PRIMARY KEY (`id`),
  ADD KEY `layanan_desa_id_desa_foreign` (`id_desa`),
  ADD KEY `layanan_desa_created_by_foreign` (`created_by`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  ADD KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  ADD KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `penduduk`
--
ALTER TABLE `penduduk`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `penduduk_nik_unique` (`nik`),
  ADD KEY `penduduk_id_desa_foreign` (`id_desa`),
  ADD KEY `penduduk_kepala_keluarga_id_foreign` (`kepala_keluarga_id`),
  ADD KEY `penduduk_user_id_foreign` (`user_id`);

--
-- Indexes for table `pengaduan`
--
ALTER TABLE `pengaduan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pengaduan_id_desa_foreign` (`id_desa`),
  ADD KEY `pengaduan_penduduk_id_foreign` (`penduduk_id`),
  ADD KEY `pengaduan_ditangani_oleh_foreign` (`ditangani_oleh`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `profil_desa`
--
ALTER TABLE `profil_desa`
  ADD PRIMARY KEY (`id`),
  ADD KEY `profil_desa_created_by_foreign` (`created_by`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `struktur_pemerintahan`
--
ALTER TABLE `struktur_pemerintahan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `struktur_pemerintahan_profil_desa_id_foreign` (`profil_desa_id`),
  ADD KEY `struktur_pemerintahan_created_by_foreign` (`created_by`);

--
-- Indexes for table `umkm`
--
ALTER TABLE `umkm`
  ADD PRIMARY KEY (`id`),
  ADD KEY `umkm_id_desa_foreign` (`id_desa`),
  ADD KEY `umkm_penduduk_id_foreign` (`penduduk_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_penduduk_id_foreign` (`penduduk_id`);

--
-- Indexes for table `verifikasi_penduduk`
--
ALTER TABLE `verifikasi_penduduk`
  ADD PRIMARY KEY (`id`),
  ADD KEY `verifikasi_penduduk_user_id_foreign` (`user_id`),
  ADD KEY `verifikasi_penduduk_penduduk_id_foreign` (`penduduk_id`),
  ADD KEY `verifikasi_penduduk_id_desa_foreign` (`id_desa`),
  ADD KEY `verifikasi_penduduk_kepala_keluarga_id_foreign` (`kepala_keluarga_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `aparat_desa`
--
ALTER TABLE `aparat_desa`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `bansos`
--
ALTER TABLE `bansos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `bansos_history`
--
ALTER TABLE `bansos_history`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=79;

--
-- AUTO_INCREMENT for table `batas_wilayah_potensi`
--
ALTER TABLE `batas_wilayah_potensi`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `berita`
--
ALTER TABLE `berita`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `exports`
--
ALTER TABLE `exports`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_import_rows`
--
ALTER TABLE `failed_import_rows`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `imports`
--
ALTER TABLE `imports`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inventaris`
--
ALTER TABLE `inventaris`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `jenis_bansos`
--
ALTER TABLE `jenis_bansos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kartu_keluarga`
--
ALTER TABLE `kartu_keluarga`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `keuangan_desa`
--
ALTER TABLE `keuangan_desa`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `layanan_desa`
--
ALTER TABLE `layanan_desa`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `penduduk`
--
ALTER TABLE `penduduk`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `pengaduan`
--
ALTER TABLE `pengaduan`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `profil_desa`
--
ALTER TABLE `profil_desa`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `struktur_pemerintahan`
--
ALTER TABLE `struktur_pemerintahan`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `umkm`
--
ALTER TABLE `umkm`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `verifikasi_penduduk`
--
ALTER TABLE `verifikasi_penduduk`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `aparat_desa`
--
ALTER TABLE `aparat_desa`
  ADD CONSTRAINT `aparat_desa_struktur_pemerintahan_id_foreign` FOREIGN KEY (`struktur_pemerintahan_id`) REFERENCES `struktur_pemerintahan` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `bansos`
--
ALTER TABLE `bansos`
  ADD CONSTRAINT `bansos_diubah_oleh_foreign` FOREIGN KEY (`diubah_oleh`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `bansos_id_desa_foreign` FOREIGN KEY (`id_desa`) REFERENCES `profil_desa` (`id`),
  ADD CONSTRAINT `bansos_jenis_bansos_id_foreign` FOREIGN KEY (`jenis_bansos_id`) REFERENCES `jenis_bansos` (`id`),
  ADD CONSTRAINT `bansos_penduduk_id_foreign` FOREIGN KEY (`penduduk_id`) REFERENCES `penduduk` (`id`);

--
-- Constraints for table `bansos_history`
--
ALTER TABLE `bansos_history`
  ADD CONSTRAINT `bansos_history_bansos_id_foreign` FOREIGN KEY (`bansos_id`) REFERENCES `bansos` (`id`),
  ADD CONSTRAINT `bansos_history_diubah_oleh_foreign` FOREIGN KEY (`diubah_oleh`) REFERENCES `users` (`id`);

--
-- Constraints for table `batas_wilayah_potensi`
--
ALTER TABLE `batas_wilayah_potensi`
  ADD CONSTRAINT `batas_wilayah_potensi_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `batas_wilayah_potensi_profil_desa_id_foreign` FOREIGN KEY (`profil_desa_id`) REFERENCES `profil_desa` (`id`);

--
-- Constraints for table `berita`
--
ALTER TABLE `berita`
  ADD CONSTRAINT `berita_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `berita_id_desa_foreign` FOREIGN KEY (`id_desa`) REFERENCES `profil_desa` (`id`);

--
-- Constraints for table `exports`
--
ALTER TABLE `exports`
  ADD CONSTRAINT `exports_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `failed_import_rows`
--
ALTER TABLE `failed_import_rows`
  ADD CONSTRAINT `failed_import_rows_import_id_foreign` FOREIGN KEY (`import_id`) REFERENCES `imports` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `imports`
--
ALTER TABLE `imports`
  ADD CONSTRAINT `imports_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `inventaris`
--
ALTER TABLE `inventaris`
  ADD CONSTRAINT `inventaris_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `inventaris_id_desa_foreign` FOREIGN KEY (`id_desa`) REFERENCES `profil_desa` (`id`);

--
-- Constraints for table `kartu_keluarga`
--
ALTER TABLE `kartu_keluarga`
  ADD CONSTRAINT `kartu_keluarga_id_desa_foreign` FOREIGN KEY (`id_desa`) REFERENCES `profil_desa` (`id`),
  ADD CONSTRAINT `kartu_keluarga_kepala_keluarga_id_foreign` FOREIGN KEY (`kepala_keluarga_id`) REFERENCES `penduduk` (`id`);

--
-- Constraints for table `keuangan_desa`
--
ALTER TABLE `keuangan_desa`
  ADD CONSTRAINT `keuangan_desa_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `keuangan_desa_id_desa_foreign` FOREIGN KEY (`id_desa`) REFERENCES `profil_desa` (`id`);

--
-- Constraints for table `layanan_desa`
--
ALTER TABLE `layanan_desa`
  ADD CONSTRAINT `layanan_desa_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `layanan_desa_id_desa_foreign` FOREIGN KEY (`id_desa`) REFERENCES `profil_desa` (`id`);

--
-- Constraints for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `penduduk`
--
ALTER TABLE `penduduk`
  ADD CONSTRAINT `penduduk_id_desa_foreign` FOREIGN KEY (`id_desa`) REFERENCES `profil_desa` (`id`),
  ADD CONSTRAINT `penduduk_kepala_keluarga_id_foreign` FOREIGN KEY (`kepala_keluarga_id`) REFERENCES `penduduk` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `penduduk_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `pengaduan`
--
ALTER TABLE `pengaduan`
  ADD CONSTRAINT `pengaduan_ditangani_oleh_foreign` FOREIGN KEY (`ditangani_oleh`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `pengaduan_id_desa_foreign` FOREIGN KEY (`id_desa`) REFERENCES `profil_desa` (`id`),
  ADD CONSTRAINT `pengaduan_penduduk_id_foreign` FOREIGN KEY (`penduduk_id`) REFERENCES `penduduk` (`id`);

--
-- Constraints for table `profil_desa`
--
ALTER TABLE `profil_desa`
  ADD CONSTRAINT `profil_desa_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `struktur_pemerintahan`
--
ALTER TABLE `struktur_pemerintahan`
  ADD CONSTRAINT `struktur_pemerintahan_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `struktur_pemerintahan_profil_desa_id_foreign` FOREIGN KEY (`profil_desa_id`) REFERENCES `profil_desa` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `umkm`
--
ALTER TABLE `umkm`
  ADD CONSTRAINT `umkm_id_desa_foreign` FOREIGN KEY (`id_desa`) REFERENCES `profil_desa` (`id`),
  ADD CONSTRAINT `umkm_penduduk_id_foreign` FOREIGN KEY (`penduduk_id`) REFERENCES `penduduk` (`id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_penduduk_id_foreign` FOREIGN KEY (`penduduk_id`) REFERENCES `penduduk` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `verifikasi_penduduk`
--
ALTER TABLE `verifikasi_penduduk`
  ADD CONSTRAINT `verifikasi_penduduk_id_desa_foreign` FOREIGN KEY (`id_desa`) REFERENCES `profil_desa` (`id`),
  ADD CONSTRAINT `verifikasi_penduduk_kepala_keluarga_id_foreign` FOREIGN KEY (`kepala_keluarga_id`) REFERENCES `penduduk` (`id`),
  ADD CONSTRAINT `verifikasi_penduduk_penduduk_id_foreign` FOREIGN KEY (`penduduk_id`) REFERENCES `penduduk` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `verifikasi_penduduk_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
