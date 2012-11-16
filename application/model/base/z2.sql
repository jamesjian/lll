CREATE TABLE staff (name varchar(255) PRIMARY KEY,
password varchar(32) NOT NULL DEFAULT ''
) engine=innodb default charset=utf8;

INSERT INTO `staff` (`name`, `password`) VALUES
('admin', '21232f297a57a5a743894a0e4a801fc3');
CREATE TABLE IF NOT EXISTS `session` (
  `session_id` varchar(100) COLLATE utf8_bin NOT NULL,
  `session_data` text COLLATE utf8_bin NOT NULL,
  `expires` int(11) NOT NULL,
  PRIMARY KEY (`session_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;



CREATE TABLE IF NOT EXISTS `page_category` (
  `id` tinyint(2) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL DEFAULT '',
  `description` text,
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `date_created` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;



INSERT INTO `page_category` (`id`, `title`, `description`, `status`, `date_created`) VALUES
(1, 'b11', 'b1b11', 1, '2012-08-01 00:00:00'),
(2, 'b2', 'b2b2', 1, '2012-08-01 00:00:00'),
(3, 'b3', 'b3b3', 1, '2012-08-01 00:00:00'),
(4, 'b4', 'b4b4', 1, '2012-08-01 00:00:00'),
(5, 'aaa11', '<p>\r\n	aaa11</p>', 1, NULL);


CREATE TABLE IF NOT EXISTS `page` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT '',
  `content` text,
  `cat_id` tinyint(2) unsigned DEFAULT '1',
  `status` tinyint(1) unsigned DEFAULT '1' COMMENT '1: published, 0: unpublished',
  `date_created` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;


INSERT INTO `page` (`id`, `title`, `content`, `cat_id`, `status`, `date_created`) VALUES
(1, 'aaa', '<p>\r\n	aaa</p>', 1, 1, NULL),
(2, 'aaa', '<p>\r\n	aaaaa</p>', 1, 1, NULL);


  CREATE TABLE user (
  id mediumint(8) unsigned AUTO_INCREMENT primary key,
  user_name varchar(30) not null default '',
  password varchar(32) NOT NULL DEFAULT '',
  email varchar(255) not null default '' unique ,
  image varchar(255) not null default '' ,
  num_of_questions mediumint(8) unsigned not null default 0,
  num_of_answers mediumint(8) unsigned  not null default 0,
  rank MEDIUMINT(8) unsigned not null default 0,
  status tinyint(1) unsigned not null default 1,
  date_created datetime) engine=innodb default charset=utf8;

  CREATE TABLE question (
 id  MEDIUMINT(8) unsigned AUTO_INCREMENT PRIMARY KEY,
  title varchar(255) NOT NULL DEFAULT '',
  user_id int(11) not null default 0,
  user_name varchar(255) not null default '',  
  tag_ids varchar(255) NOT NULL DEFAULT '',
  tag_names varchar(255) not null default '', 
  content text,
  num_of_answers smallint(4) not null default 0,
  rank int(11) not null  default 0,
  status tinyint(1) not null default 1,
  date_created datetime) engine=innodb default charset=utf8;


  CREATE TABLE answer (
  id  MEDIUMINT(8) unsigned AUTO_INCREMENT PRIMARY KEY,
  question_id MEDIUMINT(8) unsigned not null default 0,
  user_id MEDIUMINT(8) unsigned not null default 0,
    user_name varchar(30) not null default '',  #user name is fixed
  content text,
  rank MEDIUMINT(8) unsigned  not null default 0,
  status tinyint(1) unsigned  not null default 1,
  date_created datetime) engine=innodb default charset=ut

  CREATE TABLE usertoanswer (
  user_id mediumint(8) unsigned not null default 0,
  answer_id mediumint(8) unsigned not null default 0,
  
   primary key (user_id, answer_id)
 )engine=innodb default charset=utf8;