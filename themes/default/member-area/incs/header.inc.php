<?php
ob_start();



$currentPage = (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] === "on" ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

if ($_SERVER["REQUEST_METHOD"] == "GET" && strcmp(basename($currentPage), basename(__FILE__)) == 0) {
    http_response_code(404);
    die("");
}
require_once "incs/header_code.inc.php";
$membersController->verifyLoggedIn("logged_in");
$username = $_SESSION["logged_username"];
$membersController->logout($username);
$userInfo = $membersController->getUserDetails($username);
if (isset($_GET["action"]) && $_GET["action"] == "offer-page") {
    $specialOfferPageController = new SpecialOfferPagesController();
    $loginOfferPage = $specialOfferPageController->getLoginOfferPage();
    var_dump($loginOfferPage);
    if (!empty($loginOfferPage)) {
        header("Location: special-offer.php?id={$loginOfferPage['id']}");
        exit();
    }
}
?>
<!doctype html>
<html lang="en" data-layout="vertical" data-layout-style="detached" data-sidebar="light" data-topbar="dark" data-sidebar-size="lg" data-sidebar-image="none" data-preloader="disable">

<head>

    <meta charset="UTF-8">
    <meta name="description" content="<?= $siteSettingsData['meta_description'] ?>" />
    <meta name="keywords" content="<?= $siteSettingsData['meta_keywords'] ?>" />
    <meta property="og:title" content="<?= $siteSettingsData['site_title'] ?>" />
    <meta property="og:description" content="<?= $siteSettingsData['meta_description'] ?>" />
    <meta property="og:image" content="<?= $siteSettingsData['banner_image'] ?>" />
    <title><?= isset($title) ? $title . " | " . $siteSettingsData["site_title"] : $siteSettingsData["site_title"] ?></title>
    <link rel="shortcut icon" href="logo2/favicon.ico">


    <link href="themes/default/member-area/assets/libs/jsvectormap/css/jsvectormap.min.css" rel="stylesheet" type="text/css" />


    <script src="themes/default/member-area/assets/js/layout.js"></script>

    <link href="themes/default/member-area/assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />

    <link href="themes/default/member-area/assets/css/icons.min.css" rel="stylesheet" type="text/css" />

    <link href="themes/default/member-area/assets/css/app.min.css" rel="stylesheet" type="text/css" />

    <link href="themes/default/member-area/assets/css/custom.min.css" rel="stylesheet" type="text/css" />
    <style>
        @font-face {
            font-family: 'Noto Emoji';
            src: url('themes/default/member-area/fonts/NotoColorEmoji.ttf') format('truetype');
            font-weight: normal;
            font-style: normal;
        }

        .white-text {
            color: #ffffff !important;
        }

        .ck .ck-reset .ck-editor .ck-rounded-corners {
            max-width: 700px !important;
        }

        .ck-editor__editable {
            min-height: 400px !important;
            color: #000000;
        }

        .ck.ck-editor__main>.ck-editor__editable {
            border-radius: 0
        }

        pre {
            box-sizing: border-box;
            width: 700px;
            padding: 0;
            margin: 0;
            overflow: auto;
            overflow-y: auto;
            overflow-x: auto;
            max-height: 500px;
            font-size: 12px;
            line-height: 20px;
            background: #efefef;
            border: 1px solid #08ffec;
            background: #083338;
            padding: 10px;
            color: #02f681;
        }

        .ck-content pre {
            padding: 1em;
            color: #ffffff !important;
            font-family: 'Outfit', 'Noto Emoji';
        }

        p {
            /* font-family: 'Outfit', 'Noto Emoji' !important; */
        }

        .ck p {
            font-family: 'Outfit', 'Noto Emoji' !important;
        }

        .ck_content {
            font-family: 'Outfit', 'Noto Emoji' !important;
        }


        body {
            /* font-family: 'Outfit', 'Noto Emoji'; */
        }

        .text-ad-title {
            font-size: 18px;
            font-weight: bold;
        }

        .text-ad-description {
            font-size: 14px;
        }
    </style>

