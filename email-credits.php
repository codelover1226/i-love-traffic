<?php
ob_start();
session_start();
require_once "load_classes.php";
$bannerAdsController = new BannerAdsController();
$siteSettingsController = new SiteSettingsController();
$membersController = new MembersController();
$siteSettingsData = $siteSettingsController->getSettings();
$websiteLink = "";
$timer = "";
$credits = "";
$timerStart = time();
$_SESSION["is_clicking"] = true;
$clickerUsername = "";
$emailClickController = new EmailClicksController();
$todayClicks = "";
$randomRewardsController = new RandomRewardsController();
$randomRewardsSettings = $randomRewardsController->getSettings();
if (isset($_GET["type"]) && isset($_GET["id"])) {
    if ($_GET["type"] == "email" && isset($_GET["username"]) && !empty($_GET["username"])) {
        $emailsController = new EmailsController();
        $emailDetails = $emailsController->getMailDetailsByCreditKey($_GET["id"]);
        $userDetails = $membersController->getUserDetails($_GET["username"]);
        $clickerUsername = $_GET["username"];
        if (empty($userDetails)) {
            $error = "Invalid user !";
        } else if ($userDetails["account_status"] != 1) {
            $error = "Your account is not active or you have unsubscribed from our emails.";
        } else {
            if (empty($emailDetails)) {
                $error = "Invalid email;";
                $websiteLink = $siteSettingsData["installation_url"];
            } else if ($emailDetails["email_status"] != 2) {
                $error = "Invalid email;";
                $websiteLink = $siteSettingsData["installation_url"];
            } else {
                $emailClickController = new EmailClicksController();
                if ($emailClickController->checkUserEmailClick($_GET["username"], $emailDetails["id"]) > 0) {
                    $error = "You have already visited this website.";
                    $websiteLink = base64_decode($emailDetails["website_link"]);
                } else {
                    $todayClicks = $emailClickController->totalClicksToday($_GET["username"]);
                    $websiteLink = base64_decode($emailDetails["website_link"]);
                    $timer = $userDetails["timer_seconds"];
                    $credits = $userDetails["credits_per_click"];
                    $randomRewardsMessage = $randomRewardsController->giveRandomRewards($_GET["username"], $todayClicks);
                }
            }
        }
    } else if ($_GET["type"] == "loginads") {
        $membersController->verifyLoggedIn("logged_in");
        $username = $_SESSION["logged_username"];
        $loginAdsController = new LoginAdsController();
        // $loginAdsController = new LoginSpotlightAdsController();
        $loginAdDetails = $loginAdsController->getLoginAdDetails($_GET["id"]);
        if (empty($loginAdDetails)) {
            $error = "Invalid login spotlight ad.";
        } else {
            $userDetails = $membersController->getUserDetails($username);
            $timer = $userDetails["timer_seconds"];
            $adTitle = "Login Ad";
            $loginAdClickController = new LoginSpotlightAdClickController();
            if ($loginAdClickController->getTodayAdCount($username, $loginAdDetails["id"]) > 0) {
                $error = "Log in Reward already claimed.";
                $websiteLink = $loginAdDetails["ad_link"];
            } else {
                // $credits = $loginAdDetails["user_credits"];
                $websiteLink = $loginAdDetails["ad_link"];
                $credits = $userDetails["credits_per_login"];
            }
        }
    } else {
        $websiteLink = $siteSettingsData["installation_url"];
        $error = "Invalid credit link.";
    }
} else {
    $websiteLink = $siteSettingsData["installation_url"];
    $error = "Invalid credit link.";
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $siteSettingsData["site_title"] ?></title>
</head>
<style>
    body,
    html {
        margin: 0;
        padding: 0;
        font-family: Arial, sans-serif;
        height: 100%;
        overflow: hidden;
    }

    .top-bar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        background-color: #030E33;
        padding: 10px;
        color: white;
    }

    .logo {
        height: 50px;
    }

    .info {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
    }

    .timer {
        font-size: 14px;
        margin-bottom: 5px;
    }

    .user-stats {
        font-size: 14px;
        margin-bottom: 5px;
        background-color: #935656;
        padding: 5px;
        width: 150px;
    }

    .rewards-badge {
        background-color: #4CAF50;
        color: white;
        padding: 5px;
        width: 150px;
    }

    .buttons {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
    }

    .button {
        text-decoration: none;
        color: white;
        background-color: #935656;
        padding: 10px;
        margin-bottom: 5px;
        border-radius: 5px;
        text-align: center;
        width: 150px;
    }

    .button:hover {
        background-color: #4CAF50;
    }

    .banner img {
        width: 468px;
        height: 60px;
    }

    .iframe-content {
        width: 100%;
        height: calc(100% - 70px);
        border: none;
        overflow: auto;
    }

    @media screen and (max-width: 768px) {
        .top-bar {
            flex-direction: column;
            align-items: start;
        }

        .banner img {
            width: 100%;
            height: auto;
        }

        .iframe-content {
            height: calc(100% - 150px);
        }
    }


    .progressbar {
        width: 318px;
        height: 15px;
        background: url(images/surfer/progressbar.png) no-repeat;
        margin-top: 5px;
        margin-left: 100px;
        border-radius: 4px;
        overflow: hidden;
    }

    .progressbar div {
        height: 15px;
        background: url(images/surfer/progressbg_green1.gif) no-repeat;
        ;
        width: 3px;
        max-width: 320px;
        margin-left: 0px;
    }

    
    #progress-container {
        height: 10px;
        width: 400px;
        margin-top: 25px;
        background-color: #ffffff;
        border-radius: 5px;
        position: relative;
    }

    #progress-container.progress-bar {
        position: absolute;
        height: 100%;
        border-radius: 5px;
        background-color: #0fdb83;
        animation: progress-animation 10s forwards;
    }

    @keyframes progress-animation {
        0% {
            width: 0%;
        }

        100% {
            width: 100%;
        }
    }

    .loading-bar {
        width: 100%;
        background-color: #ddd;
        border-radius: 5px;
        overflow: hidden;
        margin-bottom: 5px;
    }

    .loading-progress {
        height: 10px;
        background-color: #4CAF50;
        width: 0%;
        border-radius: 5px;
        animation: loadProgress <?= $timer ?>s ease-in-out forwards;
    }

    @keyframes loadProgress {
        to {
            width: 100%;
        }
    }

    .success-message {
        color: #155724;
        background-color: #d4edda;
        border-color: #c3e6cb;
        padding: 10px;
        margin-top: 5px;
        margin-bottom: 5px;
        border-radius: 5px;
        border: 1px solid transparent;
        font-size: 14px;
        text-align: center;
    }
