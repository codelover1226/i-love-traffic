<?php

$currentPage = (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] === "on" ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
if ($_SERVER["REQUEST_METHOD"] == "GET" && strcmp(basename($currentPage), basename(__FILE__)) == 0) {
    http_response_code(404);
    die("");
}
require_once "themes/default/member-area/incs/header.inc.php";
$flag = $bannerAd160600Controller->addUserBannerAd($username);
$membersController->generateUserCSRFToken();
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
                            <h5 class="my-0 text-primary"><i class="mdi mdi-bullseye-arrow me-3"></i>Add New 160x600 Banner</h5>
                        </div>
                        <div class="card-body">
                            <p class="card-text">
                            <form action="" method="POST" accept-charset="utf-8">
                                <input type="hidden" name="csrf_token" value="<?= $membersController->getUserCSRFToken() ?>"">
                                    <div class=" form-group">
                                <label>Banner Link</label>
                                <input type="url" name="image_link" class="form-control" placeholder="Your 160x600 banner link">
                        </div><br>
                        <div class="form-group">
                            <label>Website Link</label>
                            <input type="url" name="ad_link" class="form-control" placeholder="Your target link">
                        </div>
                        <br>
                        <div class="form-group">
                            <button class="btn btn-primary">Add</button>
                        </div>

                        </form>
                        </p>
                    </div>
                </div>
            </div>
            <?php require_once "themes/default/member-area/incs/footer-ads.inc.php"; ?>            
        </div>

    </div>


    <?php require_once "themes/default/member-area/incs/footer.inc.php"; ?>