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
class EmailCreditsPackagesController extends Controller
{
    private $model;
    public function __construct()
    {
        $this->model = new EmailCreditsPackagesModel();
    }
    public function addPackage()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["credits"]) && isset($_POST["price"]) && isset($_POST["hidden"]) && isset($_POST["admin_csrf_token"])) {
            if ($this->arrayCheck($_POST)) {
                return ["success" => false, "message" => "You have sent an array. We don't allow array here."];
            }
            $adminController = new AdminController();
            if (empty($_POST["credits"]) || empty($_POST["price"]) || empty($_POST["hidden"]) || empty($_POST["admin_csrf_token"])) {
                return ["success" => false, "message" => "All fields are required."];
            }
            if ($_POST["admin_csrf_token"] != $adminController->getAdminCSRFToken()) {
                return ["success" => false, "message" => "Invalid request."];
            }
            if (!is_numeric($_POST["credits"]) || $_POST["credits"] < 0) {
                return ["success" => false, "message" => "Invalid credits amount."];
            }
            if (!is_numeric($_POST["price"]) || $_POST["price"] < 0) {
                return ["success" => false, "message" => "Invalid price amount."];
            }
            if (!is_numeric($_POST["hidden"]) || $_POST["hidden"] < 0 || 2 < $_POST["hidden"]) {
                return ["success" => false, "message" => "Invalid hidden status."];
            }
            $this->model->addNewPackage(["credits" => $_POST["credits"], "price" => $_POST["price"], "hidden" => $_POST["hidden"]]);
            return ["success" => true, "message" => "Package has been added."];
        }
    }
    public function updatePackage($id)
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["credits"]) && isset($_POST["price"]) && isset($_POST["hidden"]) && isset($_POST["admin_csrf_token"])) {
            if ($this->arrayCheck($_POST)) {
                return ["success" => false, "message" => "You have sent an array. We don't allow array here."];
            }
            $adminController = new AdminController();
            if (empty($_POST["credits"]) || empty($_POST["price"]) || empty($_POST["hidden"]) || empty($_POST["admin_csrf_token"])) {
                return ["success" => false, "message" => "All fields are required."];
            }
            if ($_POST["admin_csrf_token"] != $adminController->getAdminCSRFToken()) {
                return ["success" => false, "message" => "Invalid request."];
            }
            if (!is_numeric($_POST["credits"]) || $_POST["credits"] < 0) {
                return ["success" => false, "message" => "Invalid credits amount."];
            }
            if (!is_numeric($_POST["price"]) || $_POST["price"] < 0) {
                return ["success" => false, "message" => "Invalid price amount."];
            }
            if (!is_numeric($_POST["hidden"]) || $_POST["hidden"] < 0 || 2 < $_POST["hidden"]) {
                return ["success" => false, "message" => "Invalid hidden status."];
            }
            $this->model->updatePackage(["credits" => $_POST["credits"], "price" => $_POST["price"], "hidden" => $_POST["hidden"]], $id);
            return ["success" => true, "message" => "Package has been updated."];
        }
    }
    public function deletePackage()
    {
        if (isset($_GET["delete"]) && isset($_GET["token"])) {
            $adminController = new AdminController();
            if (!empty($_GET["delete"]) && !empty($_GET["token"]) && is_numeric($_GET["delete"]) && $adminController->getAdminCSRFToken() == $_GET["token"]) {
                $productDetails = $this->getPackageDetails($_GET["delete"]);
                if (empty($productDetails)) {
                    return ["success" => false, "message" => "Couldn't find the package."];
                }
                $this->model->deletePackage($_GET["delete"]);
                return ["success" => true, "message" => "The package has been deleted."];
            }
        }
    }
    public function getPackageDetails($id)
    {
        return $this->model->getPackageDetails($id);
    }
    public function packageList()
    {
        $offset = 0;
        if (isset($_GET["page"]) && !empty($_GET["page"]) && is_numeric($_GET["page"])) {
            $total = $this->totalPackages();
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
        return $this->model->packagesList(30, $offset);
    }
    public function getEmailCreditsPackages()
    {
        $packages = $this->model->allPackages();
        foreach ($packages as $package) {
            echo "<option value=\"" . $package["id"] . "\">" . $package["credits"] . " Credits - \$" . $package["price"] . "</option>";
        }
    }
    public function totalPackages()
    {
        return $this->model->totalPackages();
    }
    public function packagePagination()
    {
        $this->pagination(30, $this->totalPackages(), "email-credits.php");
    }
}

?>