-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 08, 2023 at 03:34 AM
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
-- Database: `student_management_system_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `email` varchar(80) NOT NULL,
  `password` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL,
  `type` varchar(13) NOT NULL DEFAULT 'admin'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `name`, `email`, `password`, `created_at`, `updated_at`, `type`) VALUES
(3, 'Dummy Admin', 'dummy@email.com', '$2y$10$/l2TjNWKTE2s.Q5eTM3mN.E4P9Ud9lHXb9X9.njyUCuWk.CUbH7B6', '2023-03-02 10:10:01', NULL, 'main_admin'),
(4, 'First Admin', 'email@email.com', '$2y$10$7SuRK45hCZ/i9QkMiUUM/O8kj8u/6lruCZcg.tgUxEwODYk2xyIjW', '2023-03-02 10:54:54', NULL, 'admin'),
(5, 'Second Admin', 'email1@email.com', '$2y$10$.3l4nz6a15TwP2q4oekhLe.OmrOiRK03inf5to.Pu394rtZ3UlHZO', '2023-03-02 10:55:49', NULL, 'super_admin'),
(6, 'Second Admin', 'emaixl1@email.com', '$2y$10$UrtkPjfCxI0lCJglG0y15uFVInPIq606CNUj0kmdd30ITPo9zMrnq', '2023-03-02 10:57:51', NULL, 'admin'),
(7, 'Second Admin', 'emaixl1d@email.com', '$2y$10$AtqA1SEqvLaZ/b5eiNvboeKOp1BzDt6RL/jSEsHM3S93dTBaWcygK', '2023-03-02 10:58:09', NULL, 'admin'),
(8, 'Second Admin', 'emaixl1dd@email.com', '$2y$10$sABc55g4jxsFkYpmBDh0H.p02aSV3m7eFxi/8l3bHJ0z8EQA6.2hK', '2023-03-02 10:59:20', NULL, 'super_admin'),
(9, 'Second Admin', 'email@email.com', '$2y$10$817DlJmefEVW3rDvZEW.He2U70NLtMoEifogkA2k6Mu0OVBB9NmUm', '2023-03-02 11:19:46', NULL, 'admin'),
(10, 'Second Admin', 'emailju@email.com', '$2y$10$YocO7U4d.QsPqjUiuLPRU.xFGJGEvnVEd5QwfA0cGMXLmEufbfCWm', '2023-03-02 11:20:42', NULL, 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `dstudents`
--

CREATE TABLE `dstudents` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `email` varchar(80) NOT NULL,
  `password` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `email` varchar(80) NOT NULL,
  `password` text NOT NULL,
  `image_url` text DEFAULT NULL,
  `class` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `name`, `email`, `password`, `image_url`, `class`, `created_at`, `updated_at`) VALUES
(1, 'John Evans', 'email@email.com', '$2y$10$nOzr3Drsb/PmrUfQglWcr.YaBUmTC4V1xc7kcEWTmckdvEOKRRBcq', 'http://res.cloudinary.com/dyjhe7cg2/image/upload/v1678106404/ch1v5wm6g3dn5yxouh59.png', NULL, '2023-03-01 08:54:28', NULL),
(2, 'John Evans', 'email8@email.com', '$2y$10$IQCUaH6Bj5fbwlVMDp1XpOOg7ZWdK5zdhwCoQCfeMj4NDD8BFeSBi', NULL, NULL, '2023-03-01 09:03:14', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dstudents`
--
ALTER TABLE `dstudents`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `dstudents`
--
ALTER TABLE `dstudents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
