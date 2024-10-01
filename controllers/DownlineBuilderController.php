<?php
echo "\n";
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
class DownlineBuilderController extends Controller
{
    private $model;
    public function __construct()
    {
        $this->model = new DownlineBuilderModel();
    }
    public function addUserProgram($username)
    {
        if (isset($_POST["affiliate_banner"]) && isset($_POST["affiliate_link"]) && isset($_POST["csrf_token"])) {
            if (empty($_POST["affiliate_banner"]) || empty($_POST["affiliate_link"]) || empty($_POST["csrf_token"])) {
                return ["success" => false, "message" => "All fields are required."];
            }
            if ($this->arrayCheck($_POST)) {
                return ["success" => false, "message" => "Array not allowed."];
            }
            $memberController = new MembersController();
            if ($_POST["csrf_token"] != $memberController->getUserCSRFToken()) {
                return ["success" => false, "message" => "Invalid request. Please refresh the page."];
            }
            if (!filter_var($_POST["affiliate_banner"], FILTER_VALIDATE_URL) || !$this->is_url_image($_POST["affiliate_banner"])) {
                return ["success" => false, "message" => "Invalid banner link."];
            }
            if (!filter_var($_POST["affiliate_link"], FILTER_VALIDATE_URL)) {
                return ["success" => false, "message" => "Invalid affiliate link."];
            }
            if ($this->getSettings()["settings_value"] <= $this->totalUserPrograms($username)) {
                return ["success" => false, "message" => "You have reached max number of programs. You can't add more."];
            }
            $this->model->addUserDownlineProgram(["username" => $username, "affiliate_banner" => $_POST["affiliate_banner"], "affiliate_link" => $_POST["affiliate_link"]]);
            return ["success" => true, "message" => "Affiliate program has been added into downline builder."];
        }
    }
    public function deleteUserProgram($username)
    {
        if (isset($_GET["delete"]) && isset($_GET["token"]) && !empty($_GET["delete"]) && is_numeric($_GET["delete"]) && !empty($_GET["token"])) {
            $programInfo = $this->getUserProgramInfo($_GET["delete"]);
            if (empty($programInfo)) {
                return ["success" => false, "message" => "Invalid affiliate program."];
            }
            if ($programInfo["username"] != $username) {
                return ["success" => false, "message" => "Invalid affiliate program."];
            }
            $memberController = new MembersController();
            if ($_GET["token"] === $memberController->getUserCSRFToken()) {
                $this->model->deleteUserProgram($_GET["delete"]);
                return ["success" => true, "message" => "Affiliate program has been deleted from your downline builder."];
            }
        }
    }
    public function getUserDownlinePrograms($username)
    {
        return $this->model->userProgramList($username);
    }
    public function getAdminDownlinePrograms()
    {
        return $this->model->adminPrograms();
    }
    public function getUserProgramInfo($id)
    {
        return $this->model->getUserProgramDetails($id);
    }
    public function totalUserPrograms($username)
    {
        return $this->model->totalUserPrograms($username);
    }
    public function getSettings()
    {
        return $this->model->getSettings();
    }
    public function totalUserPrograms_Admin()
    {
        return $this->model->totalUserPrograms_Admin();
    }
    public function deleteUserProgram_Admin()
    {
        if (isset($_GET["delete"]) && isset($_GET["token"]) && !empty($_GET["delete"]) && !empty($_GET["token"]) && is_numeric($_GET["delete"])) {
            $adminController = new AdminController();
            if ($adminController->getAdminCSRFToken() == $_GET["token"]) {
                $programInfo = $this->getUserProgramInfo($_GET["delete"]);
                if (empty($programInfo)) {
                    return ["success" => false, "message" => "Invalid downline program."];
                }
                $this->model->deleteUserProgram($_GET["delete"]);
                return ["success" => true, "message" => "Affiliate program has been deleted from downline builder."];
            }
        }
    }
    public function deleteAdminProgram()
    {
        if (isset($_GET["delete"]) && isset($_GET["token"]) && !empty($_GET["delete"]) && !empty($_GET["token"]) && is_numeric($_GET["delete"])) {
            $adminController = new AdminController();
            if ($adminController->getAdminCSRFToken() == $_GET["token"]) {
                $programInfo = $this->getAdminProgramInfo($_GET["delete"]);
                if (empty($programInfo)) {
                    return ["success" => false, "message" => "Invalid downline program."];
                }
                $this->model->deleteAdminProgram($_GET["delete"]);
                return ["success" => true, "message" => "Affiliate program has been deleted from downline builder."];
            }
        }
    }
    public function getAdminProgramInfo($id)
    {
        return $this->model->getAdminProgramDetails($id);
    }
    public function addAdminProgram()
    {
        if (isset($_POST["affiliate_banner"]) && isset($_POST["affiliate_link"]) && isset($_POST["admin_csrf_token"])) {
            if (empty($_POST["affiliate_banner"]) || empty($_POST["affiliate_link"]) || empty($_POST["admin_csrf_token"])) {
                return ["success" => false, "message" => "All fields are required."];
            }
            if ($this->arrayCheck($_POST)) {
                return ["success" => false, "message" => "Array not allowed."];
            }
            $adminController = new AdminController();
            if ($_POST["admin_csrf_token"] != $adminController->getAdminCSRFToken()) {
                return ["success" => false, "message" => "Invalid request. Please refresh the page."];
            }
            if (!filter_var($_POST["affiliate_banner"], FILTER_VALIDATE_URL) || !$this->is_url_image($_POST["affiliate_banner"])) {
                return ["success" => false, "message" => "Invalid banner link."];
            }
            if (!filter_var($_POST["affiliate_link"], FILTER_VALIDATE_URL)) {
                return ["success" => false, "message" => "Invalid affiliate link."];
            }
            $this->model->addAdminDownlineProgram(["affiliate_banner" => $_POST["affiliate_banner"], "affiliate_link" => $_POST["affiliate_link"]]);
            return ["success" => true, "message" => "Affiliate program has been added into downline builder."];
        }
    }
    public function adminProgramList()
    {
        return $this->model->adminPrograms();
    }
    public function getUserSpecificPrograms_Admin()
    {
        if (isset($_GET["username"]) && !empty($_GET["username"])) {
            return $this->model->userProgramList($_GET["username"]);
        }
    }
    public function checkSettingsExistence()
    {
        $settings = $this->getSettings();
        if (empty($settings)) {
            $this->model->insertSettings(["settings_name" => "downline_builder_limits", "settings_value" => 10]);
        }
    }
    public function updateSettings()
    {
        if (isset($_POST["downline_builder_limits"]) && isset($_POST["admin_csrf_token"])) {
            if (empty($_POST["downline_builder_limits"]) || empty($_POST["admin_csrf_token"])) {
                return ["success" => false, "message" => "All fields are required."];
            }
            if ($this->arrayCheck($_POST)) {
                return ["success" => false, "message" => "Array not allowed here."];
            }
            if (!is_numeric($_POST["downline_builder_limits"]) || $_POST["downline_builder_limits"] < 1) {
                return ["success" => false, "message" => "Invalid downline builder program limit."];
            }
            $this->model->updateSettings(["settings_value" => $_POST["downline_builder_limits"]]);
            return ["success" => true, "message" => "Downline builder settings has been updated."];
        }
    }
}

?>