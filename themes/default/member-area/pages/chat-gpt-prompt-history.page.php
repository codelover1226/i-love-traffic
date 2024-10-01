<?php

$currentPage = (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] === "on" ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
if ($_SERVER["REQUEST_METHOD"] == "GET" && strcmp(basename($currentPage), basename(__FILE__)) == 0) {
    http_response_code(404);
    die("");
}
require_once "themes/default/member-area/incs/header.inc.php";
$chatGPTHistory = $chatGPTController->userPromptList($userInfo["username"]);
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
                        <table class="table table-striped">
                            <thead>
                                <td>Email</td>
                                <td>Date</td>
                                <td></td>
                            </thead>
                            <?php if (!empty($chatGPTHistory)) : ?>
                            <?php foreach ($chatGPTHistory as $history) : ?>
                            <tr>
                                <td><?= $chatGPTController->truncateStringLarge(base64_decode($history["chat_gpt_response"])) ?>
                                </td>
                                <td><?= date("d M, Y H:i", $history["prompt_timestamp"]) ?></td>
                                <td><a href="chat-gpt-prompt-history.php?details=<?= $history['id'] ?>"
                                        class="btn btn-soft-primary waves-effect waves-light">View</button></td>
                            </tr>
                            <?php endforeach; ?>
                            <?php endif; ?>
                        </table>
                        <?= $chatGPTController->userPromptPagination($userInfo["username"]) ?>
                    </div>
                </div>
                <?php require_once "themes/default/member-area/incs/footer-ads.inc.php"; ?>

            </div>
        </div>

    </div>

    <?php require_once "themes/default/member-area/incs/footer.inc.php"; ?>