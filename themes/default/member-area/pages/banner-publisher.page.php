<?php

$currentPage = (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] === "on" ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
if ($_SERVER["REQUEST_METHOD"] == "GET" && strcmp(basename($currentPage), basename(__FILE__)) == 0) {
    http_response_code(404);
    die("");
}
require_once "themes/default/member-area/incs/header.inc.php";
$settings = $bannerPublisherController->getSettings();
$membersController->generateUserCSRFToken();
?>

<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <?php require_once "themes/default/member-area/incs/sidebar-ads.inc.php"; ?>
            <div class="col-xl-9">
                <div class="col-lg-12">
                    <div class="card border border-primary">
                        <div class="card-header bg-transparent border-primary">
                            <h5 class="my-0 text-primary"><i class="mdi mdi-bullseye-arrow me-3"></i>Banner Publisher - Earn Banner Credits</h5>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-dark">You will earn <?= $settings["settings_value"] ?> Banner Ad Credits per banner surfing</div>
                            <p class="card-text">
                                Place the banner ad code on your website and earn banner ad credits.
                                <br>
                                <span class="badge border border-primary text-primary" style="margin: 10px;">468x60 Banner Code</span><br>
                                <textarea class="form-control"> &lt;iframe frameborder="0" scrolling="no" width="468" height="60" marginwidth="0" marginheight="0" hspace="0" vspace="0" src="<?= $siteSettingsData['installation_url'] . 'banner-surfer.php?size=web&user=' . $userInfo['username'] ?>" &gt;&lt;/iframe&gt; </textarea>
                                <span class="badge border border-danger text-primary" style="margin: 10px;">468x60 Banner Preview</span><br>
                                <iframe frameborder="0" scrolling="no" width="468" height="60" marginwidth="0" marginheight="0" hspace="0" vspace="0" src="<?= $siteSettingsData['installation_url'] . 'banner-surfer.php?size=web&user=' ?>"> </iframe>
                                
                                <br>
                                <span class="badge border border-primary text-primary" style="margin: 10px;">728x90 Banner Code</span><br>
                                <textarea class="form-control"> &lt;iframe frameborder="0" scrolling="no" width="728" height="90" marginwidth="0" marginheight="0" hspace="0" vspace="0" src="<?= $siteSettingsData['installation_url'] . 'banner-surfer.php?size=web728&user=' . $userInfo['username'] ?>" &gt;&lt;/iframe&gt; </textarea>
                                <span class="badge border border-danger text-primary" style="margin: 10px;">728x90 Banner Preview</span><br>
                                <iframe frameborder="0" scrolling="no" width="728" height="90" marginwidth="0" marginheight="0" hspace="0" vspace="0" src="<?= $siteSettingsData['installation_url'] . 'banner-surfer.php?size=web728&user=' ?>"> </iframe>
                                
                                <br>
                                <span class="badge border border-primary text-primary" style="margin: 10px;">600x400 Banner Code</span><br>
                                <textarea class="form-control"> &lt;iframe frameborder="0" scrolling="no" width="600" height="400" marginwidth="0" marginheight="0" hspace="0" vspace="0" src="<?= $siteSettingsData['installation_url'] . 'banner-surfer.php?size=web600&user=' . $userInfo['username'] ?>" &gt;&lt;/iframe&gt; </textarea>
                                <span class="badge border border-danger text-primary" style="margin: 10px;">400 Banner Preview</span><br>
                                <iframe frameborder="0" scrolling="no" width="600" height="400" marginwidth="0" marginheight="0" hspace="0" vspace="0" src="<?= $siteSettingsData['installation_url'] . 'banner-surfer.php?size=web600&user=' ?>"> </iframe>
                                
                                <br>
                                <span class="badge border border-primary text-primary" style="margin: 10px;">468x60 Banner Code</span><br>
                                <textarea class="form-control"> &lt;iframe frameborder="0" scrolling="no" width="468" height="60" marginwidth="0" marginheight="0" hspace="0" vspace="0" src="<?= $siteSettingsData['installation_url'] . 'banner-surfer.php?size=web&user=' . $userInfo['username'] ?>" &gt;&lt;/iframe&gt; </textarea>
                                <span class="badge border border-danger text-primary" style="margin: 10px;">468x60 Banner Preview</span><br>
                                <iframe frameborder="0" scrolling="no" width="468" height="60" marginwidth="0" marginheight="0" hspace="0" vspace="0" src="<?= $siteSettingsData['installation_url'] . 'banner-surfer.php?size=web&user=' ?>"> </iframe>
                                
                                <br>
                                <span class="badge border border-primary text-primary" style="margin: 10px;">160x600 Banner Code</span><br>
                                <textarea class="form-control"> &lt;iframe frameborder="0" scrolling="no" width="160" height="600" marginwidth="0" marginheight="0" hspace="0" vspace="0" src="<?= $siteSettingsData['installation_url'] . 'banner-surfer.php?size=web160&user=' . $userInfo['username'] ?>" &gt;&lt;/iframe&gt; </textarea>
                                <span class="badge border border-danger text-primary" style="margin: 10px;">160x600 Banner Preview</span><br>
                                <iframe frameborder="0" scrolling="no" width="160" height="600" marginwidth="0" marginheight="0" hspace="0" vspace="0" src="<?= $siteSettingsData['installation_url'] . 'banner-surfer.php?size=web160&user=' ?>"> </iframe>
                                
                                <br>
                                <span class="badge border border-primary text-primary" style="margin: 10px;">125x125 Banner Code</span><br>
                                <textarea class="form-control"> &lt;iframe frameborder="0" scrolling="no" width="125" height="125" marginwidth="0" marginheight="0" hspace="0" vspace="0" src="<?= $siteSettingsData['installation_url'] . 'banner-surfer.php?size=small&user=' . $userInfo['username'] ?>" &gt;&lt;/iframe&gt; </textarea>
                                <span class="badge border border-danger text-primary" style="margin: 10px;">125x125 Banner Preview</span><br>
                                <iframe frameborder="0" scrolling="no" width="125" height="125" marginwidth="0" marginheight="0" hspace="0" vspace="0" src="<?= $siteSettingsData['installation_url'] . 'banner-surfer.php?size=small&user=' ?>"> </iframe>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <?php require_once "themes/default/member-area/incs/footer-ads.inc.php"; ?>

        </div>
    </div>
</div>

<?php require_once "themes/default/member-area/incs/footer.inc.php"; ?>