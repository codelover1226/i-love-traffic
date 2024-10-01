<?php

$currentPage = (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] === "on" ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
if ($_SERVER["REQUEST_METHOD"] == "GET" && strcmp(basename($currentPage), basename(__FILE__)) == 0) {
    http_response_code(404);
    die("");
}
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once "themes/default/member-area/incs/header.inc.php";
$flag = $chatGPTController->generatePrompt($userInfo);
$membersController->generateUserCSRFToken();
$chatGPTTotalUsage = $chatGPTController->currentMonthTotalUserPrompt($userInfo["username"]);
$chatGPTSettings = $chatGPTController->getSettings();
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
                <?php if ($userInfo["chat_gpt_access"] != 1) : ?>
                    <div class="alert alert-danger">Your membership don't have ChatGPT Access. Please upgrade your membership.</div>
                <?php elseif ($userInfo["chat_gpt_prompt_limit"] <= $chatGPTTotalUsage) : ?>
                    <div class="alert alert-danger">You have reached your maximum usage limit for this month. Please try again next month or upgrade your membership.</div>
                <?php elseif (strtolower($userInfo["membership_end_time"]) != "lifetime" && time() >= $userInfo["membership_end_time"]) : ?>
                    <div class="alert alert-danger">Your membeship has been expired. Please renew or upgrade your membership.</div>
                <?php elseif ($chatGPTSettings["chatGPTStatus"] != 1) : ?>
                    <div class="alert alert-danger">Admin has disabled ChatGPT.</div>
                <?php else : ?>
                    <div class="alert alert-primary">You can generate total <?= $userInfo["chat_gpt_prompt_limit"] ?> Emails per month</div>
                    <div class="alert alert-dark">You have generated total <?= $chatGPTTotalUsage ?> Emails this month</div>
                    <?php if (isset($flag) && isset($flag["chatGPT_Response"])) : ?>
                        <div class="col-xl-12 col-lg-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="text-muted">
                                        <h6 class="mb-3 fw-semibold text-uppercase">Your Email</h6>
                                        <p style="font-family: 'Outfit','Noto Emoji';" id="emailCotent"><?= nl2br($flag["chatGPT_Response"]) ?></p>
                                    </div>
                                    <div class="hstack flex-wrap gap-2 mb-3 mb-lg-0">
                                        <button onclick="copyTextToClipboard()" id="copyEmail" class="btn btn-danger btn-border">Copy Email</button>

                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                    <div class="col-lg-12">
                        <div class="card border border-primary">
                            <div class="card-header bg-transparent border-primary">
                                <h5 class="my-0 text-primary"><i class="mdi mdi-bug me-3"></i>Generate Email Content Using ChatGPT</h5>
                            </div>
                            <div class="card-body">
                                <p class="card-text">
                                <form action="" method="POST" accept-charset="utf-8">
                                    <input type="hidden" name="csrf_token" value="<?= $membersController->getUserCSRFToken() ?>"">
                                    <div class=" form-group">
                                    <label>Website Title</label>
                                    <input type="text" name="website_title" class="form-control" placeholder="Your website title">
                            </div><br>
                            <div class="form-group">
                                <label>Website Type</label>
                                <select name="website_type" class="form-control">
                                    <?php foreach ($chatGPTController->promptWebsiteTypes() as $websiteType) : ?>
                                        <option value="<?= $websiteType ?>"><?= $websiteType ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div><br>
                            <div class="form-group">
                                <label>Occasion Type</label>
                                <small style="color: red;">Choose No Occasion if you don't want the email focused on any occasion.</small>
                                <select name="occasion_type" class="form-control">
                                    <option value="No Occasion">No Occasion</option>
                                    <?php foreach ($chatGPTController->promptOccasionTypes() as $occasionType) : ?>
                                        <option value="<?= $occasionType ?>"><?= $occasionType ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div><br>
                            <div class="form-group">
                                <label>Email Focus on</label>
                                <select name="focus_type" class="form-control">
                                    <?php foreach ($chatGPTController->promptFocusType() as $focusType) : ?>
                                        <option value="<?= $focusType ?>"><?= $focusType ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div><br>
                            <div class="form-group">
                                <label>Regular Price For Your Membership/Product/Traffic</label>
                                <input type="numeric" step="0.001" name="regular_price" class="form-control" placeholder="Regular price">
                            </div><br>
                            <div class="form-group">
                                <label>Product Title</label>
                                <input type="text" name="product_title" class="form-control" placeholder="Product Title">
                            </div><br>
                            <div class="form-group">
                                <label>Exclusive/Offer Price For Your Membership/Product/Traffic</label>
                                <small style="color: red;">Leave this empty, if you don't have any exclusive offer price.</small>
                                <input type="numeric" step="0.001" name="offer_price" class="form-control" placeholder="Exclusive/Offer price">
                            </div><br>
                            <div class="form-group">
                                <button class="btn btn-primary">Generate</button>
                            </div>

                            </form>
                            </p>
                        </div>
                    </div>
                <?php endif; ?>
                <?php require_once "themes/default/member-area/incs/footer-ads.inc.php"; ?>
                
            </div>
        </div>

    </div>
    <script>
        function isElementVisible(elementId) {
            var element = document.getElementById(elementId);
            if (!element) return false;

            var style = window.getComputedStyle(element);
            return style.display !== 'none' && style.visibility !== 'hidden';
        }

        function copyTextToClipboard() {
            if (!isElementVisible('emailCotent') || !isElementVisible('copyEmail')) {
                console.log('Either the text or the button is not visible');
                return;
            }

            var text = document.getElementById("emailCotent").innerText;
            navigator.clipboard.writeText(text).then(function() {
                var button = document.getElementById("copyEmail");
                button.innerText = "Copied";

                setTimeout(function() {
                    button.innerText = "Copy Email";
                }, 3000);

            }).catch(function(err) {

            });
        }
    </script>

    <?php require_once "themes/default/member-area/incs/footer.inc.php"; ?>