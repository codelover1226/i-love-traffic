<?php
require_once "load_classes.php";
$websiteThemeLoader = new WebsiteThemeLoaderController();
if(isset($_GET["action"]) && $_GET["action"] == "add"){
    $websiteThemeLoader->loadWebsitePage("add-text-ad", "Add Text Ad", "member");
}else if(isset($_GET["options"]) && !empty($_GET["options"]) && is_numeric($_GET["options"])){
    $websiteThemeLoader->loadWebsitePage("text-ad-options", "Text Ad Options", "member");
}else{
    $websiteThemeLoader->loadWebsitePage("text-ads", "Text Ads", "member");
}