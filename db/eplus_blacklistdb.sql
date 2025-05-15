-- phpMyAdmin SQL Dump
-- version 4.8.4
-- https://www.phpmyadmin.net/
--
-- Host: mysql
-- Generation Time: Feb 18, 2020 at 02:50 PM
-- Server version: 10.3.4-MariaDB
-- PHP Version: 7.2.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `eplus_blacklistdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `sc_blacklist_index`
--

CREATE TABLE `sc_blacklist_index` (
  `id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `table_name` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `mobile_column` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `total_records` int(11) NOT NULL,
  `last_mod` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `sc_blacklist_index`
--

INSERT INTO `sc_blacklist_index` (`id`, `admin_id`, `table_name`, `mobile_column`, `total_records`, `last_mod`) VALUES
(1, 1, 'sc_ndnc_india', 'msisdn', 1263463, '2019-11-19 21:29:37'),
(3, 1, 'sc_test_BlackList', 'phones', 0, '2017-01-19 08:08:13');

-- --------------------------------------------------------

--
-- Table structure for table `sc_import_tasks`
--

CREATE TABLE `sc_import_tasks` (
  `id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `table_id` int(11) NOT NULL,
  `file_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `total_records` int(11) NOT NULL,
  `records_done` int(11) NOT NULL,
  `filetype` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `mobile_column` varchar(2) COLLATE utf8_unicode_ci NOT NULL,
  `uploaded_on` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `completed_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `status` int(11) NOT NULL COMMENT '0-not started, 1-in progress, 2-completed'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `sc_import_tasks`
--

INSERT INTO `sc_import_tasks` (`id`, `admin_id`, `table_id`, `file_name`, `total_records`, `records_done`, `filetype`, `mobile_column`, `uploaded_on`, `completed_on`, `status`) VALUES
(5, 1, 1, '1546415841-2624.csv', 1263476, 1263477, 'CSV', 'B', '2019-01-07 03:39:47', '2019-01-07 03:39:47', 2),
(7, 1, 3, '1571911826-4100.csv', 0, 0, 'CSV', '', '2019-10-24 13:40:30', '0000-00-00 00:00:00', 0);

-- --------------------------------------------------------

--
-- Table structure for table `sc_ndnc_india`
--

CREATE TABLE `sc_ndnc_india` (
  `id` int(11) NOT NULL,
  `msisdn` bigint(20) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sc_test_BlackList`
--

CREATE TABLE `sc_test_BlackList` (
  `id` int(11) NOT NULL,
  `phones` bigint(20) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `sc_test_BlackList`
--

INSERT INTO `sc_test_BlackList` (`id`, `phones`) VALUES
(1, 971502621604),
(2, 971507109441),
(3, 971506440609),
(4, 971506421418),
(5, 971508222783),
(6, 971507311025);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `sc_blacklist_index`
--
ALTER TABLE `sc_blacklist_index`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `table_name` (`table_name`),
  ADD KEY `admin_id` (`admin_id`);

--
-- Indexes for table `sc_import_tasks`
--
ALTER TABLE `sc_import_tasks`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `file_name` (`file_name`);

--
-- Indexes for table `sc_ndnc_india`
--
ALTER TABLE `sc_ndnc_india`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `msisdn` (`msisdn`);

--
-- Indexes for table `sc_test_BlackList`
--
ALTER TABLE `sc_test_BlackList`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `phones` (`phones`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `sc_blacklist_index`
--
ALTER TABLE `sc_blacklist_index`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `sc_import_tasks`
--
ALTER TABLE `sc_import_tasks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `sc_ndnc_india`
--
ALTER TABLE `sc_ndnc_india`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1263467;

--
-- AUTO_INCREMENT for table `sc_test_BlackList`
--
ALTER TABLE `sc_test_BlackList`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
