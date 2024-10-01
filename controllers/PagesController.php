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
class PagesController extends Controller
{
    private $model;
    public function __construct()
    {
        $this->model = new PagesModel();
    }
    public function getPageDetails($id)
    {
        return $this->model->getPageDetails($id);
    }
    public function updatePage($id)
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $adminController = new AdminController();
            if (isset($_POST["page_content"]) && isset($_POST["admin_csrf_token"])) {
                if ($this->arrayCheck($_POST)) {
                    return ["success" => false, "message" => "You have sent an array. We don't allow array here."];
                }
                if (empty($_POST["page_content"]) || empty($_POST["admin_csrf_token"])) {
                    return ["success" => false, "message" => "All fields are required."];
                }
                if ($_POST["admin_csrf_token"] != $adminController->getAdminCSRFToken()) {
                    return ["success" => false, "message" => "Invalid request."];
                }
                $this->model->updatePage(["page_content" => $_POST["page_content"]], $id);
                return ["success" => true, "message" => "Page has been updated."];
            }
        }
    }
    public function getPageContent($id)
    {
        $pageDetails = $this->getPageDetails($id);
        if (empty($pageDetails)) {
            return "Page not found";
        }
        return htmlspecialchars_decode($pageDetails["page_content"]);
    }
}

?>