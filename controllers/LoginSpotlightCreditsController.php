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
class LoginSpotlightCreditsController extends Controller
{
    private $model;
    public function __construct()
    {
        $this->model = new LoginSpotlightCreditsModel();
    }
    public function addCredit()
    {
        if (isset($_POST["username"]) && isset($_POST["admin_csrf_token"])) {
            if (empty($_POST["username"]) || empty($_POST["admin_csrf_token"])) {
                return ["success" => false, "message" => "Pleaes enter username."];
            }
            if ($this->arrayCheck($_POST)) {
                return ["success" => false, "message" => "Array not allowed here."];
            }
            $adminController = new AdminController();
            if ($_POST["admin_csrf_token"] != $adminController->getAdminCSRFToken()) {
                return ["success" => false, "message" => "Invalid request."];
            }
            $membersController = new MembersController();
            if (!$membersController->getUserDetails($_POST["username"])) {
                return ["success" => false, "message" => "Invalid username."];
            }
            $this->model->addCredit(["username" => $_POST["username"], "created_at" => time(), "status" => 1]);
            return ["success" => true, "message" => "Login spotlight added to the member account."];
        }
    }
    public function userActiveCredit($username)
    {
        return $this->model->countUserActiveCredit($username);
    }
    public function deleteUserCredit($username)
    {
        return $this->model->deleteUserCredit($username);
    }
}

?>