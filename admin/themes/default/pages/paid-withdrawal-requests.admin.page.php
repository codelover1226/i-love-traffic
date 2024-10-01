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
$withdrawalRequests = $withdrawalRequestsController->paidWithdrawalRequestsList();
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
            <a href="withdrawal-requests.php" class="text-primary hover:underline">Withdrawal Requests</a>
        </li>
        <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
            <span><?= $title ?></span>
        </li>
    </ul>
    <div class="grid grid-cols-1 gap-6 pt-5 lg:grid-cols-1">
        <div class="panel">
            <a href="withdrawal-requests.php"><button type="button" class="btn btn-primary">Pending Requests</button></a><br>
            <div class="mb-5">
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>Username</th>
                                <th>Payment Gateway</th>
                                <th>Payment ID</th>
                                <th>Amount</th>
                                <th>Request Date</th>
                                <th>Paid Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($withdrawalRequests)) : ?>
                                <?php foreach ($withdrawalRequests as $withdrawalRequest) : ?>
                                    <tr>
                                        <td><?= $withdrawalRequest["username"] ?></td>
                                        <td><?= $withdrawalRequest["payment_gateway"] ?></td>
                                        <td><?= $withdrawalRequest["payment_id"] ?></td>
                                        <td>$<?= $withdrawalRequest["amount"] ?></td>
                                        <td><?= date("d M, Y", $withdrawalRequest["request_timestamp"]) ?></td>
                                        <td><?= date("d M, Y", $withdrawalRequest["paid_timestamp"]) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <br>
                                <div class="alert alert-danger">No paid withdrawal requests</div><br>
                            <?php endif; ?>
                        </tbody>
                    </table>
                    <?= $withdrawalRequestsController->paidWithdrawalRequestsPagination() ?>
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