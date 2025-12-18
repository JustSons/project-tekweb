-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 18, 2025 at 03:50 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `gitar_shop`
--
CREATE DATABASE IF NOT EXISTS `gitar_shop` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `gitar_shop`;

-- --------------------------------------------------------

--
-- Table structure for table `buy`
--

CREATE TABLE `buy` (
  `id` int(11) NOT NULL,
  `item_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `nama_user` varchar(100) DEFAULT NULL,
  `nama_item` varchar(100) DEFAULT NULL,
  `jumlah` int(11) DEFAULT NULL,
  `total` int(11) DEFAULT NULL,
  `status` enum('PENDING','SENT') DEFAULT 'PENDING',
  `alamat` text DEFAULT NULL,
  `telp_penerima` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `buy`
--

INSERT INTO `buy` (`id`, `item_id`, `user_id`, `nama_user`, `nama_item`, `jumlah`, `total`, `status`, `alamat`, `telp_penerima`) VALUES
(13, 7, 11, 'User', 'Ukulele', 1, 1000000, 'SENT', 'Jl. Ince Nurdin', '291321'),
(14, 8, 11, 'User', 'Gitar Klasik', 4, 2760000, 'SENT', 'Jl. Ince Nurdin', '291321'),
(15, 8, 11, 'User', 'Gitar Klasik', 1, 690000, 'PENDING', 'Jl. Sawerigading', '3213213');

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `id` int(11) NOT NULL,
  `nama_item` varchar(100) DEFAULT NULL,
  `harga` int(11) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `gambar` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`id`, `nama_item`, `harga`, `deskripsi`, `gambar`) VALUES
(7, 'Ukulele', 1000000, 'Ukulele yang hebat', 'https://gbfusxshislkvgxuiwoh.supabase.co/storage/v1/object/public/guitars/1766025533-preview_1.jpg'),
(9, 'Gitar Elektrik', 4200000, 'Make your style electrifying', 'https://gbfusxshislkvgxuiwoh.supabase.co/storage/v1/object/public/guitars/1766025594-images+%283%29.jpg'),
(10, 'Gitar Akustik', 900000, 'Not Really Autistic', 'https://gbfusxshislkvgxuiwoh.supabase.co/storage/v1/object/public/guitars/1766025611-images+%284%29.jpg'),
(11, 'Gitar Klasik', 690000, 'Klasik Atau Rank', 'https://gbfusxshislkvgxuiwoh.supabase.co/storage/v1/object/public/guitars/1766026007-images+%282%29.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `no_telp` varchar(20) DEFAULT NULL,
  `role` enum('user','admin') DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `nama`, `email`, `password`, `no_telp`, `role`) VALUES
(4, 'Admin', 'admin@gitarshop.com', '$2y$10$3V2H6kgItSB7aTRyKdvSBuyUQlZEwPJm0khFGPWbmwijlZXjLliXK', '081234567890', 'admin'),
(11, 'User', 'user@gmail.com', '$2y$10$1dqfVhLG.L3WZUGTG/E/GeQ6lVTe2avHeiFu78YAtJthjxYNiVURy', '12345', 'user');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `buy`
--
ALTER TABLE `buy`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_buy_user` (`user_id`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `buy`
--
ALTER TABLE `buy`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `buy`
--
ALTER TABLE `buy`
  ADD CONSTRAINT `fk_buy_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
