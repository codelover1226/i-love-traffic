<?php
require_once "load_classes.php";
$websiteThemeLoader = new WebsiteThemeLoaderController();
if(isset($_GET["action"]) && $_GET["action"] == "withdraw"){
    $websiteThemeLoader->loadWebsitePage("withdraw-earnings", "Withdraw Earnings", "member");
}else if(isset($_GET["action"]) && $_GET["action"] == "payment-method"){
    $websiteThemeLoader->loadWebsitePage("update-payment-method", "Update Payment Gateway", "member");
}else{
    $websiteThemeLoader->loadWebsitePage("withdrawal-requests", "Withdrawal Requests", "member");
}