-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 28 Jul 2024 pada 21.36
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `makarya`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `employees`
--

CREATE TABLE `employees` (
  `id` int(11) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `alamat` text NOT NULL,
  `jabatan` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `email` varchar(100) NOT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `employees`
--

INSERT INTO `employees` (`id`, `nama`, `alamat`, `jabatan`, `phone`, `email`, `foto`, `created_at`) VALUES
(1, 'Rina Maya Sari', 'Magelang, Jawa Tengah', 'Manager', '+62 821-2345-6789', 'rina.maya.sari@gmail.com', 'rina-maya-sari.png', '2024-07-16 12:04:36'),
(2, 'Siti Nurhaliza', 'Semarang, Jawa Tengah', 'Manager', '+62 822-1234-5671', 'siti.nurhaliza@gmail.com', 'siti-nurhaliza.png', '2024-07-16 12:04:36'),
(3, 'Bella Indah Puspita', 'Purworejo, Jawa Tengah', 'Manager', '+62 823-5678-1232', 'bella.indah@gmail.com', 'bella-indah-puspita.png', '2024-07-16 12:04:36'),
(4, 'Dinda Fira Putri', 'Temanggung, Jawa Tengah', 'Manager', '+62 824-4567-8903', 'dinda.fira@gmail.com', 'dinda-fira-putri.png', '2024-07-16 12:04:36'),
(5, 'Tika Ayu Setiawan', 'Salatiga, Jawa Tengah', 'Manager', '+62 825-6789-0124', 'tika.ayu@gmail.com', 'tika-ayu-setiawan.png', '2024-07-16 12:04:36'),
(6, 'Lestari Dian Purnama', 'Jepara, Jawa Tengah', 'Manager', '+62 826-7890-1235', 'lestari.dian@gmail.com', 'lestari-dian-purnama.png', '2024-07-16 12:04:36'),
(7, 'Indriani Rachmawati', 'Kendal, Jawa Tengah', 'Staff', '+62 827-8901-2346', 'indriani.rachmawati@gmail.com', 'indriani-rachmawati.png', '2024-07-16 12:04:36');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user','superadmin') NOT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `username`, `full_name`, `password`, `role`, `foto`, `created_at`) VALUES
(1, 'superadmin', 'Super Admin', '$2y$10$1RGljCrmiVUaQQLvwa0bFOwCPXQAIxNhuc.hrJAkKFlGz9.pQjcHq', 'superadmin', 'super-admin.png', '2024-07-18 21:34:04'),
(2, 'admin', 'Admin', '$2y$10$0bi/HnKdn25mnDTvpqqwDe3vBxx38mn4mbdFr.CKY.QlRM2yG0tdi', 'admin', 'man.png', '2024-07-18 21:05:23'),
(3, 'user', 'User', '$2y$10$4DPTv8fhSoG7LSvGA39OSe9fJi5nBF53kcrp8c2pBs9rb2jTK22w6', 'user', 'woman.png', '2024-07-18 21:15:06'),
(4, 'eliyantosarage', 'Jagad Brahma Wiraatmaja', '$2y$10$lH1qZjZJNbcL7KpnGd8NG.Es.V7dt50enyUjixiKhRzKESC8FWYMS', 'user', NULL, '2024-07-20 15:34:19');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `phone` (`phone`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `employees`
--
ALTER TABLE `employees`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
