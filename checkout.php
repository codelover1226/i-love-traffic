<?php
ob_start();
session_start();
require_once "load_classes.php";
$membersController = new MembersController();
$siteSettingsController = new SiteSettingsController();
$membersController->verifyLoggedIn("logged_in");

if (isset($_GET["type"]) && !empty($_GET["type"])) {
    if ($_GET["type"] == "credits") {
    } else if ($_GET["type"] == "memberships") {
    } else if ($_GET["type"] == "login-ad") {
    } else if ($_GET["type"] == "combo") {
    } else {
        echo "Invalid request.";
        exit();
    }
} else {
    echo "Invalid request";
    exit();
}

$siteSettingsData = $siteSettingsController->getSettings();
$paymentGatewayController = new PaymentSettingsController();
$styleDir = "payment-gateway/assets/bootstrap/css/";
$jsDir = "payment-gateway/assets/bootstrap/js/";
$styleDirFiles = scandir($styleDir);
$paymentGatewayList = $paymentGatewayController->allActivePaymentGateway();
$userInfo = $membersController->getUserDetails($_SESSION["logged_username"]);
?>
<!DOCTYPE HTML>
<html>

<head>
    <title><?= "Checkout - " . $siteSettingsData["site_title"] ?></title>
    <?php if (!empty($styleDirFiles)) : ?>
        <?php foreach ($styleDirFiles as $stylesheet) : ?>
            <?php if (is_file($styleDir . $stylesheet)) : ?>
                <?php if (pathinfo($styleDir . $stylesheet)["extension"] == "css") : ?>
                    <link rel="stylesheet" href="<?= $styleDir . $stylesheet ?>" />
                <?php endif; ?>
            <?php endif; ?>
        <?php endforeach; ?>
    <?php endif; ?>
</head>

<body>
    <div class="container">
        <div class="d-flex align-items-center justify-content-center" style="height: 150px">
            <img src="<?= $siteSettingsData['logo_link'] ?>" class="img-fluid" />
        </div>
        <div class="border border-light p-3 mb-4">
            <div class="d-flex align-items-center justify-content-center">
                <div class="row">
                    <div class="col-lg-12">
                        <label class="badge badge-info"><h5>Choose a payment method</h5></label>
                        <br><br>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <h5>Pay with your account balance <label class="badge badge-danger">$<?= $userInfo["balance"] ?></label></h5>
                            <a href=""><button type="button" class="btn btn-danger">Pay Now >></button> </a> <br><br>
                            <div class="p-3"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="row">
                                <?php if (empty($paymentGatewayList)) : ?>
                                <?php else : ?>
                                    <div class="col-lg-12">
                                        <h5>Available Payment Methods</h5>
                                        <hr>
                                    </div>
                                    <?php foreach ($paymentGatewayList as $paymentGateway) : ?>
                                        <div class="col-lg-4">
                                            <img src="<?= 'payment-gateway/' . strtolower($paymentGateway['payment_method']) . '/' . strtolower($paymentGateway['payment_method']) . '.png' ?>" class="img-fluid" />
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

</body>

</html>