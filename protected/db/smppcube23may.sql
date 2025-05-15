-- phpMyAdmin SQL Dump
-- version 3.2.4
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 23, 2012 at 02:44 PM
-- Server version: 5.1.41
-- PHP Version: 5.3.1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `smppcube`
--

-- --------------------------------------------------------

--
-- Table structure for table `sc_contact_groups`
--

CREATE TABLE IF NOT EXISTS `sc_contact_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `group_name` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `sc_contact_groups`
--

INSERT INTO `sc_contact_groups` (`id`, `user_id`, `group_name`) VALUES
(1, 1, 'Jaipur');

-- --------------------------------------------------------

--
-- Table structure for table `sc_sender_id`
--

CREATE TABLE IF NOT EXISTS `sc_sender_id` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `senderid` varchar(15) NOT NULL,
  `req_by` int(11) NOT NULL,
  `req_on` varchar(20) NOT NULL,
  `status` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `req_by` (`req_by`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `sc_sender_id`
--


-- --------------------------------------------------------

--
-- Table structure for table `sc_sms_templates`
--

CREATE TABLE IF NOT EXISTS `sc_sms_templates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `title` varchar(25) NOT NULL,
  `content` tinytext NOT NULL,
  `created_on` varchar(20) NOT NULL,
  `last_modified` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `sc_sms_templates`
--

INSERT INTO `sc_sms_templates` (`id`, `user_id`, `title`, `content`, `created_on`, `last_modified`) VALUES
(1, 1, 'hello', 'awe fadscsdcsd\n\ndsfdfadsfzfdfggz g zg zgzfgzdfg zfgzdfgz\nz\nf\nzfvz zfgdfgxdfg xxdfgxdfgx', '2012-05-22 10:57:04 ', '0000-00-00 00:00:00'),
(3, 1, 'title too', 'edited by same', '2012-05-23 12:11:39 ', '2012-05-23 00:23:55');

-- --------------------------------------------------------

--
-- Table structure for table `sc_users`
--

