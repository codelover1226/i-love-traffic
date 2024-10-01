<?php



$currentPage = (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] === "on" ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

if ($_SERVER["REQUEST_METHOD"] == "GET" && strcmp(basename($currentPage), basename(__FILE__)) == 0) {
    http_response_code(404);
    die("");
}

require_once "themes/default/login-area/incs/header.inc.php";
$flag = $membersController->requestPasswordResetMail();
$membersController->generateUserCSRFToken();
?>
<div class="container">

    <div class="login-form">
        <div class="logo-circle">
            <img src="logo2/ILTlogo2.png" alt="Logo">
        </div>
        <?php if (isset($flag) && isset($flag["success"])) : ?>
            <?php if ($flag["success"] == true) : ?>
                <div class="alert-box success" id="successAlert"><?= $flag["message"] ?></div>
            <?php else : ?>
                <div class="alert-box error" id="successAlert"><?= $flag["message"] ?></div>
            <?php endif; ?>
        <?php endif; ?>
        <form action="" method="POST">
            <input type="hidden" name="csrf_token" class="form-control" value="<?= $membersController->getUserCSRFToken() ?>">
            <input type="text" placeholder="Username" name="username" required>
            <button type="submit">Reset</button>
        </form>
    </div>
    <div class="banner-row">
        <?= $bannerAdController->getBannerAd() ?>
    </div>
    <?php require_once "themes/default/login-area/incs/footer.inc.php" ?>