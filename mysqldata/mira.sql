-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 02, 2024 at 09:13 PM
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
-- Database: `mira`
--

-- --------------------------------------------------------

--
-- Table structure for table `calendar`
--

CREATE TABLE `calendar` (
  `table_id` int(5) NOT NULL,
  `queue_id` int(5) NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `comment`
--

CREATE TABLE `comment` (
  `comment_id` int(5) NOT NULL,
  `comment` varchar(255) NOT NULL,
  `response` varchar(255) NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `comment`
--

INSERT INTO `comment` (`comment_id`, `comment`, `response`, `date`) VALUES
(1, 'test', '', '2024-08-01'),
(2, 'test1', 'ทดสอบตอบกลับ', '2024-08-07');

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `customer_id` int(5) NOT NULL,
  `name` varchar(100) NOT NULL,
  `surname` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(100) NOT NULL,
  `age` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`customer_id`, `name`, `surname`, `email`, `phone`, `age`) VALUES
(2, 'view', 'rungpairin', 'rungpairin_ji64@live.rmutl.ac.th', '0956789805', '0000-00-00'),
(4, 'view', 'xx', 'v@gmail.com', '0956789805', '2024-07-05'),
(5, 'view', 'xx', 'v@gmail.com', '0956789805', '2024-07-05'),
(6, 'view', 'xx', 'rungpairin_369@hotmail.com', '0000000000', '2024-07-06');

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `emp_id` int(5) NOT NULL,
  `fname` varchar(100) DEFAULT NULL,
  `lname` varchar(100) DEFAULT NULL,
  `tel` varchar(15) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `bdate` date DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `address` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`emp_id`, `fname`, `lname`, `tel`, `email`, `bdate`, `age`, `address`) VALUES
(10, 'อริศรา', 'ปันวงค์ยอง', '0988065739', 'arissara1@gmail.com', '2002-10-22', 21, 'เชียงใหม่ 50300'),
(15, 'Thanin', 'Chinlapha', '0987654321', 'tchinlapha@gmail.com', '1993-12-11', 31, 'My Home');

-- --------------------------------------------------------

--
-- Table structure for table `employees_position`
--

