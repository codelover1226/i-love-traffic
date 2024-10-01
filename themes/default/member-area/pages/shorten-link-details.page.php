<?php

$currentPage = (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] === "on" ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
if ($_SERVER["REQUEST_METHOD"] == "GET" && strcmp(basename($currentPage), basename(__FILE__)) == 0) {
    http_response_code(404);
    die("");
}
require_once "themes/default/member-area/incs/header.inc.php";
require_once "modules/nsms-link-tracker/LinkTracker.php";
$linkTrackerController = new LinkTracker();
$linkDetails = $linkTrackerController->getShortenCodeDetails($_GET["details"]);
if ($linkDetails["username"] != $username) {
    $linkDetails = "";
}
?>
<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <?php include_once "themes/default/member-area/incs/sidebar-ads.inc.php"; ?>
            <div class="col-xl-9">
                <?php if (isset($flag) && isset($flag["success"])) : ?>
                <?php if ($flag["success"] == true) : ?>
                <div class="alert alert-success"><?= $flag["message"] ?></div>
                <?php else : ?>
                <div class="alert alert-danger"><?= $flag["message"] ?></div>
                <?php endif; ?>
                <?php endif; ?>
                <?php if (empty($linkDetails)) : ?>
                <div class="alert alert-danger">Sorry ! Couldn't find the tracking link.</div>
                <?php else : ?>
                <div class="col-lg-12">
                    <div class="card border border-primary">
                        <table class="table table-striped">
                            <?php if (!empty($linkDetails)) : ?>
                            <tr>
                                <td>
                                    Tracking Link
                                </td>
                                <td>
                                    <a href="l.php?l=<?= $linkDetails['shorten_code'] ?>" target="_blank">
                                        <?= $siteSettingsData["installation_url"] . "l.php?l=" . $linkDetails["shorten_code"] ?>
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Actual Link
                                </td>
                                <td>
                                    <?= $linkDetails["actual_link"] ?>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    Created At
                                </td>
                                <td>
                                    <?= date("d M, Y", $linkDetails["created_at"]) ?>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Total Visits
                                </td>
                                <td>
                                    <?= $linkDetails["total_visits"] ?>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Visits Today
                                </td>
                                <td>
                                    <?= $linkTrackerController->todayTotalClicks($_GET["details"], $username) ?>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Visits This Month
                                </td>
                                <td>
                                    <?= $linkTrackerController->thisMonthTotalClicks($_GET["details"], $username) ?>
                                </td>
                            </tr>
                            <?php endif; ?>
                        </table>
                    </div>
                    <?php $linkTrackerController->userShortenLinkPagination($username) ?>
                </div>
                <div class="col-lg-12">
                    <div class="card border border-primary">
                        <div style="padding: 5px;">
                            <h4>Visitors Country</h4>
                        </div>
                        <?php $countries = $linkTrackerController->shortenLinkClicksCountry($_GET["details"], $username); ?>
                        <table class="table table-striped">
                            <thead>
                                <td>Country</td>
                                <td>Visits</td>
                            </thead>
                            <tbody>
                                <?php if (!empty($countries)) : ?>
                                <?php foreach ($countries as $countryDetails) : ?>
                                <tr>
                                    <td><?= $countryDetails["visitor_country"] ?></td>
                                    <td><?= $countryDetails["total_clicks"] ?></td>
                                </tr>
                                <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="card border border-primary">
                        <div style="padding: 5px;">
                            <h4>Visitors Origin</h4>
                        </div>
                        <?php $countries = $linkTrackerController->shortenLinkClicksOrigin($_GET["details"], $username); ?>
                        <table class="table table-striped">
                            <thead>
                                <td>Origin</td>
                                <td>Visits</td>
                            </thead>
                            <tbody>
                                <?php if (!empty($countries)) : ?>
                                <?php foreach ($countries as $countryDetails) : ?>
                                <tr>
                                    <td><?= $countryDetails["visitor_origin"] ?></td>
                                    <td><?= $countryDetails["total_clicks"] ?></td>
                                </tr>
                                <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <?php endif; ?>
                <?php require_once "themes/default/member-area/incs/footer-ads.inc.php"; ?>
            </div>
        </div>
    </div>
    <?php require_once "themes/default/member-area/incs/footer.inc.php"; ?>