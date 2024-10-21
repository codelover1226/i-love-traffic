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
class OtherSettingsController extends Controller
{
    private $model;
    public function __construct()
    {
        $this->model = new OtherSettingsModel();
    }
    public function getBannerCreditConversation()
    {
        return $this->model->getSettingsValue("banner_credit_conversation");
    }
    public function getCoopUrlCreditConversation()
    {
        return $this->model->getSettingsValue("coop_credit_conversation");
    }
    public function getLoginAdCreditConversation()
    {
        return $this->model->getSettingsValue("login_credit_conversation");
    }
    public function updateBannerCreditConvesationRate()
    {
        if (isset($_POST["banner_credit_conversation"]) && isset($_POST["admin_csrf_token"])) {
            $array_flag = false;
            if ($this->arrayCheck($_POST)) {
                return ["success" => false, "message" => "You have sent an array. We don't allow array here."];
            }
            $adminController = new AdminController();
            if (empty($_POST["banner_credit_conversation"]) || empty($_POST["admin_csrf_token"])) {
                return ["success" => false, "message" => "All fields are required."];
            }
            if ($_POST["admin_csrf_token"] != $adminController->getAdminCSRFToken()) {
                return ["success" => false, "message" => "Invalid request."];
            }
            if (!is_numeric($_POST["banner_credit_conversation"]) || $_POST["banner_credit_conversation"] < 1) {
                return ["success" => false, "message" => "Conversation rate must be greater than 0."];
            }
            $this->model->updateSettings(["settings_value" => $_POST["banner_credit_conversation"]], "banner_credit_conversation");
            return ["success" => true, "message" => "Conversation rate has been updated."];
        }
    }
    public function updateLoginCreditConvesationRate()
    {
        if (isset($_POST["login_credit_conversation"]) && isset($_POST["admin_csrf_token"])) {
            $array_flag = false;
            if ($this->arrayCheck($_POST)) {
                return ["success" => false, "message" => "You have sent an array. We don't allow array here."];
            }
            $adminController = new AdminController();
            if (empty($_POST["login_credit_conversation"]) || empty($_POST["admin_csrf_token"])) {
                return ["success" => false, "message" => "All fields are required."];
            }
            if ($_POST["admin_csrf_token"] != $adminController->getAdminCSRFToken()) {
                return ["success" => false, "message" => "Invalid request."];
            }
            if (!is_numeric($_POST["login_credit_conversation"]) || $_POST["login_credit_conversation"] < 1) {
                return ["success" => false, "message" => "Conversation rate must be greater than 0."];
            }
            $this->model->updateSettings(["settings_value" => $_POST["login_credit_conversation"]], "login_credit_conversation");
            return ["success" => true, "message" => "Conversation rate has been updated."];
        }
    }
    public function updateCoopCreditConvesationRate()
    {
        if (isset($_POST["coop_credit_conversation"]) && isset($_POST["admin_csrf_token"])) {
            $array_flag = false;
            if ($this->arrayCheck($_POST)) {
                return ["success" => false, "message" => "You have sent an array. We don't allow array here."];
            }
            $adminController = new AdminController();
            if (empty($_POST["coop_credit_conversation"]) || empty($_POST["admin_csrf_token"])) {
                return ["success" => false, "message" => "All fields are required."];
            }
            if ($_POST["admin_csrf_token"] != $adminController->getAdminCSRFToken()) {
                return ["success" => false, "message" => "Invalid request."];
            }
            if (!is_numeric($_POST["coop_credit_conversation"]) || $_POST["coop_credit_conversation"] < 1) {
                return ["success" => false, "message" => "Conversation rate must be greater than 0."];
            }
            $this->model->updateSettings(["settings_value" => $_POST["coop_credit_conversation"]], "coop_credit_conversation");
            return ["success" => true, "message" => "Conversation rate has been updated."];
        }
    }
    public function getTextAdCreditConversation()
    {
        return $this->model->getSettingsValue("text_ad_credit_conversation");
    }
    public function updateTextAdCreditConvesationRate()
    {
        if (isset($_POST["text_ad_credit_conversation"]) && isset($_POST["admin_csrf_token"])) {
            $array_flag = false;
            foreach ($_POST as $var) {
                $array_flag = is_array($var);
                if ($array_flag) {
                    return ["success" => false, "message" => "It seems that you have sent an array and we don't allow that."];
                }
                else {
                    $adminController = new AdminController();
                    if (empty($_POST["text_ad_credit_conversation"]) || empty($_POST["admin_csrf_token"])) {
                        return ["success" => false, "message" => "All fields are required."];
                    }
                    if ($_POST["admin_csrf_token"] != $adminController->getAdminCSRFToken()) {
                        return ["success" => false, "message" => "Invalid request."];
                    }
                    if (!is_numeric($_POST["text_ad_credit_conversation"]) || $_POST["text_ad_credit_conversation"] < 1) {
                        return ["success" => false, "message" => "Conversation rate must be greater than 0."];
                    }
                    $this->model->updateSettings(["settings_value" => $_POST["text_ad_credit_conversation"]], "text_ad_credit_conversation");
                    return ["success" => true, "message" => "Conversation rate has been updated."];
                }
            }
        }
    }
    public function getSettingsValue($settings)
    {
        return $this->model->getSettingsValue($settings);
    }
    public function updateMailerSettings()
    {
        if (isset($_POST["mailer_from"]) && isset($_POST["mailer_from_name"]) && isset($_POST["mails_per_min"]) && isset($_POST["admin_csrf_token"])) {
            if ($this->arrayCheck($_POST)) {
                return ["success" => false, "message" => "Array not allowed."];
            }
            $adminController = new AdminController();
            if (empty($_POST["mailer_from"]) || empty($_POST["mailer_from_name"]) || empty($_POST["mails_per_min"]) || empty($_POST["admin_csrf_token"])) {
                return ["success" => false, "message" => "All fields are required."];
            }
            if ($_POST["admin_csrf_token"] != $adminController->getAdminCSRFToken()) {
                return ["success" => false, "message" => "Invalid request."];
            }
            if (!filter_var($_POST["mailer_from"], FILTER_VALIDATE_EMAIL)) {
                return ["success" => false, "message" => "Invalid email."];
            }
            if (50 < strlen($_POST["mailer_from_name"])) {
                return ["success" => false, "message" => "Mailer from name is too long. You can enter maximum 50 characters."];
            }
            if (intval($_POST["mails_per_min"]) < 250){
                return ["success" => false, "message" => "Mails Per Min is too low, it should be higher than 250."];
            }
            $this->model->updateSettings(["settings_value" => $_POST["mailer_from"]], "mailer_from");
            $this->model->updateSettings(["settings_value" => $_POST["mailer_from_name"]], "mailer_from_name");
            $this->model->updateSettings(["settings_value" => $_POST["mails_per_min"]], "mails_per_min");
            return ["success" => true, "message" => "Mailer settings has been updated."];
        }
    }
}

?>