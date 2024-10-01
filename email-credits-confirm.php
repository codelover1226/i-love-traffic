<?php
ob_start();
session_start();

require_once "load_classes.php";

if (isset($_SESSION["is_clicking"])) {
    $membersController = new MembersController();
    if (isset($_GET["type"]) && isset($_GET["id"]) && isset($_GET["timer_start"])) {
        if (empty($_GET["type"]) || empty($_GET["id"]) || empty($_GET["timer_start"]) || !is_numeric($_GET["timer_start"])) {
            echo "Invalid credit link.";
        } else {
            if ($_GET["type"] == "email" && isset($_GET["username"]) && !empty($_GET["username"])) {
                $emailsController = new EmailsController();
                $emailDetails = $emailsController->getMailDetailsByCreditKey($_GET["id"]);
                $userDetails = $membersController->getUserDetails($_GET["username"]);
                if (empty($userDetails)) {
                    echo "Invalid user !";
                } else if ($userDetails["account_status"] != 1) {
                    echo "Your account is not active or you have unsubscribed from our emails.";
                } else {
                    if (empty($emailDetails)) {
                        echo "Invalid email";
                    } else if ($emailDetails["email_status"] != 2) {
                        echo "Invalid email";
                    } else {
                        $siteSettingsController = new SiteSettingsController();
                        $siteSettings = $siteSettingsController->getSettings();
                        $emailValidity = $siteSettings["email_validity"] * 60 * 60 * 24;
                        if ((time() - $emailDetails["sending_time"]) > $emailValidity) {
                            echo "Email expired";
                        } else {
                            $emailClickController = new EmailClicksController();
                            if ($emailClickController->checkUserEmailClick($_GET["username"], $emailDetails["id"]) > 0) {
                                echo "You have already visited this website.";
                            } else {
                                $timer = $userDetails["timer_seconds"];
                                $credits = $userDetails["credits_per_click"];
                                if ((time() - $_GET["timer_start"]) >= $timer) {
                                    $emailClicksController = new EmailClicksController();
                                    $emailClicksController->addNewEmailClick(
                                        array(
                                            "username" => $userDetails["username"],
                                            "email_id" => $emailDetails["id"],
                                            "click_timestamp" => time(),
                                        )
                                    );
                                    $emailsController->addEmailClicks($emailDetails["id"]);
                                    $membersController->addEmailCredits($userDetails["username"], $credits);
                                    $affiliateDetails = $membersController->getUserDetails($userDetails["referrer"]);
                                    if (!empty($affiliateDetails)) {
                                        $membersController->addEmailCredits($affiliateDetails["username"], $affiliateDetails["clicks_commission"]);
                                    }
                                    $membersController->increaseEmailClick($userDetails["username"]);
                                    echo "You have earned {$credits} email credits.";
                                } else {
                                    echo "You are confirming the email link too fast.";
                                }
                            }
                        }
                    }
                }
            } else if ($_GET["type"] == "loginads") {
                $membersController->verifyLoggedIn("logged_in");
                $username = $_SESSION["logged_username"];
                $loginAdsController = new LoginSpotlightAdsController();
                $loginAdDetails = $loginAdsController->getLoginAdDetailsByCreditKey($_GET["id"]);
                if (empty($loginAdDetails)) {
                    $error = "Invalid login spotlight ad.";
                } else {
                    $userDetails = $membersController->getUserDetails($username);
                    $timer = $userDetails["timer_seconds"];
                    $loginAdClickController = new LoginSpotlightAdClickController();
                    if ($loginAdClickController->getTodayAdCount($username, $loginAdDetails["id"]) > 0) {
                        echo "You have already visited this site.";
                    } else {
                        $credits = $loginAdDetails["user_credits"];
                        if ((time() - $_GET["timer_start"]) >= $timer) {
                            $loginAdClickController = new LoginSpotlightAdClickController();
                            $loginAdClickController->addClickHistory(
                                array(
                                    "username" => $userDetails["username"],
                                    "ad_id" => $loginAdDetails["id"],
                                    "credit_key" => $loginAdDetails["credit_key"],
                                    "timestamp" => time(),
                                )
                            );
                            $membersController->addEmailCredits($userDetails["username"], $credits);
                            $loginAdsController->increaseLoginAdView($loginAdDetails["id"]);
                            $affiliateDetails = $membersController->getUserDetails($userDetails["referrer"]);
                            if (!empty($affiliateDetails)) {
                                $membersController->addEmailCredits($affiliateDetails["username"], $affiliateDetails["clicks_commission"]);
                            }
                            echo "You have earned {$credits} email credits.";
                        } else {
                            echo "You are confirming the email link too fast.";
                        }
                    }
                }
            } else {
                $websiteLink = $siteSettingsData["installation_url"];
                $error = "Invalid credit link.";
            }
        }
    }
}
