<?php

$currentPage = (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] === "on" ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
if ($_SERVER["REQUEST_METHOD"] == "GET" && strcmp(basename($currentPage), basename(__FILE__)) == 0) {
    http_response_code(404);
    die("");
}
require_once "themes/default/member-area/incs/header.inc.php";
require_once "modules/nsms-link-tracker/LinkTracker.php";
$linkTrackerController = new LinkTracker();
$flag = $linkTrackerController->addShortenLink($username, $userInfo["membership"]);
$trackingLinkSettings = $linkTrackerController->getSettings();
$totalUserLinks = $linkTrackerController->totalUserShortenLink($username);
$membersController->generateUserCSRFToken();
?>
<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <?php include_once "themes/default/member-area/incs/sidebar-ads.inc.php"; ?>
            <div class="col-xl-9">
                <?php if (isset($flag) && isset($flag["success"])) : ?>
                <?php if ($flag["success"] == true) : ?>
                <div class="alert alert-success"><?= $flag["message"] ?></div>
                <?php else : ?>
                <div class="alert alert-danger"><?= $flag["message"] ?></div>
                <?php endif; ?>
                <?php endif; ?>
                <?php if ($trackingLinkSettings["system_power"] != 1) : ?>
                <div class="alert alert-danger">Link tracker system disabled by admin.</div>
                <?php else : ?>
                <?php if ($userInfo["membership"] == 1 && $totalUserLinks >= $trackingLinkSettings["free_member_limit"]) : ?>
                <div class="alert alert-info">You can create max <?= $trackingLinkSettings["free_member_limit"] ?>
                    tracking link(s). Currently you have total <?= $totalUserLinks ?> link(s).</div>
                <div class="alert alert-danger">You have reached your max tracking link limit. Please upgrade your
                    account to create more tracking links.</div>
                <?php elseif ($userInfo["membership"] != 1 && $totalUserLinks >= $trackingLinkSettings["paid_member_limit"]) : ?>
                <div class="alert alert-info">You can create max <?= $trackingLinkSettings["paid_member_limit"] ?>
                    tracking link(s). Currently you have total <?= $totalUserLinks ?> link(s).</div>
                <div class="alert alert-danger">You have reached your max tracking link limit.</div>
                <?php else : ?>
                <div class="col-lg-12">
                    <div class="card border border-primary">
                        <div class="card-header bg-transparent border-primary">
                            <h5 class="my-0 text-primary"><i class="mdi mdi-bullseye-arrow me-3"></i>Create New Link
                            </h5>
                        </div>
                        <div class="card-body">
                            <p class="card-text">
                            <form action="" method="POST" accept-charset="utf-8">
                                <input type="hidden" name="csrf_token"
                                    value="<?= $membersController->getUserCSRFToken() ?>">
                                <div class=" form-group">
                                    <label>Website Link</label>
                                    <input type="url" name="website_link" class="form-control"
                                        placeholder="Your website link">
                                </div><br>
                                <div class="form-group">
                                    <button class="btn btn-primary">Create</button>
                                </div>
                            </form><br><br>
                            </p>
                            <p>
                                <?php if (isset($flag["tracking_link"]) && isset($_POST["website_link"])) : ?>
                                Actual Link : <a href="<?= $_POST['website_link'] ?>"
                                    target="_blank"><?= $_POST['website_link'] ?></a><br>
                                Tracking Link : <a href="<?= $flag['tracking_link'] ?>"
                                    target="_blank"><?= $flag['tracking_link'] ?></a>
                                <?php endif; ?>
                            </p>
                        </div>
                    </div>
                    <?php require_once "themes/default/member-area/incs/footer-ads.inc.php"; ?>
                </div>
                <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
        <?php require_once "themes/default/member-area/incs/footer.inc.php"; ?>