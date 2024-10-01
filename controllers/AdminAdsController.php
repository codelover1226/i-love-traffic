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
class AdminAdsController extends Controller
{
    private $model;
    public function __construct()
    {
        $this->model = new AdminAdsModel();
    }
    public function addNewAd()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["website_link"]) && isset($_POST["banner_link"]) && isset($_POST["status"]) && isset($_POST["ad_text"]) && isset($_POST["admin_csrf_token"])) {
            if ($this->arrayCheck($_POST)) {
                return ["success" => false, "message" => "Array not allowed."];
            }
            $adminController = new AdminController();
            if (empty($_POST["website_link"]) || empty($_POST["banner_link"]) || empty($_POST["status"]) || empty($_POST["admin_csrf_token"])) {
                return ["success" => false, "message" => "All fields are required."];
            }
            if ($adminController->getAdminCSRFToken() != $_POST["admin_csrf_token"]) {
                return ["success" => false, "message" => "Invalid request."];
            }
            if (!filter_var($_POST["website_link"], FILTER_SANITIZE_URL)) {
                return ["success" => false, "message" => "Please enter a valid website link."];
            }
            if (!filter_var($_POST["banner_link"], FILTER_SANITIZE_URL)) {
                return ["success" => false, "message" => "Please enter a valid banner link."];
            }
            if (!is_numeric($_POST["status"])) {
                return ["success" => false, "message" => "Invalid status."];
            }
            if ($_POST["status"] != 1 && $_POST["status"] != 2) {
                return ["success" => false, "message" => "Invalid status."];
            }
            if (!empty($_POST["ad_text"]) && 60 < strlen($_POST["ad_text"])) {
                return ["success" => false, "message" => "Ad text is too long. You can add maximum 60 characters."];
            }
            $adText = empty($_POST["ad_text"]) ? "" : $_POST["ad_text"];
            $this->model->addAd(["website_link" => $_POST["website_link"], "banner_link" => $_POST["banner_link"], "status" => $_POST["status"], "total_clicks" => 0, "ad_text" => $adText]);
            return ["success" => true, "message" => "Banner ad has been added."];
        }
    }
    public function updateAd($id)
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["website_link"]) && isset($_POST["banner_link"]) && isset($_POST["status"]) && isset($_POST["ad_text"]) && isset($_POST["admin_csrf_token"])) {
            if ($this->arrayCheck($_POST)) {
                return ["success" => false, "message" => "Array not allowed."];
            }
            $adminController = new AdminController();
            if (empty($_POST["website_link"]) || empty($_POST["banner_link"]) || empty($_POST["status"]) || empty($_POST["admin_csrf_token"])) {
                return ["success" => false, "message" => "All fields are required."];
            }
            if ($adminController->getAdminCSRFToken() != $_POST["admin_csrf_token"]) {
                return ["success" => false, "message" => "Invalid request."];
            }
            if (!filter_var($_POST["website_link"], FILTER_SANITIZE_URL)) {
                return ["success" => false, "message" => "Please enter a valid website link."];
            }
            if (!filter_var($_POST["banner_link"], FILTER_SANITIZE_URL)) {
                return ["success" => false, "message" => "Please enter a valid banner link."];
            }
            if (!is_numeric($_POST["status"])) {
                return ["success" => false, "message" => "Invalid status."];
            }
            if ($_POST["status"] != 1 && $_POST["status"] != 2) {
                return ["success" => false, "message" => "Invalid status."];
            }
            if (!empty($_POST["ad_text"]) && 60 < strlen($_POST["ad_text"])) {
                return ["success" => false, "message" => "Ad text is too long. You can add maximum 60 characters."];
            }
            $adText = empty($_POST["ad_text"]) ? "" : $_POST["ad_text"];
            $this->model->updateAd(["website_link" => $_POST["website_link"], "banner_link" => $_POST["banner_link"], "status" => $_POST["status"], "ad_text" => $adText], $id);
            return ["success" => true, "message" => "Banner ad has been updated."];
        }
    }
    public function totalAds()
    {
        return $this->model->totalAds();
    }
    public function getAdDetails($id)
    {
        return $this->model->getAdDetails($id);
    }
    public function deleteAd()
    {
        $adminController = new AdminController();
        if (isset($_GET["delete"]) && isset($_GET["token"]) && !empty($_GET["delete"]) && !empty($_GET["delete"]) && is_numeric($_GET["delete"]) && $adminController->getAdminCSRFToken() == $_GET["token"]) {
            $this->model->deleteAd($_GET["delete"]);
            return ["success" => true, "message" => "Ad has been deleted."];
        }
    }
    public function adListPagination()
    {
        return $this->pagination(30, $this->totalAds(), "admin-ads.php");
    }
    public function adList()
    {
        $offset = 0;
        if (isset($_GET["page"]) && !empty($_GET["page"]) && is_numeric($_GET["page"])) {
            $total = $this->totalAds();
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
        return $this->model->adsList(30, $offset);
    }
    public function showAd()
    {
        $adData = $this->model->getAd();
        if (!empty($adData)) {
            if (!empty($adData["ad_text"])) {
                echo "<a href=\"admin-ad-click.php?id=" . $adData["id"] . "\" target=\"_blnak\">";
                echo "<div align=\"center\">" . $adData["ad_text"] . "</div>";
                echo "</a>";
            }
            echo "<a href=\"admin-ad-click.php?id=" . $adData["id"] . "\" target=\"_blnak\">";
            echo "<img src=\"" . $adData["banner_link"] . "\" class=\"img-fluid\" alt=\"Admin Recommended Site\">";
            echo "</a>";
        }
    }
    public function adClick()
    {
        if (isset($_GET["id"]) && !empty($_GET["id"]) && is_numeric($_GET["id"])) {
            $adData = $this->getAdDetails($_GET["id"]);
            if (empty($adData)) {
                echo "Invalid ad link !";
                exit;
            }
            $this->model->increaseAdClicks($_GET["id"]);
            header("Location: " . $adData["website_link"]);
            exit;
        }
        echo "Invalid ad link !";
        exit;
    }
}

?>