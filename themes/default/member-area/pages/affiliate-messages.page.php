<?php

$currentPage = (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] === "on" ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
if ($_SERVER["REQUEST_METHOD"] == "GET" && strcmp(basename($currentPage), basename(__FILE__)) == 0) {
    http_response_code(404);
    die("");
}
require_once "themes/default/member-area/incs/header.inc.php";
$flag = $affiliateMessagingController->deleteInboxMessage($userInfo["username"]);
$inboxList = $affiliateMessagingController->affiliateInboxList($userInfo["username"]);
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
                            <h5 class="my-0 text-primary"><i class="mdi mdi-bullseye-arrow me-3"></i>Affiliate Message Inbox</h5>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-primary" role="alert">
                                Here you can find messages from your upline and downline members.
                            </div>
                            <div class="row">
                                <div class="col-xl-3 col-md-6">
                                    <div class="card card-animate">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between">
                                                <div>
                                                    <p class="fw-medium text-muted mb-0">Unread Messages</p>
                                                    <h2 class="mt-4 ff-secondary fw-semibold"><span><?= $affiliateMessagingController->totalAffiliateUnreadMessage($userInfo["username"]) ?></span></h2>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xl-3 col-md-6">
                                    <div class="card card-animate">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between">
                                                <div>
                                                    <p class="fw-medium text-muted mb-0">Total Messages In Inbox</p>
                                                    <h2 class="mt-4 ff-secondary fw-semibold"><span><?= $affiliateMessagingController->totalAffiliateInboxMessage($userInfo["username"]) ?></span></h2>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-md-6">
                                    <div class="card card-animate">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between">
                                                <div>
                                                    <p class="fw-medium text-muted mb-0">Total Sent Messages</p>
                                                    <h2 class="mt-4 ff-secondary fw-semibold"><span> <?= $affiliateMessagingController->totalAffiliateSentMessage($userInfo["username"]) ?></span></h2>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-md-6">
                                    <div class="card card-animate bg-primary">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between">
                                                <div>
                                                    <p class="fw-medium text-white-50 mb-0">Total Message Limit</p>
                                                    <h2 class="mt-4 ff-secondary fw-semibold text-white"><span><?= $affiliateMessagingController->maxMessageLimit ?></span></h2>
                                                    <p class="mb-0 text-white-50"><span class="badge bg-white bg-opacity-25 text-white mb-0">Message limit (Inbox+Sent) </span> </p>
                                                </div>
                                                <div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                            </div>
                            <table class="table table-striped">
                                <?php if (!empty($inboxList)) : ?>
                                    <thead>
                                        <td>Subject</td>
                                        <td>Sender</td>
                                        <td>Time</td>
                                        <td></td>
                                    </thead>
                                    <?php foreach ($inboxList as $messageDetails) : ?>
                                        <tr>
                                            <td><a href="affiliate-message-details.php?id=<?= $messageDetails['id'] ?>"><?= base64_decode($messageDetails["message_subject"]) ?>
                                                    <?php if ($messageDetails["reading_status"] == 1) : ?>
                                                        <span class="badge bg-primary">Unread</span>
                                                    <?php endif; ?>
                                                </a>
                                            </td>
                                            <td><?= $messageDetails["sender_username"] ?></td>
                                            <td><?= date("d, M Y h:i:s A", $messageDetails["sending_timestamp"]) ?></td>
                                            <td>
                                                <a href="affiliate-messages.php?delete=<?= $messageDetails['id'] ?>&token=<?= $membersController->getUserCSRFToken() ?>" class="btn btn-danger waves-effect waves-light">Delete</a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <div class="alert alert-danger" role="alert">
                                        No message yet !
                                    </div>
                                <?php endif; ?>
                            </table>
                            <?= $affiliateMessagingController->affiliateInboxPagination($userInfo["username"]) ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php require_once "themes/default/member-area/incs/footer-ads.inc.php"; ?>
        </div>

    </div>

    <?php require_once "themes/default/member-area/incs/footer.inc.php"; ?>