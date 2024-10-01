<?php


$currentPage = (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] === "on" ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
if ($_SERVER["REQUEST_METHOD"] == "GET" && strcmp(basename($currentPage), basename(__FILE__)) == 0) {
    http_response_code(404);
    die("");
}
require_once "themes/default/member-area/incs/header.inc.php";
$flag = $supportTicketsController->createUserReply($userInfo, $siteSettingsData);
$supportTicketDetails = $supportTicketsController->getUserTicketDetails($_GET["details"], $userInfo["username"]);
if (!empty($supportTicketDetails)) {
    $supportTicketReplies = $supportTicketsController->getUserReplies($supportTicketDetails["id"], $userInfo["username"]);
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
                <?php if (!empty($supportTicketDetails)) : ?>
                    <div class="col-lg-12">
                        <div class="card border border-primary">
                            <div class="card-header bg-transparent border-primary">
                                <h5 class="my-0 text-primary"><i class="mdi mdi-bullseye-arrow me-3"></i><?= "Support Ticket : " . $supportTicketDetails["ticket_title"] ?></h5>
                                <div class="card-body">
                                    <div class="card">
                                        <div class="card-body p-4">
                                            <p class="text-muted"><?= nl2br(htmlspecialchars($supportTicketDetails["ticket_body"])) ?></p>
                                        </div>
                                        <div class="card-body p-4">
                                            <?php if (!empty($supportTicketReplies)) : ?>
                                                <h5 class="card-title mb-4">Replies [Scroll to view old replies]</h5>
                                                <div data-simplebar="init" style="height: 300px;" class="px-3 mx-n3 simplebar-scrollable-y">
                                                    <div class="simplebar-wrapper" style="margin: 0px -16px;">
                                                        <div class="simplebar-height-auto-observer-wrapper">
                                                            <div class="simplebar-height-auto-observer"></div>
                                                        </div>
                                                        <div class="simplebar-mask">
                                                            <div class="simplebar-offset" style="right: 0px; bottom: 0px;">

                                                                <div class="simplebar-content-wrapper" tabindex="0" role="region" aria-label="scrollable content" style="height: 100%; overflow: hidden scroll;">
                                                                    <div class="simplebar-content" style="padding: 0px 16px;">
                                                                        <?php foreach ($supportTicketReplies as $replyDetails) : ?>
                                                                            <div class="d-flex mb-4">
                                                                                <div class="flex-shrink-0">
                                                                                    <?php if ($replyDetails["reply_author"] == $userInfo["username"]) : ?>
                                                                                        <img src="<?= $membersController->gravatar($userInfo['email'], $siteSettingsData['installation_url']) ?>" alt="" class="avatar-xs rounded-circle">
                                                                                    <?php else : ?>
                                                                                        <img src="logo2/ILTlogo2.png" alt="" class="avatar-xs rounded-circle">
                                                                                    <?php endif; ?>
                                                                                </div>
                                                                                <div class="flex-grow-1 ms-3">
                                                                                    <h5 class="fs-14"><?= $replyDetails["reply_author"] ?> <small class="text-muted"><?= date("d M, Y", $replyDetails["reply_timestamp"]) ?></small>
                                                                                        <?php if ($replyDetails["reply_author"] != $userInfo["username"]) : ?>
                                                                                            <span class="badge rounded-pill bg-primary-subtle text-primary">Admin</span>
                                                                                        <?php endif; ?>
                                                                                    </h5>
                                                                                    <p class="text-muted"><?= $replyDetails["reply"] ?></p>

                                                                                </div>
                                                                            </div>
                                                                        <?php endforeach; ?>
                                                                    </div>

                                                                </div>

                                                            </div>

                                                        </div>
                                                        <div class="simplebar-placeholder" style="width: 1094px; height: 598px;"></div>
                                                    </div>
                                                    <div class="simplebar-track simplebar-horizontal" style="visibility: hidden;">
                                                        <div class="simplebar-scrollbar" style="width: 0px; display: none;"></div>
                                                    </div>
                                                    <div class="simplebar-track simplebar-vertical" style="visibility: visible;">
                                                        <div class="simplebar-scrollbar" style="height: 150px; transform: translate3d(0px, 0px, 0px); display: block;"></div>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                            <?php if ($supportTicketDetails["ticket_status"] != 3) : ?>
                                                <form action="" method="post" class="mt-3">
                                                    <div class="row g-3">
                                                        <div class="col-lg-12">
                                                            <input type="hidden" name="csrf_token" value="<?= $membersController->getUserCSRFToken() ?>">
                                                            <input type="hidden" name="ticket_id" value="<?= $supportTicketDetails['id'] ?>">
                                                            <label for="exampleFormControlTextarea1" class="form-label">Write a Reply</label>
                                                            <textarea name="reply" rows="10" class="form-control bg-light border-light" id="exampleFormControlTextarea1" rows="3" placeholder="Enter comments"></textarea>
                                                        </div>
                                                        <div class="col-lg-12 text-end">
                                                            <button type="submit" class="btn btn-success">Post Reply</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                <?php else : ?>
                    <div class="alert alert-danger">Couldn't find the ticket</div>
                <?php endif; ?>
                <?php require_once "themes/default/member-area/incs/footer-ads.inc.php"; ?>
                
            </div>

        </div>

        <?php require_once "themes/default/member-area/incs/footer.inc.php"; ?>