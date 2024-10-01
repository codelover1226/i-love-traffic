<?php
require_once "load_classes.php";
$bannerAdController = new BannerAdsController();
$smallBannerAdController = new SmallBannerAdsController();
$siteController = new SiteSettingsController();
$siteSettings = $siteController->getSettings();
if (isset($_GET["size"]) && $_GET["size"] == "small" && isset($_GET["user"])) {
    $flag = 1;
    $bannerDetails = $smallBannerAdController->getRandomBannerAdDetails();
    if (!empty($bannerDetails)) {
        $bannerPublisherController = new BannerPublisherController();
        $bannerPublisherController->giveCredits($_GET["user"]);
    }
} else {
    $flag = 0;
    $bannerDetails = $bannerAdController->getRandomBannerAdDetails();
    if (!empty($bannerDetails)) {
        $bannerPublisherController = new BannerPublisherController();
        $bannerPublisherController->giveCredits($_GET["user"]);
    }
}
?>
<style>
    .bannerFrameWeb {
        width: 468px;
        height: 60px;
        position: relative;
    }

    .bannerFrameSmall {
        width: 125px;
        height: 125px;
        position: relative;
    }

    .adByTop {
        position: absolute;
        top: 0px;
        right: 0px;
        padding: 2px;
        background: rgb(46, 6, 45);
        background: linear-gradient(90deg, rgba(46, 6, 45, 1) 0%, rgba(121, 19, 9, 1) 100%, rgba(13, 35, 89, 1) 100%, rgba(22, 49, 55, 1) 100%);
        color: #ffffff;
        font-size: 11px;
        font-family: Arial, Helvetica, sans-serif;
        border-bottom-left-radius: 3px;

    }

    .adByBottom {
        position: absolute;
        bottom: 0px;
        right: 0px;
        padding: 2px;
        background: rgb(46, 6, 45);
        background: linear-gradient(90deg, rgba(46, 6, 45, 1) 0%, rgba(121, 19, 9, 1) 100%, rgba(13, 35, 89, 1) 100%, rgba(22, 49, 55, 1) 100%);
        color: #ffffff;
        font-size: 11px;
        font-family: Arial, Helvetica, sans-serif;
        border-top-left-radius: 3px;

    }
</style>
<?php if ($flag == 1 && !empty($bannerDetails)) : ?>
    <div class="bannerFrameSmall">
        <a href="<?= $siteSettings['installation_url'] . 'small-banner-click.php?id=' . $bannerDetails['id'] ?>" target="_blank"><img src="<?= $bannerDetails['image_link'] ?>" /></a>
        <a href="<?= $siteSettings['installation_url'] ?>" target="_blank">
            <div class="adByBottom" title="Ad by <?= $siteSettings['site_title'] ?>"><?= $siteSettings['site_title'] ?></div>
        </a>
    </div>
<?php elseif ($flag == 0 && !empty($bannerDetails)) : ?>
    <div class="bannerFrameWeb">
        <a href="<?= $siteSettings['installation_url'] . 'banner-click.php?id=' . $bannerDetails['id'] ?>" target="_blank"><img src="<?= $bannerDetails['image_link'] ?>" /></a>
        <a href="<?= $siteSettings['installation_url'] ?>" target="_blank">
            <div class="adByBottom" title="Ad by <?= $siteSettings['site_title'] ?>"><?= $siteSettings['site_title'] ?></div>
        </a>
    </div>
<?php endif; ?>