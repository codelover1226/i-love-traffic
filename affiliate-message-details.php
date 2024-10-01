<?php
require_once "load_classes.php";
$websiteThemeLoader = new WebsiteThemeLoaderController();
if (isset($_GET["id"]) && !empty($_GET["id"]) && is_numeric($_GET["id"])) {
    $websiteThemeLoader->loadWebsitePage("affiliate-message-details", "Affiliate Message Details", "member");
} else {
    $websiteThemeLoader = new WebsiteThemeLoaderController();
    $websiteThemeLoader->loadWebsitePage("affiliate-messages", "Affiliate Messages", "member");
}
