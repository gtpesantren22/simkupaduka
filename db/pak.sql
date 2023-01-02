-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 02 Jan 2023 pada 10.28
-- Versi server: 10.4.24-MariaDB
-- Versi PHP: 7.4.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_sentral`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `pak`
--

CREATE TABLE `pak` (
  `id_pak` int(11) NOT NULL,
  `kode_pak` varchar(30) NOT NULL,
  `lembaga` varchar(10) NOT NULL,
  `tgl_pak` varchar(50) NOT NULL,
  `status` enum('belum','proses','ditolak','selesai') NOT NULL,
  `tahun` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `pak`
--

INSERT INTO `pak` (`id_pak`, `kode_pak`, `lembaga`, `tgl_pak`, `status`, `tahun`) VALUES
(2, 'PAK.100.77414', '100', '02 January 2023 s/d 05 January 2023 ', 'belum', '2022/2023 ');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `pak`
--
ALTER TABLE `pak`
  ADD PRIMARY KEY (`id_pak`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `pak`
--
ALTER TABLE `pak`
  MODIFY `id_pak` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
