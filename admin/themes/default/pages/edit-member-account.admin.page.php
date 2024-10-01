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
$membershipsController = new MembershipsController();
$membersController = new MembersController();
$memberships = $membershipsController->getAllMemberships();
$username = $_GET["username"];
$flag = $membersController->updateMemberAccount($username);
$memberDetails = $membersController->getUserDetails($username);
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
            <a href="members.php" class="text-primary hover:underline">Members</a>
        </li>
        <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
            <span><?= $title ?></span>
        </li>
    </ul>
    <div class="grid grid-cols-1 gap-6 pt-5 lg:grid-cols-2">
        <div class="panel">
            <div class="mb-5">
                <?php if (empty($memberDetails)) : ?>
                    <div class="alert alert-danger">Couldn't find the member.</div>
                <?php else : ?>
                    <form class="forms-sample" action="" method="POST">
                        <div class="form-group">
                            <label for="noticeContent">First Name</label>
                            <input type="text" class="form-input" name="first_name" placeholder="Member's first name" value="<?= $memberDetails['first_name'] ?>">
                            <input type="hidden" name="admin_csrf_token" value="<?= $adminController->getAdminCSRFToken() ?>">
                        </div>
                        <div class="form-group">
                            <label for="noticeContent">Last Name</label>
                            <input type="text" class="form-input" name="last_name" placeholder="Member's last name" value="<?= $memberDetails['last_name'] ?>">
                        </div>
                        <div class="form-group">
                            <label for="noticeContent">Email</label>
                            <input type="text" class="form-input" name="email" placeholder="Member's last name" value="<?= $memberDetails['email'] ?>">
                        </div>
                        <div class="form-group">
                            <label for="noticeContent">Membership</label>
                            <select class="form-input" name="membership">
                                <?php if (!empty($memberships)) : ?>
                                    <?php foreach ($memberships as $membershipData) : ?>
                                        <option value="<?= $membershipData["id"] ?>" <?= $membershipData["id"] == $memberDetails["membership"] ? "selected" : "" ?>>
                                            <?= $membershipData["membership_title"] ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="noticeContent">Skype</label>
                            <input type="text" class="form-input" name="skype" placeholder="Member's skype" value="<?= $memberDetails['skype'] ?>">
                        </div>
                        <div class="form-group">
                            <label for="noticeContent">Phone</label>
                            <input type="text" class="form-input" name="phone" placeholder="Member's phone number" value="<?= $memberDetails['phone'] ?>">
                        </div>
                        <div class="form-group">
                            <label for="noticeContent">Telegram</label>
                            <input type="text" class="form-input" name="telegram" placeholder="Member's telegram" value="<?= $memberDetails['telegram'] ?>">
                        </div>
                        <div class="form-group">
                            <label for="noticeContent">PayPal</label>
                            <input type="text" class="form-input" name="paypal" placeholder="Member's PayPal" value="<?= $memberDetails['paypal'] ?>">
                        </div>
                        <div class="form-group">
                            <label for="noticeContent">Coinbase/BTC Wallet</label>
                            <input type="text" class="form-input" name="btc_coinbase" placeholder="Member's Coinbase wallet" value="<?= $memberDetails['btc_coinbase'] ?>">
                        </div>

                        <div class="form-group">
                            <label for="noticeContent">Skrill</label>
                            <input type="text" class="form-input" name="skrill" placeholder="Member's Skrill" value="<?= $memberDetails['skrill'] ?>">
                        </div>
                        <div class="form-group">
                            <label for="noticeContent">Transferwise</label>
                            <input type="text" class="form-input" name="transfer_wise" placeholder="Member's Transferwise" value="<?= $memberDetails['transfer_wise'] ?>">
                        </div>
                        <div class="form-group">
                            <label for="noticeContent">PerfectMoney</label>
                            <input type="text" class="form-input" name="perfect_money" placeholder="Member's PerfectMoney" value="<?= $memberDetails['perfect_money'] ?>">
                        </div>
                        <div class="form-group">
                            <label for="noticeContent">ETH Wallet</label>
                            <input type="text" class="form-input" name="eth_wallet" placeholder="Member's ETH Wallet" value="<?= $memberDetails['eth_wallet'] ?>">
                        </div>
                        <div class="form-group">
                            <label for="noticeContent">Balance</label>
                            <input type="number" step="0.01" class="form-input" name="balance" placeholder="Member's last name" value="<?= $memberDetails['balance'] ?>">
                        </div>
                        <div class="form-group">
                            <label for="noticeContent">Credits</label>
                            <input type="number" step="0.01" class="form-input" name="credits" placeholder="Member's email credits" value="<?= $memberDetails['credits'] ?>">
                        </div>
                        <div class="form-group">
                            <label for="noticeContent">Banner Ad Credits</label>
                            <input type="number" class="form-input" name="banner_credits" placeholder="Member's banner ad credits" value="<?= $memberDetails['banner_credits'] ?>">
                        </div>
                        <div class="form-group">
                            <label for="noticeContent">Text Ad Credits</label>
                            <input type="number" class="form-input" name="text_ad_credits" placeholder="Member's text ad credits" value="<?= $memberDetails['text_ad_credits'] ?>">
                        </div>
                        <button type="submit" class="btn btn-primary mt-6">Update Account</button>
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