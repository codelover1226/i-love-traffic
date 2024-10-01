<?php
require_once "load_classes.php";
$websiteThemeLoader = new WebsiteThemeLoaderController();
if(isset($_GET["action"]) && $_GET["action"] == "add"){
    $websiteThemeLoader->loadWebsitePage("add-web-banner", "Add 468x60 Banner Ad", "member");
}else if(isset($_GET["options"]) && !empty($_GET["options"]) && is_numeric($_GET["options"])){
    $websiteThemeLoader->loadWebsitePage("web-banner-options", "468x60 Banner Ad Options", "member");
}else{
    $websiteThemeLoader->loadWebsitePage("web-banners", "468x60 Banners", "member");
}