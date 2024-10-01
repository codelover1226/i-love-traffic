<?php
ob_start();
session_start();
require_once "../../load_classes.php";
if (file_exists("../../nsms-solo-email/SoloEmailSettings.php")) {
    require_once "../../nsms-solo-email/SoloEmailSettings.php";
}

$siteSettings = new SiteSettingsController();
$paymentSettingsController = new PaymentSettingsController();
$membersController = new MembersController();
$membersController->verifyLoggedIn("logged_in");
$username = $_SESSION["logged_username"];
$binanceData = $paymentSettingsController->getSettings("Binance");
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
                if ($_GET["type"] == "loginads") {
                    if (isset($_GET["website_link"]) && !empty($_GET["website_link"])) {
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
                } else {
                    if ($_GET["type"] == "solo-email" && $_GET["id"] == 1 && file_exists("../../nsms-solo-email/SoloEmailSettings.php")) {
                        $soloEmailSettingsController = new SoloEmailSettings();
                        $soloEmailSettings = $soloEmailSettingsController->getSettings();
                        $productType = "solo-email";
                        $productName = "Solo Email";
                        $productPrice = $soloEmailSettings["price_per_mail"];
                        $productId = 1;
                    }
                }
            }
        }
        if (!empty($productType) && !empty($productName) && !empty($productId) && !empty($productPrice)) {
            $buyerInfo = $membersController->getUserDetails($username);
            $mainDomain = $siteSettingsData["installation_url"];
            $apiURL = "https://bpay.binanceapi.com/binancepay/openapi/v2/order";
            $timestamp = round(microtime(true) * 1000);
            $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
            $nonce = "";
            for ($i = 1; $i <= 32; $i++) {
                $pos = mt_rand(0, strlen($chars) - 1);
                $char = $chars[$pos];
                $nonce .= $char;
            }
            $requestData = ["env" => ["terminalType" => "WEB"], "buyer" => ["buyerName" => ["firstName" => $buyerInfo["first_name"], "lastName" => $buyerInfo["last_name"]]], "merchantTradeNo" => time() + 1, "orderAmount" => $productPrice, "currency" => "USDT", "goods" => ["goodsType" => "01", "goodsCategory" => $productType, "referenceGoodsId" => $username, "goodsName" => $productType, "goodsDetail" => $productType], "returnUrl" => $mainDomain . "dashboard.php?payment=succss", "cancelUrl" => $mainDomain . "dashboard.php?payment=cancel"];
            $secretKey = $binanceData["ipn_api_key"];
            $apiKey = $binanceData["public_key"];
            $jsonRequest = json_encode($requestData);
            $payload = $timestamp . "\n" . $nonce . "\n" . $jsonRequest . "\n";
            $signature = strtoupper(hash_hmac("SHA512", $payload, $secretKey));
            $requestHeaders = ["Content-Type: application/json", "BinancePay-Timestamp: " . $timestamp, "BinancePay-Nonce: " . $nonce, "BinancePay-Certificate-SN: " . $apiKey, "BinancePay-Signature: " . $signature];
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_HTTPHEADER, $requestHeaders);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_URL, $apiURL);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $jsonRequest);
            $response = json_decode(curl_exec($curl), true);
            if (is_array($response) && isset($response["status"]) && $response["status"] == "SUCCESS") {
                $tmpTransactionController = new TmpTransactionsController();
                $tmpTransactionController->add(["username" => $username, "trx_id" => $response["data"]["prepayId"], "payment_method" => "Binance", "amount" => $productPrice, "product" => $productId, "product_type" => $productType, "website_link" => $websiteLink, "timestamp" => time()]);
                header("Location: " . $response["data"]["checkoutUrl"]);
                exit;
            }
            exit("Binance payment system not working. Please contact the administrator.");
        }
        exit("Invalid checkout link.");
    }
    exit("Invalid checkout link.");
}
exit("Invaid checkout link.");

?>