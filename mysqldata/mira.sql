-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: database
-- Generation Time: May 10, 2025 at 10:19 AM
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

--
-- Dumping data for table `booking`
--

INSERT INTO `booking` (`booking_id`, `customer_id`, `service_id`, `user_id`, `promotion_id`, `appointment_datetime`, `price`, `discount`, `deposit_price`, `total_price`, `note`, `status`, `is_active`, `deleted_at`, `created_at`, `updated_at`) VALUES
(2, 1, 8, 2, NULL, '2025-05-01 18:25:00', 290.00, 0.00, 29.00, 290.00, 'ทดสอบ', 'cancel', '1', NULL, '2025-04-26 11:26:00', '2025-05-08 21:56:44'),
(3, 3, 19, 2, NULL, '2025-05-10 14:30:00', 200.00, 0.00, 20.00, 200.00, 'ลูกค้าแพ้น้ำหอม กรุณาใช้ผลิตภัณฑ์ที่ไม่มีกลิ่น', 'pending', '1', NULL, '2025-04-26 12:10:31', '2025-05-08 21:56:53'),
(4, 3, 13, 2, NULL, '2025-05-11 14:30:00', 459.00, 0.00, 45.90, 459.00, 'ลูกค้าแพ้น้ำหอม กรุณาใช้ผลิตภัณฑ์ที่ไม่มีกลิ่น', 'confirm', '1', NULL, '2025-04-26 12:14:03', '2025-05-08 21:57:03');

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
('075bb6a2591708f8ddeb3b680f5bf1c3', 3, '85e7e9a5d42c35d69ae040e8db5b609fa10c8e643979e7ae62175cc5eac9787b', '172.18.0.1', 'PostmanRuntime/7.43.3', '2025-04-26 12:07:52', '2025-04-26 12:07:52', '2025-05-26 12:07:52'),
('125c39c2063baf859abde943d4e69b20', 5, 'eaaeb2280babb14aeae7ea12c682fa485700d61023dc5dfdf62a4a1fb19bc6d0', '172.18.0.1', 'PostmanRuntime/7.43.0', '2025-03-31 15:13:18', '2025-03-31 15:13:18', '2025-04-30 15:13:18'),
('14fa4568efe76158dc1a6b0f961b7861', 5, 'd44d71c4ebdcb7b42fd76f47c87028fc8fe332ffc382c294bd57426428e9b8fb', '172.18.0.1', 'PostmanRuntime/7.43.0', '2025-03-31 15:09:21', '2025-03-31 15:09:21', '2025-04-30 15:09:21'),
('26d2d2925fb547c09407f369adee47f6', 5, '97b798261607680bc999470b2d9d23a406e885534cc024f069a997cfd267f12e', '172.18.0.1', 'PostmanRuntime/7.43.0', '2025-03-31 15:06:43', '2025-03-31 15:06:43', '2025-04-30 15:06:43'),
('a3487e65bcb29e2a885bcde70c8b8c1f', 3, 'b7a20d1bd36976e99e4574221fadb5ed024028096f918ca40bf1d0af3bcaf87e', '172.18.0.1', 'PostmanRuntime/7.43.0', '2025-03-31 15:05:02', '2025-03-31 15:05:02', '2025-04-30 15:05:02'),
('d68f569b293ad683ffca1010e3b0b976', 3, 'fb5c9be2dfc177483d614902a9ffd4a6ea882a9f1bb9cd4c95eee84e96bdd96d', '172.18.0.1', 'PostmanRuntime/7.43.0', '2025-02-24 12:11:21', '2025-02-24 12:11:21', '2025-03-26 12:11:21');

-- --------------------------------------------------------

--
-- Table structure for table `estimate`
--

