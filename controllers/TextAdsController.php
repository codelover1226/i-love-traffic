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
class TextAdsController extends Controller
{
    private $model;
    public function __construct()
    {
        $this->model = new TextAdsModel();
    }
    public function textAdsList()
    {
        $offset = 0;
        if (isset($_GET["page"]) && !empty($_GET["page"]) && is_numeric($_GET["page"])) {
            $total = $this->totalTextAds();
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
        return $this->model->textAdsList(30, $offset);
    }
    public function textAdsPgination()
    {
        return $this->pagination(30, $this->totalTextAds(), "text-ads.php");
    }
    public function totalTextAds()
    {
        return $this->model->totalTextAds();
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
                return ["success" => false, "message" => "Invalid text ad."];
            }
            $this->model->updateTextAd(["status" => 1], $_GET["activate"]);
            return ["success" => true, "message" => "Text ad has been activated."];
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
                return ["success" => false, "message" => "Invalid text ad."];
            }
            $this->model->updateTextAd(["status" => 2], $_GET["pause"]);
            return ["success" => true, "message" => "Text ad has been paused."];
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
                return ["success" => false, "message" => "Invalid text ad."];
            }
            $this->model->updateTextAd(["status" => 3], $_GET["ban"]);
            return ["success" => true, "message" => "Text ad has been banned."];
        }
    }
    public function getTextAdDetails($id)
    {
        return $this->model->getTextAdDetails($id);
    }
    public function getTextAd()
    {
        $textAdData = $this->model->getTextAd();
        if (!empty($textAdData)) {
            echo "<a class=\"text-ad-title\" href=\"text-ad-click.php?id=" . $textAdData["id"] . "\" target=\"_blank\">" . $textAdData["ad_title"] . "</a>";
            echo "<br><p class=\"text-ad-description\">" . $textAdData["ad_text"] . "</p>";
        } else {
            echo "<a class=\"text-ad-title\" href=\"index.php\">i-LoveTraffic</a><br><p class=\"text-ad-description\">This is a test ad. It will be removed automatically.</p>";
        }
    }
    public function textAdClick()
    {
        if (isset($_GET["id"]) && !empty($_GET["id"]) && is_numeric($_GET["id"])) {
            $adData = $this->getTextAdDetails($_GET["id"]);
            if (empty($adData)) {
                echo "Invalid link";
                exit;
            }
            $this->model->increaseTextAdClicks($_GET["id"]);
            header("Location: " . $adData["ad_link"]);
            exit;
        }
        echo "Invalid link";
        exit;
    }
    public function totalUserTextAds($username)
    {
        return $this->model->totalUserTextAds($username);
    }
    public function userTextAdsList($username)
    {
        $offset = 0;
        if (isset($_GET["page"]) && !empty($_GET["page"]) && is_numeric($_GET["page"])) {
            $total = $this->totalUserTextAds($username);
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
        return $this->model->userTextAdsList(30, $offset, $username);
    }
    public function userTextAdsPagination($username)
    {
        return $this->pagination(30, $this->totalUserTextAds($username), "text-ads.php");
    }
    public function addUserAd($username)
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["ad_title"]) && isset($_POST["ad_text"]) && isset($_POST["ad_link"]) && isset($_POST["csrf_token"])) {
            if ($this->arrayCheck($_POST)) {
                return ["success" => false, "message" => "Array not allowed here."];
            }
            $membersController = new MembersController();
            if (empty($_POST["ad_title"]) || empty($_POST["ad_text"]) || empty($_POST["ad_link"]) || empty($_POST["csrf_token"])) {
                return ["success" => false, "message" => "All fields are required."];
            }
            if ($_POST["csrf_token"] != $membersController->getUserCSRFToken()) {
                return ["success" => false, "message" => "Invalid request."];
            }
            if (30 < strlen($_POST["ad_title"])) {
                return ["success" => false, "message" => "Ad title is too long. You can add maximum 30 characters."];
            }
            if (40 < strlen($_POST["ad_text"])) {
                return ["success" => false, "message" => "Ad text is too long. You can add maximum 40 characters."];
            }
            if (!filter_var($_POST["ad_link"], FILTER_VALIDATE_URL)) {
                return ["success" => false, "message" => "Invalid ad link. Please enter a valid URL"];
            }
            $this->model->addTextAd(["username" => $username, "ad_title" => $_POST["ad_title"], "ad_text" => $_POST["ad_text"], "ad_link" => $_POST["ad_link"], "credits" => 0, "total_views" => 0, "total_clicks" => 0, "creation_time" => time(), "status" => 1]);
            return ["success" => true, "message" => "Your ad  has been added and activated."];
        }
    }
    public function updateUserAd($username, $id)
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["ad_title"]) && isset($_POST["ad_text"]) && isset($_POST["ad_link"]) && isset($_POST["csrf_token"])) {
            if ($this->arrayCheck($_POST)) {
                return ["success" => false, "message" => "Array not allowed here."];
            }
            $textAdDetails = $this->getTextAdDetails($id);
            if (empty($textAdDetails) || $textAdDetails["username"] != $username) {
                return ["success" => false, "message" => "Couldn't find the ad."];
            }
            $membersController = new MembersController();
            if (empty($_POST["ad_title"]) || empty($_POST["ad_text"]) || empty($_POST["ad_link"]) || empty($_POST["csrf_token"])) {
                return ["success" => false, "message" => "All fields are required."];
            }
            if ($_POST["csrf_token"] != $membersController->getUserCSRFToken()) {
                return ["success" => false, "message" => "Invalid request."];
            }
            if (30 < strlen($_POST["ad_title"])) {
                return ["success" => false, "message" => "Ad title is too long. You can add maximum 30 characters."];
            }
            if (40 < strlen($_POST["ad_text"])) {
                return ["success" => false, "message" => "Ad text is too long. You can add maximum 40 characters."];
            }
            if (!filter_var($_POST["ad_link"], FILTER_VALIDATE_URL)) {
                return ["success" => false, "message" => "Invalid ad link. Please enter a valid URL"];
            }
            $this->model->updateTextAd(["ad_title" => $_POST["ad_title"], "ad_text" => $_POST["ad_text"], "ad_link" => $_POST["ad_link"]], $id);
            return ["success" => true, "message" => "Your ad  has been updated."];
        }
    }
    public function addUserAdCredits($username, $userInfo, $id)
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["credits"]) && isset($_POST["csrf_token"])) {
            if ($this->arrayCheck($_POST)) {
                return ["success" => false, "message" => "Array not allowed."];
            }
            $adDetails = $this->getTextAdDetails($id);
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
            if ($userInfo["text_ad_credits"] < $_POST["credits"]) {
                return ["success" => false, "message" => "You don't have enough credits."];
            }
            $this->model->increaseTextAdCredits($id, $_POST["credits"]);
            $membersController->deductMemberTextAdCredits($username, $_POST["credits"]);
            return ["success" => true, "message" => intval($_POST["credits"]) . " credits has been assign to the ad."];
        }
    }
    public function pauseUserAd($username, $id)
    {
        if (isset($_GET["pause"]) && isset($_GET["token"])) {
            $membersController = new MembersController();
            if (!empty($_GET["pause"]) && !empty($_GET["token"]) && is_numeric($_GET["pause"]) && 0 < $_GET["pause"] && $membersController->getUserCSRFToken() == $_GET["token"]) {
                $adDetails = $this->getTextAdDetails($id);
                if ($username != $adDetails["username"]) {
                    return ["success" => false, "message" => "Couldn't find the ad."];
                }
                if ($adDetails["status"] == 3) {
                    return ["success" => false, "message" => "Your ad has been banned by admin. You can change the ad status."];
                }
                $this->model->updateTextAd(["status" => 2], $id);
                return ["success" => true, "message" => "Your ad has been paused."];
            }
        }
    }
    public function activateUserAd($username, $id)
    {
        if (isset($_GET["activate"]) && isset($_GET["token"])) {
            $membersController = new MembersController();
            if (!empty($_GET["activate"]) && !empty($_GET["token"]) && is_numeric($_GET["activate"]) && 0 < $_GET["activate"] && $membersController->getUserCSRFToken() == $_GET["token"]) {
                $adDetails = $this->getTextAdDetails($id);
                if ($username != $adDetails["username"]) {
                    return ["success" => false, "message" => "Couldn't find the ad."];
                }
                if ($adDetails["status"] == 3) {
                    return ["success" => false, "message" => "Your ad has been banned by admin. You can change the ad status."];
                }
                $this->model->updateTextAd(["status" => 1], $id);
                return ["success" => true, "message" => "Your ad has been activated."];
            }
        }
    }
    public function deleteUserAd($username, $id)
    {
        if (isset($_GET["delete"]) && isset($_GET["token"])) {
            $membersController = new MembersController();
            if (!empty($_GET["delete"]) && !empty($_GET["token"]) && is_numeric($_GET["delete"]) && 0 < $_GET["delete"] && $membersController->getUserCSRFToken() == $_GET["token"]) {
                $adDetails = $this->getTextAdDetails($id);
                if ($username != $adDetails["username"]) {
                    return ["success" => false, "message" => "Couldn't find the ad."];
                }
                $membersController->increaseMemberTextAdCredits($username, $adDetails["credits"]);
                $this->model->deleteAd($id);
                return ["success" => true, "message" => "Your ad has been deleted."];
            }
        }
    }
    public function removeUserTextAdCredits($username, $id)
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
            $textAdDetails = $this->getTextAdDetails($id);
            if ($textAdDetails["username"] != $username) {
                return ["success" => false, "message" => "Couldn't find the ad."];
            }
            if ($textAdDetails["credits"] < $_POST["remove_credits"]) {
                return ["success" => false, "message" => "Not enough credits to remove."];
            }
            $remainCredits = $textAdDetails["credits"] - $_POST["remove_credits"];
            if ($remainCredits < 1) {
                $remainCredits = 0;
            }
            $this->model->updateTextAd(["credits" => $remainCredits], $id);
            var_dump($remainCredits);
            if (0 < $remainCredits) {
                $membersController->increaseMemberTextAdCredits($username, $_POST["remove_credits"]);
            }
            return ["success" => true, "message" => "Credits has been removed from the ad."];
        }
    }
    public function totalTextAdViews()
    {
        return $this->model->totalTextAdViews()["total_views"];
    }
}

?>