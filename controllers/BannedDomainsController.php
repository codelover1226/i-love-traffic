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
class BannedDomainsController extends Controller
{
    private $model;
    public function __construct()
    {
        $this->model = new BannedDomainsModel();
    }
    public function addNewDomain()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["domain"]) && isset($_POST["admin_csrf_token"])) {
            if ($this->arrayCheck($_POST)) {
                return ["success" => false, "message" => "You have sent an array. We don't allow array here."];
            }
            $adminController = new AdminController();
            if (empty($_POST["domain"]) || empty($_POST["admin_csrf_token"])) {
                return ["success" => false, "message" => "All fields are required."];
            }
            if ($_POST["admin_csrf_token"] != $adminController->getAdminCSRFToken()) {
                return ["success" => false, "message" => "Invalid request."];
            }
            $this->model->addNewDomain(["domain" => $_POST["domain"]]);
            return ["success" => true, "message" => "Domain has been added to banned list."];
        }
    }
    public function totalDomains()
    {
        return $this->model->totalDomains();
    }
    public function domainList()
    {
        $offset = 0;
        if (isset($_GET["page"]) && !empty($_GET["page"]) && is_numeric($_GET["page"])) {
            $total = $this->totalDomains();
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
        return $this->model->domainList(30, $offset);
    }
    public function domainPagination()
    {
        return $this->pagination(30, $this->totalDomains(), "banned-domains.php");
    }
    public function deleteDomain()
    {
        if (isset($_GET["delete"]) && isset($_GET["token"]) && !empty($_GET["delete"]) && !empty($_GET["token"])) {
            $adminController = new AdminController();
            if (is_numeric($_GET["delete"]) && $_GET["token"] == $adminController->getAdminCSRFToken()) {
                $this->model->deleteDomain($_GET["delete"]);
                return ["success" => true, "message" => "Domain has been deleted."];
            }
        }
    }
    public function bannedDoomainDetails($domain)
    {
        return $this->model->getDomainDetails($domain);
    }
}

?>