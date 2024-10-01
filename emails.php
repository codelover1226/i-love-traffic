<?php
require_once "load_classes.php";
$websiteThemeLoader = new WebsiteThemeLoaderController();
if (isset($_GET["action"]) && $_GET["action"] == "send") {
    $websiteThemeLoader->loadWebsitePage("send-mail", "Send New Email", "member");
} else if (isset($_GET["action"]) && $_GET["action"] == "schedule") {
    $websiteThemeLoader->loadWebsitePage("schedule-mail", "Schedule New Email", "member");
} else if (isset($_GET["action"]) && $_GET["action"] == "saved") {
    $websiteThemeLoader->loadWebsitePage("saved-emails", "Saved Emails", "member");
} else if (isset($_GET["action"]) && $_GET["action"] == "add-draft") {
    $websiteThemeLoader->loadWebsitePage("add-draft-email", "Save a New Email", "member");
} else if (isset($_GET["action"]) && $_GET["action"] == "auto-mail") {
    $websiteThemeLoader->loadWebsitePage("update-auto-mail", "Update Auto Mail", "member");
} else if (
    isset($_GET["action"]) && $_GET["action"] == "edit-saved" && 
    isset($_GET["id"]) && !empty($_GET["id"]) && is_numeric($_GET["id"]) && $_GET["id"] > 0) {
    $websiteThemeLoader->loadWebsitePage("edit-draft-email", "Edit Draft Email", "member");
} else {
    $websiteThemeLoader->loadWebsitePage("email-history", "Email History", "member");
}
