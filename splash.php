<?php
/*
 *
 *
 *          Author          :   Noman Prodhan
 *          Email           :   hello@nomantheking.com
 *          Websites        :   www.nomantheking.com    www.nomanprodhan.com    www.nstechvalley.com
 *
 *
 */

require_once "incs/header_code.inc.php";
if (isset($_GET["id"]) && !empty($_GET["id"]) && is_numeric($_GET["id"])) {
    $splashPageDetails = $splashPagesController->getSplashPageDetails($_GET["id"]);
    if (empty($splashPageDetails)) {
        echo "Invalid Splash Page";
        exit();
    } else {
        $siteController = new SiteSettingsController();
        $siteSettings = $siteController->getSettings();
        $splashPageContent = htmlspecialchars_decode($splashPageDetails["splash_page_content"]);
        if (isset($_GET["referrer"])) {
            $refHomeLink = $siteSettings["installation_url"] . "index.php?referrer=" . $_GET["referrer"];
            $refRegLink = $siteSettings["installation_url"] . "register.php?referrer=" . $_GET["referrer"];
            $splashPageContent = str_ireplace("{REF_HOME}", $refHomeLink, $splashPageContent);
            $splashPageContent = str_ireplace("{REF_REG}", $refRegLink, $splashPageContent);
        }
        echo $splashPageContent;
    }
} else {
    echo "Invalid Splash Page";
    exit();
}
