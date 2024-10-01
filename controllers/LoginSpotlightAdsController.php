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
class LoginSpotlightAdsController extends Controller
{
    private $model;
    public function __construct()
    {
        $this->model = new LoginSpotlightAdsModel();
    }
    public function loginAdsList()
    {
        $offset = 0;
        if (isset($_GET["page"]) && !empty($_GET["page"]) && is_numeric($_GET["page"])) {
            $total = $this->totalLoginAds();
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
        return $this->model->loginAdsList(30, $offset);
    }
    public function userLoginAdList($username)
    {
        $offset = 0;
        if (isset($_GET["page"]) && !empty($_GET["page"]) && is_numeric($_GET["page"])) {
            $total = $this->totalUserLoginAds($username);
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
        return $this->model->userLoginAdsList(30, $offset, $username);
    }
    public function totalUserLoginAds($username)
    {
        return $this->model->totalUserAds($username);
    }
    public function userLoginAdsPagination($username)
    {
        return $this->pagination(30, $this->totalUserLoginAds($username), "login-ads.php");
    }
    public function loginAdsPagination()
    {
        return $this->pagination(30, $this->totalLoginAds(), "login-ads.php");
    }
    public function totalLoginAds()
    {
        return $this->model->totalAds();
    }
    public function adStatus()
    {
        return ["Active", "Pause", "Banned"];
    }
    public function activateAd()
    {
        if (isset($_GET["activate"]) && isset($_GET["token"]) && !empty($_GET["activate"]) && !empty($_GET["token"])) {
            $adminController = new AdminController();
            if ($adminController->getAdminCSRFToken() != $_GET["token"]) {
                return ["success" => false, "message" => "Invalid request."];
            }
            if (!is_numeric($_GET["activate"]) || $_GET["activate"] < 1) {
                return ["success" => false, "message" => "Invalid login ad."];
            }
            $this->model->updateLoginAd(["status" => 1], $_GET["activate"]);
            return ["success" => true, "message" => "Login ad has been activated."];
        }
    }
    public function pauseAd()
    {
        if (isset($_GET["pause"]) && isset($_GET["token"]) && !empty($_GET["pause"]) && !empty($_GET["token"])) {
            $adminController = new AdminController();
            if ($adminController->getAdminCSRFToken() != $_GET["token"]) {
                return ["success" => false, "message" => "Invalid request."];
            }
            if (!is_numeric($_GET["pause"]) || $_GET["pause"] < 1) {
                return ["success" => false, "message" => "Invalid login ad."];
            }
            $this->model->updateLoginAd(["status" => 2], $_GET["pause"]);
            return ["success" => true, "message" => "Login ad has been paused."];
        }
    }
    public function banAd()
    {
        if (isset($_GET["ban"]) && isset($_GET["token"]) && !empty($_GET["ban"]) && !empty($_GET["token"])) {
            $adminController = new AdminController();
            if ($adminController->getAdminCSRFToken() != $_GET["token"]) {
                return ["success" => false, "message" => "Invalid request."];
            }
            if (!is_numeric($_GET["ban"]) || $_GET["ban"] < 1) {
                return ["success" => false, "message" => "Invalid login ad."];
            }
            $this->model->updateLoginAd(["status" => 3], $_GET["ban"]);
            return ["success" => true, "message" => "Login ad has been banned."];
        }
    }
    public function getLoginAdDetails($id)
    {
        return $this->model->getAdDetails($id);
    }
    public function loginAdViews($id)
    {
        $adData = $this->getLoginAdDetails($id);
        if (empty($adData)) {
            echo "Invalid link";
        } else {
            $this->model->increaseLoginAdViews($id);
        }
    }
    public function availableDates()
    {
        $bookedDates = $this->oneMonthAds();
        $avialableDates = [];
        if (empty($bookedDates)) {
            $todayDate = date("d");
            $monthEndDate = date("t");
            $counter = 0;
            for ($i = $todayDate; $i <= $monthEndDate; $i++) {
                $avialableDates[$counter] = $i . date("-M-Y");
                $counter++;
            }
            return $avialableDates;
        }
        $todayDate = date("d");
        $monthEndDate = date("t");
        $counter = 0;
        $i = $todayDate;
        while ($i <= $monthEndDate) {
            $dateFlag = false;
            $timeStamp = strtotime($i . date("M-Y") . " 00:00:00");
            foreach ($bookedDates as $date) {
                if (in_array($timeStamp, $date)) {
                    $dateFlag = true;
                    if (!$dateFlag) {
                        $avialableDates[$counter] = $i . date("-M-Y");
                        $counter++;
                    }
                    $i++;
                } else {
                    $dateFlag = false;
                }
            }
        }
        return $avialableDates;
    }
    public function oneMonthAds()
    {
        return $this->model->oneMonthAds();
    }
    public function getTodayAd()
    {
        return $this->model->getTodayAd();
    }
    public function addNewAd($data)
    {
        return $this->model->addNewAd($data);
    }
    public function getLoginAdDetailsByCreditKey($credityKey)
    {
        return $this->model->getLoginAdDetailsByCreditKey($credityKey);
    }
    public function increaseLoginAdView($id)
    {
        return $this->model->increaseLoginAdViews($id);
    }
    public function addUserLoginSpotlightAd($username)
    {
        if (isset($_POST["website_link"]) && isset($_POST["date"]) && isset($_POST["csrf_token"])) {
            if (empty($_POST["website_link"]) || empty($_POST["date"]) || empty($_POST["csrf_token"])) {
                return ["success" => false, "message" => "All fields are required."];
            }
            if ($this->arrayCheck($_POST)) {
                return ["success" => false, "message" => "Array not allowed here."];
            }
            $membersController = new MembersController();
            if ($_POST["csrf_token"] != $membersController->getUserCSRFToken()) {
                return ["success" => false, "message" => "Invalid request."];
            }
            $loginSpotlightCreditsController = new LoginSpotlightCreditsController();
            if ($loginSpotlightCreditsController->userActiveCredit($username) < 1) {
                return ["success" => false, "message" => "Sorry ! You don't have login ad credit."];
            }
            if (!filter_var($_POST["website_link"], FILTER_VALIDATE_URL)) {
                return ["success" => false, "message" => "Invalid website link."];
            }
            $avialableDates = $this->availableDates();
            if (!in_array($_POST["date"], $avialableDates)) {
                return ["successs" => false, "message" => "Sorry ! the date is not available."];
            }
            $loginAdsSettingsController = new LoginSpotlightAdSettingsController();
            $loginAdsSettings = $loginAdsSettingsController->getSettings();
            $this->addNewAd(["username" => $username, "website_link" => $_POST["website_link"], "status" => 1, "ad_timestamp" => strtotime($_POST["date"] . " 00:00:00"), "credit_key" => md5(uniqid("NTKS_")), "user_credits" => $loginAdsSettings["user_credits"], "total_views" => 0]);
            $loginSpotlightCreditsController->deleteUserCredit($username);
            return ["success" => true, "message" => "Your website has been added to login spotlight ad."];
        }
    }
}

?>