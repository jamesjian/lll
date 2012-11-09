<?php

namespace App\Module\Admin\Controller;

use \App\Model\Blog as Model_Blog;
use \App\Model\Blogcategory as Model_Blogcategory;
use \App\Transaction\Tool as Transaction_Tool;
use \Zx\View\View;
use \Zx\Test\Test;

class Tool extends Base {

    public function init() {
        $this->view_path = APPLICATION_PATH . 'module/admin/view/tool/';
        parent::init();
    }

    public function sitemap() {
        Transaction_Tool::generate_sitemap();
        View::set_view_file($this->view_path . 'sitemap_result.php');
    }
/**
 *copy from jumba server to backup database 
 */
    public function backup() {

        /*
          $to = "james.jian.hang@gmail.com";
          $message = 'php -q /home/hang2005/cron/t1.php';
          $subject = 'cron test2';
          mail($to, $subject, $message);
         */
        $datestamp = date("Y-m-d"); // Current date to append to filename of backup file in format of YYYY-MM-DD
// CONFIGURE THE FOLLOWING LINES TO MATCH YOUR SETUP
//=================================================================

        $dbname = "hang2005_d620";     // Database name. Use --all-databases if you have more than one
        $dbuser = "hang2005_d620"; // Database username
        $dbpwd = "OGM]c&U(WMQs";   // Database password
        $filename = "backup-$datestamp.sql.gz"; // The name (and optionally path) of the dump file
        $to = "james.jian.hang@gmail.com";      // Email address to send dump file to
        $from = "admin@preenet.com";      // Email address message will show as coming from
        $subject = "MYUSERNAME database backup - $datestamp"; // Subject of email
// one or both of the following must be true:
        $sendEmail = true;
        $keepFile = false;

//=================================================================
// Do not change anything below here

        $command = "mysqldump -u $dbuser --password=\"$dbpwd\" $dbname | gzip > $filename";
        $result = passthru($command);

        if ($sendEmail) {
            $attachmentname = basename($filename); // If a path was included, strip it out for the attachment name

            $message = "Compressed database backup file $attachmentname attached.";
            $mime_boundary = "<<<:" . md5(time());
            $data = chunk_split(base64_encode(implode("", file($filename))));

            $headers = "From: $from\r\n";
            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-type: multipart/mixed;\r\n";
            $headers .= " boundary=\"$mime_boundary\"\r\n";

            $content = "This is a multi-part message in MIME format.\r\n\r\n";
            $content.= "--$mime_boundary\r\n";
            $content.= "Content-Type: text/plain; charset=\"iso-8859-1\"\r\n";
            $content.= "Content-Transfer-Encoding: 7bit\r\n\r\n";
            $content.= $message . "\r\n";
            $content.= "--$mime_boundary\r\n";
            $content.= "Content-Disposition: attachment;\r\n";
            $content.= "Content-Type: Application/Octet-Stream; name=\"$attachmentname\"\r\n";
            $content.= "Content-Transfer-Encoding: base64\r\n\r\n";
            $content.= $data . "\r\n";
            $content.= "--$mime_boundary\r\n";

            mail($to, $subject, $content, $headers);
        }

//if (!$keepFile) unlink($filename); //delete the backup file from the server
    }

}
