<?php

$currentPage = (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] === "on" ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
if ($_SERVER["REQUEST_METHOD"] == "GET" && strcmp(basename($currentPage), basename(__FILE__)) == 0) {
    http_response_code(404);
    die("");
}
require_once "themes/default/member-area/incs/header.inc.php";
$salesList = $ordersController->affiliateSalesList($username);
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
                                <td>Buyer</td>
                                <td>Product</td>
                                <td>Commission</td>
                                <td>Date</td>
                            </thead>
                            <?php if (!empty($salesList)) : ?>
                                <?php foreach ($salesList as $sale) : ?>
                                    <tr>
                                        <td><?= $sale["buyer_username"] ?></td>
                                        <td><?= $sale["product_title"] ?></td>
                                        <td>$<?= $sale["affiliate_commission"] ?></td>
                                        <td><?= date("d M, Y", $sale["order_timestamp"]) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </table>
                    </div>
                    <?php $ordersController->affiliateSalesPagination($username) ?>
                </div>

                <?php require_once "themes/default/member-area/incs/footer-ads.inc.php"; ?>
            </div>
        </div>

    </div>

    <?php require_once "themes/default/member-area/incs/footer.inc.php"; ?>