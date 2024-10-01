<?php
require_once "load_classes.php";
$websiteThemeLoader = new WebsiteThemeLoaderController();

if(isset($_GET["action"]) && $_GET["action"] == "activity"){
    $websiteThemeLoader->loadWebsitePage("activity-contest", "Activity Contest Leaderboard", "member");
}else if(isset($_GET["action"]) && $_GET["action"] == "sales"){
    $websiteThemeLoader->loadWebsitePage("sales-contest", "Sales Contest Leaderboard", "member");
}else{
    $websiteThemeLoader->loadWebsitePage("referral-contest", "Referral Contest Leaderboard", "member");
}