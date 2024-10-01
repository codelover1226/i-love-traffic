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
$withdrawalRequestsController = new WithdrawalRequestsController();
$membersController = new MembersController();
$emailReportsController = new EmailReportsController();
$ordersController = new OrdersController();
$membersController = new MembersController();
$websiteSettingsController = new SiteSettingsController();
$recentOrders = $ordersController->recentOrders();
$recentEmailReports = $emailReportsController->recentEmailReports();
$recentMembers = $membersController->recentMembers();
$totalSales = $ordersController->totalSales();
$totalAffiliateBalance = $membersController->totalAffiliateBalance();
$totalWithdrawalAmount = $withdrawalRequestsController->totalAmount();
$totalRevenue = $totalSales - ($totalAffiliateBalance + $totalWithdrawalAmount);
$siteSettingsData = $websiteSettingsController->getSettings();
$supportTicketsController = new SupportTicketsController();
?>
<div class="animate__animated p-6" :class="[$store.app.animation]">
    <div x-data="finance">
        <ul class="flex space-x-2 rtl:space-x-reverse">
            <li>
                <a href="index.php" class="text-primary hover:underline">Dashboard</a>
            </li>
        </ul>
        <div class="pt-5">
            <div class="mb-6 grid grid-cols-1 gap-6 text-white sm:grid-cols-2 xl:grid-cols-4">
                <div class="panel bg-gradient-to-r from-cyan-500 to-cyan-400">
                    <div class="flex justify-between">
                        <div class="text-md font-semibold ltr:mr-1 rtl:ml-1">Total Users</div>
                    </div>
                    <div class="mt-5 flex items-center">
                        <div class="text-3xl font-bold ltr:mr-3 rtl:ml-3"><?= $membersController->totalMembers() ?></div>
                    </div>
                    <div class="mt-5 flex items-center font-semibold">
                        New Members Today <?= $membersController->newMemberToday() ?>
                    </div>
                </div>

                <div class="panel bg-gradient-to-r from-violet-500 to-violet-400">
                    <div class="flex justify-between">
                        <div class="text-md font-semibold ltr:mr-1 rtl:ml-1">Total Orders</div>
                    </div>
                    <div class="mt-5 flex items-center">
                        <div class="text-3xl font-bold ltr:mr-3 rtl:ml-3"><?= $ordersController->totalOrders() ?></div>
                    </div>
                    <div class="mt-5 flex items-center font-semibold">
                        Number of orders
                    </div>
                </div>

                <div class="panel bg-gradient-to-r from-blue-500 to-blue-400">
                    <div class="flex justify-between">
                        <div class="text-md font-semibold ltr:mr-1 rtl:ml-1">Total Sales</div>

                    </div>
                    <div class="mt-5 flex items-center">
                        <div class="text-3xl font-bold ltr:mr-3 rtl:ml-3">$<?= $ordersController->totalSales() ?></div>
                    </div>
                    <div class="mt-5 flex items-center font-semibold">
                        Total amount from orders
                    </div>
                </div>

                <div class="panel bg-gradient-to-r from-fuchsia-500 to-fuchsia-400">
                    <div class="flex justify-between">
                        <div class="text-md font-semibold ltr:mr-1 rtl:ml-1">Total Revenue</div>
                    </div>
                    <div class="mt-5 flex items-center">
                        <div class="text-3xl font-bold ltr:mr-3 rtl:ml-3">$<?= $totalRevenue ?></div>
                    </div>
                    <div class="mt-5 flex items-center font-semibold">
                        Company Revenue = (total sales - (affiliate owing + total withdrawal))
                    </div>
                </div>
                <div class="panel bg-gradient-to-r from-fuchsia-500 to-fuchsia-400">
                    <div class="flex justify-between">
                        <div class="text-md font-semibold ltr:mr-1 rtl:ml-1">Affiliate Balance</div>
                    </div>
                    <div class="mt-5 flex items-center">
                        <div class="text-3xl font-bold ltr:mr-3 rtl:ml-3">$<?= $totalAffiliateBalance ?></div>
                    </div>
                    <div class="mt-5 flex items-center font-semibold">
                        Sum of all the affiliates available balance
                    </div>
                </div>
                <div class="panel bg-gradient-to-r from-blue-500 to-blue-400">
                    <div class="flex justify-between">
                        <div class="text-md font-semibold ltr:mr-1 rtl:ml-1">Total Pending Withraw Request</div>

                    </div>
                    <div class="mt-5 flex items-center">
                        <div class="text-3xl font-bold ltr:mr-3 rtl:ml-3"><?= $withdrawalRequestsController->totalPendingWithdrawalRequests() ?></div>
                    </div>
                    <div class="mt-5 flex items-center font-semibold">
                        Total withdraw request
                    </div>
                </div>
                <div class="panel bg-gradient-to-r from-cyan-500 to-cyan-400">
                    <div class="flex justify-between">
                        <div class="text-md font-semibold ltr:mr-1 rtl:ml-1">Support Tickets Awaiting Reply </div>
                    </div>
                    <div class="mt-5 flex items-center">
                        <div class="text-3xl font-bold ltr:mr-3 rtl:ml-3"><?= $supportTicketsController->totalAwaitingReplyTickets() ?></div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-6 xl:grid-cols-2">
                <div class="grid gap-6 xl:grid-flow-row">
                    <div class="panel overflow-hidden">
                        <div class="mb-5 text-lg font-bold">Recent Orders</div>
                        <div class="table-responsive">
                            <table>
                                <thead>
                                    <tr>
                                        <th class="ltr:rounded-l-md rtl:rounded-r-md">Product</th>
                                        <th>Amount</th>
                                        <th class="text-center ltr:rounded-r-md rtl:rounded-l-md">Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($recentOrders)) : ?>
                                        <div class="row">
                                            <div class="col-12">
                                                <?php foreach ($recentOrders as $orderData) : ?>
                                                    <tr>
                                                        <td><?= $orderData["product_title"] ?></td>
                                                        <td>$<?= $orderData["product_price"] ?></td>
                                                        <td><?= date("d M, Y", $orderData["order_timestamp"]) ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="panel">
                    <div class="mb-5 text-lg font-bold">Recent Email Reports</div>
                    <div class="table-responsive">
                        <table>
                            <thead>
                                <tr>
                                    <th class="ltr:rounded-l-md rtl:rounded-r-md">Email Subject</th>
                                    <th>Report Sender</th>
                                    <th class="text-center ltr:rounded-r-md rtl:rounded-l-md">Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($recentEmailReports)) : ?>
                                    <?php foreach ($recentEmailReports as $emailReport) : ?>
                                        <tr>
                                            <td><?= base64_decode($emailReport["email_subject"]) ?></td>
                                            <td><?= $emailReport["report_sender"] ?></td>
                                            <td><?= date("d M,Y", $emailReport["report_timestamp"]) ?> </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="panel">
                    <div class="mb-5 text-lg font-bold">Recent Members</div>
                    <div class="table-responsive">
                        <table>
                            <thead>
                                <tr>
                                    <th class="ltr:rounded-l-md rtl:rounded-r-md"></th>
                                    <th>Username</th>
                                    <th>Email</th>
                                    <th>Referrer</th>
                                    <th class="text-center ltr:rounded-r-md rtl:rounded-l-md">Join Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($recentMembers)) : ?>
                                    <?php foreach ($recentMembers as $memberData) : ?>
                                        <tr>

                                            <td>
                                                <img style="height: 50px;" src="<?= $membersController->gravatar($memberData['email'], $siteSettingsData['installation_url']) ?>" height="50" alt="image" />
                                            </td>
                                            <td> <?= $memberData["username"] ?> </td>
                                            <td> <?= $memberData["email"] ?> </td>
                                            <td> <?= $memberData["referrer"] ?> </td>
                                            <td> <?= date("d M, Y", $memberData["join_timestamp"]) ?> </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once "themes/default/incs/footer.theme.php"; ?>