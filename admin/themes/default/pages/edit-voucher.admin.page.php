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
require_once "../modules/nsms-voucher/VoucherController.php";
$voucherController = new VoucherController();
$flag = $voucherController->updateVoucher($_GET["voucher"]);
$voucherDetails = $voucherController->voucherDetails($_GET["voucher"]);
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
            <a href="modules.php" class="text-primary hover:underline">Modules</a>
        </li>
        <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
            <a href="vouchers.php" class="text-primary hover:underline">Vouchers</a>
        </li>
        <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
            <span><?= $title ?></span>
        </li>
    </ul>
    <div class="grid grid-cols-1 gap-6 pt-5 lg:grid-cols-2">
        <div class="panel">
            <div class="mb-5">
                <?php if (empty($voucherDetails)) : ?>
                    <div class="alert alert-danger">Voucher doesn't exist.</div>
                <?php else : ?>
                    <div class="card">
                        <div class="card-body">
                            <div class="form-group">
                                <form class="forms-sample" action="" method="POST">
                                    <input type="hidden" name="admin_csrf_token" value="<?= $adminController->getAdminCSRFToken() ?>">
                                    <label for="noticeContent">Max Use</label>
                                    <input type="number" class="form-input" name="max_use" value="<?= $voucherDetails['max_use'] ?>" placeholder="Max Use">
                                    <label for="noticeContent">Email Credits</label>
                                    <input type="number" class="form-input" name="email_credits" value="<?= $voucherDetails['email_credits'] ?>" placeholder="Email Credits">
                                    <label for="noticeContent">Banner Credits</label>
                                    <input type="number" class="form-input" name="banner_ad_credits" value="<?= $voucherDetails['banner_ad_credits'] ?>" placeholder="Banner Ad Credits">
                                    <label for="noticeContent">Text Ad Credits</label>
                                    <input type="number" class="form-input" name="text_ad_credits" value="<?= $voucherDetails['text_ad_credits'] ?>" placeholder="Text Ad Credits">
                                    <label for="noticeContent">Login Ad</label>
                                    <input type="number" class="form-input" name="login_ads" value="<?= $voucherDetails['login_ads'] ?>" placeholder="Login Ad">
                                    <button type="submit" class="btn btn-primary mt-6">Update</button>
                                </form>
                            </div>
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