-- phpMyAdmin SQL Dump
-- version 4.9.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Aug 12, 2020 at 09:50 AM
-- Server version: 10.1.46-MariaDB
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
-- Database: `devhotelfitser_dev`
--

-- --------------------------------------------------------

--
-- Table structure for table `pm_accommodation`
--

CREATE TABLE `pm_accommodation` (
  `id` int(11) NOT NULL,
  `lang` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `title` varchar(250) DEFAULT NULL,
  `subtitle` varchar(250) DEFAULT NULL,
  `title_tag` varchar(250) DEFAULT NULL,
  `alias` varchar(100) DEFAULT NULL,
  `descr` text,
  `text` longtext,
  `video` text,
  `lat` double DEFAULT NULL,
  `lng` double DEFAULT NULL,
  `home` int(11) DEFAULT '0',
  `checked` int(11) DEFAULT '0',
  `rank` int(11) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pm_accommodation`
--

INSERT INTO `pm_accommodation` (`id`, `lang`, `name`, `title`, `subtitle`, `title_tag`, `alias`, `descr`, `text`, `video`, `lat`, `lng`, `home`, `checked`, `rank`) VALUES
(1, 2, 'Hotel', 'Hotel', '', 'Hotel', 'hotel', '', NULL, NULL, NULL, NULL, 0, 1, 1),
(2, 2, 'Lodge', 'Lodge', '', 'Lodge', 'lodge', '', NULL, NULL, NULL, NULL, 0, 1, 2),
(3, 2, 'Resort', 'Resort', '', 'Resort', 'resort', '', NULL, NULL, NULL, NULL, 0, 1, 3);

-- --------------------------------------------------------

--
-- Table structure for table `pm_accommodation_file`
--

CREATE TABLE `pm_accommodation_file` (
  `id` int(11) NOT NULL,
  `lang` int(11) NOT NULL,
  `id_item` int(11) NOT NULL,
  `home` int(11) DEFAULT '0',
  `checked` int(11) DEFAULT '1',
  `rank` int(11) DEFAULT '0',
  `file` varchar(250) DEFAULT NULL,
  `label` varchar(250) DEFAULT NULL,
  `type` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pm_accommodation_file`
--

INSERT INTO `pm_accommodation_file` (`id`, `lang`, `id_item`, `home`, `checked`, `rank`, `file`, `label`, `type`) VALUES
(3, 2, 1, NULL, 1, 3, 'sandals-grenada-pool.jpg', '', 'image'),
(3, 4, 1, NULL, 1, 3, 'sandals-grenada-pool.jpg', '', 'image'),
(4, 2, 2, NULL, 1, 4, 'eaco-01a-07.jpg', '', 'image'),
(4, 4, 2, NULL, 1, 4, 'eaco-01a-07.jpg', NULL, 'image'),
(5, 2, 3, NULL, 1, 5, 'cottage.jpg', '', 'image'),
(5, 4, 3, NULL, 1, 5, 'cottage.jpg', '', 'image'),
(6, 2, 4, NULL, 1, 6, 'beach-house.jpg', '', 'image'),
(6, 4, 4, NULL, 1, 6, 'beach-house.jpg', '', 'image'),
(7, 2, 5, NULL, 1, 7, 'hotel-background-pic1.jpg', NULL, 'image'),
(8, 2, 7, NULL, 1, 8, 'dd32d9b188d86d6d8dc40d1ff9a0ebf6.jpg', NULL, 'image'),
(9, 2, 8, NULL, 1, 9, 'lodge.jpg', NULL, 'image'),
(10, 2, 9, NULL, 1, 10, 'resort.jpeg', NULL, 'image');

-- --------------------------------------------------------

--
-- Table structure for table `pm_activity`
--

CREATE TABLE `pm_activity` (
  `id` int(11) NOT NULL,
  `lang` int(11) NOT NULL,
  `hotels` varchar(250) DEFAULT NULL,
  `users` text,
  `max_children` int(11) DEFAULT '1',
  `max_adults` int(11) DEFAULT '1',
  `max_people` int(11) DEFAULT NULL,
  `title` varchar(250) DEFAULT NULL,
  `subtitle` varchar(250) DEFAULT NULL,
  `alias` varchar(100) DEFAULT NULL,
  `descr` longtext,
  `duration` float DEFAULT '0',
  `duration_unit` varchar(50) DEFAULT NULL,
  `price` double DEFAULT '0',
  `lat` double DEFAULT NULL,
  `lng` double DEFAULT NULL,
  `home` int(11) DEFAULT '0',
  `checked` int(11) DEFAULT '0',
  `rank` int(11) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `pm_activity_file`
--

CREATE TABLE `pm_activity_file` (
  `id` int(11) NOT NULL,
  `lang` int(11) NOT NULL,
  `id_item` int(11) NOT NULL,
  `home` int(11) DEFAULT '0',
  `checked` int(11) DEFAULT '1',
  `rank` int(11) DEFAULT '0',
  `file` varchar(250) DEFAULT NULL,
  `label` varchar(250) DEFAULT NULL,
  `type` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `pm_activity_log`
--

CREATE TABLE `pm_activity_log` (
  `id` int(11) NOT NULL,
  `lang` int(2) NOT NULL DEFAULT '2',
  `user_id` int(11) NOT NULL,
  `id_item` bigint(20) NOT NULL,
  `module` varchar(50) DEFAULT NULL,
  `action` varchar(250) DEFAULT NULL,
  `purpose` text,
  `add_date` int(11) DEFAULT NULL,
  `checked` tinyint(2) NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `pm_activity_log`
--

INSERT INTO `pm_activity_log` (`id`, `lang`, `user_id`, `id_item`, `module`, `action`, `purpose`, `add_date`, `checked`) VALUES
(1, 2, 1, 42, 'rate', 'edit', 'rate 42 edit', 1573643565, 1),
(2, 2, 1, 20, 'hotel', 'edit', 'hotel 20 edit', 1573646465, 1),
(3, 2, 235, 68, 'facility', 'edit', 'facility 68 edit', 1573646525, 1),
(4, 2, 235, 68, 'facility', 'edit', 'facility 68 edit', 1573646532, 1),
(5, 2, 1, 10063, 'booking', 'edit', 'Booking  10063 checked out', 1573647065, 1),
(6, 2, 1, 19, 'hotel', 'edit', 'hotel 19 edit', 1573727013, 1),
(7, 2, 1, 19, 'hotel', 'edit', 'hotel 19 edit', 1573727047, 1),
(8, 2, 1, 19, 'hotel', 'edit', 'hotel 19 edit', 1573727070, 1),
(9, 2, 1, 19, 'hotel', 'edit', 'hotel 19 edit', 1573727086, 1),
(10, 2, 1, 10066, 'booking', 'edit', 'booking 10066 edit', 1573807010, 1),
(11, 2, 1, 10066, 'booking', 'edit', 'Booking  10066 checked in', 1573812231, 1),
(12, 2, 1, 10066, 'booking', 'edit', 'Booking  10066 checked out', 1573812232, 1),
(13, 2, 1, 37, 'room', 'edit', 'room 37 edit', 1574166297, 1),
(14, 2, 1, 39, 'room', 'edit', 'room 39 edit', 1574166527, 1),
(15, 2, 1, 39, 'room', 'edit', 'room 39 edit', 1574240184, 1),
(16, 2, 1, 1, 'destination', 'edit', 'destination 1 edit', 1574421397, 1),
(17, 2, 1, 2, 'destination', 'edit', 'destination 2 edit', 1574421410, 1),
(18, 2, 1, 3, 'destination', 'edit', 'destination 3 edit', 1574421421, 1),
(19, 2, 1, 0, 'user', 'add', 'user 0 add', 1574424292, 1),
(20, 2, 1, 0, 'user', 'add', 'user 0 add', 1574424309, 1),
(21, 2, 1, 0, 'user', 'add', 'user 0 add', 1574424315, 1),
(22, 2, 1, 0, 'user', 'add', 'user 0 add', 1574424343, 1),
(23, 2, 1, 0, 'user', 'add', 'user 0 add', 1574425319, 1),
(24, 2, 1, 0, 'user', 'add', 'user 0 add', 1574425559, 1),
(25, 2, 1, 269, 'user', 'add', 'New user Qa Das is created', 1574685530, 1),
(26, 2, 1, 25, 'user', 'edit', 'New user Qa Das is updated', 1574685532, 1),
(27, 2, 1, 25, 'user', 'edit', 'New user Qa Das is updated', 1574685535, 1),
(28, 2, 1, 25, 'user', 'edit', 'New user Qa Das is updated', 1574685543, 1),
(29, 2, 1, 25, 'user', 'edit', 'New user Qa Das is updated', 1574685550, 1),
(30, 2, 1, 25, 'user', 'edit', 'New user Qa Das is updated', 1574685560, 1),
(31, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1574688953, 1),
(32, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1574688955, 1),
(33, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1574688963, 1),
(34, 2, 1, 10075, 'booking', 'add', 'New Booking  #id =10075 is Created', 1574688981, 1),
(35, 2, 1, 267, 'user', 'edit', 'user 267 edit', 1574850345, 1),
(36, 2, 1, 267, 'user', 'edit', 'user 267 edit', 1574850467, 1),
(37, 2, 1, 267, 'user', 'edit', 'user 267 edit', 1574851939, 1),
(38, 2, 1, 267, 'user', 'edit', 'user 267 edit', 1574851951, 1),
(39, 2, 1, 267, 'user', 'edit', 'user 267 edit', 1574854682, 1),
(40, 2, 1, 267, 'user', 'edit', 'user 267 edit', 1574854731, 1),
(41, 2, 1, 267, 'user', 'edit', 'user 267 edit', 1574854741, 1),
(42, 2, 1, 267, 'user', 'edit', 'user 267 edit', 1574854881, 1),
(43, 2, 1, 267, 'user', 'edit', 'user 267 edit', 1574854896, 1),
(44, 2, 1, 267, 'user', 'edit', 'user 267 edit', 1574855259, 1),
(45, 2, 1, 19, 'hotel', 'edit', 'hotel 19 edit', 1574856004, 1),
(46, 2, 1, 10075, 'booking', 'edit', 'booking 10075 edit', 1574857113, 1),
(47, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1574868267, 1),
(48, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1574868268, 1),
(49, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1574868268, 1),
(50, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1574868270, 1),
(51, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1574868271, 1),
(52, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1574868271, 1),
(53, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1574868272, 1),
(54, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1574868276, 1),
(55, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1574868278, 1),
(56, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1574868278, 1),
(57, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1574868278, 1),
(58, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1574924680, 1),
(59, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1574924782, 1),
(60, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1574924785, 1),
(61, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1574924791, 1),
(62, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1574924812, 1),
(63, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1574924819, 1),
(64, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1574924825, 1),
(65, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1574924825, 1),
(66, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1574924826, 1),
(67, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1574924829, 1),
(68, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1574924831, 1),
(69, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1574924837, 1),
(70, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1574924839, 1),
(71, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1574924846, 1),
(72, 2, 1, 21, 'hotel', 'edit', 'hotel 21 edit', 1574936386, 1),
(73, 2, 1, 19, 'hotel', 'edit', 'hotel 19 edit', 1574937750, 1),
(74, 2, 1, 272, 'user', 'add', 'New user QA pal is created', 1574944572, 1),
(75, 2, 1, 74, 'user', 'edit', 'New user QA pal is updated', 1574944580, 1),
(76, 2, 1, 74, 'user', 'edit', 'New user QA pal is updated', 1574944587, 1),
(77, 2, 1, 74, 'user', 'edit', 'New user QA pal is updated', 1574944619, 1),
(78, 2, 1, 1, 'tax', 'edit', 'tax 1 edit', 1575360127, 1),
(79, 2, 1, 0, 'user', 'add', 'user 0 add', 1575375892, 1),
(80, 2, 1, 0, 'user', 'add', 'user 0 add', 1575375958, 1),
(81, 2, 1, 10083, 'booking', 'add', 'New Booking  #id =10083 is Created', 1575378922, 1),
(82, 2, 1, 10083, 'booking', 'edit', 'booking 10083 edit', 1575380148, 1),
(83, 2, 1, 10083, 'booking', 'edit', 'booking 10083 edit', 1575380491, 1),
(84, 2, 1, 19, 'hotel', 'edit', 'hotel 19 edit', 1575468582, 1),
(85, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1575469824, 1),
(86, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1575469825, 1),
(87, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1575469830, 1),
(88, 2, 1, 10089, 'booking', 'add', 'New Booking  #id =10089 is Created', 1575469837, 1),
(89, 2, 1, 19, 'hotel', 'edit', 'hotel 19 edit', 1575470227, 1),
(90, 2, 1, 274, 'user', 'add', 'New user Anil Das is created', 1575552296, 1),
(91, 2, 1, 90, 'user', 'edit', 'New user Anil Das is updated', 1575552305, 1),
(92, 2, 1, 90, 'user', 'edit', 'New user Anil Das is updated', 1575552305, 1),
(93, 2, 1, 90, 'user', 'edit', 'New user Anil Das is updated', 1575552306, 1),
(94, 2, 1, 90, 'user', 'edit', 'New user Anil Das is updated', 1575552309, 1),
(95, 2, 1, 90, 'user', 'edit', 'New user Anil Das is updated', 1575552314, 1),
(96, 2, 1, 275, 'user', 'add', 'New user Ravi Das is created', 1575552760, 1),
(97, 2, 1, 96, 'user', 'edit', 'New user Ravi Das is updated', 1575552764, 1),
(98, 2, 1, 96, 'user', 'edit', 'New user Ravi Das is updated', 1575552769, 1),
(99, 2, 1, 25, 'hotel', 'edit', 'hotel 25 edit', 1576140204, 1),
(100, 2, 1, 0, 'page', 'add', 'page 0 add', 1576232928, 1),
(101, 2, 1, 35, 'page', 'edit', 'page 35 edit', 1576232938, 1),
(102, 2, 1, 0, 'page', 'add', 'page 0 add', 1576233066, 1),
(103, 2, 1, 0, 'faq', 'add', 'faq 0 add', 1576236516, 1),
(104, 2, 1, 0, 'faq', 'add', 'faq 0 add', 1576236550, 1),
(105, 2, 1, 69, 'facility', 'edit', 'facility 69 edit', 1576244062, 1),
(106, 2, 1, 10083, 'booking', 'edit', 'Booking  10083 checked in', 1576490087, 1),
(107, 2, 1, 10083, 'booking', 'edit', 'Booking  10083 checked out', 1576490105, 1),
(108, 2, 1, 237, 'user', 'edit', 'New user Zafar Ansari is updated', 1576566774, 1),
(109, 2, 1, 237, 'user', 'edit', 'New user Zafar Ansari is updated', 1576566781, 1),
(110, 2, 1, 237, 'user', 'edit', 'New user Zafar Ansari is updated', 1576567048, 1),
(111, 2, 1, 237, 'user', 'edit', 'New user Zafar Ansari is updated', 1576567062, 1),
(112, 2, 1, 10256, 'booking', 'add', 'New Booking  #id =10256 is Created', 1576567092, 1),
(113, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1576567181, 1),
(114, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1576567184, 1),
(115, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1576567189, 1),
(116, 2, 1, 10257, 'booking', 'add', 'New Booking  #id =10257 is Created', 1576567195, 1),
(117, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1576567433, 1),
(118, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1576567435, 1),
(119, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1576567445, 1),
(120, 2, 1, 10258, 'booking', 'add', 'New Booking  #id =10258 is Created', 1576567457, 1),
(121, 2, 1, 9, 'menu', 'edit', 'menu 9 edit', 1576567660, 1),
(122, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1576568571, 1),
(123, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1576568573, 1),
(124, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1576568596, 1),
(125, 2, 1, 10260, 'booking', 'add', 'New Booking  #id =10260 is Created', 1576568609, 1),
(126, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1576568694, 1),
(127, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1576568715, 1),
(128, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1576568730, 1),
(129, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1576568734, 1),
(130, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1576568740, 1),
(131, 2, 1, 10261, 'booking', 'add', 'New Booking  #id =10261 is Created', 1576568747, 1),
(132, 2, 1, 10262, 'booking', 'edit', 'Booking  10262 checked in', 1576569519, 1),
(133, 2, 1, 10238, 'booking', 'edit', 'Booking  10238 checked in', 1576579052, 1),
(134, 2, 1, 10238, 'booking', 'edit', 'Booking  10238 checked out', 1576579054, 1),
(135, 2, 1, 10238, 'booking', 'edit', 'booking 10238 edit', 1576579138, 1),
(136, 2, 1, 10269, 'booking', 'edit', 'Booking  10269 checked in', 1576585227, 1),
(137, 2, 1, 10268, 'booking', 'edit', 'Booking  10268 checked in', 1576589040, 1),
(138, 2, 1, 10268, 'booking', 'edit', 'Booking  10268 checked out', 1576589043, 1),
(139, 2, 1, 76, 'facility', 'edit', 'facility 76 edit', 1576671553, 1),
(140, 2, 1, 72, 'facility', 'edit', 'facility 72 edit', 1576671593, 1),
(141, 2, 1, 66, 'facility', 'edit', 'facility 66 edit', 1576671617, 1),
(142, 2, 1, 75, 'facility', 'edit', 'facility 75 edit', 1576671666, 1),
(143, 2, 1, 74, 'facility', 'edit', 'facility 74 edit', 1576671685, 1),
(144, 2, 1, 77, 'facility', 'edit', 'facility 77 edit', 1576671706, 1),
(145, 2, 1, 78, 'facility', 'edit', 'facility 78 edit', 1576671725, 1),
(146, 2, 1, 69, 'facility', 'edit', 'facility 69 edit', 1576671742, 1),
(147, 2, 1, 68, 'facility', 'edit', 'facility 68 edit', 1576671799, 1),
(148, 2, 1, 67, 'facility', 'edit', 'facility 67 edit', 1576671919, 1),
(149, 2, 1, 80, 'facility', 'edit', 'facility 80 edit', 1576671936, 1),
(150, 2, 1, 71, 'facility', 'edit', 'facility 71 edit', 1576671964, 1),
(151, 2, 1, 79, 'facility', 'edit', 'facility 79 edit', 1576671987, 1),
(152, 2, 1, 73, 'facility', 'edit', 'facility 73 edit', 1576672008, 1),
(153, 2, 1, 70, 'facility', 'edit', 'facility 70 edit', 1576672026, 1),
(154, 2, 1, 81, 'facility', 'edit', 'facility 81 edit', 1576672043, 1),
(155, 2, 1, 68, 'facility', 'edit', 'facility 68 edit', 1576672659, 1),
(156, 2, 1, 76, 'facility', 'edit', 'facility 76 edit', 1576673227, 1),
(157, 2, 1, 72, 'facility', 'edit', 'facility 72 edit', 1576673323, 1),
(158, 2, 1, 66, 'facility', 'edit', 'facility 66 edit', 1576673335, 1),
(159, 2, 1, 75, 'facility', 'edit', 'facility 75 edit', 1576673345, 1),
(160, 2, 1, 74, 'facility', 'edit', 'facility 74 edit', 1576673359, 1),
(161, 2, 1, 77, 'facility', 'edit', 'facility 77 edit', 1576673371, 1),
(162, 2, 1, 78, 'facility', 'edit', 'facility 78 edit', 1576673381, 1),
(163, 2, 1, 69, 'facility', 'edit', 'facility 69 edit', 1576673395, 1),
(164, 2, 1, 67, 'facility', 'edit', 'facility 67 edit', 1576673406, 1),
(165, 2, 1, 80, 'facility', 'edit', 'facility 80 edit', 1576673421, 1),
(166, 2, 1, 71, 'facility', 'edit', 'facility 71 edit', 1576673431, 1),
(167, 2, 1, 79, 'facility', 'edit', 'facility 79 edit', 1576673443, 1),
(168, 2, 1, 73, 'facility', 'edit', 'facility 73 edit', 1576673463, 1),
(169, 2, 1, 70, 'facility', 'edit', 'facility 70 edit', 1576673609, 1),
(170, 2, 1, 81, 'facility', 'edit', 'facility 81 edit', 1576673620, 1),
(171, 2, 1, 2, 'page', 'edit', 'page 2 edit', 1576675542, 1),
(172, 2, 1, 10284, 'booking', 'edit', 'Booking  10284 checked in', 1576738011, 1),
(173, 2, 1, 10284, 'booking', 'edit', 'Booking  10284 checked out', 1576738014, 1),
(174, 2, 1, 10278, 'booking', 'edit', 'Booking  10278 checked in', 1576738157, 1),
(175, 2, 1, 10278, 'booking', 'edit', 'Booking  10278 checked out', 1576738160, 1),
(176, 2, 1, 32, 'room', 'edit', 'room 32 edit', 1576740103, 1),
(177, 2, 1, 33, 'room', 'edit', 'room 33 edit', 1576740251, 1),
(178, 2, 1, 34, 'room', 'edit', 'room 34 edit', 1576740285, 1),
(179, 2, 1, 35, 'room', 'edit', 'room 35 edit', 1576740306, 1),
(180, 2, 1, 36, 'room', 'edit', 'room 36 edit', 1576740575, 1),
(181, 2, 1, 37, 'room', 'edit', 'room 37 edit', 1576740597, 1),
(182, 2, 1, 38, 'room', 'edit', 'room 38 edit', 1576740638, 1),
(183, 2, 1, 39, 'room', 'edit', 'room 39 edit', 1576740655, 1),
(184, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1576747034, 1),
(185, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1576747036, 1),
(186, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1576747045, 1),
(187, 2, 1, 10290, 'booking', 'add', 'New Booking  #id =10290 is Created', 1576747049, 1),
(188, 2, 1, 310, 'user', 'add', 'New user test tset is created', 1576747189, 1),
(189, 2, 1, 188, 'user', 'edit', 'New user test tset is updated', 1576747196, 1),
(190, 2, 1, 188, 'user', 'edit', 'New user test tset is updated', 1576747199, 1),
(191, 2, 1, 188, 'user', 'edit', 'New user test tset is updated', 1576747204, 1),
(192, 2, 1, 311, 'user', 'add', 'New user QQ AA is created', 1576751144, 1),
(193, 2, 1, 192, 'user', 'edit', 'New user QQ AA is updated', 1576751155, 1),
(194, 2, 1, 192, 'user', 'edit', 'New user QQ AA is updated', 1576751270, 1),
(195, 2, 1, 312, 'user', 'add', 'New user zaff aqbal is created', 1576753330, 1),
(196, 2, 1, 195, 'user', 'edit', 'New user zaff aqbal is updated', 1576753334, 1),
(197, 2, 1, 195, 'user', 'edit', 'New user zaff aqbal is updated', 1576753337, 1),
(198, 2, 1, 195, 'user', 'edit', 'New user zaff aqbal is updated', 1576753356, 1),
(199, 2, 1, 313, 'user', 'add', 'New user test test is created', 1576754439, 1),
(200, 2, 1, 199, 'user', 'edit', 'New user test test is updated', 1576754449, 1),
(201, 2, 1, 199, 'user', 'edit', 'New user test test is updated', 1576754479, 1),
(202, 2, 1, 199, 'user', 'edit', 'New user test test is updated', 1576754598, 1),
(203, 2, 1, 199, 'user', 'edit', 'New user test test is updated', 1576755154, 1),
(204, 2, 1, 199, 'user', 'edit', 'New user test test is updated', 1576755326, 1),
(205, 2, 1, 314, 'user', 'add', 'New user Arijit Som is created', 1576757423, 1),
(206, 2, 1, 205, 'user', 'edit', 'New user Arijit Som is updated', 1576757430, 1),
(207, 2, 1, 205, 'user', 'edit', 'New user Arijit Som is updated', 1576757441, 1),
(208, 2, 1, 205, 'user', 'edit', 'New user Arijit Som is updated', 1576757674, 1),
(209, 2, 1, 314, 'user', 'edit', 'New user Arijit Som is updated', 1576757743, 1),
(210, 2, 1, 314, 'user', 'edit', 'New user Arijit Som is updated', 1576757753, 1),
(211, 2, 1, 314, 'user', 'edit', 'New user Arijit Som is updated', 1576757756, 1),
(212, 2, 1, 314, 'user', 'edit', 'New user Arijit Som is updated', 1576757769, 1),
(213, 2, 1, 10291, 'booking', 'add', 'New Booking  #id =10291 is Created', 1576757780, 1),
(214, 2, 1, 315, 'user', 'add', 'New user Kanak Sarkar is created', 1576758016, 1),
(215, 2, 1, 214, 'user', 'edit', 'New user Kanak Sarkar is updated', 1576758021, 1),
(216, 2, 1, 214, 'user', 'edit', 'New user Kanak Sarkar is updated', 1576758031, 1),
(217, 2, 1, 316, 'user', 'add', 'New user kanu Sarkar is created', 1576758510, 1),
(218, 2, 1, 217, 'user', 'edit', 'New user kanu Sarkar is updated', 1576758514, 1),
(219, 2, 1, 217, 'user', 'edit', 'New user kanu Sarkar is updated', 1576758543, 1),
(220, 2, 1, 217, 'user', 'edit', 'New user kanu Sarkar is updated', 1576759295, 1),
(221, 2, 1, 217, 'user', 'edit', 'New user kanu Sarkar is updated', 1576759344, 1),
(222, 2, 1, 317, 'user', 'add', 'New user arshad Iqbal is created', 1576762215, 1),
(223, 2, 1, 318, 'user', 'add', 'New user arshad Iqbal is created', 1576762219, 1),
(224, 2, 1, 319, 'user', 'add', 'New user arshad Iqbal is created', 1576762239, 1),
(225, 2, 1, 320, 'user', 'add', 'New user Arindam biswas is created', 1576762899, 1),
(226, 2, 1, 321, 'user', 'add', 'New user Kanak Sarkar is created', 1576763161, 1),
(227, 2, 1, 321, 'user', 'edit', 'New user Kanak Sarkar is updated', 1576763163, 1),
(228, 2, 1, 321, 'user', 'edit', 'New user Kanak Sarkar is updated', 1576763185, 1),
(229, 2, 1, 10293, 'booking', 'add', 'New Booking  #id =10293 is Created', 1576763197, 1),
(230, 2, 1, 10281, 'booking', 'edit', 'Booking  10281 checked in', 1576822320, 1),
(231, 2, 1, 10281, 'booking', 'edit', 'Booking  10281 checked out', 1576822324, 1),
(232, 2, 1, 10281, 'booking', 'edit', 'booking 10281 edit', 1576822578, 1),
(233, 2, 1, 33, 'room', 'edit', 'room 33 edit', 1576840203, 1),
(234, 2, 1, 19, 'hotel', 'edit', 'hotel 19 edit', 1576840313, 1),
(235, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1576842055, 1),
(236, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1576847540, 1),
(237, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1576847548, 1),
(238, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1576848023, 1),
(239, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1576848025, 1),
(240, 2, 1, 32, 'room', 'edit', 'room 32 edit', 1576849081, 1),
(241, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1576850642, 1),
(242, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1576850645, 1),
(243, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1576850649, 1),
(244, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1576850652, 1),
(245, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1576850681, 1),
(246, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1576850757, 1),
(247, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1576850819, 1),
(248, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1576850834, 1),
(249, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1576850860, 1),
(250, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1576850869, 1),
(251, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1576850880, 1),
(252, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1576851001, 1),
(253, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1576851003, 1),
(254, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1576851008, 1),
(255, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1576851054, 1),
(256, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1576851588, 1),
(257, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1576851591, 1),
(258, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1576851660, 1),
(259, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1576851663, 1),
(260, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1576851723, 1),
(261, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1576851754, 1),
(262, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1576851763, 1),
(263, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1576851767, 1),
(264, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1576851774, 1),
(265, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1576851907, 1),
(266, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1576851908, 1),
(267, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1576851916, 1),
(268, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1576851990, 1),
(269, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1576851994, 1),
(270, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1576852002, 1),
(271, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1576852010, 1),
(272, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1576852069, 1),
(273, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1576852152, 1),
(274, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1576852153, 1),
(275, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1576852264, 1),
(276, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1576852266, 1),
(277, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1576852411, 1),
(278, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1576852414, 1),
(279, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1576852430, 1),
(280, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1576854192, 1),
(281, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1576854195, 1),
(282, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1576854210, 1),
(283, 2, 1, 32, 'room', 'edit', 'room 32 edit', 1577086437, 1),
(284, 2, 1, 0, 'coupon', 'add', 'coupon 0 add', 1577090639, 1),
(285, 2, 1, 2, 'coupon', 'edit', 'coupon 2 edit', 1577090700, 1),
(286, 2, 1, 10307, 'booking', 'edit', 'Booking  10307 checked in', 1577093430, 1),
(287, 2, 1, 10307, 'booking', 'edit', 'Booking  10307 checked out', 1577093452, 1),
(288, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1577093939, 1),
(289, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1577093947, 1),
(290, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1577093953, 1),
(291, 2, 1, 10315, 'booking', 'add', 'New Booking  #id =10315 is Created', 1577093982, 1),
(292, 2, 1, 10315, 'booking', 'edit', 'Booking  10315 checked in', 1577094052, 1),
(293, 2, 1, 10315, 'booking', 'edit', 'Booking  10315 checked out', 1577094062, 1),
(294, 2, 1, 10315, 'booking', 'edit', 'Booking  10315 checked in', 1577094202, 1),
(295, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1577095191, 1),
(296, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1577095210, 1),
(297, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1577095212, 1),
(298, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1577095218, 1),
(299, 2, 1, 10316, 'booking', 'add', 'New Booking  #id =10316 is Created', 1577095228, 1),
(300, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1577100230, 1),
(301, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1577100231, 1),
(302, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1577100238, 1),
(303, 2, 1, 10322, 'booking', 'add', 'New Booking  #id =10322 is Created', 1577100257, 1),
(304, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1577116223, 1),
(305, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1577116226, 1),
(306, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1577116237, 1),
(307, 2, 1, 10323, 'booking', 'add', 'New Booking  #id =10323 is Created', 1577116246, 1),
(308, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1577188865, 1),
(309, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1577188866, 1),
(310, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1577188874, 1),
(311, 2, 1, 10325, 'booking', 'add', 'New Booking  #id =10325 is Created', 1577188885, 1),
(312, 2, 1, 326, 'user', 'add', 'New user test test is created', 1577190422, 1),
(313, 2, 1, 326, 'user', 'edit', 'New user test test is updated', 1577190427, 1),
(314, 2, 1, 10326, 'booking', 'edit', 'booking 10326 edit', 1577192046, 1),
(315, 2, 1, 10327, 'booking', 'edit', 'Booking  10327 Cancelled', 1577343117, 1),
(316, 2, 1, 10325, 'booking', 'edit', 'Booking  10325 Cancelled', 1577353000, 1),
(317, 2, 1, 10331, 'booking', 'edit', 'Booking  10331 checked in', 1577430959, 1),
(318, 2, 1, 10331, 'booking', 'edit', 'Booking  10331 checked in', 1577438717, 1),
(319, 2, 1, 10331, 'booking', 'edit', 'Booking  10331 checked in', 1577438739, 1),
(320, 2, 1, 10332, 'booking', 'edit', 'Booking  10332 checked in', 1577438860, 1),
(321, 2, 1, 10332, 'booking', 'edit', 'Booking  10332 checked in', 1577438896, 1),
(322, 2, 1, 10331, 'booking', 'edit', 'Booking  10331 checked in', 1577438969, 1),
(323, 2, 1, 10339, 'booking', 'edit', 'Booking  10339 checked in', 1577443209, 1),
(324, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1577449157, 1),
(325, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1577449164, 1),
(326, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1577449178, 1),
(327, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1577452820, 1),
(328, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1577453253, 1),
(329, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1577453324, 1),
(330, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1577453399, 1),
(331, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1577453568, 1),
(332, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1577453698, 1),
(333, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1577453865, 1),
(334, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1577453893, 1),
(335, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1577455090, 1),
(336, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1577455091, 1),
(337, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1577455095, 1),
(338, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1577455102, 1),
(339, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1577455102, 1),
(340, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1577455103, 1),
(341, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1577455105, 1),
(342, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1577455105, 1),
(343, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1577455107, 1),
(344, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1577455113, 1),
(345, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1577455113, 1),
(346, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1577455115, 1),
(347, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1577455116, 1),
(348, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1577455117, 1),
(349, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1577455119, 1),
(350, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1577455119, 1),
(351, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1577455120, 1),
(352, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1577455120, 1),
(353, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1577455121, 1),
(354, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1577455121, 1),
(355, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1577455121, 1),
(356, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1577455122, 1),
(357, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1577455122, 1),
(358, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1577455123, 1),
(359, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1577455123, 1),
(360, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1577455124, 1),
(361, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1577455124, 1),
(362, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1577455125, 1),
(363, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1577455125, 1),
(364, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1577455126, 1),
(365, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1577455127, 1),
(366, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1577455136, 1),
(367, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1577455136, 1),
(368, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1577455149, 1),
(369, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1577455737, 1),
(370, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1577455739, 1),
(371, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1577455742, 1),
(372, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1577455744, 1),
(373, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1577455745, 1),
(374, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1577455754, 1),
(375, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1577455755, 1),
(376, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1577456037, 1),
(377, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1577456038, 1),
(378, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1577456039, 1),
(379, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1577456041, 1),
(380, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1577456043, 1),
(381, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1577456044, 1),
(382, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1577456048, 1),
(383, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1577456055, 1),
(384, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1577456055, 1),
(385, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1577456057, 1),
(386, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1577456172, 1),
(387, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1577456177, 1),
(388, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1577456177, 1),
(389, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1577456178, 1),
(390, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1577456180, 1),
(391, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1577456180, 1),
(392, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1577456183, 1),
(393, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1577456185, 1),
(394, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1577456187, 1),
(395, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1577456222, 1),
(396, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1577456245, 1),
(397, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1577456245, 1),
(398, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1577456247, 1),
(399, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1577456248, 1),
(400, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1577456249, 1),
(401, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1577456359, 1),
(402, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1577456399, 1),
(403, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1577456403, 1),
(404, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1577456409, 1),
(405, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1577456430, 1),
(406, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1577456431, 1),
(407, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1577456432, 1),
(408, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1577456433, 1),
(409, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1577456529, 1),
(410, 2, 1, 231, 'user', 'edit', 'New user Traveler Traveler is updated', 1577456589, 1),
(411, 2, 1, 10341, 'booking', 'edit', 'booking 10341 edit', 1577682803, 1),
(412, 2, 1, 10341, 'booking', 'edit', 'Booking  10341 checked in', 1577682842, 1),
(413, 2, 1, 19, 'hotel', 'edit', 'hotel 19 edit', 1577710177, 1),
(414, 2, 1, 19, 'hotel', 'edit', 'hotel 19 edit', 1577710197, 1),
(415, 2, 1, 19, 'hotel', 'edit', 'hotel 19 edit', 1577710721, 1),
(416, 2, 1, 290, 'user', 'edit', 'user 290 edit', 1577713078, 1),
(417, 2, 1, 0, 'user', 'add', 'user 0 add', 1577714629, 1),
(418, 2, 1, 327, 'user', 'edit', 'user 327 edit', 1577714655, 1),
(419, 2, 1, 327, 'user', 'edit', 'user 327 edit', 1577714666, 1),
(420, 2, 1, 237, 'user', 'edit', 'user 237 edit', 1577718253, 1),
(421, 2, 1, 288, 'user', 'edit', 'user 288 edit', 1577718276, 1),
(422, 2, 1, 5, 'page', 'edit', 'page 5 edit', 1577784743, 1),
(423, 2, 1, 5, 'page', 'edit', 'page 5 edit', 1577784887, 1),
(424, 2, 1, 5, 'page', 'edit', 'page 5 edit', 1577785068, 1),
(425, 2, 1, 19, 'hotel', 'edit', 'hotel 19 edit', 1577785177, 1),
(426, 2, 1, 5, 'page', 'edit', 'page 5 edit', 1577785836, 1),
(427, 2, 1, 5, 'page', 'edit', 'page 5 edit', 1577785874, 1),
(428, 2, 1, 5, 'page', 'edit', 'page 5 edit', 1577785890, 1),
(429, 2, 1, 35, 'page', 'edit', 'page 35 edit', 1577786008, 1),
(430, 2, 1, 35, 'page', 'edit', 'page 35 edit', 1577786129, 1),
(431, 2, 1, 35, 'page', 'edit', 'page 35 edit', 1577786377, 1),
(432, 2, 1, 36, 'page', 'edit', 'page 36 edit', 1577786559, 1),
(433, 2, 1, 2, 'page', 'edit', 'page 2 edit', 1577941190, 1),
(434, 2, 1, 134, 'text', 'edit', 'text 134 edit', 1577948536, 1),
(435, 2, 1, 161, 'text', 'edit', 'text 161 edit', 1577948576, 1),
(436, 2, 1, 52, 'room', 'edit', 'room 52 edit', 1577956395, 1),
(437, 2, 1, 42, 'rate', 'edit', 'rate 42 edit', 1578295215, 1),
(438, 2, 1, 42, 'rate', 'edit', 'rate 42 edit', 1578295228, 1),
(439, 2, 1, 43, 'rate', 'edit', 'rate 43 edit', 1578295259, 1),
(440, 2, 1, 41, 'rate', 'edit', 'rate 41 edit', 1578295297, 1),
(441, 2, 1, 39, 'rate', 'edit', 'rate 39 edit', 1578295341, 1),
(442, 2, 1, 40, 'rate', 'edit', 'rate 40 edit', 1578295377, 1),
(443, 2, 1, 50, 'rate', 'edit', 'rate 50 edit', 1578295408, 1),
(444, 2, 1, 53, 'rate', 'edit', 'rate 53 edit', 1578295457, 1),
(445, 2, 1, 49, 'rate', 'edit', 'rate 49 edit', 1578295493, 1),
(446, 2, 1, 46, 'rate', 'edit', 'rate 46 edit', 1578295519, 1),
(447, 2, 1, 44, 'rate', 'edit', 'rate 44 edit', 1578295545, 1),
(448, 2, 1, 45, 'rate', 'edit', 'rate 45 edit', 1578295594, 1),
(449, 2, 1, 48, 'rate', 'edit', 'rate 48 edit', 1578295617, 1),
(450, 2, 1, 54, 'rate', 'edit', 'rate 54 edit', 1578295646, 1),
(451, 2, 1, 55, 'rate', 'edit', 'rate 55 edit', 1578295678, 1),
(452, 2, 1, 55, 'rate', 'edit', 'rate 55 edit', 1578295727, 1),
(453, 2, 1, 47, 'rate', 'edit', 'rate 47 edit', 1578295750, 1),
(454, 2, 1, 52, 'rate', 'edit', 'rate 52 edit', 1578295788, 1),
(455, 2, 1, 35, 'rate', 'edit', 'rate 35 edit', 1578295922, 1),
(456, 2, 1, 37, 'rate', 'edit', 'rate 37 edit', 1578295977, 1),
(457, 2, 1, 51, 'rate', 'edit', 'rate 51 edit', 1578296006, 1),
(458, 2, 1, 36, 'rate', 'edit', 'rate 36 edit', 1578296043, 1),
(459, 2, 1, 38, 'rate', 'edit', 'rate 38 edit', 1578296077, 1),
(460, 2, 1, 42, 'rate', 'edit', 'rate 42 edit', 1578313081, 1),
(461, 2, 1, 10389, 'booking', 'edit', 'Booking  10389 checked in', 1578378921, 1),
(462, 2, 1, 22, 'hotel', 'edit', 'hotel 22 edit', 1578393951, 1),
(463, 2, 1, 10403, 'booking', 'edit', 'Booking  10403 checked in', 1578399502, 1),
(464, 2, 1, 267, 'user', 'edit', 'user 267 edit', 1578405291, 1),
(465, 2, 1, 267, 'user', 'edit', 'user 267 edit', 1578405308, 1),
(466, 2, 1, 10404, 'booking', 'edit', 'Booking  10404 checked in', 1578406270, 1),
(467, 2, 1, 66, 'comment', 'edit', 'comment 66 edit', 1578551372, 1),
(468, 2, 1, 10408, 'booking', 'edit', 'Booking  10408 Cancelled', 1578556315, 1),
(469, 2, 267, 10390, 'booking', 'edit', 'Booking  10390 checked in', 1578568104, 1),
(470, 2, 267, 10390, 'booking', 'edit', 'Booking  10390 checked out', 1578568110, 1),
(471, 2, 1, 340, 'user', 'add', 'New user eon test is created', 1578571743, 1),
(472, 2, 1, 341, 'user', 'add', 'New user eon test is created', 1578571744, 1),
(473, 2, 1, 341, 'user', 'edit', 'New user eon test is updated', 1578571747, 1),
(474, 2, 1, 341, 'user', 'edit', 'New user eon test is updated', 1578571751, 1),
(475, 2, 1, 341, 'user', 'edit', 'New user eon test is updated', 1578571751, 1),
(476, 2, 1, 341, 'user', 'edit', 'New user eon test is updated', 1578571752, 1),
(477, 2, 1, 341, 'user', 'edit', 'New user eon test is updated', 1578571753, 1),
(478, 2, 1, 341, 'user', 'edit', 'New user eon test is updated', 1578571753, 1),
(479, 2, 1, 341, 'user', 'edit', 'New user eon test is updated', 1578571754, 1),
(480, 2, 1, 341, 'user', 'edit', 'New user eon test is updated', 1578571755, 1),
(481, 2, 1, 341, 'user', 'edit', 'New user eon test is updated', 1578571756, 1),
(482, 2, 1, 341, 'user', 'edit', 'New user eon test is updated', 1578571756, 1),
(483, 2, 1, 341, 'user', 'edit', 'New user eon test is updated', 1578571758, 1),
(484, 2, 1, 341, 'user', 'edit', 'New user eon test is updated', 1578571758, 1),
(485, 2, 1, 341, 'user', 'edit', 'New user eon test is updated', 1578571759, 1),
(486, 2, 1, 341, 'user', 'edit', 'New user eon test is updated', 1578571760, 1),
(487, 2, 1, 341, 'user', 'edit', 'New user eon test is updated', 1578571760, 1),
(488, 2, 1, 341, 'user', 'edit', 'New user eon test is updated', 1578571761, 1),
(489, 2, 1, 341, 'user', 'edit', 'New user eon test is updated', 1578571761, 1),
(490, 2, 1, 341, 'user', 'edit', 'New user eon test is updated', 1578571761, 1),
(491, 2, 1, 341, 'user', 'edit', 'New user eon test is updated', 1578571762, 1),
(492, 2, 1, 341, 'user', 'edit', 'New user eon test is updated', 1578571763, 1),
(493, 2, 1, 341, 'user', 'edit', 'New user eon test is updated', 1578571763, 1),
(494, 2, 1, 341, 'user', 'edit', 'New user eon test is updated', 1578571764, 1),
(495, 2, 1, 341, 'user', 'edit', 'New user eon test is updated', 1578571765, 1),
(496, 2, 1, 341, 'user', 'edit', 'New user eon test is updated', 1578571765, 1),
(497, 2, 1, 341, 'user', 'edit', 'New user eon test is updated', 1578571766, 1),
(498, 2, 1, 341, 'user', 'edit', 'New user eon test is updated', 1578571767, 1),
(499, 2, 1, 341, 'user', 'edit', 'New user eon test is updated', 1578571768, 1),
(500, 2, 1, 341, 'user', 'edit', 'New user eon test is updated', 1578571769, 1),
(501, 2, 1, 341, 'user', 'edit', 'New user eon test is updated', 1578571770, 1),
(502, 2, 1, 341, 'user', 'edit', 'New user eon test is updated', 1578571770, 1),
(503, 2, 1, 341, 'user', 'edit', 'New user eon test is updated', 1578571771, 1),
(504, 2, 1, 341, 'user', 'edit', 'New user eon test is updated', 1578571777, 1),
(505, 2, 1, 341, 'user', 'edit', 'New user eon test is updated', 1578571777, 1),
(506, 2, 1, 341, 'user', 'edit', 'New user eon test is updated', 1578571789, 1),
(507, 2, 1, 341, 'user', 'edit', 'New user eon test is updated', 1578571789, 1),
(508, 2, 1, 341, 'user', 'edit', 'New user eon test is updated', 1578571789, 1),
(509, 2, 1, 341, 'user', 'edit', 'New user eon test is updated', 1578571790, 1),
(510, 2, 1, 341, 'user', 'edit', 'New user eon test is updated', 1578571790, 1),
(511, 2, 1, 341, 'user', 'edit', 'New user eon test is updated', 1578571790, 1),
(512, 2, 1, 267, 'user', 'edit', 'user 267 edit', 1578576431, 1),
(513, 2, 1, 342, 'user', 'add', 'New user chi ca is created', 1578578593, 1),
(514, 2, 1, 343, 'user', 'add', 'New user chi ca is created', 1578578593, 1),
(515, 2, 1, 343, 'user', 'edit', 'New user chi ca is updated', 1578578601, 1),
(516, 2, 1, 344, 'user', 'add', 'New user traveler traveler is created', 1578579165, 1),
(517, 2, 1, 345, 'user', 'add', 'New user tet ya is created', 1578579490, 1),
(518, 2, 1, 345, 'user', 'edit', 'New user tet ya is updated', 1578579509, 1),
(519, 2, 1, 345, 'user', 'edit', 'New user tet ya is updated', 1578579509, 1),
(520, 2, 1, 345, 'user', 'edit', 'New user tet ya is updated', 1578579512, 1),
(521, 2, 1, 345, 'user', 'edit', 'New user tet ya is updated', 1578579513, 1),
(522, 2, 1, 345, 'user', 'edit', 'New user tet ya is updated', 1578579516, 1),
(523, 2, 1, 10407, 'booking', 'edit', 'Booking  10407 Cancelled', 1578580662, 1),
(524, 2, 1, 10404, 'booking', 'edit', 'Booking  10404 Cancelled', 1578580850, 1),
(525, 2, 1, 10401, 'booking', 'edit', 'Booking  10401 Cancelled', 1578581401, 1),
(526, 2, 1, 10408, 'booking', 'edit', 'Booking  10408 checked in', 1578639158, 1),
(527, 2, 1, 10408, 'booking', 'edit', 'Booking  10408 checked out', 1578646286, 1),
(528, 2, 1, 10387, 'booking', 'edit', 'Booking  10387 checked in', 1578647396, 1),
(529, 2, 1, 10387, 'booking', 'edit', 'Booking  10387 checked out', 1578647400, 1),
(530, 2, 1, 10400, 'booking', 'edit', 'Booking  10400 checked in', 1578647442, 1),
(531, 2, 1, 10400, 'booking', 'edit', 'Booking  10400 checked out', 1578647445, 1),
(532, 2, 1, 10389, 'booking', 'edit', 'Booking  10389 checked out', 1578648301, 1),
(533, 2, 1, 10404, 'booking', 'edit', 'Booking  10404 checked in', 1578648571, 1),
(534, 2, 1, 267, 'user', 'edit', 'user 267 edit', 1578652215, 1),
(535, 2, 1, 19, 'hotel', 'edit', 'hotel 19 edit', 1578898915, 1),
(536, 2, 1, 237, 'user', 'edit', 'New user Zafar Ansari is updated', 1578899475, 1),
(537, 2, 1, 237, 'user', 'edit', 'New user Zafar Ansari is updated', 1578899478, 1),
(538, 2, 1, 237, 'user', 'edit', 'New user Zafar Ansari is updated', 1578899481, 1),
(539, 2, 1, 237, 'user', 'edit', 'New user Zafar Ansari is updated', 1578899485, 1),
(540, 2, 1, 237, 'user', 'edit', 'New user Zafar Ansari is updated', 1578899488, 1),
(541, 2, 1, 237, 'user', 'edit', 'New user Zafar Ansari is updated', 1578899497, 1),
(542, 2, 1, 10412, 'booking', 'add', 'New Booking  #id =10412 is Created', 1578899507, 1),
(543, 2, 1, 237, 'user', 'edit', 'New user Zafar Ansari is updated', 1578904278, 1),
(544, 2, 1, 237, 'user', 'edit', 'New user Zafar Ansari is updated', 1578904279, 1),
(545, 2, 1, 237, 'user', 'edit', 'New user Zafar Ansari is updated', 1578904296, 1),
(546, 2, 1, 237, 'user', 'edit', 'New user Zafar Ansari is updated', 1578904298, 1),
(547, 2, 1, 237, 'user', 'edit', 'New user Zafar Ansari is updated', 1578904304, 1),
(548, 2, 1, 10415, 'booking', 'add', 'New Booking  #id =10415 is Created', 1578904312, 1),
(549, 2, 1, 267, 'user', 'edit', 'user 267 edit', 1578904537, 1),
(550, 2, 1, 237, 'user', 'edit', 'New user Zafar Ansari is updated', 1578913156, 1),
(551, 2, 1, 237, 'user', 'edit', 'New user Zafar Ansari is updated', 1578913164, 1),
(552, 2, 1, 237, 'user', 'edit', 'New user Zafar Ansari is updated', 1578913165, 1),
(553, 2, 1, 237, 'user', 'edit', 'New user Zafar Ansari is updated', 1578913171, 1),
(554, 2, 1, 10417, 'booking', 'add', 'New Booking  #id =10417 is Created', 1578913178, 1),
(555, 2, 1, 10414, 'booking', 'edit', 'Booking  10414 checked in', 1578919779, 1),
(556, 2, 1, 10414, 'booking', 'edit', 'booking 10414 edit', 1578919814, 1),
(557, 2, 1, 10414, 'booking', 'edit', 'Booking  10414 checked out', 1578919856, 1),
(558, 2, 1, 0, 'room', 'add', 'room 0 add', 1578920192, 1),
(559, 2, 1, 0, 'rate', 'add', 'rate 0 add', 1578920378, 1),
(560, 2, 1, 33, 'room', 'edit', 'room 33 edit', 1578997531, 1),
(561, 2, 1, 33, 'room', 'edit', 'room 33 edit', 1578997570, 1),
(562, 2, 1, 33, 'room', 'edit', 'room 33 edit', 1578997596, 1),
(563, 2, 1, 33, 'room', 'edit', 'room 33 edit', 1578999451, 1),
(564, 2, 1, 33, 'room', 'edit', 'room 33 edit', 1579001294, 1),
(565, 2, 1, 33, 'room', 'edit', 'room 33 edit', 1579002108, 1),
(566, 2, 1, 33, 'room', 'edit', 'room 33 edit', 1579002129, 1),
(567, 2, 1, 33, 'room', 'edit', 'room 33 edit', 1579002313, 1),
(568, 2, 1, 32, 'room', 'edit', 'room 32 edit', 1579002379, 1),
(569, 2, 1, 10420, 'booking', 'edit', 'Booking  10420 checked in', 1579005446, 1),
(570, 2, 1, 10407, 'booking', 'edit', 'Booking  10407 checked in', 1579005646, 1),
(571, 2, 1, 10396, 'booking', 'edit', 'Booking  10396 checked in', 1579007499, 1),
(572, 2, 1, 10396, 'booking', 'edit', 'Booking  10396 checked out', 1579007589, 1),
(573, 2, 1, 10420, 'booking', 'edit', 'Booking  10420 checked in', 1579008014, 1),
(574, 2, 1, 10407, 'booking', 'edit', 'Booking  10407 checked in', 1579008064, 1),
(575, 2, 1, 10417, 'booking', 'edit', 'Booking  10417 checked in', 1579008100, 1),
(576, 2, 1, 10417, 'booking', 'edit', 'Booking  10417 checked out', 1579008122, 1),
(577, 2, 1, 53, 'rate', 'edit', 'rate 53 edit', 1579010009, 1),
(578, 2, 1, 346, 'user', 'add', 'New user From Bakend is created', 1579077750, 1),
(579, 2, 1, 346, 'user', 'edit', 'New user From Bakend is updated', 1579077765, 1),
(580, 2, 1, 346, 'user', 'edit', 'New user From Bakend is updated', 1579077765, 1),
(581, 2, 1, 346, 'user', 'edit', 'New user From Bakend is updated', 1579077766, 1),
(582, 2, 1, 346, 'user', 'edit', 'New user From Bakend is updated', 1579077771, 1),
(583, 2, 1, 346, 'user', 'edit', 'New user From Bakend is updated', 1579077772, 1),
(584, 2, 1, 346, 'user', 'edit', 'New user From Bakend is updated', 1579077779, 1),
(585, 2, 1, 346, 'user', 'edit', 'New user From Bakend is updated', 1579077781, 1),
(586, 2, 1, 346, 'user', 'edit', 'New user From Bakend is updated', 1579077783, 1),
(587, 2, 1, 346, 'user', 'edit', 'New user From Bakend is updated', 1579077801, 1),
(588, 2, 1, 10426, 'booking', 'add', 'New Booking  #id =10426 is Created', 1579077817, 1),
(589, 2, 1, 346, 'user', 'edit', 'New user From Bakend is updated', 1579078716, 1),
(590, 2, 1, 346, 'user', 'edit', 'New user From Bakend is updated', 1579078717, 1),
(591, 2, 1, 346, 'user', 'edit', 'New user From Bakend is updated', 1579078720, 1),
(592, 2, 1, 346, 'user', 'edit', 'New user From Bakend is updated', 1579078821, 1),
(593, 2, 1, 346, 'user', 'edit', 'New user From Bakend is updated', 1579078822, 1),
(594, 2, 1, 346, 'user', 'edit', 'New user From Bakend is updated', 1579078826, 1),
(595, 2, 1, 346, 'user', 'edit', 'New user From Bakend is updated', 1579078850, 1),
(596, 2, 1, 346, 'user', 'edit', 'New user From Bakend is updated', 1579078851, 1),
(597, 2, 1, 346, 'user', 'edit', 'New user From Bakend is updated', 1579078854, 1),
(598, 2, 1, 346, 'user', 'edit', 'New user From Bakend is updated', 1579079580, 1),
(599, 2, 1, 346, 'user', 'edit', 'New user From Bakend is updated', 1579079581, 1),
(600, 2, 1, 346, 'user', 'edit', 'New user From Bakend is updated', 1579079598, 1),
(601, 2, 1, 346, 'user', 'edit', 'New user From Bakend is updated', 1579079701, 1),
(602, 2, 1, 346, 'user', 'edit', 'New user From Bakend is updated', 1579079701, 1),
(603, 2, 1, 346, 'user', 'edit', 'New user From Bakend is updated', 1579079708, 1),
(604, 2, 1, 346, 'user', 'edit', 'New user From Bakend is updated', 1579079828, 1),
(605, 2, 1, 346, 'user', 'edit', 'New user From Bakend is updated', 1579079829, 1),
(606, 2, 1, 346, 'user', 'edit', 'New user From Bakend is updated', 1579079832, 1),
(607, 2, 1, 346, 'user', 'edit', 'New user From Bakend is updated', 1579079928, 1),
(608, 2, 1, 346, 'user', 'edit', 'New user From Bakend is updated', 1579079935, 1),
(609, 2, 1, 346, 'user', 'edit', 'New user From Bakend is updated', 1579079937, 1),
(610, 2, 1, 346, 'user', 'edit', 'New user From Bakend is updated', 1579079942, 1),
(611, 2, 1, 346, 'user', 'edit', 'New user From Bakend is updated', 1579083498, 1),
(612, 2, 1, 346, 'user', 'edit', 'New user From Bakend is updated', 1579083500, 1),
(613, 2, 1, 346, 'user', 'edit', 'New user From Bakend is updated', 1579083503, 1),
(614, 2, 1, 10427, 'booking', 'add', 'New Booking  #id =10427 is Created', 1579083509, 1),
(615, 2, 1, 10438, 'booking', 'edit', 'Booking  10438 checked in', 1579173575, 1),
(616, 2, 1, 10438, 'booking', 'edit', 'Booking  10438 checked out', 1579173915, 1),
(617, 2, 1, 0, 'room', 'add', 'room 0 add', 1579507515, 1),
(618, 2, 1, 55, 'room', 'edit', 'room 55 edit', 1579509264, 1),
(619, 2, 1, 55, 'room', 'edit', 'room 55 edit', 1579509315, 1),
(620, 2, 1, 55, 'room', 'edit', 'room 55 edit', 1579509393, 1),
(621, 2, 1, 55, 'room', 'edit', 'room 55 edit', 1579511256, 1),
(622, 2, 1, 55, 'room', 'edit', 'room 55 edit', 1579511264, 1),
(623, 2, 1, 55, 'room', 'edit', 'room 55 edit', 1579511412, 1),
(624, 2, 1, 55, 'room', 'edit', 'room 55 edit', 1579511522, 1),
(625, 2, 1, 54, 'room', 'edit', 'room 54 edit', 1579512743, 1),
(626, 2, 1, 54, 'room', 'edit', 'room 54 edit', 1579512947, 1),
(627, 2, 1, 54, 'room', 'edit', 'room 54 edit', 1579513025, 1),
(628, 2, 1, 54, 'room', 'edit', 'room 54 edit', 1579513196, 1),
(629, 2, 1, 54, 'room', 'edit', 'room 54 edit', 1579513237, 1),
(630, 2, 1, 54, 'room', 'edit', 'room 54 edit', 1579513270, 1),
(631, 2, 1, 56, 'rate', 'edit', 'rate 56 edit', 1579513282, 1),
(632, 2, 1, 54, 'room', 'edit', 'room 54 edit', 1579513790, 1),
(633, 2, 1, 0, 'room', 'add', 'room 0 add', 1579513849, 1),
(634, 2, 1, 56, 'room', 'edit', 'room 56 edit', 1579514149, 1),
(635, 2, 1, 56, 'room', 'edit', 'room 56 edit', 1579514176, 1),
(636, 2, 1, 0, 'rate', 'add', 'rate 0 add', 1579514218, 1),
(637, 2, 1, 0, 'rate', 'add', 'rate 0 add', 1579514250, 1),
(638, 2, 1, 54, 'room', 'edit', 'room 54 edit', 1579514396, 1),
(639, 2, 1, 54, 'room', 'edit', 'room 54 edit', 1579514406, 1);
INSERT INTO `pm_activity_log` (`id`, `lang`, `user_id`, `id_item`, `module`, `action`, `purpose`, `add_date`, `checked`) VALUES
(640, 2, 1, 0, 'room', 'add', 'room 0 add', 1579514568, 1),
(641, 2, 1, 56, 'room', 'edit', 'room 56 edit', 1579517660, 1),
(642, 2, 1, 0, 'room', 'add', 'room 0 add', 1579521050, 1),
(643, 2, 1, 17, 'offer', 'edit', 'offer 17 edit', 1579521623, 1),
(644, 2, 1, 58, 'room', 'edit', 'room 58 edit', 1579523352, 1),
(645, 2, 1, 0, 'room', 'add', 'room 0 add', 1579523438, 1),
(646, 2, 1, 0, 'room', 'add', 'room 0 add', 1579525279, 1),
(647, 2, 1, 0, 'room', 'add', 'room 0 add', 1579525729, 1),
(648, 2, 1, 61, 'room', 'edit', 'room 61 edit', 1579525798, 1),
(649, 2, 1, 0, 'room', 'add', 'room 0 add', 1579526028, 1),
(650, 2, 1, 0, 'room', 'add', 'room 0 add', 1579526231, 1),
(651, 2, 1, 0, 'room', 'add', 'room 0 add', 1579526313, 1),
(652, 2, 1, 0, 'room', 'add', 'room 0 add', 1579526501, 1),
(653, 2, 1, 0, 'room', 'add', 'room 0 add', 1579526990, 1),
(654, 2, 1, 0, 'room', 'add', 'room 0 add', 1579527150, 1),
(655, 2, 1, 54, 'room', 'edit', 'room 54 edit', 1579527833, 1),
(656, 2, 1, 31, 'hotel', 'edit', 'hotel 31 edit', 1579527866, 1),
(657, 2, 1, 31, 'hotel', 'edit', 'hotel 31 edit', 1579527888, 1),
(658, 2, 1, 0, 'room', 'add', 'room 0 add', 1579528451, 1),
(659, 2, 1, 0, 'room', 'add', 'room 0 add', 1579529128, 1),
(660, 2, 1, 69, 'room', 'edit', 'room 69 edit', 1579529157, 1),
(661, 2, 1, 0, 'room', 'add', 'room 0 add', 1579529436, 1),
(662, 2, 1, 0, 'room', 'add', 'room 0 add', 1579529854, 1),
(663, 2, 1, 0, 'hotel', 'add', 'hotel 0 add', 1579530000, 1),
(664, 2, 1, 0, 'room', 'add', 'room 0 add', 1579586774, 1),
(665, 2, 1, 0, 'rate', 'add', 'rate 0 add', 1579588739, 1),
(666, 2, 1, 0, 'room', 'add', 'room 0 add', 1579589764, 1),
(667, 2, 1, 0, 'room', 'add', 'room 0 add', 1579590539, 1),
(668, 2, 1, 0, 'rate', 'add', 'rate 0 add', 1579590732, 1),
(669, 2, 1, 0, 'rate', 'add', 'rate 0 add', 1579590786, 1),
(670, 2, 1, 0, 'rate', 'add', 'rate 0 add', 1579590807, 1),
(671, 2, 1, 0, 'rate', 'add', 'rate 0 add', 1579591067, 1),
(672, 2, 1, 0, 'rate', 'add', 'rate 0 add', 1579591090, 1),
(673, 2, 1, 0, 'rate', 'add', 'rate 0 add', 1579591501, 1),
(674, 2, 1, 0, 'rate', 'add', 'rate 0 add', 1579592253, 1),
(675, 2, 1, 74, 'room', 'edit', 'room 74 edit', 1579592267, 1),
(676, 2, 1, 58, 'rate', 'edit', 'rate 58 edit', 1579592275, 1),
(677, 2, 1, 0, 'room', 'add', 'room 0 add', 1579592464, 1),
(678, 2, 1, 0, 'rate', 'add', 'rate 0 add', 1579592621, 1),
(679, 2, 1, 59, 'rate', 'edit', 'rate 59 edit', 1579592650, 1),
(680, 2, 1, 0, 'room', 'add', 'room 0 add', 1579592691, 1),
(681, 2, 1, 0, 'rate', 'add', 'rate 0 add', 1579592726, 1),
(682, 2, 1, 0, 'room', 'add', 'room 0 add', 1579593816, 1),
(683, 2, 1, 0, 'room', 'add', 'room 0 add', 1579593943, 1),
(684, 2, 1, 0, 'room', 'add', 'room 0 add', 1579595136, 1),
(685, 2, 1, 0, 'rate', 'add', 'rate 0 add', 1579595170, 1),
(686, 2, 1, 0, 'room', 'add', 'room 0 add', 1579595312, 1),
(687, 2, 1, 0, 'room', 'add', 'room 0 add', 1579596130, 1),
(688, 2, 1, 0, 'room', 'add', 'room 0 add', 1579596530, 1),
(689, 2, 1, 0, 'room', 'add', 'room 0 add', 1579596839, 1),
(690, 2, 1, 0, 'room', 'add', 'room 0 add', 1579596902, 1),
(691, 2, 1, 0, 'room', 'add', 'room 0 add', 1579597726, 1),
(692, 2, 1, 0, 'rate', 'add', 'rate 0 add', 1579597837, 1),
(693, 2, 1, 0, 'room', 'add', 'room 0 add', 1579597877, 1),
(694, 2, 1, 0, 'rate', 'add', 'rate 0 add', 1579597891, 1),
(695, 2, 1, 0, 'room', 'add', 'room 0 add', 1579599141, 1),
(696, 2, 1, 0, 'rate', 'add', 'rate 0 add', 1579599161, 1),
(697, 2, 1, 10454, 'booking', 'edit', 'booking 10454 edit', 1579781757, 1),
(698, 2, 1, 10498, 'booking', 'edit', 'Booking  10498 checked in', 1579852338, 1),
(699, 2, 1, 24, 'hotel', 'edit', 'hotel 24 edit', 1579864985, 1),
(700, 2, 1, 24, 'hotel', 'edit', 'hotel 24 edit', 1579865202, 1),
(701, 2, 1, 24, 'hotel', 'edit', 'hotel 24 edit', 1579865244, 1),
(702, 2, 1, 10523, 'booking', 'edit', 'Booking  10523 checked in', 1580197939, 1),
(703, 2, 1, 10523, 'booking', 'edit', 'Booking  10523 checked out', 1580198033, 1),
(704, 2, 1, 10522, 'booking', 'edit', 'booking 10522 edit', 1580198213, 1),
(705, 2, 1, 10522, 'booking', 'edit', 'booking 10522 edit', 1580198231, 1),
(706, 2, 1, 10522, 'booking', 'edit', 'booking 10522 edit', 1580198259, 1),
(707, 2, 1, 10523, 'booking', 'edit', 'booking 10523 edit', 1580198281, 1),
(708, 2, 1, 10522, 'booking', 'edit', 'Booking  10522 checked in', 1580198349, 1),
(709, 2, 1, 10522, 'booking', 'edit', 'Booking  10522 checked out', 1580198382, 1),
(710, 2, 1, 0, 'room', 'add', 'room 0 add', 1580198542, 1),
(711, 2, 1, 0, 'room', 'add', 'room 0 add', 1580199420, 1),
(712, 2, 1, 65, 'room', 'edit', 'room 65 edit', 1580199426, 1),
(713, 2, 1, 0, 'room', 'add', 'room 0 add', 1580199519, 1),
(714, 2, 1, 10532, 'booking', 'edit', 'Booking  10532 checked in', 1580203951, 1),
(715, 2, 1, 10532, 'booking', 'edit', 'Booking  10532 checked out', 1580204009, 1),
(716, 2, 1, 43, 'room', 'edit', 'room 43 edit', 1580215029, 1),
(717, 2, 1, 39, 'room', 'edit', 'room 39 edit', 1580275204, 1),
(718, 2, 1, 39, 'room', 'edit', 'room 39 edit', 1580276074, 1),
(719, 2, 1, 10512, 'booking', 'edit', 'booking 10512 edit', 1580278438, 1),
(720, 2, 1, 10537, 'booking', 'edit', 'Booking  10537 checked in', 1580278948, 1),
(721, 2, 1, 10537, 'booking', 'edit', 'Booking  10537 checked out', 1580278962, 1),
(722, 2, 1, 10538, 'booking', 'edit', 'booking 10538 edit', 1580299567, 1),
(723, 2, 1, 10538, 'booking', 'edit', 'Booking  10538 checked in', 1580299597, 1),
(724, 2, 1, 10537, 'booking', 'edit', 'booking 10537 edit', 1580300130, 1),
(725, 2, 1, 39, 'room', 'edit', 'room 39 edit', 1580364612, 1),
(726, 2, 1, 39, 'room', 'edit', 'room 39 edit', 1580365012, 1),
(727, 2, 1, 39, 'room', 'edit', 'room 39 edit', 1580365764, 1),
(728, 2, 1, 39, 'room', 'edit', 'room 39 edit', 1580365820, 1),
(729, 2, 1, 32, 'room', 'edit', 'room 32 edit', 1580367465, 1),
(730, 2, 1, 32, 'room', 'edit', 'room 32 edit', 1580367575, 1),
(731, 2, 1, 32, 'room', 'edit', 'room 32 edit', 1580367863, 1),
(732, 2, 1, 32, 'room', 'edit', 'room 32 edit', 1580368182, 1),
(733, 2, 1, 10557, 'booking', 'edit', 'booking 10557 edit', 1580457240, 1),
(734, 2, 1, 10557, 'booking', 'edit', 'Booking  10557 checked in', 1580457256, 1),
(735, 2, 1, 10557, 'booking', 'edit', 'Booking  10557 checked out', 1580457333, 1),
(736, 2, 1, 35, 'room', 'edit', 'room 35 edit', 1580470872, 1),
(737, 2, 1, 40, 'room', 'edit', 'room 40 edit', 1580476050, 1),
(738, 2, 1, 10564, 'booking', 'edit', 'Booking  10564 checked in', 1580737334, 1),
(739, 2, 1, 10564, 'booking', 'edit', 'Booking  10564 checked in', 1580737696, 1),
(740, 2, 1, 10564, 'booking', 'edit', 'Booking  10564 checked in', 1580737910, 1),
(741, 2, 1, 10564, 'booking', 'edit', 'booking 10564 edit', 1580738772, 1),
(742, 2, 1, 10564, 'booking', 'edit', 'Booking  10564 checked out', 1580738933, 1),
(743, 2, 1, 10565, 'booking', 'edit', 'Booking  10565 checked in', 1580818134, 1),
(744, 2, 1, 10565, 'booking', 'edit', 'booking 10565 edit', 1580818533, 1),
(745, 2, 1, 10565, 'booking', 'edit', 'Booking  10565 checked out', 1580818764, 1),
(746, 2, 1, 19, 'hotel', 'edit', 'hotel 19 edit', 1580819495, 1),
(747, 2, 1, 2, 'coupon', 'edit', 'coupon 2 edit', 1580904016, 1),
(748, 2, 1, 10567, 'booking', 'edit', 'Booking  10567 checked in', 1580983196, 1),
(749, 2, 1, 10567, 'booking', 'edit', 'booking 10567 edit', 1580983334, 1),
(750, 2, 1, 10567, 'booking', 'edit', 'Booking  10567 checked out', 1580983344, 1),
(751, 2, 1, 10572, 'booking', 'edit', 'Booking  10572 checked in', 1581499285, 1),
(752, 2, 1, 10575, 'booking', 'edit', 'Booking  10575 checked in', 1582101185, 1),
(753, 2, 1, 10575, 'booking', 'edit', 'booking 10575 edit', 1582101249, 1),
(754, 2, 1, 10575, 'booking', 'edit', 'Booking  10575 checked out', 1582101381, 1),
(755, 2, 231, 10576, 'booking', 'create', 'Booking Id 10576 created on 24-02-2020 - online mode', 1582525744, 1),
(756, 2, 1, 10576, 'booking', 'edit', 'Booking  10576 Cancelled', 1582525960, 1),
(757, 2, 1, 229, 'user', 'edit', 'New user Sharad Bhaiya is updated', 1582626805, 1),
(758, 2, 1, 229, 'user', 'edit', 'New user Sharad Bhaiya is updated', 1582626816, 1),
(759, 2, 1, 229, 'user', 'edit', 'New user Sharad Bhaiya is updated', 1582626828, 1),
(760, 2, 1, 10577, 'booking', 'add', 'New Booking  #id =10577 is Created', 1582626840, 1),
(761, 2, 1, 10577, 'booking', 'edit', 'booking 10577 edit', 1582627027, 1),
(762, 2, 1, 10577, 'booking', 'edit', 'Booking  10577 checked in', 1582627066, 1),
(763, 2, 1, 10578, 'booking', 'add', 'New Booking  #id =10578 is Created', 1582627234, 1),
(764, 2, 1, 10578, 'booking', 'edit', 'Booking  10578 checked in', 1582627297, 1),
(765, 2, 231, 10579, 'booking', 'create', 'Booking Id 10579 created on 04-03-2020 - online mode', 1583325379, 1),
(766, 2, 1, 10579, 'booking', 'edit', 'Booking  10579 Cancelled', 1583325492, 1),
(767, 2, 231, 10580, 'booking', 'create', 'Booking Id 10580 created on 04-03-2020 - online mode', 1583325816, 1),
(768, 2, 1, 10580, 'booking', 'edit', 'Booking  10580 Cancelled', 1583325841, 1),
(769, 2, 231, 10581, 'booking', 'create', 'Booking Id 10581 created on 04-03-2020 - online mode', 1583326021, 1),
(770, 2, 1, 10581, 'booking', 'edit', 'Booking  10581 Cancelled', 1583326058, 1),
(771, 2, 231, 10582, 'booking', 'create', 'Booking Id 10582 created on 04-03-2020 - online mode', 1583326455, 1),
(772, 2, 1, 10582, 'booking', 'edit', 'Booking  10582 Cancelled', 1583326471, 1),
(773, 2, 233, 10583, 'booking', 'create', 'Booking Id 10583 created on 05-03-2020 - online mode', 1583413333, 1),
(774, 2, 1, 10583, 'booking', 'edit', 'Booking  10583 checked in', 1583474089, 1),
(775, 2, 233, 10584, 'booking', 'create', 'Booking Id 10584 created on 06-03-2020 - online mode', 1583474434, 1),
(776, 2, 233, 10585, 'booking', 'create', 'Booking Id 10585 created on 06-03-2020 - online mode', 1583474563, 1),
(777, 2, 308, 10586, 'booking', 'create', 'Booking Id 10586 created on 11-03-2020 - app mode', 1583930688, 1),
(778, 2, 1, 229, 'user', 'edit', 'New user Sharad Bhaiya is updated', 1584094839, 1),
(779, 2, 1, 229, 'user', 'edit', 'New user Sharad Bhaiya is updated', 1584094841, 1),
(780, 2, 1, 229, 'user', 'edit', 'New user Sharad Bhaiya is updated', 1584094851, 1),
(781, 2, 1, 10587, 'booking', 'add', 'New Booking  #id =10587 is Created', 1584094869, 1),
(782, 2, 1, 10587, 'booking', 'edit', 'booking 10587 edit', 1584094958, 1),
(783, 2, 231, 10588, 'booking', 'create', 'Booking Id 10588 created on 11-06-2020 - online mode', 1591876664, 1),
(784, 2, 1, 53, 'room', 'edit', 'room 53 edit', 1594625892, 1),
(785, 2, 1, 50, 'room', 'edit', 'room 50 edit', 1594625927, 1),
(786, 2, 231, 10589, 'booking', 'create', 'Booking Id 10589 created on 25-07-2020 - app mode', 1595636144, 1),
(787, 2, 1, 0, 'hotel', 'add', 'hotel 0 add', 1595923329, 1),
(788, 2, 1, 33, 'hotel', 'edit', 'hotel 33 edit', 1595924518, 1),
(789, 2, 1, 0, 'room', 'add', 'room 0 add', 1596036013, 1),
(790, 2, 1, 0, 'rate', 'add', 'rate 0 add', 1596036074, 1),
(791, 2, 1, 65, 'rate', 'edit', 'rate 65 edit', 1596036646, 1),
(792, 2, 1, 67, 'room', 'edit', 'room 67 edit', 1596036882, 1),
(793, 2, 1, 65, 'rate', 'edit', 'rate 65 edit', 1596037228, 1),
(794, 2, 1, 65, 'rate', 'edit', 'rate 65 edit', 1596037625, 1),
(795, 2, 1, 65, 'rate', 'edit', 'rate 65 edit', 1596039844, 1),
(796, 2, 1, 65, 'rate', 'edit', 'rate 65 edit', 1596041018, 1),
(797, 2, 1, 42, 'rate', 'edit', 'rate 42 edit', 1596090309, 1),
(798, 2, 1, 42, 'rate', 'edit', 'rate 42 edit', 1596090456, 1),
(799, 2, 1, 42, 'rate', 'edit', 'rate 42 edit', 1596090605, 1),
(800, 2, 1, 2, 'coupon', 'edit', 'coupon 2 edit', 1596097007, 1),
(801, 2, 1, 2, 'coupon', 'edit', 'coupon 2 edit', 1596097039, 1),
(802, 2, 1, 2, 'coupon', 'edit', 'coupon 2 edit', 1596097057, 1),
(803, 2, 1, 2, 'coupon', 'edit', 'coupon 2 edit', 1596097077, 1),
(804, 2, 352, 10590, 'booking', 'create', 'Booking Id 10590 created on 30-07-2020 - online mode', 1596122984, 1),
(805, 2, 1, 10590, 'booking', 'edit', 'Booking  10590 checked in', 1596180214, 1),
(806, 2, 352, 10591, 'booking', 'create', 'Booking Id 10591 created on 31-07-2020 - online mode', 1596185278, 1),
(807, 2, 1, 10591, 'booking', 'edit', 'Booking  10591 checked in', 1596185880, 1),
(808, 2, 1, 10591, 'booking', 'edit', 'booking 10591 edit', 1596187790, 1),
(809, 2, 1, 10589, 'booking', 'edit', 'booking 10589 edit', 1596188352, 1),
(810, 2, 1, 10591, 'booking', 'edit', 'booking 10591 edit', 1596188989, 1),
(811, 2, 1, 10591, 'booking', 'edit', 'Booking  10591 checked out', 1596189086, 1),
(812, 2, 1, 30, 'hotel', 'edit', 'hotel 30 edit', 1596189773, 1),
(813, 2, 1, 88, 'comment', 'edit', 'comment 88 edit', 1596189932, 1),
(814, 2, 1, 352, 'user', 'edit', 'New user Protiek QA is updated', 1596190409, 1),
(815, 2, 1, 352, 'user', 'edit', 'New user Protiek QA is updated', 1596190455, 1),
(816, 2, 1, 352, 'user', 'edit', 'New user Protiek QA is updated', 1596190461, 1),
(817, 2, 1, 352, 'user', 'edit', 'New user Protiek QA is updated', 1596190490, 1),
(818, 2, 1, 2, 'coupon', 'edit', 'coupon 2 edit', 1596190804, 1),
(819, 2, 1, 10592, 'booking', 'add', 'New Booking  #id =10592 is Created', 1596190994, 1),
(820, 2, 353, 10593, 'booking', 'create', 'Booking Id 10593 created on 03-08-2020 - app mode', 1596440606, 1),
(821, 2, 1, 353, 'user', 'edit', 'user 353 edit', 1596446699, 1),
(822, 2, 1, 65, 'rate', 'edit', 'rate 65 edit', 1597131127, 1),
(823, 2, 1, 65, 'rate', 'edit', 'rate 65 edit', 1597142730, 1),
(824, 2, 1, 35, 'page', 'edit', 'page 35 edit', 1597235586, 1),
(825, 2, 1, 36, 'page', 'edit', 'page 36 edit', 1597235603, 1),
(826, 2, 1, 0, 'menu', 'add', 'menu 0 add', 1597235774, 1),
(827, 2, 1, 15, 'menu', 'edit', 'menu 15 edit', 1597235790, 1),
(828, 2, 1, 15, 'menu', 'edit', 'menu 15 edit', 1597235818, 1);

-- --------------------------------------------------------

--
-- Table structure for table `pm_activity_session`
--

CREATE TABLE `pm_activity_session` (
  `id` int(11) NOT NULL,
  `id_activity` int(11) NOT NULL,
  `days` varchar(20) DEFAULT NULL,
  `start_date` int(11) DEFAULT NULL,
  `end_date` int(11) DEFAULT NULL,
  `users` text,
  `price` double DEFAULT '0',
  `price_child` double DEFAULT '0',
  `discount` double DEFAULT '0',
  `discount_type` varchar(10) DEFAULT NULL,
  `id_tax` int(11) DEFAULT NULL,
  `taxes` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `pm_activity_session_hour`
--

CREATE TABLE `pm_activity_session_hour` (
  `id` int(11) NOT NULL,
  `id_activity_session` int(11) NOT NULL,
  `start_h` int(11) DEFAULT NULL,
  `start_m` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `pm_article`
--

CREATE TABLE `pm_article` (
  `id` int(11) NOT NULL,
  `lang` int(11) NOT NULL,
  `title` varchar(250) DEFAULT NULL,
  `subtitle` varchar(250) DEFAULT NULL,
  `alias` varchar(100) DEFAULT NULL,
  `text` longtext,
  `url` varchar(250) DEFAULT NULL,
  `tags` varchar(250) DEFAULT NULL,
  `id_page` int(11) DEFAULT NULL,
  `users` text,
  `home` int(11) DEFAULT '0',
  `checked` int(11) DEFAULT '0',
  `rank` int(11) DEFAULT '0',
  `add_date` int(11) DEFAULT NULL,
  `edit_date` int(11) DEFAULT NULL,
  `publish_date` int(11) DEFAULT NULL,
  `unpublish_date` int(11) DEFAULT NULL,
  `comment` int(11) DEFAULT '0',
  `rating` int(11) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `pm_article_file`
--

CREATE TABLE `pm_article_file` (
  `id` int(11) NOT NULL,
  `lang` int(11) NOT NULL,
  `id_item` int(11) NOT NULL,
  `home` int(11) DEFAULT '0',
  `checked` int(11) DEFAULT '1',
  `rank` int(11) DEFAULT '0',
  `file` varchar(250) DEFAULT NULL,
  `label` varchar(250) DEFAULT NULL,
  `type` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `pm_booking`
--

CREATE TABLE `pm_booking` (
  `id` int(11) NOT NULL,
  `id_destination` int(11) DEFAULT '0',
  `id_hotel` int(11) DEFAULT NULL,
  `add_date` int(11) DEFAULT NULL,
  `edit_date` int(11) DEFAULT NULL,
  `from_date` int(11) DEFAULT NULL,
  `to_date` int(11) DEFAULT NULL,
  `nights` int(11) DEFAULT '1',
  `adults` int(11) DEFAULT '1',
  `children` int(11) DEFAULT '0',
  `amount` float DEFAULT NULL,
  `tourist_tax` float DEFAULT NULL,
  `discount` float DEFAULT NULL,
  `ex_tax` float DEFAULT NULL,
  `tax_amount` float DEFAULT NULL,
  `total` float DEFAULT NULL,
  `down_payment` float DEFAULT NULL,
  `paid` float DEFAULT NULL,
  `balance` float DEFAULT NULL,
  `extra_services` text,
  `id_user` int(11) DEFAULT NULL,
  `firstname` varchar(50) DEFAULT NULL,
  `lastname` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `company` varchar(50) DEFAULT NULL,
  `gstin` varchar(50) DEFAULT NULL,
  `govid_type` varchar(50) DEFAULT NULL,
  `govid` varchar(50) DEFAULT NULL,
  `address` varchar(250) DEFAULT NULL,
  `postcode` varchar(10) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `mobile` varchar(50) DEFAULT NULL,
  `country` varchar(100) NOT NULL,
  `comments` text,
  `status` int(11) DEFAULT '1',
  `coupon_used` varchar(255) DEFAULT NULL,
  `trans` varchar(50) DEFAULT NULL,
  `payment_date` int(11) DEFAULT NULL,
  `payment_option` varchar(250) DEFAULT NULL,
  `payment_mode` varchar(250) DEFAULT NULL,
  `users` text,
  `source` enum('website','admin','app') DEFAULT 'website',
  `checked_in` enum('no','in') DEFAULT 'no',
  `checked_out` enum('no','out') DEFAULT 'no'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pm_booking`
--

INSERT INTO `pm_booking` (`id`, `id_destination`, `id_hotel`, `add_date`, `edit_date`, `from_date`, `to_date`, `nights`, `adults`, `children`, `amount`, `tourist_tax`, `discount`, `ex_tax`, `tax_amount`, `total`, `down_payment`, `paid`, `balance`, `extra_services`, `id_user`, `firstname`, `lastname`, `email`, `company`, `gstin`, `govid_type`, `govid`, `address`, `postcode`, `city`, `phone`, `mobile`, `country`, `comments`, `status`, `coupon_used`, `trans`, `payment_date`, `payment_option`, `payment_mode`, `users`, `source`, `checked_in`, `checked_out`) VALUES
(10059, 1, 19, 1570776483, 1573632202, 1574250805, 1570838400, 1, 1, 0, 700, NULL, 0, 700, 0, 700, NULL, 0, NULL, NULL, NULL, 'Sharad', 'BHaiya', 'sharad210@gmail.com', '', '', '', '', 'Arvind Tower, 242/1B, A.P.C.Road, 3rd Floor Flat - 14', '700004', 'Kolkata', '', '7827633006', 'India', '', 1, NULL, NULL, NULL, 'arrival', 'cash_pay', '1', 'website', NULL, NULL),
(10060, 1, 19, 1570799270, 1573633077, 1570752000, 1570838400, 1, 1, 0, 700, NULL, 0, 700, 0, 700, NULL, 0, NULL, NULL, NULL, 'Traveler', 'Traveler', 'qa@yopmail.com', '', '', '', '', 'test', '', 'sad', '', '', 'India', '', 1, NULL, NULL, NULL, 'arrival', 'cash_pay', '1', 'website', NULL, NULL),
(10061, 1, 19, 1571732511, 1573633699, 1571702400, 1571788800, 1, 1, 0, 700, NULL, 0, 700, 0, 700, NULL, 0, NULL, NULL, 233, 'Traveler', 'Traveler', 'qa2210@yopmail.com', '', '', '', '', 'test', '9475555', '', '', '', 'India', '', 1, NULL, NULL, NULL, 'arrival', 'cash_pay', '1', 'website', NULL, NULL),
(10062, 1, 19, 1572613037, 1573637372, 1572566400, 1572652800, 1, 1, 0, 700, NULL, 70, 700, 0, 630, NULL, 0, NULL, NULL, 232, 'Traveler', 'Traveler', 'sharad210@gmail.com', '', '', '', '', 'test', '', 'kolkata', '', '', 'India', '', 1, NULL, NULL, NULL, 'arrival', 'cash_pay', '1', 'website', NULL, NULL),
(10063, 2, 23, 1572620990, 1573639667, 1572566400, 1572652800, 1, 2, 0, 1650, NULL, 165, 1650, 267.3, 1752.3, NULL, 0, NULL, NULL, 232, 'Traveler', 'Traveler', 'sharad210@gmail.com', '', '', '', '', 'test', '', 'kolkata', '', '', 'India', '', 4, NULL, NULL, NULL, 'arrival', 'cash_pay', '1', 'website', '', 'out'),
(10064, 1, 19, 1573716778, NULL, 1573862400, 1573948800, 1, 1, 0, 700, NULL, 0, 700, 0, 700, NULL, NULL, NULL, NULL, 231, 'Traveler', 'Traveler', '', '', '', '', '', '', '', '', '', '9475312345', 'India', '', 1, NULL, NULL, NULL, 'arrival', 'cash_pay', '1', 'website', NULL, NULL),
(10065, 1, 20, 1573719013, NULL, 1573862400, 1573948800, 1, 1, 0, 704, NULL, 0, 704, 0, 704, NULL, NULL, NULL, NULL, 231, 'Traveler', 'Traveler', '', '', '', '', '', '', '', '', '', '9475312345', 'India', '', 1, NULL, NULL, NULL, 'arrival', 'cash_pay', '1', 'website', NULL, NULL),
(10066, 1, 22, 1573743895, 1573807010, 1574380800, 1574553600, 2, 2, 0, 3300, NULL, 0, 3300, 594, 3894, NULL, 0, NULL, NULL, 231, 'Traveler', 'Traveler', 'test123@fiteser.com', '', '', '', '', 'test', '', '', '', '9475312345', 'India', '', 2, NULL, NULL, NULL, 'arrival', 'cash_pay', '1', 'website', '', 'out'),
(10067, 1, 19, 1573812485, NULL, 1573776000, 1573862400, 1, 1, 0, 700, NULL, 0, 700, 0, 700, NULL, NULL, NULL, NULL, 231, 'Traveler', 'Traveler', 'sonjoy.bhadra@met-technologies.com', '', '', '', '', '', '', '', '', '9475312345', 'India', '', 1, NULL, NULL, NULL, 'arrival', 'cash_pay', '1', 'website', NULL, NULL),
(10068, 1, 22, 1574250994, NULL, 1574793000, 1575052200, 3, 1, 0, 5841, NULL, 0, 4950, 891, 5841, NULL, NULL, NULL, NULL, 231, 'Traveler', 'Traveler', 'sonjoy.bhadra@met-technologies.com', '', '', '', '', '', '', '', '', '9475312345', 'India', '', 2, NULL, NULL, NULL, 'arrival', 'cash_pay', '1', 'website', NULL, NULL),
(10071, 1, 19, 1574348091, NULL, 1574294400, 1574380800, 1, 1, 0, 700, NULL, 0, 700, 0, 700, NULL, NULL, NULL, NULL, 233, 'Traveler', 'Traveler', 'qa2210@yopmail.com', 'test', '', '123456', '123456', '123 testdrive', '700056', 'kolkata', '', '9830256890', 'India', '', 1, NULL, NULL, NULL, 'arrival', 'cash_pay', '1', 'website', NULL, NULL),
(10072, 1, 19, 1574348827, NULL, 1574726400, 1574985600, 3, 1, 0, 2100, NULL, 0, 2100, 378, 2478, NULL, NULL, NULL, NULL, 233, 'Traveler', 'Traveler', 'qa2210@yopmail.com', 'test', '', '123456', '123456', '123 testdrive', '700056', 'kolkata', '', '9830256890', 'India', '', 1, NULL, NULL, NULL, 'arrival', 'cash_pay', '1', 'website', NULL, NULL),
(10073, 1, 19, 1574665896, NULL, 1574640000, 1574812800, 2, 1, 0, 1400, NULL, 0, 1400, 252, 1652, NULL, NULL, NULL, NULL, 233, 'Traveler', 'Traveler', 'qa2210@yopmail.com', 'test', '', '123456', '123456', '123 testdrive', '700056', 'kolkata', '', '9830256890', 'India', '', 1, NULL, NULL, NULL, 'arrival', 'cash_pay', '1', 'website', NULL, NULL),
(10074, 2, 25, 1574685318, NULL, 1574640000, 1574726400, 1, 1, 0, 935, NULL, 0, 935, 0, 935, NULL, NULL, NULL, NULL, 233, 'Traveler', 'Traveler', 'qa2210@yopmail.com', 'test', '', '123456', '123456', '123 testdrive', '700056', 'kolkata', '', '9830256890', 'India', '', 1, NULL, NULL, NULL, 'arrival', 'cash_pay', '1', 'website', NULL, NULL),
(10075, 1, 19, 1574688981, 1574857113, 1574640000, 1574726400, 1, 1, 0, 700, NULL, 0, 700, 0, 700, NULL, 700, NULL, NULL, 231, 'Traveler', 'Traveler', 'sonjoy.bhadra@met-technologies.com', '', '', '4444666', '44555', 'xxx', '', '', '', '9475312345', 'India', '', 4, NULL, 'dasdsad', 1574688981, 'arrival', 'cash_pay', '1,267', 'admin', NULL, NULL),
(10076, 2, 26, 1574928654, NULL, 1574899200, 1574985600, 1, 1, 0, 2585, NULL, 0, 2585, 465.3, 3050.3, NULL, NULL, NULL, NULL, 231, 'Traveler', 'Traveler', 'sonjoy.bhadra@met-technologies.com', '', '', '4444666', '44555', '', '', '', '', '9475312345', 'India', '', 1, NULL, NULL, NULL, 'arrival', 'cash_pay', '1', 'website', NULL, NULL),
(10077, 2, 26, 1574928787, NULL, 1575072000, 1575244800, 2, 1, 0, 5170, NULL, 0, 5170, 930.6, 6100.6, NULL, NULL, NULL, NULL, 231, 'Traveler', 'Traveler', 'sonjoy.bhadra@met-technologies.com', '', '', '4444666', '44555', '', '', '', '', '9475312345', 'India', '', 1, NULL, NULL, NULL, 'arrival', 'cash_pay', '1', 'website', NULL, NULL),
(10078, 1, 19, 1574933093, NULL, 1575072000, 1575244800, 2, 1, 0, 6000, NULL, 0, 6000, 1080, 7080, NULL, NULL, NULL, NULL, 231, 'Traveler', 'Traveler', 'sonjoy.bhadra@met-technologies.com', '', '', '4444666', '44555', '', '', '', '', '9475312345', 'India', '', 1, NULL, NULL, NULL, 'arrival', 'cash_pay', '1,267', 'website', NULL, NULL),
(10079, 1, 21, 1574947505, NULL, 1574899200, 1574985600, 1, 1, 0, 891, NULL, 0, 891, 0, 891, NULL, NULL, NULL, NULL, 273, 'Traveler', 'Traveler', 'qa2@yopmail.com', '', '', '', '', '', '', '', '', '', 'India', '', 1, NULL, NULL, NULL, 'arrival', 'cash_pay', '1', 'website', NULL, NULL),
(10083, 2, 26, 1575378922, 1575380491, 1575331200, 1575417600, 1, 1, 0, 880, NULL, 0, 880, 0, 880, NULL, 0, NULL, NULL, 272, 'QA', 'pal', 'qap@yopmail.com', '', '', '752', '953', 'dum dum', '700156', 'kolkata', '', '2', 'Select Country', '', 4, NULL, NULL, NULL, 'arrival', 'cash_pay', '1', 'admin', '', 'out'),
(10271, 1, 19, 1576653813, NULL, 1576693800, 1576780200, 1, 1, NULL, 700, NULL, 0, 700, 0, 700, NULL, NULL, NULL, NULL, 266, 'sagar', 'nayak', 'sagar@gmail.com', NULL, NULL, NULL, NULL, 'kolkata', '123456', 'kolkata', NULL, '8093329914', 'india', NULL, 1, NULL, NULL, 1576653813, 'arrival', 'cash', '1,267', 'app', NULL, NULL),
(10272, 1, 19, 1576655102, NULL, 1576693800, 1576780200, 1, 7, 3, 4000, NULL, NULL, 4000, 720, 4720, NULL, NULL, NULL, NULL, 237, 'Zafar', 'Ansari', 'zafar.ansari@met-technologies.com', '42343', NULL, '42343', '23432', 'Kolkata', '700156', 'Kolkata', NULL, '9932783472', 'India', NULL, 1, NULL, NULL, 1576655102, 'arrival', 'cash', '1,267', 'app', NULL, NULL),
(10277, 1, 19, 1576668252, NULL, 1576693800, 1576780200, 1, 1, NULL, 700, NULL, 0, 700, 0, 700, NULL, NULL, NULL, NULL, 266, 'sagar', 'nayak', 'sagar@gmail.com', NULL, NULL, NULL, NULL, 'kolkata', '123456', 'kolkata', NULL, '8093329914', 'india', NULL, 1, NULL, NULL, 1576668252, 'arrival', 'cash', '1,267', 'app', NULL, NULL),
(10278, 1, 19, 1576668680, NULL, 1576693800, 1576780200, 1, 1, NULL, 700, NULL, 70, 630, 0, 630, NULL, NULL, NULL, NULL, 266, 'sagar', 'nayak', 'sagar@gmail.com', NULL, NULL, NULL, NULL, 'kolkata', '123456', 'kolkata', NULL, '8093329914', 'india', NULL, 4, NULL, NULL, 1576668680, 'arrival', 'cash', '1,267', 'app', '', 'out'),
(10279, 1, 19, 1576669519, NULL, 1576693800, 1576780200, 1, 1, NULL, 700, NULL, 70, 630, 0, 630, NULL, NULL, NULL, NULL, 266, 'sagar', 'nayak', 'sagar@gmail.com', NULL, NULL, NULL, NULL, 'kolkata', '123456', 'kolkata', NULL, '8093329914', 'india', NULL, 1, NULL, NULL, 1576669519, 'arrival', 'cash', '1,267', 'app', NULL, NULL),
(10280, 1, 19, 1576671402, NULL, 1576693800, 1576780200, 1, 1, NULL, 1600, NULL, 0, 1600, 288, 1888, NULL, NULL, NULL, NULL, 266, 'sagar', 'nayak', 'sagar@gmail.com', NULL, NULL, NULL, NULL, 'kolkata', '123456', 'kolkata', NULL, '8093329914', 'india', NULL, 2, NULL, NULL, 1576671402, 'arrival', 'cash', '1,267', 'app', NULL, NULL),
(10281, 1, 19, 1576672087, 1576822578, 1576627200, 1576713600, 1, 1, 0, 700, NULL, 0, 700, 0, 700, NULL, 700, NULL, NULL, 266, 'sagar', 'nayak', 'sagar@gmail.com', '', '', '', '', 'kolkata', '123456', 'kolkata', '', '8093329914', 'india', '', 4, NULL, NULL, 1576672087, 'arrival', 'cash', '1,267', 'app', '', 'out'),
(10282, 1, 19, 1576673007, NULL, 1576693800, 1576780200, 1, 1, NULL, 700, NULL, 70, 630, 0, 630, NULL, NULL, NULL, NULL, 255, 'Anup', 'Bora', 'anup@gmail.com', NULL, NULL, NULL, NULL, 'kolkata', '712707', 'kolkata', NULL, '9007218463', 'United Arab Emirates', NULL, 1, NULL, NULL, 1576673007, 'arrival', 'cash', '1,267', 'app', NULL, NULL),
(10283, 1, 19, 1576677775, NULL, 1576693800, 1576780200, 1, 2, 2, 2300, NULL, 230, 2070, 372.6, 2442.6, NULL, NULL, NULL, NULL, 255, 'Anup', 'Bora', 'anup@gmail.com', NULL, NULL, NULL, NULL, 'kolkata', '712707', 'kolkata', NULL, '9007218463', 'United Arab Emirates', NULL, 1, NULL, NULL, 1576677775, 'arrival', 'cash', '1,267', 'app', NULL, NULL),
(10284, 1, 19, 1576734612, NULL, 1576780200, 1576866600, 1, 1, 2, 700, NULL, 0, 700, 0, 700, NULL, NULL, NULL, NULL, 266, 'sagar', 'nayak', 'sagar@gmail.com', NULL, NULL, NULL, NULL, 'kolkata', '123456', 'kolkata', NULL, '8093329914', 'india', NULL, 4, NULL, NULL, 1576734612, 'arrival', 'cash', '1,267', 'app', '', 'out'),
(10285, 1, 19, 1576735299, NULL, 1576780200, 1576866600, 1, 3, 2, 2300, NULL, 0, 2300, 414, 2714, NULL, NULL, NULL, NULL, 255, 'Anup', 'Bora', 'anup@gmail.com', NULL, NULL, NULL, NULL, 'kolkata', '712707', 'kolkata', NULL, '9007218463', 'United Arab Emirates', NULL, 1, NULL, NULL, 1576735299, 'arrival', 'cash', '1,267', 'app', NULL, NULL),
(10286, 1, 20, 1576737480, NULL, 1576780200, 1576866600, 1, 1, NULL, 704, NULL, 0, 704, 0, 704, NULL, NULL, NULL, NULL, 255, 'Anup', 'Bora', 'anup@gmail.com', NULL, NULL, NULL, NULL, 'kolkata', '712707', 'kolkata', NULL, '9007218463', 'United Arab Emirates', NULL, 1, NULL, NULL, 1576737480, 'arrival', 'cash', '1', 'app', NULL, NULL),
(10287, 1, 19, 1576741664, NULL, 1576780200, 1576866600, 1, 1, NULL, 700, NULL, 70, 630, 0, 630, NULL, NULL, NULL, NULL, 309, 'Traveler', 'Traveler', 'test456@gmail.com', NULL, NULL, NULL, NULL, ' ', ' ', ' ', NULL, '9007218448', ' ', NULL, 1, NULL, NULL, 1576741664, 'arrival', 'cash', '1,267', 'app', NULL, NULL),
(10288, 1, 19, 1576742440, NULL, 1576780200, 1576866600, 1, 1, NULL, 700, NULL, 70, 630, 0, 630, NULL, NULL, NULL, NULL, 255, 'Anup', 'Bora', 'anup@gmail.com', NULL, NULL, NULL, NULL, 'kolkata', '712707', 'kolkata', NULL, '9007218463', 'United Arab Emirates', NULL, 2, NULL, NULL, 1576742440, 'arrival', 'cash', '1,267', 'app', NULL, NULL),
(10289, 1, 19, 1576743637, NULL, 1576780200, 1576866600, 1, 1, NULL, 1600, NULL, 0, 1600, 288, 1888, NULL, NULL, NULL, NULL, 266, 'sagar', 'nayak', 'sagar@gmail.com', NULL, NULL, NULL, NULL, 'kolkata', '123456', 'kolkata', NULL, '8093329914', 'india', NULL, 1, NULL, NULL, 1576743637, 'arrival', 'cash', '1,267', 'app', NULL, NULL),
(10290, 1, 19, 1576747049, NULL, 1576713600, 1576800000, 1, 1, NULL, 700, NULL, 0, 700, 0, 700, NULL, NULL, NULL, NULL, 231, 'Traveler', 'Traveler', 'sonjoy.bhadra@met-technologies.com', 'met', NULL, '4444666', '44555', '', '', '', '', '9475312345', 'India', NULL, 1, NULL, NULL, NULL, 'arrival', 'cash_pay', '1,267', 'admin', NULL, NULL),
(10291, 1, 19, 1576757780, NULL, 1576713600, 1576800000, 1, 1, NULL, 700, NULL, 0, 700, 0, 700, NULL, NULL, NULL, NULL, 314, 'Arijit', 'Som', 'arijit.som@met-technologies.com', 'met', NULL, 'adhar', '75857586653636', 'new', '700012', 'kolkata', NULL, '9163915585', 'India', NULL, 1, NULL, NULL, NULL, 'arrival', 'cash_pay', '1,267', 'admin', NULL, NULL),
(10292, 1, 20, 1576762903, NULL, 1576780200, 1576866600, 1, 1, NULL, 704, NULL, 0, 704, 0, 704, NULL, NULL, NULL, NULL, 255, 'Anup', 'Bora', 'anup@gmail.com', NULL, NULL, NULL, NULL, 'kolkata', '712707', 'kolkata', NULL, '9007218463', 'United Arab Emirates', NULL, 1, NULL, NULL, 1576762903, 'arrival', 'cash', '1', 'app', NULL, NULL),
(10293, 1, 19, 1576763196, NULL, 1576713600, 1576800000, 1, 1, 1, 735, NULL, 0, 735, 0, 735, NULL, NULL, NULL, NULL, 321, 'Kanak', 'Sarkar', 'kanak.sarkar@met-technologies.com', 'MET', NULL, 'Adhar', '789654', 'Axis Mall`', '985632', 'Kolkata', NULL, '8333362653', 'India', NULL, 1, NULL, NULL, NULL, 'arrival', 'cash_pay', '1,267', 'admin', NULL, NULL),
(10294, 1, 19, 1576764926, NULL, 1576780200, 1576866600, 1, 1, NULL, 700, NULL, 0, 700, 0, 700, NULL, NULL, NULL, NULL, 231, 'Traveler', 'Traveler', 'sonjoy.bhadra@met-technologies.com', '4444666', NULL, '4444666', '44555', 'kolkata', '700004', 'kolkata', NULL, '9475312345', 'India', NULL, 1, NULL, NULL, 1576764926, 'arrival', 'cash', '1,267', 'app', NULL, NULL),
(10295, 1, 19, 1576765004, NULL, 1576780200, 1576866600, 1, 1, NULL, 700, NULL, 0, 700, 0, 700, NULL, NULL, NULL, NULL, 232, 'Sharad', 'Bhaiya', 'sharad210@gmail.com', '', NULL, '', '', 'Arvind Tower, 242/1B, A.P.C.Road, 3rd Floor Flat - 14', '700004', 'kolkata', NULL, '8276343006', 'india', NULL, 1, NULL, NULL, 1576765004, 'arrival', 'cash', '1,267', 'app', NULL, NULL),
(10296, 1, 19, 1576818857, NULL, 1576866600, 1576953000, 1, 1, NULL, 700, NULL, 0, 700, 0, 700, NULL, NULL, NULL, NULL, 231, 'Traveler', 'Traveler', 'sonjoy.bhadra@met-technologies.com', '4444666', NULL, '4444666', '44555', 'kolkata', '700004', 'kolkata', NULL, '9475312345', 'India', NULL, 1, NULL, NULL, 1576818857, 'arrival', 'cash', '1,267', 'app', NULL, NULL),
(10299, 1, 19, 1576822916, NULL, 1576866600, 1576953000, 1, 1, NULL, 700, NULL, 0, 700, 0, 700, NULL, NULL, NULL, NULL, 255, 'Anup', 'Bora', 'anup@gmail.com', NULL, NULL, NULL, NULL, 'kolkata', '712707', 'kolkata', NULL, '9007218463', 'United Arab Emirates', NULL, 1, NULL, NULL, 1576822916, 'arrival', 'cash', '1,267', 'app', NULL, NULL),
(10300, 1, 19, 1576826068, NULL, 1576866600, 1576953000, 1, 1, NULL, 700, NULL, 0, 700, 0, 700, NULL, NULL, NULL, NULL, 266, 'sagar', 'nayak', 'sagar@gmail.com', NULL, NULL, NULL, NULL, 'kolkata', '123456', 'kolkata', NULL, '8093329914', 'india', NULL, 1, NULL, NULL, 1576826068, 'arrival', 'cash', '1,267', 'app', NULL, NULL),
(10301, 1, 19, 1576826389, NULL, 1576866600, 1576953000, 1, 1, NULL, 700, NULL, 0, 700, 0, 700, NULL, NULL, NULL, NULL, 255, 'Anup', 'Bora', 'anup@gmail.com', NULL, NULL, NULL, NULL, 'kolkata', '712707', 'kolkata', NULL, '9007218463', 'United Arab Emirates', NULL, 1, NULL, NULL, 1576826389, 'arrival', 'cash', '1,267', 'app', NULL, NULL),
(10302, 1, 19, 1576830348, NULL, 1576866600, 1576953000, 1, 1, NULL, 1600, NULL, 0, 1600, 288, 1888, NULL, NULL, NULL, NULL, 266, 'sagar', 'nayak', 'sagar@gmail.com', NULL, NULL, NULL, NULL, 'kolkata', '123456', 'kolkata', NULL, '8093329914', 'india', NULL, 1, NULL, NULL, 1576830348, 'arrival', 'cash', '1,267', 'app', NULL, NULL),
(10303, 1, 19, 1576831361, NULL, 1576866600, 1576953000, 1, 1, NULL, 1600, NULL, 0, 1600, 288, 1888, NULL, NULL, NULL, NULL, 266, 'sagar', 'nayak', 'sagar@gmail.com', NULL, NULL, NULL, NULL, 'kolkata', '123456', 'kolkata', NULL, '8093329914', 'india', NULL, 1, NULL, NULL, 1576831361, 'arrival', 'cash', '1,267', 'app', NULL, NULL),
(10304, 1, 19, 1576831573, NULL, 1577471400, 1577817000, 4, 1, NULL, 6400, NULL, 0, 6400, 1152, 7552, NULL, NULL, NULL, NULL, 266, 'sagar', 'nayak', 'sagar@gmail.com', NULL, NULL, NULL, NULL, 'kolkata', '123456', 'kolkata', NULL, '8093329914', 'india', NULL, 2, NULL, NULL, 1576831573, 'arrival', 'cash', '1,267', 'app', NULL, NULL),
(10305, 1, 19, 1576831607, NULL, 1576866600, 1576953000, 1, 1, NULL, 1600, NULL, 0, 1600, 288, 1888, NULL, NULL, NULL, NULL, 266, 'sagar', 'nayak', 'sagar@gmail.com', NULL, NULL, NULL, NULL, 'kolkata', '123456', 'kolkata', NULL, '8093329914', 'india', NULL, 1, NULL, NULL, 1576831607, 'arrival', 'cash', '1,267', 'app', NULL, NULL),
(10306, 1, 19, 1576831743, NULL, 1576866600, 1576953000, 1, 1, NULL, 700, NULL, 0, 700, 0, 700, NULL, NULL, NULL, NULL, 266, 'sagar', 'nayak', 'sagar@gmail.com', NULL, NULL, NULL, NULL, 'kolkata', '123456', 'kolkata', NULL, '8093329914', 'india', NULL, 1, NULL, NULL, 1576831743, 'arrival', 'cash', '1,267', 'app', NULL, NULL),
(10307, 1, 21, 1576833509, NULL, 1576866600, 1577385000, 6, 4, NULL, 10362, NULL, 0, 10362, 1865.16, 12227.2, NULL, NULL, NULL, NULL, 323, 'Traveler', 'Traveler', 'snkumar.nayak@gmail.com', NULL, NULL, NULL, NULL, ' ', ' ', ' ', NULL, '8093329976', ' ', NULL, 1, NULL, NULL, 1576833509, 'arrival', 'cash', '1', 'app', '', 'out'),
(10308, 1, 19, 1576843137, NULL, 1576866600, 1576953000, 1, 1, NULL, 700, NULL, 0, 700, 0, 700, NULL, NULL, NULL, NULL, 255, 'Anup', 'Bora', 'anup@gmail.com', NULL, NULL, NULL, NULL, 'kolkata', '712707', 'kolkata', NULL, '9007218463', 'United Arab Emirates', NULL, 1, NULL, NULL, 1576843137, 'arrival', 'cash', '1,267', 'app', NULL, NULL),
(10309, 1, 19, 1576843404, NULL, 1576866600, 1576953000, 1, 2, 1, 2300, NULL, 0, 2300, 414, 2714, NULL, NULL, NULL, NULL, 255, 'Anup', 'Bora', 'anup@gmail.com', NULL, NULL, NULL, NULL, 'kolkata', '712707', 'kolkata', NULL, '9007218463', 'United Arab Emirates', NULL, 1, NULL, NULL, 1576843404, 'arrival', 'cash', '1,267', 'app', NULL, NULL),
(10310, 1, 19, 1576859319, NULL, 1576866600, 1576953000, 1, 1, NULL, 700, NULL, 70, 630, 0, 630, NULL, NULL, NULL, NULL, 232, 'Sharad', 'Bhaiya', 'sharad210@gmail.com', '', NULL, '', '', 'Arvind Tower, 242/1B, A.P.C.Road, 3rd Floor Flat - 14', '700004', 'kolkata', NULL, '8276343006', 'india', NULL, 1, NULL, NULL, 1576859319, 'arrival', 'cash', '1,267', 'app', NULL, NULL),
(10311, 3, 27, 1576860721, NULL, 1576866600, 1576953000, 1, 1, NULL, 770, NULL, 0, 770, 0, 770, NULL, NULL, NULL, NULL, 231, 'Traveler', 'Traveler', 'sonjoy.bhadra@met-technologies.com', '4444666', NULL, '4444666', '44555', 'kolkata', '700004', 'kolkata', NULL, '9475312345', 'India', NULL, 1, NULL, NULL, 1576860721, 'arrival', 'cash', '1', 'app', NULL, NULL),
(10312, 1, 21, 1577085531, NULL, 1577125800, 1577212200, 1, 1, NULL, 891, NULL, 0, 891, 0, 891, NULL, NULL, NULL, NULL, 255, 'Anup', 'Bora', 'anup@gmail.com', NULL, NULL, NULL, NULL, 'kolkata', '712707', 'kolkata', NULL, '9007218463', 'United Arab Emirates', NULL, 2, NULL, NULL, 1577085531, 'arrival', 'cash', '1', 'app', NULL, NULL),
(10313, 1, 19, 1577089609, NULL, 1577125800, 1577212200, 1, 1, NULL, 700, NULL, 0, 700, 0, 700, NULL, NULL, NULL, NULL, 255, 'Anup', 'Bora', 'anup@gmail.com', NULL, NULL, NULL, NULL, 'kolkata', '712707', 'kolkata', NULL, '9007218463', 'United Arab Emirates', NULL, 2, NULL, NULL, 1577089609, 'arrival', 'cash', '1,267', 'app', NULL, NULL),
(10314, 1, 19, 1577090519, NULL, 1577125800, 1577212200, 1, 1, NULL, 700, NULL, 0, 700, 0, 700, NULL, NULL, NULL, NULL, 255, 'Anup', 'Bora', 'anup@gmail.com', NULL, NULL, NULL, NULL, 'kolkata', '712707', 'kolkata', NULL, '9007218463', 'United Arab Emirates', NULL, 2, NULL, NULL, 1577090519, 'arrival', 'cash', '1,267', 'app', NULL, NULL),
(10315, 1, 19, 1577093982, NULL, 1577059200, 1577145600, 1, 1, NULL, 700, NULL, 0, 700, 0, 700, NULL, NULL, NULL, NULL, 231, 'Traveler', 'Traveler', 'sonjoy.bhadra@met-technologies.com', 'met', NULL, '4444666', '44555', 'kolkata', '700004', 'kolkata', '', '9475312345', 'India', NULL, 4, NULL, 'Turct8965632', 1577093982, 'arrival', 'net_banking', '1,267', 'admin', '', 'no'),
(10316, 1, 19, 1577095228, NULL, 1577125800, 1577212200, 1, 1, NULL, 1888, NULL, 0, 1600, 288, 1888, NULL, NULL, NULL, NULL, 231, 'Traveler', 'Traveler', 'sonjoy.bhadra@met-technologies.com', 'met', NULL, '4444666', '44555', 'kolkata', '700004', 'kolkata', '', '9475312345', 'India', NULL, 2, NULL, NULL, NULL, 'arrival', 'cash_pay', '1,267', 'admin', NULL, NULL),
(10317, 1, 19, 1577095623, NULL, 1577125800, 1577212200, 1, 1, NULL, 700, NULL, 0, 700, 0, 700, NULL, NULL, NULL, NULL, 255, 'Anup', 'Bora', 'anup@gmail.com', NULL, NULL, NULL, NULL, 'kolkata', '712707', 'kolkata', NULL, '9007218463', 'United Arab Emirates', NULL, 2, NULL, NULL, 1577095623, 'arrival', 'cash', '1,267', 'app', NULL, NULL),
(10318, 1, 20, 1577096227, NULL, 1577125800, 1577212200, 1, 1, NULL, 704, NULL, 0, 704, 0, 704, NULL, NULL, NULL, NULL, 255, 'Anup', 'Bora', 'anup@gmail.com', NULL, NULL, NULL, NULL, 'kolkata', '712707', 'kolkata', NULL, '9007218463', 'United Arab Emirates', NULL, 2, NULL, NULL, 1577096227, 'arrival', 'cash', '1', 'app', NULL, NULL),
(10319, 1, 20, 1577096961, NULL, 1577125800, 1577212200, 1, 1, NULL, 704, NULL, 0, 704, 0, 704, NULL, NULL, NULL, NULL, 255, 'Anup', 'Bora', 'anup@gmail.com', NULL, NULL, NULL, NULL, 'kolkata', '712707', 'kolkata', NULL, '9007218463', 'United Arab Emirates', NULL, 2, NULL, NULL, 1577096961, 'arrival', 'cash', '1', 'app', NULL, NULL),
(10320, 1, 20, 1577097364, NULL, 1577125800, 1577212200, 1, 1, NULL, 704, NULL, 0, 704, 0, 704, NULL, NULL, NULL, NULL, 266, 'sagar', 'nayak', 'sagar@gmail.com', NULL, NULL, NULL, NULL, 'kolkata', '123456', 'kolkata', NULL, '8093329914', 'india', NULL, 2, NULL, NULL, 1577097364, 'arrival', 'cash', '1', 'app', NULL, NULL),
(10321, 1, 19, 1577097675, NULL, 1577125800, 1577212200, 1, 1, NULL, 700, NULL, 0, 700, 0, 700, NULL, NULL, NULL, NULL, 266, 'sagar', 'nayak', 'sagar@gmail.com', NULL, NULL, NULL, NULL, 'kolkata', '123456', 'kolkata', NULL, '8093329914', 'india', NULL, 1, NULL, NULL, 1577097675, 'arrival', 'cash', '1,267', 'app', NULL, NULL),
(10322, 1, 19, 1577100257, NULL, 1577125800, 1577212200, 1, 1, NULL, 1888, NULL, 0, 1600, 288, 1888, NULL, NULL, NULL, NULL, 231, 'Traveler', 'Traveler', 'sonjoy.bhadra@met-technologies.com', 'met', NULL, '4444666', '44555', 'kolkata', '700004', 'kolkata', '', '9475312345', 'India', NULL, 2, NULL, NULL, NULL, 'arrival', 'credit_card', '1,267', 'admin', NULL, NULL),
(10323, 1, 19, 1577116245, NULL, 1577145600, 1577232000, 1, 1, NULL, 700, NULL, 0, 700, 0, 700, NULL, NULL, NULL, NULL, 231, 'Traveler', 'Traveler', 'sonjoy.bhadra@met-technologies.com', 'met', NULL, '4444666', '44555', 'kolkata', '700004', 'kolkata', '', '9475312345', 'India', NULL, 1, NULL, NULL, NULL, 'arrival', 'cash_pay', '1,267', 'admin', NULL, NULL),
(10324, 1, 19, 1577183402, NULL, 1577212200, 1577298600, 1, 3, NULL, 3200, NULL, 0, 3200, 576, 3776, NULL, NULL, NULL, NULL, 232, 'Sharad', 'Bhaiya', 'sharad210@gmail.com', '', NULL, '', '', 'Arvind Tower, 242/1B, A.P.C.Road, 3rd Floor Flat - 14', '700004', 'kolkata', NULL, '8276343006', 'india', NULL, 2, NULL, NULL, 1577183402, 'arrival', 'cash', '1,267', 'app', NULL, NULL),
(10325, 1, 19, 1577188885, NULL, 1577145600, 1577232000, 1, 1, NULL, 700, NULL, 0, 700, 0, 700, NULL, NULL, NULL, NULL, 231, 'Traveler', 'Traveler', 'sonjoy.bhadra@met-technologies.com', 'met', NULL, '4444666', '44555', 'kolkata', '700004', 'kolkata', '', '9475312345', 'India', NULL, 2, NULL, NULL, NULL, 'arrival', 'cash_pay', '1,267', 'admin', NULL, NULL),
(10326, 1, 19, 1577189302, 1577192046, 1577145600, 1577232000, 1, 1, 0, 700, NULL, 10, 700, 0, 690, NULL, 690, NULL, NULL, 304, 'Traveler', 'Traveler', 'qa@yopmail.com', '', '', '', '', ' ', ' ', ' ', '', '1234567898', ' ', '', 4, NULL, NULL, 1577189302, 'arrival', 'cash', '1,267', 'app', NULL, NULL),
(10327, 1, 19, 1577191734, NULL, 1577212200, 1577298600, 1, 1, NULL, 700, NULL, 0, 700, 0, 700, NULL, NULL, NULL, NULL, 255, 'Anup', 'Bora', 'anup@gmail.com', NULL, NULL, NULL, NULL, 'kolkata', '712707', 'kolkata', NULL, '9007218463', 'United Arab Emirates', NULL, 2, NULL, NULL, 1577191734, 'arrival', 'cash', '1,267', 'app', NULL, NULL),
(10328, 1, 19, 1577353230, NULL, 1577385000, 1577471400, 1, 2, 2, 2300, NULL, 0, 2300, 414, 2714, NULL, NULL, NULL, NULL, 255, 'Anup', 'Bora', 'anup@gmail.com', NULL, NULL, NULL, NULL, 'kolkata', '712707', 'kolkata', NULL, '9007218463', 'United Arab Emirates', NULL, 2, NULL, NULL, 1577353230, 'arrival', 'cash', '1,267', 'app', NULL, NULL),
(10329, 1, 20, 1577353694, NULL, 1577385000, 1577471400, 1, 1, NULL, 704, NULL, 0, 704, 0, 704, NULL, NULL, NULL, NULL, 266, 'sagar', 'nayak', 'sagar@gmail.com', NULL, NULL, NULL, NULL, 'kolkata', '123456', 'kolkata', NULL, '8093329914', 'india', NULL, 2, NULL, NULL, 1577353694, 'arrival', 'cash', '1', 'app', NULL, NULL),
(10330, 1, 19, 1577426886, NULL, 1577471400, 1577557800, 1, 1, NULL, 1600, NULL, 0, 1600, 288, 1888, NULL, NULL, NULL, NULL, 266, 'sagar', 'nayak', 'sagar@gmail.com', NULL, NULL, NULL, NULL, 'kolkata', '123456', 'kolkata', NULL, '8093329914', 'india', NULL, 1, NULL, NULL, 1577426886, 'arrival', 'cash', '1,267', 'app', NULL, NULL),
(10331, 1, 21, 1577430769, NULL, 1577471400, 1577557800, 1, 1, NULL, 891, NULL, 0, 891, 0, 891, NULL, NULL, NULL, NULL, 266, 'sagar', 'nayak', 'sagar@gmail.com', NULL, NULL, NULL, NULL, 'kolkata', '123456', 'kolkata', NULL, '8093329914', 'india', NULL, 1, NULL, NULL, 1577430769, 'arrival', 'cash', '1', 'app', 'in', NULL),
(10332, 1, 19, 1577434179, NULL, 1577471400, 1577557800, 1, 1, NULL, 1600, NULL, 0, 1600, 288, 1888, NULL, NULL, NULL, NULL, 266, 'sagar', 'nayak', 'sagar@gmail.com', NULL, NULL, NULL, NULL, 'kolkata', '123456', 'kolkata', NULL, '8093329914', 'india', NULL, 1, NULL, NULL, 1577434179, 'arrival', 'cash', '1,267', 'app', 'in', NULL),
(10333, 1, 20, 1577434223, NULL, 1577644200, 1577730600, 1, 1, NULL, 704, NULL, 0, 704, 0, 704, NULL, NULL, NULL, NULL, 266, 'sagar', 'nayak', 'sagar@gmail.com', NULL, NULL, NULL, NULL, 'kolkata', '123456', 'kolkata', NULL, '8093329914', 'india', NULL, 2, NULL, NULL, 1577434223, 'arrival', 'cash', '1', 'app', NULL, NULL),
(10334, 1, 21, 1577434379, NULL, 1577471400, 1577557800, 1, 1, NULL, 891, NULL, 0, 891, 0, 891, NULL, NULL, NULL, NULL, 266, 'sagar', 'nayak', 'sagar@gmail.com', NULL, NULL, NULL, NULL, 'kolkata', '123456', 'kolkata', NULL, '8093329914', 'india', NULL, 1, NULL, NULL, 1577434379, 'arrival', 'cash', '1', 'app', NULL, NULL),
(10335, 1, 20, 1577434727, NULL, 1577817000, 1577903400, 1, 1, NULL, 704, NULL, 0, 704, 0, 704, NULL, NULL, NULL, NULL, 266, 'sagar', 'nayak', 'sagar@gmail.com', NULL, NULL, NULL, NULL, 'kolkata', '123456', 'kolkata', NULL, '8093329914', 'india', NULL, 2, NULL, NULL, 1577434727, 'arrival', 'cash', '1', 'app', NULL, NULL),
(10336, 1, 19, 1577437971, NULL, 1577817000, 1577903400, 1, 1, NULL, 1600, NULL, 0, 1600, 288, 1888, NULL, NULL, NULL, NULL, 266, 'sagar', 'nayak', 'sagar@gmail.com', NULL, NULL, NULL, NULL, 'kolkata', '123456', 'kolkata', NULL, '8093329914', 'india', NULL, 2, NULL, NULL, 1577437971, 'arrival', 'cash', '1,267', 'app', NULL, NULL),
(10337, 1, 19, 1577439249, NULL, 1577471400, 1577557800, 1, 1, NULL, 700, NULL, 0, 700, 0, 700, NULL, NULL, NULL, NULL, 266, 'sagar', 'nayak', 'sagar@gmail.com', NULL, NULL, NULL, NULL, 'kolkata', '123456', 'kolkata', NULL, '8093329914', 'india', NULL, 2, NULL, NULL, 1577439249, 'arrival', 'cash', '1,267', 'app', NULL, NULL),
(10338, 2, 25, 1577439966, NULL, 1577471400, 1577557800, 1, 1, NULL, 935, NULL, 0, 935, 0, 935, NULL, NULL, NULL, NULL, 266, 'sagar', 'nayak', 'sagar@gmail.com', NULL, NULL, NULL, NULL, 'kolkata', '123456', 'kolkata', NULL, '8093329914', 'india', NULL, 2, NULL, NULL, 1577439966, 'arrival', 'cash', '1', 'app', NULL, NULL),
(10339, 2, 23, 1577443089, NULL, 1577471400, 1577557800, 1, 1, NULL, 990, NULL, 0, 990, 0, 990, NULL, NULL, NULL, NULL, 266, 'sagar', 'nayak', 'sagar@gmail.com', NULL, NULL, NULL, NULL, 'kolkata', '123456', 'kolkata', NULL, '8093329914', 'india', NULL, 1, NULL, NULL, 1577443089, 'arrival', 'cash', '1', 'app', 'in', NULL),
(10340, 1, 20, 1577681838, NULL, 1577730600, 1577817000, 1, 1, NULL, 704, NULL, 0, 704, 0, 704, NULL, NULL, NULL, NULL, 304, 'Traveler', 'Traveler', 'qa@yopmail.com', NULL, NULL, NULL, NULL, 'unitech', '700156', 'Kolkata', NULL, '1234567898', 'India', NULL, 2, NULL, NULL, 1577681838, 'arrival', 'cash', '1', 'app', NULL, NULL),
(10341, 3, 30, 1577682607, 1577682803, 1577664000, 1577750400, 1, 1, 0, 880, NULL, 0, 880, 0, 880, NULL, 880, NULL, NULL, 304, 'Traveler', 'Traveler', 'qa@yopmail.com', '', '', '', '', 'unitech', '700156', 'Kolkata', '', '1234567898', 'India', '', 4, NULL, NULL, 1577682607, 'arrival', 'cash', '1', 'app', 'in', NULL),
(10342, 1, 20, 1577692897, NULL, 1577730600, 1577817000, 1, 1, NULL, 704, NULL, 0, 704, 0, 704, NULL, NULL, NULL, NULL, 255, 'Anup', 'Bora', 'anup@gmail.com', NULL, NULL, NULL, NULL, 'kolkata', '712707', 'kolkata', NULL, '9007218463', 'United Arab Emirates', NULL, 1, NULL, NULL, 1577692897, 'arrival', 'cash', '1', 'app', NULL, NULL),
(10343, 1, 20, 1577693432, NULL, 1577730600, 1577817000, 1, 1, NULL, 704, NULL, 0, 704, 0, 704, NULL, NULL, NULL, NULL, 255, 'Anup', 'Bora', 'anup@gmail.com', NULL, NULL, NULL, NULL, 'kolkata', '712707', 'kolkata', NULL, '9007218463', 'United Arab Emirates', NULL, 1, NULL, NULL, 1577693432, 'arrival', 'cash', '1', 'app', NULL, NULL),
(10344, 1, 22, 1577774695, NULL, 1577817000, 1577903400, 1, 3, NULL, 1650, NULL, 0, 1650, 297, 1947, NULL, NULL, NULL, NULL, 232, 'Sharad', 'Bhaiya', 'sharad210@gmail.com', '', NULL, '', '', 'Arvind Tower, 242/1B, A.P.C.Road, 3rd Floor Flat - 14', '700004', 'kolkata', NULL, '8276343006', 'india', NULL, 2, NULL, NULL, 1577774695, 'arrival', 'cash', '1', 'app', NULL, NULL),
(10345, 1, 22, 1577775523, NULL, 1578076200, 1578249000, 2, 3, NULL, 4180, NULL, 418, 3762, 677.16, 4439.16, NULL, NULL, NULL, NULL, 232, 'Sharad', 'Bhaiya', 'sharad210@gmail.com', '', NULL, '', '', 'Arvind Tower, 242/1B, A.P.C.Road, 3rd Floor Flat - 14', '700004', 'kolkata', NULL, '8276343006', 'india', NULL, 1, NULL, NULL, 1577775523, 'arrival', 'cash', '1', 'app', NULL, NULL),
(10346, 1, 19, 1577782999, NULL, 1577817000, 1577903400, 1, 1, NULL, 1600, NULL, 0, 1600, 288, 1888, NULL, NULL, NULL, NULL, 266, 'sagar', 'nayak', 'sagar@gmail.com', NULL, NULL, NULL, NULL, 'kolkata', '123456', 'kolkata', NULL, '8093329914', 'india', NULL, 1, NULL, NULL, 1577782999, 'arrival', 'cash', '1,267', 'app', NULL, NULL),
(10347, 1, 20, 1577783890, NULL, 1577817000, 1577903400, 1, 1, NULL, 704, NULL, 0, 704, 0, 704, NULL, NULL, NULL, NULL, 266, 'sagar', 'nayak', 'sagar@gmail.com', NULL, NULL, NULL, NULL, 'kolkata', '123456', 'kolkata', NULL, '8093329914', 'india', NULL, 1, NULL, NULL, 1577783890, 'arrival', 'cash', '1', 'app', NULL, NULL),
(10348, 1, 20, 1577784249, NULL, 1577817000, 1577903400, 1, 1, NULL, 704, NULL, 0, 704, 0, 704, NULL, NULL, NULL, NULL, 266, 'sagar', 'nayak', 'sagar@gmail.com', NULL, NULL, NULL, NULL, 'kolkata', '123456', 'kolkata', NULL, '8093329914', 'india', NULL, 1, NULL, NULL, 1577784249, 'arrival', 'cash', '1', 'app', NULL, NULL),
(10349, 1, 19, 1577785628, NULL, 1577817000, 1577903400, 1, 1, NULL, 700, NULL, 0, 700, 0, 700, NULL, NULL, NULL, NULL, 266, 'sagar', 'nayak', 'sagar@gmail.com', NULL, NULL, NULL, NULL, 'kolkata', '123456', 'kolkata', NULL, '8093329914', 'india', NULL, 1, NULL, NULL, 1577785628, 'arrival', 'cash', '1,267', 'app', NULL, NULL),
(10350, 1, 20, 1577787210, NULL, 1577817000, 1577903400, 1, 1, NULL, 704, NULL, 0, 704, 0, 704, NULL, NULL, NULL, NULL, 266, 'Sagar', 'Nayak', 'sagar@gmail.com', NULL, NULL, NULL, NULL, 'kolkata', '123456', 'kolkata', NULL, '8093329914', 'india', NULL, 1, NULL, NULL, 1577787210, 'arrival', 'cash', '1', 'app', NULL, NULL),
(10351, 1, 19, 1577787393, NULL, 1578508200, 1578594600, 1, 1, NULL, 700, NULL, 0, 700, 0, 700, NULL, NULL, NULL, NULL, 266, 'Sagar', 'Nayak', 'sagar@gmail.com', NULL, NULL, NULL, NULL, 'kolkata', '123456', 'kolkata', NULL, '8093329914', 'india', NULL, 1, NULL, NULL, 1577787393, 'arrival', 'cash', '1,267', 'app', NULL, NULL),
(10352, 1, 19, 1577787511, NULL, 1577817000, 1577903400, 1, 2, 3, 1540, NULL, 0, 1540, 277.2, 1817.2, NULL, NULL, NULL, NULL, 255, 'Anup', 'Bora', 'anup@gmail.com', NULL, NULL, NULL, NULL, 'kolkata', '712707', 'kolkata', NULL, '9007218463', 'United Arab Emirates', NULL, 1, NULL, NULL, 1577787511, 'arrival', 'cash', '1,267', 'app', NULL, NULL),
(10353, 1, 20, 1577944089, NULL, 1577989800, 1578076200, 1, 1, NULL, 704, NULL, 0, 704, 0, 704, NULL, NULL, NULL, NULL, 255, 'Anup', 'Bora', 'anup@gmail.com', NULL, NULL, NULL, NULL, 'kolkata', '712707', 'kolkata', NULL, '9007218463', 'United Arab Emirates', NULL, 1, NULL, NULL, 1577944089, 'arrival', 'cash', '1', 'app', NULL, NULL),
(10354, 1, 19, 1577962324, NULL, 1577989800, 1578076200, 1, 4, 2, 1820, NULL, 0, 1820, 327.6, 2147.6, NULL, NULL, NULL, NULL, 266, 'Sagar', 'Nayak', 'sagar@gmail.com', NULL, NULL, NULL, NULL, 'kolkata', '123456', 'kolkata', NULL, '8093329914', 'india', NULL, 1, NULL, NULL, 1577962324, 'arrival', 'cash', '1,267', 'app', NULL, NULL),
(10355, 1, 19, 1577967742, NULL, 1577989800, 1578076200, 1, 1, NULL, 700, NULL, 0, 700, 0, 700, NULL, NULL, NULL, NULL, 255, 'Anup', 'Bora', 'anup@gmail.com', NULL, NULL, NULL, NULL, 'kolkata', '712707', 'kolkata', NULL, '9007218463', 'United Arab Emirates', NULL, 1, NULL, NULL, 1577967742, 'arrival', 'cash', '1,267', 'app', NULL, NULL),
(10356, 1, 19, 1577968244, NULL, 1577989800, 1578076200, 1, 1, NULL, 700, NULL, 0, 700, 0, 700, NULL, NULL, NULL, NULL, 255, 'Anup', 'Bora', 'anup@gmail.com', NULL, NULL, NULL, NULL, 'kolkata', '712707', 'kolkata', NULL, '9007218463', 'United Arab Emirates', NULL, 1, NULL, NULL, 1577968244, 'arrival', 'cash', '1,267', 'app', NULL, NULL),
(10357, 1, 19, 1577970545, NULL, 1577989800, 1578076200, 1, 1, NULL, 700, NULL, 0, 700, 0, 700, NULL, NULL, NULL, NULL, 304, 'Traveler', 'Traveler', 'qa@yopmail.com', NULL, NULL, NULL, NULL, ' ', ' ', ' ', NULL, '1234567898', ' ', NULL, 1, NULL, NULL, 1577970545, 'arrival', 'cash', '1,267', 'app', NULL, NULL),
(10358, 1, 19, 1577973284, NULL, 1579285800, 1579372200, 1, 1, NULL, 1600, NULL, 0, 1600, 288, 1888, NULL, NULL, NULL, NULL, 266, 'Sagar', 'Nayak', 'sagar@gmail.com', NULL, NULL, NULL, NULL, 'kolkata', '123456', 'kolkata', NULL, '8093329914', 'india', NULL, 2, NULL, NULL, 1577973284, 'arrival', 'cash', '1,267', 'app', NULL, NULL),
(10359, 1, 22, 1578031731, NULL, 1578076200, 1578162600, 1, 2, NULL, 1650, NULL, 0, 1650, 297, 1947, NULL, NULL, NULL, NULL, 232, 'Sharad', 'Bhaiya', 'sharad210@gmail.com', '', NULL, '', '', 'Arvind Tower, 242/1B, A.P.C.Road, 3rd Floor Flat - 14', '700004', 'kolkata', NULL, '8276343006', 'india', NULL, 1, NULL, NULL, 1578031731, 'arrival', 'cash', '1', 'app', NULL, NULL),
(10360, 1, 19, 1578039640, NULL, 1578076200, 1578162600, 1, 1, NULL, 1600, NULL, 0, 1600, 288, 1888, NULL, NULL, NULL, NULL, 266, 'Sagar', 'Nayak', 'sagar@gmail.com', NULL, NULL, NULL, NULL, 'kolkata', '123456', 'kolkata', NULL, '8093329914', 'india', NULL, 1, NULL, NULL, 1578039640, 'arrival', 'cash', '1,267', 'app', NULL, NULL),
(10361, 1, 19, 1578039700, NULL, 1578076200, 1578162600, 1, 1, NULL, 700, NULL, 0, 700, 0, 700, NULL, NULL, NULL, NULL, 266, 'Sagar', 'Nayak', 'sagar@gmail.com', NULL, NULL, NULL, NULL, 'kolkata', '123456', 'kolkata', NULL, '8093329914', 'india', NULL, 1, NULL, NULL, 1578039700, 'arrival', 'cash', '1,267', 'app', NULL, NULL),
(10362, 1, 22, 1578040316, NULL, 1578076200, 1578162600, 1, 1, NULL, 1045, NULL, 0, 1045, 125.4, 1170.4, NULL, NULL, NULL, NULL, 255, 'Anup', 'Bora', 'anup@gmail.com', NULL, NULL, NULL, NULL, 'kolkata', '712707', 'kolkata', NULL, '9007218463', 'United Arab Emirates', NULL, 1, NULL, NULL, 1578040316, 'arrival', 'cash', '1', 'app', NULL, NULL),
(10363, 1, 19, 1578043268, NULL, 1578076200, 1578162600, 1, 1, NULL, 700, NULL, 0, 700, 0, 700, NULL, NULL, NULL, NULL, 255, 'Anup', 'Bora', 'anup@gmail.com', NULL, NULL, NULL, NULL, 'kolkata', '712707', 'kolkata', NULL, '9007218463', 'United Arab Emirates', NULL, 1, NULL, NULL, 1578043268, 'arrival', 'cash', '1,267', 'app', NULL, NULL),
(10364, 1, 19, 1578049604, NULL, 1579804200, 1579890600, 1, 3, NULL, 3900, NULL, 0, 3900, 702, 4602, NULL, NULL, NULL, NULL, 330, 'Traveler', 'Traveler', 'pr@gmail.com', NULL, NULL, NULL, NULL, ' ', ' ', ' ', NULL, '9681295249', ' ', NULL, 2, NULL, NULL, 1578049604, 'arrival', 'cash', '1,267', 'app', NULL, NULL),
(10365, 1, 19, 1578055129, NULL, 1578076200, 1578162600, 1, 1, NULL, 1600, NULL, 0, 1600, 288, 1888, NULL, NULL, NULL, NULL, 266, 'Sagar', 'Nayak', 'sagar@gmail.com', NULL, NULL, NULL, NULL, 'kolkata', '123456', 'kolkata', NULL, '8093329914', 'india', NULL, 1, NULL, NULL, 1578055129, 'arrival', 'cash', '1,267', 'app', NULL, NULL),
(10366, 1, 22, 1578055887, NULL, 1578076200, 1578162600, 1, 1, NULL, 1045, NULL, 0, 1045, 125.4, 1170.4, NULL, NULL, NULL, NULL, 266, 'Sagar', 'Nayak', 'sagar@gmail.com', NULL, NULL, NULL, NULL, 'kolkata', '123456', 'kolkata', NULL, '8093329914', 'india', NULL, 1, NULL, NULL, 1578055887, 'arrival', 'cash', '1', 'app', NULL, NULL),
(10367, 1, 20, 1578057402, NULL, 1578076200, 1578162600, 1, 1, NULL, 704, NULL, 0, 704, 0, 704, NULL, NULL, NULL, NULL, 266, 'Sagar', 'Nayak', 'sagar@gmail.com', NULL, NULL, NULL, NULL, 'kolkata', '123456', 'kolkata', NULL, '8093329914', 'india', NULL, 1, NULL, NULL, 1578057402, 'arrival', 'cash', '1', 'app', NULL, NULL),
(10368, 1, 19, 1578057660, NULL, 1578076200, 1578162600, 1, 1, NULL, 1600, NULL, 0, 1600, 288, 1888, NULL, NULL, NULL, NULL, 266, 'Sagar', 'Nayak', 'sagar@gmail.com', NULL, NULL, NULL, NULL, 'kolkata', '123456', 'kolkata', NULL, '8093329914', 'india', NULL, 1, NULL, NULL, 1578057660, 'arrival', 'cash', '1,267', 'app', NULL, NULL),
(10369, 1, 19, 1578058593, NULL, 1578076200, 1578162600, 1, 1, NULL, 700, NULL, 0, 700, 0, 700, NULL, NULL, NULL, NULL, 266, 'Sagar', 'Nayak', 'sagar@gmail.com', NULL, NULL, NULL, NULL, 'kolkata', '123456', 'kolkata', NULL, '8093329914', 'india', NULL, 1, NULL, NULL, 1578058593, 'arrival', 'cash', '1,267', 'app', NULL, NULL),
(10370, 1, 19, 1578058773, NULL, 1578076200, 1578162600, 1, 1, NULL, 1600, NULL, 0, 1600, 288, 1888, NULL, NULL, NULL, NULL, 266, 'Sagar', 'Nayak', 'sagar@gmail.com', NULL, NULL, NULL, NULL, 'kolkata', '123456', 'kolkata', NULL, '8093329914', 'india', NULL, 1, NULL, NULL, 1578058773, 'arrival', 'cash', '1,267', 'app', NULL, NULL),
(10371, 1, 19, 1578058909, NULL, 1578076200, 1578162600, 1, 1, NULL, 1600, NULL, 0, 1600, 288, 1888, NULL, NULL, NULL, NULL, 266, 'Sagar', 'Nayak', 'sagar@gmail.com', NULL, NULL, NULL, NULL, 'kolkata', '123456', 'kolkata', NULL, '8093329914', 'india', NULL, 1, NULL, NULL, 1578058909, 'arrival', 'cash', '1,267', 'app', NULL, NULL),
(10372, 1, 19, 1578058913, NULL, 1578076200, 1578162600, 1, 1, NULL, 1600, NULL, 0, 1600, 288, 1888, NULL, NULL, NULL, NULL, 255, 'Anup', 'Bora', 'anup@gmail.com', NULL, NULL, NULL, NULL, 'kolkata', '712707', 'kolkata', NULL, '9007218463', 'United Arab Emirates', NULL, 1, NULL, NULL, 1578058913, 'arrival', 'cash', '1,267', 'app', NULL, NULL),
(10373, 1, 19, 1578059455, NULL, 1578076200, 1578162600, 1, 1, NULL, 700, NULL, 0, 700, 0, 700, NULL, NULL, NULL, NULL, 255, 'Anup', 'Bora', 'anup@gmail.com', NULL, NULL, NULL, NULL, 'kolkata', '712707', 'kolkata', NULL, '9007218463', 'United Arab Emirates', NULL, 1, NULL, NULL, 1578059455, 'arrival', 'cash', '1,267', 'app', NULL, NULL),
(10374, 1, 19, 1578060662, NULL, 1578076200, 1578162600, 1, 1, NULL, 1600, NULL, 0, 1600, 288, 1888, NULL, NULL, NULL, NULL, 266, 'Sagar', 'Nayak', 'sagar@gmail.com', NULL, NULL, NULL, NULL, 'kolkata', '123456', 'kolkata', NULL, '8093329914', 'india', NULL, 1, NULL, NULL, 1578060662, 'arrival', 'cash', '1,267', 'app', NULL, NULL),
(10375, 1, 22, 1578294262, NULL, 1578335400, 1578508200, 2, 2, NULL, 4070, NULL, 0, 4070, 732.6, 4802.6, NULL, NULL, NULL, NULL, 232, 'Sharad', 'Bhaiya', 'sharad210@gmail.com', '', NULL, '', '', 'Arvind Tower, 242/1B, A.P.C.Road, 3rd Floor Flat - 14', '700004', 'kolkata', NULL, '8276343006', 'india', NULL, 1, NULL, NULL, 1578294262, 'arrival', 'cash', '1', 'app', NULL, NULL),
(10376, 1, 20, 1578295108, NULL, 1578335400, 1578421800, 1, 1, NULL, 704, NULL, 0, 704, 0, 704, NULL, NULL, NULL, NULL, 255, 'Anup', 'Bora', 'anup@gmail.com', NULL, NULL, NULL, NULL, 'kolkata', '712707', 'kolkata', NULL, '9007218463', 'United Arab Emirates', NULL, 1, NULL, NULL, 1578295108, 'arrival', 'cash', '1', 'app', NULL, NULL),
(10377, 1, 19, 1578295548, NULL, 1578335400, 1578421800, 1, 1, NULL, 700, NULL, 0, 700, 0, 700, NULL, NULL, NULL, NULL, 255, 'Anup', 'Bora', 'anup@gmail.com', NULL, NULL, NULL, NULL, 'kolkata', '712707', 'kolkata', NULL, '9007218463', 'United Arab Emirates', NULL, 1, NULL, NULL, 1578295548, 'arrival', 'cash', '1,267', 'app', NULL, NULL),
(10378, 1, 20, 1578295833, NULL, 1578335400, 1578421800, 1, 1, NULL, 704, NULL, 0, 704, 0, 704, NULL, NULL, NULL, NULL, 255, 'Anup', 'Bora', 'anup@gmail.com', NULL, NULL, NULL, NULL, 'kolkata', '712707', 'kolkata', NULL, '9007218463', 'United Arab Emirates', NULL, 1, NULL, NULL, 1578295833, 'arrival', 'cash', '1', 'app', NULL, NULL),
(10379, 1, 19, 1578297305, NULL, 1578335400, 1578421800, 1, 1, NULL, 1600, NULL, 0, 1600, 288, 1888, NULL, NULL, NULL, NULL, 266, 'Sagar', 'Nayak', 'sagar@gmail.com', NULL, NULL, NULL, NULL, 'kolkata', '123456', 'kolkata', NULL, '8093329914', 'india', NULL, 2, NULL, NULL, 1578297305, 'arrival', 'cash', '1,267', 'app', NULL, NULL),
(10380, 1, 19, 1578300899, NULL, 1578335400, 1578421800, 1, 1, NULL, 700, NULL, 0, 700, 0, 700, NULL, NULL, NULL, NULL, 255, 'Anup', 'Bora', 'anup@gmail.com', NULL, NULL, NULL, NULL, 'kolkata', '712707', 'kolkata', NULL, '9007218463', 'United Arab Emirates', NULL, 2, NULL, NULL, 1578300899, 'arrival', 'cash', '1,267', 'app', NULL, NULL),
(10381, 1, 19, 1578303585, NULL, 1578335400, 1578421800, 1, 1, NULL, 700, NULL, 0, 700, 0, 700, NULL, NULL, NULL, NULL, 255, 'Anup', 'Bora', 'anup@gmail.com', NULL, NULL, NULL, NULL, 'kolkata', '712707', 'kolkata', NULL, '9007218463', 'United Arab Emirates', NULL, 2, NULL, NULL, 1578303585, 'arrival', 'cash', '1,267', 'app', NULL, NULL),
(10382, 1, 19, 1578303718, NULL, 1578335400, 1578421800, 1, 1, NULL, 700, NULL, 0, 700, 0, 700, NULL, NULL, NULL, NULL, 255, 'Anup', 'Bora', 'anup@gmail.com', NULL, NULL, NULL, NULL, 'kolkata', '712707', 'kolkata', NULL, '9007218463', 'United Arab Emirates', NULL, 2, NULL, NULL, 1578303718, 'arrival', 'cash', '1,267', 'app', NULL, NULL),
(10383, 1, 19, 1578304256, NULL, 1578335400, 1578421800, 1, 1, NULL, 700, NULL, 0, 700, 0, 700, NULL, NULL, NULL, NULL, 266, 'Sagar', 'Nayak', 'sagar@gmail.com', NULL, NULL, NULL, NULL, 'kolkata', '123456', 'kolkata', NULL, '8093329914', 'india', NULL, 2, NULL, NULL, 1578304256, 'arrival', 'cash', '1,267', 'app', NULL, NULL),
(10384, 1, 19, 1578307090, NULL, 1578335400, 1578421800, 1, 1, NULL, 700, NULL, 0, 700, 0, 700, NULL, NULL, NULL, NULL, 266, 'Sagar', 'Nayak', 'sagar@gmail.com', NULL, NULL, NULL, NULL, 'kolkata', '123456', 'kolkata', NULL, '8093329914', 'india', NULL, 2, NULL, NULL, 1578307090, 'arrival', 'cash', '1,267', 'app', NULL, NULL),
(10385, 1, 19, 1578308719, NULL, 1578335400, 1578421800, 1, 1, NULL, 700, NULL, 0, 700, 0, 700, NULL, NULL, NULL, NULL, 304, 'Traveler', 'Traveler', 'qa@yopmail.com', NULL, NULL, NULL, NULL, 'unitech', '700156', 'Kolkata', NULL, '1234567898', 'India', NULL, 1, NULL, NULL, 1578308719, 'arrival', 'cash', '1,267', 'app', NULL, NULL),
(10386, 1, 19, 1578312787, NULL, 1578335400, 1578421800, 1, 1, NULL, 700, NULL, 0, 700, 0, 700, NULL, NULL, NULL, NULL, 264, 'Traveler', 'Traveler', 'anupkumar.bora@met-technologies.com', NULL, NULL, NULL, NULL, ' ', ' ', ' ', NULL, '8910278956', ' ', NULL, 1, NULL, NULL, 1578312787, 'arrival', 'cash', '1,267', 'app', NULL, NULL),
(10387, 1, 19, 1578315159, NULL, 1578335400, 1578421800, 1, 1, NULL, 700, NULL, 0, 700, 0, 700, NULL, NULL, NULL, NULL, 334, 'Traveler', 'Traveler', 'test8789@gmail.com', NULL, NULL, NULL, NULL, ' ', ' ', ' ', NULL, '9007218470', ' ', NULL, 1, NULL, NULL, 1578315159, 'arrival', 'cash', '1,267', 'app', 'in', 'out'),
(10388, 1, 19, 1578318971, NULL, 1578335400, 1578421800, 1, 1, NULL, 1600, NULL, 0, 1600, 288, 1888, NULL, NULL, NULL, NULL, 266, 'Sagar', 'Nayak', 'sagar@gmail.com', NULL, NULL, NULL, NULL, 'kolkata', '123456', 'kolkata', NULL, '8093329914', 'india', NULL, 2, NULL, NULL, 1578318971, 'arrival', 'cash', '1,267', 'app', NULL, NULL),
(10389, 1, 19, 1578374222, NULL, 1578421800, 1579804200, 16, 1, NULL, 25600, NULL, 0, 25600, 4608, 30208, NULL, NULL, NULL, NULL, 266, 'Sagar', 'Nayak', 'sagar@gmail.com', NULL, NULL, NULL, NULL, 'kolkata', '123456', 'kolkata', NULL, '8093329914', 'india', NULL, 1, NULL, NULL, 1578374222, 'arrival', 'cash', '1,267', 'app', 'in', 'out'),
(10390, 1, 19, 1578381293, NULL, 1578594600, 1578594600, 2, 1, 0, 1400, NULL, 0, 1400, 252, 929.25, NULL, NULL, NULL, NULL, 231, 'Traveler', 'Traveler', 'sonjoy.bhadra@met-technologies.com', 'met', '', '4444666', '44555', 'kolkata', '700004', 'kolkata', '', '9475312345', 'India', '', 4, NULL, NULL, NULL, 'arrival', 'cash_pay', '1,267', 'website', 'in', 'out'),
(10391, 1, 19, 1578381579, NULL, 1578421800, 1578508200, 1, 1, NULL, 700, NULL, 0, 700, 0, 700, NULL, NULL, NULL, NULL, 255, 'Anup', 'Bora', 'anup@gmail.com', NULL, NULL, NULL, NULL, 'kolkata', '712707', 'kolkata', NULL, '9007218463', 'United Arab Emirates', NULL, 2, NULL, NULL, 1578381579, 'arrival', 'cash', '1,267', 'app', NULL, NULL),
(10392, 1, 19, 1578384308, NULL, 1578594600, 1578700800, 2, 1, 0, 3200, NULL, 0, 3200, 576, 2832, NULL, NULL, NULL, NULL, 231, 'Traveler', 'Traveler', 'sonjoy.bhadra@met-technologies.com', 'met', '', '4444666', '44555', 'kolkata', '700004', 'kolkata', '', '9475312345', 'India', '', 1, NULL, NULL, NULL, 'arrival', 'cash_pay', '1,267', 'website', NULL, NULL),
(10393, 1, 19, 1578385559, NULL, 1578681000, 1578700800, 2, 1, 0, 1400, NULL, 0, 1400, 252, 1239, NULL, NULL, NULL, NULL, 231, 'Traveler', 'Traveler', 'sonjoy.bhadra@met-technologies.com', 'met', '', '4444666', '44555', 'kolkata', '700004', 'kolkata', '', '9475312345', 'India', '', 1, NULL, NULL, NULL, 'arrival', 'cash_pay', '1,267', 'website', NULL, NULL),
(10394, 1, 19, 1578388045, NULL, 1578940200, 1579026600, 1, 1, 2, 700, NULL, 70, 630, 0, 630, NULL, NULL, NULL, NULL, 332, 'Traveler', 'Traveler', 'P@gmail.com', NULL, NULL, NULL, NULL, ' ', ' ', ' ', NULL, '7777777777', ' ', NULL, 2, NULL, NULL, 1578388045, 'arrival', 'cash', '1,267', 'app', NULL, NULL),
(10395, 1, 19, 1578389725, NULL, 1578421800, 1578508200, 1, 1, NULL, 700, NULL, 0, 700, 0, 700, NULL, NULL, NULL, NULL, 266, 'Sagar', 'Nayak', 'sagar@gmail.com', NULL, NULL, NULL, NULL, 'kolkata', '123456', 'kolkata', NULL, '8093329914', 'india', NULL, 2, NULL, NULL, 1578389725, 'arrival', 'cash', '1,267', 'app', NULL, NULL),
(10396, 1, 19, 1578389759, NULL, 1579046400, 1579219200, 2, 1, 0, 3200, NULL, 0, 3200, 576, 3776, NULL, NULL, NULL, NULL, 231, 'Traveler', 'Traveler', 'sonjoy.bhadra@met-technologies.com', 'met', '', '4444666', '44555', 'kolkata', '700004', 'kolkata', '', '9475312345', 'India', '', 1, NULL, NULL, NULL, 'arrival', 'cash_pay', '1,267', 'website', 'in', 'out'),
(10397, 1, 19, 1578390066, NULL, 1578508200, 1578614400, 2, 1, 0, 1400, NULL, 0, 1400, 252, 1239, NULL, NULL, NULL, NULL, 231, 'Traveler', 'Traveler', 'sonjoy.bhadra@met-technologies.com', 'met', '', '4444666', '44555', 'kolkata', '700004', 'kolkata', '', '9475312345', 'India', '', 1, NULL, NULL, NULL, 'arrival', 'cash_pay', '1,267', 'website', NULL, NULL),
(10398, 1, 19, 1578392059, NULL, 1578421800, 1578508200, 1, 1, NULL, 700, NULL, 0, 700, 0, 700, NULL, NULL, NULL, NULL, 255, 'Anup', 'Bora', 'anup@gmail.com', NULL, NULL, NULL, NULL, 'kolkata', '712707', 'kolkata', NULL, '9007218463', 'United Arab Emirates', NULL, 2, NULL, NULL, 1578392059, 'arrival', 'cash', '1,267', 'app', NULL, NULL),
(10399, 1, 19, 1578392348, NULL, 1578421800, 1578508200, 1, 1, NULL, 1600, NULL, 0, 1600, 288, 1888, NULL, NULL, NULL, NULL, 266, 'Sagar', 'Nayak', 'sagar@gmail.com', NULL, NULL, NULL, NULL, 'kolkata', '123456', 'kolkata', NULL, '8093329914', 'india', NULL, 2, NULL, NULL, 1578392348, 'arrival', 'cash', '1,267', 'app', NULL, NULL),
(10400, 1, 19, 1578392834, NULL, 1578508200, 1578614400, 2, 1, 0, 1400, NULL, 0, 1400, 252, 1239, NULL, NULL, NULL, NULL, 231, 'Traveler', 'Traveler', 'sonjoy.bhadra@met-technologies.com', 'met', '', '4444666', '44555', 'kolkata', '700004', 'kolkata', '', '9475312345', 'India', '', 1, NULL, NULL, NULL, 'arrival', 'cash_pay', '1,267', 'website', 'in', 'out'),
(10401, 1, 22, 1578394183, NULL, 1578508200, 1578614400, 2, 1, 0, 1650, NULL, 0, 1650, 297, 1073.5, NULL, NULL, NULL, NULL, 231, 'Traveler', 'Traveler', 'sonjoy.bhadra@met-technologies.com', 'met', '', '4444666', '44555', 'kolkata', '700004', 'kolkata', '', '9475312345', 'India', '', 2, NULL, NULL, NULL, 'arrival', 'cash_pay', '1', 'website', NULL, NULL),
(10402, 1, 19, 1578395227, NULL, 1578421800, 1578508200, 1, 1, NULL, 700, NULL, 0, 700, 0, 700, NULL, NULL, NULL, NULL, 266, 'Sagar', 'Nayak', 'sagar@gmail.com', NULL, NULL, NULL, NULL, 'kolkata', '123456', 'kolkata', NULL, '8093329914', 'india', NULL, 2, NULL, NULL, 1578395227, 'arrival', 'cash', '1,267', 'app', NULL, NULL),
(10403, 2, 24, 1578395411, NULL, 1578421800, 1578508200, 1, 1, NULL, 880, NULL, 0, 880, 0, 880, NULL, NULL, NULL, NULL, 255, 'Anup', 'Bora', 'anup@gmail.com', NULL, NULL, NULL, NULL, 'kolkata', '712707', 'kolkata', NULL, '9007218463', 'United Arab Emirates', NULL, 2, NULL, NULL, 1578395411, 'arrival', 'cash', '1', 'app', 'in', NULL);
INSERT INTO `pm_booking` (`id`, `id_destination`, `id_hotel`, `add_date`, `edit_date`, `from_date`, `to_date`, `nights`, `adults`, `children`, `amount`, `tourist_tax`, `discount`, `ex_tax`, `tax_amount`, `total`, `down_payment`, `paid`, `balance`, `extra_services`, `id_user`, `firstname`, `lastname`, `email`, `company`, `gstin`, `govid_type`, `govid`, `address`, `postcode`, `city`, `phone`, `mobile`, `country`, `comments`, `status`, `coupon_used`, `trans`, `payment_date`, `payment_option`, `payment_mode`, `users`, `source`, `checked_in`, `checked_out`) VALUES
(10404, 1, 19, 1578397344, NULL, 1578421800, 1578508200, 1, 1, NULL, 700, NULL, 0, 700, 0, 700, NULL, NULL, NULL, NULL, 266, 'Sagar', 'Nayak', 'sagar@gmail.com', NULL, NULL, NULL, NULL, 'kolkata', '123456', 'kolkata', NULL, '8093329914', 'india', NULL, 2, NULL, NULL, 1578397344, 'arrival', 'cash', '1,267', 'app', 'in', NULL),
(10405, 1, 19, 1578397851, NULL, 1578421800, 1578508200, 1, 1, NULL, 700, NULL, 0, 700, 0, 700, NULL, NULL, NULL, NULL, 266, 'Sagar', 'Nayak', 'sagar@gmail.com', NULL, NULL, NULL, NULL, 'kolkata', '123456', 'kolkata', NULL, '8093329914', 'india', NULL, 2, NULL, NULL, 1578397851, 'arrival', 'cash', '1,267', 'app', NULL, NULL),
(10406, 1, 19, 1578397882, NULL, 1578421800, 1578508200, 1, 1, NULL, 700, NULL, 0, 700, 0, 700, NULL, NULL, NULL, NULL, 255, 'Anup', 'Bora', 'anup@gmail.com', NULL, NULL, NULL, NULL, 'kolkata', '712707', 'kolkata', NULL, '9007218463', 'United Arab Emirates', NULL, 2, NULL, NULL, 1578397882, 'arrival', 'cash', '1,267', 'app', NULL, NULL),
(10407, 1, 20, 1578400178, NULL, 1578421800, 1578508200, 1, 1, NULL, 704, NULL, 0, 704, 0, 704, NULL, NULL, NULL, NULL, 266, 'Sagar', 'Nayak', 'sagar@gmail.com', NULL, NULL, NULL, NULL, 'kolkata', '123456', 'kolkata', NULL, '8093329914', 'india', NULL, 2, NULL, NULL, 1578400178, 'arrival', 'cash', '1', 'app', 'in', NULL),
(10408, 1, 19, 1578556235, NULL, 1578528000, 1578614400, 1, 1, 0, 700, NULL, 0, 700, 0, 700, NULL, NULL, NULL, NULL, 233, 'Traveler', 'Traveler', 'qa2210@yopmail.com', 'test', '', '123456', '123456', '123 testdrive', '700056', 'kolkata', '', '9830256890', 'India', '', 2, NULL, NULL, NULL, 'arrival', 'cash_pay', '1,267', 'website', 'in', 'out'),
(10409, 1, 19, 1578664510, NULL, 1578940200, 1578960000, 1, 1, 0, 700, NULL, 0, 700, 0, 700, NULL, NULL, NULL, NULL, 233, 'Traveler', 'Traveler', 'qa2210@yopmail.com', 'test', '', '123456', '123456', '123 testdrive', '700056', 'kolkata', '', '9830256890', 'India', '', 2, NULL, NULL, NULL, 'arrival', 'cash_pay', '1,267', 'website', NULL, NULL),
(10410, 1, 21, 1578664676, NULL, 1578940200, 1578960000, 1, 1, 0, 891, NULL, 0, 891, 0, 891, NULL, NULL, NULL, NULL, 233, 'Traveler', 'Traveler', 'qa2210@yopmail.com', 'test', '', '123456', '123456', '123 testdrive', '700056', 'kolkata', '', '9830256890', 'India', '', 2, NULL, NULL, NULL, 'arrival', 'cash_pay', '1', 'website', NULL, NULL),
(10411, 1, 22, 1578822010, NULL, 1578853800, 1578940200, 1, 1, NULL, 990, NULL, 99, 891, 0, 891, NULL, NULL, NULL, NULL, 278, 'Traveler', 'Traveler', 'arindamdutta.in@gmail.com', NULL, NULL, NULL, NULL, ' ', ' ', ' ', NULL, '9830576623', ' ', NULL, 2, NULL, NULL, 1578822010, 'arrival', 'cash', '1', 'app', NULL, NULL),
(10412, 1, 19, 1578899507, NULL, 1578960000, 1579046400, 1, 2, NULL, 770, NULL, 0, 770, 0, 770, NULL, NULL, NULL, NULL, 237, 'Zafar', 'Ansari', 'zafar.ansari@met-technologies.com', 'test', NULL, '42343', '23432', 'Newtown', '700156', 'Kolkata', NULL, '9932783472', 'India', NULL, 1, NULL, NULL, NULL, 'arrival', 'cash_pay', '1,267', 'admin', NULL, NULL),
(10413, 1, 19, 1578901087, NULL, 1578940200, 1579026600, 1, 1, NULL, 700, NULL, 0, 700, 0, 700, NULL, NULL, NULL, NULL, 338, 'Dipika', 'Ghosh', 'Pritam@gmail.com', NULL, NULL, NULL, NULL, 'fff', '789456', 'ffff', NULL, '7897897899', 'ff', NULL, 1, NULL, NULL, 1578901087, 'arrival', 'cash', '1,267', 'app', NULL, NULL),
(10414, 1, 19, 1578901128, 1578919814, 1578873600, 1578960000, 1, 1, 0, 1600, NULL, 0, 1600, 288, 1888, NULL, 1888, NULL, NULL, 338, 'Dipika', 'Ghosh', 'Pritam@gmail.com', '', '', '', '', 'fff', '789456', 'ffff', '', '7897897899', 'ff', '', 4, NULL, NULL, 1578901128, 'arrival', 'cash', '1,267', 'app', 'in', 'out'),
(10415, 1, 19, 1578904312, NULL, 1579046400, 1579132800, 1, 2, NULL, 770, NULL, 0, 770, 0, 770, NULL, NULL, NULL, NULL, 237, 'Zafar', 'Ansari', 'zafar.ansari@met-technologies.com', 'test', NULL, '42343', '23432', 'Newtown', '700156', 'Kolkata', NULL, '9932783472', 'India', NULL, 1, NULL, NULL, NULL, 'arrival', 'cash_pay', '1,267', 'admin', NULL, NULL),
(10416, 1, 19, 1578909891, NULL, 1579026600, 1579113000, 1, 1, NULL, 700, NULL, 0, 700, 0, 700, NULL, NULL, NULL, NULL, 241, 'sagar', 'nayak', 'sagarnayak@gmail.com', NULL, NULL, NULL, NULL, 'kolkata', '768330', 'olkatak', NULL, '8093329914', 'india', NULL, 1, NULL, NULL, 1578909891, 'arrival', 'cash', '1,267', 'app', NULL, NULL),
(10417, 1, 19, 1578913178, NULL, 1578960000, 1579046400, 1, 2, NULL, 770, NULL, 0, 770, 0, 770, NULL, NULL, NULL, NULL, 237, 'Zafar', 'Ansari', 'zafar.ansari@met-technologies.com', 'test', NULL, '42343', '23432', 'Newtown', '700156', 'Kolkata', NULL, '9932783472', 'India', NULL, 1, NULL, NULL, NULL, 'arrival', 'cash_pay', '1,267', 'admin', 'in', 'out'),
(10418, 1, 19, 1578917227, NULL, 1578940200, 1579026600, 1, 1, NULL, 700, NULL, 0, 700, 0, 700, NULL, NULL, NULL, NULL, 266, 'Sagar', 'Nayak', 'sagar@gmail.com', NULL, NULL, NULL, NULL, 'kolkata', '123456', 'kolkata', NULL, '8093329914', 'india', NULL, 1, NULL, NULL, 1578917227, 'arrival', 'cash', '1,267', 'app', NULL, NULL),
(10419, 1, 19, 1578990408, NULL, 1578960000, 1579046400, 1, 1, 0, 700, NULL, 0, 700, 0, 700, NULL, NULL, NULL, NULL, 232, 'Sharad', 'Bhaiya', 'sharad210@gmail.com', 'MET SEZ', '', '', '', 'Arvind Tower, 242/1B, A.P.C.Road, 3rd Floor Flat - 14', '700004', 'kolkata', '', '8276343006', 'India', '', 1, NULL, NULL, NULL, 'arrival', 'cash_pay', '1,267', 'website', NULL, NULL),
(10420, 1, 19, 1578994598, NULL, 1579046400, 1579132800, 1, 1, 0, 1600, NULL, 0, 1600, 288, 1888, NULL, NULL, NULL, NULL, 232, 'Sharad', 'Bhaiya', 'sharad210@gmail.com', 'MET SEZ', '', '', '', 'Arvind Tower, 242/1B, A.P.C.Road, 3rd Floor Flat - 14', '700004', 'kolkata', '', '8276343006', 'India', '', 1, NULL, NULL, NULL, 'arrival', 'cash_pay', '1,267', 'website', 'in', NULL),
(10421, 1, 22, 1578995177, NULL, 1579026600, 1579113000, 1, 1, NULL, 1045, NULL, 0, 1045, 125.4, 1170.4, NULL, NULL, NULL, NULL, 232, 'Sharad', 'Bhaiya', 'sharad210@gmail.com', '', NULL, '', '', 'Arvind Tower, 242/1B, A.P.C.Road, 3rd Floor Flat - 14', '700004', 'kolkata', NULL, '8276343006', 'india', NULL, 1, NULL, NULL, 1578995177, 'arrival', 'cash', '1', 'app', NULL, NULL),
(10422, 1, 19, 1579070915, NULL, 1579392000, 1579478400, 1, 7, 3, 5400, NULL, 400, 5000, 840, 5840, NULL, NULL, NULL, NULL, 237, 'Wrong Test', 'Ansari', 'zafar.ansari@met-technologies.com', '42343', NULL, '42343', '23432', 'Kolkata', '700156', 'Kolkata', NULL, '9932783472', 'India', NULL, 1, NULL, NULL, 1579070915, 'arrival', 'cash', '1,267', 'app', NULL, NULL),
(10423, 1, 19, 1579071482, NULL, 1579305600, 1579478400, 2, 7, 3, 5400, NULL, 400, 5000, 840, 5840, NULL, NULL, NULL, NULL, 237, 'Anurag', 'Sen', 'anurag.sen@met-technologies.com', '42343', NULL, '42343', '23432', 'Kolkata', '700156', 'Kolkata', NULL, '9932783472', 'India', NULL, 1, NULL, NULL, 1579071482, 'arrival', 'cash', '1,267', 'app', NULL, NULL),
(10424, 1, 19, 1579073983, NULL, 1579545000, 1579977000, 5, 7, 3, 5400, NULL, 400, 5000, 840, 5840, NULL, NULL, NULL, NULL, 237, 'Anurag', 'Sen', 'anurag.sen@met-technologies.com', '42343', NULL, '42343', '23432', 'Kolkata', '700156', 'Kolkata', NULL, '9932783472', 'India', NULL, 1, NULL, NULL, 1579073983, 'arrival', 'cash', '1,267', 'app', NULL, NULL),
(10425, 1, 19, 1579077644, NULL, 1579046400, 1579305600, 3, 7, 3, 5400, NULL, 400, 5000, 840, 5840, NULL, NULL, NULL, NULL, 237, 'Anurag', 'Sen', 'anurag.sen@met-technologies.com', '42343', NULL, '42343', '23432', 'Kolkata', '700156', 'Kolkata', NULL, '9932783472', 'India', NULL, 1, NULL, NULL, 1579077644, 'arrival', 'cash', '1,267', 'app', NULL, NULL),
(10426, 1, 19, 1579077817, NULL, 1580508000, 1580594400, 1, 2, NULL, 2076.8, NULL, 0, 1760, 316.8, 2076.8, NULL, NULL, NULL, NULL, 346, 'From', 'Bakend', 'test@mail.com', 'Met', NULL, 'Aadhar', '789456123214', 'Test', '789456', 'Kolkata', NULL, '1212121212', 'India', NULL, 1, NULL, NULL, NULL, 'arrival', 'cash_pay', '1,267', 'admin', NULL, NULL),
(10427, 1, 19, 1579083509, NULL, 1580515200, 1580601600, 1, 1, NULL, 700, NULL, 0, 700, 0, 700, NULL, NULL, NULL, NULL, 346, 'From', 'Bakend', 'test@mail.com', 'Met', NULL, 'Aadhar', '789456123214', 'Test', '789456', 'Kolkata', NULL, '1212121212', 'India', NULL, 1, NULL, NULL, NULL, 'arrival', 'cash_pay', '1,267', 'admin', NULL, NULL),
(10428, 1, 19, 1579084415, NULL, 1579132800, 1579392000, 3, 7, 3, 5400, NULL, 400, 5000, 840, 5840, NULL, NULL, NULL, NULL, 237, 'Anurag', 'Sen', 'anurag.sen@met-technologies.com', '42343', NULL, '42343', '23432', 'Kolkata', '700156', 'Kolkata', NULL, '9932783472', 'India', NULL, 1, NULL, NULL, 1579084415, 'arrival', 'cash', '1,267', 'app', NULL, NULL),
(10429, 1, 19, 1579084921, NULL, 1580515200, 1580601600, 1, 7, 3, 5400, NULL, 400, 5000, 840, 5840, NULL, NULL, NULL, NULL, 237, 'API', 'Test', 'anurag.sen@met-technologies.com', '42343', NULL, '42343', '23432', 'Kolkata', '700156', 'Kolkata', NULL, '9932783472', 'India', NULL, 1, NULL, NULL, 1579084921, 'arrival', 'cash', '1,267', 'app', NULL, NULL),
(10430, 1, 19, 1579085175, NULL, 1579219200, 1579305600, 1, 2, NULL, 1400, NULL, 0, 1400, 252, 1652, NULL, NULL, NULL, NULL, 338, 'abdd', 'def', 'Pritam@gmail.com', NULL, NULL, NULL, NULL, 'abcd street  ghybgvf grdredrdrdr hbfvtftftft ', '700063', 'kolkata', NULL, '9681295222', 'India', NULL, 1, NULL, NULL, 1579085175, 'arrival', 'cash', '1,267', 'app', NULL, NULL),
(10431, 1, 19, 1579086980, NULL, 1579046400, 1579132800, 1, 3, 1, 3680, NULL, 0, 3680, 662.4, 4342.4, NULL, NULL, NULL, NULL, 338, 'abdd', 'def', 'Pritam@gmail.com', NULL, NULL, NULL, NULL, 'abcd street  ghybgvf grdredrdrdr hbfvtftftft ', '700063', 'kolkata', NULL, '9681295222', 'India', NULL, 1, NULL, NULL, 1579086980, 'arrival', 'cash', '1,267', 'app', NULL, NULL),
(10432, 1, 19, 1579087731, NULL, 1579219200, 1579305600, 1, 2, 2, 770, NULL, 0, 770, 0, 770, NULL, NULL, NULL, NULL, 338, 'abdd', 'def', 'Pritam@gmail.com', NULL, NULL, NULL, NULL, 'abcd street  ghybgvf grdredrdrdr hbfvtftftft ', '700063', 'kolkata', NULL, '9681295222', 'India', NULL, 1, NULL, NULL, 1579087731, 'arrival', 'cash', '1,267', 'app', NULL, NULL),
(10433, 1, 19, 1579089733, NULL, 1579219200, 1579305600, 1, 4, 2, 2610, NULL, 0, 2610, 469.8, 3079.8, NULL, NULL, NULL, NULL, 338, 'abdd', 'def', 'Pritam@gmail.com', NULL, NULL, NULL, NULL, 'abcd street  ghybgvf grdredrdrdr hbfvtftftft ', '700063', 'kolkata', NULL, '9681295222', 'India', NULL, 1, NULL, NULL, 1579089733, 'arrival', 'cash', '1,267', 'app', NULL, NULL),
(10434, 2, 26, 1579099292, NULL, 1579046400, 1579132800, 1, 1, NULL, 825, NULL, 0, 825, 0, 825, NULL, NULL, NULL, NULL, 338, 'abdd', 'def', 'Pritam@gmail.com', NULL, NULL, NULL, NULL, 'abcd street  ghybgvf grdredrdrdr hbfvtftftft ', '700063', 'kolkata', NULL, '9681295222', 'India', NULL, 1, NULL, NULL, 1579099292, 'arrival', 'cash', '1', 'app', NULL, NULL),
(10435, 3, 28, 1579099590, NULL, 1579046400, 1579132800, 1, 1, NULL, 2750, NULL, 0, 2750, 495, 3245, NULL, NULL, NULL, NULL, 338, 'abdd', 'def', 'Pritam@gmail.com', NULL, NULL, NULL, NULL, 'abcd street  ghybgvf grdredrdrdr hbfvtftftft ', '700063', 'kolkata', NULL, '9681295222', 'India', NULL, 1, NULL, NULL, 1579099590, 'arrival', 'cash', '1', 'app', NULL, NULL),
(10436, 1, 19, 1579099627, NULL, 1579219200, 1579392000, 2, 1, NULL, 700, NULL, 0, 700, 0, 350, NULL, NULL, NULL, NULL, 231, 'Traveler', 'Traveler', 'sonjoy.bhadra@met-technologies.com', '4444666', NULL, '4444666', '44555', 'kolkata', '700004', 'kolkata', NULL, '9475312345', 'India', NULL, 2, NULL, NULL, 1579099627, 'arrival', 'cash', '1,267', 'app', NULL, NULL),
(10437, 1, 19, 1579166550, NULL, 1579132800, 1579219200, 1, 3, NULL, 3900, NULL, 390, 3510, 631.8, 4141.8, NULL, NULL, NULL, NULL, 231, 'Traveler', 'Traveler', 'sonjoy.bhadra@met-technologies.com', '4444666', NULL, '4444666', '44555', 'kolkata', '700004', 'kolkata', NULL, '9475312345', 'India', NULL, 1, NULL, NULL, 1579166550, 'arrival', 'cash', '1,267', 'app', NULL, NULL),
(10438, 3, 30, 1579173514, NULL, 1579132800, 1579219200, 1, 1, 0, 880, NULL, 0, 880, 0, 880, NULL, NULL, NULL, NULL, 231, 'Traveler', 'Traveler', 'sonjoy.bhadra@met-technologies.com', 'met', '', '4444666', '44555', 'kolkata', '700004', 'kolkata', '', '9475312345', 'India', '', 1, NULL, NULL, NULL, 'arrival', 'cash_pay', '1', 'website', 'in', 'out'),
(10439, 1, 20, 1579177544, NULL, 1579219200, 1579305600, 1, 1, 0, 704, NULL, 0, 704, 0, 704, NULL, NULL, NULL, NULL, 231, 'Traveler', 'Traveler', 'sonjoy.bhadra@met-technologies.com', 'met', '', '4444666', '44555', 'kolkata', '700004', 'kolkata', '', '9475312345', 'India', '', 2, NULL, NULL, NULL, 'arrival', 'cash_pay', '1', 'website', NULL, NULL),
(10440, 1, 19, 1579180390, NULL, 1579219200, 1579305600, 1, 1, 0, 700, NULL, 0, 700, 0, 350, NULL, NULL, NULL, NULL, 231, 'Traveler', 'Traveler', 'sonjoy.bhadra@met-technologies.com', 'met', '', '4444666', '44555', 'kolkata', '700004', 'kolkata', '', '9475312345', 'India', '', 2, NULL, NULL, NULL, 'arrival', 'cash_pay', '1,267', 'website', NULL, NULL),
(10441, 1, 19, 1579184519, NULL, 1579132800, 1579219200, 1, 6, 3, 3380, NULL, 0, 3380, 608.4, 3988.4, NULL, NULL, NULL, NULL, 338, 'abdd', 'def', 'Pritam@gmail.com', NULL, NULL, NULL, NULL, 'abcd street  ghybgvf grdredrdrdr hbfvtftftft ', '700063', 'kolkata', NULL, '9681295222', 'India', NULL, 1, NULL, NULL, 1579184519, 'arrival', 'cash', '1,267', 'app', NULL, NULL),
(10442, 1, 19, 1579241777, NULL, 1579219200, 1579305600, 1, 1, NULL, 700, NULL, 0, 700, 0, 700, NULL, NULL, NULL, NULL, 266, 'Sagar', 'Nayak', 'sagar@gmail.com', NULL, NULL, NULL, NULL, 'kolkata', '123456', 'kolkata', NULL, '8093329914', 'india', NULL, 2, NULL, NULL, 1579241777, 'arrival', 'cash', '1,267', 'app', NULL, NULL),
(10443, 1, 20, 1579252222, NULL, 1579219200, 1579305600, 1, 1, NULL, 704, NULL, 0, 704, 0, 704, NULL, NULL, NULL, NULL, 266, 'Sagar', 'Nayak', 'sagar@gmail.com', NULL, NULL, NULL, NULL, 'kolkata', '123456', 'kolkata', NULL, '8093329914', 'india', NULL, 1, NULL, NULL, 1579252222, 'arrival', 'cash', '1', 'app', NULL, NULL),
(10444, 1, 20, 1579266191, NULL, 1579219200, 1579305600, 1, 1, NULL, 704, NULL, 0, 704, 0, 704, NULL, NULL, NULL, NULL, 266, 'Sagar', 'Nayak', 'sagar@gmail.com', NULL, NULL, NULL, NULL, 'kolkata', '123456', 'kolkata', NULL, '8093329914', 'india', NULL, 2, NULL, NULL, 1579266191, 'arrival', 'cash', '1', 'app', NULL, NULL),
(10445, 1, 20, 1579266237, NULL, 1579219200, 1579305600, 1, 1, NULL, 704, NULL, 0, 704, 0, 704, NULL, NULL, NULL, NULL, 266, 'Sagar', 'Nayak', 'sagar@gmail.com', NULL, NULL, NULL, NULL, 'kolkata', '123456', 'kolkata', NULL, '8093329914', 'india', NULL, 2, NULL, NULL, 1579266237, 'arrival', 'cash', '1', 'app', NULL, NULL),
(10446, 1, 19, 1579409674, NULL, 1579392000, 1579478400, 1, 1, NULL, 700, NULL, 0, 700, 0, 700, NULL, NULL, NULL, NULL, 338, 'abdd', 'def', 'Pritam@gmail.com', NULL, NULL, NULL, NULL, 'abcd street  ghybgvf grdredrdrdr hbfvtftftft ', '700063', 'kolkata', NULL, '9681295222', 'India', NULL, 1, NULL, NULL, 1579409674, 'arrival', 'cash', '1,267', 'app', NULL, NULL),
(10447, 1, 19, 1579518753, NULL, 1579478400, 1579564800, 1, 2, NULL, 2300, NULL, 0, 2300, 414, 2714, NULL, NULL, NULL, NULL, 338, 'abdd', 'def', 'Pritam@gmail.com', NULL, NULL, NULL, NULL, 'abcd street  ghybgvf grdredrdrdr hbfvtftftft ', '700063', 'kolkata', NULL, '9681295222', 'India', NULL, 1, NULL, NULL, 1579518753, 'arrival', 'cash', '1,267', 'app', NULL, NULL),
(10448, 1, 19, 1579599829, NULL, 1579564800, 1579737600, 2, 1, NULL, 3200, NULL, 0, 3200, 576, 3776, NULL, NULL, NULL, NULL, 266, 'Sagar', 'Nayak', 'sagar@gmail.com', NULL, NULL, NULL, NULL, 'kolkata', '123456', 'kolkata', NULL, '8093329914', 'india', NULL, 1, NULL, NULL, 1579599829, 'arrival', 'cash', '1,267', 'app', NULL, NULL),
(10449, 3, 28, 1579614596, NULL, 1579564800, 1579651200, 1, 1, NULL, 1100, NULL, 0, 1100, 132, 1232, NULL, NULL, NULL, NULL, 338, 'abdd', 'def', 'Pritam@gmail.com', NULL, NULL, NULL, NULL, 'abcd street  ghybgvf grdredrdrdr hbfvtftftft ', '700063', 'kolkata', NULL, '1234567890', 'India', NULL, 1, NULL, NULL, 1579614596, 'arrival', 'cash', '1', 'app', NULL, NULL),
(10450, 1, 19, 1579614739, NULL, 1579564800, 1579651200, 1, 4, 1, 2610, NULL, 0, 2610, 469.8, 3079.8, NULL, NULL, NULL, NULL, 338, 'abdd', 'def', 'Pritam@gmail.com', NULL, NULL, NULL, NULL, 'abcd street  ghybgvf grdredrdrdr hbfvtftftft ', '700063', 'kolkata', NULL, '1234567890', 'India', NULL, 1, NULL, NULL, 1579614739, 'arrival', 'cash', '1,267', 'app', NULL, NULL),
(10451, 1, 19, 1579683372, NULL, 1579651200, 1579737600, 1, 6, 5, 2310, NULL, 0, 2310, 415.8, 2725.8, NULL, NULL, NULL, NULL, 338, 'abdd', 'def', 'Pritam@gmail.com', NULL, NULL, NULL, NULL, 'abcd street  ghybgvf grdredrdrdr hbfvtftftft ', '700063', 'kolkata', NULL, '1234567890', 'India', NULL, 1, NULL, NULL, 1579683372, 'arrival', 'cash', '1,267', 'app', NULL, NULL),
(10452, 3, 28, 1579683655, NULL, 1579651200, 1579737600, 1, 6, 3, 3520, NULL, 0, 3520, 633.6, 4153.6, NULL, NULL, NULL, NULL, 338, 'abdd', 'def', 'Pritam@gmail.com', NULL, NULL, NULL, NULL, 'abcd street  ghybgvf grdredrdrdr hbfvtftftft ', '700063', 'kolkata', NULL, '1234567890', 'India', NULL, 1, NULL, NULL, 1579683655, 'arrival', 'cash', '1', 'app', NULL, NULL),
(10453, 1, 19, 1579687272, NULL, 1579651200, 1579737600, 1, 1, NULL, 1600, NULL, 0, 1600, 288, 1888, NULL, NULL, NULL, NULL, 266, 'Sagar', 'Nayak', 'sagar@gmail.com', NULL, NULL, NULL, NULL, 'kolkata', '123456', 'kolkata', NULL, '8093329914', 'india', NULL, 2, NULL, NULL, 1579687272, 'arrival', 'cash', '1,267', 'app', NULL, NULL),
(10454, 1, 19, 1579687467, 1579781757, 1579651200, 1579737600, 1, 1, 0, 1600, NULL, 0, 1600, 288, 1888, NULL, 1888, NULL, NULL, 266, 'Sagar', 'Nayak', 'sagar@gmail.com', '', '', '', '', 'kolkata', '123456', 'kolkata', '', '8093329914', 'india', '', 4, NULL, NULL, 1579687467, 'arrival', 'cash', '1,267', 'app', NULL, NULL),
(10455, 1, 19, 1579687831, NULL, 1579651200, 1579737600, 1, 1, NULL, 1600, NULL, 0, 1600, 288, 1888, NULL, NULL, NULL, NULL, 266, 'Sagar', 'Nayak', 'sagar@gmail.com', NULL, NULL, NULL, NULL, 'kolkata', '123456', 'kolkata', NULL, '8093329914', 'india', NULL, 2, NULL, NULL, 1579687831, 'arrival', 'cash', '1,267', 'app', NULL, NULL),
(10456, 1, 19, 1579691492, NULL, 1579651200, 1579737600, 1, 1, NULL, 1600, NULL, 0, 1600, 288, 1888, NULL, NULL, NULL, NULL, 266, 'Sagar', 'Nayak', 'sagar@gmail.com', NULL, NULL, NULL, NULL, 'kolkata', '123456', 'kolkata', NULL, '8093329914', 'india', NULL, 2, NULL, NULL, 1579691492, 'arrival', 'cash', '1,267', 'app', NULL, NULL),
(10457, 1, 19, 1579691570, NULL, 1579651200, 1579737600, 1, 1, NULL, 1600, NULL, 0, 1600, 288, 1888, NULL, NULL, NULL, NULL, 266, 'Sagar', 'Nayak', 'sagar@gmail.com', NULL, NULL, NULL, NULL, 'kolkata', '123456', 'kolkata', NULL, '8093329914', 'india', NULL, 1, NULL, NULL, 1579691570, 'arrival', 'cash', '1,267', 'app', NULL, NULL),
(10458, 1, 19, 1579691748, NULL, 1579651200, 1579737600, 1, 1, NULL, 1600, NULL, 0, 1600, 288, 1888, NULL, NULL, NULL, NULL, 266, 'Sagar', 'Nayak', 'sagar@gmail.com', NULL, NULL, NULL, NULL, 'kolkata', '123456', 'kolkata', NULL, '8093329914', 'india', NULL, 1, NULL, NULL, 1579691748, 'arrival', 'cash', '1,267', 'app', NULL, NULL),
(10459, 1, 19, 1579692918, NULL, 1579651200, 1579737600, 1, 1, NULL, 1600, NULL, 0, 1600, 288, 1888, NULL, NULL, NULL, NULL, 266, 'Sagar', 'Nayak', 'sagar@gmail.com', NULL, NULL, NULL, NULL, 'kolkata', '123456', 'kolkata', NULL, '8093329914', 'india', NULL, 1, NULL, NULL, 1579692918, 'arrival', 'cash', '1,267', 'app', NULL, NULL),
(10460, 1, 19, 1579693089, NULL, 1579737600, 1579824000, 1, 1, NULL, 770, NULL, 70, 700, 70, 770, NULL, NULL, NULL, NULL, 237, 'Zafar', 'Ansari', 'zafar.ansari@met-technologies.com', '42343', NULL, '42343', '23432', 'Kolkata', '700156', 'Kolkata', NULL, '9932783472', 'India', NULL, 1, NULL, NULL, 1579693089, 'arrival', 'cash', '1,267', 'app', NULL, NULL),
(10461, 1, 19, 1579693311, NULL, 1579737600, 1579824000, 1, 1, NULL, 770, NULL, 70, 700, 70, 770, NULL, NULL, NULL, NULL, 237, 'Zafar', 'Ansari', 'zafar.ansari@met-technologies.com', '42343', NULL, '42343', '23432', 'Kolkata', '700156', 'Kolkata', NULL, '9932783472', 'India', NULL, 1, NULL, NULL, 1579693311, 'arrival', 'cash', '1,267', 'app', NULL, NULL),
(10462, 1, 19, 1579693468, NULL, 1579737600, 1579824000, 1, 1, NULL, 770, NULL, 70, 700, 70, 770, NULL, NULL, NULL, NULL, 237, 'Zafar', 'Ansari', 'zafar.ansari@met-technologies.com', '42343', NULL, '42343', '23432', 'Kolkata', '700156', 'Kolkata', NULL, '9932783472', 'India', NULL, 1, NULL, NULL, 1579693468, 'arrival', 'cash', '1,267', 'app', NULL, NULL),
(10463, 1, 19, 1579693475, NULL, 1579737600, 1579824000, 1, 1, NULL, 770, NULL, 70, 700, 70, 770, NULL, NULL, NULL, NULL, 237, 'Zafar', 'Ansari', 'zafar.ansari@met-technologies.com', '42343', NULL, '42343', '23432', 'Kolkata', '700156', 'Kolkata', NULL, '9932783472', 'India', NULL, 1, NULL, NULL, 1579693475, 'arrival', 'cash', '1,267', 'app', NULL, NULL),
(10464, 1, 19, 1579693774, NULL, 1579737600, 1579824000, 1, 1, NULL, 770, NULL, 70, 700, 70, 770, NULL, NULL, NULL, NULL, 237, 'Zafar', 'Ansari', 'zafar.ansari@met-technologies.com', '42343', NULL, '42343', '23432', 'Kolkata', '700156', 'Kolkata', NULL, '9932783472', 'India', NULL, 1, NULL, NULL, 1579693774, 'arrival', 'cash', '1,267', 'app', NULL, NULL),
(10465, 1, 19, 1579694082, NULL, 1579737600, 1579824000, 1, 1, NULL, 700, NULL, 0, 700, 70, 770, NULL, NULL, NULL, NULL, 237, 'Zafar', 'Ansari', 'zafar.ansari@met-technologies.com', '42343', NULL, '42343', '23432', 'Kolkata', '700156', 'Kolkata', NULL, '9932783472', 'India', NULL, 1, NULL, NULL, 1579694082, 'arrival', 'cash', '1,267', 'app', NULL, NULL),
(10466, 1, 19, 1579694815, NULL, 1579737600, 1579824000, 1, 1, NULL, 700, NULL, 0, 700, 70, 770, NULL, NULL, NULL, NULL, 237, 'Zafar', 'Ansari', 'zafar.ansari@met-technologies.com', '42343', NULL, '42343', '23432', 'Kolkata', '700156', 'Kolkata', NULL, '9932783472', 'India', NULL, 1, NULL, NULL, 1579694815, 'arrival', 'cash', '1,267', 'app', NULL, NULL),
(10467, 1, 19, 1579694877, NULL, 1579737600, 1579824000, 1, 1, NULL, 700, NULL, 0, 700, 70, 770, NULL, NULL, NULL, NULL, 237, 'Zafar', 'Ansari', 'zafar.ansari@met-technologies.com', '42343', NULL, '42343', '23432', 'Kolkata', '700156', 'Kolkata', NULL, '9932783472', 'India', NULL, 1, NULL, NULL, 1579694877, 'arrival', 'cash', '1,267', 'app', NULL, NULL),
(10468, 1, 19, 1579694965, NULL, 1579737600, 1579824000, 1, 1, NULL, 770, NULL, 70, 700, 70, 770, NULL, NULL, NULL, NULL, 237, 'Zafar', 'Ansari', 'zafar.ansari@met-technologies.com', '42343', NULL, '42343', '23432', 'Kolkata', '700156', 'Kolkata', NULL, '9932783472', 'India', NULL, 1, NULL, NULL, 1579694965, 'arrival', 'cash', '1,267', 'app', NULL, NULL),
(10469, 1, 19, 1579695158, NULL, 1575936000, 1576108800, 2, 4, NULL, 5400, NULL, 400, 5000, 840, 5840, NULL, NULL, NULL, NULL, 237, 'Zafar', 'Ansari', 'zafar.ansari@met-technologies.com', '42343', NULL, '42343', '23432', 'Kolkata', '700156', 'Kolkata', NULL, '9932783472', 'India', NULL, 1, NULL, NULL, 1579695158, 'arrival', 'cash', '1,267', 'app', NULL, NULL),
(10470, 1, 19, 1579695568, NULL, 1575936000, 1576108800, 2, 7, 3, 5400, NULL, 400, 5000, 840, 5840, NULL, NULL, NULL, NULL, 237, 'Zafar', 'Ansari', 'zafar.ansari@met-technologies.com', '42343', NULL, '42343', '23432', 'Kolkata', '700156', 'Kolkata', NULL, '9932783472', 'India', NULL, 1, NULL, NULL, 1579695568, 'arrival', 'cash', '1,267', 'app', NULL, NULL),
(10471, 1, 19, 1579753774, NULL, 1575936000, 1576108800, 2, 7, 3, 5400, NULL, 400, 5000, 840, 5840, NULL, NULL, NULL, NULL, 237, 'Zafar', 'Ansari', 'zafar.ansari@met-technologies.com', '42343', NULL, '42343', '23432', 'Kolkata', '700156', 'Kolkata', NULL, '9932783472', 'India', NULL, 1, NULL, NULL, 1579753774, 'arrival', 'cash', '1,267', 'app', NULL, NULL),
(10472, 1, 19, 1579756067, NULL, 1575936000, 1576108800, 2, 7, 3, 5840, NULL, 237, 5603, 237, 5840, NULL, NULL, NULL, NULL, 237, 'Zafar', 'Ansari', 'zafar.ansari@met-technologies.com', '42343', NULL, '42343', '23432', 'Kolkata', '700156', 'Kolkata', NULL, '9932783472', 'India', NULL, 1, NULL, NULL, 1579756067, 'arrival', 'cash', '1,267', 'app', NULL, NULL),
(10473, 1, 19, 1579757397, NULL, 1575936000, 1576108800, 2, 7, 3, 5840, NULL, 237, 5603, 237, 5840, NULL, NULL, NULL, NULL, 237, 'Zafar', 'Ansari', 'zafar.ansari@met-technologies.com', '42343', NULL, '42343', '23432', 'Kolkata', '700156', 'Kolkata', NULL, '9932783472', 'India', NULL, 1, NULL, NULL, 1579757397, 'arrival', 'cash', '1,267', 'app', NULL, NULL),
(10474, 1, 19, 1579762766, NULL, 1579737600, 1579824000, 1, 1, 1, 682, NULL, 0, 682, 18, 700, NULL, NULL, NULL, NULL, 338, 'abdd', 'def', 'Pritam@gmail.com', NULL, NULL, NULL, NULL, 'abcd street  ghybgvf grdredrdrdr hbfvtftftft ', '700063', 'kolkata', NULL, '1234567890', 'India', NULL, 1, NULL, NULL, 1579762766, 'arrival', 'cash', '1,267', 'app', NULL, NULL),
(10475, 1, 19, 1579762775, NULL, 1579737600, 1579824000, 1, 1, NULL, 682, NULL, 0, 682, 18, 700, NULL, NULL, NULL, NULL, 338, 'abdd', 'def', 'Pritam@gmail.com', NULL, NULL, NULL, NULL, 'abcd street  ghybgvf grdredrdrdr hbfvtftftft ', '700063', 'kolkata', NULL, '1234567890', 'India', NULL, 1, NULL, NULL, 1579762775, 'arrival', 'cash', '1,267', 'app', NULL, NULL),
(10476, 1, 19, 1579762798, NULL, 1579737600, 1579824000, 1, 1, NULL, 682, NULL, 0, 682, 18, 700, NULL, NULL, NULL, NULL, 338, 'abdd', 'def', 'Pritam@gmail.com', NULL, NULL, NULL, NULL, 'abcd street  ghybgvf grdredrdrdr hbfvtftftft ', '700063', 'kolkata', NULL, '1234567890', 'India', NULL, 1, NULL, NULL, 1579762798, 'arrival', 'cash', '1,267', 'app', NULL, NULL),
(10477, 1, 19, 1579762970, NULL, 1579737600, 1579824000, 1, 1, NULL, 682, NULL, 0, 682, 18, 700, NULL, NULL, NULL, NULL, 338, 'abdd', 'def', 'Pritam@gmail.com', NULL, NULL, NULL, NULL, 'abcd street  ghybgvf grdredrdrdr hbfvtftftft ', '700063', 'kolkata', NULL, '1234567890', 'India', NULL, 1, NULL, NULL, 1579762970, 'arrival', 'cash', '1,267', 'app', NULL, NULL),
(10478, 2, 24, 1579765385, NULL, 1579737600, 1579824000, 1, 1, NULL, 862, NULL, 0, 862, 18, 880, NULL, NULL, NULL, NULL, 338, 'abdd', 'def', 'Pritam@gmail.com', NULL, NULL, NULL, NULL, 'abcd street  ghybgvf grdredrdrdr hbfvtftftft ', '700063', 'kolkata', NULL, '1234567890', 'India', NULL, 1, NULL, NULL, 1579765385, 'arrival', 'cash', '1', 'app', NULL, NULL),
(10479, 2, 24, 1579766037, NULL, 1579737600, 1579824000, 1, 1, NULL, 862, NULL, 0, 862, 18, 880, NULL, NULL, NULL, NULL, 338, 'abdd', 'def', 'Pritam@gmail.com', NULL, NULL, NULL, NULL, 'abcd street  ghybgvf grdredrdrdr hbfvtftftft ', '700063', 'kolkata', NULL, '1234567890', 'India', NULL, 1, NULL, NULL, 1579766037, 'arrival', 'cash', '1', 'app', NULL, NULL),
(10480, 2, 24, 1579766084, NULL, 1579737600, 1579824000, 1, 1, NULL, 880, NULL, 0, 880, 0, 880, NULL, NULL, NULL, NULL, 338, 'abdd', 'def', 'Pritam@gmail.com', NULL, NULL, NULL, NULL, 'abcd street  ghybgvf grdredrdrdr hbfvtftftft ', '700063', 'kolkata', NULL, '9681295222', 'India', NULL, 1, NULL, NULL, 1579766084, 'arrival', 'cash', '1', 'app', NULL, NULL),
(10481, 1, 19, 1579766365, NULL, 1579996800, 1579996800, 1, 1, 0, 3200, NULL, 0, 3200, 576, 2832, NULL, NULL, NULL, NULL, 231, 'Traveler', 'Traveler', 'sonjoy.bhadra@met-technologies.com', 'met', '', '4444666', '44555', 'kolkata', '700004', 'kolkata', '', '9475312345', 'India', '', 1, NULL, NULL, NULL, 'arrival', 'cash_pay', '1,267', 'website', NULL, NULL),
(10482, 2, 24, 1579766491, NULL, 1579737600, 1579824000, 1, 1, NULL, 862, NULL, 0, 862, 18, 880, NULL, NULL, NULL, NULL, 338, 'abdd', 'def', 'Pritam@gmail.com', NULL, NULL, NULL, NULL, 'abcd street  ghybgvf grdredrdrdr hbfvtftftft ', '700063', 'kolkata', NULL, '1234567890', 'India', NULL, 1, NULL, NULL, 1579766491, 'arrival', 'cash', '1', 'app', NULL, NULL),
(10483, 2, 24, 1579766599, NULL, 1579737600, 1579824000, 1, 1, NULL, 862, NULL, 0, 862, 18, 880, NULL, NULL, NULL, NULL, 338, 'abdd', 'def', 'Pritam@gmail.com', NULL, NULL, NULL, NULL, 'abcd street  ghybgvf grdredrdrdr hbfvtftftft ', '700063', 'kolkata', NULL, '1234567890', 'India', NULL, 1, NULL, NULL, 1579766599, 'arrival', 'cash', '1', 'app', NULL, NULL),
(10484, 2, 24, 1579766932, NULL, 1579737600, 1579824000, 1, 1, NULL, 862, NULL, 0, 862, 18, 880, NULL, NULL, NULL, NULL, 338, 'abdd', 'def', 'Pritam@gmail.com', NULL, NULL, NULL, NULL, 'abcd street  ghybgvf grdredrdrdr hbfvtftftft ', '700063', 'kolkata', NULL, '1234567890', 'India', NULL, 1, NULL, NULL, 1579766932, 'arrival', 'cash', '1', 'app', NULL, NULL),
(10485, 2, 24, 1579766976, NULL, 1579737600, 1579824000, 1, 1, NULL, 862, NULL, 0, 862, 18, 880, NULL, NULL, NULL, NULL, 338, 'abdd', 'def', 'Pritam@gmail.com', NULL, NULL, NULL, NULL, 'abcd street  ghybgvf grdredrdrdr hbfvtftftft ', '700063', 'kolkata', NULL, '1234567890', 'India', NULL, 1, NULL, NULL, 1579766976, 'arrival', 'cash', '1', 'app', NULL, NULL),
(10486, 2, 24, 1579767091, NULL, 1579737600, 1579824000, 1, 1, NULL, 862, NULL, 0, 862, 18, 880, NULL, NULL, NULL, NULL, 338, 'abdd', 'def', 'Pritam@gmail.com', NULL, NULL, NULL, NULL, 'abcd street  ghybgvf grdredrdrdr hbfvtftftft ', '700063', 'kolkata', NULL, '1234567890', 'India', NULL, 1, NULL, NULL, 1579767091, 'arrival', 'cash', '1', 'app', NULL, NULL),
(10487, 1, 19, 1579767231, NULL, 1579996800, 1580083200, 1, 1, 0, 210, NULL, 0, 2100, 37.8, 247.8, NULL, NULL, NULL, NULL, 231, 'Traveler', 'Traveler', 'sonjoy.bhadra@met-technologies.com', 'met', '', '4444666', '44555', 'kolkata', '700004', 'kolkata', '', '9475312345', 'India', '', 2, NULL, NULL, NULL, 'arrival', 'cash_pay', '1,267', 'website', NULL, NULL),
(10488, 2, 24, 1579768198, NULL, 1579737600, 1579824000, 1, 1, NULL, 862, NULL, 0, 862, 18, 880, NULL, NULL, NULL, NULL, 338, 'abdd', 'def', 'Pritam@gmail.com', NULL, NULL, NULL, NULL, 'abcd street  ghybgvf grdredrdrdr hbfvtftftft ', '700063', 'kolkata', NULL, '1234567890', 'India', NULL, 1, NULL, NULL, 1579768198, 'arrival', 'cash', '1', 'app', NULL, NULL),
(10489, 2, 24, 1579768468, NULL, 1579737600, 1579824000, 1, 1, NULL, 862, NULL, 0, 862, 18, 880, NULL, NULL, NULL, NULL, 338, 'abdd', 'def', 'Pritam@gmail.com', NULL, NULL, NULL, NULL, 'abcd street  ghybgvf grdredrdrdr hbfvtftftft ', '700063', 'kolkata', NULL, '1234567890', 'India', NULL, 1, NULL, NULL, 1579768468, 'arrival', 'cash', '1', 'app', NULL, NULL),
(10490, 2, 24, 1579768524, NULL, 1579737600, 1579824000, 1, 1, NULL, 862, NULL, 0, 862, 18, 880, NULL, NULL, NULL, NULL, 338, 'abdd', 'def', 'Pritam@gmail.com', NULL, NULL, NULL, NULL, 'abcd street  ghybgvf grdredrdrdr hbfvtftftft ', '700063', 'kolkata', NULL, '1234567890', 'India', NULL, 1, NULL, NULL, 1579768524, 'arrival', 'cash', '1', 'app', NULL, NULL),
(10491, 2, 24, 1579768995, NULL, 1579737600, 1579824000, 1, 1, NULL, 862, NULL, 0, 862, 18, 880, NULL, NULL, NULL, NULL, 338, 'abdd', 'def', 'Pritam@gmail.com', NULL, NULL, NULL, NULL, 'abcd street  ghybgvf grdredrdrdr hbfvtftftft ', '700063', 'kolkata', NULL, '1234567890', 'India', NULL, 1, NULL, NULL, 1579768995, 'arrival', 'cash', '1', 'app', NULL, NULL),
(10492, 2, 24, 1579770704, NULL, 1579737600, 1579824000, 1, 1, NULL, 862, NULL, 0, 862, 18, 880, NULL, NULL, NULL, NULL, 338, 'abdd', 'def', 'Pritam@gmail.com', NULL, NULL, NULL, NULL, 'abcd street  ghybgvf grdredrdrdr hbfvtftftft ', '700063', 'kolkata', NULL, '1234567890', 'India', NULL, 1, NULL, NULL, 1579770704, 'arrival', 'cash', '1', 'app', NULL, NULL),
(10493, 3, 27, 1579770977, NULL, 1579737600, 1579824000, 1, 1, NULL, 752, NULL, 0, 752, 18, 770, NULL, NULL, NULL, NULL, 338, 'abdd', 'def', 'Pritam@gmail.com', NULL, NULL, NULL, NULL, 'abcd street  ghybgvf grdredrdrdr hbfvtftftft ', '700063', 'kolkata', NULL, '1234567890', 'India', NULL, 1, NULL, NULL, 1579770977, 'arrival', 'cash', '1', 'app', NULL, NULL),
(10494, 3, 27, 1579771170, NULL, 1579737600, 1579824000, 1, 1, NULL, 752, NULL, 0, 752, 18, 770, NULL, NULL, NULL, NULL, 338, 'abdd', 'def', 'Pritam@gmail.com', NULL, NULL, NULL, NULL, 'abcd street  ghybgvf grdredrdrdr hbfvtftftft ', '700063', 'kolkata', NULL, '1234567890', 'India', NULL, 1, NULL, NULL, 1579771170, 'arrival', 'cash', '1', 'app', NULL, NULL),
(10495, 3, 27, 1579771259, NULL, 1579737600, 1579824000, 1, 1, NULL, 752, NULL, 0, 752, 18, 770, NULL, NULL, NULL, NULL, 338, 'abdd', 'def', 'Pritam@gmail.com', NULL, NULL, NULL, NULL, 'abcd street  ghybgvf grdredrdrdr hbfvtftftft ', '700063', 'kolkata', NULL, '1234567890', 'India', NULL, 1, NULL, NULL, 1579771259, 'arrival', 'cash', '1', 'app', NULL, NULL),
(10496, 3, 27, 1579772255, NULL, 1579737600, 1579824000, 1, 1, NULL, 752, NULL, 0, 752, 18, 770, NULL, NULL, NULL, NULL, 338, 'abdd', 'def', 'Pritam@gmail.com', NULL, NULL, NULL, NULL, 'abcd street  ghybgvf grdredrdrdr hbfvtftftft ', '700063', 'kolkata', NULL, '1234567890', 'India', NULL, 1, NULL, NULL, 1579772255, 'arrival', 'cash', '1', 'app', NULL, NULL),
(10497, 3, 27, 1579772375, NULL, 1579737600, 1579824000, 1, 1, NULL, 752, NULL, 0, 752, 18, 770, NULL, NULL, NULL, NULL, 338, 'abdd', 'def', 'Pritam@gmail.com', NULL, NULL, NULL, NULL, 'abcd street  ghybgvf grdredrdrdr hbfvtftftft ', '700063', 'kolkata', NULL, '1234567890', 'India', NULL, 1, NULL, NULL, 1579772375, 'arrival', 'cash', '1', 'app', NULL, NULL),
(10498, 3, 27, 1579773189, NULL, 1579737600, 1579824000, 1, 1, NULL, 752, NULL, 0, 752, 18, 770, NULL, NULL, NULL, NULL, 338, 'abdd', 'def', 'Pritam@gmail.com', NULL, NULL, NULL, NULL, 'abcd street  ghybgvf grdredrdrdr hbfvtftftft ', '700063', 'kolkata', NULL, '1234567890', 'India', NULL, 1, NULL, NULL, 1579773189, 'arrival', 'cash', '1', 'app', 'in', NULL),
(10499, 3, 27, 1579773397, NULL, 1579737600, 1579824000, 1, 1, NULL, 752, NULL, 0, 752, 18, 770, NULL, NULL, NULL, NULL, 338, 'abdd', 'def', 'Pritam@gmail.com', NULL, NULL, NULL, NULL, 'abcd street  ghybgvf grdredrdrdr hbfvtftftft ', '700063', 'kolkata', NULL, '1234567890', 'India', NULL, 1, NULL, NULL, 1579773397, 'arrival', 'cash', '1', 'app', NULL, NULL),
(10500, 3, 27, 1579773483, NULL, 1579737600, 1579824000, 1, 1, NULL, 752, NULL, 0, 752, 18, 770, NULL, NULL, NULL, NULL, 338, 'abdd', 'def', 'Pritam@gmail.com', NULL, NULL, NULL, NULL, 'abcd street  ghybgvf grdredrdrdr hbfvtftftft ', '700063', 'kolkata', NULL, '1234567890', 'India', NULL, 1, NULL, NULL, 1579773483, 'arrival', 'cash', '1', 'app', NULL, NULL),
(10501, 3, 27, 1579773631, NULL, 1579737600, 1579824000, 1, 1, NULL, 752, NULL, 0, 752, 18, 770, NULL, NULL, NULL, NULL, 338, 'abdd', 'def', 'Pritam@gmail.com', NULL, NULL, NULL, NULL, 'abcd street  ghybgvf grdredrdrdr hbfvtftftft ', '700063', 'kolkata', NULL, '1234567890', 'India', NULL, 1, NULL, NULL, 1579773631, 'arrival', 'cash', '1', 'app', NULL, NULL),
(10502, 3, 27, 1579774305, NULL, 1579737600, 1579824000, 1, 1, NULL, 752, NULL, 0, 752, 18, 770, NULL, NULL, NULL, NULL, 338, 'abdd', 'def', 'Pritam@gmail.com', NULL, NULL, NULL, NULL, 'abcd street  ghybgvf grdredrdrdr hbfvtftftft ', '700063', 'kolkata', NULL, '9681295248', 'India', NULL, 1, NULL, NULL, 1579774305, 'arrival', 'cash', '1', 'app', NULL, NULL),
(10503, 1, 19, 1579780974, NULL, 1579737600, 1579824000, 1, 1, NULL, 700, NULL, 0, 700, 0, 700, NULL, NULL, NULL, NULL, 338, 'abdd', 'def', 'Pritam@gmail.com', NULL, NULL, NULL, NULL, 'abcd street  ghybgvf grdredrdrdr hbfvtftftft ', '700063', 'kolkata', NULL, '1234567890', 'India', NULL, 1, NULL, NULL, 1579780974, 'arrival', 'cash', '1,267', 'app', NULL, NULL),
(10504, 2, 24, 1579786905, NULL, 1582848000, 1582934400, 1, 1, 0, 880, NULL, 0, 880, NULL, 880, NULL, NULL, NULL, NULL, 231, 'Traveler', 'Traveler', 'sonjoy.bhadra@met-technologies.com', 'met', '', '4444666', '44555', 'kolkata', '700004', 'kolkata', '', '9475312345', 'India', '', 2, NULL, NULL, NULL, 'arrival', 'cash_pay', '1', 'website', NULL, NULL),
(10505, 2, 24, 1579787326, NULL, 1582934400, 1583020800, 1, 1, 0, 176, NULL, 0, 1760, 31.68, 207.68, NULL, NULL, NULL, NULL, 231, 'Traveler', 'Traveler', 'sonjoy.bhadra@met-technologies.com', 'met', '', '4444666', '44555', 'kolkata', '700004', 'kolkata', '', '9475312345', 'India', '', 2, NULL, NULL, NULL, 'arrival', 'cash_pay', '1', 'website', NULL, NULL),
(10506, 2, 24, 1579864743, NULL, 1582848000, 1582934400, 1, 1, 0, 88, NULL, 0, 880, NULL, 88, NULL, NULL, NULL, NULL, 231, 'Traveler', 'Traveler', 'sonjoy.bhadra@met-technologies.com', 'met', '', '4444666', '44555', 'kolkata', '700004', 'kolkata', '', '9475312345', 'India', '', 2, NULL, NULL, NULL, 'arrival', 'cash_pay', '1', 'website', NULL, NULL),
(10507, 2, 24, 1579866481, NULL, 1583020800, 1583107200, 1, 2, 1, 6864, NULL, 0, 6864, 1235.52, 686.4, NULL, NULL, NULL, NULL, 231, 'Traveler', 'Traveler', 'sonjoy.bhadra@met-technologies.com', 'met', '', '4444666', '44555', 'kolkata', '700004', 'kolkata', '', '9475312345', 'India', '', 1, NULL, NULL, NULL, 'arrival', 'cash_pay', '1', 'website', NULL, NULL),
(10508, 1, 19, 1579866750, NULL, 1579824000, 1579910400, 1, 1, 0, 1600, NULL, 0, 1600, 288, 1888, NULL, NULL, NULL, NULL, 233, 'Traveler', 'Traveler', 'qa2210@yopmail.com', 'test', '', '123456', '123456', '123 testdrive', '700056', 'kolkata', '', '9830256890', 'India', '', 1, NULL, NULL, NULL, 'arrival', 'cash_pay', '1,267', 'website', NULL, NULL),
(10509, 1, 19, 1579867308, NULL, 1579824000, 1579910400, 1, 1, NULL, 682, NULL, 0, 682, 18, 700, NULL, NULL, NULL, NULL, 338, 'abdd', 'def', 'Pritam@gmail.com', NULL, NULL, NULL, NULL, 'abcd street  ghybgvf grdredrdrdr hbfvtftftft ', '700063', 'kolkata', NULL, '9681295248', 'India', NULL, 1, NULL, NULL, 1579867308, 'arrival', 'cash', '1,267', 'app', NULL, NULL),
(10510, 1, 19, 1579868664, NULL, 1579996800, 1580083200, 1, 1, 0, 3200, NULL, 0, 3200, 576, 2400, NULL, NULL, NULL, NULL, 231, 'Traveler', 'Traveler', 'sonjoy.bhadra@met-technologies.com', 'met', '', '4444666', '44555', 'kolkata', '700004', 'kolkata', '', '9475312345', 'India', '', 1, NULL, NULL, NULL, 'arrival', 'cash_pay', '1,267', 'website', NULL, NULL),
(10511, 1, 19, 1579868908, NULL, 1579910400, 1579996800, 1, 1, 0, 350, NULL, 0, 700, NULL, 350, NULL, NULL, NULL, NULL, 231, 'Traveler', 'Traveler', 'sonjoy.bhadra@met-technologies.com', 'met', '', '4444666', '44555', 'kolkata', '700004', 'kolkata', '', '9475312345', 'India', '', 2, NULL, NULL, NULL, 'arrival', 'cash_pay', '1,267', 'website', NULL, NULL),
(10512, 1, 19, 1579869096, 1580278437, 1579910400, 1579996800, 1, 2, 0, 1400, NULL, 0, 1400, 252, 1050, NULL, 1050, NULL, NULL, 231, 'Traveler', 'Traveler', 'sonjoy.bhadra@met-technologies.com', 'met', '', '4444666', '44555', 'kolkata', '700004', 'kolkata', '', '9475312345', 'India', '', 4, NULL, NULL, NULL, 'arrival', 'cash_pay', '1,267', 'website', NULL, NULL),
(10513, 1, 19, 1580108769, NULL, 1580083200, 1580169600, 1, 1, NULL, 682, NULL, 0, 682, 18, 700, NULL, NULL, NULL, NULL, 338, 'abdd', 'def', 'Pritam@gmail.com', NULL, NULL, NULL, NULL, 'abcd street  ghybgvf grdredrdrdr hbfvtftftft ', '700063', 'kolkata', NULL, '9681295222', 'India', NULL, 2, NULL, NULL, 1580108769, 'arrival', 'cash', '1,267', 'app', NULL, NULL),
(10514, 1, 19, 1580109837, NULL, 1575936000, 1576108800, 2, 7, 3, 5400, NULL, 400, 5000, 840, 5840, NULL, NULL, NULL, NULL, 237, 'Zafar', 'Ansari', 'zafar.ansari@met-technologies.com', '42343', NULL, '42343', '23432', 'Kolkata', '700156', 'Kolkata', NULL, '9932783472', 'India', NULL, 1, NULL, NULL, 1580109837, 'arrival', 'cash', '1,267', 'app', NULL, NULL),
(10515, 1, 19, 1580119169, NULL, 1580083200, 1580169600, 1, 1, NULL, 682, NULL, 0, 682, 18, 700, NULL, NULL, NULL, NULL, 338, 'abdd', 'def', 'Pritam@gmail.com', NULL, NULL, NULL, NULL, 'abcd street  ghybgvf grdredrdrdr hbfvtftftft ', '700063', 'kolkata', NULL, '9681295248', 'India', NULL, 2, NULL, NULL, 1580119169, 'arrival', 'cash', '1,267', 'app', NULL, NULL),
(10516, 1, 19, 1580120405, NULL, 1580083200, 1580169600, 1, 1, NULL, 1870, NULL, 0, 1870, 18, 1888, NULL, NULL, NULL, NULL, 338, 'abdd', 'def', 'Pritam@gmail.com', NULL, NULL, NULL, NULL, 'abcd street  ghybgvf grdredrdrdr hbfvtftftft ', '700063', 'kolkata', NULL, '9681295248', 'India', NULL, 1, NULL, NULL, 1580120405, 'arrival', 'cash', '1,267', 'app', NULL, NULL),
(10517, 2, 24, 1580120420, NULL, 1580083200, 1580169600, 1, 1, NULL, 862, NULL, 0, 862, 18, 880, NULL, NULL, NULL, NULL, 338, 'abdd', 'def', 'Pritam@gmail.com', NULL, NULL, NULL, NULL, 'abcd street  ghybgvf grdredrdrdr hbfvtftftft ', '700063', 'kolkata', NULL, '9681295248', 'India', NULL, 2, NULL, NULL, 1580120420, 'arrival', 'cash', '1', 'app', NULL, NULL),
(10518, 1, 19, 1580121883, NULL, 1580083200, 1580169600, 1, 1, NULL, NULL, NULL, 0, NULL, 0, 0, NULL, NULL, NULL, NULL, 338, 'abdd', 'def', 'Pritam@gmail.com', NULL, NULL, NULL, NULL, 'abcd street  ghybgvf grdredrdrdr hbfvtftftft ', '700063', 'kolkata', NULL, '1234567890', 'India', NULL, 2, NULL, NULL, 1580121883, 'arrival', 'cash', '1,267', 'app', NULL, NULL),
(10519, 1, 19, 1580131236, NULL, 1580083200, 1580169600, 1, 1, NULL, 682, NULL, 0, 682, 18, 700, NULL, NULL, NULL, NULL, 338, 'abdd', 'def', 'Pritam@gmail.com', NULL, NULL, NULL, NULL, 'abcd street  ghybgvf grdredrdrdr hbfvtftftft ', '700063', 'kolkata', NULL, '1234567890', 'India', NULL, 1, NULL, NULL, 1580131236, 'arrival', 'cash', '1,267', 'app', NULL, NULL),
(10520, 1, 19, 1580131241, NULL, 1580083200, 1580169600, 1, 1, NULL, 700, NULL, 0, 700, 0, 700, NULL, NULL, NULL, NULL, 255, 'Anup', 'Bora', 'anup@gmail.com', NULL, NULL, NULL, NULL, 'kolkata', '712707', 'kolkata', NULL, '9007218463', 'United Arab Emirates', NULL, 1, NULL, NULL, 1580131241, 'arrival', 'cash', '1,267', 'app', NULL, NULL),
(10521, 1, 19, 1580191277, NULL, 1580169600, 1580256000, 1, 1, NULL, 682, NULL, 0, 682, 18, 700, NULL, NULL, NULL, NULL, 338, 'abdd', 'def', 'Pritam@gmail.com', NULL, NULL, NULL, NULL, 'abcd street  ghybgvf grdredrdrdr hbfvtftftft ', '700063', 'kolkata', NULL, '9681295248', 'India', NULL, 2, NULL, NULL, 1580191277, 'arrival', 'cash', '1,267', 'app', NULL, NULL),
(10522, 1, 19, 1580192460, 1580198259, 1580169600, 1580256000, 1, 2, 2, 1400, NULL, 0, 1400, 252, 1652, NULL, 1652, NULL, NULL, 266, 'Sagar', 'Nayak', 'sagar@gmail.com', '', '', '', '', 'kolkata', '123456', 'kolkata', '', '8093329914', 'india', '', 3, NULL, NULL, 1580192460, 'arrival', 'cash', '1,267', 'app', 'in', 'out'),
(10523, 1, 19, 1580192806, 1580198281, 1580169600, 1580256000, 1, 1, 0, 1600, NULL, 0, 1600, 288, 1888, NULL, 1888, NULL, NULL, 266, 'Sagar', 'Nayak', 'sagar@gmail.com', '', '', '', '', 'kolkata', '123456', 'kolkata', '', '8093329914', 'india', '', 1, NULL, NULL, 1580192806, 'arrival', 'cash', '1,267', 'app', 'in', 'out'),
(10524, 2, 24, 1580198592, NULL, 1580169600, 1580256000, 1, 1, NULL, 880, NULL, 0, 880, 0, 880, NULL, NULL, NULL, NULL, 338, 'abdd', 'def', 'Pritam@gmail.com', NULL, NULL, NULL, NULL, 'abcd street  ghybgvf grdredrdrdr hbfvtftftft ', '700063', 'kolkata', NULL, '1234567890', 'India', NULL, 2, NULL, NULL, 1580198592, 'arrival', 'cash', '1', 'app', NULL, NULL),
(10525, 3, 27, 1580198691, NULL, 1580169600, 1580256000, 1, 1, NULL, 752, NULL, 0, 752, 18, 770, NULL, NULL, NULL, NULL, 338, 'abdd', 'def', 'Pritam@gmail.com', NULL, NULL, NULL, NULL, 'abcd street  ghybgvf grdredrdrdr hbfvtftftft ', '700063', 'kolkata', NULL, '1234567890', 'India', NULL, 2, NULL, NULL, 1580198691, 'arrival', 'cash', '1', 'app', NULL, NULL),
(10526, 1, 19, 1580198880, NULL, 1580169600, 1580256000, 1, 1, NULL, 1600, NULL, 0, 1600, 288, 1888, NULL, NULL, NULL, NULL, 266, 'Sagar', 'Nayak', 'sagar@gmail.com', NULL, NULL, NULL, NULL, 'kolkata', '123456', 'kolkata', NULL, '8093329914', 'india', NULL, 1, NULL, NULL, 1580198880, 'arrival', 'cash', '1,267', 'app', NULL, NULL),
(10527, 2, 24, 1580199164, NULL, 1580169600, 1580256000, 1, 2, 1, 862, NULL, 0, 862, 18, 880, NULL, NULL, NULL, NULL, 338, 'abdd', 'def', 'Pritam@gmail.com', NULL, NULL, NULL, NULL, 'abcd street  ghybgvf grdredrdrdr hbfvtftftft ', '700063', 'kolkata', NULL, '1234567890', 'India', NULL, 1, NULL, NULL, 1580199164, 'arrival', 'cash', '1', 'app', NULL, NULL),
(10528, 2, 24, 1580199316, NULL, 1580169600, 1580256000, 1, 2, 1, 862, NULL, 0, 862, 18, 880, NULL, NULL, NULL, NULL, 338, 'abdd', 'def', 'Pritam@gmail.com', NULL, NULL, NULL, NULL, 'abcd street  ghybgvf grdredrdrdr hbfvtftftft ', '700063', 'kolkata', NULL, '1234567890', 'India', NULL, 1, NULL, NULL, 1580199316, 'arrival', 'cash', '1', 'app', NULL, NULL),
(10529, 2, 24, 1580199497, NULL, 1580169600, 1580256000, 1, 2, 1, 862, NULL, 0, 862, 18, 880, NULL, NULL, NULL, NULL, 338, 'abdd', 'def', 'Pritam@gmail.com', NULL, NULL, NULL, NULL, 'abcd street  ghybgvf grdredrdrdr hbfvtftftft ', '700063', 'kolkata', NULL, '1234567890', 'India', NULL, 1, NULL, NULL, 1580199497, 'arrival', 'cash', '1', 'app', NULL, NULL),
(10530, 2, 24, 1580199598, NULL, 1580169600, 1580256000, 1, 2, 1, 862, NULL, 0, 862, 18, 880, NULL, NULL, NULL, NULL, 338, 'abdd', 'def', 'Pritam@gmail.com', NULL, NULL, NULL, NULL, 'abcd street  ghybgvf grdredrdrdr hbfvtftftft ', '700063', 'kolkata', NULL, '1234567890', 'India', NULL, 2, NULL, NULL, 1580199598, 'arrival', 'cash', '1', 'app', NULL, NULL),
(10531, 2, 24, 1580199756, NULL, 1580169600, 1580256000, 1, 1, NULL, 862, NULL, 0, 862, 18, 880, NULL, NULL, NULL, NULL, 338, 'abdd', 'def', 'Pritam@gmail.com', NULL, NULL, NULL, NULL, 'abcd street  ghybgvf grdredrdrdr hbfvtftftft ', '700063', 'kolkata', NULL, '1234567890', 'India', NULL, 2, NULL, NULL, 1580199756, 'arrival', 'cash', '1', 'app', NULL, NULL),
(10532, 1, 19, 1580201258, NULL, 1580169600, 1580428800, 3, 2, 0, 4620, NULL, 0, 4620, 831.6, 5451.6, NULL, NULL, NULL, NULL, 232, 'Sharad', 'Bhaiya', 'sharad210@gmail.com', 'MET SEZ', '', '', '', 'Arvind Tower, 242/1B, A.P.C.Road, 3rd Floor Flat - 14', '700004', 'kolkata', '', '8276343006', 'India', '', 1, NULL, NULL, NULL, 'arrival', 'cash_pay', '1,267', 'website', 'in', 'out'),
(10533, 1, 19, 1580201565, NULL, 1580342400, 1580428800, 1, 2, 0, 3080, NULL, 0, 3080, 554.4, 770, NULL, NULL, NULL, NULL, 232, 'Sharad', 'Bhaiya', 'sharad210@gmail.com', 'MET SEZ', '', '', '', 'Arvind Tower, 242/1B, A.P.C.Road, 3rd Floor Flat - 14', '700004', 'kolkata', '', '8276343006', 'India', '', 1, NULL, NULL, NULL, 'arrival', 'cash_pay', '1,267', 'website', NULL, NULL),
(10534, 1, 19, 1580207291, NULL, 1580169600, 1580256000, 1, 1, NULL, 1600, NULL, 0, 1600, 288, 1888, NULL, NULL, NULL, NULL, 266, 'Sagar', 'Nayak', 'sagar@gmail.com', NULL, NULL, NULL, NULL, 'kolkata', '123456', 'kolkata', NULL, '8093329914', 'india', NULL, 1, NULL, NULL, 1580207291, 'arrival', 'cash', '1,267', 'app', NULL, NULL),
(10535, 1, 19, 1580209161, NULL, 1580169600, 1580256000, 1, 1, NULL, 682, NULL, 0, 682, 18, 700, NULL, NULL, NULL, NULL, 338, 'abdd', 'def', 'Pritam@gmail.com', NULL, NULL, NULL, NULL, 'abcd street  ghybgvf grdredrdrdr hbfvtftftft ', '700063', 'kolkata', NULL, '9681295248', 'India', NULL, 2, NULL, NULL, 1580209161, 'arrival', 'cash', '1,267', 'app', NULL, NULL),
(10536, 1, 19, 1580211850, NULL, 1580169600, 1580256000, 1, 1, NULL, 700, NULL, 0, 700, 0, 700, NULL, NULL, NULL, NULL, 266, 'Sagar', 'Nayak', 'sagar@gmail.com', NULL, NULL, NULL, NULL, 'kolkata', '123456', 'kolkata', NULL, '8093329914', 'india', NULL, 1, NULL, NULL, 1580211850, 'arrival', 'cash', '1,267', 'app', NULL, NULL),
(10537, 1, 19, 1580278918, 1580300130, 1580342400, 1580428800, 1, 1, 0, 700, NULL, 0, 700, 0, 700, NULL, 700, NULL, NULL, 231, 'Traveler', 'Traveler', 'sonjoy.bhadra@met-technologies.com', 'met', '', '4444666', '44555', 'kolkata', '700004', 'kolkata', '', '9475312345', 'India', '', 4, NULL, NULL, NULL, 'arrival', 'cash_pay', '1,267', 'website', 'in', 'out'),
(10538, 1, 19, 1580289496, 1580299567, 1580256000, 1580342400, 1, 1, 0, 1600, NULL, 0, 1600, 288, 1888, NULL, 1888, NULL, NULL, 266, 'Sagar', 'Nayak', 'sagar@gmail.com', '', '', '', '', 'kolkata', '123456', 'kolkata', '', '8093329914', 'india', '', 4, NULL, NULL, 1580289496, 'arrival', 'cash', '1,267', 'app', 'in', NULL),
(10539, 1, 19, 1580295023, NULL, 1580256000, 1580342400, 1, 1, NULL, 1600, NULL, 0, 1600, 288, 1888, NULL, NULL, NULL, NULL, 266, 'Sagar', 'Nayak', 'sagar@gmail.com', NULL, NULL, NULL, NULL, 'kolkata', '123456', 'kolkata', NULL, '8093329914', 'india', NULL, 1, NULL, NULL, 1580295023, 'arrival', 'cash', '1,267', 'app', NULL, NULL),
(10540, 1, 19, 1580300489, NULL, 1580256000, 1580342400, 1, 1, NULL, 700, NULL, 0, 700, 0, 700, NULL, NULL, NULL, NULL, 232, 'Sharad', 'Bhaiya', 'sharad210@gmail.com', '', NULL, '', '', 'Arvind Tower, 242/1B, A.P.C.Road, 3rd Floor Flat - 14', '700004', 'kolkata', NULL, '8276343006', 'India', NULL, 1, NULL, NULL, 1580300489, 'arrival', 'cash', '1,267', 'app', NULL, NULL),
(10541, 1, 19, 1580305823, NULL, 1580256000, 1580342400, 1, 1, NULL, 682, NULL, 0, 682, 18, 700, NULL, NULL, NULL, NULL, 338, 'abdd', 'def', 'Pritam@gmail.com', NULL, NULL, NULL, NULL, 'abcd street  ghybgvf grdredrdrdr hbfvtftftft ', '700063', 'kolkata', NULL, '9681295248', 'India', NULL, 1, NULL, NULL, 1580305823, 'arrival', 'cash', '1,267', 'app', NULL, NULL),
(10542, 1, 19, 1580305832, NULL, 1580256000, 1580342400, 1, 1, NULL, 700, NULL, 0, 700, 0, 700, NULL, NULL, NULL, NULL, 338, 'abdd', 'def', 'Pritam@gmail.com', NULL, NULL, NULL, NULL, 'abcd street  ghybgvf grdredrdrdr hbfvtftftft ', '700063', 'kolkata', NULL, '1234567890', 'India', NULL, 1, NULL, NULL, 1580305832, 'arrival', 'cash', '1,267', 'app', NULL, NULL),
(10543, 1, 19, 1580306665, NULL, 1580256000, 1580342400, 1, 1, NULL, 682, NULL, 0, 682, 18, 700, NULL, NULL, NULL, NULL, 338, 'abdd', 'def', 'Pritam@gmail.com', NULL, NULL, NULL, NULL, 'abcd street  ghybgvf grdredrdrdr hbfvtftftft ', '700063', 'kolkata', NULL, '9681295248', 'India', NULL, 1, NULL, NULL, 1580306665, 'arrival', 'cash', '1,267', 'app', NULL, NULL);
INSERT INTO `pm_booking` (`id`, `id_destination`, `id_hotel`, `add_date`, `edit_date`, `from_date`, `to_date`, `nights`, `adults`, `children`, `amount`, `tourist_tax`, `discount`, `ex_tax`, `tax_amount`, `total`, `down_payment`, `paid`, `balance`, `extra_services`, `id_user`, `firstname`, `lastname`, `email`, `company`, `gstin`, `govid_type`, `govid`, `address`, `postcode`, `city`, `phone`, `mobile`, `country`, `comments`, `status`, `coupon_used`, `trans`, `payment_date`, `payment_option`, `payment_mode`, `users`, `source`, `checked_in`, `checked_out`) VALUES
(10544, 1, 19, 1580362519, NULL, 1580342400, 1580428800, 1, 1, NULL, 682, NULL, 0, 682, 18, 700, NULL, NULL, NULL, NULL, 338, 'abdd', 'def', 'Pritam@gmail.com', NULL, NULL, NULL, NULL, 'abcd street  ghybgvf grdredrdrdr hbfvtftftft ', '700063', 'kolkata', NULL, '1234567890', 'India', NULL, 1, NULL, NULL, 1580362519, 'arrival', 'cash', '1,267', 'app', NULL, NULL),
(10545, 1, 19, 1580362520, NULL, 1580342400, 1580428800, 1, 1, NULL, 700, NULL, 0, 700, 0, 700, NULL, NULL, NULL, NULL, 338, 'abdd', 'def', 'Pritam@gmail.com', NULL, NULL, NULL, NULL, 'abcd street  ghybgvf grdredrdrdr hbfvtftftft ', '700063', 'kolkata', NULL, '1234567890', 'India', NULL, 1, NULL, NULL, 1580362520, 'arrival', 'cash', '1,267', 'app', NULL, NULL),
(10546, 2, 24, 1580364087, NULL, 1580342400, 1580428800, 1, 1, NULL, 862, NULL, 0, 862, 18, 880, NULL, NULL, NULL, NULL, 338, 'abdd', 'def', 'Pritam@gmail.com', NULL, NULL, NULL, NULL, 'abcd street  ghybgvf grdredrdrdr hbfvtftftft ', '700063', 'kolkata', NULL, '9681295222', 'India', NULL, 1, NULL, NULL, 1580364087, 'arrival', 'cash', '1', 'app', NULL, NULL),
(10547, 1, 19, 1580369290, NULL, 1580342400, 1580428800, 1, 1, NULL, 1870, NULL, 0, 1870, 18, 1888, NULL, NULL, NULL, NULL, 338, 'abdd', 'def', 'Pritam@gmail.com', NULL, NULL, NULL, NULL, 'abcd street  ghybgvf grdredrdrdr hbfvtftftft ', '700063', 'kolkata', NULL, '1234567890', 'India', NULL, 1, NULL, NULL, 1580369290, 'arrival', 'cash', '1,267', 'app', NULL, NULL),
(10548, 1, 19, 1580369335, NULL, 1580342400, 1580428800, 1, 1, NULL, 1600, NULL, 0, 1600, 288, 1888, NULL, NULL, NULL, NULL, 338, 'abdd', 'def', 'Pritam@gmail.com', NULL, NULL, NULL, NULL, 'abcd street  ghybgvf grdredrdrdr hbfvtftftft ', '700063', 'kolkata', NULL, '1234567890', 'India', NULL, 1, NULL, NULL, 1580369335, 'arrival', 'cash', '1,267', 'app', NULL, NULL),
(10549, 1, 19, 1580369649, NULL, 1580342400, 1580428800, 1, 1, NULL, 682, NULL, 0, 682, 18, 700, NULL, NULL, NULL, NULL, 338, 'abdd', 'def', 'Pritam@gmail.com', NULL, NULL, NULL, NULL, 'abcd street  ghybgvf grdredrdrdr hbfvtftftft ', '700063', 'kolkata', NULL, '1234567890', 'India', NULL, 1, NULL, NULL, 1580369649, 'arrival', 'cash', '1,267', 'app', NULL, NULL),
(10550, 1, 20, 1580377433, NULL, 1580342400, 1580428800, 1, 2, 1, 1478.4, NULL, 0, 1478.4, 266.11, 1744.51, NULL, NULL, NULL, NULL, 338, 'abdd', 'def', 'Pritam@gmail.com', NULL, NULL, NULL, NULL, 'abcd street  ghybgvf grdredrdrdr hbfvtftftft ', '700063', 'kolkata', NULL, '1234567890', 'India', NULL, 1, NULL, NULL, 1580377433, 'arrival', 'cash', '1', 'app', NULL, NULL),
(10551, 1, 19, 1580382569, NULL, 1580342400, 1580428800, 1, 2, 1, 3286, NULL, 0, 3286, 18, 3304, NULL, NULL, NULL, NULL, 338, 'abdd', 'def', 'Pritam@gmail.com', NULL, NULL, NULL, NULL, 'abcd street  ghybgvf grdredrdrdr hbfvtftftft ', '700063', 'kolkata', NULL, '1234567890', 'India', NULL, 1, NULL, NULL, 1580382569, 'arrival', 'cash', '1,267', 'app', NULL, NULL),
(10552, 1, 19, 1580452134, NULL, 1580428800, 1580515200, 1, 1, NULL, 682, NULL, 0, 682, 18, 700, NULL, NULL, NULL, NULL, 338, 'abdd', 'def', 'Pritam@gmail.com', NULL, NULL, NULL, NULL, 'abcd street  ghybgvf grdredrdrdr hbfvtftftft ', '700063', 'kolkata', NULL, '9681295222', 'India', NULL, 1, NULL, NULL, 1580452134, 'arrival', 'cash', '1,267', 'app', NULL, NULL),
(10553, 2, 24, 1580452433, NULL, 1580428800, 1580515200, 1, 1, NULL, 862, NULL, 0, 862, 18, 880, NULL, NULL, NULL, NULL, 338, 'abdd', 'def', 'Pritam@gmail.com', NULL, NULL, NULL, NULL, 'abcd street  ghybgvf grdredrdrdr hbfvtftftft ', '700063', 'kolkata', NULL, '9681295222', 'India', NULL, 1, NULL, NULL, 1580452433, 'arrival', 'cash', '1', 'app', NULL, NULL),
(10554, 2, 24, 1580452494, NULL, 1580428800, 1580515200, 1, 2, 1, 880, NULL, 0, 880, 0, 880, NULL, NULL, NULL, NULL, 338, 'abdd', 'def', 'Pritam@gmail.com', NULL, NULL, NULL, NULL, 'abcd street  ghybgvf grdredrdrdr hbfvtftftft ', '700063', 'kolkata', NULL, '1234567890', 'India', NULL, 1, NULL, NULL, 1580452494, 'arrival', 'cash', '1', 'app', NULL, NULL),
(10555, 1, 19, 1580453147, NULL, 1580428800, 1580515200, 1, 1, 2, 682, NULL, 0, 682, 18, 700, NULL, NULL, NULL, NULL, 338, 'abdd', 'def', 'Pritam@gmail.com', NULL, NULL, NULL, NULL, 'abcd street  ghybgvf grdredrdrdr hbfvtftftft ', '700063', 'kolkata', NULL, '1234567890', 'India', NULL, 2, NULL, NULL, 1580453147, 'arrival', 'cash', '1,267', 'app', NULL, NULL),
(10556, 1, 19, 1580454343, NULL, 1580428800, 1580515200, 1, 1, 1, 682, NULL, 0, 682, 18, 700, NULL, NULL, NULL, NULL, 338, 'abdd', 'def', 'Pritam@gmail.com', NULL, NULL, NULL, NULL, 'abcd street  ghybgvf grdredrdrdr hbfvtftftft ', '700063', 'kolkata', NULL, '9681295248', 'India', NULL, 2, NULL, NULL, 1580454343, 'arrival', 'cash', '1,267', 'app', NULL, NULL),
(10557, 1, 19, 1580456495, 1580457240, 1580428800, 1580515200, 1, 1, 0, 700, NULL, 0, 700, 0, 700, NULL, 700, NULL, NULL, 304, 'Traveler', 'Traveler', 'qa@yopmail.com', 'Get', '', '', '', 'unitech', '700156', 'Kolkata', '', '1234567898', 'India', '', 4, NULL, NULL, NULL, 'arrival', 'cash_pay', '1,267', 'website', 'in', 'out'),
(10558, 3, 28, 1580461165, NULL, 1580428800, 1580515200, 1, 1, NULL, 3227, NULL, 0, 3227, 18, 3245, NULL, NULL, NULL, NULL, 338, 'abdd', 'def', 'Pritam@gmail.com', NULL, NULL, NULL, NULL, 'abcd street  ghybgvf grdredrdrdr hbfvtftftft ', '700063', 'kolkata', NULL, '1234567890', 'India', NULL, 1, NULL, NULL, 1580461165, 'arrival', 'cash', '1', 'app', NULL, NULL),
(10559, 3, 28, 1580461166, NULL, 1580428800, 1580515200, 1, 1, NULL, 2750, NULL, 0, 2750, 495, 3245, NULL, NULL, NULL, NULL, 338, 'abdd', 'def', 'Pritam@gmail.com', NULL, NULL, NULL, NULL, 'abcd street  ghybgvf grdredrdrdr hbfvtftftft ', '700063', 'kolkata', NULL, '1234567890', 'India', NULL, 1, NULL, NULL, 1580461166, 'arrival', 'cash', '1', 'app', NULL, NULL),
(10560, 1, 19, 1580472866, NULL, 1580428800, 1580515200, 1, 1, NULL, 682, NULL, 0, 682, 18, 700, NULL, NULL, NULL, NULL, 338, 'abdd', 'def', 'Pritam@gmail.com', NULL, NULL, NULL, NULL, 'abcd street  ghybgvf grdredrdrdr hbfvtftftft ', '700063', 'kolkata', NULL, '1234567890', 'India', NULL, 1, NULL, NULL, 1580472866, 'arrival', 'cash', '1,267', 'app', NULL, NULL),
(10561, 1, 19, 1580472891, NULL, 1580428800, 1580515200, 1, 1, NULL, 700, NULL, 0, 700, 0, 700, NULL, NULL, NULL, NULL, 338, 'abdd', 'def', 'Pritam@gmail.com', NULL, NULL, NULL, NULL, 'abcd street  ghybgvf grdredrdrdr hbfvtftftft ', '700063', 'kolkata', NULL, '1234567890', 'India', NULL, 1, NULL, NULL, 1580472891, 'arrival', 'cash', '1,267', 'app', NULL, NULL),
(10562, 1, 19, 1580472994, NULL, 1580515200, 1580601600, 1, 1, 0, 1600, NULL, 0, 1600, 288, 1888, NULL, NULL, NULL, NULL, 304, 'Traveler', 'Traveler', 'qa@yopmail.com', 'Get', '', '', '', 'unitech', '700156', 'Kolkata', '', '1234567898', 'India', '', 1, NULL, NULL, NULL, 'arrival', 'cash_pay', '1,267', 'website', NULL, NULL),
(10563, 1, 19, 1580473731, NULL, 1580428800, 1580515200, 1, 1, NULL, 682, NULL, 0, 682, 18, 700, NULL, NULL, NULL, NULL, 338, 'abdd', 'def', 'Pritam@gmail.com', NULL, NULL, NULL, NULL, 'abcd street  ghybgvf grdredrdrdr hbfvtftftft ', '700063', 'kolkata', NULL, '1234567890', 'India', NULL, 1, NULL, NULL, 1580473731, 'arrival', 'cash', '1,267', 'app', NULL, NULL),
(10564, 1, 19, 1580737024, 1580738772, 1580774400, 1580860800, 1, 1, 0, 700, NULL, 0, 700, 0, 700, NULL, 700, NULL, NULL, 231, 'Traveler', 'Traveler', 'sonjoy.bhadra@met-technologies.com', 'met', '', '4444666', '44555', 'kolkata', '700004', 'kolkata', '', '9475312345', 'India', '', 4, NULL, NULL, NULL, 'arrival', 'cash_pay', '1,267', 'website', 'in', 'out'),
(10565, 1, 19, 1580818098, 1580818533, 1580774400, 1580860800, 1, 1, 0, 700, NULL, 0, 700, 0, 700, NULL, 700, NULL, NULL, 272, 'QA', 'pal', 'qap@yopmail.com', '', '', '752', '953', 'unitech', '', '', '', '2', 'India', '', 4, NULL, NULL, NULL, 'arrival', 'cash_pay', '1,267', 'website', 'in', 'out'),
(10566, 1, 19, 1580894109, NULL, 1580860800, 1580947200, 1, 1, NULL, 700, NULL, 0, 700, 0, 700, NULL, NULL, NULL, NULL, 266, 'Sagar', 'Nayak', 'sagar@gmail.com', NULL, NULL, NULL, NULL, 'kolkata', '123456', 'kolkata', NULL, '8093329914', 'india', NULL, 1, NULL, NULL, 1580894109, 'arrival', 'cash', '1,267', 'app', NULL, NULL),
(10567, 1, 19, 1580982025, 1580983333, 1580947200, 1581033600, 1, 1, 1, 700, NULL, 0, 700, 0, 700, NULL, 700, NULL, NULL, 347, 'Traveler', 'Traveler', 'ios@yopmail.com', '', '', '', '', 'unitech', '', '', '', '1231231231', '', '', 4, NULL, NULL, 1580982025, 'arrival', 'cash', '1,267', 'app', 'in', 'out'),
(10568, 1, 19, 1580982029, NULL, 1580947200, 1581033600, 1, 1, 1, 700, NULL, 0, 700, 0, 700, NULL, NULL, NULL, NULL, 347, 'Traveler', 'Traveler', 'ios@yopmail.com', NULL, NULL, NULL, NULL, 'gg', '22', 'hh', NULL, '1231231231', 'ct', NULL, 2, NULL, NULL, 1580982029, 'arrival', 'cash', '1,267', 'app', NULL, NULL),
(10569, 1, 19, 1581055494, NULL, 1581033600, 1581120000, 1, 1, NULL, 700, NULL, 0, 700, 0, 700, NULL, NULL, NULL, NULL, 338, 'abdd', 'def', 'Pritam@gmail.com', NULL, NULL, NULL, NULL, 'abcd street  ghybgvf grdredrdrdr hbfvtftftft ', '700063', 'kolkata', NULL, '1234567890', 'India', NULL, 1, NULL, NULL, 1581055494, 'arrival', 'cash', '1,267', 'app', NULL, NULL),
(10570, 1, 19, 1581062665, NULL, 1581033600, 1581120000, 1, 1, NULL, 1600, NULL, 0, 1600, 0, 1600, NULL, NULL, NULL, NULL, 338, 'abdd', 'def', 'Pritam@gmail.com', NULL, NULL, NULL, NULL, 'abcd street  ghybgvf grdredrdrdr hbfvtftftft ', '700063', 'kolkata', NULL, '1234567890', 'India', NULL, 1, NULL, NULL, 1581062665, 'arrival', 'cash', '1,267', 'app', NULL, NULL),
(10571, 1, 22, 1581497163, NULL, 1581465600, 1581552000, 1, 1, NULL, 825, NULL, 0, 825, 0, 825, NULL, NULL, NULL, NULL, 347, 'Traveler', 'Traveler', 'ios@yopmail.com', NULL, NULL, NULL, NULL, 'gg', '22', 'hh', NULL, '1231231231', 'ct', NULL, 2, NULL, NULL, 1581497163, 'arrival', 'cash', '1', 'app', NULL, NULL),
(10572, 1, 22, 1581497169, NULL, 1581465600, 1581552000, 1, 1, NULL, 825, NULL, 0, 825, 0, 825, NULL, NULL, NULL, NULL, 347, 'Traveler', 'Traveler', 'ios@yopmail.com', NULL, NULL, NULL, NULL, 'gg', '22', 'hh', NULL, '1231231231', 'ct', NULL, 1, NULL, NULL, 1581497169, 'arrival', 'cash', '1', 'app', 'in', NULL),
(10573, 2, 23, 1581597807, NULL, 1581552000, 1581638400, 1, 2, NULL, 2090, NULL, 0, 2090, 0, 2090, NULL, NULL, NULL, NULL, 232, 'Sharad', 'Bhaiya', 'sharad210@gmail.com', '', NULL, '', '', 'Arvind Tower, 242/1B, A.P.C.Road, 3rd Floor Flat - 14', '700004', 'kolkata', NULL, '8276343006', 'India', NULL, 1, NULL, NULL, 1581597807, 'arrival', 'cash', '1', 'app', NULL, NULL),
(10574, 1, 20, 1581925892, NULL, 1581897600, 1581984000, 1, 1, NULL, 704, NULL, 0, 704, 0, 704, NULL, NULL, NULL, NULL, 347, 'Traveler', 'Traveler', 'ios@yopmail.com', NULL, NULL, NULL, NULL, 'gg', '22', 'hh', NULL, '1231231231', 'ct', NULL, 1, NULL, NULL, 1581925892, 'arrival', 'cash', '1', 'app', NULL, NULL),
(10575, 1, 19, 1582101159, 1582101249, 1582070400, 1582156800, 1, 1, 0, 700, NULL, 0, 700, 0, 700, NULL, 700, NULL, NULL, 232, 'Sharad', 'Bhaiya', 'sharad210@gmail.com', 'MET SEZ', '', '', '', 'Arvind Tower, 242/1B, A.P.C.Road, 3rd Floor Flat - 14', '700004', 'kolkata', '', '8276343006', 'India', '', 4, NULL, NULL, NULL, 'arrival', 'cash_pay', '1,267', 'website', 'in', 'out'),
(10576, 1, 19, 1582525742, NULL, 1582502400, 1582588800, 1, 1, 0, 700, NULL, 0, 700, 0, 700, NULL, NULL, NULL, NULL, 231, 'Traveler', 'Traveler', 'sonjoy.bhadra@met-technologies.com', 'met', '', '4444666', '44555', 'kolkata', '700004', 'kolkata', '', '9475312345', 'India', '', 2, NULL, NULL, NULL, 'arrival', 'cash_pay', '1,267', 'website', NULL, NULL),
(10577, 1, 19, 1582626839, 1582627027, 1582588800, 1582675200, 1, 1, 0, 700, NULL, 0, 700, 0, 700, NULL, 700, NULL, NULL, 229, 'Sharad', 'Bhaiya', 'sharad210@gmail.com', '', '', 'ABC', 'ABCD', 'Arvind Tower, 242/1B, A.P.C.Road, 3rd Floor Flat - 14', '700004', 'Kolkata', '', '7827633006', 'India', '', 4, NULL, NULL, NULL, 'arrival', 'cash_pay', '1,267', 'admin', 'in', NULL),
(10578, 1, 19, 1582627234, NULL, 1582588800, 1582675200, 1, 1, NULL, 1888, NULL, 0, 1600, 288, 1888, NULL, NULL, NULL, NULL, 229, 'Sharad', 'Bhaiya', 'sharad210@gmail.com', '', NULL, 'ABC', 'ABCD', 'Arvind Tower, 242/1B, A.P.C.Road, 3rd Floor Flat - 14', '700004', 'Kolkata', NULL, '7827633006', 'India', NULL, 1, NULL, NULL, NULL, 'arrival', 'cash_pay', '1,267', 'admin', 'in', NULL),
(10579, 2, 24, 1583325377, NULL, 1583280000, 1583366400, 1, 1, 0, 880, NULL, 0, 880, 0, 880, NULL, NULL, NULL, NULL, 231, 'Traveler', 'Traveler', 'sonjoy.bhadra@met-technologies.com', 'met', '', '4444666', '44555', 'kolkata', '700004', 'kolkata', '', '9475312345', 'India', '', 2, NULL, NULL, NULL, 'arrival', 'cash_pay', '1', 'website', NULL, NULL),
(10580, 3, 27, 1583325815, NULL, 1583280000, 1583366400, 1, 1, 0, 770, NULL, 0, 770, 0, 770, NULL, NULL, NULL, NULL, 231, 'Traveler', 'Traveler', 'sonjoy.bhadra@met-technologies.com', 'met', '', '4444666', '44555', 'kolkata', '700004', 'kolkata', '', '9475312345', 'India', '', 2, NULL, NULL, NULL, 'arrival', 'cash_pay', '1', 'website', NULL, NULL),
(10581, 1, 19, 1583326020, NULL, 1583280000, 1583366400, 1, 1, 0, 1600, NULL, 0, 1600, 288, 1888, NULL, NULL, NULL, NULL, 231, 'Traveler', 'Traveler', 'sonjoy.bhadra@met-technologies.com', 'met', '', '4444666', '44555', 'kolkata', '700004', 'kolkata', '', '9475312345', 'India', '', 2, NULL, NULL, NULL, 'arrival', 'cash_pay', '1,267', 'website', NULL, NULL),
(10582, 2, 24, 1583326454, NULL, 1583280000, 1583366400, 1, 1, 0, 880, NULL, 0, 880, 0, 880, NULL, NULL, NULL, NULL, 231, 'Traveler', 'Traveler', 'sonjoy.bhadra@met-technologies.com', 'met', '', '4444666', '44555', 'kolkata', '700004', 'kolkata', '', '9475312345', 'India', '', 2, NULL, NULL, NULL, 'arrival', 'cash_pay', '1', 'website', NULL, NULL),
(10583, 1, 19, 1583413332, NULL, 1583366400, 1583452800, 1, 1, 0, 700, NULL, 0, 700, 0, 700, NULL, NULL, NULL, NULL, 233, 'Traveler', 'Traveler', 'qa2210@yopmail.com', 'test', '', '123456', '123456', '123 testdrive', '700056', 'kolkata', '', '9830256890', 'India', '', 1, NULL, NULL, NULL, 'arrival', 'cash_pay', '1,267', 'website', 'in', NULL),
(10584, 2, 24, 1583474433, NULL, 1583452800, 1583539200, 1, 1, 0, 880, NULL, 0, 880, 0, 880, NULL, NULL, NULL, NULL, 233, 'Traveler', 'Traveler', 'qa2210@yopmail.com', 'test', '', '123456', '123456', '123 testdrive', '700056', 'kolkata', '', '9830256890', 'India', '', 1, NULL, NULL, NULL, 'arrival', 'cash_pay', '1', 'website', NULL, NULL),
(10585, 3, 27, 1583474562, NULL, 1583539200, 1583625600, 1, 1, 0, 770, NULL, 0, 770, 0, 770, NULL, NULL, NULL, NULL, 233, 'Traveler', 'Traveler', 'qa2210@yopmail.com', 'test', '', '123456', '123456', '123 testdrive', '700056', 'kolkata', '', '9830256890', 'India', '', 1, NULL, NULL, NULL, 'arrival', 'cash_pay', '1', 'website', NULL, NULL),
(10586, 1, 19, 1583930688, NULL, 1583884800, 1583971200, 1, 1, NULL, 1600, NULL, 0, 1600, 0, 1600, NULL, NULL, NULL, NULL, 308, 'Traveler', 'Traveler', 'superqa@yopmail.com', NULL, NULL, NULL, NULL, ' ', ' ', ' ', NULL, '7980028645', ' ', NULL, 1, NULL, NULL, 1583930688, 'arrival', 'cash', '1,267', 'app', NULL, NULL),
(10587, 1, 19, 1584094869, 1584094957, 1584057600, 1584144000, 1, 1, 0, 700, NULL, 0, 700, 0, 700, NULL, 700, NULL, NULL, 229, 'Sharad', 'Bhaiya', 'sharad210@gmail.com', '', '', 'ABC', 'ABCD', 'Arvind Tower, 242/1B, A.P.C.Road, 3rd Floor Flat - 14', '700004', 'Kolkata', '', '07827633006', 'India', '', 4, NULL, NULL, NULL, 'wallet', 'credit_card', '1,267', 'admin', NULL, NULL),
(10588, 1, 19, 1591876663, NULL, 1591833600, 1591920000, 1, 1, 0, 700, NULL, 0, 700, 0, 700, NULL, NULL, NULL, NULL, 231, 'Traveler', 'Traveler', 'sonjoy.bhadra@met-technologies.com', 'met', '', '4444666', '44555', 'kolkata', '700004', 'kolkata', '', '9475312345', 'India', '', 1, NULL, NULL, NULL, 'arrival', 'cash_pay', '1,267', 'website', NULL, NULL),
(10589, 1, 19, 1595636144, 1596188352, 1595635200, 1595721600, 1, 1, 0, 1600, NULL, 0, 1600, 0, 1600, NULL, 1600, NULL, NULL, 231, 'Traveler', 'Traveler', 'sonjoy.bhadra@met-technologies.com', '4444666', '', '4444666', '44555', 'kolkata', '700004', 'kolkata', '', '9475312345', 'India', '', 4, NULL, NULL, 1595636144, 'arrival', 'cash', '1,267', 'app', NULL, NULL),
(10590, 1, 19, 1596122982, NULL, 1596067200, 1596153600, 1, 2, 0, 1760, NULL, 10, 1750, 315, 2065, NULL, NULL, NULL, NULL, 352, 'Traveler', 'Traveler', 'qa21@yopmail.com', '', '', '', '', '', '', '', '', '', 'India', '', 1, NULL, NULL, NULL, 'arrival', 'cash_pay', '1,267', 'website', 'in', NULL),
(10591, 3, 30, 1596185276, 1596188989, 1596153600, 1596240000, 1, 1, 0, 880, NULL, 0, 880, 0, 880, NULL, 880, NULL, NULL, 352, 'Traveler', 'Traveler', 'qa21@yopmail.com', '', '', '', '', 'N Dum', '', '', '', '', 'India', '', 4, NULL, NULL, NULL, 'arrival', 'cash_pay', '1', 'website', 'in', 'out'),
(10592, 1, 19, 1596190994, NULL, 1596153600, 1596240000, 1, 1, NULL, 1876.2, NULL, 10, 1590, 286.2, 1876.2, NULL, NULL, NULL, NULL, 352, 'Protiek', 'QA', 'qa21@yopmail.com', 'Met', NULL, 'qagovid', 'qagovidno', 'North Dumdum', '700061', 'Kolkata', '', '7980028622', 'India', NULL, 4, NULL, 'qatid', 1596190994, 'arrival', 'cash_pay', '1,267', 'admin', NULL, NULL),
(10593, 3, 29, 1596440606, NULL, 1596412800, 1596499200, 1, 1, NULL, 1485, NULL, 0, 1485, 0, 1485, NULL, NULL, NULL, NULL, 353, 'Traveler', 'Traveler', 'qaps@yopmail.com', NULL, NULL, NULL, NULL, ' ', ' ', ' ', NULL, '1234567891', ' ', NULL, 1, NULL, NULL, 1596440606, 'arrival', 'cash', '1', 'app', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `pm_booking_activity`
--

CREATE TABLE `pm_booking_activity` (
  `id` int(11) NOT NULL,
  `id_booking` int(11) NOT NULL,
  `id_activity` int(11) NOT NULL,
  `title` varchar(250) DEFAULT NULL,
  `children` int(11) DEFAULT '0',
  `adults` int(11) DEFAULT '0',
  `duration` varchar(50) DEFAULT NULL,
  `amount` double DEFAULT '0',
  `ex_tax` double DEFAULT '0',
  `tax_rate` double DEFAULT '0',
  `date` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `pm_booking_cancel`
--

CREATE TABLE `pm_booking_cancel` (
  `id` int(11) NOT NULL,
  `id_booking` int(11) DEFAULT NULL,
  `id_room` int(11) DEFAULT NULL,
  `cancel_type` varchar(20) DEFAULT NULL,
  `reason` text,
  `days` int(11) DEFAULT NULL,
  `rooms` text,
  `days_before` int(11) DEFAULT NULL,
  `booking_amount` float DEFAULT NULL,
  `refund_charge` float DEFAULT NULL,
  `refund_amount` float DEFAULT NULL,
  `cancel_element` varchar(255) DEFAULT NULL,
  `added_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `pm_booking_cancel`
--

INSERT INTO `pm_booking_cancel` (`id`, `id_booking`, `id_room`, `cancel_type`, `reason`, `days`, `rooms`, `days_before`, `booking_amount`, `refund_charge`, `refund_amount`, `cancel_element`, `added_date`) VALUES
(1, 10582, 43, 'full', 'Change plan', NULL, NULL, NULL, 880, 0, 880, NULL, '2020-03-04 07:54:31');

-- --------------------------------------------------------

--
-- Table structure for table `pm_booking_history`
--

CREATE TABLE `pm_booking_history` (
  `id` int(11) NOT NULL,
  `id_destination` int(11) DEFAULT '0',
  `id_hotel` int(11) DEFAULT NULL,
  `add_date` int(11) DEFAULT NULL,
  `edit_date` int(11) DEFAULT NULL,
  `from_date` int(11) DEFAULT NULL,
  `to_date` int(11) DEFAULT NULL,
  `nights` int(11) DEFAULT '1',
  `adults` int(11) DEFAULT '1',
  `children` int(11) DEFAULT '1',
  `amount` float DEFAULT NULL,
  `tourist_tax` float DEFAULT NULL,
  `discount` float DEFAULT NULL,
  `ex_tax` float DEFAULT NULL,
  `tax_amount` float DEFAULT NULL,
  `total` float DEFAULT NULL,
  `down_payment` float DEFAULT NULL,
  `paid` float DEFAULT NULL,
  `balance` float DEFAULT NULL,
  `extra_services` text,
  `id_user` int(11) DEFAULT NULL,
  `firstname` varchar(50) DEFAULT NULL,
  `lastname` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `company` varchar(50) DEFAULT NULL,
  `gstin` varchar(50) DEFAULT NULL,
  `govid_type` varchar(50) DEFAULT NULL,
  `govid` varchar(50) DEFAULT NULL,
  `address` varchar(250) DEFAULT NULL,
  `postcode` varchar(10) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `mobile` varchar(50) DEFAULT NULL,
  `country` varchar(100) NOT NULL,
  `comments` text,
  `status` int(11) DEFAULT '1',
  `trans` varchar(50) DEFAULT NULL,
  `payment_date` int(11) DEFAULT NULL,
  `payment_option` varchar(250) DEFAULT NULL,
  `payment_mode` varchar(250) DEFAULT NULL,
  `users` text,
  `source` enum('website','admin') DEFAULT 'website',
  `checked_in` enum('no','in') DEFAULT 'no',
  `checked_out` enum('no','out') DEFAULT 'no'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pm_booking_history`
--

INSERT INTO `pm_booking_history` (`id`, `id_destination`, `id_hotel`, `add_date`, `edit_date`, `from_date`, `to_date`, `nights`, `adults`, `children`, `amount`, `tourist_tax`, `discount`, `ex_tax`, `tax_amount`, `total`, `down_payment`, `paid`, `balance`, `extra_services`, `id_user`, `firstname`, `lastname`, `email`, `company`, `gstin`, `govid_type`, `govid`, `address`, `postcode`, `city`, `phone`, `mobile`, `country`, `comments`, `status`, `trans`, `payment_date`, `payment_option`, `payment_mode`, `users`, `source`, `checked_in`, `checked_out`) VALUES
(10059, 1, 19, 1570776483, 1573632202, 1570752000, 1570838400, 1, 1, 0, 700, NULL, 0, 700, 0, 700, NULL, 0, NULL, NULL, NULL, 'Sharad', 'BHaiya', 'sharad210@gmail.com', '', '', '', '', 'Arvind Tower, 242/1B, A.P.C.Road, 3rd Floor Flat - 14', '700004', 'Kolkata', '', '7827633006', 'India', '', 1, NULL, NULL, 'arrival', 'cash_pay', '1', 'website', NULL, NULL),
(10060, 1, 19, 1570799270, 1573633077, 1570752000, 1570838400, 1, 1, 0, 700, NULL, 0, 700, 0, 700, NULL, 0, NULL, NULL, NULL, 'Traveler', 'Traveler', 'qa@yopmail.com', '', '', '', '', 'test', '', 'sad', '', '', 'India', '', 1, NULL, NULL, 'arrival', 'cash_pay', '1', 'website', NULL, NULL),
(10061, 1, 19, 1571732511, 1573633699, 1571702400, 1571788800, 1, 1, 0, 700, NULL, 0, 700, 0, 700, NULL, 0, NULL, NULL, 233, 'Traveler', 'Traveler', 'qa2210@yopmail.com', '', '', '', '', 'test', '9475555', '', '', '', 'India', '', 1, NULL, NULL, 'arrival', 'cash_pay', '1', 'website', NULL, NULL),
(10062, 1, 19, 1572613037, 1573637372, 1572566400, 1572652800, 1, 1, 0, 700, NULL, 70, 700, 0, 630, NULL, 0, NULL, NULL, 232, 'Traveler', 'Traveler', 'sharad210@gmail.com', '', '', '', '', 'test', '', 'kolkata', '', '', 'India', '', 1, NULL, NULL, 'arrival', 'cash_pay', '1', 'website', NULL, NULL),
(10063, 2, 23, 1572620990, 1573639667, 1572566400, 1572652800, 1, 2, 0, 1650, NULL, 165, 1650, 267.3, 1752.3, NULL, 0, NULL, NULL, 232, 'Traveler', 'Traveler', 'sharad210@gmail.com', '', '', '', '', 'test', '', 'kolkata', '', '', 'India', '', 4, NULL, NULL, 'arrival', 'cash_pay', '1', 'website', 'in', 'out'),
(10064, 1, 19, 1573716778, NULL, 1573862400, 1573948800, 1, 1, 0, 700, NULL, 0, 700, 0, 700, NULL, NULL, NULL, NULL, 231, 'Traveler', 'Traveler', '', '', '', '', '', '', '', '', '', '9475359786', 'India', '', 1, NULL, NULL, 'arrival', 'cash_pay', '1', 'website', NULL, NULL),
(10065, 1, 20, 1573719013, NULL, 1573862400, 1573948800, 1, 1, 0, 704, NULL, 0, 704, 0, 704, NULL, NULL, NULL, NULL, 231, 'Traveler', 'Traveler', '', '', '', '', '', '', '', '', '', '9475359786', 'India', '', 1, NULL, NULL, 'arrival', 'cash_pay', '1', 'website', NULL, NULL),
(10066, 1, 22, 1573743895, 1573807010, 1574380800, 1574553600, 2, 2, 0, 3300, NULL, 0, 3300, 594, 3894, NULL, 0, NULL, NULL, 231, 'Traveler', 'Traveler', 'test123@fiteser.com', '', '', '', '', 'test', '', '', '', '9475359786', 'India', '', 4, NULL, NULL, 'arrival', 'cash_pay', '1', 'website', 'in', 'out'),
(10067, 1, 19, 1573812485, NULL, 1573776000, 1573862400, 1, 1, 0, 700, NULL, 0, 700, 0, 700, NULL, NULL, NULL, NULL, 231, 'Traveler', 'Traveler', 'sonjoy.bhadra@met-technologies.com', '', '', '', '', '', '', '', '', '9475359786', 'India', '', 1, NULL, NULL, 'arrival', 'cash_pay', '1', 'website', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `pm_booking_offer`
--

CREATE TABLE `pm_booking_offer` (
  `id` int(11) NOT NULL,
  `id_booking` int(11) NOT NULL,
  `id_offer` int(11) NOT NULL,
  `id_room` int(11) DEFAULT NULL,
  `id_hotel` int(11) DEFAULT NULL,
  `title` varchar(250) DEFAULT NULL,
  `facilities` text,
  `num` varchar(10) DEFAULT NULL,
  `children` int(11) DEFAULT '0',
  `adults` int(11) DEFAULT '0',
  `amount` double DEFAULT '0',
  `ex_tax` double DEFAULT '0',
  `tax_rate` double DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `pm_booking_payment`
--

CREATE TABLE `pm_booking_payment` (
  `id` int(11) NOT NULL,
  `id_booking` int(11) NOT NULL,
  `descr` varchar(100) DEFAULT NULL,
  `method` varchar(100) DEFAULT NULL,
  `amount` double DEFAULT '0',
  `trans` varchar(100) DEFAULT NULL,
  `payu_response` longtext,
  `date` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pm_booking_payment`
--

INSERT INTO `pm_booking_payment` (`id`, `id_booking`, `descr`, `method`, `amount`, `trans`, `payu_response`, `date`) VALUES
(1, 10075, 'Payment has been success by cash_pay', 'cash', 700, 'dasdsad', NULL, 1574640000),
(177, 10271, 'will be paid at hotel on arrival', 'cash', 700, 'cash', NULL, 1576653814),
(178, 10272, 'Payment has been success by cash_pay', 'cash', 4720, 'cash', NULL, 1576655102),
(183, 10277, 'will be paid at hotel on arrival', 'cash', 700, 'cash', NULL, 1576668252),
(184, 10278, 'will be paid at hotel on arrival', 'cash', 630, 'cash', NULL, 1576668680),
(185, 10279, 'will be paid at hotel on arrival', 'cash', 630, 'cash', NULL, 1576669519),
(186, 10280, 'will be paid at hotel on arrival', 'cash', 1888, 'cash', NULL, 1576671403),
(187, 10281, 'will be paid at hotel on arrival', 'cash', 700, 'cash', NULL, 1576627200),
(188, 10282, 'will be paid at hotel on arrival', 'cash', 630, 'cash', NULL, 1576673007),
(189, 10283, 'will be paid at hotel on arrival', 'cash', 2442.6, 'cash', NULL, 1576677775),
(190, 10284, 'will be paid at hotel on arrival', 'cash', 700, 'cash', NULL, 1576734612),
(191, 10285, 'will be paid at hotel on arrival', 'cash', 2714, 'cash', NULL, 1576735299),
(192, 10286, 'will be paid at hotel on arrival', 'cash', 704, 'cash', NULL, 1576737480),
(193, 10287, 'will be paid at hotel on arrival', 'cash', 630, 'cash', NULL, 1576741664),
(194, 10288, 'will be paid at hotel on arrival', 'cash', 630, 'cash', NULL, 1576742440),
(195, 10289, 'will be paid at hotel on arrival', 'cash', 1888, 'cash', NULL, 1576743637),
(196, 10292, 'will be paid at hotel on arrival', 'cash', 704, 'cash', NULL, 1576762903),
(197, 10294, 'will be paid at hotel on arrival', 'cash', 700, 'cash', NULL, 1576764926),
(198, 10295, 'will be paid at hotel on arrival', 'cash', 700, 'cash', NULL, 1576765004),
(199, 10296, 'will be paid at hotel on arrival', 'cash', 700, 'cash', NULL, 1576818857),
(202, 10299, 'will be paid at hotel on arrival', 'cash', 700, 'cash', NULL, 1576822916),
(203, 10300, 'will be paid at hotel on arrival', 'cash', 700, 'cash', NULL, 1576826068),
(204, 10301, 'will be paid at hotel on arrival', 'cash', 700, 'cash', NULL, 1576826389),
(205, 10302, 'will be paid at hotel on arrival', 'cash', 1888, 'cash', NULL, 1576830348),
(206, 10303, 'will be paid at hotel on arrival', 'cash', 1888, 'cash', NULL, 1576831361),
(207, 10304, 'will be paid at hotel on arrival', 'cash', 7552, 'cash', NULL, 1576831573),
(208, 10305, 'will be paid at hotel on arrival', 'cash', 1888, 'cash', NULL, 1576831607),
(209, 10306, 'will be paid at hotel on arrival', 'cash', 700, 'cash', NULL, 1576831744),
(210, 10307, 'will be paid at hotel on arrival', 'cash', 12227.16, 'cash', NULL, 1576833510),
(211, 10308, 'will be paid at hotel on arrival', 'cash', 700, 'cash', NULL, 1576843137),
(212, 10309, 'will be paid at hotel on arrival', 'cash', 2714, 'cash', NULL, 1576843404),
(213, 10310, 'will be paid at hotel on arrival', 'cash', 630, 'cash', NULL, 1576859319),
(214, 10311, 'will be paid at hotel on arrival', 'cash', 770, 'cash', NULL, 1576860721),
(215, 10312, 'will be paid at hotel on arrival', 'cash', 891, 'cash', NULL, 1577085531),
(216, 10313, 'will be paid at hotel on arrival', 'cash', 700, 'cash', NULL, 1577089609),
(217, 10314, 'will be paid at hotel on arrival', 'cash', 700, 'cash', NULL, 1577090519),
(218, 10315, 'Payment has been success by net_banking', 'net_banking', 700, 'Turct8965632', NULL, 1577093982),
(219, 10317, 'will be paid at hotel on arrival', 'cash', 700, 'cash', NULL, 1577095623),
(220, 10318, 'will be paid at hotel on arrival', 'cash', 704, 'cash', NULL, 1577096227),
(221, 10319, 'will be paid at hotel on arrival', 'cash', 704, 'cash', NULL, 1577096961),
(222, 10320, 'will be paid at hotel on arrival', 'cash', 704, 'cash', NULL, 1577097364),
(223, 10321, 'will be paid at hotel on arrival', 'cash', 700, 'cash', NULL, 1577097675),
(224, 10324, 'will be paid at hotel on arrival', 'cash', 3776, 'cash', NULL, 1577183403),
(225, 10326, 'will be paid at hotel on arrival', 'cash', 690, 'cash', NULL, 1577145600),
(226, 10327, 'will be paid at hotel on arrival', 'cash', 700, 'cash', NULL, 1577191734),
(227, 10328, 'will be paid at hotel on arrival', 'cash', 2714, 'cash', NULL, 1577353230),
(228, 10329, 'will be paid at hotel on arrival', 'cash', 704, 'cash', NULL, 1577353694),
(229, 10330, 'will be paid at hotel on arrival', 'cash', 1888, 'cash', NULL, 1577426886),
(230, 10331, 'will be paid at hotel on arrival', 'cash', 891, 'cash', NULL, 1577430769),
(231, 10332, 'will be paid at hotel on arrival', 'cash', 1888, 'cash', NULL, 1577434180),
(232, 10333, 'will be paid at hotel on arrival', 'cash', 704, 'cash', NULL, 1577434223),
(233, 10334, 'will be paid at hotel on arrival', 'cash', 891, 'cash', NULL, 1577434379),
(234, 10335, 'will be paid at hotel on arrival', 'cash', 704, 'cash', NULL, 1577434727),
(235, 10336, 'will be paid at hotel on arrival', 'cash', 1888, 'cash', NULL, 1577437971),
(236, 10337, 'will be paid at hotel on arrival', 'cash', 700, 'cash', NULL, 1577439249),
(237, 10338, 'will be paid at hotel on arrival', 'cash', 935, 'cash', NULL, 1577439966),
(238, 10339, 'will be paid at hotel on arrival', 'cash', 990, 'cash', NULL, 1577443089),
(239, 10340, 'will be paid at hotel on arrival', 'cash', 704, 'cash', NULL, 1577681838),
(240, 10341, 'will be paid at hotel on arrival', 'cash', 880, 'cash', NULL, 1577664000),
(241, 10342, 'will be paid at hotel on arrival', 'cash', 704, 'cash', NULL, 1577692898),
(242, 10343, 'will be paid at hotel on arrival', 'cash', 704, 'cash', NULL, 1577693432),
(243, 10344, 'will be paid at hotel on arrival', 'cash', 1947, 'cash', NULL, 1577774695),
(244, 10345, 'will be paid at hotel on arrival', 'cash', 4439.16, 'cash', NULL, 1577775523),
(245, 10346, 'will be paid at hotel on arrival', 'cash', 1888, 'cash', NULL, 1577782999),
(246, 10347, 'will be paid at hotel on arrival', 'cash', 704, 'cash', NULL, 1577783890),
(247, 10348, 'will be paid at hotel on arrival', 'cash', 704, 'cash', NULL, 1577784249),
(248, 10349, 'will be paid at hotel on arrival', 'cash', 700, 'cash', NULL, 1577785628),
(249, 10350, 'will be paid at hotel on arrival', 'cash', 704, 'cash', NULL, 1577787210),
(250, 10351, 'will be paid at hotel on arrival', 'cash', 700, 'cash', NULL, 1577787398),
(251, 10352, 'will be paid at hotel on arrival', 'cash', 1817.2, 'cash', NULL, 1577787511),
(252, 10353, 'will be paid at hotel on arrival', 'cash', 704, 'cash', NULL, 1577944089),
(253, 10354, 'will be paid at hotel on arrival', 'cash', 2147.6, 'cash', NULL, 1577962324),
(254, 10355, 'will be paid at hotel on arrival', 'cash', 700, 'cash', NULL, 1577967742),
(255, 10356, 'will be paid at hotel on arrival', 'cash', 700, 'cash', NULL, 1577968244),
(256, 10357, 'will be paid at hotel on arrival', 'cash', 700, 'cash', NULL, 1577970545),
(257, 10358, 'will be paid at hotel on arrival', 'cash', 1888, 'cash', NULL, 1577973284),
(258, 10359, 'will be paid at hotel on arrival', 'cash', 1947, 'cash', NULL, 1578031732),
(259, 10360, 'will be paid at hotel on arrival', 'cash', 1888, 'cash', NULL, 1578039640),
(260, 10361, 'will be paid at hotel on arrival', 'cash', 700, 'cash', NULL, 1578039700),
(261, 10362, 'will be paid at hotel on arrival', 'cash', 1170.4, 'cash', NULL, 1578040316),
(262, 10363, 'will be paid at hotel on arrival', 'cash', 700, 'cash', NULL, 1578043268),
(263, 10364, 'will be paid at hotel on arrival', 'cash', 4602, 'cash', NULL, 1578049604),
(264, 10365, 'will be paid at hotel on arrival', 'cash', 1888, 'cash', NULL, 1578055129),
(265, 10366, 'will be paid at hotel on arrival', 'cash', 1170.4, 'cash', NULL, 1578055887),
(266, 10367, 'will be paid at hotel on arrival', 'cash', 704, 'cash', NULL, 1578057403),
(267, 10368, 'will be paid at hotel on arrival', 'cash', 1888, 'cash', NULL, 1578057660),
(268, 10369, 'will be paid at hotel on arrival', 'cash', 700, 'cash', NULL, 1578058593),
(269, 10370, 'will be paid at hotel on arrival', 'cash', 1888, 'cash', NULL, 1578058773),
(270, 10371, 'will be paid at hotel on arrival', 'cash', 1888, 'cash', NULL, 1578058909),
(271, 10372, 'will be paid at hotel on arrival', 'cash', 1888, 'cash', NULL, 1578058913),
(272, 10373, 'will be paid at hotel on arrival', 'cash', 700, 'cash', NULL, 1578059455),
(273, 10374, 'will be paid at hotel on arrival', 'cash', 1888, 'cash', NULL, 1578060662),
(274, 10375, 'will be paid at hotel on arrival', 'cash', 4802.6, 'cash', NULL, 1578294262),
(275, 10376, 'will be paid at hotel on arrival', 'cash', 704, 'cash', NULL, 1578295108),
(276, 10377, 'will be paid at hotel on arrival', 'cash', 700, 'cash', NULL, 1578295548),
(277, 10378, 'will be paid at hotel on arrival', 'cash', 704, 'cash', NULL, 1578295833),
(278, 10379, 'will be paid at hotel on arrival', 'cash', 1888, 'cash', NULL, 1578297305),
(279, 10380, 'will be paid at hotel on arrival', 'cash', 700, 'cash', NULL, 1578300899),
(280, 10381, 'will be paid at hotel on arrival', 'cash', 700, 'cash', NULL, 1578303586),
(281, 10382, 'will be paid at hotel on arrival', 'cash', 700, 'cash', NULL, 1578303718),
(282, 10383, 'will be paid at hotel on arrival', 'cash', 700, 'cash', NULL, 1578304256),
(283, 10384, 'will be paid at hotel on arrival', 'cash', 700, 'cash', NULL, 1578307090),
(284, 10385, 'will be paid at hotel on arrival', 'cash', 700, 'cash', NULL, 1578308719),
(285, 10386, 'will be paid at hotel on arrival', 'cash', 700, 'cash', NULL, 1578312787),
(286, 10387, 'will be paid at hotel on arrival', 'cash', 700, 'cash', NULL, 1578315159),
(287, 10388, 'will be paid at hotel on arrival', 'cash', 1888, 'cash', NULL, 1578318971),
(288, 10389, 'will be paid at hotel on arrival', 'cash', 30208, 'cash', NULL, 1578374222),
(289, 10391, 'will be paid at hotel on arrival', 'cash', 700, 'cash', NULL, 1578381579),
(290, 10394, 'will be paid at hotel on arrival', 'cash', 630, 'cash', NULL, 1578388045),
(291, 10395, 'will be paid at hotel on arrival', 'cash', 700, 'cash', NULL, 1578389725),
(292, 10398, 'will be paid at hotel on arrival', 'cash', 700, 'cash', NULL, 1578392059),
(293, 10399, 'will be paid at hotel on arrival', 'cash', 1888, 'cash', NULL, 1578392348),
(294, 10402, 'will be paid at hotel on arrival', 'cash', 700, 'cash', NULL, 1578395227),
(295, 10403, 'will be paid at hotel on arrival', 'cash', 880, 'cash', NULL, 1578395412),
(296, 10404, 'will be paid at hotel on arrival', 'cash', 700, 'cash', NULL, 1578397344),
(297, 10405, 'will be paid at hotel on arrival', 'cash', 700, 'cash', NULL, 1578397851),
(298, 10406, 'will be paid at hotel on arrival', 'cash', 700, 'cash', NULL, 1578397882),
(299, 10407, 'will be paid at hotel on arrival', 'cash', 704, 'cash', NULL, 1578400178),
(300, 10411, 'will be paid at hotel on arrival', 'cash', 891, 'cash', NULL, 1578822010),
(301, 10413, 'will be paid at hotel on arrival', 'cash', 700, 'cash', NULL, 1578901087),
(302, 10414, 'will be paid at hotel on arrival', 'cash', 1888, 'cash', NULL, 1578873600),
(303, 10416, 'will be paid at hotel on arrival', 'cash', 700, 'cash', NULL, 1578909891),
(304, 10418, 'will be paid at hotel on arrival', 'cash', 700, 'cash', NULL, 1578917228),
(305, 10421, 'will be paid at hotel on arrival', 'cash', 1170.4, 'cash', NULL, 1578995178),
(306, 10422, 'Payment has been success by cash_pay', 'cash', 5840, 'cash', NULL, 1579070915),
(307, 10423, 'Payment has been success by cash_pay', 'cash', 5840, 'cash', NULL, 1579071483),
(308, 10424, 'Payment has been success by cash_pay', 'cash', 5840, 'cash', NULL, 1579073983),
(309, 10425, 'Payment has been success by cash_pay', 'cash', 5840, 'cash', NULL, 1579077644),
(310, 10428, 'Payment has been success by cash_pay', 'cash', 5840, 'cash', NULL, 1579084415),
(311, 10429, 'Payment has been success by cash_pay', 'cash', 5840, 'cash', NULL, 1579084921),
(312, 10430, 'will be paid at hotel on arrival', 'cash', 1652, 'cash', NULL, 1579085176),
(313, 10431, 'will be paid at hotel on arrival', 'cash', 4342.4, 'cash', NULL, 1579086980),
(314, 10432, 'will be paid at hotel on arrival', 'cash', 770, 'cash', NULL, 1579087732),
(315, 10433, 'will be paid at hotel on arrival', 'cash', 3079.8, 'cash', NULL, 1579089733),
(316, 10434, 'will be paid at hotel on arrival', 'cash', 825, 'cash', NULL, 1579099292),
(317, 10435, 'will be paid at hotel on arrival', 'cash', 3245, 'cash', NULL, 1579099590),
(318, 10436, 'will be paid at hotel on arrival', 'cash', 700, 'cash', NULL, 1579099627),
(319, 10437, 'will be paid at hotel on arrival', 'cash', 4141.8, 'cash', NULL, 1579166550),
(320, 10441, 'will be paid at hotel on arrival', 'cash', 3988.4, 'cash', NULL, 1579184519),
(321, 10442, 'will be paid at hotel on arrival', 'cash', 700, 'cash', NULL, 1579241777),
(322, 10443, 'will be paid at hotel on arrival', 'cash', 704, 'cash', NULL, 1579252222),
(323, 10444, 'will be paid at hotel on arrival', 'cash', 704, 'cash', NULL, 1579266192),
(324, 10445, 'will be paid at hotel on arrival', 'cash', 704, 'cash', NULL, 1579266237),
(325, 10446, 'will be paid at hotel on arrival', 'cash', 700, 'cash', NULL, 1579409674),
(326, 10447, 'will be paid at hotel on arrival', 'cash', 2714, 'cash', NULL, 1579518753),
(327, 10448, 'will be paid at hotel on arrival', 'cash', 3776, 'cash', NULL, 1579599830),
(328, 10449, 'will be paid at hotel on arrival', 'cash', 1232, 'cash', NULL, 1579614596),
(329, 10450, 'will be paid at hotel on arrival', 'cash', 3079.8, 'cash', NULL, 1579614739),
(330, 10451, 'will be paid at hotel on arrival', 'cash', 2725.8, 'cash', NULL, 1579683372),
(331, 10452, 'will be paid at hotel on arrival', 'cash', 4153.6, 'cash', NULL, 1579683656),
(332, 10453, 'will be paid at hotel on arrival', 'cash', 1888, 'cash', NULL, 1579687273),
(333, 10454, 'will be paid at hotel on arrival', 'card', 1888, 'cash', NULL, 1579651200),
(334, 10455, 'will be paid at hotel on arrival', 'cash', 1888, 'cash', NULL, 1579687831),
(335, 10456, 'will be paid at hotel on arrival', 'cash', 1888, 'cash', NULL, 1579691492),
(336, 10457, 'will be paid at hotel on arrival', 'cash', 1888, 'cash', NULL, 1579691570),
(337, 10458, 'will be paid at hotel on arrival', 'cash', 1888, 'cash', NULL, 1579691749),
(338, 10459, 'will be paid at hotel on arrival', 'cash', 1888, 'cash', NULL, 1579692918),
(339, 10460, 'Payment has been success by cash_pay', 'cash', 770, 'cash', NULL, 1579693089),
(340, 10461, 'Payment has been success by cash_pay', 'cash', 770, 'cash', NULL, 1579693311),
(341, 10462, 'Payment has been success by cash_pay', 'cash', 770, 'cash', NULL, 1579693468),
(342, 10463, 'Payment has been success by cash_pay', 'cash', 770, 'cash', NULL, 1579693475),
(343, 10464, 'Payment has been success by cash_pay', 'cash', 770, 'cash', NULL, 1579693774),
(344, 10465, 'Payment has been success by cash_pay', 'cash', 770, 'cash', NULL, 1579694082),
(345, 10466, 'Payment has been success by cash_pay', 'cash', 770, 'cash', NULL, 1579694815),
(346, 10467, 'Payment has been success by cash_pay', 'cash', 770, 'cash', NULL, 1579694877),
(347, 10468, 'Payment has been success by cash_pay', 'cash', 770, 'cash', NULL, 1579694965),
(348, 10469, 'Payment has been success by cash_pay', 'cash', 5840, 'cash', NULL, 1579695158),
(349, 10470, 'Payment has been success by cash_pay', 'cash', 5840, 'cash', NULL, 1579695568),
(350, 10471, 'Payment has been success by cash_pay', 'cash', 5840, 'cash', NULL, 1579753774),
(351, 10472, 'Payment has been success by cash_pay', 'cash', 5840, 'cash', NULL, 1579756067),
(352, 10473, 'Payment has been success by cash_pay', 'cash', 5840, 'cash', NULL, 1579757397),
(353, 10474, 'Payment has been success by cash_pay', 'cash', 700, 'cash', NULL, 1579762766),
(354, 10475, 'Payment has been success by cash_pay', 'cash', 700, 'cash', NULL, 1579762775),
(355, 10476, 'Payment has been success by cash_pay', 'cash', 700, 'cash', NULL, 1579762798),
(356, 10477, 'Payment has been success by cash_pay', 'cash', 700, 'cash', NULL, 1579762970),
(357, 10478, 'Payment has been success by cash_pay', 'cash', 880, 'cash', NULL, 1579765385),
(358, 10479, 'Payment has been success by cash_pay', 'cash', 880, 'cash', NULL, 1579766037),
(359, 10480, 'will be paid at hotel on arrival', 'cash', 880, 'cash', NULL, 1579766085),
(360, 10482, 'Payment has been success by cash_pay', 'cash', 880, 'cash', NULL, 1579766491),
(361, 10483, 'Payment has been success by cash_pay', 'cash', 880, 'cash', NULL, 1579766599),
(362, 10484, 'Payment has been success by cash_pay', 'cash', 880, 'cash', NULL, 1579766932),
(363, 10485, 'Payment has been success by cash_pay', 'cash', 880, 'cash', NULL, 1579766976),
(364, 10486, 'Payment has been success by cash_pay', 'cash', 880, 'cash', NULL, 1579767091),
(365, 10488, 'Payment has been success by cash_pay', 'cash', 880, 'cash', NULL, 1579768199),
(366, 10489, 'Payment has been success by cash_pay', 'cash', 880, 'cash', NULL, 1579768469),
(367, 10490, 'Payment has been success by cash_pay', 'cash', 880, 'cash', NULL, 1579768524),
(368, 10491, 'Payment has been success by cash_pay', 'cash', 880, 'cash', NULL, 1579768996),
(369, 10492, 'Payment has been success by cash_pay', 'cash', 880, 'cash', NULL, 1579770705),
(370, 10493, 'Payment has been success by cash_pay', 'cash', 770, 'cash', NULL, 1579770978),
(371, 10494, 'Payment has been success by cash_pay', 'cash', 770, 'cash', NULL, 1579771170),
(372, 10495, 'Payment has been success by cash_pay', 'cash', 770, 'cash', NULL, 1579771259),
(373, 10496, 'Payment has been success by cash_pay', 'cash', 770, 'cash', NULL, 1579772255),
(374, 10497, 'Payment has been success by cash_pay', 'cash', 770, 'cash', NULL, 1579772375),
(375, 10498, 'Payment has been success by cash_pay', 'cash', 770, 'cash', NULL, 1579773189),
(376, 10499, 'Payment has been success by cash_pay', 'cash', 770, 'cash', NULL, 1579773397),
(377, 10500, 'Payment has been success by cash_pay', 'cash', 770, 'cash', NULL, 1579773483),
(378, 10501, 'Payment has been success by cash_pay', 'cash', 770, 'cash', NULL, 1579773632),
(379, 10502, 'Payment has been success by cash_pay', 'cash', 770, 'cash', NULL, 1579774305),
(380, 10503, 'will be paid at hotel on arrival', 'cash', 700, 'cash', NULL, 1579780975),
(381, 10509, 'Payment has been success by cash_pay', 'cash', 700, 'cash', NULL, 1579867309),
(382, 10513, 'Payment has been success by cash_pay', 'cash', 700, 'cash', NULL, 1580108769),
(383, 10514, 'Payment has been success by cash_pay', 'cash', 5840, 'cash', NULL, 1580109838),
(384, 10515, 'Payment has been success by cash_pay', 'cash', 700, 'cash', NULL, 1580119170),
(385, 10516, 'Payment has been success by cash_pay', 'cash', 1888, 'cash', NULL, 1580120405),
(386, 10517, 'Payment has been success by cash_pay', 'cash', 880, 'cash', NULL, 1580120420),
(387, 10518, 'Payment has been success by cash_pay', 'cash', 0, 'cash', NULL, 1580121883),
(388, 10519, 'Payment has been success by cash_pay', 'cash', 700, 'cash', NULL, 1580131236),
(389, 10520, 'will be paid at hotel on arrival', 'cash', 700, 'cash', NULL, 1580131241),
(390, 10521, 'Payment has been success by cash_pay', 'cash', 700, 'cash', NULL, 1580191278),
(391, 10522, 'will be paid at hotel on arrival', 'cash', 1652, 'cash', NULL, 1580169600),
(392, 10523, 'will be paid at hotel on arrival', 'cash', 1888, 'cash', NULL, 1580169600),
(393, 10524, 'will be paid at hotel on arrival', 'cash', 880, 'cash', NULL, 1580198592),
(394, 10525, 'Payment has been success by cash_pay', 'cash', 770, 'cash', NULL, 1580198691),
(395, 10526, 'will be paid at hotel on arrival', 'cash', 1888, 'cash', NULL, 1580198880),
(396, 10527, 'Payment has been success by cash_pay', 'cash', 880, 'cash', NULL, 1580199164),
(397, 10528, 'Payment has been success by cash_pay', 'cash', 880, 'cash', NULL, 1580199316),
(398, 10529, 'Payment has been success by cash_pay', 'cash', 880, 'cash', NULL, 1580199497),
(399, 10530, 'Payment has been success by cash_pay', 'cash', 880, 'cash', NULL, 1580199598),
(400, 10531, 'Payment has been success by cash_pay', 'cash', 880, 'cash', NULL, 1580199756),
(401, 10534, 'will be paid at hotel on arrival', 'cash', 1888, 'cash', NULL, 1580207292),
(402, 10535, 'Payment has been success by cash_pay', 'cash', 700, 'cash', NULL, 1580209161),
(403, 10536, 'will be paid at hotel on arrival', 'cash', 700, 'cash', NULL, 1580211850),
(404, 10512, 'fsadfdf', 'cash', 1050, 'fdsfsdfsDF', NULL, 1579910400),
(405, 10538, 'will be paid at hotel on arrival', 'cash', 1888, 'cash', NULL, 1580256000),
(406, 10539, 'will be paid at hotel on arrival', 'cash', 1888, 'cash', NULL, 1580295023),
(407, 10537, 'fsadfdf', 'cash', 700, 'fdsfsdfsDF', NULL, 1580169600),
(408, 10540, 'will be paid at hotel on arrival', 'cash', 700, 'cash', NULL, 1580300489),
(409, 10541, 'Payment has been success by cash_pay', 'cash', 700, 'cash', NULL, 1580305823),
(410, 10542, 'will be paid at hotel on arrival', 'cash', 700, 'cash', NULL, 1580305832),
(411, 10543, 'Payment has been success by cash_pay', 'cash', 700, 'cash', NULL, 1580306666),
(412, 10544, 'Payment has been success by cash_pay', 'cash', 700, 'cash', NULL, 1580362519),
(413, 10545, 'will be paid at hotel on arrival', 'cash', 700, 'cash', NULL, 1580362521),
(414, 10546, 'Payment has been success by cash_pay', 'cash', 880, 'cash', NULL, 1580364087),
(415, 10547, 'Payment has been success by cash_pay', 'cash', 1888, 'cash', NULL, 1580369290),
(416, 10548, 'will be paid at hotel on arrival', 'cash', 1888, 'cash', NULL, 1580369335),
(417, 10549, 'Payment has been success by cash_pay', 'cash', 700, 'cash', NULL, 1580369649),
(418, 10550, 'will be paid at hotel on arrival', 'cash', 1744.51, 'cash', NULL, 1580377433),
(419, 10551, 'Payment has been success by cash_pay', 'cash', 3304, 'cash', NULL, 1580382570),
(420, 10552, 'Payment has been success by cash_pay', 'cash', 700, 'cash', NULL, 1580452134),
(421, 10553, 'Payment has been success by cash_pay', 'cash', 880, 'cash', NULL, 1580452433),
(422, 10554, 'will be paid at hotel on arrival', 'cash', 880, 'cash', NULL, 1580452494),
(423, 10555, 'Payment has been success by cash_pay', 'cash', 700, 'cash', NULL, 1580453147),
(424, 10556, 'Payment has been success by cash_pay', 'cash', 700, 'cash', NULL, 1580454343),
(425, 10557, '', 'cash', 700, '', NULL, 1580428800),
(426, 10558, 'Payment has been success by cash_pay', 'cash', 3245, 'cash', NULL, 1580461166),
(427, 10559, 'will be paid at hotel on arrival', 'cash', 3245, 'cash', NULL, 1580461166),
(428, 10560, 'Payment has been success by cash_pay', 'cash', 700, 'cash', NULL, 1580472866),
(429, 10561, 'will be paid at hotel on arrival', 'cash', 700, 'cash', NULL, 1580472892),
(430, 10563, 'Payment has been success by cash_pay', 'cash', 700, 'cash', NULL, 1580473731),
(431, 10564, 'ytfdytf', 'cash', 700, 'uyfyufvuyfvuyvf', NULL, 1580774400),
(432, 10565, 'qa des', 'cash', 700, 'lol123', NULL, 1580774400),
(433, 10566, 'will be paid at hotel on arrival', 'cash', 700, 'cash', NULL, 1580894109),
(434, 10567, 'Payment has been success by cash_pay', 'cash', 700, 'cash', NULL, 1580947200),
(435, 10568, 'will be paid at hotel on arrival', 'cash', 700, 'cash', NULL, 1580982029),
(436, 10569, 'will be paid at hotel on arrival', 'cash', 700, 'cash', NULL, 1581055494),
(437, 10570, 'Payment has been success by cash_pay', 'cash', 1600, 'cash', NULL, 1581062665),
(438, 10571, 'Payment has been success by cash_pay', 'cash', 825, 'cash', NULL, 1581497164),
(439, 10572, 'will be paid at hotel on arrival', 'cash', 825, 'cash', NULL, 1581497169),
(440, 10573, 'will be paid at hotel on arrival', 'cash', 2090, 'cash', NULL, 1581597807),
(441, 10574, 'will be paid at hotel on arrival', 'cash', 704, 'cash', NULL, 1581925892),
(442, 10575, '', 'cash', 700, 'test', NULL, 1582070400),
(443, 10577, '', 'cash', 700, '', NULL, 1582588800),
(444, 10586, 'will be paid at hotel on arrival', 'cash', 1600, 'cash', NULL, 1583930688),
(445, 10587, '', 'card', 700, '', NULL, 1584057600),
(446, 10589, 'will be paid at hotel on arrival', 'cash', 1600, 'cash', NULL, 1595635200),
(447, 10591, 'QA Patment', 'cash', 880, 'qatid', NULL, 1596153600),
(448, 10592, 'Payment has been success by cash_pay', 'cash_pay', 1876.2, 'qatid', NULL, 1596190994),
(449, 10593, 'will be paid at hotel on arrival', 'cash', 1485, 'cash', NULL, 1596440606);

-- --------------------------------------------------------

--
-- Table structure for table `pm_booking_room`
--

CREATE TABLE `pm_booking_room` (
  `id` int(11) NOT NULL,
  `id_booking` int(11) NOT NULL,
  `id_room` int(11) DEFAULT NULL,
  `id_hotel` int(11) DEFAULT NULL,
  `title` varchar(250) DEFAULT NULL,
  `num` varchar(10) DEFAULT NULL,
  `children` int(11) DEFAULT '0',
  `adults` int(11) DEFAULT '0',
  `amount` double DEFAULT '0',
  `ex_tax` double DEFAULT '0',
  `tax_rate` double DEFAULT '0',
  `chk` enum('1','2') NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pm_booking_room`
--

INSERT INTO `pm_booking_room` (`id`, `id_booking`, `id_room`, `id_hotel`, `title`, `num`, `children`, `adults`, `amount`, `ex_tax`, `tax_rate`, `chk`) VALUES
(106, 10059, 32, 19, 'Eco Stay - Classic', NULL, 0, 1, 700, 700, NULL, '1'),
(107, 10060, 32, 19, 'Eco Stay - Classic', '', 0, 1, 700, 700, NULL, '1'),
(108, 10061, 32, 19, 'Eco Stay - Classic', '', 0, 1, 700, 700, NULL, '1'),
(109, 10062, 32, 19, 'Eco Stay - Classic', '', 0, 1, 700, 700, NULL, '1'),
(110, 10063, 42, 23, 'Durbar Guest House - Saver', '', 0, 1, 825, 825, NULL, '1'),
(111, 10063, 42, 23, 'Durbar Guest House - Saver', '', 0, 1, 825, 825, NULL, '1'),
(112, 10064, 32, 19, 'Eco Stay - Classic', NULL, 0, 1, 700, 700, NULL, '1'),
(113, 10065, 34, 20, 'Eco Palace - Classic', NULL, 0, 1, 704, 704, NULL, '1'),
(114, 10066, 39, 22, 'Stay Inn - Saver', '', 0, 1, 1650, 1650, NULL, '1'),
(115, 10066, 39, 22, 'Stay Inn - Saver', '', 0, 1, 1650, 1650, NULL, '1'),
(116, 10067, 32, 19, 'Eco Stay - Classic', NULL, 0, 1, 700, 700, NULL, '1'),
(117, 10068, 39, 22, 'Stay Inn - Saver', NULL, 0, 1, 2475, 2475, NULL, '1'),
(118, 10068, 39, 22, 'Stay Inn - Saver', NULL, 0, 1, 2475, 2475, NULL, '1'),
(123, 10071, 32, 19, 'Eco Stay - Classic', NULL, 0, 1, 700, 700, NULL, '1'),
(124, 10072, 32, 19, 'Eco Stay - Classic', NULL, 0, 1, 2100, 2100, NULL, '1'),
(125, 10073, 32, 19, 'Eco Stay - Classic', NULL, 0, 1, 1400, 1400, NULL, '1'),
(126, 10074, 44, 25, 'Padmavati - Classic', NULL, 0, 1, 935, 935, NULL, '1'),
(127, 10075, 32, 19, 'Eco Stay - Classic', '', 0, 1, 700, 700, NULL, '1'),
(128, 10076, 46, 26, 'Zapper Guest House - Saver', NULL, 0, 1, 825, 825, NULL, '1'),
(129, 10076, 45, 26, 'Zapper Guest House - Classic', NULL, 0, 1, 880, 880, NULL, '1'),
(130, 10076, 45, 26, 'Zapper Guest House - Classic', NULL, 0, 1, 880, 880, NULL, '1'),
(131, 10077, 46, 26, 'Zapper Guest House - Saver', NULL, 0, 1, 1650, 1650, NULL, '1'),
(132, 10077, 45, 26, 'Zapper Guest House - Classic', NULL, 0, 1, 1760, 1760, NULL, '1'),
(133, 10077, 45, 26, 'Zapper Guest House - Classic', NULL, 0, 1, 1760, 1760, NULL, '1'),
(134, 10078, 32, 19, 'Eco Stay - Classic', NULL, 0, 1, 1400, 1400, NULL, '1'),
(135, 10078, 32, 19, 'Eco Stay - Classic', NULL, 0, 1, 1400, 1400, NULL, '1'),
(136, 10078, 33, 19, 'Eco Stay - Deluxe', NULL, 0, 1, 3200, 3200, NULL, '1'),
(137, 10079, 35, 21, 'Eco Inn - Classic', NULL, 0, 1, 891, 891, NULL, '1'),
(148, 10083, 45, 26, 'Zapper Guest House - Classic', '', 0, 1, 880, 880, NULL, '1'),
(500, 10271, 32, 19, 'Eco Stay-Classic', NULL, 0, 1, 700, 700, 0, '1'),
(501, 10272, 32, 19, 'Eco Stay-Classic', NULL, 1, 3, 1000, 1000, 0, '1'),
(502, 10272, 32, 19, 'Eco Stay-Classic', NULL, 1, 2, 1000, 1000, 0, '1'),
(503, 10272, 33, 19, 'Eco Stay-Deluxe', NULL, 1, 2, 2000, 2000, 0, '1'),
(508, 10277, 32, 19, 'Eco Stay-Classic', NULL, 0, 1, 700, 700, 0, '1'),
(509, 10278, 32, 19, 'Eco Stay-Classic', NULL, 0, 1, 700, 700, 0, '1'),
(510, 10279, 32, 19, 'Eco Stay-Classic', NULL, 0, 1, 700, 700, 0, '1'),
(511, 10280, 33, 19, 'Eco Stay-Deluxe', NULL, 0, 1, 1600, 1600, 0, '1'),
(512, 10281, 32, 19, 'Eco Stay-Classic', '', 0, 1, 700, 700, 0, '1'),
(513, 10282, 32, 19, 'Eco Stay-Classic', NULL, 0, 1, 700, 700, 0, '1'),
(514, 10283, 32, 19, 'Eco Stay-Classic', NULL, 1, 1, 700, 700, 0, '1'),
(515, 10283, 33, 19, 'Eco Stay-Deluxe', NULL, 1, 1, 1600, 1600, 0, '1'),
(516, 10284, 32, 19, 'Eco Stay-Classic', NULL, 2, 1, 700, 700, 0, '1'),
(517, 10285, 32, 19, 'Eco Stay-Classic', NULL, 1, 2, 700, 700, 0, '1'),
(518, 10285, 33, 19, 'Eco Stay-Deluxe', NULL, 1, 1, 1600, 1600, 0, '1'),
(519, 10286, 34, 20, 'Eco Palace-Classic', NULL, 0, 1, 704, 704, 0, '1'),
(520, 10287, 32, 19, 'Eco Stay-Classic', NULL, 0, 1, 700, 700, 0, '1'),
(521, 10288, 32, 19, 'Eco Stay-Classic', NULL, 0, 1, 700, 700, 0, '1'),
(522, 10289, 33, 19, 'Eco Stay-Deluxe', NULL, 0, 1, 1600, 1600, 0, '1'),
(523, 10290, 32, 19, 'Eco Stay - Classic', NULL, 0, 1, 700, 700, NULL, '1'),
(524, 10292, 34, 20, 'Eco Palace-Classic', NULL, 0, 1, 704, 704, 0, '1'),
(525, 10293, 32, 19, 'Eco Stay - Classic', NULL, 1, 1, 735, 735, NULL, '1'),
(526, 10294, 32, 19, 'Eco Stay-Classic', NULL, 0, 1, 700, 700, 0, '1'),
(527, 10295, 32, 19, 'Eco Stay-Classic', NULL, 0, 1, 700, 700, 0, '1'),
(528, 10296, 32, 19, 'Eco Stay-Classic', NULL, 0, 1, 700, 700, 0, '1'),
(531, 10299, 32, 19, 'Eco Stay-Classic', NULL, 0, 1, 700, 700, 0, '1'),
(532, 10300, 32, 19, 'Eco Stay-Classic', NULL, 0, 1, 700, 700, 0, '1'),
(533, 10301, 32, 19, 'Eco Stay-Classic', NULL, 0, 1, 700, 700, 0, '1'),
(534, 10302, 33, 19, 'Eco Stay-Deluxe', NULL, 0, 1, 1600, 1600, 0, '1'),
(535, 10303, 33, 19, 'Eco Stay-Deluxe', NULL, 0, 1, 1600, 1600, 0, '1'),
(536, 10304, 33, 19, 'Eco Stay-Deluxe', NULL, 0, 1, 6400, 6400, 0, '1'),
(537, 10305, 33, 19, 'Eco Stay-Deluxe', NULL, 0, 1, 1600, 1600, 0, '1'),
(538, 10306, 32, 19, 'Eco Stay-Classic', NULL, 0, 1, 700, 700, 0, '1'),
(539, 10307, 36, 21, 'Eco Inn-Deluxe', NULL, 0, 2, 5016, 5016, 0, '1'),
(540, 10307, 35, 21, 'Eco Inn-Classic', NULL, 0, 2, 5346, 5346, 0, '1'),
(541, 10308, 32, 19, 'Eco Stay-Classic', NULL, 0, 1, 700, 700, 0, '1'),
(542, 10309, 32, 19, 'Eco Stay-Classic', NULL, 0, 1, 700, 700, 0, '1'),
(543, 10309, 33, 19, 'Eco Stay-Deluxe', NULL, 1, 1, 1600, 1600, 0, '1'),
(544, 10310, 32, 19, 'Eco Stay-Classic', NULL, 0, 1, 700, 700, 0, '1'),
(545, 10311, 47, 27, 'Polyshree Guest House-Classic', NULL, 0, 1, 770, 770, 0, '1'),
(546, 10312, 35, 21, 'Eco Inn-Classic', NULL, 0, 1, 891, 891, 0, '1'),
(547, 10313, 32, 19, 'Eco Stay-Classic', NULL, 0, 1, 700, 700, 0, '1'),
(548, 10314, 32, 19, 'Eco Stay-Classic', NULL, 0, 1, 700, 700, 0, '1'),
(549, 10315, 32, 19, 'Eco Stay - Classic', NULL, 0, 1, 700, 700, NULL, '1'),
(550, 10316, 33, 19, 'Eco Stay - Deluxe', NULL, 0, 1, 1600, 1600, NULL, '1'),
(551, 10317, 32, 19, 'Eco Stay-Classic', NULL, 0, 1, 700, 700, 0, '1'),
(552, 10318, 34, 20, 'Eco Palace-Classic', NULL, 0, 1, 704, 704, 0, '1'),
(553, 10319, 34, 20, 'Eco Palace-Classic', NULL, 0, 1, 704, 704, 0, '1'),
(554, 10320, 34, 20, 'Eco Palace-Classic', NULL, 0, 1, 704, 704, 0, '1'),
(555, 10321, 32, 19, 'Eco Stay-Classic', NULL, 0, 1, 700, 700, 0, '1'),
(556, 10322, 33, 19, 'Eco Stay - Deluxe', NULL, 0, 1, 1600, 1600, NULL, '1'),
(557, 10323, 32, 19, 'Eco Stay - Classic', NULL, 0, 1, 700, 700, NULL, '1'),
(558, 10324, 33, 19, 'Eco Stay-Deluxe', NULL, 0, 2, 1600, 1600, 0, '1'),
(559, 10324, 33, 19, 'Eco Stay-Deluxe', NULL, 0, 1, 1600, 1600, 0, '1'),
(560, 10325, 32, 19, 'Eco Stay - Classic', NULL, 0, 1, 700, 700, NULL, '1'),
(561, 10326, 32, 19, 'Eco Stay-Classic', '', 0, 1, 700, 700, 0, '1'),
(562, 10327, 32, 19, 'Eco Stay-Classic', NULL, 0, 1, 700, 700, 0, '1'),
(563, 10328, 32, 19, 'Eco Stay-Classic', NULL, 1, 1, 700, 700, 0, '1'),
(564, 10328, 33, 19, 'Eco Stay-Deluxe', NULL, 1, 1, 1600, 1600, 0, '1'),
(565, 10329, 34, 20, 'Eco Palace-Classic', NULL, 0, 1, 704, 704, 0, '1'),
(566, 10330, 33, 19, 'Eco Stay-Deluxe', NULL, 0, 1, 1600, 1600, 0, '1'),
(567, 10331, 35, 21, 'Eco Inn-Classic', NULL, 0, 1, 891, 891, 0, '1'),
(568, 10332, 33, 19, 'Eco Stay-Deluxe', NULL, 0, 1, 1600, 1600, 0, '1'),
(569, 10333, 34, 20, 'Eco Palace-Classic', NULL, 0, 1, 704, 704, 0, '1'),
(570, 10334, 35, 21, 'Eco Inn-Classic', NULL, 0, 1, 891, 891, 0, '1'),
(571, 10335, 34, 20, 'Eco Palace-Classic', NULL, 0, 1, 704, 704, 0, '1'),
(572, 10336, 33, 19, 'Eco Stay-Deluxe', NULL, 0, 1, 1600, 1600, 0, '1'),
(573, 10337, 32, 19, 'Eco Stay-Classic', NULL, 0, 1, 700, 700, 0, '1'),
(574, 10338, 44, 25, 'Padmavati-Classic', NULL, 0, 1, 935, 935, 0, '1'),
(575, 10339, 40, 23, 'Durbar Guest House-Classic', NULL, 0, 1, 990, 990, 0, '1'),
(576, 10340, 34, 20, 'Eco Palace-Classic', NULL, 0, 1, 704, 704, 0, '1'),
(577, 10341, 53, 30, 'Upasana Guest House-10 classic', '', 0, 1, 880, 880, 0, '1'),
(578, 10342, 34, 20, 'Eco Palace-Classic', NULL, 0, 1, 704, 704, 0, '1'),
(579, 10343, 34, 20, 'Eco Palace-Classic', NULL, 0, 1, 704, 704, 0, '1'),
(580, 10344, 39, 22, 'Stay Inn-Saver', NULL, 0, 2, 825, 825, 0, '1'),
(581, 10344, 39, 22, 'Stay Inn-Saver', NULL, 0, 1, 825, 825, 0, '1'),
(582, 10345, 38, 22, 'Stay Inn-Deluxe', NULL, 0, 2, 2090, 2090, 0, '1'),
(583, 10345, 38, 22, 'Stay Inn-Deluxe', NULL, 0, 1, 2090, 2090, 0, '1'),
(584, 10346, 33, 19, 'Eco Stay-Deluxe', NULL, 0, 1, 1600, 1600, 0, '1'),
(585, 10347, 34, 20, 'Eco Palace-Classic', NULL, 0, 1, 704, 704, 0, '1'),
(586, 10348, 34, 20, 'Eco Palace-Classic', NULL, 0, 1, 704, 704, 0, '1'),
(587, 10349, 32, 19, 'Eco Stay-Classic', NULL, 0, 1, 700, 700, 0, '1'),
(588, 10350, 34, 20, 'Eco Palace-Classic', NULL, 0, 1, 704, 704, 0, '1'),
(589, 10351, 32, 19, 'Eco Stay-Classic', NULL, 0, 1, 700, 700, 0, '1'),
(590, 10352, 32, 19, 'Eco Stay-Classic', NULL, 2, 1, 770, 770, 0, '1'),
(591, 10352, 32, 19, 'Eco Stay-Classic', NULL, 1, 1, 770, 770, 0, '1'),
(592, 10353, 34, 20, 'Eco Palace-Classic', NULL, 0, 1, 704, 704, 0, '1'),
(593, 10354, 32, 19, 'Eco Stay-Classic', NULL, 0, 3, 910, 910, 0, '1'),
(594, 10354, 32, 19, 'Eco Stay-Classic', NULL, 2, 1, 910, 910, 0, '1'),
(595, 10355, 32, 19, 'Eco Stay-Classic', NULL, 0, 1, 700, 700, 0, '1'),
(596, 10356, 32, 19, 'Eco Stay-Classic', NULL, 0, 1, 700, 700, 0, '1'),
(597, 10357, 32, 19, 'Eco Stay-Classic', NULL, 0, 1, 700, 700, 0, '1'),
(598, 10358, 33, 19, 'Eco Stay-Deluxe', NULL, 0, 1, 1600, 1600, 0, '1'),
(599, 10359, 39, 22, 'Stay Inn-Saver', NULL, 0, 1, 825, 825, 0, '1'),
(600, 10359, 39, 22, 'Stay Inn-Saver', NULL, 0, 1, 825, 825, 0, '1'),
(601, 10360, 33, 19, 'Eco Stay-Deluxe', NULL, 0, 1, 1600, 1600, 0, '1'),
(602, 10361, 32, 19, 'Eco Stay-Classic', NULL, 0, 1, 700, 700, 0, '1'),
(603, 10362, 38, 22, 'Stay Inn-Deluxe', NULL, 0, 1, 1045, 1045, 0, '1'),
(604, 10363, 32, 19, 'Eco Stay-Classic', NULL, 0, 1, 700, 700, 0, '1'),
(605, 10364, 32, 19, 'Eco Stay-Classic', NULL, 0, 1, 700, 700, 0, '1'),
(606, 10364, 33, 19, 'Eco Stay-Deluxe', NULL, 0, 1, 1600, 1600, 0, '1'),
(607, 10364, 33, 19, 'Eco Stay-Deluxe', NULL, 0, 1, 1600, 1600, 0, '1'),
(608, 10365, 33, 19, 'Eco Stay-Deluxe', NULL, 0, 1, 1600, 1600, 0, '1'),
(609, 10366, 38, 22, 'Stay Inn-Deluxe', NULL, 0, 1, 1045, 1045, 0, '1'),
(610, 10367, 34, 20, 'Eco Palace-Classic', NULL, 0, 1, 704, 704, 0, '1'),
(611, 10368, 33, 19, 'Eco Stay-Deluxe', NULL, 0, 1, 1600, 1600, 0, '1'),
(612, 10369, 32, 19, 'Eco Stay-Classic', NULL, 0, 1, 700, 700, 0, '1'),
(613, 10370, 33, 19, 'Eco Stay-Deluxe', NULL, 0, 1, 1600, 1600, 0, '1'),
(614, 10371, 33, 19, 'Eco Stay-Deluxe', NULL, 0, 1, 1600, 1600, 0, '1'),
(615, 10372, 33, 19, 'Eco Stay-Deluxe', NULL, 0, 1, 1600, 1600, 0, '1'),
(616, 10373, 32, 19, 'Eco Stay-Classic', NULL, 0, 1, 700, 700, 0, '1'),
(617, 10374, 33, 19, 'Eco Stay-Deluxe', NULL, 0, 1, 1600, 1600, 0, '1'),
(618, 10375, 38, 22, 'Stay Inn-Deluxe', NULL, 0, 1, 2090, 2090, 0, '1'),
(619, 10375, 37, 22, 'Stay Inn-Classic', NULL, 0, 1, 1980, 1980, 0, '1'),
(620, 10376, 34, 20, 'Eco Palace-Classic', NULL, 0, 1, 704, 704, 0, '1'),
(621, 10377, 32, 19, 'Eco Stay-Classic', NULL, 0, 1, 700, 700, 0, '1'),
(622, 10378, 34, 20, 'Eco Palace-Classic', NULL, 0, 1, 704, 704, 0, '1'),
(623, 10379, 33, 19, 'Eco Stay-Deluxe', NULL, 0, 1, 1600, 1600, 0, '1'),
(624, 10380, 32, 19, 'Eco Stay-Classic', NULL, 0, 1, 700, 700, 0, '1'),
(625, 10381, 32, 19, 'Eco Stay-Classic', NULL, 0, 1, 700, 700, 0, '1'),
(626, 10382, 32, 19, 'Eco Stay-Classic', NULL, 0, 1, 700, 700, 0, '1'),
(627, 10383, 32, 19, 'Eco Stay-Classic', NULL, 0, 1, 700, 700, 0, '1'),
(628, 10384, 32, 19, 'Eco Stay-Classic', NULL, 0, 1, 700, 700, 0, '1'),
(629, 10385, 32, 19, 'Eco Stay-Classic', NULL, 0, 1, 700, 700, 0, '1'),
(630, 10386, 32, 19, 'Eco Stay-Classic', NULL, 0, 1, 700, 700, 0, '1'),
(631, 10387, 32, 19, 'Eco Stay-Classic', NULL, 0, 1, 700, 700, 0, '1'),
(632, 10388, 33, 19, 'Eco Stay-Deluxe', NULL, 0, 1, 1600, 1600, 0, '1'),
(633, 10389, 33, 19, 'Eco Stay-Deluxe', NULL, 0, 1, 25600, 25600, 0, '1'),
(634, 10390, 32, 19, 'Eco Stay - Classic', NULL, 0, 1, 1400, 1400, NULL, '1'),
(635, 10391, 32, 19, 'Eco Stay-Classic', NULL, 0, 1, 700, 700, 0, '1'),
(636, 10392, 33, 19, 'Eco Stay - Deluxe', NULL, 0, 1, 3200, 3200, NULL, '1'),
(637, 10393, 32, 19, 'Eco Stay - Classic', NULL, 0, 1, 1400, 1400, NULL, '1'),
(638, 10394, 32, 19, 'Eco Stay-Classic', NULL, 2, 1, 700, 700, 0, '1'),
(639, 10395, 32, 19, 'Eco Stay-Classic', NULL, 0, 1, 700, 700, 0, '1'),
(640, 10396, 33, 19, 'Eco Stay - Deluxe', NULL, 0, 1, 3200, 3200, NULL, '1'),
(641, 10397, 32, 19, 'Eco Stay - Classic', NULL, 0, 1, 1400, 1400, NULL, '1'),
(642, 10398, 32, 19, 'Eco Stay-Classic', NULL, 0, 1, 700, 700, 0, '1'),
(643, 10399, 33, 19, 'Eco Stay-Deluxe', NULL, 0, 1, 1600, 1600, 0, '1'),
(644, 10400, 32, 19, 'Eco Stay - Classic', NULL, 0, 1, 1400, 1400, NULL, '1'),
(645, 10401, 39, 22, 'Stay Inn - Saver', NULL, 0, 1, 1650, 1650, NULL, '1'),
(646, 10402, 32, 19, 'Eco Stay-Classic', NULL, 0, 1, 700, 700, 0, '1'),
(647, 10403, 43, 24, 'Chinnar Inn-Classic', NULL, 0, 1, 880, 880, 0, '1'),
(648, 10404, 32, 19, 'Eco Stay-Classic', NULL, 0, 1, 700, 700, 0, '1'),
(649, 10405, 32, 19, 'Eco Stay-Classic', NULL, 0, 1, 700, 700, 0, '1'),
(650, 10406, 32, 19, 'Eco Stay-Classic', NULL, 0, 1, 700, 700, 0, '1'),
(651, 10407, 34, 20, 'Eco Palace-Classic', NULL, 0, 1, 704, 704, 0, '1'),
(652, 10408, 32, 19, 'Eco Stay - Classic', NULL, 0, 1, 700, 700, NULL, '1'),
(653, 10409, 32, 19, 'Eco Stay - Classic', NULL, 0, 1, 700, 700, NULL, '1'),
(654, 10410, 35, 21, 'Eco Inn - Classic', NULL, 0, 1, 891, 891, NULL, '1'),
(655, 10411, 37, 22, 'Stay Inn-Classic', NULL, 0, 1, 990, 990, 0, '1'),
(656, 10412, 32, 19, 'Eco Stay - Classic', NULL, 0, 2, 770, 770, NULL, '1'),
(657, 10413, 32, 19, 'Eco Stay-Classic', NULL, 0, 1, 700, 700, 0, '1'),
(658, 10414, 33, 19, 'Eco Stay-Deluxe', '', 0, 1, 1600, 1600, 0, '1'),
(659, 10415, 32, 19, 'Eco Stay - Classic', NULL, 0, 2, 770, 770, NULL, '1'),
(660, 10416, 32, 19, 'Eco Stay-Classic', NULL, 0, 1, 700, 700, 0, '1'),
(661, 10417, 32, 19, 'Eco Stay - Classic', NULL, 0, 2, 770, 770, NULL, '1'),
(662, 10418, 32, 19, 'Eco Stay-Classic', NULL, 0, 1, 700, 700, 0, '1'),
(663, 10419, 32, 19, 'Eco Stay - Classic', NULL, 0, 1, 700, 700, NULL, '1'),
(664, 10420, 33, 19, 'Eco Stay - Deluxe', NULL, 0, 1, 1600, 1600, NULL, '1'),
(665, 10421, 38, 22, 'Stay Inn-Deluxe', NULL, 0, 1, 1045, 1045, 0, '1'),
(666, 10422, 32, 19, 'Eco Stay-Classic', NULL, 1, 3, 1000, 1000, 0, '1'),
(667, 10422, 32, 19, 'Eco Stay-Classic', NULL, 1, 2, 1000, 1000, 0, '1'),
(668, 10422, 33, 19, 'Eco Stay-Deluxe', NULL, 1, 2, 2000, 2000, 0, '1'),
(669, 10423, 32, 19, 'Eco Stay-Classic', NULL, 1, 3, 1000, 1000, 0, '1'),
(670, 10423, 32, 19, 'Eco Stay-Classic', NULL, 1, 2, 1000, 1000, 0, '1'),
(671, 10423, 33, 19, 'Eco Stay-Deluxe', NULL, 1, 2, 2000, 2000, 0, '1'),
(672, 10424, 32, 19, 'Eco Stay-Classic', NULL, 1, 3, 1000, 1000, 0, '1'),
(673, 10424, 32, 19, 'Eco Stay-Classic', NULL, 1, 2, 1000, 1000, 0, '1'),
(674, 10424, 33, 19, 'Eco Stay-Deluxe', NULL, 1, 2, 2000, 2000, 0, '1'),
(675, 10425, 32, 19, 'Eco Stay-Classic', NULL, 1, 3, 1000, 1000, 0, '1'),
(676, 10425, 32, 19, 'Eco Stay-Classic', NULL, 1, 2, 1000, 1000, 0, '1'),
(677, 10425, 33, 19, 'Eco Stay-Deluxe', NULL, 1, 2, 2000, 2000, 0, '1'),
(678, 10426, 33, 19, 'Eco Stay - Deluxe', NULL, 0, 2, 1760, 1760, NULL, '1'),
(679, 10427, 32, 19, 'Eco Stay - Classic', NULL, 0, 1, 700, 700, NULL, '1'),
(680, 10428, 32, 19, 'Eco Stay-Classic', NULL, 1, 3, 1000, 1000, 0, '1'),
(681, 10428, 32, 19, 'Eco Stay-Classic', NULL, 1, 2, 1000, 1000, 0, '1'),
(682, 10428, 33, 19, 'Eco Stay-Deluxe', NULL, 1, 2, 2000, 2000, 0, '1'),
(683, 10429, 32, 19, 'Eco Stay-Classic', NULL, 1, 3, 1000, 1000, 0, '1'),
(684, 10429, 32, 19, 'Eco Stay-Classic', NULL, 1, 2, 1000, 1000, 0, '1'),
(685, 10429, 33, 19, 'Eco Stay-Deluxe', NULL, 1, 2, 2000, 2000, 0, '1'),
(686, 10430, 32, 19, 'Eco Stay-Classic', NULL, 0, 1, 700, 700, 0, '1'),
(687, 10430, 32, 19, 'Eco Stay-Classic', NULL, 0, 1, 700, 700, 0, '1'),
(688, 10431, 33, 19, 'Eco Stay-Deluxe', NULL, 1, 2, 1840, 1840, 0, '1'),
(689, 10431, 33, 19, 'Eco Stay-Deluxe', NULL, 0, 1, 1840, 1840, 0, '1'),
(690, 10432, 32, 19, 'Eco Stay-Classic', NULL, 2, 2, 770, 770, 0, '1'),
(691, 10433, 32, 19, 'Eco Stay-Classic', NULL, 1, 2, 770, 770, 0, '1'),
(692, 10433, 33, 19, 'Eco Stay-Deluxe', NULL, 1, 2, 1840, 1840, 0, '1'),
(693, 10434, 46, 26, 'Zapper Guest House-Saver', NULL, 0, 1, 825, 825, 0, '1'),
(694, 10435, 51, 28, 'Maruti Lodging-Suit room', NULL, 0, 1, 2750, 2750, 0, '1'),
(695, 10436, 32, 19, 'Eco Stay-Classic', NULL, 0, 1, 700, 700, 0, '1'),
(696, 10437, 32, 19, 'Eco Stay-Classic', NULL, 0, 1, 700, 700, 0, '1'),
(697, 10437, 33, 19, 'Eco Stay-Deluxe', NULL, 0, 1, 1600, 1600, 0, '1'),
(698, 10437, 33, 19, 'Eco Stay-Deluxe', NULL, 0, 1, 1600, 1600, 0, '1'),
(699, 10438, 53, 30, 'Upasana Guest House - 10 classic', NULL, 0, 1, 880, 880, NULL, '1'),
(700, 10439, 34, 20, 'Eco Palace - Classic', NULL, 0, 1, 704, 704, NULL, '1'),
(701, 10440, 32, 19, 'Eco Stay - Classic', NULL, 0, 1, 700, 700, NULL, '1'),
(702, 10441, 32, 19, 'Eco Stay-Classic', NULL, 1, 2, 770, 770, 0, '1'),
(703, 10441, 32, 19, 'Eco Stay-Classic', NULL, 1, 2, 770, 770, 0, '1'),
(704, 10441, 33, 19, 'Eco Stay-Deluxe', NULL, 1, 2, 1840, 1840, 0, '1'),
(705, 10442, 32, 19, 'Eco Stay-Classic', NULL, 0, 1, 700, 700, 0, '1'),
(706, 10443, 34, 20, 'Eco Palace-Classic', NULL, 0, 1, 704, 704, 0, '1'),
(707, 10444, 34, 20, 'Eco Palace-Classic', NULL, 0, 1, 704, 704, 0, '1'),
(708, 10445, 34, 20, 'Eco Palace-Classic', NULL, 0, 1, 704, 704, 0, '1'),
(709, 10446, 32, 19, 'Eco Stay-Classic', NULL, 0, 1, 700, 700, 0, '1'),
(710, 10447, 32, 19, 'Eco Stay-Classic', NULL, 0, 1, 700, 700, 0, '1'),
(711, 10447, 33, 19, 'Eco Stay-Deluxe', NULL, 0, 1, 1600, 1600, 0, '1'),
(712, 10448, 33, 19, 'Eco Stay-Deluxe', NULL, 0, 1, 3200, 3200, 0, '1'),
(713, 10449, 48, 28, 'Maruti Lodging-Classic', NULL, 0, 1, 1100, 1100, 0, '1'),
(714, 10450, 32, 19, 'Eco Stay-Classic', NULL, 0, 2, 770, 770, 0, '1'),
(715, 10450, 33, 19, 'Eco Stay-Deluxe', NULL, 1, 2, 1840, 1840, 0, '1'),
(716, 10451, 32, 19, 'Eco Stay-Classic', NULL, 2, 2, 770, 770, 0, '1'),
(717, 10451, 32, 19, 'Eco Stay-Classic', NULL, 2, 2, 770, 770, 0, '1'),
(718, 10451, 32, 19, 'Eco Stay-Classic', NULL, 1, 2, 770, 770, 0, '1'),
(719, 10452, 49, 28, 'Maruti Lodging-Deluxe', NULL, 1, 2, 1320, 1320, 0, '1'),
(720, 10452, 48, 28, 'Maruti Lodging-Classic', NULL, 1, 2, 1100, 1100, 0, '1'),
(721, 10452, 48, 28, 'Maruti Lodging-Classic', NULL, 1, 2, 1100, 1100, 0, '1'),
(722, 10453, 33, 19, 'Eco Stay-Deluxe', NULL, 0, 1, 1600, 1600, 0, '1'),
(723, 10454, 33, 19, 'Eco Stay-Deluxe', '', 0, 1, 1600, 1600, 0, '1'),
(724, 10455, 33, 19, 'Eco Stay-Deluxe', NULL, 0, 1, 1600, 1600, 0, '1'),
(725, 10456, 33, 19, 'Eco Stay-Deluxe', NULL, 0, 1, 1600, 1600, 0, '1'),
(726, 10457, 33, 19, 'Eco Stay-Deluxe', NULL, 0, 1, 1600, 1600, 0, '1'),
(727, 10458, 33, 19, 'Eco Stay-Deluxe', NULL, 0, 1, 1600, 1600, 0, '1'),
(728, 10459, 32, 19, 'Eco Stay-Classic', NULL, 0, 1, 1600, 1600, 0, '1'),
(729, 10460, 32, 19, 'Eco Stay-Classic', NULL, 0, 1, 1600, 1600, 0, '1'),
(730, 10461, 32, 19, 'Eco Stay-Classic', NULL, 0, 1, 1600, 1600, 0, '1'),
(731, 10462, 33, 19, 'Eco Stay-Deluxe', NULL, 0, 1, 1600, 1600, 0, '1'),
(732, 10463, 32, 19, 'Eco Stay-Classic', NULL, 0, 1, 1600, 1600, 0, '1'),
(733, 10464, 32, 19, 'Eco Stay-Classic', NULL, 0, 1, 1600, 1600, 0, '1'),
(734, 10465, 32, 19, 'Eco Stay-Classic', NULL, 0, 1, 1600, 1600, 0, '1'),
(735, 10466, 32, 19, 'Eco Stay-Classic', NULL, 0, 1, 1600, 1600, 0, '1'),
(736, 10467, 32, 19, 'Eco Stay-Classic', NULL, 0, 1, 1600, 1600, 0, '1'),
(737, 10468, 32, 19, 'Eco Stay-Classic', NULL, 0, 1, 770, 770, 0, '1'),
(738, 10469, 32, 19, 'Eco Stay-Classic', NULL, 0, 1, 1000, 1000, 0, '1'),
(739, 10469, 32, 19, 'Eco Stay-Classic', NULL, 0, 1, 1000, 1000, 0, '1'),
(740, 10469, 33, 19, 'Eco Stay-Deluxe', NULL, 0, 2, 2000, 2000, 0, '1'),
(741, 10470, 32, 19, 'Eco Stay-Classic', NULL, 1, 3, 1000, 1000, 0, '1'),
(742, 10470, 32, 19, 'Eco Stay-Classic', NULL, 1, 2, 1000, 1000, 0, '1'),
(743, 10470, 33, 19, 'Eco Stay-Deluxe', NULL, 1, 2, 2000, 2000, 0, '1'),
(744, 10471, 32, 19, 'Eco Stay-Classic', NULL, 1, 3, 1000, 1000, 0, '1'),
(745, 10471, 32, 19, 'Eco Stay-Classic', NULL, 1, 2, 1000, 1000, 0, '1'),
(746, 10471, 33, 19, 'Eco Stay-Deluxe', NULL, 1, 2, 2000, 2000, 0, '1'),
(747, 10472, 32, 19, 'Eco Stay-Classic', NULL, 1, 3, 1000, 1000, 0, '1'),
(748, 10472, 32, 19, 'Eco Stay-Classic', NULL, 1, 2, 1000, 1000, 0, '1'),
(749, 10472, 33, 19, 'Eco Stay-Deluxe', NULL, 1, 2, 2000, 2000, 0, '1'),
(750, 10473, 32, 19, 'Eco Stay-Classic', NULL, 1, 3, 1000, 1000, 0, '1'),
(751, 10473, 32, 19, 'Eco Stay-Classic', NULL, 1, 2, 1000, 1000, 0, '1'),
(752, 10473, 33, 19, 'Eco Stay-Deluxe', NULL, 1, 2, 2000, 2000, 0, '1'),
(753, 10474, 32, 19, 'Eco Stay-Classic', NULL, 1, 1, 700, 700, 0, '1'),
(754, 10475, 32, 19, 'Eco Stay-Classic', NULL, 0, 1, 700, 700, 0, '1'),
(755, 10476, 32, 19, 'Eco Stay-Classic', NULL, 0, 1, 700, 700, 0, '1'),
(756, 10477, 32, 19, 'Eco Stay-Classic', NULL, 0, 1, 700, 700, 0, '1'),
(757, 10478, 43, 24, 'Chinnar Inn-Classic', NULL, 0, 1, 880, 880, 0, '1'),
(758, 10479, 43, 24, 'Chinnar Inn-Classic', NULL, 0, 1, 880, 880, 0, '1'),
(759, 10480, 43, 24, 'Chinnar Inn-Classic', NULL, 0, 1, 880, 880, 0, '1'),
(760, 10481, 33, 19, 'Eco Stay - Deluxe', NULL, 0, 1, 3200, 3200, NULL, '1'),
(761, 10482, 43, 24, 'Chinnar Inn-Classic', NULL, 0, 1, 880, 880, 0, '1'),
(762, 10483, 43, 24, 'Chinnar Inn-Classic', NULL, 0, 1, 880, 880, 0, '1'),
(763, 10484, 43, 24, 'Chinnar Inn-Classic', NULL, 0, 1, 880, 880, 0, '1'),
(764, 10485, 43, 24, 'Chinnar Inn-Classic', NULL, 0, 1, 880, 880, 0, '1'),
(765, 10486, 43, 24, 'Chinnar Inn-Classic', NULL, 0, 1, 880, 880, 0, '1'),
(766, 10487, 32, 19, 'Eco Stay - Classic', NULL, 0, 1, 2100, 2100, NULL, '1'),
(767, 10488, 43, 24, 'Chinnar Inn-Classic', NULL, 0, 1, 880, 880, 0, '1'),
(768, 10489, 43, 24, 'Chinnar Inn-Classic', NULL, 0, 1, 880, 880, 0, '1'),
(769, 10490, 43, 24, 'Chinnar Inn-Classic', NULL, 0, 1, 880, 880, 0, '1'),
(770, 10491, 43, 24, 'Chinnar Inn-Classic', NULL, 0, 1, 880, 880, 0, '1'),
(771, 10492, 43, 24, 'Chinnar Inn-Classic', NULL, 0, 1, 880, 880, 0, '1'),
(772, 10493, 47, 27, 'Polyshree Guest House-Classic', NULL, 0, 1, 770, 770, 0, '1'),
(773, 10494, 47, 27, 'Polyshree Guest House-Classic', NULL, 0, 1, 770, 770, 0, '1'),
(774, 10495, 47, 27, 'Polyshree Guest House-Classic', NULL, 0, 1, 770, 770, 0, '1'),
(775, 10496, 47, 27, 'Polyshree Guest House-Classic', NULL, 0, 1, 770, 770, 0, '1'),
(776, 10497, 47, 27, 'Polyshree Guest House-Classic', NULL, 0, 1, 770, 770, 0, '1'),
(777, 10498, 47, 27, 'Polyshree Guest House-Classic', NULL, 0, 1, 770, 770, 0, '1'),
(778, 10499, 47, 27, 'Polyshree Guest House-Classic', NULL, 0, 1, 770, 770, 0, '1'),
(779, 10500, 47, 27, 'Polyshree Guest House-Classic', NULL, 0, 1, 770, 770, 0, '1'),
(780, 10501, 47, 27, 'Polyshree Guest House-Classic', NULL, 0, 1, 770, 770, 0, '1'),
(781, 10502, 47, 27, 'Polyshree Guest House-Classic', NULL, 0, 1, 770, 770, 0, '1'),
(782, 10503, 32, 19, 'Eco Stay-Classic', NULL, 0, 1, 700, 700, 0, '1'),
(783, 10504, 43, 24, 'Chinnar Inn - Classic', NULL, 0, 1, 880, 880, NULL, '1'),
(784, 10505, 43, 24, 'Chinnar Inn - Classic', NULL, 0, 1, 1760, 1760, NULL, '1'),
(785, 10506, 43, 24, 'Chinnar Inn - Classic', NULL, 0, 1, 880, 880, NULL, '1'),
(786, 10507, 43, 24, 'Chinnar Inn - Classic', NULL, 1, 2, 3432, 3432, NULL, '2'),
(787, 10507, 43, 24, 'Chinnar Inn - Classic', NULL, 1, 2, 3432, 3432, NULL, '2'),
(788, 10508, 33, 19, 'Eco Stay - Deluxe', NULL, 0, 1, 1600, 1600, NULL, '1'),
(789, 10509, 32, 19, 'Eco Stay-Classic', NULL, 0, 1, 700, 700, 0, '1'),
(790, 10510, 33, 19, 'Eco Stay - Deluxe', NULL, 0, 1, 3200, 3200, NULL, '1'),
(791, 10511, 32, 19, 'Eco Stay - Classic', NULL, 0, 1, 700, 700, NULL, '1'),
(792, 10512, 32, 19, 'Eco Stay - Classic', '', 0, 1, 700, 700, NULL, '1'),
(793, 10512, 32, 19, 'Eco Stay - Classic', '', 0, 1, 700, 700, NULL, '2'),
(794, 10513, 32, 19, 'Eco Stay-Classic', NULL, 0, 1, 700, 700, 0, '1'),
(795, 10514, 32, 19, 'Eco Stay-Classic', NULL, 1, 3, 1000, 1000, 0, '1'),
(796, 10514, 32, 19, 'Eco Stay-Classic', NULL, 1, 2, 1000, 1000, 0, '1'),
(797, 10514, 33, 19, 'Eco Stay-Deluxe', NULL, 1, 2, 2000, 2000, 0, '1'),
(798, 10515, 32, 19, 'Eco Stay-Classic', NULL, 0, 1, 700, 700, 0, '1'),
(799, 10516, 33, 19, 'Eco Stay-Deluxe', NULL, 0, 1, 1600, 1600, 0, '1'),
(800, 10517, 43, 24, 'Chinnar Inn-Classic', NULL, 0, 1, 880, 880, 0, '1'),
(801, 10518, 33, 19, 'Eco Stay-Deluxe', NULL, 0, 1, 1600, 1600, 0, '1'),
(802, 10519, 32, 19, 'Eco Stay-Classic', NULL, 0, 1, 700, 700, 0, '1'),
(803, 10520, 32, 19, 'Eco Stay-Classic', NULL, 0, 1, 700, 700, 0, '1'),
(804, 10521, 32, 19, 'Eco Stay-Classic', NULL, 0, 1, 700, 700, 0, '1'),
(805, 10522, 32, 19, 'Eco Stay-Classic', '', 2, 1, 700, 700, 0, '1'),
(806, 10522, 32, 19, 'Eco Stay-Classic', '', 0, 1, 700, 700, 0, '1'),
(807, 10523, 33, 19, 'Eco Stay-Deluxe', '', 0, 1, 1600, 1600, 0, '1'),
(808, 10524, 43, 24, 'Chinnar Inn-Classic', NULL, 0, 1, 880, 880, 0, '1'),
(809, 10525, 47, 27, 'Polyshree Guest House-Classic', NULL, 0, 1, 770, 770, 0, '1'),
(810, 10526, 33, 19, 'Eco Stay-Deluxe', NULL, 0, 1, 1600, 1600, 0, '1'),
(811, 10527, 43, 24, 'Chinnar Inn-Classic', NULL, 1, 2, 880, 880, 0, '1'),
(812, 10528, 43, 24, 'Chinnar Inn-Classic', NULL, 1, 2, 880, 880, 0, '1'),
(813, 10529, 43, 24, 'Chinnar Inn-Classic', NULL, 1, 2, 880, 880, 0, '1'),
(814, 10530, 43, 24, 'Chinnar Inn-Classic', NULL, 1, 2, 880, 880, 0, '1'),
(815, 10531, 43, 24, 'Chinnar Inn-Classic', NULL, 0, 1, 880, 880, 0, '1'),
(816, 10532, 32, 19, 'Eco Stay - Classic', NULL, 0, 2, 2310, 2310, NULL, '1'),
(817, 10532, 32, 19, 'Eco Stay - Classic', NULL, 0, 2, 2310, 2310, NULL, '1'),
(818, 10533, 32, 19, 'Eco Stay - Classic', NULL, 0, 2, 1540, 1540, NULL, '2'),
(819, 10533, 32, 19, 'Eco Stay - Classic', NULL, 0, 2, 1540, 1540, NULL, '1'),
(820, 10534, 33, 19, 'Eco Stay-Deluxe', NULL, 0, 1, 1600, 1600, 0, '1'),
(821, 10535, 32, 19, 'Eco Stay-Classic', NULL, 0, 1, 700, 700, 0, '1'),
(822, 10536, 32, 19, 'Eco Stay-Classic', NULL, 0, 1, 700, 700, 0, '1'),
(823, 10537, 32, 19, 'Eco Stay - Classic', '', 0, 1, 700, 700, NULL, '1'),
(824, 10538, 33, 19, 'Eco Stay-Deluxe', '', 0, 1, 1600, 1600, 0, '1'),
(825, 10539, 33, 19, 'Eco Stay-Deluxe', NULL, 0, 1, 1600, 1600, 0, '1'),
(826, 10540, 32, 19, 'Eco Stay-Classic', NULL, 0, 1, 700, 700, 0, '1'),
(827, 10541, 32, 19, 'Eco Stay-Classic', NULL, 0, 1, 700, 700, 0, '1'),
(828, 10542, 32, 19, 'Eco Stay-Classic', NULL, 0, 1, 700, 700, 0, '1'),
(829, 10543, 32, 19, 'Eco Stay-Classic', NULL, 0, 1, 700, 700, 0, '1'),
(830, 10544, 32, 19, 'Eco Stay-Classic', NULL, 0, 1, 700, 700, 0, '1'),
(831, 10545, 32, 19, 'Eco Stay-Classic', NULL, 0, 1, 700, 700, 0, '1'),
(832, 10546, 43, 24, 'Chinnar Inn-Classic', NULL, 0, 1, 880, 880, 0, '1'),
(833, 10547, 33, 19, 'Eco Stay-Deluxe', NULL, 0, 1, 1600, 1600, 0, '1'),
(834, 10548, 33, 19, 'Eco Stay-Deluxe', NULL, 0, 1, 1600, 1600, 0, '1'),
(835, 10549, 32, 19, 'Eco Stay-Classic', NULL, 0, 1, 700, 700, 0, '1'),
(836, 10550, 34, 20, 'Eco Palace-Classic', NULL, 0, 1, 739.2, 739.2, 0, '1'),
(837, 10550, 34, 20, 'Eco Palace-Classic', NULL, 1, 1, 739.2, 739.2, 0, '1'),
(838, 10551, 32, 19, 'Eco Stay-Classic', NULL, 1, 2, 1400, 1400, 0, '1'),
(839, 10552, 32, 19, 'Eco Stay-Classic', NULL, 0, 1, 700, 700, 0, '1'),
(840, 10553, 43, 24, 'Chinnar Inn-Classic', NULL, 0, 1, 880, 880, 0, '1'),
(841, 10554, 43, 24, 'Chinnar Inn-Classic', NULL, 1, 2, 880, 880, 0, '1'),
(842, 10555, 32, 19, 'Eco Stay-Classic', NULL, 2, 1, 700, 700, 0, '1'),
(843, 10556, 32, 19, 'Eco Stay-Classic', NULL, 1, 1, 700, 700, 0, '1'),
(844, 10557, 32, 19, 'Eco Stay - Classic', '', 0, 1, 700, 700, NULL, '1'),
(845, 10558, 51, 28, 'Maruti Lodging-Suit room', NULL, 0, 1, 2750, 2750, 0, '1'),
(846, 10559, 51, 28, 'Maruti Lodging-Suit room', NULL, 0, 1, 2750, 2750, 0, '1'),
(847, 10560, 32, 19, 'Eco Stay-Classic', NULL, 0, 1, 700, 700, 0, '1'),
(848, 10561, 32, 19, 'Eco Stay-Classic', NULL, 0, 1, 700, 700, 0, '1'),
(849, 10562, 33, 19, 'Eco Stay - Deluxe', NULL, 0, 1, 1600, 1600, NULL, '1'),
(850, 10563, 32, 19, 'Eco Stay-Classic', NULL, 0, 1, 700, 700, 0, '1'),
(851, 10564, 32, 19, 'Eco Stay - Classic', '', 0, 1, 700, 700, NULL, '1'),
(852, 10565, 32, 19, 'Eco Stay - Classic', '', 0, 1, 700, 700, NULL, '1'),
(853, 10566, 32, 19, 'Eco Stay-Classic', NULL, 0, 1, 700, 700, 0, '1'),
(854, 10567, 32, 19, 'Eco Stay-Classic', '', 1, 1, 700, 700, 0, '1'),
(855, 10568, 32, 19, 'Eco Stay-Classic', NULL, 1, 1, 700, 700, 0, '1'),
(856, 10569, 32, 19, 'Eco Stay-Classic', NULL, 0, 1, 700, 700, 0, '1'),
(857, 10570, 33, 19, 'Eco Stay-Deluxe', NULL, 0, 1, 1600, 1600, 0, '1'),
(858, 10571, 39, 22, 'Stay Inn-Saver', NULL, 0, 1, 825, 825, 0, '1'),
(859, 10572, 39, 22, 'Stay Inn-Saver', NULL, 0, 1, 825, 825, 0, '1'),
(860, 10573, 41, 23, 'Durbar Guest House-Deluxe', NULL, 0, 1, 1045, 1045, 0, '1'),
(861, 10573, 41, 23, 'Durbar Guest House-Deluxe', NULL, 0, 1, 1045, 1045, 0, '1'),
(862, 10574, 34, 20, 'Eco Palace-Classic', NULL, 0, 1, 704, 704, 0, '1'),
(863, 10575, 32, 19, 'Eco Stay - Classic', '', 0, 1, 700, 700, NULL, '1'),
(864, 10576, 32, 19, 'Eco Stay - Classic', NULL, 0, 1, 700, 700, NULL, '1'),
(865, 10577, 32, 19, 'Eco Stay - Classic', '', 0, 1, 700, 700, NULL, '1'),
(866, 10578, 33, 19, 'Eco Stay - Deluxe', NULL, 0, 1, 1600, 1600, NULL, '1'),
(867, 10579, 43, 24, 'Chinnar Inn - Classic', NULL, 0, 1, 880, 880, NULL, '1'),
(868, 10580, 47, 27, 'Polyshree Guest House - Classic', NULL, 0, 1, 770, 770, NULL, '1'),
(869, 10581, 33, 19, 'Eco Stay - Deluxe', NULL, 0, 1, 1600, 1600, NULL, '1'),
(870, 10582, 43, 24, 'Chinnar Inn - Classic', NULL, 0, 1, 880, 880, NULL, '1'),
(871, 10583, 32, 19, 'Eco Stay - Classic', NULL, 0, 1, 700, 700, NULL, '1'),
(872, 10584, 43, 24, 'Chinnar Inn - Classic', NULL, 0, 1, 880, 880, NULL, '1'),
(873, 10585, 47, 27, 'Polyshree Guest House - Classic', NULL, 0, 1, 770, 770, NULL, '1'),
(874, 10586, 33, 19, 'Eco Stay-Deluxe', NULL, 0, 1, 1600, 1600, 0, '1'),
(875, 10587, 32, 19, 'Eco Stay - Classic', '', 0, 1, 700, 700, NULL, '1'),
(876, 10588, 32, 19, 'Eco Stay - Classic', NULL, 0, 1, 700, 700, NULL, '1'),
(877, 10589, 33, 19, 'Eco Stay-Deluxe', '', 0, 1, 1600, 1600, 0, '1'),
(878, 10590, 33, 19, 'Eco Stay - Deluxe', NULL, 0, 2, 1760, 1760, NULL, '1'),
(879, 10591, 53, 30, 'Upasana Guest House - Classic', '', 0, 1, 880, 880, NULL, '1'),
(880, 10592, 33, 19, 'Eco Stay - Deluxe', NULL, 0, 1, 1600, 1600, NULL, '1'),
(881, 10593, 52, 29, 'Pratiksha Guest House-Classic', NULL, 0, 1, 1485, 1485, 0, '1');

-- --------------------------------------------------------

--
-- Table structure for table `pm_booking_room_history`
--

CREATE TABLE `pm_booking_room_history` (
  `id` int(11) NOT NULL,
  `id_booking` int(11) NOT NULL,
  `id_room` int(11) DEFAULT NULL,
  `id_hotel` int(11) DEFAULT NULL,
  `title` varchar(250) DEFAULT NULL,
  `num` varchar(10) DEFAULT NULL,
  `children` int(11) DEFAULT '0',
  `adults` int(11) DEFAULT '0',
  `amount` double DEFAULT '0',
  `ex_tax` double DEFAULT '0',
  `tax_rate` double DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pm_booking_room_history`
--

INSERT INTO `pm_booking_room_history` (`id`, `id_booking`, `id_room`, `id_hotel`, `title`, `num`, `children`, `adults`, `amount`, `ex_tax`, `tax_rate`) VALUES
(106, 10059, 32, 19, 'Eco Stay - Classic', NULL, 0, 1, 700, 700, NULL),
(107, 10060, 32, 19, 'Eco Stay - Classic', '', 0, 1, 700, 700, NULL),
(108, 10061, 32, 19, 'Eco Stay - Classic', '', 0, 1, 700, 700, NULL),
(109, 10062, 32, 19, 'Eco Stay - Classic', '', 0, 1, 700, 700, NULL),
(110, 10063, 42, 23, 'Durbar Guest House - Saver', '', 0, 1, 825, 825, NULL),
(111, 10063, 42, 23, 'Durbar Guest House - Saver', '', 0, 1, 825, 825, NULL),
(112, 10064, 32, 19, 'Eco Stay - Classic', NULL, 0, 1, 700, 700, NULL),
(113, 10065, 34, 20, 'Eco Palace - Classic', NULL, 0, 1, 704, 704, NULL),
(114, 10066, 39, 22, 'Stay Inn - Saver', '', 0, 1, 1650, 1650, NULL),
(115, 10066, 39, 22, 'Stay Inn - Saver', '', 0, 1, 1650, 1650, NULL),
(116, 10067, 32, 19, 'Eco Stay - Classic', NULL, 0, 1, 700, 700, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `pm_booking_service`
--

CREATE TABLE `pm_booking_service` (
  `id` int(11) NOT NULL,
  `id_booking` int(11) NOT NULL,
  `id_service` int(11) DEFAULT NULL,
  `title` varchar(250) DEFAULT NULL,
  `qty` int(11) DEFAULT '0',
  `amount` double DEFAULT '0',
  `ex_tax` double DEFAULT '0',
  `tax_rate` double DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `pm_booking_tax`
--

CREATE TABLE `pm_booking_tax` (
  `id` int(11) NOT NULL,
  `id_booking` int(11) NOT NULL,
  `id_tax` int(11) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `amount` double DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pm_booking_tax`
--

INSERT INTO `pm_booking_tax` (`id`, `id_booking`, `id_tax`, `name`, `amount`) VALUES
(61, 10059, 0, 'GST', 0),
(62, 10060, 0, 'GST', 0),
(63, 10061, 0, 'GST', 0),
(64, 10062, 0, 'GST', 0),
(65, 10063, 0, 'GST', 267.3),
(66, 10064, 0, 'GST', 0),
(67, 10065, 0, 'GST', 0),
(68, 10066, 0, 'GST', 594),
(69, 10067, 0, 'GST', 0),
(70, 10068, 0, 'GST', 891),
(73, 10071, 0, 'GST', 0),
(74, 10072, 0, 'GST', 378),
(75, 10073, 0, 'GST', 252),
(76, 10074, 0, 'GST', 0),
(77, 10075, 0, 'GST', 0),
(78, 10076, 0, 'GST', 465.3),
(79, 10077, 0, 'GST', 930.6),
(80, 10078, 0, 'GST', 1080),
(81, 10079, 0, 'GST', 0),
(85, 10083, 0, 'GST', 0),
(270, 10271, NULL, 'GST', 0),
(271, 10272, NULL, 'GST', 720),
(276, 10277, NULL, 'GST', 0),
(277, 10278, NULL, 'GST', 0),
(278, 10279, NULL, 'GST', 0),
(279, 10280, NULL, 'GST', 288),
(280, 10281, NULL, 'GST', 0),
(281, 10282, NULL, 'GST', 0),
(282, 10283, NULL, 'GST', 372.6),
(283, 10284, NULL, 'GST', 0),
(284, 10285, NULL, 'GST', 414),
(285, 10286, NULL, 'GST', 0),
(286, 10287, NULL, 'GST', 0),
(287, 10288, NULL, 'GST', 0),
(288, 10289, NULL, 'GST', 288),
(289, 10290, 0, 'GST', 0),
(290, 10291, 0, 'GST', 0),
(291, 10292, NULL, 'GST', 0),
(292, 10293, 0, 'GST', 0),
(293, 10294, NULL, 'GST', 0),
(294, 10295, NULL, 'GST', 0),
(295, 10296, NULL, 'GST', 0),
(298, 10299, NULL, 'GST', 0),
(299, 10300, NULL, 'GST', 0),
(300, 10301, NULL, 'GST', 0),
(301, 10302, NULL, 'GST', 288),
(302, 10303, NULL, 'GST', 288),
(303, 10304, NULL, 'GST', 1152),
(304, 10305, NULL, 'GST', 288),
(305, 10306, NULL, 'GST', 0),
(306, 10307, NULL, 'GST', 1865.16),
(307, 10308, NULL, 'GST', 0),
(308, 10309, NULL, 'GST', 414),
(309, 10310, NULL, 'GST', 0),
(310, 10311, NULL, 'GST', 0),
(311, 10312, NULL, 'GST', 0),
(312, 10313, NULL, 'GST', 0),
(313, 10314, NULL, 'GST', 0),
(314, 10315, 0, 'GST', 0),
(315, 10316, 0, 'GST', 288),
(316, 10317, NULL, 'GST', 0),
(317, 10318, NULL, 'GST', 0),
(318, 10319, NULL, 'GST', 0),
(319, 10320, NULL, 'GST', 0),
(320, 10321, NULL, 'GST', 0),
(321, 10322, 0, 'GST', 288),
(322, 10323, 0, 'GST', 0),
(323, 10324, NULL, 'GST', 576),
(324, 10325, 0, 'GST', 0),
(325, 10326, NULL, 'GST', 0),
(326, 10327, NULL, 'GST', 0),
(327, 10328, NULL, 'GST', 414),
(328, 10329, NULL, 'GST', 0),
(329, 10330, NULL, 'GST', 288),
(330, 10331, NULL, 'GST', 0),
(331, 10332, NULL, 'GST', 288),
(332, 10333, NULL, 'GST', 0),
(333, 10334, NULL, 'GST', 0),
(334, 10335, NULL, 'GST', 0),
(335, 10336, NULL, 'GST', 288),
(336, 10337, NULL, 'GST', 0),
(337, 10338, NULL, 'GST', 0),
(338, 10339, NULL, 'GST', 0),
(339, 10340, NULL, 'GST', 0),
(340, 10341, NULL, 'GST', 0),
(341, 10342, NULL, 'GST', 0),
(342, 10343, NULL, 'GST', 0),
(343, 10344, NULL, 'GST', 297),
(344, 10345, NULL, 'GST', 677.16),
(345, 10346, NULL, 'GST', 288),
(346, 10347, NULL, 'GST', 0),
(347, 10348, NULL, 'GST', 0),
(348, 10349, NULL, 'GST', 0),
(349, 10350, NULL, 'GST', 0),
(350, 10351, NULL, 'GST', 0),
(351, 10352, NULL, 'GST', 277.2),
(352, 10353, NULL, 'GST', 0),
(353, 10354, NULL, 'GST', 327.6),
(354, 10355, NULL, 'GST', 0),
(355, 10356, NULL, 'GST', 0),
(356, 10357, NULL, 'GST', 0),
(357, 10358, NULL, 'GST', 288),
(358, 10359, NULL, 'GST', 297),
(359, 10360, NULL, 'GST', 288),
(360, 10361, NULL, 'GST', 0),
(361, 10362, NULL, 'GST', 125.4),
(362, 10363, NULL, 'GST', 0),
(363, 10364, NULL, 'GST', 702),
(364, 10365, NULL, 'GST', 288),
(365, 10366, NULL, 'GST', 125.4),
(366, 10367, NULL, 'GST', 0),
(367, 10368, NULL, 'GST', 288),
(368, 10369, NULL, 'GST', 0),
(369, 10370, NULL, 'GST', 288),
(370, 10371, NULL, 'GST', 288),
(371, 10372, NULL, 'GST', 288),
(372, 10373, NULL, 'GST', 0),
(373, 10374, NULL, 'GST', 288),
(374, 10375, NULL, 'GST', 732.6),
(375, 10376, NULL, 'GST', 0),
(376, 10377, NULL, 'GST', 0),
(377, 10378, NULL, 'GST', 0),
(378, 10379, NULL, 'GST', 288),
(379, 10380, NULL, 'GST', 0),
(380, 10381, NULL, 'GST', 0),
(381, 10382, NULL, 'GST', 0),
(382, 10383, NULL, 'GST', 0),
(383, 10384, NULL, 'GST', 0),
(384, 10385, NULL, 'GST', 0),
(385, 10386, NULL, 'GST', 0),
(386, 10387, NULL, 'GST', 0),
(387, 10388, NULL, 'GST', 288),
(388, 10389, NULL, 'GST', 4608),
(389, 10390, 0, 'GST', 252),
(390, 10391, NULL, 'GST', 0),
(391, 10392, 0, 'GST', 576),
(392, 10393, 0, 'GST', 252),
(393, 10394, NULL, 'GST', 0),
(394, 10395, NULL, 'GST', 0),
(395, 10396, 0, 'GST', 576),
(396, 10397, 0, 'GST', 252),
(397, 10398, NULL, 'GST', 0),
(398, 10399, NULL, 'GST', 288),
(399, 10400, 0, 'GST', 252),
(400, 10401, 0, 'GST', 297),
(401, 10402, NULL, 'GST', 0),
(402, 10403, NULL, 'GST', 0),
(403, 10404, NULL, 'GST', 0),
(404, 10405, NULL, 'GST', 0),
(405, 10406, NULL, 'GST', 0),
(406, 10407, NULL, 'GST', 0),
(407, 10408, 0, 'GST', 0),
(408, 10409, 0, 'GST', 0),
(409, 10410, 0, 'GST', 0),
(410, 10411, NULL, 'GST', 0),
(411, 10412, 0, 'GST', 0),
(412, 10413, NULL, 'GST', 0),
(413, 10414, NULL, 'GST', 288),
(414, 10415, 0, 'GST', 0),
(415, 10416, NULL, 'GST', 0),
(416, 10417, 0, 'GST', 0),
(417, 10418, NULL, 'GST', 0),
(418, 10419, 0, 'GST', 0),
(419, 10420, 0, 'GST', 288),
(420, 10421, NULL, 'GST', 125.4),
(421, 10422, NULL, 'GST', 840),
(422, 10423, NULL, 'GST', 840),
(423, 10424, NULL, 'GST', 840),
(424, 10425, NULL, 'GST', 840),
(425, 10426, 0, 'GST', 316.8),
(426, 10427, 0, 'GST', 0),
(427, 10428, NULL, 'GST', 840),
(428, 10429, NULL, 'GST', 840),
(429, 10430, NULL, 'GST', 252),
(430, 10431, NULL, 'GST', 662.4),
(431, 10432, NULL, 'GST', 0),
(432, 10433, NULL, 'GST', 469.8),
(433, 10434, NULL, 'GST', 0),
(434, 10435, NULL, 'GST', 495),
(435, 10436, NULL, 'GST', 0),
(436, 10437, NULL, 'GST', 631.8),
(437, 10438, 0, 'GST', 0),
(438, 10439, 0, 'GST', 0),
(439, 10440, 0, 'GST', 0),
(440, 10441, NULL, 'GST', 608.4),
(441, 10442, NULL, 'GST', 0),
(442, 10443, NULL, 'GST', 0),
(443, 10444, NULL, 'GST', 0),
(444, 10445, NULL, 'GST', 0),
(445, 10446, NULL, 'GST', 0),
(446, 10447, NULL, 'GST', 414),
(447, 10448, NULL, 'GST', 576),
(448, 10449, NULL, 'GST', 132),
(449, 10450, NULL, 'GST', 469.8),
(450, 10451, NULL, 'GST', 415.8),
(451, 10452, NULL, 'GST', 633.6),
(452, 10453, NULL, 'GST', 288),
(453, 10454, NULL, 'GST', 288),
(454, 10455, NULL, 'GST', 288),
(455, 10456, NULL, 'GST', 288),
(456, 10457, NULL, 'GST', 288),
(457, 10458, NULL, 'GST', 288),
(458, 10459, NULL, 'GST', 288),
(459, 10460, NULL, 'GST', 70),
(460, 10461, NULL, 'GST', 70),
(461, 10462, NULL, 'GST', 70),
(462, 10463, NULL, 'GST', 70),
(463, 10464, NULL, 'GST', 70),
(464, 10465, NULL, 'GST', 70),
(465, 10466, NULL, 'GST', 70),
(466, 10467, NULL, 'GST', 70),
(467, 10468, NULL, 'GST', 70),
(468, 10469, NULL, 'GST', 840),
(469, 10470, NULL, 'GST', 840),
(470, 10471, NULL, 'GST', 840),
(471, 10472, NULL, 'GST', 237),
(472, 10473, NULL, 'GST', 237),
(473, 10474, NULL, 'GST', 18),
(474, 10475, NULL, 'GST', 18),
(475, 10476, NULL, 'GST', 18),
(476, 10477, NULL, 'GST', 18),
(477, 10478, NULL, 'GST', 18),
(478, 10479, NULL, 'GST', 18),
(479, 10480, NULL, 'GST', 0),
(480, 10481, 0, 'GST', 576),
(481, 10482, NULL, 'GST', 18),
(482, 10483, NULL, 'GST', 18),
(483, 10484, NULL, 'GST', 18),
(484, 10485, NULL, 'GST', 18),
(485, 10486, NULL, 'GST', 18),
(486, 10487, 0, 'GST', 378),
(487, 10488, NULL, 'GST', 18),
(488, 10489, NULL, 'GST', 18),
(489, 10490, NULL, 'GST', 18),
(490, 10491, NULL, 'GST', 18),
(491, 10492, NULL, 'GST', 18),
(492, 10493, NULL, 'GST', 18),
(493, 10494, NULL, 'GST', 18),
(494, 10495, NULL, 'GST', 18),
(495, 10496, NULL, 'GST', 18),
(496, 10497, NULL, 'GST', 18),
(497, 10498, NULL, 'GST', 18),
(498, 10499, NULL, 'GST', 18),
(499, 10500, NULL, 'GST', 18),
(500, 10501, NULL, 'GST', 18),
(501, 10502, NULL, 'GST', 18),
(502, 10503, NULL, 'GST', 0),
(503, 10504, 0, 'GST', 0),
(504, 10505, 0, 'GST', 316.8),
(505, 10506, 0, 'GST', 0),
(506, 10507, 0, 'GST', 1235.52),
(507, 10508, 0, 'GST', 288),
(508, 10509, NULL, 'GST', 18),
(509, 10510, 0, 'GST', 576),
(510, 10511, 0, 'GST', 0),
(511, 10512, 0, 'GST', 252),
(512, 10513, NULL, 'GST', 18),
(513, 10514, NULL, 'GST', 840),
(514, 10515, NULL, 'GST', 18),
(515, 10516, NULL, 'GST', 18),
(516, 10517, NULL, 'GST', 18),
(517, 10518, NULL, 'GST', 0),
(518, 10519, NULL, 'GST', 18),
(519, 10520, NULL, 'GST', 0),
(520, 10521, NULL, 'GST', 18),
(521, 10522, NULL, 'GST', 252),
(522, 10523, NULL, 'GST', 288),
(523, 10524, NULL, 'GST', 0),
(524, 10525, NULL, 'GST', 18),
(525, 10526, NULL, 'GST', 288),
(526, 10527, NULL, 'GST', 18),
(527, 10528, NULL, 'GST', 18),
(528, 10529, NULL, 'GST', 18),
(529, 10530, NULL, 'GST', 18),
(530, 10531, NULL, 'GST', 18),
(531, 10532, 0, 'GST', 831.6),
(532, 10533, 0, 'GST', 554.4),
(533, 10534, NULL, 'GST', 288),
(534, 10535, NULL, 'GST', 18),
(535, 10536, NULL, 'GST', 0),
(536, 10537, 0, 'GST', 0),
(537, 10538, NULL, 'GST', 288),
(538, 10539, NULL, 'GST', 288),
(539, 10540, NULL, 'GST', 0),
(540, 10541, NULL, 'GST', 18),
(541, 10542, NULL, 'GST', 0),
(542, 10543, NULL, 'GST', 18),
(543, 10544, NULL, 'GST', 18),
(544, 10545, NULL, 'GST', 0),
(545, 10546, NULL, 'GST', 18),
(546, 10547, NULL, 'GST', 18),
(547, 10548, NULL, 'GST', 288),
(548, 10549, NULL, 'GST', 18),
(549, 10550, NULL, 'GST', 266.11),
(550, 10551, NULL, 'GST', 18),
(551, 10552, NULL, 'GST', 18),
(552, 10553, NULL, 'GST', 18),
(553, 10554, NULL, 'GST', 0),
(554, 10555, NULL, 'GST', 18),
(555, 10556, NULL, 'GST', 18),
(556, 10557, 0, 'GST', 0),
(557, 10558, NULL, 'GST', 18),
(558, 10559, NULL, 'GST', 495),
(559, 10560, NULL, 'GST', 18),
(560, 10561, NULL, 'GST', 0),
(561, 10562, 0, 'GST', 288),
(562, 10563, NULL, 'GST', 18),
(563, 10564, 0, 'GST', 0),
(564, 10565, 0, 'GST', 0),
(565, 10566, NULL, NULL, 0),
(566, 10567, NULL, 'GST', 0),
(567, 10568, NULL, NULL, 0),
(568, 10569, NULL, NULL, 0),
(569, 10570, NULL, NULL, 0),
(570, 10571, NULL, NULL, 0),
(571, 10572, NULL, NULL, 0),
(572, 10573, NULL, NULL, 0),
(573, 10574, NULL, NULL, 0),
(574, 10575, 0, 'GST', 0),
(575, 10576, 0, 'GST', 0),
(576, 10577, 0, 'GST', 0),
(577, 10578, 0, 'GST', 288),
(578, 10579, 0, 'GST', 0),
(579, 10580, 0, 'GST', 0),
(580, 10581, 0, 'GST', 288),
(581, 10582, 0, 'GST', 0),
(582, 10583, 0, 'GST', 0),
(583, 10584, 0, 'GST', 0),
(584, 10585, 0, 'GST', 0),
(585, 10586, NULL, NULL, 0),
(586, 10587, 0, 'GST', 0),
(587, 10588, 0, 'GST', 0),
(588, 10589, NULL, 'GST', 0),
(589, 10590, 0, 'GST', 315),
(590, 10591, 0, 'GST', 0),
(591, 10592, 0, 'GST', 286.2),
(592, 10593, NULL, NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `pm_cities`
--

CREATE TABLE `pm_cities` (
  `id` int(11) NOT NULL,
  `city` varchar(255) NOT NULL,
  `state_id` int(11) NOT NULL,
  `country_name` varchar(50) NOT NULL DEFAULT 'India'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pm_cities`
--

INSERT INTO `pm_cities` (`id`, `city`, `state_id`, `country_name`) VALUES
(1, 'North and Middle Andaman', 32, 'India'),
(2, 'South Andaman', 32, 'India'),
(3, 'Nicobar', 32, 'India'),
(4, 'Adilabad', 1, 'India'),
(5, 'Anantapur', 1, 'India'),
(6, 'Chittoor', 1, 'India'),
(7, 'East Godavari', 1, 'India'),
(8, 'Guntur', 1, 'India'),
(9, 'Hyderabad', 1, 'India'),
(10, 'Kadapa', 1, 'India'),
(11, 'Karimnagar', 1, 'India'),
(12, 'Khammam', 1, 'India'),
(13, 'Krishna', 1, 'India'),
(14, 'Kurnool', 1, 'India'),
(15, 'Mahbubnagar', 1, 'India'),
(16, 'Medak', 1, 'India'),
(17, 'Nalgonda', 1, 'India'),
(18, 'Nellore', 1, 'India'),
(19, 'Nizamabad', 1, 'India'),
(20, 'Prakasam', 1, 'India'),
(21, 'Rangareddi', 1, 'India'),
(22, 'Srikakulam', 1, 'India'),
(23, 'Vishakhapatnam', 1, 'India'),
(24, 'Vizianagaram', 1, 'India'),
(25, 'Warangal', 1, 'India'),
(26, 'West Godavari', 1, 'India'),
(27, 'Anjaw', 3, 'India'),
(28, 'Changlang', 3, 'India'),
(29, 'East Kameng', 3, 'India'),
(30, 'Lohit', 3, 'India'),
(31, 'Lower Subansiri', 3, 'India'),
(32, 'Papum Pare', 3, 'India'),
(33, 'Tirap', 3, 'India'),
(34, 'Dibang Valley', 3, 'India'),
(35, 'Upper Subansiri', 3, 'India'),
(36, 'West Kameng', 3, 'India'),
(37, 'Barpeta', 2, 'India'),
(38, 'Bongaigaon', 2, 'India'),
(39, 'Cachar', 2, 'India'),
(40, 'Darrang', 2, 'India'),
(41, 'Dhemaji', 2, 'India'),
(42, 'Dhubri', 2, 'India'),
(43, 'Dibrugarh', 2, 'India'),
(44, 'Goalpara', 2, 'India'),
(45, 'Golaghat', 2, 'India'),
(46, 'Hailakandi', 2, 'India'),
(47, 'Jorhat', 2, 'India'),
(48, 'Karbi Anglong', 2, 'India'),
(49, 'Karimganj', 2, 'India'),
(50, 'Kokrajhar', 2, 'India'),
(51, 'Lakhimpur', 2, 'India'),
(52, 'Marigaon', 2, 'India'),
(53, 'Nagaon', 2, 'India'),
(54, 'Nalbari', 2, 'India'),
(55, 'North Cachar Hills', 2, 'India'),
(56, 'Sibsagar', 2, 'India'),
(57, 'Sonitpur', 2, 'India'),
(58, 'Tinsukia', 2, 'India'),
(59, 'Araria', 4, 'India'),
(60, 'Aurangabad', 4, 'India'),
(61, 'Banka', 4, 'India'),
(62, 'Begusarai', 4, 'India'),
(63, 'Bhagalpur', 4, 'India'),
(64, 'Bhojpur', 4, 'India'),
(65, 'Buxar', 4, 'India'),
(66, 'Darbhanga', 4, 'India'),
(67, 'Purba Champaran', 4, 'India'),
(68, 'Gaya', 4, 'India'),
(69, 'Gopalganj', 4, 'India'),
(70, 'Jamui', 4, 'India'),
(71, 'Jehanabad', 4, 'India'),
(72, 'Khagaria', 4, 'India'),
(73, 'Kishanganj', 4, 'India'),
(74, 'Kaimur', 4, 'India'),
(75, 'Katihar', 4, 'India'),
(76, 'Lakhisarai', 4, 'India'),
(77, 'Madhubani', 4, 'India'),
(78, 'Munger', 4, 'India'),
(79, 'Madhepura', 4, 'India'),
(80, 'Muzaffarpur', 4, 'India'),
(81, 'Nalanda', 4, 'India'),
(82, 'Nawada', 4, 'India'),
(83, 'Patna', 4, 'India'),
(84, 'Purnia', 4, 'India'),
(85, 'Rohtas', 4, 'India'),
(86, 'Saharsa', 4, 'India'),
(87, 'Samastipur', 4, 'India'),
(88, 'Sheohar', 4, 'India'),
(89, 'Sheikhpura', 4, 'India'),
(90, 'Saran', 4, 'India'),
(91, 'Sitamarhi', 4, 'India'),
(92, 'Supaul', 4, 'India'),
(93, 'Siwan', 4, 'India'),
(94, 'Vaishali', 4, 'India'),
(95, 'Pashchim Champaran', 4, 'India'),
(96, 'Bastar', 36, 'India'),
(97, 'Bilaspur', 36, 'India'),
(98, 'Dantewada', 36, 'India'),
(99, 'Dhamtari', 36, 'India'),
(100, 'Durg', 36, 'India'),
(101, 'Jashpur', 36, 'India'),
(102, 'Janjgir-Champa', 36, 'India'),
(103, 'Korba', 36, 'India'),
(104, 'Koriya', 36, 'India'),
(105, 'Kanker', 36, 'India'),
(106, 'Kawardha', 36, 'India'),
(107, 'Mahasamund', 36, 'India'),
(108, 'Raigarh', 36, 'India'),
(109, 'Rajnandgaon', 36, 'India'),
(110, 'Raipur', 36, 'India'),
(111, 'Surguja', 36, 'India'),
(112, 'Diu', 29, 'India'),
(113, 'Daman', 29, 'India'),
(114, 'Central Delhi', 25, 'India'),
(115, 'East Delhi', 25, 'India'),
(116, 'New Delhi', 25, 'India'),
(117, 'North Delhi', 25, 'India'),
(118, 'North East Delhi', 25, 'India'),
(119, 'North West Delhi', 25, 'India'),
(120, 'South Delhi', 25, 'India'),
(121, 'South West Delhi', 25, 'India'),
(122, 'West Delhi', 25, 'India'),
(123, 'North Goa', 26, 'India'),
(124, 'South Goa', 26, 'India'),
(125, 'Ahmedabad', 5, 'India'),
(126, 'Amreli District', 5, 'India'),
(127, 'Anand', 5, 'India'),
(128, 'Banaskantha', 5, 'India'),
(129, 'Bharuch', 5, 'India'),
(130, 'Bhavnagar', 5, 'India'),
(131, 'Dahod', 5, 'India'),
(132, 'The Dangs', 5, 'India'),
(133, 'Gandhinagar', 5, 'India'),
(134, 'Jamnagar', 5, 'India'),
(135, 'Junagadh', 5, 'India'),
(136, 'Kutch', 5, 'India'),
(137, 'Kheda', 5, 'India'),
(138, 'Mehsana', 5, 'India'),
(139, 'Narmada', 5, 'India'),
(140, 'Navsari', 5, 'India'),
(141, 'Patan', 5, 'India'),
(142, 'Panchmahal', 5, 'India'),
(143, 'Porbandar', 5, 'India'),
(144, 'Rajkot', 5, 'India'),
(145, 'Sabarkantha', 5, 'India'),
(146, 'Surendranagar', 5, 'India'),
(147, 'Surat', 5, 'India'),
(148, 'Vadodara', 5, 'India'),
(149, 'Valsad', 5, 'India'),
(150, 'Ambala', 6, 'India'),
(151, 'Bhiwani', 6, 'India'),
(152, 'Faridabad', 6, 'India'),
(153, 'Fatehabad', 6, 'India'),
(154, 'Gurgaon', 6, 'India'),
(155, 'Hissar', 6, 'India'),
(156, 'Jhajjar', 6, 'India'),
(157, 'Jind', 6, 'India'),
(158, 'Karnal', 6, 'India'),
(159, 'Kaithal', 6, 'India'),
(160, 'Kurukshetra', 6, 'India'),
(161, 'Mahendragarh', 6, 'India'),
(162, 'Mewat', 6, 'India'),
(163, 'Panchkula', 6, 'India'),
(164, 'Panipat', 6, 'India'),
(165, 'Rewari', 6, 'India'),
(166, 'Rohtak', 6, 'India'),
(167, 'Sirsa', 6, 'India'),
(168, 'Sonepat', 6, 'India'),
(169, 'Yamuna Nagar', 6, 'India'),
(170, 'Palwal', 6, 'India'),
(171, 'Bilaspur', 7, 'India'),
(172, 'Chamba', 7, 'India'),
(173, 'Hamirpur', 7, 'India'),
(174, 'Kangra', 7, 'India'),
(175, 'Kinnaur', 7, 'India'),
(176, 'Kulu', 7, 'India'),
(177, 'Lahaul and Spiti', 7, 'India'),
(178, 'Mandi', 7, 'India'),
(179, 'Shimla', 7, 'India'),
(180, 'Sirmaur', 7, 'India'),
(181, 'Solan', 7, 'India'),
(182, 'Una', 7, 'India'),
(183, 'Anantnag', 8, 'India'),
(184, 'Badgam', 8, 'India'),
(185, 'Bandipore', 8, 'India'),
(186, 'Baramula', 8, 'India'),
(187, 'Doda', 8, 'India'),
(188, 'Jammu', 8, 'India'),
(189, 'Kargil', 8, 'India'),
(190, 'Kathua', 8, 'India'),
(191, 'Kupwara', 8, 'India'),
(192, 'Leh', 8, 'India'),
(193, 'Poonch', 8, 'India'),
(194, 'Pulwama', 8, 'India'),
(195, 'Rajauri', 8, 'India'),
(196, 'Srinagar', 8, 'India'),
(197, 'Samba', 8, 'India'),
(198, 'Udhampur', 8, 'India'),
(199, 'Bokaro', 34, 'India'),
(200, 'Chatra', 34, 'India'),
(201, 'Deoghar', 34, 'India'),
(202, 'Dhanbad', 34, 'India'),
(203, 'Dumka', 34, 'India'),
(204, 'Purba Singhbhum', 34, 'India'),
(205, 'Garhwa', 34, 'India'),
(206, 'Giridih', 34, 'India'),
(207, 'Godda', 34, 'India'),
(208, 'Gumla', 34, 'India'),
(209, 'Hazaribagh', 34, 'India'),
(210, 'Koderma', 34, 'India'),
(211, 'Lohardaga', 34, 'India'),
(212, 'Pakur', 34, 'India'),
(213, 'Palamu', 34, 'India'),
(214, 'Ranchi', 34, 'India'),
(215, 'Sahibganj', 34, 'India'),
(216, 'Seraikela and Kharsawan', 34, 'India'),
(217, 'Pashchim Singhbhum', 34, 'India'),
(218, 'Ramgarh', 34, 'India'),
(219, 'Bidar', 9, 'India'),
(220, 'Belgaum', 9, 'India'),
(221, 'Bijapur', 9, 'India'),
(222, 'Bagalkot', 9, 'India'),
(223, 'Bellary', 9, 'India'),
(224, 'Bangalore Rural District', 9, 'India'),
(225, 'Bangalore Urban District', 9, 'India'),
(226, 'Chamarajnagar', 9, 'India'),
(227, 'Chikmagalur', 9, 'India'),
(228, 'Chitradurga', 9, 'India'),
(229, 'Davanagere', 9, 'India'),
(230, 'Dharwad', 9, 'India'),
(231, 'Dakshina Kannada', 9, 'India'),
(232, 'Gadag', 9, 'India'),
(233, 'Gulbarga', 9, 'India'),
(234, 'Hassan', 9, 'India'),
(235, 'Haveri District', 9, 'India'),
(236, 'Kodagu', 9, 'India'),
(237, 'Kolar', 9, 'India'),
(238, 'Koppal', 9, 'India'),
(239, 'Mandya', 9, 'India'),
(240, 'Mysore', 9, 'India'),
(241, 'Raichur', 9, 'India'),
(242, 'Shimoga', 9, 'India'),
(243, 'Tumkur', 9, 'India'),
(244, 'Udupi', 9, 'India'),
(245, 'Uttara Kannada', 9, 'India'),
(246, 'Ramanagara', 9, 'India'),
(247, 'Chikballapur', 9, 'India'),
(248, 'Yadagiri', 9, 'India'),
(249, 'Alappuzha', 10, 'India'),
(250, 'Ernakulam', 10, 'India'),
(251, 'Idukki', 10, 'India'),
(252, 'Kollam', 10, 'India'),
(253, 'Kannur', 10, 'India'),
(254, 'Kasaragod', 10, 'India'),
(255, 'Kottayam', 10, 'India'),
(256, 'Kozhikode', 10, 'India'),
(257, 'Malappuram', 10, 'India'),
(258, 'Palakkad', 10, 'India'),
(259, 'Pathanamthitta', 10, 'India'),
(260, 'Thrissur', 10, 'India'),
(261, 'Thiruvananthapuram', 10, 'India'),
(262, 'Wayanad', 10, 'India'),
(263, 'Alirajpur', 11, 'India'),
(264, 'Anuppur', 11, 'India'),
(265, 'Ashok Nagar', 11, 'India'),
(266, 'Balaghat', 11, 'India'),
(267, 'Barwani', 11, 'India'),
(268, 'Betul', 11, 'India'),
(269, 'Bhind', 11, 'India'),
(270, 'Bhopal', 11, 'India'),
(271, 'Burhanpur', 11, 'India'),
(272, 'Chhatarpur', 11, 'India'),
(273, 'Chhindwara', 11, 'India'),
(274, 'Damoh', 11, 'India'),
(275, 'Datia', 11, 'India'),
(276, 'Dewas', 11, 'India'),
(277, 'Dhar', 11, 'India'),
(278, 'Dindori', 11, 'India'),
(279, 'Guna', 11, 'India'),
(280, 'Gwalior', 11, 'India'),
(281, 'Harda', 11, 'India'),
(282, 'Hoshangabad', 11, 'India'),
(283, 'Indore', 11, 'India'),
(284, 'Jabalpur', 11, 'India'),
(285, 'Jhabua', 11, 'India'),
(286, 'Katni', 11, 'India'),
(287, 'Khandwa', 11, 'India'),
(288, 'Khargone', 11, 'India'),
(289, 'Mandla', 11, 'India'),
(290, 'Mandsaur', 11, 'India'),
(291, 'Morena', 11, 'India'),
(292, 'Narsinghpur', 11, 'India'),
(293, 'Neemuch', 11, 'India'),
(294, 'Panna', 11, 'India'),
(295, 'Rewa', 11, 'India'),
(296, 'Rajgarh', 11, 'India'),
(297, 'Ratlam', 11, 'India'),
(298, 'Raisen', 11, 'India'),
(299, 'Sagar', 11, 'India'),
(300, 'Satna', 11, 'India'),
(301, 'Sehore', 11, 'India'),
(302, 'Seoni', 11, 'India'),
(303, 'Shahdol', 11, 'India'),
(304, 'Shajapur', 11, 'India'),
(305, 'Sheopur', 11, 'India'),
(306, 'Shivpuri', 11, 'India'),
(307, 'Sidhi', 11, 'India'),
(308, 'Singrauli', 11, 'India'),
(309, 'Tikamgarh', 11, 'India'),
(310, 'Ujjain', 11, 'India'),
(311, 'Umaria', 11, 'India'),
(312, 'Vidisha', 11, 'India'),
(313, 'Ahmednagar', 12, 'India'),
(314, 'Akola', 12, 'India'),
(315, 'Amrawati', 12, 'India'),
(316, 'Aurangabad', 12, 'India'),
(317, 'Bhandara', 12, 'India'),
(318, 'Beed', 12, 'India'),
(319, 'Buldhana', 12, 'India'),
(320, 'Chandrapur', 12, 'India'),
(321, 'Dhule', 12, 'India'),
(322, 'Gadchiroli', 12, 'India'),
(323, 'Gondiya', 12, 'India'),
(324, 'Hingoli', 12, 'India'),
(325, 'Jalgaon', 12, 'India'),
(326, 'Jalna', 12, 'India'),
(327, 'Kolhapur', 12, 'India'),
(328, 'Latur', 12, 'India'),
(329, 'Mumbai City', 12, 'India'),
(330, 'Mumbai suburban', 12, 'India'),
(331, 'Nandurbar', 12, 'India'),
(332, 'Nanded', 12, 'India'),
(333, 'Nagpur', 12, 'India'),
(334, 'Nashik', 12, 'India'),
(335, 'Osmanabad', 12, 'India'),
(336, 'Parbhani', 12, 'India'),
(337, 'Pune', 12, 'India'),
(338, 'Raigad', 12, 'India'),
(339, 'Ratnagiri', 12, 'India'),
(340, 'Sindhudurg', 12, 'India'),
(341, 'Sangli', 12, 'India'),
(342, 'Solapur', 12, 'India'),
(343, 'Satara', 12, 'India'),
(344, 'Thane', 12, 'India'),
(345, 'Wardha', 12, 'India'),
(346, 'Washim', 12, 'India'),
(347, 'Yavatmal', 12, 'India'),
(348, 'Bishnupur', 13, 'India'),
(349, 'Churachandpur', 13, 'India'),
(350, 'Chandel', 13, 'India'),
(351, 'Imphal East', 13, 'India'),
(352, 'Senapati', 13, 'India'),
(353, 'Tamenglong', 13, 'India'),
(354, 'Thoubal', 13, 'India'),
(355, 'Ukhrul', 13, 'India'),
(356, 'Imphal West', 13, 'India'),
(357, 'East Garo Hills', 14, 'India'),
(358, 'East Khasi Hills', 14, 'India'),
(359, 'Jaintia Hills', 14, 'India'),
(360, 'Ri-Bhoi', 14, 'India'),
(361, 'South Garo Hills', 14, 'India'),
(362, 'West Garo Hills', 14, 'India'),
(363, 'West Khasi Hills', 14, 'India'),
(364, 'Aizawl', 15, 'India'),
(365, 'Champhai', 15, 'India'),
(366, 'Kolasib', 15, 'India'),
(367, 'Lawngtlai', 15, 'India'),
(368, 'Lunglei', 15, 'India'),
(369, 'Mamit', 15, 'India'),
(370, 'Saiha', 15, 'India'),
(371, 'Serchhip', 15, 'India'),
(372, 'Dimapur', 16, 'India'),
(373, 'Kohima', 16, 'India'),
(374, 'Mokokchung', 16, 'India'),
(375, 'Mon', 16, 'India'),
(376, 'Phek', 16, 'India'),
(377, 'Tuensang', 16, 'India'),
(378, 'Wokha', 16, 'India'),
(379, 'Zunheboto', 16, 'India'),
(380, 'Angul', 17, 'India'),
(381, 'Boudh', 17, 'India'),
(382, 'Bhadrak', 17, 'India'),
(383, 'Bolangir', 17, 'India'),
(384, 'Bargarh', 17, 'India'),
(385, 'Baleswar', 17, 'India'),
(386, 'Cuttack', 17, 'India'),
(387, 'Debagarh', 17, 'India'),
(388, 'Dhenkanal', 17, 'India'),
(389, 'Ganjam', 17, 'India'),
(390, 'Gajapati', 17, 'India'),
(391, 'Jharsuguda', 17, 'India'),
(392, 'Jajapur', 17, 'India'),
(393, 'Jagatsinghpur', 17, 'India'),
(394, 'Khordha', 17, 'India'),
(395, 'Kendujhar', 17, 'India'),
(396, 'Kalahandi', 17, 'India'),
(397, 'Kandhamal', 17, 'India'),
(398, 'Koraput', 17, 'India'),
(399, 'Kendrapara', 17, 'India'),
(400, 'Malkangiri', 17, 'India'),
(401, 'Mayurbhanj', 17, 'India'),
(402, 'Nabarangpur', 17, 'India'),
(403, 'Nuapada', 17, 'India'),
(404, 'Nayagarh', 17, 'India'),
(405, 'Puri', 17, 'India'),
(406, 'Rayagada', 17, 'India'),
(407, 'Sambalpur', 17, 'India'),
(408, 'Subarnapur', 17, 'India'),
(409, 'Sundargarh', 17, 'India'),
(410, 'Karaikal', 27, 'India'),
(411, 'Mahe', 27, 'India'),
(412, 'Puducherry', 27, 'India'),
(413, 'Yanam', 27, 'India'),
(414, 'Amritsar', 18, 'India'),
(415, 'Bathinda', 18, 'India'),
(416, 'Firozpur', 18, 'India'),
(417, 'Faridkot', 18, 'India'),
(418, 'Fatehgarh Sahib', 18, 'India'),
(419, 'Gurdaspur', 18, 'India'),
(420, 'Hoshiarpur', 18, 'India'),
(421, 'Jalandhar', 18, 'India'),
(422, 'Kapurthala', 18, 'India'),
(423, 'Ludhiana', 18, 'India'),
(424, 'Mansa', 18, 'India'),
(425, 'Moga', 18, 'India'),
(426, 'Mukatsar', 18, 'India'),
(427, 'Nawan Shehar', 18, 'India'),
(428, 'Patiala', 18, 'India'),
(429, 'Rupnagar', 18, 'India'),
(430, 'Sangrur', 18, 'India'),
(431, 'Ajmer', 19, 'India'),
(432, 'Alwar', 19, 'India'),
(433, 'Bikaner', 19, 'India'),
(434, 'Barmer', 19, 'India'),
(435, 'Banswara', 19, 'India'),
(436, 'Bharatpur', 19, 'India'),
(437, 'Baran', 19, 'India'),
(438, 'Bundi', 19, 'India'),
(439, 'Bhilwara', 19, 'India'),
(440, 'Churu', 19, 'India'),
(441, 'Chittorgarh', 19, 'India'),
(442, 'Dausa', 19, 'India'),
(443, 'Dholpur', 19, 'India'),
(444, 'Dungapur', 19, 'India'),
(445, 'Ganganagar', 19, 'India'),
(446, 'Hanumangarh', 19, 'India'),
(447, 'Juhnjhunun', 19, 'India'),
(448, 'Jalore', 19, 'India'),
(449, 'Jodhpur', 19, 'India'),
(450, 'Jaipur', 19, 'India'),
(451, 'Jaisalmer', 19, 'India'),
(452, 'Jhalawar', 19, 'India'),
(453, 'Karauli', 19, 'India'),
(454, 'Kota', 19, 'India'),
(455, 'Nagaur', 19, 'India'),
(456, 'Pali', 19, 'India'),
(457, 'Pratapgarh', 19, 'India'),
(458, 'Rajsamand', 19, 'India'),
(459, 'Sikar', 19, 'India'),
(460, 'Sawai Madhopur', 19, 'India'),
(461, 'Sirohi', 19, 'India'),
(462, 'Tonk', 19, 'India'),
(463, 'Udaipur', 19, 'India'),
(464, 'East Sikkim', 20, 'India'),
(465, 'North Sikkim', 20, 'India'),
(466, 'South Sikkim', 20, 'India'),
(467, 'West Sikkim', 20, 'India'),
(468, 'Ariyalur', 21, 'India'),
(469, 'Chennai', 21, 'India'),
(470, 'Coimbatore', 21, 'India'),
(471, 'Cuddalore', 21, 'India'),
(472, 'Dharmapuri', 21, 'India'),
(473, 'Dindigul', 21, 'India'),
(474, 'Erode', 21, 'India'),
(475, 'Kanchipuram', 21, 'India'),
(476, 'Kanyakumari', 21, 'India'),
(477, 'Karur', 21, 'India'),
(478, 'Madurai', 21, 'India'),
(479, 'Nagapattinam', 21, 'India'),
(480, 'The Nilgiris', 21, 'India'),
(481, 'Namakkal', 21, 'India'),
(482, 'Perambalur', 21, 'India'),
(483, 'Pudukkottai', 21, 'India'),
(484, 'Ramanathapuram', 21, 'India'),
(485, 'Salem', 21, 'India'),
(486, 'Sivagangai', 21, 'India'),
(487, 'Tiruppur', 21, 'India'),
(488, 'Tiruchirappalli', 21, 'India'),
(489, 'Theni', 21, 'India'),
(490, 'Tirunelveli', 21, 'India'),
(491, 'Thanjavur', 21, 'India'),
(492, 'Thoothukudi', 21, 'India'),
(493, 'Thiruvallur', 21, 'India'),
(494, 'Thiruvarur', 21, 'India'),
(495, 'Tiruvannamalai', 21, 'India'),
(496, 'Vellore', 21, 'India'),
(497, 'Villupuram', 21, 'India'),
(498, 'Dhalai', 22, 'India'),
(499, 'North Tripura', 22, 'India'),
(500, 'South Tripura', 22, 'India'),
(501, 'West Tripura', 22, 'India'),
(502, 'Almora', 33, 'India'),
(503, 'Bageshwar', 33, 'India'),
(504, 'Chamoli', 33, 'India'),
(505, 'Champawat', 33, 'India'),
(506, 'Dehradun', 33, 'India'),
(507, 'Haridwar', 33, 'India'),
(508, 'Nainital', 33, 'India'),
(509, 'Pauri Garhwal', 33, 'India'),
(510, 'Pithoragharh', 33, 'India'),
(511, 'Rudraprayag', 33, 'India'),
(512, 'Tehri Garhwal', 33, 'India'),
(513, 'Udham Singh Nagar', 33, 'India'),
(514, 'Uttarkashi', 33, 'India'),
(515, 'Agra', 23, 'India'),
(516, 'Allahabad', 23, 'India'),
(517, 'Aligarh', 23, 'India'),
(518, 'Ambedkar Nagar', 23, 'India'),
(519, 'Auraiya', 23, 'India'),
(520, 'Azamgarh', 23, 'India'),
(521, 'Barabanki', 23, 'India'),
(522, 'Badaun', 23, 'India'),
(523, 'Bagpat', 23, 'India'),
(524, 'Bahraich', 23, 'India'),
(525, 'Bijnor', 23, 'India'),
(526, 'Ballia', 23, 'India'),
(527, 'Banda', 23, 'India'),
(528, 'Balrampur', 23, 'India'),
(529, 'Bareilly', 23, 'India'),
(530, 'Basti', 23, 'India'),
(531, 'Bulandshahr', 23, 'India'),
(532, 'Chandauli', 23, 'India'),
(533, 'Chitrakoot', 23, 'India'),
(534, 'Deoria', 23, 'India'),
(535, 'Etah', 23, 'India'),
(536, 'Kanshiram Nagar', 23, 'India'),
(537, 'Etawah', 23, 'India'),
(538, 'Firozabad', 23, 'India'),
(539, 'Farrukhabad', 23, 'India'),
(540, 'Fatehpur', 23, 'India'),
(541, 'Faizabad', 23, 'India'),
(542, 'Gautam Buddha Nagar', 23, 'India'),
(543, 'Gonda', 23, 'India'),
(544, 'Ghazipur', 23, 'India'),
(545, 'Gorkakhpur', 23, 'India'),
(546, 'Ghaziabad', 23, 'India'),
(547, 'Hamirpur', 23, 'India'),
(548, 'Hardoi', 23, 'India'),
(549, 'Mahamaya Nagar', 23, 'India'),
(550, 'Jhansi', 23, 'India'),
(551, 'Jalaun', 23, 'India'),
(552, 'Jyotiba Phule Nagar', 23, 'India'),
(553, 'Jaunpur District', 23, 'India'),
(554, 'Kanpur Dehat', 23, 'India'),
(555, 'Kannauj', 23, 'India'),
(556, 'Kanpur Nagar', 23, 'India'),
(557, 'Kaushambi', 23, 'India'),
(558, 'Kushinagar', 23, 'India'),
(559, 'Lalitpur', 23, 'India'),
(560, 'Lakhimpur Kheri', 23, 'India'),
(561, 'Lucknow', 23, 'India'),
(562, 'Mau', 23, 'India'),
(563, 'Meerut', 23, 'India'),
(564, 'Maharajganj', 23, 'India'),
(565, 'Mahoba', 23, 'India'),
(566, 'Mirzapur', 23, 'India'),
(567, 'Moradabad', 23, 'India'),
(568, 'Mainpuri', 23, 'India'),
(569, 'Mathura', 23, 'India'),
(570, 'Muzaffarnagar', 23, 'India'),
(571, 'Pilibhit', 23, 'India'),
(572, 'Pratapgarh', 23, 'India'),
(573, 'Rampur', 23, 'India'),
(574, 'Rae Bareli', 23, 'India'),
(575, 'Saharanpur', 23, 'India'),
(576, 'Sitapur', 23, 'India'),
(577, 'Shahjahanpur', 23, 'India'),
(578, 'Sant Kabir Nagar', 23, 'India'),
(579, 'Siddharthnagar', 23, 'India'),
(580, 'Sonbhadra', 23, 'India'),
(581, 'Sant Ravidas Nagar', 23, 'India'),
(582, 'Sultanpur', 23, 'India'),
(583, 'Shravasti', 23, 'India'),
(584, 'Unnao', 23, 'India'),
(585, 'Varanasi', 23, 'India'),
(586, 'Birbhum', 24, 'India'),
(587, 'Bankura', 24, 'India'),
(588, 'Bardhaman', 24, 'India'),
(589, 'Darjeeling', 24, 'India'),
(590, 'Dakshin Dinajpur', 24, 'India'),
(591, 'Hooghly', 24, 'India'),
(592, 'Howrah', 24, 'India'),
(593, 'Jalpaiguri', 24, 'India'),
(594, 'Cooch Behar', 24, 'India'),
(595, 'Kolkata', 24, 'India'),
(596, 'Malda', 24, 'India'),
(597, 'Midnapore', 24, 'India'),
(598, 'Murshidabad', 24, 'India'),
(599, 'Nadia', 24, 'India'),
(600, 'North 24 Parganas', 24, 'India'),
(601, 'South 24 Parganas', 24, 'India'),
(602, 'Purulia', 24, 'India'),
(603, 'Uttar Dinajpur', 24, 'India');

-- --------------------------------------------------------

--
-- Table structure for table `pm_comment`
--

CREATE TABLE `pm_comment` (
  `id` int(11) NOT NULL,
  `id_user` int(11) DEFAULT NULL,
  `item_type` varchar(30) DEFAULT NULL,
  `id_item` int(11) DEFAULT NULL,
  `id_booking` bigint(20) DEFAULT NULL,
  `rating` varchar(100) DEFAULT NULL,
  `params` text,
  `checked` int(11) DEFAULT '0',
  `add_date` int(11) DEFAULT NULL,
  `edit_date` int(11) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `msg` longtext,
  `ip` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pm_comment`
--

INSERT INTO `pm_comment` (`id`, `id_user`, `item_type`, `id_item`, `id_booking`, `rating`, `params`, `checked`, `add_date`, `edit_date`, `name`, `email`, `msg`, `ip`) VALUES
(1, NULL, 'hotel', 2, NULL, '0', NULL, NULL, 1558694238, NULL, 'lodh moumita', 'moumitaslodh@gmail.com', 'need info', '182.156.196.50'),
(2, NULL, 'article', 1, NULL, NULL, NULL, NULL, 1558694761, NULL, 'lodh moumita', 'moumitaslodh@gmail.com', 'test info', '182.156.196.50'),
(3, NULL, 'hotel', 4, NULL, '0', NULL, NULL, 1558697390, NULL, 'sadsad', 'sadsa@ghgh.jhjh', 'sdsad', '182.156.196.50'),
(4, NULL, 'article', 1, NULL, NULL, NULL, NULL, 1558697451, NULL, 'dsfds', 'dsfds@ghg.hghg', 'dsfdsf', '182.156.196.50'),
(5, NULL, 'hotel', 5, NULL, '0', NULL, NULL, 1558961017, NULL, 'Das Protik', 'dp@yopmail.com', 'Test Comment this is for test ', '182.156.196.50'),
(6, NULL, 'hotel', 5, NULL, '0', NULL, NULL, 1558965121, NULL, 'Das Protik', 'dp@yopmail.com', 'Test Comment this is for test ', '182.156.196.50'),
(7, NULL, 'hotel', 10, NULL, '2', NULL, NULL, 1558967625, NULL, 'PrideMedia', 'prity.jha@met-technologies.com', 'Hi, This is for Testing Purpose', '182.156.196.50'),
(8, NULL, 'hotel', 4, NULL, '3', NULL, NULL, 1559039937, NULL, 'Das Gop', 'dg@yopmail.com', 'This is test comment.', '182.156.196.50'),
(9, NULL, 'hotel', 4, NULL, NULL, NULL, NULL, 1559049619, NULL, 'Das Gop', 'dg@yopmail.com', 'This is test comment.', '182.156.196.50'),
(10, NULL, 'article', 1, NULL, NULL, NULL, NULL, 1559216788, NULL, 'Das Hari', 'dh@yopmail.com', 'This is test comment.', '182.156.196.50'),
(11, NULL, 'article', 1, NULL, NULL, NULL, NULL, 1559234658, NULL, 'subhankar dutta', 'subhankard815@gmail.com', 'test comment', '182.156.196.50'),
(12, NULL, 'hotel', 2, NULL, '4', NULL, NULL, 1559285869, NULL, 'Santanu', 'asdf@asf.on', 'asdf', '182.156.196.50'),
(13, NULL, 'hotel', 6, NULL, '0', NULL, NULL, 1559294662, NULL, 'czxc', 'nnn@jjj.jjj', 'xczcx', '182.156.196.50'),
(14, NULL, 'hotel', 5, NULL, '4', NULL, NULL, 1559312119, NULL, 'Sonjoy Bhadra', 'sonjoy.bhadra@met-technologies.com', 'test', '182.156.196.50'),
(15, NULL, 'hotel', 10, NULL, NULL, NULL, 1, 1559557072, NULL, 'Subhankar Dutta', 'subhankar.dutta@met-technologies.com', 'sdfsfsdfsdfsdf', '182.156.196.50'),
(16, NULL, 'hotel', 10, NULL, NULL, NULL, 1, 1559557299, NULL, 'Subhankar Dutta', 'subhankar.dutta@met-technologies.com', 'gdgdfgfdgfdgfdg', '182.156.196.50'),
(17, NULL, 'hotel', 10, NULL, NULL, NULL, 1, 1559560045, NULL, 'Protik Das', 'protik@yopmail.com', 'dddd', '182.156.196.50'),
(18, NULL, 'hotel', 2, NULL, '4', NULL, 1, 1559573715, NULL, 'Subhankar Dutta', 'subhankar.dutta@met-technologies.com', 'Hi this is test comment ', '182.156.196.50'),
(19, NULL, 'hotel', 10, NULL, '4', NULL, 1, 1559654335, NULL, 'Sangita Nandy', 'sangita.nandy@met-technologies.com', 'this is very good ', '182.156.196.50'),
(20, NULL, 'article', 1, NULL, NULL, NULL, NULL, 1559730214, NULL, 'hhg', 'admin@gmail.com', 'hfhfghfh', '182.156.196.50'),
(21, NULL, 'article', 5, NULL, NULL, NULL, NULL, 1560467436, NULL, 'Rupam Dey', 'ba@fitser.com', 'kijasfc;jubnfdnsfcawnf;jnfen', '182.72.178.114'),
(22, NULL, 'article', 5, NULL, NULL, NULL, NULL, 1560467462, NULL, 'Rupam Dey', 'ba@fitser.com', 'kijasfc;jubnfdnsfcawnf;jnfen', '182.72.178.114'),
(23, NULL, 'article', 1, NULL, NULL, NULL, NULL, 1560498739, NULL, 'Test', 'mou@hmk.vmm', 'test', '182.72.178.114'),
(24, NULL, 'page', 1212, NULL, NULL, NULL, 1, 1560859612, 1560859612, 'Test Name', 'tn@yopmail.com', 'Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of \"de Finibus Bonorum et Malorum\" (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, \"Lorem ipsum dolor sit amet..\", comes from a line in section 1.10.32.', NULL),
(25, NULL, 'page', 22, NULL, NULL, NULL, 1, 1560859736, 1560859803, 'das manu', 'dm@yopmail.com', 'This is test comment.', '182.72.178.114'),
(26, NULL, 'hotel', 14, NULL, '3', NULL, 1, 1561206290, 1561206405, 'Arindam Dutta', 'arindam.dutta@met-technologies.com', 'test comment', '182.72.178.114'),
(27, NULL, 'hotel', 12, NULL, '0', NULL, NULL, 1561443578, NULL, 'Protik Das', 'protik@yopmail.com', 'Test Comment', '182.72.178.114'),
(28, NULL, 'hotel', 12, NULL, '3', NULL, NULL, 1561459533, NULL, 'Protik Das', 'protik@yopmail.com', 'This is test review.', '182.72.178.114'),
(29, NULL, 'article', 5, NULL, NULL, NULL, NULL, 1561541998, NULL, 'Sayantan', 'shayantanpal@gmail.com', 'Test comment section', '182.72.178.114'),
(30, NULL, 'article', 5, NULL, NULL, NULL, NULL, 1561544078, NULL, '64363456', '346@rye.ioi', 'hhdfgh', '182.72.178.114'),
(31, NULL, 'article', 5, NULL, NULL, NULL, NULL, 1561561260, NULL, 'pratap', 'roy.pratap@aa.uk', 'testing doine', '182.72.178.114'),
(32, NULL, 'article', 5, NULL, NULL, NULL, NULL, 1561561309, NULL, 'pratap', 'proy@channel4.co', 'testing doine', '182.72.178.114'),
(33, NULL, 'article', 5, NULL, NULL, NULL, NULL, 1561561324, NULL, 'pratap', 'proy@channel4.co.uk', 'testing doine', '182.72.178.114'),
(34, NULL, 'article', 5, NULL, NULL, NULL, NULL, 1561561343, NULL, 'pratapDGYWG767866987987', 'proy@channel4.co.uk', 'testing doine==============\r\nbaxhggdxhaGDSHgajhaJUUYU8Y', '182.72.178.114'),
(35, NULL, 'article', 5, NULL, NULL, NULL, NULL, 1561561387, NULL, 'pratapDGYWG767866987987RW5R35T4364564574575474584568', 'proy@channel4.co.uk', 'testing doine==============\r\nbaxhggdxhaGDSHgajhaJUUYU8Y', '182.72.178.114'),
(36, NULL, 'article', 5, NULL, NULL, NULL, NULL, 1561561400, NULL, 'pratapDGYWG767866987987RW5R35T436456457457547458456823342141112324235&&*(*(&*(&(*)&((', 'proy@channel4.co.uk', 'testing doine==============\r\nbaxhggdxhaGDSHgajhaJUUYU8Y', '182.72.178.114'),
(37, NULL, 'article', 5, NULL, NULL, NULL, NULL, 1561631668, NULL, 'Das Pal', 'dd@yopmail.com', 'this is test comment.', '182.72.178.114'),
(38, NULL, 'article', 5, NULL, NULL, NULL, NULL, 1561724114, NULL, 'subhankar dutta', 'subhankard815@gmail.com', 'test thsdbdfg', '182.72.178.114'),
(39, NULL, 'article', 5, NULL, NULL, NULL, NULL, 1561724127, NULL, 'subhankar dutta', 'subhankard815@gmail.com', 'test thsdbdfg', '182.72.178.114'),
(40, NULL, 'article', 5, NULL, NULL, NULL, NULL, 1561725345, NULL, 'subhankar dutta', 'subhankard815@gmail.com', 'test thsdbdfg', '182.72.178.114'),
(41, NULL, 'article', 5, NULL, NULL, NULL, NULL, 1562065174, NULL, 'sangita', 'sangita.nandy@metchnologies.com', 'test', '182.72.178.114'),
(42, NULL, 'article', 5, NULL, NULL, NULL, 1, 1562568805, 1562573523, 'Arshad Khan', 'arshad@mail.com', 'dzfdsf', '182.72.178.114'),
(43, NULL, 'hotel', 18, NULL, '4', NULL, 1, 1565332879, NULL, 'Tester Qa', 'dqa@yopmail.com', 'this is nice room.  this is nice room.  this is nice room.  this is nice room.  this is nice room.  this is nice room.  this is nice room.  this is nice room.  this is nice room.  this is nice room.  this is nice room.  this is nice room.  this is nice room.  this is nice room.  this is nice room.  this is nice room.  this is nice room.  this is nice room.  this is nice room.  this is nice room.  this is nice room.  this is nice room.  this is nice room.  this is nice room.  this is nice room.  this is nice room.  this is nice room.  this is nice room.  this is nice room.  this is nice room.  this is nice room.  this is nice room.  this is nice room.  this is nice room.  this is nice room.  this is nice room.  this is nice room.  this is nice room.  this is nice room.  this is nice room.  this is nice room.  this is nice room.  this is nice room.  this is nice room.  this is nice room.  this is nice room.  this is nice room.  this is nice room.  this is nice room.  ', '182.156.196.50'),
(44, NULL, 'hotel', 18, NULL, '4', NULL, 1, 1565333066, NULL, 'Tester Qa', 'dqa@yopmail.com', 'This is good hotel This is good hotel This is good hotel This is good hotel This is good hotel This is good hotel This is good hotel This is good hotel This is good hotel This is good hotel This is good hotel This is good hotel This is good hotel This is good hotel This is good hotel This is good hotel This is good hotel This is good hotel This is good hotel This is good hotel This is good hotel This is good hotel This is good hotel This is good hotel This is good hotel ', '182.156.196.50'),
(45, NULL, 'hotel', 13, NULL, '3', NULL, NULL, 1565342842, NULL, 'Prity Jha', 'prity.jha@met-technologies.com', 'nice room', '182.156.196.50'),
(49, NULL, 'hotel', 19, NULL, '5', 'a:2:{s:12:\"room_quality\";s:1:\"4\";s:12:\"room_service\";s:1:\"5\";}', NULL, 1573824125, NULL, 'Traveler Traveler', 'sonjoy.bhadra@met-technologies.com', 'dasdsadsad', '182.156.196.50'),
(50, NULL, 'hotel', 19, NULL, '4', 'a:5:{s:12:\"room_quality\";s:1:\"4\";s:11:\"hospitality\";s:1:\"5\";s:14:\"staff_courtesy\";s:1:\"4\";s:14:\"space_in_rooms\";s:1:\"4\";s:18:\"child_friendliness\";s:1:\"3\";}', 1, 1574084228, NULL, 'Traveler Traveler', 'sonjoy.bhadra@met-technologies.com', 'sdsdffsdf', '182.156.196.50'),
(51, NULL, 'hotel', 19, NULL, '4', 'a:5:{s:12:\"room_quality\";s:1:\"4\";s:11:\"hospitality\";s:1:\"5\";s:14:\"staff_courtesy\";s:1:\"4\";s:14:\"space_in_rooms\";s:1:\"3\";s:18:\"child_friendliness\";s:1:\"5\";}', 1, 1574086154, NULL, 'Traveler Traveler', 'sonjoy.bhadra@met-technologies.com', 'another test ', '182.156.196.50'),
(54, NULL, 'hotel', 19, NULL, '5', 'a:2:{s:12:\"room_quality\";s:1:\"5\";s:12:\"room_service\";s:1:\"4\";}', 1, 1575888802, NULL, 'Zafar Ansari', 'zafar.ansari@met-technologies.com', 'Test Comment', '182.156.196.50'),
(55, NULL, 'hotel', 19, NULL, '5', 'a:2:{s:12:\"room_quality\";s:1:\"5\";s:12:\"room_service\";s:1:\"4\";}', 1, 1575889073, NULL, 'Zafar Ansari', 'zafar.ansari@met-technologies.com', 'Test Comment', '182.156.196.50'),
(56, NULL, 'hotel', 19, NULL, '4.5', 'a:2:{s:12:\"room_quality\";s:1:\"5\";s:12:\"room_service\";s:1:\"4\";}', 1, 1575889130, NULL, 'Zafar Ansari', 'zafar.ansari@met-technologies.com', 'Test Comment', '182.156.196.50'),
(57, NULL, 'hotel', 19, NULL, '4.5', 'a:2:{s:12:\"room_quality\";s:1:\"4\";s:12:\"room_service\";s:1:\"5\";}', 1, 1575984380, NULL, 'Zafar Ansari', 'zafar.ansari@met-technologies.com', 'Test Comment', '182.156.196.50'),
(58, 237, 'hotel', 19, NULL, '4.5', 'a:2:{s:12:\"room_quality\";s:1:\"4\";s:12:\"room_service\";s:1:\"5\";}', 1, 1575984685, NULL, 'Zafar Ansari', 'zafar.ansari@met-technologies.com', 'Test Comment', '182.156.196.50'),
(59, 237, 'hotel', 19, NULL, '4.5', 'a:2:{s:12:\"room_quality\";s:1:\"4\";s:12:\"room_service\";s:1:\"5\";}', 1, 1576059960, NULL, 'Zafar Ansari', 'zafar.ansari@met-technologies.com', 'Test Comment', '182.156.196.50'),
(60, 237, 'hotel', 19, NULL, '4.5', 'a:2:{s:12:\"room_quality\";s:1:\"4\";s:12:\"room_service\";s:1:\"5\";}', 1, 1576151256, NULL, 'Zafar Ansari', 'zafar.ansari@met-technologies.com', 'Test Comment', '182.156.196.50'),
(61, 266, 'hotel', 21, NULL, '0', 'a:4:{s:12:\"Room Quality\";s:1:\"0\";s:11:\"Hospitality\";s:1:\"0\";s:14:\"Space In Rooms\";s:1:\"0\";s:18:\"Child Friendliness\";s:1:\"0\";}', 1, 1576151948, NULL, 'sagar nayak', 'sagar@gmail.com', '', '182.156.196.50'),
(62, 266, 'hotel', 21, NULL, '2.25', 'a:4:{s:12:\"Room Quality\";s:1:\"1\";s:11:\"Hospitality\";s:1:\"2\";s:14:\"Space In Rooms\";s:1:\"2\";s:18:\"Child Friendliness\";s:1:\"4\";}', 1, 1576151990, NULL, 'sagar nayak', 'sagar@gmail.com', 'great stay.', '182.156.196.50'),
(63, 266, 'hotel', 22, NULL, '1.2', 'a:5:{s:12:\"Room Quality\";s:1:\"0\";s:11:\"Hospitality\";s:1:\"0\";s:14:\"Staff Courtesy\";s:1:\"0\";s:14:\"Space in Rooms\";s:1:\"3\";s:18:\"Child friendliness\";s:1:\"3\";}', 1, 1576155689, NULL, 'sagar nayak', 'sagar@gmail.com', 'nfgnfgndgndgn\ndn\ndg\nnd\ngn\ndgn\ndn\nd\nnd\nn\ndgn\ndgn\ndg\n\nn\ndn\nd\nnd\nn\ndg\nn\ndgn\nds\nn', '182.156.196.50'),
(64, 266, 'hotel', 22, NULL, '3.8', 'a:5:{s:12:\"Room Quality\";s:1:\"3\";s:11:\"Hospitality\";s:1:\"3\";s:14:\"Staff Courtesy\";s:1:\"5\";s:14:\"Space in Rooms\";s:1:\"5\";s:18:\"Child friendliness\";s:1:\"3\";}', 1, 1576158918, NULL, 'sagar nayak', 'sagar@gmail.com', 'nice', '182.156.196.50'),
(65, 255, 'hotel', 21, NULL, '4', 'a:4:{s:12:\"Room Quality\";s:1:\"3\";s:11:\"Hospitality\";s:1:\"3\";s:14:\"Space In Rooms\";s:1:\"5\";s:18:\"Child Friendliness\";s:1:\"5\";}', 1, 1576585330, NULL, 'Anup Bora', 'anup@gmail.com', 'nice', '182.156.196.50'),
(66, 266, 'hotel', 19, NULL, '0', 'a:5:{s:12:\"Room Quality\";s:1:\"0\";s:11:\"Hospitality\";s:1:\"0\";s:14:\"Staff Courtesy\";s:1:\"0\";s:14:\"Space in Rooms\";s:1:\"0\";s:18:\"Child Friendliness\";s:1:\"0\";}', 2, 1578056305, 1578551372, 'Sagar Nayak', 'sagar@gmail.com', '', '42.110.131.168'),
(68, NULL, 'hotel', 19, 10390, '4.4', 'a:5:{s:12:\"room_quality\";s:1:\"4\";s:11:\"hospitality\";s:1:\"5\";s:14:\"staff_courtesy\";s:1:\"4\";s:14:\"space_in_rooms\";s:1:\"5\";s:18:\"child_friendliness\";s:1:\"4\";}', 1, 1578569495, NULL, 'Traveler Traveler', 'sonjoy.bhadra@met-technologies.com', 'hi this test review', '182.156.196.50'),
(69, 338, 'hotel', 19, NULL, '3.4', 'a:5:{s:12:\"Room Quality\";s:1:\"3\";s:11:\"Hospitality\";s:1:\"4\";s:14:\"Staff Courtesy\";s:1:\"3\";s:14:\"Space in Rooms\";s:1:\"4\";s:18:\"Child Friendliness\";s:1:\"3\";}', 1, 1579164208, NULL, 'abdd def', 'Pritam@gmail.com', 'Good', '47.15.238.212'),
(70, NULL, 'hotel', 30, 10438, '4', 'N;', 1, 1579174031, NULL, 'Traveler Traveler', 'sonjoy.bhadra@met-technologies.com', 'this is test review', '182.156.196.50'),
(71, 338, 'hotel', 19, NULL, '2.2', 'a:5:{s:12:\"Room Quality\";s:1:\"0\";s:11:\"Hospitality\";s:1:\"4\";s:14:\"Staff Courtesy\";s:1:\"4\";s:14:\"Space in Rooms\";s:1:\"0\";s:18:\"Child Friendliness\";s:1:\"3\";}', 1, 1579409598, NULL, 'abdd def', 'Pritam@gmail.com', '', '42.110.193.70'),
(72, 237, 'hotel', 19, NULL, '3.1666666666667', 'a:6:{s:12:\"Room Quality\";s:1:\"3\";s:11:\"Hospitality\";s:1:\"4\";s:14:\"Staff Courtesy\";s:1:\"4\";s:14:\"Space in Rooms\";s:1:\"4\";s:18:\"Child Friendliness\";s:1:\"4\";s:0:\"\";s:0:\"\";}', 1, 1579507822, NULL, 'Zafar Ansarii', 'zafar.ansari@met-technologies.com', 'abcd', '182.156.196.50'),
(73, 237, 'hotel', 19, NULL, '4.2', 'a:5:{s:12:\"Room Quality\";s:1:\"3\";s:11:\"Hospitality\";s:1:\"4\";s:14:\"Staff Courtesy\";s:1:\"4\";s:14:\"Space in Rooms\";s:1:\"5\";s:18:\"Child Friendliness\";s:1:\"5\";}', 1, 1579508314, NULL, 'Zafar Ansarii', 'zafar.ansari@met-technologies.com', 'abcd', '182.156.196.50'),
(74, 338, 'hotel', 19, NULL, '0', 'a:5:{s:12:\"Room Quality\";s:1:\"0\";s:11:\"Hospitality\";s:1:\"0\";s:14:\"Staff Courtesy\";s:1:\"0\";s:14:\"Space in Rooms\";s:1:\"0\";s:18:\"Child Friendliness\";s:1:\"0\";}', 1, 1579508651, NULL, 'abdd def', 'Pritam@gmail.com', '', '47.15.248.239'),
(75, 338, 'hotel', 19, NULL, '0', 'a:5:{s:12:\"Room Quality\";s:1:\"0\";s:11:\"Hospitality\";s:1:\"0\";s:14:\"Staff Courtesy\";s:1:\"0\";s:14:\"Space in Rooms\";s:1:\"0\";s:18:\"Child Friendliness\";s:1:\"0\";}', 1, 1579508662, NULL, 'abdd def', 'Pritam@gmail.com', '', '47.15.148.52'),
(76, 338, 'hotel', 19, NULL, '0', 'a:5:{s:12:\"Room Quality\";s:1:\"0\";s:11:\"Hospitality\";s:1:\"0\";s:14:\"Staff Courtesy\";s:1:\"0\";s:14:\"Space in Rooms\";s:1:\"0\";s:18:\"Child Friendliness\";s:1:\"0\";}', 1, 1579508810, NULL, 'abdd def', 'Pritam@gmail.com', '', '47.15.148.52'),
(77, 237, 'hotel', 19, NULL, '4.6', 'a:5:{s:12:\"Room Quality\";s:1:\"4\";s:11:\"Hospitality\";s:1:\"4\";s:14:\"Staff Courtesy\";s:1:\"5\";s:14:\"Space in Rooms\";s:1:\"5\";s:18:\"Child Friendliness\";s:1:\"5\";}', 1, 1579509897, NULL, 'Zafar Ansarii', 'zafar.ansari@met-technologies.com', '', '182.156.196.50'),
(78, 237, 'hotel', 19, NULL, '0', 'a:1:{s:0:\"\";s:0:\"\";}', 1, 1579510100, NULL, 'Zafar Ansarii', 'zafar.ansari@met-technologies.com', '\nBbbvhbvbv', '182.156.196.50'),
(79, 237, 'hotel', 19, NULL, '4.8', 'a:5:{s:12:\"Room Quality\";s:1:\"5\";s:11:\"Hospitality\";s:1:\"5\";s:14:\"Staff Courtesy\";s:1:\"4\";s:14:\"Space in Rooms\";s:1:\"5\";s:18:\"Child Friendliness\";s:1:\"5\";}', 1, 1579510546, NULL, 'Zafar Ansarii', 'zafar.ansari@met-technologies.com', 'Hbhbhbhbhbhbhbhbhb', '182.156.196.50'),
(80, 338, 'hotel', 19, NULL, '0', 'a:5:{s:12:\"Room Quality\";s:1:\"0\";s:11:\"Hospitality\";s:1:\"0\";s:14:\"Staff Courtesy\";s:1:\"0\";s:14:\"Space in Rooms\";s:1:\"0\";s:18:\"Child Friendliness\";s:1:\"0\";}', 1, 1579510627, NULL, 'abdd def', 'Pritam@gmail.com', 'Jjj', '47.15.148.52'),
(81, 237, 'hotel', 19, NULL, '1.6', 'a:3:{s:12:\"Room Quality\";s:1:\"4\";s:0:\"\";s:0:\"\";s:14:\"Staff Courtesy\";s:1:\"4\";}', 1, 1579510905, NULL, 'Zafar Ansarii', 'zafar.ansari@met-technologies.com', 'Jhjhjhjhjhj', '182.156.196.50'),
(82, 237, 'hotel', 19, NULL, '0', 'a:1:{s:0:\"\";s:0:\"\";}', 1, 1579529862, NULL, 'Zafar Ansarii', 'zafar.ansari@met-technologies.com', '', '182.156.196.50'),
(83, 237, 'hotel', 19, NULL, '3', 'a:5:{s:12:\"Room Quality\";s:1:\"3\";s:0:\"\";s:0:\"\";s:14:\"Staff Courtesy\";s:1:\"3\";s:14:\"Space in Rooms\";s:1:\"4\";s:18:\"Child Friendliness\";s:1:\"5\";}', 1, 1580464954, NULL, 'Zafar Ansarii', 'zafar.ansari@met-technologies.com', 'Hkbbnmgjn bhkkmn. ', '182.156.196.50'),
(84, 338, 'hotel', 19, NULL, '3.6', 'a:5:{s:12:\"Room Quality\";s:1:\"3\";s:11:\"Hospitality\";s:1:\"3\";s:14:\"Staff Courtesy\";s:1:\"3\";s:14:\"Space in Rooms\";s:1:\"4\";s:18:\"Child Friendliness\";s:1:\"5\";}', 1, 1580464954, NULL, 'abdd def', 'Pritam@gmail.com', 'Khkhkhkhcvj  bbjkll vjlll xdjkm', '47.15.206.234'),
(85, 347, 'hotel', 19, NULL, '0', 'a:5:{s:12:\"Room Quality\";s:1:\"0\";s:11:\"Hospitality\";s:1:\"0\";s:14:\"Staff Courtesy\";s:1:\"0\";s:14:\"Space in Rooms\";s:1:\"0\";s:18:\"Child Friendliness\";s:1:\"0\";}', 1, 1581499753, NULL, 'Traveler Traveler', 'ios@yopmail.com', '', '182.156.196.50'),
(86, NULL, 'hotel', 19, 10567, '2.6', 'a:5:{s:12:\"room_quality\";s:1:\"2\";s:11:\"hospitality\";s:1:\"3\";s:14:\"staff_courtesy\";s:1:\"2\";s:14:\"space_in_rooms\";s:1:\"4\";s:18:\"child_friendliness\";s:1:\"2\";}', NULL, 1581500076, NULL, 'Traveler Traveler', 'ios@yopmail.com', '3', '182.156.196.50'),
(87, NULL, 'hotel', 19, 10408, '4', 'a:2:{s:14:\"staff_courtesy\";s:1:\"3\";s:14:\"space_in_rooms\";s:1:\"5\";}', 2, 1583484393, NULL, 'Traveler Traveler', 'qa2210@yopmail.com', 'this is test comment', '182.156.196.50'),
(88, NULL, 'hotel', 30, 10591, '4', 'a:3:{s:9:\"cleanness\";s:1:\"3\";s:18:\"quality_of_service\";s:1:\"4\";s:13:\"customer_care\";s:1:\"5\";}', 1, 1596189850, 1596189932, 'Protiek QA', 'qa21@yopmail.com', 'This is test Comment, This is test comment, This is test comment, This is test Comment, This is test comment, This is test comment, This is test Comment, This is test comment, This is test comment, This is test Comment, This is test comment, This is test comment good', '103.121.156.146');

-- --------------------------------------------------------

--
-- Table structure for table `pm_country`
--

CREATE TABLE `pm_country` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `code` varchar(3) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pm_country`
--

INSERT INTO `pm_country` (`id`, `name`, `code`) VALUES
(1, 'Afghanistan', 'AF'),
(2, 'Åland', 'AX'),
(3, 'Albania', 'AL'),
(4, 'Algeria', 'DZ'),
(5, 'American Samoa', 'AS'),
(6, 'Andorra', 'AD'),
(7, 'Angola', 'AO'),
(8, 'Anguilla', 'AI'),
(9, 'Antarctica', 'AQ'),
(10, 'Antigua and Barbuda', 'AG'),
(11, 'Argentina', 'AR'),
(12, 'Armenia', 'AM'),
(13, 'Aruba', 'AW'),
(14, 'Australia', 'AU'),
(15, 'Austria', 'AT'),
(16, 'Azerbaijan', 'AZ'),
(17, 'Bahamas', 'BS'),
(18, 'Bahrain', 'BH'),
(19, 'Bangladesh', 'BD'),
(20, 'Barbados', 'BB'),
(21, 'Belarus', 'BY'),
(22, 'Belgium', 'BE'),
(23, 'Belize', 'BZ'),
(24, 'Benin', 'BJ'),
(25, 'Bermuda', 'BM'),
(26, 'Bhutan', 'BT'),
(27, 'Bolivia', 'BO'),
(28, 'Bonaire', 'BQ'),
(29, 'Bosnia and Herzegovina', 'BA'),
(30, 'Botswana', 'BW'),
(31, 'Bouvet Island', 'BV'),
(32, 'Brazil', 'BR'),
(33, 'British Indian Ocean Territory', 'IO'),
(34, 'British Virgin Islands', 'VG'),
(35, 'Brunei', 'BN'),
(36, 'Bulgaria', 'BG'),
(37, 'Burkina Faso', 'BF'),
(38, 'Burundi', 'BI'),
(39, 'Cambodia', 'KH'),
(40, 'Cameroon', 'CM'),
(41, 'Canada', 'CA'),
(42, 'Cape Verde', 'CV'),
(43, 'Cayman Islands', 'KY'),
(44, 'Central African Republic', 'CF'),
(45, 'Chad', 'TD'),
(46, 'Chile', 'CL'),
(47, 'China', 'CN'),
(48, 'Christmas Island', 'CX'),
(49, 'Cocos [Keeling] Islands', 'CC'),
(50, 'Colombia', 'CO'),
(51, 'Comoros', 'KM'),
(52, 'Cook Islands', 'CK'),
(53, 'Costa Rica', 'CR'),
(54, 'Croatia', 'HR'),
(55, 'Cuba', 'CU'),
(56, 'Curacao', 'CW'),
(57, 'Cyprus', 'CY'),
(58, 'Czech Republic', 'CZ'),
(59, 'Democratic Republic of the Congo', 'CD'),
(60, 'Denmark', 'DK'),
(61, 'Djibouti', 'DJ'),
(62, 'Dominica', 'DM'),
(63, 'Dominican Republic', 'DO'),
(64, 'East Timor', 'TL'),
(65, 'Ecuador', 'EC'),
(66, 'Egypt', 'EG'),
(67, 'El Salvador', 'SV'),
(68, 'Equatorial Guinea', 'GQ'),
(69, 'Eritrea', 'ER'),
(70, 'Estonia', 'EE'),
(71, 'Ethiopia', 'ET'),
(72, 'Falkland Islands', 'FK'),
(73, 'Faroe Islands', 'FO'),
(74, 'Fiji', 'FJ'),
(75, 'Finland', 'FI'),
(76, 'France', 'FR'),
(77, 'French Guiana', 'GF'),
(78, 'French Polynesia', 'PF'),
(79, 'French Southern Territories', 'TF'),
(80, 'Gabon', 'GA'),
(81, 'Gambia', 'GM'),
(82, 'Georgia', 'GE'),
(83, 'Germany', 'DE'),
(84, 'Ghana', 'GH'),
(85, 'Gibraltar', 'GI'),
(86, 'Greece', 'GR'),
(87, 'Greenland', 'GL'),
(88, 'Grenada', 'GD'),
(89, 'Guadeloupe', 'GP'),
(90, 'Guam', 'GU'),
(91, 'Guatemala', 'GT'),
(92, 'Guernsey', 'GG'),
(93, 'Guinea', 'GN'),
(94, 'Guinea-Bissau', 'GW'),
(95, 'Guyana', 'GY'),
(96, 'Haiti', 'HT'),
(97, 'Heard Island and McDonald Islands', 'HM'),
(98, 'Honduras', 'HN'),
(99, 'Hong Kong', 'HK'),
(100, 'Hungary', 'HU'),
(101, 'Iceland', 'IS'),
(102, 'India', 'IN'),
(103, 'Indonesia', 'ID'),
(104, 'Iran', 'IR'),
(105, 'Iraq', 'IQ'),
(106, 'Ireland', 'IE'),
(107, 'Isle of Man', 'IM'),
(108, 'Israel', 'IL'),
(109, 'Italy', 'IT'),
(110, 'Ivory Coast', 'CI'),
(111, 'Jamaica', 'JM'),
(112, 'Japan', 'JP'),
(113, 'Jersey', 'JE'),
(114, 'Jordan', 'JO'),
(115, 'Kazakhstan', 'KZ'),
(116, 'Kenya', 'KE'),
(117, 'Kiribati', 'KI'),
(118, 'Kosovo', 'XK'),
(119, 'Kuwait', 'KW'),
(120, 'Kyrgyzstan', 'KG'),
(121, 'Laos', 'LA'),
(122, 'Latvia', 'LV'),
(123, 'Lebanon', 'LB'),
(124, 'Lesotho', 'LS'),
(125, 'Liberia', 'LR'),
(126, 'Libya', 'LY'),
(127, 'Liechtenstein', 'LI'),
(128, 'Lithuania', 'LT'),
(129, 'Luxembourg', 'LU'),
(130, 'Macao', 'MO'),
(131, 'Macedonia', 'MK'),
(132, 'Madagascar', 'MG'),
(133, 'Malawi', 'MW'),
(134, 'Malaysia', 'MY'),
(135, 'Maldives', 'MV'),
(136, 'Mali', 'ML'),
(137, 'Malta', 'MT'),
(138, 'Marshall Islands', 'MH'),
(139, 'Martinique', 'MQ'),
(140, 'Mauritania', 'MR'),
(141, 'Mauritius', 'MU'),
(142, 'Mayotte', 'YT'),
(143, 'Mexico', 'MX'),
(144, 'Micronesia', 'FM'),
(145, 'Moldova', 'MD'),
(146, 'Monaco', 'MC'),
(147, 'Mongolia', 'MN'),
(148, 'Montenegro', 'ME'),
(149, 'Montserrat', 'MS'),
(150, 'Morocco', 'MA'),
(151, 'Mozambique', 'MZ'),
(152, 'Myanmar [Burma]', 'MM'),
(153, 'Namibia', 'NA'),
(154, 'Nauru', 'NR'),
(155, 'Nepal', 'NP'),
(156, 'Netherlands', 'NL'),
(157, 'New Caledonia', 'NC'),
(158, 'New Zealand', 'NZ'),
(159, 'Nicaragua', 'NI'),
(160, 'Niger', 'NE'),
(161, 'Nigeria', 'NG'),
(162, 'Niue', 'NU'),
(163, 'Norfolk Island', 'NF'),
(164, 'North Korea', 'KP'),
(165, 'Northern Mariana Islands', 'MP'),
(166, 'Norway', 'NO'),
(167, 'Oman', 'OM'),
(168, 'Pakistan', 'PK'),
(169, 'Palau', 'PW'),
(170, 'Palestine', 'PS'),
(171, 'Panama', 'PA'),
(172, 'Papua New Guinea', 'PG'),
(173, 'Paraguay', 'PY'),
(174, 'Peru', 'PE'),
(175, 'Philippines', 'PH'),
(176, 'Pitcairn Islands', 'PN'),
(177, 'Poland', 'PL'),
(178, 'Portugal', 'PT'),
(179, 'Puerto Rico', 'PR'),
(180, 'Qatar', 'QA'),
(181, 'Republic of the Congo', 'CG'),
(182, 'Réunion', 'RE'),
(183, 'Romania', 'RO'),
(184, 'Russia', 'RU'),
(185, 'Rwanda', 'RW'),
(186, 'Saint Barthélemy', 'BL'),
(187, 'Saint Helena', 'SH'),
(188, 'Saint Kitts and Nevis', 'KN'),
(189, 'Saint Lucia', 'LC'),
(190, 'Saint Martin', 'MF'),
(191, 'Saint Pierre and Miquelon', 'PM'),
(192, 'Saint Vincent and the Grenadines', 'VC'),
(193, 'Samoa', 'WS'),
(194, 'San Marino', 'SM'),
(195, 'São Tomé and Príncipe', 'ST'),
(196, 'Saudi Arabia', 'SA'),
(197, 'Senegal', 'SN'),
(198, 'Serbia', 'RS'),
(199, 'Seychelles', 'SC'),
(200, 'Sierra Leone', 'SL'),
(201, 'Singapore', 'SG'),
(202, 'Sint Maarten', 'SX'),
(203, 'Slovakia', 'SK'),
(204, 'Slovenia', 'SI'),
(205, 'Solomon Islands', 'SB'),
(206, 'Somalia', 'SO'),
(207, 'South Africa', 'ZA'),
(208, 'South Georgia and the South Sandwich Islands', 'GS'),
(209, 'South Korea', 'KR'),
(210, 'South Sudan', 'SS'),
(211, 'Spain', 'ES'),
(212, 'Sri Lanka', 'LK'),
(213, 'Sudan', 'SD'),
(214, 'Suriname', 'SR'),
(215, 'Svalbard and Jan Mayen', 'SJ'),
(216, 'Swaziland', 'SZ'),
(217, 'Sweden', 'SE'),
(218, 'Switzerland', 'CH'),
(219, 'Syria', 'SY'),
(220, 'Taiwan', 'TW'),
(221, 'Tajikistan', 'TJ'),
(222, 'Tanzania', 'TZ'),
(223, 'Thailand', 'TH'),
(224, 'Togo', 'TG'),
(225, 'Tokelau', 'TK'),
(226, 'Tonga', 'TO'),
(227, 'Trinidad and Tobago', 'TT'),
(228, 'Tunisia', 'TN'),
(229, 'Turkey', 'TR'),
(230, 'Turkmenistan', 'TM'),
(231, 'Turks and Caicos Islands', 'TC'),
(232, 'Tuvalu', 'TV'),
(233, 'U.S. Minor Outlying Islands', 'UM'),
(234, 'U.S. Virgin Islands', 'VI'),
(235, 'Uganda', 'UG'),
(236, 'Ukraine', 'UA'),
(237, 'United Arab Emirates', 'AE'),
(238, 'United Kingdom', 'GB'),
(239, 'United States', 'US'),
(240, 'Uruguay', 'UY'),
(241, 'Uzbekistan', 'UZ'),
(242, 'Vanuatu', 'VU'),
(243, 'Vatican City', 'VA'),
(244, 'Venezuela', 'VE'),
(245, 'Vietnam', 'VN'),
(246, 'Wallis and Futuna', 'WF'),
(247, 'Western Sahara', 'EH'),
(248, 'Yemen', 'YE'),
(249, 'Zambia', 'ZM'),
(250, 'Zimbabwe', 'ZW');

-- --------------------------------------------------------

--
-- Table structure for table `pm_coupon`
--

CREATE TABLE `pm_coupon` (
  `id` int(11) NOT NULL,
  `users` int(11) NOT NULL,
  `title` varchar(250) DEFAULT NULL,
  `code` varchar(50) DEFAULT NULL,
  `discount` double DEFAULT '0',
  `discount_type` varchar(10) DEFAULT NULL,
  `id_hotel` int(11) DEFAULT NULL,
  `rooms` text,
  `checked` int(11) DEFAULT '0',
  `publish_date` int(11) DEFAULT NULL,
  `unpublish_date` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pm_coupon`
--

INSERT INTO `pm_coupon` (`id`, `users`, `title`, `code`, `discount`, `discount_type`, `id_hotel`, `rooms`, `checked`, `publish_date`, `unpublish_date`) VALUES
(1, 1, 'COUPON', 'TEST2019', 10, 'rate', NULL, '32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,48,49,51,52,53', 1, 1569224280, NULL),
(2, 1, 'qacop', 'yoyo', 10, 'fixed', 20, '32,33', 1, 1577090580, 1596240000);

-- --------------------------------------------------------

--
-- Table structure for table `pm_currency`
--

CREATE TABLE `pm_currency` (
  `id` int(11) NOT NULL,
  `code` varchar(5) DEFAULT NULL,
  `sign` varchar(5) DEFAULT NULL,
  `main` int(11) DEFAULT '0',
  `rank` int(11) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pm_currency`
--

INSERT INTO `pm_currency` (`id`, `code`, `sign`, `main`, `rank`) VALUES
(1, 'USD', '$', 0, 1),
(2, 'EUR', '€', 0, 2),
(3, 'GBP', '£', 0, 3),
(4, 'INR', 'Rs', 1, 4),
(5, 'AUD', 'A$', 0, 5),
(6, 'CAD', 'C$', 0, 6),
(7, 'CNY', '¥', 0, 7),
(8, 'TRY', '₺', 0, 8),
(9, 'THB', '฿', NULL, 9);

-- --------------------------------------------------------

--
-- Table structure for table `pm_destination`
--

CREATE TABLE `pm_destination` (
  `id` int(11) NOT NULL,
  `lang` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `title` varchar(250) DEFAULT NULL,
  `subtitle` varchar(250) DEFAULT NULL,
  `title_tag` varchar(250) DEFAULT NULL,
  `alias` varchar(100) DEFAULT NULL,
  `descr` text,
  `text` longtext,
  `video` text,
  `lat` double DEFAULT NULL,
  `lng` double DEFAULT NULL,
  `home` int(11) DEFAULT '0',
  `checked` int(11) DEFAULT '0',
  `rank` int(11) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pm_destination`
--

INSERT INTO `pm_destination` (`id`, `lang`, `name`, `title`, `subtitle`, `title_tag`, `alias`, `descr`, `text`, `video`, `lat`, `lng`, `home`, `checked`, `rank`) VALUES
(1, 2, 'Kolkata', 'Kolkata', 'Kolkata,west bengal,india', 'Kolkata', 'kolkata', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. ', '<p>Kolkata</p>\r\n', 'https://www.youtube.com/watch?v=EqPtz5qN7HM', 22.56263, 88.36304, 0, 1, 2),
(2, 2, 'Mumbai', 'Mumbai', 'Mumbai, 17, Shri S. R. Marg', 'Mumbai', 'mumbai', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum dictum ut nisi eu tristique. Proin malesuada sagittis est, at porta velit. Vestibulum nec scelerisque nisi. ', '<p>Mumbai</p>\r\n', 'https://www.youtube.com/watch?v=EqPtz5qN7HM', 22.56263, 88.36304, 0, 1, 3),
(3, 2, 'Delhi', 'Delhi', 'Delhi, City centre', 'Delhi', 'delhi', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum dictum ut nisi eu tristique. Proin malesuada sagittis est, at porta velit. Vestibulum nec scelerisque nisi. ', '<p>Delhi</p>\r\n', 'https://www.youtube.com/watch?v=EqPtz5qN7HM', 22.56263, 88.36304, 0, 1, 4);

-- --------------------------------------------------------

--
-- Table structure for table `pm_destination_file`
--

CREATE TABLE `pm_destination_file` (
  `id` int(11) NOT NULL,
  `lang` int(11) NOT NULL,
  `id_item` int(11) NOT NULL,
  `home` int(11) DEFAULT '0',
  `checked` int(11) DEFAULT '1',
  `rank` int(11) DEFAULT '0',
  `file` varchar(250) DEFAULT NULL,
  `label` varchar(250) DEFAULT NULL,
  `type` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pm_destination_file`
--

INSERT INTO `pm_destination_file` (`id`, `lang`, `id_item`, `home`, `checked`, `rank`, `file`, `label`, `type`) VALUES
(1, 2, 1, NULL, 1, 1, '0-81923500-1538998009-k1.jpg', '', 'image'),
(2, 2, 2, NULL, 1, 2, 'taj-mahal-hotel.jpeg', '', 'image'),
(3, 2, 3, NULL, 1, 3, 'best-luxury-hotels-in-new-delhi.jpg', '', 'image');

-- --------------------------------------------------------

--
-- Table structure for table `pm_device`
--

CREATE TABLE `pm_device` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `device_type` int(11) NOT NULL,
  `fcm_token` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pm_device`
--

INSERT INTO `pm_device` (`id`, `user_id`, `device_type`, `fcm_token`) VALUES
(1, 255, 1, 'cQ6ibRfUeKc:APA91bFXvZYjxzvCDEP90TPxtbDl42dqxmTNImwieBsUdUjOMzhcMk2USMCmF9XsGt9MrYJoBlKjE2FhqD3O0o0Zp5VP9eMhjjHjkS5BFuHFqDAfuDO05DQ5xAo8jnWkMqjboVOYzPWP'),
(2, 283, 1, 'fRGqVEYIAtw:APA91bHqZfa4cjMWNr_VQaZ3ECx-coUi37MJNh_DqLXN6MWfIeEqNKkKA0Cr8FaTlzMkqxkFIcBcxiO1Q9_uC8UeLsSZW_thOUnsATPbMDEdP8s9wYhV5bWe4S8qIPezvshAQtPMZ5zG'),
(3, 252, 1, 'ghmnhffgdfgndndghmngfmnghm'),
(4, 232, 1, 'd5hNVPnSilo:APA91bH4agqcxDdEAXuVA3oT6_hdrcsU75R1OLvARK3q4kg37TNHp6KXUasVvPbmJ35aWlnEHTBr7NAxjVDE8EaVTR41_A6iXqFGrayPk9FZk1ytXWgZouv5fFeopxb_8yz6StsNJO3p'),
(7, 297, 1, 'fRGqVEYIAtw:APA91bHqZfa4cjMWNr_VQaZ3ECx-coUi37MJNh_DqLXN6MWfIeEqNKkKA0Cr8FaTlzMkqxkFIcBcxiO1Q9_uC8UeLsSZW_thOUnsATPbMDEdP8s9wYhV5bWe4S8qIPezvshAQtPMZ5zG'),
(10, 300, 1, 'd2RcK6im7mU:APA91bH3qGiPZrtRa2suRNaBPLacFhM06JSgjEiG6dlsGZNP0BFRfYxhSNf7B1ouz12Ec6nUBMJNrVYJDi4CzCglPvbDi_WwZuTMPIeNe6U-_T2GxHJvvJNM-C-UnqtQI2YqGKwQynf7'),
(13, 303, 1, 'd5HeMlb6kpw:APA91bFo4vwxMWMgGjpNhDdrm1jXjsf9zuhj7snE1jD3CwywTR-Cg8wjWGOVND2vzKrs5UVNYk3e_FCjMxRdK_GI1wIADqBfsNdvDKkhIg9jYNayUI9W_gMLPeTSnc0zlIZNquZkoUt4'),
(14, 266, 1, 'dceir_S-89M:APA91bEW3R-BBULhPKi0-4G8Wmjfcbio547USD6NhlUN_SJ7br_7ZMEgKs6MYhFrzFRfkqzYI2OS6l7z-2N_oyLoSLL1V8LJstnKRw4ym3Avtfuzt4PeEcCOzGgMxKiqBJpb4vTvFG7r'),
(15, 266, 1, 'dceir_S-89M:APA91bEW3R-BBULhPKi0-4G8Wmjfcbio547USD6NhlUN_SJ7br_7ZMEgKs6MYhFrzFRfkqzYI2OS6l7z-2N_oyLoSLL1V8LJstnKRw4ym3Avtfuzt4PeEcCOzGgMxKiqBJpb4vTvFG7r'),
(16, 278, 1, 'c4Sxxav-hCA:APA91bEq9EWj_uu5YupJpPb3IitU6sy7l4ZH5FS-xWI4ZRsQDeJhLSaiG2WnY2RDh3AbqpHSKoBxu-irelwMRLSEecKX_vqHm46EFP0-HMfad2vF_YQh2JmV_p40P8UehY_8RgxMbZEv'),
(17, 304, 1, 'f2pB7jR0TU8:APA91bFSB-WPQiT-fQ7Mmo1ICgJk0OO_Gl3bW4QQOA6rfXrHp94Au4Ym7ayKpDGGEhe-OCC88yokbwPwBRHxJJjl0aeYnkWhhmt0jOE5Fp0DV7yXLjZVtZcJjaqe_i4_3KIKQwoLLtRk'),
(18, 305, 1, 'exWcHCCsm1I:APA91bE1QO_k-XxaKkwPBcW9jOhlXmrGrce6MT7s4z_-jVJOmtey_Imqy-dDbzTfKClVk16drnWzd5AMgExVvbnPOiqipH-Kn1xVE_2aJTkJsjLVWVkW46cQqUtf0r65zmbu1fbK93Yt'),
(19, 306, 1, 'fRGqVEYIAtw:APA91bHqZfa4cjMWNr_VQaZ3ECx-coUi37MJNh_DqLXN6MWfIeEqNKkKA0Cr8FaTlzMkqxkFIcBcxiO1Q9_uC8UeLsSZW_thOUnsATPbMDEdP8s9wYhV5bWe4S8qIPezvshAQtPMZ5zG'),
(20, 307, 1, 'eqsJEVLEpyg:APA91bHwt--g74Ao4FLxeIaxxAOcxphBTC_zc3kB2SCyFKMmwtJM-yQt-UR7h3rtOUV_c-wtwZd7iinBsqca7tY8bKeuhAe6KfzEcuoUdAHb_eW_4TnNLB8h2YmxPKU_if6NFcmo1HXg'),
(21, 268, 1, 'cQ6ibRfUeKc:APA91bFXvZYjxzvCDEP90TPxtbDl42dqxmTNImwieBsUdUjOMzhcMk2USMCmF9XsGt9MrYJoBlKjE2FhqD3O0o0Zp5VP9eMhjjHjkS5BFuHFqDAfuDO05DQ5xAo8jnWkMqjboVOYzPWP'),
(22, 308, 1, 'cOZLg5-AnKA:APA91bHJ7_34_lYfvcxZdFwzmUMVqtxhjLuuS7QIE_436shU37pRGs2zxbKxRTJw72ZP9fBX7xoaG-bIIexGRE4g7AnN27zIvz3dWubp0n-bQThvjMsWuOgz6bmfD74XUwtZXyNqICRA'),
(23, 229, 1, 'cUrnRNluiiQ:APA91bHi-NgUHWKC0_c9SHnwVPMWFvYNh0Z5lrxU9Ul5bTTPFGranhy86VOWyBuIaJKlNOO2BVQnTf7to3dk9rbKRsMxo4k13P6fmEjhitN8Eotpt-y4GN3MSg9TXxdL8ziwXfk6SRwi'),
(24, 309, 1, 'fRGqVEYIAtw:APA91bHqZfa4cjMWNr_VQaZ3ECx-coUi37MJNh_DqLXN6MWfIeEqNKkKA0Cr8FaTlzMkqxkFIcBcxiO1Q9_uC8UeLsSZW_thOUnsATPbMDEdP8s9wYhV5bWe4S8qIPezvshAQtPMZ5zG'),
(25, 241, 1, 'eMVszvz5XTw:APA91bHoF0gmlGTaK36Nn9hlDn2NY1sLzsYYDYNfhtXOjOHJsXjVUq24zhIEdCvjXAu8wdB8SLTuQu8Peb5cGwpqFSxCYFFxKCx8bEgTQ34fa51W4hOoa_ngxVrEP9T7IhsprPlVqA8G'),
(26, 231, 1, 'fEp0RNp5Hak:APA91bFgYRyESFIVxWQ55iqI-s1aTJYBvaC5w__BWIxxsoxy22CfJws6dHf8ZXwKG5a0EaSjNFFbe7OSvXfrjY3KAp_2tdxAT4LecnNPDTC9lSDDW6Wf2ClrdPGlBMRhx4Ostdehblos'),
(27, 231, 1, 'fEp0RNp5Hak:APA91bFgYRyESFIVxWQ55iqI-s1aTJYBvaC5w__BWIxxsoxy22CfJws6dHf8ZXwKG5a0EaSjNFFbe7OSvXfrjY3KAp_2tdxAT4LecnNPDTC9lSDDW6Wf2ClrdPGlBMRhx4Ostdehblos'),
(28, 323, 1, 'fU42zMZsCgk:APA91bHbN6LkyapiVDjPx621l9ypb3mb8WnUS78nlkTCaqNtQ3ibTwX5UKYY6lyLd-huchGO38M9qUjGfVbtqU7OP0MoN7TuTYygWZ1d-2vMTCuINFCKQIP8f5-ZHSWRvMGWwvqsiC4t'),
(29, 324, 1, 'dFKVc-PXIls:APA91bGNXfv7q71FNaesqQKrzGsoPNMNTnQjx7Rrx_IXWJDbOM9hkbRXtuJu0MFr8885jD-ZFPqal4MR-PgnHfKAc1kGvKGs08LWzR_bm9JjXjsIfYaj8WxmblQx0aepRO1xN5-JISc4'),
(30, 325, 1, 'dFKVc-PXIls:APA91bGNXfv7q71FNaesqQKrzGsoPNMNTnQjx7Rrx_IXWJDbOM9hkbRXtuJu0MFr8885jD-ZFPqal4MR-PgnHfKAc1kGvKGs08LWzR_bm9JjXjsIfYaj8WxmblQx0aepRO1xN5-JISc4'),
(31, 329, 1, 'fteYwNcmQ3k:APA91bGQSXt39o5GTmanDoOdhyO-SSRE5TbtZiGq1JeUSkzvOMpCJQGADSZLO9lQLpZBgLYptldVfYxjwDDLkZcvSKpIWtDvPJLFzUuPfkIaxOAq0miXTQwLZc1EzMI5xoHCcyqvIFBL'),
(32, 330, 1, 'e4yXwVLr3Lk:APA91bFzLg7F1h9aCNINja_6TjGOdZndHMMzf8etSdTPSg7w8rgz0QKzWJPSdgAfk8xurV00T15Cx5cGcF-dpADaLnm7tMCtQzWSMr3G-o0JSkeMCDCRteJv1AUilOjSj1ELL5RjoUFz'),
(33, 331, 1, 'cdTQEySUe9M:APA91bENdF1WLFpT7oEscRUYLNLye7cGp8X6Ucm-_hg5lR3X4Gi64oB_mrHq_rjlLC7kWXIw4_AwfZnz2YfTugtS1e6HnqGLckHHArRnn_Fmf6W1K2N19ITO8SJUmVOk35AjZjVg6l2J'),
(34, 264, 1, 'eeNW-ERsSCE:APA91bFHUcJjJ27N22nH_2-euVv49p0S3HvmxuAskORJghvBsmWGGB3vf9MtRbAJi9w6HLIZ-6dmj71rE09JpRJXeLt1v9dj1jhAhA4-SJ2USAnYy2vnQe5jPvCVfeV0iTIAiMXqEypu'),
(35, 332, 2, 'ehreheh'),
(36, 332, 2, 'ehreheh'),
(37, 332, 1, 'fV-9lxdfmx0:APA91bGl-TDrOcY28MdqHPzfm4E8EGZhUCv47MGsO4qSvdvIcAQG5f3dNUEcSoxRElS4vbqde3ysohoTQ6Zkkc-67sQXExqwnZHZnLpH6ZuaIfLnpXbTWdQOK7AWWw7wQ-6f6_wHEOrh'),
(38, 332, 1, 'fV-9lxdfmx0:APA91bGl-TDrOcY28MdqHPzfm4E8EGZhUCv47MGsO4qSvdvIcAQG5f3dNUEcSoxRElS4vbqde3ysohoTQ6Zkkc-67sQXExqwnZHZnLpH6ZuaIfLnpXbTWdQOK7AWWw7wQ-6f6_wHEOrh'),
(39, 333, 1, 'cbnMUjIJNiI:APA91bEOsY3k5dn0DooSG9WXZRbCmCapNrqoVqDgmGut-L2HFKuoqGeHph3LTg4xCD6VbqlXDKO4xRIGPN7qWXSABkiMPjexENfm61Q75ABpTBe5ZluAExIdGoxxc3xWLP7GjvoUWNzX'),
(40, 334, 1, 'eeNW-ERsSCE:APA91bFHUcJjJ27N22nH_2-euVv49p0S3HvmxuAskORJghvBsmWGGB3vf9MtRbAJi9w6HLIZ-6dmj71rE09JpRJXeLt1v9dj1jhAhA4-SJ2USAnYy2vnQe5jPvCVfeV0iTIAiMXqEypu'),
(41, 335, 2, 'test'),
(42, 336, 2, 'test'),
(43, 337, 2, 'test'),
(44, 338, 2, 'eTSCHdSJAgA:APA91bGK_ZeU87yh_r-iM6bZfnf4L7UDWY_zbQoNFiGE6XjjZEdLyWfjNBYfR4vKPkDzjDvYYXWWP-BM0fucw-Rtlh5ogV6vuTH54gf7MF3VkIJlopsJwng6SS4GrEmyW9auyzV5_1un'),
(45, 338, 1, 'cQ6ibRfUeKc:APA91bFXvZYjxzvCDEP90TPxtbDl42dqxmTNImwieBsUdUjOMzhcMk2USMCmF9XsGt9MrYJoBlKjE2FhqD3O0o0Zp5VP9eMhjjHjkS5BFuHFqDAfuDO05DQ5xAo8jnWkMqjboVOYzPWP'),
(46, 339, 2, 'test'),
(47, 266, 2, 'crQi1v95OeU:APA91bHAOITjmLoWP7AfdT0jk8SDCmms7axlIrsCUcS-sp3VSI2_bfg0PXzkUnDTKB1T26Qi3Hl_m2Erqp65-Vo2OMmVXaK9da1c6vuxdKU2pafWBLH5A4keA5KP__2APvTm04o91XeY'),
(48, 266, 2, 'crQi1v95OeU:APA91bHAOITjmLoWP7AfdT0jk8SDCmms7axlIrsCUcS-sp3VSI2_bfg0PXzkUnDTKB1T26Qi3Hl_m2Erqp65-Vo2OMmVXaK9da1c6vuxdKU2pafWBLH5A4keA5KP__2APvTm04o91XeY'),
(49, 278, 2, 'test'),
(50, 268, 2, 'fr64Jrp6b7s:APA91bF0cBwWgx9BKhx0zaBiP5LAdoxKzyWLLL_V-77LeNB_4wEtBzgxSBPP5c_4fFCWbe-rNfCROXJaAlm5KxcDwbV0tGsJJNaYhc2ReTmAJiLrrwIxSpnrExx27cClncjkXq33spLC'),
(51, 347, 2, 'cRuziSlFPzo:APA91bF498a4j46xQ_KkKtCM8rvU-gVRo3a2yNqFbwE6QN_2ZZJI2T7apH7bqqStc0j5Di0UIe8ShTbfIXIrXvfTG_7_B5xkOjjNT8EqKC_uc_obXAdI8oYf17Tfn1KtJAD0cQ8f_rkT'),
(52, 347, 1, 'edRYCpO8SC8:APA91bFGR7IUF8kJOpnaxMsghLm8ZWYrB4mQ2lf2teJ3FEgcb626oycJbebBGLvIb5AkYw35CZhlyHU-H18M5C9hFJj5pbGnjSJOorzIZHvTkJSc7l6ThJy6ymNZ9UvDw4iG7M4kYHdT'),
(53, 347, 1, 'edRYCpO8SC8:APA91bFGR7IUF8kJOpnaxMsghLm8ZWYrB4mQ2lf2teJ3FEgcb626oycJbebBGLvIb5AkYw35CZhlyHU-H18M5C9hFJj5pbGnjSJOorzIZHvTkJSc7l6ThJy6ymNZ9UvDw4iG7M4kYHdT'),
(54, 348, 1, 'cQ6ibRfUeKc:APA91bFXvZYjxzvCDEP90TPxtbDl42dqxmTNImwieBsUdUjOMzhcMk2USMCmF9XsGt9MrYJoBlKjE2FhqD3O0o0Zp5VP9eMhjjHjkS5BFuHFqDAfuDO05DQ5xAo8jnWkMqjboVOYzPWP'),
(55, 274, 2, 'eTSCHdSJAgA:APA91bGK_ZeU87yh_r-iM6bZfnf4L7UDWY_zbQoNFiGE6XjjZEdLyWfjNBYfR4vKPkDzjDvYYXWWP-BM0fucw-Rtlh5ogV6vuTH54gf7MF3VkIJlopsJwng6SS4GrEmyW9auyzV5_1un'),
(56, 349, 1, 'cOZLg5-AnKA:APA91bHJ7_34_lYfvcxZdFwzmUMVqtxhjLuuS7QIE_436shU37pRGs2zxbKxRTJw72ZP9fBX7xoaG-bIIexGRE4g7AnN27zIvz3dWubp0n-bQThvjMsWuOgz6bmfD74XUwtZXyNqICRA'),
(57, 350, 1, 'cnYgvYjdCLA:APA91bGvNWAjRmWEGiiMKEaGaCF4QEEJZoKFoBA3olFx33KpyQDDpnK5_782QlSWGqHVrbc8rv2GeHo0kmqZvx1tHc51fBu53ZkLIc15M3V3b-SzHXUlilioUi7rihcQhZvnttwsg7jl'),
(58, 351, 1, 'c1Y70wsOCXk:APA91bG1XBRQM6Y25bZ8BmIVxGkeLf_lHJ-UFMxCm0r4N38hZPxcH8_9KTslAcLgVoacXjhphu9Mr7yRakisK7IhM3NcTqdVZ5_tusZrxv5ZDen7xdAfTd5JEcvLzQ0lams5WTjRoU4_'),
(59, 353, 1, 'cCVLYJHOvNw:APA91bG83tfbKVk9z0dc2Be6-h4BXdjb8aN7tFPx0EyXD_yhZfF_BTb6s7ZcxwXgAIGrgDqAm9-nqVgU2IsQFxXYkyCQmlZjJuHXYHp_TxqC0hEVrGJSaekBfRMB8l-7bjt0FsslA260');

-- --------------------------------------------------------

--
-- Table structure for table `pm_email_content`
--

CREATE TABLE `pm_email_content` (
  `id` int(11) NOT NULL,
  `lang` int(11) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `subject` varchar(250) DEFAULT NULL,
  `content` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pm_email_content`
--

INSERT INTO `pm_email_content` (`id`, `lang`, `name`, `subject`, `content`) VALUES
(1, 1, 'CONTACT', 'Contact', '<b>Nom:</b> {name}<b>Adresse:</b> {address}<b>Téléphone:</b> {phone}<b>E-mail:</b> {email}<b>Message:</b>{msg}'),
(1, 2, 'CONTACT', 'Contact', '<p><b>Name:</b> {name}<br />\r\n<b>Address:</b> {address}<br />\r\n<b>Phone:</b> {phone}<br />\r\n<b>E-mail:</b> {email}<br />\r\n<b>Message:</b><br />\r\n{msg}</p>\r\n'),
(1, 3, 'CONTACT', 'Contact', '<b>Name:</b> {name}<b>Address:</b> {address}<b>Phone:</b> {phone}<b>E-mail:</b> {email}<b>Message:</b>{msg}'),
(1, 4, 'CONTACT', 'Contact', '<p><b>Name:</b> {name}<br />\r\n<b>Address:</b> {address}<br />\r\n<b>Phone:</b> {phone}<br />\r\n<b>E-mail:</b> {email}<br />\r\n<b>Message:</b><br />\r\n{msg}</p>\r\n'),
(2, 1, 'BOOKING_REQUEST', 'Demande de réservation', '<p><b>Adresse de facturation</b><br />\r\n{firstname} {lastname}<br />\r\n{address}<br />\r\n{postcode} {city}<br />\r\nSociété : {company}<br />\r\nTéléphone : {phone}<br />\r\nMobile : {mobile}<br />\r\nEmail : {email}</p>\r\n\r\n<p><strong>Détails de la réservation</strong><br />\r\nArrivée : <b>{Check_in}</b><br />\r\nDépart : <b>{Check_out}</b><br />\r\n<b>{num_nights}</b> nuit(s)<br />\r\n<b>{num_guests}</b> personne(s) - Adulte(s) : <b>{num_adults}</b> / Enfant(s) : <b>{num_children}</b></p>\r\n\r\n<p><b>Chambres</b></p>\r\n\r\n<p>{rooms}</p>\r\n\r\n<p><b>Services supplémentaires</b></p>\r\n\r\n<p>{extra_services}</p>\r\n\r\n<p><b>Activités</b></p>\r\n\r\n<p>{activities}</p>\r\n\r\n<p><b>Commentaires</b><br />\r\n{comments}</p>\r\n'),
(2, 2, 'BOOKING_REQUEST', 'Booking request', '<p><b>Billing address</b><br />\r\n{firstname} {lastname}<br />\r\n{address}<br />\r\n{postcode} {city}<br />\r\nCompany: {company}<br />\r\nPhone: {phone}<br />\r\nMobile: {mobile}<br />\r\nEmail: {email}</p>\r\n\r\n<p><strong>Booking details</strong><br />\r\nCheck in <b>{Check_in}</b><br />\r\nCheck out <b>{Check_out}</b><br />\r\n<b>{num_nights}</b> nights<br />\r\n<b>{num_guests}</b> persons - Adults: <b>{num_adults}</b> / Children: <b>{num_children}</b></p>\r\n\r\n<p><strong>Rooms</strong></p>\r\n\r\n<p>{rooms}</p>\r\n\r\n<p><b>Extra services</b></p>\r\n\r\n<p>{extra_services}</p>\r\n\r\n<p><b>Activities</b></p>\r\n\r\n<p>{activities}</p>\r\n\r\n<p><b>Comments</b><br />\r\n{comments}</p>\r\n'),
(2, 3, 'BOOKING_REQUEST', 'Booking request', '<p><b>Billing address</b><br />\r\n{firstname} {lastname}<br />\r\n{address}<br />\r\n{postcode} {city}<br />\r\nCompany: {company}<br />\r\nPhone: {phone}<br />\r\nMobile: {mobile}<br />\r\nEmail: {email}</p>\r\n\r\n<p><strong>Booking details</strong><br />\r\nCheck in <b>{Check_in}</b><br />\r\nCheck out <b>{Check_out}</b><br />\r\n<b>{num_nights}</b> nights<br />\r\n<b>{num_guests}</b> persons - Adults: <b>{num_adults}</b> / Children: <b>{num_children}</b></p>\r\n\r\n<p><strong>Rooms</strong></p>\r\n\r\n<p>{rooms}</p>\r\n\r\n<p><b>Extra services</b></p>\r\n\r\n<p>{extra_services}</p>\r\n\r\n<p><b>Activities</b></p>\r\n\r\n<p>{activities}</p>\r\n\r\n<p><b>Comments</b><br />\r\n{comments}</p>\r\n'),
(2, 4, 'BOOKING_REQUEST', 'Booking request', '<p><b>Billing address</b><br />\r\n{firstname} {lastname}<br />\r\n{address}<br />\r\n{postcode} {city}<br />\r\nCompany: {company}<br />\r\nPhone: {phone}<br />\r\nMobile: {mobile}<br />\r\nEmail: {email}</p>\r\n\r\n<p><strong>Booking details</strong><br />\r\nCheck in <b>{Check_in}</b><br />\r\nCheck out <b>{Check_out}</b><br />\r\n<b>{num_nights}</b> nights<br />\r\n<b>{num_guests}</b> persons - Adults: <b>{num_adults}</b> / Children: <b>{num_children}</b></p>\r\n\r\n<p><strong>Rooms</strong></p>\r\n\r\n<p>{rooms}</p>\r\n\r\n<p><b>Extra services</b></p>\r\n\r\n<p>{extra_services}</p>\r\n\r\n<p><b>Activities</b></p>\r\n\r\n<p>{activities}</p>\r\n\r\n<p><b>Comments</b><br />\r\n{comments}</p>\r\n'),
(3, 1, 'BOOKING_CONFIRMATION', 'Confirmation de réservation', '<p><b>Adresse de facturation</b><br />\r\n{firstname} {lastname}<br />\r\n{address}<br />\r\n{postcode} {city}<br />\r\nSociété : {company}<br />\r\nTéléphone : {phone}<br />\r\nMobile : {mobile}<br />\r\nEmail : {email}</p>\r\n\r\n<p><strong>Détails de la réservation</strong><br />\r\nArrivée : <b>{Check_in}</b><br />\r\nDépart : <b>{Check_out}</b><br />\r\n<b>{num_nights}</b> nuit(s)<br />\r\n<b>{num_guests}</b> personne(s) - Adulte(s) : <b>{num_adults}</b> / Enfant(s) : <b>{num_children}</b></p>\r\n\r\n<p><b>Chambres</b></p>\r\n\r\n<p>{rooms}</p>\r\n\r\n<p><b>Services supplémentaires</b></p>\r\n\r\n<p>{extra_services}</p>\r\n\r\n<p><b>Activités</b></p>\r\n\r\n<p>{activities}</p>\r\n\r\n<p>Taxe de séjour : {tourist_tax}<br />\r\nRéduction: {discount}<br />\r\n{taxes}<br />\r\nTotal : <strong>{total} TTC</strong></p>\r\n\r\n<p>Acompte : <strong>{down_payment} TTC</strong></p>\r\n\r\n<p><b>Commentaires</b><br />\r\n{comments}</p>\r\n\r\n<p>{payment_notice}</p>\r\n'),
(3, 2, 'BOOKING_CONFIRMATION', 'Your Booking confirmation', '<p style=\"color: rgb(0, 0, 0); font-size: 16px; margin: 0px; text-align: center;\">{payment_notice}</p>\r\n\r\n<p style=\"color: rgb(0, 0, 0); font-size: 16px; margin: 0px; text-align: center;\">Thank you for booking with us. Have a great stay!</p>\r\n\r\n<table cellpadding=\"0\" cellspacing=\"0\" style=\"width: 100%;border:10px solid #fafafa;border-top:3px;\">\r\n	<tbody>\r\n		<tr>\r\n			<td style=\"padding: 20px;background: #fff;\">\r\n			<h2 style=\"font-weight: bold;color: #000;font-size: 18px;margin: 0;\">Booking ID : {booking_id}</h2>\r\n			</td>\r\n			<td style=\"padding: 20px;background: #fff;text-align: right;\">\r\n			<h2 style=\"font-weight: bold;color: #000;font-size: 18px;margin: 0;\">Booking Date : {booking_date}</h2>\r\n			</td>\r\n		</tr>\r\n		<tr>\r\n			<td colspan=\"2\" style=\"text-align: center;\"> </td>\r\n		</tr>\r\n	</tbody>\r\n</table>\r\n\r\n<table cellpadding=\"0\" cellspacing=\"0\" style=\"width: 100%;border:10px solid #fafafa;border-top:3px;\">\r\n	<tbody>\r\n		<tr>\r\n			<td style=\"padding: 20px;background: #fff;\">\r\n			<h2 style=\"font-weight: bold;color: #000;font-size: 18px;margin: 0 0 20px;\">Check In</h2>\r\n\r\n			<p style=\"text-transform: uppercase;font-size: 18px;color: #000;margin: 0;\">{Check_in}</p>\r\n			</td>\r\n			<td style=\"padding: 20px;background: #fff;text-align: right;\">\r\n			<h2 style=\"font-weight: bold;color: #000;font-size: 18px;margin: 0 0 20px;\">Check Out</h2>\r\n\r\n			<p style=\"text-transform: uppercase;font-size: 18px;color: #000;margin: 0;\">{Check_out}</p>\r\n			</td>\r\n		</tr>\r\n	</tbody>\r\n</table>\r\n\r\n<p>{rooms}</p>\r\n\r\n<table cellpadding=\"0\" cellspacing=\"0\" style=\"width: 100%;border:10px solid #fafafa;border-top:3px;\">\r\n	<tbody>\r\n		<tr>\r\n			<td style=\"padding: 20px;background: #fff;\">\r\n			<p>Total Cost</p>\r\n\r\n			<p>Discount</p>\r\n\r\n			<p>Taxes</p>\r\n\r\n			<p style=\"font-size: 22px;font-weight: bold;margin: 0;\">TOTAL PAYABLE</p>\r\n\r\n			<p style=\"margin: 0;\">At hotel</p>\r\n			</td>\r\n			<td style=\"padding: 20px;background: #fff;text-align: right;\">\r\n			<p>{total_cost}</p>\r\n\r\n			<p>{discount}</p>\r\n\r\n			<p>{taxes}</p>\r\n\r\n			<p style=\"font-size: 22px;font-weight: bold;margin: 0;\">{total}</p>\r\n\r\n			<p style=\"margin: 0;\">Incl. of all taxes</p>\r\n			</td>\r\n		</tr>\r\n	</tbody>\r\n</table>\r\n'),
(3, 3, 'BOOKING_CONFIRMATION', 'Booking confirmation', '<p><b>Billing address</b><br />\r\n{firstname} {lastname}<br />\r\n{address}<br />\r\n{postcode} {city}<br />\r\nCompany: {company}<br />\r\nPhone: {phone}<br />\r\nMobile: {mobile}<br />\r\nEmail: {email}</p>\r\n\r\n<p><strong>Booking details</strong><br />\r\nCheck in <b>{Check_in}</b><br />\r\nCheck out <b>{Check_out}</b><br />\r\n<b>{num_nights}</b> nights<br />\r\n<b>{num_guests}</b> persons - Adults: <b>{num_adults}</b> / Children: <b>{num_children}</b></p>\r\n\r\n<p><strong>Rooms</strong></p>\r\n\r\n<p>{rooms}</p>\r\n\r\n<p><b>Extra services</b></p>\r\n\r\n<p>{extra_services}</p>\r\n\r\n<p><b>Activities</b></p>\r\n\r\n<p>{activities}</p>\r\n\r\n<p>Tourist tax: {tourist_tax}<br />\r\nDiscount: {discount}<br />\r\n{taxes}<br />\r\nTotal: <strong>{total} incl. VAT</strong></p>\r\n\r\n<p>Down payment: <strong>{down_payment} incl. VAT</strong></p>\r\n\r\n<p><b>Comments</b><br />\r\n{comments}</p>\r\n\r\n<p>{payment_notice}</p>\r\n'),
(3, 4, 'BOOKING_CONFIRMATION', 'Booking confirmation', '<tr><td style=\"padding: 5px 20px;\">{rooms}</td> </tr> \r\n<tr><td style=\"padding: 5px 20px;\"> \r\n{comments}\r\n\r\n{payment_notice} \r\n</td></tr>\r\n<tr style=\"height: 20px;\"> </tr>\r\n<tr style=\"background: #d7b956;color:#fff;font-size: 19px;\"><td style=\"padding: 6px 20px;\">Booking Details</td></tr>\r\n<tr style=\"height: 10px;border-bottom: 1px solid #ccc;display: block;\"> </tr>\r\n<tr style=\"height: 20px;\"> </tr>\r\n</tbody>\r\n</table> \r\n<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" class=\"tbl2\" style=\"padding: 0 20px;\">\r\n	<tbody>\r\n		<tr>\r\n			<td style=\"border-bottom: none!important;border-right: none!important;border:1px solid #ccc;padding: 15px;margin: 2px;\"><strong>CHECK-IN -</strong> {Check_in} </td>\r\n			<td style=\"border-bottom: none!important;border:1px solid #ccc;padding: 15px;margin: 2px;\"><strong>CHECK-OUT -</strong> {Check_out} </td>\r\n			<td style=\"border-right: none!important;border:1px solid #ccc;padding: 15px;margin: 2px;\"><strong>TOTAL -</strong> {total} incl. VAT </td>\r\n		</tr>\r\n		<tr>\r\n			<td style=\"border-right: none!important;border:1px solid #ccc;padding: 15px;margin: 2px;\"><strong>NIGHTS -</strong> {num_nights} </td>\r\n			<td style=\"border-right: none;border:1px solid #ccc;padding: 15px;margin: 2px;\">{num_guests} persons - Adults: {num_adults} / Children: {num_children} </td>\r\n			<td style=\"border-right: none;border:1px solid #ccc;padding: 15px;margin: 2px;\"><strong>DOWN PAYMENT -</strong> {down_payment} incl. VAT  </td>\r\n		</tr>\r\n	</tbody>\r\n</table>'),
(4, 2, 'BOOKING_CONFIRMATION_ONLINE', 'Your Booking confirmation', '<p style=\"color: rgb(0, 0, 0); font-size: 16px; margin: 0px; text-align: center;\">{payment_notice}</p>\r\n\r\n<p style=\"color: rgb(0, 0, 0); font-size: 16px; margin: 0px; text-align: center;\">Thank you for booking with us. Have a great stay!</p>\r\n\r\n<table cellpadding=\"0\" cellspacing=\"0\" style=\"width: 100%;border:10px solid #fafafa;border-top:3px;\">\r\n	<tbody>\r\n		<tr>\r\n			<td style=\"padding: 20px;background: #fff;\">\r\n			<h2 style=\"font-weight: bold;color: #000;font-size: 18px;margin: 0;\">Booking ID : {booking_id}</h2>\r\n			</td>\r\n			<td style=\"padding: 20px;background: #fff;text-align: right;\">\r\n			<h2 style=\"font-weight: bold;color: #000;font-size: 18px;margin: 0;\">Booking Date : {booking_date}</h2>\r\n			</td>\r\n		</tr>\r\n		<tr>\r\n			<td colspan=\"2\" style=\"text-align: center;\"> </td>\r\n		</tr>\r\n	</tbody>\r\n</table>\r\n\r\n<table cellpadding=\"0\" cellspacing=\"0\" style=\"width: 100%;border:10px solid #fafafa;border-top:3px;\">\r\n	<tbody>\r\n		<tr>\r\n			<td style=\"padding: 20px;background: #fff;\">\r\n			<h2 style=\"font-weight: bold;color: #000;font-size: 18px;margin: 0 0 20px;\">Check In</h2>\r\n\r\n			<p style=\"text-transform: uppercase;font-size: 18px;color: #000;margin: 0;\">{Check_in}</p>\r\n			</td>\r\n			<td style=\"padding: 20px;background: #fff;text-align: right;\">\r\n			<h2 style=\"font-weight: bold;color: #000;font-size: 18px;margin: 0 0 20px;\">Check Out</h2>\r\n\r\n			<p style=\"text-transform: uppercase;font-size: 18px;color: #000;margin: 0;\">{Check_out}</p>\r\n			</td>\r\n		</tr>\r\n	</tbody>\r\n</table>\r\n\r\n<p>{rooms}</p>\r\n\r\n<table cellpadding=\"0\" cellspacing=\"0\" style=\"width: 100%;border:10px solid #fafafa;border-top:3px;\">\r\n	<tbody>\r\n		<tr>\r\n			<td style=\"padding: 20px;background: #fff;\">\r\n			<p>Total Cost</p>\r\n\r\n			<p>Discount</p>\r\n\r\n			<p>Taxes</p>\r\n\r\n			<p style=\"font-size: 22px;font-weight: bold;margin: 0;\">PAID ONLINE</p>\r\n\r\n			<p style=\"margin: 0;\">Online Payment</p>\r\n\r\n			</td>\r\n			<td style=\"padding: 20px;background: #fff;text-align: right;\">\r\n			<p>{total_cost}</p>\r\n\r\n			<p>{discount}</p>\r\n\r\n			<p>{taxes}</p>\r\n\r\n			<p style=\"font-size: 22px;font-weight: bold;margin: 0;\">{total}</p>\r\n\r\n			<p style=\"margin: 0;\">Incl. of all taxes</p>\r\n			</td>\r\n		</tr>\r\n	</tbody>\r\n</table>\r\n');

-- --------------------------------------------------------

--
-- Table structure for table `pm_facility`
--

CREATE TABLE `pm_facility` (
  `id` int(11) NOT NULL,
  `lang` int(11) NOT NULL,
  `name` varchar(250) DEFAULT NULL,
  `type` enum('hotel','room') NOT NULL DEFAULT 'hotel',
  `rank` int(11) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pm_facility`
--

INSERT INTO `pm_facility` (`id`, `lang`, `name`, `type`, `rank`) VALUES
(66, 2, 'Complimentry breakfast', 'hotel', NULL),
(67, 2, 'Gyser', 'hotel', NULL),
(68, 2, 'AC', 'hotel', NULL),
(69, 2, 'Free Wi-Fi', 'hotel', NULL),
(70, 2, 'TV', 'hotel', NULL),
(71, 2, 'Kitchen', 'hotel', NULL),
(72, 2, 'CCTV', 'hotel', NULL),
(73, 2, 'Power Backup', 'hotel', NULL),
(74, 2, 'Elevator', 'hotel', NULL),
(75, 2, 'Conference room', 'hotel', NULL),
(76, 2, 'Banquet hall', 'hotel', NULL),
(77, 2, 'Fire Safety', 'hotel', NULL),
(78, 2, 'First Aid', 'hotel', NULL),
(79, 2, 'Laundry', 'hotel', NULL),
(80, 2, 'Intercom', 'hotel', NULL),
(81, 2, 'Work table', 'hotel', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `pm_facility_file`
--

CREATE TABLE `pm_facility_file` (
  `id` int(11) NOT NULL,
  `lang` int(11) NOT NULL,
  `id_item` int(11) NOT NULL,
  `home` int(11) DEFAULT '0',
  `checked` int(11) DEFAULT '1',
  `rank` int(11) DEFAULT '0',
  `file` varchar(250) DEFAULT NULL,
  `label` varchar(250) DEFAULT NULL,
  `type` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pm_facility_file`
--

INSERT INTO `pm_facility_file` (`id`, `lang`, `id_item`, `home`, `checked`, `rank`, `file`, `label`, `type`) VALUES
(101, 2, 68, NULL, 1, 33, '16.png', NULL, 'image'),
(102, 2, 76, NULL, 1, 34, '1.png', NULL, 'image'),
(103, 2, 72, NULL, 1, 35, '2.png', NULL, 'image'),
(104, 2, 66, NULL, 1, 36, '3.png', NULL, 'image'),
(105, 2, 75, NULL, 1, 37, '4.png', NULL, 'image'),
(106, 2, 74, NULL, 1, 38, '5.png', NULL, 'image'),
(107, 2, 77, NULL, 1, 39, '6.png', NULL, 'image'),
(108, 2, 78, NULL, 1, 40, '7.png', NULL, 'image'),
(109, 2, 69, NULL, 1, 41, '8.png', NULL, 'image'),
(110, 2, 67, NULL, 1, 42, '9.png', NULL, 'image'),
(111, 2, 80, NULL, 1, 43, '10.png', NULL, 'image'),
(112, 2, 71, NULL, 1, 44, '11.png', NULL, 'image'),
(113, 2, 79, NULL, 1, 45, '12.png', NULL, 'image'),
(114, 2, 73, NULL, 1, 46, '13.png', NULL, 'image'),
(115, 2, 70, NULL, 1, 47, '14.png', NULL, 'image'),
(116, 2, 81, NULL, 1, 48, '15.png', NULL, 'image');

-- --------------------------------------------------------

--
-- Table structure for table `pm_faq`
--

CREATE TABLE `pm_faq` (
  `id` int(11) NOT NULL,
  `question` varchar(100) DEFAULT NULL,
  `answer` longtext,
  `add_date` int(11) DEFAULT NULL,
  `edit_date` int(11) DEFAULT NULL,
  `checked` int(11) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pm_faq`
--

INSERT INTO `pm_faq` (`id`, `question`, `answer`, `add_date`, `edit_date`, `checked`) VALUES
(1, 'Faq Question One', 'Faq Answer One Faq Answer OneFaq Answer OneFaq Answer OneFaq Answer OneFaq Answer OneFaq Answer OneFaq Answer OneFaq Answer OneFaq Answer OneFaq Answer OneFaq Answer OneFaq Answer OneFaq Answer One', NULL, 1576236516, 1),
(2, 'Faq Question Two', 'Faq Answer TwoFaq Answer TwoFaq Answer TwoFaq Answer TwoFaq Answer TwoFaq Answer TwoFaq Answer TwoFaq Answer TwoFaq Answer TwoFaq Answer TwoFaq Answer TwoFaq Answer TwoFaq Answer TwoFaq Answer TwoFaq Answer TwoFaq Answer TwoFaq Answer TwoFaq Answer TwoFaq Answer TwoFaq Answer TwoFaq Answer TwoFaq Answer TwoFaq Answer TwoFaq Answer TwoFaq Answer Two', NULL, 1576236550, 1);

-- --------------------------------------------------------

--
-- Table structure for table `pm_feedback_params`
--

CREATE TABLE `pm_feedback_params` (
  `id` int(11) NOT NULL,
  `id_hotel` int(11) DEFAULT NULL,
  `params` varchar(250) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `pm_feedback_params`
--

INSERT INTO `pm_feedback_params` (`id`, `id_hotel`, `params`) VALUES
(1, 19, 'Room Quality'),
(2, 19, 'Hospitality'),
(3, 20, 'Room Quality'),
(4, 20, 'Service'),
(5, 31, 'Room Quality'),
(6, 31, 'Room Service'),
(7, 31, 'Car Parking'),
(8, 22, 'Room Quality'),
(9, 22, 'Hospitality'),
(10, 22, 'Staff Courtesy'),
(11, 22, 'Space in Rooms'),
(12, 22, 'Child friendliness'),
(13, 19, 'Staff Courtesy'),
(14, 19, 'Space in Rooms'),
(20, 19, 'Child Friendliness'),
(16, 21, 'Room Quality'),
(17, 21, 'Hospitality'),
(18, 21, 'Space In Rooms'),
(19, 21, 'Child Friendliness'),
(21, 30, 'Cleanness'),
(22, 30, 'Quality of Service'),
(23, 30, 'Customer Care');

-- --------------------------------------------------------

--
-- Table structure for table `pm_floors`
--

CREATE TABLE `pm_floors` (
  `id` int(11) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `checked` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pm_floors`
--

INSERT INTO `pm_floors` (`id`, `name`, `checked`) VALUES
(1, 'Ground Floor', 1),
(2, 'First Floor', 1),
(3, 'Second Floor', 1),
(4, 'Third Floor', 1),
(5, 'Fourth Floor', 1),
(6, 'Fifth Floor', 1),
(7, 'Sixth Floor', 1);

-- --------------------------------------------------------

--
-- Table structure for table `pm_gallery`
--

CREATE TABLE `pm_gallery` (
  `id` int(255) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `status` smallint(2) NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `pm_gallery`
--

INSERT INTO `pm_gallery` (`id`, `image`, `status`) VALUES
(3, '7B3KDeZaCT4fV6HXQNME.jpg', 1),
(4, 'bn6BRq5dF3WrxJMVYD8w.jpg', 1),
(5, 'MHPR07ubdnKD8XypANzS.jpg', 1),
(6, 'HwqK8WmS1chZ9YnTjRBs.jpg', 1),
(7, 'S985ykYNrw6P2mvtaAue.jpg', 1),
(8, '8EAs1b3NM5wajpXyeDk4.jpg', 1),
(9, 'Az2xW75QGc8U1eEXbkRP.jpg', 1),
(10, 'r6J8jnEUutfS2eYBQVRc.jpg', 1),
(11, 'We8N4U1E5DYXwmbHkV2q.jpg', 1),
(12, 'W46P8jrcanR1qVDbdMkF.jpg', 1);

-- --------------------------------------------------------

--
-- Table structure for table `pm_hotel`
--

CREATE TABLE `pm_hotel` (
  `id` int(11) NOT NULL,
  `hotelid` varchar(255) DEFAULT NULL,
  `lang` int(11) NOT NULL,
  `users` text,
  `title` varchar(250) DEFAULT NULL,
  `subtitle` varchar(250) DEFAULT NULL,
  `alias` varchar(100) DEFAULT NULL,
  `class` int(11) DEFAULT '0',
  `id_accommodation` int(11) NOT NULL,
  `address` varchar(250) DEFAULT NULL,
  `address_1` text,
  `address_2` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `state` varchar(255) DEFAULT NULL,
  `zipcode` varchar(25) NOT NULL,
  `country_code` varchar(5) DEFAULT NULL,
  `lat` double DEFAULT NULL,
  `lng` double DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `web` varchar(250) DEFAULT NULL,
  `descr` longtext,
  `facilities` varchar(250) DEFAULT NULL,
  `id_destination` int(11) DEFAULT NULL,
  `paypal_email` varchar(250) DEFAULT NULL,
  `book_policy` longtext,
  `general_policies` longtext,
  `cancel_policy` longtext,
  `home` int(11) DEFAULT '0',
  `checked` int(11) DEFAULT '0',
  `rank` int(11) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pm_hotel`
--

INSERT INTO `pm_hotel` (`id`, `hotelid`, `lang`, `users`, `title`, `subtitle`, `alias`, `class`, `id_accommodation`, `address`, `address_1`, `address_2`, `city`, `state`, `zipcode`, `country_code`, `lat`, `lng`, `email`, `phone`, `web`, `descr`, `facilities`, `id_destination`, `paypal_email`, `book_policy`, `general_policies`, `cancel_policy`, `home`, `checked`, `rank`) VALUES
(19, '9856856', 2, '1,267', 'Eco Stay', 'test', 'eco-stay', 3, 1, 'Plot no AA-IIB 204', NULL, 'HIG 1 Action area 2B, 02-566 New Town', 'Kolkata', 'West Bengal', '700157', 'IN', 22.4941, 88.4381, 'ecostay@guptahotels.com', '7699287925', '', '<p><strong>Description:</strong></p>\r\n\r\n<p>Ecostay in Kolkata is an ideal choice of stay for business and leisure travelers, offering fine services at budget rates. The property is well maintained by a trained and skillful staff who ensure all your needs are catered to.</p>\r\n\r\n<p><strong>Facilities</strong></p>\r\n\r\n<ul>\r\n	<li>The rooms are equipped with modern amenities like AC, Geyser and much more.</li>\r\n	<li>To ensure the safety of guests, the hotel provides facilities like fire safety, first aid and round the clock security.</li>\r\n	<li>Additionally, we have a 24-hour helpdesk to offer guests assistance at any hour.</li>\r\n</ul>\r\n\r\n<p><strong>What\'s Nearby</strong></p>\r\n\r\n<p>Head out to visit the key attractions of Kolkata like –</p>\r\n\r\n<ul>\r\n	<li> Eco Park</li>\r\n	<li> Mother’s Wax Museum</li>\r\n	<li> Central Park</li>\r\n	<li> Nicco Park.</li>\r\n</ul>\r\n', '68,66,77,78,69,67,70', 1, NULL, '<p> </span> <label class=\"col-lg-2 control-label\"> Booking policy </label> <span data-cke-marker=\"1\"> 30decggfhfghgfh</p>\r\n', '<p> </span> A maximum of 1 child upto an age of 8 years and an extra infant upto an age of 2 years is allowed free of charge, subject to room occupancy capacity. Breakfast will be included in stay but no extra bed or mattress will be provided. Extra person charges are applicable for anyone above 8 years of age. Extra person charges are also applicable for extra kids during check-in. <span data-cke-marker=\"1\"> </p>\r\n', '<p>In case of cancellation please contact to Hotel Admin:</p>\r\n\r\n<ul>\r\n	<li>Incase of no show, no refund will be provided.</li>\r\n	<li>Booking cannot be cancelled or modified on or after the check-in date.</li>\r\n</ul>\r\n', 1, 1, 1),
(20, '9955', 2, '1', 'Eco Palace', 'Eco Palace', 'eco-palace', 3, 1, 'Plot no AA-IIB/424', NULL, 'MIG 1,ActionArea IIB,6-0532, New Town', 'Kolkata', 'West Bengal', '700157', 'IN', 22.4941, 88.4381, 'reservation@guptahotels.com', '8170902908', 'dsa', '<p><strong>Description:</strong></p>\r\n\r\n<p>Eco Palace in Kolkata is an ideal choice of stay for those travelling either for business or leisure. The establishment offers fine services at budget rates. Maintained by a skilled and friendly roster of staff-members, the hotel ensures you have a positive rental experience.</p>\r\n\r\n<p><strong>Facilities</strong></p>\r\n\r\n<ul>\r\n	<li>The property offers AC, Geyser and more.</li>\r\n	<li>We take our guests safety seriously and provide facilities like fire safety, first aid and round the clock security to ensure the same.</li>\r\n	<li>Our 24 hour helpdesk ensures your needs and requirements are catered to.</li>\r\n</ul>\r\n\r\n<p><strong>What\'s Nearby</strong></p>\r\n\r\n<ul>\r\n	<li>Eco park and mothers wax museum.</li>\r\n	<li>There is Bank of India e-gallery, Bank and ATM nearby the hotel so you are never out of cash</li>\r\n</ul>\r\n', '68,66,69,67,70', 1, NULL, NULL, NULL, '<p>In case of cancellation please contact to Hotel Admin <span data-cke-marker=\"1\"> </p>\r\n\r\n<ul>\r\n	<li>Incase of no show, no refund will be provided.</li>\r\n	<li>Booking cannot be cancelled or modified on or after the check-in date.</li>\r\n</ul>\r\n', 0, 1, 2),
(21, '258965', 2, '1', 'Eco Inn', 'Eco Inn', 'eco-inn', 3, 2, 'AA-IIB,708 Premises no-21/574', NULL, 'New Town', 'Kolkata', 'West Bengal', '700161', 'IN', 22.5882834, 88.4734476, 'reservation@guptahotels.com', '7063176016', '', '<p><strong>Description:</strong></p>\r\n\r\n<p>Eco Inn is strategically located in Kolkata to offer you the best services at affordable rates. The establishment is maintained with a skilled and friendly roster of staff-members to ensure you have a memorable and comfortable stay.</p>\r\n\r\n<p><strong>Facilities</strong></p>\r\n\r\n<ul>\r\n	<li>The rooms are equipped with modern amenities like air-conditioning, card payment, geyser and much more.</li>\r\n	<li>The hotel provides facilities such as fire safety, first aid and round the clock security for the guest’s security.</li>\r\n	<li>We also have a 24-hour helpdesk to offer assistance to the guests at any time.</li>\r\n</ul>\r\n\r\n<p><strong>What\'s Nearby</strong></p>\r\n\r\n<p>Head out to visit the key attractions of Kolkata like –</p>\r\n\r\n<ul>\r\n	<li>Eco Park</li>\r\n	<li>Mother’s Wax Museum</li>\r\n	<li>Central Park</li>\r\n	<li>Nicco Park.</li>\r\n</ul>\r\n', '68,66,77,78,69,67,70', 1, NULL, NULL, NULL, '<p> In case of cancellation please contact to Hotel Admin:</p>\r\n\r\n<ul>\r\n	<li>Incase of no show, no refund will be provided.</li>\r\n	<li>Booking cannot be cancelled or modified on or after the check-in date.</li>\r\n</ul>\r\n\r\n<p><span data-cke-marker=\"1\"> </p>', 0, 1, 3),
(22, '1007', 2, '1', 'Stay Inn', 'Stay Inn', 'stay-inn', 3, 3, 'Natunpara,Bablatala,Gopalpur,', NULL, 'Rajarhat Main Road,Near Ark Plaza, Opp Derozio Collage', 'Kolkata', 'West Bengal', '700136', 'IN', 22.8283, 88.0778, 'reservation@guptahotels.com', '9635145760', '', '<p><strong>Description:</strong></p>\r\n\r\n<p>With its easy accessibility to many key attractions, Hotel Stay Inn in Kolkata offers you a luxurious accommodation; experience grandeur and comfort at affordable rates when you stay with us. Our well-trained and courteous staff-members strive to maintain a top-notch establishment and ensure that your stay is smooth.</p>\r\n\r\n<p><strong>Facilities</strong></p>\r\n\r\n<ul>\r\n	<li>Complimentary breakfast, in-house restaurant, parking facility and more are equipped in our property.</li>\r\n	<li>The hotel provides facilities like fire safety, first aid and round the clock security for the guests.</li>\r\n	<li>Additionally, we have a 24-hour helpdesk to offer assistance to guests at any hour.</li>\r\n</ul>\r\n\r\n<p><strong>What\'s Nearby</strong></p>\r\n\r\n<ul>\r\n	<li>Explore Kolkata and visit the famous landmarks and attractions such as Central Park, Nalban and Nicco Park among others.</li>\r\n	<li>Axis Bank ATM and SBI ATM close to the hotel grant you financial liquidity at any time.</li>\r\n</ul>\r\n', '68,66,77,78,69,67,70', 1, NULL, '', '', '<p> In case of cancellation please contact to Hotel Admin:</p>\r\n\r\n<ul>\r\n	<li>Incase of no show, no refund will be provided.</li>\r\n	<li>Booking cannot be cancelled or modified on or after the check-in date.</li>\r\n</ul>\r\n', 0, 1, 4),
(23, '1234789', 2, '1', 'Durbar Guest House', '', 'durbar-guest-house', 3, 2, '5/420 Kalipark Complex,Vidysagar Pally, ', NULL, 'Bablatala,Gopalpur,', 'Kolkata', 'West Bengal', '700136', 'IN', 22.8283, 88.0778, 'reservation@guptahotels.com', '6289939436', '', '<p><strong>Description:</strong></p>\r\n\r\n<p>Durbar Guest House is a rental property of minimalistic and classically tasteful architecture located in Vidyasagar Pally, Kolkata. The establishment is close in proximity to a landmark statue of Maa Durga,<br />\r\nAt Durbar Guest House you will find services within an affordable price range. The rooms are bright and beautifully finished and furnished with wooden furniture, including cupboards. The corridors and reception area is well maintained.</p>\r\n\r\n<p><strong>Facilities</strong></p>\r\n\r\n<p>All rooms have a queen-sized bed, AC, TV and elegant tiled bathrooms with geyser. The guest house also boasts of CCTV cameras, elevator and power backup. In this guest house, you will also have access to a kitchen where you can cook and store your food. Free internet facility is provided to all guests. Payments via cards are accepted here.</p>\r\n\r\n<p><strong>What’s Nearby</strong></p>\r\n\r\n<p>Guests can look forward to amusements such as the boating services at the nearby Eco Point, the Sculpture Garden and the Culture and Heritage of Bengal. There are numerous restaurants located in the vicinity of the guest house XII Zodiac, The Bahamas Love Cafe and Restaurant, Wang’s kitchen, PFC, Aminia Restaurant and Arsalan Restaurant & Caterer.</p>\r\n', '68,72,74,69,73,70', 2, NULL, NULL, NULL, '<p> In case of cancellation please contact to Hotel Admin:</p>\r\n\r\n<ul>\r\n	<li>Incase of no show, no refund will be provided.</li>\r\n	<li>Booking cannot be cancelled or modified on or after the check-in date.</li>\r\n</ul>\r\n', 0, 1, 5),
(24, '987456', 2, '1', 'Chinnar Inn', '', 'chinnar-inn', 3, 2, '224 ,Bholanath Sarani Chinnar Park Naer ', NULL, 'Zenith motors ', 'kolkata', 'West Bengal', '700157', 'IN', 22.4941, 88.4381, 'reservation@guptahotels.com', '8697082953', '', '<p><strong>Description:</strong></p>\r\n\r\n<p>Chinnar Inn is a budget-friendly hotel of refined tastes. The hotel is located in the posh neighbourhood of Rajarhat in Kolkata. Chinnar Inn offers customers aesthetically designed and decorated rooms that prioritise comfort.<br />\r\nGuests can look forward to a well-furnished living space, with a lot of wooden elements used throughout for the interior decor of the rooms. The creative light setup design also infuses an ambiance of luxury. All the rooms have been provided with large windows with safety grills which let the guests enjoy some amazing views, fresh air and a lot of sunlight, without compromising security. </p>\r\n\r\n<p><strong>Facilities</strong></p>\r\n\r\n<p>Guests can look forward to facilities such as card payment in all forms, and amenities such as an elevator service, laundry provisions, CCTV cameras, and free Wi-Fi. All these facilities are maintained by a team of dedicated staff.</p>\r\n\r\n<p><strong>What’s Nearby</strong></p>\r\n\r\n<p>The hotel is located close to a number of eateries like A Sweet Surrender, The Cloud 9, and Aminia, for the benefit of the guests.</p>\r\n', '68,72,74,69,79,70', 2, NULL, '', '', '<p> In case of cancellation please contact to Hotel Admin:</p>\r\n\r\n<ul>\r\n	<li>Incase of no show, no refund will be provided.</li>\r\n	<li>Booking cannot be cancelled or modified on or after the check-in date.</li>\r\n</ul>\r\n', 1, 1, 6),
(25, '12345', 2, '1', 'Padmavati', '', 'padmavati', 3, 1, '24/2 Chinnar Park ', NULL, 'Behind NPG Hotel ', 'Kolkata', 'West Bengal', '700157', 'IN', 22.4941, 88.4381, 'reservation@guptahotels.com', '6290649294', '', '<p><strong>Description:</strong></p>\r\n\r\n<p>Padmavati Hotel in Kolkata is an ideal choice of stay for those travelling either for business or leisure. The establishment offers fine services at budget rates. Maintained by a skilled and friendly roster of staff-members, the hotel ensures you have a positive rental experience.</p>\r\n\r\n<p><strong>Facilities</strong></p>\r\n\r\n<ul>\r\n	<li>The property offers AC, Geyser and more.</li>\r\n	<li>We take our guests safety seriously and provide facilities like fire safety, first aid and round the clock security to ensure the same.</li>\r\n	<li>Our 24 hour helpdesk ensures your needs and requirements are catered to.</li>\r\n</ul>\r\n\r\n<p><strong>What\'s Nearby</strong></p>\r\n\r\n<ul>\r\n	<li>Netaji Subhash Chandra Bose Internatiol Airport, City center 2, Inox movie hall.</li>\r\n	<li>There is Bank of India e-gallery, Bank and ATM nearby the hotel so you are never out of cash.</li>\r\n</ul>\r\n\r\n<p> </p>\r\n', '68,72,77,78,69,71,70', 2, NULL, NULL, NULL, '<p> In case of cancellation please contact to Hotel Admin:</p>\r\n\r\n<ul>\r\n	<li>Incase of no show, no refund will be provided.</li>\r\n	<li>Booking cannot be cancelled or modified on or after the check-in date.</li>\r\n</ul>\r\n', 0, 1, 7),
(26, '789456', 2, '1', 'Zapper Guest House', '', 'zapper-guest-house', 3, 3, '13/2/14 Jhowtalla Lane ', NULL, 'Near lokenath mandir ', 'Kolkata', 'West Bengal', '700157', 'IN', 22.4941, 88.4381, 'reservation@guptahotels.com', '8617235689', '', '<p><strong>Description:</strong></p>\r\n\r\n<p>Greetings from the ‘City of Joy’- Kolkata! Zapper Guest House is pleased to announce its services for guests. Zapper Guest House is serving guests since May 23, 2016.  The Guest House is located in Kolkata to make your stay more fantastic and offer you a memorable hotel-stay at a budgeted rate. You can easily reach the hotel from Netaji Subhash Chandra Bose International Airport by auto or a cab.<br />\r\nThe hotel is designed to make your stay more comfortable, with all the basic facilities provided within a budget fee. The establishment goes off the beaten path to ensure that everything is in place as reflected by their well-trained staffs.  </p>\r\n\r\n<p><strong>Facilities</strong></p>\r\n\r\n<ul>\r\n	<li>Your room amenities will include a work table, intercom and a luggage rack for your belongings.</li>\r\n	<li>The hotel provides other facilities such as photocopier, fire safety, first aid, and round the clock security.</li>\r\n	<li>The hotel also offers amenities such as dry cleaning, free admission for children, and 100% power back-up and a lot more.</li>\r\n	<li>It is the perfect place to stay for families or young travellers for either leisure or business.</li>\r\n	<li>A 24-hour helpdesk to assist you at any hour of the day.</li>\r\n</ul>\r\n\r\n<p><strong>What’s Nearby</strong></p>\r\n\r\n<ul>\r\n	<li>Visit famous tourist attractions such as the Eco Tourism Park and Mother’s Wax Museum which are located nearby.</li>\r\n	<li>Choose your meal from the menus of nearby dining greats such as Arsalan Chinar Park, Subway, or Sizzler.</li>\r\n	<li>Head out to nearby shopping destinations such as City Centre 2 and Spencer\'s Axis Mall.</li>\r\n	<li>Nearby ATM service</li>\r\n</ul>\r\n', '68,77,78,69,80,70,81', 2, NULL, NULL, NULL, '<p> In case of cancellation please contact to Hotel Admin:</p>\r\n\r\n<ul>\r\n	<li>Incase of no show, no refund will be provided.</li>\r\n	<li>Booking cannot be cancelled or modified on or after the check-in date.</li>\r\n</ul>\r\n', 0, 1, 8),
(27, '789445', 2, '1', 'Polyshree Guest House', '', 'polyshree-guest-house', 3, 3, '2/3 Jayngra Teghoria Road Baguhati ,', NULL, 'Near lokenath Mandir ', 'Kolkata', 'West Bengal', '700059', 'IN', 22.56263, 88.36304, 'reservation@guptahotels.com', '8917610760', '', '<p><strong>Description:</strong></p>\r\n\r\n<p>Pallyshree Guest House is a reasonably priced, deluxe-grade hotel located on the Jyangra-Tegharia Road, near Sitala Mandir in Baguiati locality, Kolkata. The hotel provides aesthetically designed rooms that offer a contrast of dark colour accented walls, light coloured floors and bright coloured drapes.</p>\r\n\r\n<p><strong>Facilities</strong></p>\r\n\r\n<p>In addition to the usual amenities like TV, AC, free Wi-Fi, kitchen and CCTV cameras the hotel also provides amenities like mini fridge and hair dryer for the convenience of their guests.</p>\r\n\r\n<p><strong>What’s Nearby</strong></p>\r\n\r\n<p>Guests can visit interesting places like Iskcon Krishna Kuthir, Sculpture Garden and Kolkata Museum of Modern Art which are located near this property. Guests looking for different types of food can visit restaurants like Aminia Restaurant, Momo I Am and Advieh which are located near this hotel. The Brahmachari Ashram bus station, Dum Dum Cantt train station and Netaji Subhash Chandra Bose International Airport are all located in the vicinity of this property.</p>\r\n\r\n<p> </p>\r\n', '68,72,69,67,71,70', 3, NULL, NULL, NULL, '<p>In case of cancellation please contact to Hotel Admin:</p>\r\n\r\n<ul>\r\n	<li>Incase of no show, no refund will be provided.</li>\r\n	<li>Booking cannot be cancelled or modified on or after the check-in date</li>\r\n</ul>\r\n', 1, 1, 9),
(28, '33214', 2, '1', 'Maruti Lodging', '', 'maruti-lodging', 3, 3, 'Shree tower 2,4 th floor RAA -36,', NULL, 'VIP Road Raghunathpur ', 'Kolkata', 'West Bengal', '700059', 'IN', 22.6200222, 88.4321592, 'reservation@guptahotels.com', '8697554142', '', '<p><strong>Description:</strong></p>\r\n\r\n<p>Hotel Maruti Lodging is located on the VIP Road in Kolkata. The hotel offers comfortable rooms that are designed with a lot of thought for guests in search of pocket-friendly prices. Rooms painted aesthetically in bold blues along with intricate patterns offer a breezy, homely feel to the guests. Well-lit rooms with tasteful illumination are a plus. Large windows have been provided in all rooms for the benefit of the guests. The windows have been covered with long and thick curtains. An extra addition to every room is the plush couch for lounging.</p>\r\n\r\n<p><strong>Facilities</strong></p>\r\n\r\n<p>The additional features provided by the hotel are free Wi-Fi, an uninterrupted power backup source, an elevator facility, a conference room, and a banquet hall. The front desk service is also elegantly decorated and is handled by helpful and experienced staff.</p>\r\n\r\n<p><strong>What’s Nearby</strong></p>\r\n\r\n<p>The hotel is located close to the Netaji Shubhash Airport, Dumdum Metro Station, and Bidhannagar Railway Station, etc. Popular dining options such as Vegout, KFC, are also located nearby, alongside shopping destinations.</p>\r\n\r\n<p> </p>\r\n\r\n<p></span></p>\r\n', '68,76,75,74,69,73,70', 3, NULL, NULL, NULL, '<p> In case of cancellation please contact to Hotel Admin:</p>\r\n\r\n<ul>\r\n	<li>Incase of no show, no refund will be provided.</li>\r\n	<li>Booking cannot be cancelled or modified on or after the check-in date.</li>\r\n</ul>\r\n', 0, 1, 10),
(29, '99874', 2, '1', 'Pratiksha Guest House', '', 'pratiksha-guest-house', 3, 1, '68/1 Bangur AVENUE Block -B,', NULL, 'Behind Reliance Fresh ', 'Kolkata', 'West Bengal', '700055', 'IN', 22.607285, 88.4105742, 'reservation@guptahotels.com', '8608975158', '', '<p><strong>Description:</strong></p>\r\n\r\n<p>Pratiksha Guest House in Kolkata is an ideal choice of stay for those travelling either for business or leisure. The establishment offers fine services at budget rates. Maintained by a skilled and friendly roster of staff-members, the hotel ensures you have a positive rental experience.</p>\r\n\r\n<p><strong>Facilities</strong></p>\r\n\r\n<ul>\r\n	<li>The property offers AC, Geyser and more.</li>\r\n	<li>We take our guests safety seriously and provide facilities like fire safety, first aid and round the clock security to ensure the same.</li>\r\n	<li>Our 24 hour helpdesk ensures your needs and requirements are catered to.</li>\r\n</ul>\r\n\r\n<p><strong>What\'s Nearby</strong></p>\r\n\r\n<ul>\r\n	<li>To make the most of your visit, head out to famous tourist spots like Central Park, Pareshnath Temple and Lokenath Santiniketan Ashram.</li>\r\n	<li>There is Bank of India e-gallery, Canara Bank ATM and UCO Bank nearby the hotel so you are never out of cash.</li>\r\n</ul>\r\n\r\n<p> </p>\r\n\r\n<p><span data-cke-marker=\"1\"> </p>\r\n', '68,77,78,69,70', 3, NULL, NULL, NULL, '<p> In case of cancellation please contact to Hotel Admin:</p>\r\n\r\n<ul>\r\n	<li>Incase of no show, no refund will be provided.</li>\r\n	<li>Booking cannot be cancelled or modified on or after the check-in date.</li>\r\n</ul>\r\n', 0, 1, 11),
(30, '998123', 2, '1', 'Upasana Guest House', '', 'upasana-guest-house', 0, 2, '35/1 , Bangur Avenue ', NULL, 'Block-D ', 'Kolkata', 'West Bengal', '700055', 'IN', 22.607285, 88.4105742, 'reservation@guptahotels.com', '9874488624', '', '<p><strong>Description:</strong></p>\r\n\r\n<p>Upasana Guest House in Kolkata is an ideal choice of stay for those travelling either for business or leisure. The establishment offers fine services at budget rates. Maintained by a skilled and friendly roster of staff-members, the hotel ensures you have a positive rental experience.</p>\r\n\r\n<p><strong>Facilities</strong></p>\r\n\r\n<ul>\r\n	<li>The property offers AC, Geyser and more.</li>\r\n	<li>We take our guests safety seriously and provide facilities like fire safety, first aid and round the clock security to ensure the same.</li>\r\n	<li>Our 24 hour helpdesk ensures your needs and requirements are catered to.</li>\r\n</ul>\r\n\r\n<p><strong>What\'s Nearby</strong></p>\r\n\r\n<ul>\r\n	<li>Netaji Subhash Chandra Bose Internatiol Airport, Bangur Bus Stop,  Central Park Station Metro Station, Patipukur Railway station.</li>\r\n	<li>There is Bank of India e-gallery, Bank and ATM nearby the hotel so you are never out of cash.</li>\r\n</ul>\r\n', '68,69,70', 3, NULL, '', '', '<p> In case of cancellation please contact to Hotel Admin:</p>\r\n\r\n<ul>\r\n	<li>Incase of no show, no refund will be provided.</li>\r\n	<li>Booking cannot be cancelled or modified on or after the check-in date.</li>\r\n</ul>\r\n', 0, 1, 12),
(31, '22243222', 2, '1', 'Testdemo hotel', 'Testdemo hotel', 'testdemo-hotel', 4, 1, 'Newtown, Kolkata, West Bengal, India', NULL, 'cxzczx', 'Kolkata', 'West Bengal', '700125', 'IN', 22.576524, 88.405071, 'sonjoy.bhadra@met-technologies.com', '9112356998', 'dsanvbnvbn.com', '<p>Testdemo hotel</p>\r\n', '', 1, NULL, '', '', '<p>Testdemo hotelTestdemo hotelTestdemo hotelTestdemo hotelTestdemo hotel</p>\r\n', 0, 1, 13),
(32, '22243227878', 2, '1', 'Test Hotell', 'Eco Palace', 'test-hotell', NULL, 1, 'Newtown, Kolkata, West Bengal, India', NULL, '', 'Kolkata', 'West Bengal', '700125', 'AS', 22.576524, 88.405071, 'abc@met-technologies.com', '9112356998', 'dsanvbnvbn', '<p>dffsfsdf</p>\r\n', '69,67,80,71', 3, NULL, '<p>dffsdfdf</p>\r\n', '<p>sdfsdff</p>\r\n', '<p>safsdfsdf</p>\r\n', 0, 1, 14),
(33, '007', 2, '1', 'QA Hotel', 'QASub', 'qa-hotel', 4, 1, 'Ex Part2, Ayur Vigyan Nagar', NULL, '', 'New Delhi', 'Delhi', '110049', 'IN', 22.577151999999998, 88.4867072, 'qah@yopmail.com', '1234567890', '', '<p>This is test Hotel Description, This is test Hotel Description, This is test Hotel Description, This is  This is test Hotel Description, This is </p>\r\n', '68,76,72', 3, NULL, '<p>This is Test Hotel Policy. This is Test Hotel Policy. This is Test Hotel Policy.</p>\r\n', '<p>This is test General policy, This is test General policy, This is test General policy, </p>\r\n', '<p>This is test Cancellation policy, This is test Cancellation policy, This is test Cancellation policy, This is test Cancellation policy, This is test Cancellation policy, </p>\r\n', 1, 1, 15);

-- --------------------------------------------------------

--
-- Table structure for table `pm_hotel_cancel_policy`
--

CREATE TABLE `pm_hotel_cancel_policy` (
  `id` int(11) NOT NULL,
  `id_hotel` int(11) NOT NULL,
  `duration_type` varchar(25) DEFAULT NULL,
  `value` varchar(11) DEFAULT NULL,
  `fees` int(11) DEFAULT '0',
  `fees_type` varchar(25) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pm_hotel_cancel_policy`
--

INSERT INTO `pm_hotel_cancel_policy` (`id`, `id_hotel`, `duration_type`, `value`, `fees`, `fees_type`) VALUES
(14, 21, 'day', '7', 10, 'parcentage'),
(15, 21, 'hours', '48', 25, 'parcentage'),
(16, 19, 'day', '7', 10, 'parcentage'),
(17, 19, 'hours', '48', 25, 'parcentage'),
(18, 25, 'day', '7', 10, 'parcentage'),
(19, 25, 'hours', '24', 20, 'fixed'),
(20, 19, 'hours', '24', 50, 'parcentage'),
(21, 22, 'day', '7', 40, 'fixed'),
(22, 22, 'hours', '24', 100, 'fixed'),
(23, 24, 'day', '72', 10, 'parcentage');

-- --------------------------------------------------------

--
-- Table structure for table `pm_hotel_file`
--

CREATE TABLE `pm_hotel_file` (
  `id` int(11) NOT NULL,
  `lang` int(11) NOT NULL,
  `id_item` int(11) NOT NULL,
  `home` int(11) DEFAULT '0',
  `checked` int(11) DEFAULT '1',
  `rank` int(11) DEFAULT '0',
  `file` varchar(250) DEFAULT NULL,
  `label` varchar(250) DEFAULT NULL,
  `type` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pm_hotel_file`
--

INSERT INTO `pm_hotel_file` (`id`, `lang`, `id_item`, `home`, `checked`, `rank`, `file`, `label`, `type`) VALUES
(74, 2, 24, NULL, 1, 74, 'classic-room-2x-2.jpg', 'Rooms', 'image'),
(75, 2, 24, NULL, 1, 75, 'classic-room-2x.jpg', 'Rooms', 'image'),
(76, 2, 24, NULL, 1, 76, 'washroom-3.jpg', 'Washroom', 'image'),
(77, 2, 24, NULL, 1, 77, 'property-building.jpg', 'Property Building', 'image'),
(78, 2, 24, NULL, 1, 78, 'classic-room-2x-3.jpg', 'Rooms', 'image'),
(79, 2, 24, NULL, 1, 79, 'av5a1728.jpg', 'Washroom', 'image'),
(80, 2, 24, NULL, 1, 80, 'classic-room-2x-5.jpg', 'Rooms', 'image'),
(81, 2, 24, NULL, 1, 81, 'reception.jpg', 'Reception', 'image'),
(82, 2, 24, NULL, 1, 82, 'washroom-2.jpg', 'Washroom', 'image'),
(83, 2, 24, NULL, 1, 83, 'classic-room-2x-4.jpg', 'Rooms', 'image'),
(84, 2, 24, NULL, 1, 84, 'washroom.jpg', 'Washroom', 'image'),
(85, 2, 24, NULL, 1, 85, 'washroom-4.jpg', 'Washroom', 'image'),
(86, 2, 25, NULL, 1, 86, 'clsassic-room.jpg', 'Rooms', 'image'),
(88, 2, 25, NULL, 1, 88, 'classic2x.jpg', 'Rooms', 'image'),
(89, 2, 25, NULL, 1, 89, 'classic.jpg', 'Rooms', 'image'),
(90, 2, 25, NULL, 1, 90, 'classic-troom.jpg', 'Rooms', 'image'),
(91, 2, 25, NULL, 1, 91, 'classic-2x.jpg', 'Rooms', 'image'),
(92, 2, 25, NULL, 1, 92, 'classic-2x-room.jpg', 'Rooms', 'image'),
(93, 2, 25, NULL, 1, 93, 'classic-room-3.jpg', 'Rooms', 'image'),
(94, 2, 25, NULL, 1, 94, 'classic-room-2.jpg', 'Rooms', 'image'),
(95, 2, 25, NULL, 1, 95, 'classic-room.jpg', 'Rooms', 'image'),
(97, 2, 25, NULL, 1, 97, 'washroom-3.jpg', 'Washroom', 'image'),
(98, 2, 25, NULL, 1, 98, 'washroom-4.jpg', 'Washroom', 'image'),
(99, 2, 25, NULL, 1, 99, 'washroom-5.jpg', 'Washroom', 'image'),
(101, 2, 25, NULL, 1, 101, 'washroom-7.jpg', 'Washroom', 'image'),
(102, 2, 25, NULL, 1, 102, 'washroom.jpg', 'Washroom', 'image'),
(103, 2, 25, NULL, 1, 103, 'washroom-2.jpg', 'Washroom', 'image'),
(104, 2, 25, NULL, 1, 104, 'washroom-6.jpg', 'Washroom', 'image'),
(105, 2, 25, NULL, 1, 105, 'reception.jpg', 'Reception', 'image'),
(107, 2, 26, NULL, 1, 107, 'classic-room-5.jpg', 'Rooms', 'image'),
(108, 2, 26, NULL, 1, 108, 'classic-room-4.jpg', 'Rooms', 'image'),
(109, 2, 26, NULL, 1, 109, 'classic-room-3.jpg', 'Rooms', 'image'),
(110, 2, 26, NULL, 1, 110, 'building.jpg', 'Property Building', 'image'),
(111, 2, 26, NULL, 1, 111, 'classic-room.jpg', 'Rooms', 'image'),
(112, 2, 26, NULL, 1, 112, 'classic-room-2.jpg', 'Rooms', 'image'),
(113, 2, 26, NULL, 1, 113, 'prop-building.jpg', 'Property Building', 'image'),
(114, 2, 26, NULL, 1, 114, 'reception-2.jpg', 'Reception', 'image'),
(115, 2, 26, NULL, 1, 115, 'classic.jpg', 'Rooms', 'image'),
(116, 2, 26, NULL, 1, 116, 'washroom.jpg', 'Washroom', 'image'),
(118, 2, 27, NULL, 1, 118, 'classic-5.jpg', 'Rooms', 'image'),
(119, 2, 27, NULL, 1, 119, 'washroom-2.jpg', 'Washroom', 'image'),
(120, 2, 27, NULL, 1, 120, 'classic-2.jpg', 'Rooms', 'image'),
(121, 2, 27, NULL, 1, 121, 'classic.jpg', 'Rooms', 'image'),
(122, 2, 27, NULL, 1, 122, 'classiuc.jpg', 'Rooms', 'image'),
(123, 2, 27, NULL, 1, 123, 'classic-3.jpg', 'Rooms', 'image'),
(124, 2, 27, NULL, 1, 124, 'classic-4.jpg', 'Rooms', 'image'),
(125, 2, 27, NULL, 1, 125, 'reception.jpg', 'Reception', 'image'),
(126, 2, 27, NULL, 1, 126, 'bathroom.jpg', 'Washroom', 'image'),
(127, 2, 27, NULL, 1, 127, 'reception-2.jpg', 'Reception', 'image'),
(128, 2, 28, NULL, 1, 128, 'classuc-room.jpg', 'Rooms', 'image'),
(129, 2, 28, NULL, 1, 129, 'classic-room-2.jpg', 'Rooms', 'image'),
(130, 2, 28, NULL, 1, 130, 'deluxe-room-2.jpg', 'Rooms', 'image'),
(131, 2, 28, NULL, 1, 131, 'classic-room-5.jpg', 'Rooms', 'image'),
(132, 2, 28, NULL, 1, 132, 'av5a1476.jpg', 'Washroom', 'image'),
(133, 2, 28, NULL, 1, 133, 'deluxe-room.jpg', 'Rooms', 'image'),
(134, 2, 28, NULL, 1, 134, 'classic-room-4.jpg', 'Rooms', 'image'),
(135, 2, 28, NULL, 1, 135, 'deluxe-twin-bed.jpg', 'Rooms', 'image'),
(136, 2, 28, NULL, 1, 136, 'classic-room.jpg', 'Rooms', 'image'),
(137, 2, 28, NULL, 1, 137, 'classic-room-3.jpg', 'Rooms', 'image'),
(138, 2, 28, NULL, 1, 138, 'reception-2.jpg', 'Reception', 'image'),
(139, 2, 28, NULL, 1, 139, 'reception.jpg', 'Reception', 'image'),
(140, 2, 28, NULL, 1, 140, 'deluxe-twin-bed-room.jpg', 'Rooms', 'image'),
(141, 2, 28, NULL, 1, 141, 'deluxe-twin-bed-room-2.jpg', 'Rooms', 'image'),
(142, 2, 28, NULL, 1, 142, 'washroom-classic-2.jpg', 'Washroom', 'image'),
(143, 2, 28, NULL, 1, 143, 'washroom-deluxe-2.jpg', 'Washroom', 'image'),
(144, 2, 28, NULL, 1, 144, 'washroom-deluxe.jpg', 'Washroom', 'image'),
(145, 2, 28, NULL, 1, 145, 'washroom-classic.jpg', 'Washroom', 'image'),
(147, 2, 29, NULL, 1, 147, 'classic-room-3.jpg', 'Rooms', 'image'),
(148, 2, 29, NULL, 1, 148, 'reception-3.jpg', 'Reception', 'image'),
(149, 2, 29, NULL, 1, 149, 'room-classic-2.jpg', 'Rooms', 'image'),
(150, 2, 29, NULL, 1, 150, 'building-prop.jpg', 'Building', 'image'),
(151, 2, 29, NULL, 1, 151, 'classic-room-2.jpg', 'Rooms', 'image'),
(152, 2, 29, NULL, 1, 152, 'classic-room.jpg', 'Rooms', 'image'),
(153, 2, 29, NULL, 1, 153, 'classic.jpg', 'Rooms', 'image'),
(154, 2, 29, NULL, 1, 154, 'reception-2.jpg', 'Reception', 'image'),
(155, 2, 29, NULL, 1, 155, 'reception.jpg', 'Reception', 'image'),
(156, 2, 29, NULL, 1, 156, 'washroom-3.jpg', 'Washroom', 'image'),
(157, 2, 29, NULL, 1, 157, 'washroom.jpg', 'Washroom', 'image'),
(158, 2, 29, NULL, 1, 158, 'room-classic.jpg', 'Rooms', 'image'),
(159, 2, 29, NULL, 1, 159, 'washroom-2.jpg', 'Washroom', 'image'),
(160, 2, 30, NULL, 1, 160, 'classic-room.jpg', 'Rooms', 'image'),
(161, 2, 30, NULL, 1, 161, 'reception-2.jpg', 'Reception', 'image'),
(162, 2, 30, NULL, 1, 162, 'washroom-2.jpg', 'Washroom', 'image'),
(163, 2, 30, NULL, 1, 163, 'washroom.jpg', 'Washroom', 'image'),
(164, 2, 30, NULL, 1, 164, 'building-prop.jpg', 'Building', 'image'),
(165, 2, 30, NULL, 1, 165, 'classic-room-2.jpg', 'Rooms', 'image'),
(166, 2, 30, NULL, 1, 166, 'classic-2x-room.jpg', 'Rooms', 'image'),
(167, 2, 30, NULL, 1, 167, 'classic.jpg', 'Rooms', 'image'),
(168, 2, 30, NULL, 1, 168, 'washroom-3.jpg', 'Washroom', 'image'),
(169, 2, 30, NULL, 1, 169, 'reception.jpg', 'Reception', 'image'),
(170, 2, 26, NULL, 1, 170, 'reception.jpg', 'Reception', 'image'),
(172, 2, 27, NULL, 1, 172, 'washroom.jpg', 'Washroom', 'image'),
(175, 2, 29, NULL, 1, 175, 'building-prop-2.jpg', 'Building', 'image'),
(179, 2, 19, NULL, 1, 178, 'img3.jpg', '', 'image'),
(180, 2, 19, NULL, 1, 179, 'img1.jpg', '', 'image'),
(181, 2, 19, NULL, 1, 180, 'img5.jpg', '', 'image'),
(182, 2, 19, NULL, 1, 181, 'img6.jpg', '', 'image'),
(183, 2, 19, NULL, 1, 182, 'img2.jpg', 'Room Quality', 'image'),
(184, 2, 19, NULL, 1, 183, 'img4.jpg', 'Room Quality', 'image'),
(185, 2, 20, NULL, 1, 184, 'img1.jpeg', '', 'image'),
(186, 2, 20, NULL, 1, 185, 'img3.jpeg', '', 'image'),
(187, 2, 20, NULL, 1, 186, 'img2.jpeg', '', 'image'),
(188, 2, 20, NULL, 1, 187, 'img4.jpg', '', 'image'),
(189, 2, 20, NULL, 1, 188, 'img5.jpeg', '', 'image'),
(190, 2, 20, NULL, 1, 189, 'img6.jpg', '', 'image'),
(191, 2, 21, NULL, 1, 190, 'img2.jpg', '', 'image'),
(192, 2, 21, NULL, 1, 191, 'img1.jpg', '', 'image'),
(193, 2, 21, NULL, 1, 192, 'img3.jpg', '', 'image'),
(194, 2, 21, NULL, 1, 193, 'img5.jpg', '', 'image'),
(195, 2, 21, NULL, 1, 194, 'img6.jpg', '', 'image'),
(196, 2, 21, NULL, 1, 195, 'img4.jpg', '', 'image'),
(197, 2, 22, NULL, 1, 196, 'img2.jpg', '', 'image'),
(198, 2, 22, NULL, 1, 197, 'img4.jpg', '', 'image'),
(199, 2, 22, NULL, 1, 198, 'img5.jpg', '', 'image'),
(200, 2, 22, NULL, 1, 199, 'img1.jpg', '', 'image'),
(201, 2, 22, NULL, 1, 200, 'img3.jpg', '', 'image'),
(202, 2, 22, NULL, 1, 201, 'img6.jpg', '', 'image'),
(203, 2, 23, NULL, 1, 202, 'img-3160-1-2-0.jpg', '', 'image'),
(204, 2, 23, NULL, 1, 203, 'room-5.jpg', NULL, 'image'),
(205, 2, 33, NULL, 1, 204, '250px-qa-badge.jpg', NULL, 'image'),
(206, 2, 33, NULL, 1, 205, 'quality-assurance1.jpg', NULL, 'image'),
(207, 2, 33, NULL, 1, 206, 'color-2-inch-qa-logo-300x300.jpg', NULL, 'image');

-- --------------------------------------------------------

--
-- Table structure for table `pm_lang`
--

CREATE TABLE `pm_lang` (
  `id` int(11) NOT NULL,
  `title` varchar(20) DEFAULT NULL,
  `locale` varchar(20) DEFAULT NULL,
  `main` int(11) DEFAULT '0',
  `checked` int(11) DEFAULT '0',
  `rank` int(11) DEFAULT '0',
  `tag` varchar(20) DEFAULT NULL,
  `rtl` int(11) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pm_lang`
--

INSERT INTO `pm_lang` (`id`, `title`, `locale`, `main`, `checked`, `rank`, `tag`, `rtl`) VALUES
(1, 'Français', 'fr_FR', 0, 2, 2, 'fr', 0),
(2, 'English', 'en_GB', 1, 1, 1, 'en', 0),
(3, 'عربي', 'ar_MA', 0, 2, 3, 'ar', 1),
(4, 'Spanish', 'esp_ESP', NULL, 2, 4, 'esp', 0);

-- --------------------------------------------------------

--
-- Table structure for table `pm_lang_file`
--

CREATE TABLE `pm_lang_file` (
  `id` int(11) NOT NULL,
  `id_item` int(11) NOT NULL,
  `home` int(11) DEFAULT '0',
  `checked` int(11) DEFAULT '1',
  `rank` int(11) DEFAULT '0',
  `file` varchar(250) DEFAULT NULL,
  `label` varchar(250) DEFAULT NULL,
  `type` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pm_lang_file`
--

INSERT INTO `pm_lang_file` (`id`, `id_item`, `home`, `checked`, `rank`, `file`, `label`, `type`) VALUES
(1, 1, 0, 1, 2, 'fr.png', '', 'image'),
(2, 2, 0, 1, 1, 'gb.png', '', 'image'),
(3, 3, 0, 1, 3, 'ar.png', '', 'image'),
(4, 4, NULL, 1, 4, '1200px-flag-of-spain-svg-1.png', '', 'image');

-- --------------------------------------------------------

--
-- Table structure for table `pm_location`
--

CREATE TABLE `pm_location` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `address` varchar(250) DEFAULT NULL,
  `lat` double DEFAULT NULL,
  `lng` double DEFAULT NULL,
  `checked` int(11) DEFAULT '0',
  `pages` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pm_location`
--

INSERT INTO `pm_location` (`id`, `name`, `address`, `lat`, `lng`, `checked`, `pages`) VALUES
(1, 'Gupta hotels', 'Maldives Mint, Neeloafaru Magu 20014, Maldives', -0.60627, 73.08858, 1, '2'),
(4, 'Fitser', 'Candor Techspace, Ground Floor, Tower A1, IT/ITES SEZ, Block No DH, Action Area 1D, New Town, Rajarhat, Kolkata, West Bengal 700156', 22.5736131, 88.4813425, 1, '2');

-- --------------------------------------------------------

--
-- Table structure for table `pm_media`
--

CREATE TABLE `pm_media` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `pm_media_file`
--

CREATE TABLE `pm_media_file` (
  `id` int(11) NOT NULL,
  `id_item` int(11) NOT NULL,
  `home` int(11) DEFAULT '0',
  `checked` int(11) DEFAULT '1',
  `rank` int(11) DEFAULT '0',
  `file` varchar(250) DEFAULT NULL,
  `label` varchar(250) DEFAULT NULL,
  `type` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `pm_menu`
--

CREATE TABLE `pm_menu` (
  `id` int(11) NOT NULL,
  `lang` int(11) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `title` varchar(250) DEFAULT NULL,
  `id_parent` int(11) DEFAULT NULL,
  `item_type` varchar(30) DEFAULT NULL,
  `id_item` int(11) DEFAULT NULL,
  `url` text,
  `main` int(11) DEFAULT '1',
  `footer` int(11) DEFAULT '0',
  `checked` int(11) DEFAULT '0',
  `rank` int(11) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pm_menu`
--

INSERT INTO `pm_menu` (`id`, `lang`, `name`, `title`, `id_parent`, `item_type`, `id_item`, `url`, `main`, `footer`, `checked`, `rank`) VALUES
(1, 1, 'Accueil', 'Lorem ipsum dolor sit amet', NULL, 'page', 1, NULL, 1, 0, 1, 1),
(1, 2, 'Home', 'Gupta Hotels , Luxury Hotels', NULL, 'page', 1, '', 1, 0, 1, 1),
(1, 3, 'ترحيب', 'هو سقطت الساحلية ذات, أن.', NULL, 'page', 1, NULL, 1, 0, 1, 1),
(1, 4, 'Home', 'Gupta Hotels , Luxury Hotels', NULL, 'page', 1, '', 1, 0, 1, 1),
(2, 1, 'Contact', 'Contact', NULL, 'page', 2, NULL, 1, 1, 1, 9),
(2, 2, 'Contact', 'Contact', NULL, 'page', 2, NULL, 1, 1, 1, 9),
(2, 3, 'جهة الاتصال', 'جهة الاتصال', NULL, 'page', 2, NULL, 1, 1, 1, 9),
(2, 4, 'Contact', 'Contact', NULL, 'page', 2, NULL, 1, 1, 1, 9),
(3, 1, 'Mentions légales', 'Mentions légales', NULL, 'page', 3, NULL, 0, 1, 2, 10),
(3, 2, 'Legal notices', 'Legal notices', NULL, 'page', 3, NULL, 0, 1, 2, 10),
(3, 3, 'يذكر القانونية', 'يذكر القانونية', NULL, 'page', 3, NULL, 0, 1, 2, 10),
(3, 4, 'Legal notices', 'Legal notices', NULL, 'page', 3, NULL, 0, 1, 2, 10),
(4, 1, 'Plan du site', 'Plan du site', NULL, 'page', 4, NULL, 0, 1, 2, 11),
(4, 2, 'Sitemap', 'Sitemap', NULL, 'page', 4, '', 0, 1, 2, 11),
(4, 3, 'خريطة الموقع', 'خريطة الموقع', NULL, 'page', 4, NULL, 0, 1, 2, 11),
(4, 4, 'Sitemap', 'Sitemap', NULL, 'page', 4, NULL, 0, 1, 2, 11),
(5, 1, 'Qui sommes-nous ?', 'Qui sommes-nous ?', NULL, 'page', 5, NULL, 1, 0, 1, 2),
(5, 2, 'About us', 'About us', NULL, 'page', 5, NULL, 1, 0, 1, 2),
(5, 3, 'معلومات عنا', 'معلومات عنا', NULL, 'page', 5, NULL, 1, 0, 1, 2),
(5, 4, 'About us', 'About us', NULL, 'page', 5, NULL, 1, 0, 1, 2),
(6, 1, 'Galerie', 'Galerie', NULL, 'page', 7, NULL, 1, 0, 1, 8),
(6, 2, 'Gallery', 'Gallery', NULL, 'page', 7, NULL, 1, 0, 1, 8),
(6, 3, 'صور معرض', 'صور معرض', NULL, 'page', 7, NULL, 1, 0, 1, 8),
(6, 4, 'Gallery', 'Gallery', NULL, 'page', 7, NULL, 1, 0, 1, 8),
(7, 1, 'Hôtels', 'Hôtels', NULL, 'page', 9, NULL, 1, 0, 2, 4),
(7, 2, 'Hotels', 'Hotels', NULL, 'page', 9, NULL, 1, 0, 2, 4),
(7, 3, 'الفنادق', 'الفنادق', NULL, 'page', 9, NULL, 1, 0, 2, 4),
(7, 4, 'Hotels', 'Hotels', NULL, 'page', 9, NULL, 1, 0, 2, 4),
(8, 1, 'Réserver', 'Réserver', NULL, 'page', 10, NULL, 1, 0, 1, 7),
(8, 2, 'Booking', 'Booking', NULL, 'page', 10, '', 0, 0, 1, 7),
(8, 3, 'الحجز', 'الحجز', NULL, 'page', 10, NULL, 1, 0, 1, 7),
(8, 4, 'Booking', 'Booking', NULL, 'page', 10, '', 0, 0, 1, 7),
(9, 1, 'Activités', 'Activités', NULL, 'page', 16, NULL, 1, 0, 1, 5),
(9, 2, 'Activities', 'Activities', NULL, 'page', 16, '', 1, 0, 2, 5),
(9, 3, 'Activities', 'Activities', NULL, 'page', 16, NULL, 1, 0, 1, 5),
(9, 4, 'Activities', 'Activities', NULL, 'page', 16, '', 1, 0, 2, 5),
(10, 1, 'Destinations', '', NULL, 'page', 18, '', 1, 0, 1, 6),
(10, 2, 'Destinations', '', NULL, 'page', 18, '', 1, 0, 1, 6),
(10, 3, 'وجهات', '', NULL, 'page', 18, '', 1, 0, 1, 6),
(10, 4, 'Destinations', '', NULL, 'page', 18, '', 1, 0, 1, 6),
(11, 2, 'Accommodations', 'Accommodations', NULL, 'page', 19, '', 1, 0, 1, 3),
(11, 4, 'Accommodations', 'Accommodations', NULL, 'page', 19, '', 1, 0, 1, 3),
(14, 2, 'Offers', ' Offers', NULL, 'page', 32, '', 1, 0, 1, 14),
(15, 2, 'Terms & Conditions', 'Terms & Conditions', NULL, 'page', 33, '', 0, 1, 1, 15);

-- --------------------------------------------------------

--
-- Table structure for table `pm_message`
--

CREATE TABLE `pm_message` (
  `id` int(11) NOT NULL,
  `add_date` int(11) DEFAULT NULL,
  `edit_date` int(11) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `address` longtext,
  `phone` varchar(100) DEFAULT NULL,
  `subject` varchar(250) DEFAULT NULL,
  `msg` longtext
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pm_message`
--

INSERT INTO `pm_message` (`id`, `add_date`, `edit_date`, `name`, `email`, `address`, `phone`, `subject`, `msg`) VALUES
(1, 1558607759, NULL, 'chitta', 'chittaranjan10@gmail.com', '', 'mmmmmm@####', 'hi', 'hi'),
(2, 1558614226, NULL, 'PrideMedia', 'test@yopmail.com', 'asdsad', '728934905488', 'GGGGGGGGGGG', '@'),
(3, 1558614285, NULL, 'PrideMedia', 'test@yopmail.com', 'asdsad', '728934905488', 'GGGGGGGGGGG', '%$'),
(4, 1558614339, NULL, 'PrideMedia', 'test@yopmail.com', '', '', 'GGGGGGGGGGG', '@'),
(5, 1558614388, NULL, 'PrideMedia', 'test@yopmail.com', '', '', 'GGGGGGGGGGG', 'test'),
(6, 1558614541, NULL, 'PrideMedia', 'test@yopmail.com', '', 'dsadsdsdsadsd', 'GGGGGGGGGGG', 'test'),
(7, 1558697341, NULL, 'fdfdg', 'fgdf@ghg.jhghg', '', '', 'fdfd', 'fdgfd'),
(8, 1558961496, NULL, 'eertret', 'rtrtertre@gmail.vom', '', '', 'ettetrt', 'rrttetet'),
(9, 1558961508, NULL, 'eertret', 'rtrtertre@gmail.com', '', '', 'ettetrt', 'rrttetet'),
(10, 1559052079, NULL, 'Sonjoy Bhadra', 'sonjoy.bhadra@met-technologies.com', 'asasdasdsa', '91 + 9475359786', 'test', 'fsdfsdfsdfsdfsd'),
(11, 1559053925, NULL, 'Sonjoy Bhadra', 'sonjoy.bhadra@met-technologies.com', 'fsdfsdfsf', '91 + 9475359786', 'test', 'fsdfsdfsdfsdfsdf'),
(12, 1559054124, NULL, 'Sonjoy Bhadra', 'sonjoy.bhadra@met-technologies.com', 'fsdfsdfsf', '91 + 9475359786', 'test', 'fsdfsdfsdfsdfsdf'),
(13, 1559054149, NULL, 'Sonjoy Bhadra', 'sonjoy.bhadra@met-technologies.com', 'fsdfsdfsf', '91 + 9475359786', 'test', 'fsdfsdfsdfsdfsdf'),
(14, 1559054265, NULL, 'Sonjoy Bhadra', 'sonjoy.bhadra@met-technologies.com', 'asasdsadsa', '91 + 9475359786', 'test', 'dsadsadsad'),
(15, 1559054667, NULL, 'Sonjoy Bhadra', 'sonjoy.bhadra@met-technologies.com', 'sdsadsadsad', '91 + 9475359786', 'test', 'asdsadsd'),
(16, 1559054704, NULL, 'Sonjoy Bhadra', 'sonjoy.bhadra@met-technologies.com', 'sdsadsadsad', '91 + 9475359786', 'test', 'asdsadsd'),
(17, 1559054778, NULL, 'Sonjoy Bhadra', 'sonjoy.bhadra@met-technologies.com', 'sdsadsadsad', '91 + 9475359786', 'test', 'asdsadsd'),
(18, 1559054785, NULL, 'Sonjoy Bhadra', 'sonjoy.bhadra@met-technologies.com', 'sdsadsadsad', '91 + 9475359786', 'test', 'asdsadsd'),
(19, 1559215505, NULL, 'Pal Tanu', 'pt@yopmail.com', 'Unitech, Kolkata- 70000', '1234567890', 'Test Subject', 'This is test.'),
(20, 1559296362, NULL, 'subhankar dutta', 'subhankard815@gmail.com', 'newtown', '8334045660', 'test subject', 'test message'),
(21, 1560459219, NULL, 'Rupam Dey', 'ba@fitser.com', '', '6598464684', 'test', 'Lorem Ipsum'),
(22, 1560499023, NULL, 'Test', 'mou@hmk.vmm', 'test', '2345678900', 'test', 'jkhk'),
(23, 1560860412, 1560860462, 'Test Message', 'tm@yopmail.com', '', 'a', 'Test subject', 'This is test message. This is test message. This is test message. '),
(24, 1560956677, NULL, 'Test', 'test@gmail.com', 'testtttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttt', '2345678900', 'test', 'testy'),
(25, 1561361678, NULL, 'Test', 'rai1436dey@gmail.com', 'a', '1234567890', 'test', 'fgvgnh'),
(26, 1561543500, NULL, 'y56747', '47567457@jjhkj.ooi', 'hfgjn56', '7777777777', 'fyjft', 'fjtjftjftj'),
(27, 1561559961, NULL, 'Roy Pratap', 'roy.pratap@gmail.com', 'kolkata', '9804047430', 'pcr testr', 'testing'),
(28, 1561632466, NULL, 'Pal Das', 'pd2@yopmail.com', '', '1212121212', 'test subject', 'test message'),
(29, 1561720213, NULL, 'sdad', 'asds@gggg.jjj', '', '1234567890', 'sdsad', 'dfdsf'),
(30, 1562228987, NULL, 'Arshad', 'arshad@ymail.com', '', '9836953330', 'adsfgg', 'sdfgfhh'),
(31, 1562229039, NULL, 'Arshad', 'arshad@mail.com', '', '9836953330', 'addffg', 'afaggghh'),
(32, 1577950380, NULL, 'Test NAme', 'test@demo.com', 'Kolkata', '1236547896', 'Test Subject', 'Test Message'),
(33, 1595927016, NULL, 'Sarkar QA', 'sqa@yopmail.com', 'Birat Nagar', '1234567890', 'QA Sub', 'Qa Message, QA Message, QA Message');

-- --------------------------------------------------------

--
-- Table structure for table `pm_notification`
--

CREATE TABLE `pm_notification` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `action_id` int(11) DEFAULT NULL,
  `notification_title` varchar(100) NOT NULL,
  `notification_desc` longtext NOT NULL,
  `action_url` varchar(100) DEFAULT NULL,
  `status` enum('1','0') NOT NULL DEFAULT '0' COMMENT '1=Read,0=Unread',
  `created_ts` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pm_notification`
--

INSERT INTO `pm_notification` (`id`, `user_id`, `action_id`, `notification_title`, `notification_desc`, `action_url`, `status`, `created_ts`) VALUES
(92, 323, 10307, 'Booking Checked In', 'Your booking # 10307 is successfully Checked In', NULL, '0', '2019-12-23 03:00:31'),
(93, 323, 10307, 'Booking Checked Out', 'Your booking # 10307 is successfully Checked Out', NULL, '0', '2019-12-23 03:00:53'),
(94, 255, 10314, 'Booking Cancle', 'Booking is Successfully Cancled. Booking ID :10314', NULL, '0', '2019-12-23 03:02:25'),
(95, 231, 10315, 'Booking Checked In', 'Your booking # 10315 is successfully Checked In', NULL, '0', '2019-12-23 03:10:52'),
(96, 231, 10315, 'Booking Checked Out', 'Your booking # 10315 is successfully Checked Out', NULL, '0', '2019-12-23 03:11:02'),
(97, 231, 10315, 'Booking Checked In', 'Your booking # 10315 is successfully Checked In', NULL, '0', '2019-12-23 03:13:22'),
(98, 255, 10313, 'Booking Cancle', 'Booking is Successfully Cancled. Booking ID :10313', NULL, '0', '2019-12-23 03:34:14'),
(99, 255, 10317, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10317', NULL, '0', '2019-12-23 03:37:03'),
(100, 255, 10312, 'Booking Cancle', 'Booking is Successfully Cancled. Booking ID :10312', NULL, '0', '2019-12-23 03:40:06'),
(101, 255, 10317, 'Booking Cancle', 'Booking is Successfully Cancled. Booking ID :10317', NULL, '0', '2019-12-23 03:42:29'),
(102, 255, 10318, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10318', NULL, '0', '2019-12-23 03:47:07'),
(103, 255, 10318, 'Booking Cancle', 'Booking is Successfully Cancled. Booking ID :10318', NULL, '0', '2019-12-23 03:55:09'),
(104, 255, 10319, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10319', NULL, '0', '2019-12-23 03:59:22'),
(105, 255, 10319, 'Booking Cancle', 'Booking is Successfully Cancled. Booking ID :10319', NULL, '0', '2019-12-23 04:04:18'),
(106, 266, 10304, 'Booking Cancle', 'Booking is Successfully Cancled. Booking ID :10304', NULL, '0', '2019-12-23 04:04:57'),
(107, 266, 10320, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10320', NULL, '0', '2019-12-23 04:06:04'),
(108, 266, 10320, 'Booking Cancle', 'Booking is Successfully Cancled. Booking ID :10320', NULL, '0', '2019-12-23 04:07:41'),
(109, 266, 10321, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10321', NULL, '0', '2019-12-23 04:11:15'),
(110, 255, 10319, 'Booking Cancle', 'Booking is Successfully Cancled. Booking ID :10319', NULL, '0', '2019-12-23 04:26:14'),
(111, 232, 10324, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10324', NULL, '0', '2019-12-24 04:00:03'),
(112, 232, 10324, 'Booking Cancle', 'Booking is Successfully Cancled. Booking ID :10324', NULL, '0', '2019-12-24 04:01:47'),
(113, 255, 255, 'Profile Update', 'Hi, Your Profile is Successfully Updated.', NULL, '0', '2019-12-24 04:10:31'),
(114, 266, 266, 'Profile Update', 'Hi, Your Profile is Successfully Updated.', NULL, '0', '2019-12-24 04:10:42'),
(115, 255, 255, 'Profile Update', 'Hi, Your Profile is Successfully Updated.', NULL, '0', '2019-12-24 04:14:19'),
(116, 255, 255, 'Profile Update', 'Hi, Your Profile is Successfully Updated.', NULL, '0', '2019-12-24 04:34:39'),
(117, 304, 304, 'Password Change', 'Hi, Your Profile Password is Successfully Changed.', NULL, '0', '2019-12-24 05:27:37'),
(118, 304, 10326, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10326', NULL, '0', '2019-12-24 05:38:22'),
(119, 255, 255, 'Profile Update', 'Hi, Your Profile is Successfully Updated.', NULL, '0', '2019-12-24 05:52:50'),
(120, 304, 304, 'Profile Update', 'Hi, Your Profile is Successfully Updated.', NULL, '0', '2019-12-24 06:08:59'),
(121, 255, 255, 'Profile Update', 'Hi, Your Profile is Successfully Updated.', NULL, '0', '2019-12-24 06:09:09'),
(122, 255, 10327, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10327', NULL, '0', '2019-12-24 06:18:54'),
(123, 255, 10328, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10328', NULL, '0', '2019-12-26 03:10:31'),
(124, 255, 10328, 'Booking Cancle', 'Booking is Successfully Cancled. Booking ID :10328', NULL, '0', '2019-12-26 03:11:01'),
(125, 266, 10329, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10329', NULL, '0', '2019-12-26 03:18:15'),
(126, 266, 10329, 'Booking Cancle', 'Booking is Successfully Cancled. Booking ID :10329', NULL, '0', '2019-12-26 03:18:34'),
(127, 266, 10330, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10330', NULL, '0', '2019-12-27 11:38:07'),
(128, 266, 10331, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10331', NULL, '0', '2019-12-27 12:42:50'),
(129, 266, 10331, 'Booking Checked In', 'Your booking # 10331 is successfully Checked In', NULL, '0', '2019-12-27 12:46:00'),
(130, 266, 10332, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10332', NULL, '0', '2019-12-27 01:39:40'),
(131, 266, 10333, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10333', NULL, '0', '2019-12-27 01:40:23'),
(132, 266, 10334, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10334', NULL, '0', '2019-12-27 01:42:59'),
(133, 266, 10335, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10335', NULL, '0', '2019-12-27 01:48:47'),
(134, 266, 10336, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10336', NULL, '0', '2019-12-27 02:42:51'),
(135, 266, 10331, 'Booking Checked In', 'Your booking # 10331 is successfully Checked In', NULL, '0', '2019-12-27 02:55:17'),
(136, 266, 10331, 'Booking Checked In', 'Your booking # 10331 is successfully Checked In', NULL, '0', '2019-12-27 02:55:39'),
(137, 266, 10332, 'Booking Checked In', 'Your booking # 10332 is successfully Checked In', NULL, '0', '2019-12-27 02:57:41'),
(138, 266, 10332, 'Booking Checked In', 'Your booking # 10332 is successfully Checked In', NULL, '0', '2019-12-27 02:58:17'),
(139, 266, 10331, 'Booking Checked In', 'Your booking # 10331 is successfully Checked In', NULL, '0', '2019-12-27 02:59:29'),
(140, 266, 10337, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10337', NULL, '0', '2019-12-27 03:04:09'),
(141, 266, 10336, 'Booking Cancle', 'Booking is Successfully Cancled. Booking ID :10336', NULL, '0', '2019-12-27 03:08:58'),
(142, 266, 10338, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10338', NULL, '0', '2019-12-27 03:16:06'),
(143, 266, 10337, 'Booking Cancle', 'Booking is Successfully Cancled. Booking ID :10337', NULL, '0', '2019-12-27 03:23:10'),
(144, 266, 10338, 'Booking Cancle', 'Booking is Successfully Cancled. Booking ID :10338', NULL, '0', '2019-12-27 03:33:49'),
(145, 266, 10335, 'Booking Cancle', 'Booking is Successfully Cancled. Booking ID :10335', NULL, '0', '2019-12-27 03:33:56'),
(146, 266, 10339, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10339', NULL, '0', '2019-12-27 04:08:09'),
(147, 266, 10339, 'Booking Checked In', 'Your booking # 10339 is successfully Checked In', NULL, '0', '2019-12-27 04:10:10'),
(148, 266, 10333, 'Booking Cancle', 'Booking is Successfully Cancled. Booking ID :10333', NULL, '0', '2019-12-27 04:19:50'),
(149, 304, 10340, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10340', NULL, '0', '2019-12-30 10:27:18'),
(150, 304, 10340, 'Booking Cancle', 'Booking is Successfully Cancled. Booking ID :10340', NULL, '0', '2019-12-30 10:33:36'),
(151, 304, 10341, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10341', NULL, '0', '2019-12-30 10:40:08'),
(152, 304, 10341, 'Booking Checked In', 'Your booking # 10341 is successfully Checked In', NULL, '0', '2019-12-30 10:44:03'),
(153, 255, 10342, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10342', NULL, '0', '2019-12-30 01:31:38'),
(154, 255, 10343, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10343', NULL, '0', '2019-12-30 01:40:32'),
(155, 232, 10344, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10344', NULL, '0', '2019-12-31 12:14:55'),
(156, 232, 232, 'Profile Update', 'Hi, Your Profile is Successfully Updated.', NULL, '0', '2019-12-31 12:16:45'),
(157, 232, 10345, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10345', NULL, '0', '2019-12-31 12:28:44'),
(158, 232, 10344, 'Booking Cancle', 'Booking is Successfully Cancled. Booking ID :10344', NULL, '0', '2019-12-31 12:37:57'),
(159, 232, 232, 'Profile Update', 'Hi, Your Profile is Successfully Updated.', NULL, '0', '2019-12-31 12:38:15'),
(160, 266, 10346, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10346', NULL, '0', '2019-12-31 02:33:20'),
(161, 266, 10347, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10347', NULL, '0', '2019-12-31 02:48:11'),
(162, 266, 10348, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10348', NULL, '0', '2019-12-31 02:54:09'),
(163, 255, 255, 'Profile Update', 'Hi, Your Profile is Successfully Updated.', NULL, '0', '2019-12-31 02:57:50'),
(164, 255, 255, 'Profile Update', 'Hi, Your Profile is Successfully Updated.', NULL, '0', '2019-12-31 03:00:23'),
(165, 266, 10349, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10349', NULL, '0', '2019-12-31 03:17:09'),
(166, 266, 266, 'Profile Update', 'Hi, Your Profile is Successfully Updated.', NULL, '0', '2019-12-31 03:36:18'),
(167, 266, 10350, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10350', NULL, '0', '2019-12-31 03:43:30'),
(168, 266, 10351, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10351', NULL, '0', '2019-12-31 03:46:38'),
(169, 255, 10352, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10352', NULL, '0', '2019-12-31 03:48:31'),
(170, 255, 10353, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10353', NULL, '0', '2020-01-02 11:18:09'),
(171, 266, 10354, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10354', NULL, '0', '2020-01-02 04:22:04'),
(172, 255, 10355, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10355', NULL, '0', '2020-01-02 05:52:22'),
(173, 255, 10356, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10356', NULL, '0', '2020-01-02 06:00:45'),
(174, 304, 304, 'Profile Update', 'Hi, Your Profile is Successfully Updated.', NULL, '0', '2020-01-02 06:09:15'),
(175, 304, 10357, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10357', NULL, '0', '2020-01-02 06:39:06'),
(176, 304, 304, 'Profile Update', 'Hi, Your Profile is Successfully Updated.', NULL, '0', '2020-01-02 07:01:31'),
(177, 329, 329, 'Registration Successful', 'Welcome to HMS. Your registration is successful.', NULL, '0', '2020-01-02 07:10:20'),
(178, 266, 10358, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10358', NULL, '0', '2020-01-02 07:24:44'),
(179, 266, 10358, 'Booking Cancle', 'Booking is Successfully Cancled. Booking ID :10358', NULL, '0', '2020-01-02 07:25:10'),
(180, 330, 330, 'Registration Successful', 'Welcome to HMS. Your registration is successful.', NULL, '0', '2020-01-03 11:21:22'),
(181, 232, 10359, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10359', NULL, '0', '2020-01-03 11:38:52'),
(182, 232, 232, 'Profile Update', 'Hi, Your Profile is Successfully Updated.', NULL, '0', '2020-01-03 11:50:42'),
(183, 266, 10360, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10360', NULL, '0', '2020-01-03 01:50:41'),
(184, 266, 10361, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10361', NULL, '0', '2020-01-03 01:51:40'),
(185, 255, 10362, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10362', NULL, '0', '2020-01-03 02:01:56'),
(186, 255, 10363, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10363', NULL, '0', '2020-01-03 02:51:08'),
(187, 330, 10364, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10364', NULL, '0', '2020-01-03 04:36:44'),
(188, 330, 10364, 'Booking Cancle', 'Booking is Successfully Cancled. Booking ID :10364', NULL, '0', '2020-01-03 04:39:27'),
(189, 266, 10365, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10365', NULL, '0', '2020-01-03 06:08:50'),
(190, 266, 10366, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10366', NULL, '0', '2020-01-03 06:21:28'),
(191, 266, 10367, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10367', NULL, '0', '2020-01-03 06:46:43'),
(192, 266, 10368, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10368', NULL, '0', '2020-01-03 06:51:00'),
(193, 266, 10369, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10369', NULL, '0', '2020-01-03 07:06:33'),
(194, 266, 10370, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10370', NULL, '0', '2020-01-03 07:09:33'),
(195, 266, 10371, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10371', NULL, '0', '2020-01-03 07:11:49'),
(196, 255, 10372, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10372', NULL, '0', '2020-01-03 07:11:53'),
(197, 255, 10373, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10373', NULL, '0', '2020-01-03 07:20:55'),
(198, 266, 10374, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10374', NULL, '0', '2020-01-03 07:41:02'),
(199, 232, 10375, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10375', NULL, '0', '2020-01-06 12:34:22'),
(200, 255, 10376, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10376', NULL, '0', '2020-01-06 12:48:29'),
(201, 255, 10377, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10377', NULL, '0', '2020-01-06 12:55:49'),
(202, 255, 10378, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10378', NULL, '0', '2020-01-06 01:00:33'),
(203, 266, 10379, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10379', NULL, '0', '2020-01-06 01:25:05'),
(204, 331, 331, 'Registration Successful', 'Welcome to HMS. Your registration is successful.', NULL, '0', '2020-01-06 01:52:39'),
(205, 255, 10380, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10380', NULL, '0', '2020-01-06 02:24:59'),
(206, 332, 332, 'Registration Successful', 'Welcome to HMS. Your registration is successful.', NULL, '0', '2020-01-06 02:52:46'),
(207, 255, 10381, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10381', NULL, '0', '2020-01-06 03:09:46'),
(208, 255, 10382, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10382', NULL, '0', '2020-01-06 03:11:58'),
(209, 266, 10383, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10383', NULL, '0', '2020-01-06 03:20:57'),
(210, 266, 10383, 'Booking Cancle', 'Booking is Successfully Cancled. Booking ID :10383', NULL, '0', '2020-01-06 03:22:54'),
(211, 266, 10384, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10384', NULL, '0', '2020-01-06 04:08:10'),
(212, 304, 10385, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10385', NULL, '0', '2020-01-06 04:35:20'),
(213, 264, 10386, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10386', NULL, '0', '2020-01-06 05:43:08'),
(214, 333, 333, 'Registration Successful', 'Welcome to HMS. Your registration is successful.', NULL, '0', '2020-01-06 06:14:04'),
(215, 334, 334, 'Registration Successful', 'Welcome to HMS. Your registration is successful.', NULL, '0', '2020-01-06 06:20:37'),
(216, 334, 10387, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10387', NULL, '0', '2020-01-06 06:22:40'),
(217, 266, 10388, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10388', NULL, '0', '2020-01-06 07:26:11'),
(218, 266, 10389, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10389', NULL, '0', '2020-01-07 10:47:02'),
(219, 266, 10389, 'Booking Checked In', 'Your booking # 10389 is successfully Checked In', NULL, '0', '2020-01-07 12:05:22'),
(220, 255, 10391, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10391', NULL, '0', '2020-01-07 12:49:40'),
(221, 255, 10391, 'Booking Cancle', 'Booking is Successfully Cancled. Booking ID :10391', NULL, '0', '2020-01-07 12:50:03'),
(222, 335, 335, 'Registration Successful', 'Welcome to HMS. Your registration is successful.', NULL, '0', '2020-01-07 01:16:20'),
(223, 336, 336, 'Registration Successful', 'Welcome to HMS. Your registration is successful.', NULL, '0', '2020-01-07 01:25:12'),
(224, 332, 10394, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10394', NULL, '0', '2020-01-07 02:37:25'),
(225, 332, 10394, 'Booking Cancle', 'Booking is Successfully Cancled. Booking ID :10394', NULL, '0', '2020-01-07 02:38:51'),
(226, 266, 10395, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10395', NULL, '0', '2020-01-07 03:05:25'),
(227, 337, 337, 'Registration Successful', 'Welcome to HMS. Your registration is successful.', NULL, '0', '2020-01-07 03:29:05'),
(228, 255, 10398, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10398', NULL, '0', '2020-01-07 03:44:19'),
(229, 266, 10399, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10399', NULL, '0', '2020-01-07 03:49:08'),
(230, 338, 338, 'Registration Successful', 'Welcome to HMS. Your registration is successful.', NULL, '0', '2020-01-07 04:04:30'),
(231, 266, 10402, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10402', NULL, '0', '2020-01-07 04:37:08'),
(232, 266, 10402, 'Booking Cancle', 'Booking is Successfully Cancled. Booking ID :10402', NULL, '0', '2020-01-07 04:37:38'),
(233, 255, 10403, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10403', NULL, '0', '2020-01-07 04:40:12'),
(234, 255, 10403, 'Booking Cancle', 'Booking is Successfully Cancled. Booking ID :10403', NULL, '0', '2020-01-07 04:41:25'),
(235, 266, 10399, 'Booking Cancle', 'Booking is Successfully Cancled. Booking ID :10399', NULL, '0', '2020-01-07 04:47:28'),
(236, 266, 10395, 'Booking Cancle', 'Booking is Successfully Cancled. Booking ID :10395', NULL, '0', '2020-01-07 04:47:47'),
(237, 255, 10398, 'Booking Cancle', 'Booking is Successfully Cancled. Booking ID :10398', NULL, '0', '2020-01-07 04:51:48'),
(238, 266, 10388, 'Booking Cancle', 'Booking is Successfully Cancled. Booking ID :10388', NULL, '0', '2020-01-07 04:56:42'),
(239, 266, 10384, 'Booking Cancle', 'Booking is Successfully Cancled. Booking ID :10384', NULL, '0', '2020-01-07 04:59:19'),
(240, 255, 10382, 'Booking Cancle', 'Booking is Successfully Cancled. Booking ID :10382', NULL, '0', '2020-01-07 05:00:01'),
(241, 255, 10381, 'Booking Cancle', 'Booking is Successfully Cancled. Booking ID :10381', NULL, '0', '2020-01-07 05:09:09'),
(242, 266, 10379, 'Booking Cancle', 'Booking is Successfully Cancled. Booking ID :10379', NULL, '0', '2020-01-07 05:11:49'),
(243, 266, 10404, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10404', NULL, '0', '2020-01-07 05:12:25'),
(244, 255, 10380, 'Booking Cancle', 'Booking is Successfully Cancled. Booking ID :10380', NULL, '0', '2020-01-07 05:13:37'),
(245, 266, 10405, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10405', NULL, '0', '2020-01-07 05:20:51'),
(246, 255, 10406, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10406', NULL, '0', '2020-01-07 05:21:22'),
(247, 255, 10406, 'Booking Cancle', 'Booking is Successfully Cancled. Booking ID :10406', NULL, '0', '2020-01-07 05:22:26'),
(248, 255, 10403, 'Booking Checked In', 'Your booking # 10403 is successfully Checked In', NULL, '0', '2020-01-07 05:48:22'),
(249, 266, 10407, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10407', NULL, '0', '2020-01-07 05:59:39'),
(250, 339, 339, 'Registration Successful', 'Welcome to HMS. Your registration is successful.', NULL, '0', '2020-01-07 07:19:16'),
(251, 266, 10404, 'Booking Checked In', 'Your booking # 10404 is successfully Checked In', NULL, '0', '2020-01-07 07:41:11'),
(252, 266, 10405, 'Booking Cancle', 'Booking is Successfully Cancled. Booking ID :10405', NULL, '0', '2020-01-08 01:41:57'),
(253, 266, 266, 'Profile Update', 'Hi, Your Profile is Successfully Updated.', NULL, '0', '2020-01-09 11:52:01'),
(254, 231, 10390, 'Booking Checked In', 'Your booking # 10390 is successfully Checked In', NULL, '0', '2020-01-09 04:38:25'),
(255, 231, 10390, 'Booking Checked Out', 'Your booking # 10390 is successfully Checked Out', NULL, '0', '2020-01-09 04:38:30'),
(256, 233, 10408, 'Booking Checked In', 'Your booking # 10408 is successfully Checked In', NULL, '0', '2020-01-10 12:22:38'),
(257, 233, 10408, 'Booking Checked Out', 'Your booking # 10408 is successfully Checked Out', NULL, '0', '2020-01-10 02:21:26'),
(258, 334, 10387, 'Booking Checked In', 'Your booking # 10387 is successfully Checked In', NULL, '0', '2020-01-10 02:39:57'),
(259, 334, 10387, 'Booking Checked Out', 'Your booking # 10387 is successfully Checked Out', NULL, '0', '2020-01-10 02:40:01'),
(260, 231, 10400, 'Booking Checked In', 'Your booking # 10400 is successfully Checked In', NULL, '0', '2020-01-10 02:40:42'),
(261, 231, 10400, 'Booking Checked Out', 'Your booking # 10400 is successfully Checked Out', NULL, '0', '2020-01-10 02:40:46'),
(262, 266, 10389, 'Booking Checked Out', 'Your booking # 10389 is successfully Checked Out', NULL, '0', '2020-01-10 02:55:01'),
(263, 266, 10404, 'Booking Checked In', 'Your booking # 10404 is successfully Checked In', NULL, '0', '2020-01-10 02:59:32'),
(264, 278, 10411, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10411', NULL, '0', '2020-01-12 03:10:10'),
(265, 278, 10411, 'Booking Cancle', 'Booking is Successfully Cancled. Booking ID :10411', NULL, '0', '2020-01-12 03:11:03'),
(266, 338, 338, 'Profile Update', 'Hi, Your Profile is Successfully Updated.', NULL, '0', '2020-01-13 11:50:50'),
(267, 338, 338, 'Profile Update', 'Hi, Your Profile is Successfully Updated.', NULL, '0', '2020-01-13 12:12:32'),
(268, 338, 338, 'Profile Update', 'Hi, Your Profile is Successfully Updated.', NULL, '0', '2020-01-13 12:20:59'),
(269, 338, 10413, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10413', NULL, '0', '2020-01-13 01:08:08'),
(270, 338, 10414, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10414', NULL, '0', '2020-01-13 01:08:49'),
(271, 241, 10416, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10416', NULL, '0', '2020-01-13 03:34:52'),
(272, 266, 10418, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10418', NULL, '0', '2020-01-13 05:37:08'),
(273, 338, 10414, 'Booking Checked In', 'Your booking # 10414 is successfully Checked In', NULL, '0', '2020-01-13 06:19:40'),
(274, 338, 10414, 'Booking Checked Out', 'Your booking # 10414 is successfully Checked Out', NULL, '0', '2020-01-13 06:20:58'),
(275, 232, 10421, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10421', NULL, '0', '2020-01-14 03:16:18'),
(276, 338, 338, 'Profile Update', 'Hi, Your Profile is Successfully Updated.', NULL, '0', '2020-01-14 04:09:03'),
(277, 338, 338, 'Profile Update', 'Hi, Your Profile is Successfully Updated.', NULL, '0', '2020-01-14 05:21:21'),
(278, 232, 10420, 'Booking Checked In', 'Your booking # 10420 is successfully Checked In', NULL, '0', '2020-01-14 06:07:27'),
(279, 266, 10407, 'Booking Checked In', 'Your booking # 10407 is successfully Checked In', NULL, '0', '2020-01-14 06:10:47'),
(280, 231, 10396, 'Booking Checked In', 'Your booking # 10396 is successfully Checked In', NULL, '0', '2020-01-14 06:41:40'),
(281, 231, 10396, 'Booking Checked Out', 'Your booking # 10396 is successfully Checked Out', NULL, '0', '2020-01-14 06:43:10'),
(282, 232, 10420, 'Booking Checked In', 'Your booking # 10420 is successfully Checked In', NULL, '0', '2020-01-14 06:50:15'),
(283, 266, 10407, 'Booking Checked In', 'Your booking # 10407 is successfully Checked In', NULL, '0', '2020-01-14 06:51:05'),
(284, 237, 10417, 'Booking Checked In', 'Your booking # 10417 is successfully Checked In', NULL, '0', '2020-01-14 06:51:41'),
(285, 237, 10417, 'Booking Checked Out', 'Your booking # 10417 is successfully Checked Out', NULL, '0', '2020-01-14 06:52:03'),
(286, 237, 10422, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10422', NULL, '0', '2020-01-15 06:48:35'),
(287, 237, 10423, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10423', NULL, '0', '2020-01-15 06:58:03'),
(288, 237, 10424, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10424', NULL, '0', '2020-01-15 01:09:43'),
(289, 237, 237, 'Profile Update', 'Hi, Your Profile is Successfully Updated.', NULL, '0', '2020-01-15 01:27:22'),
(290, 338, 338, 'Profile Update', 'Hi, Your Profile is Successfully Updated.', NULL, '0', '2020-01-15 01:28:39'),
(291, 237, 10425, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10425', NULL, '0', '2020-01-15 08:40:44'),
(292, 237, 10428, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10428', NULL, '0', '2020-01-15 10:33:35'),
(293, 237, 10429, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10429', NULL, '0', '2020-01-15 10:42:01'),
(294, 338, 10430, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10430', NULL, '0', '2020-01-15 10:46:16'),
(295, 338, 10431, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10431', NULL, '0', '2020-01-15 11:16:21'),
(296, 338, 10432, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10432', NULL, '0', '2020-01-15 11:28:52'),
(297, 338, 10433, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10433', NULL, '0', '2020-01-15 12:02:14'),
(298, 338, 10434, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10434', NULL, '0', '2020-01-15 02:41:33'),
(299, 338, 10435, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10435', NULL, '0', '2020-01-15 02:46:31'),
(300, 231, 10436, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10436', NULL, '0', '2020-01-15 02:47:07'),
(301, 231, 10437, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10437', NULL, '0', '2020-01-16 09:22:30'),
(302, 231, 10438, 'Booking Checked In', 'Your booking # 10438 is successfully Checked In', NULL, '0', '2020-01-16 11:19:36'),
(303, 231, 10438, 'Booking Checked Out', 'Your booking # 10438 is successfully Checked Out', NULL, '0', '2020-01-16 11:25:16'),
(304, 338, 10441, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10441', NULL, '0', '2020-01-16 02:21:59'),
(305, 266, 10442, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10442', NULL, '0', '2020-01-17 06:16:18'),
(306, 266, 10443, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10443', NULL, '0', '2020-01-17 09:10:22'),
(307, 266, 10444, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10444', NULL, '0', '2020-01-17 01:03:12'),
(308, 266, 10445, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10445', NULL, '0', '2020-01-17 01:03:57'),
(309, 266, 10445, 'Booking Cancle', 'Booking is Successfully Cancled. Booking ID :10445', NULL, '0', '2020-01-17 01:04:16'),
(310, 266, 10444, 'Booking Cancle', 'Booking is Successfully Cancled. Booking ID :10444', NULL, '0', '2020-01-17 01:04:41'),
(311, 266, 10442, 'Booking Cancle', 'Booking is Successfully Cancled. Booking ID :10442', NULL, '0', '2020-01-17 01:04:46'),
(312, 338, 10446, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10446', NULL, '0', '2020-01-19 04:54:34'),
(313, 338, 10447, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10447', NULL, '0', '2020-01-20 11:12:33'),
(314, 338, 338, 'Password Change', 'Hi, Your Profile Password is Successfully Changed.', NULL, '0', '2020-01-21 06:41:39'),
(315, 266, 10448, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10448', NULL, '0', '2020-01-21 09:43:50'),
(316, 338, 10449, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10449', NULL, '0', '2020-01-21 01:49:57'),
(317, 338, 10450, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10450', NULL, '0', '2020-01-21 01:52:20'),
(318, 338, 10451, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10451', NULL, '0', '2020-01-22 08:56:12'),
(319, 338, 10452, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10452', NULL, '0', '2020-01-22 09:00:56'),
(320, 266, 10453, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10453', NULL, '0', '2020-01-22 10:01:13'),
(321, 266, 10454, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10454', NULL, '0', '2020-01-22 10:04:28'),
(322, 266, 10455, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10455', NULL, '0', '2020-01-22 10:10:31'),
(323, 266, 10456, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10456', NULL, '0', '2020-01-22 11:11:32'),
(324, 266, 10457, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10457', NULL, '0', '2020-01-22 11:12:50'),
(325, 266, 10456, 'Booking Cancle', 'Booking is Successfully Cancled. Booking ID :10456', NULL, '0', '2020-01-22 11:13:44'),
(326, 266, 10455, 'Booking Cancle', 'Booking is Successfully Cancled. Booking ID :10455', NULL, '0', '2020-01-22 11:13:52'),
(327, 266, 10453, 'Booking Cancle', 'Booking is Successfully Cancled. Booking ID :10453', NULL, '0', '2020-01-22 11:13:59'),
(328, 266, 10454, 'Booking Cancle', 'Booking is Successfully Cancled. Booking ID :10454', NULL, '0', '2020-01-22 11:14:04'),
(329, 266, 10458, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10458', NULL, '0', '2020-01-22 11:15:49'),
(330, 266, 10459, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10459', NULL, '0', '2020-01-22 11:35:18'),
(331, 237, 10460, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10460', NULL, '0', '2020-01-22 11:38:10'),
(332, 237, 10461, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10461', NULL, '0', '2020-01-22 11:41:51'),
(333, 237, 10462, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10462', NULL, '0', '2020-01-22 11:44:28'),
(334, 237, 10463, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10463', NULL, '0', '2020-01-22 11:44:35'),
(335, 237, 10464, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10464', NULL, '0', '2020-01-22 11:49:34'),
(336, 237, 10465, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10465', NULL, '0', '2020-01-22 11:54:43'),
(337, 237, 10466, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10466', NULL, '0', '2020-01-22 12:06:55'),
(338, 237, 10467, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10467', NULL, '0', '2020-01-22 12:07:57'),
(339, 237, 10468, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10468', NULL, '0', '2020-01-22 12:09:25'),
(340, 237, 10469, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10469', NULL, '0', '2020-01-22 12:12:39'),
(341, 237, 10470, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10470', NULL, '0', '2020-01-22 12:19:29'),
(342, 237, 10471, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10471', NULL, '0', '2020-01-23 04:29:34'),
(343, 237, 10472, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10472', NULL, '0', '2020-01-23 05:07:47'),
(344, 237, 10473, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10473', NULL, '0', '2020-01-23 05:29:58'),
(345, 338, 10474, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10474', NULL, '0', '2020-01-23 06:59:26'),
(346, 338, 10475, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10475', NULL, '0', '2020-01-23 06:59:35'),
(347, 338, 10476, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10476', NULL, '0', '2020-01-23 06:59:59'),
(348, 338, 10477, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10477', NULL, '0', '2020-01-23 07:02:50'),
(349, 338, 10478, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10478', NULL, '0', '2020-01-23 07:43:05'),
(350, 338, 10479, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10479', NULL, '0', '2020-01-23 07:53:58'),
(351, 338, 10480, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10480', NULL, '0', '2020-01-23 07:54:45'),
(352, 338, 10482, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10482', NULL, '0', '2020-01-23 08:01:31'),
(353, 338, 10483, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10483', NULL, '0', '2020-01-23 08:03:19'),
(354, 338, 10484, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10484', NULL, '0', '2020-01-23 08:08:52'),
(355, 338, 10485, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10485', NULL, '0', '2020-01-23 08:09:36'),
(356, 338, 10486, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10486', NULL, '0', '2020-01-23 08:11:32'),
(357, 338, 10488, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10488', NULL, '0', '2020-01-23 08:29:59'),
(358, 338, 10489, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10489', NULL, '0', '2020-01-23 08:34:29'),
(359, 338, 10490, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10490', NULL, '0', '2020-01-23 08:35:24'),
(360, 338, 10491, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10491', NULL, '0', '2020-01-23 08:43:16'),
(361, 338, 10492, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10492', NULL, '0', '2020-01-23 09:11:45'),
(362, 338, 10493, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10493', NULL, '0', '2020-01-23 09:16:18'),
(363, 338, 10494, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10494', NULL, '0', '2020-01-23 09:19:30'),
(364, 338, 10495, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10495', NULL, '0', '2020-01-23 09:20:59'),
(365, 338, 10496, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10496', NULL, '0', '2020-01-23 09:37:35'),
(366, 338, 10497, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10497', NULL, '0', '2020-01-23 09:39:35'),
(367, 338, 10498, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10498', NULL, '0', '2020-01-23 09:53:10'),
(368, 338, 10499, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10499', NULL, '0', '2020-01-23 09:56:37'),
(369, 338, 10500, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10500', NULL, '0', '2020-01-23 09:58:03'),
(370, 338, 10501, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10501', NULL, '0', '2020-01-23 10:00:32'),
(371, 338, 10502, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10502', NULL, '0', '2020-01-23 10:11:45'),
(372, 338, 10503, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10503', NULL, '0', '2020-01-23 12:02:55'),
(373, 338, 10498, 'Booking Checked In', 'Your booking # 10498 is successfully Checked In', NULL, '0', '2020-01-24 07:52:19'),
(374, 338, 10509, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10509', NULL, '0', '2020-01-24 12:01:49'),
(375, 338, 10513, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10513', NULL, '0', '2020-01-27 07:06:09'),
(376, 237, 10514, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10514', NULL, '0', '2020-01-27 07:23:58'),
(377, 338, 10513, 'Booking Cancle', 'Booking is Successfully Cancled. Booking ID :10513', NULL, '0', '2020-01-27 09:44:12'),
(378, 338, 10515, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10515', NULL, '0', '2020-01-27 09:59:30'),
(379, 338, 10515, 'Booking Cancle', 'Booking is Successfully Cancled. Booking ID :10515', NULL, '0', '2020-01-27 10:17:51'),
(380, 338, 10516, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10516', NULL, '0', '2020-01-27 10:20:05'),
(381, 338, 10517, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10517', NULL, '0', '2020-01-27 10:20:21'),
(382, 338, 10517, 'Booking Cancle', 'Booking is Successfully Cancled. Booking ID :10517', NULL, '0', '2020-01-27 10:20:48'),
(383, 338, 10518, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10518', NULL, '0', '2020-01-27 10:44:44'),
(384, 338, 10518, 'Booking Cancle', 'Booking is Successfully Cancled. Booking ID :10518', NULL, '0', '2020-01-27 12:31:51'),
(385, 338, 10519, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10519', NULL, '0', '2020-01-27 01:20:36'),
(386, 255, 10520, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10520', NULL, '0', '2020-01-27 01:20:41'),
(387, 338, 10521, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10521', NULL, '0', '2020-01-28 06:01:18'),
(388, 266, 10522, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10522', NULL, '0', '2020-01-28 06:21:01'),
(389, 266, 10523, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10523', NULL, '0', '2020-01-28 06:26:46'),
(390, 266, 10523, 'Booking Checked In', 'Your booking # 10523 is successfully Checked In', NULL, '0', '2020-01-28 07:52:20'),
(391, 266, 10523, 'Booking Checked Out', 'Your booking # 10523 is successfully Checked Out', NULL, '0', '2020-01-28 07:53:54'),
(392, 266, 10522, 'Booking Checked In', 'Your booking # 10522 is successfully Checked In', NULL, '0', '2020-01-28 07:59:10'),
(393, 266, 10522, 'Booking Checked Out', 'Your booking # 10522 is successfully Checked Out', NULL, '0', '2020-01-28 07:59:43'),
(394, 338, 10524, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10524', NULL, '0', '2020-01-28 08:03:12'),
(395, 338, 10525, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10525', NULL, '0', '2020-01-28 08:04:51'),
(396, 266, 10526, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10526', NULL, '0', '2020-01-28 08:08:00'),
(397, 338, 10527, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10527', NULL, '0', '2020-01-28 08:12:44'),
(398, 338, 10521, 'Booking Cancle', 'Booking is Successfully Cancled. Booking ID :10521', NULL, '0', '2020-01-28 08:14:56'),
(399, 338, 10528, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10528', NULL, '0', '2020-01-28 08:15:17'),
(400, 338, 10524, 'Booking Cancle', 'Booking is Successfully Cancled. Booking ID :10524', NULL, '0', '2020-01-28 08:17:51'),
(401, 338, 10529, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10529', NULL, '0', '2020-01-28 08:18:17'),
(402, 338, 10525, 'Booking Cancle', 'Booking is Successfully Cancled. Booking ID :10525', NULL, '0', '2020-01-28 08:19:37'),
(403, 338, 10530, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10530', NULL, '0', '2020-01-28 08:19:58'),
(404, 338, 10531, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10531', NULL, '0', '2020-01-28 08:22:37'),
(405, 338, 10530, 'Booking Cancle', 'Booking is Successfully Cancled. Booking ID :10530', NULL, '0', '2020-01-28 09:23:59'),
(406, 232, 10532, 'Booking Checked In', 'Your booking # 10532 is successfully Checked In', NULL, '0', '2020-01-28 09:32:32'),
(407, 232, 10532, 'Booking Checked Out', 'Your booking # 10532 is successfully Checked Out', NULL, '0', '2020-01-28 09:33:30'),
(408, 266, 10534, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10534', NULL, '0', '2020-01-28 10:28:12'),
(409, 338, 10535, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10535', NULL, '0', '2020-01-28 10:59:22'),
(410, 266, 10536, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10536', NULL, '0', '2020-01-28 11:44:11'),
(411, 338, 10531, 'Booking Cancle', 'Booking is Successfully Cancled. Booking ID :10531', NULL, '0', '2020-01-28 12:19:29'),
(412, 338, 10535, 'Booking Cancle', 'Booking is Successfully Cancled. Booking ID :10535', NULL, '0', '2020-01-28 12:20:58'),
(413, 231, 10537, 'Booking Checked In', 'Your booking # 10537 is successfully Checked In', NULL, '0', '2020-01-29 06:22:29'),
(414, 231, 10537, 'Booking Checked Out', 'Your booking # 10537 is successfully Checked Out', NULL, '0', '2020-01-29 06:22:43'),
(415, 266, 10538, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10538', NULL, '0', '2020-01-29 09:18:17'),
(416, 266, 10539, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10539', NULL, '0', '2020-01-29 10:50:24'),
(417, 266, 10538, 'Booking Checked In', 'Your booking # 10538 is successfully Checked In', NULL, '0', '2020-01-29 12:06:38'),
(418, 232, 10540, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10540', NULL, '0', '2020-01-29 12:21:30'),
(419, 338, 10541, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10541', NULL, '0', '2020-01-29 01:50:24'),
(420, 338, 10542, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10542', NULL, '0', '2020-01-29 01:50:32'),
(421, 338, 10543, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10543', NULL, '0', '2020-01-29 02:04:26'),
(422, 338, 10544, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10544', NULL, '0', '2020-01-30 05:35:19'),
(423, 338, 10545, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10545', NULL, '0', '2020-01-30 05:35:21'),
(424, 338, 10546, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10546', NULL, '0', '2020-01-30 06:01:27'),
(425, 338, 10547, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10547', NULL, '0', '2020-01-30 07:28:11'),
(426, 338, 10548, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10548', NULL, '0', '2020-01-30 07:28:56'),
(427, 338, 10549, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10549', NULL, '0', '2020-01-30 07:34:09'),
(428, 338, 10550, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10550', NULL, '0', '2020-01-30 09:43:53'),
(429, 338, 10551, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10551', NULL, '0', '2020-01-30 11:09:30'),
(430, 338, 10552, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10552', NULL, '0', '2020-01-31 06:28:54'),
(431, 338, 10553, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10553', NULL, '0', '2020-01-31 06:33:53'),
(432, 338, 10554, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10554', NULL, '0', '2020-01-31 06:34:54'),
(433, 338, 10555, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10555', NULL, '0', '2020-01-31 06:45:47'),
(434, 338, 10556, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10556', NULL, '0', '2020-01-31 07:05:43'),
(435, 338, 338, 'Profile Update', 'Hi, Your Profile is Successfully Updated.', NULL, '0', '2020-01-31 07:08:47'),
(436, 338, 10556, 'Booking Cancle', 'Booking is Successfully Cancled. Booking ID :10556', NULL, '0', '2020-01-31 07:20:31'),
(437, 338, 10555, 'Booking Cancle', 'Booking is Successfully Cancled. Booking ID :10555', NULL, '0', '2020-01-31 07:23:42'),
(438, 304, 10557, 'Booking Checked In', 'Your booking # 10557 is successfully Checked In', NULL, '0', '2020-01-31 07:54:17'),
(439, 304, 10557, 'Booking Checked Out', 'Your booking # 10557 is successfully Checked Out', NULL, '0', '2020-01-31 07:55:34'),
(440, 338, 10558, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10558', NULL, '0', '2020-01-31 08:59:26'),
(441, 338, 10559, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10559', NULL, '0', '2020-01-31 08:59:26'),
(442, 338, 338, 'Password Change', 'Hi, Your Profile Password is Successfully Changed.', NULL, '0', '2020-01-31 11:59:45'),
(443, 338, 10560, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10560', NULL, '0', '2020-01-31 12:14:27'),
(444, 338, 10561, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10561', NULL, '0', '2020-01-31 12:14:52'),
(445, 338, 10563, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10563', NULL, '0', '2020-01-31 12:28:51'),
(446, 231, 10564, 'Booking Checked In', 'Your booking # 10564 is successfully Checked In', NULL, '0', '2020-02-03 01:42:15'),
(447, 231, 10564, 'Booking Checked In', 'Your booking # 10564 is successfully Checked In', NULL, '0', '2020-02-03 01:48:17'),
(448, 231, 10564, 'Booking Checked In', 'Your booking # 10564 is successfully Checked In', NULL, '0', '2020-02-03 01:51:51'),
(449, 231, 10564, 'Booking Checked Out', 'Your booking # 10564 is successfully Checked Out', NULL, '0', '2020-02-03 02:08:54'),
(450, 272, 10565, 'Booking Checked In', 'Your booking # 10565 is successfully Checked In', NULL, '0', '2020-02-04 12:08:55'),
(451, 272, 10565, 'Booking Checked Out', 'Your booking # 10565 is successfully Checked Out', NULL, '0', '2020-02-04 12:19:25'),
(452, 347, 347, 'Registration Successful', 'Welcome to HMS. Your registration is successful.', NULL, '0', '2020-02-05 08:54:29'),
(453, 266, 10566, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10566', NULL, '0', '2020-02-05 09:15:09'),
(454, 347, 10567, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10567', NULL, '0', '2020-02-06 09:40:26'),
(455, 347, 10568, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10568', NULL, '0', '2020-02-06 09:40:29'),
(456, 347, 10568, 'Booking Cancle', 'Booking is Successfully Cancled. Booking ID :10568', NULL, '0', '2020-02-06 09:56:50'),
(457, 347, 10567, 'Booking Checked In', 'Your booking # 10567 is successfully Checked In', NULL, '0', '2020-02-06 09:59:58'),
(458, 347, 10567, 'Booking Checked Out', 'Your booking # 10567 is successfully Checked Out', NULL, '0', '2020-02-06 10:02:25'),
(459, 338, 10569, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10569', NULL, '0', '2020-02-07 06:04:54'),
(460, 338, 10570, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10570', NULL, '0', '2020-02-07 08:04:25'),
(461, 348, 348, 'Registration Successful', 'Welcome to HMS. Your registration is successful.', NULL, '0', '2020-02-07 10:00:39'),
(462, 347, 10571, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10571', NULL, '0', '2020-02-12 08:46:04'),
(463, 347, 10572, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10572', NULL, '0', '2020-02-12 08:46:09'),
(464, 347, 10571, 'Booking Cancle', 'Booking is Successfully Cancled. Booking ID :10571', NULL, '0', '2020-02-12 09:18:58'),
(465, 347, 10572, 'Booking Checked In', 'Your booking # 10572 is successfully Checked In', NULL, '0', '2020-02-12 09:21:26'),
(466, 232, 10573, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10573', NULL, '0', '2020-02-13 12:43:28'),
(467, 338, 338, 'Profile Update', 'Hi, Your Profile is Successfully Updated.', NULL, '0', '2020-02-14 07:06:00'),
(468, 347, 10574, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10574', NULL, '0', '2020-02-17 07:51:32'),
(469, 232, 10575, 'Booking Checked In', 'Your booking # 10575 is successfully Checked In', NULL, '0', '2020-02-19 08:33:06'),
(470, 232, 10575, 'Booking Checked Out', 'Your booking # 10575 is successfully Checked Out', NULL, '0', '2020-02-19 08:36:23'),
(471, 229, 10577, 'Booking Checked In', 'Your booking # 10577 is successfully Checked In', NULL, '0', '2020-02-25 10:37:47'),
(472, 229, 10578, 'Booking Checked In', 'Your booking # 10578 is successfully Checked In', NULL, '0', '2020-02-25 10:41:37'),
(473, 233, 10583, 'Booking Checked In', 'Your booking # 10583 is successfully Checked In', NULL, '0', '2020-03-06 05:54:50'),
(474, 349, 349, 'Registration Successful', 'Welcome to HMS. Your registration is successful.', NULL, '0', '2020-03-11 07:02:45'),
(475, 308, 10586, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10586', NULL, '0', '2020-03-11 12:44:48'),
(476, 350, 350, 'Registration Successful', 'Welcome to HMS. Your registration is successful.', NULL, '0', '2020-06-11 04:24:29'),
(477, 351, 351, 'Registration Successful', 'Welcome to HMS. Your registration is successful.', NULL, '0', '2020-07-08 06:58:15'),
(478, 231, 10589, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10589', NULL, '0', '2020-07-25 12:15:44'),
(479, 352, 10590, 'Booking Checked In', 'Your booking # 10590 is successfully Checked In', NULL, '0', '2020-07-31 07:23:34'),
(480, 352, 10591, 'Booking Checked In', 'Your booking # 10591 is successfully Checked In', NULL, '0', '2020-07-31 08:58:00'),
(481, 352, 10591, 'Booking Checked Out', 'Your booking # 10591 is successfully Checked Out', NULL, '0', '2020-07-31 09:51:26'),
(482, 353, 353, 'Registration Successful', 'Welcome to HMS. Your registration is successful.', NULL, '0', '2020-08-03 07:04:38'),
(483, 353, 10593, 'Booking Confirm', 'Booking is Successfully Done. Booking ID :10593', NULL, '0', '2020-08-03 07:43:26');

-- --------------------------------------------------------

--
-- Table structure for table `pm_offer`
--

CREATE TABLE `pm_offer` (
  `id` int(11) NOT NULL,
  `lang` int(11) NOT NULL,
  `users` text,
  `name` varchar(50) DEFAULT NULL,
  `alias` text,
  `id_destination` int(11) NOT NULL,
  `id_hotel` int(11) NOT NULL,
  `id_room` int(11) NOT NULL,
  `facilities` text,
  `offer_price` int(11) NOT NULL,
  `text` text,
  `no_day_night` varchar(50) DEFAULT NULL,
  `max_adults` int(11) DEFAULT NULL,
  `max_children` int(11) NOT NULL DEFAULT '0',
  `day_start` int(11) DEFAULT NULL,
  `day_end` int(11) DEFAULT NULL,
  `checked` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pm_offer`
--

INSERT INTO `pm_offer` (`id`, `lang`, `users`, `name`, `alias`, `id_destination`, `id_hotel`, `id_room`, `facilities`, `offer_price`, `text`, `no_day_night`, `max_adults`, `max_children`, `day_start`, `day_end`, `checked`) VALUES
(1, 2, '2', 'All Inclusive Gateway Package', 'all-inclusive-gateway-package', 1, 21, 35, '42,41,43', 1845, '<p>Sun. Sand. Unlimited fun, with all-inclusive vacation packages and hotel deals at select Marriott brand destinations, you can make the most of every moment. Feel truly pampered as you enjoy meals, drinks and more. Search for dates at our hotels worldwide.</p>\r\n\r\n<h3>Inclusions</h3>\r\n\r\n<ul>\r\n	<li>3 days 2 nights of stay</li>\r\n	<li>Complimentary Breakfast</li>\r\n	<li>One session of SPA</li>\r\n	<li>Bottle of Champagne</li>\r\n	<li>Free pick and drop from Airport</li>\r\n</ul>\r\n', '2', 2, 0, 1569801600, 1577750400, 1),
(2, 2, '2', 'Business Travel Package', 'business-travel-package', 3, 28, 48, '45,46,47,48', 1549, '<p>Gupta Hotels offer \'Business Travel’, designed to suit your business travel needs. Your special requests are now available with our compliments to make your business trip a truly comfortable and rewarding experience.</p>\r\n\r\n<h3>Inclusions</h3>\r\n', '3', 2, 1, 1546300800, 1577750400, 1),
(16, 2, '1', 'Special Summer Package', 'special-summer-package', 1, 2, 7, '44,49,50,51,52,53,54,56,55', 2599, '<p>Beat the heat this summer with special package at the Gupta Hotels. Whether you had a long hectic week and yearning for a short breaks to rejuvenate or would like to pamper yourself and family with finest form of indulgences, none provides a better option than Gupta Hotels. Our summer packages are therefor designed to make every moment of your stay an incredible one.</p>\r\n\r\n<h3>Inclusions</h3>\r\n', '3', 2, 1, 1564617600, 1569801600, 1),
(17, 2, '1', 'Honeymoon Bliss Package', 'honeymoon-bliss-package', 1, 21, 35, '57,58,59,60,61,62,63,64,65', 2500, '<p>Travel to the destination of your dreams, and let Gupta Hotels help you fill your love story with champagne, room service and other indulgences. Search dates and rates and create a getaway you’ll never forget.</p>\r\n\r\n<h3>Inclusions</h3>\r\n', '3', 2, 0, 1565049600, 1580428800, 1);

-- --------------------------------------------------------

--
-- Table structure for table `pm_offer_file`
--

CREATE TABLE `pm_offer_file` (
  `id` int(11) NOT NULL,
  `lang` int(11) NOT NULL,
  `id_item` int(11) NOT NULL,
  `home` int(11) DEFAULT '0',
  `checked` int(11) DEFAULT '1',
  `rank` int(11) DEFAULT '0',
  `file` varchar(250) DEFAULT NULL,
  `label` varchar(250) DEFAULT NULL,
  `type` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pm_offer_file`
--

INSERT INTO `pm_offer_file` (`id`, `lang`, `id_item`, `home`, `checked`, `rank`, `file`, `label`, `type`) VALUES
(1, 2, 15, NULL, 1, 1, 'bait-and-switch-for-sail-fish-and-marlin-in-guatemala-pacific-blue-bayou.jpg', NULL, 'image'),
(2, 2, 1, NULL, 1, 2, 'signature-blogs-luxury-fishing-lodges-getaway.jpg', '', 'image'),
(5, 2, 2, NULL, 1, 5, 'business-tour.jpg', '', 'image'),
(6, 2, 17, NULL, 1, 6, '10-breathtaking-honeymoon-destinations-in-india-to-visit-during-monsoons-goa.png', '', 'image'),
(7, 2, 16, NULL, 1, 7, 'rh-beach-2-optimized.jpg', '', 'image');

-- --------------------------------------------------------

--
-- Table structure for table `pm_package`
--

CREATE TABLE `pm_package` (
  `id` int(11) NOT NULL,
  `users` text,
  `id_hotel` int(11) DEFAULT NULL,
  `id_accommodation` int(11) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `days` text,
  `min_nights` int(11) DEFAULT NULL,
  `max_nights` int(11) DEFAULT NULL,
  `day_start` int(11) DEFAULT NULL,
  `day_end` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pm_package`
--

INSERT INTO `pm_package` (`id`, `users`, `id_hotel`, `id_accommodation`, `name`, `days`, `min_nights`, `max_nights`, `day_start`, `day_end`) VALUES
(10, '2', NULL, NULL, 'Special', '1,2,3,4,5,6,7', 1, 3, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `pm_packages_file`
--

CREATE TABLE `pm_packages_file` (
  `id` int(11) NOT NULL,
  `lang` int(11) NOT NULL,
  `id_item` int(11) NOT NULL,
  `home` int(11) DEFAULT '0',
  `checked` int(11) DEFAULT '1',
  `rank` int(11) DEFAULT '0',
  `file` varchar(250) DEFAULT NULL,
  `label` varchar(250) DEFAULT NULL,
  `type` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `pm_page`
--

CREATE TABLE `pm_page` (
  `id` int(11) NOT NULL,
  `lang` int(11) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `title` varchar(250) DEFAULT NULL,
  `subtitle` varchar(250) DEFAULT NULL,
  `title_tag` varchar(250) DEFAULT NULL,
  `alias` varchar(100) DEFAULT NULL,
  `descr` longtext,
  `robots` varchar(20) DEFAULT NULL,
  `keywords` varchar(250) DEFAULT NULL,
  `intro` longtext,
  `text` longtext,
  `id_parent` int(11) DEFAULT NULL,
  `page_model` varchar(50) DEFAULT NULL,
  `article_model` varchar(50) DEFAULT NULL,
  `home` int(11) DEFAULT '0',
  `checked` int(11) DEFAULT '0',
  `rank` int(11) DEFAULT '0',
  `add_date` int(11) DEFAULT NULL,
  `edit_date` int(11) DEFAULT NULL,
  `comment` int(11) DEFAULT '0',
  `rating` int(11) DEFAULT '0',
  `system` int(11) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pm_page`
--

INSERT INTO `pm_page` (`id`, `lang`, `name`, `title`, `subtitle`, `title_tag`, `alias`, `descr`, `robots`, `keywords`, `intro`, `text`, `id_parent`, `page_model`, `article_model`, `home`, `checked`, `rank`, `add_date`, `edit_date`, `comment`, `rating`, `system`) VALUES
(1, 1, 'Accueil', 'Lorem ipsum dolor sit amet', 'Consectetur adipiscing elit', 'Accueil', '', '', 'index,follow', '', '', '', NULL, 'home', '', 1, 1, 1, 1557396800, 1557396800, 0, 0, 0),
(1, 2, 'Home ', 'Met HMS , Luxury Hotels', '', ' Everything. Right, where you need it', 'home', '', 'index,follow', '', '', '<blockquote class=\"text-center\">\r\n<p>A man travels the world over in search of what he needs and returns home to find it.</p>\r\n</blockquote>\r\n\r\n<p class=\"text-muted\" style=\"text-align: center;\">- George A. Moore -</p>\r\n', NULL, 'home', '', 1, 1, 1, 1557396800, 1572602558, 0, 0, 0),
(1, 3, 'ترحيب', 'هو سقطت الساحلية ذات, أن.', 'غير بمعارضة وهولندا، الإقتصادية قد, فقد الفرنسي المعاهدات قد من.', 'ترحيب', '', '', 'index,follow', '', '', '', NULL, 'home', '', 1, 1, 1, 1557396800, 1557396800, 0, 0, 0),
(1, 4, 'Home', 'Gupta hotels , Luxury Hotels', '', 'Gupta hotels : Everything. Right, where you need it', '', '', 'index,follow', '', '', '<blockquote class=\"text-center\">\r\n<p>A man travels the world over in search of what he needs and returns home to find it.</p>\r\n</blockquote>\r\n\r\n<p class=\"text-muted\" style=\"text-align: center;\">- George A. Moore -</p>\r\n', NULL, 'home', '', 1, 1, 1, 1557396800, 1557470710, 0, 0, 0),
(2, 1, 'Contact', 'Contact', '', 'Contact', 'contact', '', 'index,follow', '', '', '', NULL, 'contact', '', 0, 1, 9, 1557396800, 1557396800, 0, 0, 0),
(2, 2, 'Contact', 'Contact', '', 'Contact', 'contact', '', 'index,follow', '', '', '', NULL, 'contact', '', 0, 1, 9, 1557396800, 1577941190, 0, 0, 0),
(2, 3, 'جهة الاتصال', 'جهة الاتصال', '', 'جهة الاتصال', 'contact', '', 'index,follow', '', '', '', NULL, 'contact', '', 0, 1, 9, 1557396800, 1557396800, 0, 0, 0),
(2, 4, 'Contact', 'Contact', '', 'Contact', 'contact', '', 'index,follow', '', '', '', NULL, 'contact', '', 0, 1, 9, 1557396800, 1557396800, 0, 0, 0),
(3, 1, 'Mentions légales', 'Mentions légales', '', 'Mentions légales', 'mentions-legales', '', 'index,follow', '', '', '', NULL, 'page', '', 0, 1, 13, 1557396800, 1557396800, 0, 0, 0),
(3, 2, 'Legal notices', 'Legal notices', '', 'Legal notices', 'legal-notices', '', 'index,follow', '', '', '<p> </span> Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque fringilla vel est at rhoncus. Cras porttitor ligula vel magna vehicula accumsan. Mauris eget elit et sem commodo interdum. Aenean dolor sem, tincidunt ac neque tempus, hendrerit blandit lacus. Vivamus placerat nulla in mi tristique, fringilla fermentum nisl vehicula. Nullam quis eros non magna tincidunt interdum ac eu eros. Morbi malesuada pulvinar ultrices. Etiam bibendum efficitur risus, sit amet venenatis urna ullamcorper non. Proin fermentum malesuada tortor, vitae mattis sem scelerisque in. Curabitur rutrum leo at mi efficitur suscipit. Vivamus tristique lorem eros, sit amet malesuada augue sodales sed. <span data-cke-marker=\"1\"> </p>\r\n', NULL, 'page', '', 0, 1, 13, 1557396800, 1559567885, 0, 0, 0),
(3, 3, 'يذكر القانونية', 'يذكر القانونية', '', 'يذكر القانونية', 'legal-notices', '', 'index,follow', '', '', '', NULL, 'page', '', 0, 1, 13, 1557396800, 1557396800, 0, 0, 0),
(3, 4, 'Legal notices', 'Legal notices', '', 'Legal notices', 'legal-notices', '', 'index,follow', '', '', '<p> </span> Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque fringilla vel est at rhoncus. Cras porttitor ligula vel magna vehicula accumsan. Mauris eget elit et sem commodo interdum. Aenean dolor sem, tincidunt ac neque tempus, hendrerit blandit lacus. Vivamus placerat nulla in mi tristique, fringilla fermentum nisl vehicula. Nullam quis eros non magna tincidunt interdum ac eu eros. Morbi malesuada pulvinar ultrices. Etiam bibendum efficitur risus, sit amet venenatis urna ullamcorper non. Proin fermentum malesuada tortor, vitae mattis sem scelerisque in. Curabitur rutrum leo at mi efficitur suscipit. Vivamus tristique lorem eros, sit amet malesuada augue sodales sed. <span data-cke-marker=\"1\"> </p>\r\n', NULL, 'page', '', 0, 1, 13, 1557396800, 1559567885, 0, 0, 0),
(4, 1, 'Plan du site', 'Plan du site', '', 'Plan du site', 'plan-site', '', 'index,follow', '', '', '', NULL, 'sitemap', '', 0, 1, 15, 1557396800, 1557396800, 0, 0, 0),
(4, 2, 'Sitemap', 'Sitemap', '', 'Sitemap', 'sitemap', '', 'index,follow', '', '', '', NULL, 'sitemap', '', 0, 1, 15, 1557396800, 1557396800, 0, 0, 0),
(4, 3, 'خريطة الموقع', 'خريطة الموقع', '', 'خريطة الموقع', 'sitemap', '', 'index,follow', '', '', '', NULL, 'sitemap', '', 0, 1, 15, 1557396800, 1557396800, 0, 0, 0),
(4, 4, 'Sitemap', 'Sitemap', '', 'Sitemap', 'sitemap', '', 'index,follow', '', '', '', NULL, 'sitemap', '', 0, 1, 15, 1557396800, 1557396800, 0, 0, 0),
(5, 1, 'Qui sommes-nous ?', 'Qui sommes-nous ?', '', 'Qui sommes-nous ?', 'qui-sommes-nous', '', 'index,follow', '', '', '', NULL, 'page', 'article', 0, 1, 4, 1557396800, 1557396800, 0, 0, 0),
(5, 2, 'About us', 'About us', '', 'About us', 'about-us', '', 'index,follow', '', '', '<p><b style=\"mso-bidi-font-weight:normal\">MET-HMS – YOUR BEST PLATFORM TO BOOK HOTELS ANYWHERE IN INDIA</span></b></p>\r\n\r\n<p>Welcome to HMS (Hotel Management System), your one-stop hotel booking and reservation software. Our main objective is to simplify your booking procedure of hotels, resorts and other accommodations throughout India. Our software is 100% designed and supported in Kolkata. HMS, powered by MET is one-in-all hotel management software that will help to connect you with numerous small and medium-priced hotels in India with real-time and 2-way easy integration. Through our online portal, you can make direct bookings from your desktop or smartphone or even via other social apps. Our software helps the hoteliers to get separate platform individually through which they can directly deal with the tourists.</span> <span data-cke-marker=\"1\"> </p>\r\n\r\n<p><b>The Hotel Management Software You Can Trust</span></b> <span data-cke-marker=\"1\"> </p>\r\n\r\n<p>HMS-powered by Met Technologies is the hotel booking and reservation software that you can trust. We provide complete full-stack, highly efficient, end-to-end software for the hotels and accommodation managers. HMS-MET help both the small and medium hospitality businesses to streamline their operational processes along with maximizing the revenues. We are based in Kolkata and currently serving over 1000+ hotels and properties around India. Our software is developed keeping in mind the accommodation types, rentals, requirements and budget. No matter what is the size of your property, HMS has the best solution for you in all budgets.</p>\r\n\r\n<p><strong>How HMS-MET Help To Streamline Your Hotel Management Business?</strong></p>\r\n\r\n<p><strong><em>1. Streamline Your Business</em></strong></p>\r\n\r\n<p>Our software is easy to manage and has a streamlined database that helps the hotelier business to operate easily and smoothly. This software offers full-guest accounting, reporting for guests and management, front and back-office tools and several other marketing tools. Our hotel booking software provides everything you need to manage your hotel business.</p>\r\n\r\n<p><strong><em>2. Connect You To The World Of Online Reservation</em></strong></p>\r\n\r\n<p>With HMS-MET channel manager, you can automate your inventory and can able to connect your property with the major booking channels from one central website. Our easy to use, automatic channel management tool will not only maximize your online exposure but also improve your revenues.</span></p>\r\n\r\n<p><strong><em>3. Increase Your Marketing Reach</em></strong></p>\r\n\r\n<p>With our software, we help you to increase the visibility of the property on search engines, attracting more people to the website for hotel booking. Additionally, our marketing program helps properties to boost up engagement through Google+ and other social media posts. This software allows the hotel businesses to capitalise on direct online booking.</span></p>\r\n\r\n<p><b style=\"mso-bidi-font-weight:normal\">Exceptional features of HMS-MET</span></b> <span data-cke-marker=\"1\"> </p>\r\n\r\n<ol>\r\n	<li>Easy to use</span></li>\r\n	<li>Integrated easily and have smooth payment processing</span></li>\r\n	<li>24*7 customer support</span></li>\r\n	<li>Lots of features to make online booking easy</span></li>\r\n	<li>We are for small and medium-priced hotels and resorts</span></li>\r\n	<li>100% customer satisfaction</span></li>\r\n	<li>Easily manageable online platform to book hotels anywhere in India</span></li>\r\n</ol>\r\n\r\n<p>Our HMS, powered by MET provides the best value for money on online booking!</p>\r\n', NULL, 'page', '', 0, 1, 4, 1557396800, 1577785890, 0, 0, 0),
(5, 3, 'معلومات عنا', 'معلومات عنا', '', 'معلومات عنا', 'about us', '', 'index,follow', '', '', '', NULL, 'page', 'article', 0, 1, 4, 1557396800, 1557396800, 0, 0, 0),
(5, 4, 'About us', 'About us', '', 'About us', 'about-us', '', 'index,follow', '', '', '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque fringilla vel est at rhoncus. Cras porttitor ligula vel magna vehicula accumsan. Mauris eget elit et sem commodo interdum. Aenean dolor sem, tincidunt ac neque tempus, hendrerit blandit lacus. Vivamus placerat nulla in mi tristique, fringilla fermentum nisl vehicula. Nullam quis eros non magna tincidunt interdum ac eu eros. Morbi malesuada pulvinar ultrices. Etiam bibendum efficitur risus, sit amet venenatis urna ullamcorper non. Proin fermentum malesuada tortor, vitae mattis sem scelerisque in. Curabitur rutrum leo at mi efficitur suscipit. Vivamus tristique lorem eros, sit amet malesuada augue sodales sed.</p>\r\n', NULL, 'page', 'article', 0, 1, 4, 1557396800, 1557396800, 0, 0, 0),
(6, 1, 'Recherche', 'Recherche', '', 'Recherche', 'search', '', 'noindex,nofollow', '', '', '', NULL, 'search', '', 0, 1, 16, 1557396800, 1557396800, 0, 0, 1),
(6, 2, 'Search', 'Search', '', 'Search', 'search', '', 'noindex,nofollow', '', '', '', NULL, 'search', '', 0, 1, 16, 1557396800, 1557396800, 0, 0, 1),
(6, 3, 'بحث', 'بحث', '', 'بحث', 'search', '', 'noindex,nofollow', '', '', '', NULL, 'search', '', 0, 1, 16, 1557396800, 1557396800, 0, 0, 1),
(6, 4, 'Search', 'Search', '', 'Search', 'search', '', 'noindex,nofollow', '', '', '', NULL, 'search', '', 0, 1, 16, 1557396800, 1557396800, 0, 0, 1),
(7, 1, 'Galerie', 'Galerie', '', 'Galerie', 'galerie', '', 'index,follow', '', '', '', NULL, 'page', 'gallery', 0, 1, 2, 1557396800, 1557396800, 0, 0, 0),
(7, 2, 'Gallery', 'Gallery', '', 'Gallery', 'gallery', '', 'noindex,nofollow', '', '', '', NULL, 'gallery-hotels', 'hotel-2', 0, 1, 2, 1557396800, 1564064688, 0, 0, 0),
(7, 3, 'صور معرض', 'صور معرض', '', 'صور معرض', 'gallery', '', 'index,follow', '', '', '', NULL, 'page', 'gallery', 0, 1, 2, 1557396800, 1557396800, 0, 0, 0),
(8, 1, '404', 'Erreur 404 : Page introuvable !', '', '404 Page introuvable', '404', '', 'noindex,nofollow', '', '', '<p>L\'URL demandée n\'a pas été trouvée sur ce serveur.<br />\r\nLa page que vous voulez afficher n\'existe pas, ou est temporairement indisponible.</p>\r\n\r\n<p>Merci d\'essayer les actions suivantes :</p>\r\n\r\n<ul>\r\n    <li>Assurez-vous que l\'URL dans la barre d\'adresse de votre navigateur est correctement orthographiée et formatée.</li>\r\n    <li>Si vous avez atteint cette page en cliquant sur un lien ou si vous pensez que cela concerne une erreur du serveur, contactez l\'administrateur pour l\'alerter.</li>\r\n</ul>\r\n', NULL, '404', '', 0, 1, 17, 1557396800, 1557396800, 0, 0, 1),
(8, 2, '404', '404 Error: Page not found!', '', '404 Not Found', '404', '', 'noindex,nofollow', '', '', '<p>The wanted URL was not found on this server.<br />\r\nThe page you wish to display does not exist, or is temporarily unavailable.</p>\r\n\r\n<p>Thank you for trying the following actions :</p>\r\n\r\n<ul>\r\n    <li>Be sure the URL in the address bar of your browser is correctly spelt and formated.</li>\r\n    <li>If you reached this page by clicking a link or if you think that it is about an error of the server, contact the administrator to alert him.</li>\r\n</ul>\r\n', NULL, '404', '', 0, 1, 17, 1557396800, 1557396800, 0, 0, 1),
(8, 3, '404', '404 Error: Page not found!', '', '404 Not Found', '404', '', 'noindex,nofollow', '', '', '', NULL, '404', '', 0, 1, 17, 1557396800, 1557396800, 0, 0, 1),
(8, 4, '404', '404 Error: Page not found!', '', '404 Not Found', '404', '', 'noindex,nofollow', '', '', '<p>The wanted URL was not found on this server.<br />\r\nThe page you wish to display does not exist, or is temporarily unavailable.</p>\r\n\r\n<p>Thank you for trying the following actions :</p>\r\n\r\n<ul>\r\n    <li>Be sure the URL in the address bar of your browser is correctly spelt and formated.</li>\r\n    <li>If you reached this page by clicking a link or if you think that it is about an error of the server, contact the administrator to alert him.</li>\r\n</ul>\r\n', NULL, '404', '', 0, 1, 17, 1557396800, 1557396800, 0, 0, 1),
(9, 1, 'Hôtels', 'Hôtels', '', 'Hôtels', 'hotels', '', 'index,follow', '', '', '', NULL, 'hotels', 'hotel', 0, 1, 3, 1557396800, 1557396800, 0, 0, 1),
(9, 2, 'Hotels', 'Hotels', '', 'Hotels', 'hotels', '', 'index,follow', '', '', '', NULL, 'hotels', 'hotel', 0, 1, 3, 1557396800, 1560552914, 0, 0, 1),
(9, 3, 'الفنادق', 'الفنادق', '', 'الفنادق', 'hotels', '', 'index,follow', '', '', '', NULL, 'hotels', 'hotel', 0, 1, 3, 1557396800, 1557396800, 0, 0, 1),
(9, 4, 'Hotels', 'Hotels', '', 'Hotels', 'hotels', '', 'index,follow', '', '', '', NULL, 'hotels', 'hotel', 0, 1, 3, 1557396800, 1557396800, 0, 0, 1),
(10, 1, 'Réserver', 'Réserver', '', 'Réserver', 'reserver', '', 'index,nofollow', '', '', '', NULL, 'booking', 'booking', 0, 1, 6, 1557396800, 1557396800, 0, 0, 1),
(10, 2, 'Booking', 'Booking', '', 'Booking', 'booking', '', 'index,nofollow', '', '', '', NULL, 'booking', 'booking', 0, 1, 6, 1557396800, 1557396800, 0, 0, 1),
(10, 3, 'الحجز', 'الحجز', '', 'الحجز', 'booking', '', 'index,nofollow', '', '', '', NULL, 'booking', 'booking', 0, 1, 6, 1557396800, 1557396800, 0, 0, 1),
(10, 4, 'Booking', 'Booking', '', 'Booking', 'booking', '', 'index,nofollow', '', '', '', NULL, 'booking', 'booking', 0, 1, 6, 1557396800, 1557396800, 0, 0, 1),
(11, 1, 'Coordonnées', 'Coordonnées', '', 'Coordonnées', 'coordonnees', '', 'noindex,nofollow', '', '', '', 10, 'details', '', 0, 1, 10, 1557396800, 1557396800, 0, 0, 1),
(11, 2, 'Details', 'Booking details', '', 'Booking details', 'booking-details', '', 'noindex,nofollow', '', '', '', 10, 'details', '', 0, 1, 10, 1557396800, 1557396800, 0, 0, 1),
(11, 3, 'تفاصيل الحجز', 'تفاصيل الحجز', '', 'تفاصيل الحجز', 'booking-details', '', 'noindex,nofollow', '', '', '', 10, 'details', '', 0, 1, 10, 1557396800, 1557396800, 0, 0, 1),
(11, 4, 'Details', 'Booking details', '', 'Booking details', 'booking-details', '', 'noindex,nofollow', '', '', '', 10, 'details', '', 0, 1, 10, 1557396800, 1557396800, 0, 0, 1),
(12, 1, 'Paiement', 'Paiement', '', 'Paiement', 'paiement', '', 'noindex,nofollow', '', '', '', 13, 'payment', '', 0, 1, 12, 1557396800, 1557396800, 0, 0, 1),
(12, 2, 'Payment', 'Payment', '', 'Payment', 'payment', '', 'noindex,nofollow', '', '', '', 13, 'payment', '', 0, 1, 12, 1557396800, 1557396800, 0, 0, 1),
(12, 3, 'دفع', 'دفع', '', 'دفع', 'payment', '', 'noindex,nofollow', '', '', '', 13, 'payment', '', 0, 1, 12, 1557396800, 1557396800, 0, 0, 1),
(12, 4, 'Payment', 'Payment', '', 'Payment', 'payment', '', 'noindex,nofollow', '', '', '', 13, 'payment', '', 0, 1, 12, 1557396800, 1557396800, 0, 0, 1),
(13, 1, 'Résumé de la réservation', 'Résumé de la réservation', '', 'Résumé de la réservation', 'resume-reservation', '', 'noindex,nofollow', '', '', '', 11, 'summary', '', 0, 1, 11, 1557396800, 1557396800, 0, 0, 1),
(13, 2, 'Summary', 'Booking summary', '', 'Booking summary', 'booking-summary', '', 'noindex,nofollow', '', '', '', 11, 'summary', '', 0, 1, 11, 1557396800, 1557396800, 0, 0, 1),
(13, 3, 'ملخص الحجز', 'ملخص الحجز', '', 'ملخص الحجز', 'booking-summary', '', 'noindex,nofollow', '', '', '', 11, 'summary', '', 0, 1, 11, 1557396800, 1557396800, 0, 0, 1),
(13, 4, 'Summary', 'Booking summary', '', 'Booking summary', 'booking-summary', '', 'noindex,nofollow', '', '', '', 11, 'summary', '', 0, 1, 11, 1557396800, 1557396800, 0, 0, 1),
(14, 1, 'Espace client', 'Espace client', '', 'Espace client', 'espace-client', '', 'noindex,nofollow', '', '', '', NULL, 'account', '', 0, 1, 18, 1557396800, 1557396800, 0, 0, 1),
(14, 2, 'Account', 'Account', '', 'Account', 'account', '', 'noindex,nofollow', '', '', '', NULL, 'account', '', 0, 1, 18, 1557396800, 1557396800, 0, 0, 1),
(14, 3, 'Account', 'Account', '', 'Account', 'account', '', 'noindex,nofollow', '', '', '', NULL, 'account', '', 0, 1, 18, 1557396800, 1557396800, 0, 0, 1),
(14, 4, 'Account', 'Account', '', 'Account', 'account', '', 'noindex,nofollow', '', '', '', NULL, 'account', '', 0, 1, 18, 1557396800, 1557396800, 0, 0, 1),
(15, 1, 'Activités', 'Activités', '', 'Activités', 'reservation-activitees', '', 'noindex,nofollow', '', '', '', 10, 'booking-activities', '', 0, 1, 7, 1557396800, 1557396800, 0, 0, 1),
(15, 2, 'Activities', 'Activities', '', 'Activities', 'booking-activities', '', 'noindex,nofollow', '', '', '', 10, 'booking-activities', '', 0, 1, 7, 1557396800, 1557396800, 0, 0, 1),
(15, 3, 'Activities', 'Activities', '', 'Activities', 'booking-activities', '', 'noindex,nofollow', '', '', '', 10, 'booking-activities', '', 0, 1, 7, 1557396800, 1557396800, 0, 0, 1),
(15, 4, 'Activities', 'Activities', '', 'Activities', 'booking-activities', '', 'noindex,nofollow', '', '', '', 10, 'booking-activities', '', 0, 1, 7, 1557396800, 1557396800, 0, 0, 1),
(16, 1, 'Activités', 'Activités', '', 'Activités', 'activitees', '', 'index,follow', '', '', '', NULL, 'activities', 'activity', 0, 1, 5, 1557396800, 1557396800, 0, 0, 1),
(16, 2, 'Activities', 'Activities', '', 'Activities', 'activities', '', 'index,follow', '', '', '', NULL, 'activities', 'activity', 0, 1, 5, 1557396800, 1557396800, 0, 0, 1),
(16, 3, 'Activities', 'Activities', '', 'Activities', 'activities', '', 'index,follow', '', '', '', NULL, 'activities', 'activity', 0, 1, 5, 1557396800, 1557396800, 0, 0, 1),
(16, 4, 'Activities', 'Activities', '', 'Activities', 'activities', '', 'index,follow', '', '', '', NULL, 'activities', 'activity', 0, 1, 5, 1557396800, 1557396800, 0, 0, 1),
(17, 1, 'Blog', 'Blog', '', 'Blog', 'blog', '', 'index,follow', '', '', '', NULL, 'blog', 'article-blog', 0, 1, 14, 1557396800, 1557396800, 0, 0, 0),
(17, 2, 'Blog', 'Blog', '', 'Blog', 'blog', '', 'index,follow', '', '', '', NULL, 'blog', 'article-blog', 0, 1, 14, 1557396800, 1557396800, 0, 0, 0),
(17, 3, 'مدونة', 'مدونة', '', 'مدونة', 'blog', '', 'index,follow', '', '', '', NULL, 'blog', 'article-blog', 0, 1, 14, 1557396800, 1557396800, 0, 0, 0),
(17, 4, 'Blog', 'Blog', '', 'Blog', 'blog', '', 'index,follow', '', '', '', NULL, 'blog', 'article-blog', 0, 1, 14, 1557396800, 1557396800, 0, 0, 0),
(18, 1, 'Destinations', 'Destinations', '', 'Destinations', 'destinations', '', 'index,follow', '', '', '', NULL, 'destinations', '', 0, 1, 8, 1557396800, 1557396800, 0, 0, 1),
(18, 2, 'Destinations', 'Destinations', '', 'Destinations', 'destinations', '', 'index,follow', '', '', '', NULL, 'destinations', '', 0, 1, 8, 1557396800, 1557396800, 0, 0, 1),
(18, 3, 'وجهات', 'وجهات', '', 'وجهات', 'destinations', '', 'index,follow', '', '', '', NULL, 'destinations', '', 0, 1, 8, 1557396800, 1557396800, 0, 0, 1),
(18, 4, 'Destinations', 'Destinations', '', 'Destinations', 'destinations', '', 'index,follow', '', '', '', NULL, 'destinations', '', 0, 1, 8, 1557396800, 1557396800, 0, 0, 1),
(19, 2, 'Accommodations', 'Accommodations', '', 'Accommodations', 'accommodations', '', '', NULL, '', '', NULL, 'accommodations', '', 0, 1, 19, 1559028032, 1559028032, 0, NULL, NULL),
(19, 4, 'Accommodations', 'Accommodations', '', 'Accommodations', 'accommodations', '', '', NULL, '', '', NULL, 'accommodations', '', 0, 1, 19, 1559028032, 1559028032, 0, NULL, NULL),
(20, 2, 'Accommodation', 'Accommodation', '', 'Accommodation', 'accommodation', '', '', NULL, '', '', NULL, 'accommodation', '', 0, 1, 21, 1559032506, 1560931545, 0, NULL, NULL),
(20, 4, 'Accommodation', 'Accommodation', '', 'Accommodation', 'accommodation', '', '', NULL, '', '', NULL, 'accommodation', '', 0, 1, 21, 1559032506, 1559032704, 0, NULL, NULL),
(21, 2, 'Active account', 'Active account', '', 'Active account', 'active-account', '', '', NULL, '', '<p style=\"text-align: center;\"><span style=\"font-size:14px;\"><span style=\"color:#008000;\">Hi Your account successfully activated </span></span></p>\r\n\r\n<p style=\"text-align: center;\"><span style=\"font-size:14px;\"><span style=\"color:#008000;\"> Please login</span></span></p>\r\n', NULL, 'active-account', '', 0, 1, 20, 1560348243, 1561728161, 0, NULL, NULL),
(21, 4, 'Active account', 'Active account', '', 'Active account', 'active-account', '', '', NULL, '', '<p style=\"text-align: center;\"><span style=\"font-size:14px;\"><span style=\"color:#008000;\">Hi Your account successfully activeted </span></span></p>\r\n\r\n<p style=\"text-align: center;\"><span style=\"font-size:14px;\"><span style=\"color:#008000;\"> Please login</span></span></p>\r\n', NULL, 'active-account', '', 0, 1, 20, 1560348243, 1560348898, 0, NULL, NULL),
(26, 2, 'Expired', 'Expired', '', 'expired', 'expired', '', '', NULL, '', '<p>The link has been expired.</p>\r\n', NULL, 'page', '', 0, 1, 25, 1562235563, 1562235563, NULL, NULL, NULL),
(27, 2, 'Login', 'Login', '', 'Login', 'login', 'Login', '', NULL, '', '', NULL, 'login', '', 0, 1, 26, 1562939842, 1562939842, 0, NULL, NULL),
(28, 2, 'Sign up', 'Sign up', '', 'Sign up', 'sign-up', 'Sign up', '', NULL, '', '', NULL, 'register', '', 0, 1, 27, 1562939895, 1563378539, 0, NULL, NULL),
(29, 2, 'Forgot password', 'Forgot password', 'Forgot password', 'Forgot password', 'forgot-password', 'Forgot password', '', NULL, '', '', NULL, 'forgot-password', '', 0, 1, 28, 1562939938, 1563435968, 0, NULL, NULL),
(31, 2, 'Confirm', 'Confirm', '', 'Confirm', 'confirm', 'Confirm', '', NULL, '', '<p>Confirm</p>\r\n', NULL, 'confirm', '', 0, 1, 29, 1563275622, 1563275622, 0, NULL, NULL),
(32, 2, 'offers', 'offers', '', 'Special offers', 'offers', '', '', NULL, '', '', NULL, 'offers', 'offer', 0, 1, 30, 1565088520, 1565180167, 0, NULL, NULL),
(34, 2, 'Package', 'Package', '', 'Package', 'package', 'Package', 'noindex,nofollow', NULL, '', '', NULL, 'page', 'offer-book', 0, 1, 31, 1565172293, 1565172293, 0, NULL, NULL),
(35, 2, 'Term and Condition', 'Term and Condition', 'Term and Condition', 'Term and Condition', 'term-and-condition', 'Term and Condition', 'index,follow', NULL, 'Term and Condition', '<p>Welcome to HMS!</p>\r\n\r\n<p>These terms and conditions outline the rules and regulations for the use of Met Technologies Pvt. Ltd.\'s Website, located at demohotel.fitser.com.</p>\r\n\r\n<p>By accessing this website we assume you accept these terms and conditions. Do not continue to use HMS if you do not agree to take all of the terms and conditions stated on this page.</p>\r\n\r\n<p>The following terminology applies to these Terms and Conditions, Privacy Statement and Disclaimer Notice and all Agreements: \"Client\", \"You\" and \"Your\" refers to you, the person log on this website and compliant to the Company’s terms and conditions. \"The Company\", \"Ourselves\", \"We\", \"Our\" and \"Us\", refers to our Company. \"Party\", \"Parties\", or \"Us\", refers to both the Client and ourselves. All terms refer to the offer, acceptance and consideration of payment necessary to undertake the process of our assistance to the Client in the most appropriate manner for the express purpose of meeting the Client’s needs in respect of provision of the Company’s stated services, in accordance with and subject to, prevailing law of Netherlands. Any use of the above terminology or other words in the singular, plural, capitalization and/or he/she or they, are taken as interchangeable and therefore as referring to same. <span data-cke-marker=\"1\">  </span></p>\r\n\r\n<h3><strong>Cookies</strong></h3>\r\n\r\n<p>We employ the use of cookies. By accessing HMS, you agreed to use cookies in agreement with the Met Technologies Pvt. Ltd.\'s Privacy Policy.</p>\r\n\r\n<p>Most interactive websites use cookies to let us retrieve the user’s details for each visit. Cookies are used by our website to enable the functionality of certain areas to make it easier for people visiting our website. Some of our affiliate/advertising partners may also use cookies. </span></p>\r\n\r\n<h3><strong>License</strong></h3>\r\n\r\n<p>Unless otherwise stated, Met Technologies Pvt. Ltd. and/or its licensors own the intellectual property rights for all material on HMS. All intellectual property rights are reserved. You may access this from HMS for your own personal use subjected to restrictions set in these terms and conditions.</p>\r\n\r\n<p>You must not: </span></p>\r\n\r\n<ul>\r\n	<li>Republish material from HMS</li>\r\n	<li>Sell, rent or sub-license material from HMS</li>\r\n	<li>Reproduce, duplicate or copy material from HMS</li>\r\n	<li>Redistribute content from HMS  </span></li>\r\n</ul>\r\n\r\n<p>This Agreement shall begin on the date hereof.</p>\r\n\r\n<p>Parts of this website offer an opportunity for users to post and exchange opinions and information in certain areas of the website. Met Technologies Pvt. Ltd. does not filter, edit, publish or review Comments prior to their presence on the website. Comments do not reflect the views and opinions of Met Technologies Pvt. Ltd.,its agents and/or affiliates. Comments reflect the views and opinions of the person who post their views and opinions. To the extent permitted by applicable laws, Met Technologies Pvt. Ltd. shall not be liable for the Comments or for any liability, damages or expenses caused and/or suffered as a result of any use of and/or posting of and/or appearance of the Comments on this website.</p>\r\n\r\n<p>Met Technologies Pvt. Ltd. reserves the right to monitor all Comments and to remove any Comments which can be considered inappropriate, offensive or causes breach of these Terms and Conditions.</span></p>\r\n\r\n<p>You warrant and represent that: <span data-cke-marker=\"1\">  </span></p>\r\n\r\n<ul>\r\n	<li>You are entitled to post the Comments on our website and have all necessary licenses and consents to do so;</li>\r\n	<li>The Comments do not invade any intellectual property right, including without limitation copyright, patent or trademark of any third party;</li>\r\n	<li>The Comments do not contain any defamatory, libelous, offensive, indecent or otherwise unlawful material which is an invasion of privacy</li>\r\n	<li>The Comments will not be used to solicit or promote business or custom or present commercial activities or unlawful activity.</li>\r\n</ul>\r\n\r\n<p>You hereby grant Met Technologies Pvt. Ltd. a non-exclusive license to use, reproduce, edit and authorize others to use, reproduce and edit any of your Comments in any and all forms, formats or media. <span data-cke-marker=\"1\">  </span></p>\r\n\r\n<h3><strong>Hyperlinking to our Content</strong></h3>\r\n\r\n<p>The following organizations may link to our Website without prior written approval: </span></p>\r\n\r\n<ul>\r\n	<li>Government agencies;</li>\r\n	<li>Search engines;</li>\r\n	<li>News organizations;</li>\r\n	<li>Online directory distributors may link to our Website in the same manner as they hyperlink to the Websites of other listed businesses; and</li>\r\n	<li>System wide Accredited Businesses except soliciting non-profit organizations, charity shopping malls, and charity fundraising groups which may not hyperlink to our Web site. <span data-cke-marker=\"1\"> </span></li>\r\n</ul>\r\n\r\n<p>These organizations may link to our home page, to publications or to other Website information so long as the link: (a) is not in any way deceptive; (b) does not falsely imply sponsorship, endorsement or approval of the linking party and its products and/or services; and (c) fits within the context of the linking party’s site.</p>\r\n\r\n<p>We may consider and approve other link requests from the following types of organizations: </span></p>\r\n\r\n<ul>\r\n	<li>commonly-known consumer and/or business information sources;</li>\r\n	<li>dot.com community sites;</li>\r\n	<li>associations or other groups representing charities;</li>\r\n	<li>online directory distributors;</li>\r\n	<li>internet portals;</li>\r\n	<li>accounting, law and consulting firms; and</li>\r\n	<li>educational institutions and trade associations. <span data-cke-marker=\"1\"> </span></li>\r\n</ul>\r\n\r\n<p>We will approve link requests from these organizations if we decide that: (a) the link would not make us look unfavorably to ourselves or to our accredited businesses; (b) the organization does not have any negative records with us; (c) the benefit to us from the visibility of the hyperlink compensates the absence of Met Technologies Pvt. Ltd.; and (d) the link is in the context of general resource information.</p>\r\n\r\n<p>These organizations may link to our home page so long as the link: (a) is not in any way deceptive; (b) does not falsely imply sponsorship, endorsement or approval of the linking party and its products or services; and (c) fits within the context of the linking party’s site.</p>\r\n\r\n<p>If you are one of the organizations listed in paragraph 2 above and are interested in linking to our website, you must inform us by sending an e-mail to Met Technologies Pvt. Ltd.. Please include your name, your organization name, contact information as well as the URL of your site, a list of any URLs from which you intend to link to our Website, and a list of the URLs on our site to which you would like to link. Wait 2-3 weeks for a response.</p>\r\n\r\n<p>Approved organizations may hyperlink to our Website as follows: </span></p>\r\n\r\n<ul>\r\n	<li>By use of our corporate name; or</li>\r\n	<li>By use of the uniform resource locator being linked to; or</li>\r\n	<li>By use of any other description of our Website being linked to that makes sense within the context and format of content on the linking party’s site. <span data-cke-marker=\"1\"> </span></li>\r\n</ul>\r\n\r\n<p>No use of Met Technologies Pvt. Ltd.\'s logo or other artwork will be allowed for linking absent a trademark license agreement.</p>\r\n\r\n<h3><strong>iFrames</strong></h3>\r\n\r\n<p>Without prior approval and written permission, you may not create frames around our Webpages that alter in any way the visual presentation or appearance of our Website.</p>\r\n\r\n<h3><strong>Content Liability</strong></h3>\r\n\r\n<p>We shall not be hold responsible for any content that appears on your Website. You agree to protect and defend us against all claims that is rising on your Website. No link(s) should appear on any Website that may be interpreted as libelous, obscene or criminal, or which infringes, otherwise violates, or advocates the infringement or other violation of, any third party rights.</p>\r\n\r\n<h3><strong>Your Privacy</strong></h3>\r\n\r\n<p>Please read Privacy Policy</p>\r\n\r\n<h3><strong>Reservation of Rights</strong></h3>\r\n\r\n<p>We reserve the right to request that you remove all links or any particular link to our Website. You approve to immediately remove all links to our Website upon request. We also reserve the right to amen these terms and conditions and it’s linking policy at any time. By continuously linking to our Website, you agree to be bound to and follow these linking terms and conditions.</p>\r\n\r\n<h3><strong>Removal of links from our website</strong></h3>\r\n\r\n<p>If you find any link on our Website that is offensive for any reason, you are free to contact and inform us any moment. We will consider requests to remove links but we are not obligated to or so or to respond to you directly.</p>\r\n\r\n<p>We do not ensure that the information on this website is correct, we do not warrant its completeness or accuracy; nor do we promise to ensure that the website remains available or that the material on the website is kept up to date.</p>\r\n\r\n<h3><strong>Disclaimer</strong></h3>\r\n\r\n<p>To the maximum extent permitted by applicable law, we exclude all representations, warranties and conditions relating to our website and the use of this website. Nothing in this disclaimer will: </span></p>\r\n\r\n<ul>\r\n	<li>limit or exclude our or your liability for death or personal injury;</li>\r\n	<li>limit or exclude our or your liability for fraud or fraudulent misrepresentation;</li>\r\n	<li>limit any of our or your liabilities in any way that is not permitted under applicable law; or</li>\r\n	<li>exclude any of our or your liabilities that may not be excluded under applicable law. <span data-cke-marker=\"1\"> </span></li>\r\n</ul>\r\n\r\n<p>The limitations and prohibitions of liability set in this Section and elsewhere in this disclaimer: (a) are subject to the preceding paragraph; and (b) govern all liabilities arising under the disclaimer, including liabilities arising in contract, in tort and for breach of statutory duty.</p>\r\n\r\n<p>As long as the website and the information and services on the website are provided free of charge, we will not be liable for any loss or damage of any nature.        </p>\r\n', NULL, 'page', '', 0, 1, 32, 1576232928, 1597235586, 0, NULL, NULL),
(36, 2, 'Privacy Policy', 'Privacy Policy', '', 'Privacy Policy', 'privacy-policy', 'Privacy Policy', 'index,follow', NULL, 'Privacy Policy', '<h2>Privacy Policy for Met Technologies Pvt. Ltd.</h2>\r\n\r\n<p>At HMS, accessible from demohotel.fitser.com, one of our main priorities is the privacy of our visitors. This Privacy Policy document contains types of information that is collected and recorded by HMS and how we use it.</p>\r\n\r\n<p>If you have additional questions or require more information about our Privacy Policy, do not hesitate to contact us.</p>\r\n\r\n<h2>Log Files</h2>\r\n\r\n<p>HMS follows a standard procedure of using log files. These files log visitors when they visit websites. All hosting companies do this and a part of hosting services\' analytics. The information collected by log files include internet protocol (IP) addresses, browser type, Internet Service Provider (ISP), date and time stamp, referring/exit pages, and possibly the number of clicks. These are not linked to any information that is personally identifiable. The purpose of the information is for analyzing trends, administering the site, tracking users\' movement on the website, and gathering demographic information.</p>\r\n\r\n<h2>Cookies and Web Beacons</h2>\r\n\r\n<p>Like any other website, HMS uses \'cookies\'. These cookies are used to store information including visitors\' preferences, and the pages on the website that the visitor accessed or visited. The information is used to optimize the users\' experience by customizing our web page content based on visitors\' browser type and/or other information. </span></p>\r\n\r\n<h2>Google DoubleClick DART Cookie</h2>\r\n\r\n<p>Google is one of a third-party vendor on our site. It also uses cookies, known as DART cookies, to serve ads to our site visitors based upon their visit to www.website.com and other sites on the internet. However, visitors may choose to decline the use of DART cookies by visiting the Google ad and content network Privacy Policy at the following URL – <a href=\"https://policies.google.com/technologies/ads\">https://policies.google.com/technologies/ads</a></p>\r\n\r\n<h2>Our Advertising Partners</h2>\r\n\r\n<p>Some of advertisers on our site may use cookies and web beacons. Our advertising partners are listed below. Each of our advertising partners has their own Privacy Policy for their policies on user data. For easier access, we hyperlinked to their Privacy Policies below. </span></p>\r\n\r\n<ul>\r\n	<li>\r\n	<p>Google</p>\r\n\r\n	<p><a href=\"https://policies.google.com/technologies/ads\">https://policies.google.com/technologies/ads</a> <span data-cke-marker=\"1\"> </span></p>\r\n	</li>\r\n</ul>\r\n\r\n<h2>Privacy Policies</h2>\r\n\r\n<p>You may consult this list to find the Privacy Policy for each of the advertising partners of HMS. Our Privacy Policy was created with the help of the <a href=\"https://www.privacypolicygenerator.info\">Privacy Policy Generator</a> and the <a href=\"https://www.privacypolicyonline.com\">Privacy Policy Generator Online</a>.</p>\r\n\r\n<p>Third-party ad servers or ad networks uses technologies like cookies, JavaScript, or Web Beacons that are used in their respective advertisements and links that appear on HMS, which are sent directly to users\' browser. They automatically receive your IP address when this occurs. These technologies are used to measure the effectiveness of their advertising campaigns and/or to personalize the advertising content that you see on websites that you visit.</p>\r\n\r\n<p>Note that HMS has no access to or control over these cookies that are used by third-party advertisers.</p>\r\n\r\n<h2>Third Party Privacy Policies</h2>\r\n\r\n<p>HMS\'s Privacy Policy does not apply to other advertisers or websites. Thus, we are advising you to consult the respective Privacy Policies of these third-party ad servers for more detailed information. It may include their practices and instructions about how to opt-out of certain options. You may find a complete list of these Privacy Policies and their links here: Privacy Policy Links.</p>\r\n\r\n<p>You can choose to disable cookies through your individual browser options. To know more detailed information about cookie management with specific web browsers, it can be found at the browsers\' respective websites. What Are Cookies?</p>\r\n\r\n<h2>Children\'s Information</h2>\r\n\r\n<p>Another part of our priority is adding protection for children while using the internet. We encourage parents and guardians to observe, participate in, and/or monitor and guide their online activity.</p>\r\n\r\n<p>HMS does not knowingly collect any Personal Identifiable Information from children under the age of 13. If you think that your child provided this kind of information on our website, we strongly encourage you to contact us immediately and we will do our best efforts to promptly remove such information from our records.</p>\r\n\r\n<h2>Online Privacy Policy Only</h2>\r\n\r\n<p>This Privacy Policy applies only to our online activities and is valid for visitors to our website with regards to the information that they shared and/or collect in HMS. This policy is not applicable to any information collected offline or via channels other than this website.</p>\r\n\r\n<h2>Consent</h2>\r\n\r\n<p>By using our website, you hereby consent to our Privacy Policy and agree to its Terms and Conditions. </p>\r\n', NULL, 'page', '', 0, 1, 33, 1576233066, 1597235603, 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `pm_page_file`
--

CREATE TABLE `pm_page_file` (
  `id` int(11) NOT NULL,
  `lang` int(11) NOT NULL,
  `id_item` int(11) NOT NULL,
  `home` int(11) DEFAULT '0',
  `checked` int(11) DEFAULT '1',
  `rank` int(11) DEFAULT '0',
  `file` varchar(250) DEFAULT NULL,
  `label` varchar(250) DEFAULT NULL,
  `type` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `pm_rate`
--

CREATE TABLE `pm_rate` (
  `id` int(11) NOT NULL,
  `id_room` int(11) NOT NULL,
  `id_hotel` int(11) DEFAULT NULL,
  `id_package` int(11) DEFAULT '10',
  `users` text,
  `start_date` int(11) DEFAULT NULL,
  `end_date` int(11) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT '0.00',
  `child_price` int(11) DEFAULT '0',
  `discount` double DEFAULT '0',
  `discount_type` varchar(10) DEFAULT 'rate',
  `people` int(11) DEFAULT NULL,
  `price_sup` int(11) DEFAULT '0',
  `fixed_sup` double DEFAULT NULL,
  `id_tax` int(11) DEFAULT NULL,
  `taxes` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pm_rate`
--

INSERT INTO `pm_rate` (`id`, `id_room`, `id_hotel`, `id_package`, `users`, `start_date`, `end_date`, `price`, `child_price`, `discount`, `discount_type`, `people`, `price_sup`, `fixed_sup`, `id_tax`, `taxes`) VALUES
(35, 43, 24, 10, '1', 1566172800, 1609372800, 880.00, 10, NULL, '', 2, 20, NULL, NULL, '1'),
(36, 41, 23, 10, '1', 1566172800, 1609372800, 1045.00, 0, NULL, '', 3, 0, NULL, NULL, '1'),
(37, 42, 23, 10, '1', 1566172800, 1609372800, 825.00, 0, NULL, '', 1, 0, NULL, NULL, '1'),
(38, 40, 23, 10, '1', 1566172800, 1609372800, 990.00, 0, NULL, '', 2, 0, NULL, NULL, '1'),
(39, 35, 21, 10, '1', 1566172800, 1609372800, 990.00, 5, 10, 'rate', 2, 0, NULL, NULL, '1'),
(40, 36, 21, 10, '1', 1566172800, 1609372800, 1045.00, 5, 20, 'rate', 3, 10, NULL, NULL, '1'),
(41, 34, 20, 10, '1', 1566172800, 1609372800, 880.00, 5, 20, 'rate', 2, 10, NULL, NULL, '1'),
(42, 32, 19, 10, '1', 1566172800, 1595980800, 1000.00, 0, 30, 'rate', 2, 10, NULL, NULL, '1'),
(43, 33, 19, 10, '1', 1566172800, 1609372800, 2000.00, 5, 20, 'rate', 2, 10, NULL, NULL, '1'),
(44, 49, 28, 10, '1', 1566172800, 1609372800, 1320.00, 0, NULL, '', 3, 0, NULL, NULL, '1'),
(45, 48, 28, 10, '1', 1566172800, 1609372800, 1100.00, 0, NULL, '', 2, 0, NULL, NULL, '1'),
(46, 51, 28, 10, '1', 1566172800, 1609372800, 2750.00, 0, NULL, '', 2, 0, NULL, NULL, '1'),
(47, 44, 25, 10, '1', 1566172800, 1609372800, 935.00, 0, NULL, '', 2, 0, NULL, NULL, '1'),
(48, 47, 27, 10, '1', 1566172800, 1609372800, 770.00, 0, NULL, '', 2, 0, NULL, NULL, '1'),
(49, 52, 29, 10, '1', 1566172800, 1609372800, 1485.00, 0, NULL, '', 2, 0, NULL, NULL, '1'),
(50, 37, 22, 10, '1', 1566172800, 1609372800, 990.00, 0, NULL, '', 2, 0, NULL, NULL, '1'),
(51, 39, 22, 10, '1', 1566172800, 1609372800, 825.00, 0, NULL, '', 2, 10, NULL, NULL, '1'),
(52, 38, 22, 10, '1', 1566172800, 1609372800, 1045.00, 0, NULL, '', 3, 0, NULL, NULL, '1'),
(53, 53, 30, 10, '1', 1566172800, 1609372800, 880.00, 0, NULL, '', 2, 0, NULL, NULL, '1'),
(54, 46, 26, 10, '1', 1566172800, 1609372800, 825.00, 0, NULL, '', 2, 0, NULL, NULL, '1'),
(55, 45, 26, 10, '1', 1566172800, 1609372800, 880.00, 0, NULL, '', 2, 0, NULL, NULL, '1'),
(63, 62, 26, NULL, '1', 1579910400, 1580428800, 120.00, 100, NULL, '', 38, 500, NULL, NULL, ''),
(64, 63, 24, NULL, '1', 1577836800, 1580428800, 1200.00, NULL, NULL, '', 2, NULL, NULL, NULL, ''),
(65, 67, 33, 10, '1', 1596067200, 1597449600, 900.00, 10, 10, 'rate', 5, 10, NULL, NULL, '1');

-- --------------------------------------------------------

--
-- Table structure for table `pm_rate_child`
--

CREATE TABLE `pm_rate_child` (
  `id` int(11) NOT NULL,
  `id_rate` int(11) NOT NULL,
  `min_age` int(11) DEFAULT NULL,
  `max_age` int(11) DEFAULT NULL,
  `price` double DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `pm_rate_perday`
--

CREATE TABLE `pm_rate_perday` (
  `id` int(11) NOT NULL,
  `id_rate` int(11) NOT NULL,
  `sdate` text,
  `edate` text,
  `price` double DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pm_rate_perday`
--

INSERT INTO `pm_rate_perday` (`id`, `id_rate`, `sdate`, `edate`, `price`) VALUES
(1, 23, '3', '8', 200),
(2, 24, '3', '8', 200),
(3, 10, NULL, NULL, 1059);

-- --------------------------------------------------------

--
-- Table structure for table `pm_report`
--

CREATE TABLE `pm_report` (
  `id` int(11) NOT NULL,
  `hotel_id` int(11) DEFAULT NULL,
  `report_name` varchar(100) NOT NULL,
  `report_content` longtext NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `pm_room`
--

CREATE TABLE `pm_room` (
  `id` int(11) NOT NULL,
  `lang` int(11) NOT NULL,
  `id_hotel` int(11) NOT NULL,
  `id_type` int(11) DEFAULT NULL,
  `users` text,
  `max_children` int(11) DEFAULT '1',
  `max_adults` int(11) DEFAULT '1',
  `max_people` int(11) DEFAULT NULL,
  `min_people` int(11) DEFAULT NULL,
  `title` varchar(250) DEFAULT NULL,
  `subtitle` varchar(250) DEFAULT NULL,
  `alias` varchar(100) DEFAULT NULL,
  `descr` longtext,
  `facilities` text,
  `stock` int(11) DEFAULT '1',
  `price` double DEFAULT '0',
  `bed_type` varchar(255) DEFAULT NULL,
  `additional_bed_type` varchar(255) DEFAULT NULL,
  `number_beds` int(11) DEFAULT NULL,
  `room_dimention` varchar(255) DEFAULT NULL,
  `home` int(11) DEFAULT '0',
  `views` varchar(255) DEFAULT NULL,
  `number_of_days_full` int(5) DEFAULT NULL,
  `number_of_days_cancel` int(5) DEFAULT NULL,
  `cancel_fees` int(11) DEFAULT NULL,
  `fees_type` varchar(50) DEFAULT NULL,
  `checked` int(11) DEFAULT '0',
  `rank` int(11) DEFAULT '0',
  `start_lock` int(11) DEFAULT NULL,
  `end_lock` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pm_room`
--

INSERT INTO `pm_room` (`id`, `lang`, `id_hotel`, `id_type`, `users`, `max_children`, `max_adults`, `max_people`, `min_people`, `title`, `subtitle`, `alias`, `descr`, `facilities`, `stock`, `price`, `bed_type`, `additional_bed_type`, `number_beds`, `room_dimention`, `home`, `views`, `number_of_days_full`, `number_of_days_cancel`, `cancel_fees`, `fees_type`, `checked`, `rank`, `start_lock`, `end_lock`) VALUES
(32, 2, 19, 1, '1', 2, 3, 5, 1, 'Classic', 'Classic', '19classic', '<p> </span> <strong>Lorem Ipsum</strong> is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum. qa</p>\r\n', '68,66,69,67,70', 19, 850, 'Queen', '', 1, '', 0, '', NULL, NULL, NULL, '', 1, 1, NULL, NULL),
(33, 2, 19, NULL, '1', 1, 3, 4, 1, 'Deluxe', '', '2deluxe', '<p> </span> <strong>Lorem Ipsum</strong> is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>\r\n', '68,66,69,67,70', 3, 950, 'Queen', '', 1, '', 0, '', NULL, NULL, NULL, '', 1, 2, NULL, NULL),
(34, 2, 20, NULL, '1', 1, 2, 3, 1, 'Classic', '', '8classic', '<p> </span> <strong>Lorem Ipsum</strong> is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>\r\n', '68,66,69,67,70', 7, 800, 'King', '', 1, '', 0, '', NULL, NULL, NULL, '', 1, 3, NULL, NULL),
(35, 2, 21, NULL, '1', 1, 2, 3, 1, 'Classic', '', '8classic2', '<p> </span></p>\r\n\r\n<p> </span> <strong>Lorem Ipsum</strong> is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>\r\n', '68,66,69,67,70', 8, 900, 'King', '', 1, '', 0, '', NULL, NULL, NULL, '', 1, 4, NULL, NULL),
(36, 2, 21, NULL, '1', 1, 3, 4, 1, 'Deluxe', '', '8deluxe', '<p> </span></p>\r\n\r\n<p> </span> <strong>Lorem Ipsum</strong> is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>\r\n', '68,66,69,67,70', 8, 950, 'King', '', 1, '', 0, '', NULL, NULL, NULL, '', 1, 5, NULL, NULL),
(37, 2, 22, NULL, '1', 1, 2, 3, 1, 'Classic', '', '11classic', '<p> </span></p>\r\n\r\n<p> </span> <strong>Lorem Ipsum</strong> is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>\r\n', '68,66,69,67,70', 11, 900, 'King', '', 1, '', 0, '', NULL, NULL, NULL, '', 1, 6, NULL, NULL),
(38, 2, 22, NULL, '1', 1, 3, 4, 1, 'Deluxe', '', '5deluxe', '<p> </span></p>\r\n\r\n<p> </span> <strong>Lorem Ipsum</strong> is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>\r\n', '68,66,69,67,70', 5, 950, 'King', '', 1, '', 0, '', NULL, NULL, NULL, '', 1, 7, NULL, NULL),
(39, 2, 22, NULL, '1', 1, 2, 3, 1, 'Saver', '', '2saver', '<p> </span></p>\r\n\r\n<p> </span> <strong>Lorem Ipsum</strong> is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>\r\n', '68,66,69,67,70', 2, 750, 'King', '', 1, '', 0, '', NULL, NULL, NULL, '', 1, 8, NULL, NULL),
(40, 2, 23, NULL, '1', 1, 2, 3, 1, 'Classic', '', '22classic', '', '68,66,69,67,70', 3, 900, 'Queen', '', 1, '', 0, '', NULL, NULL, NULL, '', 1, 9, NULL, NULL),
(41, 2, 23, NULL, '1', 1, 3, 4, 1, 'Deluxe', '', 'deluxe', '', '68,66,69,67,70', 10, 950, 'Queen', '', 1, '', 0, '', NULL, NULL, NULL, '', 1, 10, NULL, NULL),
(42, 2, 23, NULL, '1', 1, 2, 3, 1, 'Saver', '', 'saver', '', '68,66,69,67,70', 2, 750, 'Queen', '', 1, '', 0, '', NULL, NULL, NULL, '', 1, 11, NULL, NULL),
(43, 2, 24, NULL, '1', 1, 2, 3, 1, 'Classic', '', '13-classic', '', '68,66,69,67,70', 13, 800, 'Queen', '', 1, '', 0, '', NULL, NULL, NULL, '', 1, 12, NULL, NULL),
(44, 2, 25, NULL, '1', 1, 2, 3, 1, 'Classic', '', 'classic', '', '68,66,69,67,70', 10, 850, 'Queen', '', 1, '', 0, '', NULL, NULL, NULL, '', 1, 13, NULL, NULL),
(45, 2, 26, NULL, '1', 1, 2, 3, 1, 'Classic', '', '2classic', '', '68,66,69,67,70', 10, 800, 'King', '', 1, '', 0, '', NULL, NULL, NULL, '', 1, 14, NULL, NULL),
(46, 2, 26, NULL, '1', 1, 2, 3, 1, 'Saver', '', 'saver2', '', '68,66,69,67,70', 1, 750, 'King', '', 1, '', 0, '', NULL, NULL, NULL, '', 1, 15, NULL, NULL),
(47, 2, 27, NULL, '1', 1, 2, 3, 1, 'Classic', '', '13classic', '', '68,66,69,67,70', 13, 700, 'Queen', '', 1, '', 0, '', NULL, NULL, NULL, '', 1, 16, NULL, NULL),
(48, 2, 28, NULL, '1', 1, 2, 3, 1, 'Classic', '', 'classic2', '', '68,66,69,67,70', 13, 1000, 'King', '', 1, '', 0, '', NULL, NULL, NULL, '', 1, 17, NULL, NULL),
(49, 2, 28, NULL, '1', 1, 3, 4, 1, 'Deluxe', '', '12deluxe', '', '68,66,69,67,70', 12, 1200, 'King', '', 1, '', 0, '', NULL, NULL, NULL, '', 1, 18, NULL, NULL),
(50, 2, 28, NULL, '1', 1, 1, 2, 1, 'Banquet Hall', '', 'banquet-hall', '', '68,66,69,67,70', 1, 600, 'King', '', 1, '', 0, '', NULL, NULL, NULL, '', 2, 19, NULL, NULL),
(51, 2, 28, NULL, '1,139', 1, 2, 3, 1, 'Suit room', '', 'suit-room', '', '68,66,69,67,70', 2, 2500, 'King', '', 2, '', 0, '', NULL, NULL, NULL, '', 1, 20, NULL, NULL),
(52, 2, 29, NULL, '1', 1, 2, 3, 1, 'Classic', '', 'classic-3', '', '68,66,69,67,70', 6, 1350, 'King', '', 1, '', 0, '', NULL, NULL, NULL, '', 1, 21, NULL, NULL),
(53, 2, 30, NULL, '1', 1, 2, 3, 1, 'Classic', '', 'classics', '', '68,66,69,67,70', 10, 800, 'Queen', '', 1, '', 0, '', NULL, NULL, NULL, '', 1, 22, NULL, NULL),
(62, 2, 26, NULL, '1', 18, 20, 38, 1, 'Test room', '', 'test-room', '', '', 2, 120, '', '', NULL, '', 0, '', NULL, NULL, NULL, '', 1, 23, NULL, NULL),
(63, 2, 24, 1, '1', 0, 2, 2, 1, 'Classic-test', '', 'classic-test', '', '', 2, 1200, '', '', NULL, '', 0, '', NULL, NULL, NULL, '', 1, 24, NULL, NULL),
(64, 2, 21, NULL, '1', NULL, 3, 0, NULL, 'test', '', 'test', '', '', 1, 500, '', '', NULL, '', 0, '', NULL, NULL, NULL, '', 2, 25, NULL, NULL),
(65, 2, 20, NULL, '1', NULL, 3, 0, NULL, 'test1', '', 'test1', '', '', 1, 600, '', '', NULL, '', 0, '', NULL, NULL, NULL, '', 1, 26, NULL, NULL),
(66, 2, 20, NULL, '1', NULL, 3, 0, NULL, 'test2', '', 'test2', '', '', 1, 600, '', '', NULL, '', 0, '', NULL, NULL, NULL, '', 1, 27, NULL, NULL),
(67, 2, 33, NULL, '1', 2, 3, 5, 1, 'QA Room', '', 'qa-room', '<p>This is test room, this is test room, this is test room</p>\r\n', '67,80,71', 3, 1000, 'King', '', 2, '12*15', 0, '', NULL, NULL, NULL, '', 1, 28, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `pm_room_cancel_policy`
--

CREATE TABLE `pm_room_cancel_policy` (
  `id` int(11) NOT NULL,
  `id_room` int(11) NOT NULL,
  `duration_type` varchar(25) DEFAULT NULL,
  `value` varchar(11) DEFAULT NULL,
  `fees` int(11) DEFAULT '0',
  `fees_type` varchar(25) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pm_room_cancel_policy`
--

INSERT INTO `pm_room_cancel_policy` (`id`, `id_room`, `duration_type`, `value`, `fees`, `fees_type`) VALUES
(9, 37, 'day', '7', 10, 'parcentage'),
(10, 37, 'hours', '48', 25, 'parcentage'),
(11, 39, 'day', '7', 10, 'parcentage'),
(12, 39, 'hours', '48', 25, 'parcentage'),
(13, 39, 'hours', '24', 50, 'parcentage');

-- --------------------------------------------------------

--
-- Table structure for table `pm_room_closing`
--

CREATE TABLE `pm_room_closing` (
  `id` int(11) NOT NULL,
  `id_room` int(11) NOT NULL,
  `from_date` int(11) DEFAULT NULL,
  `to_date` int(11) DEFAULT NULL,
  `stock` int(11) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `pm_room_file`
--

CREATE TABLE `pm_room_file` (
  `id` int(11) NOT NULL,
  `lang` int(11) NOT NULL,
  `id_item` int(11) NOT NULL,
  `home` int(11) DEFAULT '0',
  `checked` int(11) DEFAULT '1',
  `rank` int(11) DEFAULT '0',
  `file` varchar(250) DEFAULT NULL,
  `label` varchar(250) DEFAULT NULL,
  `type` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pm_room_file`
--

INSERT INTO `pm_room_file` (`id`, `lang`, `id_item`, `home`, `checked`, `rank`, `file`, `label`, `type`) VALUES
(18, 2, 52, NULL, 1, 18, 'classic.jpg', '', 'image'),
(19, 2, 53, NULL, 1, 19, 'classic.jpg', '', 'image'),
(22, 2, 51, NULL, 1, 22, 'maruti-suit.jpg', '', 'image'),
(24, 2, 33, NULL, 1, 24, 'img2.jpg', '', 'image'),
(25, 2, 32, NULL, 1, 25, 'img4.jpg', '', 'image'),
(26, 2, 43, NULL, 1, 26, 'living-room-intro.jpg', '', 'image'),
(27, 2, 34, NULL, 1, 27, 'photo-1540518614846-7eded433c457.jpg', '', 'image'),
(28, 2, 35, NULL, 1, 28, 'room-5.jpg', '', 'image'),
(29, 2, 36, NULL, 1, 29, 'deluxe-king-guest-room-575035-high.jpg', '', 'image'),
(30, 2, 37, NULL, 1, 30, 'img3.jpg', '', 'image'),
(31, 2, 38, NULL, 1, 31, 'img6.jpg', '', 'image'),
(33, 2, 40, NULL, 1, 33, 'city-continental-twin-bedroom.jpg', '', 'image'),
(34, 2, 41, NULL, 1, 34, 'executive-twin-room.jpg', NULL, 'image'),
(35, 2, 42, NULL, 1, 35, 'imperial-hotel-twin-room.jpg', NULL, 'image'),
(37, 2, 44, NULL, 1, 36, 'hotel-bedroom.jpg', NULL, 'image'),
(38, 2, 46, NULL, 1, 37, 'photo-1540518614846-7eded433c457.jpg', NULL, 'image'),
(39, 2, 47, NULL, 1, 38, 'deluxe-king-guest-room-575035-high.jpg', NULL, 'image'),
(40, 2, 48, NULL, 1, 39, 'room-5.jpg', NULL, 'image'),
(41, 2, 49, NULL, 1, 40, 'hotel-bedroom.jpg', NULL, 'image'),
(42, 2, 45, NULL, 1, 41, 'img2.jpg', NULL, 'image'),
(44, 2, 33, NULL, 1, 42, 'room-5.jpg', '', 'image'),
(45, 2, 33, NULL, 1, 43, 'living-room-intro.jpg', NULL, 'image'),
(46, 2, 33, NULL, 1, 44, 'living-room-intro.jpg', NULL, 'image'),
(47, 2, 33, NULL, 1, 45, 'room-5.jpg', NULL, 'image'),
(48, 2, 33, NULL, 1, 46, 'living-room-intro.jpg', '', 'image'),
(49, 2, 33, NULL, 1, 47, 'room-5.jpg', '', 'image'),
(50, 2, 33, NULL, 1, 48, 'living-room-intro.jpg', '', 'image'),
(51, 2, 33, NULL, 1, 49, 'room-5.jpg', '', 'image'),
(52, 2, 32, NULL, 1, 50, 'photo-1540518614846-7eded433c457.jpg', NULL, 'image'),
(56, 2, 43, NULL, 1, 51, 'living-room-intro.jpg', NULL, 'image'),
(57, 2, 43, NULL, 1, 52, 'living-room-intro.jpg', NULL, 'image'),
(64, 2, 39, NULL, 1, 59, 'ezgif-com-webp-to-jpg.jpg', NULL, 'image'),
(65, 2, 39, NULL, 1, 60, 'twin-bedroom.jpg', '', 'image'),
(66, 2, 39, NULL, 1, 61, '7c8d2fc287e7b1a9.jpg', NULL, 'image'),
(67, 2, 39, NULL, 1, 62, 'ezgif-com-webp-to-jpg.jpg', NULL, 'image'),
(68, 2, 39, NULL, 1, 63, 'living-room-intro.jpg', NULL, 'image'),
(69, 2, 32, NULL, 1, 64, '58044a6b597cf9c4.jpg', NULL, 'image'),
(70, 2, 32, NULL, 1, 65, 'img4.jpg', NULL, 'image'),
(71, 2, 32, NULL, 1, 66, '58044a6b597cf9c4.jpg', NULL, 'image'),
(72, 2, 32, NULL, 1, 67, 'img4.jpg', NULL, 'image'),
(73, 2, 32, NULL, 1, 68, '58044a6b597cf9c4.jpg', NULL, 'image'),
(74, 2, 32, NULL, 1, 69, 'img4.jpg', NULL, 'image'),
(75, 2, 32, NULL, 1, 70, '58044a6b597cf9c4.jpg', NULL, 'image'),
(76, 2, 32, NULL, 1, 71, 'img4.jpg', NULL, 'image'),
(77, 2, 35, NULL, 1, 72, 'qa-training.png', NULL, 'image'),
(78, 2, 67, NULL, 1, 73, 'qa-logo.png', '', 'image');

-- --------------------------------------------------------

--
-- Table structure for table `pm_room_lock`
--

CREATE TABLE `pm_room_lock` (
  `id` int(11) NOT NULL,
  `id_room` int(11) DEFAULT NULL,
  `from_date` int(11) DEFAULT NULL,
  `to_date` int(11) DEFAULT NULL,
  `add_date` int(11) DEFAULT NULL,
  `sessid` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `pm_room_names`
--

CREATE TABLE `pm_room_names` (
  `id` int(11) NOT NULL,
  `id_room` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `stock` int(11) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pm_room_names`
--

INSERT INTO `pm_room_names` (`id`, `id_room`, `name`, `stock`) VALUES
(2, 3, 'G1', 0),
(3, 2, 'F1', NULL),
(4, 2, 'F2', NULL),
(5, 2, 'F3', NULL),
(6, 2, 'F4', NULL),
(7, 3, 'G2', NULL),
(8, 5, 'GA1', NULL),
(9, 5, 'GA2', NULL),
(10, 5, 'GA3', NULL),
(11, 5, 'GA4', NULL),
(12, 5, 'GA5', NULL),
(13, 4, 'FA1', NULL),
(14, 4, 'FA2', NULL),
(15, 4, 'FA3', NULL),
(16, 4, 'FA4', NULL),
(17, 6, 'F2-201', NULL),
(18, 6, 'F2-202', NULL),
(19, 6, 'F2-203', NULL),
(20, 6, 'F2-204', NULL),
(21, 7, '101', NULL),
(22, 7, '102', NULL),
(23, 7, '103', NULL),
(24, 7, '201', NULL),
(25, 7, '202', NULL),
(26, 7, 'F203', NULL),
(27, 8, '101', NULL),
(28, 8, '102', NULL),
(29, 8, '103', NULL),
(30, 8, '201', NULL),
(31, 8, '202', NULL),
(32, 8, '203', NULL),
(33, 9, '101', NULL),
(34, 9, '102', NULL),
(35, 9, '103', NULL),
(36, 9, '201', NULL),
(37, 9, '202', NULL),
(38, 9, '203', NULL),
(39, 11, '101', NULL),
(40, 11, '102', NULL),
(41, 11, '103', NULL),
(42, 11, '201', NULL),
(43, 11, '202', NULL),
(44, 11, '203', NULL),
(45, 10, '302', NULL),
(46, 10, '303', NULL),
(47, 10, '304', NULL),
(48, 10, '305', NULL),
(49, 10, '306', NULL),
(50, 10, '308', NULL),
(51, 12, '101', NULL),
(52, 12, '102', NULL),
(53, 12, '103', NULL),
(54, 12, '201', NULL),
(55, 12, '202', NULL),
(56, 12, '203', NULL),
(57, 13, '101', NULL),
(58, 13, '102', NULL),
(59, 13, '103', NULL),
(60, 13, '201', NULL),
(61, 13, '202', NULL),
(62, 13, '203', NULL),
(63, 14, '101', NULL),
(64, 14, '102', NULL),
(65, 14, '103', NULL),
(66, 14, '201', NULL),
(67, 14, '202', NULL),
(68, 14, '203', NULL),
(69, 15, '101', NULL),
(70, 15, '102', NULL),
(71, 15, '103', NULL),
(72, 15, '201', NULL),
(73, 15, '202', NULL),
(74, 15, '203', NULL),
(75, 18, '200', NULL),
(76, 18, '201', NULL),
(77, 18, '202', NULL),
(78, 18, '203', NULL),
(79, 16, 'F1', NULL),
(80, 19, 'F101', NULL),
(81, 19, 'F102', NULL),
(82, 21, 'F1', NULL),
(83, 21, 'f2', NULL),
(84, 21, 'F3', NULL),
(85, 22, 'FA1', NULL),
(86, 22, 'FA2', NULL),
(87, 25, 'Room 1', NULL),
(88, 25, 'Room 2', NULL),
(89, 25, 'Room 3', NULL),
(90, 25, 'Room 4', NULL),
(91, 28, 'FI', NULL),
(92, 28, 'F2', NULL),
(93, 29, 'FI', NULL),
(94, 29, 'F2', NULL),
(95, 30, 'FI', NULL),
(96, 30, 'F2', NULL),
(97, 33, 'F1', NULL),
(98, 33, 'F2', NULL),
(99, 33, 'F3', NULL),
(100, 57, 'FI', NULL),
(101, 57, 'F2', NULL);

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

-- --------------------------------------------------------

--
-- Table structure for table `pm_room_slots`
--

CREATE TABLE `pm_room_slots` (
  `id` int(11) NOT NULL,
  `id_room` int(11) NOT NULL,
  `from_time` varchar(25) DEFAULT NULL,
  `to_time` varchar(11) DEFAULT NULL,
  `price` int(11) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pm_room_slots`
--

INSERT INTO `pm_room_slots` (`id`, `id_room`, `from_time`, `to_time`, `price`) VALUES
(4, 2, '1558346400', '1558350000', 99),
(5, 3, '1558346400', '1558378800', 99),
(6, 4, '1558375200', '1558386000', 99),
(8, 5, '01:00AM', '02:00AM', 200);

-- --------------------------------------------------------

--
-- Table structure for table `pm_room_slots_booking`
--

CREATE TABLE `pm_room_slots_booking` (
  `id` int(11) NOT NULL,
  `id_hotel` int(11) DEFAULT NULL,
  `id_booking` int(11) DEFAULT NULL,
  `from_time` int(11) DEFAULT NULL,
  `to_time` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `pm_service`
--

CREATE TABLE `pm_service` (
  `id` int(11) NOT NULL,
  `lang` int(11) NOT NULL,
  `users` text,
  `id_hotel` int(11) DEFAULT NULL,
  `id_accommodation` int(11) DEFAULT NULL,
  `title` varchar(250) DEFAULT NULL,
  `descr` text,
  `long_descr` text,
  `type` varchar(50) DEFAULT NULL,
  `rooms` varchar(250) DEFAULT NULL,
  `price` double DEFAULT NULL,
  `id_tax` int(11) DEFAULT NULL,
  `taxes` text,
  `mandatory` int(11) DEFAULT '0',
  `start_date` int(11) DEFAULT NULL,
  `end_date` int(11) DEFAULT NULL,
  `checked` int(11) DEFAULT '0',
  `rank` int(11) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `pm_slide`
--

CREATE TABLE `pm_slide` (
  `id` int(11) NOT NULL,
  `lang` int(11) NOT NULL,
  `legend` text,
  `url` varchar(250) DEFAULT NULL,
  `id_page` int(11) DEFAULT NULL,
  `checked` int(11) DEFAULT '0',
  `rank` int(11) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pm_slide`
--

INSERT INTO `pm_slide` (`id`, `lang`, `legend`, `url`, `id_page`, `checked`, `rank`) VALUES
(9, 2, '', '', 1, 2, 1),
(10, 2, '', '', 1, 1, 2),
(11, 2, '', '', 1, 2, 3),
(12, 2, '', '', 1, 2, 4),
(13, 2, '', '', 1, 1, 5);

-- --------------------------------------------------------

--
-- Table structure for table `pm_slide_file`
--

CREATE TABLE `pm_slide_file` (
  `id` int(11) NOT NULL,
  `lang` int(11) NOT NULL,
  `id_item` int(11) NOT NULL,
  `home` int(11) DEFAULT '0',
  `checked` int(11) DEFAULT '1',
  `rank` int(11) DEFAULT '0',
  `file` varchar(250) DEFAULT NULL,
  `label` varchar(250) DEFAULT NULL,
  `type` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pm_slide_file`
--

INSERT INTO `pm_slide_file` (`id`, `lang`, `id_item`, `home`, `checked`, `rank`, `file`, `label`, `type`) VALUES
(12, 2, 9, NULL, 1, 10, 'hotel-background-pic1.jpg', '', 'image'),
(13, 2, 10, NULL, 1, 11, 'hotel-background-pic2.jpg', NULL, 'image'),
(14, 2, 11, NULL, 1, 12, 'hotel-background-pic1.jpg', NULL, 'image'),
(15, 2, 12, NULL, 1, 13, 'hotel-background-pic2.jpg', NULL, 'image'),
(16, 2, 13, NULL, 1, 14, 'hotel-background-pic2.jpg', NULL, 'image');

-- --------------------------------------------------------

--
-- Table structure for table `pm_social`
--

CREATE TABLE `pm_social` (
  `id` int(11) NOT NULL,
  `type` varchar(50) DEFAULT NULL,
  `url` text,
  `checked` int(11) DEFAULT '1',
  `rank` int(11) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pm_social`
--

INSERT INTO `pm_social` (`id`, `type`, `url`, `checked`, `rank`) VALUES
(1, 'facebook', 'https://www.facebook.com/', 2, 1);

-- --------------------------------------------------------

--
-- Table structure for table `pm_stats_user`
--

CREATE TABLE `pm_stats_user` (
  `id` int(11) NOT NULL,
  `users` text,
  `firstname` varchar(100) DEFAULT NULL,
  `lastname` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `login` varchar(50) DEFAULT NULL,
  `pass` varchar(100) DEFAULT NULL,
  `type` varchar(20) DEFAULT NULL,
  `add_date` int(11) DEFAULT NULL,
  `edit_date` int(11) DEFAULT NULL,
  `checked` int(11) DEFAULT '0',
  `fb_id` varchar(50) DEFAULT NULL,
  `address` varchar(250) DEFAULT NULL,
  `postcode` varchar(10) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `company` varchar(100) DEFAULT NULL,
  `gstin` varchar(50) DEFAULT NULL,
  `govid_type` varchar(50) DEFAULT NULL,
  `govid` varchar(100) DEFAULT NULL,
  `country` varchar(100) DEFAULT NULL,
  `mobile` varchar(50) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `token` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pm_stats_user`
--

INSERT INTO `pm_stats_user` (`id`, `users`, `firstname`, `lastname`, `email`, `login`, `pass`, `type`, `add_date`, `edit_date`, `checked`, `fb_id`, `address`, `postcode`, `city`, `company`, `gstin`, `govid_type`, `govid`, `country`, `mobile`, `phone`, `token`) VALUES
(1, '1', 'Super', 'Admin', 'admin@fitser.com', 'superadmin', 'db265e87c31a4e30da20757ed44c656d', 'administrator', 1557396800, 1566278789, 1, '', 'Chingrighata', '700105', 'Kolkata', 'Fitser', '', 'Adhar', '7788556632659', 'India', '9475359786', '', '4ca6752927d6160874217acd791a961d'),
(2, '1', 'Sonjoy', 'Bhadra', 'sonjoy.bhadra@met-technoloies.com', 'sonjoybhadra', '3835caee043c34b38f6127f962a0361f', 'administrator', 1568293893, 1568293936, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '9475359785', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `pm_tag`
--

CREATE TABLE `pm_tag` (
  `id` int(11) NOT NULL,
  `lang` int(11) NOT NULL,
  `value` varchar(250) DEFAULT NULL,
  `pages` varchar(250) DEFAULT NULL,
  `checked` int(11) DEFAULT '0',
  `rank` int(11) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pm_tag`
--

INSERT INTO `pm_tag` (`id`, `lang`, `value`, `pages`, `checked`, `rank`) VALUES
(1, 2, 'Test Tag', '2,9', 1, 1),
(2, 2, 'Test Tag 2', '14', 1, 2),
(3, 2, 'Best Hotel', '5,17', 1, 3);

-- --------------------------------------------------------

--
-- Table structure for table `pm_tax`
--

CREATE TABLE `pm_tax` (
  `id` int(11) NOT NULL,
  `lang` int(11) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `value` double DEFAULT '0',
  `id_accommodation` text,
  `checked` int(11) DEFAULT '0',
  `rank` int(11) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pm_tax`
--

INSERT INTO `pm_tax` (`id`, `lang`, `name`, `value`, `id_accommodation`, `checked`, `rank`) VALUES
(1, 2, 'GST', 18, '7,8,9', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `pm_tax_slab`
--

CREATE TABLE `pm_tax_slab` (
  `id` int(11) NOT NULL,
  `id_tax` int(11) NOT NULL,
  `start` int(11) DEFAULT NULL,
  `end` int(11) DEFAULT NULL,
  `value` double DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pm_tax_slab`
--

INSERT INTO `pm_tax_slab` (`id`, `id_tax`, `start`, `end`, `value`) VALUES
(4, 1, 0, 1000, 0),
(5, 1, 1001, 1200, 12),
(6, 1, 1201, 999999, 18);

-- --------------------------------------------------------

--
-- Table structure for table `pm_testimonial`
--

CREATE TABLE `pm_testimonial` (
  `id` bigint(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `comment` text NOT NULL,
  `status` tinyint(2) NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `pm_testimonial`
--

INSERT INTO `pm_testimonial` (`id`, `name`, `comment`, `status`, `created_at`, `updated_at`) VALUES
(1, 'George A. Moore', 'A man travels the world over in search of what he needs and returns home to find it.', 1, '2020-02-28 03:25:38', '2020-03-03 18:36:37');

-- --------------------------------------------------------

--
-- Table structure for table `pm_text`
--

CREATE TABLE `pm_text` (
  `id` int(11) NOT NULL,
  `lang` int(11) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `value` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pm_text`
--

INSERT INTO `pm_text` (`id`, `lang`, `name`, `value`) VALUES
(1, 1, 'CREATION', 'Création'),
(1, 2, 'CREATION', 'Creation'),
(1, 3, 'CREATION', 'إنشاء'),
(1, 4, 'CREATION', 'Creation'),
(2, 1, 'MESSAGE', 'Message'),
(2, 2, 'MESSAGE', 'Message'),
(2, 3, 'MESSAGE', 'رسالة'),
(2, 4, 'MESSAGE', 'Message'),
(3, 1, 'EMAIL', 'E-mail'),
(3, 2, 'EMAIL', 'E-mail'),
(3, 3, 'EMAIL', 'بَرِيدٌ إلِكْترونيّ'),
(3, 4, 'EMAIL', 'E-mail'),
(4, 1, 'PHONE', 'Tél.'),
(4, 2, 'PHONE', 'Phone'),
(4, 3, 'PHONE', 'رقم هاتف'),
(4, 4, 'PHONE', 'Phone'),
(5, 1, 'FAX', 'Fax'),
(5, 2, 'FAX', 'Fax'),
(5, 3, 'FAX', 'فاكس'),
(5, 4, 'FAX', 'Fax'),
(6, 1, 'COMPANY', 'Société'),
(6, 2, 'COMPANY', 'Company'),
(6, 3, 'COMPANY', 'مشروع'),
(6, 4, 'COMPANY', 'Company'),
(7, 1, 'COPY_CODE', 'Recopiez le code'),
(7, 2, 'COPY_CODE', 'Copy the code'),
(7, 3, 'COPY_CODE', 'رمز الأمان'),
(7, 4, 'COPY_CODE', 'Copy the code'),
(8, 1, 'SUBJECT', 'Sujet'),
(8, 2, 'SUBJECT', 'Subject'),
(8, 3, 'SUBJECT', 'موضوع'),
(8, 4, 'SUBJECT', 'Subject'),
(9, 1, 'REQUIRED_FIELD', 'Champ requis'),
(9, 2, 'REQUIRED_FIELD', 'Required field'),
(9, 3, 'REQUIRED_FIELD', 'الحقل المطلوب'),
(9, 4, 'REQUIRED_FIELD', 'Required field'),
(10, 1, 'INVALID_CAPTCHA_CODE', 'Le code de sécurité saisi est incorrect'),
(10, 2, 'INVALID_CAPTCHA_CODE', 'Invalid security code'),
(10, 3, 'INVALID_CAPTCHA_CODE', 'رمز الحماية أدخلته غير صحيح'),
(10, 4, 'INVALID_CAPTCHA_CODE', 'Invalid security code'),
(11, 1, 'INVALID_EMAIL', 'Adresse e-mail invalide'),
(11, 2, 'INVALID_EMAIL', 'Invalid email address'),
(11, 3, 'INVALID_EMAIL', 'بريد إلكتروني خاطئ'),
(11, 4, 'INVALID_EMAIL', 'Invalid email address'),
(12, 1, 'FIRSTNAME', 'Prénom'),
(12, 2, 'FIRSTNAME', 'First Name'),
(12, 3, 'FIRSTNAME', 'الاسم الأول'),
(12, 4, 'FIRSTNAME', 'First Name'),
(13, 1, 'LASTNAME', 'Nom'),
(13, 2, 'LASTNAME', 'Last Name'),
(13, 3, 'LASTNAME', 'اسم العائلة'),
(13, 4, 'LASTNAME', 'Last Name'),
(14, 1, 'ADDRESS', 'Adresse'),
(14, 2, 'ADDRESS', 'Address'),
(14, 3, 'ADDRESS', 'عنوان الشارع'),
(14, 4, 'ADDRESS', 'Address'),
(15, 1, 'POSTCODE', 'Code postal'),
(15, 2, 'POSTCODE', 'Post Code'),
(15, 3, 'POSTCODE', 'الرمز البريدي'),
(15, 4, 'POSTCODE', 'Post code'),
(16, 1, 'CITY', 'Ville'),
(16, 2, 'CITY', 'City'),
(16, 3, 'CITY', 'مدينة'),
(16, 4, 'CITY', 'City'),
(17, 1, 'MOBILE', 'Portable'),
(17, 2, 'MOBILE', 'Mobile'),
(17, 3, 'MOBILE', 'هاتف'),
(17, 4, 'MOBILE', 'Mobile'),
(18, 1, 'ADD', 'Ajouter'),
(18, 2, 'ADD', 'Add'),
(18, 3, 'ADD', 'إضافة على'),
(18, 4, 'ADD', 'Add'),
(19, 1, 'EDIT', 'Modifier'),
(19, 2, 'EDIT', 'Edit'),
(19, 3, 'EDIT', 'تغيير'),
(19, 4, 'EDIT', 'Edit'),
(20, 1, 'INVALID_INPUT', 'Saisie invalide'),
(20, 2, 'INVALID_INPUT', 'Invalid input'),
(20, 3, 'INVALID_INPUT', 'إدخال غير صالح'),
(20, 4, 'INVALID_INPUT', 'Invalid input'),
(21, 1, 'MAIL_DELIVERY_FAILURE', 'Echec lors de l\'envoi du message.'),
(21, 2, 'MAIL_DELIVERY_FAILURE', 'A failure occurred during the delivery of this message.'),
(21, 3, 'MAIL_DELIVERY_FAILURE', 'حدث فشل أثناء تسليم هذه الرسالة.'),
(21, 4, 'MAIL_DELIVERY_FAILURE', 'A failure occurred during the delivery of this message.'),
(22, 1, 'MAIL_DELIVERY_SUCCESS', 'Merci de votre intérêt, votre message a bien été envoyé.\nNous vous contacterons dans les plus brefs délais.'),
(22, 2, 'MAIL_DELIVERY_SUCCESS', 'Thank you for your interest, your message has been sent.\nWe will contact you as soon as possible.'),
(22, 3, 'MAIL_DELIVERY_SUCCESS', 'خزان لاهتمامك ، تم إرسال رسالتك . سوف نتصل بك في أقرب وقت ممكن .'),
(22, 4, 'MAIL_DELIVERY_SUCCESS', 'Thank you for your interest, your message has been sent.\nWe will contact you as soon as possible.'),
(23, 1, 'SEND', 'Envoyer'),
(23, 2, 'SEND', 'Send'),
(23, 3, 'SEND', 'ارسل انت'),
(23, 4, 'SEND', 'Send'),
(24, 1, 'FORM_ERRORS', 'Le formulaire comporte des erreurs.'),
(24, 2, 'FORM_ERRORS', 'The following form contains some errors.'),
(24, 3, 'FORM_ERRORS', 'النموذج التالي يحتوي على بعض الأخطاء.'),
(24, 4, 'FORM_ERRORS', 'The following form contains some errors.'),
(25, 1, 'FROM_DATE', 'Du'),
(25, 2, 'FROM_DATE', 'From'),
(25, 3, 'FROM_DATE', 'من'),
(25, 4, 'FROM_DATE', 'From'),
(26, 1, 'TO_DATE', 'au'),
(26, 2, 'TO_DATE', 'Till'),
(26, 3, 'TO_DATE', 'حتى'),
(26, 4, 'TO_DATE', 'Till'),
(27, 1, 'FROM', 'De'),
(27, 2, 'FROM', 'From'),
(27, 3, 'FROM', 'من'),
(27, 4, 'FROM', 'From'),
(28, 1, 'TO', 'à'),
(28, 2, 'TO', 'to'),
(28, 3, 'TO', 'إلى'),
(28, 4, 'TO', 'to'),
(29, 1, 'BOOK', 'Réserver'),
(29, 2, 'BOOK', 'Book'),
(29, 3, 'BOOK', 'للحجز'),
(29, 4, 'BOOK', 'Book'),
(30, 1, 'READMORE', 'Lire la suite'),
(30, 2, 'READMORE', 'Read more'),
(30, 3, 'READMORE', 'اقرأ المزيد'),
(30, 4, 'READMORE', 'Read more'),
(31, 1, 'BACK', 'Retour'),
(31, 2, 'BACK', 'Back'),
(31, 3, 'BACK', 'عودة'),
(31, 4, 'BACK', 'Back'),
(32, 1, 'DISCOVER', 'Découvrir'),
(32, 2, 'DISCOVER', 'Discover'),
(32, 3, 'DISCOVER', 'اكتشف'),
(32, 4, 'DISCOVER', 'Discover'),
(33, 1, 'ALL', 'Tous'),
(33, 2, 'ALL', 'All'),
(33, 3, 'ALL', 'كل'),
(33, 4, 'ALL', 'All'),
(34, 1, 'ALL_RIGHTS_RESERVED', 'Tous droits réservés'),
(34, 2, 'ALL_RIGHTS_RESERVED', 'All Rights Reserved'),
(34, 3, 'ALL_RIGHTS_RESERVED', 'جميع الحقوق محفوظه'),
(34, 4, 'ALL_RIGHTS_RESERVED', 'All rights reserved'),
(35, 1, 'FORGOTTEN_PASSWORD', 'Mot de passe oublié ?'),
(35, 2, 'FORGOTTEN_PASSWORD', 'Forgot Password?'),
(35, 3, 'FORGOTTEN_PASSWORD', 'هل نسيت كلمة المرور؟'),
(35, 4, 'FORGOTTEN_PASSWORD', 'Forgot Password?'),
(36, 1, 'LOG_IN', 'Connexion'),
(36, 2, 'LOG_IN', 'Log in'),
(36, 3, 'LOG_IN', 'تسجيل الدخول'),
(36, 4, 'LOG_IN', 'Log in'),
(37, 1, 'SIGN_UP', 'Inscription'),
(37, 2, 'SIGN_UP', 'Signup'),
(37, 3, 'SIGN_UP', 'تسجيل'),
(37, 4, 'SIGN_UP', 'Sign up'),
(38, 1, 'LOG_OUT', 'Déconnexion'),
(38, 2, 'LOG_OUT', 'Log out'),
(38, 3, 'LOG_OUT', 'تسجيل الخروج'),
(38, 4, 'LOG_OUT', 'Log out'),
(39, 1, 'SEARCH', 'Rechercher'),
(39, 2, 'SEARCH', 'Search'),
(39, 3, 'SEARCH', 'ابحث عن'),
(39, 4, 'SEARCH', 'Search'),
(40, 1, 'RESET_PASS_SUCCESS', 'Votre nouveau mot de passe vous a été envoyé sur votre adresse e-mail.'),
(40, 2, 'RESET_PASS_SUCCESS', 'Your new password was sent to you on your e-mail.'),
(40, 3, 'RESET_PASS_SUCCESS', 'تم إرسال كلمة المرور الجديدة إلى عنوان البريد الإلكتروني الخاص بك'),
(40, 4, 'RESET_PASS_SUCCESS', 'Your new password was sent to you on your e-mail.'),
(41, 1, 'PASS_TOO_SHORT', 'Le mot de passe doit contenir 6 caractères au minimum'),
(41, 2, 'PASS_TOO_SHORT', 'The password must contain 6 characters at least'),
(41, 3, 'PASS_TOO_SHORT', 'يجب أن يحتوي على كلمة المرور ستة أحرف على الأقل'),
(41, 4, 'PASS_TOO_SHORT', 'The password must contain 6 characters at least'),
(42, 1, 'PASS_DONT_MATCH', 'Les mots de passe doivent correspondre'),
(42, 2, 'PASS_DONT_MATCH', 'The passwords don\'t match'),
(42, 3, 'PASS_DONT_MATCH', 'يجب أن تتطابق كلمات المرور'),
(42, 4, 'PASS_DONT_MATCH', 'The passwords don\'t match'),
(43, 1, 'ACCOUNT_EXISTS', 'Un compte existe déjà avec cette adresse e-mail'),
(43, 2, 'ACCOUNT_EXISTS', 'An account already exists with this e-mail'),
(43, 3, 'ACCOUNT_EXISTS', 'حساب موجود بالفعل مع هذا عنوان البريد الإلكتروني'),
(43, 4, 'ACCOUNT_EXISTS', 'An account already exists with this e-mail'),
(44, 1, 'ACCOUNT_CREATED', 'Votre compte a bien été créé. Vous allez recevoir un email de confirmation. Cliquez sur le lien de cet e-mail pour confirmer votre compte avant de continuer.'),
(44, 2, 'ACCOUNT_CREATED', 'Thank you for Signup, your account has been created. You will receive a welcome email and login in your account.'),
(44, 3, 'ACCOUNT_CREATED', 'Your account has been created. You will receive a confirmation email. Click on the link in this email to confirm your account before continuing.'),
(44, 4, 'ACCOUNT_CREATED', 'Your account has been created. You will receive a confirmation email. Click on the link in this email to confirm your account before continuing.'),
(45, 1, 'INCORRECT_LOGIN', 'Les informations de connexion sont incorrectes.'),
(45, 2, 'INCORRECT_LOGIN', 'You have entered an invalid Email or Password'),
(45, 3, 'INCORRECT_LOGIN', 'معلومات تسجيل الدخول غير صحيحة.'),
(45, 4, 'INCORRECT_LOGIN', 'You have entered an invalid username/email or password'),
(46, 1, 'I_SIGN_UP', 'Je m\'inscris'),
(46, 2, 'I_SIGN_UP', 'Signup'),
(46, 3, 'I_SIGN_UP', 'يمكنني الاشتراك'),
(46, 4, 'I_SIGN_UP', 'I sign up'),
(47, 1, 'ALREADY_HAVE_ACCOUNT', 'J\'ai déjà un compte'),
(47, 2, 'ALREADY_HAVE_ACCOUNT', 'Already have an account?'),
(47, 3, 'ALREADY_HAVE_ACCOUNT', 'لدي بالفعل حساب'),
(47, 4, 'ALREADY_HAVE_ACCOUNT', 'I already have an account'),
(48, 1, 'MY_ACCOUNT', 'Mon compte'),
(48, 2, 'MY_ACCOUNT', 'My Account'),
(48, 3, 'MY_ACCOUNT', 'حسابي'),
(48, 4, 'MY_ACCOUNT', 'My account'),
(49, 1, 'COMMENTS', 'Commentaires'),
(49, 2, 'COMMENTS', 'Comments'),
(49, 3, 'COMMENTS', 'تعليقات'),
(49, 4, 'COMMENTS', 'Comments'),
(50, 1, 'LET_US_KNOW', 'Faîtes-nous savoir ce que vous pensez'),
(50, 2, 'LET_US_KNOW', 'Let us know what you think'),
(50, 3, 'LET_US_KNOW', 'ماذا عن رايك؟'),
(50, 4, 'LET_US_KNOW', 'Let us know what you think'),
(51, 1, 'COMMENT_SUCCESS', 'Merci de votre intérêt, votre commentaire va être soumis à validation.'),
(51, 2, 'COMMENT_SUCCESS', 'Thank you for your interest, your comment will be checked.'),
(51, 3, 'COMMENT_SUCCESS', 'شكرا ل اهتمامك، و سيتم التحقق من صحة للتعليق.'),
(51, 4, 'COMMENT_SUCCESS', 'Thank you for your interest, your comment will be checked.'),
(52, 1, 'NO_SEARCH_RESULT', 'Aucun résultat. Vérifiez l\'orthographe des termes de recherche (> 3 caractères) ou essayez d\'autres mots.'),
(52, 2, 'NO_SEARCH_RESULT', 'No results found that match your search!!'),
(52, 3, 'NO_SEARCH_RESULT', 'لا نتيجة. التدقيق الإملائي للكلمات (أكثر من ثلاثة أحرف ) أو محاولة بعبارة أخرى .'),
(52, 4, 'NO_SEARCH_RESULT', 'No results found that match your search!!'),
(53, 1, 'SEARCH_EXCEEDED', 'Nombre de recherches dépassé.'),
(53, 2, 'SEARCH_EXCEEDED', 'Number of researches exceeded.'),
(53, 3, 'SEARCH_EXCEEDED', 'عدد من الأبحاث السابقة .'),
(53, 4, 'SEARCH_EXCEEDED', 'Number of researches exceeded.'),
(54, 1, 'SECONDS', 'secondes'),
(54, 2, 'SECONDS', 'seconds'),
(54, 3, 'SECONDS', 'ثواني'),
(54, 4, 'SECONDS', 'seconds'),
(55, 1, 'FOR_A_TOTAL_OF', 'sur un total de'),
(55, 2, 'FOR_A_TOTAL_OF', 'for a total of'),
(55, 3, 'FOR_A_TOTAL_OF', 'من الكل'),
(55, 4, 'FOR_A_TOTAL_OF', 'for a total of'),
(56, 1, 'COMMENT', 'Commentaire'),
(56, 2, 'COMMENT', 'Comment'),
(56, 3, 'COMMENT', 'تعقيب'),
(56, 4, 'COMMENT', 'Comment'),
(57, 1, 'VIEW', 'Visionner'),
(57, 2, 'VIEW', 'View'),
(57, 3, 'VIEW', 'ل عرض'),
(57, 4, 'VIEW', 'View'),
(58, 1, 'RECENT_ARTICLES', 'Articles récents'),
(58, 2, 'RECENT_ARTICLES', 'Recent articles'),
(58, 3, 'RECENT_ARTICLES', 'المقالات الأخيرة'),
(58, 4, 'RECENT_ARTICLES', 'Recent articles'),
(59, 1, 'RSS_FEED', 'Flux RSS'),
(59, 2, 'RSS_FEED', 'RSS feed'),
(59, 3, 'RSS_FEED', 'تغذية RSS'),
(59, 4, 'RSS_FEED', 'RSS feed'),
(60, 1, 'COUNTRY', 'Pays'),
(60, 2, 'COUNTRY', 'Country'),
(60, 3, 'COUNTRY', 'Country'),
(60, 4, 'COUNTRY', 'Country'),
(61, 1, 'ROOM', 'Chambre'),
(61, 2, 'ROOM', 'Room'),
(61, 3, 'ROOM', 'Room'),
(61, 4, 'ROOM', 'Room'),
(62, 1, 'INCL_VAT', 'TTC'),
(62, 2, 'INCL_VAT', 'incl. GST'),
(62, 3, 'INCL_VAT', 'incl. VAT'),
(62, 4, 'INCL_VAT', 'incl. VAT'),
(63, 1, 'NIGHTS', 'nuits'),
(63, 2, 'NIGHTS', 'Nights'),
(63, 3, 'NIGHTS', 'nights'),
(63, 4, 'NIGHTS', 'Nights'),
(64, 1, 'ADULTS', 'Adultes'),
(64, 2, 'ADULTS', 'Adult'),
(64, 3, 'ADULTS', 'Adults'),
(64, 4, 'ADULTS', 'Adults'),
(65, 1, 'CHILDREN', 'Enfants'),
(65, 2, 'CHILDREN', 'Children'),
(65, 3, 'CHILDREN', 'Children'),
(65, 4, 'CHILDREN', 'Children'),
(66, 1, 'PERSONS', 'personnes'),
(66, 2, 'PERSONS', 'Person'),
(66, 3, 'PERSONS', 'persons'),
(66, 4, 'PERSONS', 'persons'),
(67, 1, 'CONTACT_DETAILS', 'Coordonnées'),
(67, 2, 'CONTACT_DETAILS', 'Contact details'),
(67, 3, 'CONTACT_DETAILS', 'Contact details'),
(67, 4, 'CONTACT_DETAILS', 'Contact details'),
(68, 1, 'NO_AVAILABILITY', 'Aucune disponibilité'),
(68, 2, 'NO_AVAILABILITY', 'No availability'),
(68, 3, 'NO_AVAILABILITY', 'No availability'),
(68, 4, 'NO_AVAILABILITY', 'No availability'),
(69, 1, 'AVAILABILITIES', 'Disponibilités'),
(69, 2, 'AVAILABILITIES', 'Availabilities'),
(69, 3, 'AVAILABILITIES', 'Availabilities'),
(69, 4, 'AVAILABILITIES', 'Availabilities'),
(70, 1, 'CHECK', 'Vérifier'),
(70, 2, 'CHECK', 'Check'),
(70, 3, 'CHECK', 'Check'),
(70, 4, 'CHECK', 'Check'),
(71, 1, 'BOOKING_DETAILS', 'Détails de la réservation'),
(71, 2, 'BOOKING_DETAILS', 'Booking Details'),
(71, 3, 'BOOKING_DETAILS', 'Booking details'),
(71, 4, 'BOOKING_DETAILS', 'Booking details'),
(72, 1, 'SPECIAL_REQUESTS', 'Demandes spéciales'),
(72, 2, 'SPECIAL_REQUESTS', 'Special requests'),
(72, 3, 'SPECIAL_REQUESTS', 'Special requests'),
(72, 4, 'SPECIAL_REQUESTS', 'Special requests'),
(73, 1, 'PREVIOUS_STEP', 'Étape précédente'),
(73, 2, 'PREVIOUS_STEP', 'Back'),
(73, 3, 'PREVIOUS_STEP', 'Previous step'),
(73, 4, 'PREVIOUS_STEP', 'Previous step'),
(74, 1, 'CONFIRM_BOOKING', 'Confirmer la réservation'),
(74, 2, 'CONFIRM_BOOKING', 'Confirm the booking'),
(74, 3, 'CONFIRM_BOOKING', 'Confirm the booking'),
(74, 4, 'CONFIRM_BOOKING', 'Confirm the booking'),
(75, 1, 'ALSO_DISCOVER', 'Découvrez aussi'),
(75, 2, 'ALSO_DISCOVER', 'Also discover'),
(75, 3, 'ALSO_DISCOVER', 'Also discover'),
(75, 4, 'ALSO_DISCOVER', 'Also discover'),
(76, 1, 'CHECK_PEOPLE', 'Merci de vérifier le nombre de personnes pour l\'hébergement choisi.'),
(76, 2, 'CHECK_PEOPLE', 'Please verify the number of people for the chosen accommodation'),
(76, 3, 'CHECK_PEOPLE', 'Please verify the number of people for the chosen accommodation'),
(76, 4, 'CHECK_PEOPLE', 'Please verify the number of people for the chosen accommodation'),
(77, 1, 'BOOKING', 'Réservation'),
(77, 2, 'BOOKING', 'Booking'),
(77, 3, 'BOOKING', 'Booking'),
(77, 4, 'BOOKING', 'Booking'),
(78, 1, 'NIGHT', 'nuit'),
(78, 2, 'NIGHT', 'Night'),
(78, 3, 'NIGHT', 'night'),
(78, 4, 'NIGHT', 'night'),
(79, 1, 'WEEK', 'semaine'),
(79, 2, 'WEEK', 'week'),
(79, 3, 'WEEK', 'week'),
(79, 4, 'WEEK', 'week'),
(80, 1, 'EXTRA_SERVICES', 'Services supplémentaires'),
(80, 2, 'EXTRA_SERVICES', 'Extra services'),
(80, 3, 'EXTRA_SERVICES', 'Extra services'),
(80, 4, 'EXTRA_SERVICES', 'Extra services'),
(81, 1, 'BOOKING_TERMS', ''),
(81, 2, 'BOOKING_TERMS', ''),
(81, 3, 'BOOKING_TERMS', ''),
(81, 4, 'BOOKING_TERMS', ''),
(82, 1, 'NEXT_STEP', 'Étape suivante'),
(82, 2, 'NEXT_STEP', 'Continue'),
(82, 3, 'NEXT_STEP', 'Next step'),
(82, 4, 'NEXT_STEP', 'Next step'),
(83, 1, 'TOURIST_TAX', 'Taxe de séjour'),
(83, 2, 'TOURIST_TAX', 'Tourist tax'),
(83, 3, 'TOURIST_TAX', 'Tourist tax'),
(83, 4, 'TOURIST_TAX', 'Tourist tax'),
(84, 1, 'CHECK_IN', 'Arrivée'),
(84, 2, 'CHECK_IN', 'Check in'),
(84, 3, 'CHECK_IN', 'Check in'),
(84, 4, 'CHECK_IN', 'Check in'),
(85, 1, 'CHECK_OUT', 'Départ'),
(85, 2, 'CHECK_OUT', 'Check out'),
(85, 3, 'CHECK_OUT', 'Check out'),
(85, 4, 'CHECK_OUT', 'Check out'),
(86, 1, 'TOTAL', 'Total'),
(86, 2, 'TOTAL', 'Total'),
(86, 3, 'TOTAL', 'Total'),
(86, 4, 'TOTAL', 'Total'),
(87, 1, 'CAPACITY', 'Capacité'),
(87, 2, 'CAPACITY', 'Capacity'),
(87, 3, 'CAPACITY', 'Capacity'),
(87, 4, 'CAPACITY', 'Capacity'),
(88, 1, 'FACILITIES', 'Équipements'),
(88, 2, 'FACILITIES', 'Facilities'),
(88, 3, 'FACILITIES', 'Facilities'),
(88, 4, 'FACILITIES', 'Facilities'),
(89, 1, 'PRICE', 'Prix'),
(89, 2, 'PRICE', 'Price'),
(89, 3, 'PRICE', 'Price'),
(89, 4, 'PRICE', 'Price'),
(90, 1, 'MORE_DETAILS', 'Plus d\'infos'),
(90, 2, 'MORE_DETAILS', 'More details'),
(90, 3, 'MORE_DETAILS', 'More details'),
(90, 4, 'MORE_DETAILS', 'More details'),
(91, 1, 'FROM_PRICE', 'À partir de'),
(91, 2, 'FROM_PRICE', 'From'),
(91, 3, 'FROM_PRICE', 'From'),
(91, 4, 'FROM_PRICE', 'From'),
(92, 1, 'AMOUNT', 'Montant'),
(92, 2, 'AMOUNT', 'Amount'),
(92, 3, 'AMOUNT', 'Amount'),
(92, 4, 'AMOUNT', 'Amount'),
(93, 1, 'PAY', 'Payer'),
(93, 2, 'PAY', 'Check out'),
(93, 3, 'PAY', 'Check out'),
(93, 4, 'PAY', 'Check out'),
(94, 1, 'PAYMENT_PAYPAL_NOTICE', 'Cliquez sur \"Payer\" ci-dessous, vous allez être redirigé vers le site sécurisé de PayPal'),
(94, 2, 'PAYMENT_PAYPAL_NOTICE', 'Click on \"Check Out\" below, you will be redirected towards the secure site of PayPal'),
(94, 3, 'PAYMENT_PAYPAL_NOTICE', 'Click on \"Check Out\" below, you will be redirected towards the secure site of PayPal'),
(94, 4, 'PAYMENT_PAYPAL_NOTICE', 'Click on \"Check Out\" below, you will be redirected towards the secure site of PayPal'),
(95, 1, 'PAYMENT_CANCEL_NOTICE', 'Le paiement a été annulé.<br>Merci de votre visite et à bientôt.'),
(95, 2, 'PAYMENT_CANCEL_NOTICE', 'The payment has been cancelled.<br>Thank you for your visit and see you soon.'),
(95, 3, 'PAYMENT_CANCEL_NOTICE', 'The payment has been cancelled.<br>Thank you for your visit and see you soon.'),
(95, 4, 'PAYMENT_CANCEL_NOTICE', 'The payment has been cancelled.<br>Thank you for your visit and see you soon.'),
(96, 1, 'PAYMENT_SUCCESS_NOTICE', 'Le paiement a été réalisé avec succès.<br>Merci de votre visite et à bientôt !'),
(96, 2, 'PAYMENT_SUCCESS_NOTICE', 'Your payment has been successfully processed.<br>Booking confirmation mail send your email.<br>Thank you for your visit and see you soon!'),
(96, 3, 'PAYMENT_SUCCESS_NOTICE', 'Your payment has been successfully processed.<br>Thank you for your visit and see you soon!'),
(96, 4, 'PAYMENT_SUCCESS_NOTICE', 'Your payment has been successfully processed.<br>Thank you for your visit and see you soon!'),
(97, 1, 'BILLING_ADDRESS', 'Adresse de facturation'),
(97, 2, 'BILLING_ADDRESS', 'Billing Address'),
(97, 3, 'BILLING_ADDRESS', 'Billing address'),
(97, 4, 'BILLING_ADDRESS', 'Billing address'),
(98, 1, 'DOWN_PAYMENT', 'Acompte'),
(98, 2, 'DOWN_PAYMENT', 'Down payment'),
(98, 3, 'DOWN_PAYMENT', 'Down payment'),
(98, 4, 'DOWN_PAYMENT', 'Down payment'),
(99, 1, 'PAYMENT_CHECK_NOTICE', 'Merci d\'envoyer un chèque à \"Panda Multi Resorts, Neeloafaru Magu, Maldives\" d\'un montant de {amount}.<br>Votre réservation sera validée à réception du paiement.<br>Merci de votre visite et à bientôt !'),
(99, 2, 'PAYMENT_CHECK_NOTICE', '<p>Thank you for your booking.<br>Your reservation will be confirmed upon receipt of the payment.<br>Thank you for your visit and see you soon!</p>'),
(99, 3, 'PAYMENT_CHECK_NOTICE', 'Thank you for sending a check of {amount} to \"Panda Multi Resorts, Neeloafaru Magu, Maldives\".<br>Your reservation will be confirmed upon receipt of the payment.<br>Thank you for your visit and see you soon!'),
(99, 4, 'PAYMENT_CHECK_NOTICE', 'Thank you for sending a check of {amount} to \"Gupta hotels, Neeloafaru Magu, Maldives\".<br>Your reservation will be confirmed upon receipt of the payment.<br>Thank you for your visit and see you soon!'),
(100, 1, 'PAYMENT_ARRIVAL_NOTICE', 'Veuillez régler le solde de votre réservation d\'un montant de {amount} à votre arrivée.<br>Merci de votre visite et à bientôt !'),
(100, 2, 'PAYMENT_ARRIVAL_NOTICE', 'Thank you for your confirmation , that balance of {amount} for your booking on your arrival.<br>Thank you for your visit and see you soon!'),
(100, 3, 'PAYMENT_ARRIVAL_NOTICE', 'Thank you for paying the balance of {amount} for your booking on your arrival.<br>Thank you for your visit and see you soon!'),
(100, 4, 'PAYMENT_ARRIVAL_NOTICE', 'Thank you for paying the balance of {amount} for your booking on your arrival.<br>Thank you for your visit and see you soon!'),
(101, 1, 'MAX_PEOPLE', 'Pers. max'),
(101, 2, 'MAX_PEOPLE', 'Max people'),
(101, 3, 'MAX_PEOPLE', 'Max people'),
(101, 4, 'MAX_PEOPLE', 'Max people'),
(102, 1, 'VAT_AMOUNT', 'Dont TVA'),
(102, 2, 'VAT_AMOUNT', 'VAT amount'),
(102, 3, 'VAT_AMOUNT', 'VAT amount'),
(102, 4, 'VAT_AMOUNT', 'VAT amount'),
(103, 1, 'MIN_NIGHTS', 'Nuits min'),
(103, 2, 'MIN_NIGHTS', 'Min nights'),
(103, 3, 'MIN_NIGHTS', 'Min nights'),
(103, 4, 'MIN_NIGHTS', 'Min nights'),
(104, 1, 'ROOMS', 'Chambres'),
(104, 2, 'ROOMS', 'Rooms'),
(104, 3, 'ROOMS', 'Rooms'),
(104, 4, 'ROOMS', 'Rooms'),
(105, 1, 'RATINGS', 'Note(s)'),
(105, 2, 'RATINGS', 'Rating(s)'),
(105, 3, 'RATINGS', 'Rating(s)'),
(105, 4, 'RATINGS', 'Rating(s)'),
(106, 1, 'MIN_PEOPLE', 'Pers. min'),
(106, 2, 'MIN_PEOPLE', 'Min people'),
(106, 3, 'MIN_PEOPLE', 'Min people'),
(106, 4, 'MIN_PEOPLE', 'Min people'),
(107, 1, 'HOTEL', 'Hôtel'),
(107, 2, 'HOTEL', 'Hotel'),
(107, 3, 'HOTEL', 'Hotel'),
(107, 4, 'HOTEL', 'Hotel'),
(108, 1, 'MAKE_A_REQUEST', 'Faire une demande'),
(108, 2, 'MAKE_A_REQUEST', 'Make a request'),
(108, 3, 'MAKE_A_REQUEST', 'Make a request'),
(108, 4, 'MAKE_A_REQUEST', 'Make a request'),
(109, 1, 'FULLNAME', 'Nom complet'),
(109, 2, 'FULLNAME', 'Full Name'),
(109, 3, 'FULLNAME', 'Full Name'),
(109, 4, 'FULLNAME', 'Full Name'),
(110, 1, 'PASSWORD', 'Mot de passe'),
(110, 2, 'PASSWORD', 'Password'),
(110, 3, 'PASSWORD', 'Password'),
(110, 4, 'PASSWORD', 'Password'),
(111, 1, 'LOG_IN_WITH_FACEBOOK', 'Enregistrez-vous avec Facebook'),
(111, 2, 'LOG_IN_WITH_FACEBOOK', 'Log in with Facebook'),
(111, 3, 'LOG_IN_WITH_FACEBOOK', 'Log in with Facebook'),
(111, 4, 'LOG_IN_WITH_FACEBOOK', 'Log in with Facebook'),
(112, 1, 'OR', 'Ou'),
(112, 2, 'OR', 'Or'),
(112, 3, 'OR', 'Or'),
(112, 4, 'OR', 'Or'),
(113, 1, 'NEW_PASSWORD', 'Nouveau mot de passe'),
(113, 2, 'NEW_PASSWORD', 'New password'),
(113, 3, 'NEW_PASSWORD', 'New password'),
(113, 4, 'NEW_PASSWORD', 'New password'),
(114, 1, 'NEW_PASSWORD_NOTICE', 'Merci d\'entrer l\'adresse e-mail correspondant à votre compte. Un nouveau mot de passe vous sera envoyé par e-mail.'),
(114, 2, 'NEW_PASSWORD_NOTICE', 'Please enter your e-mail address corresponding to your account. A new password will be sent to you by e-mail.'),
(114, 3, 'NEW_PASSWORD_NOTICE', 'Please enter your e-mail address corresponding to your account. A new password will be sent to you by e-mail.'),
(114, 4, 'NEW_PASSWORD_NOTICE', 'Please enter your e-mail address corresponding to your account. A new password will be sent to you by e-mail.'),
(115, 1, 'USERNAME', 'Utilisateur'),
(115, 2, 'USERNAME', 'Username'),
(115, 3, 'USERNAME', 'Username'),
(115, 4, 'USERNAME', 'Username'),
(116, 1, 'PASSWORD_CONFIRM', 'Confirmer mot de passe'),
(116, 2, 'PASSWORD_CONFIRM', 'Confirm Password'),
(116, 3, 'PASSWORD_CONFIRM', 'Confirm password'),
(116, 4, 'PASSWORD_CONFIRM', 'Confirm password'),
(117, 1, 'USERNAME_EXISTS', 'Un compte existe déjà avec ce nom d\'utilisateur'),
(117, 2, 'USERNAME_EXISTS', 'An account already exists with this Mobile No.'),
(117, 3, 'USERNAME_EXISTS', 'An account already exists with this username'),
(117, 4, 'USERNAME_EXISTS', 'An account already exists with this username'),
(118, 1, 'ACCOUNT_EDIT_SUCCESS', 'Les informations de votre compte ont bien été modifiées.'),
(118, 2, 'ACCOUNT_EDIT_SUCCESS', 'Your account information have been changed.'),
(118, 3, 'ACCOUNT_EDIT_SUCCESS', 'Your account information have been changed.'),
(118, 4, 'ACCOUNT_EDIT_SUCCESS', 'Your account information have been changed.'),
(119, 1, 'ACCOUNT_EDIT_FAILURE', 'Echec de la modification des informations de compte.'),
(119, 2, 'ACCOUNT_EDIT_FAILURE', 'An error occured during the modification of the account information.'),
(119, 3, 'ACCOUNT_EDIT_FAILURE', 'An error occured during the modification of the account information.'),
(119, 4, 'ACCOUNT_EDIT_FAILURE', 'An error occured during the modification of the account information.'),
(120, 1, 'ACCOUNT_CREATE_FAILURE', 'Echec de la création du compte.'),
(120, 2, 'ACCOUNT_CREATE_FAILURE', 'Failed to create account.'),
(120, 3, 'ACCOUNT_CREATE_FAILURE', 'Failed to create account.'),
(120, 4, 'ACCOUNT_CREATE_FAILURE', 'Failed to create account.'),
(121, 1, 'PAYMENT_CHECK', 'Par chèque'),
(121, 2, 'PAYMENT_CHECK', 'By check'),
(121, 3, 'PAYMENT_CHECK', 'By check'),
(121, 4, 'PAYMENT_CHECK', 'By check'),
(122, 1, 'PAYMENT_ARRIVAL', 'A l\'arrivée'),
(122, 2, 'PAYMENT_ARRIVAL', 'On arrival'),
(122, 3, 'PAYMENT_ARRIVAL', 'On arrival'),
(122, 4, 'PAYMENT_ARRIVAL', 'On arrival'),
(123, 1, 'CHOOSE_PAYMENT', 'Choisissez un moyen de paiement'),
(123, 2, 'CHOOSE_PAYMENT', 'Choose a method of payment'),
(123, 3, 'CHOOSE_PAYMENT', 'Choose a method of payment'),
(123, 4, 'CHOOSE_PAYMENT', 'Choose a method of payment'),
(124, 1, 'PAYMENT_CREDIT_CARDS', 'Cartes de credit'),
(124, 2, 'PAYMENT_CREDIT_CARDS', 'Credit/Debit Cards'),
(124, 3, 'PAYMENT_CREDIT_CARDS', 'Credit cards'),
(124, 4, 'PAYMENT_CREDIT_CARDS', 'Credit cards'),
(125, 1, 'MAX_ADULTS', 'Adultes max'),
(125, 2, 'MAX_ADULTS', 'Max Adult'),
(125, 3, 'MAX_ADULTS', 'Max adults'),
(125, 4, 'MAX_ADULTS', 'Max adults'),
(126, 1, 'MAX_CHILDREN', 'Enfants max'),
(126, 2, 'MAX_CHILDREN', 'Max children'),
(126, 3, 'MAX_CHILDREN', 'Max children'),
(126, 4, 'MAX_CHILDREN', 'Max children'),
(127, 1, 'PAYMENT_CARDS_NOTICE', 'Cliquez sur \"Payer\" ci-dessous, vous allez être redirigé vers le site sécurisé de 2Checkout.com'),
(127, 2, 'PAYMENT_CARDS_NOTICE', 'Click on \"Check Out\" below, you will be redirected towards the secure site of 2Checkout.com'),
(127, 3, 'PAYMENT_CARDS_NOTICE', 'Click on \"Check Out\" below, you will be redirected towards the secure site of 2Checkout.com'),
(127, 4, 'PAYMENT_CARDS_NOTICE', 'Click on \"Check Out\" below, you will be redirected towards the secure site of 2Checkout.com'),
(128, 1, 'COOKIES_NOTICE', 'Les cookies nous aident à fournir une meilleure expérience utilisateur. En utilisant notre site, vous acceptez l\'utilisation de cookies.'),
(128, 2, 'COOKIES_NOTICE', 'Cookies help us provide better user experience. By using our website, you agree to the use of cookies.'),
(128, 3, 'COOKIES_NOTICE', 'Cookies help us provide better user experience. By using our website, you agree to the use of cookies.'),
(128, 4, 'COOKIES_NOTICE', 'Cookies help us provide better user experience. By using our website, you agree to the use of cookies.'),
(129, 1, 'DURATION', 'Durée'),
(129, 2, 'DURATION', 'Duration'),
(129, 3, 'DURATION', 'Duration'),
(129, 4, 'DURATION', 'Duration'),
(130, 1, 'PERSON', 'Personne'),
(130, 2, 'PERSON', 'Person'),
(130, 3, 'PERSON', 'Person'),
(130, 4, 'PERSON', 'Person'),
(131, 1, 'CHOOSE_A_DATE', 'Choisissez une date'),
(131, 2, 'CHOOSE_A_DATE', 'Choose a date'),
(131, 3, 'CHOOSE_A_DATE', 'Choose a date'),
(131, 4, 'CHOOSE_A_DATE', 'Choose a date'),
(132, 1, 'TIMESLOT', 'Horaire'),
(132, 2, 'TIMESLOT', 'Time slot'),
(132, 3, 'TIMESLOT', 'Time slot'),
(132, 4, 'TIMESLOT', 'Time slot'),
(133, 1, 'ACTIVITIES', 'Activités'),
(133, 2, 'ACTIVITIES', 'Activities'),
(133, 3, 'ACTIVITIES', 'Activities'),
(133, 4, 'ACTIVITIES', 'Activities'),
(134, 1, 'DESTINATION', 'Destination'),
(134, 2, 'DESTINATION', 'Location'),
(134, 3, 'DESTINATION', 'Destination'),
(134, 4, 'DESTINATION', 'Destination'),
(135, 1, 'NO_HOTEL_FOUND', 'Aucun hotel trouvé'),
(135, 2, 'NO_HOTEL_FOUND', 'No hotel found'),
(135, 3, 'NO_HOTEL_FOUND', 'No hotel found'),
(135, 4, 'NO_HOTEL_FOUND', 'No hotel found'),
(136, 1, 'FOR', 'pour'),
(136, 2, 'FOR', 'for'),
(136, 3, 'FOR', 'for'),
(136, 4, 'FOR', 'for'),
(137, 1, 'FIND_ACTIVITIES_AND_TOURS', 'Découvrez nos activités et excursions'),
(137, 2, 'FIND_ACTIVITIES_AND_TOURS', 'Find out our activities and tours'),
(137, 3, 'FIND_ACTIVITIES_AND_TOURS', 'Find out our activities and tours'),
(137, 4, 'FIND_ACTIVITIES_AND_TOURS', 'Find out our activities and tours'),
(138, 1, 'MINUTES', 'minute(s)'),
(138, 2, 'MINUTES', 'minute(s)'),
(138, 3, 'MINUTES', 'minute(s)'),
(138, 4, 'MINUTES', 'minute(s)'),
(139, 1, 'HOURS', 'heure(s)'),
(139, 2, 'HOURS', 'hour(s)'),
(139, 3, 'HOURS', 'hour(s)'),
(139, 4, 'HOURS', 'hour(s)'),
(140, 1, 'DAYS', 'jour(s)'),
(140, 2, 'DAYS', 'day(s)'),
(140, 3, 'DAYS', 'day(s)'),
(140, 4, 'DAYS', 'day(s)'),
(141, 1, 'WEEKS', 'semaine(s)'),
(141, 2, 'WEEKS', 'week(s)'),
(141, 3, 'WEEKS', 'week(s)'),
(141, 4, 'WEEKS', 'week(s)'),
(142, 1, 'RESULTS', 'Résultats'),
(142, 2, 'RESULTS', 'Results'),
(142, 3, 'RESULTS', 'Results'),
(142, 4, 'RESULTS', 'Results'),
(143, 1, 'BOOKING_HISTORY', 'Historique des réservations'),
(143, 2, 'BOOKING_HISTORY', 'Booking History'),
(143, 3, 'BOOKING_HISTORY', 'Booking history'),
(143, 4, 'BOOKING_HISTORY', 'Booking history'),
(144, 1, 'BOOKING_SUMMARY', 'Résumé de la réservation'),
(144, 2, 'BOOKING_SUMMARY', 'Booking summary'),
(144, 3, 'BOOKING_SUMMARY', 'Booking summary'),
(144, 4, 'BOOKING_SUMMARY', 'Booking summary'),
(145, 1, 'BOOKING_DATE', 'Date de la réservations'),
(145, 2, 'BOOKING_DATE', 'Booking date'),
(145, 3, 'BOOKING_DATE', 'Booking date'),
(145, 4, 'BOOKING_DATE', 'Booking date'),
(146, 1, 'NO_BOOKING_YET', 'Pas encore de réservation...'),
(146, 2, 'NO_BOOKING_YET', 'No booking yet...'),
(146, 3, 'NO_BOOKING_YET', 'No booking yet...'),
(146, 4, 'NO_BOOKING_YET', 'No booking yet...'),
(147, 1, 'PAYMENT', 'Paiement'),
(147, 2, 'PAYMENT', 'Payment'),
(147, 3, 'PAYMENT', 'Payment'),
(147, 4, 'PAYMENT', 'Payment'),
(148, 1, 'PAYMENT_DATE', 'Date du paiement'),
(148, 2, 'PAYMENT_DATE', 'Payment date'),
(148, 3, 'PAYMENT_DATE', 'Payment date'),
(148, 4, 'PAYMENT_DATE', 'Payment date'),
(149, 1, 'PAYMENT_METHOD', 'Méthode de paiement'),
(149, 2, 'PAYMENT_METHOD', 'Payment method'),
(149, 3, 'PAYMENT_METHOD', 'Payment method'),
(149, 4, 'PAYMENT_METHOD', 'Payment method'),
(150, 1, 'NUM_TRANSACTION', 'N°transaction'),
(150, 2, 'NUM_TRANSACTION', 'Num. transaction'),
(150, 3, 'NUM_TRANSACTION', 'Num. transaction'),
(150, 4, 'NUM_TRANSACTION', 'Num. transaction'),
(151, 1, 'STATUS', 'Statut'),
(151, 2, 'STATUS', 'Status'),
(151, 3, 'STATUS', 'Status'),
(151, 4, 'STATUS', 'Status'),
(152, 1, 'AWAITING', 'En attente'),
(152, 2, 'AWAITING', 'Awaiting'),
(152, 3, 'AWAITING', 'Awaiting'),
(152, 4, 'AWAITING', 'Awaiting'),
(153, 1, 'CANCELLED', 'Annulé'),
(153, 2, 'CANCELLED', 'Cancelled'),
(153, 3, 'CANCELLED', 'Cancelled'),
(153, 4, 'CANCELLED', 'Cancelled'),
(154, 1, 'REJECTED_PAYMENT', 'Paiement rejeté'),
(154, 2, 'REJECTED_PAYMENT', 'Rejected payment'),
(154, 3, 'REJECTED_PAYMENT', 'Rejected payment'),
(154, 4, 'REJECTED_PAYMENT', 'Rejected payment'),
(155, 1, 'PAYED', 'Payé'),
(155, 2, 'PAYED', 'Paid'),
(155, 3, 'PAYED', 'Payed'),
(155, 4, 'PAYED', 'Paid'),
(156, 1, 'INCL_TAX', 'TTC'),
(156, 2, 'INCL_TAX', 'incl. tax'),
(156, 3, 'INCL_TAX', 'incl. tax'),
(156, 4, 'INCL_TAX', 'incl. tax'),
(157, 1, 'TAGS', 'Tags'),
(157, 2, 'TAGS', 'Tags'),
(157, 3, 'TAGS', 'Tags'),
(157, 4, 'TAGS', 'Tags'),
(158, 1, 'ARCHIVES', 'Archives'),
(158, 2, 'ARCHIVES', 'Archives'),
(158, 3, 'ARCHIVES', 'Archives'),
(158, 4, 'ARCHIVES', 'Archives'),
(159, 1, 'STARS', 'Étoiles'),
(159, 2, 'STARS', 'Stars'),
(159, 3, 'STARS', 'Stars'),
(159, 4, 'STARS', 'Stars'),
(160, 1, 'HOTEL_CLASS', 'Catégorie d\'Hôtel'),
(160, 2, 'HOTEL_CLASS', 'Hotel Class'),
(160, 3, 'HOTEL_CLASS', 'Hotel Class'),
(160, 4, 'HOTEL_CLASS', 'Hotel Class'),
(161, 1, 'YOUR_BUDGET', 'Votre Budget'),
(161, 2, 'YOUR_BUDGET', 'Price'),
(161, 3, 'YOUR_BUDGET', 'Your Budget'),
(161, 4, 'YOUR_BUDGET', 'Your Budget'),
(162, 1, 'LOAD_MORE', 'Voir plus'),
(162, 2, 'LOAD_MORE', 'Load more'),
(162, 3, 'LOAD_MORE', 'Load more'),
(162, 4, 'LOAD_MORE', 'Load more'),
(163, 1, 'DO_YOU_HAVE_A_COUPON', 'Avez-vous un code promo ?'),
(163, 2, 'DO_YOU_HAVE_A_COUPON', 'Do you have a coupon?'),
(163, 3, 'DO_YOU_HAVE_A_COUPON', 'Do you have a coupon?'),
(163, 4, 'DO_YOU_HAVE_A_COUPON', 'Do you have a coupon?'),
(164, 1, 'DISCOUNT', 'Réduction'),
(164, 2, 'DISCOUNT', 'Discount'),
(164, 3, 'DISCOUNT', 'Discount'),
(164, 4, 'DISCOUNT', 'Discount'),
(165, 1, 'COUPON_CODE_SUCCESS', 'Félicitations ! Le code promo a été ajouté avec succès.'),
(165, 2, 'COUPON_CODE_SUCCESS', 'Congratulations! The coupon code has been successfully added.'),
(165, 3, 'COUPON_CODE_SUCCESS', 'Congratulations! The coupon code has been successfully added.'),
(165, 4, 'COUPON_CODE_SUCCESS', 'Congratulations! The coupon code has been successfully added.'),
(166, 1, 'ROOMS', 'chambres'),
(166, 2, 'ROOMS', 'rooms'),
(166, 3, 'ROOMS', 'rooms'),
(166, 4, 'ROOMS', 'rooms'),
(167, 1, 'ADULT', 'adulte'),
(167, 2, 'ADULT', 'Adult'),
(167, 3, 'ADULT', 'adult'),
(167, 4, 'ADULT', 'adult'),
(168, 1, 'CHILD', 'enfant'),
(168, 2, 'CHILD', 'child'),
(168, 3, 'CHILD', 'child'),
(168, 4, 'CHILD', 'child'),
(169, 1, 'ACTIVITY', 'Activité'),
(169, 2, 'ACTIVITY', 'Activity'),
(169, 3, 'ACTIVITY', 'Activity'),
(169, 4, 'ACTIVITY', 'Activity'),
(170, 1, 'DATE', 'Date'),
(170, 2, 'DATE', 'Date'),
(170, 3, 'DATE', 'Date'),
(170, 4, 'DATE', 'Date'),
(171, 1, 'QUANTITY', 'Quantité'),
(171, 2, 'QUANTITY', 'Quantity'),
(171, 3, 'QUANTITY', 'Quantity'),
(171, 4, 'QUANTITY', 'Quantity'),
(172, 1, 'SERVICE', 'Service'),
(172, 2, 'SERVICE', 'Service'),
(172, 3, 'SERVICE', 'Service'),
(172, 4, 'SERVICE', 'Service'),
(173, 1, 'BOOKING_NOTICE', '<h2>Réservez sur notre site</h2><p class=\"lead mb0\">Dépêchez-vous ! Sélectionnez vos chambres, complétez votre réservation et profitez de nos packages et offres spéciales ! <br><b>Meilleur prix garanti !</b></p>'),
(173, 2, 'BOOKING_NOTICE', '<h2>Book on our website</h2><p class=\"lead mb0\">Hurry up! Select the your rooms, complete your booking and take advantage of our special offers and packages!<br><b>Best price guarantee!</b></p>'),
(173, 3, 'BOOKING_NOTICE', '<h2>Book on our website</h2><p class=\"lead mb0\">Hurry up! Select the your rooms, complete your booking and take advantage of our special offers and packages!<br><b>Best price guarantee!</b></p>'),
(173, 4, 'BOOKING_NOTICE', '<h2>Book on our website</h2><p class=\"lead mb0\">Hurry up! Select the your rooms, complete your booking and take advantage of our special offers and packages!<br><b>Best price guarantee!</b></p>'),
(174, 1, 'NUM_ROOMS', 'Nb chambres'),
(174, 2, 'NUM_ROOMS', 'No. rooms'),
(174, 3, 'NUM_ROOMS', 'Num rooms'),
(174, 4, 'NUM_ROOMS', 'Num rooms'),
(175, 1, 'TOP_DESTINATIONS', 'Top Destinations'),
(175, 2, 'TOP_DESTINATIONS', 'Top Destinations'),
(175, 3, 'TOP_DESTINATIONS', 'Top Destinations'),
(175, 4, 'TOP_DESTINATIONS', 'Top Destinations'),
(176, 1, 'BEST_RATES_SUBTITLE', 'Meilleurs tarifs à partir de seulement {min_price}'),
(176, 2, 'BEST_RATES_SUBTITLE', 'Best rates starting at just {min_price}'),
(176, 3, 'BEST_RATES_SUBTITLE', 'Best rates starting at just {min_price}'),
(176, 4, 'BEST_RATES_SUBTITLE', 'Best rates starting at just {min_price}'),
(177, 1, 'CONTINUE_AS_GUEST', 'Continuer sans m\'enregistrer'),
(177, 2, 'CONTINUE_AS_GUEST', 'Continue as guest'),
(177, 3, 'CONTINUE_AS_GUEST', 'Continue as guest'),
(177, 4, 'CONTINUE_AS_GUEST', 'Continue as guest'),
(178, 1, 'PRIVACY_POLICY_AGREEMENT', '<small>J\'accepte que les informations recueillies par ce formulaire soient stockées dans un fichier informatisé afin de traiter ma demande.<br>Conformément au \"Réglement Général sur la Protection des Données\", vous pouvez exercer votre droit d\'accès aux données vous concernant et les faire rectifier via le formulaire de contact.</small>'),
(178, 2, 'PRIVACY_POLICY_AGREEMENT', '<small>I agree that the information collected by this form will be stored in a database in order to process my request.</small>'),
(178, 3, 'PRIVACY_POLICY_AGREEMENT', '<small>I agree that the information collected by this form will be stored in a database in order to process my request.<br>In accordance with the \"General Data Protection Regulation\", you can exercise your right to access to your data and make them rectified via the contact form.</small>'),
(178, 4, 'PRIVACY_POLICY_AGREEMENT', '<small>I agree that the information collected by this form will be stored in a database in order to process my request.<br>In accordance with the \"General Data Protection Regulation\", you can exercise your right to access to your data and make them rectified via the contact form.</small>'),
(179, 1, 'COMPLETE_YOUR_BOOKING', 'Terminez votre réservation !'),
(179, 2, 'COMPLETE_YOUR_BOOKING', 'Complete your booking!'),
(179, 3, 'COMPLETE_YOUR_BOOKING', 'Complete your booking!'),
(179, 4, 'COMPLETE_YOUR_BOOKING', 'Complete your booking!'),
(180, 1, 'CHILDREN_AGE', 'Age des enfants'),
(180, 2, 'CHILDREN_AGE', 'Age of children'),
(180, 3, 'CHILDREN_AGE', 'Age of children'),
(180, 4, 'CHILDREN_AGE', 'Age of children'),
(181, 1, 'I_AM_HOTEL_OWNER', 'Je suis propriétaire'),
(181, 2, 'I_AM_HOTEL_OWNER', 'I am a hotel owner'),
(181, 3, 'I_AM_HOTEL_OWNER', 'I am a hotel owner'),
(181, 4, 'I_AM_HOTEL_OWNER', 'I am a hotel owner'),
(182, 1, 'I_AM_TRAVELER', 'Je suis vacancier'),
(182, 2, 'I_AM_TRAVELER', 'I am a traveler'),
(182, 3, 'I_AM_TRAVELER', 'I am a traveler'),
(182, 4, 'I_AM_TRAVELER', 'I am a traveler'),
(183, 2, 'INVALID_PHONE', 'Invalid phone number'),
(183, 4, 'INVALID_PHONE', 'Invalid phone number'),
(184, 2, 'INVALID_MOBILE', 'Invalid mobile number'),
(184, 4, 'INVALID_MOBILE', 'Invalid mobile number'),
(185, 2, 'HOTEL_DETAILS', 'Hotel Details'),
(185, 4, 'HOTEL_DETAILS', 'Hotel Details'),
(186, 2, 'COUPON_CODE_ERROR', 'Sorry !! coupon code you entered is invalid or expired.'),
(186, 4, 'COUPON_CODE_ERROR', 'Sorry !! coupon code you entered is invalid or expired.'),
(187, 2, 'NOT_REGISTERED_EMAIL', 'Not a registered email address.'),
(187, 4, 'NOT_REGISTERED_EMAIL', 'Not registered email address.'),
(188, 2, 'WISHLIST_TEXT', 'Favourites'),
(188, 4, 'WISHLIST_TEXT', 'Wishlist'),
(189, 2, 'TRANSACTION_TEXT', 'Transaction'),
(189, 4, 'TRANSACTION_TEXT', 'Transaction'),
(190, 2, 'BOOKING_ID_TEXT', 'Booking ID'),
(190, 4, 'BOOKING_ID_TEXT', 'Booking ID'),
(191, 2, 'FEATURES_AMENITIES', 'Amenities'),
(191, 4, 'FEATURES_AMENITIES', 'Features/ Amenities'),
(192, 2, 'NO_TRANSACTION_YET', 'No transaction yet...'),
(192, 4, 'NO_TRANSACTION_YET', 'No transaction yet...'),
(193, 2, 'NO_WISHLIST_YET', 'No wishlist yet...'),
(193, 4, 'NO_WISHLIST_YET', 'No wishlist yet...'),
(194, 2, 'UPCOMING_BOOKING_HISTORY', 'Upcoming Booking History'),
(194, 4, 'UPCOMING_BOOKING_HISTORY', 'Upcoming Booking History'),
(195, 2, 'PAST_BOOKING_HISTORY', 'Past Booking History'),
(195, 4, 'PAST_BOOKING_HISTORY', 'Past Booking History'),
(196, 2, 'DELETE_CONFIRM3', ' Before delete a hotel/room/service/tax system will as for reschedule/cancel booking if already has booking future dates. '),
(196, 4, 'DELETE_CONFIRM3', ' Before delete a hotel/room/service/tax system will as for reschedule/cancel booking if already has booking future dates. '),
(197, 2, 'ROOM_NOT_AVAILABLE', 'Room not available.'),
(198, 2, 'MORE_BOOKING', 'More Booking'),
(199, 2, 'EMPTY_LOGIN', 'Please enter Username or E-mail'),
(200, 2, 'WALLET_BALANCE', 'Your Wallet Balance'),
(201, 2, 'WALLET', 'Wallet'),
(202, 2, 'NO_WALLET_BALANCE', 'You have no balance in wallet.'),
(203, 2, 'CHANGE_PASSWORD', 'Change Password'),
(204, 2, 'UPDATE', 'Update'),
(205, 2, 'SAVE', 'Save'),
(206, 2, 'ACCOMMODATION', 'Accommodation Type'),
(207, 2, 'DESTINATION_REQUIRED', 'Please Select a Destination !'),
(208, 2, 'OFFER_PRICE', 'Offer Price'),
(209, 2, 'DAY', 'Day'),
(210, 2, 'PER', 'Per'),
(211, 2, 'CANCEL', 'Cancel'),
(212, 2, 'GOVID', 'Govt. ID No.'),
(213, 2, 'GOVID_TYPE', 'Govt. ID'),
(214, 2, 'ONGOING_BOOKING_HISTORY', 'Ongoing Booking History');

-- --------------------------------------------------------

--
-- Table structure for table `pm_types`
--

CREATE TABLE `pm_types` (
  `id` int(11) NOT NULL,
  `id_hotel` int(11) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `checked` int(11) NOT NULL DEFAULT '0',
  `day_start` int(11) DEFAULT NULL,
  `day_end` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `pm_user`
--

CREATE TABLE `pm_user` (
  `id` int(11) NOT NULL,
  `users` text,
  `firstname` varchar(100) DEFAULT NULL,
  `lastname` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `login` varchar(50) DEFAULT NULL,
  `otp` varchar(25) DEFAULT NULL,
  `pass` varchar(100) DEFAULT NULL,
  `type` varchar(20) DEFAULT NULL,
  `add_date` int(11) DEFAULT NULL,
  `edit_date` int(11) DEFAULT NULL,
  `checked` int(11) DEFAULT '0',
  `fb_id` varchar(50) DEFAULT NULL,
  `address` varchar(250) DEFAULT NULL,
  `postcode` varchar(10) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `state` varchar(100) DEFAULT NULL,
  `company` varchar(100) DEFAULT NULL,
  `gstin` varchar(50) DEFAULT NULL,
  `govid_type` varchar(50) DEFAULT NULL,
  `govid` varchar(100) DEFAULT NULL,
  `country` varchar(100) DEFAULT NULL,
  `country_code` int(11) DEFAULT '91',
  `mobile` varchar(50) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `user_image` varchar(255) DEFAULT NULL,
  `token` varchar(50) DEFAULT NULL,
  `permissions` longtext
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pm_user`
--

INSERT INTO `pm_user` (`id`, `users`, `firstname`, `lastname`, `email`, `login`, `otp`, `pass`, `type`, `add_date`, `edit_date`, `checked`, `fb_id`, `address`, `postcode`, `city`, `state`, `company`, `gstin`, `govid_type`, `govid`, `country`, `country_code`, `mobile`, `phone`, `user_image`, `token`, `permissions`) VALUES
(1, '', 'Admin', 'Hotel', 'admin@guptahotels.com', 'admin', NULL, 'e10adc3949ba59abbe56e057f20f883e', 'administrator', 1557396800, 1569404297, 1, '', 'Chingrighata', '700105', 'Kolkata', NULL, 'Fitser', '', '', '', 'India', 0, '9475359785', '', '', '4ca6752927d6160874217acd791a961d', NULL),
(229, '1', 'Sharad', 'Bhaiya', 'sharad210@gmail.com', 'sharad210@gmail.com', '1207', 'e10adc3949ba59abbe56e057f20f883e', 'registered', 1571043562, NULL, 1, NULL, 'Arvind Tower, 242/1B, A.P.C.Road, 3rd Floor Flat - 14', '700004', 'Kolkata', NULL, '', NULL, 'ABC', 'ABCD', 'India', 0, '07827633006', NULL, '', '', NULL),
(230, '', 'Traveler', 'Traveler', 'sssss.sssss@f3g.com', '9830233006', '3999', 'e10adc3949ba59abbe56e057f20f883e', 'registered', 1571044453, 1573642921, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '9830233006', NULL, '', '', NULL),
(231, '1', 'Traveler', 'Traveler', 'sonjoy.bhadra@met-technologies.com', 'sonjoy.bhadra@met-technologies.com', '6444', 'e10adc3949ba59abbe56e057f20f883e', 'registered', 1571045549, 1591876651, 1, NULL, 'kolkata', '700004', 'kolkata', 'West Bengal ', 'met', '', '4444666', '44555', 'India', 91, '9475312345', '', '', '', NULL),
(232, NULL, 'Sharad', 'Bhaiya', 'sharad210@gmail.com', 'sharad210@gmail.com', '9231', 'e10adc3949ba59abbe56e057f20f883e', 'registered', 1571051769, 1582101149, 1, NULL, 'Arvind Tower, 242/1B, A.P.C.Road, 3rd Floor Flat - 14', '700004', 'kolkata', 'west bengal', 'MET SEZ', '', '', '', 'India', 91, '8276343006', '', 'Sharad-27.jpg', '', NULL),
(233, NULL, 'Traveler', 'Traveler', 'qa2210@yopmail.com', '9830256890', '5056', '16d7a4fca7442dda3ad93c9a726597e4', 'registered', 1571729249, 1583474552, 1, NULL, '123 testdrive', '700056', 'kolkata', NULL, 'test', '', '123456', '123456', 'India', 0, '9830256890', '', '', '', NULL),
(234, '1', 'demo', 'admin', 'demo@fitser.com', 'demoadmin', NULL, 'e10adc3949ba59abbe56e057f20f883e', 'administrator', 1572848319, 1572848319, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '8250181209', NULL, '', NULL, 'a:28:{s:7:\"booking\";a:5:{i:0;s:3:\"all\";i:1;s:3:\"add\";i:2;s:4:\"edit\";i:3;s:4:\"view\";i:4;s:6:\"upload\";}s:5:\"hotel\";a:5:{i:0;s:3:\"all\";i:1;s:3:\"add\";i:2;s:4:\"edit\";i:3;s:4:\"view\";i:4;s:6:\"upload\";}s:5:\"types\";a:5:{i:0;s:3:\"all\";i:1;s:3:\"add\";i:2;s:4:\"edit\";i:3;s:4:\"view\";i:4;s:6:\"upload\";}s:4:\"room\";a:5:{i:0;s:3:\"all\";i:1;s:3:\"add\";i:2;s:4:\"edit\";i:3;s:4:\"view\";i:4;s:6:\"upload\";}s:5:\"offer\";a:5:{i:0;s:3:\"all\";i:1;s:3:\"add\";i:2;s:4:\"edit\";i:3;s:4:\"view\";i:4;s:6:\"upload\";}s:4:\"rate\";a:5:{i:0;s:3:\"all\";i:1;s:3:\"add\";i:2;s:4:\"edit\";i:3;s:4:\"view\";i:4;s:6:\"upload\";}s:7:\"service\";a:5:{i:0;s:3:\"all\";i:1;s:3:\"add\";i:2;s:4:\"edit\";i:3;s:4:\"view\";i:4;s:6:\"upload\";}s:8:\"facility\";a:5:{i:0;s:3:\"all\";i:1;s:3:\"add\";i:2;s:4:\"edit\";i:3;s:4:\"view\";i:4;s:6:\"upload\";}s:6:\"coupon\";a:5:{i:0;s:3:\"all\";i:1;s:3:\"add\";i:2;s:4:\"edit\";i:3;s:4:\"view\";i:4;s:6:\"upload\";}s:3:\"tax\";a:5:{i:0;s:3:\"all\";i:1;s:3:\"add\";i:2;s:4:\"edit\";i:3;s:4:\"view\";i:4;s:6:\"upload\";}s:13:\"accommodation\";a:5:{i:0;s:3:\"all\";i:1;s:3:\"add\";i:2;s:4:\"edit\";i:3;s:4:\"view\";i:4;s:6:\"upload\";}s:11:\"destination\";a:5:{i:0;s:3:\"all\";i:1;s:3:\"add\";i:2;s:4:\"edit\";i:3;s:4:\"view\";i:4;s:6:\"upload\";}s:4:\"menu\";a:5:{i:0;s:3:\"all\";i:1;s:3:\"add\";i:2;s:4:\"edit\";i:3;s:4:\"view\";i:4;s:6:\"upload\";}s:7:\"article\";a:5:{i:0;s:3:\"all\";i:1;s:3:\"add\";i:2;s:4:\"edit\";i:3;s:4:\"view\";i:4;s:6:\"upload\";}s:4:\"page\";a:5:{i:0;s:3:\"all\";i:1;s:3:\"add\";i:2;s:4:\"edit\";i:3;s:4:\"view\";i:4;s:6:\"upload\";}s:6:\"widget\";a:5:{i:0;s:3:\"all\";i:1;s:3:\"add\";i:2;s:4:\"edit\";i:3;s:4:\"view\";i:4;s:6:\"upload\";}s:5:\"slide\";a:5:{i:0;s:3:\"all\";i:1;s:3:\"add\";i:2;s:4:\"edit\";i:3;s:4:\"view\";i:4;s:6:\"upload\";}s:7:\"vendors\";a:5:{i:0;s:3:\"all\";i:1;s:3:\"add\";i:2;s:4:\"edit\";i:3;s:4:\"view\";i:4;s:6:\"upload\";}s:7:\"comment\";a:5:{i:0;s:3:\"all\";i:1;s:3:\"add\";i:2;s:4:\"edit\";i:3;s:4:\"view\";i:4;s:6:\"upload\";}s:13:\"email_content\";a:5:{i:0;s:3:\"all\";i:1;s:3:\"add\";i:2;s:4:\"edit\";i:3;s:4:\"view\";i:4;s:6:\"upload\";}s:4:\"text\";a:5:{i:0;s:3:\"all\";i:1;s:3:\"add\";i:2;s:4:\"edit\";i:3;s:4:\"view\";i:4;s:6:\"upload\";}s:3:\"tag\";a:5:{i:0;s:3:\"all\";i:1;s:3:\"add\";i:2;s:4:\"edit\";i:3;s:4:\"view\";i:4;s:6:\"upload\";}s:6:\"social\";a:5:{i:0;s:3:\"all\";i:1;s:3:\"add\";i:2;s:4:\"edit\";i:3;s:4:\"view\";i:4;s:6:\"upload\";}s:4:\"lang\";a:5:{i:0;s:3:\"all\";i:1;s:3:\"add\";i:2;s:4:\"edit\";i:3;s:4:\"view\";i:4;s:6:\"upload\";}s:8:\"location\";a:5:{i:0;s:3:\"all\";i:1;s:3:\"add\";i:2;s:4:\"edit\";i:3;s:4:\"view\";i:4;s:6:\"upload\";}s:8:\"currency\";a:5:{i:0;s:3:\"all\";i:1;s:3:\"add\";i:2;s:4:\"edit\";i:3;s:4:\"view\";i:4;s:6:\"upload\";}s:4:\"user\";a:5:{i:0;s:3:\"all\";i:1;s:3:\"add\";i:2;s:4:\"edit\";i:3;s:4:\"view\";i:4;s:6:\"upload\";}s:6:\"wallet\";a:5:{i:0;s:3:\"all\";i:1;s:3:\"add\";i:2;s:4:\"edit\";i:3;s:4:\"view\";i:4;s:6:\"upload\";}}'),
(235, '1', 'demo', 'Hotel', 'demohotel@fitser.com', 'demohotel', '2459', 'e10adc3949ba59abbe56e057f20f883e', 'hotel', 1572848830, 1573643157, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '8250151209', NULL, '', '', 'a:11:{s:7:\"booking\";a:5:{i:0;s:3:\"all\";i:1;s:3:\"add\";i:2;s:4:\"edit\";i:3;s:4:\"view\";i:4;s:6:\"upload\";}s:5:\"hotel\";a:5:{i:0;s:3:\"all\";i:1;s:3:\"add\";i:2;s:4:\"edit\";i:3;s:4:\"view\";i:4;s:6:\"upload\";}s:5:\"types\";a:5:{i:0;s:3:\"all\";i:1;s:3:\"add\";i:2;s:4:\"edit\";i:3;s:4:\"view\";i:4;s:6:\"upload\";}s:4:\"room\";a:5:{i:0;s:3:\"all\";i:1;s:3:\"add\";i:2;s:4:\"edit\";i:3;s:4:\"view\";i:4;s:6:\"upload\";}s:5:\"offer\";a:5:{i:0;s:3:\"all\";i:1;s:3:\"add\";i:2;s:4:\"edit\";i:3;s:4:\"view\";i:4;s:6:\"upload\";}s:4:\"rate\";a:5:{i:0;s:3:\"all\";i:1;s:3:\"add\";i:2;s:4:\"edit\";i:3;s:4:\"view\";i:4;s:6:\"upload\";}s:7:\"service\";a:5:{i:0;s:3:\"all\";i:1;s:3:\"add\";i:2;s:4:\"edit\";i:3;s:4:\"view\";i:4;s:6:\"upload\";}s:8:\"facility\";a:5:{i:0;s:3:\"all\";i:1;s:3:\"add\";i:2;s:4:\"edit\";i:3;s:4:\"view\";i:4;s:6:\"upload\";}s:6:\"coupon\";a:5:{i:0;s:3:\"all\";i:1;s:3:\"add\";i:2;s:4:\"edit\";i:3;s:4:\"view\";i:4;s:6:\"upload\";}s:3:\"tax\";a:5:{i:0;s:3:\"all\";i:1;s:3:\"add\";i:2;s:4:\"edit\";i:3;s:4:\"view\";i:4;s:6:\"upload\";}s:4:\"user\";a:5:{i:0;s:3:\"all\";i:1;s:3:\"add\";i:2;s:4:\"edit\";i:3;s:4:\"view\";i:4;s:6:\"upload\";}}'),
(236, '1,234', 'Test', 'Traveler', 'democustomer@fitser.com', '9470059781', '7249', 'e10adc3949ba59abbe56e057f20f883e', 'registered', 1572849163, 1597239616, 1, NULL, '', '', '', NULL, '', '', '', '', 'India', 0, '9470059781', '', '', '', NULL),
(237, '1', 'Zafar', 'Ansarii', 'zafar.ansari@met-technologies.com', 'zafar.ansari@met-technologies.com', '8076', '8975ca870012be158efd8c5824b3eb7c', 'registered', 1573818429, 1577718253, 1, NULL, 'Newtown', '700156', 'Kolkata', 'West Bengal', 'test', NULL, '42343', '23432', 'India', 91, '9932783472', NULL, 'Zafar-63.jpg', '', NULL),
(238, NULL, 'Traveler', 'Traveler', 'soumya.hazra@met-technologies.com', '9932783471', '3652', '25d55ad283aa400af464c76d713c07ad', 'registered', 1573819654, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '9932783471', NULL, '', '', NULL),
(239, NULL, 'Traveler', 'Traveler', 'soumyaa.hazra@met-technologies.com', '9932783473', '', '8d969817eda63ba5eb9f49ea11f0b5ae', 'registered', 1573819923, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '9932783473', NULL, '', NULL, NULL),
(240, NULL, 'Traveler', 'Traveler', 'soumyaaaa.hazra@met-technologies.com', '9932783477', '', '25d55ad283aa400af464c76d713c07ad', 'registered', 1574084587, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '9932783477', NULL, '', NULL, NULL),
(241, NULL, 'sagar', 'nayak', 'sagarnayak@gmail.com', '8096754556778654', '9821', '9af76329c78e28c977ab1bcd1c3fe9b8', 'registered', 1574149711, NULL, 1, NULL, 'kolkata', '768330', 'olkatak', 'west bengal', 'MET SEZ', NULL, NULL, NULL, 'india', 91, '8093329914', NULL, 'sagar-49.jpg', '', NULL),
(242, NULL, 'Traveler', 'Traveler', 'test@gmail.com', '9874561230', '', 'e10adc3949ba59abbe56e057f20f883e', 'registered', 1574149855, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '9874561230', NULL, '', NULL, NULL),
(243, NULL, 'Traveler', 'Traveler', 'soumyaaaaq.hazra@met-technologies.com', '9932783478', '', '25d55ad283aa400af464c76d713c07ad', 'registered', 1574149953, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '9932783478', NULL, '', NULL, NULL),
(244, NULL, 'Traveler', 'Traveler', 'soumyaaae.hazra@met-technologies.com', '9932783479', '', '25d55ad283aa400af464c76d713c07ad', 'registered', 1574150049, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '9932783479', NULL, '', NULL, NULL),
(245, NULL, 'Traveler', 'Traveler', 'test1@gmail.com', '9874561233', '', 'e10adc3949ba59abbe56e057f20f883e', 'registered', 1574150057, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '9874561233', NULL, '', NULL, NULL),
(246, NULL, 'Traveler', 'Traveler', 'soumyaaaqq.hazra@met-technologies.com', '9932783454', '', '25d55ad283aa400af464c76d713c07ad', 'registered', 1574150167, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '9932783454', NULL, '', NULL, NULL),
(247, NULL, 'Traveler', 'Traveler', 'test2@gmail.com', '9874561232', '', 'e10adc3949ba59abbe56e057f20f883e', 'registered', 1574150463, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '9874561232', NULL, '', NULL, NULL),
(248, NULL, 'Traveler', 'Traveler', 'test3@gmail.com', '9874562232', '', 'e10adc3949ba59abbe56e057f20f883e', 'registered', 1574161154, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '9874562232', NULL, '', NULL, NULL),
(249, NULL, 'Traveler', 'Traveler', 'soumyaaaqq.hazraa@met-technologies.com', '9932783455', '', '25d55ad283aa400af464c76d713c07ad', 'registered', 1574162672, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '9932783455', NULL, '', NULL, NULL),
(250, NULL, 'Traveler', 'Traveler', 'sagardgnd@gmail.com', '809675455677865', '', 'e10adc3949ba59abbe56e057f20f883e', 'registered', 1574168633, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '809675455677865', NULL, '', NULL, NULL),
(251, NULL, 'Traveler', 'Traveler', 'sgbsfb@gmail.com', '918093328813', '', 'e10adc3949ba59abbe56e057f20f883e', 'registered', 1574172702, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '918093328813', NULL, '', NULL, NULL),
(252, NULL, 'Traveler', 'Traveler', 'test4@gmail.com', '88854631248', '', 'e10adc3949ba59abbe56e057f20f883e', 'registered', 1574226643, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '88854631248', NULL, '', NULL, NULL),
(253, NULL, 'Traveler', 'Traveler', 'sfsfb@gmail.com', '918093329914', '9378', 'e10adc3949ba59abbe56e057f20f883e', 'registered', 1574227732, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '918093329914', NULL, '', NULL, NULL),
(254, NULL, 'Traveler', 'Traveler', 'svsfb@gmail.com', '918093329945', '', 'e10adc3949ba59abbe56e057f20f883e', 'registered', 1574228151, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '918093329945', NULL, '', NULL, NULL),
(255, NULL, 'Anup', 'Bora', 'anup@gmail.com', '919876543210', '1463', 'e10adc3949ba59abbe56e057f20f883e', 'registered', 1574229958, NULL, 1, NULL, 'kolkata', '712707', 'kolkata', 'West Bengal', 'MET', NULL, NULL, NULL, 'United Arab Emirates', 91, '9007218463', NULL, 'Anup-69.jpg', '', NULL),
(256, NULL, 'Traveler', 'Traveler', 'bdbd@gmailc.om', '918093329956', '', 'e10adc3949ba59abbe56e057f20f883e', 'registered', 1574230086, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '918093329956', NULL, '', NULL, NULL),
(257, NULL, 'Traveler', 'Traveler', 'sagarnayak@gmail.com', '918093329919', '', 'e10adc3949ba59abbe56e057f20f883e', 'registered', 1574231362, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '918093329919', NULL, '', NULL, NULL),
(258, NULL, 'Traveler', 'Traveler', 'bubai@gmail.com', '919007218463', '3366', 'e10adc3949ba59abbe56e057f20f883e', 'registered', 1574243967, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '919007218463', NULL, '', NULL, NULL),
(259, NULL, 'Traveler', 'Traveler', 'test55@gmail.com', '916546544644', '', 'e10adc3949ba59abbe56e057f20f883e', 'registered', 1574258252, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '916546544644', NULL, '', NULL, NULL),
(260, NULL, 'Traveler', 'Traveler', 'soumyw.hazraa@met-technologies.com', '9932783444', '', '25d55ad283aa400af464c76d713c07ad', 'registered', 1574338620, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 91, '9932783444', NULL, '', NULL, NULL),
(261, NULL, 'Traveler', 'Traveler', 'test789@gmail.com', '918562314790', '', 'e10adc3949ba59abbe56e057f20f883e', 'registered', 1574342088, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 91, '918562314790', NULL, '', NULL, NULL),
(262, NULL, 'Traveler', 'Traveler', 'test666@gmail.com', '7894561230', '', 'e10adc3949ba59abbe56e057f20f883e', 'registered', 1574342459, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 91, '7894561230', NULL, '', NULL, NULL),
(263, NULL, 'Traveler', 'Traveler', 'test99@gmail.com', '9007218462', '5003', 'e10adc3949ba59abbe56e057f20f883e', 'registered', 1574342614, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 91, '9007218462', NULL, '', NULL, NULL),
(264, NULL, 'Traveler', 'Traveler', 'anupkumar.bora@met-technologies.com', '8910278956', '5168', 'e10adc3949ba59abbe56e057f20f883e', 'registered', 1574344182, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 91, '8910278956', NULL, '', NULL, NULL),
(265, NULL, 'Traveler', 'Traveler', 'sagar.kumarnayak@met-technologies.com', '8093329915', '', 'e10adc3949ba59abbe56e057f20f883e', 'registered', 1574400390, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 91, '8093329915', NULL, '', NULL, NULL),
(266, NULL, 'Sagar', 'Nayak', 'sagar@gmail.com', '9178065324', '9821', 'e10adc3949ba59abbe56e057f20f883e', 'registered', 1574400512, NULL, 1, NULL, 'kolkata', '123456', 'kolkata', 'west bengal', 'MET', NULL, NULL, NULL, 'india', 91, '8093329914', NULL, 'Sagar-54.jpg', NULL, NULL),
(267, '1', 'demo', 'manager', 'demomanager@met-technologies.com', 'demomanager', NULL, 'e10adc3949ba59abbe56e057f20f883e', 'manager', 1574425559, 1578904537, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '8250151204', NULL, '', NULL, 'a:6:{s:7:\"booking\";a:2:{i:0;s:3:\"add\";i:1;s:4:\"edit\";}s:5:\"hotel\";a:1:{i:0;s:6:\"upload\";}s:4:\"room\";a:4:{i:0;s:3:\"all\";i:1;s:3:\"add\";i:2;s:4:\"edit\";i:3;s:6:\"upload\";}s:5:\"offer\";a:4:{i:0;s:3:\"all\";i:1;s:3:\"add\";i:2;s:4:\"edit\";i:3;s:6:\"upload\";}s:4:\"rate\";a:4:{i:0;s:3:\"all\";i:1;s:3:\"add\";i:2;s:4:\"edit\";i:3;s:6:\"upload\";}s:3:\"faq\";a:4:{i:0;s:3:\"all\";i:1;s:3:\"add\";i:2;s:4:\"edit\";i:3;s:6:\"upload\";}}'),
(268, NULL, 'Traveler', 'Traveler', 'Sharad.bhaiya@met-technologies.com ', '1234567890', '2934', 'e10adc3949ba59abbe56e057f20f883e', 'registered', 1574678131, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 91, '1234567890', NULL, '', '', NULL),
(269, '1', 'Qa', 'Das', 'qada@yopmail.com', 'qada@yopmail.com', NULL, NULL, 'registered', NULL, NULL, 1, NULL, '', '', '', NULL, '', NULL, '147', '586', 'Select Country', NULL, '1', NULL, '', NULL, NULL),
(270, NULL, 'Traveler', 'Traveler', 'sandeep.shaw@met-technologies.com', '9007218433', '', 'e10adc3949ba59abbe56e057f20f883e', 'registered', 1574687380, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 91, '9007218433', NULL, '', NULL, NULL),
(271, NULL, 'Traveler', 'Traveler', 'anupbora93@gmail.com', '9007218465', '', 'e10adc3949ba59abbe56e057f20f883e', 'registered', 1574690091, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 91, '9007218465', NULL, '', NULL, NULL),
(272, '1', 'QA', 'pal', 'qap@yopmail.com', 'qap@yopmail.com', '4600', NULL, 'registered', NULL, 1580818083, 1, NULL, '', '', '', NULL, '', '', '752', '953', 'India', NULL, '2', '', '', '', NULL),
(273, NULL, 'Traveler', 'Traveler', 'qa2@yopmail.com', 'qa2@yopmail.com', '', '16d7a4fca7442dda3ad93c9a726597e4', 'registered', 1574947404, 1574947482, 1, NULL, '', '', '', NULL, '', '', '', '', 'India', NULL, '', '', '', NULL, NULL),
(274, '1', 'Anil', 'Das', 'ad2@yopmail.com', 'ad2@yopmail.com', '3444', NULL, 'registered', NULL, NULL, 1, NULL, 'unitech', '700156', 'kolkata', NULL, 'Get Tech', NULL, '452', '879', 'India', NULL, '1234567899', NULL, NULL, NULL, NULL),
(275, '1', 'Ravi', 'Das', 'rd2@yopmail.com', 'rd2@yopmail.com', NULL, NULL, 'registered', NULL, NULL, 1, NULL, 'unitech', '700156', 'kolkata', NULL, 'Got tech', NULL, '258', '456', 'India', NULL, '12345612345', NULL, NULL, NULL, NULL),
(276, NULL, 'Traveler', 'Traveler', 'fitserseo@gmail.com', 'fitserseo@gmail.com', '', '53e8b77457ae92fcdc89ae8203a8adc8', 'registered', 1575553137, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL),
(278, NULL, 'Traveler', 'Traveler', 'arindamdutta.in@gmail.com', '9830576623', '1932', '25d55ad283aa400af464c76d713c07ad', 'registered', 1575993554, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 91, '9830576623', NULL, NULL, '', NULL),
(279, NULL, 'Traveler', 'Traveler', 'androidtest@gmail.com', '9143389301', '', 'cc03e747a6afbbcbf8be7668acfebee5', 'registered', 1576137432, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 91, '9143389301', NULL, NULL, NULL, NULL),
(280, NULL, 'Traveler', 'Traveler', 'sagar1@gmail.com', '9178065324', '', 'e10adc3949ba59abbe56e057f20f883e', 'registered', 1576139108, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 91, '9178065324', NULL, NULL, NULL, NULL),
(281, NULL, 'Metest', 'Like', 'test.demomet@gmail.com', 'test.demomet@gmail.com', '', 'd41d8cd98f00b204e9800998ecf8427e', 'registered', 1576231944, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL),
(282, '1', 'Traveler', 'Traveler', 'qwerty@gmail.com', '8093328856', '', 'e10adc3949ba59abbe56e057f20f883e', 'registered', 1576502842, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 91, '8093328856', NULL, NULL, NULL, NULL),
(283, '1', 'Traveler', 'Traveler', 'test11@gmail.com', '9007218461', '', 'ceb6c970658f31504a901b89dcd3e461', 'registered', 1576503582, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 91, '9007218461', NULL, NULL, NULL, NULL),
(284, '1', 'Traveler', 'Traveler', 'test123@gmail.com', '9007218469', '', 'ceb6c970658f31504a901b89dcd3e461', 'registered', 1576504264, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 91, '9007218469', NULL, NULL, NULL, NULL),
(285, '1', 'Traveler', 'Traveler', 'test22@gmail.com', '9007218452', '', 'ceb6c970658f31504a901b89dcd3e461', 'registered', 1576504645, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 91, '9007218452', NULL, NULL, NULL, NULL),
(286, '1', 'Traveler', 'Traveler', 'test222@gmail.com', '9007218464', '', 'ceb6c970658f31504a901b89dcd3e461', 'registered', 1576505750, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 91, '9007218464', NULL, NULL, NULL, NULL),
(287, '1', 'Traveler', 'Traveler', 'test223@gmail.com', '9007218468', '', 'ceb6c970658f31504a901b89dcd3e461', 'registered', 1576505941, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 91, '9007218468', NULL, NULL, NULL, NULL),
(288, '1', 'Traveler', 'Traveler', 'test88@gmail.com', '9007218460', '', 'ceb6c970658f31504a901b89dcd3e461', 'registered', 1576561200, 1577718275, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 91, '9007218460', NULL, NULL, NULL, NULL),
(289, '1', 'Traveler', 'Traveler', 'test77@gmail.com', '9007218466', '', 'ceb6c970658f31504a901b89dcd3e461', 'registered', 1576562401, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 91, '9007218466', NULL, NULL, NULL, NULL),
(290, '1', 'Traveler', 'Traveler', 'test881@gmail.com', '9007218499', '', 'ceb6c970658f31504a901b89dcd3e461', 'registered', 1576562550, 1577713078, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 91, '9007218499', NULL, NULL, NULL, NULL),
(297, '1', 'Traveler', 'Traveler', 'test999@gmail.com', '9007218455', '', 'ceb6c970658f31504a901b89dcd3e461', 'registered', 1576563920, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 91, '9007218455', NULL, NULL, NULL, NULL),
(300, '1', 'Traveler', 'Traveler', 'test555@gmail.com', '9007218444', '', 'ceb6c970658f31504a901b89dcd3e461', 'registered', 1576566196, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 91, '9007218444', NULL, NULL, NULL, NULL),
(303, '1', 'Traveler', 'Traveler', 'sudhir.biswas@met-technologies.com', '9932783446', '', '25d55ad283aa400af464c76d713c07ad', 'registered', 1576566694, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 91, '9932783446', NULL, NULL, NULL, NULL),
(304, '1', 'Traveler', 'Traveler', 'qa@yopmail.com', '1234567898', '3232', '408d3f4ee256e472d01d210f217e7552', 'registered', 1576581876, 1580472984, 1, NULL, 'unitech', '700156', 'Kolkata', 'West Bengal', 'Get', '', '', '', 'India', 91, '1234567898', '', 'Traveler-92.jpg', '', NULL),
(305, '1', 'Traveler', 'Traveler', 'sfbfbsfb@gmail.com', '8093329956', '', 'e10adc3949ba59abbe56e057f20f883e', 'registered', 1576590184, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 91, '8093329956', NULL, NULL, NULL, NULL),
(306, '1', 'Traveler', 'Traveler', 'test777@gmail.com', '9007218488', '', 'ceb6c970658f31504a901b89dcd3e461', 'registered', 1576590221, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 91, '9007218488', NULL, NULL, NULL, NULL),
(307, '1', 'Traveler', 'Traveler', 'qa2@ypmail.com', '1234561234', '', 'e358efa489f58062f10dd7316b65649e', 'registered', 1576655340, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 91, '1234561234', NULL, NULL, NULL, NULL),
(308, '1', 'Traveler', 'Traveler', 'superqa@yopmail.com', '7980028645', '3380', 'c81e728d9d4c2f636f067f89cc14862c', 'registered', 1576656933, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 91, '7980028645', NULL, NULL, '', NULL),
(309, '1', 'Traveler', 'Traveler', 'test456@gmail.com', '9007218448', '', 'f925916e2754e5e03f75dd58a5733251', 'registered', 1576741617, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 91, '9007218448', NULL, NULL, NULL, NULL),
(310, '1', 'test', 'tset', 'ttr@yopmail.com', 'ttr@yopmail.com', NULL, NULL, 'registered', NULL, NULL, 1, NULL, 'unitech', '700127', 'kolkata', NULL, 'met', NULL, 'adhar', '99865', 'India', NULL, '9565686565', NULL, NULL, NULL, NULL),
(311, '1', 'QQ', 'AA', 'sanq@met-technologies.com', 'sanq@met-technologies.com', NULL, NULL, 'registered', NULL, NULL, 1, NULL, 'kastopure', '700102', 'kolkata', NULL, 'met', NULL, 'adahar', '98569', 'Select Country', NULL, '9898989898', NULL, NULL, NULL, NULL),
(312, '1', 'zaff', 'aqbal', 'amit.met@yopmail.com', 'amit.met@yopmail.com', NULL, NULL, 'registered', NULL, NULL, 1, NULL, 'kastopure', '700106', 'kolkata', NULL, 'met', NULL, '987654', '987654', 'India', NULL, '9989869585', NULL, NULL, NULL, NULL),
(313, '1', 'test', 'test', 'pqrt@yopmail.com', 'pqrt@yopmail.com', NULL, NULL, 'registered', NULL, NULL, 1, NULL, 'kestopure', '700125', 'kolkata', NULL, '', NULL, 'addar', '65756765', 'India', NULL, '8926277902', NULL, NULL, NULL, NULL),
(314, '1', 'Arijit', 'Som', 'arijit.som@met-technologies.com', 'arijit.som@met-technologies.com', NULL, 'e10adc3949ba59abbe56e057f20f883e', 'registered', NULL, NULL, 1, NULL, 'new', '700012', 'kolkata', NULL, 'met', NULL, 'adhar', '75857586653636', 'India', 91, '9163915585', NULL, NULL, NULL, NULL),
(320, '1', 'Arindam', 'biswas', 'arindam.biswas@met-technologies.com', 'arindam.biswas@met-technologies.com', NULL, NULL, 'registered', NULL, NULL, 1, NULL, 'bhabanipur', '700152', 'Kolkata', NULL, 'MET', NULL, 'Adhar', '987593', 'India', 91, '9447553556', NULL, NULL, NULL, NULL),
(321, '1', 'Kanak', 'Sarkar', 'kanak.sarkar@met-technologies.com', 'kanak.sarkar@met-technologies.com', NULL, NULL, 'registered', NULL, NULL, 1, NULL, 'Axis Mall`', '985632', 'Kolkata', NULL, 'MET', NULL, 'Adhar', '789654', 'India', 91, '8333362653', NULL, NULL, NULL, NULL),
(322, '1', 'Traveler', 'Traveler', 'arijit2.som@met-technologies.com', 'arijit2.som@met-technologies.com', '', 'e10adc3949ba59abbe56e057f20f883e', 'registered', 1576826194, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL),
(323, '1', 'sagar', 'nayak', 'snkumar.nayak@gmail.com', '8093329976', '', 'a90fd0aeba6403fb9bb567fd4e4fa533', 'registered', 1576833227, NULL, 1, NULL, 'Kestopur', '700091', 'Kolkata', 'West Bengal', 'met', NULL, NULL, NULL, 'india', 91, '8093329976', NULL, 'sagar-35.jpg', NULL, NULL),
(324, '1', 'Traveler', 'Traveler', 'yoyo@yopmail.com', '1425367889', '', '408d3f4ee256e472d01d210f217e7552', 'registered', 1577086846, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 91, '1425367889', NULL, NULL, NULL, NULL),
(325, '1', 'Traveler', 'Traveler', 'fgg@fgg.ggg', '1234567778', '', '408d3f4ee256e472d01d210f217e7552', 'registered', 1577089973, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 91, '1234567778', NULL, NULL, NULL, NULL),
(326, '1', 'test', 'test', 'test@test.com', 'test@test.com', '6444', NULL, 'registered', NULL, NULL, 1, NULL, '', '', '', NULL, '', NULL, '45ter7890', '34567', 'India', NULL, '9475359786', NULL, NULL, NULL, NULL),
(327, '1', 'tets', 'test', 'ttatt@yopmail.com', 'ttatt', NULL, 'df3e160547c82093a6ef170e98c86f65', 'registered', 1577714629, 1577714666, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '8251965636', NULL, NULL, NULL, NULL),
(328, NULL, 'Traveler', 'Traveler', 'fitser.usa@gmail.com', 'fitser.usa@gmail.com', '', '53e8b77457ae92fcdc89ae8203a8adc8', 'registered', 1577794256, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL),
(329, '1', 'Traveler', 'Traveler', 'abcd@gmail.com', '1236549870', '', '12bce374e7be15142e8172f668da00d8', 'registered', 1577972420, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 91, '1236549870', NULL, NULL, NULL, NULL),
(330, '1', 'Traveler', 'Traveler', 'pr@gmail.com', '9681295249', '9761', '3e0fd66c9b28cdaf9edcd62a13d33021', 'registered', 1578030681, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 91, '9681295249', NULL, NULL, NULL, NULL),
(331, '1', 'Traveler', 'Traveler', 'mahesh.mahalik@met-technologies.com', '7978124904', '496', '2b90f3566926ae3d995a1cb231bee349', 'registered', 1578298958, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 91, '7978124904', NULL, NULL, NULL, NULL),
(332, '1', 'Traveler', 'Traveler', 'P@gmail.com', '7777777777', '5418', 'e10adc3949ba59abbe56e057f20f883e', 'registered', 1578302566, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 91, '7777777777', NULL, NULL, NULL, NULL),
(333, '1', 'Traveler', 'Traveler', 'sandipan@gmail.com', '9830780683', '', 'f925916e2754e5e03f75dd58a5733251', 'registered', 1578314643, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 91, '9830780683', NULL, NULL, NULL, NULL),
(334, '1', 'Traveler', 'Traveler', 'test8789@gmail.com', '9007218470', '', 'f925916e2754e5e03f75dd58a5733251', 'registered', 1578315037, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 91, '9007218470', NULL, NULL, NULL, NULL),
(335, '1', 'Traveler', 'Traveler', 'Prgjhgkh@gmail.com', '7777774578', '', 'e10adc3949ba59abbe56e057f20f883e', 'registered', 1578383180, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 91, '7777774578', NULL, NULL, NULL, NULL),
(336, '1', 'Traveler', 'Traveler', 'Prit@gmail.com', '7777774570', '', 'e8ba17ae5151873654e55d6fd843281d', 'registered', 1578383712, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 91, '7777774570', NULL, NULL, NULL, NULL),
(337, '1', 'Traveler', 'Traveler', 'q@gmail.com', '2222222222', '', 'e8ba17ae5151873654e55d6fd843281d', 'registered', 1578391145, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 91, '2222222222', NULL, NULL, NULL, NULL),
(338, '1', 'Pritam', 'Dey', 'Pritam@gmail.com', '9681295248', '2934', 'e8ba17ae5151873654e55d6fd843281d', 'registered', 1578393269, NULL, 1, NULL, 'abcd street  ghybgvf grdredrdrdr hbfvtftftft ', '700063', 'kolkata', 'state', 'met', NULL, NULL, NULL, 'India', 91, '1234567890', NULL, 'Pritam-32.jpg', '', NULL),
(339, '1', 'Traveler', 'Traveler', 'dipika.ghosh@met-technologies.com', '7897897890', '', '32168089bcccf393e8b3db239770c119', 'registered', 1578404955, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 91, '7897897890', NULL, NULL, NULL, NULL),
(340, '1', 'eon', 'test', 'eon@yopmail.com', 'eon@yopmail.com', NULL, NULL, 'registered', NULL, NULL, 1, NULL, 'tetst', '700106', 'kolkata', NULL, '', NULL, 'adhar', '770025366', 'India', NULL, '9933263536', NULL, NULL, NULL, NULL),
(341, '1', 'eon', 'test', 'eon@yopmail.com', 'eon@yopmail.com', NULL, NULL, 'registered', NULL, NULL, 1, NULL, 'tetst', '700106', 'kolkata', NULL, '', NULL, 'adhar', '770025366', 'India', NULL, '9933263536', NULL, NULL, NULL, NULL),
(342, '1', 'chi', 'ca', 'chi@yopmail.com', 'chi@yopmail.com', NULL, NULL, 'registered', NULL, NULL, 1, NULL, 'newtown`', '700106', 'kolkata', NULL, 'MET', NULL, 'ADHAR', '90905623562586', 'India', NULL, '9965369334', NULL, NULL, NULL, NULL),
(343, '1', 'chi', 'ca', 'chi@yopmail.com', 'chi@yopmail.com', NULL, NULL, 'registered', NULL, NULL, 1, NULL, 'newtown`', '700106', 'kolkata', NULL, 'MET', NULL, 'ADHAR', '90905623562586', 'India', NULL, '9965369334', NULL, NULL, NULL, NULL),
(344, '1', 'traveler', 'traveler', 'tter@yopmail.com', 'tter@yopmail.com', NULL, NULL, 'registered', NULL, NULL, 1, NULL, 'test', '701015', 'kolkata', NULL, 'met', NULL, 'Adhar', '88552525656', 'India', NULL, '7965632569', NULL, NULL, NULL, NULL),
(345, '1', 'tet', 'ya', 'tet@yopmail.com', 'tet@yopmail.com', NULL, NULL, 'registered', NULL, NULL, 1, NULL, 'test', '700012', 'kolkata', NULL, 'met', NULL, 'AAA', '88899568596', 'India', NULL, '9125250012', NULL, NULL, NULL, NULL),
(346, '1', 'From', 'Bakend', 'test@mail.com', 'test@mail.com', NULL, NULL, 'registered', NULL, NULL, 1, NULL, 'Test', '789456', 'Kolkata', NULL, 'Met', NULL, 'Aadhar', '789456123214', 'India', NULL, '1212121212', NULL, NULL, NULL, NULL),
(347, '1', 'Traveler', 'Traveler', 'ios@yopmail.com', '1231231231', '', '5853a333b21bbce60f411e533c67b68f', 'registered', 1580892869, NULL, 1, NULL, 'gg', '22', 'hh', 'ss', 'cn', NULL, NULL, NULL, 'ct', 91, '1231231231', NULL, NULL, NULL, NULL),
(348, '1', 'Traveler', 'Traveler', 'dipika@gmail.com', '9638527410', '', 'e8ba17ae5151873654e55d6fd843281d', 'registered', 1581069638, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 91, '9638527410', NULL, NULL, NULL, NULL),
(349, '1', 'Traveler', 'Traveler', 'qapro@yopmail.com', '7980028646', '', 'c445fa82f7c36d10c14d7a8950550abd', 'registered', 1583910164, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 91, '7980028646', NULL, NULL, NULL, NULL),
(350, '1', 'Traveler', 'Traveler', 'pansaripalace@gmail.com', '9784880781', '', '38249b3a6a5b36e4f2e0e268a701739d', 'registered', 1591892668, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 91, '9784880781', NULL, NULL, NULL, NULL),
(351, '1', 'Traveler', 'Traveler', 'noore.firdous@gmail.com', '9899706037', '', 'c7d76459d4700320523b3913e8bba6b1', 'registered', 1594191494, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 91, '9899706037', NULL, NULL, NULL, NULL),
(352, '1', 'Protiek', 'QA', 'qa21@yopmail.com', 'qa21@yopmail.com', '', 'c4a1a08c18d19f29b1312397f9378f16', 'registered', 1596045706, 1596186809, 1, NULL, 'North Dumdum', '700061', 'Kolkata', NULL, 'Met', '', 'qagovid', 'qagovidno', 'India', NULL, '7980028622', '', NULL, NULL, NULL),
(353, '1', 'QA', 'Traveler', 'qaps@yopmail.com', '1234567891', '', 'c445fa82f7c36d10c14d7a8950550abd', 'registered', 1596438277, 1596446699, 2, NULL, 'Birati', '700061', 'Kolkata', 'West Bengal', 'Met', NULL, NULL, NULL, 'India', 91, '1234567891', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `pm_vendors`
--

CREATE TABLE `pm_vendors` (
  `id` int(11) NOT NULL,
  `lang` int(11) NOT NULL,
  `legend` text,
  `url` varchar(250) DEFAULT NULL,
  `id_page` int(11) DEFAULT NULL,
  `checked` int(11) DEFAULT '0',
  `rank` int(11) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pm_vendors`
--

INSERT INTO `pm_vendors` (`id`, `lang`, `legend`, `url`, `id_page`, `checked`, `rank`) VALUES
(4, 2, 'Make my trip', 'https://www.makemytrip.com/', 1, 1, 1),
(4, 4, 'Make my trip', 'https://www.makemytrip.com/', 1, 1, 1),
(5, 2, 'OYO Hotel', 'https://www.oyorooms.com/', 1, 1, 2),
(5, 4, 'OYO Hotel', 'https://www.oyorooms.com/', 1, 1, 2),
(6, 2, 'Hotel Trivago', 'https://www.trivago.in/', 1, 1, 3),
(6, 4, 'Hotel Trivago', 'https://www.trivago.in/', 1, 1, 3),
(7, 2, 'goibibo', 'https://www.goibibo.com/', 1, 1, 4),
(7, 4, 'goibibo', 'https://www.goibibo.com/', 1, 1, 4),
(8, 2, 'Booking.com', 'https://www.booking.com/', 1, 1, 5),
(8, 4, 'Booking.com', 'https://www.booking.com/', 1, 1, 5),
(9, 2, 'Tripadvisor', 'https://www.tripadvisor.in/', 1, 1, 6),
(9, 4, 'Tripadvisor', 'https://www.tripadvisor.in/', 1, 1, 6),
(10, 2, '', '', 1, 1, 7);

-- --------------------------------------------------------

--
-- Table structure for table `pm_vendors_file`
--

CREATE TABLE `pm_vendors_file` (
  `id` int(11) NOT NULL,
  `lang` int(11) NOT NULL,
  `id_item` int(11) NOT NULL,
  `home` int(11) DEFAULT '0',
  `checked` int(11) DEFAULT '1',
  `rank` int(11) DEFAULT '0',
  `file` varchar(250) DEFAULT NULL,
  `label` varchar(250) DEFAULT NULL,
  `type` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pm_vendors_file`
--

INSERT INTO `pm_vendors_file` (`id`, `lang`, `id_item`, `home`, `checked`, `rank`, `file`, `label`, `type`) VALUES
(11, 2, 8, NULL, 1, 5, '6c1b867dc92b79334c868ab4944bd819.png', NULL, 'image'),
(11, 4, 8, NULL, 1, 5, '6c1b867dc92b79334c868ab4944bd819.png', NULL, 'image'),
(12, 2, 9, NULL, 1, 6, '1200px-tripadvisor-logo-svg.png', NULL, 'image'),
(12, 4, 9, NULL, 1, 6, '1200px-tripadvisor-logo-svg.png', NULL, 'image'),
(13, 2, 4, NULL, 1, 7, 'make-my-trip1.png', NULL, 'image'),
(13, 4, 4, NULL, 1, 7, 'make-my-trip1.png', NULL, 'image'),
(14, 2, 5, NULL, 1, 8, 'oyo-logo1.png', NULL, 'image'),
(14, 4, 5, NULL, 1, 8, 'oyo-logo1.png', NULL, 'image'),
(15, 2, 6, NULL, 1, 9, 'trivago-logo1.png', NULL, 'image'),
(15, 4, 6, NULL, 1, 9, 'trivago-logo1.png', NULL, 'image'),
(16, 2, 7, NULL, 1, 10, 'goibibo1.png', NULL, 'image'),
(16, 4, 7, NULL, 1, 10, 'goibibo1.png', NULL, 'image'),
(18, 2, 10, NULL, 1, 11, 'treebo-logo-og-image-4.png', NULL, 'image');

-- --------------------------------------------------------

--
-- Table structure for table `pm_version_control`
--

CREATE TABLE `pm_version_control` (
  `id` int(11) NOT NULL,
  `version_code` varchar(500) NOT NULL,
  `severity` varchar(500) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `pm_version_control`
--

INSERT INTO `pm_version_control` (`id`, `version_code`, `severity`) VALUES
(1, '1', 'critical'),
(2, '2', 'critical'),
(4, '4', 'critical'),
(3, '3', 'critical'),
(5, '5', 'critical'),
(6, '6', 'critical');

-- --------------------------------------------------------

--
-- Table structure for table `pm_wallet`
--

CREATE TABLE `pm_wallet` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `checked` int(2) NOT NULL DEFAULT '1',
  `added_date` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `pm_wallet_history`
--

CREATE TABLE `pm_wallet_history` (
  `id` int(11) NOT NULL,
  `id_booking` int(11) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `type` enum('credit','debit') NOT NULL,
  `purpose` enum('cancel','booking','add_cash','bonus') NOT NULL,
  `c_date` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `pm_widget`
--

CREATE TABLE `pm_widget` (
  `id` int(11) NOT NULL,
  `lang` int(11) NOT NULL,
  `title` varchar(250) DEFAULT NULL,
  `showtitle` int(11) DEFAULT NULL,
  `pos` varchar(20) DEFAULT NULL,
  `allpages` int(11) DEFAULT NULL,
  `pages` varchar(250) DEFAULT NULL,
  `type` varchar(20) DEFAULT NULL,
  `content` longtext,
  `class` varchar(200) DEFAULT NULL,
  `checked` int(11) DEFAULT '0',
  `rank` int(11) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pm_widget`
--

INSERT INTO `pm_widget` (`id`, `lang`, `title`, `showtitle`, `pos`, `allpages`, `pages`, `type`, `content`, `class`, `checked`, `rank`) VALUES
(1, 1, 'Qui sommes-nous ?', 1, 'footer_col_1', 1, '', '', '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum eget auctor ipsum. Mauris pharetra neque a mauris commodo, at aliquam leo malesuada. Maecenas eget elit eu ligula rhoncus dapibus at non erat. In sed velit eget eros gravida consectetur varius imperdiet lectus.</p>\r\n', NULL, 2, 1),
(1, 2, 'About us', 1, 'footer_col_1', 1, '', '', '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum eget auctor ipsum. Mauris pharetra neque a mauris commodo, at aliquam leo malesuada. Maecenas eget elit eu ligula rhoncus dapibus at non erat. In sed velit eget eros gravida consectetur varius imperdiet lectus.</p>\r\n', '', 2, 1),
(1, 3, 'عنا', 1, 'footer_col_1', 1, '', '', '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum eget auctor ipsum. Mauris pharetra neque a mauris commodo, at aliquam leo malesuada. Maecenas eget elit eu ligula rhoncus dapibus at non erat. In sed velit eget eros gravida consectetur varius imperdiet lectus.</p>\r\n', NULL, 2, 1),
(1, 4, 'About us', 1, 'footer_col_1', 1, '', '', '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum eget auctor ipsum. Mauris pharetra neque a mauris commodo, at aliquam leo malesuada. Maecenas eget elit eu ligula rhoncus dapibus at non erat. In sed velit eget eros gravida consectetur varius imperdiet lectus.</p>\r\n', NULL, 2, 1),
(3, 1, 'Derniers articles', 1, 'footer_col_2', 1, '', 'latest_articles', '', '', 2, 2),
(3, 2, 'Latest articles', 1, 'footer_col_2', 1, '', 'latest_articles', '', '', 2, 2),
(3, 3, 'المقالات الأخيرة', 1, 'footer_col_2', 1, '', 'latest_articles', '', '', 2, 2),
(3, 4, 'Latest articles', 1, 'footer_col_2', 1, '', 'latest_articles', '', '', 2, 2),
(4, 1, 'Contactez-nous', 0, 'footer_col_3', 1, '', 'contact_informations', '', '', 1, 3),
(4, 2, 'Contact us', 0, 'footer_col_3', 1, '', 'contact_informations', '', '', 1, 3),
(4, 3, 'اتصل بنا', 0, 'footer_col_3', 1, '', 'contact_informations', '', '', 1, 3),
(4, 4, 'Contact us', 0, 'footer_col_3', 1, '', 'contact_informations', '', '', 1, 3),
(5, 1, 'Footer form', 0, 'footer_col_3', 1, '', 'footer_form', '', 'footer-form mt10', 0, 4),
(5, 2, 'Footer form', 0, 'footer_col_3', 1, '', 'footer_form', '', 'footer-form mt10', 0, 4),
(5, 3, 'Footer form', 0, 'footer_col_3', 1, '', 'footer_form', '', 'footer-form mt10', 0, 4),
(5, 4, 'Footer form', 0, 'footer_col_3', 1, '', 'footer_form', '', 'footer-form mt10', 0, 4),
(6, 1, 'Blog side', 0, 'right', 0, '17', 'blog_side', '', '', 1, 5),
(6, 2, 'Blog side', 0, 'right', 0, '17', 'blog_side', '', '', 1, 5),
(6, 3, 'Blog side', 0, 'right', 0, '17', 'blog_side', '', '', 1, 5),
(6, 4, 'Blog side', 0, 'right', 0, '17', 'blog_side', '', '', 1, 5);

-- --------------------------------------------------------

--
-- Table structure for table `pm_wishlist`
--

CREATE TABLE `pm_wishlist` (
  `id` int(11) NOT NULL,
  `hotel_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `date` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `pm_wishlist`
--

INSERT INTO `pm_wishlist` (`id`, `hotel_id`, `user_id`, `date`) VALUES
(4, 6, 8, 1559727063),
(18, 7, 1, 1560944493),
(16, 2, 18, 1560935739),
(12, 10, 8, 1559820401),
(20, 6, 43, 1560944509),
(21, 10, 43, 1560944655),
(29, 4, 56, 1562758276),
(23, 5, 43, 1560944683),
(24, 2, 1, 1560947198),
(25, 3, 18, 1560947745),
(27, 4, 18, 1561443310),
(35, 20, 237, 1574934911),
(177, 27, 338, 1580455880),
(61, 20, 277, 1576051031),
(51, 20, 255, 1575274224),
(194, 24, 338, 1580908135),
(137, 19, 232, 1578294473),
(138, 19, 304, 1578308460),
(62, 19, 277, 1576051034),
(85, 21, 277, 1576228122),
(139, 19, 334, 1578318893),
(140, 22, 332, 1578387688),
(178, 19, 266, 1580893584),
(174, 19, 255, 1580282395),
(202, 19, 338, 1581059107),
(197, 19, 347, 1580908561),
(198, 23, 338, 1581058798),
(205, 27, 232, 1584446506),
(207, 19, 353, 1596443452);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `pm_accommodation`
--
ALTER TABLE `pm_accommodation`
  ADD PRIMARY KEY (`id`,`lang`);

--
-- Indexes for table `pm_accommodation_file`
--
ALTER TABLE `pm_accommodation_file`
  ADD PRIMARY KEY (`id`,`lang`),
  ADD KEY `destination_file_fkey` (`id_item`,`lang`),
  ADD KEY `destination_file_lang_fkey` (`lang`);

--
-- Indexes for table `pm_activity`
--
ALTER TABLE `pm_activity`
  ADD PRIMARY KEY (`id`,`lang`),
  ADD KEY `activity_lang_fkey` (`lang`);

--
-- Indexes for table `pm_activity_file`
--
ALTER TABLE `pm_activity_file`
  ADD PRIMARY KEY (`id`,`lang`),
  ADD KEY `activity_file_fkey` (`id_item`,`lang`),
  ADD KEY `activity_file_lang_fkey` (`lang`);

--
-- Indexes for table `pm_activity_log`
--
ALTER TABLE `pm_activity_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pm_activity_session`
--
ALTER TABLE `pm_activity_session`
  ADD PRIMARY KEY (`id`),
  ADD KEY `activity_session_fkey` (`id_activity`);

--
-- Indexes for table `pm_activity_session_hour`
--
ALTER TABLE `pm_activity_session_hour`
  ADD PRIMARY KEY (`id`),
  ADD KEY `activity_session_hour_fkey` (`id_activity_session`);

--
-- Indexes for table `pm_article`
--
ALTER TABLE `pm_article`
  ADD PRIMARY KEY (`id`,`lang`),
  ADD KEY `article_lang_fkey` (`lang`),
  ADD KEY `article_page_fkey` (`id_page`,`lang`);

--
-- Indexes for table `pm_article_file`
--
ALTER TABLE `pm_article_file`
  ADD PRIMARY KEY (`id`,`lang`),
  ADD KEY `article_file_fkey` (`id_item`,`lang`),
  ADD KEY `article_file_lang_fkey` (`lang`);

--
-- Indexes for table `pm_booking`
--
ALTER TABLE `pm_booking`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pm_booking_activity`
--
ALTER TABLE `pm_booking_activity`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_activity_fkey` (`id_booking`);

--
-- Indexes for table `pm_booking_cancel`
--
ALTER TABLE `pm_booking_cancel`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pm_booking_history`
--
ALTER TABLE `pm_booking_history`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pm_booking_offer`
--
ALTER TABLE `pm_booking_offer`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_room_fkey` (`id_booking`);

--
-- Indexes for table `pm_booking_payment`
--
ALTER TABLE `pm_booking_payment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_payment_fkey` (`id_booking`);

--
-- Indexes for table `pm_booking_room`
--
ALTER TABLE `pm_booking_room`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_room_fkey` (`id_booking`);

--
-- Indexes for table `pm_booking_room_history`
--
ALTER TABLE `pm_booking_room_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_room_fkey` (`id_booking`);

--
-- Indexes for table `pm_booking_service`
--
ALTER TABLE `pm_booking_service`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_service_fkey` (`id_booking`);

--
-- Indexes for table `pm_booking_tax`
--
ALTER TABLE `pm_booking_tax`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_tax_fkey` (`id_booking`);

--
-- Indexes for table `pm_comment`
--
ALTER TABLE `pm_comment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pm_country`
--
ALTER TABLE `pm_country`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pm_coupon`
--
ALTER TABLE `pm_coupon`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pm_currency`
--
ALTER TABLE `pm_currency`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pm_destination`
--
ALTER TABLE `pm_destination`
  ADD PRIMARY KEY (`id`,`lang`);

--
-- Indexes for table `pm_destination_file`
--
ALTER TABLE `pm_destination_file`
  ADD PRIMARY KEY (`id`,`lang`),
  ADD KEY `destination_file_fkey` (`id_item`,`lang`),
  ADD KEY `destination_file_lang_fkey` (`lang`);

--
-- Indexes for table `pm_device`
--
ALTER TABLE `pm_device`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pm_email_content`
--
ALTER TABLE `pm_email_content`
  ADD PRIMARY KEY (`id`,`lang`),
  ADD KEY `email_content_lang_fkey` (`lang`);

--
-- Indexes for table `pm_facility`
--
ALTER TABLE `pm_facility`
  ADD PRIMARY KEY (`id`,`lang`),
  ADD KEY `facility_lang_fkey` (`lang`);

--
-- Indexes for table `pm_facility_file`
--
ALTER TABLE `pm_facility_file`
  ADD PRIMARY KEY (`id`,`lang`),
  ADD KEY `facility_file_fkey` (`id_item`,`lang`),
  ADD KEY `facility_file_lang_fkey` (`lang`);

--
-- Indexes for table `pm_faq`
--
ALTER TABLE `pm_faq`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pm_feedback_params`
--
ALTER TABLE `pm_feedback_params`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pm_floors`
--
ALTER TABLE `pm_floors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pm_gallery`
--
ALTER TABLE `pm_gallery`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pm_hotel`
--
ALTER TABLE `pm_hotel`
  ADD PRIMARY KEY (`id`,`lang`),
  ADD UNIQUE KEY `hotelid` (`hotelid`(10)),
  ADD KEY `hotel_lang_fkey` (`lang`);

--
-- Indexes for table `pm_hotel_cancel_policy`
--
ALTER TABLE `pm_hotel_cancel_policy`
  ADD PRIMARY KEY (`id`),
  ADD KEY `room_closing_fkey` (`id_hotel`),
  ADD KEY `id_hotel` (`id_hotel`);

--
-- Indexes for table `pm_hotel_file`
--
ALTER TABLE `pm_hotel_file`
  ADD PRIMARY KEY (`id`,`lang`),
  ADD KEY `hotel_file_fkey` (`id_item`,`lang`),
  ADD KEY `hotel_file_lang_fkey` (`lang`);

--
-- Indexes for table `pm_lang`
--
ALTER TABLE `pm_lang`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pm_lang_file`
--
ALTER TABLE `pm_lang_file`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lang_file_fkey` (`id_item`);

--
-- Indexes for table `pm_location`
--
ALTER TABLE `pm_location`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pm_media`
--
ALTER TABLE `pm_media`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pm_media_file`
--
ALTER TABLE `pm_media_file`
  ADD PRIMARY KEY (`id`),
  ADD KEY `media_file_fkey` (`id_item`);

--
-- Indexes for table `pm_menu`
--
ALTER TABLE `pm_menu`
  ADD PRIMARY KEY (`id`,`lang`),
  ADD KEY `menu_lang_fkey` (`lang`);

--
-- Indexes for table `pm_message`
--
ALTER TABLE `pm_message`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pm_notification`
--
ALTER TABLE `pm_notification`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pm_offer`
--
ALTER TABLE `pm_offer`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pm_offer_file`
--
ALTER TABLE `pm_offer_file`
  ADD PRIMARY KEY (`id`,`lang`),
  ADD KEY `page_file_fkey` (`id_item`,`lang`),
  ADD KEY `page_file_lang_fkey` (`lang`);

--
-- Indexes for table `pm_package`
--
ALTER TABLE `pm_package`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pm_packages_file`
--
ALTER TABLE `pm_packages_file`
  ADD PRIMARY KEY (`id`,`lang`),
  ADD KEY `page_file_fkey` (`id_item`,`lang`),
  ADD KEY `page_file_lang_fkey` (`lang`);

--
-- Indexes for table `pm_page`
--
ALTER TABLE `pm_page`
  ADD PRIMARY KEY (`id`,`lang`),
  ADD KEY `page_lang_fkey` (`lang`);

--
-- Indexes for table `pm_page_file`
--
ALTER TABLE `pm_page_file`
  ADD PRIMARY KEY (`id`,`lang`),
  ADD KEY `page_file_fkey` (`id_item`,`lang`),
  ADD KEY `page_file_lang_fkey` (`lang`);

--
-- Indexes for table `pm_rate`
--
ALTER TABLE `pm_rate`
  ADD PRIMARY KEY (`id`),
  ADD KEY `rate_room_fkey` (`id_room`);

--
-- Indexes for table `pm_rate_child`
--
ALTER TABLE `pm_rate_child`
  ADD PRIMARY KEY (`id`),
  ADD KEY `rate_child_fkey` (`id_rate`);

--
-- Indexes for table `pm_rate_perday`
--
ALTER TABLE `pm_rate_perday`
  ADD PRIMARY KEY (`id`),
  ADD KEY `rate_child_fkey` (`id_rate`);

--
-- Indexes for table `pm_report`
--
ALTER TABLE `pm_report`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pm_room`
--
ALTER TABLE `pm_room`
  ADD PRIMARY KEY (`id`,`lang`),
  ADD KEY `room_lang_fkey` (`lang`),
  ADD KEY `room_hotel_fkey` (`id_hotel`,`lang`);

--
-- Indexes for table `pm_room_cancel_policy`
--
ALTER TABLE `pm_room_cancel_policy`
  ADD PRIMARY KEY (`id`),
  ADD KEY `room_closing_fkey` (`id_room`);

--
-- Indexes for table `pm_room_closing`
--
ALTER TABLE `pm_room_closing`
  ADD PRIMARY KEY (`id`),
  ADD KEY `room_closing_fkey` (`id_room`);

--
-- Indexes for table `pm_room_file`
--
ALTER TABLE `pm_room_file`
  ADD PRIMARY KEY (`id`,`lang`),
  ADD KEY `room_file_fkey` (`id_item`,`lang`),
  ADD KEY `room_file_lang_fkey` (`lang`);

--
-- Indexes for table `pm_room_lock`
--
ALTER TABLE `pm_room_lock`
  ADD PRIMARY KEY (`id`),
  ADD KEY `room_lock_fkey` (`id_room`);

--
-- Indexes for table `pm_room_names`
--
ALTER TABLE `pm_room_names`
  ADD PRIMARY KEY (`id`),
  ADD KEY `room_closing_fkey` (`id_room`);

--
-- Indexes for table `pm_room_new_stock_rate`
--
ALTER TABLE `pm_room_new_stock_rate`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pm_room_slots`
--
ALTER TABLE `pm_room_slots`
  ADD PRIMARY KEY (`id`),
  ADD KEY `room_closing_fkey` (`id_room`);

--
-- Indexes for table `pm_room_slots_booking`
--
ALTER TABLE `pm_room_slots_booking`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pm_service`
--
ALTER TABLE `pm_service`
  ADD PRIMARY KEY (`id`,`lang`),
  ADD KEY `service_lang_fkey` (`lang`);

--
-- Indexes for table `pm_slide`
--
ALTER TABLE `pm_slide`
  ADD PRIMARY KEY (`id`,`lang`),
  ADD KEY `slide_lang_fkey` (`lang`),
  ADD KEY `slide_page_fkey` (`id_page`,`lang`);

--
-- Indexes for table `pm_slide_file`
--
ALTER TABLE `pm_slide_file`
  ADD PRIMARY KEY (`id`,`lang`),
  ADD KEY `slide_file_fkey` (`id_item`,`lang`),
  ADD KEY `slide_file_lang_fkey` (`lang`);

--
-- Indexes for table `pm_social`
--
ALTER TABLE `pm_social`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pm_stats_user`
--
ALTER TABLE `pm_stats_user`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pm_tag`
--
ALTER TABLE `pm_tag`
  ADD PRIMARY KEY (`id`,`lang`),
  ADD KEY `tag_lang_fkey` (`lang`);

--
-- Indexes for table `pm_tax`
--
ALTER TABLE `pm_tax`
  ADD PRIMARY KEY (`id`,`lang`),
  ADD KEY `tax_lang_fkey` (`lang`);

--
-- Indexes for table `pm_tax_slab`
--
ALTER TABLE `pm_tax_slab`
  ADD PRIMARY KEY (`id`),
  ADD KEY `rate_child_fkey` (`id_tax`);

--
-- Indexes for table `pm_testimonial`
--
ALTER TABLE `pm_testimonial`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pm_text`
--
ALTER TABLE `pm_text`
  ADD PRIMARY KEY (`id`,`lang`),
  ADD KEY `text_lang_fkey` (`lang`);

--
-- Indexes for table `pm_types`
--
ALTER TABLE `pm_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pm_user`
--
ALTER TABLE `pm_user`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pm_vendors`
--
ALTER TABLE `pm_vendors`
  ADD PRIMARY KEY (`id`,`lang`),
  ADD KEY `slide_lang_fkey` (`lang`),
  ADD KEY `slide_page_fkey` (`id_page`,`lang`);

--
-- Indexes for table `pm_vendors_file`
--
ALTER TABLE `pm_vendors_file`
  ADD PRIMARY KEY (`id`,`lang`),
  ADD KEY `slide_file_fkey` (`id_item`,`lang`),
  ADD KEY `slide_file_lang_fkey` (`lang`);

--
-- Indexes for table `pm_version_control`
--
ALTER TABLE `pm_version_control`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pm_wallet`
--
ALTER TABLE `pm_wallet`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pm_wallet_history`
--
ALTER TABLE `pm_wallet_history`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pm_widget`
--
ALTER TABLE `pm_widget`
  ADD PRIMARY KEY (`id`,`lang`),
  ADD KEY `widget_lang_fkey` (`lang`);

--
-- Indexes for table `pm_wishlist`
--
ALTER TABLE `pm_wishlist`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `pm_accommodation`
--
ALTER TABLE `pm_accommodation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `pm_accommodation_file`
--
ALTER TABLE `pm_accommodation_file`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `pm_activity`
--
ALTER TABLE `pm_activity`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pm_activity_file`
--
ALTER TABLE `pm_activity_file`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pm_activity_log`
--
ALTER TABLE `pm_activity_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=829;

--
-- AUTO_INCREMENT for table `pm_activity_session`
--
ALTER TABLE `pm_activity_session`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pm_activity_session_hour`
--
ALTER TABLE `pm_activity_session_hour`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pm_article`
--
ALTER TABLE `pm_article`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pm_article_file`
--
ALTER TABLE `pm_article_file`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pm_booking`
--
ALTER TABLE `pm_booking`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10594;

--
-- AUTO_INCREMENT for table `pm_booking_activity`
--
ALTER TABLE `pm_booking_activity`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pm_booking_cancel`
--
ALTER TABLE `pm_booking_cancel`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `pm_booking_history`
--
ALTER TABLE `pm_booking_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10068;

--
-- AUTO_INCREMENT for table `pm_booking_offer`
--
ALTER TABLE `pm_booking_offer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pm_booking_payment`
--
ALTER TABLE `pm_booking_payment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=450;

--
-- AUTO_INCREMENT for table `pm_booking_room`
--
ALTER TABLE `pm_booking_room`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=882;

--
-- AUTO_INCREMENT for table `pm_booking_room_history`
--
ALTER TABLE `pm_booking_room_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=117;

--
-- AUTO_INCREMENT for table `pm_booking_service`
--
ALTER TABLE `pm_booking_service`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pm_booking_tax`
--
ALTER TABLE `pm_booking_tax`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=593;

--
-- AUTO_INCREMENT for table `pm_comment`
--
ALTER TABLE `pm_comment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=89;

--
-- AUTO_INCREMENT for table `pm_country`
--
ALTER TABLE `pm_country`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=251;

--
-- AUTO_INCREMENT for table `pm_coupon`
--
ALTER TABLE `pm_coupon`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `pm_currency`
--
ALTER TABLE `pm_currency`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `pm_destination`
--
ALTER TABLE `pm_destination`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `pm_destination_file`
--
ALTER TABLE `pm_destination_file`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `pm_device`
--
ALTER TABLE `pm_device`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT for table `pm_email_content`
--
ALTER TABLE `pm_email_content`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `pm_facility`
--
ALTER TABLE `pm_facility`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=82;

--
-- AUTO_INCREMENT for table `pm_facility_file`
--
ALTER TABLE `pm_facility_file`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=117;

--
-- AUTO_INCREMENT for table `pm_faq`
--
ALTER TABLE `pm_faq`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `pm_feedback_params`
--
ALTER TABLE `pm_feedback_params`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `pm_floors`
--
ALTER TABLE `pm_floors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `pm_gallery`
--
ALTER TABLE `pm_gallery`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `pm_hotel`
--
ALTER TABLE `pm_hotel`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `pm_hotel_cancel_policy`
--
ALTER TABLE `pm_hotel_cancel_policy`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `pm_hotel_file`
--
ALTER TABLE `pm_hotel_file`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=208;

--
-- AUTO_INCREMENT for table `pm_lang`
--
ALTER TABLE `pm_lang`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `pm_lang_file`
--
ALTER TABLE `pm_lang_file`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `pm_location`
--
ALTER TABLE `pm_location`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `pm_media`
--
ALTER TABLE `pm_media`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pm_media_file`
--
ALTER TABLE `pm_media_file`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pm_menu`
--
ALTER TABLE `pm_menu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `pm_message`
--
ALTER TABLE `pm_message`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `pm_notification`
--
ALTER TABLE `pm_notification`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=484;

--
-- AUTO_INCREMENT for table `pm_offer`
--
ALTER TABLE `pm_offer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `pm_offer_file`
--
ALTER TABLE `pm_offer_file`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `pm_package`
--
ALTER TABLE `pm_package`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `pm_packages_file`
--
ALTER TABLE `pm_packages_file`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pm_page`
--
ALTER TABLE `pm_page`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `pm_page_file`
--
ALTER TABLE `pm_page_file`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `pm_rate`
--
ALTER TABLE `pm_rate`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

--
-- AUTO_INCREMENT for table `pm_rate_child`
--
ALTER TABLE `pm_rate_child`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pm_rate_perday`
--
ALTER TABLE `pm_rate_perday`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `pm_report`
--
ALTER TABLE `pm_report`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pm_room`
--
ALTER TABLE `pm_room`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;

--
-- AUTO_INCREMENT for table `pm_room_cancel_policy`
--
ALTER TABLE `pm_room_cancel_policy`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `pm_room_closing`
--
ALTER TABLE `pm_room_closing`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pm_room_file`
--
ALTER TABLE `pm_room_file`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=79;

--
-- AUTO_INCREMENT for table `pm_room_lock`
--
ALTER TABLE `pm_room_lock`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pm_room_names`
--
ALTER TABLE `pm_room_names`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=102;

--
-- AUTO_INCREMENT for table `pm_room_new_stock_rate`
--
ALTER TABLE `pm_room_new_stock_rate`
  MODIFY `id` bigint(21) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pm_room_slots`
--
ALTER TABLE `pm_room_slots`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `pm_room_slots_booking`
--
ALTER TABLE `pm_room_slots_booking`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pm_service`
--
ALTER TABLE `pm_service`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pm_slide`
--
ALTER TABLE `pm_slide`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `pm_slide_file`
--
ALTER TABLE `pm_slide_file`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `pm_social`
--
ALTER TABLE `pm_social`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `pm_stats_user`
--
ALTER TABLE `pm_stats_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `pm_tag`
--
ALTER TABLE `pm_tag`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `pm_tax`
--
ALTER TABLE `pm_tax`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `pm_tax_slab`
--
ALTER TABLE `pm_tax_slab`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `pm_testimonial`
--
ALTER TABLE `pm_testimonial`
  MODIFY `id` bigint(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `pm_text`
--
ALTER TABLE `pm_text`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=215;

--
-- AUTO_INCREMENT for table `pm_types`
--
ALTER TABLE `pm_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pm_user`
--
ALTER TABLE `pm_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=354;

--
-- AUTO_INCREMENT for table `pm_vendors`
--
ALTER TABLE `pm_vendors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `pm_vendors_file`
--
ALTER TABLE `pm_vendors_file`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `pm_version_control`
--
ALTER TABLE `pm_version_control`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `pm_wallet`
--
ALTER TABLE `pm_wallet`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pm_wallet_history`
--
ALTER TABLE `pm_wallet_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pm_widget`
--
ALTER TABLE `pm_widget`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `pm_wishlist`
--
ALTER TABLE `pm_wishlist`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=208;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `pm_activity`
--
ALTER TABLE `pm_activity`
  ADD CONSTRAINT `activity_lang_fkey` FOREIGN KEY (`lang`) REFERENCES `pm_lang` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `pm_activity_file`
--
ALTER TABLE `pm_activity_file`
  ADD CONSTRAINT `activity_file_fkey` FOREIGN KEY (`id_item`,`lang`) REFERENCES `pm_activity` (`id`, `lang`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `activity_file_lang_fkey` FOREIGN KEY (`lang`) REFERENCES `pm_lang` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `pm_activity_session`
--
ALTER TABLE `pm_activity_session`
  ADD CONSTRAINT `activity_session_fkey` FOREIGN KEY (`id_activity`) REFERENCES `pm_activity` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `pm_activity_session_hour`
--
ALTER TABLE `pm_activity_session_hour`
  ADD CONSTRAINT `activity_session_hour_fkey` FOREIGN KEY (`id_activity_session`) REFERENCES `pm_activity_session` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `pm_article`
--
ALTER TABLE `pm_article`
  ADD CONSTRAINT `article_lang_fkey` FOREIGN KEY (`lang`) REFERENCES `pm_lang` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `article_page_fkey` FOREIGN KEY (`id_page`,`lang`) REFERENCES `pm_page` (`id`, `lang`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `pm_article_file`
--
ALTER TABLE `pm_article_file`
  ADD CONSTRAINT `article_file_fkey` FOREIGN KEY (`id_item`,`lang`) REFERENCES `pm_article` (`id`, `lang`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `article_file_lang_fkey` FOREIGN KEY (`lang`) REFERENCES `pm_lang` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `pm_booking_activity`
--
ALTER TABLE `pm_booking_activity`
  ADD CONSTRAINT `booking_activity_fkey` FOREIGN KEY (`id_booking`) REFERENCES `pm_booking` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `pm_booking_payment`
--
ALTER TABLE `pm_booking_payment`
  ADD CONSTRAINT `booking_payment_fkey` FOREIGN KEY (`id_booking`) REFERENCES `pm_booking` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `pm_booking_room`
--
ALTER TABLE `pm_booking_room`
  ADD CONSTRAINT `booking_room_fkey` FOREIGN KEY (`id_booking`) REFERENCES `pm_booking` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `pm_booking_service`
--
ALTER TABLE `pm_booking_service`
  ADD CONSTRAINT `booking_service_fkey` FOREIGN KEY (`id_booking`) REFERENCES `pm_booking` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `pm_booking_tax`
--
ALTER TABLE `pm_booking_tax`
  ADD CONSTRAINT `booking_tax_fkey` FOREIGN KEY (`id_booking`) REFERENCES `pm_booking` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `pm_destination_file`
--
ALTER TABLE `pm_destination_file`
  ADD CONSTRAINT `destination_file_fkey` FOREIGN KEY (`id_item`,`lang`) REFERENCES `pm_destination` (`id`, `lang`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `destination_file_lang_fkey` FOREIGN KEY (`lang`) REFERENCES `pm_lang` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `pm_email_content`
--
ALTER TABLE `pm_email_content`
  ADD CONSTRAINT `email_content_lang_fkey` FOREIGN KEY (`lang`) REFERENCES `pm_lang` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `pm_facility`
--
ALTER TABLE `pm_facility`
  ADD CONSTRAINT `facility_lang_fkey` FOREIGN KEY (`lang`) REFERENCES `pm_lang` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `pm_facility_file`
--
ALTER TABLE `pm_facility_file`
  ADD CONSTRAINT `facility_file_fkey` FOREIGN KEY (`id_item`,`lang`) REFERENCES `pm_facility` (`id`, `lang`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `facility_file_lang_fkey` FOREIGN KEY (`lang`) REFERENCES `pm_lang` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `pm_hotel`
--
ALTER TABLE `pm_hotel`
  ADD CONSTRAINT `hotel_lang_fkey` FOREIGN KEY (`lang`) REFERENCES `pm_lang` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `pm_hotel_file`
--
ALTER TABLE `pm_hotel_file`
  ADD CONSTRAINT `hotel_file_fkey` FOREIGN KEY (`id_item`,`lang`) REFERENCES `pm_hotel` (`id`, `lang`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `hotel_file_lang_fkey` FOREIGN KEY (`lang`) REFERENCES `pm_lang` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `pm_lang_file`
--
ALTER TABLE `pm_lang_file`
  ADD CONSTRAINT `lang_file_fkey` FOREIGN KEY (`id_item`) REFERENCES `pm_lang` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `pm_media_file`
--
ALTER TABLE `pm_media_file`
  ADD CONSTRAINT `media_file_fkey` FOREIGN KEY (`id_item`) REFERENCES `pm_media` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `pm_menu`
--
ALTER TABLE `pm_menu`
  ADD CONSTRAINT `menu_lang_fkey` FOREIGN KEY (`lang`) REFERENCES `pm_lang` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `pm_page`
--
ALTER TABLE `pm_page`
  ADD CONSTRAINT `page_lang_fkey` FOREIGN KEY (`lang`) REFERENCES `pm_lang` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `pm_page_file`
--
ALTER TABLE `pm_page_file`
  ADD CONSTRAINT `page_file_fkey` FOREIGN KEY (`id_item`,`lang`) REFERENCES `pm_page` (`id`, `lang`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `page_file_lang_fkey` FOREIGN KEY (`lang`) REFERENCES `pm_lang` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `pm_rate`
--
ALTER TABLE `pm_rate`
  ADD CONSTRAINT `rate_room_fkey` FOREIGN KEY (`id_room`) REFERENCES `pm_room` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `pm_rate_child`
--
ALTER TABLE `pm_rate_child`
  ADD CONSTRAINT `rate_child_fkey` FOREIGN KEY (`id_rate`) REFERENCES `pm_rate` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `pm_room`
--
ALTER TABLE `pm_room`
  ADD CONSTRAINT `room_hotel_fkey` FOREIGN KEY (`id_hotel`,`lang`) REFERENCES `pm_hotel` (`id`, `lang`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `room_lang_fkey` FOREIGN KEY (`lang`) REFERENCES `pm_lang` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `pm_room_closing`
--
ALTER TABLE `pm_room_closing`
  ADD CONSTRAINT `room_closing_fkey` FOREIGN KEY (`id_room`) REFERENCES `pm_room` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `pm_room_file`
--
ALTER TABLE `pm_room_file`
  ADD CONSTRAINT `room_file_fkey` FOREIGN KEY (`id_item`,`lang`) REFERENCES `pm_room` (`id`, `lang`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `room_file_lang_fkey` FOREIGN KEY (`lang`) REFERENCES `pm_lang` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `pm_room_lock`
--
ALTER TABLE `pm_room_lock`
  ADD CONSTRAINT `room_lock_fkey` FOREIGN KEY (`id_room`) REFERENCES `pm_room` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `pm_service`
--
ALTER TABLE `pm_service`
  ADD CONSTRAINT `service_lang_fkey` FOREIGN KEY (`lang`) REFERENCES `pm_lang` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `pm_slide`
--
ALTER TABLE `pm_slide`
  ADD CONSTRAINT `slide_lang_fkey` FOREIGN KEY (`lang`) REFERENCES `pm_lang` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `slide_page_fkey` FOREIGN KEY (`id_page`,`lang`) REFERENCES `pm_page` (`id`, `lang`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `pm_slide_file`
--
ALTER TABLE `pm_slide_file`
  ADD CONSTRAINT `slide_file_fkey` FOREIGN KEY (`id_item`,`lang`) REFERENCES `pm_slide` (`id`, `lang`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `slide_file_lang_fkey` FOREIGN KEY (`lang`) REFERENCES `pm_lang` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `pm_tag`
--
ALTER TABLE `pm_tag`
  ADD CONSTRAINT `tag_lang_fkey` FOREIGN KEY (`lang`) REFERENCES `pm_lang` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `pm_tax`
--
ALTER TABLE `pm_tax`
  ADD CONSTRAINT `tax_lang_fkey` FOREIGN KEY (`lang`) REFERENCES `pm_lang` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `pm_text`
--
ALTER TABLE `pm_text`
  ADD CONSTRAINT `text_lang_fkey` FOREIGN KEY (`lang`) REFERENCES `pm_lang` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `pm_widget`
--
ALTER TABLE `pm_widget`
  ADD CONSTRAINT `widget_lang_fkey` FOREIGN KEY (`lang`) REFERENCES `pm_lang` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
