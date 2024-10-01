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
class BannedEmailsController extends Controller
{
    private $model;
    public function __construct()
    {
        $this->model = new BannedEmailsModel();
    }
    public function addNewEmail()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["email"]) && isset($_POST["admin_csrf_token"])) {
            if ($this->arrayCheck($_POST)) {
                return ["success" => false, "message" => "You have sent an array. We don't allow array here."];
            }
            $adminController = new AdminController();
            if (empty($_POST["email"]) || empty($_POST["admin_csrf_token"])) {
                return ["success" => false, "message" => "All fields are required."];
            }
            if ($_POST["admin_csrf_token"] != $adminController->getAdminCSRFToken()) {
                return ["success" => false, "message" => "Invalid request."];
            }
            if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
                return ["success" => false, "message" => "Invalid email address."];
            }
            $this->model->addNewEmail(["email" => $_POST["email"]]);
            return ["success" => true, "message" => "Email has been added to banned list."];
        }
    }
    public function totalEmails()
    {
        return $this->model->totalEmails();
    }
    public function emailList()
    {
        $offset = 0;
        if (isset($_GET["page"]) && !empty($_GET["page"]) && is_numeric($_GET["page"])) {
            $total = $this->totalEmails();
            $total_offset = ceil($total / 30);
            if ($_GET["page"] - 1 < 0) {
                $offset = 0;
            } else {
                if ($total_offset < $_GET["page"] - 1) {
                    $offset = 0;
                } else {
                    $offset = ($_GET["page"] - 1) * 30;
                }
            }
        }
        return $this->model->emailList(30, $offset);
    }
    public function emailPagination()
    {
        return $this->pagination(30, $this->totalEmails(), "banned-emails.php");
    }
    public function deleteEmail()
    {
        if (isset($_GET["delete"]) && isset($_GET["token"]) && !empty($_GET["delete"]) && !empty($_GET["token"])) {
            $adminController = new AdminController();
            if (is_numeric($_GET["delete"]) && $_GET["token"] == $adminController->getAdminCSRFToken()) {
                $this->model->deleteEmail($_GET["delete"]);
                return ["success" => true, "message" => "Email has been deleted."];
            }
        }
    }
    public function bannedEmailDetails($email)
    {
        return $this->model->getEmailDetails($email);
    }
}

?>