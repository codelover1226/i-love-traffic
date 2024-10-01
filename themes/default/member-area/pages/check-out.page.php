<?php

$currentPage = (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] === "on" ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
if ($_SERVER["REQUEST_METHOD"] == "GET" && strcmp(basename($currentPage), basename(__FILE__)) == 0) {
    http_response_code(404);
    die("");
}
require_once "themes/default/member-area/incs/header.inc.php";
$productDetails = "";
if (isset($_GET["type"]) && isset($_GET["id"])) {
    if (!empty($_GET["type"]) && !empty($_GET["id"])) {
        if ($_GET["type"] == "credits") {
            if (is_numeric($_GET["id"]) && $_GET["id"] > 0) {
                $emailCreditsController = new EmailCreditsPackagesController();
                $creditsInfo = $emailCreditsController->getPackageDetails($_GET["id"]);
                $productDetails = array(
                    "name" => "Email Credits " . $creditsInfo["credits"],
                    "price" => $creditsInfo["price"]
                );
            }
        } else if ($_GET["type"] == "membership") {
            if (is_numeric($_GET["id"]) && $_GET["id"] > 0) {
                $membershipsController = new MembershipsController();
                $membershipDetails = $membershipsController->getMembershipDetails($_GET["id"]);
                if ($membershipDetails["status"] == 1 && $membershipDetails["subscription_type"] != 1) {
                    $productDetails = array(
                        "name" => "Membership : " . $membershipDetails["membership_title"],
                        "price" => $membershipDetails["price"]
                    );
                }
            }
        } else if ($_GET["type"] == "combo") {
            if (is_numeric($_GET["id"]) && $_GET["id"] > 0) {
                $productsController = new ProductsController();
                $comboDetails = $productsController->getProductDetails($_GET["id"]);
                if ($comboDetails["status"] == 1) {
                    $productDetails = array(
                        "name" => "Combo Offer : " . $comboDetails["product_title"],
                        "price" => $comboDetails["product_price"]
                    );
                }
            }
        } else if ($_GET["type"] == "loginads") {
            $loginAdsController = new LoginSpotlightAdsController();
            $loginAdsSettingsController = new LoginSpotlightAdSettingsController();
            $loginAdsSettings = $loginAdsSettingsController->getSettings();
            $availableDates = $loginAdsController->availableDates();
            if (in_array($_GET["id"], $availableDates)) {
                $productDetails = array(
                    "name" => "Login Spotlight Ad on " . $_GET["id"],
                    "price" => $loginAdsSettings["ad_price"]
                );
            }
        }
    }
}
$membersController->generateUserCSRFToken();
if (!empty($productDetails)) {
    $paymentGateways = $paymentSettingsController->allActivePaymentGateway();
}
?>
<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12">
                <?php if (isset($flag) && isset($flag["success"])) : ?>
                    <?php if ($flag["success"] == true) : ?>
                        <div class="alert alert-success"><?= $flag["message"] ?></div>
                    <?php else : ?>
                        <div class="alert alert-danger"><?= $flag["message"] ?></div>
                    <?php endif; ?>
                <?php endif; ?>
                <?php if (empty($productDetails)) : ?>
                    <div class="alert alert-danger">Invalid order link !</div>
                <?php else : ?>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card border border-primary">
                                <div class="card-header bg-transparent border-primary">
                                    <h5 class="my-0 text-primary"><i class="mdi mdi-bullseye-arrow me-3"></i>Product Details</h5>
                                </div>
                                <div class="card-body">
                                    <p class="card-text">
                                    <h3><?= $productDetails["name"] ?> - $<?= $productDetails["price"] ?></h3>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="card border border-primary">
                                <div class="card-header bg-transparent border-primary">
                                    <h5 class="my-0 text-primary"><i class="mdi mdi-bullseye-arrow me-3"></i>Pay With Account Balance</h5>
                                </div>
                                <div class="card-body">
                                    <p class="card-text">
                                    <h3>Current Balance : $<?= $userInfo["balance"] ?></h3>
                                    <div class="form-group">
                                        <a href="dashboard.php?type=<?= $_GET['type'] ?>&id=<?= $_GET['id'] ?>&amount=<?= $productDetails['price'] ?><?= isset($_GET['website_link']) ? '&website_link=' . $_GET['website_link'] : '' ?>"><button class="btn btn-success">Pay Now</button></a><br><br><br><br>
                                    </div>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <?php if (isset($paymentGateways) && !empty($paymentGateways)) : ?>
                            <?php foreach ($paymentGateways as $paymentGateway) : ?>
                                <div class="col-lg-6">
                                    <div class="card border border-primary">
                                        <div class="card-header bg-transparent border-primary">
                                            <h5 class="my-0 text-primary"><i class="mdi mdi-bullseye-arrow me-3"></i>Pay With <?= $paymentGateway["payment_method"] ?></h5>
                                        </div>
                                        <div class="card-body">
                                            <p class="card-text">
                                                <img src="<?= 'payment-gateways/' . strtolower($paymentGateway['payment_method']) . '/' . strtolower($paymentGateway['payment_method']) . '.png' ?>" height="100" alt="<?= $paymentGateway['payment_method'] ?>">
                                            <div class="form-group">
                                                <a href="payment-gateways/<?= strtolower($paymentGateway['payment_method']) ?>/checkout.php?type=<?= $_GET['type'] ?>&id=<?= $_GET['id'] ?>&amount=<?= $productDetails['price'] ?><?= isset($_GET['website_link']) ? '&website_link=' . $_GET['website_link'] : '' ?>"><button class="btn btn-success">Pay Now</button></a><br><br><br><br>
                                            </div>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>

        </div>
    </div>
    <?php require_once "themes/default/member-area/incs/footer.inc.php"; ?>