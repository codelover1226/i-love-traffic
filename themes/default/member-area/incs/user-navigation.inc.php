<?php

$currentPage = (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] === "on" ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
if ($_SERVER["REQUEST_METHOD"] == "GET" && strcmp(basename($currentPage), basename(__FILE__)) == 0) {
    http_response_code(404);
    die("");
}
?>

<div class="col-lg-3">
    <div class="bg-primary text-white p-2 mb-3 d-lg-none d-sm-block rounded">
        <button class="btn btn-primary" onclick="$('#accountNavbar').toggleClass('d-none');">
            <i class="fa fa-navicon"></i>
        </button>
    </div>
    <div class="d-none d-lg-block  mb-3" id="accountNavbar">
        <ul class="list-group small">
            <li class=" userAccountNav list-group-item-primary font-weight-bold">
                <a data-toggle="collapse"><i class="fa fa-user"></i> Account Settings</a>
            </li>
            <div class="collapse show" id="globalItems">
                <li class="list-group-item"><a href="dashboard.php?action=account">Edit Account Info</a></li>
                <li class="list-group-item"><a href="dashboard.php?action=password">Change Password</a></li>
                <li class="list-group-item"><a href="dashboard.php?action=vacation">Vacation Settings</a></li>
                <li class="list-group-item"><a href="dashboard.php?action=email-subscription">Email Subscription</a></li>
                <li class="list-group-item"><a href="store.php">Buy Membership</a></li>
            </div>
            <li class=" userAccountNav list-group-item-primary font-weight-bold">
                <a data-toggle="collapse"><i class="fa fa-ad"></i> Advertising</a>
            </li>
            <div class="collapse show" id="globalItems">
                <li class="list-group-item"><a href="store.php">Buy Advertising</a></li>
                <li class="list-group-item"><a href="emails.php?action=send">Send Email</a></li>
                <li class="list-group-item"><a href="emails.php?action=schedule">Schedule Email</a></li>
                <li class="list-group-item"><a href="dashboard.php?action=convert-credits">Convert Credits</a></li>
                <li class="list-group-item"><a href="emails.php">Email History</a></li>
                <li class="list-group-item"><a href="web-banners.php">Banner Ads (468x60)</a></li>
                <li class="list-group-item"><a href="small-banners.php">Banner Ads (125x125)</a></li>
                <li class="list-group-item"><a href="text-ads.php">Text Ads</a></li>
                <li class="list-group-item"><a href="login-ads.php">Login Spotlight Ads</a></li>
            </div>
            <li class=" userAccountNav list-group-item-primary font-weight-bold">
                <a data-toggle="collapse"><i class="fa fa-dollar"></i> Affiliate</a>
            </li>
            <div class="collapse show" id="globalItems">
                <li class="list-group-item"><a href="withdrawal.php?action=payment-method">Payment Gateway</a></li>
                <li class="list-group-item"><a href="withdrawal.php">Withdrawal Requests</a></li>
                <li class="list-group-item"><a href="withdrawal.php?action=withdraw">Withdraw Earnings</a></li>
                <li class="list-group-item"><a href="affiliates.php">Sales</a></li>
                <li class="list-group-item"><a href="rewards.php">Rewards</a></li>
                <li class="list-group-item"><a href="referrals.php">Referrals</a></li>
                <li class="list-group-item"><a href="referrals.php?action=promotion">Promotional Tools</a></li>
                <li class="list-group-item"><a href="contest.php?action=referral">Referral Contest</a></li>
                <li class="list-group-item"><a href="contest.php?action=activity">Activity Contest</a></li>
            </div>
        </ul>
    </div>
</div>