<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$currentPage = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

if ($_SERVER['REQUEST_METHOD'] == "GET" && strcmp(basename($currentPage), basename(__FILE__)) == 0) {
    header("Location: / ");
    exit();
}
require_once "../load_classes.php";
require_once "../configs/config.php";
require_once "../vendor/autoload.php";

$membersController = new MembersController();
$membershipEndList = $membersController->getMembershipEndList();
if (!empty($membershipEndList)) {
    $siteSettingsController = new SiteSettingsController();
    $siteSettings = $siteSettingsController->getSettings();
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host = SMTP_HOST;
    $mail->SMTPAuth = true;
    $mail->SMTPSecure = SMTP_PROTOCOL;
    $mail->SMTPKeepAlive = true;
    $mail->Port = SMTP_PORT;
    $mail->Username = SMTP_USER;
    $mail->Password = SMTP_PASS;
    $mail->setFrom($siteSettings["admin_email"], $siteSettings["site_title"] . " - Admin");
    $mail->addReplyTo($siteSettings["admin_email"], $siteSettings["site_title"] . " - Admin");

    foreach ($membershipEndList as $memberDetails) {
        $membersController->updateMemberProfileData($memberDetails["username"], array(
            "membership" => 1,
            "membership_end_time" => 'lifetime',
        ));
        $emailSubject = "Membership End Notification - ";
        $emailSubject .= date("d M, Y");
        $emailBody = "Dear ";
        $emailBody .= $memberDetails["first_name"] . " " . $memberDetails["last_name"];
        $emailBody .= "<br>";
        $emailBody .= "Your membership has been ended today. Now you are in free membership.<br>";
        $emailBody .= "<br>";
        $emailBody .= "Best Regards<br>";
        $emailBody .= $siteSettings["site_title"];

        $mail->Subject = $emailSubject;
        $mail->msgHTML($emailBody);
        try {
            $mail->addAddress($memberDetails["email"], $memberDetails["first_name"] . " " . $memberDetails["last_name"]);
        } catch (Exception $e) {
            continue;
        }
        try {
            $mail->send();
        } catch (Exception $e) {
            $mail->getSMTPInstance()->reset();
        }
        $mail->clearAddresses();
    }
}

$vacationEndList = $membersController->vacationEndMemberList();
if (!empty($vacationEndList)) {
    foreach ($vacationEndList as $memberDetails) {
        try {
            $membersController->updateMemberProfileData($memberDetails["username"], array(
                "account_status" => 1
            ));
        } catch (Exception $e) {
        }
    }
}

$tmpTrxController = new TmpTransactionsController();
$tmpTrxController->deleteOldHistory();

$emailClicksController = new EmailClicksController();
$emailClicksController->deleteOldHistory();
