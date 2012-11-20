<?php
defined('SYSPATH') or die('No direct script access.');
namespace App\Transaction;

class Swiftmail {

    /**
     *
     * @param Swift_Message $message   Swift_Message::newInstance()->......
     * @return boolean
     */
    public static function send_email($message) {
        //$transport = Swift_MailTransport::newInstance();   //simplest mail transport
        if (IS_PRODUCTION) {
            //if in test environment, no mail server
            $transport = \Swift_SmtpTransport::newInstance(MAIL_SERVER, MAIL_PORT)
                    ->setUsername(MAIL_USER)
                    ->setPassword(MAIL_PASSWORD);
            //Create the Mailer using your created Transport
            $mailer = \Swift_Mailer::newInstance($transport);
            //Send the message
            if ($mailer->send($message, $failures)) {
                \Zx\Test\Test::object_log('$description', 'succ', __FILE__, __LINE__, __CLASS__, __METHOD__);      
                return true;
            } else {
                \Zx\Test\Test::object_log('$description', 'fail', __FILE__, __LINE__, __CLASS__, __METHOD__);   
                return false;
            }
        } else {
            //if in test environement, will not send email and always true
            return true;
        }
    }

    /**
     * when an order has been confirmed, send it to the registered user
     * contain a pdf file
     * @param array $arr 
     */
    public static function this_is_an_important_email_template_dont_delete_or_modify_me($arr) {
        //simplest mail starts: The message
        $message = "Line 1\nLine 2\nLine 3hhhhh";

        // In case any of our lines are larger than 70 characters, we should use wordwrap()
        $message = wordwrap($message, 70);

        // Send
        mail('james.hang@amtek.com.au', 'My Subject', $message);
        //simplest mail ends
        //pdf starts
        defined('APPLICATION_PATH1')
                || define('APPLICATION_PATH1', realpath(dirname(__FILE__)) . '//application/vendor/');
        set_include_path(implode(PATH_SEPARATOR, array(
                    APPLICATION_PATH1, get_include_path(),
                )));
        require_once 'Zend/Loader/Autoloader.php';
        Zend_Loader_Autoloader::getInstance();
        $pdf = new Zend_Pdf();
        $page = new Zend_Pdf_Page(Zend_Pdf_Page::SIZE_A4);
        $font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA);
        //$image = Zend_Pdf_Image::imageWithPath(DOCROOT . 'image/icon/vcgr-logo.jpg');
        //$page->drawImage($image, 100, 800, 180, 840);
        $page->setFont($font, 24);
        $page->drawText('New Licence Allocation', 200, 800);
        $page->setFont($font, 12);
        $page->drawLine(100, 790, 500, 790);  // line 1
        $page->drawLine(100, 740, 500, 740);  // line 2
        $page->drawLine(100, 690, 500, 690);  // line 3
        $page->drawLine(100, 790, 100, 690);
        $page->drawLine(250, 790, 250, 690);
        $page->drawLine(500, 790, 500, 690);
        $page->drawText('vvvvvvvvvvLicence No. ', 120, 765);
        $page->drawText('999999', 255, 765);
        $page->drawText('serial No. ', 120, 725);
        $page->drawText('123456', 255, 725);

        $pdf->pages[] = $page;
        $pdf_string = $pdf->render();    // or $pdf_string = file_get_contents("foo.pdf")
        //pdf ends
        require_once 'vcgr3/application/vendor/Swift-4.0.6/lib/swift_required.php';
        $description = 'licence id, serial no, machine model, .....from $arraaaaa';
        $message = Swift_Message::newInstance();
        //Give the message a subject
        $message->setSubject('New Licence Allocation')
                //Set the From address with an associative array
                ->setFrom(array('xyz@ab.com' => 'xyz'))
                //Set the To addresses with an associative array
                ->setTo(array('james.jian.hang@gmail.com',
                        //'staff2@vcgr.gov.au' => 'A name')
                ))
                //Give it a body for normal text 
                ->setBody('<q>Here is the message itself</q>', 'text/html')
                //And optionally an alternative body for html and embedded image
                ->addPart('A new licence is allocated to a machine, <a href="kkk">the details</a> <h1>list below</h1>:' .
                        'image <img src="' . $message->embed(Swift_Image::fromPath('vcgr3/image/icon/action.jpg')) . '" alt="Image" />' . $description, 'text/html')

