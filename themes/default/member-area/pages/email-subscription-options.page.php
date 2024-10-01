<?php

$currentPage = (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] === "on" ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
if ($_SERVER["REQUEST_METHOD"] == "GET" && strcmp(basename($currentPage), basename(__FILE__)) == 0) {
    http_response_code(404);
    die("");
}
require_once "themes/default/member-area/incs/header.inc.php";
$flag = $membersController->changeUserEmailSubscription($username);
$userInfo = $membersController->getUserDetails($username);
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
                            <h5 class="my-0 text-primary"><i class="mdi mdi-bullseye-arrow me-3"></i>Email Subscription Settings</h5>
                        </div>
                        <div class="card-body">
                            <p class="card-text">
                                <?php if ($userInfo["account_status"] == 1) : ?>
                            <div class="alert alert-info">Currently you are subscribed to our emails. You will get emails from our user and can earn credits.</div>
                        <?php else : ?>
                            <div class="alert alert-info">Currently you are not subscribed to our emails. You will not get emails from our user and can't earn credits.</div>
                        <?php endif; ?>
                        <form action="" method="POST" accept-charset="utf-8">
                            <input type="hidden" name="csrf_token" value="<?= $membersController->getUserCSRFToken() ?>"">
                                    <div class=" form-group">
                            <label>Email Subscription</label>
                            <select name="subscription" class="form-control">
                                <option value="1" <?= $userInfo["account_status"] == 1 ? "selected" : "" ?>>Enable</option>
                                <option value="3" <?= $userInfo["account_status"] == 3 ? "selected" : "" ?>>Disable</option>
                            </select>
                        </div><br>
                        <div class="form-group">
                            <button class="btn btn-primary">Update</button>
                        </div>

                        </form>
                        </p>
                    </div>
                </div>
                <?php require_once "themes/default/member-area/incs/footer-ads.inc.php"; ?>
            </div>
        </div>
    </div>
    <?php require_once "themes/default/member-area/incs/footer.inc.php"; ?>