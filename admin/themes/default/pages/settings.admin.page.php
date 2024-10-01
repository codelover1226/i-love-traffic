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
$settingsController = new SiteSettingsController();
$flag = $settingsController->updateSettings();
$settings = $settingsController->getSettings();
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
            <span><?= $title ?></span>
        </li>
    </ul>
    <div class="grid grid-cols-1 gap-6 pt-5 lg:grid-cols-2">
        <div class="panel">
            <div class="mb-5">
                <form class="forms-sample" action="" method="POST">
                    <div class="form-group">
                        <label for="noticeContent">Website Title</label>
                        <input type="text" class="form-input" name="site_title" value="<?= $settings['site_title'] ?>" placeholder="Your website title">
                        <input type="hidden" name="admin_csrf_token" value="<?= $adminController->getAdminCSRFToken() ?>">
                    </div>
                    <div class="form-group">
                        <label for="noticeContent">Installation URL</label>
                        <input type="url" class="form-input" name="installation_url" placeholder="Example : https://nsmailerscript.com/" value="<?= $settings['installation_url'] ?>">
                    </div>
                    <div class="form-group">
                        <label for="noticeContent">Logo Link</label>
                        <input type="url" class="form-input" name="logo_link" placeholder="Example : https://nsmailerscript.com/images/logo.png" value="<?= $settings['logo_link'] ?>">
                    </div>
                    <div class="form-group">
                        <label for="noticeContent">Admin/Contact Email</label>
                        <input type="email" class="form-input" name="admin_email" placeholder="Administrator email" value="<?= $settings['admin_email'] ?>">
                    </div>
                    <div class="form-group">
                        <label for="exampleSelectGender">Website Theme</label>
                        <select class="form-input" name="website_theme">
                            <?php if (!empty($settingsController->getWebsiteThemeList())) : ?>
                                <?php foreach ($settingsController->getWebsiteThemeList() as $themeDir) : ?>
                                    <option value="<?= $themeDir ?>" <?= $settings["website_theme"] == $themeDir ? "selected" : "" ?>><?= $themeDir ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="exampleSelectGender">Member Registration</label>
                        <select class="form-input" name="member_registration">
                            <option value="1" <?= $settings["member_registration"] == 1 ? "selected" : "" ?>>Enable</option>
                            <option value="2" <?= $settings["member_registration"] == 2 ? "selected" : "" ?>>Disable</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="noticeContent">Google reCaptcha (V2) Public Key </label>
                        <input type="text" class="form-input" name="google_captcha_public_key" placeholder="Google reCaptch V2 Public Key" value="<?= $settings['google_captcha_public_key'] ?>">
                    </div>
                    <div class="form-group">
                        <label for="noticeContent">Google reCaptcha (V2) Private Key </label>
                        <input type="text" class="form-input" name="google_captcha_private_key" placeholder="Google reCaptch V2 Public Key" value="<?= $settings['google_captcha_private_key'] ?>">
                    </div>
                    <div class="form-group">
                        <label for="exampleSelectGender">Anti-Cheat System</label>
                        <select class="form-input" name="anti_cheat_system">
                            <option value="1" <?= $settings["anti_cheat_system"] == 1 ? "selected" : "" ?>>Enable</option>
                            <option value="2" <?= $settings["anti_cheat_system"] == 2 ? "selected" : "" ?>>Disable</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="exampleSelectGender">Email Validity Time (Days)</label>
                        <select class="form-input" name="email_validity">
                            <?= $settingsController->emailValidityDays($settings["email_validity"]) ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary mt-6">Update</button>
                </form>
            </div>
        </div>
        <div class="panel">
            <h5 style="font-size: 25px;">Available Macros & Functions</h5>
            <br>
            <p>
                Use these macros for splash pages<br>
                Referral Link (Homepage)<br>
                {REF_HOME}<br><br>
                Referral Link (Register Page)<br>
                {REF_REG}<br><br><br>
                Use these functions for php pages<br>
                Referral Link (Homepage)<br>
                &lt;?= $membersController->refHomeLink() ?><br><br>

                Referral Link (Register Page)
                &lt;?= $membersController->refRegLink() ?><br><br>
            </p>
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