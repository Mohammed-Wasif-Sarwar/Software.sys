-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 25, 2025 at 02:22 PM
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
-- Database: `software_solutions`
--

-- --------------------------------------------------------

--
-- Table structure for table `countries`
--

CREATE TABLE `countries` (
  `Country` varchar(50) NOT NULL,
  `Flag` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `countries`
--

INSERT INTO `countries` (`Country`, `Flag`) VALUES
('Afghanistan', '🇦🇫'),
('Algeria', '🇩🇿'),
('Angola', '🇦🇴'),
('Argentina', '🇦🇷'),
('Bangladesh', '🇧🇩'),
('Brazil', '🇧🇷'),
('Cambodia', '🇰🇭'),
('Canada', '🇨🇦'),
('China', '🇨🇳'),
('Colombia', '🇨🇴'),
('Democratic Republic of the Congo', '🇨🇩'),
('Egypt', '🇪🇬'),
('Ethiopia', '🇪🇹'),
('France', '🇫🇷'),
('Germany', '🇩🇪'),
('India', '🇮🇳'),
('Indonesia', '🇮🇩'),
('Iran', '🇮🇷'),
('Iraq', '🇮🇶'),
('Italy', '🇮🇹'),
('Japan', '🇯🇵'),
('Kenya', '🇰🇪'),
('Madagascar', '🇲🇬'),
('Malaysia', '🇲🇾'),
('Mexico', '🇲🇽'),
('Morocco', '🇲🇦'),
('Mozambique', '🇲🇿'),
('Myanmar', '🇲🇲'),
('Nepal', '🇳🇵'),
('Netherlands', '🇳🇱'),
('Niger', '🇳🇪'),
('Nigeria', '🇳🇬'),
('Pakistan', '🇵🇰'),
('Peru', '🇵🇪'),
('Philippines', '🇵🇭'),
('Poland', '🇵🇱'),
('Russia', '🇷🇺'),
('Saudi Arabia', '🇸🇦'),
('South Africa', '🇿🇦'),
('South Korea', '🇰🇷'),
('Spain', '🇪🇸'),
('Sudan', '🇸🇩'),
('Thailand', '🇹🇭'),
('Turkey', '🇹🇷'),
('Uganda', '🇺🇬'),
('Ukraine', '🇺🇦'),
('United Kingdom', '🇬🇧'),
('United States', '🇺🇸'),
('Venezuela', '🇻🇪'),
('Vietnam', '🇻🇳');

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

CREATE TABLE `projects` (
  `PID` int(11) NOT NULL,
  `UserName` varchar(20) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `Title` varchar(50) NOT NULL,
  `StartDate` date NOT NULL,
  `EndDate` date NOT NULL,
  `Short_Description` varchar(1000) NOT NULL,
  `Phase_Dev` enum('Design','Development','Testing','Deployment','Complete') DEFAULT NULL,
  `Rating` tinyint(4) DEFAULT NULL CHECK (`Rating` between 1 and 5),
  `Feedback` text DEFAULT NULL,
  `Cost` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `projects`
--

INSERT INTO `projects` (`PID`, `UserName`, `Email`, `Title`, `StartDate`, `EndDate`, `Short_Description`, `Phase_Dev`, `Rating`, `Feedback`, `Cost`) VALUES
(1, 'johndoe', 'john@example.com', 'Health Tracker App', '2024-01-15', '2024-04-20', 'An app to monitor and track health metrics like steps, heart rate, and sleep.', 'Complete', 5, 'Very smooth experience. User-friendly and stable.', 8000),
(2, 'alikhan', 'ali@example.com', 'Online Tutoring Platform', '2024-03-01', '2024-05-30', 'A platform to connect tutors and students in real-time for virtual learning.', 'Deployment', 4, 'Good progress. Just a few bugs left.', 6000),
(3, 'mariag', 'maria@example.com', 'E-commerce Website', '2024-02-10', '2024-06-01', 'A full-stack online store with payment gateway and cart features.', 'Testing', NULL, NULL, 9500),
(4, 'chenwei', 'chen@example.com', 'AI Personal Assistant', '2024-04-05', '2024-09-20', 'Voice-controlled assistant with Natural Language Processing capabilities.', 'Design', NULL, '', 12000),
(5, 'aminay', 'amina@example.com', 'School Management System', '2023-09-01', '2024-02-10', 'Manages attendance, fees, schedules, and student reports.', 'Complete', 2, 'Not very user-friendly. Needs improvements.', 7000),
(6, 'dsmith', 'david@example.com', 'Crypto Portfolio Tracker', '2024-01-20', '2024-04-15', 'Tracks investments, trends, and market movements.', 'Deployment', 5, 'Clean UI and excellent features.', 10000),
(7, 'frahman', 'fatima@example.com', 'Online Bookstore', '2024-03-15', '2024-07-30', 'An online marketplace to buy, review, and recommend books.', 'Development', NULL, NULL, 7500),
(8, 'liam123', 'liam@example.com', 'Fitness Community Forum', '2023-11-01', '2024-03-05', 'Social platform for fitness enthusiasts to share progress and tips.', 'Complete', 3, 'Decent project, but can be optimized.', 6700),
(9, 'sofia_m', 'sofia@example.com', 'Travel Itinerary Planner', '2024-02-20', '2024-05-20', 'Helps users build, save, and share trip plans with others.', 'Testing', NULL, NULL, 7200),
(10, 'tariqh', 'tariq@example.com', 'Job Board for Freelancers', '2023-08-10', '2024-01-15', 'Job listing site focused on freelance gigs with messaging and profiles.', 'Complete', 5, 'Excellent work! Highly recommended.', 9200);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `First_Name` varchar(50) NOT NULL,
  `Second_Name` varchar(50) NOT NULL,
  `UserName` varchar(20) NOT NULL,
  `Country` varchar(50) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `Password` char(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`First_Name`, `Second_Name`, `UserName`, `Country`, `Email`, `Password`) VALUES
('Ali', 'Khan', 'alikhan', 'Pakistan', 'ali@example.com', '$2y$10$IZIY2l/.BMoeYUrz9lrExOFFuzTimynXNySchQPA4.Z2F3c3i8BOS'),
('Amina', 'Yusuf', 'aminay', 'Nigeria', 'amina@example.com', '$2y$10$AQUEMUkTKDIvkTZ8IHXZieg6UGZZ7b9mBheZwbJtT2VwmgBYLyh8y'),
('Chen', 'Wei', 'chenwei', 'China', 'chen@example.com', '$2y$10$/7FqgMsZgw71V93AnD052eUSnpMcjFrE2tSoqqPeThs/7syunlPbm'),
('David', 'Smith', 'dsmith', 'United Kingdom', 'david@example.com', '$2y$10$Nzn288gWmbyZ5wHAvjEQ2OVHeoHnQ1ao7ElM7c3IbKggKNQclYoiu'),
('Fatima', 'Rahman', 'frahman', 'Bangladesh', 'fatima@example.com', '$2y$10$3mtGzI/1bvR4i9rkkj4vGuJXpSgS2DzAoPKj5Ra6T4dsy43Kq9aAa'),
('John', 'Doe', 'johndoe', 'United States', 'john@example.com', '$2y$10$Pd41Vo2B7gsf7bStij2XP.d6Ydc3vEQPY1bvER/NY9se/ub6aSsXK'),
('Liam', 'Miller', 'liam123', 'Canada', 'liam@example.com', '$2y$10$zK8l0AGWg2uiWRTP6QphOe.ATqPudRmDDKSxtlcmWBgj.hvpEPBhS'),
('Maria', 'Gonzalez', 'mariag', 'Mexico', 'maria@example.com', '$2y$10$ooxk0baTzB7Ot9vwgIiT4ex9vfLGRlr77R4xoIpYlMkVAJaDTWSxq'),
('Fatimah', 'Afrin', 'mws254', 'Bangladesh', 'kamalsoma2005@gmail.com', '$2y$10$RB5Z9QcDHjvpHlOmVGdyxeUJa1yfxJWCjD/hLvY368f2rtECFSDd.'),
('Sofia', 'Martinez', 'sofia_m', 'Argentina', 'sofia@example.com', '$2y$10$ITHOKm.x53NE4JTzi855ceal3TMizPMefMxIxjTS69GlraePYlO1e'),
('Tariq', 'Hassan', 'tariqh', 'Egypt', 'tariq@example.com', '$2y$10$Vn.2L7/VWYYSocREUvwKhO.O0hnie3QLat0WWk1JEzhVxtQD.z4Wi');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `countries`
--
ALTER TABLE `countries`
  ADD PRIMARY KEY (`Country`);

--
-- Indexes for table `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`PID`),
  ADD KEY `UserName` (`UserName`,`Email`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`UserName`,`Email`),
  ADD KEY `Country` (`Country`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `projects`
--
ALTER TABLE `projects`
  MODIFY `PID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `projects`
--
ALTER TABLE `projects`
  ADD CONSTRAINT `projects_ibfk_1` FOREIGN KEY (`UserName`,`Email`) REFERENCES `users` (`UserName`, `Email`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`Country`) REFERENCES `countries` (`Country`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
