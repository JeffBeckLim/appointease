-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 12, 2024 at 05:08 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `appointease`
--

-- --------------------------------------------------------

--
-- Table structure for table `announcements`
--

CREATE TABLE `announcements` (
  `announcement_id` int(11) NOT NULL,
  `description` text NOT NULL,
  `pic_path` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `appointment_id` int(11) NOT NULL,
  `doctor_id` int(11) NOT NULL,
  `appointment_specialties` text NOT NULL,
  `user_id` int(11) NOT NULL,
  `doctor_schedule_id` int(11) NOT NULL,
  `appointment_reason` text NOT NULL,
  `appointment_start_time` time NOT NULL,
  `appointment_duration` int(11) NOT NULL,
  `appointment_date` date NOT NULL,
  `appointment_status` text NOT NULL,
  `doctor_comments` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`appointment_id`, `doctor_id`, `appointment_specialties`, `user_id`, `doctor_schedule_id`, `appointment_reason`, `appointment_start_time`, `appointment_duration`, `appointment_date`, `appointment_status`, `doctor_comments`) VALUES
(7, 3, '', 15, 105, '', '09:00:00', 60, '2024-01-22', 'Pending', ''),
(8, 4, '', 15, 30, '', '13:00:00', 30, '2024-01-22', 'Pending', ''),
(9, 7, '', 15, 45, '', '09:30:00', 30, '2024-01-29', 'Pending', ''),
(10, 8, '', 15, 58, '', '10:00:00', 60, '2024-01-29', 'Pending', '');

-- --------------------------------------------------------

--
-- Table structure for table `doctors`
--

