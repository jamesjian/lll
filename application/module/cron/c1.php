<?php

/*
  /home1/huarend1/public_html/baoxiancom/application/module/cron/c1.php
 * 
 */
include 'cron_head.php';

$description = Article::backup_sql();
//\Zx\Test\Test::object_log('$description', $description, __FILE__, __LINE__, __CLASS__, __METHOD__);      
$message = Swift_Message::newInstance();
$message->setSubject('article backup')
        ->setFrom(array(MAIL_USER => 'baoxian.com.au'))
        ->setTo(array('james.jian.hang@gmail.com'))
        ->setBody($description, 'text/html');
Swiftmail::send_email($message);

