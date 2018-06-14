-- phpMyAdmin SQL Dump
-- version 4.7.9
-- https://www.phpmyadmin.net/
--
-- Host: philgookang.cjroju7c6c9o.ap-northeast-2.rds.amazonaws.com:3306
-- Generation Time: Jun 14, 2018 at 02:09 PM
-- Server version: 5.7.21-log
-- PHP Version: 7.0.30-0ubuntu0.16.04.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `stock`
--

-- --------------------------------------------------------

--
-- Table structure for table `company`
--

CREATE TABLE `company` (
  `idx` int(11) NOT NULL,
  `name` varchar(60) NOT NULL,
  `code` varchar(6) NOT NULL,
  `market` tinyint(1) NOT NULL,
  `need_history` tinyint(1) NOT NULL,
  `last_updated` date NOT NULL,
  `created_date_time` datetime NOT NULL,
  `status` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `company_stock`
--

CREATE TABLE `company_stock` (
  `idx` int(11) NOT NULL,
  `company_idx` int(11) NOT NULL,
  `price` int(11) NOT NULL,
  `prev_diff` int(11) NOT NULL,
  `percentage` float NOT NULL,
  `open` int(11) NOT NULL,
  `high` int(11) NOT NULL,
  `low` int(11) NOT NULL,
  `volume` double NOT NULL,
  `date` date NOT NULL,
  `created_date_time` datetime NOT NULL,
  `status` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `model_result`
--

CREATE TABLE `model_result` (
  `idx` int(11) NOT NULL,
  `playlist_idx` int(11) NOT NULL,
  `train_company_idx` int(11) NOT NULL,
  `test_company_idx` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `f1` double NOT NULL,
  `recall` double NOT NULL,
  `accuracy` double NOT NULL,
  `precise` double NOT NULL,
  `score` double NOT NULL,
  `created_date_time` datetime NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `playlist`
--

CREATE TABLE `playlist` (
  `idx` int(11) NOT NULL,
  `type` tinyint(4) NOT NULL,
  `rank` tinyint(4) NOT NULL,
  `company_idx` int(11) NOT NULL,
  `company_stock_idx` int(11) NOT NULL,
  `date` date NOT NULL,
  `svm_processed` int(1) NOT NULL,
  `hmm_processed` int(1) NOT NULL,
  `created_date_time` datetime NOT NULL,
  `status` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `company`
--
ALTER TABLE `company`
  ADD PRIMARY KEY (`idx`),
  ADD KEY `need_history` (`need_history`),
  ADD KEY `code` (`code`),
  ADD KEY `last_updated` (`last_updated`),
  ADD KEY `status` (`status`),
  ADD KEY `need_history_2` (`need_history`,`status`),
  ADD KEY `name` (`name`,`code`,`status`);

--
-- Indexes for table `company_stock`
--
ALTER TABLE `company_stock`
  ADD PRIMARY KEY (`idx`),
  ADD KEY `company_idx` (`company_idx`),
  ADD KEY `date` (`date`),
  ADD KEY `stats` (`status`),
  ADD KEY `company_idx_2` (`company_idx`,`status`),
  ADD KEY `price` (`price`),
  ADD KEY `company_idx_3` (`company_idx`,`date`,`status`),
  ADD KEY `company_idx_4` (`company_idx`,`price`,`date`,`status`),
  ADD KEY `high` (`high`),
  ADD KEY `low` (`low`),
  ADD KEY `percentage` (`percentage`);

--
-- Indexes for table `model_result`
--
ALTER TABLE `model_result`
  ADD PRIMARY KEY (`idx`),
  ADD KEY `playlist_idx` (`playlist_idx`,`train_company_idx`,`test_company_idx`),
  ADD KEY `playlist_idx_2` (`playlist_idx`,`train_company_idx`,`test_company_idx`,`status`),
  ADD KEY `score` (`score`),
  ADD KEY `playlist_idx_3` (`playlist_idx`,`score`,`status`);

--
-- Indexes for table `playlist`
--
ALTER TABLE `playlist`
  ADD PRIMARY KEY (`idx`);


/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

