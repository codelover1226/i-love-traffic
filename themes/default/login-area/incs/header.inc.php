<?php



$currentPage = (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] === "on" ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

if ($_SERVER["REQUEST_METHOD"] == "GET" && strcmp(basename($currentPage), basename(__FILE__)) == 0) {
    http_response_code(404);
    die("");
}
require_once "incs/header_code.inc.php";
$membersController->verifyLoggedIn("login");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <LINK REL="SHORTCUT ICON" HREF="logo2/favicon.ico">
    <meta name="description" content="<?= $siteSettingsData['meta_description'] ?>" />
    <meta name="keywords" content="<?= $siteSettingsData['meta_keywords'] ?>" />
    <meta property="og:title" content="<?= $siteSettingsData['site_title'] ?>" />
    <meta property="og:description" content="<?= $siteSettingsData['meta_description'] ?>" />
    <meta property="og:image" content="<?= $siteSettingsData['banner_image'] ?>" />
    <title><?= isset($title) ? $title . " | " . $siteSettingsData["site_title"] : $siteSettingsData["site_title"] ?></title>
    <!-- <link rel="shortcut icon" href="logo2/2logo.png"> -->
    <link rel="shortcut icon" href="logo2/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="themes/default/login-area/style.css">
</head>

<body>
    <div class="overlay"></div>

    <nav class="navbar">
        <a href="index.php">Home</a>
        <a href="register.php">Register</a>
        <a href="login.php">Login</a>
    </nav>