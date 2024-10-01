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
$flag = $linkTrackerController->deleteShortenLink();
$linkList = $linkTrackerController->shortenLinkList();
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
            <span><?= $title ?></span>
        </li>
    </ul>
    <div class="grid grid-cols-1 gap-6 pt-5 lg:grid-cols-2">
        <div class="panel">

            <div class="relative inline-flex align-middle">
                <a href="link-trackers.php?action=settings"><button type="button" class="btn btn-dark ltr:rounded-r-none rtl:rounded-l-none">Settings</button></a>
            </div>
            <br><br>

            <div class="mb-5">
                <div class="card">
                    <div class="card-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Username</th>
                                    <th>Actual Link</th>
                                    <th>Tracking Code</th>
                                    <th>Created At</th>
                                    <th>Total Visits</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($linkList)) : ?>
                                    <?php foreach ($linkList as $linkDetails) : ?>
                                        <tr>
                                            <td>
                                                <?= $linkDetails["username"] ?>
                                            </td>
                                            <td>
                                                <?= strlen($linkDetails["actual_link"]) > 15 ? substr($linkDetails["actual_link"], 0, 15) . "..." : $linkDetails["actual_link"] ?>
                                            </td>
                                            <td>
                                                <?= $linkDetails["shorten_code"] ?>
                                            </td>
                                            <td>
                                                <?= date("d M, Y", $linkDetails["created_at"]) ?>
                                            </td>
                                            <td>
                                                <?= $linkDetails["total_visits"] ?>
                                            </td>
                                            <td>
                                                <a href="link-trackers.php?details=<?= $linkDetails['shorten_code'] ?>&user=<?= $linkDetails['username'] ?>"><button class="badge badge-info">Details</button></a>
                                                <a href="link-trackers.php?delete=<?= $linkDetails['shorten_code'] ?>&token=<?= $adminController->getAdminCSRFToken() ?>"><button class="badge badge-danger">Delete</button></a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>

                            </tbody>
                        </table>
                        <?= $linkTrackerController->shortenLinkPagination() ?>
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