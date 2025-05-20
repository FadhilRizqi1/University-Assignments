-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 15 Bulan Mei 2025 pada 21.40
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
-- Database: `jubeka_db`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `contact_messages`
--

CREATE TABLE `contact_messages` (
  `id` int(11) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `subjek` varchar(255) NOT NULL,
  `pesan` text NOT NULL,
  `tanggal_kirim` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `contact_messages`
--

INSERT INTO `contact_messages` (`id`, `nama`, `email`, `subjek`, `pesan`, `tanggal_kirim`) VALUES
(1, 'Ahmad Fadhil Rizqi', 'fadhilrizqi@gmail.com', 'Uji Coba Pesan', 'Ini adalah pesan uji coba', '2025-05-15 19:10:29');

-- --------------------------------------------------------

--
-- Struktur dari tabel `jual`
--

CREATE TABLE `jual` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `nama_barang` varchar(255) NOT NULL,
  `deskripsi` text NOT NULL,
  `harga` int(50) NOT NULL,
  `kategori_id` int(10) UNSIGNED DEFAULT NULL,
  `gambar` varchar(255) NOT NULL,
  `nomor_telepon_penjual` varchar(20) DEFAULT NULL,
  `alamat_penjual` text DEFAULT NULL,
  `tanggal_post` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `jual`
--

INSERT INTO `jual` (`id`, `user_id`, `nama_barang`, `deskripsi`, `harga`, `kategori_id`, `gambar`, `nomor_telepon_penjual`, `alamat_penjual`, `tanggal_post`) VALUES
(13, 1, 'Motor Beat', 'Honda Beat kesayangan.\r\n\r\nSurat-surat lengkap.\r\n\r\nMesin halus, Kelistrikan oke.\r\n\r\nPlat On, pajak off.\r\n\r\nSeri Kota Palembang.\r\n\r\nSiap pakai.', 8000000, 5, 'img_67fea4c1a9af93.37250610.webp', NULL, NULL, '2025-05-01 14:58:36'),
(14, 1, 'Mouse Logitech', 'Mouse Logitech, pemakaian kurang lebih 2 bulan. Minus bagian scroll kurang responsif', 57000, 1, 'img_67fea4824ba9b4.92604770.jpeg', '081234560002', 'Komp. Griya Hero Abadi Blok B2, Sukarami, Palembang', '2025-05-01 14:58:36'),
(33, 1, 'Samsung a50s', 'Samsung a50s ram 4/64 kondisi msh sangat baik msh original pabrik, sidik jari layar aktif garansi resmi sein\r\nKel hp+kotak bae Jual 900 nego tipis', 900000, 1, 'img_680dec8f471411.42077329.jpg', '081234560003', 'Jl. Angkatan 45 Lorong Harapan, Ilir Barat I, Palembang', '2025-05-01 14:58:36'),
(34, 1, 'Yamaha yzf', '2018 Yamaha YZF · Jarak yang sudah ditempuh 13.000 kilometer\r\nForsale \r\n- R15 V3 tahun 2018\r\n- kilometer 13 ribu dari baru jarang di pakai \r\n- mesin Stndr belum pernah di bongkar\r\n- ss lengkap\r\n- pajak mati 1 tahun \r\n- motor msih Stndr Ado yg di modif dikit\r\n- motor terawat oli rutin\r\nHarga msih nego', 20500000, 5, 'img_680ded087cf2b3.24478968.webp', '081234560004', 'Jl. Inspektur Marzuki No. 20, Siring Agung, Palembang', '2025-05-01 14:58:36'),
(35, 1, 'Honda Civic Type R 2.0 2018', 'Honda Civic Type R 2.0 Manual Tahun 2018 Kilometer 15 rb Pajak desember 2025 Bebas banjir & bebas laka Record', 890000000, 5, 'img_680dee02c2b7f4.71897847.png', '081234560005', 'Perumahan CitraGrand City Blok C5, Talang Kelapa, Palembang', '2025-05-01 14:58:36'),
(36, 1, 'Vivo y12s', 'Jual hp Vivo y12s batangan jaringan 4g ram 3/32 hp masih ORI Galo lumayan mulus nominus siap nego bensin cek lah sampe puas', 700000, 1, 'img_680defac22af17.20944455.jpg', '081234560006', 'Jl. Residen Abdul Rozak, Kalidoni, Palembang', '2025-05-01 14:58:36'),
(37, 1, 'Asus VivoBook X551B', 'Dijual Leptop Asus Mulus No Minus Siap Sekolah skripsi Kerja\r\nAsus VivoBook X551B\r\nWindows Ori\r\nProsesor AMD A6 Graphics Radeon R4\r\nRam 4GB / Hdd 1Tera\r\nLayar  141\" HD Jernih\r\nBody/fisik 95% Mulus\r\nHarga Boleh Nego', 2100000, 1, 'img_680df1b66d4df6.13431481.jpg', '081234560007', 'Jl. Radial Rusun Blok 30, Bukit Kecil, Palembang', '2025-05-01 14:58:36'),
(38, 1, 'Adidas Trail Running', 'sepatu adidias trail running\r\nsize 39 insol 24,5\r\nmade Indonesia\r\nno minus semua cuman butuh cuci', 190000, 2, 'img_680df22f8b1d18.11461506.jpg', '081234560008', 'Komp. OPI Jakabaring Cluster A1, Seberang Ulu I, Palembang', '2025-05-01 14:58:36'),
(39, 12, 'Elgato Stream Deck +', 'Barang masi seperti baru. Pemakaian Sekali saja, boleh cek kondisi .\r\n\r\nCocok untuk para streamer.', 3200000, 1, 'img_6813910f9a4935.61390736.webp', '081234560009', 'Jl. MP Mangkunegara No. 8, Kenten, Palembang', '2025-05-01 15:19:43'),
(40, 12, 'Realme Watch 3 1.8 inc Large Color Display', 'Original Realme IMEI terdaftar\r\n\r\nKelengkapan full set beserta box\r\n\r\nKondisi Istimewa', 550000, 2, 'img_681391dfb2bee9.08343353.webp', '081234560010', 'Jl. Demang Lebar Daun Lr. Pribadi, Ilir Barat I, Palembang', '2025-05-01 15:23:11'),
(41, 12, 'Topi MLB Streetwear Sportswear Hat Pria “Oakland Athletics”', 'Available!\r\nBaseball Cap by MLB “Oakland Athletics”\r\n\r\nOSFA / Adjustable Size\r\nGood Condition\r\nSaran di reshape aja biar makin jos\r\n\r\nMinus:\r\n• Gigi Strap nya sisa 2 (bisa ganti strap baru)', 199000, 2, 'img_681392710b8907.41886756.png', '081234560011', 'Jl. Basuki Rahmat Gg. Pribadi, Kemuning, Palembang', '2025-05-01 15:25:37'),
(42, 12, 'Jaket GDG 🤩👍', 'Jangan dibeli, cuman pamer.🥲', 270000000, 4, 'img_6813947ea12b88.59050381.jpg', '081234560012', 'Jl. Mayor Ruslan No. 9, Ilir Timur II, Palembang', '2025-05-01 15:34:22'),
(43, 12, 'Dickies Eisenhower Corduroy Work Jacket - Dark Brown', 'Dickies Eisenhower Corduroy Work Jacket\r\nDark Brown\r\nSize L (68 x 56)\r\nUsed Like New\r\n100% Original', 1500000, 2, 'img_681394f50c87e4.39393660.png', '081234560013', 'Komp. Polygon Blok DD5, Gandus, Palembang', '2025-05-01 15:36:21'),
(44, 12, 'Carhartt WIP Essentials Bag Small - Hamilton Brown', 'Carhartt WIP Essentials Bag Small\r\nHamilton Brown\r\nOne Size\r\nUsed Like New\r\n100% Original', 750000, 2, 'img_681395afb08be6.95675497.png', '081234560014', 'Jl. Srijaya Negara Bukit Besar, Palembang', '2025-05-01 15:39:27'),
(45, 12, 'SAMSUNG ADAPTER 25w ORIGINAL', '- ORIGINAL 1000000%\r\n\r\n- Beli di Samsung nya\r\n\r\n- Cuma buka segel tp blm pernah di pake\r\n\r\n- DI JAMIN ORIGINAL DAN MULUS', 250000, 1, 'img_68139695e763a0.28940843.webp', '081234560015', 'Jl. Way Hitam Lr. Karet, Ilir Barat I, Palembang', '2025-05-01 15:43:17'),
(46, 12, 'Panci Presto GETRA size paling besar 135liter', 'Bahan besi sangat berat, tidak ada penyok sama sekali. Masa pemakaian 3th. Minus nya hanya 1 putaran atas nya pecah. Pemakaian 1th terakhir tanpa putaran tetap bisa berfungsi dgn baik. Ada bagian pengunci di bagian kuping nya. Harga baru saat ini +/- 5jt an. Monggo yg berminat saja..', 150000000, 3, 'img_681396dba79525.86714596.webp', '081112223333', 'Jl. Demang Lebar Daun No. 77, Ilir Barat I, Palembang', '2025-05-01 15:44:27'),
(47, 12, 'Fiorenza Hitam set toples', 'Fiorenza hitam set toples', 265000, 3, 'img_68139705befe21.86771097.webp', '081112223334', 'Perumnas Talang Kelapa Blok S10, Alang-Alang Lebar, Palembang', '2025-05-01 15:45:09'),
(48, 12, 'Meja Makan Set 4 Kursi Sofa Jati Super Kualitas Harga Terjangkau', 'CV. ELW PROJECT INTERIORS\r\n\r\n(Authentic Wood Project)\r\n\r\nBEST..\r\n\r\n- MEJA BARS PINUS LINE /L /U\r\n\r\n- MEJA KASIR PINUS LINE /L /U\r\n\r\nEQUIPMENT..\r\n\r\n- AYUNAN JATI\r\n\r\n- MEJA RIAS JATI\r\n\r\n- KURSI SOFA JATI\r\n\r\n- MEJA TV LACI JATI\r\n\r\n- MEJA LESEHAN JATI\r\n\r\n- KURSI SANTAI JATI/ROTAN\r\n\r\n- RANJANG TIDUR JATI/BESI\r\n\r\n- LEMARI PAKAIAN JATI/BESI\r\n\r\n- KURSI BAR CAFE JATI/ROTAN\r\n\r\n- MEJA KURSI TAMU JATI/BESI\r\n\r\n- SEKAT RUANGAN JATI/ROTAN\r\n\r\n- JAM LEMARI HIAS JATI/MARMER\r\n\r\n- MEJA SET KURSI MAKAN RESTO JATI\r\n\r\n- MEJA RAPAT KANTOR JATI/TREMBESI\r\n\r\n- MEJA SET KURSI MAKAN RESTO TREMBESI\r\n\r\nMasih Banyak Lagi Berbagai Jenis Interior Lainnya.\r\n\r\nDiutamakan Kualitas Bahan, Pengerjaan Dan Finishing.\r\n\r\nPilihan Bahan, Ukuran, Dan Motif Bervariasi Sesuai Pesanan..', 5275000, 3, 'img_68139738e46d44.24737565.webp', '081234560016', 'Jl. Kolonel H. Burlian Km. 7, Sukarami, Palembang', '2025-05-01 15:46:00');

-- --------------------------------------------------------

--
-- Struktur dari tabel `kategori`
--

CREATE TABLE `kategori` (
  `id` int(10) UNSIGNED NOT NULL,
  `nama_kategori` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `kategori`
--

INSERT INTO `kategori` (`id`, `nama_kategori`) VALUES
(6, 'Buku & Majalah'),
(1, 'Elektronik'),
(2, 'Fashion'),
(4, 'Hobi & Koleksi'),
(7, 'Lainnya'),
(5, 'Otomotif'),
(3, 'Rumah Tangga');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `nomor_telepon` varchar(20) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `nomor_telepon`, `alamat`, `created_at`) VALUES
(1, 'Fadhilrizqi', '$2y$10$Wh91QiZ9Gfybm14W.i9Duebbslz/UXT0m3nTL14vtnLnqbod.1Mmi', 'fadhilrizqi@gmail.com', '081279056668', 'Jl. Sudirman No. 1, RT 01 RW 02, Kel. 20 Ilir D. III, Kec. Ilir Tim. I, Kota Palembang, Sumatera Selatan, 30121', '2025-02-27 14:39:36'),
(5, 'Edo Wicaksonooo', '$2y$10$8sk/REd4SlUbH.yfszDAHu033SguceHehR5pY2tn8W9xRi6fpFQoy', 'Edoo@gmail.comoo', '082345678901', 'Jl. Pangeran Ratu No. 10, Kel. 15 Ulu, Kec. Seberang Ulu I, Kota Palembang, Sumatera Selatan, 30257', '2025-03-13 03:24:14'),
(6, 'Rizqi', '$2y$10$5qdyqZAB6HtS6uSbauvsaOk2m6vbfVqenZVhkWXRhOdKqlCJScsbe', 'Rizqi@gmail.com', '083456789012', 'Perumahan Griya Maju Blok A5 No. 3, Kel. Talang Kelapa, Kec. Alang-Alang Lebar, Kota Palembang, Sumatera Selatan, 30153', '2025-03-13 18:25:28'),
(8, 'Fahren', '$2y$10$MPKklpY0lXav8vLGmXLX0e7QJ7FEbt8yZ0rzOMBjp5jB3Hgygs9ku', 'Fahren@gmail.com', '085678901234', 'Jl. Kancil Putih No. 22, Kel. Demang Lebar Daun, Kec. Ilir Bar. I, Kota Palembang, Sumatera Selatan, 30151', '2025-03-17 15:36:58'),
(10, 'Ahmad Fadhil Rizqi', '$2y$10$fo/8YePR6uuuO6T9Qn1LC.dzKz4krrKvO4HuQqTTjyttUlkCBwWtu', 'fadhilsirega@gmail.com', '081279056668', 'Kemang Manis', '2025-04-15 18:45:13'),
(11, 'MAFALQI', '$2y$10$ZEMu2sP4d0nfGUvmcfHlKeZuaMy.xVN9pzUM10sVeILKwMGM0HHLu', 'mafalqi@gmail.com', '0823646162', 'Prabumulih', '2025-04-15 18:47:42'),
(12, 'Fadhil', '$2y$10$bkIwHX.qo.AlOgZ6mCjn7.q3Wm.WQc30W328pKzAOaMS5MF7UhZc6', 'fadhils@gmail.com', '0812790536736', 'Bukit', '2025-05-01 14:50:43');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `jual`
--
ALTER TABLE `jual`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_kategori` (`kategori_id`),
  ADD KEY `fk_jual_user_idx` (`user_id`);

--
-- Indeks untuk tabel `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nama_kategori` (`nama_kategori`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `jual`
--
ALTER TABLE `jual`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT untuk tabel `kategori`
--
ALTER TABLE `kategori`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `jual`
--
ALTER TABLE `jual`
  ADD CONSTRAINT `fk_jual_kategori` FOREIGN KEY (`kategori_id`) REFERENCES `kategori` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_jual_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
