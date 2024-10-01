<?php
require_once "load_classes.php";
$websiteThemeLoader = new WebsiteThemeLoaderController();
if (isset($_GET["details"]) && !empty($_GET["details"])) {
    $websiteThemeLoader->loadWebsitePage("shorten-link-details", "Tracking Link Details", "member");
} else if (isset($_GET["action"]) && $_GET["action"] == "add") {
    $websiteThemeLoader->loadWebsitePage("add-shorten-link", "Add New Tracking Link", "member");
} else {
    $websiteThemeLoader->loadWebsitePage("shorten-links", "Tracking Links", "member");
}
