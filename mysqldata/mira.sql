-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: database
-- Generation Time: Apr 25, 2025 at 11:48 PM
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
-- Table structure for table `booking`
--

CREATE TABLE `booking` (
  `booking_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `promotion_id` int(11) DEFAULT NULL,
  `appointment_datetime` datetime DEFAULT NULL,
  `price` double(11,2) DEFAULT NULL,
  `discount` double(11,2) DEFAULT NULL,
  `deposit_price` double(11,2) DEFAULT NULL COMMENT '10%',
  `total_price` double(11,2) DEFAULT NULL,
  `note` text DEFAULT NULL,
  `status` enum('pending','confirm','cancel','complete') NOT NULL DEFAULT 'pending',
  `is_active` enum('0','1') NOT NULL DEFAULT '1',
  `deleted_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

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
(3, 'tchinlapha@gmail.com', '$2y$10$qjwuDNu1m1sVvEnTEZfQmuy9SSsLKdt7eceGmhNgu43rbAgjwHp8.', 'Thanin', 'Chinlapha', '0825157905', '1993-12-11', 'CNX', '1', NULL, '2025-02-22 10:32:53', '2025-02-22 10:32:53'),
(5, 'tchinlapha.x2@gmail.com', '$2y$10$1k9735pyBLMZZF0kDYIzGeE4D79Fpjmro80hha0J0D9cViY4563nC', 'Thanin2', 'Chinlapha', '0825157905', '1993-12-11', 'CNX', '1', NULL, '2025-03-31 15:06:15', '2025-03-31 15:13:13');

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
('125c39c2063baf859abde943d4e69b20', 5, 'eaaeb2280babb14aeae7ea12c682fa485700d61023dc5dfdf62a4a1fb19bc6d0', '172.18.0.1', 'PostmanRuntime/7.43.0', '2025-03-31 15:13:18', '2025-03-31 15:13:18', '2025-04-30 15:13:18'),
('14fa4568efe76158dc1a6b0f961b7861', 5, 'd44d71c4ebdcb7b42fd76f47c87028fc8fe332ffc382c294bd57426428e9b8fb', '172.18.0.1', 'PostmanRuntime/7.43.0', '2025-03-31 15:09:21', '2025-03-31 15:09:21', '2025-04-30 15:09:21'),
('26d2d2925fb547c09407f369adee47f6', 5, '97b798261607680bc999470b2d9d23a406e885534cc024f069a997cfd267f12e', '172.18.0.1', 'PostmanRuntime/7.43.0', '2025-03-31 15:06:43', '2025-03-31 15:06:43', '2025-04-30 15:06:43'),
('a3487e65bcb29e2a885bcde70c8b8c1f', 3, 'b7a20d1bd36976e99e4574221fadb5ed024028096f918ca40bf1d0af3bcaf87e', '172.18.0.1', 'PostmanRuntime/7.43.0', '2025-03-31 15:05:02', '2025-03-31 15:05:02', '2025-04-30 15:05:02'),
('d68f569b293ad683ffca1010e3b0b976', 3, 'fb5c9be2dfc177483d614902a9ffd4a6ea882a9f1bb9cd4c95eee84e96bdd96d', '172.18.0.1', 'PostmanRuntime/7.43.0', '2025-02-24 12:11:21', '2025-02-24 12:11:21', '2025-03-26 12:11:21');

-- --------------------------------------------------------

--
-- Table structure for table `promotion`
--

CREATE TABLE `promotion` (
  `promotion_id` int(11) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `discount` double(11,2) NOT NULL DEFAULT 0.00,
  `code` varchar(255) NOT NULL,
  `start_datetime` datetime NOT NULL,
  `end_datetime` datetime NOT NULL,
  `is_active` enum('0','1') NOT NULL DEFAULT '1',
  `deleted_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `promotion`
--

INSERT INTO `promotion` (`promotion_id`, `image`, `name`, `description`, `discount`, `code`, `start_datetime`, `end_datetime`, `is_active`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, NULL, 'ชื่อโปรโมชั่น', 'รายละเอียด', 20.00, 'ABC123456', '2025-04-25 00:00:00', '2029-12-31 23:59:00', '1', NULL, '2025-04-25 18:21:56', '2025-04-25 23:40:33'),
(2, NULL, 'ชื่อโปรโมชั่น edit', 'รายละเอียด edit', 200.00, 'ABC123456Z', '2025-04-26 00:00:00', '2029-12-31 23:59:00', '0', '2025-04-25 21:32:23', '2025-04-25 21:31:16', '2025-04-25 21:32:23'),
(3, NULL, 'ชื่อโปรโมชั่น edit editz', 'รายละเอียด edit editz', 400.00, 'ABC1234222', '2025-04-30 00:00:00', '2029-12-31 23:59:00', '1', NULL, '2025-04-25 21:31:55', '2025-04-25 21:32:13');

-- --------------------------------------------------------

--
-- Table structure for table `service`
--

CREATE TABLE `service` (
  `service_id` int(11) NOT NULL,
  `service_type_id` int(11) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `price` double(11,2) DEFAULT NULL,
  `time` int(3) DEFAULT NULL,
  `is_active` enum('0','1') NOT NULL DEFAULT '1',
  `deleted_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `service`
--

INSERT INTO `service` (`service_id`, `service_type_id`, `image`, `name`, `description`, `price`, `time`, `is_active`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 2, '680b6bb8c8b26_1745578936.png', 'บริการ 1 แก้ไข', 'รายละเอียดบริการ 1 แก้ไข แก้ไข', 199.00, 59, '1', NULL, '2025-04-24 16:08:47', '2025-04-25 11:02:16'),
(2, 1, NULL, 'ชื่อบริการ', 'รายละเอียด', 200.00, 60, '1', NULL, '2025-04-25 09:27:55', '2025-04-25 09:27:55'),
(3, 2, NULL, 'ชื่อบริการ 2', 'รายละเอียด รายละเอียด รายละเอียด', 500.00, 30, '0', '2025-04-25 09:31:32', '2025-04-25 09:30:18', '2025-04-25 10:33:09');

-- --------------------------------------------------------

--
-- Table structure for table `service_type`
--

CREATE TABLE `service_type` (
  `service_type_id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `is_active` enum('0','1') DEFAULT '1',
  `deleted_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `service_type`
--

INSERT INTO `service_type` (`service_type_id`, `name`, `description`, `is_active`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'ประเภทที่ 1', 'รายละเอียดประเภทที่ 1 edit', '1', NULL, '2025-04-24 15:09:22', '2025-04-24 15:51:19'),
(2, 'ทดสอบเพิ่มข้อมูลประเภทของบริการ 1', 'ทดสอบเพิ่มข้อมูลประเภทของบริการ 1 รายละเอียด', '1', NULL, '2025-04-24 15:32:07', '2025-04-24 15:32:07'),
(3, ' เพิ่มข้อมูลประเภทของบริการ 2', '', '0', '2025-04-24 15:34:21', '2025-04-24 15:32:19', '2025-04-24 15:34:21');

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
(1, 1, 'admin', '$2y$10$QaD4oTnP8NQUHqExpB6iRuW4paCeFEsW.x3TjdMrzYEOV43smhvRu', NULL, 'Administrator', '', 'admin@mail.com', '0987654321', '1999-01-01', '-', '2025-04-25 08:42:52', '1', NULL, '2024-08-16 20:39:51', '2025-04-25 08:42:52'),
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
(1, 'ผู้ดูแลระบบ', 'dashboard, user, role, employee, employee-schedule, customer, stock, service_type, service, estimate, promotion, comment, finance', '1', NULL, '2024-11-03 01:42:56', '2025-04-24 15:05:46'),
(2, 'พนักงาน', 'dashboard, role, estimate, promotion, comment', '1', NULL, '2024-11-03 02:06:49', '2025-02-09 11:17:29');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `booking`
--
ALTER TABLE `booking`
  ADD PRIMARY KEY (`booking_id`);

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
-- Indexes for table `promotion`
--
ALTER TABLE `promotion`
  ADD PRIMARY KEY (`promotion_id`);

--
-- Indexes for table `service`
--
ALTER TABLE `service`
  ADD PRIMARY KEY (`service_id`);

--
-- Indexes for table `service_type`
--
ALTER TABLE `service_type`
  ADD PRIMARY KEY (`service_type_id`);

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
-- AUTO_INCREMENT for table `booking`
--
ALTER TABLE `booking`
  MODIFY `booking_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `customer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `promotion`
--
ALTER TABLE `promotion`
  MODIFY `promotion_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `service`
--
ALTER TABLE `service`
  MODIFY `service_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `service_type`
--
ALTER TABLE `service_type`
  MODIFY `service_type_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

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
