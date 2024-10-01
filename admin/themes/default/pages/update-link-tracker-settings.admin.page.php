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
require_once "../modules/nsms-link-tracker/LinkTracker.php";
$linkTrackerController = new LinkTracker();
$flag = $linkTrackerController->updateSettings();
$settings = $linkTrackerController->getSettings();
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
            <a href="link-trackers.php" class="text-primary hover:underline">Link Tracker</a>
        </li>
        <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
            <span><?= $title ?></span>
        </li>
    </ul>
    <div class="grid grid-cols-1 gap-6 pt-5 lg:grid-cols-2">
        <div class="panel">

            <div class="relative inline-flex align-middle">
                <a href="link-trackers.php"><button type="button" class="btn btn-dark ltr:rounded-r-none rtl:rounded-l-none">Link List</button></a>
            </div>
            <br><br>
            <div class="mb-5">
                <div class="card">
                    <div class="card-body">
                        <div class="form-group">
                            <form class="forms-sample" action="" method="POST">
                                <input type="hidden" name="admin_csrf_token" value="<?= $adminController->getAdminCSRFToken() ?>">
                                <label for="exampleSelectGender">Enable The Module</label>
                                <select class="form-input" name="system_power">
                                    <option value="1" <?= $settings["system_power"] == 1 ? "selected" : "" ?>>Enable</option>
                                    <option value="2" <?= $settings["system_power"] == 2 ? "selected" : "" ?>>Disable</option>
                                </select>
                                <label for="noticeContent">Free Members Limit </label>
                                <input type="text" class="form-input" name="free_member_limit" placeholder="Free members limit" value="<?= $settings['free_member_limit'] ?>">

                                <label for="noticeContent">Paid Members Limit </label>
                                <input type="text" class="form-input" name="paid_member_limit" placeholder="Paid Members Limit" value="<?= $settings['paid_member_limit'] ?>">
                                <button type="submit" class="btn btn-success mr-2">Update</button>
                            </form>
                        </div>
                    </div>
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