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
$flag = $adminController->changeAdminPassword();
$adminDetails = $adminController->getAdminDetails($_SESSION["admin_username"]);
$adminController->adminCSRFTokenGen();
?>

<div class="animate__animated p-6" :class="[$store.app.animation]">
    <h2 class="text-xl"><?= $title ?></h2>
    <ul class="flex space-x-2 rtl:space-x-reverse">
        <li>
            <a href="index.php" class="text-primary hover:underline">Dashboard</a>
        </li>
        <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
            <span><?= $title ?></span>
        </li>
    </ul>
    <div class="grid grid-cols-1 gap-6 pt-5 lg:grid-cols-2">
        <div class="panel">
            <div class="mb-5">
                <form action="" method="POST">
                    <input type="hidden" name="admin_csrf_token" value="<?= $adminController->getAdminCSRFToken() ?>">
                    <label for="c_password">Current Password</label>
                    <input id="c_password" name="password" type="password" placeholder="Enter your current password" class="form-input" />

                    <label for="c_password">New Password</label>
                    <input id="c_password" name="newpassword" type="password" placeholder="Enter your new password" class="form-input" />

                    <label for="c_password">Confirm New Password</label>
                    <input id="c_password" name="confirmnewpassword" type="password" placeholder="Enter your new password again" class="form-input" />
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