-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2
-- http://www.phpmyadmin.net
--
-- Φιλοξενητής: localhost
-- Χρόνος δημιουργίας: 29 Αυγ 2019 στις 08:13:50
-- Έκδοση διακομιστή: 5.7.21-0ubuntu0.16.04.1
-- Έκδοση PHP: 7.0.29-1+ubuntu16.04.1+deb.sury.org+1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Βάση δεδομένων: `nodemculog`
--
CREATE DATABASE IF NOT EXISTS `nodemculog` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `nodemculog`;

-- --------------------------------------------------------

--
-- Στημένη δομή για προβολή `daily-report`
--
CREATE TABLE IF NOT EXISTS `daily-report` (
`rfid` varchar(20)
,`DATE(date)` date
,`diff` time
,`diff_in_hours` bigint(21)
,`diff_in_minutes` bigint(21)
,`diff_in_seconds` bigint(21)
);

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `employee`
--

CREATE TABLE IF NOT EXISTS `employee` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `onoma` varchar(40) NOT NULL,
  `eponymo` varchar(40) NOT NULL,
  `rfid` varchar(20) NOT NULL,
  `tmima` varchar(80) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `logs`
--

CREATE TABLE IF NOT EXISTS `logs` (
  `id` int(6) UNSIGNED NOT NULL AUTO_INCREMENT,
  `CardNumber` double DEFAULT NULL,
  `Name` varchar(30) CHARACTER SET latin1 DEFAULT NULL,
  `SerialNumber` double NOT NULL,
  `DateLog` date DEFAULT NULL,
  `TimeIn` time DEFAULT NULL,
  `TimeOut` time DEFAULT NULL,
  `UserStat` varchar(100) CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=30 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `rfid_user`
--

CREATE TABLE IF NOT EXISTS `rfid_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rfid` varchar(20) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) CHARACTER SET latin1 NOT NULL,
  `SerialNumber` double NOT NULL,
  `gender` varchar(100) CHARACTER SET latin1 NOT NULL,
  `CardID` double NOT NULL,
  `CardID_select` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=62 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Δομή για προβολή `daily-report`
--
DROP TABLE IF EXISTS `daily-report`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `daily-report`  AS  select `rfid_user`.`rfid` AS `rfid`,cast(`rfid_user`.`date` as date) AS `DATE(date)`,timediff(max(`rfid_user`.`date`),min(`rfid_user`.`date`)) AS `diff`,timestampdiff(HOUR,min(`rfid_user`.`date`),max(`rfid_user`.`date`)) AS `diff_in_hours`,timestampdiff(MINUTE,min(`rfid_user`.`date`),max(`rfid_user`.`date`)) AS `diff_in_minutes`,timestampdiff(SECOND,min(`rfid_user`.`date`),max(`rfid_user`.`date`)) AS `diff_in_seconds` from `rfid_user` group by `rfid_user`.`rfid`,cast(`rfid_user`.`date` as date) ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
