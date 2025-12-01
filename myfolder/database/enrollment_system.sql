-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Nov 30, 2025 at 10:48 PM
-- Server version: 9.1.0
-- PHP Version: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `enrollment_system`
--

-- --------------------------------------------------------

--
-- Stand-in structure for view `active_students_view`
-- (See below for the actual view)
--
DROP VIEW IF EXISTS `active_students_view`;
CREATE TABLE IF NOT EXISTS `active_students_view` (
`student_id` varchar(50)
,`student_status` enum('active','graduated','transferred','archived')
,`archive_date` timestamp
,`archive_reason` varchar(255)
,`student_name` varchar(100)
,`grade_level` varchar(20)
,`gender` varchar(10)
,`birthdate` date
,`religion` varchar(50)
,`address` text
,`contact_number` varchar(20)
,`father_name` varchar(100)
,`father_occupation` varchar(100)
,`mother_name` varchar(100)
,`mother_occupation` varchar(100)
,`guardian_name` varchar(100)
,`guardian_relationship` varchar(50)
,`previous_school` varchar(200)
,`last_school_year` varchar(20)
);

-- --------------------------------------------------------

--
-- Table structure for table `activity_log`
--

DROP TABLE IF EXISTS `activity_log`;
CREATE TABLE IF NOT EXISTS `activity_log` (
  `log_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `username` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `role` enum('admin','teacher','student') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `activity_type` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `activity_description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `login_time` datetime DEFAULT CURRENT_TIMESTAMP,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`log_id`),
  KEY `idx_login_time` (`login_time`),
  KEY `idx_role` (`role`),
  KEY `idx_activity_type` (`activity_type`),
  KEY `user_id` (`user_id`),
  KEY `idx_username` (`username`),
  KEY `idx_datetime_role` (`login_time`,`role`)
) ENGINE=InnoDB AUTO_INCREMENT=70 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `activity_log`
--

INSERT INTO `activity_log` (`log_id`, `user_id`, `username`, `role`, `activity_type`, `activity_description`, `login_time`, `ip_address`) VALUES
(1, NULL, '2024-0001', 'student', 'login', 'Student logged in successfully', '2025-11-20 11:37:19', '127.0.0.1'),
(2, NULL, '2024-0002', 'student', 'login', 'Student logged in successfully', '2025-11-20 12:37:19', '127.0.0.1'),
(3, NULL, 'admin', 'admin', 'login', 'Administrator logged in', '2025-11-20 13:07:19', '127.0.0.1'),
(4, NULL, '2024-0003', 'student', 'enrollment_submitted', 'Enrollment submitted for approval', '2025-11-20 13:22:19', '127.0.0.1'),
(5, NULL, 'admin', 'admin', 'student_deleted', 'Admin deleted student: Jhonny Doe Joe (2024-0004)', '2025-11-23 14:50:25', NULL),
(6, NULL, 'admin', 'admin', 'student_deleted', 'Admin deleted student: Juan Carlos Reyes (2024-0001)', '2025-11-23 15:07:18', NULL),
(7, NULL, 'admin', 'admin', 'student_deleted', 'Admin deleted student: Maria Sofia Santos (2024-0002)', '2025-11-23 15:07:21', NULL),
(8, NULL, 'admin', 'admin', 'student_deleted', 'Admin deleted student: Cyruz Dave Juanites (2024-0003)', '2025-11-24 01:08:13', NULL),
(9, NULL, 'admin', 'admin', 'student_deleted', 'Admin deleted student: Alyssa Hope Garcia (2024-0127)', '2025-11-24 12:54:24', NULL),
(10, NULL, 'admin', 'admin', 'student_deleted', 'Admin deleted student: Alyssa Jean Cruz (2024-0153)', '2025-11-24 12:55:30', NULL),
(11, NULL, 'admin', 'admin', 'student_deleted', 'Admin deleted student: Ariana Joyce Rivera (2024-0101)', '2025-11-24 12:55:32', NULL),
(12, NULL, 'admin', 'admin', 'student_deleted', 'Admin deleted student: Brandon Tyler Lim (2024-0128)', '2025-11-24 12:55:33', NULL),
(13, NULL, 'admin', 'admin', 'student_deleted', 'Admin deleted student: Brent Harvey Santos (2024-0154)', '2025-11-24 12:55:34', NULL),
(14, NULL, 'admin', 'admin', 'student_deleted', 'Admin deleted student: Bryan Cedrick Ramos (2024-0102)', '2025-11-24 12:55:35', NULL),
(15, NULL, 'admin', 'admin', 'student_deleted', 'Admin deleted student: Cassandra Mila Villanueva (2024-0129)', '2025-11-24 12:55:35', NULL),
(16, NULL, 'admin', 'admin', 'student_deleted', 'Admin deleted student: Celine Raine Tan (2024-0155)', '2025-11-24 12:55:36', NULL),
(17, NULL, 'admin', 'admin', 'student_deleted', 'Admin deleted student: Cheska Elaine Lopez (2024-0103)', '2025-11-24 12:55:36', NULL),
(18, NULL, 'admin', 'admin', 'student_deleted', 'Admin deleted student: Darren Keith Santiago (2024-0104)', '2025-11-24 12:55:37', NULL),
(19, NULL, 'admin', 'admin', 'student_deleted', 'Admin deleted student: Darren Luke Villanueva (2024-0156)', '2025-11-24 12:55:37', NULL),
(20, NULL, 'admin', 'admin', 'student_deleted', 'Admin deleted student: Dylan Zach Torres (2024-0130)', '2025-11-24 12:55:38', NULL),
(21, NULL, 'admin', 'admin', 'student_deleted', 'Admin deleted student: Eliza Danielle Cruz (2024-0105)', '2025-11-24 12:55:39', NULL),
(22, NULL, 'admin', 'admin', 'student_deleted', 'Admin deleted student: Ella Faye Bautista (2024-0157)', '2025-11-24 12:55:39', NULL),
(23, NULL, 'admin', 'admin', 'student_deleted', 'Admin deleted student: Eunice Marie Silva (2024-0131)', '2025-11-24 12:55:40', NULL),
(24, NULL, 'admin', 'admin', 'student_deleted', 'Admin deleted student: Felix Adrian Dizon (2024-0158)', '2025-11-24 12:55:40', NULL),
(25, NULL, 'admin', 'admin', 'student_deleted', 'Admin deleted student: Felix Jordan Santos (2024-0106)', '2025-11-24 12:55:41', NULL),
(26, NULL, 'admin', 'admin', 'student_deleted', 'Admin deleted student: Francis Emil Ramos (2024-0132)', '2025-11-24 12:55:41', NULL),
(27, NULL, 'admin', 'admin', 'student_deleted', 'Admin deleted student: Georgia Lane Uy (2024-0159)', '2025-11-24 12:55:42', NULL),
(28, NULL, 'admin', 'admin', 'student_deleted', 'Admin deleted student: Gianna Faith Dela Cruz (2024-0133)', '2025-11-24 12:55:43', NULL),
(29, NULL, 'admin', 'admin', 'student_deleted', 'Admin deleted student: Grace Andrea Dizon (2024-0107)', '2025-11-24 12:55:43', NULL),
(30, NULL, 'admin', 'admin', 'student_deleted', 'Admin deleted student: Hanz Dominic Reyes (2024-0108)', '2025-11-24 12:55:44', NULL),
(31, NULL, 'admin', 'admin', 'student_deleted', 'Admin deleted student: Harold Zachary Reyes (2024-0134)', '2025-11-24 12:55:44', NULL),
(32, NULL, 'admin', 'admin', 'student_deleted', 'Admin deleted student: Harvey Logan Cruz (2024-0160)', '2025-11-24 12:55:45', NULL),
(33, NULL, 'admin', 'admin', 'student_deleted', 'Admin deleted student: Isabella Marie Bautista (2024-0109)', '2025-11-24 12:55:45', NULL),
(34, NULL, 'admin', 'admin', 'student_deleted', 'Admin deleted student: Isabelle Joy Aquino (2024-0135)', '2025-11-24 12:55:46', NULL),
(35, NULL, 'admin', 'admin', 'student_deleted', 'Admin deleted student: Jared Cole Evangelista (2024-0136)', '2025-11-24 12:55:47', NULL),
(36, NULL, 'admin', 'admin', 'student_deleted', 'Admin deleted student: Jaxon Tyler Gonzales (2024-0110)', '2025-11-24 12:55:47', NULL),
(37, NULL, 'admin', 'admin', 'student_deleted', 'Admin deleted student: Kyla Denise Bautista (2024-0137)', '2025-11-24 12:55:48', NULL),
(38, NULL, 'admin', 'admin', 'student_deleted', 'Admin deleted student: Kylie Anne Mendoza (2024-0111)', '2025-11-24 12:55:48', NULL),
(39, NULL, 'admin', 'admin', 'student_deleted', 'Admin deleted student: Liam Carter Santos (2024-0112)', '2025-11-24 12:55:49', NULL),
(40, NULL, 'admin', 'admin', 'student_deleted', 'Admin deleted student: Lucas Henry Miranda (2024-0138)', '2025-11-24 12:55:49', NULL),
(41, NULL, 'admin', 'admin', 'student_deleted', 'Admin deleted student: Megan Ysabelle Flores (2024-0139)', '2025-11-24 12:55:50', NULL),
(42, NULL, 'admin', 'admin', 'student_deleted', 'Admin deleted student: Mia Gabrielle Tan (2024-0113)', '2025-11-24 12:55:53', NULL),
(43, NULL, 'admin', 'admin', 'student_deleted', 'Admin deleted student: Nathaniel Jay Cruz (2024-0140)', '2025-11-24 12:55:53', NULL),
(44, NULL, 'admin', 'admin', 'student_deleted', 'Admin deleted student: Noah Benjamin Dela Cruz (2024-0114)', '2025-11-24 12:55:54', NULL),
(45, NULL, 'admin', 'admin', 'student_deleted', 'Admin deleted student: Olivia Faith Ramos (2024-0115)', '2025-11-24 12:55:55', NULL),
(46, NULL, 'admin', 'admin', 'student_deleted', 'Admin deleted student: Olivia Sarah Mendoza (2024-0141)', '2025-11-24 12:55:55', NULL),
(47, NULL, 'admin', 'admin', 'student_deleted', 'Admin deleted student: Parker Joshua Reyes (2024-0116)', '2025-11-24 12:55:56', NULL),
(48, NULL, 'admin', 'admin', 'student_deleted', 'Admin deleted student: Paul Adrian Dizon (2024-0142)', '2025-11-24 12:55:56', NULL),
(49, NULL, 'admin', 'admin', 'student_deleted', 'Admin deleted student: Queenie Mae Velasco (2024-0117)', '2025-11-24 12:55:57', NULL),
(50, NULL, 'admin', 'admin', 'student_deleted', 'Admin deleted student: Quinn Avery Santos (2024-0143)', '2025-11-24 12:55:58', NULL),
(51, NULL, 'admin', 'admin', 'student_deleted', 'Admin deleted student: Raven Isaiah Torres (2024-0118)', '2025-11-24 12:55:58', NULL),
(52, NULL, 'admin', 'admin', 'student_deleted', 'Admin deleted student: Riley Jaxon Tan (2024-0144)', '2025-11-24 12:55:59', NULL),
(53, NULL, 'admin', 'admin', 'student_deleted', 'Admin deleted student: Serena Mae Villanueva (2024-0145)', '2025-11-24 12:55:59', NULL),
(54, NULL, 'admin', 'admin', 'student_deleted', 'Admin deleted student: Sophia Claire Bautista (2024-0119)', '2025-11-24 12:56:00', NULL),
(55, NULL, 'admin', 'admin', 'student_deleted', 'Admin deleted student: Travis Leon Gonzaga (2024-0146)', '2025-11-24 12:56:01', NULL),
(56, NULL, 'admin', 'admin', 'student_deleted', 'Admin deleted student: Tristan Angelo Uy (2024-0120)', '2025-11-24 12:56:01', NULL),
(57, NULL, 'admin', 'admin', 'student_deleted', 'Admin deleted student: Uma Claire Bautista (2024-0147)', '2025-11-24 12:56:02', NULL),
(58, NULL, 'admin', 'admin', 'student_deleted', 'Admin deleted student: Uma Leanne Navarro (2024-0121)', '2025-11-24 12:56:03', NULL),
(59, NULL, 'admin', 'admin', 'student_deleted', 'Admin deleted student: Victor James Ramirez (2024-0148)', '2025-11-24 12:56:03', NULL),
(60, NULL, 'admin', 'admin', 'student_deleted', 'Admin deleted student: Vince Gabriel Ramirez (2024-0122)', '2025-11-24 12:56:04', NULL),
(61, NULL, 'admin', 'admin', 'student_deleted', 'Admin deleted student: Willow Anne Cruz (2024-0123)', '2025-11-24 12:56:05', NULL),
(62, NULL, 'admin', 'admin', 'student_deleted', 'Admin deleted student: Willow Eve Cruz (2024-0149)', '2025-11-24 12:56:05', NULL),
(63, NULL, 'admin', 'admin', 'student_deleted', 'Admin deleted student: Xander Levi Santos (2024-0124)', '2025-11-24 12:56:06', NULL),
(64, NULL, 'admin', 'admin', 'student_deleted', 'Admin deleted student: Xavier Paul Angeles (2024-0150)', '2025-11-24 12:56:07', NULL),
(65, NULL, 'admin', 'admin', 'student_deleted', 'Admin deleted student: Yana Celeste Javier (2024-0151)', '2025-11-24 12:56:07', NULL),
(66, NULL, 'admin', 'admin', 'student_deleted', 'Admin deleted student: Yza Marielle Dizon (2024-0125)', '2025-11-24 12:56:08', NULL),
(67, NULL, 'admin', 'admin', 'student_deleted', 'Admin deleted student: Zachary Miles Lorenzo (2024-0152)', '2025-11-24 12:56:09', NULL),
(68, NULL, 'admin', 'admin', 'student_deleted', 'Admin deleted student: Zion Matthew Uy (2024-0126)', '2025-11-24 12:56:09', NULL),
(69, NULL, 'admin', 'admin', 'enrollment_approved', 'Admin approved enrollment for Cyruz Dave Juanites (2024-0171)', '2025-11-24 13:37:37', NULL);

