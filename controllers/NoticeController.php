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
class NoticeController extends Controller
{
    private $model;
    public function __construct()
    {
        $this->model = new NoticeModel();
    }
    public function getNotice()
    {
        return $this->model->getNotice();
    }
    public function updateNotice()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $adminController = new AdminController();
            if (isset($_POST["notice"]) && isset($_POST["notice_style"]) && isset($_POST["notice_status"]) && isset($_POST["admin_csrf_token"])) {
                if ($this->arrayCheck($_POST)) {
                    return ["success" => false, "message" => "You have sent an array. We don't allow array here."];
                }
                if (empty($_POST["notice"]) || empty($_POST["notice_style"]) || empty($_POST["notice_status"]) || empty($_POST["admin_csrf_token"])) {
                    return ["success" => false, "message" => "All fields are required."];
                }
                if ($_POST["admin_csrf_token"] != $adminController->getAdminCSRFToken()) {
                    return ["success" => false, "message" => "Invalid request."];
                }
                if ($_POST["notice_style"] < 1 || 3 < $_POST["notice_style"]) {
                    return ["success" => false, "message" => "Invalid notice style."];
                }
                if ($_POST["notice_status"] < 1 || 2 < $_POST["notice_status"]) {
                    return ["success" => false, "message" => "Invalid notice status."];
                }
                if (4000 < strlen($_POST["notice"])) {
                    return ["success" => false, "message" => "Notice is too long. You can have max 4000 characters."];
                }
                $this->model->updateNotice(["notice" => $_POST["notice"], "notice_style" => $_POST["notice_style"], "notice_status" => $_POST["notice_status"]]);
                return ["success" => true, "message" => "Notice has been updated."];
            }
        }
    }
}

?>