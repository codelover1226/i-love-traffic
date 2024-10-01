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
$membersController = new MembersController();
$websiteSettingsController = new SiteSettingsController();
$memberDetails = "";
if (isset($_GET["username"]) && !empty($_GET["username"])) {
    $memberDetails = $membersController->getUserDetails($_GET["username"]);
}
$adminController->adminCSRFTokenGen();
$siteSettingsData = $websiteSettingsController->getSettings();
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
    <div class="pt-5">
        <?php if (empty($memberDetails)) : ?>
            <div class="alert alert-danger">Couldn't find the member.</div>
        <?php else : ?>
            <div class="mb-5 grid grid-cols-1 gap-5 lg:grid-cols-3 xl:grid-cols-4">
                <div class="panel">
                    <div class="mb-5 flex items-center justify-between">
                        <h5 class="text-lg font-semibold dark:text-white-light">Profile</h5>
                    </div>
                    <div class="mb-5">
                        <div class="flex flex-col items-center justify-center">
                            <img src="<?= $membersController->gravatar($memberDetails['email'], $siteSettingsData['installation_url'], 200) ?>" alt="image" class="mb-5 h-24 w-24 rounded-full object-cover">
                            <p class="text-xl font-semibold text-primary"><?= $memberDetails["username"] ?></p>
                        </div>
                        <ul class="m-auto mt-5 flex max-w-[160px] flex-col space-y-4 font-semibold text-white-dark">
                            <li class="flex items-center gap-2">
                                First Name : <?= $memberDetails["first_name"] ?>
                            </li>
                            <li class="flex items-center gap-2">
                                Last Name : <?= $memberDetails["last_name"] ?>
                            </li>
                            <li class="flex items-center gap-2">
                                Country: <?= $memberDetails["country"] ?>
                            </li>
                            <li class="flex items-center gap-2">
                                Joined Date: <?= date("d M, Y", $memberDetails["join_timestamp"]) ?>
                            </li>
                            <li>
                                <a href="<?= $memberDetails["email"] ?>" class="flex items-center gap-2" x-tooltip="<?= $memberDetails['email'] ?>">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0">
                                        <path opacity="0.5" d="M2 12C2 8.22876 2 6.34315 3.17157 5.17157C4.34315 4 6.22876 4 10 4H14C17.7712 4 19.6569 4 20.8284 5.17157C22 6.34315 22 8.22876 22 12C22 15.7712 22 17.6569 20.8284 18.8284C19.6569 20 17.7712 20 14 20H10C6.22876 20 4.34315 20 3.17157 18.8284C2 17.6569 2 15.7712 2 12Z" stroke="currentColor" stroke-width="1.5"></path>
                                        <path d="M6 8L8.1589 9.79908C9.99553 11.3296 10.9139 12.0949 12 12.0949C13.0861 12.0949 14.0045 11.3296 15.8411 9.79908L18 8" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
                                    </svg>
                                    <span class="truncate text-primary"><?= $memberDetails["email"] ?></span></a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="panel lg:col-span-2 xl:col-span-3">
                    <div class="mb-5">
                        <h5 class="text-lg font-semibold dark:text-white-light">Other Details</h5>
                    </div>
                    <div class="mb-5">
                        <div class="table-responsive font-semibold text-[#515365] dark:text-white-light">
                            <table class="whitespace-nowrap">
                                <tbody class="dark:text-white-dark">
                                    <tr>
                                        <td>Phone</td>
                                        <td>
                                            <?= $memberDetails["phone"] ?>
                                        </td>

                                    </tr>
                                    <tr>
                                        <td>Telegram</td>
                                        <td>
                                            <?= $memberDetails["telegram"] ?>
                                        </td>

                                    </tr>
                                    <tr>
                                        <td>Skype</td>
                                        <td>
                                            <?= $memberDetails["skype"] ?>
                                        </td>

                                    </tr>
                                    <tr>
                                        <td>Membership</td>
                                        <td>
                                            <?= $memberDetails["membership_title"] ?>
                                        </td>

                                    </tr>
                                    <tr>
                                        <td>Email Credits</td>
                                        <td>
                                            <?= $memberDetails["credits"] ?>
                                        </td>

                                    </tr>
                                    <tr>
                                        <td>Banner Ad Credits</td>
                                        <td>
                                            <?= $memberDetails["banner_credits"] ?>
                                        </td>

                                    </tr>
                                    <tr>
                                        <td>Text Ad Credits </td>
                                        <td>
                                            <?= $memberDetails["text_ad_credits"] ?>
                                        </td>

                                    </tr>
                                    <tr>
                                        <td>Balance</td>
                                        <td>
                                            $<?= $memberDetails["balance"] ?>
                                        </td>

                                    </tr>
                                    <tr>
                                        <td>PayPal</td>
                                        <td>
                                            <?= $memberDetails["paypal"] ?>
                                        </td>

                                    </tr>
                                    <tr>
                                        <td>BTC/Coinbase</td>
                                        <td>
                                            <?= $memberDetails["btc_coinbase"] ?>
                                        </td>

                                    </tr>
                                    <tr>
                                        <td>Skrill</td>
                                        <td>
                                            <?= $memberDetails["skrill"] ?>
                                        </td>

                                    </tr>
                                    <tr>
                                        <td>Transferwise</td>
                                        <td>
                                            <?= $memberDetails["transfer_wise"] ?>
                                        </td>

                                    </tr>
                                    <tr>
                                        <td>Perfectmoney</td>
                                        <td>
                                            <?= $memberDetails["perfect_money"] ?>
                                        </td>

                                    </tr>
                                    <tr>
                                        <td>ETH Wallet</td>
                                        <td>
                                            <?= $memberDetails["eth_wallet"] ?>
                                        </td>

                                    </tr>
                                    <tr>
                                        <td>Total Clicks</td>
                                        <td>
                                            <?= $memberDetails["total_clicks"] ?>
                                        </td>

                                    </tr>
                                    <tr>
                                        <td>Total Affiliate Link Views</td>
                                        <td>
                                            <?= $memberDetails["referral_link_clicks"] ?>
                                        </td>

                                    </tr>
                                    <tr>
                                        <td>Upline/Referrer</td>
                                        <td>
                                            <?= $memberDetails["referrer"] ?>
                                        </td>

                                    </tr>
                                    <tr>
                                        <td>Account Status</td>
                                        <td>
                                            <?= $membersController->memberStatus()[$memberDetails["account_status"]] ?>
                                        </td>

                                    </tr>
                                    <tr>
                                        <td>Registration IP</td>
                                        <td>
                                            <?= $memberDetails["registration_ip"] ?>
                                        </td>

                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>


<?php require_once "themes/default/incs/footer.theme.php"; ?>