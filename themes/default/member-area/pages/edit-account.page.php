<?php

$currentPage = (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] === "on" ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
if ($_SERVER["REQUEST_METHOD"] == "GET" && strcmp(basename($currentPage), basename(__FILE__)) == 0) {
    http_response_code(404);
    die("");
}
require_once "incs/header_code.inc.php";
$username = $_SESSION["logged_username"];
$userInfo = $membersController->getUserDetails($username);
if (isset($_POST["first_name"]) && isset($_POST["last_name"])) {
    $flag = $membersController->updateUserAccountInfo($username);
} else if (isset($_POST["email"])) {
    $flag = $membersController->updateUserEmail($username, $userInfo);
} else if (isset($_FILES["image"])) {
    $flag = $membersController->updateUserProfileImage($username, $userInfo);
}
$membersController->generateUserCSRFToken();
require_once "themes/default/member-area/incs/header.inc.php";
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
                            <h5 class="my-0 text-primary"><i class="mdi mdi-bullseye-arrow me-3"></i>Change Profile Picture</h5>
                        </div>
                        <div class="card-body">
                            <p class="card-text">
                            <form action="" method="POST" accept-charset="utf-8" enctype="multipart/form-data">
                                <input type="hidden" name="csrf_token"
                                    value="<?= $membersController->getUserCSRFToken() ?>">
                                <div class=" form-group">
                                    <label for="image">Upload Profile Picture: <label class="badge badge-danger badge-sm"> Required</label></label>
                                    <input type="file" name="image" id="image" class="form-control" required /><br>
                                </div><br>
                                <div class="form-group">
                                    <button class="btn btn-primary">Update</button>
                                </div>

                            </form>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="card border border-primary">
                        <div class="card-header bg-transparent border-primary">
                            <h5 class="my-0 text-primary"><i class="mdi mdi-bullseye-arrow me-3"></i>Update Profile
                                Information</h5>
                        </div>
                        <div class="card-body">
                            <p class="card-text">
                            <form action="" method="POST" accept-charset="utf-8">
                                <input type="hidden" name="csrf_token"
                                    value="<?= $membersController->getUserCSRFToken() ?>">
                                    <div class=" form-group">
                                <label>First Name <label class="badge badge-danger badge-sm"> Required</label></label>
                                <input type="text" class="form-control" name="first_name"
                                    value="<?= $userInfo['first_name'] ?>" required /><br>
                                <label>Last Name <label class="badge badge-danger badge-sm"> Required</label></label>
                                <input type="text" class="form-control" name="last_name"
                                    value="<?= $userInfo['last_name'] ?>" required /><br>
                                <label>Phone</label>
                                <input type="text" class="form-control" name="phone"
                                    value="<?= $userInfo['phone'] ?>" /><br>
                                <label>Skype</label>
                                <input type="text" class="form-control" name="skype"
                                    value="<?= $userInfo['skype'] ?>" /><br>
                                <label>Telegram</label>
                                <input type="text" class="form-control" name="telegram"
                                    value="<?= $userInfo['telegram'] ?>" />
                        </div><br>
                        <div class="form-group">
                            <button class="btn btn-primary">Update</button>
                        </div>

                        </form>
                        </p>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="card border border-primary">
                        <div class="card-header bg-transparent border-primary">
                            <h5 class="my-0 text-primary"><i class="mdi mdi-bullseye-arrow me-3"></i>Change Email</h5>
                        </div>
                        <div class="card-body">
                            <p class="card-text">
                            <form action="" method="POST" accept-charset="utf-8">
                                <input type="hidden" name="csrf_token"
                                    value="<?= $membersController->getUserCSRFToken() ?>">
                                <div class=" form-group">
                                    <label>Email <label class="badge badge-danger badge-sm"> Required</label></label>
                                    <input type="email" class="form-control" name="email"
                                        value="<?= $userInfo['email'] ?>" required /><br>
                                    <label>Current Password <label class="badge badge-danger badge-sm">
                                            Required</label></label>
                                    <input type="password" class="form-control" name="current_password" required />
                                </div><br>
                                <div class="form-group">
                                    <button class="btn btn-primary">Update</button>
                                </div>

                            </form>
                            </p>
                        </div>
                    </div>
                    <?php require_once "themes/default/member-area/incs/footer-ads.inc.php"; ?>
                </div>
            </div>
        </div>
    </div>


    <?php require_once "themes/default/member-area/incs/footer.inc.php"; ?>