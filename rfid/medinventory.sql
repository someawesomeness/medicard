-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:4306
-- Generation Time: May 15, 2024 at 12:35 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.1.25

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
(4, 'Test', 'Two', '$2y$10$Hmo9QlSp8pcXo9ekm/SPcOMQrrBJdgyk8bOm/QmdtMZDcmevuv5LG', 'testwo@email.com', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(5, 'Test', 'Three', '$2y$10$NW4ThVLKjKJg4ew.M.em0OfrEleJ5yOfdCt9K7VNc0.0apZUVyZ/a', 'testhree@email.com', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(33, 'air', 'supply', '111', 'airsupply@email.com', '2024-05-15 04:37:57', '2024-05-15 04:37:57');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `adminstaffs`
--
ALTER TABLE `adminstaffs`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `adminstaffs`
--
ALTER TABLE `adminstaffs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
