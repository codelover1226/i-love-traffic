<?php

$currentPage = (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] === "on" ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
if ($_SERVER["REQUEST_METHOD"] == "GET" && strcmp(basename($currentPage), basename(__FILE__)) == 0) {
    http_response_code(404);
    die("");
}
require_once "themes/default/member-area/incs/header.inc.php";
$flag = $downlineBuilderController->deleteUserProgram($username);
$downlinePrograms = $downlineBuilderController->getUserDownlinePrograms($username);
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
                <?php if (empty($downlinePrograms)) : ?>
                    <div class="alert alert-info">You don't have any affiliate program in your downline builder.</div>
                <?php endif; ?>
                <div class="col-lg-12">
                    <div class="card border border-primary">
                        <table class="table table-striped">
                            <?php if (!empty($downlinePrograms)) : ?>
                                <thead>
                                    <td></td>
                                    <td></td>
                                </thead>
                                <?php foreach ($downlinePrograms as $programData) : ?>
                                    <?php if ($downlineBuilderController->is_url_image($programData["affiliate_banner"])) : ?>
                                        <tr>
                                            <td><a href="<?= $programData['affiliate_link'] ?>" target="_blank"><img src="<?= $programData["affiliate_banner"] ?>" height="60" width="468" /></a></td>
                                            <td><a href="downline-builder.php?action=my-programs&delete=<?= $programData['id'] ?>&token=<?= $membersController->getUserCSRFToken() ?>" class="btn btn-primary waves-effect waves-light btn-sm">Delete</a></td>
                                        </tr>

                                    <?php endif; ?>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </table>
                    </div>
                </div>
                <?php require_once "themes/default/member-area/incs/footer-ads.inc.php"; ?>
            </div>
        </div>

    </div>

    <?php require_once "themes/default/member-area/incs/footer.inc.php"; ?>