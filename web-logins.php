<?php
require_once "load_classes.php";
$websiteThemeLoader = new WebsiteThemeLoaderController();
if(isset($_GET["action"]) && $_GET["action"] == "add"){
    $websiteThemeLoader->loadWebsitePage("add-web-login", "Add Login Ad", "member");
}else if(isset($_GET["options"]) && !empty($_GET["options"]) && is_numeric($_GET["options"])){
    $websiteThemeLoader->loadWebsitePage("web-login-options", "Login Ad Options", "member");
}else{
    $websiteThemeLoader->loadWebsitePage("web-logins", "Logins Ads", "member");
}