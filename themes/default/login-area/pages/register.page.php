<?php



$currentPage = (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] === "on" ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

if ($_SERVER["REQUEST_METHOD"] == "GET" && strcmp(basename($currentPage), basename(__FILE__)) == 0) {
    http_response_code(404);
    die("");
}

require_once "themes/default/login-area/incs/header.inc.php";
$flag = $membersController->createNewAccount();
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
        <?php if ($siteSettingsData["anti_cheat_system"] == 1 && !empty($membersController->userIPInfo($_SERVER["REMOTE_ADDR"])) && !isset($flag["success"])) : ?>
            <div class="alert-box error">Someone already created an account form this IP <?= $_SERVER["REMOTE_ADDR"] ?> </div>
        <?php endif; ?>
        <form action="" method="POST">
            <input type="hidden" name="csrf_token" value="<?= $membersController->getUserCSRFToken() ?>" class="form-control">
            <input type="text" name="first_name" placeholder="First Name" value="" class="form-control" required>
            <input type="text" name="last_name" placeholder="Last Name" value="" class="form-control" required>
            <input type="email" name="email" placeholder="Email-Use gmail please" value="" class="form-control" required>
            <input type="text" name="username" placeholder="Username" value="" class="form-control" required>
            <input type="password" name="password" placeholder="Password" value="" class="form-control" required>
            <input type="password" name="confirm_password" placeholder="Confirm Password" class="form-control" required>
            <?= $siteSettings->getGoogleREcaptcha() ?><br>
            <input type="checkbox" name="tos" value="agree" class="form-check-input">
            I have read, and agree to <a href="tos.php">Terms of Services</a>
            <label class="form-check-label">
                <p style="color: red;"><?= isset($_COOKIE["nsms_affiliate"]) ? "You are referred by " . $_COOKIE["nsms_affiliate"] : "" ?></p>
            </label>
            <button type="submit">Register</button>
        </form>
    </div>
    

    <?php require_once "themes/default/login-area/incs/footer.inc.php" ?>