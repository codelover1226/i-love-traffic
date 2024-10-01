<?php
require_once "load_classes.php";
$websiteThemeLoader = new WebsiteThemeLoaderController();

if (isset($_GET["action"]) && $_GET["action"] == "add") {
    $websiteThemeLoader->loadWebsitePage("add-downline-program", "Add Affiliate Program", "member");
} else if (isset($_GET["action"]) && $_GET["action"] == "my-programs") {
    $websiteThemeLoader->loadWebsitePage("my-downline-builder", "My Downline Builder", "member");
} else if (isset($_GET["action"]) && $_GET["action"] == "admin") {
    $websiteThemeLoader->loadWebsitePage("admin-downline", "Admin Recommended", "member");
} else {
    $websiteThemeLoader->loadWebsitePage("downline-builder", "Downline Builder", "member");
}
