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
$randomRewardsController = new RandomRewardsController();
$flag = $randomRewardsController->updateSettings();
$randomRewardsSettings = $randomRewardsController->getSettings();
$adminController->adminCSRFTokenGen();
?>
<div class="animate__animated p-6" :class="[$store.app.animation]">
    <h2 class="text-xl"><?= $title ?></h2>
    <ul class="flex space-x-2 rtl:space-x-reverse">
        <li>
            <a href="index.php" class="text-primary hover:underline">Dashboard</a>
        </li>
        <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
            <span>Mailer & Members</span>
        </li>
        <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
            <a href="email-list.php" class="text-primary hover:underline">Mailer</a>
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
                        <label for="noticeContent">Email Credits Reward</label>
                        <input type="text" class="form-input" name="credits_rewards" value="<?= $randomRewardsSettings['credits_rewards'] ?>" placeholder="Email credits">
                        <input type="hidden" name="admin_csrf_token" value="<?= $adminController->getAdminCSRFToken() ?>">

                        <label for="noticeContent">Banner Ad Credits Reward</label>
                        <input type="text" class="form-input" name="banner_credits_rewards" value="<?= $randomRewardsSettings['banner_credits_rewards'] ?>" placeholder="Banner ad credits">

                        <label for="noticeContent">Text Ad Credits Reward</label>
                        <input type="text" class="form-input" name="text_ad_rewards" value="<?= $randomRewardsSettings['text_ad_rewards'] ?>" placeholder="Text ad credits">

                        <label for="noticeContent">Money Rewards</label>
                        <input type="number" step="0.000001" class="form-input" name="money_rewards" value="<?= $randomRewardsSettings['money_rewards'] ?>" placeholder="Money rewards">

                        <label for="noticeContent">Minimum Email Reads for Money</label>
                        <input type="text" class="form-input" name="clicks_required_for_money" value="<?= $randomRewardsSettings['clicks_required_for_money'] ?>" placeholder="Minimum email reads required to get money">

                        <label for="noticeContent">Email Reads to Get Rewards</label>
                        <input type="text" class="form-input" name="clicks_required_for_rewards" value="<?= $randomRewardsSettings['clicks_required_for_rewards'] ?>" placeholder="Email reads required to get any reward">

                        <button type="submit" class="btn btn-primary mt-6">Update</button>
                </form>
            </div>
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