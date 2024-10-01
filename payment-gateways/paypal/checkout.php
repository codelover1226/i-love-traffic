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
$paymentSettingsData = $paymentSettingsController->getSettings("PayPal");
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
            $mainDomain = $siteSettingsData["installation_url"];
            $clientId = $paymentSettingsData["public_key"];
            $clientSecret = $paymentSettingsData["private_key"];
            $apiEndpoint = "https://api.paypal.com";
            $tokenEndpoint = $apiEndpoint . "/v1/oauth2/token";
            $paymentEndpoint = $apiEndpoint . "/v2/checkout/orders";
            $ch = curl_init($tokenEndpoint);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ["Accept: application/json", "Accept-Language: en_US"]);
            curl_setopt($ch, CURLOPT_USERPWD, $clientId . ":" . $clientSecret);
            curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($ch);
            $result = json_decode($response, true);
            $accessToken = $result["access_token"];
            $data = ["intent" => "CAPTURE", "purchase_units" => [["amount" => ["currency_code" => "USD", "value" => $productPrice]]], "application_context" => ["return_url" => $mainDomain . "dashboard.php?payment=success", "cancel_url" => $mainDomain . "dashboard.php"]];
            $ch = curl_init($paymentEndpoint);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json", "Authorization: Bearer " . $accessToken]);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            $response = curl_exec($ch);
            $result = json_decode($response, true);
            $orderID = $result["id"];
            $approvalUrl = "";
            foreach ($result["links"] as $link) {
                if ($link["rel"] === "approve") {
                    $approvalUrl = $link["href"];
                    $tmpTrxController = new TmpTransactionsController();
                    $tmpTrxController->add(["username" => $username, "trx_id" => $orderID, "payment_method" => "PayPal", "amount" => $productPrice, "product" => $productId, "product_type" => $productType, "website_link" => $websiteLink, "timestamp" => time()]);
                    header("Location: " . $approvalUrl);
                    exit;
                }
            }
        } else {
            exit("Invalid checkout link.");
        }
    } else {
        exit("Invalid checkout link.");
    }
} else {
    exit("Invaid checkout link.");
}

?>