CREATE TABLE `employees_position` (
  `emp_id` int(5) NOT NULL,
  `position_id` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employees_position`
--

INSERT INTO `employees_position` (`emp_id`, `position_id`) VALUES
(10, 1),
(10, 2),
(10, 3),
(15, 3);

-- --------------------------------------------------------

--
-- Table structure for table `estimate`
--

CREATE TABLE `estimate` (
  `estimate_id` int(5) NOT NULL,
  `customer_id` int(5) NOT NULL,
  `file` varchar(255) NOT NULL,
  `detail` varchar(255) NOT NULL,
  `response` varchar(255) NOT NULL,
  `date` date NOT NULL,
  `status` enum('ยังไม่ได้ตอบกลับ','ตอบกลับแล้ว') NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `estimate`
--

INSERT INTO `estimate` (`estimate_id`, `customer_id`, `file`, `detail`, `response`, `date`, `status`, `price`) VALUES
(1, 2, '', 'test', 'test', '2024-07-30', 'ตอบกลับแล้ว', 0.00);

-- --------------------------------------------------------

--
-- Table structure for table `finance`
--

CREATE TABLE `finance` (
  `finance_id` int(5) NOT NULL,
  `date` date NOT NULL,
  `list` varchar(255) NOT NULL,
  `income` decimal(10,2) NOT NULL,
  `expense` decimal(10,2) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `note` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `finance`
--

INSERT INTO `finance` (`finance_id`, `date`, `list`, `income`, `expense`, `total`, `note`) VALUES
(1, '2024-08-05', 'test', 100.00, 0.00, 100.00, ''),
(2, '2024-08-05', 'test', 0.00, 50.00, 50.00, ''),
(3, '2024-11-02', 'ทดสอบ', 1000.00, 0.00, 1000.00, 'ทดสอบ รายรับ (บาท)'),
(4, '2024-11-02', 'ทดสอบ', 0.00, 432.00, -432.00, 'ทดสอบ รายจ่าย (บาท)'),
(5, '2024-11-02', 'ทดสอบ', 300.00, 200.00, 100.00, 'ทดสอบทดสอบทดสอบ');

-- --------------------------------------------------------

--
-- Table structure for table `position_type`
--

CREATE TABLE `position_type` (
  `position_id` int(5) NOT NULL,
  `position_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `position_type`
--

INSERT INTO `position_type` (`position_id`, `position_name`) VALUES
(1, 'พนักงานทำเล็บ'),
(2, 'พนักงานสักคิ้ว'),
(3, 'พนักงานสักปาก'),
(4, 'พนักงานต่อขนตา'),
(5, 'พนักงานทรีทเมนท์');

-- --------------------------------------------------------

--
-- Table structure for table `promotion`
--

CREATE TABLE `promotion` (
  `promotion_id` int(5) NOT NULL,
  `promotion_name` varchar(255) NOT NULL,
  `detail` text NOT NULL,
  `date_start` date NOT NULL,
  `date_end` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `promotion`
--

INSERT INTO `promotion` (`promotion_id`, `promotion_name`, `detail`, `date_start`, `date_end`) VALUES
(1, 'โปรโมชั่นหน้าร้านลด 10%', 'ลูกค้า walk in หน้าร้านเท่านั้น', '2024-08-04', '2024-09-08');

-- --------------------------------------------------------

--
-- Table structure for table `queue`
--

CREATE TABLE `queue` (
  `queue_id` int(5) NOT NULL,
  `customer_id` int(5) NOT NULL,
  `emp_id` int(5) NOT NULL,
  `service_id` int(5) NOT NULL,
  `service_type_id` int(5) NOT NULL,
  `queue_date` date NOT NULL,
  `queue_time` time NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `slip` varchar(255) NOT NULL,
  `create_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('Pending','Confirmed','Cancelled','Completed','No-Show') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `role`
--

CREATE TABLE `role` (
  `role_id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `permission` text DEFAULT NULL COMMENT 'Array values',
  `is_active` enum('0','1') DEFAULT '1',
  `deleted_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role`
--

INSERT INTO `role` (`role_id`, `name`, `permission`, `is_active`, `deleted_at`, `created_at`, `updated_at`) VALUES
(7, 'Admin', 'dashboard, manager, role, employee, employee-schedule, customer, stock, service, estimate, promotion, comment, finance', '1', NULL, '2024-11-03 01:42:56', '2024-11-03 01:42:56'),
(9, 'พนักงาน', 'dashboard, role, estimate, promotion, comment', '1', NULL, '2024-11-03 02:06:49', '2024-11-03 02:49:29');

-- --------------------------------------------------------

--
-- Table structure for table `service`
--

CREATE TABLE `service` (
  `service_id` int(5) NOT NULL,
  `service_name` varchar(100) NOT NULL,
  `service_type_id` int(5) NOT NULL,
  `service_price` decimal(7,2) NOT NULL,
  `service_time` int(2) NOT NULL,
  `service_detail` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `service`
--

INSERT INTO `service` (`service_id`, `service_name`, `service_type_id`, `service_price`, `service_time`, `service_detail`) VALUES
(6, 'ทำเล็บ', 1, 200.00, 90, 'ใส่กลิตเตอร์'),
(7, 'ทำเล็บ', 1, 200.00, 90, '-'),
(8, 'ทรีทเมนต์หน้า', 5, 900.00, 60, '-');

-- --------------------------------------------------------

--
-- Table structure for table `service_type`
--

CREATE TABLE `service_type` (
  `service_type_id` int(5) NOT NULL COMMENT 'รหัสประเภทการบริการ',
  `service_type_name` varchar(100) NOT NULL COMMENT 'ชื่อประเภทการบริการ'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `service_type`
--

INSERT INTO `service_type` (`service_type_id`, `service_type_name`) VALUES
(1, 'บริการทำเล็บ'),
(2, 'บริการสักคิ้ว'),
(3, 'บริการสักปาก'),
(4, 'บริการต่อขนตา'),
(5, 'บริการทรีทเมนท์'),
(6, 'สินค้าขายหน้าร้าน');

-- --------------------------------------------------------

--
-- Table structure for table `stock`
--

CREATE TABLE `stock` (
  `stock_id` int(5) UNSIGNED ZEROFILL NOT NULL,
  `stock_name` varchar(100) NOT NULL,
  `service_type_id` int(5) UNSIGNED ZEROFILL NOT NULL,
  `amount` int(100) NOT NULL,
  `image` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `stock`
--

INSERT INTO `stock` (`stock_id`, `stock_name`, `service_type_id`, `amount`, `image`) VALUES
(00001, 'เข็มสักคิ้ว', 00002, 2, 'เข็มสัก.png'),
(00007, 'เข็มสักคิ้ว', 00004, 12, 'เข็มสัก.png'),
(00009, 'เข็มสักคิ้ว', 00004, 1, 'ทิชชู่.png');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `firstname` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `lastname` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(10) DEFAULT NULL,
  `birthdate` date DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `username` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `password` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `image` varchar(255) NOT NULL DEFAULT 'avatar.png',
  `is_active` enum('0','1') NOT NULL DEFAULT '1',
  `deleted_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `role_id`, `firstname`, `lastname`, `email`, `phone`, `birthdate`, `age`, `address`, `username`, `password`, `image`, `is_active`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 7, 'Arissara', 'Panwongyong', 'Arissara010@gmail.com', '0987654321', '2000-10-12', 24, 'asd', 'arissara', '4a7d1ed414474e4033ac29ccb8653d9b', '672685ac7bf1a_1730577836.png', '1', NULL, '2024-08-16 20:39:51', '2024-11-03 03:03:56'),
(2, 9, 'Thanin', 'Chinlapha', 'tch@gmail.com', '0987654321', '1993-12-11', 30, '1231sa231d', 'tchinlapha', 'e10adc3949ba59abbe56e057f20f883e', '672685b547f48_1730577845.jpg', '1', NULL, '2024-08-16 20:39:51', '2024-11-03 03:04:05'),
(3, 7, 'aasfas', 'asfasf', 'asfasf@asf', '0987654321', '2024-08-02', 0, '1111', 'aaaa', '$2y$10$.FZCgld.feFzc3kPd3qWz.ryUBDuD7eg9QeWvzA20A9', '67268394afd23_1730577300.png', '1', NULL, '2024-11-03 02:55:00', '2024-11-03 02:55:00'),
(4, 7, 'aasfas', 'asfasf', 'asfasf@asf', '0987654321', '2024-08-02', 0, '1111', 'aaaazz', '$2y$10$Leh88xgVxL3ji6n7lDuVSeJpOLqW.9o9A8vvR3MfFuf', '6726843ecc297_1730577470.jpg', '1', NULL, '2024-11-03 02:57:50', '2024-11-03 02:57:50');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `calendar`
--
ALTER TABLE `calendar`
  ADD PRIMARY KEY (`table_id`),
  ADD KEY `foreign key queue` (`queue_id`);

--
-- Indexes for table `comment`
--
ALTER TABLE `comment`
  ADD PRIMARY KEY (`comment_id`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`customer_id`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`emp_id`);

--
-- Indexes for table `employees_position`
--
ALTER TABLE `employees_position`
  ADD PRIMARY KEY (`emp_id`,`position_id`),
  ADD KEY `position_id` (`position_id`);

--
-- Indexes for table `estimate`
--
ALTER TABLE `estimate`
  ADD PRIMARY KEY (`estimate_id`),
  ADD KEY `foreign key customer` (`customer_id`);

--
-- Indexes for table `finance`
--
ALTER TABLE `finance`
  ADD PRIMARY KEY (`finance_id`);

--
-- Indexes for table `position_type`
--
ALTER TABLE `position_type`
  ADD PRIMARY KEY (`position_id`);

--
-- Indexes for table `promotion`
--
ALTER TABLE `promotion`
  ADD PRIMARY KEY (`promotion_id`);

--
-- Indexes for table `queue`
--
ALTER TABLE `queue`
  ADD PRIMARY KEY (`queue_id`),
  ADD KEY `foreign key emp_id` (`emp_id`),
  ADD KEY `foreign key service_id` (`service_id`),
  ADD KEY `foreign key service_type_id` (`service_type_id`),
  ADD KEY `foreign key customer_id` (`customer_id`);

--
-- Indexes for table `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`role_id`);

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
-- Indexes for table `stock`
--
ALTER TABLE `stock`
  ADD PRIMARY KEY (`stock_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `calendar`
--
ALTER TABLE `calendar`
  MODIFY `table_id` int(5) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `comment`
--
ALTER TABLE `comment`
  MODIFY `comment_id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `customer_id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `emp_id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `estimate`
--
ALTER TABLE `estimate`
  MODIFY `estimate_id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `finance`
--
ALTER TABLE `finance`
  MODIFY `finance_id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `position_type`
--
ALTER TABLE `position_type`
  MODIFY `position_id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `promotion`
--
ALTER TABLE `promotion`
  MODIFY `promotion_id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `queue`
--
ALTER TABLE `queue`
  MODIFY `queue_id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `role`
--
ALTER TABLE `role`
  MODIFY `role_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `service`
--
ALTER TABLE `service`
  MODIFY `service_id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `service_type`
--
ALTER TABLE `service_type`
  MODIFY `service_type_id` int(5) NOT NULL AUTO_INCREMENT COMMENT 'รหัสประเภทการบริการ', AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `stock`
--
ALTER TABLE `stock`
  MODIFY `stock_id` int(5) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `calendar`
--
ALTER TABLE `calendar`
  ADD CONSTRAINT `foreign key queue` FOREIGN KEY (`queue_id`) REFERENCES `queue` (`queue_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `employees_position`
--
ALTER TABLE `employees_position`
  ADD CONSTRAINT `employees_position_ibfk_1` FOREIGN KEY (`emp_id`) REFERENCES `employees` (`emp_id`),
  ADD CONSTRAINT `employees_position_ibfk_2` FOREIGN KEY (`position_id`) REFERENCES `position_type` (`position_id`);

--
-- Constraints for table `estimate`
--
ALTER TABLE `estimate`
  ADD CONSTRAINT `foreign key customer` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`customer_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `queue`
--
ALTER TABLE `queue`
  ADD CONSTRAINT `foreign key customer_id` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`customer_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `foreign key emp_id` FOREIGN KEY (`emp_id`) REFERENCES `employees` (`emp_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `foreign key service_id` FOREIGN KEY (`service_id`) REFERENCES `service` (`service_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `foreign key service_type_id` FOREIGN KEY (`service_type_id`) REFERENCES `service_type` (`service_type_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
