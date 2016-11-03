-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Host: 172.17.0.10
-- Generation Time: Nov 03, 2016 at 09:24 AM
-- Server version: 5.6.33
-- PHP Version: 5.6.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `marmot`
--

-- --------------------------------------------------------

--
-- Table structure for table `pcore_user`
--

CREATE TABLE `pcore_user` (
  `user_id` int(10) NOT NULL COMMENT '用户主键id',
  `cellphone` char(11) DEFAULT NULL COMMENT '用户手机号',
  `password` char(32) NOT NULL COMMENT '用户密码',
  `salt` char(4) NOT NULL COMMENT '盐杂质',
  `create_time` int(10) NOT NULL COMMENT '创建时间',
  `update_time` int(10) NOT NULL COMMENT '更新时间',
  `status` tinyint(1) NOT NULL COMMENT '状态(STATUS_NORMAL,0,默认),(STATUS_DELETE,0,删除)',
  `status_time` int(10) NOT NULL COMMENT '状态更新时间',
  `real_name` varchar(255) NOT NULL COMMENT '真实姓名',
  `user_name` varchar(255) NOT NULL COMMENT '用户名',
  `nick_name` varchar(255) NOT NULL COMMENT '昵称'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='买家用户表';

--
-- Indexes for dumped tables
--

--
-- Indexes for table `pcore_user`
--
ALTER TABLE `pcore_user`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `user_name` (`user_name`),
  ADD UNIQUE KEY `cellphone` (`cellphone`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `pcore_user`
--
ALTER TABLE `pcore_user`
  MODIFY `user_id` int(10) NOT NULL AUTO_INCREMENT COMMENT '用户主键id', AUTO_INCREMENT=1;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
