<?php

$currentPage = (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] === "on" ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
if ($_SERVER["REQUEST_METHOD"] == "GET" && strcmp(basename($currentPage), basename(__FILE__)) == 0) {
    http_response_code(404);
    die("");
}
require_once "themes/default/member-area/incs/header.inc.php";
$totalActiveMembers = $membersController->totalMemberByStatus(1);
$membershipMaxRecipient = $userInfo["max_recipient"];
$maxRecipient = $totalActiveMembers > $membershipMaxRecipient ? $membershipMaxRecipient : $totalActiveMembers;
if (isset($_POST["add_schedule"])) {
    $flag = $emailsController->schedulEmail($userInfo, $maxRecipient);
} else if (!isset($_POST["add_schedule"]) && isset($_POST["email_subject"])) {
    $flag = $emailsController->checkNewSchedulEmailWebsite($userInfo, $maxRecipient);
} else {
    $membersController->generateUserCSRFToken();
}
?>
<style>
    .white-text {
        color: #ffffff !important;
    }

    .ck .ck-reset .ck-editor .ck-rounded-corners {
        max-width: 700px !important;
    }

    .ck-editor__editable {
        min-height: 400px !important;
        color: #000000;
    }

    .ck.ck-editor__main>.ck-editor__editable {
        border-radius: 0
    }

    .ck.ck-editor__main>.ck-editor__editable:not(.ck-focused) {
        /* border-color: #292c40 !important; */
    }

    pre {
        box-sizing: border-box;
        width: 700px;
        padding: 0;
        margin: 0;
        overflow: auto;
        overflow-y: auto;
        overflow-x: auto;
        max-height: 500px;
        font-size: 12px;
        line-height: 20px;
        background: #efefef;
        border: 1px solid #08ffec;
        background: #083338;
        padding: 10px;
        color: #02f681;
    }

    .ck-content pre {
        padding: 1em;
        color: #ffffff !important;
        /* background: hsla(0, 0%, 78%, .3);
            border: 1px solid #02f681;
            border-radius: 2px;
            text-align: left;
            direction: ltr;
            tab-size: 4;
            white-space: pre-wrap;
            font-style: normal;
            min-width: 200px; */
    }
</style>

<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <?php require_once "themes/default/member-area/incs/sidebar-ads.inc.php"; ?>
            <div class="col-xl-9">
                <div class="alert alert-info"><?= "Server Time : " . date("d M, Y H:i:s") ?></div>
                <div class="alert alert-info">You can send <?= $userInfo["email_sending_limit"] ?> email(s) daily. Today you sent total <?= $emailsController->totalUserEmailsToday($username) ?> email(s)</div>
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
                            <h5 class="my-0 text-primary"><i class="mdi mdi-bullseye-arrow me-3"></i>Schedule An Email</h5>
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
                            <form action="" method="POST" accept-charset="utf-8">
                                <input type="hidden" name="csrf_token" value="<?= $membersController->getUserCSRFToken() ?>"">
                                    <div class=" form-group"><br>
                                <label>Email Subject</label>
                                <input type="text" name="email_subject" class="form-control" placeholder="Your email subject">
                        </div><br>
                        <div class="form-group">
                            <label>Email Body</label>
                            <textarea type="text" name="email_body" id="email_body" class="form-control" placeholder="Email body"></textarea>
                        </div><br>
                        <div class="form-group">
                            <label>Target Link</label>
                            <input type="url" name="website_link" class="form-control" placeholder="Target link">
                        </div><br>
                        <div class="form-group">
                            <label>Credits (Max : <?= $maxRecipient - 1 ?>)</label>
                            <input type="number" name="credits_assign" class="form-control" placeholder="Credits">
                        </div><br>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-lg-6">
                                    <label>Date</label>
                                    <input type="date" name="schedule_date" class="form-control" placeholder="Date">
                                </div>
                                <div class="col-lg-3">
                                    <label>Time (Hour)</label>
                                    <select name="schedule_hour" class="form-control" placeholder="Schedule hour">
                                        <?php for ($i = 0; $i <= 23; $i++) : ?>
                                            <option value="<?= $i ?>"><?= sprintf("%02d", $i) ?></option>
                                        <?php endfor; ?>
                                    </select>
                                </div>
                                <div class="col-lg-3">
                                    <label>Time (Minute)</label>
                                    <select name="schedule_minute" class="form-control" placeholder="Schedule minute">
                                        <?php for ($m = 0; $m <= 55; $m += 5) : ?>
                                            <option value="<?= $m ?>"><?= sprintf("%02d", $m) ?></option>
                                        <?php endfor; ?>
                                    </select>
                                </div>
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

                    <?php require_once "themes/default/member-area/incs/footer-ads.inc.php"; ?>
                </div>
            </div>

        </div>
        <?php require_once "themes/default/member-area/incs/footer.inc.php"; ?>