CREATE TABLE IF NOT EXISTS `sc_users` (
  `userid` bigint(20) NOT NULL AUTO_INCREMENT,
  `loginid` varchar(25) NOT NULL,
  `password` varchar(40) NOT NULL,
  `name` varchar(40) NOT NULL,
  `category` varchar(10) NOT NULL,
  `mobile` varchar(10) NOT NULL,
  `email` varchar(45) NOT NULL,
  `upline_id` varchar(40) NOT NULL,
  `status` varchar(15) NOT NULL,
  `spam_status` int(2) NOT NULL,
  `smsc` varchar(30) NOT NULL,
  `dnd_refund` int(2) NOT NULL,
  PRIMARY KEY (`userid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `sc_users`
--

INSERT INTO `sc_users` (`userid`, `loginid`, `password`, `name`, `category`, `mobile`, `email`, `upline_id`, `status`, `spam_status`, `smsc`, `dnd_refund`) VALUES
(1, 'client', '12345', 'Saurabh Pandey', 'client', '9887676765', 'a@a.com', 'admin', 'active', 0, 'tata', 0),
(2, 'res', '12345', 'Reseller One', 'reseller', '9988844424', 's@s.com', 'admin', 'active', 1, 'tata', 0);

-- --------------------------------------------------------

--
-- Table structure for table `sc_user_contacts`
--

CREATE TABLE IF NOT EXISTS `sc_user_contacts` (
  `id` bigint(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `contact_no` varchar(15) NOT NULL,
  `contact_name` varchar(30) NOT NULL,
  `contact_email` varchar(40) NOT NULL,
  `group_id` int(11) NOT NULL,
  `city` varchar(15) NOT NULL,
  `state` varchar(15) NOT NULL,
  `zip` int(6) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=19 ;

--
-- Dumping data for table `sc_user_contacts`
--

INSERT INTO `sc_user_contacts` (`id`, `user_id`, `contact_no`, `contact_name`, `contact_email`, `group_id`, `city`, `state`, `zip`) VALUES
(1, 1, '9829435767', 'Sam', 'sam08sk@ymail.com', 1, 'Jaipur', 'Rajasthan', 302020),
(2, 0, '77321312312', 'nikhil', 'nikhil@gmail.com', 0, 'Jaipur', 'rajasthan', 302020),
(3, 0, '9866687768', 'danny', 'danny@gmail.com', 0, 'Jaipur', 'rajasthan', 302020),
(4, 0, '9866687768', 'danny', 'danny@gmail.com', 0, 'Jaipur', 'rajasthan', 302020),
(5, 0, '8767666887', 'cadad', 'jhghg@bjjh.sd', 0, 'gjhghjgjj', 'hjgjhjh', 768768),
(6, 1, '5566577577', 'hjkjhj', 'hkjhkj', 0, 'kjhkjhkj', 'hkjhkjh', 54345),
(7, 1, 'hfgfhjn', 'mmkmklm', 'mklmklmkl', 0, 'lkmnlkklkjkl', 'mklmklmklm', 898989),
(8, 1, 'ggfpoiohkjh', 'kj', 'hjhjhkhhjh', 1, 'hhjhkjhjkh', 'hjjliugf', 68687),
(9, 1, 'ggfpoiohkjh', 'kj', 'hjhjhkhhjh', 1, 'hhjhkjhjkh', 'hjjliugf', 68687),
(10, 1, '687678688', 'hjghjgjh', 'gjhg', 1, 'hghjgjghjh', 'hjghjg', 545343),
(11, 1, 'dfsds', 'fgdf', 'hikjlkj', 1, 'jhkj', 'hk', 0),
(12, 1, 'jhkhjkl', 'kjhlkjh', 'h', 1, 'jhllkjhjk', 'kljh', 57676),
(13, 1, 'fvdcDCd', 'hjghjghg', 'hjghjghhg', 1, 'jhgjghjghkhjg', 'hgjgjghjg', 987887),
(14, 1, '86877676687', 'jkhjh', 'jhjhgjgkj', 1, 'jhgjgjhg', 'gjghjgjhg', 989789),
(15, 1, '878977897', 'hgkjghhkj', 'jgjhgkjhg', 1, 'kjhgkgjhg', 'hjgjgjgjhg', 987879),
(16, 1, '879798797', 'bhhghjhb', 'jhhjghjg', 1, 'jghgkjhgkh', 'ghjgkhjgh', 798788),
(17, 1, 'hkjhihiukhk', 'mhjghjghjg', 'jgjhghhjkgjg', 0, 'gkhjghjhgkjg', 'jhghjgkhj', 0),
(18, 1, '564562345', 'nbjbb', 'jbhjbhjbhj', 1, 'hbhjbhjbhj', 'bhjbhjbj', 987987);

-- --------------------------------------------------------

--
-- Table structure for table `sc_user_credits`
--

CREATE TABLE IF NOT EXISTS `sc_user_credits` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `total_h` int(11) NOT NULL,
  `total_m` int(11) NOT NULL,
  `total_p` int(11) NOT NULL,
  `used_h` int(11) NOT NULL,
  `used_m` int(11) NOT NULL,
  `used_p` int(11) NOT NULL,
  `rem_h` int(11) NOT NULL,
  `rem_m` int(11) NOT NULL,
  `rem_p` int(11) NOT NULL,
  `expiry` varchar(25) NOT NULL,
  `status` varchar(10) NOT NULL,
  `alloted_by` int(11) NOT NULL,
  PRIMARY KEY (`id`,`user_id`),
  UNIQUE KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `sc_user_credits`
--


-- --------------------------------------------------------

--
-- Table structure for table `sc_user_sites`
--

CREATE TABLE IF NOT EXISTS `sc_user_sites` (
  `site_id` varchar(35) NOT NULL,
  `user_id` varchar(35) NOT NULL,
  `web_url` varchar(80) NOT NULL,
  `logo_path` varchar(80) NOT NULL,
  `jquery_css` varchar(100) NOT NULL,
  `company_name` varchar(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sc_user_sites`
--

INSERT INTO `sc_user_sites` (`site_id`, `user_id`, `web_url`, `logo_path`, `jquery_css`, `company_name`) VALUES
('spcube_default', '1', 'http://localhost/smppcube/', 'img/logo.png', 'sc_styles/black-tie/jquery-ui-1.8.12.custom.css', 'SMPP Cube');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
