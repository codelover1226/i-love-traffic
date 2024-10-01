<?php

$currentPage = (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] === "on" ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
if ($_SERVER["REQUEST_METHOD"] == "GET" && strcmp(basename($currentPage), basename(__FILE__)) == 0) {
    http_response_code(404);
    die("");
}
require_once "themes/default/member-area/incs/header.inc.php";
$emailList = $emailsController->userEmailList($username);
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
                        <table class="table table-striped">
                            <thead>
                                <td>Subject</td>
                                <td>Sending Date</td>
                                <td>Total Clicks</td>
                                <td>Total Sent</td>
                                <td>Status</td>
                                <td></td>
                            </thead>
                            <?php if (!empty($emailList)) : ?>
                                <?php foreach ($emailList as $emailData) : ?>
                                    <tr>
                                        <td><?= base64_decode($emailData["email_subject"]) ?></td>
                                        <td><?= date("d M, Y H:i", $emailData["sending_time"]) ?></td>
                                        <td><?= $emailData["total_clicks"] ?></td>
                                        <td><?= $emailData["total_sent"] ?></td>
                                        <td>
                                            <?php if ($emailData["suspend_status"] == 1) : ?>
                                                Email Supended
                                            <?php else : ?>
                                                <?= $emailsController->emailStatus()[$emailData["email_status"]] ?>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </table>
                    </div>
                </div>
                <?php require_once "themes/default/member-area/incs/footer-ads.inc.php"; ?>
            </div>
        </div>

    </div>

    <?php require_once "themes/default/member-area/incs/footer.inc.php"; ?>