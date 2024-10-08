<?php

$currentPage = (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] === "on" ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
if ($_SERVER["REQUEST_METHOD"] == "GET" && strcmp(basename($currentPage), basename(__FILE__)) == 0) {
    http_response_code(404);
    die("");
}
require_once "themes/default/member-area/incs/header.inc.php";
require_once "modules/nsms-voucher/VoucherController.php";
$voucherController = new VoucherController();
$flag = $voucherController->applyVoucher($username);
if (isset($_POST["voucher_code"])) {
    $voucherDetails = $voucherController->voucherDetails($_POST["voucher_code"]);
}
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
                        <div class="card-header bg-transparent border-primary">
                            <h5 class="my-0 text-primary"><i class="mdi mdi-bullseye-arrow me-3"></i>Voucher</h5>
                        </div>
                        <div class="card-body">
                            <p class="card-text">
                            <form action="" method="POST" accept-charset="utf-8">
                                <input type="hidden" name="csrf_token" value="<?= $membersController->getUserCSRFToken() ?>"">
                                    <div class=" form-group">
                                <label>Voucher Code</label>
                                <input type="text" name="voucher_code" class="form-control" placeholder="Voucher Code">
                        </div><br>
                        <br>
                        <div class="form-group">
                            <button class="btn btn-primary">Claim</button>
                        </div>

                        </form>
                        </p>
                    </div><br>
                    <?php if (!empty($voucherDetails)) : ?>
                        <div style="padding:16px">
                            <h3>Voucher Details</h3>
                            <table class="table align-middle table-nowrap">
                                <tbody>
                                    <tr>
                                        <td style="width: 30%">
                                            <p class="mb-0">Email Credits</p>
                                        </td>
                                        <td style="width: 25%">
                                            <h5 class="mb-0"><?= $voucherDetails["email_credits"] ?></h5>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <p class="mb-0">Banner Ad Credits</p>
                                        </td>
                                        <td>
                                            <h5 class="mb-0"><?= $voucherDetails["banner_ad_credits"] ?></h5>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <p class="mb-0">Text Ad Credits</p>
                                        </td>
                                        <td>
                                            <h5 class="mb-0"><?= $voucherDetails["text_ad_credits"] ?></h5>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <p class="mb-0">Login Ad Credits</p>
                                        </td>
                                        <td>
                                            <h5 class="mb-0"><?= $voucherDetails["login_ads"] ?></h5>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                    <?php require_once "themes/default/member-area/incs/footer-ads.inc.php"; ?>
                </div>
            </div>
        </div>


        <?php require_once "themes/default/member-area/incs/footer.inc.php"; ?>