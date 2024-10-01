<?php
ob_start();
session_start();
require_once "../../load_classes.php";

$siteSettings = new SiteSettingsController();
$paymentSettingsController = new PaymentSettingsController();
$membersController = new MembersController();
$membersController->verifyLoggedIn("logged_in");
$username = $_SESSION["logged_username"];
$coinbaseData = $paymentSettingsController->getSettings("Coinbase");
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
            $buyerInfo = $membersController->getUserDetails($username);
            $mainDomain = $siteSettingsData["installation_url"];
            $coinbaseAPILink = "https://api.commerce.coinbase.com/charges";
            $requestHeaders = ["X-CC-Api-Key: " . $coinbaseData["ipn_api_key"], "X-CC-Version: 2018-03-22", "Content-Type: application/json"];
            $requestData = ["name" => $buyerInfo["first_name"] . " " . $buyerInfo["last_name"], "description" => $productName, "local_price" => ["amount" => $productPrice, "currency" => "USD"], "pricing_type" => "fixed_price", "metadata" => ["username" => $username], "redirect_url" => $mainDomain . "dashboard.php?payment=succss", "cancel_url" => $mainDomain . "dashboard.php?payment=cancel"];
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_HTTPHEADER, $requestHeaders);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_URL, $coinbaseAPILink);
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($requestData));
            $response = json_decode(curl_exec($curl), true);
            curl_close($curl);
            if (is_array($response) && isset($response["data"]["hosted_url"])) {
                $tmpTransactionController = new TmpTransactionsController();
                $tmpTransactionController->add(["username" => $username, "trx_id" => $response["data"]["code"], "payment_method" => "Coinbase Commerce", "amount" => $productPrice, "product" => $productId, "product_type" => $productType, "website_link" => $websiteLink, "timestamp" => time()]);
                header("Location: " . $response["data"]["hosted_url"]);
                exit;
            }
            exit("Coinbase commerce payment system not working. Please contact the administrator.");
        }
        exit("Invalid checkout link.");
    }
    exit("Invalid checkout link.");
}
exit("Invaid checkout link.");

?>