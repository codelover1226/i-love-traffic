<?php

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
    header("Location: / ");
    exit();
}
require_once "../load_classes.php";
require_once "../configs/config.php";
require_once "../vendor/autoload.php";
$emailsController = new EmailsController();
$membersController = new MembersController();
$emailQueue = $emailsController->getScheduleEmailsForSending();
$websiteSettingsController = new SiteSettingsController();
$siteSettingsData = $websiteSettingsController->getSettings();
if (!empty($emailQueue)) {
    $membersList = $membersController->getAllMembers();
    if (!empty($membersList)) {
        foreach ($emailQueue as $emailDetails) {
            $emailsController->updateEmailStatus($emailDetails["id"], 1);
        }
        $siteSettingsController = new SiteSettingsController();
        $siteSettings = $siteSettingsController->getSettings();
        $otherSettingsController = new OtherSettingsController();
        $mailerFrom = $otherSettingsController->getSettingsValue("mailer_from");
        $mailerFromName = $otherSettingsController->getSettingsValue("mailer_from_name");
        // $mail = new PHPMailer(true);
        // $mail->isSMTP();
        // $mail->Host = SMTP_HOST;
        // $mail->SMTPAuth = true;
        // $mail->SMTPSecure = SMTP_PROTOCOL;
        // $mail->SMTPKeepAlive = true;
        // $mail->Port = SMTP_PORT;
        // $mail->Username = SMTP_USER;
        // $mail->Password = SMTP_PASS;
        // $mail->CharSet = 'UTF-8';
        // $mail->setFrom($mailerFrom["settings_value"], $mailerFromName["settings_value"]);
        // $mail->addReplyTo($mailerFrom["settings_value"], $mailerFromName["settings_value"]);
        // $mail->AddCustomHeader('Precedence', 'bulk');
	    // $mail->AddCustomHeader("List-Id: $mailerFromName Member Mail <$mailerFrom>");


        foreach ($emailQueue as $emailDetails) {
            $todayUserEmails = $emailsController->totalUserEmailsToday($emailDetails["sender_username"]);
            $senderDetails = $membersController->getUserDetails($emailDetails["sender_username"]);
            if ($todayUserEmails >= $senderDetails["email_sending_limit"]) {
                $membersController->addEmailCredits($emailDetails["sender_username"], $emailDetails["credits_assign"]);
                $emailsController->updateEmailStatus($emailDetails["id"], 4);
                continue;
            }
            $emailCounter = 0;
            $remainCredits = 0;
            $totalMembers = count($membersList);
            if ($totalMembers == $emailDetails["credits_assign"]) {
                $maxRecipent = $totalMembers;
            } else if ($emailDetails["credits_assign"] > $totalMembers) {
                $maxRecipent = $totalMembers;
            } else if ($emailDetails["credits_assign"] < $totalMembers) {
                $maxRecipent = $emailDetails["credits_assign"];
            }
            foreach ($membersList as $memberDetails) {
                if ($emailDetails["sender_username"] != $memberDetails["username"]) {
                    $emailSubject = base64_decode($emailDetails["email_subject"]);
                    $emailSubject = str_ireplace("{FIRSTNAME}", $memberDetails["first_name"], $emailSubject);
                    $emailSubject = str_ireplace("{LASTNAME}", $memberDetails["last_name"], $emailSubject);
                    $emailBody = base64_decode($emailDetails["email_body"]);
                    $emailBody = htmlspecialchars_decode($emailBody);
                    $emailBody = str_ireplace("{FIRSTNAME}", $memberDetails["first_name"], $emailBody);
                    $emailBody = str_ireplace("{LASTNAME}", $memberDetails["last_name"], $emailBody);
                    $emailBody .= "<br><br>";
                    $emailBody .= "Please click on the link below to get credit<br>";
                    $emailBody .= "<a href='{$siteSettings['installation_url']}/email-credits.php?type=email&id={$emailDetails['credit_key']}&username={$memberDetails['username']}'>";
                    $emailBody .= "{$siteSettings['installation_url']}/email-credits.php?type=email&id={$emailDetails['credit_key']}&username={$memberDetails['username']}</a>";
                    $emailBody .= "<br>";
                    $emailBody .= "<br>";
                    $emailBody .= "<hr>";
                    $emailBody .= "<br>";
                    $emailBody .= "<br>";
                    $emailBody .= "<br>";
                    $emailBody .= "Is there anything wrong in the email? Click on the link below to report it.<br>";
                    $emailBody .= "<a href='{$siteSettings['installation_url']}/email-report.php?id={$emailDetails['id']}&report-key={$memberDetails['email_report_key']}&username={$memberDetails['username']}'>";
                    $emailBody .= "{$siteSettings['installation_url']}/email-report.php?id={$emailDetails['id']}&report-key={$memberDetails['email_report_key']}&username={$memberDetails['username']}</a>";
                    $emailBody .= "<br><br>";
                    $emailBody .= "<br><br>";
                    $emailBody .= "Please click the following link to unsubscribe from our emails.<br>";
                    $emailBody .= "<a href='{$siteSettings['installation_url']}/unsubscribe.php?unsubscribe={$memberDetails['account_activation_key']}&username={$memberDetails['username']}'>{$siteSettings['installation_url']}/unsubscribe.php?unsubscribe={$memberDetails['account_activation_key']}&username={$memberDetails['username']}</a>";
                    $emailBody .= "<br>";
                    $emailBody .= "<br>";
                    $emailBody .= "©" . date("Y");
                    $emailBody .= " " . $siteSettings['site_title'];
                    $emailBody .= " | Powered By i-lovetraffic.online 1315 Piedmont Rd #32896, San Jose, CA 95132";
                    
                    $profileSection = '<table cellpadding="0" cellspacing="0" width="600" border="0" style="min-width: 600px; background-position-x: right; background:url(https://i-lovetraffic.online/images/1.png); width: 100%;" dir="ltr" class="st-Copy st-Copy--caption st-Width st-Width--mobile">
                            <tbody>
                                <tr>
                                    <td height="156" width="252" valign="bottom" align="right" style="background-color: #f9f9f900; border: 0; border-collapse: collapse; margin: 0; padding: 0; -webkit-font-smoothing: antialiased; -moz-osx-font-smoothing: grayscale; font-size: 0; line-height: 0px; mso-line-height-rule: exactly; background-size: 100% 100%; border-top-left-radius: 5px;" class="Header-left Target"><a rel="noopener" href="https://i-lovetraffic.online" style="-webkit-font-smoothing: antialiased; -moz-osx-font-smoothing: grayscale; outline: 0; text-decoration: none;"> <img height="156" width="252" alt="" src="https://stripe-images.s3.amazonaws.com/notifications/hosted/20180110/Header/Left.png" style="display: none; border: 0; line-height: 100%; width: 100%;"> </a></td>
                                    <td height="156" valign="center" align="center" style="background-color: #f9f9f900; border: 0; border-collapse: collapse; margin: 0; padding: 0; -webkit-font-smoothing: antialiased; -moz-osx-font-smoothing: grayscale; font-size: 0; line-height: 0px; mso-line-height-rule: exactly; background-size: 100% 100%; width: 96px !important;" class="Header-icon Target"><a rel="noopener" href="https://i-lovetraffic.online" style="-webkit-font-smoothing: antialiased; -moz-osx-font-smoothing: grayscale; outline: 0; text-decoration: none;"> <img height="156" width="96" alt="" src='.$membersController->gravatar($senderDetails['email'], $siteSettingsData['installation_url']).' style="display: block; border: 0; height: 96px; border-radius: 100%"> </a></td>
                                    <td height="156" width="252" valign="bottom" align="left" style="background-color: #f9f9f900; border: 0; border-collapse: collapse; margin: 0; padding: 0; -webkit-font-smoothing: antialiased; -moz-osx-font-smoothing: grayscale; font-size: 0; line-height: 0px; mso-line-height-rule: exactly; background-size: 100% 100%; border-top-right-radius: 5px;" class="Header-right Target"><a rel="noopener" href="https://i-lovetraffic.online" style="-webkit-font-smoothing: antialiased; -moz-osx-font-smoothing: grayscale; outline: 0; text-decoration: none;"> <img height="156" width="252" alt="" src="https://stripe-images.s3.amazonaws.com/notifications/hosted/20180110/Header/Right.png" style="display: none; border: 0; line-height: 100%; width: 100%;"> </a></td>
                                </tr>
                            </tbody>
                        </table>
                        <table cellpadding="0" cellspacing="0" width="600" border="0" style="min-width: 600px; background-color: #ffffff; width: 100%;" class="st-Copy st-Copy--caption st-Width st-Width--mobile">
                            <tbody>
                                <tr>
                                    <td align="center" style="border: 0; border-collapse: collapse; margin: 0; padding: 0; -webkit-font-smoothing: antialiased; -moz-osx-font-smoothing: grayscale; width: 472px; font-family: -apple-system, BlinkMacSystemFont, \'Segoe UI\', Roboto, \'Helvetica Neue\', Ubuntu, sans-serif; mso-line-height-rule: exactly; vertical-align: middle; color: #32325d; font-size: 24px; line-height: 32px;" class="Content Title-copy Font Font--title">
                                        '.$senderDetails['first_name'].' '.$senderDetails['last_name'].'
                                    </td>
                                </tr>
                                <tr>
                                    <td height="12" colspan="3" style="border: 0; margin: 0; padding: 0; font-size: 1px; line-height: 1px; mso-line-height-rule: exactly;" class="st-Spacer st-Spacer--stacked">
                                        <div class="st-Spacer st-Spacer--filler">&nbsp;</div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>';
                    $emailBody = $profileSection.$emailBody;
                    // $mail->AddCustomHeader("List-Unsubscribe: <{$siteSettings['installation_url']}/unsubscribe.php?unsubscribe={$memberDetails['account_activation_key']}&username={$memberDetails['username']}>");
	                // $mail->AddCustomHeader("List-Unsubscribe-Post: List-Unsubscribe=One-Click");

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
                    // $mail->clearCustomHeaders();	
                    $emailCounter++;
                    if ($maxRecipent == $emailCounter) {
                        break;
                    }
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
                            "from" => $mailerFrom["settings_value"], // The sender email address
                            "from_name" => $mailerFromName["settings_value"], // The sender name
                            "to" => $memberDetails["email"], // The addressee email address
                            "replyto" => $mailerFrom["settings_value"], // The email address where replies to this message will be sent by default
                            "replyto_name" => $mailerFromName["settings_value"], // The name where replies to this message will be sent by default
                            "sender" => $mailerFrom["settings_value"],
                            "subject" => $emailSubject, // The email subject
                            "content" => $emailBody, // The email content. Can contain HTML (set is_html parameter to true if so).
                            "content_nonhtml" => false, // The plain text-only content for clients not supporting HTML emails (quite rare nowadays). If set to false, a text-only version of the given content will be automatically generated.
                            "list_unsubscribe_url" => false, // Optional. Specify the URL where users can unsubscribe from your mailing list. Some email clients will show this URL as an option to the user, and it's likely to be considered by many SPAM filters as a good signal, so it's really recommended.
                            "attachments" => false,
                            "is_embed_images" => false, // When set to true, Emailqueue will find all the <img ... /> tags in your provided HTML code on the "content" parameter and convert them into embedded images that are attached to the email itself instead of being referenced by URL. This might cause email clients to show the email straightaway without the user having to accept manually to load the images. Setting this option to true will greatly increase the bandwidth usage of your SMTP server, since each message will contain hard copies of all embedded messages. 10k emails with 300Kbs worth of images each means around 3Gb. of data to be transferred!
                            "custom_headers" => [
                                "Precedence" => "bulk",
                                "List-Id" => "$mailerFromName Member Mail <$mailerFrom>",
                                "List-Unsubscribe" => "<{$siteSettings['installation_url']}/unsubscribe.php?unsubscribe={$memberDetails['account_activation_key']}&username={$memberDetails['username']}>",
                                "List-Unsubscribe-Post" => "List-Unsubscribe=One-Click"                                
                            ] // Optional. A hash array of additional headers where each key is the header name and each value is its value.
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
            $remainCredits = $emailDetails["credits_assign"] - $emailCounter;
            if ($remainCredits > 0) {
                $membersController->addEmailCredits($emailDetails["sender_username"], $remainCredits);
            }
            $emailsController->updateEmailData($emailDetails["id"], array(
                "total_sent" => $emailCounter,
                "email_status" => 2
            ));
        }
    }
}
