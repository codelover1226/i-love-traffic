<?php



$currentPage = (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] === "on" ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

if ($_SERVER["REQUEST_METHOD"] == "GET" && strcmp(basename($currentPage), basename(__FILE__)) == 0) {
    http_response_code(404);
    die("");
}
require_once "themes/default/login-area/incs/header.inc.php";
if (isset($_GET["action"]) && $_GET["action"] == "activate") {
    $flag = $membersController->memberAccountActivation();
}
if (isset($_POST["username"])) {
    $flag = $membersController->login();
}
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
            <input type="password" placeholder="Password" name="password" required>
            <button type="submit">Login</button>
            <a href="forget.php" class="forgot-password">Forgot Password?</a>
            <a href="request-activation.php" class="forgot-password">Request Account Activation</a>
        </form>
    </div>
   <div style="text-align: center;">
    <p><a href="{REF_HOME}" target="_blank"> 
        <iframe frameborder="0" scrolling="no" width="468" height="60" marginwidth="0" marginheight="0" hspace="0" vspace="0" src="https://i-lovetraffic.online/banner-surfer.php?size=web&" ></iframe>
    </a></p>
</div>

    <?php require_once "themes/default/login-area/incs/footer.inc.php" ?>