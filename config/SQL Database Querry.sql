-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 07, 2026 at 12:58 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `fault_management_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `faults`
--

CREATE TABLE `faults` (
  `id` int(11) NOT NULL,
  `ticket_id` varchar(10) NOT NULL,
  `company_name` varchar(100) NOT NULL,
  `branch` varchar(100) DEFAULT NULL,
  `employee_id` varchar(30) NOT NULL,
  `fault_type` varchar(50) NOT NULL,
  `description` text NOT NULL,
  `status` enum('New','Assigned','In Progress','Resolved') DEFAULT 'New',
  `assigned_to` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `technician_notes` text DEFAULT NULL,
  `started_at` datetime DEFAULT NULL,
  `resolved_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `faults`
--

INSERT INTO `faults` (`id`, `ticket_id`, `company_name`, `branch`, `employee_id`, `fault_type`, `description`, `status`, `assigned_to`, `created_at`, `technician_notes`, `started_at`, `resolved_at`) VALUES
(1, 'TIC-2548', 'Monik Agro Ventures Pvt Ltd', NULL, 'MA000255', 'General', 'fire on badulla branch ', 'Resolved', 3, '2026-05-06 09:30:42', NULL, NULL, NULL),
(2, 'TIC-5135', 'Monik Lands Pvt Ltd', NULL, 'ML000170', 'General', 'yyyy', 'In Progress', 3, '2026-05-06 10:35:17', NULL, NULL, NULL),
(3, 'TIC-1590', 'Monik Lands Pvt Ltd', NULL, 'ML000170', 'General', 'eeee', 'Resolved', 3, '2026-05-07 03:33:01', NULL, NULL, NULL),
(4, 'TIC-9889', 'Monik Agro Ventures Pvt Ltd', NULL, 'MA000171', 'General', 'laptop broken\r\n', 'In Progress', 3, '2026-05-07 03:45:39', NULL, NULL, NULL),
(5, 'TIC-7509', 'Ceylon Monik Building Society Ltd', NULL, 'CB000777', 'General', 'bulb didn\'t work', 'In Progress', 4, '2026-05-07 04:01:11', NULL, NULL, NULL),
(6, 'TIC-9763', 'Monik Trading Pvt Ltd', NULL, 'MT000888', 'General', 'phone broke ', 'Resolved', 3, '2026-05-07 04:20:13', 'this work will take three days ', NULL, NULL),
(7, 'TIC-9023', 'Monik Lands Pvt Ltd', NULL, 'ML000888', 'General', 'phone didn\'t work', 'In Progress', 3, '2026-05-07 05:17:50', 'this work will get two days', NULL, NULL),
(8, 'TIC-6128', 'Commercial Micro Credit Investment Trust', NULL, 'CMC000111', 'General', 'check', 'Resolved', 3, '2026-05-07 06:23:49', 'one minute', '2026-05-07 11:55:11', '2026-05-07 11:55:28'),
(9, 'TIC-5743', 'Monik Lands Pvt Ltd', 'Badulla', 'ML000775', 'General', 'check 2', 'Resolved', 6, '2026-05-07 07:02:39', 'two months', '2026-05-07 12:34:00', '2026-05-07 12:34:31');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('Main Admin','Assign Admin','Technician') NOT NULL,
  `profession` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `full_name`, `username`, `password`, `role`, `profession`, `created_at`) VALUES
(1, 'Pawan Dharmasena', 'admin', '$2y$10$X9G95bv6XBkE86o0KxCRm.WpkSyeRZtt1RBzYIXSUUBdd0ylfubVq', 'Main Admin', NULL, '2026-05-06 07:24:43'),
(2, 'Dharmasena', 'dharmasena@gmail.com', '$2y$10$N959WBf/0nZwDD.Sr4FzJehSmmu47UFgHs.SUh449F4Gb/DsrPogC', 'Assign Admin', NULL, '2026-05-06 09:29:03'),
(3, 'Nimsara', 'Nimsara@gmail.com', '$2y$10$W/A3me8TJyBEAgL8caps5edWEePcElbEHLT./o2L29Z7T.DpSDb7a', 'Technician', 'IT related', '2026-05-06 09:30:12'),
(4, 'Pawan Nimsara', 'pawan1@gmail.com', '$2y$10$048PW4/7Zq820DCTSzxYhu4BA53fG8oc99qXwRdFkQlyDGP5mh4UG', 'Technician', 'Electrician', '2026-05-07 04:00:18'),
(5, 'pawan', 'pawan2@gmail.com', '$2y$10$g13lpCHjus966nlOwaLzy.BdVPkfmAhAq7m.2tcU/D9fOFlJ4CiHe', 'Assign Admin', '', '2026-05-07 05:28:25'),
(6, 'chamuditha', 'chamuditha@gmail.com', '$2y$10$md5lZGc1bYfG0bx74IPlD.aP8DuQpI.OnMt59Oq77090jUIMDFG56', 'Technician', 'IT related', '2026-05-07 05:29:16'),
(7, 'chamuditha1', 'chamuditha1@gmail.com', '$2y$10$rI2DNWGdIjhEIg5VinwyoOZ7nYshmHsP08Bwz.LsXvgctLssS3fFy', 'Technician', 'IT related', '2026-05-07 05:33:42'),
(8, 'chamuditha2', 'chamuditha2@gmail.com', '$2y$10$VXbZcIJ5CN.tvfhOF5s8gujwTLLw3emDwuPdu29z78XVVhV14gKt6', 'Technician', 'Electrician', '2026-05-07 05:41:31'),
(9, 'chamuditha3', 'chamuditha3@gmail.com', '$2y$10$bYfZvAwRVm6MOCIDXrdl2.xQJxIoEVJbx6Le4DPcsAlOxXCqTF5Y6', 'Technician', 'Electrician', '2026-05-07 05:47:06'),
(10, 'Pawan 1', 'pawan3@gmail.com', '$2y$10$QRPLfLRANTMnLLeQ4jj5uOhkCVFQ2Q.g0CLCkZuIBgg4UCq3LRQdS', 'Assign Admin', 'Electrician', '2026-05-07 05:51:28'),
(11, 'Pawan 5', 'pawan5@gmail.com', '$2y$10$nuDStBvms.W45nuQWm.3LO5d4vf7c3CdSiW/a4aYW9c.SyT5MXobO', 'Assign Admin', '', '2026-05-07 06:07:05'),
(12, 'chamuditha6', 'chamuditha5@gmail.com', '$2y$10$97WiH.M5ZQ77xBNlQCxVG.DBLWuqfw3Bxw/s1VQe53Nu0Y53qUkYK', 'Technician', 'IT related', '2026-05-07 10:27:04');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `faults`
--
ALTER TABLE `faults`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ticket_id` (`ticket_id`),
  ADD KEY `assigned_to` (`assigned_to`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `faults`
--
ALTER TABLE `faults`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `faults`
--
ALTER TABLE `faults`
  ADD CONSTRAINT `faults_ibfk_1` FOREIGN KEY (`assigned_to`) REFERENCES `users` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