                //Optionally add any attachments
                ->attach(Swift_Attachment::fromPath('phpinfo.php'))
                //add dynamic pdf attachment
                ->attach(Swift_Attachment::newInstance($pdf_string, 'my.pdf', 'application/pdf'))
        ;
        //$transport = Swift_MailTransport::newInstance();   //simlest mail transport
        $transport = Swift_SmtpTransport::newInstance('mail.cosmosmetal.com.au', 2626)
                ->setUsername('vip@cosmosmetal.com.au')
                ->setPassword('-QFs*17V7nQ$');
        //Create the Mailer using your created Transport
        $mailer = Swift_Mailer::newInstance($transport);
        //Send the message
        if ($mailer->send($message, $failures)) {
            echo 'ok3!';
        } else {
            var_dump($failures);
        }
    }

    /**
     *
     * @param type $newsletter  db object
     * @param string $user_email  string
     * @return type 
     */
    public static function send_newsletter_to_user($newsletter, $user_email, $user_name) {
        $description = $newsletter->description;
        $arr_search = array('company_name');
        $arr_replace = array($user_name);
        $description = str_replace($arr_search, $arr_replace, $description);
        $message = Swift_Message::newInstance();
        //Give the message a subject
        $message->setSubject($newsletter->title)
                //Set the From address with an associative array
                ->setFrom(array(NEWSLETTER_MAIL_USER => 'James From ??list'))
                //Set the To addresses with an associative array
                ->setTo(array($user_email))
                //Give it a body for normal text 
                ->setBody($description, 'text/html');
        if (trim($newsletter->attachment) != '') {
            $message->attach(Swift_Attachment::fromPath(PHPUPLOADROOT . 'newsletter/' . $newsletter->attachment));
        }
        //App_Test::objectLog('$new_password',$user_email, __FILE__, __LINE__, __CLASS__, __METHOD__);
        //$transport = Swift_MailTransport::newInstance();   //simplest mail transport
        $transport = Swift_SmtpTransport::newInstance(MAIL_SERVER, MAIL_PORT)
                ->setUsername(NEWSLETTER_MAIL_USER)
                ->setPassword(NEWSLETTER_MAIL_PASSWORD);
        //Create the Mailer using your created Transport
        $mailer = Swift_Mailer::newInstance($transport);
        //Send the message

        if ($mailer->send($message, $failures)) {
            //App_Test::objectLog('$new_password','succ', __FILE__, __LINE__, __CLASS__, __METHOD__);
            return true;
        } else {
            //App_Test::objectLog('$new_password','fail', __FILE__, __LINE__, __CLASS__, __METHOD__);
            return false;
        }
    }

    public static function send_order_to_admin($arr) {
        //$message = "Line 1\nLine 2\nLine 3hhhhh";
        // In case any of our lines are larger than 70 characters, we should use wordwrap()
        //$message = wordwrap($message, 70);
        $pdf_string = $arr['pdf'];
        $description = "<q>The attachment is a new order, order reference number is {$arr['order_id']} .</q>";
        $message = Swift_Message::newInstance();
        $message->setSubject('New Order')
                ->setFrom(array(MAIL_USER => '??list'))
                ->setTo(array($arr['user_email']))
                ->setBody($description, 'text/html')
                ->attach(Swift_Attachment::newInstance($pdf_string, 'new_order.pdf', 'application/pdf'))
        ;
        return self::send_email($message);
    }

    public static function send_activation_link($user_arr) {
        $code = substr(md5($user_arr['id']), 1, 10) . substr(md5($user_arr['email']), 1, 10) . substr(md5($user_arr['user_name']), 1, 12); //32 digits
        $link = 'http://' . SERVER_NAME . "/user/activate/" . $user_arr['id'] . '/' . $code;
        $contact_link = 'http://' . SERVER_NAME . "message/contact";
        $description = "???" . $user_arr['user_name'] . ": ??! <br />
        ????????list???? <br />??? <a href='$link'>??</a>??????list??,  <br />
        ????????????????????????????list???  <br />" .
                $link .
                "<br />????????, ????<a href='$contact_link'>??</a>?????";

        $message = Swift_Message::newInstance();
        $message->setSubject('Activate your account')
                ->setFrom(array(MAIL_USER => '??list'))
                ->setTo(array($user_arr['email']))
                ->setBody($description, 'text/html');
        return self::send_email($message);
    }

    /**
     * send new password to customer's email box
     * @param object $user
     * @param string $new_password
     */
    public static function send_new_password($user, $new_password) {
        $description = '????list???????' . $new_password . "? ???????”????“????????????";
        $message = Swift_Message::newInstance();
        $message->setSubject('????list??????')
                ->setFrom(array(MAIL_USER => '??list'))
                ->setTo(array($user->email))
                ->setBody($description, 'text/html');
        return self::send_email($message);
    }

    /**
     * send contact us message to info email box
     * email might be phone number
     * @param type $arr 
     */
    public static function send_contact_us_email($arr) {
        $info_email = INFO_MAIL;
        $description = 'customer name: ' . $arr['sender_name'] . BR .
                'email: ' . $arr['sender_email'] . BR .
                'category:' . $arr['cat_name'] . BR .
                'title:' . $arr['title'] . BR .
                'message: ' . $arr['description'] . BR;

//        App_Test::objectLog('$description',$description . ADMIN_EMAIL, __FILE__, __LINE__, __CLASS__, __METHOD__);
        $message = Swift_Message::newInstance();
        $message->setSubject('????/??????')
                ->setFrom(array(MAIL_USER => '??list'))
                ->setTo(array($info_email))
                ->setBody($description, 'text/html');
        return self::send_email($message);
    }

    public static function send_string_to_admin($str) {
        
    }

}