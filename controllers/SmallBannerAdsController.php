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
class SmallBannerAdsController extends Controller
{
    private $model;
    public function __construct()
    {
        $this->model = new SmallBannerAdsModel();
    }
    public function BannerAdsList()
    {
        $offset = 0;
        if (isset($_GET["page"]) && !empty($_GET["page"]) && is_numeric($_GET["page"])) {
            $total = $this->totalBannerAds();
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
        return $this->model->bannerAdsList(30, $offset);
    }
    public function bannerAdsPgination()
    {
        return $this->pagination(30, $this->totalBannerAds(), "small-banner-ads.php");
    }
    public function totalBannerAds()
    {
        return $this->model->totalBannerAds();
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
                return ["success" => false, "message" => "Invalid banner ad."];
            }
            $this->model->updateBannerAd(["status" => 1], $_GET["activate"]);
            return ["success" => true, "message" => "Banner ad has been activated."];
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
                return ["success" => false, "message" => "Invalid banner ad."];
            }
            $this->model->updateBannerAd(["status" => 2], $_GET["pause"]);
            return ["success" => true, "message" => "Banner ad has been paused."];
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
                return ["success" => false, "message" => "Invalid banner ad."];
            }
            $this->model->updateBannerAd(["status" => 3], $_GET["ban"]);
            return ["success" => true, "message" => "Banner ad has been banned."];
        }
    }
    public function getBannerAdDetails($id)
    {
        return $this->model->getBannerAdDetails($id);
    }
    public function getBannerAd()
    {
        $bannerData = $this->model->getBannerAd();
        if (!empty($bannerData)) {
            echo "<div class='bannerFrameWeb-125-125'>
                    <a href=\"small-banner-click.php?id=" . $bannerData["id"] . "\" target=\"_blank\">
                        <img src=\"" . $bannerData["image_link"] . "\" height=\"125\" width=\"125\" />
                    </a>
                    <a class=\"adByBottom\" href=\"https://i-lovetraffic.online/\" target=\"_blank\">
                        I-Love Traffic
                    </a>
                </div>";
        } else {
            echo "<a href=\"index.php\"><img src=\"images/125x125.jpg\" height=\"125\" width=\"125\" /></a>";
        }
    }
    public function getRandomBannerAdDetails()
    {
        return $this->model->getBannerAd();
    }
    public function bannerAdClick()
    {
        if (isset($_GET["id"]) && !empty($_GET["id"]) && is_numeric($_GET["id"])) {
            $bannerAdData = $this->getBannerAdDetails($_GET["id"]);
            if (empty($bannerAdData)) {
                echo "Invalid link";
                exit;
            }
            $this->model->increaseBannerAdClicks($_GET["id"]);
            header("Location: " . $bannerAdData["ad_link"]);
            exit;
        }
        echo "Invalid link";
        exit;
    }
    public function totalBannerViews()
    {
        return $this->model->totalBannerViews()["total_views"];
    }
    public function totalUserBannerAds($username)
    {
        return $this->model->totalUserBannerAds($username);
    }
    public function userBannerAdsPagination($username)
    {
        return $this->pagination(30, $this->totalUserBannerAds($username), "small-banners.php");
    }
    public function userBannerAdsList($username)
    {
        $offset = 0;
        if (isset($_GET["page"]) && !empty($_GET["page"]) && is_numeric($_GET["page"])) {
            $total = $this->totalBannerAds();
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
        return $this->model->userBannerAdsList(30, $offset, $username);
    }
    public function updateUserAd($username, $id)
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["image_link"]) && isset($_POST["ad_link"]) && isset($_POST["csrf_token"])) {
            if ($this->arrayCheck($_POST)) {
                return ["success" => false, "message" => "Array not allowed here."];
            }
            $bannerAdDetails = $this->getBannerAdDetails($id);
            if (empty($bannerAdDetails) || $bannerAdDetails["username"] != $username) {
                return ["success" => false, "message" => "Couldn't find the ad."];
            }
            $membersController = new MembersController();
            if (empty($_POST["image_link"]) || empty($_POST["ad_link"]) || empty($_POST["csrf_token"])) {
                return ["success" => false, "message" => "All fields are required."];
            }
            if ($_POST["csrf_token"] != $membersController->getUserCSRFToken()) {
                return ["success" => false, "message" => "Invalid request."];
            }
            if (!filter_var($_POST["ad_link"], FILTER_VALIDATE_URL)) {
                return ["success" => false, "message" => "Invalid ad link. Please enter a valid URL"];
            }
            if (!filter_var($_POST["image_link"], FILTER_VALIDATE_URL) || !$this->is_url_image($_POST["image_link"])) {
                return ["success" => false, "message" => "Invalid banner link. Please enter a valid banner URL"];
            }
            $this->model->updateBannerAd(["image_link" => $_POST["image_link"], "ad_link" => $_POST["ad_link"]], $id);
            return ["success" => true, "message" => "Your ad  has been updated."];
        }
    }
    public function addUserAdCredits($username, $userInfo, $id)
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["credits"]) && isset($_POST["csrf_token"])) {
            if ($this->arrayCheck($_POST)) {
                return ["success" => false, "message" => "Array not allowed."];
            }
            $adDetails = $this->getbannerAdDetails($id);
            if (empty($adDetails) || $adDetails["username"] != $username) {
                return ["success" => false, "message" => "Couldn't find the ad."];
            }
            $membersController = new MembersController();
            if (empty($_POST["credits"]) || empty($_POST["csrf_token"])) {
                return ["success" => false, "message" => "Please enter credits amount you want to add."];
            }
            if ($membersController->getUserCSRFToken() != $_POST["csrf_token"]) {
                return ["success" => false, "message" => "Invalid request."];
            }
            if (!is_numeric($_POST["credits"]) || $_POST["credits"] < 1 || !is_int(intval($_POST["credits"]))) {
                return ["success" => false, "message" => "Invalid credits."];
            }
            if ($userInfo["banner_credits"] < $_POST["credits"]) {
                return ["success" => false, "message" => "You don't have enough credits."];
            }
            $this->model->increaseBannerAdCredits($id, $_POST["credits"]);
            $membersController->deductMemberBannerAdCredits($username, $_POST["credits"]);
            return ["success" => true, "message" => intval($_POST["credits"]) . " credits has been assign to the ad."];
        }
    }
    public function pauseUserAd($username, $id)
    {
        if (isset($_GET["pause"]) && isset($_GET["token"])) {
            $membersController = new MembersController();
            if (!empty($_GET["pause"]) && !empty($_GET["token"]) && is_numeric($_GET["pause"]) && 0 < $_GET["pause"] && $membersController->getUserCSRFToken() == $_GET["token"]) {
                $adDetails = $this->getbannerAdDetails($id);
                if ($username != $adDetails["username"]) {
                    return ["success" => false, "message" => "Couldn't find the ad."];
                }
                if ($adDetails["status"] == 3) {
                    return ["success" => false, "message" => "Your ad has been banned by admin. You can change the ad status."];
                }
                $this->model->updateBannerAd(["status" => 2], $id);
                return ["success" => true, "message" => "Your ad has been paused."];
            }
        }
    }
    public function activateUserAd($username, $id)
    {
        if (isset($_GET["activate"]) && isset($_GET["token"])) {
            $membersController = new MembersController();
            if (!empty($_GET["activate"]) && !empty($_GET["token"]) && is_numeric($_GET["activate"]) && 0 < $_GET["activate"] && $membersController->getUserCSRFToken() == $_GET["token"]) {
                $adDetails = $this->getbannerAdDetails($id);
                if ($username != $adDetails["username"]) {
                    return ["success" => false, "message" => "Couldn't find the ad."];
                }
                if ($adDetails["status"] == 3) {
                    return ["success" => false, "message" => "Your ad has been banned by admin. You can change the ad status."];
                }
                $this->model->updateBannerAd(["status" => 1], $id);
                return ["success" => true, "message" => "Your ad has been activated."];
            }
        }
    }
    public function deleteUserAd($username, $id)
    {
        if (isset($_GET["delete"]) && isset($_GET["token"])) {
            $membersController = new MembersController();
            if (!empty($_GET["delete"]) && !empty($_GET["token"]) && is_numeric($_GET["delete"]) && 0 < $_GET["delete"] && $membersController->getUserCSRFToken() == $_GET["token"]) {
                $adDetails = $this->getbannerAdDetails($id);
                if ($username != $adDetails["username"] || empty($adDetails)) {
                    return ["success" => false, "message" => "Couldn't find the banner ad."];
                }
                $membersController->increaseMemberBannerAdCredits($username, $adDetails["credits"]);
                $this->model->deleteAd($id);
                return ["success" => true, "message" => "Your ad has been deleted."];
            }
        }
    }
    public function removeBannerAdCredits($username, $id)
    {
        if (isset($_POST["remove_credits"]) && isset($_POST["csrf_token"])) {
            if ($this->arrayCheck($_POST)) {
                return ["success" => false, "message" => "Array not allowed."];
            }
            $membersController = new MembersController();
            if (empty($_POST["remove_credits"]) || empty($_POST["csrf_token"])) {
                return ["success" => false, "message" => "Please enter credits amount you want to remove."];
            }
            if ($_POST["csrf_token"] != $membersController->getUserCSRFToken()) {
                return ["success" => false, "message" => "Invalid request."];
            }
            if (!is_numeric($_POST["remove_credits"]) || $_POST["remove_credits"] < 1) {
                return ["success" => false, "message" => "Invalid credits."];
            }
            $bannerAdDetails = $this->getbannerAdDetails($id);
            if ($bannerAdDetails["username"] != $username) {
                return ["success" => false, "message" => "Couldn't find the ad."];
            }
            if ($bannerAdDetails["credits"] < $_POST["remove_credits"]) {
                return ["success" => false, "message" => "Not enough credits to remove."];
            }
            $remainCredits = $bannerAdDetails["credits"] - $_POST["remove_credits"];
            if ($remainCredits < 1) {
                $remainCredits = 0;
            }
            $this->model->updateBannerAd(["credits" => $remainCredits], $id);
            var_dump($remainCredits);
            if (0 < $remainCredits) {
                $membersController->increaseMemberBannerAdCredits($username, $_POST["remove_credits"]);
            }
            return ["success" => true, "message" => "Credits has been removed from the ad."];
        }
    }
    public function addUserBannerAd($username)
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["image_link"]) && isset($_POST["ad_link"]) && isset($_POST["csrf_token"])) {
            if ($this->arrayCheck($_POST)) {
                return ["success" => false, "message" => "Array not allowed here."];
            }
            $membersController = new MembersController();
            if (empty($_POST["image_link"]) || empty($_POST["ad_link"]) || empty($_POST["csrf_token"])) {
                return ["success" => false, "message" => "All fields are required."];
            }
            if ($_POST["csrf_token"] != $membersController->getUserCSRFToken()) {
                return ["success" => false, "message" => "Invalid request."];
            }
            if (!filter_var($_POST["ad_link"], FILTER_VALIDATE_URL)) {
                return ["success" => false, "message" => "Invalid website link."];
            }
            if (!filter_var($_POST["image_link"], FILTER_VALIDATE_URL) || !$this->is_url_image($_POST["image_link"])) {
                return ["success" => false, "message" => "Invalid banner link."];
            }
            $this->model->addBannerAd(["username" => $username, "image_link" => $_POST["image_link"], "ad_link" => $_POST["ad_link"], "credits" => 0, "total_views" => 0, "total_clicks" => 0, "creation_time" => time(), "status" => 1]);
            return ["success" => true, "message" => "Your banenr has been added and activated. Now you can assign credits."];
        }
    }
}

?>