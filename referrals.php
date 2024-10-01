<?php
require_once "load_classes.php";
$websiteThemeLoader = new WebsiteThemeLoaderController();
if(isset($_GET["action"]) && $_GET["action"] == "promotion"){
    $websiteThemeLoader->loadWebsitePage("promotional-tools", "Affiliate Promotional Tools", "member");
}else if(isset($_GET["username"]) && !empty($_GET["username"])){
    $websiteThemeLoader->loadWebsitePage("search-referral", "Search Referral", "member");
}else{
    $websiteThemeLoader->loadWebsitePage("referrals", "Referrals", "member");
}