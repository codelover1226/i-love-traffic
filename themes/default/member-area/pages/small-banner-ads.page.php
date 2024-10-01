<?php

$currentPage = (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] === "on" ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
if ($_SERVER["REQUEST_METHOD"] == "GET" && strcmp(basename($currentPage), basename(__FILE__)) == 0) {
    http_response_code(404);
    die("");
}
require_once "themes/default/member-area/incs/header.inc.php";
if (isset($_GET["pause"])) {
    $flag = $smallBannerAdController->pauseUserAd($username, $_GET["pause"]);
} else if (isset($_GET["activate"])) {
    $flag = $smallBannerAdController->activateUserAd($username, $_GET["activate"]);
} else if (isset($_GET["delete"])) {
    $flag = $smallBannerAdController->deleteUserAd($username, $_GET["delete"]);
}
$membersController->generateUserCSRFToken();
$bannerAdsList = $smallBannerAdController->userBannerAdsList($username);
function formatNumberDigits($number) {
    if ($number > 10000) {
        $divide = $number / 1000;
        return number_format($divide, 2, ".", ",") . "K";
    } else {
        return $number;
    }
}
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
                        <table class="table table-striped">
                            <thead>
                                <td>Banner</td>
                                <td>Link</td>
                                <td>Credits</td>
                                <td>Views</td>
                                <td>Clicks</td>
                                <td>Status</td>
                                <td></td>
                            </thead>
                            <?php if (!empty($bannerAdsList)) : ?>
                            <?php foreach ($bannerAdsList as $adData) : ?>
                            <tr>
                                <td><a href="<?= $adData['image_link'] ?>" target="_blank"><img
                                            src="<?= $adData['image_link'] ?>" height="50" width="50" /></a></td>
                                <td><?= strlen($adData["ad_link"]) > 15 ? substr($adData["ad_link"], 0, 15) . "..." : $adData["ad_link"] ?>
                                </td>
                                <td><?= $adData["credits"] ?></td>
                                <td><?= formatNumberDigits($adData["total_views"]) ?></td>
                                <td><?= formatNumberDigits($adData["total_clicks"]) ?></td>
                                <td>
                                    <?= $smallBannerAdController->adStatus()[$adData["status"] - 1] ?>
                                </td>
                                <td>
                                    <a href="small-banners.php?options=<?= $adData['id'] ?>">
                                        <button class="btn btn-danger btn-sm">Options</button>
                                    </a> &nbsp;
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            <?php endif; ?>
                        </table>
                    </div>
                    <?php $smallBannerAdController->userBannerAdsPagination($username) ?>
                </div>

                <?php require_once "themes/default/member-area/incs/footer-ads.inc.php"; ?>
            </div>
        </div>

    </div>

    <?php require_once "themes/default/member-area/incs/footer.inc.php"; ?>