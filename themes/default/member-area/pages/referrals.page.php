<?php

$currentPage = (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] === "on" ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
if ($_SERVER["REQUEST_METHOD"] == "GET" && strcmp(basename($currentPage), basename(__FILE__)) == 0) {
    http_response_code(404);
    die("");
}
require_once "themes/default/member-area/incs/header.inc.php";
$referralList = $membersController->memberReferrals($username);
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
                            <h5 class="my-0 text-primary"><i class="mdi mdi-bullseye-arrow me-3"></i>Search Referral</h5>
                        </div>
                        <div class="card-body">
                            <p class="card-text">
                            <form accept="">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <input class="form-control" type="text" name="username" placeholder="Username" />
                                    </div>
                                    <div class="col-lg-6">
                                        <button class="btn btn-success btn-md" type="submit">Search</button>
                                    </div>
                                </div>
                            </form>
                            </p>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="card border border-primary">
                            <table class="table table-striped">
                                <thead>
                                    <td>Username</td>
                                    <td>First Name</td>
                                    <td>Last Name</td>
                                    <td>Membership</td>
                                    <td>Join Date</td>
                                    <td>Total Clicks</td>
                                    <td>Status</td>
                                </thead>
                                <?php if (!empty($referralList)) : ?>
                                    <?php foreach ($referralList as $referralData) : ?>
                                        <tr>
                                            <td><?= $referralData["username"] ?></td>
                                            <td><?= $referralData["first_name"] ?></td>
                                            <td><?= $referralData["last_name"] ?></td>
                                            <td><?= $referralData["membership_title"] ?></td>
                                            <td><?= date("d M, Y", $referralData["join_timestamp"]) ?></td>
                                            <td><?= $referralData["total_clicks"] ?></td>
                                            <td><?= $membersController->memberStatus()[$referralData["account_status"]] ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </table>
                        </div>
                        <?php $membersController->memberReferrralsPagination($username) ?>
                    </div>
                    <?php require_once "themes/default/member-area/incs/footer-ads.inc.php"; ?>

                </div>
            </div>
        </div>

    </div>

    <?php require_once "themes/default/member-area/incs/footer.inc.php"; ?>