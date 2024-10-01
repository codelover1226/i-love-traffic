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
class BannerPublisherController extends Controller
{
    private $model;
    public function __construct()
    {
        $this->model = new BannerPublisherModel();
    }
    public function getSettings()
    {
        return $this->model->getSettings();
    }
    public function giveCredits($username)
    {
        $membersController = new MembersController();
        $userInfo = $membersController->getUserDetails($username);
        if (!empty($userInfo)) {
            $this->model->addBannerCredit($username);
        }
    }
    public function updateSettings()
    {
        if (isset($_POST["banner_publisher"]) && isset($_POST["admin_csrf_token"])) {
            if ($this->arrayCheck($_POST)) {
                return ["success" => false, "message" => "Array not allowed."];
            }
            $adminController = new AdminController();
            if (empty($_POST["banner_publisher"]) || empty($_POST["admin_csrf_token"])) {
                return ["success" => false, "message" => "All fields are reauired."];
            }
            if ($_POST["admin_csrf_token"] != $adminController->getAdminCSRFToken()) {
                return ["success" => false, "message" => "Invlaid request."];
            }
            if (!filter_var($_POST["banner_publisher"], FILTER_VALIDATE_INT)) {
                return ["success" => false, "message" => "Invlaid credit amount."];
            }
            $this->model->updateBannerPublisherSettings(["settings_value" => $_POST["banner_publisher"]], "banner_publisher");
            return ["success" => true, "message" => "Mailer settings has been updated."];
        }
    }
}

?>