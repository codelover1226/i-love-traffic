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
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <meta name="description" content="<?= $siteSettingsData['meta_description'] ?>" />
  <meta name="keywords" content="<?= $siteSettingsData['meta_keywords'] ?>" />
  <meta property="og:title" content="<?= $siteSettingsData['site_title'] ?>" />
  <meta property="og:description" content="<?= $siteSettingsData['meta_description'] ?>" />
  <meta property="og:image" content="<?= $siteSettingsData['banner_image'] ?>" />
  <title><?= isset($title) ? $title . " | " . $siteSettingsData["site_title"] : $siteSettingsData["site_title"] ?></title>

  <link rel="stylesheet" href="themes/default/general-area/assets/css/bootstrap.min.css">
  <link rel="stylesheet" href="themes/default/general-area/assets/css/all.min.css">
  <link rel="stylesheet" href="themes/default/general-area/assets/css/animate.css">
  <link rel="stylesheet" href="themes/default/general-area/assets/css/nice-select.css">
  <link rel="stylesheet" href="themes/default/general-area/assets/css/owl.min.css">
  <link rel="stylesheet" href="themes/default/general-area/assets/css/jquery-ui.min.css">
  <link rel="stylesheet" href="themes/default/general-area/assets/css/magnific-popup.css">
  <link rel="stylesheet" href="themes/default/general-area/assets/css/flaticon.css">
  <link rel="stylesheet" href="themes/default/general-area/assets/css/main.css">
  <link rel="shortcut icon" href="logo2/favicon.ico" type="image/x-icon">
</head>

<body>
  <div class="preloader">
    <div class="preloader-inner">
      <div class="preloader-icon">
        <span></span>
        <span></span>
      </div>
    </div>
  </div>
  <a href="#0" class="scrollToTop"><i class="fas fa-angle-up"></i></a>
  <div class="overlay"></div>

  <header class="header-section">
    <div class="container">
      <div class="header-wrapper">
        <div class="logo">
          <a href="index.php">
            <img src="themes/default/general-area/assets/images/logo/ILTlogo.png" alt="logo">
          </a>
        </div>
        <ul class="menu">
          <li>
            <a href="index.php">Home</a>
          </li>
          <li>
            <a href="faqs.php">FAQs</a>
          </li>
          <li>
          <a href="https://easyonlineadvertising.com/support" target="_blank" rel="noopener">Support</a>
           </li>
          <li>
            <a href="login.php">Login</a>
          </li>
          <li>
            <a href="register.php" class="m-0 header-button">Register</a>
          </li>
        </ul>
      </div>
    </div>
  </header>
 <section class="banner-3 bg_img oh" data-background="themes/default/general-area/assets/images/banner/ILT5b.png">
  <div class="container">
    <div class="row align-items-center">
      <div class="col-lg-7">
        <div class="banner-content-3 cl-white">
          <br><br><br><br><br><br>
          <p>
            <h3 style="color: white; font-family: 'Dancing Script', cursive; font-size: 40px; text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.6);">
              You've just found powerful email advertising and viral banner distribution!
            </h3>
          </p>
          <div class="banner-button-group">
            <a href="register.php" class="button-4">Get your Free Account Now!</a>
          </div>
        </div>
      </div>
      <div class="col-lg-5 d-none d-lg-block">
 <div class="banner-thumb-3">
 
 </div>
 </div>
    </div>
  </div>
</section>