<?php
/**
 * todo: next step
 * this is a transaction table of score
 * record all transactions (increase and decrease of score)
 * don't disclose to user, only for administration
 * 
 * CREATE TABLE score (
 id  INT(11) unsigned  AUTO_INCREMENT PRIMARY KEY,
 uid MEDIUMINT(8) unsigned, 
 item_id  MEDIUMINT(8) unsigned  not null default 0, 
 operation varchar(255) notT NULL DEFAULT '',  
  previous_score MEDIUMINT(8) unsigned,
  difference tinyint(3) unsigned,
  current_score MEDIUMINT(8) unsigned,
  status unsigned tinyint(1) not null default 1,
  date_created datetime) engine=innodb default charset=utf8
 * 
 */
