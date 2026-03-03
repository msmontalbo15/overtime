-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 03, 2026 at 12:50 PM
-- Server version: 10.4.22-MariaDB
-- PHP Version: 8.1.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `overtime`
--

-- --------------------------------------------------------

--
-- Table structure for table `accomplishment`
--

CREATE TABLE `accomplishment` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `overtime_date` date NOT NULL,
  `time_in` time NOT NULL,
  `time_out` time NOT NULL,
  `remarks` varchar(10000) NOT NULL,
  `other_day` varchar(30) NOT NULL,
  `is_wfh` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `overtime`
--

CREATE TABLE `overtime` (
  `id` int(11) NOT NULL,
  `request_date` date NOT NULL,
  `activities` text NOT NULL,
  `list_users` varchar(255) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `overtime`
--

INSERT INTO `overtime` (`id`, `request_date`, `activities`, `list_users`, `status`) VALUES
(3, '2026-02-01', '', '[\"39\"]', 0);

-- --------------------------------------------------------

--
-- Table structure for table `purpose`
--

CREATE TABLE `purpose` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `overtime_date` date NOT NULL,
  `time_in` time NOT NULL,
  `time_out` time NOT NULL,
  `remarks` varchar(10000) NOT NULL,
  `other_day` varchar(30) NOT NULL,
  `is_wfh` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `salary_grade`
--

CREATE TABLE `salary_grade` (
  `grade` int(11) NOT NULL,
  `salary` int(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `salary_grade`
--

INSERT INTO `salary_grade` (`grade`, `salary`) VALUES
(1, 13530),
(2, 14372),
(3, 15265),
(4, 16209),
(5, 17205),
(6, 18255),
(7, 19365),
(8, 20534),
(9, 22219),
(10, 24381),
(11, 28512),
(12, 30705),
(13, 32870),
(14, 35434),
(15, 38413),
(16, 41616),
(17, 45138),
(18, 49015);

-- --------------------------------------------------------

--
-- Table structure for table `system_settings`
--

CREATE TABLE `system_settings` (
  `id` int(11) NOT NULL,
  `setting_key` varchar(100) NOT NULL,
  `setting_value` mediumtext DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `system_settings`
--

INSERT INTO `system_settings` (`id`, `setting_key`, `setting_value`, `updated_at`) VALUES
(1, 'logo_path', 'assets/images/logo_1772292915.png', '2026-02-28 15:35:15'),
(2, 'icon_path', 'assets/images/icon_1772293512.ico', '2026-02-28 15:45:12'),
(3, 'app_title', 'Digital Communications Office', '2026-02-28 15:32:46'),
(4, 'theme_color', 'sidebar-dark-indigo', '2026-02-28 15:54:29'),
(5, 'navbar_color', 'navbar-dark navbar-dark', '2026-03-03 05:34:28'),
(6, 'template_request_header', 'Ms. FLOCERFIDA D. VILLAMAR\r\nOfficer-in-Charge, Human Resources Management Office', '2026-02-28 15:32:46'),
(7, 'template_request_through', 'Ms. FELIZA SALAZAR\r\nHead, Payroll Unit', '2026-02-28 15:32:46'),
(8, 'template_signatory_name', 'Frances Marion Salazar', '2026-02-28 15:32:46'),
(9, 'template_signatory_title', 'Officer-In-Charge', '2026-02-28 15:32:46'),
(10, 'template_signatory_office', 'Digital Communications Office', '2026-02-28 15:32:46'),
(11, 'template_summary_for_name', 'HON. WES GATCHALIAN', '2026-02-28 15:32:46'),
(12, 'template_summary_for_title', 'City Mayor', '2026-02-28 15:32:46');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `username` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `middle` varchar(10) NOT NULL,
  `possition` varchar(255) NOT NULL,
  `user_type` tinyint(4) NOT NULL DEFAULT 1,
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `grade` int(3) NOT NULL,
  `appointment` int(1) NOT NULL DEFAULT 1,
  `purpose` varchar(225) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `username`, `password`, `first_name`, `last_name`, `middle`, `possition`, `user_type`, `status`, `grade`, `appointment`, `purpose`) VALUES
(40, 'admin', 'b457e4b5d3dabd83e98ad6dee50ebf4ad602fc950d7fae202b70b4314e47694041f070b51bb4145d1b6f4cfc18450b4cb99d9739594674e3d2474051e8e391cc', 'ADMIN', 'DEFAULT', 'A', 'A', 3, 1, 1, 1, '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accomplishment`
--
ALTER TABLE `accomplishment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `overtime`
--
ALTER TABLE `overtime`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `purpose`
--
ALTER TABLE `purpose`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `salary_grade`
--
ALTER TABLE `salary_grade`
  ADD PRIMARY KEY (`grade`);

--
-- Indexes for table `system_settings`
--
ALTER TABLE `system_settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `setting_key` (`setting_key`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accomplishment`
--
ALTER TABLE `accomplishment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `overtime`
--
ALTER TABLE `overtime`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `purpose`
--
ALTER TABLE `purpose`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `system_settings`
--
ALTER TABLE `system_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
