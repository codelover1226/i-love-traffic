<?php

$currentPage = (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] === "on" ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
if ($_SERVER["REQUEST_METHOD"] == "GET" && strcmp(basename($currentPage), basename(__FILE__)) == 0) {
    http_response_code(404);
    die("");
}
require_once "themes/default/member-area/incs/header.inc.php";
$id = $_GET["options"];
if (isset($_POST["image_link"])) {
    $flag = $bannerAd160600Controller->updateUserAd($username, $id);
} else if (isset($_POST["credits"])) {
    $flag = $bannerAd160600Controller->addUserAdCredits($username, $userInfo, $id);
} else if (isset($_POST["remove_credits"])) {
    $flag = $bannerAd160600Controller->removeBannerAdCredits($username, $id);
}
$bannerAdDetails = $bannerAd160600Controller->getbannerAdDetails($id);
if ($bannerAdDetails["username"] != $username) {
    $adAccess = false;
    $flag = array(
        "success" => false,
        "message" => "Couldn't find the banner ad."
    );
} else {
    $adAccess = true;
}

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
                <?php if ($adAccess && !empty($bannerAdDetails)) : ?>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card border border-primary">
                                <div class="card-header bg-transparent border-primary">
                                    <h5 class="my-0 text-primary"><i class="mdi mdi-bullseye-arrow me-3"></i>Edit Ad</h5>
                                </div>
                                <div class="card-body">
                                    <div id="errorbox" class="alert alert-danger" style="display:none"></div>
                                    <form action="" method="POST" accept-charset="utf-8">
                                        <input type="hidden" name="csrf_token" value="<?= $membersController->getUserCSRFToken() ?>"">
                                    <div class=" form-group">
                                        <label>Banner Link</label>
                                        <input type="text" name="image_link" class="form-control" placeholder="Banner Link" value="<?= $bannerAdDetails['image_link'] ?>">
                                </div><br>
                                <div class="form-group">
                                    <label>Ad Link</label>
                                    <input type="url" name="ad_link" class="form-control" placeholder="Your target link" value="<?= $bannerAdDetails['ad_link'] ?>">
                                </div><br>
                                <div class="form-group">
                                    <button class="btn btn-primary">Update</button>
                                </div>

                                </form>
                            </div>

                        </div>
                        <br>
                        <div class="col-lg-12">
                            <div class="card border border-success">
                                <div class="card-header bg-transparent border-primary">
                                    <h5 class="my-0 text-success"><i class="mdi mdi-bullseye-arrow me-3"></i>Add Credits</h5>
                                </div>
                                <div class="card-body">
                                    <div id="errorbox" class="alert alert-danger" style="display:none"></div>
                                    <form action="" method="POST" accept-charset="utf-8">
                                        <input type="hidden" name="csrf_token" value="<?= $membersController->getUserCSRFToken() ?>"">
                                    <div class=" form-group">
                                        <label>Credits</label>
                                        <input type="number" name="credits" class="form-control" placeholder="Credits amount">
                                </div><br>
                                <div class="form-group">
                                    <button class="btn btn-success">Add Credits</button>
                                </div>

                                </form>
                            </div>
                        </div>

                        <br>
                        <div class="col-lg-12">
                            <div class="card border border-danger">
                                <div class="card-header bg-transparent border-primary">
                                    <h5 class="my-0 text-danger"><i class="mdi mdi-bullseye-arrow me-3"></i>Remove Credits</h5>
                                </div>
                                <div class="card-body">
                                    <div id="errorbox" class="alert alert-danger" style="display:none"></div>
                                    <form action="" method="POST" accept-charset="utf-8">
                                        <input type="hidden" name="csrf_token" value="<?= $membersController->getUserCSRFToken() ?>"">
                                    <div class=" form-group">
                                        <label>Credits</label>
                                        <input type="number" name="remove_credits" class="form-control" placeholder="Credits amount" value="<?= $bannerAdDetails['credits'] ?>">
                                </div><br>
                                <div class="form-group">
                                    <button class="btn btn-danger">Remove Credits</button>
                                </div>

                                </form>
                            </div>
                        </div>

                        <br>
                        <div class="col-lg-12">
                            <div class="card border border-danger">
                                <div class="card-header bg-transparent border-primary">
                                    <h5 class="my-0 text-danger"><i class="mdi mdi-bullseye-arrow me-3"></i>Other Options</h5>
                                </div>
                                <div class="card-body">
                                    <?php if ($bannerAdDetails["status"] != 1 && $bannerAdDetails["status"] != 3) : ?>
                                        <a href="web-banners.php?activate=<?= $id ?>&token=<?= $membersController->getUserCSRFToken() ?>"><button type="button" class="btn btn-success">Activate Ad</button></a>
                                    <?php endif; ?>
                                    <?php if ($bannerAdDetails["status"] != 2 && $bannerAdDetails["status"] != 3) : ?>
                                        <a href="web-banners.php?pause=<?= $id ?>&token=<?= $membersController->getUserCSRFToken() ?>"><button type="button" class="btn btn-info">Pause Ad</button></a>
                                    <?php endif; ?>
                                    <a href="web-banners.php?delete=<?= $id ?>&token=<?= $membersController->getUserCSRFToken() ?>"><button type="button" class="btn btn-danger">Delete Ad</button></a>
                                </div>
                            </div>
                        </div>

                    </div>
                <?php endif; ?>
            </div>
            <?php require_once "themes/default/member-area/incs/footer-ads.inc.php"; ?>
        </div>
    </div>
</div>


<?php require_once "themes/default/member-area/incs/footer.inc.php"; ?>