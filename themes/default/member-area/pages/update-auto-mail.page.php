<?php

$currentPage = (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] === "on" ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
if ($_SERVER["REQUEST_METHOD"] == "GET" && strcmp(basename($currentPage), basename(__FILE__)) == 0) {
    http_response_code(404);
    die("");
}
require_once "themes/default/member-area/incs/header.inc.php";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $flag = $membersController->updateAutoEmail($userInfo);
    $userInfo = $membersController->getUserDetails($userInfo["username"]);
}
$membersController->generateUserCSRFToken();
?>
<style>
.white-text {
    color: #ffffff !important;
}

.ck .ck-reset .ck-editor .ck-rounded-corners {
    max-width: 700px !important;
}

.ck-editor__editable {
    min-height: 400px !important;
    color: #000000;
}

.ck.ck-editor__main>.ck-editor__editable {
    border-radius: 0
}

.ck.ck-editor__main>.ck-editor__editable:not(.ck-focused) {
    /* border-color: #292c40 !important; */
}

pre {
    box-sizing: border-box;
    width: 700px;
    padding: 0;
    margin: 0;
    overflow: auto;
    overflow-y: auto;
    overflow-x: auto;
    max-height: 500px;
    font-size: 12px;
    line-height: 20px;
    background: #efefef;
    border: 1px solid #08ffec;
    background: #083338;
    padding: 10px;
    color: #02f681;
}

.ck-content pre {
    padding: 1em;
    color: #ffffff !important;
    /* background: hsla(0, 0%, 78%, .3);
            border: 1px solid #02f681;
            border-radius: 2px;
            text-align: left;
            direction: ltr;
            tab-size: 4;
            white-space: pre-wrap;
            font-style: normal;
            min-width: 200px; */
}
</style>

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
                            <h5 class="my-0 text-primary"><i class="mdi mdi-bullseye-arrow me-3"></i>Auto Email</h5>
                        </div>
                        <div class="card-body">
                            <p class="card-text">
                                <?php if ($userInfo["membership"] > 1) : ?>
                            <form action="" method="POST" accept-charset="utf-8">
                                <input type="hidden" name="csrf_token"
                                    value="<?= $membersController->getUserCSRFToken() ?>"">
                                    <div class=" form-group"><br>
                                <label>Email Subject</label>
                                <input type="text" name="auto_email_subject" class="form-control"
                                    placeholder="Your email subject"
                                    value="<?= base64_decode($userInfo['auto_email_subject']) ?>">
                        </div><br>
                        <div class="form-group">
                            <label>Email Body</label>
                            <textarea type="text" name="auto_email_body" id="email_body" class="form-control"
                                placeholder="Email body"><?= base64_decode($userInfo['auto_email_body']) ?></textarea>
                        </div><br>
                        <div class="form-group">
                            <label>Target Link</label>
                            <input type="url" name="auto_email_website" class="form-control" placeholder="Target link"
                                value="<?= $userInfo['auto_email_website'] ?>">
                        </div><br>
                        <div class=" form-group">
                            <label>Enable Auto Email</label>
                            <select name="auto_email_status" class="form-control">
                                <option value="1" <?= $userInfo["auto_email_status"] == 1 ? "selected" : "" ?>>Enable
                                </option>
                                <option value="2" <?= $userInfo["auto_email_status"] == 2 ? "selected" : "" ?>>Disable
                                </option>
                            </select>
                        </div><br>
                        <div class="alert alert-info">Macros<br>Member's First Name {FIRSTNAME}<br>Member's Last Name
                            {LASTNAME}</div>
                        <div class="form-group">
                            <button class="btn btn-info">Update</button>
                        </div>
                        </form>
                        <?php else : ?>
                        <div class="alert alert-danger">Please upgrade your account to use auto email feature.</div>
                        <?php endif; ?>
                        </p>
                    </div>
                </div>

                <?php require_once "themes/default/member-area/incs/footer-ads.inc.php"; ?>
            </div>
        </div>

    </div>
    <?php require_once "themes/default/member-area/incs/footer.inc.php"; ?>