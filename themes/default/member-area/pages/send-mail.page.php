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
 error_log("I am send mail page");
$currentPage = (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] === "on" ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
if ($_SERVER["REQUEST_METHOD"] == "GET" && strcmp(basename($currentPage), basename(__FILE__)) == 0) {
    http_response_code(404);
    die("");
}
require_once "themes/default/member-area/incs/header.inc.php";
$totalActiveMembers = $membersController->totalMemberByStatus(1);
$membershipMaxRecipient = $userInfo["max_recipient"];
$maxRecipient = $totalActiveMembers > $membershipMaxRecipient ? $membershipMaxRecipient : $totalActiveMembers;
if (isset($_POST["add_queue"])) {
    $flag = $emailsController->addMailInQueue($userInfo, $maxRecipient);
} else if (!isset($_POST["add_queue"]) && isset($_POST["email_subject"])) {
    $flag = $emailsController->checkNewEmailWebsite($userInfo, $maxRecipient);
} else {
    $membersController->generateUserCSRFToken();
}
$email_subject = "";
$email_body = "";
$website_link = "";
if (
    isset($_GET["draft"]) &&
    !empty($_GET["draft"]) &&
    is_numeric($_GET["draft"]) &&
    $_GET["draft"] > 0
) {
    $draftInfo = $emailDraftsController->getDraftInfo($_GET["draft"]);
    if (!empty($draftInfo) && $draftInfo["username"] == "$username") {
        $email_subject = $draftInfo["email_subject"];
        $email_body = $draftInfo["email_body"];
        $website_link = $draftInfo["website_link"];
    }
}
?>


<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <?php require_once "themes/default/member-area/incs/sidebar-ads.inc.php"; ?>
            <div class="col-xl-9">
                <div class="alert alert-info">You can send <?= $userInfo["email_sending_limit"] ?> email(s) daily. </div>
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
                            <p><span style="color: #fa0909;">Do not use subject lines that are misleading or look as though they come from site admin.</span></p>
                            <p><span style="color: #fa0909;">Do not use currency signs ($ etc) in your subject line.</span></p>
                            <h5 class="my-0 text-primary"><i class="mdi mdi-bullseye-arrow me-3"></i>Send Email</h5>
                        </div>
                        <div class="card-body">
                            <p class="card-text">
                                <?php if ($userInfo["account_status"] == 3) : ?>
                            <div class="alert alert-danger">You have unsubscribed your account. You need to subscribe to our emails to send new email.</div>
                            <a href="dashboard.php?action=email-subscription"><button class="btn btn-info">Subscribe</button></a>
                        <?php elseif ($userInfo["account_status"] == 4) : ?>
                            <div class="alert alert-danger">Your account is in vacation mode. You need to disable the vacation mode to send new email.</div>
                            <a href="dashboard.php?action=vacation"><button class="btn btn-info">Vacation Settings</button></a>
                        <?php elseif ($userInfo["email_sending_limit"] <= $emailsController->totalUserEmailsToday($username)) : ?>
                            <div class="alert alert-danger">You have reached your email sending limit.</div>
                            <a href="store.php"><button class="btn btn-info">Upgrade membership</button></a>
                        <?php elseif ($userInfo["account_status"] == 1) : ?>
                            <form id="emailForm" action="" method="POST" accept-charset="utf-8">
                                <input type="hidden" name="csrf_token" value="<?= $membersController->getUserCSRFToken() ?>"">
                                    <div class=" form-group"><br>
                                <label>Email Subject</label>
                                <input type="text" name="email_subject" class="form-control" placeholder="Your email subject" value="<?= $email_subject ?>">
                        </div><br>
                        <div class="form-group">
                            <label>Email Body</label>
                            <textarea style="font-family: 'Outfit', 'Noto Emoji';" type="text" name="email_body" id="email_body" class="form-control" placeholder="Email body"><?= $email_body ?></textarea>
                        </div><br>
                        <div class="form-group">
                            <label>Target Link</label>
                            <input type="url" name="website_link" class="form-control" placeholder="Target link" value="<?= $website_link ?>">
                        </div><br>
                        <div class="form-group">
                        <p class="fw-medium text-muted mb-0" style="font-size: 12px;">Available Email Credits:
                                            <?= $userInfo["credits"] ?></p>
                            <label>Mail to Active Members (Max : <?= $maxRecipient - 1 ?>)</label>
                            <input type="number" name="credits_assign" class="form-control" placeholder="Credits">
                        </div><br>
                        <div class="alert alert-info">Macros<br>Member's First Name {FIRSTNAME}<br>Member's Last Name {LASTNAME}</div>
                        <div class="form-group">
                            <button class="btn btn-info">Next</button>
                        </div>
                        </form>
                    <?php endif; ?>
                    </p>
                    </div>
                </div>

            </div>

            <div class="row">
                <div class="col-lg-6"><?= $bannerAdController->getBannerAd() ?></div>
                <div class="col-lg-6"><?= $bannerAdController->getBannerAd() ?></div>
            </div>
        </div>

    </div>
    <?php require_once "themes/default/member-area/incs/footer.inc.php"; ?>
    <?php require_once "themes/default/member-area/incs/footer.inc.php"; ?>
    <?php require_once "themes/default/member-area/incs/footer.inc.php"; ?>