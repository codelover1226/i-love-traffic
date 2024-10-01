<?php

$currentPage = (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] === "on" ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
if ($_SERVER["REQUEST_METHOD"] == "GET" && strcmp(basename($currentPage), basename(__FILE__)) == 0) {
    http_response_code(404);
    die("");
}
require_once "themes/default/member-area/incs/header.inc.php";
$affiliateSettings = $affiliateSettingsController->getSettings();
$flag = $membersController->updateUserPaymentMethod($username);
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
                <?php
                if (
                    $affiliateSettings["paypal"] == 2 && $affiliateSettings["btc_coinbase"] == 2 &&
                    $affiliateSettings["skrill"] == 2 && $affiliateSettings["transfer_wise"] == 2 &&
                    $affiliateSettings["perfect_money"] == 2 && $affiliateSettings["eth_wallet"] == 2
                ) : ?>
                    <div class="alert alert-danger">No availalbe payment method. Please check again later.</div>
                <?php else : ?>
                    <div class="col-lg-12">
                        <div class="card border border-primary">
                            <div class="card-header bg-transparent border-primary">
                                <h5 class="my-0 text-primary"><i class="mdi mdi-bullseye-arrow me-3"></i>Update Your Payment Methods</h5>
                            </div>
                            <div class="card-body">
                                <p class="card-text">
                                <form action="" method="POST" accept-charset="utf-8">
                                    <input type="hidden" name="csrf_token" value="<?= $membersController->getUserCSRFToken() ?>"">
                                            <?php if ($affiliateSettings["paypal"] == 1) : ?>
                                            <div class=" form-group">
                                    <label>PayPal</label>
                                    <input type="email" name="paypal" class="form-control" placeholder="Your PayPal email" value="<?= $userInfo['paypal'] ?>">
                            </div><br>
                        <?php endif; ?>
                        <?php if ($affiliateSettings["btc_coinbase"] == 1) : ?>
                            <div class="form-group">
                                <label>Coinbase</label>
                                <input type="text" name="btc_coinbase" class="form-control" placeholder="Your Conbase wallet" value="<?= $userInfo['btc_coinbase'] ?>">
                            </div><br>
                        <?php endif; ?>
                        <?php if ($affiliateSettings["skrill"] == 1) : ?>
                            <div class="form-group">
                                <label>Skrill</label>
                                <input type="email" name="skrill" class="form-control" placeholder="Your Skrill email" value="<?= $userInfo['skrill'] ?>">
                            </div><br>
                        <?php endif; ?>
                        <?php if ($affiliateSettings["transfer_wise"] == 1) : ?>
                            <div class="form-group">
                                <label>TransferWise</label>
                                <input type="email" name="transfer_wise" class="form-control" placeholder="Your Transferwise email" value="<?= $userInfo['transfer_wise'] ?>">
                            </div><br>
                        <?php endif; ?>
                        <?php if ($affiliateSettings["perfect_money"] == 1) : ?>
                            <div class="form-group">
                                <label>PerfectMoney</label>
                                <input type="email" name="perfect_money" class="form-control" placeholder="Your PerfectMoney email" value="<?= $userInfo['perfect_money'] ?>">
                            </div><br>
                        <?php endif; ?>
                        <?php if ($affiliateSettings["eth_wallet"] == 1) : ?>
                            <div class="form-group">
                                <label>ETH Wallet</label>
                                <input type="text" name="eth_wallet" class="form-control" placeholder="Your ETH Wallet" value="<?= $userInfo['eth_wallet'] ?>">
                            </div><br>
                        <?php endif; ?>
                        <br>
                        <div class="form-group">
                            <button class="btn btn-primary">Update</button>
                        </div>

                        </div>
                        </form>
                        </p>
                    </div>
                <?php endif; ?>
            </div>
            <?php require_once "themes/default/member-area/incs/footer-ads.inc.php"; ?>
        </div>
    </div>

</div>


<?php require_once "themes/default/member-area/incs/footer.inc.php"; ?>