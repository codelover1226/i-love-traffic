<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once "load_classes.php";
$websiteThemeLoader = new WebsiteThemeLoaderController();
$websiteThemeLoader->loadWebsitePage("login", "Login", "login");