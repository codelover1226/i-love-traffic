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
class SplashPagesController extends Controller
{
    private $model;
    public function __construct()
    {
        $this->model = new SplashPagesModel();
    }
    public function splashPagesList()
    {
        $offset = 0;
        if (isset($_GET["page"]) && !empty($_GET["page"]) && is_numeric($_GET["page"])) {
            $total = $this->totalSplashPages();
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
        return $this->model->splashPageList(30, $offset);
    }
    public function totalSplashPages()
    {
        return $this->model->totalSplashPages();
    }
    public function splashPagesPagination()
    {
        $this->pagination(30, $this->totalSplashPages(), "splash-pages.php");
    }
    public function addNewSplashPage()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["splash_page_name"]) && isset($_POST["splash_page_content"]) && isset($_POST["admin_csrf_token"])) {
            if ($this->arrayCheck($_POST)) {
                return ["success" => false, "message" => "You have sent an array. We don't allow array here."];
            }
            $adminController = new AdminController();
            if (empty($_POST["splash_page_name"]) || empty($_POST["splash_page_content"]) || empty($_POST["admin_csrf_token"])) {
                return ["success" => false, "message" => "All fields are required."];
            }
            if ($adminController->getAdminCSRFToken() != $_POST["admin_csrf_token"]) {
                return ["success" => false, "message" => "Invalid request."];
            }
            if (255 < strlen($_POST["splash_page_name"])) {
                return ["success" => false, "message" => "Splash page name is too long."];
            }
            $this->model->addSplashPage(["splash_page_name" => $_POST["splash_page_name"], "splash_page_content" => $_POST["splash_page_content"]]);
            return ["success" => true, "message" => "Splash page has been added."];
        }
    }
    public function updateSplashPage($id)
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["splash_page_name"]) && isset($_POST["splash_page_content"]) && isset($_POST["admin_csrf_token"])) {
            if ($this->arrayCheck($_POST)) {
                return ["success" => false, "message" => "You have sent an array. We don't allow array here."];
            }
            $adminController = new AdminController();
            if (empty($_POST["splash_page_name"]) || empty($_POST["splash_page_content"]) || empty($_POST["admin_csrf_token"])) {
                return ["success" => false, "message" => "All fields are required."];
            }
            if ($adminController->getAdminCSRFToken() != $_POST["admin_csrf_token"]) {
                return ["success" => false, "message" => "Invalid request."];
            }
            if (255 < strlen($_POST["splash_page_name"])) {
                return ["success" => false, "message" => "Splash page name is too long."];
            }
            $this->model->updateSplashPage(["splash_page_name" => $_POST["splash_page_name"], "splash_page_content" => $_POST["splash_page_content"]], $id);
            return ["success" => true, "message" => "Splash page has been updated."];
        }
    }
    public function getSplashPageDetails($id)
    {
        return $this->model->getSplashPageDetails($id);
    }
    public function deleteSplashPage()
    {
        if (isset($_GET["delete"]) && isset($_GET["token"])) {
            $adminController = new AdminController();
            if (!empty($_GET["delete"]) && !empty($_GET["token"]) && is_numeric($_GET["delete"]) && $adminController->getAdminCSRFToken() == $_GET["token"]) {
                $productDetails = $this->getSplashPageDetails($_GET["delete"]);
                if (empty($productDetails)) {
                    return ["success" => false, "message" => "Couldn't find the page."];
                }
                $this->model->deleteSplashPage($_GET["delete"]);
                return ["success" => true, "message" => "The page has been deleted."];
            }
        }
    }
    public function allSplashPages()
    {
        return $this->model->allSplashPages();
    }
}

?>