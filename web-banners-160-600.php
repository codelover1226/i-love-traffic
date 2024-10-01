<?php
require_once "load_classes.php";
$websiteThemeLoader = new WebsiteThemeLoaderController();
if(isset($_GET["action"]) && $_GET["action"] == "add"){
    $websiteThemeLoader->loadWebsitePage("add-web-banner-160-600", "Add 160x600 Banner Ad", "member");
}else if(isset($_GET["options"]) && !empty($_GET["options"]) && is_numeric($_GET["options"])){
    $websiteThemeLoader->loadWebsitePage("web-banner-options-160-600", "160x600 Banner Ad Options", "member");
}else{
    $websiteThemeLoader->loadWebsitePage("web-banners-160-600", "160x600 Banners", "member");
}