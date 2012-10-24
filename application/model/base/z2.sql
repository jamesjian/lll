-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Oct 23, 2012 at 10:00 PM
-- Server version: 5.5.24-log
-- PHP Version: 5.4.3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `z2`
--

-- --------------------------------------------------------

--
-- Table structure for table `article`
--

CREATE TABLE IF NOT EXISTS `article` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL DEFAULT '',
  `title_en` varchar(255) NOT NULL,
  `cat_id` int(11) NOT NULL DEFAULT '1',
  `keyword` varchar(255) NOT NULL DEFAULT '',
  `keyword_en` varchar(255) NOT NULL,
  `abstract` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `content` text,
  `rank` int(11) DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `date_created` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12 ;

--
-- Dumping data for table `article`
--

INSERT INTO `article` (`id`, `title`, `title_en`, `cat_id`, `keyword`, `keyword_en`, `abstract`, `url`, `content`, `rank`, `status`, `date_created`) VALUES
(1, 'niba   national insurance broker', '1', 4, 'niba,多伦多', 'niba, insurance, organization', 'national insurance broker', 'niba', '<h1>\r\n	<span style="color: rgb(153, 153, 153); font-family: Verdana, Helvetica, sans-serif; line-height: 18px; ">NIBA is the national trade association for licensed life and general insurance brokers in Australia. It is a not for profit organisation and neither sells insurance products or represents insurance companies. NIBA is the national voice of insurance brokers and an advocate for insurance consumers. Our website provides information for consumers, insurance and risk professionals throughout the industry and NIBA members.</span></h1>', 13, 1, NULL),
(2, 'c1', 'c1', 1, '中国', '', 'eeeeeeeeeeeeeee', '', '', 0, 1, NULL),
(3, 'c2', 'c2', 1, '中国', '', 'fffffffffffffff', '', '<p>\r\n	<img alt="" src="http://localhost/pree/upload/5074f3431c50bcfalogo.png" style="width: 360px; height: 60px; " /></p>', 0, 1, NULL),
(4, 'c3', 'c3', 2, '', '', 'ggggggggggggg', '', '<p>\r\n	<img alt="" src="http://localhost/pree/upload/5074f35d0ec91Acupuncturist.jpg" style="width: 141px; height: 116px; " /></p>', 0, 1, NULL),
(5, 'aaa1', '', 2, '', '', 'ccccccccccccccccccc', '', '<p>\r\n	bbb1</p>', 0, 1, NULL),
(6, 'ADFASFDS', '', 2, '', '', 'dddddddddddddddd', '', '<p>\r\n	ASDASDFSADF</p>', 0, 1, NULL),
(7, 'ccc3333', '', 1, '', '', 'hhhhhhhhhhhhhhhhh', '', '<p>\r\n	cccc<img alt="" src="http://localhost/pree/upload/5074f4814ecc6natura therapist.jpg" style="width: 141px; height: 116px; " /></p>', 0, 1, NULL),
(8, 'ccc111', '', 1, '', '', 'gggggggggggggggg', '', '<p>\r\n	<img alt="" src="http://localhost/pree/upload/5074f46ab4d51nurse.jpg" style="width: 141px; height: 116px; " /></p>', 0, 1, NULL),
(9, 'd1', '', 2, 'd1', 'd1, insurance, organization', 'iiiiiiiiiiiii', 'd1', '<p>\r\n	d1</p>', 1, 1, NULL),
(10, 'd21', 'd21', 4, 'd21', 'd21, insurance, organization', 'jjjjjjjjjjjj', 'd21', '<p>\r\n	d21</p>', 21, 0, NULL),
(11, 'a1', 'a1', 2, '', '', 'aaaaaaaaa', '', '<p>\r\n	<a href="http://localhost/pree/upload/5074f2a9187aecystic_fib_logo.png"><img alt="" src="http://localhost/pree/upload/5074f2e639a08cystic_fib_logo.png" style="width: 60px; height: 60px; " /></a></p>', 0, 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `article_category`
--

CREATE TABLE IF NOT EXISTS `article_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL DEFAULT '',
  `title_en` varchar(255) NOT NULL,
  `keyword` varchar(255) NOT NULL,
  `keyword_en` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `description` text,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `date_created` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `article_category`
--

INSERT INTO `article_category` (`id`, `title`, `title_en`, `keyword`, `keyword_en`, `url`, `description`, `status`, `date_created`) VALUES
(1, 'æ¾³æ´²ä¿é™©å¸¸è¯†', 'Australia Insurance Knowledge', 'æ¾³æ´²,ä¿é™©,å¸¸è¯†', 'Australia Insurance Knowledge', '', '<p>\r\n	c111</p>', 1, '2012-08-01 00:00:00'),
(2, 'æ¾³æ´²ä¿é™©æ³•è§„', 'Australia Insurance Law', 'æ¾³æ´²,ä¿é™©,æ³•è§„', 'Australia Insurance Law', '', '<p>\r\n	b2b2</p>', 1, '2012-08-01 00:00:00'),
(3, 'æ¾³æ´²ä¿é™©æ•™è‚²', 'Australia Insurance Education', 'æ¾³æ´²,ä¿é™©,æ•™è‚²', 'Australia Insurance Education', '', '<p>\r\n	b3b3</p>', 1, '2012-08-01 00:00:00'),
(4, 'æ¾³æ´²ä¿é™©æœºæž„', 'Australia Insurance Organization', 'æ¾³æ´²,ä¿é™©,æœºæž„', 'Australia Insurance Organization', '', '<p>\r\n	b4b4</p>', 1, '2012-08-01 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `page`
--

CREATE TABLE IF NOT EXISTS `page` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT '',
  `content` text,
  `cat_id` tinyint(2) DEFAULT '1',
  `status` tinyint(1) DEFAULT '1' COMMENT '1: published, 0: unpublished',
  `date_created` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `page`
--

INSERT INTO `page` (`id`, `title`, `content`, `cat_id`, `status`, `date_created`) VALUES
(1, 'aaa', '<p>\r\n	aaa</p>', 1, 1, NULL),
(2, 'aaa', '<p>\r\n	aaaaa</p>', 1, 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `page_category`
--

CREATE TABLE IF NOT EXISTS `page_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL DEFAULT '',
  `description` text,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `date_created` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `page_category`
--

INSERT INTO `page_category` (`id`, `title`, `description`, `status`, `date_created`) VALUES
(1, 'b11', 'b1b11', 1, '2012-08-01 00:00:00'),
(2, 'b2', 'b2b2', 1, '2012-08-01 00:00:00'),
(3, 'b3', 'b3b3', 1, '2012-08-01 00:00:00'),
(4, 'b4', 'b4b4', 1, '2012-08-01 00:00:00'),
(5, 'aaa11', '<p>\r\n	aaa11</p>', 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `session`
--

CREATE TABLE IF NOT EXISTS `session` (
  `session_id` varchar(100) COLLATE utf8_bin NOT NULL,
  `session_data` text COLLATE utf8_bin NOT NULL,
  `expires` int(11) NOT NULL,
  PRIMARY KEY (`session_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `session`
--

INSERT INTO `session` (`session_id`, `session_data`, `expires`) VALUES
('2eopr7nql48m6c0u5iv12gi2s5', 'success_message|s:0:"";error_message|s:0:"";staff|a:1:{s:10:"staff_name";s:5:"admin";}CK_UPLOAD_PATH|s:4:"article";current_admin_page|s:38:"/pree/admin/article/retrieve/1/title/ASC/";l1_menu|s:4:"Blog";', 1349844159),
('6i79c5hgafn61isou9jpkpdcb0', 'success_message|s:0:"";error_message|s:0:"";', 1350019972),
('84ahn8pnbunmmjacifo1tt1226', 'success_message|s:0:"";error_message|s:0:"";staff|a:1:{s:10:"staff_name";s:5:"admin";}CK_UPLOAD_PATH|s:7:"article";current_admin_page|s:41:"/pree/admin/article/retrieve/4/title/ASC/";admin_l1_menu|s:7:"Article";', 1350008294),
('88uded53b7bkpk4tvjbjmkr5u3', 'success_message|s:0:"";error_message|s:0:"";staff|a:1:{s:10:"staff_name";s:5:"admin";}CK_UPLOAD_PATH|s:4:"article";current_admin_page|s:33:"/pree/admin/articlecategory/retrieve";l1_menu|s:4:"Blog";', 1349824229),
('8qgiafjnqtpj784h76tp81q552', 'success_message|s:0:"";error_message|s:0:"";current_admin_page|s:39:"/pree/admin/article/retrieve/1/title/DESC/";staff|a:1:{s:10:"staff_name";s:5:"admin";}CK_UPLOAD_PATH|s:4:"article";', 1349758365),
('a3dvcsvihq47955hqiahboc3h7', 'success_message|s:0:"";error_message|s:0:"";CK_UPLOAD_PATH|s:4:"article";staff|a:1:{s:10:"staff_name";s:5:"admin";}current_admin_page|s:47:"/pree/public/admin/page/retrieve/1/title/ASC";', 1349421479),
('afcfqusd14ld9bg16o2k95dcu7', 'success_message|s:0:"";error_message|s:0:"";staff|a:1:{s:10:"staff_name";s:5:"admin";}', 1348367053),
('bpuhaaofu554idd3n68ba3pfo6', '', 1350611484),
('bu6omi10o54vr3vcqmn0lbrt64', 'breadcrumb|a:2:{i:0;a:2:{s:4:"link";s:22:"http://localhost/pree/";s:5:"title";s:6:"首页";}i:1;a:2:{s:4:"link";s:84:"http://localhost/pree/front/article/category/æ¾³æ´²ä¿é™©æ³•è§„";s:5:"title";s:39:"æ¾³æ´²ä¿é™©æ³•è§„";}}', 1350351574),
('ck0lo6snh8dg1s74l5n8q4e026', '', 1351030464),
('dgi7245a9tf0p13pvfo2umbfq1', 'success_message|s:0:"";error_message|s:0:"";', 1349292453),
('e3ndh7beuujd0bslrapni941h3', 'success_message|s:0:"";error_message|s:0:"";', 1350255432),
('h62n9bhjb3od85pp19a8m9vt54', 'pk|s:4:"ppkk";', 1348173507),
('iurqomnlbj1mbavtsmstq9i554', '', 1350970293),
('lt10ov83re123edgijkm80gih4', 'success_message|s:0:"";error_message|s:0:"";staff|a:1:{s:10:"staff_name";s:5:"admin";}', 1349744146),
('msesqvck2b499cjbod3n7ua8j3', 'success_message|s:0:"";error_message|s:0:"";staff|a:1:{s:10:"staff_name";s:5:"admin";}', 1349394425),
('p1laeda9s6p90ilac46l4g66g0', 'success_message|s:0:"";error_message|s:0:"";staff|a:1:{s:10:"staff_name";s:5:"admin";}', 1350859891),
('q8qtotc22lg2ftqi29f9h0e1h3', 'success_message|s:0:"";error_message|s:0:"";staff|a:1:{s:10:"staff_name";s:5:"admin";}CK_UPLOAD_PATH|s:7:"article";current_admin_page|s:36:"/pree/admin/articlecategory/retrieve";l1_menu|s:7:"Article";', 1349932218),
('rrm27pvi8s4p4gchb1adoc3p36', 'success_message|s:0:"";error_message|s:0:"";staff|a:1:{s:10:"staff_name";s:5:"admin";}', 1349319984);

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE IF NOT EXISTS `staff` (
  `name` varchar(50) NOT NULL,
  `password` char(32) NOT NULL,
  `group_id` tinyint(1) NOT NULL DEFAULT '1',
  `email` varchar(255) DEFAULT NULL,
  `date_created` datetime DEFAULT NULL,
  PRIMARY KEY (`name`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `staff`
--

INSERT INTO `staff` (`name`, `password`, `group_id`, `email`, `date_created`) VALUES
('admin', '21232f297a57a5a743894a0e4a801fc3', 1, 'admin@site.com', NULL);
