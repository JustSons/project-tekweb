-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 17, 2025 at 07:19 PM
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
(9, 4, 9, 'Dav', 'Budiman', 1, 10000, 'PENDING', 'Jalan Kebenaran', '08546456456'),
(10, 5, 9, 'Dav', 'Budiwoman', 1, 15000, 'SENT', 'Jalan Kebenaran', '08546456456'),
(11, 6, 9, 'Dav', 'Budiman', 1, 100000, 'SENT', 'asdasd', '08546456456'),
(12, 4, 9, 'Dav', 'Budiman', 1, 10000, 'PENDING', 'sadasd', '08546456456');

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
(4, 'Budiman', 10000, 'Gitar 100 string', 'https://gbfusxshislkvgxuiwoh.supabase.co/storage/v1/object/public/guitars/1765994915-Screenshot+2025-08-06+192736.png'),
(5, 'Budiwoman', 15000, 'Gitar 1000 string', 'https://gbfusxshislkvgxuiwoh.supabase.co/storage/v1/object/public/guitars/1765995037-Screenshot+2025-10-18+185019.png'),
(6, 'Budiman', 100000, 'Gitar 1 juta string', 'https://gbfusxshislkvgxuiwoh.supabase.co/storage/v1/object/public/guitars/1765995156-Screenshot+2025-08-21+204443.png');

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
(9, 'Dav', 'wnest@gitarshop.com', '$2y$10$x4L.ZfePFCoH1AdNwCEnhO4UhCckrtAY07fNrCY9o4/4xtvwMvfHe', '08546456456', 'user'),
(10, 'Dav', 'c14240020@john.petra.ac.id', '$2y$10$Ipx/FzjpCjvX1sDlf07.n.AIq5SVmIZ76pNaxFKdUJAx0XdVteDj2', '08546456456', 'user');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

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
