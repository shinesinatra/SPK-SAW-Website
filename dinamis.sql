-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 17 Agu 2025 pada 18.28
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
-- Database: `dinamis`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `kriteria`
--

CREATE TABLE `kriteria` (
  `kode` varchar(10) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `bobot` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `kriteria`
--

INSERT INTO `kriteria` (`kode`, `nama`, `bobot`) VALUES
('C1', 'Nilai Rata-Rata Rapor', 0.3),
('C2', 'Presentase Kehadiran', 0.2),
('C3', 'Keaktifan Ekstrakulikuler', 0.2),
('C4', 'Keaktifan Kompetisi Lomba', 0.2),
('C5', 'Penilaian Sikap Perilaku', 0.1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `penilaian`
--

CREATE TABLE `penilaian` (
  `kelas` varchar(10) NOT NULL,
  `nisn` varchar(20) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `nilai_rata_rata_rapor` double DEFAULT NULL,
  `presentase_kehadiran` double DEFAULT NULL,
  `keaktifan_ekstrakulikuler` double DEFAULT NULL,
  `keaktifan_kompetisi_lomba` double DEFAULT NULL,
  `penilaian_sikap_perilaku` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `penilaian`
--

INSERT INTO `penilaian` (`kelas`, `nisn`, `nama`, `nilai_rata_rata_rapor`, `presentase_kehadiran`, `keaktifan_ekstrakulikuler`, `keaktifan_kompetisi_lomba`, `penilaian_sikap_perilaku`) VALUES
('VI', '123', 'Adiva Saraun', 8.5, 8.9, 6.5, 6, 9),
('VI', '124', 'Albertus Eko', 8.3, 9.1, 7, 6, 9.2),
('VI', '125', 'Andreas Dwi', 7.9, 9.6, 7, 7, 9.1),
('VI', '126', 'Thomas Agung', 8.1, 9.5, 6, 6, 9.3),
('VI', '127', 'Veronica Tri', 8.3, 9.4, 6.5, 6.5, 9.5);

-- --------------------------------------------------------

--
-- Struktur dari tabel `perankingan`
--

CREATE TABLE `perankingan` (
  `nisn` varchar(50) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `nilai` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `perankingan`
--

INSERT INTO `perankingan` (`nisn`, `nama`, `nilai`) VALUES
('123', 'Adiva Saraun', 0.93729636591479),
('124', 'Albertus Eko', 0.95079518649565),
('125', 'Andreas Dwi', 0.97461300309598),
('126', 'Thomas Agung', 0.92455089930709),
('127', 'Veronica Tri', 0.96020308123249);

-- --------------------------------------------------------

--
-- Struktur dari tabel `siswa`
--

CREATE TABLE `siswa` (
  `nisn` varchar(20) NOT NULL,
  `kelas` varchar(5) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `tanggal_lahir` date NOT NULL,
  `jenis_kelamin` varchar(15) NOT NULL,
  `alamat` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `siswa`
--

INSERT INTO `siswa` (`nisn`, `kelas`, `nama`, `tanggal_lahir`, `jenis_kelamin`, `alamat`) VALUES
('123', 'VI', 'Adiva Saraun', '2013-01-01', 'Perempuan', 'Jakarta Pusat'),
('124', 'VI', 'Albertus Eko', '2013-01-02', 'Laki-laki', 'Jakarta Utara'),
('125', 'VI', 'Andreas Dwi', '2013-01-03', 'Laki-laki', 'Jakarta Selatan'),
('126', 'VI', 'Thomas Agung', '2013-01-04', 'Laki-laki', 'Jakarta Timur'),
('127', 'VI', 'Veronica Tri', '2013-01-05', 'Perempuan', 'Jakarta Barat');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `nik` varchar(10) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`nik`, `full_name`, `username`, `password`) VALUES
('nik001', 'Administrator', 'admin', 'admin'),
('nik002', 'Agustinus Hery Siswanto', 'agustinus.hery', 'admin');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `kriteria`
--
ALTER TABLE `kriteria`
  ADD PRIMARY KEY (`kode`),
  ADD UNIQUE KEY `nama` (`nama`);

--
-- Indeks untuk tabel `perankingan`
--
ALTER TABLE `perankingan`
  ADD PRIMARY KEY (`nisn`);

--
-- Indeks untuk tabel `siswa`
--
ALTER TABLE `siswa`
  ADD PRIMARY KEY (`nisn`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`nik`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
