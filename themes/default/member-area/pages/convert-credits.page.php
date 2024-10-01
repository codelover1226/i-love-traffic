<?php

$currentPage = (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] === "on" ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
if ($_SERVER["REQUEST_METHOD"] == "GET" && strcmp(basename($currentPage), basename(__FILE__)) == 0) {
    http_response_code(404);
    die("");
}
require_once "themes/default/member-area/incs/header.inc.php";
$textAdConversationRate = $otherSettingsController->getTextAdCreditConversation();
$bannerAdConversationRate = $otherSettingsController->getBannerCreditConversation();
if (isset($_POST["text_ad_credit"])) {
    $flag = $membersController->convertCreditToTextAdCredits($username, $userInfo, $textAdConversationRate["settings_value"]);
} else if (isset($_POST["banner_ad_credit"])) {
    $flag = $membersController->convertCreditToBannerCredits($username, $userInfo, $bannerAdConversationRate["settings_value"]);
}
$membersController->generateUserCSRFToken();
?>

<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <?php require_once "themes/default/member-area/incs/sidebar-ads.inc.php"; ?>
            <div class="col-xl-9">
                <?php if (isset($flag) && isset($flag["success"])) : ?>
                    <?php if ($flag["success"] == true) : ?>
                        <div class="alert alert-success"><?= $flag["message"] ?></div>
                    <?php else : ?>
                        <div class="alert alert-danger"><?= $flag["message"] ?></div>
                    <?php endif; ?>
                <?php endif; ?>
                <div class="col-lg-12">
                    <div class="card border border-primary">
                        <div class="card-header bg-transparent border-primary">
                            <h5 class="my-0 text-primary"><i class="mdi mdi-bullseye-arrow me-3"></i>Convert Email Credits into Banner Ad Credits</h5>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-dark">1 Credit equals to <?= $bannerAdConversationRate["settings_value"] ?> Banner Ad Credits</div>
                            <p class="card-text">
                            <form action="" method="POST" accept-charset="utf-8">
                                <input type="hidden" name="csrf_token" value="<?= $membersController->getUserCSRFToken() ?>"">
                                    <div class=" form-group">
                                <label>Credit Amount</label>
                                <input type="number" name="credit_amount" class="form-control" placeholder="How many email credits do you want to convert ?">
                        </div><br>
                        <div class="form-group">
                            <button class="btn btn-primary" name="banner_ad_credit">Convert</button>
                        </div>

                        </form>
                        </p>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="card border border-success">
                        <div class="card-header bg-transparent border-primary">
                            <h5 class="my-0 text-success"><i class="mdi mdi-bullseye-arrow me-3"></i>Convert Email Credits into Text Ad Credits</h5>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-primary">1 Credit equals to <?= $textAdConversationRate["settings_value"] ?> Text Ad Credits</div>
                            <p class="card-text">
                            <form action="" method="POST" accept-charset="utf-8">
                                <input type="hidden" name="csrf_token" value="<?= $membersController->getUserCSRFToken() ?>"">
                                    <div class=" form-group">
                                <label>Credit Amount</label>
                                <input type="number" name="credit_amount" class="form-control" placeholder="How many email credits do you want to convert ?">
                        </div><br>
                        <div class="form-group">
                            <button class="btn btn-primary" name="text_ad_credit">Convert</button>
                        </div>

                        </form>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <?php require_once "themes/default/member-area/incs/footer-ads.inc.php"; ?>

    </div>
</div>
</div>

<?php require_once "themes/default/member-area/incs/footer.inc.php"; ?>