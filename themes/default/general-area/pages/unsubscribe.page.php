<?php



$currentPage = (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] === "on" ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

if ($_SERVER["REQUEST_METHOD"] == "GET" && strcmp(basename($currentPage), basename(__FILE__)) == 0) {
    http_response_code(404);
    die("");
}

require_once "themes/default/general-area/incs/header.inc.php";
$pagesController = new PagesController();
$flag = $membersController->unsubscribe();
?>
<main id="main">

    <div class="container"><br><br>
        <div align="center">
            <h3><strong>Unsubscribe
            </h3>
        </div>
        <p><?php if (isset($flag) && isset($flag["message"])) : ?>
                <?= $flag["message"] ?>
            <?php endif; ?>
        </p>
    </div>
</main>

<?php require_once "themes/default/general-area/incs/footer.inc.php"; ?>s