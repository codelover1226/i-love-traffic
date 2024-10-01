<?php
ob_start();
session_start();
require_once "vendor/autoload.php";
require_once "../../load_classes.php";
$siteSettings = new SiteSettingsController();
$paymentSettingsController = new PaymentSettingsController();
$membersController = new MembersController();
$membersController->verifyLoggedIn("logged_in");
$username = $_SESSION["logged_username"];
$stripeData = $paymentSettingsController->getSettings("Stripe");
Stripe\Stripe::setApiKey($stripeData["private_key"]);
$siteSettingsData = $siteSettings->getSettings();
$productType = "";
$productName = "";
$productPrice = 0;
$productId = 0;
$websiteLink = "";
if (isset($_GET["type"]) && isset($_GET["id"]) && isset($_GET["amount"])) {
    if (!empty($_GET["type"]) && !empty($_GET["id"]) && !empty($_GET["amount"])) {
        if (is_numeric($_GET["amount"]) && 0 < $_GET["amount"]) {
            if ($_GET["type"] == "membership" || $_GET["type"] == "combo" || $_GET["type"] == "credits") {
                if (is_numeric($_GET["id"]) && 0 < $_GET["id"]) {
                    if ($_GET["type"] == "membership") {
                        $membershipsController = new MembershipsController();
                        $productDetails = $membershipsController->getMembershipDetails($_GET["id"]);
                        if (empty($productDetails)) {
                            exit("Invalid membership.");
                        }
                        $productType = "membership";
                        $productName = $productDetails["membership_title"] . " - Membershp";
                        $productPrice = $productDetails["price"];
                        $productId = $productDetails["id"];
                    } else {
                        if ($_GET["type"] == "combo") {
                            $productsController = new ProductsController();
                            $productDetails = $productsController->getProductDetails($_GET["id"]);
                            if (empty($productDetails)) {
                                exit("Invalid combo offer.");
                            }
                            $productType = "combo";
                            $productName = $productDetails["product_title"] . " - Combo Offer";
                            $productPrice = $productDetails["product_price"];
                            $productId = $productDetails["id"];
                        } else {
                            if ($_GET["type"] == "credits") {
                                $emailCreditsController = new EmailCreditsPackagesController();
                                $productDetails = $emailCreditsController->getPackageDetails($_GET["id"]);
                                if (empty($productDetails)) {
                                    exit("Invalid credits package.");
                                }
                                $productType = "credits";
                                $productName = $productDetails["credits"] . " Email Credits";
                                $productPrice = $productDetails["price"];
                                $productId = $productDetails["id"];
                            }
                        }
                    }
                }
            } else {
                if ($_GET["type"] == "loginads" && isset($_GET["website_link"]) && !empty($_GET["website_link"])) {
                    if (filter_var($_GET["website_link"], FILTER_SANITIZE_URL)) {
                        $loginAdSettingsController = new LoginSpotlightAdSettingsController();
                        $loginAdsSettingsData = $loginAdSettingsController->getSettings();
                        $loginAds = new LoginSpotlightAdsController();
                        $availableDates = $loginAds->availableDates();
                        $productId = $_GET["id"];
                        if (!in_array($productId, $availableDates)) {
                            exit("This date is not available.");
                        }
                        $websiteLink = $_GET["website_link"];
                        $productType = "loginads";
                        $productName = "Login Ads : " . $_GET["id"];
                        $productPrice = $loginAdsSettingsData["ad_price"];
                    } else {
                        exit("Invalid website link.");
                    }
                }
            }
        }
        if (!empty($productType) && !empty($productName) && !empty($productId) && !empty($productPrice)) {
            $mainDomain = $siteSettingsData["installation_url"];
            Stripe\Stripe::setApiKey($stripeData["private_key"]);
            $customKey = md5($username . time());
            if ($productType == "membership" && !empty($productDetails["stripe_price_id"])) {
                $sessionParams = ["payment_method_types" => ["card"], "line_items" => [["price" => $productDetails["stripe_price_id"], "quantity" => 1]], "mode" => "subscription", "success_url" => $mainDomain . "dashboard.php?payment=success", "cancel_url" => $mainDomain . "dashboard.php?payment=cancel", "metadata" => ["custom_key" => $customKey], "subscription_data" => ["metadata" => ["custom_key" => $customKey]]];
                $session = Stripe\Checkout\Session::create($sessionParams);
                $sessionId = $session->id;
                $subscriptionId = $session->subscription;
                $memberSubscriptionController = new MemberSubscriptionsController();
                $tmpTrxController = new TmpTransactionsController();
                $tmpTrxController->add(["username" => $username, "trx_id" => $customKey, "payment_method" => "Stripe", "amount" => $productPrice, "product" => $productId, "product_type" => $productType, "website_link" => $websiteLink, "timestamp" => time()]);
            } else {
                $session = Stripe\Checkout\Session::create(["payment_method_types" => ["card"], "line_items" => [["price_data" => ["currency" => "usd", "product_data" => ["name" => $productName], "unit_amount" => $productPrice * 100], "quantity" => 1]], "metadata" => ["product_type" => $productType, "product_id" => $productId, "username" => $username, "custom_key" => $customKey], "mode" => "payment", "success_url" => $mainDomain . "dashboard.php?payment=success", "cancel_url" => $mainDomain . "dashboard.php?payment=cancel"]);
                $tmpTrxController = new TmpTransactionsController();
                $tmpTrxController->add(["username" => $username, "trx_id" => $customKey, "payment_method" => "Stripe", "amount" => $productPrice, "product" => $productId, "product_type" => $productType, "website_link" => $websiteLink, "timestamp" => time()]);
            }
            header("HTTP/1.1 303 See Other");
            header("Location: " . $session->url);
            exit;
        }
        exit("Invalid checkout link.");
    }
    exit("Invalid checkout link.");
}
exit("Invaid checkout link.");

?>