<?php

$currentPage = (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] === "on" ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
if ($_SERVER["REQUEST_METHOD"] == "GET" && strcmp(basename($currentPage), basename(__FILE__)) == 0) {
    http_response_code(404);
    die("");
}
require_once "themes/default/member-area/incs/header.inc.php";
$withdrawalReqeusts = $withdrawalRequestsController->userWithdrawalRequestsList($username);
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
                <div class="row">
                    <div class="col-md-4">
                        <div class="card mini-stats-wid">
                            <div class="card-body">
                                <div class="d-flex">
                                    <div class="flex-grow-1">
                                        <p class="text-muted fw-medium">Total Requests</p>
                                        <h4 class="mb-0">
                                            <?= $withdrawalRequestsController->totalUserWithdrawalRequests($username) ?>
                                        </h4>
                                    </div>

                                    <div class="flex-shrink-0 align-self-center">
                                        <div class="mini-stat-icon avatar-sm rounded-circle bg-primary">
                                            <span class="avatar-title">
                                                <i class="bx bx-list-ol font-size-24"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card mini-stats-wid">
                            <div class="card-body">
                                <div class="d-flex">
                                    <div class="flex-grow-1">
                                        <p class="text-muted fw-medium">Total Withdraw</p>
                                        <h4 class="mb-0">
                                            $<?= $withdrawalRequestsController->totalUserPaidAmount($username) ?></h4>
                                    </div>

                                    <div class="flex-shrink-0 align-self-center ">
                                        <div class="avatar-sm rounded-circle bg-primary mini-stat-icon">
                                            <span class="avatar-title rounded-circle bg-primary">
                                                <i class="bx bx-money font-size-24"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card mini-stats-wid">
                            <div class="card-body">
                                <div class="d-flex">
                                    <div class="flex-grow-1">
                                        <p class="text-muted fw-medium">Pending</p>
                                        <h4 class="mb-0">
                                            $<?= $withdrawalRequestsController->totalUserPendingAmount($username) ?>
                                        </h4>
                                    </div>

                                    <div class="flex-shrink-0 align-self-center">
                                        <div class="avatar-sm rounded-circle bg-primary mini-stat-icon">
                                            <span class="avatar-title rounded-circle bg-primary">
                                                <i class="bx bx-money font-size-24"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="card border border-primary">
                        <table class="table table-striped">
                            <thead>
                                <td>Request Date</td>
                                <td>Amount</td>
                                <td>Payment Method</td>
                                <td>Paid Date</td>
                                <td>Status</td>
                                <td></td>
                            </thead>
                            <?php if (!empty($withdrawalReqeusts)) : ?>
                            <?php foreach ($withdrawalReqeusts as $withdrawalRequest) : ?>
                            <tr>
                                <td><?= date("d M, Y", $withdrawalRequest["request_timestamp"]) ?></td>
                                <td>$<?= $withdrawalRequest["amount"] ?></td>
                                <td><?= $withdrawalRequest["payment_gateway"] ?></td>
                                <td><?= empty($withdrawalRequest["paid_timestamp"]) ? "" : date("d M, Y", $withdrawalRequest["paid_timestamp"]) ?>
                                </td>
                                <td><?= $withdrawalRequest["status"] == 1 ? "Pending" : "Paid" ?></td>

                            </tr>
                            <?php endforeach; ?>
                            <?php endif; ?>
                        </table>
                    </div>
                    <?php $withdrawalRequestsController->userWithdrawalRequestsPagination($username) ?>
                </div>

                <?php require_once "themes/default/member-area/incs/footer-ads.inc.php"; ?>
            </div>
        </div>
    </div>

    <?php require_once "themes/default/member-area/incs/footer.inc.php"; ?>