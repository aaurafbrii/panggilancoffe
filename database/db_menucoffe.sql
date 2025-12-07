-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 27, 2025 at 03:26 AM
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
-- Database: `db_menucoffe`
--

-- --------------------------------------------------------

--
-- Table structure for table `menu`
--

CREATE TABLE `menu` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `category` varchar(50) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `menu`
--

INSERT INTO `menu` (`id`, `name`, `category`, `price`, `image`, `description`) VALUES
(1, 'Cafe Latte', 'Minuman', 19000.00, '1761131346_68f8bb52d7a0f.jpeg', 'COFFEE'),
(3, 'Americano', 'Minuman', 17000.00, '1761131227_68f8badb3c5d3.jpeg', 'COFFEE'),
(5, 'Butter Croissant ', 'Makanan', 17000.00, '1761134749_68f8c89d5c833.jpeg', 'MAKANAN'),
(6, 'Chocolate Croissant ', 'Makanan', 19000.00, '1761134796_68f8c8cc7c63f.jpeg', 'MAKANAN'),
(7, 'Thai Tea', 'Minuman', 21000.00, '1761134966_68f8c9763074e.jpeg', 'NON COFFEE'),
(8, 'Peach Tea', 'Minuman', 19000.00, '1761135048_68f8c9c8e2f5d.jpeg', 'NON COFFEE'),
(9, 'Matcha', 'Minuman', 25000.00, '1761135179_68f8ca4be18c8.jpeg', 'NON COFFEE'),
(10, 'Tiramisu Cake', 'Makanan', 35000.00, '1761135302_68f8cac69b974.jpeg', 'MAKANAN');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `menu_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `total_price` decimal(10,2) DEFAULT NULL,
  `order_date` datetime DEFAULT current_timestamp(),
  `status` enum('pending','diproses','selesai') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `menu_id`, `quantity`, `total_price`, `order_date`, `status`) VALUES
(1, 2, 1, 1, 20000.00, '2025-10-20 15:21:05', 'selesai'),
(2, 2, 1, 2, 40000.00, '2025-10-20 15:35:23', 'selesai'),
(3, 2, 3, 2, 70000.00, '2025-10-20 20:40:39', 'selesai'),
(5, 4, 10, 1, 35000.00, '2025-10-22 19:16:32', 'pending'),
(6, 3, 10, 3, 105000.00, '2025-10-27 09:16:59', 'pending'),
(7, 3, 9, 1, 25000.00, '2025-10-27 09:17:53', 'diproses');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` enum('admin','customer') DEFAULT 'customer'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`) VALUES
(1, 'Admin', 'admin123@gmail.com', '0192023a7bbd73250516f069df18b500', 'admin'),
(2, 'Kiki', 'kiki123@gmail.com', '656ead03af397857bdcd84292e6a3bbd', 'customer'),
(3, 'Aura', 'auraf123@gmail.com', '0425ed39936af3c810d9ed1b1aecd6da', 'customer'),
(4, 'aura', 'aura123@gmail.com', 'b9ab6d5388f50d0a523499d09fc2d207', 'customer');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `orders_ibfk_2` (`menu_id`);

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
-- AUTO_INCREMENT for table `menu`
--
ALTER TABLE `menu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`menu_id`) REFERENCES `menu` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