</head>

<body>


    <div id="layout-wrapper">

        <header id="page-topbar">
            <div class="layout-width">
                <div class="navbar-header">
                    <div class="d-flex">

                        <div class="navbar-brand-box horizontal-logo">
                            <a href="dashboard.php" class="logo logo-dark">
                                <span class="logo-sm">
                                    <img src="themes/default/member-area/assets/images/logo-sm.png" alt="" height="22">
                                </span>
                                <span class="logo-lg">
                                    <img src="themes/default/member-area/assets/images/logo-dark.png" alt="" height="17">
                                </span>
                            </a>

                            <a href="dashboard.php" class="logo logo-light">
                                <span class="logo-sm">
                                    <img src="logo/ILTlogo.png" alt="" height="33">
                                </span>
                                <span class="logo-lg">
                                    <img src="logo/ILTlogo.png" alt="" height="30">
                                </span>
                            </a>
                        </div>

                        <button type="button" class="btn btn-sm px-3 fs-16 header-item vertical-menu-btn topnav-hamburger" id="topnav-hamburger-icon">
                            <span class="hamburger-icon">
                                <span></span>
                                <span></span>
                                <span></span>
                            </span>
                        </button>
                    </div>

                    <div class="d-flex align-items-center">
                        <div class="ms-1 header-item d-none d-sm-flex">
                            <button type="button" class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle" data-toggle="fullscreen">
                                <i class='bx bx-fullscreen fs-22'></i>
                            </button>
                        </div>

                        <div class="ms-1 header-item d-none d-sm-flex">
                            <button type="button" class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle light-dark-mode">
                                <i class='bx bx-moon fs-22'></i>
                            </button>
                        </div>
                        <div class="dropdown ms-sm-3 header-item topbar-user">
                            <button type="button" class="btn" id="page-header-user-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="d-flex align-items-center">
                                    <img class="rounded-circle header-profile-user" src="<?= $membersController->gravatar($userInfo['email'], $siteSettingsData['installation_url']) ?>" alt="Header Avatar">
                                    <span class="text-start ms-xl-2">
                                        <span class="d-none d-xl-inline-block ms-1 fw-semibold user-name-text"><?= $userInfo["username"] ?></span>
                                        <span class="d-none d-xl-block ms-1 fs-13 user-name-sub-text"><?= $userInfo["membership_title"] ?></span>
                                    </span>
                                </span>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end">
                                <h6 class="dropdown-header">Welcome <?= $userInfo["first_name"] ?>!</h6>
                                <a class="dropdown-item" href="dashboard.php?action=account"><i class="mdi mdi-account-circle text-muted fs-16 align-middle me-1"></i> <span class="align-middle">Edit Profile</span></a>
                                <a class="dropdown-item" href="dashboard.php?action=password"><i class="mdi mdi-lock text-muted fs-16 align-middle me-1"></i> <span class="align-middle">Change Password</span></a>
                                <a class="dropdown-item" href="dashboard.php?action=vacation"><i class="mdi mdi-cog-outline text-muted fs-16 align-middle me-1"></i> <span class="align-middle">Vacation Settings</span></a>
                                <a class="dropdown-item" href="dashboard.php?action=email-subscription"><i class="mdi mdi-message-text-outline text-muted fs-16 align-middle me-1"></i> <span class="align-middle">Email Subscription</span></a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#"><i class="mdi mdi-wallet text-muted fs-16 align-middle me-1"></i> <span class="align-middle">Balance : <b>$<?= $userInfo["balance"] ?></b></span></a>
                                <a class="dropdown-item" href="?logout=<?= $_SESSION['user_login_csrf'] ?>"><i class="mdi mdi-logout text-muted fs-16 align-middle me-1"></i> <span class="align-middle" data-key="t-logout">Logout</span></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <div class="app-menu navbar-menu">

            <div class="navbar-brand-box">

                <a href="dashboard.php" class="logo logo-dark">
                    <span class="logo-sm">
                        <img src="themes/default/member-area/assets/images/logo-sm.png" alt="" height="22">
                    </span>
                    <span class="logo-lg">
                        <img src="themes/default/member-area/assets/images/logo-dark.png" alt="" height="17">
                    </span>
                </a>

                <a href="dashboard.php" class="logo logo-light">
                    <span class="logo-sm">
                        <img src="themes/default/member-area/assets/images/logo-sm.png" alt="" height="22">
                    </span>
                    <span class="logo-lg">
                        <img src="themes/default/member-area/assets/images/logo-light.png" alt="" height="17">
                    </span>
                </a>
                <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover" id="vertical-hover">
                    <i class="ri-record-circle-line"></i>
                </button>
            </div>

            <div id="scrollbar">
                <div class="container-fluid">

                    <div id="two-column-menu">
                    </div>
                    <ul class="navbar-nav" id="navbar-nav">
                        <li class="menu-title"><span data-key="t-menu">Menu</span></li>
                        <li class="nav-item">
                            <a class="nav-link menu-link" href="dashboard.php">
                                <i class="ri-dashboard-2-line"></i> <span>Dashboard</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link menu-link" href="store.php">
                                <i class="ri-store-2-line"></i> <span>Store</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link menu-link" href="order-history.php">
                                <i class="ri-file-list-3-line"></i> <span>Order History</span>
                            </a>
                        </li>
                        <?php include_once "themes/default/member-area/incs/sub-menus/chat-gpt.menu.php"; ?>
                        <?php include_once "themes/default/member-area/incs/sub-menus/email.menu.php"; ?>
                        <li class="nav-item">
                            <a class="nav-link menu-link" href="dashboard.php?action=convert-credits">
                                <i class="ri-exchange-line"></i> <span>Convert Credits</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link menu-link" href="banner-publisher.php">
                                <i class="ri-chat-upload-line"></i> <span>Earn Banner Credits</span>
                            </a>
                        </li>
                        <li class="nav-item">
