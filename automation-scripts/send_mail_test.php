<?php

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
    
    
    require_once "../load_classes.php";
    require_once "../configs/config.php";
    require_once "../vendor/autoload.php";
    error_reporting(E_ALL);
    $membersController = new MembersController();
    $membershipEndList = $membersController->getMembershipEndList();


    $siteSettingsController = new SiteSettingsController();
    $siteSettings = $siteSettingsController->getSettings();
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->SMTPDebug = true;
    $mail->Host = SMTP_HOST;
    $mail->SMTPAuth = true;
    $mail->SMTPSecure = SMTP_PROTOCOL;
    $mail->SMTPKeepAlive = true;
    $mail->Port = SMTP_PORT;
    $mail->Username = SMTP_USER;
    $mail->Password = SMTP_PASS;
    $mail->setFrom($siteSettings["admin_email"], $siteSettings["site_title"] . " - Admin");
    $mail->addReplyTo($siteSettings["admin_email"], $siteSettings["site_title"] . " - Admin");

    $emailSubject = "Membership End Notification - ";
    $emailSubject .= date("d M, Y");
    $emailBody = "Dear ";
    $emailBody .= "Chetan Ghadiya";
    $emailBody .= "<br>";
    $emailBody .= "Your membership has been ended today. Now you are in free membership.<br>";
    $emailBody .= "<br>";
    $emailBody .= "Best Regards<br>";
    $emailBody .= $siteSettings["site_title"];

    $mail->Subject = $emailSubject;
    $mail->msgHTML($emailBody);
    $mail->addAddress('crghadiya@gmail.com', "Chetan Ghadiya");
    echo 'TEST';
    try {
        echo $mail->send();
    } catch (Exception $e) {
        $mail->getSMTPInstance()->reset();
    }
    $mail->clearAddresses();
