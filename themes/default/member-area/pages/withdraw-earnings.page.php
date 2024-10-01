<?php

$currentPage = (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] === "on" ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
if ($_SERVER["REQUEST_METHOD"] == "GET" && strcmp(basename($currentPage), basename(__FILE__)) == 0) {
    http_response_code(404);
    die("");
}
require_once "themes/default/member-area/incs/header.inc.php";
$affiliateSettings = $affiliateSettingsController->getSettings();
$flag = $withdrawalRequestsController->addNewUserWithdrawalRequest($username, $userInfo);
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
                <?php if ($userInfo["balance"] < $affiliateSettings["minimum_withdraw"]) : ?>
                <div class="alert alert-danger">You will need to have minimum
                    $<?= $affiliateSettings["minimum_withdraw"] ?> to request your earnings</div>
                <?php elseif (
                    empty($userInfo["paypal"]) &&
                    empty($userInfo["btc_coinbase"]) && empty($userInfo["skrill"]) &&
                    empty($userInfo["transfer_wise"]) && empty($userInfo["perfect_money"]) && empty($userInfo["eth_wallet"])
                ) : ?>
                <div class="alert alert-danger">Please set a payment gateway first.</div>
                <?php else : ?>
                <div class="col-lg-12">
                    <div class="card border border-primary">
                        <div class="card-header bg-transparent border-primary">
                            <h5 class="my-0 text-primary"><i class="mdi mdi-bullseye-arrow me-3"></i>Withdraw Earnings
                            </h5>
                        </div>
                        <div class="card-body">
                            <p class="card-text">
                            <form action="" method="POST" accept-charset="utf-8">
                                <input type="hidden" name="csrf_token"
                                    value="<?= $membersController->getUserCSRFToken() ?>"">
                                    <div class=" form-group">
                                <label>Amount</label>
                                <input type="number" step="0.01" name="amount" class="form-control" placeholder="Amount"
                                    value="<?= $userInfo['balance'] ?>">
                        </div><br>
                        <div class="form-group">
                            <label>Payment Method</label>
                            <select class="form-control" name="payment_gateway">
                                <?php if ($affiliateSettings["paypal"] == 1 && !empty($userInfo["paypal"])) : ?>
                                <option value="PayPal">PayPal</option>
                                <?php endif; ?>
                                <?php if ($affiliateSettings["btc_coinbase"] == 1 && !empty($userInfo["btc_coinbase"])) : ?>
                                <option value="Coinbase">Coinbase</option>
                                <?php endif; ?>
                                <?php if ($affiliateSettings["skrill"] == 1 && !empty($userInfo["skrill"])) : ?>
                                <option value="Skrill">Skrill</option>
                                <?php endif; ?>
                                <?php if ($affiliateSettings["transfer_wise"] == 1 && !empty($userInfo["transfer_wise"])) : ?>
                                <option value="TransferWise">TransferWise</option>
                                <?php endif; ?>
                                <?php if ($affiliateSettings["perfect_money"] == 1 && !empty($userInfo["perfect_money"])) : ?>
                                <option value="PerfectMoney">PerfectMoney</option>
                                <?php endif; ?>
                                <?php if ($affiliateSettings["eth_wallet"] == 1 && !empty($userInfo["eth_wallet"])) : ?>
                                <option value="ETH Wallet">ETH Wallet</option>
                                <?php endif; ?>
                            </select>
                        </div><br>
                        <div class="form-group">
                            <button class="btn btn-primary">Send Request</button>
                        </div>

                        </form>
                        </p>
                    </div>
                </div>
                <?php endif; ?>
                <?php require_once "themes/default/member-area/incs/footer-ads.inc.php"; ?>
            </div>
        </div>

    </div>

    <?php require_once "themes/default/member-area/incs/footer.inc.php"; ?>