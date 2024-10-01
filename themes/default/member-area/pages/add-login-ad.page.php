<?php

$currentPage = (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] === "on" ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
if ($_SERVER["REQUEST_METHOD"] == "GET" && strcmp(basename($currentPage), basename(__FILE__)) == 0) {
    http_response_code(404);
    die("");
}
require_once "themes/default/member-area/incs/header.inc.php";
$loginAdCreditController = new LoginSpotlightCreditsController();
$flag = $loginAdsController->addUserLoginSpotlightAd($username);
$loginAdsAvailableDates = $loginAdsController->availableDates();
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
                <div class="alert alert-primary">You have total <?= $loginAdCreditController->userActiveCredit($username) ?> login ads available.</div>
                <?php if ($loginAdCreditController->userActiveCredit($username) > 0) : ?>
                    <div class="col-lg-12">
                        <div class="card border border-primary">
                            <div class="card-header bg-transparent border-primary">
                                <h5 class="my-0 text-primary"><i class="mdi mdi-bullseye-arrow me-3"></i>Add New Login Spotlight Ad</h5>
                            </div>
                            <div class="card-body">
                                <p class="card-text">
                                <form action="" method="POST" accept-charset="utf-8">
                                    <input type="hidden" name="csrf_token" value="<?= $membersController->getUserCSRFToken() ?>"">
                                    <div class=" form-group">
                                    <select name="date" class="form-select" required>
                                        <?php if (empty($loginAdsAvailableDates)) : ?>
                                            <option value="">No available dates this month</option>
                                        <?php else : ?>
                                            <?php foreach ($loginAdsAvailableDates as $availableDate) : ?>
                                                <option value="<?= $availableDate ?>"><?= $availableDate ?></option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                            </div><br>
                            <div class="form-group">
                                <label>Website Link</label>
                                <input type="url" name="website_link" class="form-control" placeholder="Your target link">
                            </div>
                            <br>
                            <div class="form-group">
                                <button class="btn btn-primary">Add</button>
                            </div>

                            </form>
                            </p>
                        </div>
                    </div>
                <?php else : ?>
                    <div class="alert alert-danger">Sorry you don't have login spotlight ad credit. Please buy from store.</div>
                <?php endif; ?>
                <?php require_once "themes/default/member-area/incs/footer-ads.inc.php"; ?>

            </div>
        </div>

    </div>


    <?php require_once "themes/default/member-area/incs/footer.inc.php"; ?>