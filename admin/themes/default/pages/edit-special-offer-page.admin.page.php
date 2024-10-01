<?php
/*
 *
 *
 *          Author          :   Noman Prodhan
 *          Email           :   hello@nomantheking.com
 *          Websites        :   www.nomantheking.com    www.nomanprodhan.com    www.nstechvalley.com
 *
 *
 */


$currentPage = (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] === "on" ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

if ($_SERVER["REQUEST_METHOD"] == "GET" && strcmp(basename($currentPage), basename(__FILE__)) == 0) {
    http_response_code(404);
    die("");
}

require_once "themes/default/incs/header.theme.php";
$specialOfferPagesController = new SpecialOfferPagesController();
$id = $_GET["edit"];
$flag = $specialOfferPagesController->updatePage($id);
$pageDetails = $specialOfferPagesController->getPageDetails($id);
$adminController->adminCSRFTokenGen();
?>
<div class="animate__animated p-6" :class="[$store.app.animation]">
    <h2 class="text-xl"><?= $title ?></h2>
    <ul class="flex space-x-2 rtl:space-x-reverse">
        <li>
            <a href="index.php" class="text-primary hover:underline">Dashboard</a>
        </li>
        <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
            <span>Pages & Settings</span>
        </li>
        <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
            <span>Pages</span>
        </li>
        <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
        <a href="special-offer-pages.php" class="text-primary hover:underline">Special Offer Pages</a>
        </li>
        <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
            <span><?= $title ?></span>
        </li>
    </ul>
    <div class="grid grid-cols-1 gap-6 pt-5 lg:grid-cols-2">
        <div class="panel">
            <div class="mb-5">
                <?php if (empty($pageDetails)) : ?>
                    <div class="alert alert-danger">Couldn't find the page</div>
                <?php else : ?>
                    <div class="card">
                        <div class="card-body">
                            <form class="forms-sample" action="" method="POST">
                                <div class="form-group">
                                    <label for="noticeContent">Page Title</label>
                                    <input type="text" class="form-input" name="page_title" placeholder="Page title" value="<?= $pageDetails['page_title'] ?>" />
                                    <input type="hidden" name="admin_csrf_token" value="<?= $adminController->getAdminCSRFToken() ?>">
                                </div>
                                <div class="form-group">
                                    <label for="noticeContent">Page Content (You can use HTML/CSS/JS)</label>
                                    <textarea class="form-input" rows="15" name="page_content" placeholder="Page content"><?= $pageDetails['page_content'] ?></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary mt-2">Update Page</button>
                            </form>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php if (isset($flag) && isset($flag["success"])) : ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            <?php if ($flag["success"] == true) : ?>
                Swal.fire({
                    title: 'Success!',
                    text: '<?= addslashes($flag["message"]) ?>',
                    icon: 'success',
                    confirmButtonText: 'OK'
                });
            <?php else : ?>
                Swal.fire({
                    title: 'Error!',
                    text: '<?= addslashes($flag["message"]) ?>',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            <?php endif; ?>
        });
    </script>
<?php endif; ?>
<?php require_once "themes/default/incs/footer.theme.php"; ?>