<a class="nav-link menu-link" href="voucher.php">
<i class="ri-store-2-line"></i> <span>Enter Promo Code</span>
</a>
</li>
<a class="nav-link menu-link" href="#sidebarLinkTracker" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarLinkTracker">
<i class="ri-links-line"></i> <span data-key="t-emails">Link Tracker</span>
</a>
 <div class="collapse menu-dropdown" id="sidebarLinkTracker">
 <ul class="nav nav-sm flex-column">
 <li class="nav-item">
 <a href="link-tracker.php" class="nav-link" data-key="t-calendar">Tracking Links</a>
 </li>
 <li class="nav-item">
 <a href="link-tracker.php?action=add" class="nav-link" data-key="t-chat">New Link</a>
 </li>
 </ul>
 </div>
 </li>
<?php include_once "themes/default/member-area/incs/sub-menus/468x60.menu.php"; ?>
<?php include_once "themes/default/member-area/incs/sub-menus/160x600.menu.php"; ?>
<?php include_once "themes/default/member-area/incs/sub-menus/728x90.menu.php"; ?>
<?php include_once "themes/default/member-area/incs/sub-menus/125x125.menu.php"; ?>
<?php include_once "themes/default/member-area/incs/sub-menus/600x400.menu.php"; ?>
<?php include_once "themes/default/member-area/incs/sub-menus/text-ads.menu.php"; ?>
<?php include_once "themes/default/member-area/incs/sub-menus/login-ads.menu.php"; ?>

<?php include_once "themes/default/member-area/incs/sub-menus/affiliates.menu.php"; ?>
<?php include_once "themes/default/member-area/incs/sub-menus/affiliate-messaging.menu.php"; ?>
<?php include_once "themes/default/member-area/incs/sub-menus/contests.menu.php"; ?>
<?php include_once "themes/default/member-area/incs/sub-menus/support.menu.php"; ?>
            </ul>
        </div>
    </div>

    <div class="sidebar-background"></div>
</div>
<div class="vertical-overlay"></div>
<div class="main-content">