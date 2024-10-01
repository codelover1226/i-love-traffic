<?php
require_once "load_classes.php";
if(isset($_GET["details"]) && !empty($_GET["details"]) && is_numeric($_GET["details"])){
    $websiteThemeLoader = new WebsiteThemeLoaderController();
    $websiteThemeLoader->loadWebsitePage("chat-gpt-prompt-history-details", "ChatGPT Email Details", "member");
}else{
    $websiteThemeLoader = new WebsiteThemeLoaderController();
    $websiteThemeLoader->loadWebsitePage("chat-gpt-prompt-history", "ChatGPT Email History", "member");
}
