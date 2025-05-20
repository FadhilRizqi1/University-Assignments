-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 11 Bulan Mei 2025 pada 08.15
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
-- Database: `db_kursus_daring`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `instruktur`
--

CREATE TABLE `instruktur` (
  `id_instruktur` int(11) NOT NULL,
  `nama_instruktur` varchar(255) NOT NULL,
  `email_instruktur` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `instruktur`
--

INSERT INTO `instruktur` (`id_instruktur`, `nama_instruktur`, `email_instruktur`) VALUES
(1, 'Dr. Budi Santoso', 'budi.s@gmail.com'),
(2, 'Prof. Ani Wijaya', 'ani.w@gmail.com'),
(3, 'Retno Wulandari', 'retno.w@gmail.com');

-- --------------------------------------------------------

--
-- Struktur dari tabel `kursus`
--

CREATE TABLE `kursus` (
  `id_kursus` int(11) NOT NULL,
  `nama_kursus` varchar(255) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `id_instruktur` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `kursus`
--

INSERT INTO `kursus` (`id_kursus`, `nama_kursus`, `deskripsi`, `id_instruktur`) VALUES
(1, 'Dasar Pemrograman Web', 'Pengenalan HTML, CSS, dan JavaScript.', 1),
(2, 'Algoritma Lanjut', 'Mempelajari algoritma dan struktur data tingkat lanjut.', 2),
(3, 'Manajemen Basis Data', 'Konsep dan implementasi basis data relasional.', 1),
(4, 'Statistika Dasar', 'Pengantar konsep statistika.', 2),
(5, 'Pemrograman Python', 'Belajar Python dari dasar.', 1),
(6, 'Keamanan Siber', 'Dasar-dasar keamanan informasi.', 1),
(7, 'Machine Learning Dasar', 'Pengenalan Machine Learning.', 3);

-- --------------------------------------------------------

--
-- Struktur dari tabel `pendaftaran`
--

CREATE TABLE `pendaftaran` (
  `id_pendaftaran` int(11) NOT NULL,
  `id_pengguna` int(11) DEFAULT NULL,
  `id_kursus` int(11) DEFAULT NULL,
  `tgl_pendaftaran` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pendaftaran`
--

INSERT INTO `pendaftaran` (`id_pendaftaran`, `id_pengguna`, `id_kursus`, `tgl_pendaftaran`) VALUES
(1, 1, 1, '2025-05-10 16:29:35'),
(2, 1, 3, '2025-05-10 16:29:35'),
(3, 1, 5, '2025-05-10 16:29:35'),
(4, 2, 1, '2025-05-10 16:29:35'),
(5, 2, 2, '2025-05-10 16:29:35'),
(6, 2, 4, '2025-05-10 16:29:35'),
(7, 2, 6, '2025-05-10 16:29:35'),
(8, 3, 7, '2025-05-10 16:33:05'),
(9, 3, 2, '2025-05-10 18:50:43'),
(10, 4, 6, '2025-05-10 19:07:11'),
(11, 5, 2, '2025-05-11 06:13:28'),
(12, 5, 7, '2025-05-11 06:13:28');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengguna`
--

CREATE TABLE `pengguna` (
  `id_pengguna` int(11) NOT NULL,
  `nama_lengkap` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `kata_sandi` varchar(255) NOT NULL,
  `tgl_gabung` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pengguna`
--

INSERT INTO `pengguna` (`id_pengguna`, `nama_lengkap`, `email`, `kata_sandi`, `tgl_gabung`) VALUES
(1, 'Dewi Anggraini', 'dewi@gmail.com', '$2y$10$E.qg3hYTVM26NgsBE3s2AOPmHIk8Qx2xZ80.ExF/2SCnVeoG4G66C', '2025-05-10 16:29:35'),
(2, 'Rangga Saputra', 'rangga@gmail.com', '$2y$10$tN3sB8qYV2oP7rLgE0f1DOJmK3kI9y3wZ71.GxG/5SDnVeoH5G77D', '2025-05-10 16:29:35'),
(3, 'Ahmad Fadhil Rizqi', 'fadhilrizqi@gmail.com', '$2y$10$Cd3UP4O5za34/t0fGxUkEeKEVeiGEn8MQIgB3aA7RYOhl6uKGmuL.', '2025-05-10 16:32:59'),
(4, 'Bayu Samudra', 'Samudra@gmail.com', '$2y$10$BFRK.SzOO/SYMhawZTlWpueRnqigHhsj3x.4SzeikX3v55sGRIX6O', '2025-05-10 19:07:01'),
(5, 'Jeremiah Alwin Siahaan', 'jere@gmail.com', '$2y$10$ZM5zi/iVAWBoHcEBcT9NKeGEd2oyt8gwdPdbr19SpfDJpKMp9gbQa', '2025-05-11 06:12:24');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `instruktur`
--
ALTER TABLE `instruktur`
  ADD PRIMARY KEY (`id_instruktur`),
  ADD UNIQUE KEY `email_instruktur` (`email_instruktur`);

--
-- Indeks untuk tabel `kursus`
--
ALTER TABLE `kursus`
  ADD PRIMARY KEY (`id_kursus`),
  ADD KEY `id_instruktur` (`id_instruktur`);

--
-- Indeks untuk tabel `pendaftaran`
--
ALTER TABLE `pendaftaran`
  ADD PRIMARY KEY (`id_pendaftaran`),
  ADD UNIQUE KEY `id_pengguna` (`id_pengguna`,`id_kursus`),
  ADD KEY `id_kursus` (`id_kursus`);

--
-- Indeks untuk tabel `pengguna`
--
ALTER TABLE `pengguna`
  ADD PRIMARY KEY (`id_pengguna`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `instruktur`
--
ALTER TABLE `instruktur`
  MODIFY `id_instruktur` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `kursus`
--
ALTER TABLE `kursus`
  MODIFY `id_kursus` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `pendaftaran`
--
ALTER TABLE `pendaftaran`
  MODIFY `id_pendaftaran` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT untuk tabel `pengguna`
--
ALTER TABLE `pengguna`
  MODIFY `id_pengguna` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `kursus`
--
ALTER TABLE `kursus`
  ADD CONSTRAINT `kursus_ibfk_1` FOREIGN KEY (`id_instruktur`) REFERENCES `instruktur` (`id_instruktur`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `pendaftaran`
--
ALTER TABLE `pendaftaran`
  ADD CONSTRAINT `pendaftaran_ibfk_1` FOREIGN KEY (`id_pengguna`) REFERENCES `pengguna` (`id_pengguna`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `pendaftaran_ibfk_2` FOREIGN KEY (`id_kursus`) REFERENCES `kursus` (`id_kursus`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
