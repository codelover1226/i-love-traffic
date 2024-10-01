<?php

$currentPage = (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] === "on" ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
if ($_SERVER["REQUEST_METHOD"] == "GET" && strcmp(basename($currentPage), basename(__FILE__)) == 0) {
    http_response_code(404);
    die("");
}
require_once "themes/default/member-area/incs/header.inc.php";
$messageDetails = $affiliateMessagingController->getMessageDetails($_GET["id"]);
$messageAccess = false;
if (!empty($messageDetails)) {
    if (strtolower($messageDetails["receiver_username"]) == strtolower($userInfo["username"]) && intval($messageDetails["receiver_delete_status"]) == 1) {
        $messageAccess = true;
        $affiliateMessagingController->markMessageRead($userInfo["username"], $_GET["id"]);
    } else if (strtolower($messageDetails["sender_username"]) == strtolower($userInfo["username"]) && intval($messageDetails["sender_delete_status"]) == 1) {
        $messageAccess = true;
    }
}
if ($messageAccess) {
    $senderDetails = $membersController->getUserDetails($messageDetails["sender_username"]);
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
                <?php if ($messageAccess) : ?>
                    <div class="col-lg-12">
                        <div class="card border border-primary">
                            <div class="card-header bg-transparent border-primary">
                                <h5 class="my-0 text-primary"><i class="mdi mdi-bullseye-arrow me-3"></i>Message Subject : <?= base64_decode($messageDetails["message_subject"]) ?></h5>
                            </div>
                            <div class="card-body">
                                <div class="d-flex mb-4 align-items-center">
                                    <div class="flex-shrink-0">
                                        <img src="<?= $membersController->gravatar($senderDetails['email'], $siteSettingsData['installation_url']) ?>" alt="" class="avatar-sm rounded-circle">
                                    </div>
                                    <div class="flex-grow-1 ms-2">
                                        <h5 class="card-title mb-1"><?= $messageDetails["sender_username"] ?> <span class="badge bg-primary"><?= date("d, M Y h:i:s A", $messageDetails["sending_timestamp"]) ?></span></h5>
                                        <p class="text-muted mb-0">Membership : <?= $senderDetails["membership_title"] ?></p>
                                    </div>

                                </div>
                                <p>Message : </p>
                                <p><?= base64_decode($messageDetails["message_body"]) ?></p>
                            </div>
                        </div>

                    </div>
                <?php else : ?>
                    <div class="alert alert-danger" role="alert">
                        Coulnd't find the message !
                    </div>

                <?php endif; ?>
                <?php require_once "themes/default/member-area/incs/footer-ads.inc.php"; ?>
                
            </div>

        </div>


        <?php require_once "themes/default/member-area/incs/footer.inc.php"; ?>