<?php

$currentPage = (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] === "on" ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
if ($_SERVER["REQUEST_METHOD"] == "GET" && strcmp(basename($currentPage), basename(__FILE__)) == 0) {
    http_response_code(404);
    die("");
}
require_once "themes/default/member-area/incs/header.inc.php";
require_once "modules/nsms-link-tracker/LinkTracker.php";
$linkTrackerController = new LinkTracker();
$flag = $linkTrackerController->deleteUserShortenLink($username, $_GET["token"]);
$linkList = $linkTrackerController->userShortenLinkList($username);
$membersController->generateUserCSRFToken();
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
                <div class="col-lg-12">
                    <div class="card border border-primary">
                        <table class="table table-striped">
                            <thead>
                                <td>Tracking Link</td>
                                <td>Actual Link</td>
                                <td>Created At</td>
                                <td>Total Visits</td>
                                <td>status</td>
                                <td></td>
                                <td></td>
                            </thead>
                            <?php if (!empty($linkList)) : ?>
                            <?php foreach ($linkList as $linkDetails) : ?>
                            <tr>
                                <td>
                                    <a href="l.php?l=<?= $linkDetails['shorten_code'] ?>" target="_blank">
                                        <?= $siteSettingsData["installation_url"] . "l.php?l=" . $linkDetails["shorten_code"] ?>
                                    </a>
                                </td>
                                <td>
                                    <?= strlen($linkDetails["actual_link"]) > 15 ? $linkDetails["actual_link"] . "..." : $linkDetails["actual_link"] ?>
                                </td>
                                <td>
                                    <?= date("d M, Y", $linkDetails["created_at"]) ?>
                                </td>
                                <td>
                                    <?= $linkDetails["total_visits"] ?>
                                </td>
                                <td>
                                    <?php if ($linkDetails["status"] == 2) : ?>
                                        <span class="badge bg-success"><?= $linkTrackerController->linkStatus()[$linkDetails["status"] - 1] ?></span>
                                    <?php elseif ($linkDetails["status"] == 1) : ?>
                                        <span class="badge bg-warning"><?= $linkTrackerController->linkStatus()[$linkDetails["status"] - 1] ?></span>
                                    <?php elseif ($linkDetails["status"] == 3) : ?>
                                        <span class="badge bg-danger"><?= $linkTrackerController->linkStatus()[$linkDetails["status"] - 1] ?></span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="link-tracker.php?details=<?= $linkDetails['shorten_code'] ?>">
                                        <button class="btn btn-info btn-sm">Details</button>
                                    </a>
                                </td>
                                <td>
                                    <a
                                        href="link-tracker.php?delete=<?= $linkDetails['shorten_code'] ?>&token=<?= $membersController->getUserCSRFToken() ?>">
                                        <button class="btn btn-danger btn-sm">Delete</button>
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            <?php endif; ?>
                        </table>
                    </div>
                    <?php $linkTrackerController->userShortenLinkPagination($username) ?>
                </div>
                <?php require_once "themes/default/member-area/incs/footer-ads.inc.php"; ?>
            </div>
        </div>
    </div>
    <?php require_once "themes/default/member-area/incs/footer.inc.php"; ?>