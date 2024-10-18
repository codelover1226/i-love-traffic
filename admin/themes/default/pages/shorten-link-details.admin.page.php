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
$linkDetails = $linkTrackerController->shortenLinkDetails($_GET["details"], $_GET["user"]);
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

    <div class="grid grid-cols-1 gap-6 pt-5 lg:grid-cols-1">
        <div class="panel">

            <div class="relative inline-flex align-middle">
                <a href="link-trackers.php"><button type="button" class="btn btn-dark ltr:rounded-r-none rtl:rounded-l-none">Link List</button></a>
            </div>
            <br><br>
            <div class="mb-5">
                <div class="card">
                    <div class="card-body">
                        <?php if (empty($linkDetails)) : ?>
                            <div class="alert alert-danger">Couldn't find the shorten link details.</div>
                        <?php else : ?>
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <td style="background: #820a0a; color: #fff;">Username</td>
                                        <td><?= $linkDetails["username"] ?></td>
                                    </tr>
                                    <tr>
                                        <td style="background: #820a0a; color: #fff;">Actual Link</td>
                                        <td><?= $linkDetails["actual_link"] ?></td>
                                    </tr>
                                    <tr>
                                        <td style="background: #820a0a; color: #fff;">Shorten Code</td>
                                        <td><?= $linkDetails["shorten_code"] ?></td>
                                    </tr>
                                    <tr>
                                        <td style="background: #820a0a; color: #fff;">Created At</td>
                                        <td><?= date("d M, Y", $linkDetails["created_at"]) ?></td>
                                    </tr>
                                    <tr>
                                        <td style="background: #820a0a; color: #fff;">Total Visits</td>
                                        <td><?= $linkDetails["total_visits"] ?></td>
                                    </tr>
                                    <tr>
                                        <td style="background: #820a0a; color: #fff;">Total Visits This Month</td>
                                        <td><?= $linkTrackerController->thisMonthTotalClicks($_GET["details"], $_GET["user"]) ?></td>
                                    </tr>
                                    <tr>
                                        <td style="background: #820a0a; color: #fff;">Total Visits Today</td>
                                        <td><?= $linkTrackerController->todayTotalClicks($_GET["details"], $_GET["user"]) ?></td>
                                    </tr>
                                </tbody>
                            </table>
                            <br>
                            <?php $linkCountryList = $linkTrackerController->shortenLinkClicksCountry($_GET["details"], $_GET["user"]); ?>
                            <?php $linkOriginList = $linkTrackerController->shortenLinkClicksOrigin($_GET["details"], $_GET["user"]); ?>
                            <?php if (!empty($linkCountryList)) : ?>
                                <div class="grid grid-cols-1 gap-6 pt-5 lg:grid-cols-2">
                                    <div class="table-responsive">
                                        <h4>Visitors Country List</h4>
                                        <table class="table">
                                            <tbody>
                                                <thead>
                                                    <td>Country</td>
                                                    <td>Total Clicks</td>
                                                </thead>
                                                <?php foreach ($linkCountryList as $countryDetails) : ?>
                                                    <tr>
                                                    <tr>
                                                        <td><?= !empty($countryDetails["visitor_country"]) ? $countryDetails["visitor_country"] : "Unknown" ?></td>
                                                        <td><?= $countryDetails["total_clicks"] ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="table-responsive">
                                        <h4>Visitors Origin List</h4>
                                        <table class="table">
                                            <tbody>
                                                <thead>
                                                    <td>Origin</td>
                                                    <td>Total Clicks</td>
                                                </thead>
                                                <?php foreach ($linkOriginList as $originDetails) : ?>
                                                    <tr>
                                                    <tr>
                                                        <td><?= !empty($originDetails["visitor_origin"]) ? $originDetails["visitor_origin"] : "Unknown" ?></td>
                                                        <td><?= $originDetails["total_clicks"] ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>
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