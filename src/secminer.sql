-- phpMyAdmin SQL Dump
-- version 3.4.5
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2013 年 12 月 21 日 01:27
-- 服务器版本: 5.5.16
-- PHP 版本: 5.3.8

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `secminer`
--

-- --------------------------------------------------------

--
-- 表的结构 `buyer_info`
--

CREATE TABLE IF NOT EXISTS `buyer_info` (
  `id` int(11) NOT NULL,
  `credit` decimal(2,1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- 转存表中的数据 `buyer_info`
--

INSERT INTO `buyer_info` (`id`, `credit`) VALUES
(3, 0.0),
(4, 0.0),
(5, 0.0),
(7, 0.0);

-- --------------------------------------------------------

--
-- 表的结构 `comment_info`
--

CREATE TABLE IF NOT EXISTS `comment_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `content` varchar(300) COLLATE utf8_unicode_ci NOT NULL,
  `oid` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `oid` (`oid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=11 ;

--
-- 转存表中的数据 `comment_info`
--

INSERT INTO `comment_info` (`id`, `time`, `content`, `oid`) VALUES
(1, '2013-12-19 14:54:10', 'ss', 14),
(2, '2013-12-19 15:29:46', 'OK', 18),
(3, '2013-12-19 15:34:31', 'THK', 13),
(4, '2013-12-19 15:48:12', '再次感谢，速度很快', 21),
(5, '2013-12-19 15:51:48', '谢谢', 19),
(6, '2013-12-19 15:51:57', '谢谢', 5),
(7, '2013-12-19 15:55:34', '谢啦', 22),
(8, '2013-12-19 15:57:43', '谢谢', 9),
(9, '2013-12-20 07:41:02', '很好，很喜欢！', 23),
(10, '2013-12-20 07:46:21', '很喜欢', 24);

-- --------------------------------------------------------

--
-- 表的结构 `goods_focus`
--

CREATE TABLE IF NOT EXISTS `goods_focus` (
  `id` int(11) NOT NULL,
  `bid` int(11) NOT NULL,
  PRIMARY KEY (`id`,`bid`),
  KEY `bid` (`bid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- 转存表中的数据 `goods_focus`
--

INSERT INTO `goods_focus` (`id`, `bid`) VALUES
(1, 3),
(2, 3),
(4, 3),
(1, 4),
(2, 4),
(6, 4),
(1, 5);

-- --------------------------------------------------------

--
-- 表的结构 `goods_info`
--

CREATE TABLE IF NOT EXISTS `goods_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `usingdgr` decimal(2,1) NOT NULL,
  `originalprice` float DEFAULT NULL,
  `currentprice` float NOT NULL,
  `dscrb` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `state` enum('sellout','onsell') COLLATE utf8_unicode_ci DEFAULT NULL,
  `sid` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sid` (`sid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=11 ;

--
-- 转存表中的数据 `goods_info`
--

INSERT INTO `goods_info` (`id`, `name`, `usingdgr`, `originalprice`, `currentprice`, `dscrb`, `quantity`, `state`, `sid`) VALUES
(1, 'adog', 1.0, 11.11, 10, 'dsafdsfsdgv', 21, 'onsell', 3),
(2, 'cartoon', 1.0, 4.5, 3, 'popular cartoon role', 1, 'onsell', 3),
(3, 'toy', 2.0, 100, 30, 'good', 10, 'onsell', 3),
(4, 'a gril toy', 1.0, 30, 25, 'a new toy', 0, 'onsell', 3),
(5, 'tea', 3.0, 40, 12, 'good\r\n<br/>\r\nihgyjgjyjh\r\nhyggg\r\n<br/>', 29, 'onsell', 4),
(6, 'picture a', 8.0, 45, 4.5, 'blue', 0, 'onsell', 4),
(7, 'clock', 1.0, 80, 20, 'good', 0, 'onsell', 4),
(8, 'tian wen', 1.0, 250, 0, 'çº¯å¤©ç„¶ çº¯é‡Žç”Ÿ', 1, 'sellout', 4),
(9, '123', 9.0, 250, 1, '很好', 1, 'sellout', 4),
(10, 'qq', 1.0, 0, 0, '', 1, 'onsell', 4);

-- --------------------------------------------------------

--
-- 表的结构 `goods_need`
--

CREATE TABLE IF NOT EXISTS `goods_need` (
  `bid` int(11) NOT NULL,
  `content` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `bid` (`bid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=8 ;

--
-- 转存表中的数据 `goods_need`
--

INSERT INTO `goods_need` (`bid`, `content`, `id`, `time`) VALUES
(3, '求羽毛球拍', 4, '2013-12-06 23:10:16'),
(3, '需篮球一个', 5, '2013-12-06 23:10:34'),
(3, '有人有旱冰鞋么？', 6, '2013-12-06 23:10:46'),
(4, '求购《软件工程》', 7, '2013-12-10 02:56:42');

-- --------------------------------------------------------

--
-- 表的结构 `goods_photo`
--

CREATE TABLE IF NOT EXISTS `goods_photo` (
  `id` int(11) NOT NULL,
  `photo` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`,`photo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- 转存表中的数据 `goods_photo`
--

INSERT INTO `goods_photo` (`id`, `photo`) VALUES
(1, 'gdimg/1'),
(2, 'gdimg/2'),
(3, 'gdimg/3'),
(4, 'gdimg/4'),
(5, 'gdimg/5'),
(6, 'gdimg/6'),
(7, 'gdimg/7'),
(8, 'gdimg/8'),
(9, 'gdimg/9'),
(10, 'gdimg/10');

-- --------------------------------------------------------

--
-- 表的结构 `goods_sort`
--

CREATE TABLE IF NOT EXISTS `goods_sort` (
  `id` int(11) NOT NULL,
  `sort` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`,`sort`),
  KEY `sort` (`sort`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- 表的结构 `goods_tag`
--

CREATE TABLE IF NOT EXISTS `goods_tag` (
  `id` int(11) NOT NULL,
  `tag` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`,`tag`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- 表的结构 `msg_info`
--

CREATE TABLE IF NOT EXISTS `msg_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `listener` int(11) NOT NULL,
  `talker` int(11) NOT NULL,
  `content` varchar(400) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `listener` (`listener`),
  KEY `talker` (`talker`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `order_goods`
--

CREATE TABLE IF NOT EXISTS `order_goods` (
  `gid` int(11) NOT NULL,
  `oid` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` float DEFAULT NULL,
  PRIMARY KEY (`oid`,`gid`),
  KEY `gid` (`gid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- 转存表中的数据 `order_goods`
--

INSERT INTO `order_goods` (`gid`, `oid`, `quantity`, `price`) VALUES
(1, 1, 1, 10),
(2, 2, 4, 3),
(2, 3, 3, 3),
(4, 4, 1, 25),
(6, 5, 1, 4.5),
(1, 6, 0, 10),
(2, 7, 1, 3),
(2, 8, 1, 3),
(7, 9, 1, 20),
(2, 10, 5, 3),
(2, 11, 3, 3),
(2, 12, 1, 3),
(2, 13, 8, 3),
(5, 14, 1, 12),
(2, 15, 1, 3),
(2, 16, 1, 3),
(2, 17, 1, 3),
(3, 18, 1, 30),
(5, 19, 1, 12),
(2, 20, 1, 3),
(3, 21, 1, 30),
(3, 22, 1, 30),
(2, 23, 1, 3),
(1, 24, 1, 10);

-- --------------------------------------------------------

--
-- 表的结构 `order_info`
--

CREATE TABLE IF NOT EXISTS `order_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `state` enum('initial','canceled','waiting','delivering','completed') COLLATE utf8_unicode_ci DEFAULT NULL,
  `bid` int(11) NOT NULL,
  `totalcost` float DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `bid` (`bid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=25 ;

--
-- 转存表中的数据 `order_info`
--

INSERT INTO `order_info` (`id`, `time`, `state`, `bid`, `totalcost`) VALUES
(1, '2013-11-16 03:25:07', 'initial', 3, 10),
(2, '2013-11-16 03:27:09', 'initial', 3, 12),
(3, '2013-11-16 03:29:24', 'initial', 3, 9),
(4, '2013-11-16 03:31:10', 'initial', 3, 25),
(5, '2013-12-19 15:33:55', 'completed', 4, 4.5),
(6, '2013-12-07 03:11:47', 'canceled', 4, 0),
(7, '2013-11-16 06:29:43', 'initial', 3, 3),
(8, '2013-11-16 06:29:50', 'initial', 3, 3),
(9, '2013-12-07 02:17:30', 'completed', 4, 20),
(10, '2013-12-19 15:52:33', 'delivering', 3, 15),
(11, '2013-12-07 02:29:42', 'canceled', 3, 9),
(12, '2013-12-07 02:11:14', 'canceled', 3, 3),
(13, '2013-12-19 15:33:34', 'completed', 4, 24),
(14, '2013-12-19 10:01:48', 'completed', 4, 12),
(15, '2013-12-19 15:32:52', 'delivering', 3, 3),
(16, '2013-12-19 15:46:53', 'delivering', 3, 3),
(17, '2013-12-19 15:32:37', 'delivering', 5, 3),
(18, '2013-12-19 10:01:41', 'completed', 4, 30),
(19, '2013-12-07 01:42:50', 'completed', 4, 12),
(20, '2013-12-09 07:47:01', 'initial', 4, 3),
(21, '2013-12-19 09:59:34', 'completed', 4, 30),
(22, '2013-12-19 15:54:15', 'completed', 4, 30),
(23, '2013-12-20 07:40:00', 'completed', 4, 3),
(24, '2013-12-20 07:45:26', 'completed', 4, 10);

-- --------------------------------------------------------

--
-- 表的结构 `person_tag`
--

CREATE TABLE IF NOT EXISTS `person_tag` (
  `id` int(11) NOT NULL,
  `tag` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`,`tag`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- 表的结构 `reply_info`
--

CREATE TABLE IF NOT EXISTS `reply_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `content` varchar(300) COLLATE utf8_unicode_ci NOT NULL,
  `cid` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `cid` (`cid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=8 ;

--
-- 转存表中的数据 `reply_info`
--

INSERT INTO `reply_info` (`id`, `time`, `content`, `cid`) VALUES
(1, '2013-12-19 15:05:53', '不客气', 1),
(2, '2013-12-19 15:30:56', '谢谢购买哦！', 2),
(3, '2013-12-19 15:41:59', '不客气，欢迎下次再来', 3),
(4, '2013-12-19 15:48:41', '应该的', 4),
(5, '2013-12-19 16:09:39', '不客气', 7),
(6, '2013-12-20 07:42:10', '感谢支持！', 9),
(7, '2013-12-20 07:46:48', '感谢支持', 10);

-- --------------------------------------------------------

--
-- 表的结构 `seller_info`
--

CREATE TABLE IF NOT EXISTS `seller_info` (
  `id` int(11) NOT NULL,
  `credit` decimal(2,1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- 转存表中的数据 `seller_info`
--

INSERT INTO `seller_info` (`id`, `credit`) VALUES
(3, 0.0),
(4, 0.0),
(5, 0.0),
(7, 0.0);

-- --------------------------------------------------------

--
-- 表的结构 `sort_info`
--

CREATE TABLE IF NOT EXISTS `sort_info` (
  `totalsort` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `subsort` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`totalsort`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- 表的结构 `super_user`
--

CREATE TABLE IF NOT EXISTS `super_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `password` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `user_info`
--

CREATE TABLE IF NOT EXISTS `user_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `nickname` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `addr` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `gender` enum('m','f','u') COLLATE utf8_unicode_ci DEFAULT NULL,
  `grade` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `school` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(160) COLLATE utf8_unicode_ci NOT NULL,
  `profile` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phoneno` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=8 ;

--
-- 转存表中的数据 `user_info`
--

INSERT INTO `user_info` (`id`, `name`, `nickname`, `addr`, `gender`, `grade`, `school`, `password`, `profile`, `phoneno`) VALUES
(3, 'lessmoon', 'lessmoon', '2', 'u', '11', 'HIT', '3c2f5decde47940c8baf3b80dea449bd', 'profile/3', '18686774990'),
(4, 'hdt', 'hdt', '15-717', 'f', '3', 'hit', '698d51a19d8a121ce581499d7b701668', 'profile/4', '18686774880'),
(5, 'eg', 'example', '2', 'u', '12çº§', 'hit', '2a6a84e9e44441afbd75cc19ce28be37', 'profile/5', '18686831710'),
(7, 'fengzhaojia', 'fzj', '15-717', 'f', '3', 'hit', '202cb962ac59075b964b07152d234b70', 'img/profile.jpg', '18686774880');

--
-- 限制导出的表
--

--
-- 限制表 `buyer_info`
--
ALTER TABLE `buyer_info`
  ADD CONSTRAINT `buyer_info_ibfk_1` FOREIGN KEY (`id`) REFERENCES `user_info` (`id`);

--
-- 限制表 `comment_info`
--
ALTER TABLE `comment_info`
  ADD CONSTRAINT `comment_info_ibfk_1` FOREIGN KEY (`oid`) REFERENCES `order_info` (`id`);

--
-- 限制表 `goods_focus`
--
ALTER TABLE `goods_focus`
  ADD CONSTRAINT `goods_focus_ibfk_1` FOREIGN KEY (`id`) REFERENCES `goods_info` (`id`),
  ADD CONSTRAINT `goods_focus_ibfk_2` FOREIGN KEY (`bid`) REFERENCES `buyer_info` (`id`);

--
-- 限制表 `goods_info`
--
ALTER TABLE `goods_info`
  ADD CONSTRAINT `goods_info_ibfk_1` FOREIGN KEY (`sid`) REFERENCES `seller_info` (`id`);

--
-- 限制表 `goods_need`
--
ALTER TABLE `goods_need`
  ADD CONSTRAINT `goods_need_ibfk_1` FOREIGN KEY (`bid`) REFERENCES `buyer_info` (`id`);

--
-- 限制表 `goods_photo`
--
ALTER TABLE `goods_photo`
  ADD CONSTRAINT `goods_photo_ibfk_1` FOREIGN KEY (`id`) REFERENCES `goods_info` (`id`);

--
-- 限制表 `goods_sort`
--
ALTER TABLE `goods_sort`
  ADD CONSTRAINT `goods_sort_ibfk_1` FOREIGN KEY (`id`) REFERENCES `goods_info` (`id`),
  ADD CONSTRAINT `goods_sort_ibfk_2` FOREIGN KEY (`sort`) REFERENCES `sort_info` (`totalsort`);

--
-- 限制表 `goods_tag`
--
ALTER TABLE `goods_tag`
  ADD CONSTRAINT `goods_tag_ibfk_1` FOREIGN KEY (`id`) REFERENCES `goods_info` (`id`);

--
-- 限制表 `msg_info`
--
ALTER TABLE `msg_info`
  ADD CONSTRAINT `msg_info_ibfk_1` FOREIGN KEY (`listener`) REFERENCES `user_info` (`id`),
  ADD CONSTRAINT `msg_info_ibfk_2` FOREIGN KEY (`talker`) REFERENCES `user_info` (`id`);

--
-- 限制表 `order_goods`
--
ALTER TABLE `order_goods`
  ADD CONSTRAINT `order_goods_ibfk_1` FOREIGN KEY (`oid`) REFERENCES `order_info` (`id`),
  ADD CONSTRAINT `order_goods_ibfk_2` FOREIGN KEY (`gid`) REFERENCES `goods_info` (`id`);

--
-- 限制表 `order_info`
--
ALTER TABLE `order_info`
  ADD CONSTRAINT `order_info_ibfk_1` FOREIGN KEY (`bid`) REFERENCES `buyer_info` (`id`);

--
-- 限制表 `person_tag`
--
ALTER TABLE `person_tag`
  ADD CONSTRAINT `person_tag_ibfk_1` FOREIGN KEY (`id`) REFERENCES `user_info` (`id`);

--
-- 限制表 `reply_info`
--
ALTER TABLE `reply_info`
  ADD CONSTRAINT `reply_info_ibfk_1` FOREIGN KEY (`cid`) REFERENCES `comment_info` (`id`);

--
-- 限制表 `seller_info`
--
ALTER TABLE `seller_info`
  ADD CONSTRAINT `seller_info_ibfk_1` FOREIGN KEY (`id`) REFERENCES `user_info` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
