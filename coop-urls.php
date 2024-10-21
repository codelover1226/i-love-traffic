<?php
require_once "load_classes.php";
$websiteThemeLoader = new WebsiteThemeLoaderController();
if(isset($_GET["action"]) && $_GET["action"] == "add"){
    $websiteThemeLoader->loadWebsitePage("add-coop-url", "Add Coop Url", "member");
}else if(isset($_GET["options"]) && !empty($_GET["options"]) && is_numeric($_GET["options"])){
    $websiteThemeLoader->loadWebsitePage("coop-url-options", "Coop Url Options", "member");
}else{
    $websiteThemeLoader->loadWebsitePage("coop-urls", "Coop Urls", "member");
}