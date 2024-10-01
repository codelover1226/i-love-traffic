<?php

$currentPage = (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] === "on" ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
if ($_SERVER["REQUEST_METHOD"] == "GET" && strcmp(basename($currentPage), basename(__FILE__)) == 0) {
    http_response_code(404);
    die("");
}
require_once "themes/default/member-area/incs/header.inc.php";
$splashPagesList = $splashPagesController->allSplashPages();

$promotionalEmailsList = $promotionalsEmailsController->getEmails();
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
                            <h5 class="my-0 text-primary"><i class="mdi mdi-bullseye-arrow me-3"></i>Referral Links</h5>
                        </div>
                        <table class="table table-striped">
                            <tr>
                                <td>Homepage : </td>
                                <td>
                                    <a target="_blank"
                                        href="<?= $siteSettingsData['installation_url'] . 'index.php?referrer=' . $username ?>">
                                        <?= $siteSettingsData['installation_url'] . 'index.php?referrer=' . $username ?>
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td>Register Page :</td>
                                <td>
                                    <a target="_blank"
                                        href="<?= $siteSettingsData['installation_url'] . 'register.php?referrer=' . $username ?>">
                                        <?= $siteSettingsData['installation_url'] . 'register.php?referrer=' . $username ?>
                                    </a>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                <?php if (!empty($splashPagesList)) : ?>
                <div class="col-lg-12">
                    <div class="card border border-primary">
                        <div class="card-header bg-transparent border-primary">
                            <h5 class="my-0 text-primary"><i class="mdi mdi-bullseye-arrow me-3"></i>Splash Pages</h5>
                        </div>
                        <table class="table table-striped">
                            <?php foreach ($splashPagesList as $splashPage) : ?>
                            <?php $splashPageLink = $siteSettingsData['installation_url'] . 'splash.php?id=' . $splashPage['id'] . '&referrer=' . $username; ?>
                            <tr>
                                <td>
                                    <a target="_blank" href="<?= $splashPageLink ?>"><?= $splashPageLink ?></a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </table>
                    </div>
                </div>
                <?php endif; ?>
                <?php if (!empty($promotionalEmailsList)) : ?>
                <div class="col-lg-12">
                    <div class="card border border-primary">
                        <div class="card-header bg-transparent border-primary">
                            <h5 class="my-0 text-primary"><i class="mdi mdi-bullseye-arrow me-3"></i>Promotional Emails
                            </h5>
                        </div>
                        <div class="accordion custom-accordionwithicon" id="accordionWithicon">
                            <?php $counter = 1;
                                foreach ($promotionalEmailsList as $emailDetails) : ?>
                            <div class="accordion custom-accordionwithicon-plus" id="accordionWithplusicon">
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="email<?= $emailDetails['id'] ?>">
                                        <button class="accordion-button <?= $counter == 1 ? 'collapsed' : '' ?>"
                                            type="button" data-bs-toggle="collapse"
                                            data-bs-target="#email<?= $emailDetails['id'] ?>div" aria-expanded="true"
                                            aria-controls="email<?= $emailDetails['id'] ?>div">
                                            <?= base64_decode($emailDetails['email_subject']) ?>
                                        </button>
                                    </h2>
                                    <div id="email<?= $emailDetails['id'] ?>div"
                                        class="accordion-collapse collapse <?= $counter == 1 ? 'show' : '' ?>"
                                        aria-labelledby="email<?= $emailDetails['id'] ?>"
                                        data-bs-parent="#accordionWithplusicon">
                                        <div class="accordion-body" id="emailContent<?= $emailDetails['id'] ?>">
                                            <div class="copy-target">
                                                <div class="content-to-copy">
                                                    <?php
                                                            $emailBody = base64_decode($emailDetails['email_body']);
                                                            $emailBody = str_ireplace("{REF_HOME}", $siteSettingsData["installation_url"] . "index.php?referrer=" . $userInfo["username"], $emailBody);
                                                            $emailBody = str_ireplace("{REF_REG}", $siteSettingsData["installation_url"] . "register.php?referrer=" . $userInfo["username"], $emailBody);
                                                            echo nl2br($emailBody);
                                                            ?>
                                                </div>
                                            </div>
                                        </div>
                                        <br>
                                        <button type="button" data-content-id="emailContent<?= $emailDetails['id'] ?>"
                                            class="btn rounded-pill btn-primary waves-effect waves-light copy-content-btn">Copy
                                            Content</button>
                                    </div>
                                </div>
                            </div>
                            <?php $counter++;
                                endforeach; ?>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
                <div class="col-lg-12">
                    <div class="card border border-primary">
                        <div class="card-header bg-transparent border-primary">
                            <h5 class="my-0 text-primary"><i class="mdi mdi-bullseye-arrow me-3"></i>Banners</h5>
                        </div>
                        <?= $membersController->promotionalBanners($username, $siteSettingsData["installation_url"]); ?>
                    </div>
                </div>
                <?php require_once "themes/default/member-area/incs/footer-ads.inc.php"; ?>
            </div>
        </div>
    </div>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.copy-content-btn').forEach(button => {
            button.addEventListener('click', function() {
                const contentId = this.getAttribute('data-content-id');
                const contentToCopy = document.querySelector('#' + contentId + ' .copy-target')
                    .innerText;
                navigator.clipboard.writeText(contentToCopy)
                    .then(() => console.log('Content copied to clipboard!'))
                    .catch(err => console.error('Error in copying text: ', err));
            });
        });
    });
    </script>
    <?php require_once "themes/default/member-area/incs/footer.inc.php"; ?>