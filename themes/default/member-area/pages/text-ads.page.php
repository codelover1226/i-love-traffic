<?php

$currentPage = (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] === "on" ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
if ($_SERVER["REQUEST_METHOD"] == "GET" && strcmp(basename($currentPage), basename(__FILE__)) == 0) {
    http_response_code(404);
    die("");
}
require_once "themes/default/member-area/incs/header.inc.php";
if (isset($_GET["pause"])) {
    $flag = $textAdController->pauseUserAd($username, $_GET["pause"]);
} else if (isset($_GET["activate"])) {
    $flag = $textAdController->activateUserAd($username, $_GET["activate"]);
} else if (isset($_GET["delete"])) {
    $flag = $textAdController->deleteUserAd($username, $_GET["delete"]);
}
$membersController->generateUserCSRFToken();
$textAdsList = $textAdController->userTextAdsList($username);
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
                                <td>Title</td>
                                <td>Link</td>
                                <td>Credits</td>
                                <td>Views</td>
                                <td>Clicks</td>
                                <td>Status</td>
                                <td></td>
                            </thead>
                            <?php if (!empty($textAdsList)) : ?>
                            <?php foreach ($textAdsList as $textAdData) : ?>
                            <tr>
                                <td><?= $textAdData["ad_title"] ?></td>
                                <td><?= strlen($textAdData["ad_link"]) > 15 ? substr($textAdData["ad_link"], 0, 10) . "..." : $textAdData["ad_link"] ?>
                                </td>
                                <td><?= $textAdData["credits"] ?></td>
                                <td><?= formatNumberDigits($textAdData["total_views"]) ?></td>
                                <td><?= formatNumberDigits($textAdData["total_clicks"]) ?></td>
                                <td>
                                    <?= $textAdController->adStatus()[$textAdData["status"] - 1] ?>
                                </td>
                                <td>
                                    <a href="text-ads.php?options=<?= $textAdData['id'] ?>">
                                        <button class="btn btn-danger btn-sm">Options</button>
                                    </a> &nbsp;
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            <?php endif; ?>
                        </table>
                    </div>
                    <?php $textAdController->userTextAdsPagination($username) ?>
                </div>

                <?php require_once "themes/default/member-area/incs/footer-ads.inc.php"; ?>
            </div>
        </div>

    </div>
    <?php require_once "themes/default/member-area/incs/footer.inc.php"; ?>