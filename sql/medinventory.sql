-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 18, 2024 at 07:25 PM
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
-- Database: `medinventory`
--

-- --------------------------------------------------------

--
-- Table structure for table `adminstaffs`
--

CREATE TABLE `adminstaffs` (
  `id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `password` varchar(300) NOT NULL,
  `email` varchar(50) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `adminstaffs`
--

INSERT INTO `adminstaffs` (`id`, `first_name`, `last_name`, `password`, `email`, `created_at`, `updated_at`) VALUES
(1, 'Testing', 'One', 'test', 'testone@email.com', '2024-05-12 06:41:11', '2024-05-12 06:41:11'),
(3, 'Healthcare', 'Official', 'health', 'healthcare@email.com', '2024-05-12 10:19:15', '2024-05-12 10:19:15'),
(33, 'air', 'supply', '111', 'airsupply@email.com', '2024-05-15 04:37:57', '2024-05-15 04:37:57'),
(79, 'tristan', 'lods', '1111', 'lods@email.com', '2024-05-17 20:45:27', '2024-05-17 20:45:27'),
(80, 'decall', 'gen', 'dec', 'decolgen@email.com', '2024-05-17 21:25:20', '2024-05-17 21:25:20'),
(81, 'starter', 'upp', 'start', 'startup@email.com', '2024-05-17 21:27:52', '2024-06-18 00:38:42'),
(85, 'jay', 'tonee', '7111', 'tj@email.com', '2024-06-18 00:40:03', '2024-06-18 00:40:03'),
(86, 'three', 'lala', '3333', 'tutest@email.com', '2024-06-18 00:41:43', '2024-06-18 00:41:43'),
(87, 'test', 'lalamove', '11223', 'jlen@email.com', '2024-06-18 09:58:03', '2024-06-18 09:58:25');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `product_name` varchar(200) NOT NULL,
  `description` varchar(250) DEFAULT NULL,
  `product_expiration` date NOT NULL,
  `img` varchar(150) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `product_name`, `description`, `product_expiration`, `img`, `price`, `stock`, `created_by`, `created_at`, `updated_at`) VALUES
(427, 'Bioflu', 'for flu\r\n                            ', '0000-00-00', 'product-1718044879.jpg', 3.00, 8, 1, '2024-06-10 20:41:19', '2024-06-10 20:41:19'),
(428, 'Biogesic', 'for fever       ', '0000-00-00', 'product-1718044903.jpg', 2.00, 8, 1, '2024-06-10 20:41:43', '2024-06-10 20:41:43'),
(429, 'Ceelin', 'vitamin c\r\n                            ', '0000-00-00', 'product-1718045275.jpg', 6.00, 5, 1, '2024-06-10 20:42:32', '2024-06-11 02:47:55'),
(430, 'Conzace', 'multivitamins\r\n                            ', '0000-00-00', 'product-1718045250.jpg', 8.00, 8, 1, '2024-06-10 20:47:30', '2024-06-10 20:47:30'),
(432, 'tuseran', 'cough\r\n                            ', '2024-06-22', 'product-1718689980.jpg', 2.00, 5, 1, '2024-06-18 06:36:39', '2024-06-18 13:53:00');

-- --------------------------------------------------------

--
-- Table structure for table `productsuppliers`
--

CREATE TABLE `productsuppliers` (
  `id` int(11) NOT NULL,
  `supplier` int(11) DEFAULT NULL,
  `product` int(11) DEFAULT NULL,
  `quantity_ordered` int(11) DEFAULT NULL,
  `quantity_received` int(11) DEFAULT NULL,
  `quantity_remaining` int(11) DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `productsuppliers`
--

INSERT INTO `productsuppliers` (`id`, `supplier`, `product`, `quantity_ordered`, `quantity_received`, `quantity_remaining`, `status`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 1, 427, NULL, NULL, NULL, NULL, NULL, '2024-06-10 20:41:19', '2024-06-10 20:41:19'),
(2, 2, 430, NULL, NULL, NULL, NULL, NULL, '2024-06-10 20:47:30', '2024-06-10 20:47:30');

-- --------------------------------------------------------

--
-- Table structure for table `stocks`
--

CREATE TABLE `stocks` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `created_by` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE `suppliers` (
  `id` int(11) NOT NULL,
  `supplier_name` varchar(200) NOT NULL,
  `supplier_location` varchar(200) NOT NULL,
  `email` varchar(100) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `suppliers`
--

INSERT INTO `suppliers` (`id`, `supplier_name`, `supplier_location`, `email`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'FJ', 'A', 'fj@yahoo.com', 1, '2024-06-10 20:40:23', '2024-06-10 20:40:23'),
(2, 'testsup', 'testup 123', 'tj@email.com', 1, '2024-06-10 20:40:35', '2024-06-10 20:40:35');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `adminstaffs`
--
ALTER TABLE `adminstaffs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_user` (`created_by`);

--
-- Indexes for table `productsuppliers`
--
ALTER TABLE `productsuppliers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `supplier` (`supplier`),
  ADD KEY `product` (`product`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `stocks`
--
ALTER TABLE `stocks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `stock_ibfk_2` (`product_id`);

--
-- Indexes for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_created_by` (`created_by`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `adminstaffs`
--
ALTER TABLE `adminstaffs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=88;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=445;

--
-- AUTO_INCREMENT for table `productsuppliers`
--
ALTER TABLE `productsuppliers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `fk_user` FOREIGN KEY (`created_by`) REFERENCES `adminstaffs` (`id`);

--
-- Constraints for table `productsuppliers`
--
ALTER TABLE `productsuppliers`
  ADD CONSTRAINT `productsuppliers_ibfk_1` FOREIGN KEY (`supplier`) REFERENCES `suppliers` (`id`),
  ADD CONSTRAINT `productsuppliers_ibfk_2` FOREIGN KEY (`product`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `productsuppliers_ibfk_3` FOREIGN KEY (`created_by`) REFERENCES `adminstaffs` (`id`);

--
-- Constraints for table `stocks`
--
ALTER TABLE `stocks`
  ADD CONSTRAINT `stock_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `stocks_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `adminstaffs` (`id`);

--
-- Constraints for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD CONSTRAINT `fk_created_by` FOREIGN KEY (`created_by`) REFERENCES `adminstaffs` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
