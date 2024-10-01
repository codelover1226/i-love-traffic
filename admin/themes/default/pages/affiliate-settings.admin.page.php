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
$affiliateSettingsController = new AffiliateSettingsController();
$flag = $affiliateSettingsController->updateSettings();
$settings = $affiliateSettingsController->getSettings();
$adminController->adminCSRFTokenGen();
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
            <span>Affiliates</span>
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
                        <label for="noticeContent">Minimum Withdraw Amount</label>
                        <input type="number" step="0.10" class="form-input" name="minimum_withdraw" value="<?= $settings['minimum_withdraw'] ?>" placeholder="Minimum Withdraw Amount">
                        <input type="hidden" name="admin_csrf_token" value="<?= $adminController->getAdminCSRFToken() ?>">
                    </div>
                    <span class="badge bg-primary rounded-none">Payment Gateway for Affiliates</span>
                    <div class="form-group">
                        <label for="noticeContent">Enable PayPal</label>
                        <select class="form-input" name="paypal">
                            <option value="1" <?= $settings["paypal"] == 1 ? "selected" : "" ?>>Yes</option>
                            <option value="2" <?= $settings["paypal"] == 2 ? "selected" : "" ?>>No</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="noticeContent">Enable Coinbase</label>
                        <select class="form-input" name="btc_coinbase">
                            <option value="1" <?= $settings["btc_coinbase"] == 1 ? "selected" : "" ?>>Yes</option>
                            <option value="2" <?= $settings["btc_coinbase"] == 2 ? "selected" : "" ?>>No</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="noticeContent">Enable Skrill</label>
                        <select class="form-input" name="skrill">
                            <option value="1" <?= $settings["skrill"] == 1 ? "selected" : "" ?>>Yes</option>
                            <option value="2" <?= $settings["skrill"] == 2 ? "selected" : "" ?>>No</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="noticeContent">Enable TransferWise</label>
                        <select class="form-input" name="transfer_wise">
                            <option value="1" <?= $settings["transfer_wise"] == 1 ? "selected" : "" ?>>Yes</option>
                            <option value="2" <?= $settings["transfer_wise"] == 2 ? "selected" : "" ?>>No</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="noticeContent">Enable PerfectMoney</label>
                        <select class="form-input" name="perfect_money">
                            <option value="1" <?= $settings["perfect_money"] == 1 ? "selected" : "" ?>>Yes</option>
                            <option value="2" <?= $settings["perfect_money"] == 2 ? "selected" : "" ?>>No</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="noticeContent">Enable ETH Wallet</label>
                        <select class="form-input" name="eth_wallet">
                            <option value="1" <?= $settings["eth_wallet"] == 1 ? "selected" : "" ?>>Yes</option>
                            <option value="2" <?= $settings["eth_wallet"] == 2 ? "selected" : "" ?>>No</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary mt-6">Add</button>
                </form>
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