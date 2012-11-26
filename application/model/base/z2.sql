
drop table ts8wl_ad;
drop table ts8wl_answer;
drop table ts8wl_question;
drop table ts8wl_user;
drop table ts8wl_staff;
drop table ts8wl_tag;
drop table ts8wl_article;
drop table ts8wl_article_category;
drop table ts8wl_user_to_answer;
drop table ts8wl_page;
drop table ts8wl_page_category;
drop table session;
  CREATE TABLE ts8wl_ad (
 id int(11) AUTO_INCREMENT PRIMARY KEY,
  title varchar(255) NOT NULL DEFAULT '',
  user_id int(11) not null default 0,
  user_name varchar(255) not null  default '',  
  tag_ids varchar(255) NOT NULL DEFAULT '',
  tag_names varchar(255) not null default '', 
  content text,
  num_of_displays int(11) default 0,  
  num_of_clicks int(11) default 0,      status tinyint(1) not null default 1,
  date_created datetime) engine=innodb default charset=utf8

INSERT INTO `jm3`.`ts8wl_ad` (`id`, `title`, `user_id`, `user_name`, `tag_ids`, `tag_names`, `content`, `num_of_displays`, `num_of_clicks`, `status`, `date_created`) VALUES ('0', 'empty', '0', '', '', '', NULL, '0', '0', '1', NULL);

CREATE TABLE IF NOT EXISTS `ts8wl_answer` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `question_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `user_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `user_name` varchar(30) NOT NULL DEFAULT '',
  `content` text,
  `rank` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `date_created` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `ts8wl_page` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT '',
  `content` text,
  `cat_id` tinyint(2) DEFAULT '1',
  `status` tinyint(1) DEFAULT '1' COMMENT '1: published, 0: unpublished',
  `date_created` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;


INSERT INTO `ts8wl_page` (`id`, `title`, `content`, `cat_id`, `status`, `date_created`) VALUES
(1, 'aaa', '<p>\r\n	aaa</p>', 1, 1, NULL),
(2, 'aaa', '<p>\r\n	aaaaa</p>', 1, 1, NULL);


CREATE TABLE IF NOT EXISTS `ts8wl_page_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL DEFAULT '',
  `description` text,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `date_created` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;


INSERT INTO `ts8wl_page_category` (`id`, `title`, `description`, `status`, `date_created`) VALUES
(1, 'b11', 'b1b11', 1, '2012-08-01 00:00:00'),
(2, 'b2', 'b2b2', 1, '2012-08-01 00:00:00'),
(3, 'b3', 'b3b3', 1, '2012-08-01 00:00:00'),
(4, 'b4', 'b4b4', 1, '2012-08-01 00:00:00'),
(5, 'aaa11', '<p>\r\n	aaa11</p>', 1, NULL);


CREATE TABLE IF NOT EXISTS `ts8wl_question` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL DEFAULT '',
  `user_id` int(11) NOT NULL DEFAULT '0',
  `user_name` varchar(255) NOT NULL DEFAULT '',
  `tag_ids` varchar(255) NOT NULL DEFAULT '',
  `tag_names` varchar(255) NOT NULL DEFAULT '',
  `content` text,
  `num_of_answers` smallint(4) NOT NULL DEFAULT '0',
  `rank` int(11) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `date_created` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `session` (
  `session_id` varchar(100) COLLATE utf8_bin NOT NULL,
  `session_data` text COLLATE utf8_bin NOT NULL,
  `expires` int(11) NOT NULL,
  PRIMARY KEY (`session_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


CREATE TABLE IF NOT EXISTS `ts8wl_staff` (
  `name` varchar(255) NOT NULL,
  `password` varchar(32) NOT NULL DEFAULT '',
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


INSERT INTO `ts8wl_staff` (`name`, `password`) VALUES
('admin', '21232f297a57a5a743894a0e4a801fc3');

CREATE TABLE IF NOT EXISTS `ts8wl_user` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `user_name` varchar(30) NOT NULL DEFAULT '',
  `password` varchar(32) NOT NULL DEFAULT '',
  `email` varchar(255) NOT NULL DEFAULT '',
  `image` varchar(255) NOT NULL DEFAULT '',
  `num_of_questions` mediumint(8) NOT NULL DEFAULT '0',
  `num_of_answers` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `rank` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `date_created` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `ts8wl_user_to_answer` (
  `user_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `answer_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`user_id`,`answer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `session` (
  `session_id` varchar(100) COLLATE utf8_bin NOT NULL,
  `session_data` text COLLATE utf8_bin NOT NULL,
  `expires` int(11) NOT NULL,
  PRIMARY KEY (`session_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

CREATE TABLE ts8wl_tag (
  id int(11) AUTO_INCREMENT PRIMARY KEY,
  name varchar(255) NOT NULL DEFAULT '',
  num_of_questions mediumint(6) default 0,
  num_of_ads mediumint(6) default 0,
  rank int(11) default 0,
  status tinyint(1) not null default 1,
  date_created datetime) engine=innodb default charset=utf8;


INSERT INTO `ts8wl_user` (`id`, `user_name`, `password`, `email`, `image`, `num_of_questions`, `num_of_answers`, `rank`, `status`, `date_created`) VALUES
(1, '匿名提问用户', 'aaa', 'aaa@aaa.com', '', 0, 0, 0, 1, NULL),
(2, '匿名回答用户', 'aaa', 'aaa@aaa1.com', '', 0, 0, 0, 1, NULL);