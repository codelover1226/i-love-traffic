<?php

$currentPage = (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] === "on" ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
if ($_SERVER["REQUEST_METHOD"] == "GET" && strcmp(basename($currentPage), basename(__FILE__)) == 0) {
    http_response_code(404);
    die("");
}
require_once "themes/default/member-area/incs/header.inc.php";
$noticeDetails = $noticeController->getNotice();
$topSellerList = $ordersController->topSellerThisMonth();
$topReferrerList = $membersController->topReferrersThisMonth();
$topClickers = $emailClicksController->topClickersThisMonth();
$noticeContent = $noticeController->getNotice();
$membershipExpire = "";
if (isset($_GET["type"]) && isset($_GET["id"]) && isset($_GET["amount"])) {
    if (!empty($_GET["type"]) && !empty($_GET["id"]) && !empty($_GET["amount"])) {
        if (is_numeric($_GET["amount"]) && $_GET["amount"] > 0) {
            if ($_GET["type"] == "membership" || $_GET["type"] == "combo" || $_GET["type"] == "credits") {
                if (is_numeric($_GET["id"]) && $_GET["id"] > 0) {
                    $paymentIPNController = new PaymentIPNController();
                    $flag = $paymentIPNController->processOrder($userInfo["username"], $_GET["type"], $_GET["id"], $_GET["amount"], "account", strtoupper(uniqid("nsms_")));
                    $userInfo = $membersController->getUserDetails($userInfo["username"]);
                }
            } else if ($_GET["type"] == "loginads") {
                $paymentIPNController = new PaymentIPNController();
                if (isset($_GET["website_link"]) && !empty($_GET["website_link"])) {
                    if (filter_var($_GET["website_link"], FILTER_SANITIZE_URL)) {
                        $flag = $paymentIPNController->processOrder($userInfo["username"], $_GET["type"], $_GET["id"], $_GET["amount"], "account", strtoupper(uniqid("nsms_")), $_GET["website_link"]);
                        $userInfo = $membersController->getUserDetails($userInfo["username"]);
                    } else {
                        $flag = array(
                            "success" => false,
                            "message" => "Invalid website link."
                        );
                    }
                }
            }
        }
    }
}
if (!empty($userInfo["membership_end_time"])) {
    if (is_numeric($userInfo["membership_end_time"])) {
        $membershipExpire = "[ Expires " . date("d M, Y", $userInfo["membership_end_time"]) . " ]";
    } else {
        $membershipExpire = "[ " . $userInfo["membership_end_time"] . " ]";
    }
}

