<?php
ob_start();
if (session_id() == "") {
    session_start();
} else if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
/*
 *
 *
 *          Author          :   Viacheslav Salenko
 *          Email           :   naturesky0411@gmail.com
 *          Websites        :   https://naturedev.vercel.app/
 *
 *
 */

$currentPage = (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] === "on" ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

if ($_SERVER["REQUEST_METHOD"] == "GET" && strcmp(basename($currentPage), basename(__FILE__)) == 0) {
    http_response_code(404);
    die("");
}
require_once "load_classes.php";
$siteSettings = new SiteSettingsController();
$bannerAdController = new BannerAdsController();
$bannerAd160600Controller = new BannerAds160600Controller();
$bannerAd72890Controller = new BannerAds72890Controller();
$bannerAd600400Controller = new BannerAds600400Controller();
$smallBannerAdController = new SmallBannerAdsController();
$textAdController = new TextAdsController();
$membersController = new MembersController();
$withdrawalRequestsController = new WithdrawalRequestsController();
$emailsController = new EmailsController();
$emailCreditsPackageController = new EmailCreditsPackagesController();
$membershipsController = new MembershipsController();
$productsController = new ProductsController();
$loginAdsController = new LoginSpotlightAdsController();
$ordersController = new OrdersController();
$rewardsController = new RewardsController();
$affiliateSettingsController = new AffiliateSettingsController();
$loginAdsSettingsController = new LoginSpotlightAdSettingsController();
$splashPagesController = new SplashPagesController();
$referralContestController = new ReferralContestController();
$activityContestController = new ActivityContestController();
$otherSettingsController = new OtherSettingsController();
$loginAdsController = new LoginSpotlightAdsController();
$noticeController = new NoticeController();
$emailClicksController = new EmailClicksController();
$emailDraftsController = new EmailDraftsController();
$adminAdsController = new AdminAdsController();
$paymentSettingsController = new PaymentSettingsController();
$siteSettingsData = $siteSettings->getSettings();
$downlineBuilderController = new DownlineBuilderController();
$salesContestController = new SalesContestController();
$bannerPublisherController = new BannerPublisherController();
$supportTicketsController = new SupportTicketsController();
$affiliateMessagingController = new AffiliateMessagingController();
$chatGPTController = new ChatGPTController();
$promotionalsEmailsController = new PromotionalEmailsController();

if (isset($_GET["referrer"]) && !empty($_GET["referrer"])) {
    setcookie("nsms_affiliate", $_GET["referrer"], time() + 7776000, "/", $_SERVER["HTTP_HOST"], false, true);
    $membersController->increaseReferralLinkClicks($_GET["referrer"]);
}

