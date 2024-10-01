<?php

$currentPage = (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] === "on" ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
if ($_SERVER["REQUEST_METHOD"] == "GET" && strcmp(basename($currentPage), basename(__FILE__)) == 0) {
    http_response_code(404);
    die("");
}
require_once "themes/default/member-area/incs/header.inc.php";
$flag = $affiliateMessagingController->sendAffiliateMessage($userInfo);
$composeFlag = false;
if ($userInfo["membership"] != 1) {
    if ($userInfo["membership_end_time"] == "Lifetime") {
        $composeFlag = true;
    } else if (is_numeric($userInfo["membership_end_time"]) && $userInfo["membership_end_time"] >= time()) {
        $composeFlag = true;
    }
}
$membersController->generateUserCSRFToken();
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
                <div class="col-lg-12">
                    <div class="card border border-primary">
                        <div class="card-header bg-transparent border-primary">
                            <h5 class="my-0 text-primary"><i class="mdi mdi-bullseye-arrow me-3"></i>Compose Affiliate Message</h5>
                        </div>
                        <div class="card-body">

                            <?php if ($composeFlag) : ?>
                                <p class="card-text">
                                <form action="" method="POST" accept-charset="utf-8">
                                    <input type="hidden" name="csrf_token" value="<?= $membersController->getUserCSRFToken() ?>"">
                                    <div class=" form-group">
                                    <label>Recipient Username</label>
                                    <input type="text" name="receiver_username" class="form-control" placeholder="Enter recipient username (Must be your upline/downline member)">
                                    <label>Subject</label>
                                    <input type="text" name="message_subject" class="form-control" placeholder="Message subject">
                        </div><br>
                        <div class="form-group">
                            <label>Message</label>
                            <textarea rows="10" name="message_body" class="form-control" placeholder="Your message"></textarea>
                        </div><br>
                        <div class="form-group">
                            <button class="btn btn-primary">Send</button>
                        </div>

                        </form>
                        </p>
                    <?php else : ?>
                        <div class="alert alert-primary" role="alert">
                            Please upgrade/renew your account to a premium membership to send messages to your upline/downline members.
                        </div>
                    <?php endif; ?>
                    </div>
                </div>
                <?php require_once "themes/default/member-area/incs/footer-ads.inc.php"; ?>

            </div>
        </div>

    </div>


    <?php require_once "themes/default/member-area/incs/footer.inc.php"; ?>