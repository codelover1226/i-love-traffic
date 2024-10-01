<?php

$currentPage = (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] === "on" ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
if ($_SERVER["REQUEST_METHOD"] == "GET" && strcmp(basename($currentPage), basename(__FILE__)) == 0) {
    http_response_code(404);
    die("");
}
require_once "themes/default/member-area/incs/header.inc.php";
$flag = $downlineBuilderController->addUserProgram($username);
$membersController->generateUserCSRFToken();
?>
<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <?php include_once "themes/default/member-area/incs/sidebar-ads.inc.php"; ?>
            <div class="col-xl-9">
                <div class="row">
                    <div class="col-lg-4">
                        <div class="card">
                            <a href="downline-builder.php?action=admin" class="btn btn-danger waves-effect waves-light btn-lg">Admin Recommended<i class="mdi mdi-arrow-right ms-1"></i></a>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="card">
                            <a href="downline-builder.php?action=my-programs" class="btn btn-primary waves-effect waves-light btn-lg">My Downline Builder<i class="mdi mdi-arrow-right ms-1"></i></a>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="card">
                            <a href="downline-builder.php?action=add" class="btn btn-dark waves-effect waves-light btn-lg">Add Affiliate Program<i class="mdi mdi-arrow-right ms-1"></i></a>
                        </div>
                    </div>
                </div>
                <div class="card border border-primary">
                    <div class="card-header bg-transparent border-primary">
                        <h5 class="my-0 text-primary"><i class="mdi mdi-bullseye-arrow me-3"></i>Add New Affiliate Link In Downline Builder</h5>
                    </div>

                    <div class="card-body">
                        <?php if (isset($flag) && isset($flag["success"])) : ?>
                            <?php if ($flag["success"] == true) : ?>
                                <div class="alert alert-success"><?= $flag["message"] ?></div>
                            <?php else : ?>
                                <div class="alert alert-danger"><?= $flag["message"] ?></div>
                            <?php endif; ?>
                        <?php endif; ?>
                        <div class="alert alert-dark">You can add total <?= $downlineBuilderController->getSettings()["settings_value"] ?> affiliate links.</div>
                        <p class="card-text">
                        <form action="" method="POST" accept-charset="utf-8">
                            <input type="hidden" name="csrf_token" value="<?= $membersController->getUserCSRFToken() ?>"">
                            <div class=" form-group">
                            <label>Affiliate Link</label>
                            <input type="url" name="affiliate_link" class="form-control" placeholder="Enter your affiiate link here.">
                    </div>
                    <div class=" form-group">
                        <label>Affiliate Banner</label>
                        <input type="url" name="affiliate_banner" class="form-control" placeholder="Enter your affiiate banner link here.">
                    </div>
                    <br>
                    <div class="form-group">
                        <button class="btn btn-primary">Add</button>
                    </div>
                    </form>
                    </p>
                </div>
                <?php require_once "themes/default/member-area/incs/footer-ads.inc.php"; ?>
            </div>
        </div>
    </div>

    <?php require_once "themes/default/member-area/incs/footer.inc.php"; ?>