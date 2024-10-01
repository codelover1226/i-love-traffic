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
$adminAdsController = new AdminAdsController();
$id = $_GET["id"];
$flag = $adminAdsController->updateAd($id);
$adDetails = $adminAdsController->getAdDetails($id);
$adminController->adminCSRFTokenGen();
?>
<div class="animate__animated p-6" :class="[$store.app.animation]">
    <h2 class="text-xl"><?= $title ?></h2>
    <ul class="flex space-x-2 rtl:space-x-reverse">
        <li>
            <a href="index.php" class="text-primary hover:underline">Dashboard</a>
        </li>
        <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
            <span>Advertisements</span>
        </li>
        <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
            <a href="admin-ads.php" class="text-primary hover:underline">Admin Ads</a>
        </li>
        <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
            <span><?= $title ?></span>
        </li>
    </ul>
    <div class="grid grid-cols-1 gap-6 pt-5 lg:grid-cols-2">
        <div class="panel">
            <div class="mb-5">
                <?php if (empty($adDetails)) : ?>
                    <div class="alert alert-danger">Couldn't find the ad !</div>
                <?php else : ?>
                    <div class="card">
                        <div class="card-body">
                            <form class="forms-sample" action="" method="POST">
                                <div class="form-group">
                                    <label for="noticeContent">Website Link</label>
                                    <input type="url" class="form-input" name="website_link" value="<?= $adDetails['website_link'] ?>" placeholder="Website link">
                                    <input type="hidden" name="admin_csrf_token" value="<?= $adminController->getAdminCSRFToken() ?>">
                                </div>
                                <div class="form-group">
                                    <label for="noticeContent">Banner Link</label>
                                    <input type="url" class="form-input" name="banner_link" value="<?= $adDetails['banner_link'] ?>" placeholder="Banner link">
                                </div>
                                <div class="form-group">
                                    <label for="noticeContent">Ad Text [Optional]</label>
                                    <input type="text" class="form-input" name="ad_text" placeholder="Ad Text">
                                </div>
                                <div class="form-group">
                                    <label for="exampleSelectGender">Show Banner ?</label>
                                    <select class="form-input" id="status" name="status">
                                        <option value="1" <?= $adDetails['status'] == 1 ? 'selected' : '' ?>>Yes</option>
                                        <option value="2" <?= $adDetails['status'] == 2 ? 'selected' : '' ?>>No</option>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary mt-6">Update</button>
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