CREATE TABLE `estimate` (
  `estimate_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `status` enum('pending','responsed') NOT NULL,
  `is_active` enum('0','1') NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `feedback_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `service_id` int(11) DEFAULT NULL,
  `rating` enum('1','2','3','4','5') NOT NULL DEFAULT '1',
  `comment` text DEFAULT NULL,
  `is_active` enum('0','1') NOT NULL DEFAULT '1',
  `deleted_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`feedback_id`, `customer_id`, `service_id`, `rating`, `comment`, `is_active`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '3', 'บริการดีมาก พนักงานน่ารักเป็นกันเอง ใส่ใจในทุกรายละเอียด ผลลัพธ์ออกมาพอใจมากค่ะ จะกลับมาใช้บริการอีกแน่นอน', '1', NULL, '2025-04-26 23:59:24', '2025-04-27 00:11:55'),
(2, 3, 2, '5', 'ประทับใจในการบริการมากๆ คุณหมอมีความเชี่ยวชาญ อธิบายขั้นตอนการรักษาได้ชัดเจน ทำให้รู้สึกสบายใจตลอดการรักษา', '1', NULL, '2025-04-27 00:12:16', '2025-04-27 00:12:16'),
(3, 5, 1, '1', 'แย่มาก! พนักงานพูดจาไม่ดี ไม่มีการอธิบายขั้นตอนการทำทรีทเมนต์ ทำเสร็จแล้วผิวแพ้ แต่ไม่มีการติดตามผล ไม่แนะนำเลย', '1', NULL, '2025-04-27 00:12:46', '2025-04-27 00:12:46'),
(4, 3, NULL, '3', 'บริการพอใช้ได้ ผลลัพธ์ไม่เห็นผลชัดเจนเท่าที่คาดหวัง อาจต้องทำหลายครั้ง พนักงานบริการดี แต่ต้องรอนาน', '1', NULL, '2025-04-27 00:16:36', '2025-04-27 00:16:36'),
(5, 3, NULL, '3', 'บริการพอใช้ได้ ผลลัพธ์ไม่เห็นผลชัดเจนเท่าที่คาดหวัง อาจต้องทำหลายครั้ง พนักงานบริการดี แต่ต้องรอนาน', '0', '2025-04-27 00:17:29', '2025-04-27 00:17:22', '2025-04-27 00:17:29'),
(6, 3, 1, '3', 'บริการพอใช้ได้ ผลลัพธ์ไม่เห็นผลชัดเจนเท่าที่คาดหวัง อาจต้องทำหลายครั้ง พนักงานบริการดี แต่ต้องรอนาน', '0', '2025-04-27 00:17:39', '2025-04-27 00:17:35', '2025-04-27 00:17:39');

-- --------------------------------------------------------

--
-- Table structure for table `finance`
--

CREATE TABLE `finance` (
  `finance_id` int(11) NOT NULL,
  `description` text NOT NULL,
  `income` decimal(10,2) DEFAULT 0.00,
  `outcome` decimal(10,2) DEFAULT 0.00,
  `transaction_date` date NOT NULL,
  `is_active` enum('0','1') NOT NULL DEFAULT '1',
  `deleted_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

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
(1, NULL, 'ชื่อโปรโมชั่น', 'รายละเอียด', 20.00, 'ABC123456', '2025-04-25 00:00:00', '2029-12-31 23:59:00', '0', '2025-05-08 21:55:26', '2025-04-25 18:21:56', '2025-05-08 21:55:26'),
(2, NULL, 'ชื่อโปรโมชั่น edit', 'รายละเอียด edit', 200.00, 'ABC123456Z', '2025-04-26 00:00:00', '2029-12-31 23:59:00', '0', '2025-04-25 21:32:23', '2025-04-25 21:31:16', '2025-04-25 21:32:23'),
(3, NULL, 'ชื่อโปรโมชั่น edit editz', 'รายละเอียด edit editz', 400.00, 'ABC1234222', '2025-04-30 00:00:00', '2029-12-31 23:59:00', '0', '2025-05-08 21:55:23', '2025-04-25 21:31:55', '2025-05-08 21:55:23'),
(4, '681d284099eea_1746741312.jpg', 'ลูกค้าใหม่ สักคิ้วสีฝุ่น ลด 50%', 'ลูกค้าใหม่ สักคิ้วสีฝุ่น  รับส่วนลด 50%', 550.00, 'newc50', '2025-05-01 04:54:00', '2030-12-31 04:54:00', '1', NULL, '2025-05-08 21:55:12', '2025-05-08 21:55:12');

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
(1, 2, '680b6bb8c8b26_1745578936.png', 'บริการ 1 แก้ไข', 'รายละเอียดบริการ 1 แก้ไข แก้ไข', 199.00, 59, '0', '2025-05-08 21:06:25', '2025-04-24 16:08:47', '2025-05-08 21:06:25'),
(2, 1, NULL, 'ชื่อบริการ', 'รายละเอียด', 200.00, 60, '0', '2025-05-08 21:06:28', '2025-04-25 09:27:55', '2025-05-08 21:06:28'),
(3, 2, NULL, 'ชื่อบริการ 2', 'รายละเอียด รายละเอียด รายละเอียด', 500.00, 30, '0', '2025-04-25 09:31:32', '2025-04-25 09:30:18', '2025-04-25 10:33:09'),
(4, 5, '681f1e3caf9b7_1746869820.jpg', 'สักคิ้ว สีฝุ่น ออมเบรย์', 'การสักคิ้วดวยสีฝุ่น หรือไล่สีแบบออมเบรย์', 1099.00, 120, '1', NULL, '2025-05-08 21:08:07', '2025-05-10 09:37:00'),
(5, 5, '681f1e45280fe_1746869829.jpg', 'สักคิ้ว ลายเส้น 6 มิติ', 'การสักคิ้วแบบเส้น ', 2099.00, 120, '1', NULL, '2025-05-08 21:09:01', '2025-05-10 09:37:09'),
(6, 5, '681f1e4dba0b5_1746869837.jpg', 'เติมสีคิ้ว', 'การเติมสีคิ้ว', 790.00, 90, '1', NULL, '2025-05-08 21:09:58', '2025-05-10 09:37:17'),
(7, 1, '681f1ec60655f_1746869958.jpeg', 'ทรีทเม้นท์ Aloe vera', 'การทำทรีทเมนท์บนในหน้าด้วย Alov era', 259.00, 60, '1', NULL, '2025-05-08 21:11:07', '2025-05-10 09:39:18'),
(8, 7, '681f1f512a203_1746870097.jpg', 'มาร์กหน้าคอลลาเจน', 'การทำมาร์กหน้าด้วยคอลลาเจน', 290.00, 60, '1', NULL, '2025-05-08 21:12:17', '2025-05-10 09:41:37'),
(9, 1, '681f1ed034457_1746869968.jpg', 'ทรีทเม้นท์หน้าด้วยมือ', 'การทำทรีทเมนท์ด้วยมือ', 359.00, 60, '1', NULL, '2025-05-08 21:13:02', '2025-05-10 09:39:28'),
(10, 7, '681f1f484a439_1746870088.jpg', 'มาร์กหน้าทองคำ 24K', 'การทำมาร์กหน้าด้วยทองคำ 24K', 390.00, 60, '1', NULL, '2025-05-08 21:13:55', '2025-05-10 09:41:28'),
(11, 2, '681f1f38b96be_1746870072.jpg', 'สครับหน้าทองคำ 24K', 'การสครับหน้าด้วยทองคำ 24K', 390.00, 60, '1', NULL, '2025-05-08 21:14:30', '2025-05-10 09:41:12'),
(12, 1, '681f1eda61511_1746869978.jpg', 'ทรีทเม้นท์หน้าด้วยมือและเครื่อง', 'การทำทรีทเม้นท์หน้าด้วยมือและเครื่อง ทำให้หน้ากระชับมากยิ่งขึ้น', 559.00, 90, '1', NULL, '2025-05-08 21:15:38', '2025-05-10 09:39:38'),
(13, 1, '681f1eeb644db_1746869995.jpg', 'ทรีทเม้นท์หน้าด้วยครื่อง', 'การทำทรีทเม้นท์หน้าด้วยครื่องมือที่ทันสมัย', 459.00, 60, '1', NULL, '2025-05-08 21:16:29', '2025-05-10 09:39:55'),
(14, 1, '681f1f2c145d7_1746870060.jpg', 'ทรีทเม้นท์น้ำมันรังไหม', 'การทำทรีทเมนท์หน้าด้วยน้ำมันรังไหมจากธรรมชาติ 100%', 390.00, 60, '1', NULL, '2025-05-08 21:18:03', '2025-05-10 09:41:00'),
(15, 5, '681f1e5f91bf7_1746869855.jpg', 'ลบสีคิ้ว', 'การลบคิ้วด้วยเครื่อง laser', 890.00, 90, '1', NULL, '2025-05-08 21:19:12', '2025-05-10 09:37:35'),
(16, 6, '681f1e731fc4d_1746869875.jpg', 'ทำเล็บเจล 1-2 สี', 'ทำเล็บเจลทาเฉพาะสีพื้น 1-2 สี', 150.00, 90, '1', NULL, '2025-05-08 21:21:27', '2025-05-10 09:37:55'),
(17, 6, '681f1e811af74_1746869889.jpg', 'ทำเล็บเจล 3 สีขึ้นไป', 'ทำเล็บเจลทาเฉพาะสีพื้น  3 สีขึ้นไป', 200.00, 90, '1', NULL, '2025-05-08 21:23:01', '2025-05-10 09:38:09'),
(18, 6, '681f1e8b34d0c_1746869899.jpg', 'ทาเล้บเจลแบบไล่สี ออมเบรย์', '', 350.00, 120, '1', NULL, '2025-05-08 21:24:12', '2025-05-10 09:38:19'),
(19, 6, '681f1e95ce3eb_1746869909.jpg', 'ทำเล็บสีลูกแก้ว', '', 200.00, 90, '1', NULL, '2025-05-08 21:24:44', '2025-05-10 09:38:29'),
(20, 6, '681f1ea009674_1746869920.jpg', 'ต่อเล็บเจล', '', 300.00, 120, '1', NULL, '2025-05-08 21:25:29', '2025-05-10 09:38:40'),
(21, 6, '681f1eab19b0c_1746869931.jpg', 'ล้างสีเจล', '', 100.00, 30, '1', NULL, '2025-05-08 21:26:02', '2025-05-10 09:38:51');

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
(1, 'ทรีทเม้นท์หน้า', 'การทำทรีทเม้นท์ใบหน้า', '1', NULL, '2025-04-24 15:09:22', '2025-05-08 21:04:44'),
(2, 'สครับหน้า', 'การทำสครับใบหน้า', '1', NULL, '2025-04-24 15:32:07', '2025-05-08 21:04:35'),
(3, ' เพิ่มข้อมูลประเภทของบริการ 2', '', '0', '2025-04-24 15:34:21', '2025-04-24 15:32:19', '2025-04-24 15:34:21'),
(5, 'สักคิ้ว', 'การสักคิ้ว', '1', NULL, '2025-05-08 21:05:21', '2025-05-08 21:05:21'),
(6, 'ทำเล็บ', 'การตกแต่งเล็บด้วยสีเจล', '1', NULL, '2025-05-08 21:05:52', '2025-05-08 21:05:52'),
(7, 'มาร์กหน้า', 'การทำมาร์กใบหน้า', '1', NULL, '2025-05-08 21:06:14', '2025-05-08 21:06:14');

-- --------------------------------------------------------

--
-- Table structure for table `stock_movement`
--

CREATE TABLE `stock_movement` (
  `movement_id` int(11) NOT NULL,
  `supply_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `movement_type` enum('in','out') NOT NULL,
  `reference` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `movement_date` date NOT NULL,
  `is_active` enum('0','1') NOT NULL DEFAULT '1',
  `deleted_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `stock_movement`
--

INSERT INTO `stock_movement` (`movement_id`, `supply_id`, `quantity`, `movement_type`, `reference`, `notes`, `movement_date`, `is_active`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 1, 10, 'in', '', '', '2025-05-10', '1', NULL, '2025-05-10 10:18:26', '2025-05-10 10:18:26');

-- --------------------------------------------------------

--
-- Table structure for table `supplies`
--

CREATE TABLE `supplies` (
  `supply_id` int(11) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `unit` varchar(50) DEFAULT NULL,
  `min_quantity` int(11) DEFAULT 0,
  `is_active` enum('0','1') NOT NULL DEFAULT '1',
  `deleted_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `supplies`
--

INSERT INTO `supplies` (`supply_id`, `image`, `name`, `description`, `unit`, `min_quantity`, `is_active`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, NULL, 'ทิชชู่', '', 'ม้วน', 1, '1', NULL, '2025-05-10 09:53:40', '2025-05-10 10:18:37');

-- --------------------------------------------------------

--
-- Table structure for table `transaction`
--

CREATE TABLE `transaction` (
  `transaction_id` int(11) NOT NULL,
  `booking_id` int(11) NOT NULL,
  `image` varchar(255) NOT NULL,
  `is_active` enum('0','1') NOT NULL DEFAULT '1',
  `deleted_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

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
(1, 1, 'admin', '$2y$10$QaD4oTnP8NQUHqExpB6iRuW4paCeFEsW.x3TjdMrzYEOV43smhvRu', NULL, 'Administrator', '', 'admin@mail.com', '0987654321', '1999-01-01', '-', '2025-05-10 09:53:06', '1', NULL, '2024-08-16 20:39:51', '2025-05-10 09:53:06'),
(2, 2, 'employee', '$2y$10$QaD4oTnP8NQUHqExpB6iRuW4paCeFEsW.x3TjdMrzYEOV43smhvRu', NULL, 'John', 'Doe', 'employee@mail.com', '0123456789', '1993-12-11', '-', NULL, '1', NULL, '2024-08-16 20:39:51', '2024-11-03 03:04:05'),
(3, 2, 'sontaya', '$2y$10$ShZGcY9pqSd1Xz9cg1fT2u8mw/aYjRrTIjMpLIz3hjMddCDY1Ybny', '681f1f6916ac0_1746870121.jpg', 'สนธยา', 'พาชนะ', 'sontaya@gmail.com', '0864314457', '1994-06-15', '54 หมู่ 6 ตำบลบ้านโฮ่ง อำเภอบ้านโฮ่ง จังหวัดลำพูน 51130', NULL, '1', NULL, '2025-05-08 22:03:16', '2025-05-10 09:42:01'),
(4, 2, 'phanthit', '$2y$10$BtO87FG.SMA7pbJeMaf.n.tW7KXy2H4YnlHXLfoUU3qikvt1pF1Uq', '681f1f742b3a1_1746870132.jpg', 'พันธ์ทิศ', '-', 'phanthit@gmail.com', '0945869334', '1987-06-25', '129/4 หมู่ 8 ตำบลบ้านโฮ่ง อำเภอบ้านโฮ่ง จังหวัดลำพูน 51130', NULL, '1', NULL, '2025-05-08 22:09:51', '2025-05-10 09:42:12');

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
(1, 'ผู้ดูแลระบบ', 'dashboard, work-calendar, user, role, customer, service_type, service, promotion, booking, feedback, estimate, finance, supply, stock', '1', NULL, '2024-11-03 01:42:56', '2025-05-10 09:22:34'),
(2, 'พนักงาน', 'dashboard, customer, promotion, booking', '1', NULL, '2024-11-03 02:06:49', '2025-04-26 10:34:13');

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
-- Indexes for table `estimate`
--
ALTER TABLE `estimate`
  ADD PRIMARY KEY (`estimate_id`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`feedback_id`);

--
-- Indexes for table `finance`
--
ALTER TABLE `finance`
  ADD PRIMARY KEY (`finance_id`);

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
-- Indexes for table `stock_movement`
--
ALTER TABLE `stock_movement`
  ADD PRIMARY KEY (`movement_id`),
  ADD KEY `supply_id` (`supply_id`);

--
-- Indexes for table `supplies`
--
ALTER TABLE `supplies`
  ADD PRIMARY KEY (`supply_id`);

--
-- Indexes for table `transaction`
--
ALTER TABLE `transaction`
  ADD PRIMARY KEY (`transaction_id`);

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
  MODIFY `booking_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `customer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `estimate`
--
ALTER TABLE `estimate`
  MODIFY `estimate_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `feedback_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `finance`
--
ALTER TABLE `finance`
  MODIFY `finance_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `promotion`
--
ALTER TABLE `promotion`
  MODIFY `promotion_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `service`
--
ALTER TABLE `service`
  MODIFY `service_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `service_type`
--
ALTER TABLE `service_type`
  MODIFY `service_type_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `stock_movement`
--
ALTER TABLE `stock_movement`
  MODIFY `movement_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `supplies`
--
ALTER TABLE `supplies`
  MODIFY `supply_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `transaction`
--
ALTER TABLE `transaction`
  MODIFY `transaction_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

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

--
-- Constraints for table `stock_movement`
--
ALTER TABLE `stock_movement`
  ADD CONSTRAINT `fk_movement_supply` FOREIGN KEY (`supply_id`) REFERENCES `supplies` (`supply_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
