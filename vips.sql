-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 13, 2026 at 07:31 PM
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
-- Database: `vips`
--

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `name` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `role` enum('user','event_manager','admin') DEFAULT 'user',
  `phone` varchar(20) DEFAULT NULL,
  `trained` tinyint(1) DEFAULT 0,
  `dataset_path` varchar(255) DEFAULT NULL,
  `is_verified` tinyint(1) DEFAULT 0,
  `dataset_generated` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `name`, `password`, `created_at`, `role`, `phone`, `trained`, `dataset_path`, `is_verified`, `dataset_generated`) VALUES
(2, 'test@example.com', 'test', '$2y$10$/jwOMKWp2JSpGEMDkg3vVuReSvmeEChfj6AWgpPfR9.o9VVugk9ym', '2025-05-16 11:46:32', 'user', NULL, 0, NULL, 0, 1),
(3, 'asif17111998@gmail.com', 'admin', '$2y$10$ejhyJr5r4G1KbinQ/XYHu.fYz5rGQgqRvbU4Xd7qdGrCQnmfeQrmu', '2026-05-13 10:58:40', 'admin', '+919875535211', 0, NULL, 1, 0),
(4, 'event@manager.com', 'manager', '$2y$10$PZvbVS0L3APMWQNpBoVeHuflhyLoiWljRAJI.WPaILUpfObVE4u0y', '2026-05-13 11:00:21', 'event_manager', NULL, 0, NULL, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `verifications`
--

CREATE TABLE `verifications` (
  `phone` varchar(20) NOT NULL,
  `code_hash` varchar(255) NOT NULL,
  `expires_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `verifications`
--

INSERT INTO `verifications` (`phone`, `code_hash`, `expires_at`) VALUES
('+917003940156', '$2y$10$uJImRldUgGZtzED/oxPZ3eHzSuB9/0i83RW6Zc0571J58Vg279j0e', '2025-11-11 18:14:58'),
('6289763312', '$2y$10$JKku1un8UDDryKoLw0heeOm2ZMYEUKVRQ73QOiv4UPqZVPmE8CzSC', '2026-04-11 16:29:59'),
('9875535211', '$2y$10$FepeLUWrg7/zvQUxCI0niOOIxi4fRIvFHmM22A7WMa44pgKBTexbC', '2026-04-11 16:29:45');

-- --------------------------------------------------------

--
-- Table structure for table `verification_codes`
--

CREATE TABLE `verification_codes` (
  `id` int(11) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `code_hash` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL,
  `expires_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `verifications`
--
ALTER TABLE `verifications`
  ADD PRIMARY KEY (`phone`);

--
-- Indexes for table `verification_codes`
--
ALTER TABLE `verification_codes`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `verification_codes`
--
ALTER TABLE `verification_codes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