function formatNumberDigits($number) {
    if ($number > 10000) {
        $divide = $number;
        return number_format($divide, 0, ".", ",");
    } else {
        return $number;
    }
}
?>
<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12">
                <?php if ($affiliateMessagingController->totalAffiliateUnreadMessage($userInfo["username"]) > 0) : ?>
                <div class="alert alert-primary" role="alert">
                    You have unread affiliate message. Go to your <a style="text-decoration: underline;"
                        href="affiliate-messages.php">inbox</a>.
                </div>
                <?php endif; ?>


                <?php if ($noticeContent["notice_status"] == 1) : ?>
                <?php if ($noticeContent["notice_style"] == 1) : ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?= $noticeContent["notice"] ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php elseif ($noticeContent["notice_style"] == 2) : ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?= $noticeContent["notice"] ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php else : ?>
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <?= $noticeContent["notice"] ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php endif; ?>
                <?php endif; ?>
                <div class="card crm-widget">
                    <div class="card-body p-0">
                        <div class="row row-cols-md-3 row-cols-1">
                            <div class="col col-lg border-end">
                                <div class="py-4 px-3">
                                    <p class="text-muted text-uppercase fs-16"><span style="color:#ff0033;">Welcome to
                                            I-Love Traffic. Email Exchange and Viral Banner Distributor</span></p>
                                    <p class="text-muted fs-15"><span style="color:#000000;">New members use the promo
                                            code: I Love Traffic for a bonus of 1000 mailing credits,
                                            500 banner ad impressions and 250 text ad impressions.</span></p>
                                    <p class="text-muted fs-15"><span style="color:#000000;">All members use the promo
                                            code: I Love Banners for a bonus of 10k banner ad credits.</span></p>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card crm-widget">
                    <div class="card-body p-0">
                        <div class="row row-cols-md-3 row-cols-1">
                            <div class="col col-lg border-end">
                                <div class="py-4 px-3">
                                    <p class="text-muted text-uppercase fs-16" style="text-align: center;">
                                        <font color="#000000">💙 You Will Love our Viral Banner system. We have already
                                            delivered&nbsp;</font>
                                    </p>
                                    <p class="text-uppercase fs-16" style="text-align: center;">
                                        <?= formatNumberDigits($bannerAdController->totalBannerViews() + $smallBannerAdController->totalBannerViews()) ?>
                                        Views</span></p>
                                    <p class="text-muted text-uppercase fs-16" style="text-align: center;">
                                        <font color="#000000">across the broader web so far and we're just getting
                                            started! 💙</font>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xxl-6">
                        <div class="d-flex flex-column h-100">
                            <div class="row h-100">
                                <div class="card">
                                    <div class="card-header">
                                        <h6 class="card-title mb-0">💙 Profile Information</h6>
                                    </div>
                                    <div class="card-body p-4 text-center">
                                        <div class="mt-4 mt-md-0">
                                            <img src="<?= $membersController->gravatar($userInfo['email'], $siteSettingsData['installation_url']) ?>"
                                                alt="" class="img-thumbnail rounded-circle avatar-xl">
                                        </div>
                                        <h5 class="card-title mb-1">
                                            <?= $userInfo['first_name'] . " " . $userInfo['last_name'] ?></h5>
                                        <p class="text-muted mb-0">Membership : <?= $userInfo['membership_title'] ?>
                                            <br>Membership Expire Date : <?= $membershipExpire ?>
                                        </p>
                                        <?php if (!empty($userInfo["referrer"])) : ?>
                                        <p class="text-muted mb-0">You are referred by <?= $userInfo['referrer'] ?></p>
                                        <?php endif; ?>
                                        <br>
                                        <button type="button" class="btn btn-primary btn-lg">
                                            Balance <span
                                                class="badge bg-success ms-1">$<?= $userInfo['balance'] ?></span>
                                        </button>
                                        <button type="button" class="btn btn-primary btn-lg">
                                            Credits <span
                                                class="badge bg-danger ms-1"><?= $userInfo['credits'] ?></span>
                                        </button>
                                    </div>
                                    <div class="card-footer text-center">
                                        <ul class="list-inline mb-0">
                                            <li class="list-inline-item">
                                                <a class="btn btn-danger waves-effect waves-light"
                                                    href="dashboard.php?action=vacation" role="button">Vacation
                                                    Settings</a>
                                            </li>
                                            <li class="list-inline-item">
                                                <a class="btn btn-dark waves-effect waves-light"
                                                    href="dashboard.php?action=email-subscription" role="button">Email
                                                    Subscription</a>
                                            </li>
                                            <li class="list-inline-item">
                                                <a class="btn btn-primary waves-effect waves-light"
                                                    href="affiliates.php" role="button">Sales</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card card-animate">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between">
                                                <div>
                                                    <span class="badge bg-danger">Text Ad</span><br>
                                                    <?= $textAdController->getTextAd() ?>
                                                </div>
                                                <div>
                                                    <div class="avatar-sm flex-shrink-0">
                                                        <span class="avatar-title bg-info-subtle rounded-circle fs-2">
                                                            <i data-feather="file-text" class="text-info"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card card-animate">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between">
                                                <div>
                                                    <span class="badge bg-danger">Text Ad</span><br>
                                                    <?= $textAdController->getTextAd() ?>
                                                </div>
                                                <div>
                                                    <div class="avatar-sm flex-shrink-0">
                                                        <span class="avatar-title bg-info-subtle rounded-circle fs-2">
                                                            <i data-feather="file-text" class="text-info"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xxl-6">
                        <div class="d-flex flex-column h-100">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card card-animate">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between">
                                                <div>
                                                    <p class="fw-medium text-muted mb-0" style="font-size: 20px;">Total
                                                        Referrals</p>
                                                    <h2 class="mt-4 ff-secondary fw-semibold">
                                                        <span><?= $membersController->totalUserReferrals($username) ?></span>
                                                    </h2>
                                                </div>
                                                <div>
                                                    <div class="avatar-sm flex-shrink-0">
                                                        <span class="avatar-title bg-info-subtle rounded-circle fs-2">
                                                            <i data-feather="users" class="text-info"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="card card-animate">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between">
                                                <div>
                                                    <p class="fw-medium text-muted mb-0" style="font-size: 20px;">
                                                        Referral Link Clicks</p>
                                                    <h2 class="mt-4 ff-secondary fw-semibold">
                                                        <span><?= $userInfo["referral_link_clicks"] ?></span>
                                                    </h2>
                                                </div>
                                                <div>
                                                    <div class="avatar-sm flex-shrink-0">
                                                        <span class="avatar-title bg-info-subtle rounded-circle fs-2">
                                                            <i data-feather="activity" class="text-info"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card card-animate">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between">
                                                <div>
                                                    <p class="fw-medium text-muted mb-0" style="font-size: 20px;">Email
                                                        Credits</p>
                                                    <h2 class="mt-4 ff-secondary fw-semibold">
                                                        <span><span><?= $userInfo["credits"] ?></span></span>

                                                    </h2>
                                                </div>
                                                <div>
                                                    <div class="avatar-sm flex-shrink-0">
                                                        <span class="avatar-title bg-info-subtle rounded-circle fs-2">
                                                            <i data-feather="mail" class="text-info"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="card card-animate">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between">
                                                <div>
                                                    <p class="fw-medium text-muted mb-0" style="font-size: 20px;">Banner
                                                        Ad Credits</p>
                                                    <h2 class="mt-4 ff-secondary fw-semibold">
                                                        <span><?= $userInfo["banner_credits"] ?></span>
                                                    </h2>
                                                </div>
                                                <div>
                                                    <div class="avatar-sm flex-shrink-0">
                                                        <span class="avatar-title bg-info-subtle rounded-circle fs-2">
                                                            <i data-feather="image" class="text-info"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card card-animate">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between">
                                                <div>
                                                    <p class="fw-medium text-muted mb-0" style="font-size: 20px;">Text
                                                        Ad Credits</p>
                                                    <h2 class="mt-4 ff-secondary fw-semibold">
                                                        <span><?= $userInfo["text_ad_credits"] ?></span>
                                                    </h2>
                                                </div>
                                                <div>
                                                    <div class="avatar-sm flex-shrink-0">
                                                        <span class="avatar-title bg-info-subtle rounded-circle fs-2">
                                                            <i data-feather="file-text" class="text-info"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6" style="display: none;">
                                    <div class="card card-animate">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between">
                                                <div>
                                                    <p class="fw-medium text-muted mb-0" style="font-size: 20px;">Total
                                                        Sales</p>
                                                    <h2 class="mt-4 ff-secondary fw-semibold">
                                                        <span><?= $ordersController->totalAffiliateSales($username) ?></span>
                                                    </h2>
                                                </div>
                                                <div>
                                                    <div class="avatar-sm flex-shrink-0">
                                                        <span class="avatar-title bg-info-subtle rounded-circle fs-2">
                                                            <i data-feather="shopping-cart" class="text-info"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="card card-animate">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between">
                                                <div>
                                                    <p class="fw-medium text-muted mb-0" style="font-size: 20px;">Login
                                                        Ad Credits</p>
                                                    <h2 class="mt-4 ff-secondary fw-semibold">
                                                        <span><?= $userInfo["login_ad_credits"] ?></span>
                                                    </h2>
                                                </div>
                                                <div>
                                                    <div class="avatar-sm flex-shrink-0">
                                                        <span class="avatar-title bg-info-subtle rounded-circle fs-2">
                                                            <i data-feather="log-in" class="text-info"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="card card-animate">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between">
                                                <div>
                                                    <span class="badge bg-dark" style="font-size: 14px">Admin
                                                        Recommended Site</span><br>
                                                    <?= $adminAdsController->showAd() ?>
                                                </div>
                                                <div>
                                                    <div class="avatar-sm flex-shrink-0">
                                                        <span class="avatar-title bg-info-subtle rounded-circle fs-2">
                                                            <i data-feather="thumbs-up" class="text-info"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-4 col-md-6">
                        <div class="card card-height-100">
                            <div class="card-header align-items-center d-flex">
                                <h4 class="card-title mb-0 flex-grow-1">Top Referrers This Month</h4>

                            </div>
                            <div class="card-body">
                                <div class="table-responsive table-card">
                                    <table class="table align-middle table-borderless table-centered table-nowrap mb-0">
                                        <thead class="text-muted table-light">
                                            <tr>
                                                <th scope="col">#</th>
                                                <th scope="col">Username</th>
                                                <th scope="col">Total Referrals</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (!empty($topReferrerList)) : ?>
                                            <?php $counter = 1;
                                        foreach ($topReferrerList as $topReferrer) : ?>
                                            <tr>
                                                <td>
                                                    <p><?= $counter ?></p>
                                                </td>
                                                <td>
                                                    <p><?= $topReferrer["referrer"] ?></p>

                                                </td>
                                                <td>
                                                    <p><?= $topReferrer["total_referrals"] ?></p>

                                                </td>
                                            </tr>
                                            <?php $counter++;
                                        endforeach; ?>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-4 col-md-6">
                        <div class="card card-height-100">
                            <div class="card-header align-items-center d-flex">
                                <h4 class="card-title mb-0 flex-grow-1">Top Email Readers This Month</h4>

                            </div>
                            <div class="card-body">
                                <div class="table-responsive table-card">
                                    <table class="table align-middle table-borderless table-centered table-nowrap mb-0">
                                        <thead class="text-muted table-light">
                                            <tr>
                                                <th scope="col">#</th>
                                                <th scope="col">Username</th>
                                                <th scope="col">Total Reads</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (!empty($topClickers)) : ?>
                                            <?php $counter = 1;
                                        foreach ($topClickers as $topClicker) : ?>
                                            <tr>
                                                <td>
                                                    <p><?= $counter ?></p>
                                                </td>
                                                <td>
                                                    <p><?= $topClicker["username"] ?></p>

                                                </td>
                                                <td>
                                                    <p><?= $topClicker["total_clicks"] ?></p>

                                                </td>
                                            </tr>
                                            <?php $counter++;
                                        endforeach; ?>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row col-xl-4 col-md-6">
                        <div class="col-lg-6 justify-content-center"><?= $bannerAd160600Controller->getBannerAd() ?>
                        </div>
                        <div class="col-lg-6 justify-content-center"><?= $bannerAd160600Controller->getBannerAd() ?>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6 justify-content-center"><?= $bannerAdController->getBannerAd() ?></div>
                    <div class="col-lg-6 justify-content-center"><?= $bannerAdController->getBannerAd() ?></div>
                </div>
                <div class="row mt-3">
                    <div class="col-lg-6 justify-content-center"><?= $bannerAd600400Controller->getBannerAd() ?></div>
                    <div class="col-lg-6 justify-content-center"><?= $bannerAd600400Controller->getBannerAd() ?></div>
                </div>
                <div class="row mt-3">
                    <div class="col-lg-12 d-flex justify-content-center "><?= $bannerAd72890Controller->getBannerAd() ?>
                    </div>
                    <div class="col-lg-12 d-flex justify-content-center mt-3">
                        <?= $bannerAd72890Controller->getBannerAd() ?></div>
                </div>
            </div>

        </div>
    </div>
    <?php require_once "themes/default/member-area/incs/footer.inc.php"; ?>