-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Waktu pembuatan: 18 Des 2024 pada 15.19
-- Versi server: 8.0.30
-- Versi PHP: 8.3.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ferigodb`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `jadwal`
--

CREATE TABLE `jadwal` (
  `pelabuhan_asal` varchar(20) NOT NULL,
  `pelabuhan_tujuan` varchar(20) NOT NULL,
  `layanan` varchar(10) NOT NULL,
  `harga` int NOT NULL,
  `jadwal_masuk` date NOT NULL,
  `jam_masuk` varchar(20) NOT NULL,
  `umur` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `jadwal`
--

INSERT INTO `jadwal` (`pelabuhan_asal`, `pelabuhan_tujuan`, `layanan`, `harga`, `jadwal_masuk`, `jam_masuk`, `umur`) VALUES
('Bakauheni, Lampung', 'Merak, Banten', 'Reguler', 1800, '2024-11-28', '7:00', 'Di bawah 2 tahun'),
('Merak, Banten', 'Bakauheni, Lampung', 'Reguler', 1800, '2024-11-28', '7:00', 'Di bawah 2 tahun'),
('Bakauheni, Lampung', 'Merak, Banten', 'Eksekutif', 4000, '2024-11-28', '7:00', 'Di bawah 2 tahun'),
('Merak, Banten', 'Bakauheni, Lampung', 'Eksekutif', 4000, '2024-11-28', '7:00', 'Di bawah 2 tahun'),
('Bakauheni, Lampung', 'Merak, Banten', 'Reguler', 22700, '2024-11-28', '7:00', 'Di atas 6 tahun'),
('Merak, Banten', 'Bakauheni, Lampung', 'Reguler', 22700, '2024-11-28', '7:00', 'Di atas 6 tahun'),
('Bakauheni, Lampung', 'Merak, Banten', 'Eksekutif', 84000, '2024-11-28', '7:00', 'Di atas 6 tahun'),
('Merak, Banten', 'Bakauheni, Lampung', 'Eksekutif', 84000, '2024-11-28', '7:00', 'Di atas 6 tahun'),
('Bakauheni, Lampung', 'Merak, Banten', 'Reguler', 1800, '2024-11-28', '12:00', 'Di bawah 2 tahun'),
('Merak, Banten', 'Bakauheni, Lampung', 'Reguler', 1800, '2024-11-28', '12:00', 'Di bawah 2 tahun'),
('Bakauheni, Lampung', 'Merak, Banten', 'Eksekutif', 4000, '2024-11-28', '12:00', 'Di bawah 2 tahun'),
('Merak, Banten', 'Bakauheni, Lampung', 'Eksekutif', 4000, '2024-11-28', '12:00', 'Di bawah 2 tahun'),
('Bakauheni, Lampung', 'Merak, Banten', 'Reguler', 22700, '2024-11-28', '12:00', 'Di atas 6 tahun'),
('Merak, Banten', 'Bakauheni, Lampung', 'Reguler', 22700, '2024-11-28', '12:00', 'Di atas 6 tahun'),
('Bakauheni, Lampung', 'Merak, Banten', 'Eksekutif', 84000, '2024-11-28', '12:00', 'Di atas 6 tahun'),
('Merak, Banten', 'Bakauheni, Lampung', 'Eksekutif', 84000, '2024-11-28', '12:00', 'Di atas 6 tahun'),
('Bakauheni, Lampung', 'Merak, Banten', 'Reguler', 1800, '2024-11-28', '20:00', 'Di bawah 2 tahun'),
('Merak, Banten', 'Bakauheni, Lampung', 'Reguler', 1800, '2024-11-28', '20:00', 'Di bawah 2 tahun'),
('Bakauheni, Lampung', 'Merak, Banten', 'Eksekutif', 4000, '2024-11-28', '20:00', 'Di bawah 2 tahun'),
('Merak, Banten', 'Bakauheni, Lampung', 'Eksekutif', 4000, '2024-11-28', '20:00', 'Di bawah 2 tahun'),
('Bakauheni, Lampung', 'Merak, Banten', 'Reguler', 22700, '2024-11-28', '20:00', 'Di atas 6 tahun'),
('Merak, Banten', 'Bakauheni, Lampung', 'Reguler', 22700, '2024-11-28', '20:00', 'Di atas 6 tahun'),
('Bakauheni, Lampung', 'Merak, Banten', 'Eksekutif', 84000, '2024-11-28', '20:00', 'Di atas 6 tahun'),
('Merak, Banten', 'Bakauheni, Lampung', 'Eksekutif', 84000, '2024-11-28', '20:00', 'Di atas 6 tahun'),
('Bakauheni, Lampung', 'Merak, Banten', 'Reguler', 1800, '2024-11-29', '7:00', 'Di bawah 2 tahun'),
('Merak, Banten', 'Bakauheni, Lampung', 'Reguler', 1800, '2024-11-29', '7:00', 'Di bawah 2 tahun'),
('Bakauheni, Lampung', 'Merak, Banten', 'Eksekutif', 4000, '2024-11-29', '7:00', 'Di bawah 2 tahun'),
('Merak, Banten', 'Bakauheni, Lampung', 'Eksekutif', 4000, '2024-11-29', '7:00', 'Di bawah 2 tahun'),
('Bakauheni, Lampung', 'Merak, Banten', 'Reguler', 22700, '2024-11-29', '7:00', 'Di atas 6 tahun'),
('Merak, Banten', 'Bakauheni, Lampung', 'Reguler', 22700, '2024-11-29', '7:00', 'Di atas 6 tahun'),
('Bakauheni, Lampung', 'Merak, Banten', 'Eksekutif', 84000, '2024-11-29', '7:00', 'Di atas 6 tahun'),
('Merak, Banten', 'Bakauheni, Lampung', 'Eksekutif', 84000, '2024-11-29', '7:00', 'Di atas 6 tahun'),
('Bakauheni, Lampung', 'Merak, Banten', 'Reguler', 1800, '2024-11-29', '12:00', 'Di bawah 2 tahun'),
('Merak, Banten', 'Bakauheni, Lampung', 'Reguler', 1800, '2024-11-29', '12:00', 'Di bawah 2 tahun'),
('Bakauheni, Lampung', 'Merak, Banten', 'Eksekutif', 4000, '2024-11-29', '12:00', 'Di bawah 2 tahun'),
('Merak, Banten', 'Bakauheni, Lampung', 'Eksekutif', 4000, '2024-11-29', '12:00', 'Di bawah 2 tahun'),
('Bakauheni, Lampung', 'Merak, Banten', 'Reguler', 22700, '2024-11-29', '12:00', 'Di atas 6 tahun'),
('Merak, Banten', 'Bakauheni, Lampung', 'Reguler', 22700, '2024-11-29', '12:00', 'Di atas 6 tahun'),
('Bakauheni, Lampung', 'Merak, Banten', 'Eksekutif', 84000, '2024-11-29', '12:00', 'Di atas 6 tahun'),
('Merak, Banten', 'Bakauheni, Lampung', 'Eksekutif', 84000, '2024-11-29', '12:00', 'Di atas 6 tahun'),
('Bakauheni, Lampung', 'Merak, Banten', 'Reguler', 1800, '2024-11-29', '20:00', 'Di bawah 2 tahun'),
('Merak, Banten', 'Bakauheni, Lampung', 'Reguler', 1800, '2024-11-29', '20:00', 'Di bawah 2 tahun'),
('Bakauheni, Lampung', 'Merak, Banten', 'Eksekutif', 4000, '2024-11-29', '20:00', 'Di bawah 2 tahun'),
('Merak, Banten', 'Bakauheni, Lampung', 'Eksekutif', 4000, '2024-11-29', '20:00', 'Di bawah 2 tahun'),
('Bakauheni, Lampung', 'Merak, Banten', 'Reguler', 22700, '2024-11-29', '20:00', 'Di atas 6 tahun'),
('Merak, Banten', 'Bakauheni, Lampung', 'Reguler', 22700, '2024-11-29', '20:00', 'Di atas 6 tahun'),
('Bakauheni, Lampung', 'Merak, Banten', 'Eksekutif', 84000, '2024-11-29', '20:00', 'Di atas 6 tahun'),
('Merak, Banten', 'Bakauheni, Lampung', 'Eksekutif', 84000, '2024-11-29', '20:00', 'Di atas 6 tahun'),
('Bakauheni, Lampung', 'Merak, Banten', 'Reguler', 1800, '2024-11-30', '7:00', 'Di bawah 2 tahun'),
('Merak, Banten', 'Bakauheni, Lampung', 'Reguler', 1800, '2024-11-30', '7:00', 'Di bawah 2 tahun'),
('Bakauheni, Lampung', 'Merak, Banten', 'Eksekutif', 4000, '2024-11-30', '7:00', 'Di bawah 2 tahun'),
('Merak, Banten', 'Bakauheni, Lampung', 'Eksekutif', 4000, '2024-11-30', '7:00', 'Di bawah 2 tahun'),
('Bakauheni, Lampung', 'Merak, Banten', 'Reguler', 22700, '2024-11-30', '7:00', 'Di atas 6 tahun'),
('Merak, Banten', 'Bakauheni, Lampung', 'Reguler', 22700, '2024-11-30', '7:00', 'Di atas 6 tahun'),
('Bakauheni, Lampung', 'Merak, Banten', 'Eksekutif', 84000, '2024-11-30', '7:00', 'Di atas 6 tahun'),
('Merak, Banten', 'Bakauheni, Lampung', 'Eksekutif', 84000, '2024-11-30', '7:00', 'Di atas 6 tahun'),
('Bakauheni, Lampung', 'Merak, Banten', 'Reguler', 1800, '2024-11-30', '12:00', 'Di bawah 2 tahun'),
('Merak, Banten', 'Bakauheni, Lampung', 'Reguler', 1800, '2024-11-30', '12:00', 'Di bawah 2 tahun'),
('Bakauheni, Lampung', 'Merak, Banten', 'Eksekutif', 4000, '2024-11-30', '12:00', 'Di bawah 2 tahun'),
('Merak, Banten', 'Bakauheni, Lampung', 'Eksekutif', 4000, '2024-11-30', '12:00', 'Di bawah 2 tahun'),
('Bakauheni, Lampung', 'Merak, Banten', 'Reguler', 22700, '2024-11-30', '12:00', 'Di atas 6 tahun'),
('Merak, Banten', 'Bakauheni, Lampung', 'Reguler', 22700, '2024-11-30', '12:00', 'Di atas 6 tahun'),
('Bakauheni, Lampung', 'Merak, Banten', 'Eksekutif', 84000, '2024-11-30', '12:00', 'Di atas 6 tahun'),
('Merak, Banten', 'Bakauheni, Lampung', 'Eksekutif', 84000, '2024-11-30', '12:00', 'Di atas 6 tahun'),
('Bakauheni, Lampung', 'Merak, Banten', 'Reguler', 1800, '2024-11-30', '20:00', 'Di bawah 2 tahun'),
('Merak, Banten', 'Bakauheni, Lampung', 'Reguler', 1800, '2024-11-30', '20:00', 'Di bawah 2 tahun'),
('Bakauheni, Lampung', 'Merak, Banten', 'Eksekutif', 4000, '2024-11-30', '20:00', 'Di bawah 2 tahun'),
('Merak, Banten', 'Bakauheni, Lampung', 'Eksekutif', 4000, '2024-11-30', '20:00', 'Di bawah 2 tahun'),
('Bakauheni, Lampung', 'Merak, Banten', 'Reguler', 22700, '2024-11-30', '20:00', 'Di atas 6 tahun'),
('Merak, Banten', 'Bakauheni, Lampung', 'Reguler', 22700, '2024-11-30', '20:00', 'Di atas 6 tahun'),
('Bakauheni, Lampung', 'Merak, Banten', 'Eksekutif', 84000, '2024-11-30', '20:00', 'Di atas 6 tahun'),
('Merak, Banten', 'Bakauheni, Lampung', 'Eksekutif', 84000, '2024-11-30', '20:00', 'Di atas 6 tahun');

-- --------------------------------------------------------

--
-- Struktur dari tabel `riwayat_pemesanan`
--

CREATE TABLE `riwayat_pemesanan` (
  `id` int NOT NULL,
  `nickname` varchar(50) DEFAULT NULL,
  `asal` varchar(50) DEFAULT NULL,
  `tujuan` varchar(50) DEFAULT NULL,
  `layanan` varchar(50) DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `jam` varchar(20) DEFAULT NULL,
  `penumpang` int DEFAULT NULL,
  `harga` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `riwayat_pemesanan`
--

INSERT INTO `riwayat_pemesanan` (`id`, `nickname`, `asal`, `tujuan`, `layanan`, `tanggal`, `jam`, `penumpang`, `harga`) VALUES
(1, 'M. Thoriqul Fadli', 'Bakauheni, Lampung', 'Merak, Banten', 'Reguler', '2024-11-28', '7:00', 1, 22700),
(2, 'M. Thoriqul Fadli', 'Bakauheni, Lampung', 'Merak, Banten', 'Reguler', '2024-11-28', '7:00', 1, 22700),
(3, 'M. Thoriqul Fadli', 'Bakauheni, Lampung', 'Merak, Banten', 'Reguler', '2024-11-28', '7:00', 1, 22700),
(4, 'M. Thoriqul Fadli', 'Bakauheni, Lampung', 'Merak, Banten', 'Reguler', '2024-11-28', '7:00', 1, 22700),
(5, 'M. Thoriqul Fadli', 'Bakauheni, Lampung', 'Merak, Banten', 'Eksekutif', '2024-11-28', '20:00', 1, 4000),
(6, 'M. Thoriqul Fadli', 'Bakauheni, Lampung', 'Merak, Banten', 'Reguler', '2024-11-28', '7:00', 1, 22700),
(7, 'M. Thoriqul Fadli', 'Bakauheni, Lampung', 'Merak, Banten', 'Eksekutif', '2024-11-28', '12:00', 1, 84000),
(8, 'danildermawan', 'Bakauheni, Lampung', 'Merak, Banten', 'Reguler', '2024-11-28', '7:00', 1, 22700),
(9, 'admin123', 'Bakauheni, Lampung', 'Merak, Banten', 'Eksekutif', '2024-11-29', '7:00', 1, 84000),
(10, 'admin123', 'Merak, Banten', 'Bakauheni, Lampung', 'Reguler', '2024-11-29', '7:00', 1, 1800),
(11, 'admin123', 'Bakauheni, Lampung', 'Merak, Banten', 'Reguler', '2024-11-30', '20:00', 1, 22700),
(12, 'admin123', 'Bakauheni, Lampung', 'Merak, Banten', 'Eksekutif', '2024-11-28', '12:00', 1, 4000),
(13, 'admin123', 'Bakauheni, Lampung', 'Merak, Banten', 'Reguler', '2024-11-28', '20:00', 1, 22700),
(14, 'user12345', 'Bakauheni, Lampung', 'Merak, Banten', 'Eksekutif', '2024-11-30', '12:00', 1, 84000),
(15, 'M. Thoriqul Fadli', 'Bakauheni, Lampung', 'Merak, Banten', 'Reguler', '2024-11-28', '7:00', 1, 22700),
(16, 'admin123', 'Bakauheni, Lampung', 'Merak, Banten', 'Reguler', '2024-11-30', '7:00', 1, 22700),
(17, 'ahmadfadhil', 'Bakauheni, Lampung', 'Merak, Banten', 'Eksekutif', '2024-11-29', '20:00', 1, 84000),
(18, 'admin123', 'Bakauheni, Lampung', 'Merak, Banten', 'Eksekutif', '2024-11-30', '7:00', 1, 84000),
(19, 'admin123', 'Merak, Banten', 'Bakauheni, Lampung', 'Reguler', '2024-11-29', '20:00', 1, 1800),
(20, 'admin123', 'Merak, Banten', 'Bakauheni, Lampung', 'Eksekutif', '2024-11-30', '20:00', 1, 4000);

-- --------------------------------------------------------

--
-- Struktur dari tabel `userlog`
--

CREATE TABLE `userlog` (
  `id` int NOT NULL,
  `username` varchar(155) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `userID` char(13) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `userlog`
--

INSERT INTO `userlog` (`id`, `username`, `email`, `password`, `userID`) VALUES
(1, 'M. Thoriqul Fadli', 'mthoriqulfadli@gmail.com', '$2y$10$eXpmiKx9k5OaMRQU2wGg.uJ7DCeBRejZ2tvexgJHCPYl39I2hLjSO', '670522c8418f5'),
(2, 'admin123', 'admin123@gmail.com', '$2y$10$sRbbYsknQb/vS0zLBXs2..X8.iVJS0qPPAVPRLwF7zCTz.iiY0WY2', '6705e243f042d'),
(3, 'rendi123456', 'admin143@gmail.com', '$2y$10$drYli9fZdbEfXXAy2psVA.CinSbI6U8CP8hujRjqGhlVll/Y.mEF6', '6706183a4e941'),
(4, 'danildermawan', 'danil@gmail.com', '$2y$10$WdLPMvem0XHbOfjRH6k4AOavN/homEsS9pNFKxF2MEhGfEaCd205S', '674be4768ef02'),
(5, 'user12345', 'user12345@gmail.com', '$2y$10$WVbVxSA5VH2CZl8PdgNXBudm/RLeP6MLqe78WllEwUfrYevyHN0Cq', '674d6cf473e14'),
(6, 'ahmadfadhil', 'fadhil123@gmail.com', '$2y$10$N.Sj4wlj4JjzP6JYutlntec7sToTOiZy0gmrhtJPd6T8PuaOFCniW', '675d232bc4155');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `riwayat_pemesanan`
--
ALTER TABLE `riwayat_pemesanan`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `userlog`
--
ALTER TABLE `userlog`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `riwayat_pemesanan`
--
ALTER TABLE `riwayat_pemesanan`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT untuk tabel `userlog`
--
ALTER TABLE `userlog`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
