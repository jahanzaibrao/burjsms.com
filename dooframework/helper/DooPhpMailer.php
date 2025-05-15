<?php
require 'vendor/autoload.php';
/* sample code




//send an email for test
        
        Doo::loadHelper("DooPhpMailer");
        $mail = DooPhpMailer::getMailObj();
        
        try{
            $mail->SMTPOptions = array(
                'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
            );
            $mail->SMTPDebug = 0;
            $mail->isSMTP(); 
            $mail->Host = 'smtp.gmail.com';  
            $mail->SMTPAuth = true;
            $mail->Username = 'support@cubelabs.in';
            $mail->Password = 'mypass'; 
            $mail->SMTPSecure = 'tls';  
            $mail->Port = 587; 
            
            $mail->setFrom('support@cubelabs.in');
            $mail->addAddress('saurabh.pandey@cubelabs.in');
            $mail->Subject  = 'First Test PHPMailer Message';
            $mail->Body = 'Hi! This is my first e-mail sent through PHPMailer.';
            
            //in case of html you can get the body from file in Controller
            $html = $this->view()->getRendered('mail/sample_email', $data); //$data has substitution variables
            $mail->isHTML(true);
            $mail->Body = $html;
            
            $mail->send();
            echo 'Message has been sent';
            
        } catch (Exception $e) {
            echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
        }
        
        
        //also you first have to add a task
        $data['content'] = 'Have a blast';
        $html = $this->view()->getRendered('mail/sample', $data);
        
        $list = array('saurabh.pandey@cubelabs.in','support@cubelabs.in');
        $obj = Doo::loadModel('ScEmailQueue', true);
        $obj->added_on = date(Doo::conf()->date_format_db);
        $obj->sender_email = 'enquiry@smppcube.com';
        $obj->sender_name = 'Anu K';
        $obj->recipient_list = serialize($list);
        $obj->email_sub = 'This is my first HTML test email';
        $obj->email_text = $html;
        $obj->status = 0;
        Doo::db()->insert($obj);
        echo 'done';exit;

*/


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class DooPhpMailer extends PHPMailer
{

    public static function getMailObj()
    {
        $objMail = new PHPMailer(true);
        return $objMail;
    }
}
