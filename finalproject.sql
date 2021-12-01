-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Nov 30, 2021 at 11:46 PM
-- Server version: 5.7.31
-- PHP Version: 7.3.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `finalproject`
--

-- --------------------------------------------------------

--
-- Table structure for table `friends`
--

DROP TABLE IF EXISTS `friends`;
CREATE TABLE IF NOT EXISTS `friends` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userA` int(11) NOT NULL,
  `userB` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `friends_B` (`userB`),
  KEY `friends_A` (`userA`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `images`
--

DROP TABLE IF EXISTS `images`;
CREATE TABLE IF NOT EXISTS `images` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` varchar(1000) NOT NULL,
  `url` varchar(1000) NOT NULL,
  `likes` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `images` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tags`
--

DROP TABLE IF EXISTS `tags`;
CREATE TABLE IF NOT EXISTS `tags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `image_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `tags` (`image_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `age` int(11) DEFAULT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
COMMIT;




-- --------------------------------------------------------

--
-- Constraints for dumped tables
--

-- --------------------------------------------------------

--
-- Constraints for table `images`
--

START TRANSACTION;
ALTER TABLE `images`
  ADD CONSTRAINT `images` FOREIGN KEY (`user_id`) REFERENCES `users`
(`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

-- --------------------------------------------------------

--
-- Constraints for table `friends`
--

START TRANSACTION;
ALTER TABLE `friends`
  ADD CONSTRAINT `friends_A` FOREIGN KEY (`userA`) REFERENCES `users`
(`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

START TRANSACTION;
ALTER TABLE `friends`
  ADD CONSTRAINT `friends_B` FOREIGN KEY (`userB`) REFERENCES `users`
(`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

-- --------------------------------------------------------

--
-- Constraints for table `tags`
--

START TRANSACTION;
ALTER TABLE `tags`
  ADD CONSTRAINT `tags` FOREIGN KEY (`image_id`) REFERENCES `images`
(`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
