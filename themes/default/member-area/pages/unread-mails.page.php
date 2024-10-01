<?php

$currentPage = (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] === "on" ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
if ($_SERVER["REQUEST_METHOD"] == "GET" && strcmp(basename($currentPage), basename(__FILE__)) == 0) {
    http_response_code(404);
    die("");
}
require_once "themes/default/member-area/incs/header.inc.php";
$unreadMails = new UnreadMailsController();
$unreadMailList = $unreadMails->unreadMailsList($userInfo["username"]);
?>

<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <?php require_once "themes/default/member-area/incs/sidebar-ads.inc.php"; ?>
            <div class="col-xl-9">
                <?php if (isset($flag) && isset($flag["success"])) : ?>
                <?php if ($flag["success"] == true) : ?>
                <div class="alert alert-success"><?= $flag["message"] ?></div>
                <?php else : ?>
                <div class="alert alert-danger"><?= $flag["message"] ?></div>
                <?php endif; ?>
                <?php endif; ?>
                <div class="alert alert-info">Total Unread Mails : <?= $unreadMails->totalUnreadMails($username) ?>
                </div>
                <div class="col-lg-12">
                    <div class="card border border-primary">
                        <table class="table table-striped">
                            <thead>
                                <td>Subject</td>
                                <td>Sending Date</td>
                                <td></td>
                            </thead>
                            <?php if (!empty($unreadMailList)) : ?>
                            <?php foreach ($unreadMailList as $unreadMailDetails) : ?>
                            <tr>
                                <td><?= str_ireplace("{LASTNAME}", $userInfo["last_name"], str_ireplace("{FIRSTNAME}", $userInfo["first_name"], base64_decode($unreadMailDetails["email_subject"]))) ?>
                                </td>
                                <td><?= date("d M, Y H:i:s", $unreadMailDetails["sending_time"]) ?></td>
                                <td><a href="<?= $siteSettingsData['installation_url'] . 'email-credits.php?type=email&id=' . $unreadMailDetails['credit_key'] . '&username=' . $username ?>"
                                        target="_blank">View</a></td>
                            </tr>
                            <?php endforeach; ?>
                            <?php endif; ?>
                        </table>
                    </div>
                    <?= $unreadMails->unreadMailsPagination($username) ?>
                </div>

                <?php require_once "themes/default/member-area/incs/footer-ads.inc.php"; ?>
            </div>
        </div>

    </div>

    <?php require_once "themes/default/member-area/incs/footer.inc.php"; ?>