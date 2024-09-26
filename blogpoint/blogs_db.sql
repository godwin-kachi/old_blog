-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 07, 2024 at 01:51 PM
-- Server version: 10.4.17-MariaDB
-- PHP Version: 7.4.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `blogs_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `blogs`
--

CREATE TABLE `blogs` (
  `blog_id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `content` text NOT NULL,
  `image` varchar(255) NOT NULL DEFAULT './images/blog,png',
  `user_id` int(11) NOT NULL,
  `tags` varchar(255) DEFAULT NULL,
  `categories` text DEFAULT NULL,
  `click_count` int(11) NOT NULL DEFAULT 0,
  `active` tinyint(4) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `blogs`
--

INSERT INTO `blogs` (`blog_id`, `title`, `content`, `image`, `user_id`, `tags`, `categories`, `click_count`, `active`, `created_at`, `updated_at`) VALUES
(2, 'test', 'test tst', '', 1, NULL, NULL, 0, 0, '2024-09-04 08:05:45', '2024-09-04 08:05:45'),
(3, 'test', 'test test', '', 1, NULL, NULL, 0, 0, '2024-09-04 08:06:24', '2024-09-04 08:06:24'),
(5, 'abeg', 'abeg abge', '/images/pix.png', 2, NULL, NULL, 0, 0, '2024-09-04 20:21:44', '2024-09-04 20:21:44'),
(6, 'Blog 1', 'This is Blog 1.', './images/create.pgn', 1, NULL, '', 0, 0, '2024-09-04 23:56:35', '2024-09-04 23:56:35'),
(7, 'Blog 2', 'This is Blog 2.', './images/create2.pgn', 1, NULL, NULL, 0, 0, '2024-09-05 00:01:23', '2024-09-05 00:01:23'),
(8, 'Blog 3', 'This is Blog 3.', './images/create3.pgn', 1, NULL, NULL, 0, 0, '2024-09-05 00:03:10', '2024-09-05 00:03:10'),
(9, 'Blog 4', 'This is Blog 4.', './images/create4.pgn', 1, 'street,campaign', 'Agric,Science', 0, 0, '2024-09-05 00:04:49', '2024-09-05 00:04:49'),
(10, 'Blog 10', 'This is Blog 10.', './images/create10.pgn', 2, 'street,campaign,river', NULL, 0, 0, '2024-09-05 00:05:36', '2024-09-05 09:44:07');

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `comment_id` int(11) NOT NULL,
  `blog_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `comment` varchar(255) NOT NULL,
  `likes` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `pilots`
--

CREATE TABLE `pilots` (
  `pilot_id` int(11) NOT NULL,
  `firstname` varchar(50) NOT NULL,
  `lastname` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `active` tinyint(4) NOT NULL DEFAULT 0,
  `role_id` tinyint(4) NOT NULL DEFAULT 0,
  `user_code` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `firstname` varchar(50) NOT NULL,
  `lastname` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 0,
  `role_id` tinyint(1) NOT NULL DEFAULT 0,
  `user_code` varchar(255) NOT NULL,
  `updated_at` datetime NOT NULL DEFAULT current_timestamp(),
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `blogs`
--
ALTER TABLE `blogs`
  ADD PRIMARY KEY (`blog_id`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`comment_id`);

--
-- Indexes for table `pilots`
--
ALTER TABLE `pilots`
  ADD PRIMARY KEY (`pilot_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `blogs`
--
ALTER TABLE `blogs`
  MODIFY `blog_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pilots`
--
ALTER TABLE `pilots`
  MODIFY `pilot_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
