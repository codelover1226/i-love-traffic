<?php

$currentPage = (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] === "on" ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
if ($_SERVER["REQUEST_METHOD"] == "GET" && strcmp(basename($currentPage), basename(__FILE__)) == 0) {
    http_response_code(404);
    die("");
}

require_once "themes/default/member-area/incs/header.inc.php";
$emailDetails = $chatGPTController->userPromptDetails($_GET["details"], $userInfo["username"]);
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
                <?php if (empty($emailDetails)) : ?>
                    <div class="alert alert-danger">Couldn't find the email.</div>
                <?php else : ?>
                    <div class="col-xl-12 col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="text-muted">
                                    <h6 class="mb-3 fw-semibold text-uppercase">Your Email</h6>
                                    <p style="font-family: 'Outfit','Noto Emoji';" id="emailCotent"><?= nl2br(base64_decode($emailDetails["chat_gpt_response"])) ?></p>
                                </div>
                                <div class="hstack flex-wrap gap-2 mb-3 mb-lg-0">
                                    <button onclick="copyTextToClipboard()" id="copyEmail" class="btn btn-danger btn-border">Copy Email</button>

                                </div>
                            </div>
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