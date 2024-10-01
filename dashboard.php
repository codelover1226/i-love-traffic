<?php
require_once "load_classes.php";
$websiteThemeLoader = new WebsiteThemeLoaderController();

if(isset($_GET["action"]) && $_GET["action"] == "sales"){
    $websiteThemeLoader->loadWebsitePage("sales", "Affiliate Sales", "member");
}else if(isset($_GET["action"]) && $_GET["action"] == "email-subscription"){
    $websiteThemeLoader->loadWebsitePage("email-subscription-options", "Email Subscription", "member");
}else if(isset($_GET["action"]) && $_GET["action"] == "vacation"){
    $websiteThemeLoader->loadWebsitePage("member-vacation-settings", "Vacation Settings", "member");
}else if(isset($_GET["action"]) && $_GET["action"] == "password"){
    $websiteThemeLoader->loadWebsitePage("change-password", "Change Password", "member");
}else if(isset($_GET["action"]) && $_GET["action"] == "account"){
    $websiteThemeLoader->loadWebsitePage("edit-account", "Edit Account DetailsThe email has been suspended.", "member");
}else if(isset($_GET["action"]) && $_GET["action"] == "convert-credits"){
    $websiteThemeLoader->loadWebsitePage("convert-credits", "Convert Credits", "member");
}else if(isset($_GET["action"]) && $_GET["action"] == "support"){
    $websiteThemeLoader->loadWebsitePage("support", "Contact Us For Support", "member");
}else{
    $websiteThemeLoader->loadWebsitePage("dashboard", "Dashboard", "member");
}