<?php
require_once "load_classes.php";
$websiteThemeLoader = new WebsiteThemeLoaderController();
if(
    isset($_GET["action"]) && 
    $_GET["action"] = "reset" && 
    isset($_GET["user"]) && 
    !empty($_GET["user"]) && 
    isset($_GET["key"]) && 
    !empty($_GET["key"])){
        $websiteThemeLoader->loadWebsitePage("reset-password", "Reset Your Password", "login");
}else{
    $websiteThemeLoader->loadWebsitePage("password-reset-request", "Reset Your Password", "login");
}
