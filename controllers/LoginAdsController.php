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
class LoginAdsController extends Controller
{
    private $model;
    public function __construct()
    {
        $this->model = new LoginAdsModel();
    }
    public function LoginAdsList()
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
    public function loginAdsPgination()
    {
        return $this->pagination(30, $this->totalLoginAds(), "web-login-ads.php");
    }
    public function totalLoginAds()
    {
        return $this->model->totalLoginAds();
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
        return $this->model->getLoginAdDetails($id);
    }
    public function getLoginAd()
    {
        $loginData = $this->model->getLoginAd();
        return $loginData;
        if (!empty($loginData)) {
            echo "<div class='loginFrameWeb-468-60'>
                    <a href=\"login-click.php?id=" . $loginData["id"] . "\" target=\"_blank\">
                        <img src=\"" . $loginData["image_link"] . "\" height=\"60\" width=\"468\" />
                    </a>
                    <a class=\"adByBottom\" href=\"https://i-lovetraffic.online/\" target=\"_blank\">
                        I-Love Traffic
                    </a>
                </div>";
        } else {
            echo "<a href=\"index.php\"><img src=\"images/468x60.jpg\" height=\"60\" width=\"468\" /></a>";
        }
    }
    public function getRandomLoginAdDetails()
    {
        return $this->model->getLoginAd();
    }
    public function loginAdClick()
    {
        if (isset($_GET["id"]) && !empty($_GET["id"]) && is_numeric($_GET["id"])) {
            $loginAdData = $this->getLoginAdDetails($_GET["id"]);
            if (empty($loginAdData)) {
                echo "Invalid link";
                exit;
            }
            $this->model->increaseLoginAdClicks($_GET["id"]);
            header("Location: " . $loginAdData["ad_link"]);
            exit;
        }
        echo "Invalid link";
        exit;
    }
    public function increaseLoginAdClicks($id)
    {
        $this->model->increaseLoginAdClicks($id);
        $this->model->deductLoginAdCredits(1, $id);
    }
    public function totalLoginViews()
    {
        return $this->model->totalLoginViews()["total_views"];
    }
    public function totalUserLoginAds($username)
    {
        return $this->model->totalUserLoginAds($username);
    }
    public function userLoginAdsPagination($username)
    {
        return $this->pagination(30, $this->totalUserLoginAds($username), "web-logins.php");
    }
    public function userLoginAdsList($username)
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
        return $this->model->userLoginAdsList(30, $offset, $username);
    }
    public function updateUserAd($username, $id)
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["image_link"]) && isset($_POST["ad_link"]) && isset($_POST["csrf_token"])) {
            if ($this->arrayCheck($_POST)) {
                return ["success" => false, "message" => "Array not allowed here."];
            }
            $loginAdDetails = $this->getLoginAdDetails($id);
            if (empty($loginAdDetails) || $loginAdDetails["username"] != $username) {
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
                return ["success" => false, "message" => "Invalid login link. Please enter a valid login URL"];
            }
            $this->model->updateLoginAd(["image_link" => $_POST["image_link"], "ad_link" => $_POST["ad_link"]], $id);
            return ["success" => true, "message" => "Your ad  has been updated."];
        }
    }
    public function addUserAdCredits($username, $userInfo, $id)
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["credits"]) && isset($_POST["csrf_token"])) {
            if ($this->arrayCheck($_POST)) {
                return ["success" => false, "message" => "Array not allowed."];
            }
            $adDetails = $this->getloginAdDetails($id);
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
            if ($userInfo["login_ad_credits"] < $_POST["credits"]) {
                return ["success" => false, "message" => "You don't have enough credits."];
            }
            $this->model->increaseLoginAdCredits($id, $_POST["credits"]);
            $membersController->deductMemberLoginAdCredits($username, $_POST["credits"]);
            return ["success" => true, "message" => intval($_POST["credits"]) . " credits has been assign to the ad."];
        }
    }
    public function pauseUserAd($username, $id)
    {
        if (isset($_GET["pause"]) && isset($_GET["token"])) {
            $membersController = new MembersController();
            if (!empty($_GET["pause"]) && !empty($_GET["token"]) && is_numeric($_GET["pause"]) && 0 < $_GET["pause"] && $membersController->getUserCSRFToken() == $_GET["token"]) {
                $adDetails = $this->getloginAdDetails($id);
                if ($username != $adDetails["username"]) {
                    return ["success" => false, "message" => "Couldn't find the ad."];
                }
                if ($adDetails["status"] == 3) {
                    return ["success" => false, "message" => "Your ad has been banned by admin. You can change the ad status."];
                }
                $this->model->updateLoginAd(["status" => 2], $id);
                return ["success" => true, "message" => "Your ad has been paused."];
            }
        }
    }
    public function activateUserAd($username, $id)
    {
        if (isset($_GET["activate"]) && isset($_GET["token"])) {
            $membersController = new MembersController();
            if (!empty($_GET["activate"]) && !empty($_GET["token"]) && is_numeric($_GET["activate"]) && 0 < $_GET["activate"] && $membersController->getUserCSRFToken() == $_GET["token"]) {
                $adDetails = $this->getloginAdDetails($id);
                if ($username != $adDetails["username"]) {
                    return ["success" => false, "message" => "Couldn't find the ad."];
                }
                if ($adDetails["status"] == 3) {
                    return ["success" => false, "message" => "Your ad has been banned by admin. You can change the ad status."];
                }
                $this->model->updateLoginAd(["status" => 1], $id);
                return ["success" => true, "message" => "Your ad has been activated."];
            }
        }
    }
    public function deleteUserAd($username, $id)
    {
        if (isset($_GET["delete"]) && isset($_GET["token"])) {
            $membersController = new MembersController();
            if (!empty($_GET["delete"]) && !empty($_GET["token"]) && is_numeric($_GET["delete"]) && 0 < $_GET["delete"] && $membersController->getUserCSRFToken() == $_GET["token"]) {
                $adDetails = $this->getloginAdDetails($id);
                if ($username != $adDetails["username"] || empty($adDetails)) {
                    return ["success" => false, "message" => "Couldn't find the login ad."];
                }
                $membersController->increaseMemberLoginAdCredits($username, $adDetails["credits"]);
                $this->model->deleteAd($id);
                return ["success" => true, "message" => "Your ad has been deleted."];
            }
        }
    }
    public function removeLoginAdCredits($username, $id)
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
            $loginAdDetails = $this->getloginAdDetails($id);
            if ($loginAdDetails["username"] != $username) {
                return ["success" => false, "message" => "Couldn't find the ad."];
            }
            if ($loginAdDetails["credits"] < $_POST["remove_credits"]) {
                return ["success" => false, "message" => "Not enough credits to remove."];
            }
            $remainCredits = $loginAdDetails["credits"] - $_POST["remove_credits"];
            if ($remainCredits < 1) {
                $remainCredits = 0;
            }
            $this->model->updateLoginAd(["credits" => $remainCredits], $id);
            var_dump($remainCredits);
            if (0 < $remainCredits) {
                $membersController->increaseMemberLoginAdCredits($username, $_POST["remove_credits"]);
            }
            return ["success" => true, "message" => "Credits has been removed from the ad."];
        }
    }
    public function addUserLoginAd($username)
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["image_link"]) && isset($_POST["ad_link"]) && isset($_POST["csrf_token"])) {
            if ($this->arrayCheck($_POST)) {
                return ["success" => false, "message" => "Array not allowed here."];
            }
            $membersController = new MembersController();
            if (empty($_POST["ad_link"]) || empty($_POST["csrf_token"])) {
            // if (empty($_POST["image_link"]) || empty($_POST["ad_link"]) || empty($_POST["csrf_token"])) {
                return ["success" => false, "message" => "All fields are required."];
            }
            if ($_POST["csrf_token"] != $membersController->getUserCSRFToken()) {
                return ["success" => false, "message" => "Invalid request."];
            }
            if (!filter_var($_POST["ad_link"], FILTER_VALIDATE_URL)) {
                return ["success" => false, "message" => "Invalid website link."];
            }
            // if (!filter_var($_POST["image_link"], FILTER_VALIDATE_URL) || !$this->is_url_image($_POST["image_link"])) {
            //     return ["success" => false, "message" => "Invalid login link."];
            // }
            $this->model->addLoginAd(["username" => $username, "ad_link" => $_POST["ad_link"], "credits" => 0, "total_views" => 0, "total_clicks" => 0, "creation_time" => time(), "status" => 1]);
            // $this->model->addLoginAd(["username" => $username, "image_link" => $_POST["image_link"], "ad_link" => $_POST["ad_link"], "credits" => 0, "total_views" => 0, "total_clicks" => 0, "creation_time" => time(), "status" => 1]);
            return ["success" => true, "message" => "Your banenr has been added and activated. Now you can assign credits."];
        }
    }
}

?>