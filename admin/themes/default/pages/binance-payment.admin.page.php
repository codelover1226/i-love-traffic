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
$paymentSettingsController = new PaymentSettingsController();
$flag = $paymentSettingsController->updateBinance();
$paymentSettings = $paymentSettingsController->getSettings("Binance");
$adminController->adminCSRFTokenGen();
$siteSettingsController = new SiteSettingsController();
$siteSettingsData = $siteSettingsController->getSettings();
?>
<div class="animate__animated p-6" :class="[$store.app.animation]">
    <h2 class="text-xl"><?= $title ?></h2>
    <ul class="flex space-x-2 rtl:space-x-reverse">
        <li>
            <a href="index.php" class="text-primary hover:underline">Dashboard</a>
        </li>
        <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
            <span>Store & Affiliates</span>
        </li>
        <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
            <span>Payment Gateways</span>
        </li>
        <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
            <span><?= $title ?></span>
        </li>
    </ul>
    <div class="grid grid-cols-1 gap-6 pt-5 lg:grid-cols-2">
        <div class="panel">
            <div class="mb-5">
                <form action="" method="POST">
                    <div class="form-group">
                        <label for="noticeContent">API Key</label>
                        <input type="text" class="form-input" name="public_key" placeholder="Binance API Key" value="<?= $paymentSettings['public_key'] ?>">
                        <input type="hidden" name="admin_csrf_token" value="<?= $adminController->getAdminCSRFToken() ?>">
                    </div>
                    <div class="form-group">
                        <label for="noticeContent">Secret Key</label>
                        <input type="text" class="form-input" name="ipn_api_key" placeholder="Binance Secret Key" value="<?= $paymentSettings['ipn_api_key'] ?>">
                        <input type="hidden" name="admin_csrf_token" value="<?= $adminController->getAdminCSRFToken() ?>">
                    </div>
                    <div class="form-group">
                        <label for="exampleSelectGender">Payment Method Status</label>
                        <select class="form-input" id="status" name="status">
                            <option value="1" <?= $paymentSettings["status"] == 1 ? "selected" : "" ?>>Enable</option>
                            <option value="2" <?= $paymentSettings["status"] == 2 ? "selected" : "" ?>>Disable</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary mt-6">Update</button>
                </form>
                <br>
                IPN/Webhook Link: <?= $siteSettingsData["installation_url"] ?>payment-gateways/binance/ipn.php
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