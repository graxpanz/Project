-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: database
-- Generation Time: Feb 24, 2025 at 12:13 PM
-- Server version: 11.6.2-MariaDB-ubu2404
-- PHP Version: 8.2.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mira`
--

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `customer_id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `firstname` varchar(100) NOT NULL,
  `lastname` varchar(100) DEFAULT NULL,
  `phone` varchar(12) DEFAULT NULL,
  `birthdate` date DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `is_active` enum('0','1') NOT NULL DEFAULT '1',
  `deleted_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`customer_id`, `email`, `password`, `firstname`, `lastname`, `phone`, `birthdate`, `address`, `is_active`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'johndoe@gmail.com', '$2y$10$Yy6RaX9SXoE/l3NAkdQ9yu/aE81/LWftHbl9l7wVBGdv5lDGB99S6', 'Johnz', 'Doe', '0987654321', NULL, 'John doe home', '1', NULL, '2025-02-22 09:39:37', '2025-02-22 10:19:53'),
(2, 'askjfh@mail.com', '$2y$10$y.2kcJX4jTGVY5/E1vaiZO1cHNnsBrjI0a6Ovsl30MQqePaH1g6.m', 'alksfdh', 'kasjfh', '0987654333', '1993-11-12', 'asklfjlkasf', '0', '2025-02-22 10:29:41', '2025-02-22 10:25:57', '2025-02-22 10:29:41'),
(3, 'tchinlapha@gmail.com', '$2y$10$qjwuDNu1m1sVvEnTEZfQmuy9SSsLKdt7eceGmhNgu43rbAgjwHp8.', 'Thanin', 'Chinlapha', '0825157905', '1993-12-11', 'CNX', '1', NULL, '2025-02-22 10:32:53', '2025-02-22 10:32:53');

-- --------------------------------------------------------

--
-- Table structure for table `customer_session`
--

CREATE TABLE `customer_session` (
  `session_id` varchar(255) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `token` varchar(255) NOT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `last_activity` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `expired_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `customer_session`
--

INSERT INTO `customer_session` (`session_id`, `customer_id`, `token`, `ip_address`, `user_agent`, `last_activity`, `created_at`, `expired_at`) VALUES
('d68f569b293ad683ffca1010e3b0b976', 3, 'fb5c9be2dfc177483d614902a9ffd4a6ea882a9f1bb9cd4c95eee84e96bdd96d', '172.18.0.1', 'PostmanRuntime/7.43.0', '2025-02-24 12:11:21', '2025-02-24 12:11:21', '2025-03-26 12:11:21');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `user_id` int(11) NOT NULL,
  `user_role_id` int(11) NOT NULL,
  `username` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `password` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `image` varchar(100) DEFAULT NULL,
  `firstname` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `lastname` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(10) DEFAULT NULL,
  `birthdate` date DEFAULT NULL,
  `address` text DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `is_active` enum('0','1') NOT NULL DEFAULT '1',
  `deleted_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `user_role_id`, `username`, `password`, `image`, `firstname`, `lastname`, `email`, `phone`, `birthdate`, `address`, `last_login`, `is_active`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 1, 'admin', '$2y$10$QaD4oTnP8NQUHqExpB6iRuW4paCeFEsW.x3TjdMrzYEOV43smhvRu', NULL, 'Administrator', '', 'admin@mail.com', '0987654321', '1999-01-01', '-', '2025-02-22 09:18:18', '1', NULL, '2024-08-16 20:39:51', '2025-02-22 09:18:18'),
(2, 2, 'employee', '$2y$10$QaD4oTnP8NQUHqExpB6iRuW4paCeFEsW.x3TjdMrzYEOV43smhvRu', NULL, 'John', 'Doe', 'employee@mail.com', '0123456789', '1993-12-11', '-', NULL, '1', NULL, '2024-08-16 20:39:51', '2024-11-03 03:04:05');

-- --------------------------------------------------------

--
-- Table structure for table `user_role`
--

CREATE TABLE `user_role` (
  `user_role_id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `permission` text DEFAULT NULL COMMENT 'Array values',
  `is_active` enum('0','1') DEFAULT '1',
  `deleted_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_role`
--

INSERT INTO `user_role` (`user_role_id`, `name`, `permission`, `is_active`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'ผู้ดูแลระบบ', 'dashboard, user, role, employee, employee-schedule, customer, stock, service, estimate, promotion, comment, finance', '1', NULL, '2024-11-03 01:42:56', '2025-02-09 11:17:45'),
(2, 'พนักงาน', 'dashboard, role, estimate, promotion, comment', '1', NULL, '2024-11-03 02:06:49', '2025-02-09 11:17:29');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`customer_id`);

--
-- Indexes for table `customer_session`
--
ALTER TABLE `customer_session`
  ADD PRIMARY KEY (`session_id`),
  ADD KEY `idx_token` (`token`),
  ADD KEY `idx_customer` (`customer_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `user_role`
--
ALTER TABLE `user_role`
  ADD PRIMARY KEY (`user_role_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `customer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `user_role`
--
ALTER TABLE `user_role`
  MODIFY `user_role_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `customer_session`
--
ALTER TABLE `customer_session`
  ADD CONSTRAINT `customer_session_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`customer_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
