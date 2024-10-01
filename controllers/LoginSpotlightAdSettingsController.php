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
class LoginSpotlightAdSettingsController extends Controller
{
    private $model;
    public function __construct()
    {
        $this->model = new LoginSpotlightAdSettingsModel();
    }
    public function getSettings()
    {
        return $this->model->getSettings();
    }
    public function updateSettings()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $adminController = new AdminController();
            if (isset($_POST["ad_price"]) && isset($_POST["user_credits"]) && isset($_POST["admin_csrf_token"])) {
                if ($this->arrayCheck($_POST)) {
                    return ["success" => false, "message" => "You have sent an array. We don't allow array here."];
                }
                if (empty($_POST["ad_price"]) || empty($_POST["user_credits"]) || empty($_POST["admin_csrf_token"])) {
                    return ["success" => false, "message" => "All fields are required."];
                }
                if ($_POST["admin_csrf_token"] != $adminController->getAdminCSRFToken()) {
                    return ["success" => false, "message" => "Invalid request."];
                }
                if (!is_numeric($_POST["ad_price"])) {
                    return ["success" => false, "message" => "Invalid price."];
                }
                if ($_POST["ad_price"] < 0) {
                    return ["success" => false, "message" => "Invalid price."];
                }
                if (!is_numeric($_POST["user_credits"])) {
                    return ["success" => false, "message" => "Invalid user credits."];
                }
                if ($_POST["user_credits"] < 0) {
                    return ["success" => false, "message" => "Invalid user credits."];
                }
                $this->model->updateSettings(["ad_price" => $_POST["ad_price"], "user_credits" => $_POST["user_credits"]]);
                return ["success" => true, "message" => "Settings has been updated."];
            }
        }
    }
}

?>