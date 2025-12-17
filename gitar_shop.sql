-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 17, 2025 at 06:25 PM
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

INSERT INTO `buy` (`id`, `user_id`, `nama_user`, `nama_item`, `jumlah`, `total`, `status`, `alamat`, `telp_penerima`) VALUES
(2, NULL, 'dsad', 'dsadsad', 4, 92, 'SENT', 'dsadsa', '3213213'),
(3, 5, 'Dav', 'dsad', 11, 253, 'SENT', 'Dadadadadadad', '08546456456'),
(4, 5, 'Dav', 'GitHub', 3, 36, 'SENT', 'Dadadadadadad', '08546456456'),
(5, 5, 'Dav', 'dsad', 3, 69, 'SENT', 'Asadasdas', '08546456456'),
(6, 6, 'Dav', 'dsad', 2, 46, 'PENDING', 'testes2w', '435534535435'),
(7, 6, 'Dav', 'GitHub', 3, 36, 'SENT', 'testes2w', '435534535435'),
(8, 6, 'Dav', 'dsadsad', 2, 46, 'SENT', 'testes2w', '435534535435');

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
(1, 'dsad', 23, 'dsadsa', 'https://gbfusxshislkvgxuiwoh.supabase.co/storage/v1/object/public/guitars/1765887281-Screenshot+2025-12-01+183629.png'),
(2, 'GitHub', 12, 'dsad', 'https://gbfusxshislkvgxuiwoh.supabase.co/storage/v1/object/public/guitars/1765887315-POSTER+GITHUB+WORKSHOP+2025-min.png'),
(3, 'dsadsad', 23, 'dsadsa', 'https://gbfusxshislkvgxuiwoh.supabase.co/storage/v1/object/public/guitars/1765887364-Nomor1+AOK10.png');

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
(5, 'Dav', 'c14240020@john.petra.ac.id', '$2y$10$gWrXkVmF6kPkrNHKN0s35OArpLiNrUyaTh7j7FrJl.JP0sd.aM8M.', '08546456456', 'user'),
(6, 'Dav', 'wnest@gitarshop.com', '$2y$10$OOXFk.OPBLfAVGqIP7DWqe/BUvlQR.SLP8Joy4iWStGcM.Ibo.EQe', 'sadasfda', 'user'),
(7, 'budi', 'asdasdsad@gmail.com', '$2y$10$3UQoy4jQZelX.kt3neYFI.sw2QnvT88pGdEKLxxHcViZKBzWVVq96', 'sadasfda', 'user'),
(8, 'Dav', 'oaskdoksadokad@gmail.com', '$2y$10$lFkBRUgOlmgQPpECYFYT1OZ5.B/H/7y2Qx2qKnMv.2KFckGwmKXxK', '12335425435', 'user');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

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
