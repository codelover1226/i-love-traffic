<?php

$currentPage = (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] === "on" ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
if ($_SERVER["REQUEST_METHOD"] == "GET" && strcmp(basename($currentPage), basename(__FILE__)) == 0) {
    http_response_code(404);
    die("");
}
require_once "themes/default/member-area/incs/header.inc.php";
if (isset($_POST["vacation_end"])) {
    $flag = $membersController->enableUserVacationMode($username, $userInfo);
} else if (isset($_GET["vacation"])) {
    $flag = $membersController->endVacationMode($username);
}
$userInfo = $membersController->getUserDetails($username);
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
                            <h5 class="my-0 text-primary"><i class="mdi mdi-bullseye-arrow me-3"></i>Vacation Settings</h5>
                        </div>
                        <div class="card-body">
                            <p class="card-text">
                                <?php if ($userInfo["account_status"] == 4) : ?>
                            <div class="alert alert-info">Currently your account is in vacation mode.
                                <br>
                                Your vacation mode will end on <?= date("d M, Y", $userInfo["vacation_end_time"]) ?>
                                Click the below button to end the vacation
                            </div>
                            <a href="dashboard.php?action=vacation&vacation=end&token=<?= $membersController->getUserCSRFToken() ?>"><button class="btn btn-danger">End Vacation</button></a>
                        <?php elseif ($userInfo["account_status"] != 1) : ?>
                            <div class="alert alert-info">It seems that, you are not subscribed to our emails.</div>
                        <?php else : ?>
                            <div class="alert alert-info">Your account is active and subscribed to our emails. If you want to enable vacation mode, just select the vacation end date and hit the enable vacation button.</div>
                            <form action="" method="POST" accept-charset="utf-8">
                                <input type="hidden" name="csrf_token" value="<?= $membersController->getUserCSRFToken() ?>"">
                                    <div class=" form-group">
                                <label>Vacation End Date</label>
                                <input type="date" class="form-control" name="vacation_end">
                        </div><br>
                        <div class="form-group">
                            <button class="btn btn-primary">Enable Vacation</button>
                        </div>

                        </form>
                    <?php endif; ?>
                    </p>
                    </div>
                </div>
                <?php require_once "themes/default/member-area/incs/footer-ads.inc.php"; ?>
            </div>
        </div>
    </div>
    <?php require_once "themes/default/member-area/incs/footer.inc.php"; ?>