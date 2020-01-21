-- phpMyAdmin SQL Dump
-- version 4.9.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 11, 2019 at 12:05 PM
-- Server version: 10.4.8-MariaDB
-- PHP Version: 7.1.32

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tivo`
--

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

CREATE TABLE `projects` (
  `ID` int(11) NOT NULL,
  `name` varchar(70) COLLATE utf8_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `userid` int(11) NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `catid` int(11) NOT NULL,
  `customer` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `publisher` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `acronym` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `process_name` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `geofacets_process_name` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `stage` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `pdfType` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `geofacets_pdfType` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `pm` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `timestamp` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `calendar_id` varchar(1000) COLLATE utf8_unicode_ci NOT NULL,
  `calendar_color` varchar(6) COLLATE utf8_unicode_ci NOT NULL,
  `complete` int(11) NOT NULL,
  `complete_sync` int(11) NOT NULL,
  `frequency` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tivo` int(11) NOT NULL,
  `country` varchar(70) COLLATE utf8_unicode_ci DEFAULT NULL,
  `team_name` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_name` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `broadcast_day_start_time` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `projects`
--

INSERT INTO `projects` (`ID`, `name`, `image`, `userid`, `description`, `catid`, `customer`, `publisher`, `acronym`, `process_name`, `geofacets_process_name`, `stage`, `pdfType`, `geofacets_pdfType`, `pm`, `timestamp`, `status`, `calendar_id`, `calendar_color`, `complete`, `complete_sync`, `frequency`, `tivo`, `country`, `team_name`, `user_name`, `broadcast_day_start_time`) VALUES
(21, 'BVN', 'default.png', 1, '', 6, '', NULL, NULL, 'CSV', NULL, NULL, NULL, NULL, NULL, 1575523445, 0, '', '', 0, 0, NULL, 1, NULL, 'Spi International Dutch Team', NULL, '00:00:00'),
(22, 'CTY', 'default.png', 1, '', 6, '', NULL, NULL, 'CSV', NULL, NULL, NULL, NULL, NULL, 1575523445, 0, '', '', 0, 0, NULL, 1, NULL, 'Spi International UK Team', NULL, '00:00:00'),
(23, 'DCI', 'default.png', 1, '', 6, '', NULL, NULL, 'DOC', NULL, NULL, NULL, NULL, NULL, 1575523445, 0, '', '', 0, 0, NULL, 1, NULL, 'Spi International Italian Team', NULL, '00:00:00'),
(24, 'PDI', 'default.png', 1, '', 6, '', NULL, NULL, 'DOC', NULL, NULL, NULL, NULL, NULL, 1575523445, 0, '', '', 0, 0, NULL, 1, NULL, 'Spi International Italian Team', NULL, '00:00:00'),
(25, 'CIT', 'default.png', 1, '', 6, '', NULL, NULL, 'DOC', NULL, NULL, NULL, NULL, NULL, 1575523445, 0, '', '', 0, 0, NULL, 1, NULL, 'Spi International UK Team', NULL, '00:00:00'),
(26, 'CAN', 'default.png', 1, '', 6, '', NULL, NULL, 'DOCX', NULL, NULL, NULL, NULL, NULL, 1575523445, 0, '', '', 0, 0, NULL, 1, NULL, 'Spi International Dutch Team', NULL, '00:00:00'),
(27, 'KET-EU', 'default.png', 1, '', 6, '', NULL, NULL, 'DOCX', NULL, NULL, NULL, NULL, NULL, 1575523445, 0, '', '', 0, 0, NULL, 1, NULL, 'Spi International Dutch Team', NULL, '00:00:00'),
(28, 'TV1', 'default.png', 1, '', 6, '', NULL, NULL, 'DOCX', NULL, NULL, NULL, NULL, NULL, 1575523445, 0, '', '', 0, 0, NULL, 1, NULL, 'Spi International Dutch Team', NULL, '00:00:00'),
(29, 'MI0', 'default.png', 1, '', 6, '', NULL, NULL, 'DOCX', NULL, NULL, NULL, NULL, NULL, 1575523445, 0, '', '', 0, 0, NULL, 1, NULL, 'Spi International French Team', NULL, '00:00:00'),
(30, 'WEC', 'default.png', 1, '', 6, '', NULL, NULL, 'DOCX', NULL, NULL, NULL, NULL, NULL, 1575523445, 0, '', '', 0, 0, NULL, 1, NULL, 'Spi International French Team', NULL, '00:00:00'),
(31, 'L7D', 'default.png', 1, '', 6, '', NULL, NULL, 'DOCX', NULL, NULL, NULL, NULL, NULL, 1575523445, 0, '', '', 0, 0, NULL, 1, NULL, 'Spi International Italian Team', NULL, '00:00:00'),
(32, 'PPI', 'default.png', 1, '', 6, '', NULL, NULL, 'DOCX', NULL, NULL, NULL, NULL, NULL, 1575523445, 0, '', '', 0, 0, NULL, 1, NULL, 'Spi International Italian Team', NULL, '00:00:00'),
(33, 'TM7', 'default.png', 1, '', 6, '', NULL, NULL, 'DOCX', NULL, NULL, NULL, NULL, NULL, 1575523445, 0, '', '', 0, 0, NULL, 1, NULL, 'Spi International Italian Team', NULL, '00:00:00'),
(34, 'ACH', 'default.png', 1, '', 6, '', NULL, NULL, 'DOCX', NULL, NULL, NULL, NULL, NULL, 1575523446, 0, '', '', 0, 0, NULL, 1, NULL, 'Spi International UK Team', NULL, '00:00:00'),
(35, 'PCE', 'default.png', 1, '', 6, '', NULL, NULL, 'TXT', NULL, NULL, NULL, NULL, NULL, 1575523446, 0, '', '', 0, 0, NULL, 1, NULL, 'Spi International Italian Team', NULL, '00:00:00'),
(36, 'TOC-EU', 'default.png', 1, '', 6, '', NULL, NULL, 'TXT', NULL, NULL, NULL, NULL, NULL, 1575523446, 0, '', '', 0, 0, NULL, 1, NULL, 'Spi International Italian Team', NULL, '00:00:00'),
(37, 'PLQ', 'default.png', 1, '', 6, '', NULL, NULL, 'XLS', NULL, NULL, NULL, NULL, NULL, 1575523446, 0, '', '', 0, 0, NULL, 1, NULL, 'Spi International Dutch Team', NULL, '00:00:00'),
(38, 'GON', 'default.png', 1, '', 6, '', NULL, NULL, 'XLS', NULL, NULL, NULL, NULL, NULL, 1575523446, 0, '', '', 0, 0, NULL, 1, NULL, 'Spi International French Team', NULL, '00:00:00'),
(39, 'VOT', 'default.png', 1, '', 6, '', NULL, NULL, 'XLS', NULL, NULL, NULL, NULL, NULL, 1575523446, 0, '', '', 0, 0, NULL, 1, NULL, 'Spi International French Team', NULL, '00:00:00'),
(40, 'ALI', 'default.png', 1, '', 6, '', NULL, NULL, 'XLS', NULL, NULL, NULL, NULL, NULL, 1575523446, 0, '', '', 0, 0, NULL, 1, NULL, 'Spi International Italian Team', NULL, '00:00:00'),
(41, 'ITI', 'default.png', 1, '', 6, '', NULL, NULL, 'XLS', NULL, NULL, NULL, NULL, NULL, 1575523447, 0, '', '', 0, 0, NULL, 1, NULL, 'Spi International Italian Team', NULL, '00:00:00'),
(42, 'LAB', 'default.png', 1, '', 6, '', NULL, NULL, 'XLS', NULL, NULL, NULL, NULL, NULL, 1575523447, 0, '', '', 0, 0, NULL, 1, NULL, 'Spi International Italian Team', NULL, '00:00:00'),
(43, 'MA5', 'default.png', 1, '', 6, '', NULL, NULL, 'XLS', NULL, NULL, NULL, NULL, NULL, 1575523447, 0, '', '', 0, 0, NULL, 1, NULL, 'Spi International Italian Team', NULL, '00:00:00'),
(44, 'MCP', 'default.png', 1, '', 6, '', NULL, NULL, 'XLS', NULL, NULL, NULL, NULL, NULL, 1575523447, 0, '', '', 0, 0, NULL, 1, NULL, 'Spi International Italian Team', NULL, '00:00:00'),
(45, 'MDC', 'default.png', 1, '', 6, '', NULL, NULL, 'XLS', NULL, NULL, NULL, NULL, NULL, 1575523447, 0, '', '', 0, 0, NULL, 1, NULL, 'Spi International Italian Team', NULL, '00:00:00'),
(46, 'MUI', 'default.png', 1, '', 6, '', NULL, NULL, 'XLS', NULL, NULL, NULL, NULL, NULL, 1575523447, 0, '', '', 0, 0, NULL, 1, NULL, 'Spi International Italian Team', NULL, '00:00:00'),
(47, 'PA0', 'default.png', 1, '', 6, '', NULL, NULL, 'XLS', NULL, NULL, NULL, NULL, NULL, 1575523447, 0, '', '', 0, 0, NULL, 1, NULL, 'Spi International Italian Team', NULL, '00:00:00'),
(48, 'RA4', 'default.png', 1, '', 6, '', NULL, NULL, 'XLS', NULL, NULL, NULL, NULL, NULL, 1575523447, 0, '', '', 0, 0, NULL, 1, NULL, 'Spi International Italian Team', NULL, '00:00:00'),
(49, 'RAM', 'default.png', 1, '', 6, '', NULL, NULL, 'XLS', NULL, NULL, NULL, NULL, NULL, 1575523447, 0, '', '', 0, 0, NULL, 1, NULL, 'Spi International Italian Team', NULL, '00:00:00'),
(50, 'RWA', 'default.png', 1, '', 6, '', NULL, NULL, 'XLS', NULL, NULL, NULL, NULL, NULL, 1575523447, 0, '', '', 0, 0, NULL, 1, NULL, 'Spi International Italian Team', NULL, '00:00:00'),
(51, 'VH6', 'default.png', 1, '', 6, '', NULL, NULL, 'XLS', NULL, NULL, NULL, NULL, NULL, 1575523447, 0, '', '', 0, 0, NULL, 1, NULL, 'Spi International Italian Team', NULL, '00:00:00'),
(52, 'CEF', 'default.png', 1, '', 6, '', NULL, NULL, 'XLS', NULL, NULL, NULL, NULL, NULL, 1575523447, 0, '', '', 0, 0, NULL, 1, NULL, 'Spi International UK Team', NULL, '00:00:00'),
(53, 'DAG', 'default.png', 1, '', 6, '', NULL, NULL, 'XLS', NULL, NULL, NULL, NULL, NULL, 1575523447, 0, '', '', 0, 0, NULL, 1, NULL, 'Spi International UK Team', NULL, '00:00:00'),
(54, 'BBD', 'default.png', 1, '', 6, '', NULL, NULL, 'XLSX', NULL, NULL, NULL, NULL, NULL, 1575523448, 0, '', '', 0, 0, NULL, 1, NULL, 'Spi International Dutch Team', NULL, '00:00:00'),
(55, 'PBL', 'default.png', 1, '', 6, '', NULL, NULL, 'XLSX', NULL, NULL, NULL, NULL, NULL, 1575523448, 0, '', '', 0, 0, NULL, 1, NULL, 'Spi International Dutch Team', NULL, '00:00:00'),
(56, 'LA7-DISH', 'default.png', 1, '', 6, '', NULL, NULL, 'XLSX', NULL, NULL, NULL, NULL, NULL, 1575523448, 0, '', '', 0, 0, NULL, 1, NULL, 'Spi International Italian Team', NULL, '00:00:00'),
(57, 'ZTI', 'default.png', 1, '', 6, '', NULL, NULL, 'XLSX', NULL, NULL, NULL, NULL, NULL, 1575523448, 0, '', '', 0, 0, NULL, 1, NULL, 'Spi International Italian Team', NULL, '00:00:00'),
(58, 'AII', 'default.png', 1, '', 6, '', NULL, NULL, 'XLSX', NULL, NULL, NULL, NULL, NULL, 1575523448, 0, '', '', 0, 0, NULL, 1, NULL, 'Spi International UK Team', NULL, '00:00:00'),
(59, 'B4M', 'default.png', 1, '', 6, '', NULL, NULL, 'XLSX', NULL, NULL, NULL, NULL, NULL, 1575523448, 0, '', '', 0, 0, NULL, 1, NULL, 'Spi International UK Team', NULL, '00:00:00'),
(60, 'TP5', 'default.png', 1, '', 6, '', NULL, NULL, 'XLSX', NULL, NULL, NULL, NULL, NULL, 1575523448, 0, '', '', 0, 0, NULL, 1, NULL, 'Spi International UK Team', NULL, '00:00:00'),
(61, 'S1T', 'default.png', 1, '', 6, '', NULL, NULL, 'XML', NULL, NULL, NULL, NULL, NULL, 1575523448, 0, '', '', 0, 0, NULL, 1, NULL, 'Spi International Dutch Team', NULL, '00:00:00'),
(62, 'VAW', 'default.png', 1, '', 6, '', NULL, NULL, 'XML', NULL, NULL, NULL, NULL, NULL, 1575523448, 0, '', '', 0, 0, NULL, 1, NULL, 'Spi International Dutch Team', NULL, '00:00:00'),
(63, 'VIJ', 'default.png', 1, '', 6, '', NULL, NULL, 'XML', NULL, NULL, NULL, NULL, NULL, 1575523448, 0, '', '', 0, 0, NULL, 1, NULL, 'Spi International Dutch Team', NULL, '00:00:00'),
(64, 'VT4', 'default.png', 1, '', 6, '', NULL, NULL, 'XML', NULL, NULL, NULL, NULL, NULL, 1575523448, 0, '', '', 0, 0, NULL, 1, NULL, 'Spi International Dutch Team', NULL, '00:00:00'),
(65, 'ZES', 'default.png', 1, '', 6, '', NULL, NULL, 'XML', NULL, NULL, NULL, NULL, NULL, 1575523448, 0, '', '', 0, 0, NULL, 1, NULL, 'Spi International Dutch Team', NULL, '00:00:00'),
(66, 'ARN', 'default.png', 1, '', 6, '', NULL, NULL, 'XML', NULL, NULL, NULL, NULL, NULL, 1575523448, 0, '', '', 0, 0, NULL, 1, NULL, 'Spi International Dutch Team', NULL, '00:00:00'),
(67, '6IH', 'default.png', 1, '', 6, '', NULL, NULL, 'XML', NULL, NULL, NULL, NULL, NULL, 1575523448, 0, '', '', 0, 0, NULL, 1, NULL, 'Spi International French Team', NULL, '00:00:00'),
(68, 'M6M', 'default.png', 1, '', 6, '', NULL, NULL, 'XML', NULL, NULL, NULL, NULL, NULL, 1575523448, 0, '', '', 0, 0, NULL, 1, NULL, 'Spi International French Team', NULL, '00:00:00'),
(69, 'M6T', 'default.png', 1, '', 6, '', NULL, NULL, 'XML', NULL, NULL, NULL, NULL, NULL, 1575523448, 0, '', '', 0, 0, NULL, 1, NULL, 'Spi International French Team', NULL, '00:00:00'),
(70, 'PA1', 'default.png', 1, '', 6, '', NULL, NULL, 'XML', NULL, NULL, NULL, NULL, NULL, 1575523448, 0, '', '', 0, 0, NULL, 1, NULL, 'Spi International French Team', NULL, '00:00:00'),
(71, 'SRC-EU', 'default.png', 1, '', 6, '', NULL, NULL, 'XML', NULL, NULL, NULL, NULL, NULL, 1575523448, 0, '', '', 0, 0, NULL, 1, NULL, 'Spi International French Team', NULL, '00:00:00'),
(72, 'TEV', 'default.png', 1, '', 6, '', NULL, NULL, 'XML', NULL, NULL, NULL, NULL, NULL, 1575523448, 0, '', '', 0, 0, NULL, 1, NULL, 'Spi International French Team', NULL, '00:00:00'),
(73, 'BOK', 'default.png', 1, '', 6, '', NULL, NULL, 'XML', NULL, NULL, NULL, NULL, NULL, 1575523448, 0, '', '', 0, 0, NULL, 1, NULL, 'Spi International UK Team', NULL, '00:00:00'),
(74, 'DOD', 'default.png', 1, '', 6, '', NULL, NULL, 'XML', NULL, NULL, NULL, NULL, NULL, 1575523448, 0, '', '', 0, 0, NULL, 1, NULL, 'Spi International Dutch Team', NULL, '00:00:00'),
(75, 'ND3', 'default.png', 1, '', 6, '', NULL, NULL, 'CSV', NULL, NULL, NULL, NULL, NULL, 1575523448, 0, '', '', 0, 0, NULL, 1, NULL, 'Spi International Dutch Team', NULL, '00:00:00'),
(76, 'ND2', 'default.png', 1, '', 6, '', NULL, NULL, 'CSV', NULL, NULL, NULL, NULL, NULL, 1575523448, 0, '', '', 0, 0, NULL, 1, NULL, 'Spi International Dutch Team', NULL, '00:00:00'),
(77, 'ND1', 'default.png', 1, '', 6, '', NULL, NULL, 'CSV', NULL, NULL, NULL, NULL, NULL, 1575523448, 0, '', '', 0, 0, NULL, 1, NULL, 'Spi International Dutch Team', NULL, '00:00:00'),
(78, 'ECA', 'default.png', 1, '', 6, '', NULL, NULL, 'XLS', NULL, NULL, NULL, NULL, NULL, 1575523448, 0, '', '', 0, 0, NULL, 1, NULL, 'Spi International Dutch Team', NULL, '00:00:00'),
(79, 'RT8', 'default.png', 1, '', 6, '', NULL, NULL, 'XML', NULL, NULL, NULL, NULL, NULL, 1575523448, 0, '', '', 0, 0, NULL, 1, NULL, 'Spi International Dutch Team', NULL, '00:00:00'),
(80, 'RT7', 'default.png', 1, '', 6, '', NULL, NULL, 'XML', NULL, NULL, NULL, NULL, NULL, 1575523448, 0, '', '', 0, 0, NULL, 1, NULL, 'Spi International Dutch Team', NULL, '00:00:00'),
(81, 'RT5', 'default.png', 1, '', 6, '', NULL, NULL, 'XML', NULL, NULL, NULL, NULL, NULL, 1575523448, 0, '', '', 0, 0, NULL, 1, NULL, 'Spi International Dutch Team', NULL, '00:00:00'),
(82, 'RT4', 'default.png', 1, '', 6, '', NULL, NULL, 'XML', NULL, NULL, NULL, NULL, NULL, 1575523448, 0, '', '', 0, 0, NULL, 1, NULL, 'Spi International Dutch Team', NULL, '00:00:00'),
(83, 'HCN', 'default.png', 1, '', 6, '', NULL, NULL, 'XML', NULL, NULL, NULL, NULL, NULL, 1575523448, 0, '', '', 0, 0, NULL, 1, NULL, 'Spi International Dutch Team', NULL, '00:00:00'),
(84, 'DSB', 'default.png', 1, '', 6, '', NULL, NULL, 'XML', NULL, NULL, NULL, NULL, NULL, 1575523448, 0, '', '', 0, 0, NULL, 1, NULL, 'Spi International Dutch Team', NULL, '00:00:00'),
(85, 'NWN', 'default.png', 1, '', 6, '', NULL, NULL, 'XML', NULL, NULL, NULL, NULL, NULL, 1575523448, 0, '', '', 0, 0, NULL, 1, NULL, 'Spi International Dutch Team', NULL, '00:00:00'),
(86, 'NGD', 'default.png', 1, '', 6, '', NULL, NULL, 'XML', NULL, NULL, NULL, NULL, NULL, 1575523448, 0, '', '', 0, 0, NULL, 1, NULL, 'Spi International Dutch Team', NULL, '00:00:00'),
(87, 'BVA', 'default.png', 1, '', 6, '', NULL, NULL, 'XML', NULL, NULL, NULL, NULL, NULL, 1575523448, 0, '', '', 0, 0, NULL, 1, NULL, 'Spi International Dutch Team', NULL, '00:00:00'),
(88, 'NJN-EU', 'NJN-EU1.cfg', 38, '', 6, '', NULL, '', '', '', '', '', '', '', 1575959886, 0, '', '', 0, 0, '', 1, NULL, 'Spi International Dutch Team', '', '00:00:00'),
(89, 'AEN', 'default.png', 1, '', 6, '', NULL, NULL, 'XML', NULL, NULL, NULL, NULL, NULL, 1575523448, 0, '', '', 0, 0, NULL, 1, NULL, 'Spi International Dutch Team', NULL, '00:00:00'),
(90, 'BFF', 'default.png', 1, '', 6, '', NULL, NULL, 'XML', NULL, NULL, NULL, NULL, NULL, 1575523448, 0, '', '', 0, 0, NULL, 1, NULL, 'Spi International Dutch Team', NULL, '00:00:00'),
(91, 'NIV', 'default.png', 1, '', 6, '', NULL, NULL, 'XML', NULL, NULL, NULL, NULL, NULL, 1575523448, 0, '', '', 0, 0, NULL, 1, NULL, 'Spi International Dutch Team', NULL, '00:00:00'),
(92, 'NTT', 'default.png', 1, '', 6, '', NULL, NULL, 'XLSX', NULL, NULL, NULL, NULL, NULL, 1575523448, 0, '', '', 0, 0, NULL, 1, NULL, 'Spi International Dutch Team', NULL, '00:00:00'),
(93, 'ATV', 'default.png', 1, '', 6, '', NULL, NULL, 'XML', NULL, NULL, NULL, NULL, NULL, 1575523448, 0, '', '', 0, 0, NULL, 1, NULL, 'Spi International Dutch Team', NULL, '00:00:00'),
(94, 'LIM', 'default.png', 1, '', 6, '', NULL, NULL, 'XML', NULL, NULL, NULL, NULL, NULL, 1575523448, 0, '', '', 0, 0, NULL, 1, NULL, 'Spi International Dutch Team', NULL, '00:00:00'),
(95, 'BOD-EU', 'default.png', 1, '', 6, '', NULL, NULL, 'XLS', NULL, NULL, NULL, NULL, NULL, 1575523448, 0, '', '', 0, 0, NULL, 1, NULL, 'Spi International Dutch Team', NULL, '00:00:00'),
(96, 'CNL', 'default.png', 1, '', 6, '', NULL, NULL, 'XLSX', NULL, NULL, NULL, NULL, NULL, 1575523448, 0, '', '', 0, 0, NULL, 1, NULL, 'Spi International Dutch Team', NULL, '00:00:00'),
(97, '24f', 'NJN-EU.cfg', 1, '', 6, '24706', NULL, '', 'xlsx', '', '', '', '', '', 1575634160, 0, '', '', 0, 0, '', 1, NULL, 'Spi International French Team', 'Madhi', '06:00:00'),
(98, 'ghyt', 'NJN1.cfg', 1, '', 9, '345', NULL, '', 'csv', '', '', '', '', '', 1575631286, 0, '', '', 0, 0, '', 1, NULL, 'Spi International Dutch Team', 'mpanchabekasan', '05:03:00'),
(99, '6546546', 'NJN2.cfg', 1, '', 6, '564', NULL, '', 'xlsx', '', '', '', '', '', 1575631752, 0, '', '', 0, 0, '', 1, NULL, 'Spi International Dutch Team', 'Anitha', '05:05:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `projects`
--
ALTER TABLE `projects`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=100;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
