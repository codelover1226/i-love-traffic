<?php

$currentPage = (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] === "on" ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
if ($_SERVER["REQUEST_METHOD"] == "GET" && strcmp(basename($currentPage), basename(__FILE__)) == 0) {
    http_response_code(404);
    die("");
}
require_once "themes/default/member-area/incs/header.inc.php";
if (isset($_GET["pause"])) {
    $flag = $coopUrlsController->pauseUserAd($username, $_GET["pause"]);
} else if (isset($_GET["activate"])) {
    $flag = $coopUrlsController->activateUserAd($username, $_GET["activate"]);
} else if (isset($_GET["delete"])) {
    $flag = $coopUrlsController->deleteUserAd($username, $_GET["delete"]);
}
$membersController->generateUserCSRFToken();
$bannerAdsList = $coopUrlsController->userCoopUrlsList($username);
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
                                <td>Link</td>
                                <td>Credits</td>
                                <td>Views</td>
                                <!-- <td>Clicks</td> -->
                                <td>Status</td>
                                <td></td>
                            </thead>
                            <?php if (!empty($bannerAdsList)) : ?>
                            <?php foreach ($bannerAdsList as $adData) : ?>
                            <tr>
                                <!-- <td><a href="<?= $adData['image_link'] ?>" target="_blank"><img
                                            src="<?= $adData['image_link'] ?>" height="15" width="50" /></a></td> -->
                                <td><?= strlen($adData["ad_link"]) > 35 ? substr($adData["ad_link"], 0, 35) . "..." : $adData["ad_link"] ?>
                                </td>
                                <td><?= $adData["credits"] ?></td>
                                <td><?= formatNumberDigits($adData["total_views"]) ?></td>
                                <!-- <td><?= formatNumberDigits($adData["total_clicks"]) ?></td> -->
                                <td>
                                    <?php if ($adData["status"] == 2) : ?>
                                        <span class="badge bg-success"><?= $coopUrlsController->adStatus()[$adData["status"] - 1] ?></span>
                                    <?php elseif ($adData["status"] == 1) : ?>
                                        <span class="badge bg-warning"><?= $coopUrlsController->adStatus()[$adData["status"] - 1] ?></span>
                                    <?php elseif ($adData["status"] == 3) : ?>
                                        <span class="badge bg-danger"><?= $coopUrlsController->adStatus()[$adData["status"] - 1] ?></span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="coop-urls.php?options=<?= $adData['id'] ?>">
                                        <button class="btn btn-danger btn-sm">Options</button>
                                    </a> &nbsp;
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            <?php endif; ?>
                        </table>
                    </div>
                    <?php $coopUrlsController->userCoopUrlsPagination($username) ?>
                </div>

                <?php require_once "themes/default/member-area/incs/footer-ads.inc.php"; ?>
            </div>
        </div>

    </div>

    <?php require_once "themes/default/member-area/incs/footer.inc.php"; ?>