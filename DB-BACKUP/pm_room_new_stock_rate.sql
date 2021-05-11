-- phpMyAdmin SQL Dump
-- version 4.9.4
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 04, 2020 at 01:43 AM
-- Server version: 10.1.45-MariaDB
-- PHP Version: 7.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sterlingguesthou_sterlingguesthouse`
--

-- --------------------------------------------------------

--
-- Table structure for table `pm_room_new_stock_rate`
--

CREATE TABLE `pm_room_new_stock_rate` (
  `id` bigint(21) NOT NULL,
  `id_hotel` bigint(21) NOT NULL,
  `id_room` bigint(21) NOT NULL,
  `new_stock` varchar(255) DEFAULT NULL,
  `new_price` varchar(255) DEFAULT NULL,
  `new_disc_price` varchar(255) DEFAULT NULL,
  `is_blocked` tinyint(2) NOT NULL DEFAULT '0' COMMENT '0-> Unblocked, 1-> Blocked',
  `date` date NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `pm_room_new_stock_rate`
--

INSERT INTO `pm_room_new_stock_rate` (`id`, `id_hotel`, `id_room`, `new_stock`, `new_price`, `new_disc_price`, `is_blocked`, `date`, `created_at`, `updated_at`) VALUES
(1, 32, 54, NULL, NULL, '1500', 0, '2020-05-26', '2020-05-26 05:58:09', '2020-05-26 11:03:58'),
(2, 32, 54, NULL, NULL, '1500', 0, '2020-05-27', '2020-05-26 05:58:09', '2020-05-26 11:03:58'),
(3, 32, 54, '8', '1672', '1500', 0, '2020-05-28', '2020-05-26 05:58:09', '2020-05-28 07:48:02'),
(4, 32, 54, '8', '1672', NULL, 0, '2020-05-29', '2020-05-26 07:12:32', '2020-05-28 07:48:02'),
(5, 32, 54, NULL, '1672', NULL, 0, '2020-05-30', '2020-05-26 07:12:32', '2020-05-28 07:02:12'),
(6, 33, 55, '-11', '-3', '-11', 1, '2020-05-27', '2020-05-27 09:45:15', '2020-05-27 14:44:11'),
(7, 33, 55, NULL, NULL, NULL, 1, '2020-05-28', '2020-05-27 10:17:39', '2020-05-27 15:14:27'),
(8, 33, 55, '1', '-5', '-3', 0, '2020-05-29', '2020-05-27 10:17:39', '2020-05-29 13:46:01'),
(9, 33, 55, '6', '55', NULL, 0, '2020-05-30', '2020-05-27 10:17:39', '2020-05-29 14:31:27'),
(10, 33, 55, '6', '55', NULL, 0, '2020-05-31', '2020-05-27 11:18:49', '2020-05-29 14:31:30'),
(11, 33, 55, NULL, '110', NULL, 1, '2020-06-01', '2020-05-27 11:18:49', '2020-05-27 15:23:11'),
(12, 33, 55, NULL, NULL, NULL, 1, '2020-06-03', '2020-05-27 11:42:26', NULL),
(13, 33, 55, NULL, NULL, NULL, 1, '2020-06-04', '2020-05-27 11:42:26', NULL),
(14, 32, 54, '-50', NULL, NULL, 0, '2020-06-01', '2020-05-29 09:30:51', '2020-05-29 13:31:08'),
(15, 33, 55, '5', '121', '120', 0, '2020-06-06', '2020-05-29 10:48:46', '2020-06-03 15:15:36'),
(16, 33, 55, NULL, NULL, NULL, 0, '2020-06-07', '2020-05-29 10:48:46', '2020-05-29 14:52:27'),
(17, 33, 55, NULL, NULL, '120', 0, '2020-06-05', '2020-05-29 10:58:44', NULL),
(18, 32, 58, '10', NULL, NULL, 0, '2020-06-04', '2020-06-03 05:47:29', '2020-06-03 11:31:03'),
(19, 32, 54, '8', '1672', NULL, 0, '2020-06-04', '2020-06-03 07:13:25', '2020-06-03 11:20:18');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `pm_room_new_stock_rate`
--
ALTER TABLE `pm_room_new_stock_rate`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `pm_room_new_stock_rate`
--
ALTER TABLE `pm_room_new_stock_rate`
  MODIFY `id` bigint(21) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