-- --------------------------------------------------------

--
-- Stand-in structure for view `archived_students_view`
-- (See below for the actual view)
--
DROP VIEW IF EXISTS `archived_students_view`;
CREATE TABLE IF NOT EXISTS `archived_students_view` (
`student_id` varchar(50)
,`student_status` enum('active','graduated','transferred','archived')
,`archive_date` timestamp
,`archive_reason` varchar(255)
,`student_name` varchar(100)
,`grade_level` varchar(20)
,`gender` varchar(10)
,`birthdate` date
,`religion` varchar(50)
,`address` text
,`contact_number` varchar(20)
,`father_name` varchar(100)
,`father_occupation` varchar(100)
,`mother_name` varchar(100)
,`mother_occupation` varchar(100)
,`guardian_name` varchar(100)
,`guardian_relationship` varchar(50)
,`previous_school` varchar(200)
,`last_school_year` varchar(20)
);

-- --------------------------------------------------------

--
-- Table structure for table `archive_log`
--

DROP TABLE IF EXISTS `archive_log`;
CREATE TABLE IF NOT EXISTS `archive_log` (
  `log_id` int NOT NULL AUTO_INCREMENT,
  `student_id` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `action` enum('archived','restored') COLLATE utf8mb4_general_ci NOT NULL,
  `action_by` varchar(50) COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Admin user_id or username',
  `action_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `reason` text COLLATE utf8mb4_general_ci,
  `previous_status` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `new_status` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`log_id`),
  KEY `student_id` (`student_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `archive_log`
--

INSERT INTO `archive_log` (`log_id`, `student_id`, `action`, `action_by`, `action_date`, `reason`, `previous_status`, `new_status`) VALUES
(1, '2022-0001', 'archived', 'admin', '2023-04-30 02:00:00', 'Graduated from elementary', 'active', 'graduated'),
(2, '2022-0002', 'archived', 'admin', '2023-04-30 02:00:00', 'Graduated from elementary', 'active', 'graduated'),
(3, '2022-0003', 'archived', 'admin', '2023-04-30 02:00:00', 'Graduated from elementary', 'active', 'graduated'),
(4, '2022-0004', 'archived', 'admin', '2023-01-15 06:30:00', 'Transferred to another school', 'active', 'transferred'),
(5, '2022-0005', 'archived', 'admin', '2023-04-30 02:00:00', 'Graduated from elementary', 'active', 'graduated'),
(6, '2023-0001', 'archived', 'admin', '2024-04-30 02:00:00', 'Graduated from elementary', 'active', 'graduated'),
(7, '2023-0002', 'archived', 'admin', '2024-04-30 02:00:00', 'Graduated from elementary', 'active', 'graduated'),
(8, '2023-0003', 'archived', 'admin', '2024-04-30 02:00:00', 'Graduated from elementary', 'active', 'graduated'),
(9, '2023-0004', 'archived', 'admin', '2024-02-20 01:15:00', 'Transferred to international school', 'active', 'transferred'),
(10, '2023-0005', 'archived', 'admin', '2024-04-30 02:00:00', 'Graduated from elementary', 'active', 'graduated');

-- --------------------------------------------------------

--
-- Table structure for table `enrollment`
--

DROP TABLE IF EXISTS `enrollment`;
CREATE TABLE IF NOT EXISTS `enrollment` (
  `enrollment_id` int NOT NULL AUTO_INCREMENT,
  `student_id` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `section_id` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `enrollment_status` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `date_enrolled` date NOT NULL,
  `school_year` varchar(20) COLLATE utf8mb4_general_ci DEFAULT '2024-2025',
  PRIMARY KEY (`enrollment_id`),
  KEY `student_id` (`student_id`),
  KEY `section_id` (`section_id`)
) ENGINE=InnoDB AUTO_INCREMENT=386 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `enrollment`
--

INSERT INTO `enrollment` (`enrollment_id`, `student_id`, `section_id`, `enrollment_status`, `date_enrolled`, `school_year`) VALUES
(303, '2024-0101', 'SEC-K-A', 'enrolled', '2025-06-01', '2024-2025'),
(304, '2024-0102', 'SEC-K-A', 'enrolled', '2025-06-01', '2024-2025'),
(305, '2024-0103', 'SEC-K-A', 'enrolled', '2025-06-01', '2024-2025'),
(306, '2024-0104', 'SEC-K-A', 'enrolled', '2025-06-01', '2024-2025'),
(307, '2024-0105', 'SEC-K-A', 'enrolled', '2025-06-01', '2024-2025'),
(308, '2024-0106', 'SEC-K-A', 'enrolled', '2025-06-01', '2024-2025'),
(309, '2024-0107', 'SEC-K-A', 'enrolled', '2025-06-01', '2024-2025'),
(310, '2024-0108', 'SEC-K-A', 'enrolled', '2025-06-01', '2024-2025'),
(311, '2024-0109', 'SEC-K-A', 'enrolled', '2025-06-01', '2024-2025'),
(312, '2024-0110', 'SEC-K-A', 'enrolled', '2025-06-01', '2024-2025'),
(313, '2024-0111', 'SEC-1-A', 'enrolled', '2025-06-01', '2024-2025'),
(314, '2024-0112', 'SEC-1-A', 'enrolled', '2025-06-01', '2024-2025'),
(315, '2024-0113', 'SEC-1-A', 'enrolled', '2025-06-01', '2024-2025'),
(316, '2024-0114', 'SEC-1-A', 'enrolled', '2025-06-01', '2024-2025'),
(317, '2024-0115', 'SEC-1-A', 'enrolled', '2025-06-01', '2024-2025'),
(318, '2024-0116', 'SEC-1-A', 'enrolled', '2025-06-01', '2024-2025'),
(319, '2024-0117', 'SEC-1-A', 'enrolled', '2025-06-01', '2024-2025'),
(320, '2024-0118', 'SEC-1-A', 'enrolled', '2025-06-01', '2024-2025'),
(321, '2024-0119', 'SEC-1-A', 'enrolled', '2025-06-01', '2024-2025'),
(322, '2024-0120', 'SEC-1-A', 'enrolled', '2025-06-01', '2024-2025'),
(323, '2024-0121', 'SEC-2-A', 'enrolled', '2025-06-01', '2024-2025'),
(324, '2024-0122', 'SEC-2-A', 'enrolled', '2025-06-01', '2024-2025'),
(325, '2024-0123', 'SEC-2-A', 'enrolled', '2025-06-01', '2024-2025'),
(326, '2024-0124', 'SEC-2-A', 'enrolled', '2025-06-01', '2024-2025'),
(327, '2024-0125', 'SEC-2-A', 'enrolled', '2025-06-01', '2024-2025'),
(328, '2024-0126', 'SEC-2-A', 'enrolled', '2025-06-01', '2024-2025'),
(329, '2024-0127', 'SEC-2-A', 'enrolled', '2025-06-01', '2024-2025'),
(330, '2024-0128', 'SEC-2-A', 'enrolled', '2025-06-01', '2024-2025'),
(331, '2024-0129', 'SEC-2-A', 'enrolled', '2025-06-01', '2024-2025'),
(332, '2024-0130', 'SEC-2-A', 'enrolled', '2025-06-01', '2024-2025'),
(333, '2024-0131', 'SEC-3-A', 'enrolled', '2025-06-01', '2024-2025'),
(334, '2024-0132', 'SEC-3-A', 'enrolled', '2025-06-01', '2024-2025'),
(335, '2024-0133', 'SEC-3-A', 'enrolled', '2025-06-01', '2024-2025'),
(336, '2024-0134', 'SEC-3-A', 'enrolled', '2025-06-01', '2024-2025'),
(337, '2024-0135', 'SEC-3-A', 'enrolled', '2025-06-01', '2024-2025'),
(338, '2024-0136', 'SEC-3-A', 'enrolled', '2025-06-01', '2024-2025'),
(339, '2024-0137', 'SEC-3-A', 'enrolled', '2025-06-01', '2024-2025'),
(340, '2024-0138', 'SEC-3-A', 'enrolled', '2025-06-01', '2024-2025'),
(341, '2024-0139', 'SEC-3-A', 'enrolled', '2025-06-01', '2024-2025'),
(342, '2024-0140', 'SEC-3-A', 'enrolled', '2025-06-01', '2024-2025'),
(343, '2024-0141', 'SEC-4-A', 'enrolled', '2025-06-01', '2024-2025'),
(344, '2024-0142', 'SEC-4-A', 'enrolled', '2025-06-01', '2024-2025'),
(345, '2024-0143', 'SEC-4-A', 'enrolled', '2025-06-01', '2024-2025'),
(346, '2024-0144', 'SEC-4-A', 'enrolled', '2025-06-01', '2024-2025'),
(347, '2024-0145', 'SEC-4-A', 'enrolled', '2025-06-01', '2024-2025'),
(348, '2024-0146', 'SEC-4-A', 'enrolled', '2025-06-01', '2024-2025'),
(349, '2024-0147', 'SEC-4-A', 'enrolled', '2025-06-01', '2024-2025'),
(350, '2024-0148', 'SEC-4-A', 'enrolled', '2025-06-01', '2024-2025'),
(351, '2024-0149', 'SEC-4-A', 'enrolled', '2025-06-01', '2024-2025'),
(352, '2024-0150', 'SEC-4-A', 'enrolled', '2025-06-01', '2024-2025'),
(353, '2024-0151', 'SEC-5-A', 'enrolled', '2025-06-01', '2024-2025'),
(354, '2024-0152', 'SEC-5-A', 'enrolled', '2025-06-01', '2024-2025'),
(355, '2024-0153', 'SEC-5-A', 'enrolled', '2025-06-01', '2024-2025'),
(356, '2024-0154', 'SEC-5-A', 'enrolled', '2025-06-01', '2024-2025'),
(357, '2024-0155', 'SEC-5-A', 'enrolled', '2025-06-01', '2024-2025'),
(358, '2024-0156', 'SEC-5-A', 'enrolled', '2025-06-01', '2024-2025'),
(359, '2024-0157', 'SEC-5-A', 'enrolled', '2025-06-01', '2024-2025'),
(360, '2024-0158', 'SEC-5-A', 'enrolled', '2025-06-01', '2024-2025'),
(361, '2024-0159', 'SEC-5-A', 'enrolled', '2025-06-01', '2024-2025'),
(362, '2024-0160', 'SEC-5-A', 'enrolled', '2025-06-01', '2024-2025'),
(363, '2024-0161', 'SEC-6-A', 'enrolled', '2025-06-01', '2024-2025'),
(364, '2024-0162', 'SEC-6-A', 'enrolled', '2025-06-01', '2024-2025'),
(365, '2024-0163', 'SEC-6-A', 'enrolled', '2025-06-01', '2024-2025'),
(366, '2024-0164', 'SEC-6-A', 'enrolled', '2025-06-01', '2024-2025'),
(367, '2024-0165', 'SEC-6-A', 'enrolled', '2025-06-01', '2024-2025'),
(368, '2024-0166', 'SEC-6-A', 'enrolled', '2025-06-01', '2024-2025'),
(369, '2024-0167', 'SEC-6-A', 'enrolled', '2025-06-01', '2024-2025'),
(370, '2024-0168', 'SEC-6-A', 'enrolled', '2025-06-01', '2024-2025'),
(371, '2024-0169', 'SEC-6-A', 'enrolled', '2025-06-01', '2024-2025'),
(372, '2024-0170', 'SEC-6-A', 'enrolled', '2025-06-01', '2024-2025'),
(373, '2024-0171', 'SEC-1-A', 'enrolled', '2025-11-24', '2024-2025'),
(374, '2024-0172', 'SEC-K-A', 'enrolled', '2025-11-24', '2024-2025'),
(375, '2024-0173', 'SEC-1-A', 'enrolled', '2025-11-25', '2024-2025'),
(376, '2022-0001', 'SEC-6-A', 'graduated', '2022-06-01', '2022-2023'),
(377, '2022-0002', 'SEC-6-A', 'graduated', '2022-06-01', '2022-2023'),
(378, '2022-0003', 'SEC-6-A', 'graduated', '2022-06-01', '2022-2023'),
(379, '2022-0004', 'SEC-5-A', 'transferred', '2022-06-01', '2022-2023'),
(380, '2022-0005', 'SEC-6-A', 'graduated', '2022-06-01', '2022-2023'),
(381, '2023-0001', 'SEC-6-A', 'graduated', '2023-06-01', '2023-2024'),
(382, '2023-0002', 'SEC-6-A', 'graduated', '2023-06-01', '2023-2024'),
(383, '2023-0003', 'SEC-6-A', 'graduated', '0000-00-00', '2023-2024'),
(384, '2023-0004', 'SEC-4-A', 'transferred', '2023-06-01', '2023-2024'),
(385, '2023-0005', 'SEC-6-A', 'graduated', '2023-06-01', '2023-2024');

-- --------------------------------------------------------

--
-- Table structure for table `grade`
--

DROP TABLE IF EXISTS `grade`;
CREATE TABLE IF NOT EXISTS `grade` (
  `grade_id` int NOT NULL AUTO_INCREMENT,
  `student_id` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `subject_code` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `grading_period` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `grade_score` decimal(5,2) NOT NULL,
  PRIMARY KEY (`grade_id`),
  KEY `student_id` (`student_id`),
  KEY `subject_code` (`subject_code`)
) ENGINE=InnoDB AUTO_INCREMENT=55 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `grade`
--

INSERT INTO `grade` (`grade_id`, `student_id`, `subject_code`, `grading_period`, `grade_score`) VALUES
(11, '2022-0001', 'MATH-1', '1st', 94.00),
(12, '2022-0001', 'MATH-1', '2nd', 95.50),
(13, '2022-0001', 'MATH-1', '3rd', 93.00),
(14, '2022-0001', 'MATH-1', '4th', 96.00),
(15, '2022-0001', 'ENG-1', '1st', 91.00),
(16, '2022-0001', 'ENG-1', '2nd', 92.00),
(17, '2022-0001', 'ENG-1', '3rd', 90.50),
(18, '2022-0001', 'ENG-1', '4th', 93.00),
(19, '2022-0001', 'SCI-1', '1st', 89.00),
(20, '2022-0001', 'SCI-1', '2nd', 90.00),
(21, '2022-0001', 'SCI-1', '3rd', 91.50),
(22, '2022-0001', 'SCI-1', '4th', 92.00),
(23, '2022-0002', 'MATH-1', '1st', 96.00),
(24, '2022-0002', 'MATH-1', '2nd', 97.00),
(25, '2022-0002', 'MATH-1', '3rd', 95.50),
(26, '2022-0002', 'MATH-1', '4th', 98.00),
(27, '2022-0002', 'ENG-1', '1st', 95.00),
(28, '2022-0002', 'ENG-1', '2nd', 96.00),
(29, '2022-0002', 'ENG-1', '3rd', 94.50),
(30, '2022-0002', 'ENG-1', '4th', 97.00),
(31, '2022-0003', 'MATH-1', '1st', 88.00),
(32, '2022-0003', 'MATH-1', '2nd', 89.50),
(33, '2022-0003', 'MATH-1', '3rd', 90.00),
(34, '2022-0003', 'MATH-1', '4th', 91.00),
(35, '2022-0003', 'ENG-1', '1st', 86.00),
(36, '2022-0003', 'ENG-1', '2nd', 87.50),
(37, '2022-0003', 'ENG-1', '3rd', 88.00),
(38, '2022-0003', 'ENG-1', '4th', 89.00),
(39, '2023-0001', 'MATH-1', '1st', 93.00),
(40, '2023-0001', 'MATH-1', '2nd', 94.00),
(41, '2023-0001', 'MATH-1', '3rd', 92.50),
(42, '2023-0001', 'MATH-1', '4th', 95.00),
(43, '2023-0001', 'ENG-1', '1st', 90.00),
(44, '2023-0001', 'ENG-1', '2nd', 91.50),
(45, '2023-0001', 'ENG-1', '3rd', 92.00),
(46, '2023-0001', 'ENG-1', '4th', 93.00),
(47, '2023-0002', 'MATH-1', '1st', 87.00),
(48, '2023-0002', 'MATH-1', '2nd', 88.50),
(49, '2023-0002', 'MATH-1', '3rd', 89.00),
(50, '2023-0002', 'MATH-1', '4th', 90.00),
(51, '2023-0002', 'ENG-1', '1st', 85.00),
(52, '2023-0002', 'ENG-1', '2nd', 86.00),
(53, '2023-0002', 'ENG-1', '3rd', 87.50),
(54, '2023-0002', 'ENG-1', '4th', 88.00);

-- --------------------------------------------------------

--
-- Table structure for table `schedule`
--

DROP TABLE IF EXISTS `schedule`;
CREATE TABLE IF NOT EXISTS `schedule` (
  `schedule_id` int NOT NULL AUTO_INCREMENT,
  `day_time` datetime NOT NULL,
  `room_number` int NOT NULL,
  `subject_code` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `section_id` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `teacher_id` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`schedule_id`),
  KEY `subject_code` (`subject_code`),
  KEY `section_id` (`section_id`),
  KEY `teacher_id` (`teacher_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `section`
--

DROP TABLE IF EXISTS `section`;
CREATE TABLE IF NOT EXISTS `section` (
  `section_id` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `section_name` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `grade_level` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`section_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `section`
--

INSERT INTO `section` (`section_id`, `section_name`, `grade_level`) VALUES
('SEC-1-A', 'Grade 1-A', 'Grade 1'),
('SEC-2-A', 'Grade 2-A', 'Grade 2'),
('SEC-3-A', 'Grade 3-A', 'Grade 3'),
('SEC-4-A', 'Grade 4-A', 'Grade 4'),
('SEC-5-A', 'Grade 5-A', 'Grade 5'),
('SEC-6-A', 'Grade 6-A', 'Grade 6'),
('SEC-K-A', 'Kindergarten-A', 'Kindergarten');

-- --------------------------------------------------------

--
-- Table structure for table `student`
--

DROP TABLE IF EXISTS `student`;
CREATE TABLE IF NOT EXISTS `student` (
  `student_id` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `student_status` enum('active','graduated','transferred','archived') COLLATE utf8mb4_general_ci DEFAULT 'active',
  `archive_date` timestamp NULL DEFAULT NULL,
  `archive_reason` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `student_name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `grade_level` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `gender` varchar(10) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `birthdate` date DEFAULT NULL,
  `religion` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_general_ci,
  `contact_number` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `father_name` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `father_occupation` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `mother_name` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `mother_occupation` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `guardian_name` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `guardian_relationship` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `previous_school` varchar(200) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `last_school_year` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`student_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student`
--

INSERT INTO `student` (`student_id`, `student_status`, `archive_date`, `archive_reason`, `student_name`, `grade_level`, `gender`, `birthdate`, `religion`, `address`, `contact_number`, `father_name`, `father_occupation`, `mother_name`, `mother_occupation`, `guardian_name`, `guardian_relationship`, `previous_school`, `last_school_year`) VALUES
('2022-0001', 'graduated', '2023-04-30 02:00:00', 'Graduated from elementary', 'Miguel Angelo Cruz', 'Grade 6', 'Male', '2011-03-15', 'Roman Catholic', 'Makati City, Philippines', '09171234001', 'Roberto Cruz', 'Businessman', 'Elena Cruz', 'Accountant', NULL, NULL, NULL, NULL),
('2022-0002', 'graduated', '2023-04-30 02:00:00', 'Graduated from elementary', 'Isabella Marie Fernandez', 'Grade 6', 'Female', '2011-07-22', 'Roman Catholic', 'Pasig City, Philippines', '09181234002', 'Fernando Fernandez', 'Lawyer', 'Rosa Fernandez', 'Teacher', NULL, NULL, NULL, NULL),
('2022-0003', 'graduated', '2023-04-30 02:00:00', 'Graduated from elementary', 'Gabriel Luis Torres', 'Grade 6', 'Male', '2011-09-10', 'Roman Catholic', 'Taguig City, Philippines', '09191234003', 'Luis Torres', 'Engineer', 'Carmen Torres', 'Doctor', NULL, NULL, NULL, NULL),
('2022-0004', 'transferred', '2023-01-15 06:30:00', 'Transferred to another school', 'Sofia Angela Ramirez', 'Grade 5', 'Female', '2012-11-05', 'Roman Catholic', 'Paranaque City, Philippines', '09201234004', 'Antonio Ramirez', 'Manager', 'Linda Ramirez', 'Nurse', NULL, NULL, NULL, NULL),
('2022-0005', 'graduated', '2023-04-30 02:00:00', 'Graduated from elementary', 'Carlos Eduardo Martinez', 'Grade 6', 'Male', '2011-04-18', 'Roman Catholic', 'Las Pinas City, Philippines', '09211234005', 'Eduardo Martinez', 'Architect', 'Patricia Martinez', 'Dentist', NULL, NULL, NULL, NULL),
('2023-0001', 'graduated', '2024-04-30 02:00:00', 'Graduated from elementary', 'Andrea Nicole Santos', 'Grade 6', 'Female', '2012-01-20', 'Roman Catholic', 'Muntinlupa City, Philippines', '09221234006', 'Ricardo Santos', 'Pilot', 'Diana Santos', 'Flight Attendant', NULL, NULL, NULL, NULL),
('2023-0002', 'graduated', '2024-04-30 02:00:00', 'Graduated from elementary', 'Rafael Antonio Gomez', 'Grade 6', 'Male', '2012-05-12', 'Roman Catholic', 'Caloocan City, Philippines', '09231234007', 'Antonio Gomez', 'Police Officer', 'Maria Gomez', 'Social Worker', NULL, NULL, NULL, NULL),
('2023-0003', 'graduated', '2024-04-30 02:00:00', 'Graduated from elementary', 'Katrina Mae Lopez', 'Grade 6', 'Female', '2012-08-30', 'Roman Catholic', 'Valenzuela City, Philippines', '09241234008', 'Jose Lopez', 'Chef', 'Angelina Lopez', 'Entrepreneur', NULL, NULL, NULL, NULL),
('2023-0004', 'transferred', '2024-02-20 01:15:00', 'Transferred to international school', 'Daniel James Rivera', 'Grade 4', 'Male', '2014-02-14', 'Roman Catholic', 'Malabon City, Philippines', '09251234009', 'James Rivera', 'IT Manager', 'Christine Rivera', 'HR Manager', NULL, NULL, NULL, NULL),
('2023-0005', 'graduated', '2024-04-30 02:00:00', 'Graduated from elementary', 'Bianca Louise Mendoza', 'Grade 6', 'Female', '2012-10-08', 'Roman Catholic', 'Navotas City, Philippines', '09261234010', 'Roberto Mendoza', 'Seaman', 'Gloria Mendoza', 'Teacher', NULL, NULL, NULL, NULL),
('2024-0101', 'active', NULL, NULL, 'Ariana Joyce Rivera', 'Kindergarten', 'Female', '2019-02-10', 'Roman Catholic', 'Manila City', '09181110001', 'Joel Rivera', 'Driver', 'Maricel Rivera', 'Vendor', NULL, NULL, 'Little Steps Academy', '2023-2024'),
('2024-0102', 'active', NULL, NULL, 'Bryan Cedrick Ramos', 'Kindergarten', 'Male', '2019-05-18', 'Born Again', 'Quezon City', '09181110002', 'Cris Ramos', 'Technician', 'Jenny Ramos', 'Cashier', NULL, NULL, 'Small Wonders School', '2023-2024'),
('2024-0103', 'active', NULL, NULL, 'Cheska Elaine Lopez', 'Kindergarten', 'Female', '2019-03-22', 'Roman Catholic', 'Pasig City', '09181110003', 'Robert Lopez', 'Security Guard', 'Elaine Lopez', 'Sales Clerk', NULL, NULL, 'Kids Learning Hub', '2023-2024'),
('2024-0104', 'active', NULL, NULL, 'Darren Keith Santiago', 'Kindergarten', 'Male', '2019-07-09', 'Iglesia ni Cristo', 'Taguig City', '09181110004', 'Kevin Santiago', 'Mechanic', 'Riza Santiago', 'Clerk', NULL, NULL, 'Bright Minds Preschool', '2023-2024'),
('2024-0105', 'active', NULL, NULL, 'Eliza Danielle Cruz', 'Kindergarten', 'Female', '2019-11-04', 'Roman Catholic', 'Makati City', '09181110005', 'Paolo Cruz', 'Engineer', 'Danica Cruz', 'Nurse', NULL, NULL, 'Little Stars Academy', '2023-2024'),
('2024-0106', 'active', NULL, NULL, 'Felix Jordan Santos', 'Kindergarten', 'Male', '2019-01-16', 'Born Again', 'Caloocan City', '09181110006', 'Mark Santos', 'Driver', 'Lyn Santos', 'Vendor', NULL, NULL, 'Kids First School', '2023-2024'),
('2024-0107', 'active', NULL, NULL, 'Grace Andrea Dizon', 'Kindergarten', 'Female', '2019-06-12', 'Roman Catholic', 'Valenzuela City', '09181110007', 'Jun Dizon', 'Technician', 'Mariel Dizon', 'Teacher', NULL, NULL, 'Valenzuela Learning Center', '2023-2024'),
('2024-0108', 'active', NULL, NULL, 'Hanz Dominic Reyes', 'Kindergarten', 'Male', '2019-04-28', 'Roman Catholic', 'Manila City', '09181110008', 'Richard Reyes', 'Architect', 'Anna Reyes', 'Bank Teller', NULL, NULL, 'ABC Preschool', '2023-2024'),
('2024-0109', 'active', NULL, NULL, 'Isabella Marie Bautista', 'Kindergarten', 'Female', '2019-08-30', 'Roman Catholic', 'Quezon City', '09181110009', 'Jovit Bautista', 'Carpenter', 'Josephine Bautista', 'Vendor', NULL, NULL, 'Happy Kids Academy', '2023-2024'),
('2024-0110', 'active', NULL, NULL, 'Jaxon Tyler Gonzales', 'Kindergarten', 'Male', '2019-12-05', 'Born Again', 'Pasay City', '09181110010', 'Tomas Gonzales', 'Technician', 'Ella Gonzales', 'Cashier', NULL, NULL, 'Smart Minds Preschool', '2023-2024'),
('2024-0111', 'active', NULL, NULL, 'Kylie Anne Mendoza', 'Grade 1', 'Female', '2018-03-14', 'Roman Catholic', 'Pasig City', '09181110011', 'Ramon Mendoza', 'Engineer', 'Shiela Mendoza', 'Teacher', NULL, NULL, 'ABC Preschool', '2023-2024'),
('2024-0112', 'active', NULL, NULL, 'Liam Carter Santos', 'Grade 1', 'Male', '2018-06-08', 'Born Again', 'Makati City', '09181110012', 'Michael Santos', 'Driver', 'Rhea Santos', 'Clerk', NULL, NULL, 'Little Scholars', '2023-2024'),
('2024-0113', 'active', NULL, NULL, 'Mia Gabrielle Tan', 'Grade 1', 'Female', '2018-01-19', 'Christian', 'Cavite City', '09181110013', 'Anthony Tan', 'Businessman', 'Clarisse Tan', 'Teacher', NULL, NULL, 'Bright Future Preschool', '2023-2024'),
('2024-0114', 'active', NULL, NULL, 'Noah Benjamin Dela Cruz', 'Grade 1', 'Male', '2018-02-07', 'Roman Catholic', 'Taguig City', '09181110014', 'Benedict Dela Cruz', 'Technician', 'Alona Dela Cruz', 'Cashier', NULL, NULL, 'Kids First School', '2023-2024'),
('2024-0115', 'active', NULL, NULL, 'Olivia Faith Ramos', 'Grade 1', 'Female', '2018-10-04', 'Roman Catholic', 'Manila City', '09181110015', 'Cesar Ramos', 'Driver', 'Lorna Ramos', 'Vendor', NULL, NULL, 'Little Angels Academy', '2023-2024'),
('2024-0116', 'active', NULL, NULL, 'Parker Joshua Reyes', 'Grade 1', 'Male', '2018-09-28', 'Born Again', 'Quezon City', '09181110016', 'Joey Reyes', 'Technician', 'Abigail Reyes', 'Clerk', NULL, NULL, 'Tiny Steps School', '2023-2024'),
('2024-0117', 'active', NULL, NULL, 'Queenie Mae Velasco', 'Grade 1', 'Female', '2018-12-11', 'Roman Catholic', 'Pasay City', '09181110017', 'Leo Velasco', 'Engineer', 'Dianne Velasco', 'Teacher', NULL, NULL, 'Young Learners Academy', '2023-2024'),
('2024-0118', 'active', NULL, NULL, 'Raven Isaiah Torres', 'Grade 1', 'Male', '2018-07-06', 'Christian', 'Parañaque City', '09181110018', 'Mark Torres', 'Security Guard', 'Hazel Torres', 'Cashier', NULL, NULL, 'Little Stars Preschool', '2023-2024'),
('2024-0119', 'active', NULL, NULL, 'Sophia Claire Bautista', 'Grade 1', 'Female', '2018-11-23', 'Roman Catholic', 'Caloocan City', '09181110019', 'Dario Bautista', 'Driver', 'Jessica Bautista', 'Vendor', NULL, NULL, 'ABC Preschool', '2023-2024'),
('2024-0120', 'active', NULL, NULL, 'Tristan Angelo Uy', 'Grade 1', 'Male', '2018-05-17', 'Christian', 'Manila City', '09181110020', 'Jonathan Uy', 'Businessman', 'Grace Uy', 'Teacher', NULL, NULL, 'Little Bright Academy', '2023-2024'),
('2024-0121', 'active', NULL, NULL, 'Uma Leanne Navarro', 'Grade 2', 'Female', '2017-09-12', 'Roman Catholic', 'Pasig City', '09181110021', 'Raffy Navarro', 'Driver', 'Cathy Navarro', 'Vendor', NULL, NULL, 'XYZ Elementary', '2023-2024'),
('2024-0122', 'active', NULL, NULL, 'Vince Gabriel Ramirez', 'Grade 2', 'Male', '2017-04-30', 'Christian', 'Taguig City', '09181110022', 'Gerry Ramirez', 'Technician', 'Lorna Ramirez', 'Cashier', NULL, NULL, 'Pasig Elementary', '2023-2024'),
('2024-0123', 'active', NULL, NULL, 'Willow Anne Cruz', 'Grade 2', 'Female', '2017-08-02', 'Roman Catholic', 'Quezon City', '09181110023', 'Henry Cruz', 'Engineer', 'Maribel Cruz', 'Teacher', NULL, NULL, 'Trinity Elementary', '2023-2024'),
('2024-0124', 'active', NULL, NULL, 'Xander Levi Santos', 'Grade 2', 'Male', '2017-06-15', 'Born Again', 'Manila City', '09181110024', 'Philip Santos', 'Driver', 'Andrea Santos', 'Vendor', NULL, NULL, 'ABC Learning Center', '2023-2024'),
('2024-0125', 'active', NULL, NULL, 'Yza Marielle Dizon', 'Grade 2', 'Female', '2017-03-18', 'Roman Catholic', 'Caloocan City', '09181110025', 'Ronald Dizon', 'Security Guard', 'Mae Dizon', 'Clerk', NULL, NULL, 'Little Achievers School', '2023-2024'),
('2024-0126', 'active', NULL, NULL, 'Zion Matthew Uy', 'Grade 2', 'Male', '2017-10-25', 'Christian', 'Parañaque City', '09181110026', 'Noel Uy', 'Businessman', 'Celine Uy', 'Nurse', NULL, NULL, 'Future Learners Academy', '2023-2024'),
('2024-0127', 'active', NULL, NULL, 'Alyssa Hope Garcia', 'Grade 2', 'Female', '2017-12-29', 'Roman Catholic', 'Makati City', '09181110027', 'Jerson Garcia', 'Technician', 'Rowena Garcia', 'Teacher', NULL, NULL, 'Little Wonders', '2023-2024'),
('2024-0128', 'active', NULL, NULL, 'Brandon Tyler Lim', 'Grade 2', 'Male', '2017-02-13', 'Christian', 'Las Piñas City', '09181110028', 'Harold Lim', 'Driver', 'Jenny Lim', 'Sales Clerk', NULL, NULL, 'Las Piñas Elementary', '2023-2024'),
('2024-0129', 'active', NULL, NULL, 'Cassandra Mila Villanueva', 'Grade 2', 'Female', '2017-07-08', 'Roman Catholic', 'Makati City', '09181110029', 'Dennis Villanueva', 'Architect', 'Marites Villanueva', 'Clerk', NULL, NULL, 'Makati Elementary', '2023-2024'),
('2024-0130', 'active', NULL, NULL, 'Dylan Zach Torres', 'Grade 2', 'Male', '2017-01-05', 'Born Again', 'Quezon City', '09181110030', 'Elmer Torres', 'Carpenter', 'Bella Torres', 'Vendor', NULL, NULL, 'QC Elementary', '2023-2024'),
('2024-0131', 'active', NULL, NULL, 'Eunice Marie Silva', 'Grade 3', 'Female', '2016-05-21', 'Roman Catholic', 'Cavite City', '09181110031', 'Allan Silva', 'Driver', 'Liza Silva', 'Teacher', NULL, NULL, 'Cavite Elementary', '2023-2024'),
('2024-0132', 'active', NULL, NULL, 'Francis Emil Ramos', 'Grade 3', 'Male', '2016-09-10', 'Christian', 'Pasay City', '09181110032', 'Eric Ramos', 'Technician', 'Jean Ramos', 'Cashier', NULL, NULL, 'Pasay Elementary', '2023-2024'),
('2024-0133', 'active', NULL, NULL, 'Gianna Faith Dela Cruz', 'Grade 3', 'Female', '2016-11-04', 'Roman Catholic', 'Taguig City', '09181110033', 'Norman Dela Cruz', 'Engineer', 'Ivy Dela Cruz', 'Nurse', NULL, NULL, 'Taguig Elementary', '2023-2024'),
('2024-0134', 'active', NULL, NULL, 'Harold Zachary Reyes', 'Grade 3', 'Male', '2016-03-25', 'Born Again', 'Manila City', '09181110034', 'Jerome Reyes', 'Driver', 'Michelle Reyes', 'Vendor', NULL, NULL, 'Happy Learners School', '2023-2024'),
('2024-0135', 'active', NULL, NULL, 'Isabelle Joy Aquino', 'Grade 3', 'Female', '2016-08-19', 'Roman Catholic', 'Quezon City', '09181110035', 'Oscar Aquino', 'Security Guard', 'Grace Aquino', 'Clerk', NULL, NULL, 'QC Elementary', '2023-2024'),
('2024-0136', 'active', NULL, NULL, 'Jared Cole Evangelista', 'Grade 3', 'Male', '2016-12-12', 'Christian', 'Mandaluyong City', '09181110036', 'Paul Evangelista', 'Technician', 'Lou Evangelista', 'Sales Clerk', NULL, NULL, 'Mandaluyong Elementary', '2023-2024'),
('2024-0137', 'active', NULL, NULL, 'Kyla Denise Bautista', 'Grade 3', 'Female', '2016-01-17', 'Roman Catholic', 'Caloocan City', '09181110037', 'Arnold Bautista', 'Mechanic', 'Ruth Bautista', 'Vendor', NULL, NULL, 'Caloocan North Elementary', '2023-2024'),
('2024-0138', 'active', NULL, NULL, 'Lucas Henry Miranda', 'Grade 3', 'Male', '2016-06-27', 'Christian', 'Las Piñas City', '09181110038', 'Hector Miranda', 'Driver', 'Joanna Miranda', 'Cashier', NULL, NULL, 'Las Piñas Elementary', '2023-2024'),
('2024-0139', 'active', NULL, NULL, 'Megan Ysabelle Flores', 'Grade 3', 'Female', '2016-10-14', 'Roman Catholic', 'Pasig City', '09181110039', 'Rob Flores', 'Technician', 'Marie Flores', 'Teacher', NULL, NULL, 'Pasig Elementary', '2023-2024'),
('2024-0140', 'active', NULL, NULL, 'Nathaniel Jay Cruz', 'Grade 3', 'Male', '2016-02-03', 'Born Again', 'Makati City', '09181110040', 'Gilbert Cruz', 'Driver', 'Elaine Cruz', 'Vendor', NULL, NULL, 'KinderSmart School', '2023-2024'),
('2024-0141', 'active', NULL, NULL, 'Olivia Sarah Mendoza', 'Grade 4', 'Female', '2015-05-06', 'Roman Catholic', 'Makati City', '09181110041', 'Felix Mendoza', 'Technician', 'Monica Mendoza', 'Teacher', NULL, NULL, 'Makati Elementary', '2023-2024'),
('2024-0142', 'active', NULL, NULL, 'Paul Adrian Dizon', 'Grade 4', 'Male', '2015-08-22', 'Born Again', 'Manila City', '09181110042', 'Arvin Dizon', 'Carpenter', 'Rica Dizon', 'Vendor', NULL, NULL, 'Manila Elementary', '2023-2024'),
('2024-0143', 'active', NULL, NULL, 'Quinn Avery Santos', 'Grade 4', 'Female', '2015-03-31', 'Roman Catholic', 'Pasay City', '09181110043', 'Ernie Santos', 'Driver', 'Lucille Santos', 'Clerk', NULL, NULL, 'Pasay Elementary', '2023-2024'),
('2024-0144', 'active', NULL, NULL, 'Riley Jaxon Tan', 'Grade 4', 'Male', '2015-11-18', 'Christian', 'Taguig City', '09181110044', 'Hector Tan', 'Mechanic', 'Rowena Tan', 'Teacher', NULL, NULL, 'Taguig Elementary', '2023-2024'),
('2024-0145', 'active', NULL, NULL, 'Serena Mae Villanueva', 'Grade 4', 'Female', '2015-07-02', 'Roman Catholic', 'Parañaque City', '09181110045', 'Leo Villanueva', 'Technician', 'Camille Villanueva', 'Cashier', NULL, NULL, 'Parañaque Elementary', '2023-2024'),
('2024-0146', 'active', NULL, NULL, 'Travis Leon Gonzaga', 'Grade 4', 'Male', '2015-09-16', 'Christian', 'Caloocan City', '09181110046', 'Joey Gonzaga', 'Security Guard', 'Marie Gonzaga', 'Vendor', NULL, NULL, 'Caloocan Elementary', '2023-2024'),
('2024-0147', 'active', NULL, NULL, 'Uma Claire Bautista', 'Grade 4', 'Female', '2015-01-21', 'Roman Catholic', 'Muntinlupa City', '09181110047', 'Jun Bautista', 'Engineer', 'Bea Bautista', 'Teacher', NULL, NULL, 'Muntinlupa Elementary', '2023-2024'),
('2024-0148', 'active', NULL, NULL, 'Victor James Ramirez', 'Grade 4', 'Male', '2015-04-09', 'Born Again', 'Valenzuela City', '09181110048', 'Ronnie Ramirez', 'Driver', 'Mica Ramirez', 'Vendor', NULL, NULL, 'Valenzuela Elementary', '2023-2024'),
('2024-0149', 'active', NULL, NULL, 'Willow Eve Cruz', 'Grade 4', 'Female', '2015-10-28', 'Christian', 'Las Piñas City', '09181110049', 'Arman Cruz', 'Technician', 'Belle Cruz', 'Cashier', NULL, NULL, 'Las Piñas Elementary', '2023-2024'),
('2024-0150', 'active', NULL, NULL, 'Xavier Paul Angeles', 'Grade 4', 'Male', '2015-06-24', 'Roman Catholic', 'Pasig City', '09181110050', 'Noli Angeles', 'Mechanic', 'Jessa Angeles', 'Vendor', NULL, NULL, 'Pasig Elementary', '2023-2024'),
('2024-0151', 'active', NULL, NULL, 'Yana Celeste Javier', 'Grade 5', 'Female', '2014-03-07', 'Roman Catholic', 'Makati City', '09181110051', 'Robby Javier', 'Engineer', 'Claudine Javier', 'Teacher', NULL, NULL, 'Makati Elementary', '2023-2024'),
('2024-0152', 'active', NULL, NULL, 'Zachary Miles Lorenzo', 'Grade 5', 'Male', '2014-01-29', 'Christian', 'Taguig City', '09181110052', 'Mario Lorenzo', 'Driver', 'Jessa Lorenzo', 'Cashier', NULL, NULL, 'Taguig Elementary', '2023-2024'),
('2024-0153', 'active', NULL, NULL, 'Alyssa Jean Cruz', 'Grade 5', 'Female', '2014-11-15', 'Born Again', 'Caloocan City', '09181110053', 'Ben Cruz', 'Technician', 'Rowena Cruz', 'Vendor', NULL, NULL, 'Caloocan Elementary', '2023-2024'),
('2024-0154', 'active', NULL, NULL, 'Brent Harvey Santos', 'Grade 5', 'Male', '2014-09-05', 'Roman Catholic', 'Manila City', '09181110054', 'Erwin Santos', 'Security Guard', 'Liza Santos', 'Clerk', NULL, NULL, 'Manila Elementary', '2023-2024'),
('2024-0155', 'active', NULL, NULL, 'Celine Raine Tan', 'Grade 5', 'Female', '2014-12-20', 'Christian', 'Pasig City', '09181110055', 'Harvey Tan', 'Businessman', 'Helen Tan', 'Nurse', NULL, NULL, 'Pasig Elementary', '2023-2024'),
('2024-0156', 'active', NULL, NULL, 'Darren Luke Villanueva', 'Grade 5', 'Male', '2014-08-11', 'Roman Catholic', 'Las Piñas City', '09181110056', 'Joey Villanueva', 'Driver', 'April Villanueva', 'Vendor', NULL, NULL, 'Las Piñas Elementary', '2023-2024'),
('2024-0157', 'active', NULL, NULL, 'Ella Faye Bautista', 'Grade 5', 'Female', '2014-07-23', 'Born Again', 'Cavite City', '09181110057', 'Mario Bautista', 'Technician', 'Jean Bautista', 'Cashier', NULL, NULL, 'Cavite Elementary', '2023-2024'),
('2024-0158', 'active', NULL, NULL, 'Felix Adrian Dizon', 'Grade 5', 'Male', '2014-04-14', 'Roman Catholic', 'Mandaluyong City', '09181110058', 'Ernest Dizon', 'Mechanic', 'Karen Dizon', 'Teacher', NULL, NULL, 'Mandaluyong Elementary', '2023-2024'),
('2024-0159', 'active', NULL, NULL, 'Georgia Lane Uy', 'Grade 5', 'Female', '2014-10-03', 'Christian', 'Manila City', '09181110059', 'Jonathan Uy', 'Businessman', 'Aimee Uy', 'Clerk', NULL, NULL, 'KinderSmart School', '2023-2024'),
('2024-0160', 'active', NULL, NULL, 'Harvey Logan Cruz', 'Grade 5', 'Male', '2014-05-26', 'Roman Catholic', 'Quezon City', '09181110060', 'Eric Cruz', 'Driver', 'Ellen Cruz', 'Vendor', NULL, NULL, 'QC Elementary', '2023-2024'),
('2024-0161', 'active', NULL, NULL, 'Ian Marcus Dela Rosa', 'Grade 6', 'Male', '2013-03-12', 'Roman Catholic', 'Quezon City', '09181110061', 'Marco Dela Rosa', 'Driver', 'Irene Dela Rosa', 'Vendor', NULL, NULL, 'Quezon City Elementary School', '2023-2024'),
('2024-0162', 'active', NULL, NULL, 'Jana Elise Santos', 'Grade 6', 'Female', '2013-07-25', 'Christian', 'Makati City', '09181110062', 'Alvin Santos', 'Technician', 'Grace Santos', 'Clerk', NULL, NULL, 'Makati Elementary School', '2023-2024'),
('2024-0163', 'active', NULL, NULL, 'Kyle Anthony Mendoza', 'Grade 6', 'Male', '2013-09-04', 'Born Again', 'Pasig City', '09181110063', 'Bert Mendoza', 'Engineer', 'Sharon Mendoza', 'Teacher', NULL, NULL, 'Pasig Elementary School', '2023-2024'),
('2024-0164', 'active', NULL, NULL, 'Lexi Anne Ramirez', 'Grade 6', 'Female', '2013-01-18', 'Roman Catholic', 'Taguig City', '09181110064', 'Romeo Ramirez', 'Security Guard', 'Jenny Ramirez', 'Cashier', NULL, NULL, 'Taguig Elementary School', '2023-2024'),
('2024-0165', 'active', NULL, NULL, 'Mason Tyler Cruz', 'Grade 6', 'Male', '2013-11-09', 'Christian', 'Manila City', '09181110065', 'Nestor Cruz', 'Driver', 'Helena Cruz', 'Vendor', NULL, NULL, 'Manila Elementary School', '2023-2024'),
('2024-0166', 'active', NULL, NULL, 'Nina Ysabelle Velasco', 'Grade 6', 'Female', '2013-05-02', 'Roman Catholic', 'Parañaque City', '09181110066', 'Joel Velasco', 'Mechanic', 'Ruth Velasco', 'Vendor', NULL, NULL, 'Parañaque Elementary School', '2023-2024'),
('2024-0167', 'active', NULL, NULL, 'Owen Jacob Bautista', 'Grade 6', 'Male', '2013-10-20', 'Born Again', 'Caloocan City', '09181110067', 'Richard Bautista', 'Technician', 'Elena Bautista', 'Clerk', NULL, NULL, 'Caloocan North Elementary', '2023-2024'),
('2024-0168', 'active', NULL, NULL, 'Penelope Faith Gonzaga', 'Grade 6', 'Female', '2013-02-14', 'Roman Catholic', 'Las Piñas City', '09181110068', 'Harold Gonzaga', 'Driver', 'Claire Gonzaga', 'Vendor', NULL, NULL, 'Las Piñas Elementary School', '2023-2024'),
('2024-0169', 'active', NULL, NULL, 'Quentin Marc Uy', 'Grade 6', 'Male', '2013-06-27', 'Christian', 'Muntinlupa City', '09181110069', 'Ricky Uy', 'Businessman', 'Marian Uy', 'Nurse', NULL, NULL, 'Muntinlupa Elementary School', '2023-2024'),
('2024-0170', 'active', NULL, NULL, 'Rihanna Mae Flores', 'Grade 6', 'Female', '2013-04-30', 'Roman Catholic', 'Pasay City', '09181110070', 'Tony Flores', 'Technician', 'Rowena Flores', 'Teacher', NULL, NULL, 'Pasay Elementary School', '2023-2024'),
('2024-0171', 'active', NULL, NULL, 'Cyruz Dave Juanites', 'Grade 1', 'Male', '2013-12-08', 'Roman Catholic', 'P-Atis, Gubat, Daet, Camarines Norte, Bicol Region, 4600', '09123345689', 'Marlon Juanites', 'Security Guard', 'Lanie Juanites', 'House Wife', 'Lanie Juanites', 'Mother', 'Moreno Integrated School', '2024-2025'),
('2024-0172', 'active', NULL, NULL, 'Jhonny Doe', 'Kindergarten', 'Male', '2015-05-02', 'Roman Catholic', 'P-Atis, Gubat, Daet, Camarines Norte, Bicol Region, 4600', '09123456789', 'test father', 'test parent', 'test mother', 'test parent', 'test', 'test', 'test school', '2024-2025'),
('2024-0173', 'active', NULL, NULL, 'Juan Vasquez Dela Cruz', 'Grade 1', 'Male', '2015-08-26', 'Roman Catholic', 'P-Atis, Gubat, Daet, Camarines Norte, Bicol Region, 4600', '09123456789', 'test father', 'test parent', 'test mother', 'test parent', 'N/A', 'N/A', 'test school', '2024-2025');

-- --------------------------------------------------------

--
-- Stand-in structure for view `student_history_view`
-- (See below for the actual view)
--
DROP VIEW IF EXISTS `student_history_view`;
CREATE TABLE IF NOT EXISTS `student_history_view` (
`student_id` varchar(50)
,`student_name` varchar(100)
,`student_status` enum('active','graduated','transferred','archived')
,`grade_level` varchar(20)
,`archive_date` timestamp
,`archive_reason` varchar(255)
,`school_year` varchar(20)
,`section_name` varchar(50)
,`enrollment_status` varchar(20)
);

-- --------------------------------------------------------

--
-- Table structure for table `subject`
--

DROP TABLE IF EXISTS `subject`;
CREATE TABLE IF NOT EXISTS `subject` (
  `subject_code` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `subject_name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `teacher_id` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`subject_code`),
  KEY `teacher_id` (`teacher_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subject`
--

INSERT INTO `subject` (`subject_code`, `subject_name`, `teacher_id`) VALUES
('AP-1', 'Araling Panlipunan', 'T005'),
('ENG-1', 'English', 'T002'),
('EPP-1', 'Edukasyon sa Pagpapakatao', 'T002'),
('FIL-1', 'Filipino', 'T004'),
('MAPEH-1', 'MAPEH', 'T001'),
('MATH-1', 'Mathematics', 'T001'),
('SCI-1', 'Science', 'T003');

-- --------------------------------------------------------

--
-- Table structure for table `teacher`
--

DROP TABLE IF EXISTS `teacher`;
CREATE TABLE IF NOT EXISTS `teacher` (
  `teacher_id` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `teacher_name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`teacher_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `teacher`
--

INSERT INTO `teacher` (`teacher_id`, `teacher_name`) VALUES
('T001', 'Mrs. Maria Santos'),
('T002', 'Mr. Juan Dela Cruz'),
('T003', 'Ms. Ana Garcia'),
('T004', 'Mr. Pedro Reyes'),
('T005', 'Mrs. Sofia Martinez');

-- --------------------------------------------------------

--
-- Table structure for table `teacher_students`
--

DROP TABLE IF EXISTS `teacher_students`;
CREATE TABLE IF NOT EXISTS `teacher_students` (
  `id` int NOT NULL AUTO_INCREMENT,
  `teacher_id` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `student_id` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `assigned_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_assignment` (`teacher_id`,`student_id`),
  KEY `idx_teacher_id` (`teacher_id`),
  KEY `idx_student_id` (`student_id`)
) ENGINE=InnoDB AUTO_INCREMENT=65 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `teacher_students`
--

INSERT INTO `teacher_students` (`id`, `teacher_id`, `student_id`, `assigned_date`) VALUES
(44, 'T001', '2024-0121', '2025-11-29 06:36:50'),
(45, 'T001', '2024-0122', '2025-11-29 06:36:50'),
(46, 'T001', '2024-0123', '2025-11-29 06:36:50'),
(47, 'T001', '2024-0124', '2025-11-29 06:36:50'),
(48, 'T001', '2024-0125', '2025-11-29 06:36:50'),
(49, 'T001', '2024-0126', '2025-11-29 06:36:50'),
(50, 'T001', '2024-0127', '2025-11-29 06:36:50'),
(51, 'T001', '2024-0128', '2025-11-29 06:36:50'),
(52, 'T001', '2024-0129', '2025-11-29 06:36:50'),
(53, 'T001', '2024-0130', '2025-11-29 06:36:50');

-- --------------------------------------------------------

--
-- Table structure for table `user_account`
--

DROP TABLE IF EXISTS `user_account`;
CREATE TABLE IF NOT EXISTS `user_account` (
  `user_id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `role` enum('admin','teacher','student') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'student',
  `student_id` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `teacher_id` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `date_created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `username` (`username`),
  KEY `student_id` (`student_id`),
  KEY `teacher_id` (`teacher_id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_account`
--

INSERT INTO `user_account` (`user_id`, `username`, `password`, `role`, `student_id`, `teacher_id`, `date_created`) VALUES
(4, 'admin', '$2y$10$1sQX0p9rYtanuDF5We5Ar.YcayZVqRdq6TenlziX4uBIuEPb8SWGu', 'admin', NULL, NULL, '2025-11-01 14:00:00'),
(5, 'msantos', '$2y$10$DuZ7Vqec5xH2l2zUTeV0ZOZGm90DIxxub.XaKbVhZaJQxWhcIP8se', 'teacher', NULL, 'T001', '2025-11-01 14:00:00'),
(6, 'jdelacruz', '$2y$10$BePoLoGSglfdyj7JyeoVVO9cyrm/b6v3gV5w44Ajzexa9jZW0ifam', 'teacher', NULL, 'T002', '2025-11-01 14:00:00'),
(7, 'ana', '$2y$10$z9sK0N/GTEHeFralwf8m2Own50rtp9ugrN0n2fpvpy/b4g0m9QmEW', 'teacher', NULL, 'T003', '2025-11-16 03:41:47'),
(12, '2024-0171', '$2y$10$jFdOU.PT.n1gf0ZdaTdqNeMh3Ytp2892NcQgWuqjNgmrKekqvZ1t6', 'student', '2024-0171', NULL, '2025-11-24 05:10:27'),
(13, '2024-0172', '$2y$10$2okVpxX4bSKI9A3k2l29BeKShO5XUqYHcGlkodpL5.lgY2GnxUT42', 'student', '2024-0172', NULL, '2025-11-24 07:13:38'),
(14, '2024-0173', '$2y$10$S9ESLNp5LaWhTyS.6vStcukdAFE3oOufgdfZFtjXtYnvCU6O0/Yc.', 'student', '2024-0173', NULL, '2025-11-25 05:04:35');

-- --------------------------------------------------------

--
-- Structure for view `active_students_view`
--
DROP TABLE IF EXISTS `active_students_view`;

DROP VIEW IF EXISTS `active_students_view`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `active_students_view`  AS SELECT `student`.`student_id` AS `student_id`, `student`.`student_status` AS `student_status`, `student`.`archive_date` AS `archive_date`, `student`.`archive_reason` AS `archive_reason`, `student`.`student_name` AS `student_name`, `student`.`grade_level` AS `grade_level`, `student`.`gender` AS `gender`, `student`.`birthdate` AS `birthdate`, `student`.`religion` AS `religion`, `student`.`address` AS `address`, `student`.`contact_number` AS `contact_number`, `student`.`father_name` AS `father_name`, `student`.`father_occupation` AS `father_occupation`, `student`.`mother_name` AS `mother_name`, `student`.`mother_occupation` AS `mother_occupation`, `student`.`guardian_name` AS `guardian_name`, `student`.`guardian_relationship` AS `guardian_relationship`, `student`.`previous_school` AS `previous_school`, `student`.`last_school_year` AS `last_school_year` FROM `student` WHERE (`student`.`student_status` = 'active') ;

-- --------------------------------------------------------

--
-- Structure for view `archived_students_view`
--
DROP TABLE IF EXISTS `archived_students_view`;

DROP VIEW IF EXISTS `archived_students_view`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `archived_students_view`  AS SELECT `student`.`student_id` AS `student_id`, `student`.`student_status` AS `student_status`, `student`.`archive_date` AS `archive_date`, `student`.`archive_reason` AS `archive_reason`, `student`.`student_name` AS `student_name`, `student`.`grade_level` AS `grade_level`, `student`.`gender` AS `gender`, `student`.`birthdate` AS `birthdate`, `student`.`religion` AS `religion`, `student`.`address` AS `address`, `student`.`contact_number` AS `contact_number`, `student`.`father_name` AS `father_name`, `student`.`father_occupation` AS `father_occupation`, `student`.`mother_name` AS `mother_name`, `student`.`mother_occupation` AS `mother_occupation`, `student`.`guardian_name` AS `guardian_name`, `student`.`guardian_relationship` AS `guardian_relationship`, `student`.`previous_school` AS `previous_school`, `student`.`last_school_year` AS `last_school_year` FROM `student` WHERE (`student`.`student_status` in ('graduated','transferred','archived')) ;

-- --------------------------------------------------------

--
-- Structure for view `student_history_view`
--
DROP TABLE IF EXISTS `student_history_view`;

DROP VIEW IF EXISTS `student_history_view`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `student_history_view`  AS SELECT `s`.`student_id` AS `student_id`, `s`.`student_name` AS `student_name`, `s`.`student_status` AS `student_status`, `s`.`grade_level` AS `grade_level`, `s`.`archive_date` AS `archive_date`, `s`.`archive_reason` AS `archive_reason`, `e`.`school_year` AS `school_year`, `sec`.`section_name` AS `section_name`, `e`.`enrollment_status` AS `enrollment_status` FROM ((`student` `s` left join `enrollment` `e` on((`s`.`student_id` = `e`.`student_id`))) left join `section` `sec` on((`e`.`section_id` = `sec`.`section_id`))) ORDER BY `s`.`student_id` DESC, `e`.`school_year` DESC ;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activity_log`
--
ALTER TABLE `activity_log`
  ADD CONSTRAINT `activity_log_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user_account` (`user_id`) ON DELETE SET NULL;

--
-- Constraints for table `archive_log`
--
ALTER TABLE `archive_log`
  ADD CONSTRAINT `archive_log_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `student` (`student_id`);

--
-- Constraints for table `enrollment`
--
ALTER TABLE `enrollment`
  ADD CONSTRAINT `enrollment_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `student` (`student_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `enrollment_ibfk_2` FOREIGN KEY (`section_id`) REFERENCES `section` (`section_id`) ON DELETE SET NULL;

--
-- Constraints for table `grade`
--
ALTER TABLE `grade`
  ADD CONSTRAINT `grade_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `student` (`student_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `grade_ibfk_2` FOREIGN KEY (`subject_code`) REFERENCES `subject` (`subject_code`) ON DELETE CASCADE;

--
-- Constraints for table `schedule`
--
ALTER TABLE `schedule`
  ADD CONSTRAINT `schedule_ibfk_1` FOREIGN KEY (`subject_code`) REFERENCES `subject` (`subject_code`) ON DELETE CASCADE,
  ADD CONSTRAINT `schedule_ibfk_2` FOREIGN KEY (`section_id`) REFERENCES `section` (`section_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `schedule_ibfk_3` FOREIGN KEY (`teacher_id`) REFERENCES `teacher` (`teacher_id`) ON DELETE CASCADE;

--
-- Constraints for table `subject`
--
ALTER TABLE `subject`
  ADD CONSTRAINT `subject_ibfk_1` FOREIGN KEY (`teacher_id`) REFERENCES `teacher` (`teacher_id`) ON DELETE SET NULL;

--
-- Constraints for table `teacher_students`
--
ALTER TABLE `teacher_students`
  ADD CONSTRAINT `teacher_students_ibfk_1` FOREIGN KEY (`teacher_id`) REFERENCES `teacher` (`teacher_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `teacher_students_ibfk_2` FOREIGN KEY (`student_id`) REFERENCES `student` (`student_id`) ON DELETE CASCADE;

--
-- Constraints for table `user_account`
--
ALTER TABLE `user_account`
  ADD CONSTRAINT `user_account_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `student` (`student_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `user_account_ibfk_2` FOREIGN KEY (`teacher_id`) REFERENCES `teacher` (`teacher_id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;