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
$otherSettingsController = new OtherSettingsController();
$flag = $otherSettingsController->updateMailerSettings();
$mailerFrom = $otherSettingsController->getSettingsValue("mailer_from");
$mailerFromName = $otherSettingsController->getSettingsValue("mailer_from_name");
$mailsPerMin = $otherSettingsController->getSettingsValue("mails_per_min");
$emailLinkValidDays = $otherSettingsController->getSettingsValue("email_link_valid_days");
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
            <span>Mailer</span>
        </li>
        <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
            <span><?= $title ?></span>
        </li>
    </ul>
    <div class="grid grid-cols-1 gap-6 pt-5 lg:grid-cols-2">
        <div class="panel">
            <div class="mb-5">
                <form class="forms-sample" action="" method="POST">
                    <div class="form-group">
                        <label for="noticeContent">From Email</label>
                        <input type="email" class="form-input" name="mailer_from" value="<?= $mailerFrom['settings_value'] ?>" placeholder="Mailer from email address">
                        <input type="hidden" name="admin_csrf_token" value="<?= $adminController->getAdminCSRFToken() ?>">
                    </div>
                    <div class="form-group">
                        <label for="noticeContent">From Name</label>
                        <input type="text" class="form-input" name="mailer_from_name" value="<?= $mailerFromName['settings_value'] ?>" placeholder="Mailer from name">
                    </div>
                    <div class="form-group">
                        <label for="noticeContent">Mails Per Min</label>
                        <input type="number" class="form-input" name="mails_per_min" value="<?= $mailsPerMin['settings_value'] ?>" placeholder="Mails Per Min">
                    </div>
                    <button type="submit" class="btn btn-primary mt-6">Update</button>
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