<?php
require_once "load_classes.php";
$websiteThemeLoader = new WebsiteThemeLoaderController();
if(isset($_GET["details"]) && !empty($_GET["details"]) && is_numeric($_GET["details"])){
    $websiteThemeLoader->loadWebsitePage("support-ticket-details", "Support Ticket Details", "member");
}else{
    $websiteThemeLoader->loadWebsitePage("support-tickets", "Support Tickets", "member");
}