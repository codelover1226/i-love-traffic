<?php
require_once "load_classes.php";
$websiteThemeLoader = new WebsiteThemeLoaderController();
if(isset($_GET["action"]) && $_GET["action"] == "add"){
    $websiteThemeLoader->loadWebsitePage("add-small-banner", "Add 125x125 Banner Ad", "member");
}else if(isset($_GET["options"]) && !empty($_GET["options"]) && is_numeric($_GET["options"])){
    $websiteThemeLoader->loadWebsitePage("small-banners-options", "125x125 Banner Ad Options", "member");
}else{
    $websiteThemeLoader->loadWebsitePage("small-banner-ads", "125x125 Banners", "member");
}