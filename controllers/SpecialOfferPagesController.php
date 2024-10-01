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
class SpecialOfferPagesController extends Controller
{
    private $model;
    public function __construct()
    {
        $this->model = new SpecialOfferPagesModel();
    }
    public function pageList()
    {
        $offset = 0;
        if (isset($_GET["page"]) && !empty($_GET["page"]) && is_numeric($_GET["page"])) {
            $total = $this->totalPages();
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
        return $this->model->pageList(30, $offset);
    }
    public function totalPages()
    {
        return $this->model->totalPages();
    }
    public function pageListPagination()
    {
        return $this->pagination(30, $this->totalPages(), "special-offer-pages.php");
    }
    public function addNewPage()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["page_title"]) && isset($_POST["page_content"]) && isset($_POST["admin_csrf_token"])) {
            if ($this->arrayCheck($_POST)) {
                return ["success" => false, "message" => "Array not allowed here"];
            }
            $adminController = new AdminController();
            if (empty($_POST["page_title"]) || empty($_POST["page_content"]) || empty($_POST["admin_csrf_token"])) {
                return ["success" => false, "message" => "All fields are required."];
            }
            if ($_POST["admin_csrf_token"] != $adminController->getAdminCSRFToken()) {
                return ["success" => false, "message" => "Invlaid request."];
            }
            if (100 < strlen($_POST["page_title"])) {
                return ["success" => false, "message" => "Page title is too long. You can enter maximum 100 characters."];
            }
            $this->model->addNewPage(["page_title" => $_POST["page_title"], "page_content" => $_POST["page_content"], "show_on_login" => 2]);
            return ["success" => true, "message" => "Special offer page has been created."];
        }
    }
    public function updatePage($id)
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["page_title"]) && isset($_POST["page_content"]) && isset($_POST["admin_csrf_token"])) {
            if ($this->arrayCheck($_POST)) {
                return ["success" => false, "message" => "Array not allowed here"];
            }
            $adminController = new AdminController();
            if (empty($_POST["page_title"]) || empty($_POST["page_content"]) || empty($_POST["admin_csrf_token"])) {
                return ["success" => false, "message" => "All fields are required."];
            }
            if ($_POST["admin_csrf_token"] != $adminController->getAdminCSRFToken()) {
                return ["success" => false, "message" => "Invlaid request."];
            }
            if (100 < strlen($_POST["page_title"])) {
                return ["success" => false, "message" => "Page title is too long. You can enter maximum 100 characters."];
            }
            $this->model->updatePage(["page_title" => $_POST["page_title"], "page_content" => $_POST["page_content"]], $id);
            $this->model->removeOtherPageFromLogin($id);
            return ["success" => true, "message" => "Special offer page has been updated."];
        }
    }
    public function enableShowOnLogin()
    {
        if (isset($_GET["show"]) && isset($_GET["token"]) && !empty($_GET["show"]) && !empty($_GET["token"]) && is_numeric($_GET["show"])) {
            $adminController = new AdminController();
            if ($adminController->getAdminCSRFToken() != $_GET["token"]) {
                return ["success" => false, "message" => "Invalid request"];
            }
            $this->model->updatePage(["show_on_login" => 1], $_GET["show"]);
            $this->model->removeOtherPageFromLogin($_GET["show"]);
            return ["success" => true, "message" => "Now the page will be shown to user after successful login"];
        }
    }
    public function disableShowOnLogin()
    {
        if (isset($_GET["remove-login"]) && isset($_GET["token"]) && !empty($_GET["remove-login"]) && !empty($_GET["token"]) && is_numeric($_GET["remove-login"])) {
            $adminController = new AdminController();
            if ($adminController->getAdminCSRFToken() != $_GET["token"]) {
                return ["success" => false, "message" => "Invalid request"];
            }
            $this->model->updatePage(["show_on_login" => 2], $_GET["remove-login"]);
            return ["success" => true, "message" => "Now the page will not shown to user after successful login"];
        }
    }
    public function deletePage()
    {
        if (isset($_GET["delete"]) && isset($_GET["token"]) && !empty($_GET["delete"]) && !empty($_GET["token"]) && is_numeric($_GET["delete"])) {
            $adminController = new AdminController();
            if ($adminController->getAdminCSRFToken() != $_GET["token"]) {
                return ["success" => false, "message" => "Invalid request"];
            }
            $this->model->deletePage($_GET["delete"]);
            return ["success" => true, "message" => "The page has been deleted."];
        }
    }
    public function getLoginOfferPage()
    {
        return $this->model->getLoginOfferPage();
    }
    public function getPageDetails($id)
    {
        return $this->model->getPageDetails($id);
    }
}

?>