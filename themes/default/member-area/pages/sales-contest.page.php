<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$currentPage = (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] === "on" ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
if ($_SERVER["REQUEST_METHOD"] == "GET" && strcmp(basename($currentPage), basename(__FILE__)) == 0) {
    http_response_code(404);
    die("");
}
require_once "themes/default/member-area/incs/header.inc.php";
$contestInfo = $salesContestController->salesContestLeaderboard();
$contestEndTime = strtotime($contestInfo["contest_info"]["end_date"] . "23:59:59");
$contestStartTime = strtotime($contestInfo["contest_info"]["start_date"] . "00:00:00");
?>
<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <?php include_once "themes/default/member-area/incs/sidebar-ads.inc.php"; ?>
            <div class="col-xl-9">
                <?php if ($contestInfo["contest_info"]["status"] != 1) : ?>
                    <div class="alert alert-danger">Sales contest is not available now. Please check back later.</div>
                <?php else : ?>
                    <?php if ($contestEndTime < time()) : ?>
                        <div class="alert alert-danger">Sales contest has been ended.</div>
                    <?php endif; ?>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="card bg-info text-light">
                                <div class="card-body">
                                    <h5 class="mb-6 text-light"><i class="mdi mdi-alert-circle-outline me-3"></i>Start Date</h5>
                                    <h4 class="card-text" style="color:aliceblue"><?= date("d M, Y", $contestStartTime) ?></h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="card bg-danger text-light">
                                <div class="card-body">
                                    <h5 class="mb-6 text-light"><i class="mdi mdi-alert-circle-outline me-3"></i>End Date</h5>
                                    <h4 class="card-text" style="color:aliceblue"><?= date("d M, Y", $contestEndTime) ?></h4>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="card border border-danger">
                                <div class="card-header bg-transparent border-danger">
                                    <h5 class="my-0 text-danger"><i class="mdi mdi-bullseye-arrow me-3"></i>First Prize</h5>
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title">Rewards $<?= $contestInfo["contest_info"]["first_prize_money"] ?></h5>
                                    <p class="card-text">
                                        Credits : <?= $contestInfo["contest_info"]["first_prize_credits"] ?><br>
                                        Banner Credits : <?= $contestInfo["contest_info"]["first_prize_banner_credits"] ?><br>
                                        Text Ad Credits : <?= $contestInfo["contest_info"]["first_prize_text_credits"] ?><br>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="card border border-success">
                                <div class="card-header bg-transparent border-success">
                                    <h5 class="my-0 text-success"><i class="mdi mdi-bullseye-arrow me-3"></i>Second Prize</h5>
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title">Rewards $<?= $contestInfo["contest_info"]["second_prize_money"] ?></h5>
                                    <p class="card-text">
                                        Credits : <?= $contestInfo["contest_info"]["second_prize_credits"] ?><br>
                                        Banner Credits : <?= $contestInfo["contest_info"]["second_prize_banner_credits"] ?><br>
                                        Text Ad Credits : <?= $contestInfo["contest_info"]["second_prize_text_credits"] ?><br>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="card border border-primary">
                                <div class="card-header bg-transparent border-primary">
                                    <h5 class="my-0 text-primary"><i class="mdi mdi-bullseye-arrow me-3"></i>Third Prize</h5>
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title">Rewards $<?= $contestInfo["contest_info"]["third_prize_money"] ?></h5>
                                    <p class="card-text">
                                        Credits : <?= $contestInfo["contest_info"]["third_prize_credits"] ?><br>
                                        Banner Credits : <?= $contestInfo["contest_info"]["third_prize_banner_credits"] ?><br>
                                        Text Ad Credits : <?= $contestInfo["contest_info"]["third_prize_text_credits"] ?><br>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-12">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">Leaderboard</h4>
                                    <div class="table-responsive">
                                        <table class="table mb-0">

                                            <thead>
                                                <tr>
                                                    <th>Rank</th>
                                                    <th>Username</th>
                                                    <th>Total Sold ($)</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if (!empty($contestInfo["leaderboard"])) : ?>
                                                    <?php $counter = 1;
                                                    foreach ($contestInfo["leaderboard"] as $clickerData) : ?>
                                                        <tr>
                                                            <td><?= $counter; ?></td>
                                                            <td><?= $clickerData["affiliate_username"] ?></td>
                                                            <td>$<?= $clickerData["total_sold"] ?></td>
                                                        </tr>
                                                    <?php $counter++;
                                                    endforeach; ?>
                                                <?php endif; ?>
                                            </tbody>
                                        </table>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                <?php require_once "themes/default/member-area/incs/footer-ads.inc.php"; ?>
            </div>
        </div>
        <?php require_once "themes/default/member-area/incs/footer.inc.php"; ?>