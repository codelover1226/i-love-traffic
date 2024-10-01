<?php
/*
 *
 *
 *          Author          :   Noman Prodhan
 *          Email           :   hello@nomantheking.com
 *          Websites        :   www.nomantheking.com    www.nomanprodhan.com    www.nstechvalley.com
 *
 *
 */

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

use Emailqueue\emailqueue_inject;

define("EMAILQUEUE_DIR", __DIR__."/../emailqueue/"); // Set this to your Emailqueue's installation directory.
include_once EMAILQUEUE_DIR."config/application.config.inc.php"; // Include emailqueue configuration.
include_once EMAILQUEUE_DIR."config/db.config.inc.php"; // Include Emailqueue's database connection configuration.
include_once EMAILQUEUE_DIR."scripts/emailqueue_inject.class.php"; // Include Emailqueue's emailqueue_inject class.
$emailqueue_inject = new emailqueue_inject(EMAILQUEUE_DB_HOST, EMAILQUEUE_DB_UID, EMAILQUEUE_DB_PWD, EMAILQUEUE_DB_DATABASE);

$currentPage = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

if ($_SERVER['REQUEST_METHOD'] == "GET" && strcmp(basename($currentPage), basename(__FILE__)) == 0) {
    // header("Location: / ");
    // exit();
}
require_once "../load_classes.php";
require_once "../configs/config.php";
require_once "../vendor/autoload.php";

$massMailerController = new MassMailController();
$email = $massMailerController->getQueuedEmail();
if (!empty($email)) {
    $membersController = new MembersController();
    $massMailerController->updateMail($email["id"], array(
        "status" => 1
    ));
    if ($email["membership"] == 0) {
        $memberList = $membersController->getAllMembers();
    } else {
        $memberList = $membersController->getMembersByMembership($email["membership"]);
    }

    if (!empty($memberList)) {
        $siteSettingsController = new SiteSettingsController();
        $siteSettings = $siteSettingsController->getSettings();
        // $mail = new PHPMailer(true);
        // $mail->isSMTP();
        // $mail->Host = SMTP_HOST;
        // $mail->SMTPAuth = true;
        // $mail->SMTPSecure = SMTP_PROTOCOL;
        // $mail->SMTPKeepAlive = true;
        // $mail->Port = SMTP_PORT;
        // $mail->Username = SMTP_USER;
        // $mail->Password = SMTP_PASS;
        // $mail->setFrom($siteSettings["admin_email"], $siteSettings["site_title"]);
        // $mail->addReplyTo($siteSettings["admin_email"], $siteSettings["site_title"]);

        foreach ($memberList as $memberDetails) {
            $emailSubject = htmlspecialchars_decode(base64_decode($email["email_subject"]));
            $emailSubject = str_ireplace("{FIRSTNAME}", $memberDetails["first_name"], $emailSubject);
            $emailSubject = str_ireplace("{LASTNAME}", $memberDetails["last_name"], $emailSubject);
            $emailBody = htmlspecialchars_decode(base64_decode($email["email_body"]));
            $emailBody = str_ireplace("{FIRSTNAME}", $memberDetails["first_name"], $emailBody);
            $emailBody = str_ireplace("{LASTNAME}", $memberDetails["last_name"], $emailBody);

            // $mail->Subject = $emailSubject;
            // $mail->msgHTML($emailBody);
            // try {
            //     $mail->addAddress($memberDetails["email"], $memberDetails["first_name"] . " " . $memberDetails["last_name"]);
            // } catch (Exception $e) {
            //     continue;
            // }
            // try {
            //     $mail->send();
            // } catch (Exception $e) {
            //     $mail->getSMTPInstance()->reset();
            // }
            // $mail->clearAddresses();
            try {
                // Call the emailqueue_inject::inject method to inject an email
                $result = $emailqueue_inject->inject([
                    "foreign_id_a" => false, // Optional, an id number for your internal records. e.g. Your internal id of the user who has sent this email.
                    "foreign_id_b" => false, // Optional, a secondary id number for your internal records.
                    "priority" => 10, // The priority of this email in relation to others: The lower the priority, the sooner it will be sent. e.g. An email with priority 10 will be sent first even if one thousand emails with priority 11 have been injected before. Defaults to 10
                    "is_immediate" => true, // Set it to true to queue this email to be delivered as soon as possible. (doesn't overrides priority setting). Defaults to true.
                    "is_send_now" => false, // Set it to true to make this email be sent right now, without waiting for the next delivery call. This effectively gets rid of the queueing capabilities of emailqueue and can delay the execution of your script a little while the SMTP connection is done. Use it in those cases where you don't want your users to wait not even a minute to receive your message. Defaults to false.
                    "date_queued" => false, // If specified, this message will be sent only when the given timestamp has been reached. Leave it to false to send the message as soon as possible. (doesn't overrides priority setting)
                    "is_html" => true, // Whether the given "content" parameter contains HTML or not. Defaults to true.	
                    "from" => $siteSettings["admin_email"], // The sender email address
                    "from_name" => $siteSettings["site_title"], // The sender name
                    "to" => $memberDetails["email"], // The addressee email address
                    "replyto" => $siteSettings["admin_email"], // The email address where replies to this message will be sent by default
                    "replyto_name" => $siteSettings["site_title"], // The name where replies to this message will be sent by default
                    "sender" => $siteSettings["admin_email"],
                    "subject" => $emailSubject, // The email subject
                    "content" => $emailBody, // The email content. Can contain HTML (set is_html parameter to true if so).
                    "content_nonhtml" => false, // The plain text-only content for clients not supporting HTML emails (quite rare nowadays). If set to false, a text-only version of the given content will be automatically generated.
                    "list_unsubscribe_url" => false, // Optional. Specify the URL where users can unsubscribe from your mailing list. Some email clients will show this URL as an option to the user, and it's likely to be considered by many SPAM filters as a good signal, so it's really recommended.
                    "attachments" => false,
                    "is_embed_images" => false, 
                ]);
            } catch (Exception $e) {
                echo "Emailqueue error: ".$e->getMessage()."<br>";
            }
            
            if($result)
                echo "Message correctly injected.<br>";
            else
                echo "Error while queing message.<br>";
         }
    }
}
