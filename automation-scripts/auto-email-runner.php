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


$currentPage = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

if ($_SERVER['REQUEST_METHOD'] == "GET" && strcmp(basename($currentPage), basename(__FILE__)) == 0) {
    header("Location: / ");
    exit();
}
require_once "../load_classes.php";

$membersController = new MembersController();
$autoEmails = $membersController->getAutoEmails();
if (!empty($autoEmails)) {
    $emailsController = new EmailsController();
    $totalMembers = $membersController->totalMemberByStatus(1) - 1;
    if ($totalMembers > 0) {
        foreach ($autoEmails as $autoEmailDetails) {
            $maxRecipent = 0;
            if ($autoEmailDetails["credits"] > $totalMembers) {
                $maxRecipent = $totalMembers;
            } else if ($autoEmailDetails["credits"] < $totalMembers) {
                $maxRecipent = $autoEmailDetails["credits"];
            } else if ($totalMembers == $autoEmailDetails["credits"]) {
                $maxRecipent = $autoEmailDetails["credits"];
            }
            $membersController->deductMemberCredits($autoEmailDetails["username"], $maxRecipent);
            $emailsController->insertEmail(
                array(
                    "sender_username" => $autoEmailDetails["username"],
                    "email_subject" => $autoEmailDetails["auto_email_subject"],
                    "email_body" => $autoEmailDetails["auto_email_body"],
                    "website_link" => base64_encode($autoEmailDetails["auto_email_website"]),
                    "sending_time" => time(),
                    "total_sent" => 0,
                    "creation_timestamp" => time(),
                    "total_clicks" => 0,
                    "credits_assign" => $maxRecipent,
                    "credit_key" => md5(uniqid("NTKS")),
                    "email_status" => 0,
                    "suspend_status" => 0,
                )
            );
        }
    }
}
