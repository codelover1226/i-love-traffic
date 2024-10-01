<?php
$currentPage = (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] === "on" ? "https" : "http") . "://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
if ($_SERVER["REQUEST_METHOD"] == "GET" && strcmp(basename($currentPage), basename(__FILE__)) == 0) {
    header("Location: / ");
    exit;
}
if (file_exists("load_classes.php")) {
    require_once "load_classes.php";
} else {
    if (file_exists("../load_classes.php")) {
        require_once "../load_classes.php";
    }
}
class SiteSettingsController extends Controller
{
    private $model;
    public function __construct()
    {
        $this->model = new SiteSettingsModel();
    }
    public function getSettings()
    {
        return $this->model->getSettings();
    }
    public function updateSettings()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $adminController = new AdminController();
            if (isset($_POST["site_title"]) && isset($_POST["installation_url"]) && isset($_POST["admin_email"]) && isset($_POST["google_captcha_public_key"]) && isset($_POST["google_captcha_private_key"]) && isset($_POST["logo_link"]) && isset($_POST["website_theme"]) && isset($_POST["member_registration"]) && isset($_POST["anti_cheat_system"]) && isset($_POST["email_validity"]) && isset($_POST["admin_csrf_token"])) {
                if ($this->arrayCheck($_POST)) {
                    return ["success" => false, "message" => "You have sent an array. We don't allow array here."];
                }
                if (empty($_POST["site_title"]) || empty($_POST["installation_url"]) || empty($_POST["admin_email"]) || empty($_POST["logo_link"]) || empty($_POST["website_theme"]) || empty($_POST["member_registration"]) || empty($_POST["anti_cheat_system"]) || empty($_POST["email_validity"]) || empty($_POST["admin_csrf_token"])) {
                    return ["success" => false, "message" => "All fields are required."];
                }
                if ($_POST["admin_csrf_token"] != $adminController->getAdminCSRFToken()) {
                    return ["success" => false, "message" => "Invalid request."];
                }
                if (255 < strlen($_POST["site_title"])) {
                    return ["success" => false, "message" => "Website title is too long. You can enter max 255 characters."];
                }
                if (!filter_var($_POST["admin_email"], FILTER_VALIDATE_EMAIL)) {
                    return ["success" => false, "message" => "Invalid admin email address."];
                }
                if (!filter_var($_POST["installation_url"], FILTER_VALIDATE_URL)) {
                    return ["success" => false, "message" => "Invalid installation url."];
                }
                if (!filter_var($_POST["logo_link"], FILTER_VALIDATE_URL)) {
                    return ["success" => false, "message" => "Invalid logo link."];
                }
                if (255 < strlen($_POST["google_captcha_public_key"])) {
                    return ["success" => false, "message" => "Invalid Google reCaptcha Public Key"];
                }
                if (255 < strlen($_POST["google_captcha_private_key"])) {
                    return ["success" => false, "message" => "Invalid Google reCaptcha Private Key"];
                }
                if (!is_numeric($_POST["member_registration"]) || $_POST["member_registration"] != 1 && $_POST["member_registration"] != 2) {
                    return ["success" => false, "message" => "Invalid member registration status."];
                }
                if (!is_numeric($_POST["anti_cheat_system"]) || $_POST["anti_cheat_system"] != 1 && $_POST["anti_cheat_system"] != 2) {
                    return ["success" => false, "message" => "Invalid anti-cheat system status."];
                }
                if (!is_numeric($_POST["email_validity"])) {
                    return ["success" => false, "message" => "Invalid email validity."];
                }
                if ($_POST["email_validity"] < 2 || 40 < $_POST["email_validity"]) {
                    return ["success" => false, "message" => "Invalid email validity."];
                }
                $this->model->updateSettings(["site_title" => $_POST["site_title"], "installation_url" => $_POST["installation_url"], "admin_email" => $_POST["admin_email"], "google_captcha_public_key" => $_POST["google_captcha_public_key"], "google_captcha_private_key" => $_POST["google_captcha_private_key"], "logo_link" => $_POST["logo_link"], "website_theme" => $_POST["website_theme"], "anti_cheat_system" => $_POST["anti_cheat_system"], "member_registration" => $_POST["member_registration"], "email_validity" => $_POST["email_validity"]]);
                return ["success" => true, "message" => "Website settings has been updated."];
            }
        }
    }
    public function updateSEOSettings()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $adminController = new AdminController();
            if (isset($_POST["meta_description"]) && isset($_POST["meta_keywords"]) && isset($_POST["banner_image"]) && isset($_POST["admin_csrf_token"])) {
                $array_flag = false;
                foreach ($_POST as $var) {
                    $array_flag = is_array($var);
                    if ($array_flag) {
                        if ($array_flag) {
                            return ["success" => false, "message" => "It seems that you have sent an array and we don't allow that."];
                        }
                        if (empty($_POST["meta_description"]) || empty($_POST["meta_keywords"]) || empty($_POST["banner_image"]) || empty($_POST["admin_csrf_token"])) {
                            return ["success" => false, "message" => "All fields are required."];
                        }
                        if ($_POST["admin_csrf_token"] != $adminController->getAdminCSRFToken()) {
                            return ["success" => false, "message" => "Invalid request."];
                        }
                        if (500 < strlen($_POST["meta_description"])) {
                            return ["success" => false, "message" => "Meta description is too long. You can enter max 500 characters."];
                        }
                        if (500 < strlen($_POST["meta_keywords"])) {
                            return ["success" => false, "message" => "Meta keywords is too long. You can enter max 400 characters."];
                        }
                        if (!filter_var($_POST["banner_image"], FILTER_VALIDATE_URL)) {
                            return ["success" => false, "message" => "Invalid banner image url."];
                        }
                        $this->model->updateSettings(["meta_description" => $_POST["meta_description"], "meta_keywords" => $_POST["meta_keywords"], "banner_image" => $_POST["banner_image"]]);
                        return ["success" => true, "message" => "Website SEO settings has been updated."];
                    }
                }
            }
        }
    }
    public function getWebsiteThemeList()
    {
        $allFileDirs = scandir("../themes");
        $allFileDirs = array_diff($allFileDirs, [".", ".."]);
        $counter = 0;
        $themeDirs = [];
        foreach ($allFileDirs as $val) {
            if (is_dir("../themes/" . $val)) {
                $themeDirs[$counter] = $val;
                $counter++;
            }
        }
        return $themeDirs;
    }
    public function getGoogleREcaptcha()
    {
        $siteSettings = $this->getSettings();
        if (!empty($siteSettings["google_captcha_public_key"]) && !empty($siteSettings["google_captcha_private_key"])) {
            echo "<div class=\"g-recaptcha\" data-sitekey=\"" . $this->getSettings()["google_captcha_public_key"] . "\"></div>";
            echo "<script src=\"https://www.google.com/recaptcha/api.js\" async defer></script>";
        }
    }
    public function emailValidityDays($currentDay)
    {
        for ($i = 2; $i <= 40; $i++) {
            if ($currentDay == $i) {
                echo "<option value=\"" . $i . "\" selected>" . $i . " Days</option>";
            } else {
                echo "<option value=\"" . $i . "\">" . $i . " Days</option>";
            }
        }
    }
}

?>