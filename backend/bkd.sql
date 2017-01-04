-- phpMyAdmin SQL Dump
-- version 3.4.10.1deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jan 04, 2017 at 02:19 PM
-- Server version: 5.6.30
-- PHP Version: 5.5.37-1+deprecated+dontuse+deb.sury.org~precise+1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `bkd`
--

-- --------------------------------------------------------

--
-- Table structure for table `bkd_feedback`
--

CREATE TABLE IF NOT EXISTS `bkd_feedback` (
  `bf_id` int(11) NOT NULL AUTO_INCREMENT,
  `bf_user_id` int(11) NOT NULL,
  `bf_shopping_medium` varchar(20) NOT NULL,
  `bf_shopping_schedule` varchar(20) NOT NULL,
  `bf_feedback` varchar(20) NOT NULL,
  `bf_suggestion` text NOT NULL,
  `bf_added_on` int(11) NOT NULL,
  `bf_updated_on` int(11) NOT NULL,
  PRIMARY KEY (`bf_id`),
  KEY `bf_user_id` (`bf_user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `bkd_feedback`
--

INSERT INTO `bkd_feedback` (`bf_id`, `bf_user_id`, `bf_shopping_medium`, `bf_shopping_schedule`, `bf_feedback`, `bf_suggestion`, `bf_added_on`, `bf_updated_on`) VALUES
(1, 1, 'Daily', 'Daily', 'Good', 'Hello', 1483512086, 1483512086);

-- --------------------------------------------------------

--
-- Table structure for table `bkd_user`
--

CREATE TABLE IF NOT EXISTS `bkd_user` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_first_name` varchar(30) NOT NULL,
  `user_last_name` varchar(30) NOT NULL,
  `user_mobile` int(11) NOT NULL,
  `user_telephone` int(11) NOT NULL,
  `user_gender` char(1) NOT NULL,
  `user_email` varchar(35) NOT NULL,
  `user_dob` int(11) NOT NULL,
  `user_dom` int(11) NOT NULL,
  `user_added_on` int(11) NOT NULL,
  `user_updated_on` int(11) NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `bkd_user`
--

INSERT INTO `bkd_user` (`user_id`, `user_first_name`, `user_last_name`, `user_mobile`, `user_telephone`, `user_gender`, `user_email`, `user_dob`, `user_dom`, `user_added_on`, `user_updated_on`) VALUES
(1, 'Harsh', 'Singhal', 2147483647, 2147483647, 'M', 'singhal.harsh1994@gmail.com', 12121212, 12121212, 1483509196, 1483509196),
(2, 'Harsh', 'Singhal', 2147483647, 2147483647, 'M', 'singhal.harsh1994@gmail.com', 12121212, 12121212, 1483510264, 1483510264),
(3, 'Harsh', 'Singhal', 2147483647, 2147483647, 'M', 'singhal.harsh1994@gmail.com', 12121212, 12121212, 1483510304, 1483510304),
(4, 'Harsh', 'Singhal', 2147483647, 2147483647, 'M', 'singhal.harsh1994@gmail.com', 12121212, 12121212, 1483511328, 1483511328),
(5, 'Harsh', 'Singhal', 2147483647, 2147483647, 'M', 'singhal.harsh1994@gmail.com', 12121212, 12121212, 1483511403, 1483511403);

-- --------------------------------------------------------

--
-- Table structure for table `bkd_user_address`
--

CREATE TABLE IF NOT EXISTS `bkd_user_address` (
  `ua_id` int(11) NOT NULL AUTO_INCREMENT,
  `ua_user_id` int(11) NOT NULL,
  `ua_address` varchar(7) NOT NULL,
  `ua_city` varchar(20) NOT NULL,
  `ua_district` varchar(20) NOT NULL,
  `ua_state` varchar(20) NOT NULL,
  `ua_pincode` int(10) NOT NULL,
  `ua_country` int(11) NOT NULL,
  `ua_added_on` int(11) NOT NULL,
  `ua_updated_on` int(11) NOT NULL,
  `ua_landmark` varchar(40) NOT NULL,
  PRIMARY KEY (`ua_id`),
  KEY `ua_user_id` (`ua_user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `bkd_user_address`
--

INSERT INTO `bkd_user_address` (`ua_id`, `ua_user_id`, `ua_address`, `ua_city`, `ua_district`, `ua_state`, `ua_pincode`, `ua_country`, `ua_added_on`, `ua_updated_on`, `ua_landmark`) VALUES
(1, 3, 'Ghar', 'Mathura', 'Mathura', 'UP', 281403, 0, 1483510304, 1483510304, 'Ghar'),
(2, 4, 'Ghar', 'Mathura', 'Mathura', 'UP', 281403, 0, 1483511328, 1483511328, 'Ghar'),
(3, 5, 'Ghar', 'Mathura', 'Mathura', 'UP', 281403, 0, 1483511403, 1483511403, 'Ghar');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bkd_feedback`
--
ALTER TABLE `bkd_feedback`
  ADD CONSTRAINT `bkd_feedback_ibfk_1` FOREIGN KEY (`bf_user_id`) REFERENCES `bkd_user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `bkd_user_address`
--
ALTER TABLE `bkd_user_address`
  ADD CONSTRAINT `bkd_user_address_ibfk_1` FOREIGN KEY (`ua_user_id`) REFERENCES `bkd_user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
