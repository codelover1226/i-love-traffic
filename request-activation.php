<?php
require_once "load_classes.php";
$websiteThemeLoader = new WebsiteThemeLoaderController();
$websiteThemeLoader->loadWebsitePage("request-activation", "Request Activation Email", "login");