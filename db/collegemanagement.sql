-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 03, 2025 at 06:50 AM
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
-- Database: `collegemanagement`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `mobile_number` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`, `email`, `created_at`, `mobile_number`) VALUES
(1, 'admin@gmail.com', '$2y$10$bstswVOaRPizI3xCKfLPye49PK83f4.kICe82qcTTn.E/ZlZ26ZJW', 'admin@gmail.com', '2024-11-10 09:57:16', '1234567890');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_assignments`
--

CREATE TABLE `tbl_assignments` (
  `assignment_id` int(11) NOT NULL,
  `faculty_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `assignment_title` varchar(255) NOT NULL,
  `assignment_description` text DEFAULT NULL,
  `assignment_file` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_assignments`
--

INSERT INTO `tbl_assignments` (`assignment_id`, `faculty_id`, `course_id`, `subject_id`, `assignment_title`, `assignment_description`, `assignment_file`, `created_at`) VALUES
(3, 1, 2, 13, 'Complete this as soon as possible', 'Yo', '1740158164_jeans.png', '2025-02-21 17:16:04'),
(4, 1, 2, 52, 'Complete this as soon as possible', 'asa', '1740158195_departments_report (2).pdf', '2025-02-21 17:16:35');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_attendance`
--

CREATE TABLE `tbl_attendance` (
  `attendance_id` int(11) NOT NULL,
  `attendance_student_id` int(11) NOT NULL,
  `attendance_photo` varchar(255) DEFAULT NULL,
  `attendance_date` datetime NOT NULL,
  `attendance_latitude` decimal(10,8) DEFAULT NULL,
  `attendance_longitude` decimal(11,8) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_attendance`
--

INSERT INTO `tbl_attendance` (`attendance_id`, `attendance_student_id`, `attendance_photo`, `attendance_date`, `attendance_latitude`, `attendance_longitude`) VALUES
(1, 7, 'attendance_7_1739960496.png', '2025-02-19 15:51:36', 18.14668380, 74.57659500);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_course`
--

CREATE TABLE `tbl_course` (
  `course_id` int(11) NOT NULL,
  `course_name` varchar(255) NOT NULL,
  `course_code` varchar(50) NOT NULL,
  `course_description` text DEFAULT NULL,
  `course_credits` int(11) DEFAULT NULL,
  `course_department_id` int(11) NOT NULL,
  `course_duration` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_course`
--

INSERT INTO `tbl_course` (`course_id`, `course_name`, `course_code`, `course_description`, `course_credits`, `course_department_id`, `course_duration`, `created_at`) VALUES
(2, 'Data Structures', 'CS102', 'This course covers the fundamental data structures such as arrays, linked lists, stacks, queues, and trees, which are essential for problem-solving.', 4, 1, 2, '2024-11-12 18:35:11'),
(3, 'Operating Systems', 'CS202', 'This course covers the basic principles of operating systems, including process management, memory management, file systems, and system security.', 4, 1, 2, '2024-11-12 18:35:11'),
(4, 'Fluid Mechanics', 'ME102', 'This course deals with the study of fluids at rest and in motion, including topics like fluid properties, fluid statics, and flow dynamics.', 3, 2, 2, '2024-11-12 18:35:11'),
(5, 'Thermodynamics', 'ME202', 'This course focuses on the fundamental principles of thermodynamics, including laws of thermodynamics, heat engines, and refrigeration cycles.', 4, 2, 2, '2024-11-12 18:35:11'),
(6, 'Circuit Analysis', 'EE101', 'This course introduces the analysis of electrical circuits, including techniques for solving DC and AC circuits, network theorems, and transient analysis.', 4, 3, 2, '2024-11-12 18:35:11'),
(7, 'Power Systems', 'EE201', 'This course covers the design, operation, and maintenance of power generation, transmission, and distribution systems.', 4, 3, 2, '2024-11-12 18:35:11');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_department`
--

CREATE TABLE `tbl_department` (
  `department_id` int(11) NOT NULL,
  `department_name` varchar(255) NOT NULL,
  `department_code` varchar(50) NOT NULL,
  `department_description` text DEFAULT NULL,
  `department_hod_name` varchar(255) DEFAULT NULL,
  `department_hod_contact` varchar(20) DEFAULT NULL,
  `department_email` varchar(255) NOT NULL,
  `department_phone` varchar(20) DEFAULT NULL,
  `department_password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_department`
--

INSERT INTO `tbl_department` (`department_id`, `department_name`, `department_code`, `department_description`, `department_hod_name`, `department_hod_contact`, `department_email`, `department_phone`, `department_password`, `created_at`) VALUES
(1, 'Computer Science', 'CS101', 'The Computer Science department focuses on computing, algorithms, and software engineering. It offers a comprehensive curriculum in various CS fields.', 'Dr. John Doe', '9876543210', 'cs@university.edu', '011-23456789', 'cs@university.edu', '2024-11-12 18:32:21'),
(2, 'Mechanical Engineering', 'ME101', 'The Mechanical Engineering department offers programs focused on mechanics, thermodynamics, and manufacturing technologies.', 'Dr. Jane Smith', '9123456789', 'me@university.edu', '011-98765432', 'me@university.edu', '2024-11-12 18:32:21'),
(3, 'Electrical Engineering', 'EE101', 'The Electrical Engineering department covers circuits, electromagnetics, and power systems with a strong focus on research and development.', 'Dr. Richard Lee', '9234567890', 'ee@university.edu', '011-11223344', 'password789', '2024-11-12 18:32:21');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_exam`
--

CREATE TABLE `tbl_exam` (
  `exam_id` int(11) NOT NULL,
  `exam_title` varchar(255) NOT NULL,
  `exam_description` text DEFAULT NULL,
  `exam_start_date` datetime NOT NULL,
  `exam_end_date` datetime NOT NULL,
  `exam_status` enum('Scheduled','Completed') DEFAULT 'Scheduled',
  `exam_department_id` int(11) DEFAULT NULL,
  `exam_course_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_exam`
--

INSERT INTO `tbl_exam` (`exam_id`, `exam_title`, `exam_description`, `exam_start_date`, `exam_end_date`, `exam_status`, `exam_department_id`, `exam_course_id`, `created_at`, `updated_at`) VALUES
(3, 'First Semester', 'www', '2024-12-01 21:31:00', '2025-02-02 15:48:00', 'Scheduled', 1, 3, '2025-02-02 12:18:00', '2025-02-03 02:25:11'),
(4, 'Second Semester', 'Compulsion\r\n', '2025-02-01 23:32:00', '2025-02-28 23:32:00', 'Scheduled', 1, 2, '2025-02-02 18:03:08', '2025-02-16 13:01:10');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_exam_schedule`
--

CREATE TABLE `tbl_exam_schedule` (
  `schedule_id` int(11) NOT NULL,
  `schedule_exam` varchar(255) NOT NULL,
  `schedule_course` varchar(255) NOT NULL,
  `schedule_subject` varchar(255) NOT NULL,
  `schedule_date` date NOT NULL,
  `schedule_start_time` time NOT NULL,
  `schedule_end_time` time NOT NULL,
  `schedule_status` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_exam_schedule`
--

INSERT INTO `tbl_exam_schedule` (`schedule_id`, `schedule_exam`, `schedule_course`, `schedule_subject`, `schedule_date`, `schedule_start_time`, `schedule_end_time`, `schedule_status`) VALUES
(2, '3', '3', '19', '2025-02-03', '13:29:00', '23:30:00', 0),
(3, '4', '2', '13', '2025-02-01', '11:39:00', '11:39:00', 0),
(4, '4', '2', '9', '2025-02-08', '11:41:00', '23:45:00', 0),
(5, '3', '3', '17', '1972-07-21', '09:44:00', '12:01:00', 2);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_faculty`
--

CREATE TABLE `tbl_faculty` (
  `faculty_id` int(11) NOT NULL,
  `faculty_name` varchar(255) NOT NULL,
  `faculty_email` varchar(255) NOT NULL,
  `faculty_password` varchar(255) NOT NULL,
  `faculty_phone` varchar(15) DEFAULT NULL,
  `faculty_designation` varchar(255) DEFAULT NULL,
  `faculty_department_id` int(11) NOT NULL,
  `faculty_specialization` varchar(255) DEFAULT NULL,
  `faculty_date_of_joining` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_faculty`
--

INSERT INTO `tbl_faculty` (`faculty_id`, `faculty_name`, `faculty_email`, `faculty_password`, `faculty_phone`, `faculty_designation`, `faculty_department_id`, `faculty_specialization`, `faculty_date_of_joining`, `created_at`) VALUES
(1, 'Neeta SM', 'neeta@faculty.com', 'neeta@faculty.com', '7304767697', 'Professor', 1, 'M-Tech', '2025-02-02', '2025-02-02 10:40:02'),
(3, 'Nanaware D. B.', 'nanaware@gmail.com', 'nanaware@gmail.com', '+1 (816) 625-22', 'Professor', 1, 'Repudiandae providen', '2025-02-16', '2025-02-16 18:10:08'),
(4, 'Monkey D Luffy', 'monkey@gmail.com', 'monkey@gmail.com', '+1 (568) 875-89', 'Velit veniam velit ', 2, 'Repudiandae providen', '2025-02-16', '2025-02-16 18:11:54');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_faculty_attendance`
--

CREATE TABLE `tbl_faculty_attendance` (
  `attendance_id` int(11) NOT NULL,
  `attendance_faculty_id` int(11) NOT NULL,
  `attendance_photo` varchar(255) DEFAULT NULL,
  `attendance_date` datetime NOT NULL DEFAULT current_timestamp(),
  `attendance_latitude` varchar(50) DEFAULT NULL,
  `attendance_longitude` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_notices`
--

CREATE TABLE `tbl_notices` (
  `notice_id` int(11) NOT NULL,
  `notice_title` varchar(255) NOT NULL,
  `notice_description` text NOT NULL,
  `notice_status` int(11) NOT NULL DEFAULT 1,
  `notice_date` datetime NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_notices`
--

INSERT INTO `tbl_notices` (`notice_id`, `notice_title`, `notice_description`, `notice_status`, `notice_date`, `created_at`) VALUES
(1, 'Tommorrow is Test', 'Be ready All', 0, '2025-02-01 03:55:00', '2025-02-01 10:25:58'),
(2, 'Ducimus nihil volup', 'Voluptatibus ut sunt', 1, '1999-04-06 17:27:00', '2025-02-03 16:18:26'),
(3, 'Sit adipisicing anim', 'Elit itaque fugit ', 1, '1984-07-16 05:24:00', '2025-02-03 16:18:30'),
(4, 'Id ipsum laboris min', 'Irure ratione aut co', 1, '2005-05-21 20:18:00', '2025-02-03 16:18:33'),
(5, 'Atque magni assumend', 'Aute nemo fuga Quid', 1, '1985-04-15 14:43:00', '2025-02-03 16:18:36'),
(6, 'Sed dolore esse eos ', 'Nostrud ut deleniti ', 1, '2021-05-21 07:38:00', '2025-02-03 16:19:49'),
(7, 'Et molestiae et fugi', 'Ratione ut eveniet ', 1, '2015-09-01 04:28:00', '2025-02-03 16:19:51'),
(8, 'Enim nihil officia s', 'Dolorum quia cupidit', 1, '2015-12-23 21:39:00', '2025-02-03 16:19:54'),
(9, 'Ea aliqua Et vitae ', 'Voluptate aperiam ut', 1, '2000-07-24 13:33:00', '2025-02-03 16:19:57'),
(10, 'Magnam beatae sequi ', 'Deleniti nobis quide', 1, '1992-04-22 17:54:00', '2025-02-03 16:20:01'),
(11, 'Okay Fine', 'A sequel film, entitled Demon Slayer: Kimetsu no Yaiba – The Movie: Mugen Train, premiered in Japan on October 16, 2020, with the staff and cast reprising their roles.[14]\r\n\r\nA second season, covering the \"Entertainment District\" arc, was announced on February 14, 2021, with the staff and cast from the first season and film returning.[15] Sotozaki returned as director, with character designs by Matsushima and animation by Ufotable.[16][17] In September 2021, it was announced that the second season would air for two cours.[18] On September 25, following the Fuji Television broadcast of Mugen Train, it was announced that the \"Entertainment District\" arc would premiere on December 5, and be preceded by a television series recompilation of the \"Mugen Train\" arc as featured in the film that premiered on October 10, 2021.[19]\r\n\r\nAt the end of the second season finale, it was announced that a third season, covering the \"Swordsmith Village\" arc, was in production.[20] It premiered on April 9, 2023, with a one-hour special,[21] and ended on June 18 of that same year with a 70-minute special.[22]\r\n\r\nA fourth season, covering the \"Hashira Training\" arc, was announced following the end of the third season finale.[23] It premiered on May 12, 2024, with a one-hour episode.[24][25] The season ended with a 60-minute episode, which aired on June 30 of the same year.[26]', 0, '2025-02-05 19:30:00', '2025-02-05 14:00:19');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_principal`
--

CREATE TABLE `tbl_principal` (
  `principal_id` int(11) NOT NULL,
  `principal_name` varchar(100) NOT NULL,
  `principal_email` varchar(100) NOT NULL,
  `principal_password` varchar(255) NOT NULL,
  `principal_phone` varchar(15) NOT NULL,
  `principal_address` text DEFAULT NULL,
  `principal_photo` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_principal`
--

INSERT INTO `tbl_principal` (`principal_id`, `principal_name`, `principal_email`, `principal_password`, `principal_phone`, `principal_address`, `principal_photo`, `created_at`) VALUES
(1, 'Test', 'oh@gmail.com', '$2y$10$LGHjhhHmOIG/6IvOVqpPkuD9eQ6qknbKnueHJY93PkqoETomEHpdu', '23456678908', 'Baramati, Pune', 'canteenautomation.png', '2025-02-17 18:23:04');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_regular_time_table`
--

CREATE TABLE `tbl_regular_time_table` (
  `regular_time_table_id` int(11) NOT NULL,
  `department_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `faculty_id` int(11) NOT NULL,
  `period_start_time` time NOT NULL,
  `period_end_time` time NOT NULL,
  `period_day` enum('Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_regular_time_table`
--

INSERT INTO `tbl_regular_time_table` (`regular_time_table_id`, `department_id`, `course_id`, `subject_id`, `faculty_id`, `period_start_time`, `period_end_time`, `period_day`, `created_at`) VALUES
(6, 1, 2, 8, 1, '10:44:00', '10:44:00', 'Wednesday', '2025-02-03 17:14:33'),
(7, 1, 2, 8, 1, '22:45:00', '10:44:00', 'Monday', '2025-02-03 17:14:48'),
(8, 1, 2, 8, 1, '12:36:00', '12:36:00', 'Tuesday', '2025-02-03 19:06:09'),
(9, 1, 2, 9, 1, '12:36:00', '12:36:00', 'Monday', '2025-02-03 19:06:36'),
(11, 1, 2, 13, 1, '12:43:00', '12:43:00', 'Tuesday', '2025-02-03 19:13:42'),
(13, 1, 2, 11, 1, '14:02:00', '14:02:00', 'Thursday', '2025-02-04 08:32:59'),
(14, 1, 2, 8, 1, '14:06:00', '02:06:00', 'Saturday', '2025-02-15 08:36:21'),
(15, 1, 3, 18, 1, '04:11:00', '04:11:00', 'Monday', '2025-02-16 10:41:24');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_results`
--

CREATE TABLE `tbl_results` (
  `result_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `semester` varchar(50) NOT NULL,
  `result_description` text NOT NULL,
  `percentage` decimal(5,2) NOT NULL,
  `result_file` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_results`
--

INSERT INTO `tbl_results` (`result_id`, `student_id`, `course_id`, `semester`, `result_description`, `percentage`, `result_file`, `created_at`) VALUES
(1, 7, 2, '1', '123', 12.00, '1740073616_jeans.png', '2025-02-20 17:46:56'),
(2, 7, 2, '2', '211', 13.00, '', '2025-02-20 17:47:39');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_students`
--

CREATE TABLE `tbl_students` (
  `student_id` int(11) NOT NULL,
  `student_first_name` varchar(50) NOT NULL,
  `student_last_name` varchar(50) NOT NULL,
  `student_email` varchar(100) NOT NULL,
  `student_contact` varchar(15) NOT NULL,
  `student_state` varchar(50) NOT NULL,
  `student_city` varchar(50) NOT NULL,
  `student_mother_name` varchar(50) NOT NULL,
  `student_father_name` varchar(50) NOT NULL,
  `student_mother_occupation` varchar(50) DEFAULT NULL,
  `student_father_occupation` varchar(50) DEFAULT NULL,
  `student_course` int(11) NOT NULL,
  `student_roll` varchar(11) NOT NULL,
  `student_type` enum('Part-time','Full-time') NOT NULL,
  `student_dob` date NOT NULL,
  `student_image` varchar(100) DEFAULT 'default.png',
  `student_gender` enum('Male','Female') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_students`
--

INSERT INTO `tbl_students` (`student_id`, `student_first_name`, `student_last_name`, `student_email`, `student_contact`, `student_state`, `student_city`, `student_mother_name`, `student_father_name`, `student_mother_occupation`, `student_father_occupation`, `student_course`, `student_roll`, `student_type`, `student_dob`, `student_image`, `student_gender`, `created_at`) VALUES
(7, 'John', 'Doe', 'john.doe@example.com', '1234567890', 'California', 'Los Angeles', 'jane', 'Robert Doe', 'Teacher', 'Engineer', 2, 'CS10101', 'Full-time', '2000-05-15', '', 'Male', '2024-11-12 18:55:05'),
(8, 'Alice', 'Smith', 'alice.smith@example.com', '0987654321', 'Texas', 'Houston', 'Mary Smith', 'William Smith', 'Doctor', 'Businessman', 2, 'ME10202', 'Full-time', '2001-08-25', '', 'Female', '2024-11-12 18:55:05'),
(10, 'Sanket', 'Jadhav', 'sanketjadhav@gmail.com', '7304767697', 'Maharashtra', 'Mumbai', 'Mother', 'Father', 'Housewife', 'Service', 4, '1002', 'Part-time', '2025-02-16', '', 'Male', '2025-02-16 16:14:07'),
(12, 'Sanket', 'Jadhav', 'sanketjadhav1@gmail.com', '7304767697', 'Maharashtra', 'Mumbai', 'Mother', 'Father', 'Housewife', 'Service', 4, '1003', 'Part-time', '2025-02-16', '', 'Male', '2025-02-16 16:15:21'),
(13, 'Renee', 'Phelps', 'nunalizyw@mailinator.com', 'Nulla sed verit', 'Maharashtra', 'Pune', 'Yoshi Harding', 'Melanie Blackburn', 'In exercitation dist', 'Corrupti ducimus e', 2, '1004', 'Full-time', '2013-12-02', '', 'Female', '2025-02-19 14:44:01');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_subjects`
--

CREATE TABLE `tbl_subjects` (
  `subject_id` int(11) NOT NULL,
  `subject_name` varchar(255) NOT NULL,
  `subject_code` varchar(50) NOT NULL,
  `subject_type` tinyint(1) NOT NULL COMMENT '1 = Core, 2 = Optional',
  `subject_for` varchar(50) NOT NULL COMMENT 'Specifies semester (e.g., 1 Semester, 2 Semester, etc.)',
  `subject_theory` int(11) NOT NULL COMMENT 'Marks for theory',
  `subject_practical` int(11) NOT NULL COMMENT 'Marks for practical',
  `subject_course` int(11) NOT NULL COMMENT 'Foreign key to course ID',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_subjects`
--

INSERT INTO `tbl_subjects` (`subject_id`, `subject_name`, `subject_code`, `subject_type`, `subject_for`, `subject_theory`, `subject_practical`, `subject_course`, `created_at`, `updated_at`) VALUES
(1, 'Calculus I', 'MATH101', 1, '1 Semester', 100, 50, 1, '2024-11-12 18:44:02', '2024-11-12 18:44:02'),
(2, 'Linear Algebra', 'MATH102', 1, '1 Semester', 100, 50, 1, '2024-11-12 18:44:02', '2024-11-12 18:44:02'),
(3, 'Differential Equations', 'MATH103', 1, '1 Semester', 100, 50, 1, '2024-11-12 18:44:02', '2024-11-12 18:44:02'),
(4, 'Probability & Statistics', 'MATH104', 1, '1 Semester', 100, 50, 1, '2024-11-12 18:44:02', '2024-11-12 18:44:02'),
(5, 'Real Analysis', 'MATH105', 1, '1 Semester', 100, 50, 1, '2024-11-12 18:44:02', '2024-11-12 18:44:02'),
(6, 'Mathematical Modelling', 'MATH106', 2, '1 Semester', 70, 40, 1, '2024-11-12 18:44:02', '2024-11-12 18:44:02'),
(7, 'Advanced Calculus', 'MATH107', 2, '1 Semester', 70, 40, 1, '2024-11-12 18:44:02', '2024-11-12 18:44:02'),
(8, 'Introduction to Programming', 'CS101', 1, '1 Semester', 80, 40, 2, '2024-11-12 18:44:02', '2024-11-12 18:44:02'),
(9, 'Data Structures and Algorithms', 'CS102', 1, '1 Semester', 100, 50, 2, '2024-11-12 18:44:02', '2024-11-12 18:44:02'),
(10, 'Operating Systems', 'CS103', 1, '1 Semester', 100, 50, 2, '2024-11-12 18:44:02', '2024-11-12 18:44:02'),
(11, 'Database Management Systems', 'CS104', 1, '1 Semester', 100, 50, 2, '2024-11-12 18:44:02', '2024-11-12 18:44:02'),
(12, 'Software Engineering', 'CS105', 1, '1 Semester', 100, 50, 2, '2024-11-12 18:44:02', '2024-11-12 18:44:02'),
(13, 'Computer Networks', 'CS106', 2, '1 Semester', 70, 40, 2, '2024-11-12 18:44:02', '2024-11-12 18:44:02'),
(14, 'Web Development', 'CS107', 2, '1 Semester', 70, 30, 2, '2024-11-12 18:44:02', '2024-11-12 18:44:02'),
(15, 'Statistics for Data Science', 'DS101', 1, '1 Semester', 90, 50, 3, '2024-11-12 18:44:02', '2024-11-12 18:44:02'),
(16, 'Introduction to Machine Learning', 'DS102', 1, '1 Semester', 100, 50, 3, '2024-11-12 18:44:02', '2024-11-12 18:44:02'),
(17, 'Data Structures and Algorithms for Data Science', 'DS103', 1, '1 Semester', 90, 50, 3, '2024-11-12 18:44:02', '2024-11-12 18:44:02'),
(18, 'Big Data Technologies', 'DS104', 1, '1 Semester', 100, 50, 3, '2024-11-12 18:44:02', '2024-11-12 18:44:02'),
(19, 'Artificial Intelligence for Data Science', 'DS105', 1, '1 Semester', 100, 50, 3, '2024-11-12 18:44:02', '2024-11-12 18:44:02'),
(20, 'Data Visualization', 'DS106', 2, '1 Semester', 70, 30, 3, '2024-11-12 18:44:02', '2024-11-12 18:44:02'),
(21, 'Deep Learning', 'DS107', 2, '1 Semester', 70, 40, 3, '2024-11-12 18:44:02', '2024-11-12 18:44:02'),
(22, 'Software Development Life Cycle', 'SE101', 1, '1 Semester', 80, 40, 4, '2024-11-12 18:44:02', '2024-11-12 18:44:02'),
(23, 'Agile Methodologies', 'SE102', 1, '1 Semester', 90, 50, 4, '2024-11-12 18:44:02', '2024-11-12 18:44:02'),
(24, 'Software Architecture and Design', 'SE103', 1, '1 Semester', 100, 50, 4, '2024-11-12 18:44:02', '2024-11-12 18:44:02'),
(25, 'Software Testing and Debugging', 'SE104', 1, '1 Semester', 100, 50, 4, '2024-11-12 18:44:02', '2024-11-12 18:44:02'),
(26, 'Software Project Management', 'SE105', 1, '1 Semester', 100, 50, 4, '2024-11-12 18:44:02', '2024-11-12 18:44:02'),
(27, 'Cloud Computing', 'SE106', 2, '1 Semester', 70, 40, 4, '2024-11-12 18:44:02', '2024-11-12 18:44:02'),
(28, 'DevOps Practices', 'SE107', 2, '1 Semester', 70, 30, 4, '2024-11-12 18:44:02', '2024-11-12 18:44:02'),
(29, 'Foundations of Artificial Intelligence', 'AI101', 1, '1 Semester', 100, 50, 5, '2024-11-12 18:44:02', '2024-11-12 18:44:02'),
(30, 'Natural Language Processing', 'AI102', 1, '1 Semester', 90, 50, 5, '2024-11-12 18:44:02', '2024-11-12 18:44:02'),
(31, 'Computer Vision', 'AI103', 1, '1 Semester', 100, 50, 5, '2024-11-12 18:44:02', '2024-11-12 18:44:02'),
(32, 'Machine Learning Algorithms', 'AI104', 1, '1 Semester', 100, 50, 5, '2024-11-12 18:44:02', '2024-11-12 18:44:02'),
(33, 'Robotics', 'AI105', 1, '1 Semester', 100, 50, 5, '2024-11-12 18:44:02', '2024-11-12 18:44:02'),
(34, 'Reinforcement Learning', 'AI106', 2, '1 Semester', 70, 40, 5, '2024-11-12 18:44:02', '2024-11-12 18:44:02'),
(35, 'Ethical AI', 'AI107', 2, '1 Semester', 70, 30, 5, '2024-11-12 18:44:02', '2024-11-12 18:44:02'),
(36, 'HTML & CSS Basics', 'WD101', 1, '1 Semester', 70, 40, 6, '2024-11-12 18:44:02', '2024-11-12 18:44:02'),
(37, 'JavaScript Fundamentals', 'WD102', 1, '1 Semester', 80, 40, 6, '2024-11-12 18:44:02', '2024-11-12 18:44:02'),
(38, 'Backend Development with Node.js', 'WD103', 1, '1 Semester', 100, 50, 6, '2024-11-12 18:44:02', '2024-11-12 18:44:02'),
(39, 'Database Management for Web Development', 'WD104', 1, '1 Semester', 100, 50, 6, '2024-11-12 18:44:02', '2024-11-12 18:44:02'),
(40, 'Responsive Web Design', 'WD105', 1, '1 Semester', 100, 50, 6, '2024-11-12 18:44:02', '2024-11-12 18:44:02'),
(41, 'Mobile Web Development', 'WD106', 2, '1 Semester', 70, 40, 6, '2024-11-12 18:44:02', '2024-11-12 18:44:02'),
(42, 'Web Security', 'WD107', 2, '1 Semester', 70, 30, 6, '2024-11-12 18:44:02', '2024-11-12 18:44:02'),
(43, 'Calculus II', 'MATH201', 1, '2 Semester', 100, 50, 1, '2024-11-12 18:47:35', '2024-11-12 18:47:35'),
(44, 'Abstract Algebra', 'MATH202', 1, '2 Semester', 100, 50, 1, '2024-11-12 18:47:35', '2024-11-12 18:47:35'),
(45, 'Complex Analysis', 'MATH203', 1, '2 Semester', 100, 50, 1, '2024-11-12 18:47:35', '2024-11-12 18:47:35'),
(46, 'Numerical Methods', 'MATH204', 1, '2 Semester', 100, 50, 1, '2024-11-12 18:47:35', '2024-11-12 18:47:35'),
(47, 'Mathematical Proofs', 'MATH205', 1, '2 Semester', 100, 50, 1, '2024-11-12 18:47:35', '2024-11-12 18:47:35'),
(48, 'Mathematical Modelling', 'MATH206', 2, '2 Semester', 70, 40, 1, '2024-11-12 18:47:35', '2024-11-12 18:47:35'),
(49, 'Advanced Probability', 'MATH207', 2, '2 Semester', 70, 40, 1, '2024-11-12 18:47:35', '2024-11-12 18:47:35'),
(50, 'Data Structures II', 'CS201', 1, '2 Semester', 100, 50, 2, '2024-11-12 18:47:35', '2024-11-12 18:47:35'),
(51, 'Algorithms Design', 'CS202', 1, '2 Semester', 100, 50, 2, '2024-11-12 18:47:35', '2024-11-12 18:47:35'),
(52, 'Computer Architecture', 'CS203', 1, '2 Semester', 100, 50, 2, '2024-11-12 18:47:35', '2024-11-12 18:47:35'),
(53, 'Discrete Mathematics', 'CS204', 1, '2 Semester', 100, 50, 2, '2024-11-12 18:47:35', '2024-11-12 18:47:35'),
(54, 'Digital Logic Design', 'CS205', 1, '2 Semester', 100, 50, 2, '2024-11-12 18:47:35', '2024-11-12 18:47:35'),
(55, 'Web Development', 'CS206', 2, '2 Semester', 70, 40, 2, '2024-11-12 18:47:35', '2024-11-12 18:47:35'),
(56, 'Computer Networks II', 'CS207', 2, '2 Semester', 70, 40, 2, '2024-11-12 18:47:35', '2024-11-12 18:47:35'),
(57, 'Linear Regression', 'DS201', 1, '2 Semester', 100, 50, 3, '2024-11-12 18:47:35', '2024-11-12 18:47:35'),
(58, 'Data Preprocessing', 'DS202', 1, '2 Semester', 100, 50, 3, '2024-11-12 18:47:35', '2024-11-12 18:47:35'),
(59, 'Data Mining', 'DS203', 1, '2 Semester', 100, 50, 3, '2024-11-12 18:47:35', '2024-11-12 18:47:35'),
(60, 'Big Data Processing', 'DS204', 1, '2 Semester', 100, 50, 3, '2024-11-12 18:47:35', '2024-11-12 18:47:35'),
(61, 'Data Ethics', 'DS205', 1, '2 Semester', 100, 50, 3, '2024-11-12 18:47:35', '2024-11-12 18:47:35'),
(62, 'Cloud Computing', 'DS206', 2, '2 Semester', 70, 40, 3, '2024-11-12 18:47:35', '2024-11-12 18:47:35'),
(63, 'Text Mining', 'DS207', 2, '2 Semester', 70, 40, 3, '2024-11-12 18:47:35', '2024-11-12 18:47:35'),
(64, 'Software Design Patterns', 'SE201', 1, '2 Semester', 100, 50, 4, '2024-11-12 18:47:35', '2024-11-12 18:47:35'),
(65, 'Database Systems II', 'SE202', 1, '2 Semester', 100, 50, 4, '2024-11-12 18:47:35', '2024-11-12 18:47:35'),
(66, 'Software Security', 'SE203', 1, '2 Semester', 100, 50, 4, '2024-11-12 18:47:35', '2024-11-12 18:47:35'),
(67, 'Requirements Engineering', 'SE204', 1, '2 Semester', 100, 50, 4, '2024-11-12 18:47:35', '2024-11-12 18:47:35'),
(68, 'Quality Assurance', 'SE205', 1, '2 Semester', 100, 50, 4, '2024-11-12 18:47:35', '2024-11-12 18:47:35'),
(69, 'Web Technologies', 'SE206', 2, '2 Semester', 70, 40, 4, '2024-11-12 18:47:35', '2024-11-12 18:47:35'),
(70, 'Data Visualization', 'SE207', 2, '2 Semester', 70, 40, 4, '2024-11-12 18:47:35', '2024-11-12 18:47:35'),
(71, 'AI Algorithms', 'AI201', 1, '2 Semester', 100, 50, 5, '2024-11-12 18:47:35', '2024-11-12 18:47:35'),
(72, 'Machine Learning Applications', 'AI202', 1, '2 Semester', 100, 50, 5, '2024-11-12 18:47:35', '2024-11-12 18:47:35'),
(73, 'Natural Language Processing II', 'AI203', 1, '2 Semester', 100, 50, 5, '2024-11-12 18:47:35', '2024-11-12 18:47:35'),
(74, 'Robotic Process Automation', 'AI204', 1, '2 Semester', 100, 50, 5, '2024-11-12 18:47:35', '2024-11-12 18:47:35'),
(75, 'AI in Healthcare', 'AI205', 1, '2 Semester', 100, 50, 5, '2024-11-12 18:47:35', '2024-11-12 18:47:35'),
(76, 'AI for IoT', 'AI206', 2, '2 Semester', 70, 40, 5, '2024-11-12 18:47:35', '2024-11-12 18:47:35'),
(78, 'Advanced JavaScript', 'WD201', 1, '2 Semester', 100, 50, 6, '2024-11-12 18:47:35', '2024-11-12 18:47:35'),
(79, 'Node.js and Express', 'WD202', 1, '2 Semester', 100, 50, 6, '2024-11-12 18:47:35', '2024-11-12 18:47:35'),
(80, 'Web Application Architecture', 'WD203', 1, '2 Semester', 100, 50, 6, '2024-11-12 18:47:35', '2024-11-12 18:47:35'),
(81, 'Database Systems', 'WD204', 1, '2 Semester', 100, 50, 6, '2024-11-12 18:47:35', '2024-11-12 18:47:35'),
(82, 'Web Development Frameworks', 'WD205', 1, '2 Semester', 100, 50, 6, '2024-11-12 18:47:35', '2024-11-12 18:47:35'),
(83, 'Web Accessibility', 'WD206', 2, '2 Semester', 70, 40, 6, '2024-11-12 18:47:35', '2024-11-12 18:47:35'),
(84, 'Progressive Web Apps', 'WD207', 2, '2 Semester', 70, 40, 6, '2024-11-12 18:47:35', '2024-11-12 18:47:35');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_uploaded_assignments`
--

CREATE TABLE `tbl_uploaded_assignments` (
  `uploaded_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `assignment_id` int(11) NOT NULL,
  `uploaded_file` varchar(255) NOT NULL,
  `uploaded_description` text NOT NULL,
  `uploaded_status` tinyint(4) DEFAULT 1,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_uploaded_assignments`
--

INSERT INTO `tbl_uploaded_assignments` (`uploaded_id`, `student_id`, `course_id`, `assignment_id`, `uploaded_file`, `uploaded_description`, `uploaded_status`, `uploaded_at`) VALUES
(1, 7, 2, 3, 'blog1.png', 'Test', 2, '2025-02-21 18:21:34'),
(2, 7, 2, 4, 'privacy_policy.png', 'sasa', 2, '2025-02-24 18:57:30'),
(3, 7, 2, 4, 'chatpulse_icon.png', 'sasasa', 2, '2025-02-24 19:29:40');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_assignments`
--
ALTER TABLE `tbl_assignments`
  ADD PRIMARY KEY (`assignment_id`);

--
-- Indexes for table `tbl_attendance`
--
ALTER TABLE `tbl_attendance`
  ADD PRIMARY KEY (`attendance_id`),
  ADD KEY `attendance_student_id` (`attendance_student_id`);

--
-- Indexes for table `tbl_course`
--
ALTER TABLE `tbl_course`
  ADD PRIMARY KEY (`course_id`),
  ADD KEY `course_department_id` (`course_department_id`);

--
-- Indexes for table `tbl_department`
--
ALTER TABLE `tbl_department`
  ADD PRIMARY KEY (`department_id`),
  ADD UNIQUE KEY `department_email` (`department_email`);

--
-- Indexes for table `tbl_exam`
--
ALTER TABLE `tbl_exam`
  ADD PRIMARY KEY (`exam_id`),
  ADD KEY `exam_department_id` (`exam_department_id`),
  ADD KEY `exam_course_id` (`exam_course_id`);

--
-- Indexes for table `tbl_exam_schedule`
--
ALTER TABLE `tbl_exam_schedule`
  ADD PRIMARY KEY (`schedule_id`);

--
-- Indexes for table `tbl_faculty`
--
ALTER TABLE `tbl_faculty`
  ADD PRIMARY KEY (`faculty_id`),
  ADD KEY `faculty_department_id` (`faculty_department_id`);

--
-- Indexes for table `tbl_faculty_attendance`
--
ALTER TABLE `tbl_faculty_attendance`
  ADD PRIMARY KEY (`attendance_id`);

--
-- Indexes for table `tbl_notices`
--
ALTER TABLE `tbl_notices`
  ADD PRIMARY KEY (`notice_id`);

--
-- Indexes for table `tbl_principal`
--
ALTER TABLE `tbl_principal`
  ADD PRIMARY KEY (`principal_id`),
  ADD UNIQUE KEY `principal_email` (`principal_email`);

--
-- Indexes for table `tbl_regular_time_table`
--
ALTER TABLE `tbl_regular_time_table`
  ADD PRIMARY KEY (`regular_time_table_id`),
  ADD KEY `department_id` (`department_id`),
  ADD KEY `course_id` (`course_id`),
  ADD KEY `subject_id` (`subject_id`),
  ADD KEY `faculty_id` (`faculty_id`);

--
-- Indexes for table `tbl_results`
--
ALTER TABLE `tbl_results`
  ADD PRIMARY KEY (`result_id`);

--
-- Indexes for table `tbl_students`
--
ALTER TABLE `tbl_students`
  ADD PRIMARY KEY (`student_id`),
  ADD UNIQUE KEY `student_email` (`student_email`),
  ADD UNIQUE KEY `student_roll` (`student_roll`);

--
-- Indexes for table `tbl_subjects`
--
ALTER TABLE `tbl_subjects`
  ADD PRIMARY KEY (`subject_id`);

--
-- Indexes for table `tbl_uploaded_assignments`
--
ALTER TABLE `tbl_uploaded_assignments`
  ADD PRIMARY KEY (`uploaded_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_assignments`
--
ALTER TABLE `tbl_assignments`
  MODIFY `assignment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tbl_attendance`
--
ALTER TABLE `tbl_attendance`
  MODIFY `attendance_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_course`
--
ALTER TABLE `tbl_course`
  MODIFY `course_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `tbl_department`
--
ALTER TABLE `tbl_department`
  MODIFY `department_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tbl_exam`
--
ALTER TABLE `tbl_exam`
  MODIFY `exam_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tbl_exam_schedule`
--
ALTER TABLE `tbl_exam_schedule`
  MODIFY `schedule_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tbl_faculty`
--
ALTER TABLE `tbl_faculty`
  MODIFY `faculty_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tbl_faculty_attendance`
--
ALTER TABLE `tbl_faculty_attendance`
  MODIFY `attendance_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_notices`
--
ALTER TABLE `tbl_notices`
  MODIFY `notice_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `tbl_principal`
--
ALTER TABLE `tbl_principal`
  MODIFY `principal_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_regular_time_table`
--
ALTER TABLE `tbl_regular_time_table`
  MODIFY `regular_time_table_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `tbl_results`
--
ALTER TABLE `tbl_results`
  MODIFY `result_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tbl_students`
--
ALTER TABLE `tbl_students`
  MODIFY `student_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `tbl_subjects`
--
ALTER TABLE `tbl_subjects`
  MODIFY `subject_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=85;

--
-- AUTO_INCREMENT for table `tbl_uploaded_assignments`
--
ALTER TABLE `tbl_uploaded_assignments`
  MODIFY `uploaded_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tbl_attendance`
--
ALTER TABLE `tbl_attendance`
  ADD CONSTRAINT `tbl_attendance_ibfk_1` FOREIGN KEY (`attendance_student_id`) REFERENCES `tbl_students` (`student_id`);

--
-- Constraints for table `tbl_course`
--
ALTER TABLE `tbl_course`
  ADD CONSTRAINT `tbl_course_ibfk_1` FOREIGN KEY (`course_department_id`) REFERENCES `tbl_department` (`department_id`) ON DELETE CASCADE;

--
-- Constraints for table `tbl_exam`
--
ALTER TABLE `tbl_exam`
  ADD CONSTRAINT `tbl_exam_ibfk_1` FOREIGN KEY (`exam_department_id`) REFERENCES `tbl_department` (`department_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tbl_exam_ibfk_2` FOREIGN KEY (`exam_course_id`) REFERENCES `tbl_course` (`course_id`) ON DELETE CASCADE;

--
-- Constraints for table `tbl_faculty`
--
ALTER TABLE `tbl_faculty`
  ADD CONSTRAINT `tbl_faculty_ibfk_1` FOREIGN KEY (`faculty_department_id`) REFERENCES `tbl_department` (`department_id`) ON DELETE CASCADE;

--
-- Constraints for table `tbl_regular_time_table`
--
ALTER TABLE `tbl_regular_time_table`
  ADD CONSTRAINT `tbl_regular_time_table_ibfk_1` FOREIGN KEY (`department_id`) REFERENCES `tbl_department` (`department_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tbl_regular_time_table_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `tbl_course` (`course_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tbl_regular_time_table_ibfk_3` FOREIGN KEY (`subject_id`) REFERENCES `tbl_subjects` (`subject_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tbl_regular_time_table_ibfk_4` FOREIGN KEY (`faculty_id`) REFERENCES `tbl_faculty` (`faculty_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
