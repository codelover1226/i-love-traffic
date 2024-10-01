<?php



$currentPage = (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] === "on" ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

if ($_SERVER["REQUEST_METHOD"] == "GET" && strcmp(basename($currentPage), basename(__FILE__)) == 0) {
    http_response_code(404);
    die("");
}

require_once "themes/default/login-area/incs/header.inc.php";
$userDetails = $membersController->getUserDetails($_GET["user"]);
$username = $_GET["user"];
$key = $_GET["key"];
if (isset($_POST["password"])) {
    $flag = $membersController->resetMemberPassword($username, $key);
    $userDetails = $membersController->getUserDetails($username);
}

$membersController->generateUserCSRFToken();
?>

<div class="container">

    <div class="login-form">
        <div class="logo-circle">
            <img src="2logo/ILTlogo2.png" alt="Logo">
        </div>
        <?php if (isset($flag) && isset($flag["success"])) : ?>
            <?php if ($flag["success"] == true) : ?>
                <div class="alert-box success" id="successAlert"><?= $flag["message"] ?></div>
            <?php else : ?>
                <div class="alert-box error" id="successAlert"><?= $flag["message"] ?></div>
            <?php endif; ?>
        <?php endif; ?>
        <?php if (empty($userDetails) && !isset($flag["success"])) : ?>
            <div class="alert-box error">Invalid or expired password reset link.</div>
        <?php elseif ($userDetails["password_reset"] != $_GET["key"] && !isset($flag["success"])) : ?>
            <div class="alert-box error">Invalid or expired password reset link.</div>
        <?php elseif (!isset($flag["success"]) || $flag["success"] != true) : ?>

            <form action="" method="POST">
                <input type="hidden" name="csrf_token" class="form-control" value="<?= $membersController->getUserCSRFToken() ?>">
                <input type="password" placeholder="New Password" name="password" required>
                <input type="confirm_password" placeholder="Confirm New Password" name="password" required>
                <button type="submit">Reset</button>
                <a href="forget.php" class="forgot-password">Forgot Password?</a>
                <a href="forget.php" class="forgot-password">Request Account Activation</a>
            </form>
        <?php endif; ?>

    </div>
    <div class="banner-row">
        <?= $bannerAdController->getBannerAd() ?>
    </div>
    <?php require_once "themes/default/login-area/incs/footer.inc.php" ?>