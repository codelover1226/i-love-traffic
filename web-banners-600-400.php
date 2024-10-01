<?php
require_once "load_classes.php";
$websiteThemeLoader = new WebsiteThemeLoaderController();
if(isset($_GET["action"]) && $_GET["action"] == "add"){
    $websiteThemeLoader->loadWebsitePage("add-web-banner-600-400", "Add 600x400 Banner Ad", "member");
}else if(isset($_GET["options"]) && !empty($_GET["options"]) && is_numeric($_GET["options"])){
    $websiteThemeLoader->loadWebsitePage("web-banner-options-600-400", "600x400 Banner Ad Options", "member");
}else{
    $websiteThemeLoader->loadWebsitePage("web-banners-600-400", "600x400 Banners", "member");
}