CREATE TABLE `doctors` (
  `doctor_table_id` int(11) NOT NULL,
  `doctor_id` int(11) DEFAULT NULL,
  `doctor_degree` varchar(250) DEFAULT NULL,
  `doctor_school` text DEFAULT NULL,
  `doctor_grad_year` year(4) DEFAULT NULL,
  `doctor_status` varchar(32) DEFAULT NULL,
  `doctor_specialties` text DEFAULT NULL,
  `doctor_services` text DEFAULT NULL,
  `doctor_about_me` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `doctors`
--

INSERT INTO `doctors` (`doctor_table_id`, `doctor_id`, `doctor_degree`, `doctor_school`, `doctor_grad_year`, `doctor_status`, `doctor_specialties`, `doctor_services`, `doctor_about_me`) VALUES
(1, 3, 'Bachelor of Science in Medical Technology', 'Ago Medical Group', '1993', 'Active', 'Cholesterol,Blood pressure,Eye Checkup,Osteoporosis,Physical exams,Skin check', 'General Body Check-up', ''),
(2, 4, 'BS RADIOLOGIC TECHNOLOGIST', 'Medical Colleges of Northern Philippines', '0000', 'Active', 'X-ray,Ultrasound', 'Radiology', ''),
(3, 7, 'BS MEDICAL TECHNOLOGY', 'University of Sto. Thomas', '0000', 'Active', 'Cbc,Bloodtyping,Clotting time/Bleeding time', 'Hematology', ''),
(4, 8, 'Bachelor of Science in Medical Technology', 'Medical Colleges of Northern Philippines', '0000', 'Active', 'Urinalysis,Fecalysis,Pregnancy Test', 'Clinical Microscopy', ''),
(5, 9, 'BS MEDICAL TECHNOLOGY', 'University of Sto. Thomas', '0000', 'Active', 'FBS,BUN,Creatinine,Triglycerides', 'Blood Chemistry', ''),
(6, 10, 'BS MEDICAL TECHNOLOGY', 'Medical Colleges of Northern Philippines', '0000', 'Active', 'HBsAg,Rpr,HIV', 'Serology', ''),
(7, 11, 'Bachelor of Science in Medical Technology', 'University of Sto. Thomas', '0000', 'Active', 'Sputum Exam,Wound & Miscellaneous Culture,Stool Culture.', 'Microbiology', ''),
(8, 12, 'BS DM', 'University of Sto. Thomas', '0000', 'Active', 'Dental Check-up,Tooth Extraction,Teeth Cleening', 'Dentistry', '');

-- --------------------------------------------------------

--
-- Table structure for table `doctor_schedule`
--

CREATE TABLE `doctor_schedule` (
  `doctor_schedule_id` int(11) NOT NULL,
  `doctor_id` int(11) NOT NULL,
  `doctor_schedule_day` enum('Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday') NOT NULL,
  `doctor_schedule_start_time` time NOT NULL,
  `doctor_schedule_end_time` time NOT NULL,
  `doctor_schedule_duration` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `doctor_schedule`
--

INSERT INTO `doctor_schedule` (`doctor_schedule_id`, `doctor_id`, `doctor_schedule_day`, `doctor_schedule_start_time`, `doctor_schedule_end_time`, `doctor_schedule_duration`) VALUES
(1, 3, 'Monday', '08:30:00', '09:00:00', 30),
(7, 3, 'Saturday', '09:30:00', '10:00:00', 30),
(17, 3, 'Wednesday', '10:30:00', '11:00:00', 30),
(18, 3, 'Wednesday', '01:00:00', '01:30:00', 30),
(19, 3, 'Wednesday', '13:30:00', '14:00:00', 30),
(23, 3, 'Friday', '09:00:00', '09:30:00', 30),
(24, 3, 'Friday', '09:30:00', '10:30:00', 60),
(25, 3, 'Friday', '10:30:00', '11:30:00', 60),
(26, 3, 'Friday', '13:30:00', '14:30:00', 60),
(27, 4, 'Sunday', '13:00:00', '13:30:00', 30),
(28, 4, 'Sunday', '13:30:00', '14:00:00', 30),
(29, 4, 'Sunday', '14:00:00', '14:30:00', 30),
(30, 4, 'Monday', '13:00:00', '13:30:00', 30),
(31, 4, 'Monday', '13:30:00', '14:00:00', 30),
(32, 4, 'Monday', '14:00:00', '14:30:00', 30),
(33, 4, 'Monday', '14:30:00', '15:00:00', 30),
(34, 4, 'Monday', '15:00:00', '15:30:00', 30),
(35, 4, 'Tuesday', '13:00:00', '13:30:00', 30),
(36, 4, 'Tuesday', '13:30:00', '14:00:00', 30),
(37, 4, 'Tuesday', '14:00:00', '14:30:00', 30),
(40, 4, 'Friday', '13:00:00', '13:30:00', 30),
(41, 4, 'Friday', '13:30:00', '14:00:00', 30),
(42, 4, 'Saturday', '13:00:00', '13:30:00', 30),
(43, 4, 'Saturday', '13:30:00', '14:00:00', 30),
(45, 7, 'Monday', '09:30:00', '10:00:00', 30),
(46, 7, 'Monday', '10:30:00', '11:00:00', 30),
(47, 7, 'Monday', '11:00:00', '11:30:00', 30),
(48, 7, 'Monday', '13:00:00', '14:00:00', 60),
(49, 7, 'Monday', '14:00:00', '15:00:00', 60),
(50, 7, 'Tuesday', '09:00:00', '09:30:00', 30),
(51, 7, 'Tuesday', '09:30:00', '10:00:00', 30),
(52, 7, 'Tuesday', '10:00:00', '10:30:00', 30),
(53, 7, 'Wednesday', '13:00:00', '13:30:00', 30),
(54, 7, 'Wednesday', '13:30:00', '14:30:00', 60),
(55, 7, 'Thursday', '09:00:00', '10:00:00', 60),
(56, 7, 'Friday', '09:00:00', '10:00:00', 60),
(57, 7, 'Friday', '10:00:00', '11:00:00', 60),
(58, 8, 'Monday', '10:00:00', '11:00:00', 60),
(59, 8, 'Monday', '13:00:00', '14:00:00', 60),
(60, 8, 'Monday', '14:00:00', '15:00:00', 60),
(61, 8, 'Monday', '15:00:00', '16:00:00', 60),
(62, 8, 'Tuesday', '09:00:00', '10:00:00', 60),
(63, 8, 'Tuesday', '10:00:00', '11:00:00', 60),
(64, 8, 'Tuesday', '11:00:00', '12:00:00', 60),
(65, 8, 'Wednesday', '09:00:00', '10:00:00', 60),
(66, 8, 'Wednesday', '13:00:00', '14:00:00', 60),
(67, 8, 'Wednesday', '14:00:00', '15:00:00', 60),
(68, 8, 'Thursday', '10:00:00', '11:00:00', 60),
(69, 8, 'Thursday', '13:00:00', '14:00:00', 60),
(70, 8, 'Thursday', '14:00:00', '15:00:00', 60),
(74, 9, 'Sunday', '13:00:00', '13:30:00', 30),
(75, 9, 'Sunday', '13:30:00', '14:00:00', 30),
(76, 9, 'Sunday', '14:00:00', '15:00:00', 60),
(77, 9, 'Monday', '10:30:00', '11:00:00', 30),
(78, 9, 'Monday', '13:00:00', '14:00:00', 60),
(79, 9, 'Monday', '14:00:00', '15:00:00', 60),
(80, 9, 'Tuesday', '09:30:00', '10:15:00', 45),
(81, 9, 'Tuesday', '10:30:00', '11:30:00', 60),
(82, 9, 'Tuesday', '13:00:00', '14:00:00', 60),
(83, 9, 'Tuesday', '14:00:00', '15:00:00', 60),
(84, 9, 'Wednesday', '09:00:00', '10:00:00', 60),
(85, 9, 'Wednesday', '10:00:00', '11:00:00', 60),
(86, 9, 'Wednesday', '11:00:00', '12:00:00', 60),
(87, 9, 'Thursday', '13:00:00', '14:00:00', 60),
(88, 9, 'Thursday', '02:00:00', '03:00:00', 60),
(89, 9, 'Thursday', '15:00:00', '16:00:00', 60),
(90, 9, 'Friday', '09:00:00', '10:00:00', 60),
(91, 9, 'Friday', '10:00:00', '11:00:00', 60),
(92, 3, 'Sunday', '01:00:00', '01:30:00', 30),
(93, 3, 'Sunday', '13:30:00', '14:30:00', 60),
(94, 3, 'Sunday', '14:30:00', '15:00:00', 30),
(95, 3, 'Sunday', '15:00:00', '16:00:00', 60),
(96, 3, 'Sunday', '16:00:00', '16:30:00', 30),
(97, 3, 'Sunday', '16:30:00', '17:00:00', 30),
(98, 3, 'Monday', '09:00:00', '10:00:00', 60),
(99, 3, 'Monday', '10:00:00', '11:00:00', 60),
(100, 3, 'Monday', '11:00:00', '11:30:00', 30),
(101, 3, 'Monday', '13:00:00', '13:30:00', 30),
(102, 3, 'Monday', '13:30:00', '14:00:00', 30),
(103, 3, 'Wednesday', '14:00:00', '15:00:00', 60),
(104, 3, 'Wednesday', '15:00:00', '16:00:00', 60),
(105, 3, 'Thursday', '09:00:00', '10:00:00', 60),
(106, 3, 'Thursday', '10:00:00', '11:00:00', 60),
(107, 3, 'Thursday', '11:30:00', '12:00:00', 30),
(108, 3, 'Thursday', '13:00:00', '13:30:00', 30),
(109, 3, 'Thursday', '13:30:00', '14:30:00', 60),
(110, 3, 'Thursday', '14:30:00', '15:00:00', 30),
(111, 3, 'Friday', '14:30:00', '15:00:00', 30),
(112, 3, 'Friday', '15:00:00', '16:00:00', 60),
(113, 3, 'Saturday', '10:00:00', '10:30:00', 30),
(114, 3, 'Saturday', '10:30:00', '11:30:00', 60),
(115, 4, 'Sunday', '14:30:00', '15:00:00', 30),
(116, 4, 'Sunday', '15:00:00', '15:30:00', 30),
(117, 4, 'Sunday', '15:30:00', '16:00:00', 30),
(118, 4, 'Tuesday', '14:30:00', '15:00:00', 30),
(119, 4, 'Tuesday', '15:00:00', '15:30:00', 30),
(120, 4, 'Friday', '14:00:00', '14:30:00', 30),
(121, 4, 'Friday', '14:30:00', '15:00:00', 30),
(122, 4, 'Friday', '15:00:00', '15:30:00', 30),
(123, 4, 'Friday', '15:30:00', '16:00:00', 30),
(124, 4, 'Saturday', '14:00:00', '14:30:00', 30),
(125, 4, 'Saturday', '14:30:00', '15:00:00', 30),
(126, 7, 'Monday', '15:00:00', '16:00:00', 60),
(127, 7, 'Tuesday', '10:30:00', '11:00:00', 30),
(128, 7, 'Tuesday', '11:00:00', '11:30:00', 30),
(129, 7, 'Tuesday', '13:00:00', '14:00:00', 60),
(130, 7, 'Tuesday', '14:00:00', '15:00:00', 60),
(131, 7, 'Wednesday', '14:30:00', '15:00:00', 30),
(132, 7, 'Wednesday', '15:00:00', '16:00:00', 60),
(133, 7, 'Wednesday', '16:00:00', '17:00:00', 60),
(134, 7, 'Thursday', '10:00:00', '11:00:00', 60),
(135, 7, 'Thursday', '11:00:00', '12:00:00', 60),
(136, 7, 'Thursday', '13:00:00', '13:30:00', 30),
(137, 7, 'Thursday', '13:30:00', '14:00:00', 30),
(138, 7, 'Thursday', '14:00:00', '14:30:00', 30),
(139, 7, 'Thursday', '14:30:00', '15:00:00', 30),
(140, 7, 'Friday', '11:00:00', '12:00:00', 60),
(141, 7, 'Friday', '13:00:00', '13:30:00', 30),
(142, 7, 'Friday', '13:30:00', '14:00:00', 30),
(143, 7, 'Friday', '14:00:00', '14:30:00', 30),
(144, 7, 'Friday', '14:30:00', '15:00:00', 30),
(145, 8, 'Monday', '16:00:00', '17:00:00', 60),
(146, 8, 'Tuesday', '13:00:00', '14:00:00', 60),
(147, 8, 'Tuesday', '14:00:00', '15:00:00', 60),
(148, 8, 'Tuesday', '15:00:00', '16:00:00', 60),
(149, 8, 'Wednesday', '15:00:00', '16:00:00', 60),
(150, 8, 'Wednesday', '16:00:00', '17:00:00', 60),
(151, 8, 'Thursday', '15:00:00', '16:00:00', 60),
(152, 8, 'Thursday', '16:00:00', '17:00:00', 60),
(153, 8, 'Saturday', '10:00:00', '11:00:00', 60),
(154, 8, 'Saturday', '11:00:00', '12:00:00', 60),
(155, 8, 'Saturday', '13:00:00', '14:00:00', 60),
(156, 8, 'Saturday', '14:00:00', '15:00:00', 60),
(157, 8, 'Saturday', '15:00:00', '16:00:00', 60),
(158, 9, 'Friday', '13:00:00', '14:00:00', 60),
(159, 9, 'Friday', '14:00:00', '15:00:00', 60),
(160, 10, 'Monday', '09:00:00', '09:45:00', 45),
(161, 10, 'Monday', '09:45:00', '10:30:00', 45),
(162, 10, 'Monday', '10:30:00', '11:15:00', 45),
(163, 10, 'Monday', '11:15:00', '12:00:00', 45),
(164, 10, 'Monday', '13:00:00', '14:00:00', 60),
(165, 10, 'Monday', '14:00:00', '15:00:00', 60),
(166, 10, 'Monday', '15:00:00', '16:00:00', 60),
(167, 10, 'Tuesday', '09:00:00', '09:45:00', 45),
(168, 10, 'Tuesday', '09:45:00', '10:30:00', 45),
(169, 10, 'Tuesday', '10:30:00', '11:15:00', 45),
(170, 10, 'Tuesday', '11:15:00', '12:00:00', 45),
(171, 10, 'Tuesday', '13:00:00', '14:00:00', 60),
(172, 10, 'Tuesday', '14:00:00', '15:00:00', 60),
(173, 10, 'Tuesday', '15:00:00', '16:00:00', 60),
(174, 10, 'Wednesday', '09:00:00', '09:45:00', 45),
(175, 10, 'Wednesday', '09:45:00', '10:30:00', 45),
(176, 10, 'Wednesday', '10:30:00', '11:15:00', 45),
(177, 10, 'Wednesday', '11:15:00', '12:00:00', 45),
(178, 10, 'Wednesday', '13:00:00', '14:00:00', 60),
(179, 10, 'Wednesday', '14:00:00', '15:00:00', 60),
(180, 10, 'Wednesday', '15:00:00', '16:00:00', 60),
(181, 10, 'Thursday', '09:00:00', '09:45:00', 45),
(182, 10, 'Thursday', '09:45:00', '10:30:00', 45),
(183, 10, 'Thursday', '10:30:00', '11:15:00', 45),
(184, 10, 'Thursday', '11:15:00', '12:00:00', 45),
(185, 10, 'Thursday', '13:00:00', '14:00:00', 60),
(186, 10, 'Thursday', '14:00:00', '15:00:00', 60),
(187, 10, 'Thursday', '15:00:00', '16:00:00', 60),
(188, 10, 'Friday', '09:00:00', '09:45:00', 45),
(189, 10, 'Friday', '09:45:00', '10:30:00', 45),
(190, 10, 'Friday', '10:30:00', '11:15:00', 45),
(191, 10, 'Friday', '11:15:00', '12:00:00', 45),
(192, 10, 'Friday', '13:00:00', '14:00:00', 60),
(193, 10, 'Friday', '14:00:00', '15:00:00', 60),
(194, 10, 'Friday', '15:00:00', '16:00:00', 60),
(195, 11, 'Sunday', '13:30:00', '14:00:00', 30),
(196, 11, 'Sunday', '14:00:00', '14:30:00', 30),
(197, 11, 'Sunday', '14:30:00', '15:00:00', 30),
(198, 11, 'Sunday', '15:00:00', '15:30:00', 30),
(199, 11, 'Sunday', '15:30:00', '16:00:00', 30),
(200, 11, 'Sunday', '16:00:00', '16:30:00', 30),
(201, 11, 'Sunday', '16:30:00', '17:00:00', 30),
(202, 11, 'Tuesday', '09:30:00', '10:15:00', 45),
(203, 11, 'Tuesday', '10:15:00', '11:00:00', 45),
(204, 11, 'Tuesday', '11:00:00', '11:45:00', 45),
(205, 11, 'Tuesday', '13:00:00', '13:45:00', 45),
(206, 11, 'Tuesday', '13:45:00', '14:30:00', 45),
(207, 11, 'Tuesday', '14:30:00', '15:15:00', 45),
(208, 11, 'Tuesday', '15:15:00', '16:00:00', 45),
(209, 11, 'Thursday', '09:00:00', '09:45:00', 45),
(210, 11, 'Thursday', '09:45:00', '10:30:00', 45),
(211, 11, 'Thursday', '10:30:00', '11:15:00', 45),
(212, 11, 'Thursday', '13:00:00', '14:00:00', 60),
(213, 11, 'Thursday', '14:00:00', '15:00:00', 60),
(214, 11, 'Thursday', '15:00:00', '16:00:00', 60),
(215, 11, 'Friday', '09:00:00', '09:45:00', 45),
(216, 11, 'Friday', '09:45:00', '10:30:00', 45),
(217, 11, 'Friday', '10:30:00', '11:15:00', 45),
(218, 11, 'Friday', '13:00:00', '14:00:00', 60),
(219, 11, 'Friday', '14:00:00', '15:00:00', 60),
(220, 11, 'Friday', '15:00:00', '16:00:00', 60),
(221, 11, 'Saturday', '09:00:00', '09:45:00', 45),
(222, 11, 'Saturday', '09:45:00', '10:30:00', 45),
(223, 11, 'Saturday', '10:30:00', '11:15:00', 45),
(224, 11, 'Saturday', '13:00:00', '14:00:00', 60),
(225, 11, 'Saturday', '14:00:00', '15:00:00', 60),
(226, 11, 'Saturday', '15:00:00', '16:00:00', 60),
(227, 12, 'Monday', '09:00:00', '09:45:00', 45),
(228, 12, 'Monday', '09:45:00', '10:30:00', 45),
(229, 12, 'Monday', '10:30:00', '11:15:00', 45),
(230, 12, 'Monday', '11:15:00', '12:00:00', 45),
(231, 12, 'Monday', '13:00:00', '13:45:00', 45),
(232, 12, 'Monday', '13:45:00', '14:30:00', 45),
(233, 12, 'Monday', '14:30:00', '15:15:00', 45),
(234, 12, 'Tuesday', '09:00:00', '09:45:00', 45),
(235, 12, 'Tuesday', '09:45:00', '10:30:00', 45),
(236, 12, 'Tuesday', '10:30:00', '11:15:00', 45),
(237, 12, 'Tuesday', '11:15:00', '12:00:00', 45),
(238, 12, 'Tuesday', '13:00:00', '14:00:00', 60),
(239, 12, 'Tuesday', '14:00:00', '15:00:00', 60),
(240, 12, 'Tuesday', '15:00:00', '16:00:00', 60),
(241, 12, 'Wednesday', '09:00:00', '09:45:00', 45),
(242, 12, 'Wednesday', '09:45:00', '10:30:00', 45),
(243, 12, 'Wednesday', '10:30:00', '11:15:00', 45),
(244, 12, 'Wednesday', '11:15:00', '12:00:00', 45),
(245, 12, 'Wednesday', '13:00:00', '14:00:00', 60),
(246, 12, 'Wednesday', '14:00:00', '15:00:00', 60),
(247, 12, 'Wednesday', '15:00:00', '16:00:00', 60),
(248, 12, 'Thursday', '09:00:00', '09:45:00', 45),
(249, 12, 'Thursday', '09:45:00', '10:30:00', 45),
(250, 12, 'Thursday', '10:30:00', '11:15:00', 45),
(251, 12, 'Thursday', '11:15:00', '12:00:00', 45),
(252, 12, 'Thursday', '13:00:00', '14:00:00', 60),
(253, 12, 'Thursday', '14:00:00', '15:00:00', 60),
(254, 12, 'Thursday', '15:00:00', '16:00:00', 60),
(255, 12, 'Friday', '09:00:00', '09:45:00', 45),
(256, 12, 'Friday', '09:45:00', '10:30:00', 45),
(257, 12, 'Friday', '10:30:00', '11:15:00', 45),
(258, 12, 'Friday', '11:15:00', '12:00:00', 45),
(259, 12, 'Friday', '13:00:00', '14:00:00', 60),
(260, 12, 'Friday', '14:00:00', '15:00:00', 60),
(261, 12, 'Friday', '15:00:00', '16:00:00', 60);

-- --------------------------------------------------------

--
-- Table structure for table `facilities`
--

CREATE TABLE `facilities` (
  `facility_id` int(11) NOT NULL,
  `description` text NOT NULL,
  `icon` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `facilities`
--

INSERT INTO `facilities` (`facility_id`, `description`, `icon`) VALUES
(1, '', 'assets/img/features/IMG20231211152120.jpg'),
(2, '', 'assets/img/features/IMG20231211150751.jpg'),
(3, '', 'assets/img/features/IMG20231211150015.jpg'),
(4, '', 'assets/img/features/IMG20231211145234.jpg'),
(5, '', 'assets/img/features/IMG20231211145231.jpg'),
(6, '', 'assets/img/features/IMG20231211145138.jpg'),
(7, '', 'assets/img/features/IMG20231211141321.jpg'),
(8, '', 'assets/img/features/IMG20231211141313.jpg'),
(9, '', 'assets/img/features/IMG20231211141254.jpg'),
(10, '', 'assets/img/features/IMG20231211141238.jpg'),
(11, '', 'assets/img/features/IMG20231211141225.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `finances`
--

CREATE TABLE `finances` (
  `finance_id` int(11) NOT NULL,
  `description` text NOT NULL,
  `pic_path` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `patient_form`
--

CREATE TABLE `patient_form` (
  `form_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `health_description` text DEFAULT NULL,
  `prescription` text DEFAULT NULL,
  `medication_name` text DEFAULT NULL,
  `pill_amount` text DEFAULT NULL,
  `pill_doses` text DEFAULT NULL,
  `otc_medicines` text DEFAULT NULL,
  `herbal_medicine_list` text DEFAULT NULL,
  `other_medicine_list` text DEFAULT NULL,
  `allergic_reaction` text DEFAULT NULL,
  `allergic_medicine` text DEFAULT NULL,
  `allergic_reaction_description` text DEFAULT NULL,
  `allergy_triggers` text DEFAULT NULL,
  `other_allergy_triggers` text DEFAULT NULL,
  `pregnant` text DEFAULT NULL,
  `pregnancy_times` int(11) DEFAULT NULL,
  `children_birthed` int(11) DEFAULT NULL,
  `pap_smear` text DEFAULT NULL,
  `last_pap_smear_date` date DEFAULT NULL,
  `abnormal_pap` text DEFAULT NULL,
  `mammogram` text DEFAULT NULL,
  `last_mammogram_date` date DEFAULT NULL,
  `mother_medical_problems` text DEFAULT NULL,
  `father_medical_problems` text DEFAULT NULL,
  `sisters_medical_problems` text DEFAULT NULL,
  `brothers_medical_problems` text DEFAULT NULL,
  `medical_conditions` text DEFAULT NULL,
  `other_condition` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `records`
--

CREATE TABLE `records` (
  `record_id` int(11) NOT NULL,
  `doctor_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `description` text NOT NULL,
  `record_date` date NOT NULL,
  `attachment_title` text NOT NULL,
  `attachment_path` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `review_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `doctor_id` int(11) NOT NULL,
  `review_date` date NOT NULL,
  `comment` text NOT NULL,
  `rating` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `service_id` int(11) NOT NULL,
  `service` text NOT NULL,
  `specialty` text NOT NULL,
  `icon` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`service_id`, `service`, `specialty`, `icon`) VALUES
(1, 'Hematology', 'blood extraction, blood transfusion', 'assets/img/specialities/hematology.png'),
(2, 'Clinical Microscopy', '', 'assets/img/specialities/clinicalm.png'),
(3, 'Blood Chemistry', '', 'assets/img/specialities/bloodchem.svg'),
(4, 'Serology', '', 'assets/img/specialities/serology.png'),
(5, 'Microbiology', '', 'assets/img/specialities/microbiology.png'),
(6, 'Radiology', '', 'assets/img/specialities/xray.jpg'),
(7, 'Dentistry', '', 'assets/img/specialities/dentistry.png');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `fname` varchar(250) NOT NULL,
  `lname` varchar(250) NOT NULL,
  `birthday` date DEFAULT NULL,
  `gender` varchar(250) NOT NULL,
  `username` varchar(250) NOT NULL,
  `password` varchar(250) NOT NULL,
  `email` varchar(250) NOT NULL,
  `contactnum` varchar(250) NOT NULL,
  `usertype` varchar(250) NOT NULL,
  `idpic` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `fname`, `lname`, `birthday`, `gender`, `username`, `password`, `email`, `contactnum`, `usertype`, `idpic`) VALUES
(2, 'admin', 'admin', '1996-12-22', 'Male', 'admin', '$2y$10$J9fGOnJQXWfEWWB1gK1ddeFhHvJchQa0Frha8E6eGSypP3PBcBXje', 'admin@admin.com', '09922863662', 'admin', 'assets/uploads/idpics/default-id.png'),
(3, 'Fullbert alec', 'Gillego', '1971-02-09', 'Male', 'fullbertalec', '$2y$10$FGt5ihRD1Y44J9CFC8u06e1GsmvCgfeX10xVIAHi5b2zmOhUem3ki', 'fullbertalecgillego@gmail.com', '09158364995', 'practitioner', 'assets/uploads/idpics/FULBERT ALEC R. GILLEGO(2).jpg'),
(4, 'Angelica', 'Batallones', '1995-09-01', 'Female', 'angelica', '$2y$10$KM463jVN7P1yN/T9xgaN2O09LzvpIIg07fDZJce76Aj6IlQqqi/fm', 'angelicabatallones@gmail.com', '09463619440', 'practitioner', 'assets/uploads/idpics/ANGELICA BATALLONES.jpg'),
(7, 'Guadalyn', 'Nuyda', '1987-07-16', 'Female', 'guadalyn', '$2y$10$JQDgXy2Bdue3eOeOOQK6wu5BvA2Zzmg.fC2YV6.//N0xOSwLJ0ZU6', 'guadalynnuyda@gmail.com', '0912345678', 'practitioner', 'assets/uploads/idpics/GUADALYN D(1).jpg'),
(8, 'Janzhel Marie', 'Rosales', '1995-07-19', 'Female', 'janzhelmarie', '$2y$10$MV62AeniaewDaFqC70x11eejdULyG1RcWEGTETHKcXWW6JZexkPDe', 'janzhelmarierosales@gmail.com', '09995462789', 'practitioner', 'assets/uploads/idpics/JANZHEL MARIE R(1).jpg'),
(9, 'Jocelyn', 'Lozares', '1988-03-17', 'Female', 'jocelyn', '$2y$10$oGJSNkO8SPasH5oY4r3heeisQivYeMBY90AdHy/bQJryYXdfv8fCm', 'jocelynlozares@gmail.com', '09922863662', 'practitioner', 'assets/uploads/idpics/JOCELYN D. LOZARES.jpg'),
(10, 'Madeline ', 'Ranola', '1977-12-02', 'Female', 'madeline', '$2y$10$Ai6ObmrtDzdDVvfJ20ssR.aRir0VmyNduRIbTgtJUHwG/76qa39vO', 'ranolamadeline@gmail.com', '09995462789', 'practitioner', 'assets/uploads/idpics/MADELINE DL. RAÑOLA.jpg'),
(11, 'Manuel Lius', 'Placides', '1994-06-08', 'Male', 'manuellius', '$2y$10$dCaNicuVNF/48TShtl5qi.eiuNcjtczS24BsA.v9ZQxmY73orzQxy', 'manuelliusplacides@gmail.com', '09922567291', 'practitioner', 'assets/uploads/idpics/MANUEL LIUS V. PLACIDES(1).jpg'),
(12, 'Rose Mary', 'Donasco', '1994-03-09', 'Female', 'rosemary', '$2y$10$Bg7C/HhuzETqzslMe6nR4OWfuDjFQoOG5XnOl4tcgPk3avfv70bui', 'rosemarydonasco@gmail.com', '09922863662', 'practitioner', 'assets/uploads/idpics/ROSE MARY D. DONASCO(2).jpg'),
(15, 'patient', 'account', '2000-12-01', 'Male', 'patient', '$2y$10$oYTkNfO9/eSpFlIbwY8Hw.hm2U1K.PTPWj5vlADWM5yYtrNhbEkPK', 'patient@gmail.com', '09925873545', 'patient', 'assets/uploads/idpics/default-id.png');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `announcements`
--
ALTER TABLE `announcements`
  ADD PRIMARY KEY (`announcement_id`);

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`appointment_id`),
  ADD KEY `fk_appointments_doctors` (`doctor_id`),
  ADD KEY `fk_appointments_users` (`user_id`);

--
-- Indexes for table `doctors`
--
ALTER TABLE `doctors`
  ADD PRIMARY KEY (`doctor_table_id`),
  ADD KEY `fk_doctor_id` (`doctor_id`);

--
-- Indexes for table `doctor_schedule`
--
ALTER TABLE `doctor_schedule`
  ADD PRIMARY KEY (`doctor_schedule_id`),
  ADD KEY `fk_doctor_schedule_doctor_id` (`doctor_id`);

--
-- Indexes for table `facilities`
--
ALTER TABLE `facilities`
  ADD PRIMARY KEY (`facility_id`);

--
-- Indexes for table `finances`
--
ALTER TABLE `finances`
  ADD PRIMARY KEY (`finance_id`);

--
-- Indexes for table `patient_form`
--
ALTER TABLE `patient_form`
  ADD PRIMARY KEY (`form_id`),
  ADD KEY `fk_user_id` (`user_id`);

--
-- Indexes for table `records`
--
ALTER TABLE `records`
  ADD PRIMARY KEY (`record_id`),
  ADD KEY `fk_records_doctors` (`doctor_id`),
  ADD KEY `fk_records_users` (`user_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`review_id`),
  ADD KEY `fk_reviews_doctors` (`doctor_id`),
  ADD KEY `fk_reviews_users` (`user_id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`service_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `announcements`
--
ALTER TABLE `announcements`
  MODIFY `announcement_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `appointment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `doctors`
--
ALTER TABLE `doctors`
  MODIFY `doctor_table_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `doctor_schedule`
--
ALTER TABLE `doctor_schedule`
  MODIFY `doctor_schedule_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=263;

--
-- AUTO_INCREMENT for table `facilities`
--
ALTER TABLE `facilities`
  MODIFY `facility_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `finances`
--
ALTER TABLE `finances`
  MODIFY `finance_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `patient_form`
--
ALTER TABLE `patient_form`
  MODIFY `form_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `records`
--
ALTER TABLE `records`
  MODIFY `record_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `review_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `service_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `appointments`
--
ALTER TABLE `appointments`
  ADD CONSTRAINT `fk_appointments_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `doctors`
--
ALTER TABLE `doctors`
  ADD CONSTRAINT `fk_doctor_id` FOREIGN KEY (`doctor_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `doctor_schedule`
--
ALTER TABLE `doctor_schedule`
  ADD CONSTRAINT `fk_doctor_schedule_doctor_id` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`doctor_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `patient_form`
--
ALTER TABLE `patient_form`
  ADD CONSTRAINT `fk_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `records`
--
ALTER TABLE `records`
  ADD CONSTRAINT `fk_records_doctors` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`doctor_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_records_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `fk_reviews_doctors` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`doctor_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_reviews_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
