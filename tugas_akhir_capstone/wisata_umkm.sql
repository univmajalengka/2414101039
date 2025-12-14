-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 14, 2025 at 04:30 PM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `wisata_umkm`
--

-- --------------------------------------------------------

--
-- Table structure for table `pesanan`
--

CREATE TABLE `pesanan` (
  `id` int(11) NOT NULL,
  `nama_pemesan` varchar(100) DEFAULT NULL,
  `nomor_hp` varchar(20) DEFAULT NULL,
  `tanggal_pesan` date DEFAULT NULL,
  `durasi_wisata` int(11) DEFAULT 1,
  `jumlah_peserta` int(11) DEFAULT NULL,
  `jenis_tiket` varchar(50) DEFAULT NULL,
  `hari_kunjungan` varchar(20) DEFAULT NULL,
  `harga_paket` decimal(15,2) DEFAULT NULL,
  `total_tagihan` decimal(15,2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `layanan_travel` tinyint(1) DEFAULT 0,
  `layanan_makan` tinyint(1) DEFAULT 0,
  `layanan_penginapan` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pesanan`
--

INSERT INTO `pesanan` (`id`, `nama_pemesan`, `nomor_hp`, `tanggal_pesan`, `durasi_wisata`, `jumlah_peserta`, `jenis_tiket`, `hari_kunjungan`, `harga_paket`, `total_tagihan`, `created_at`, `layanan_travel`, `layanan_makan`, `layanan_penginapan`) VALUES
(3, 'Muhamad Anugrah Aidil Akbar ', '21356', '8649-05-04', 1, 1, 'hemat', 'weekday', '90000.00', '340000.00', '2025-12-13 18:04:58', 0, 0, 1),
(6, 'Akbar', '0867876531243', '2026-02-28', 2, 10, 'hemat', 'weekend', '100000.00', '13000000.00', '2025-12-14 14:33:16', 1, 1, 1),
(8, 'Muhamad Anugrah Aidil Akbar ', '0896643391911', '2026-01-01', 2, 10, 'hemat', 'weekend', '100000.00', '9000000.00', '2025-12-14 14:56:10', 0, 1, 1),
(9, 'Muhamad Anugrah Aidil Akbar ', '0896643391911', '2026-01-01', 3, 10, 'hemat', 'weekend', '100000.00', '13000000.00', '2025-12-14 15:02:48', 0, 1, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `pesanan`
--
ALTER TABLE `pesanan`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `pesanan`
--
ALTER TABLE `pesanan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
