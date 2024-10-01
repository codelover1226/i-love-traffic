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
$emailCreditsController = new EmailCreditsPackagesController();
$id = $_GET["id"];
$flag = $emailCreditsController->updatePackage($id);
$packageDetails = $emailCreditsController->getPackageDetails($id);
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
        <a href="email-credits.php" class="text-primary hover:underline">Credits Packages</a>
        </li>
        <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
            <span><?= $title ?></span>
        </li>
    </ul>
    <div class="grid grid-cols-1 gap-6 pt-5 lg:grid-cols-2">
        <div class="panel">
            <div class="mb-5">
                <?php if (empty($packageDetails)) : ?>
                    <div class="alert alert-danger">Couldn't find the package.</div>
                <?php else : ?>
                    <form class="forms-sample" action="" method="POST">
                        <div class="form-group">
                            <label for="noticeContent">Credits Amount</label>
                            <input type="text" class="form-input" name="credits" placeholder="Enter credits amount" value="<?= $packageDetails['credits'] ?>">
                            <input type="hidden" name="admin_csrf_token" value="<?= $adminController->getAdminCSRFToken() ?>">
                        </div>
                        <div class="form-group">
                            <label for="noticeContent">Price</label>
                            <input type="number" step="0.1" class="form-input" name="price" placeholder="Enter price" value="<?= $packageDetails['price'] ?>">
                        </div>
                        <div class="form-group">
                            <label for="exampleSelectGender">Want to hide this from store page ? ?</label>
                            <select class="form-input" name="hidden">
                                <option value="1" <?= $packageDetails['hidden'] == 1 ? "selected" : "" ?>>Yes</option>
                                <option value="2" <?= $packageDetails['hidden'] == 2 ? "selected" : "" ?>>No</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary mt-6">Update Package</button>
                    </form>
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