<?php
require_once "load_classes.php";
$websiteThemeLoader = new WebsiteThemeLoaderController();
if(isset($_GET["action"]) && $_GET["action"] == "add"){
    $websiteThemeLoader->loadWebsitePage("add-web-banner-728-90", "Add 728x90 Banner Ad", "member");
}else if(isset($_GET["options"]) && !empty($_GET["options"]) && is_numeric($_GET["options"])){
    $websiteThemeLoader->loadWebsitePage("web-banner-options-728-90", "728x90 Banner Ad Options", "member");
}else{
    $websiteThemeLoader->loadWebsitePage("web-banners-728-90", "728x90 Banners", "member");
}