-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 11 Jan 2025 pada 08.54
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
-- Database: `bay-school`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `students`
--

CREATE TABLE `students` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `nis` varchar(20) NOT NULL,
  `nisn` varchar(20) NOT NULL,
  `parent_name` varchar(100) NOT NULL,
  `city` varchar(50) NOT NULL,
  `gender` enum('L','P') NOT NULL,
  `grade` varchar(20) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `students`
--

INSERT INTO `students` (`id`, `name`, `nis`, `nisn`, `parent_name`, `city`, `gender`, `grade`, `created_at`, `updated_at`) VALUES
(1, 'Arditya Satria L', '07493', '0089723451', 'Thomas slebew', 'Jakarta', 'L', 'XI IPS 2', '2025-01-10 17:03:03', '2025-01-10 17:03:03'),
(2, 'Elvan Nissage S', '07982', '0089387374', 'James Soap', 'Jakarta', 'L', 'XI MIPA 1', '2025-01-10 17:03:03', '2025-01-10 17:03:03'),
(3, 'Feiruz Amru Ghani', '07869', '0086987698', 'Justin Hope', 'Jakarta', 'P', 'X MIPA 3', '2025-01-10 17:03:03', '2025-01-10 17:03:03'),
(4, 'Jordan Nico', '07593', '0057612893', 'Amanda Nico', 'Jakarta', 'L', 'XI IPS 4', '2025-01-10 17:03:03', '2025-01-10 17:03:03'),
(5, 'Nadila Adja', '07945', '0098127945', 'Jack Adja', 'Jakarta', 'P', 'XI IPS 2', '2025-01-10 17:03:03', '2025-01-10 17:03:03'),
(6, 'Johnny Ahmad', '07895', '0088297495', 'Danny Ahmad', 'Jakarta', 'L', 'XI MIPA 1', '2025-01-10 17:03:03', '2025-01-10 17:03:03');

-- --------------------------------------------------------

--
-- Struktur dari tabel `user`
--

CREATE TABLE `user` (
  `id` int(10) NOT NULL,
  `username` varchar(200) NOT NULL,
  `password` varchar(200) NOT NULL,
  `status` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `user`
--

INSERT INTO `user` (`id`, `username`, `password`, `status`) VALUES
(1, 'admin', 'admin', 'admin');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nis` (`nis`),
  ADD UNIQUE KEY `nisn` (`nisn`);

--
-- Indeks untuk tabel `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT untuk tabel `user`
--
ALTER TABLE `user`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