</style>

<body>
    <div class="top-bar">
        <img src="logo2/logo_wide2.png" alt="Logo" class="logo">
        <div class="info">
            <?php if (isset($error)) : ?>
                <p style="font-weight: bold;"><?= $error ?></p>
            <?php else : ?>
                <div id="progress-container" class="loading-bar">
                    <div class="loading-progress"></div>
                </div>
                <div id="congrats" hidden>
                    <?php if (!empty($credits)) : ?>
                        <div class="success-message">You have earned <?= $credits ?> credits</div>
                    <?php else : ?>
                        <div class="success-message">You have earned credits</div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            <?php if (!empty($todayClicks)) : ?>
                <span class="user-stats">Reads Today : <?= $todayClicks ?></span>
            <?php endif; ?>
            
        </div>
        <div class="buttons">
            <a href="dashboard.php?action=offer-page" class="button">Dashboard</a>

            <?php if (!empty($websiteLink)) : ?>
                <a href="<?= $websiteLink ?>" target="_blank" class="button">Open in New Tab</a>
            <?php endif; ?>
        </div>
        <div class="banner">
            <?= $bannerAdsController->getBannerAd() ?>
        </div>
    </div>
    <iframe src="<?= $websiteLink ?>" class="iframe-content"></iframe>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script>
        $('#progress-container').bind('animationend webkitAnimationEnd oAnimationEnd MSAnimationEnd', function(e) {
            $(this).remove();
        });
        $.ajaxSetup({
            async: false
        });
        $('#progress-container').bind('animationend webkitAnimationEnd oAnimationEnd MSAnimationEnd', function(e) {
            $.get('email-credits-confirm.php', {
                type: '<?= $_GET["type"] ?>',
                id: '<?= $_GET["id"] ?>',
                timer_start: '<?= $timerStart ?>',
                username: '<?= $clickerUsername ?>'
            }, function(data, textStatus, jqXHR) {
                $('#successMessage').text(data)
            });
        });
        $('#progress-container').bind('animationend webkitAnimationEnd oAnimationEnd MSAnimationEnd', function(e) {
            $("#congrats").show();
        });
    </script>
</body>

</html>